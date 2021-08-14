<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style><?php	require_once(__DIR__."/panel.css");	?></style>
	</head>
	<body>
		<header>
			<nav>
				<div id="logo_nav">
					<a href="panel">
						<img src="static/logo.svg" alt="logo" width="30">
						<span id="logo_text">Dispolatcher</span>
					</a>
				</div>
				<div id="call_nav" style="display:none;"><a href="#call">Call</a></div>
				<div id="dispatch_nav"><a href="#dispatch">Dispatch</a></div>
				<?php	if(isset($site_params["level"]) && $site_params["level"] >= 1){?>
					<div id="admin_nav"><a href="#admin">Admin</a></div>
				<?php	}	?>
				<div id="settings_nav"><a href="#settings">Settings</a></div>
				<div id="logout_nav"><a id="logout_button" href="logout">Logout</a></div>
				<div id="nav_button_block">
					<a id="nav_button" href="javascript:void(0)" onclick="nav_responsive();">
						<svg style="width: 1em; height: 1em; vertical-align: -.125em;" viewBox="0 0 448 512">
							<path fill="currentColor" d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"></path>
						</svg>
					</a>
				</div>
			</nav>
		</header>
		<main>
			<div id="dispatch_container" class="main_container" style="display:none;">
				<div class="s-table" id="dispatch_table">
					<div class="s-table_row s-table_header">
						<div class="s-table_item">Dispatches (Loading...)</div>
						<div class="s-table_footer">
						</div>
					</div>
				</div>
			</div>
			<div id="settings_container" class="main_container" style="display:none;">
				<div class="s-table">
					<div class="s-table_row s-table_header" style="padding: 15px 0;">
						<div class="s-table_item">Settings</div>
						<div class="s-table_footer">
						</div>
					</div>
					<form method="POST" id="change_password_form" class="s-table_row">
						<div class="s-table_item">
							<div style="border:0;border:0;">Change password</div>
						</div>
						<div class="s-table_item" style="border-top: 1px solid;">
							<div style="border:0;" class="responsive_title">Previous password</div>
							<div style="border:0;"><input type="password" name="old_pass" placeholder="Old Password" maxlength="100" pattern="^.{6,100}$" title="Password must have a length between 6 and 100." style="width: 85%;border-radius: 10px;border: 0;padding: 6px 0 6px 6px;background: rgb(120,190,255);font-size: 16px;"></div>
						</div>
						<div class="s-table_item" style="border-top: 1px solid;">
							<div style="border:0;" class="responsive_title">New password</div>
							<div style="border:0;"><input type="password" name="new_pass" placeholder="New Password" maxlength="100" pattern="^.{6,100}$" title="Password must have a length between 6 and 100." style="width: 85%;border-radius: 10px;border: 0;padding: 6px 0 6px 6px;background: rgb(120,190,255);font-size: 16px;"></div>
						</div>
						<div class="s-table_item" style="border-top: 1px solid;">
							<div style="border:0;" class="responsive_title">Confirm password</div>
							<div style="border:0;"><input type="password" name="new_pass_confirm" placeholder="Confirm Password" maxlength="100" pattern="^.{6,100}$" title="Password must have a length between 6 and 100." style="width: 85%;border-radius: 10px;border: 0;padding: 6px 0 6px 6px;background: rgb(120,190,255);font-size: 16px;"></div>
						</div>
						<div class="s-table_footer">
							<input type="submit" class="positive_button" value="Submit">
						</div>
					</form>
					<form class="s-table_row super_button" method="POST">
						<input type="submit" style="width:100%;height:100%;padding: 15px 0;border-radius:10px;" class="super_button" name="unsession" value="Deauthorize other sessions">
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
						<button id="mute_button" class="unmuted">
							<svg style="width: 1.25em; height: 1em; vertical-align: -.125em; font-size: 30px;" viewBox="0 0 640 512">
								<path fill="currentColor" d="M633.82 458.1l-157.8-121.96C488.61 312.13 496 285.01 496 256v-48c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v48c0 17.92-3.96 34.8-10.72 50.2l-26.55-20.52c3.1-9.4 5.28-19.22 5.28-29.67V96c0-53.02-42.98-96-96-96s-96 42.98-96 96v45.36L45.47 3.37C38.49-2.05 28.43-.8 23.01 6.18L3.37 31.45C-2.05 38.42-.8 48.47 6.18 53.9l588.36 454.73c6.98 5.43 17.03 4.17 22.46-2.81l19.64-25.27c5.41-6.97 4.16-17.02-2.82-22.45zM400 464h-56v-33.77c11.66-1.6 22.85-4.54 33.67-8.31l-50.11-38.73c-6.71.4-13.41.87-20.35.2-55.85-5.45-98.74-48.63-111.18-101.85L144 241.31v6.85c0 89.64 63.97 169.55 152 181.69V464h-56c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h160c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16z"></path>
							</svg><br>mute
						</button>
					</div>
				</div>
				<div class="flex">
					<button id="hangup_button" class="round_button">
						<svg style="width: 1em; height: 1em; vertical-align: -.125em; font-size: 30px;" viewBox="0 0 512 512">
							<path fill="currentColor" d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"></path>
						</svg>
					</button>
				</div>
			</div>
			<div id="admin_container" class="main_container" style="display:none;">
					<?php	if(isset($site_params["dispatchers_html_table"])){echo($site_params["dispatchers_html_table"]);}	?>
			</div>
		</main>
		<audio autoplay></audio>
		<script>
			<?php	require_once(__DIR__."/../essentials/iceservers.php");	?>
			<?php	require_once(__DIR__."/panel.js");	?>
			<?php if (isset($site_params["error"])) {echo ("alert(\"" . $site_params["error"] . "\");");}	?>
		</script>
	</body>
</html>