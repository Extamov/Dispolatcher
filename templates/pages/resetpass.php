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
					<input type="hidden" name="id" value="<?php	echo($site_params["id"]);	?>">
					<img style="width:70px;" src="static/logo.svg" alt="logo">
					<div style="text-align: center;margin-top: 20px;font-size: 13px;">Choose a new password for your account.</div>
					<input class="container_part" style="margin: 3px 0 15px 0;" type="password" autocomplete="off" maxlength="100" pattern="^.{6,100}$" name="pass" title="Password must have a length between 6 and 100." placeholder="Password" required>
					<input class="container_part" style="margin: 15px 0 20px 0;" type="password" autocomplete="off" maxlength="100" pattern="^.{6,100}$" name="pass_confirm" title="Password must have a length between 6 and 100." placeholder="Password confirm" required>
					<input class="container_part" type="submit" value="RESTORE PASSWORD">
				</form>
			</main>
			<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
		</div>
		<script>
			<?php	if( isset($site_params["error"]) ){ echo sprintf("alert('%s');", $site_params["error"]); }	?>
			document.querySelector("form").onsubmit = event => {
				let password = document.querySelector("input[name=pass]").value;
				let password_confirm = document.querySelector("input[name=pass_confirm]").value;

				if(password != password_confirm){
					alert("Password confim must be the same as Password");
					return false;
				}
			};
		</script>
	</body>
</html>