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
			</nav>
		</header>
		<main>
			<div id="dispatch_container" style="display:none;" class="main_container">
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
							<a class="netural_button" href="#">Ignore</a>
						</div>
					</div>
				</div>
			</div>
			<div id="settings_container" class="main_container">
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
							<a class="positive_button" href="#">Submit</a>
						</div>
					</form>
				</div>
			</div>
		</main>
	</body>
</html>