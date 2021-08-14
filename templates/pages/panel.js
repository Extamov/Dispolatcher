let rtc_connection;
let candidates;
let localStream;
let remoteStream;
let ids_order = [];

function ajax(url, method, body) {
	return new Promise((resolve, reject) => {
		let xhttp = new XMLHttpRequest();

		xhttp.onload = () => {
			resolve(xhttp.responseText);
		};

		xhttp.onerror = () => {
			setTimeout(() => {
				xhttp.open(method, url, true);
				xhttp.send(body);
			}, 2000);
		};

		if(typeof body === "object"){
			let newFormData = new FormData();

			for(let key of Object.keys(body)){
				newFormData.append(key, body[key]);
			}
			body = newFormData;
		}

		xhttp.open(method, url, true);

		xhttp.send(body);
	});
}

const navs = ["call", "dispatch", "admin", "settings"];
let hangup_sound = new Audio("static/hangup.opus");

function nav_responsive(responsive = null) {
	let main = document.querySelector("main");
	let header = document.querySelector("header");
	let nav = document.querySelector("nav");
	if (responsive === null) {
		responsive = !nav.classList.contains("responsive_nav");
	}
	if (!responsive) {
		main.classList.remove("responsive_main");
		header.classList.remove("responsive_header");
		nav.classList.remove("responsive_nav");
	} else {
		main.classList.add("responsive_main");
		header.classList.add("responsive_header");
		nav.classList.add("responsive_nav");
	}
}
document.querySelector("#change_password_form").onsubmit = event => {
	let password = document.querySelector("input[name=new_pass]").value;
	let password_confirm = document.querySelector("input[name=new_pass_confirm]").value;
	if (password != password_confirm) {
		alert("Password confim must be the same as Password");
		return false;
	}
};

function update_nav(event = null) {
	if (window.location.hash) {
		let hash = window.location.hash.substring(1);
		if (navs.includes(hash)) {
			navs.forEach(nav => {
				document.querySelector("#" + nav + "_container").style.display = "none";
			});
			if (!document.querySelector("#" + hash + "_nav") || document.querySelector("#" + hash + "_nav").style.display == "none") {
				window.location.href = "#dispatch";
				return;
			}

			document.querySelector("#" + hash + "_container").style.display = "";
		} else {
			window.location.hash = "#dispatch";
		}
	} else {
		window.location.hash = "#dispatch";
	}
	nav_responsive(false);
}

async function check_calls_thread() {
	let calls = JSON.parse(await ajax("api/receive_calls", "GET"));
	let s_table = document.querySelector("#dispatch_table");
	let table_html = `
		<div class="s-table_row s-table_header" style="padding: 15px 0;">
			<div class="s-table_item">Dispatches</div>
			<div class="s-table_footer">
			</div>
		</div>
	`;
	let new_ids_order = [];

	calls.forEach(call => {
		let type = "";
		if (call["type"] == "POLICE") {
			type = "Police";
		} else if (call["type"] == "AMBULANCE") {
			type = "Ambulance";
		} else {
			type = "Fire Service";
		}

		let location_column = "";

		if (call["location"]) {
			location_column = `
				<div class="s-table_item">
					<div>Location</div>
					<div>${call["location"]}</div>
				</div>
			`;
		}

		table_html += `
		<div class="s-table_row">
			<div class="s-table_item">
				<div>Type</div>
				<div>${type}</div>
			</div>
			<div class="s-table_item">
				<div>IP</div>
				<div>${call["caller_ip"]}</div>
			</div>
			<div class="s-table_item">
				<div>Time</div>
				<div>${call["date"]}</div>
			</div>${location_column}
			<div class="s-table_footer">
				<a class="positive_button" href="#" onclick="answerCall('${call["id"]}');">Accept</a>
				<a class="negative_button" href="#" onclick="denyCall('${call["id"]}');">Deny</a>
			</div>
		</div>
		`;
		new_ids_order[call["id"]] = JSON.parse(call["offer"]);
	});
	if (
		s_table.innerHTML
			.replaceAll("\n", "")
			.replaceAll("\r", "")
			.replaceAll(" ", "")
			.replaceAll("\t", "")
			.replaceAll("'", '"')
			.replaceAll("=", "")
			.replaceAll('"', "")
			.toLowerCase() !=
		table_html.replaceAll("\n", "").replaceAll("\r", "").replaceAll(" ", "").replaceAll("\t", "").replaceAll("'", '"').replaceAll("=", "").replaceAll('"', "").toLowerCase()
	) {
		s_table.innerHTML = table_html;
		ids_order = new_ids_order;
	}
	setTimeout(() => {
		check_calls_thread();
	}, 1000);
}

async function wait_for_candidates() {
	return new Promise(async (resolve, reject) => {
		let response = await ajax("api/receive_candidates", "POST", {});

		if (response === "waiting") {
			setTimeout(() => {
				resolve(wait_for_candidates());
			}, 400);
		} else {
			resolve(response);
		}
	});
}

function createMediaStream() {
	return new Promise((resolve, reject) => {
		navigator.mediaDevices
			.getUserMedia({
				audio: {
					channelCount: 1,
					autoGainControl: true,
					noiseSuppression: false,
					echoCancellation: false
				}
			})
			.then(stream => {
				resolve(stream);
			})
			.catch(error => {
				reject(error);
			});
	});
}

function denyCall(id) {
	if (rtc_connection) {
		alert("You can't deny a call while in a call.");
		return;
	}

	ajax("api/close_call.php", "POST", {
		id: id
	});
}

