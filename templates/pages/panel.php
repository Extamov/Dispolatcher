<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<script src="static/fontawesome.js"></script>
		<script src="static/jquery.js"></script>
		<style><?php	require_once(__DIR__."/panel.css");	?></style>
	</head>
	<body>
		<header>
			<nav>
				<div id="logo_nav">
					<a href="#dispatch">
						<img src="static/logo.svg" width="30">
						<span id="logo_text">police</span>
					</a>
				</div>
				<div id="call_nav" style="display:none;"><a href="#call">Call</a></div>
				<div><a href="#dispatch">Dispatch</a></div>
				<?php	if(isset($site_params["level"]) && $site_params["level"] >= 2){?>
					<div><a href="#admin">Admin</a></div>
				<?php	}	?>
				<div><a href="#settings">Settings</a></div>
				<div id="logout_nav"><a id="logout_button" href="logout">Logout</a></div>
				<div id="nav_button_block"><a id="nav_button" href="javascript:void(0)" onclick="nav_responsive();"><i class="fa fa-bars"></i></a></div>
			</nav>
		</header>
		<main>
			<div id="dispatch_container" class="main_container" style="display:none;">
				<div class="s-table" id="dispatch_table">
					<div class="s-table_row">
						<div class="s-table_item">
							<div>Type</div>
							<div>Loading...</div>
						</div>
						<div class="s-table_item">
							<div>IP</div>
							<div>Loading...</div>
						</div>
						<div class="s-table_item">
							<div>Location</div>
							<div>Loading...</div>
						</div>
						<div class="s-table_item">
							<div>Time</div>
							<div>Loading...</div>
						</div>
						<div class="s-table_footer">
							<a class="positive_button" href="#">Loading...</a>
							<a class="negative_button" href="#">Loading...</a>
							<a class="neutral_button" href="#">Loading...</a>
						</div>
					</div>
				</div>
			</div>
			<div id="settings_container" class="main_container" style="display:none;">
				<div class="s-table">
					<form method="POST" class="s-table_row" style="max-width: 600px;">
						<div class="s-table_item">
							<div style="border:0;border:0;">Change password</div>
						</div>
						<div class="s-table_item" style="border-top: 1px solid;">
							<div style="border:0;">Previous password</div>
							<div style="flex: 0.6;border:0;"><input type="password" style="width: 85%;border-radius: 10px;border: 0;padding: 6px 0 6px 6px;background: rgb(120,190,255);font-size: 16px;"></div>
						</div>
						<div class="s-table_item" style="border-top: 1px solid;">
							<div style="border:0;">New password</div>
							<div style="flex: 0.6;border:0;"><input type="password" style="width: 85%;border-radius: 10px;border: 0;padding: 6px 0 6px 6px;background: rgb(120,190,255);font-size: 16px;"></div>
						</div>
						<div class="s-table_item" style="border-top: 1px solid;">
							<div style="border:0;">Confirm password</div>
							<div style="flex: 0.6;border:0;"><input type="password" style="width: 85%;border-radius: 10px;border: 0;padding: 6px 0 6px 6px;background: rgb(120,190,255);font-size: 16px;"></div>
						</div>
						<div class="s-table_footer">
							<input type="submit" class="positive_button" value="Submit">
						</div>
					</form>
				</div>
			</div>
			<div id="call_container" class="flex flex_column main_container" style="display:none;width:100%">
				<div class="flex flex_column">
					<div class="flex align_flex_end" id="inside_header" class="flex">Dispatch call</div>
					<div class="flex" id="police_logo"><img width="150" src="static/policeman.svg"></div>
				</div>
				<div class="flex align_flex_end" id="middle_block">
					<div class="flex">
						<button id="mute_button" class="unmuted"><i class="fas fa-microphone-slash responsive_icon"></i><br>mute</button>
					</div>
				</div>
				<div class="flex">
					<button id="hangup_button" class="round_button"><i class="fas fa-phone responsive_icon"></i></button>
				</div>
			</div>
			<div id="admin_container" class="main_container" style="display:none;">

			</div>
		</main>
		<audio autoplay></audio>
		<script>
			const configuration = {
				iceServers: [
					{
						urls: [
							'stun:stun.l.google.com:19302',
							'stun:stun1.l.google.com:19302',
							'stun:stun2.l.google.com:19302'
						],
					},
				],
			};
			var rtc_connection;
			var candidates;
			var localStream;
			var remoteStream;
			var ids_order = [];

			function ajax(url, method, body){
				return new Promise((resolve, reject) => {
					$.ajax({
						method: method,
						url: url,
						data: body
					}).done(data => {
						resolve(data);
					}).fail(() => {
						setTimeout(() => {resolve(ajax(url, method, body))}, 1000);
					})
				})
			}

			const navs = ["call", "dispatch", "admin", "settings"];
			var hangup_sound = new Audio("static/hangup.webm");

			function nav_responsive(responsive = null) {
				var main = document.querySelector("main");
				var header = document.querySelector("header");
				var nav = document.querySelector("nav");
				if(responsive === null){
					responsive = !nav.classList.contains("responsive_nav");
				}
				if(!responsive){
					main.classList.remove("responsive_main");
					header.classList.remove("responsive_header");
					nav.classList.remove("responsive_nav");
				}else{
					main.classList.add("responsive_main");
					header.classList.add("responsive_header");
					nav.classList.add("responsive_nav");
				}
			}
			function update_nav(event = null){
				if(window.location.hash){
					var hash = window.location.hash.substring(1);
					if(navs.includes(hash)){
						navs.forEach(nav => {
							document.querySelector("#"+nav+"_container").style.display = "none";
						});
						if(document.querySelector("#"+hash+"_nav")){
							if(document.querySelector("#"+hash+"_nav").style.display == "none"){
								window.location.href = "#dispatch";
								return;
							}
						}

						document.querySelector("#"+hash+"_container").style.display = "";

					}else{
						window.location.hash = "#dispatch";
					}
				}else{
					window.location.hash = "#dispatch";
				}
				nav_responsive(false);
			}

			async function check_calls_thread(){
				var calls = JSON.parse(await ajax("api/receive_calls", "GET"));
				var s_table = document.querySelector("#dispatch_table");
				var table_html = "";
				var new_ids_order = [];

				calls.forEach(call => {
					var type = "";
					if(call["type"] == "POLICE"){
						type = "Police";
					}else if(call["type"] == "AMBULANCE"){
						type = "Ambulance";
					}else{
						type = "Fire Service";
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
						</div>
						<div class="s-table_footer">
							<a class="positive_button" href="#" onclick="answerCall('${call["id"]}');">Accept</a>
							<a class="negative_button" href="#" onclick="denyCall('${call["id"]}');">Deny</a>
							<a class="neutral_button" href="#" onclick="ignoreCall('${call["id"]}');">Ignore</a>
						</div>
					</div>
					`;
					new_ids_order[call["id"]] = JSON.parse(call["offer"]);
				});
				if(s_table.innerHTML.replaceAll("\n","").replaceAll("\r","").replaceAll(" ","").replaceAll("\t","").replaceAll("'","\"").replaceAll("=","").replaceAll("\"", "").toLowerCase() != table_html.replaceAll("\n","").replaceAll("\r","").replaceAll(" ","").replaceAll("\t","").replaceAll("'","\"").replaceAll("=","").replaceAll("\"", "").toLowerCase()){
					s_table.innerHTML = table_html;
					ids_order = new_ids_order;
				}
				setTimeout(() => {check_calls_thread();}, 1000);
			}

			async function wait_for_candidates(){
				return new Promise(async (resolve, reject) => {
					var response = await ajax("api/receive_candidates", "POST", {});

					if(response === "waiting"){
						setTimeout(() => {resolve(wait_for_candidates())}, 400);
					}else{
						resolve(response);
					}
				});
			}

			function createMediaStream(){
				return new Promise((resolve, reject) => {
					navigator.mediaDevices.getUserMedia({
						audio: {
							channelCount: 2,
							autoGainControl: false,
							noiseSuppression: false,
							echoCancellation: false
					}}).then(stream => {
						resolve(stream);
					}).catch(error => {
						reject(error);
					});
				});
			}

			function denyCall(id){
				ajax("api/close_call.php", "POST", {
					"id": id
				});
			}

			async function answerCall(id){
				rtc_connection = new RTCPeerConnection(configuration);

				try{
					localStream = await createMediaStream();
					localStream.getTracks().forEach(track => rtc_connection.addTrack(track, localStream));
				}catch{
					alert("You must enable microphone access in order to call.");
					return;
				}

				var call_header = document.querySelector("#inside_header");

				call_header.innerHtml = "Connecting...";
				document.querySelector("#call_nav").style.display = "";
				window.location.hash = "#call";

				candidates = [];

				remoteStream = new MediaStream();
				document.querySelector("audio").srcObject = remoteStream;

				rtc_connection.addEventListener('track', async (event) => {
					remoteStream.addTrack(event.track, remoteStream);
				});

				rtc_connection.onicecandidate = async (event) => {
					candidates.push(event.candidate);
				}

				try{
					rtc_connection.setRemoteDescription(ids_order[id]);
				}catch{
					disconnectCall("Got invalid data");
					return;
				}

				var rtc_answer = await rtc_connection.createAnswer();

				await rtc_connection.setLocalDescription(rtc_answer);

				var response = await ajax("api/accept_call.php", "POST", {
					"id": id,
					"answer": JSON.stringify(rtc_answer)
				});

				if(response != "true"){
					disconnectCall("Failed to connect");
					return;
				}

				call_header.innerHTML = "Exchanging";

				var receivecandidates_response = await wait_for_candidates();

				if(receivecandidates_response == "false"){
					disconnectCall("Exchange failed");
					return;
				}

				try{
					var caller_candidates = JSON.parse(receivecandidates_response);

					caller_candidates.forEach(candidate => {
						rtc_connection.addIceCandidate(candidate);
					});
				}catch{
					disconnectCall("Got invalid data");
					return;
				}

				async function send_candidates(){
					if(candidates.length > 0 && candidates[candidates.length-1] === null){
						candidates.splice(candidates.length-1,1);
						var sendcandidates_response = await ajax("api/send_candidates", "POST", {
							candidates: JSON.stringify(candidates)
						});

						if(sendcandidates_response == "true"){
							call_header.innerHTML = "Handshaking...";
							function checking_connected(){
								if(rtc_connection.connectionState == "connected"){
									rtc_connection.onconnectionstatechange = event => {
										if(rtc_connection.connectionState != "connected"){
											disconnectCall("Connection lost");
										}
									}
									call_header.innerHTML = "Emergency Call";
									document.querySelector("body").style.background = "linear-gradient(45deg, rgba(234,155,73,1) 0%, rgba(32,124,229,1) 100%)";
								}else if(rtc_connection.connectionState == "connecting"){
									setTimeout(() => {
										checking_connected();
									}, 1000);
								}else{
									disconnectCall("Handshaking failed");
								}
							}
							checking_connected();
						}else{
							disconnectCall("Exchanging failed");
							return;
						}
					}else{
						setTimeout(() => {
							send_candidates()
						}, 1000);
					}
				}

				send_candidates();
			}

			document.querySelector("#hangup_button").onclick = event => {disconnectCall();}

			document.querySelector("#mute_button").onclick = event => {
				if(localStream.getTracks()[0].enabled){
					localStream.getTracks()[0].enabled = false;
					document.querySelector("#mute_button").classList.add("muted");
						document.querySelector("#mute_button").classList.remove("unmuted");
				}else{
					localStream.getTracks()[0].enabled = true;
					document.querySelector("#mute_button").classList.remove("muted");
						document.querySelector("#mute_button").classList.add("unmuted");
				}
			}

			function disconnectCall(message = "Disconnected"){
				var call_header = document.querySelector("#inside_header");
				call_header.innerHTML = message;
				hangup_sound.play();
				ajax("api/close_call.php", "POST", {});
				setTimeout(() => {
					call_header.innerHTML = "Emergency Services";
					document.querySelector("#mute_button").classList.remove("muted");
						document.querySelector("#mute_button").classList.add("unmuted");
					document.querySelector("#call_nav").style.display = "none";
					document.querySelector("body").style.background = "linear-gradient(to bottom,rgba(42, 94, 232, 0.75) 0%,rgba(42, 94, 232, 1) 60%)";
					window.location.hash = "#dispatch";
				}, 2000);
				rtc_connection.close();
				if(localStream){
					localStream.getTracks().forEach(function(track) {
						track.stop();
					});
				}
				if(remoteStream){
					remoteStream.getTracks().forEach(function(track) {
						track.stop();
					});
				}
			}

			window.onhashchange = update_nav;
			check_calls_thread();
			update_nav();
	</script>
	</body>
</html>