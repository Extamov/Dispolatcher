let rtc_connection;
let candidates;
let localStream;
let remoteStream;

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

async function wait_for_answer() {
	return new Promise(async (resolve, reject) => {
		let response = await ajax("api/get_answer", "POST", {});

		if (response === "waiting") {
			setTimeout(() => {
				resolve(wait_for_answer());
			}, 400);
		} else {
			resolve(response);
		}
	});
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

let dialing_sound = new Audio("static/calling.webm");
dialing_sound.loop = true;
let hangup_sound = new Audio("static/hangup.opus");

document.querySelector("#call_button").onclick = async event => {
	if (document.querySelector("#call_button")) {
		try {
			document.querySelector("#call_button").id = "hangup_button";
		} catch {}

		let call_header = document.querySelector("#inside_header");
		let call_type = document.querySelector("#select_type").value;

		rtc_connection = new RTCPeerConnection(configuration);

		try {
			localStream = await createMediaStream();
			localStream.getTracks().forEach(track => rtc_connection.addTrack(track, localStream));
		} catch {
			alert("You must enable microphone access in order to call.");
			try {
				document.querySelector("#hangup_button").id = "call_button";
			} catch {}
			return;
		}

		call_header.innerHTML = "Registering...";

		candidates = [];

		remoteStream = new MediaStream();
		document.querySelector("audio").srcObject = remoteStream;

		rtc_connection.addEventListener("track", async event => {
			remoteStream.addTrack(event.track, remoteStream);
		});

		rtc_connection.onicecandidate = async event => {
			candidates.push(event.candidate);
		};

		let rtc_offer = await rtc_connection.createOffer();

		await rtc_connection.setLocalDescription(rtc_offer);

		let registercall_response = await ajax("api/register_call", "POST", {
			type: call_type,
			rtc_offer: JSON.stringify(rtc_offer)
		});

		if (registercall_response != "true") {
			disconnectCall("Failed to register");
			return;
		}

		call_header.innerHTML = "Calling...";
		dialing_sound.currentTime = 0;
		dialing_sound.play();

		rtc_answer = await wait_for_answer();

		if (rtc_answer == "false") {
			disconnectCall("Call rejected");
			return;
		} else if (rtc_answer == "timeout") {
			disconnectCall("Timed out");
			return;
		}

		await rtc_connection.setRemoteDescription(JSON.parse(rtc_answer));

		document.querySelector("#select_type").parentElement.style.display = "none";
		document.querySelector("#mute_button").parentElement.style.display = "";

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
					let receivecandidates_response = await wait_for_candidates();

					if (receivecandidates_response == "false") {
						disconnectCall("Exchange failed");
					} else if (receivecandidates_response == "timeout") {
						disconnectCall("Timed out");
					} else {
						received_candidates = JSON.parse(receivecandidates_response);
						received_candidates.forEach(candidate => {
							rtc_connection.addIceCandidate(candidate);
						});

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
					}
				} else {
					disconnectCall("Exchange failed");
				}
			} else {
				setTimeout(() => {
					send_candidates();
				}, 1000);
			}
		}

		dialing_sound.pause();
		send_candidates();

		call_header.innerHTML = "Exchanging";
	} else {
		disconnectCall();
	}

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
		document.querySelector("#inside_header").classList.add("align_flex_end");
		document.querySelector("#middle_block").classList.add("align_flex_end");
		document.querySelector("#select_type").parentElement.style.display = "none";
		document.querySelector("#police_logo").style.display = "";
		document.querySelector("#mute_button").parentElement.style.display = "";
		document.querySelector("body").style.background = "linear-gradient(45deg, rgba(234,155,73,1) 0%, rgba(32,124,229,1) 100%)";
		try {
			document.querySelector("#call_button").id = "hangup_button";
		} catch {}
		document.querySelector("#hangup_button").disabled = false;
	}

	function isConnectedStyle() {
		return document.querySelector("#police_logo").style.display !== "none";
	}

	function disconnectCall(message = "Disconnected") {
		let call_header = document.querySelector("#inside_header");

		if (call_header.innerHTML != "Disconnected") {
			call_header.innerHTML = message;
			hangup_sound.play();
		}

		dialing_sound.pause();
		ajax("api/close_call.php", "POST", {});
		setTimeout(() => {
			call_header.innerHTML = "Emergency Services";
			document.querySelector("#inside_header").classList.remove("align_flex_end");
			document.querySelector("#middle_block").classList.remove("align_flex_end");
			document.querySelector("#select_type").parentElement.style.display = "";
			document.querySelector("#police_logo").style.display = "none";
			document.querySelector("#mute_button").parentElement.style.display = "none";
			document.querySelector("body").style.background = "linear-gradient(45deg, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%)";
			try {
				document.querySelector("#hangup_button").id = "call_button";
			} catch {}
			document.querySelector("#call_button").disabled = false;
			document.querySelector("#mute_button").classList.remove("muted");
			document.querySelector("#mute_button").classList.add("unmuted");
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
};