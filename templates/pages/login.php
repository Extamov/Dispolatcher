<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style><?php	require_once(__DIR__."/loginform.css");	?></style>
	</head>
	<body>
		<div id="root">
			<header>Police Dispatch Login</header>
			<main>
				<form method="POST" class="container flex flex_column">
					<img style="width:70px;" src="static/logo.svg" alt="">
					<input class="container_part" type="email" autocomplete="off" maxlength="42" name="email" pattern="^[a-zA-Z0-9,.-_]{2,15}@[a-zA-Z0-9,.-_]{2,15}\.[a-zA-Z]{2,10}$" title="Email can only contain english letters, comma, dash and underscore" placeholder="Email" required>
					<input class="container_part" style="margin: 15px 0 5px 0;" type="password" autocomplete="off" maxlength="100" pattern="^.{6,100}$" name="pass" title="Password must have a length between 6 and 100." placeholder="Password" required>
					<div style="margin-bottom: 15px;">
						<a href="." style="float: left;">Not police?</a>
						<a href="resetlogin" style="float: right;">Forgot password?</a>
					</div>
					<input class="container_part" type="submit" value="LOG IN">
				</form>
			</main>
			<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
		</div>
		<script><?php	if( isset($site_params["error"]) ){ echo sprintf("alert('%s');", $site_params["error"]); }	?></script>
	</body>
</html>