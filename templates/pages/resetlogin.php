<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style><?php	require_once(__DIR__."/loginform.css");	?></style>
	</head>
	
	<body>
		<div id="root">
			<header>Dispolatcher Restore</header>
			<main>
				<form method="POST" class="container flex flex_column">
					<img style="width:70px;" src="static/logo.svg" alt="logo">
					<div style="text-align: center;margin-top: 20px;font-size: 13px;">Enter the email associated to your account.</div>
					<input class="container_part" style="margin: 3px 0 5px 0;" type="email" autocomplete="off" maxlength="42" name="email" pattern="^[a-zA-Z0-9,.-_]{2,15}@[a-zA-Z0-9,.-_]{2,15}\.[a-zA-Z]{2,10}$" title="Email can only contain english letters, comma, dash and underscore" placeholder="Email" required>
					<div style="margin-bottom: 15px;">
						<a href="." style="float: left;">Not police?</a>
						<a href="login" style="float: right;">Remember password?</a>
					</div>
					<input class="container_part" type="submit" value="RESTORE PASSWORD">
				</form>
			</main>
			<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
		</div>
		<script>
			<?php	if( isset($site_params["error"]) ){ echo sprintf("alert('%s');", $site_params["error"]); }	?>
		</script>
	</body>
</html>