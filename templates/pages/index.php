<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<script src="static/fontawesome.js"></script>
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
				<div class="flex" style="display:none;" id="police_logo"><img width="150" src="static/policeman.svg"></div> <!--display=''-->
			</div>
			<div class="flex" id="middle_block"> <!--align_flex_end-->
				<div style="flex:1;" class="flex">
					<select name="type" id="select_type">
						<option value="police">Police</option>
						<option value="ambulance">Ambulance</option>
						<option value="fire">Fire Service</option>
					</select>
				</div>
				<div class="flex" style="display:none;"> <!--display=''-->
					<button id="mute_button"><i class="fas fa-microphone-slash responsive_icon"></i><br>mute</button>
				</div>
			</div>
			<div class="flex">
				<button id="call_button" class="round_button"><i class="fas fa-phone responsive_icon"></i></button>
			</div>
		</main>
		<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
		<script>
			function in_call(){
				document.querySelector("#inside_header").classList.add("align_flex_end");
				document.querySelector("#middle_block").classList.add("align_flex_end");
				document.querySelector("#select_type").parentElement.style.display = "none";
				document.querySelector("#police_logo").style.display = "";
				document.querySelector("#mute_button").parentElement.style.display = "";
				document.querySelector("#call_button").id = "hangup_button";
				document.body.style.background = "linear-gradient(45deg, rgba(234,155,73,1) 0%, rgba(32,124,229,1) 100%)";
			}

			function not_in_call(){
				document.querySelector("#inside_header").classList.remove("align_flex_end");
				document.querySelector("#middle_block").classList.remove("align_flex_end");
				document.querySelector("#select_type").parentElement.style.display = "";
				document.querySelector("#police_logo").style.display = "none";
				document.querySelector("#mute_button").parentElement.style.display = "none";
				document.querySelector("#hangup_button").id = "call_button";
				document.body.style.background = "linear-gradient(45deg, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%)";
			}
			document.querySelector("#call_button").onclick = event => {
				if(document.querySelector("#call_button")){
					in_call();
				}else{
					not_in_call();
				}
			}
		</script>
	</body>
</html>