async function answerCall(id) {
	if (rtc_connection) {
		alert("You can't answer a call while in a call.");
		return;
	}

	rtc_connection = new RTCPeerConnection(configuration);

	try {
		localStream = await createMediaStream();
		localStream.getTracks().forEach(track => rtc_connection.addTrack(track, localStream));
	} catch {
		alert("You must enable microphone access in order to call.");
		return;
	}

	let call_header = document.querySelector("#inside_header");

	call_header.innerHtml = "Connecting...";
	document.querySelector("#call_nav").style.display = "";
	window.location.hash = "#call";

	candidates = [];

	remoteStream = new MediaStream();
	document.querySelector("audio").srcObject = remoteStream;

	rtc_connection.addEventListener("track", async event => {
		remoteStream.addTrack(event.track, remoteStream);
	});

	rtc_connection.onicecandidate = async event => {
		candidates.push(event.candidate);
	};

	try {
		rtc_connection.setRemoteDescription(ids_order[id]);
	} catch {
		disconnectCall("Got invalid data");
		return;
	}

	let rtc_answer = await rtc_connection.createAnswer();

	await rtc_connection.setLocalDescription(rtc_answer);

	let response = await ajax("api/accept_call.php", "POST", {
		id: id,
		answer: JSON.stringify(rtc_answer)
	});

	if (response != "true") {
		disconnectCall("Failed to connect");
		return;
	}

	call_header.innerHTML = "Exchanging";

	let receivecandidates_response = await wait_for_candidates();

	if (receivecandidates_response == "false") {
		disconnectCall("Exchange failed");
		return;
	}

	try {
		let caller_candidates = JSON.parse(receivecandidates_response);

		caller_candidates.forEach(candidate => {
			rtc_connection.addIceCandidate(candidate);
		});
	} catch {
		disconnectCall("Got invalid data");
		return;
	}

	async function send_candidates() {
		if (
			candidates.length > 0 &&
			(candidates[candidates.length - 1] === null ||
				rtc_connection.iceConnectionState == "completed" ||
				rtc_connection.iceConnectionState == "connected" ||
				rtc_connection.iceGatheringState == "complete")
		) {
			candidates.splice(candidates.length - 1, 1);
			let sendcandidates_response = await ajax("api/send_candidates", "POST", {
				candidates: JSON.stringify(candidates)
			});

			if (sendcandidates_response == "true") {
				function checkStatus(disconnect_timeout) {
					if (rtc_connection.iceConnectionState == "connected" && !isConnectedStyle()) {
						call_header.innerHTML = "Emergency call";
						connectedCallStyle();
					} else if (disconnect_timeout >= 20) {
						disconnectCall("Connection lost");
					} else if (rtc_connection.iceConnectionState == "disconnected" || rtc_connection.iceConnectionState == "failed") {
						call_header.innerHTML = "Reconnecting";
						disconnect_timeout += 1;
					} else if (rtc_connection.iceConnectionState == "connecting" || rtc_connection.iceConnectionState == "connected") {
						call_header.innerHTML = "Emergency call";
						disconnect_timeout = 0;
					}

					if (rtc_connection && rtc_connection.iceConnectionState != "closed") {
						setTimeout(() => {
							checkStatus(disconnect_timeout);
						}, 500);
					}
				}

				call_header.innerHTML = "Connecting";
				checkStatus(0);
			} else {
				disconnectCall("Exchanging failed");
				return;
			}
		} else {
			setTimeout(() => {
				send_candidates();
			}, 1000);
		}
	}

	send_candidates();
}

document.querySelector("#hangup_button").onclick = event => {
	disconnectCall();
};

document.querySelector("#mute_button").onclick = event => {
	if (localStream) {
		if (localStream.getTracks()[0].enabled) {
			localStream.getTracks()[0].enabled = false;
			document.querySelector("#mute_button").classList.add("muted");
			document.querySelector("#mute_button").classList.remove("unmuted");
		} else {
			localStream.getTracks()[0].enabled = true;
			document.querySelector("#mute_button").classList.remove("muted");
			document.querySelector("#mute_button").classList.add("unmuted");
		}
	}
};

function connectedCallStyle() {
	document.querySelector("body").style.background = "linear-gradient(45deg, rgba(234,155,73,1) 0%, rgba(32,124,229,1) 100%)";
}

function isConnectedStyle() {
	return document.querySelector("body").style.background == "linear-gradient(45deg, rgba(234,155,73,1) 0%, rgba(32,124,229,1) 100%)";
}

function disconnectCall(message = "Disconnected") {
	let call_header = document.querySelector("#inside_header");

	if (call_header.innerHTML != "Disconnected") {
		call_header.innerHTML = message;
		hangup_sound.play();
	}

	ajax("api/close_call.php", "POST", {});
	setTimeout(() => {
		call_header.innerHTML = "Emergency Services";
		document.querySelector("#mute_button").classList.remove("muted");
		document.querySelector("#mute_button").classList.add("unmuted");
		document.querySelector("#call_nav").style.display = "none";
		document.querySelector("body").style.background = "linear-gradient(to bottom,rgba(42, 94, 232, 0.75) 0%,rgba(42, 94, 232, 1) 60%)";
		window.location.hash = "#dispatch";
	}, 2000);
	if (rtc_connection) {
		rtc_connection.close();
		rtc_connection = null;
	}
	if (localStream) {
		localStream.getTracks().forEach(function (track) {
			track.stop();
		});
	}
	if (remoteStream) {
		remoteStream.getTracks().forEach(function (track) {
			track.stop();
		});
	}
}

window.onhashchange = update_nav;
check_calls_thread();
update_nav();