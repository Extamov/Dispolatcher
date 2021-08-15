<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style><?php	require_once(__DIR__."/index.css");	?></style>
	</head>

	<body>
		<header>
			<a href="login" id="login">
				<span id="login_icon">
					<svg style="width: 1em; height: 1em; vertical-align: -.125em;" viewBox="0 0 512 512">
						<path fill="currentColor" d="M416 448h-84c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h84c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32h-84c-6.6 0-12-5.4-12-12V76c0-6.6 5.4-12 12-12h84c53 0 96 43 96 96v192c0 53-43 96-96 96zm-47-201L201 79c-15-15-41-4.5-41 17v96H24c-13.3 0-24 10.7-24 24v96c0 13.3 10.7 24 24 24h136v96c0 21.5 26 32 41 17l168-168c9.3-9.4 9.3-24.6 0-34z"></path>
					</svg>
				</span>
				<span id="login_text">Login</span>
			</a>
		</header>
		<main class="flex flex_column flex_responsive">
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
					<button id="mute_button" class="unmuted">
						<svg style="width: 1.25em; height: 1em; vertical-align: -.125em; font-size: 30px;" viewBox="0 0 640 512">
							<path fill="currentColor" d="M633.82 458.1l-157.8-121.96C488.61 312.13 496 285.01 496 256v-48c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v48c0 17.92-3.96 34.8-10.72 50.2l-26.55-20.52c3.1-9.4 5.28-19.22 5.28-29.67V96c0-53.02-42.98-96-96-96s-96 42.98-96 96v45.36L45.47 3.37C38.49-2.05 28.43-.8 23.01 6.18L3.37 31.45C-2.05 38.42-.8 48.47 6.18 53.9l588.36 454.73c6.98 5.43 17.03 4.17 22.46-2.81l19.64-25.27c5.41-6.97 4.16-17.02-2.82-22.45zM400 464h-56v-33.77c11.66-1.6 22.85-4.54 33.67-8.31l-50.11-38.73c-6.71.4-13.41.87-20.35.2-55.85-5.45-98.74-48.63-111.18-101.85L144 241.31v6.85c0 89.64 63.97 169.55 152 181.69V464h-56c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h160c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16z"></path>
						</svg><br>mute
					</button>
				</div>
			</div>
			<div class="flex">
				<button id="call_button" class="round_button">
					<svg style="width: 1em; height: 1em; vertical-align: -.125em; font-size: 30px;" viewBox="0 0 512 512">
						<path fill="currentColor" d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"></path>
					</svg>
				</button>
			</div>
		</main>
		<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
		<audio autoplay></audio>
		<script>
			<?php	require_once(__DIR__."/../essentials/iceservers.php");	?>
			<?php	require_once(__DIR__."/index.js");	?>
		</script>
	</body>
</html>