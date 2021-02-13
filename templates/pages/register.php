<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style><?php	require_once(__DIR__."/loginform.css");	?></style>
		<style>
			.container{
				max-height: 440px;
				top: 53%;
			}
			@media screen and (max-height: 590px) {
				.container{
					max-height: 370px;
				}
				.container_part{
					margin: 9px 0;
				}
			}
		</style>
	</head>
	<body>
		<div id="root">
			<header>Police Dispatch Register</header>
			<main>
				<form method="POST" class="container flex flex_column">
					<img style="width:70px;" src="static/logo.svg" alt="">
					<input class="container_part" type="email" autocomplete="off" maxlength="42" name="email" pattern="^[a-zA-Z0-9,.-_]{2,15}@[a-zA-Z0-9,.-_]{2,15}\.[a-zA-Z]{2,10}$" title="Email can only contain english letters, comma, dash and underscore" placeholder="Email" required>
					<input class="container_part" type="email" autocomplete="off" maxlength="42" name="email_confirm" pattern="^[a-zA-Z0-9,.-_]{2,15}@[a-zA-Z0-9,.-_]{2,15}\.[a-zA-Z]{2,10}$" title="Email can only contain english letters, comma, dash and underscore" placeholder="Email confirm" required>
					<input class="container_part" type="password" autocomplete="off" maxlength="100" pattern="^.{6,100}$" name="pass" title="Password must have a length between 6 and 100." placeholder="Password" required>
					<input class="container_part" style="margin: 15px 0 5px 0;" type="password" autocomplete="off" maxlength="100" pattern="^.{6,100}$" name="pass_confirm" title="Password must have a length between 6 and 100." placeholder="Password confirm" required>
					<div style="margin-bottom: 15px;font-size:14px;text-align:center;">
						<a href="panel">Go back to panel</a>
					</div>
					<input class="container_part" type="submit" value="SIGN UP">
				</form>
			</main>
			<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
			<script>
				<?php	if( isset($site_params["error"]) ){ echo sprintf("alert('%s');", $site_params["error"]); }	?>
				document.querySelector("form").onsubmit = (e) => {
					var email = document.querySelector("input[name=email]").value;
					var email_confirm = document.querySelector("input[name=email_confirm]").value;

					var password = document.querySelector("input[name=pass]").value;
					var password_confirm = document.querySelector("input[name=pass_confirm]").value;

					if(email != email_confirm){
						alert("Email confim must be the same as Email");
						return false;
					}else if(password != password_confirm){
						alert("Password confim must be the same as Password");
						return false;
					}
				}
			</script>
		</div>
	</body>
</html>