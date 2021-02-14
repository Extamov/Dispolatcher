<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<script src="static/fontawesome.js"></script>
		<script src="static/jquery.js"></script>
		<style><?php	require_once(__DIR__."/index.css");	?></style>
	</head>
	<body>
		<header>
			<a href="login" id="login">
				<span id="login_icon"><i class="fas fa-sign-in-alt"></i></span>
				<span id="login_text">Login</span>
			</a>
		</header>
		<main class="flex flex_column">
			<div class="flex flex_column">
				<div class="flex" id="inside_header" class="flex">Emergency Services</div>
				<div class="flex" style="display:none;" id="police_logo"><img width="150" src="static/policeman.svg"></div>
			</div>
			<div class="flex" id="middle_block">
				<div style="flex:1;" class="flex">
					<select name="type" id="select_type">
						<option value="1">Police</option>
						<option value="2">Ambulance</option>
						<option value="3">Fire Service</option>
					</select>
				</div>
				<div class="flex" style="display:none;">
					<button id="mute_button" class="unmuted"><i class="fas fa-microphone-slash responsive_icon"></i><br>mute</button>
				</div>
			</div>
			<div class="flex">
				<button id="call_button" class="round_button"><i class="fas fa-phone responsive_icon"></i></button>
			</div>
		</main>
		<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
		<audio autoplay></audio>
		<script>
			<?php	require_once(__DIR__."/../essentials/iceservers.php");	?>
			var rtc_connection;
			var candidates;
			var localStream;
			var remoteStream;

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

			function createMediaStream(){
				return new Promise((resolve, reject) => {
					navigator.mediaDevices.getUserMedia({<?php	require_once(__DIR__."/../essentials/mediaproperties.php");	?>}).then(stream => {
						resolve(stream);
					}).catch(error => {
						reject(error);
					});
				});
			}

			async function wait_for_answer(){
				return new Promise(async (resolve, reject) => {
					var response = await ajax("api/get_answer", "POST", {});

					if(response === "waiting"){
						setTimeout(() => {resolve(wait_for_answer())}, 400);
					}else{
						resolve(response);
					}
				});
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

			var dialing_sound = new Audio("static/calling.webm");
			dialing_sound.loop = true;
			var hangup_sound = new Audio("static/hangup.webm");

			document.querySelector("#call_button").onclick = async (event) => {
				if(document.querySelector("#call_button")){
					document.querySelector("#call_button").disabled = true;

					var call_header = document.querySelector("#inside_header");
					var call_type = document.querySelector("#select_type").value;

					rtc_connection = new RTCPeerConnection(configuration);

					try{
						localStream = await createMediaStream();
						localStream.getTracks().forEach(track => rtc_connection.addTrack(track, localStream));
					}catch{
						alert("You must enable microphone access in order to call.");
						document.querySelector("#call_button").disabled = false;
						return;
					}

					call_header.innerHTML = "Registering...";

					candidates = [];

					remoteStream = new MediaStream();
					document.querySelector("audio").srcObject = remoteStream;

					rtc_connection.addEventListener('track', async (event) => {
						remoteStream.addTrack(event.track, remoteStream);
					});

					rtc_connection.onicecandidate = async (event) => {
						candidates.push(event.candidate);
					}

					var rtc_offer = await rtc_connection.createOffer();

					await rtc_connection.setLocalDescription(rtc_offer);

					var registercall_response = await ajax("api/register_call", "POST", {
						type: call_type,
						rtc_offer: JSON.stringify(rtc_offer)
					});

					if(registercall_response != "true"){
						disconnectCall("Failed to register");
						return;
					}

					call_header.innerHTML = "Calling...";
					dialing_sound.play();

					rtc_answer = await wait_for_answer();

					if(rtc_answer == "false"){
						disconnectCall("Call rejected");
						return;
					}else if(rtc_answer == "timeout"){
						disconnectCall("Timed out");
						return;
					}

					await rtc_connection.setRemoteDescription(JSON.parse(rtc_answer));

					document.querySelector("#select_type").parentElement.style.display = "none";
					document.querySelector("#mute_button").parentElement.style.display = "";

					async function send_candidates() {
						if (candidates.length > 0 && candidates[candidates.length - 1] === null) {
							candidates.splice(candidates.length - 1, 1);
							var sendcandidates_response = await ajax("api/send_candidates", "POST", {
								candidates: JSON.stringify(candidates)
							});

							if(sendcandidates_response == "true"){
								var receivecandidates_response = await wait_for_candidates();

								if(receivecandidates_response == "false"){
									disconnectCall("Exchange failed");
								}else if(receivecandidates_response == "timeout"){
									disconnectCall("Timed out");
								}else{
									received_candidates = JSON.parse(receivecandidates_response);
									received_candidates.forEach(candidate => {
										rtc_connection.addIceCandidate(candidate);
									});
									call_header.innerHTML = "Handshaking...";
									dialing_sound.pause();
									function checking_connected(){
										if(rtc_connection.connectionState == "connected"){
											call_header.innerHTML = "connected";
											rtc_connection.onconnectionstatechange = event => {
												if(rtc_connection.connectionState == "disconnected"){
													disconnectCall("Connection lost");
												}else{
													call_header.innerHTML = rtc_connection.connectionState;
												}
											}
											document.querySelector("#inside_header").classList.add("align_flex_end");
											document.querySelector("#middle_block").classList.add("align_flex_end");
											document.querySelector("#select_type").parentElement.style.display = "none";
											document.querySelector("#police_logo").style.display = "";
											document.querySelector("#mute_button").parentElement.style.display = "";
											document.querySelector("body").style.background = "linear-gradient(45deg, rgba(234,155,73,1) 0%, rgba(32,124,229,1) 100%)";
											try{document.querySelector("#call_button").id = "hangup_button";}catch{}
											document.querySelector("#hangup_button").disabled = false;
										}else if(rtc_connection.connectionState == "disconnected"){
											disconnectCall("Connection lost");
										}else{
											call_header.innerHTML = rtc_connection.connectionState;
											setTimeout(() => {
												checking_connected();
											}, 1000);
										}
									}
									checking_connected();
								}
							}else{
								disconnectCall("Exchange failed");
							}
						}else{
							setTimeout(() => {
								send_candidates()
							}, 1000);
						}
					}

					send_candidates();

					call_header.innerHTML = "Exchanging";
				}else{
					disconnectCall();
				}

				document.querySelector("#mute_button").onclick = event => {
					if(localStream){
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
				}

				function disconnectCall(message = "Disconnected"){
					var call_header = document.querySelector("#inside_header");
					call_header.innerHTML = message;
					dialing_sound.pause();
					hangup_sound.play();
					ajax("api/close_call.php", "POST", {});
					setTimeout(() => {
						call_header.innerHTML = "Emergency Services";
						document.querySelector("#inside_header").classList.remove("align_flex_end");
						document.querySelector("#middle_block").classList.remove("align_flex_end");
						document.querySelector("#select_type").parentElement.style.display = "";
						document.querySelector("#police_logo").style.display = "none";
						document.querySelector("#mute_button").parentElement.style.display = "none";
						document.querySelector("body").style.background = "linear-gradient(45deg, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%)";
						try{document.querySelector("#hangup_button").id = "call_button";}catch{}
						document.querySelector("#call_button").disabled = false;
						document.querySelector("#mute_button").classList.remove("muted");
						document.querySelector("#mute_button").classList.add("unmuted");
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
			};
		</script>
	</body>
</html>