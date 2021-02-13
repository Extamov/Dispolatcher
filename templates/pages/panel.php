<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<script src="static/fontawesome.js"></script>
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
				<div id="call_nav"><a href="#call">Call</a></div>
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
				<div class="s-table">
					<div class="s-table_row">
						<div class="s-table_item">
							<div>Type</div>
							<div>Police</div>
						</div>
						<div class="s-table_item">
							<div>IP</div>
							<div>192.168.1.1</div>
						</div>
						<div class="s-table_item">
							<div>Location</div>
							<div>Israel, Sderot</div>
						</div>
						<div class="s-table_item">
							<div>Time</div>
							<div>15:00</div>
						</div>
						<div class="s-table_footer">
							<a class="positive_button" href="#">Accept</a>
							<a class="negative_button" href="#">Deny</a>
							<a class="neutral_button" href="#">Ignore</a>
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
			<div id="call_container" class="flex flex_column main_container" style="display:none;">
				<div class="flex flex_column">
					<div class="flex align_flex_end" id="inside_header" class="flex">Dispatch call</div>
					<div class="flex" id="police_logo"><img width="150" src="static/policeman.svg"></div>
				</div>
				<div class="flex align_flex_end" id="middle_block">
					<div class="flex">
						<button id="mute_button"><i class="fas fa-microphone-slash responsive_icon"></i><br>mute</button>
					</div>
				</div>
				<div class="flex">
					<button id="hangup_button" class="round_button"><i class="fas fa-phone responsive_icon"></i></button>
				</div>
			</div>
			<div id="admin_container" class="main_container" style="display:none;">

			</div>
		</main>
		<script>
			const navs = ["call", "dispatch", "admin", "settings"];
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
						document.querySelector("#"+hash+"_container").style.display = "";
					}else{
						window.location.hash = "#dispatch";
					}
				}else{
					window.location.hash = "#dispatch";
				}
				nav_responsive(false);
			}
			window.onhashchange = update_nav;
			update_nav();
	</script>
	</body>
</html>