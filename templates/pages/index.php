<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style>
			*{
				outline: none;
			}
			body{
				margin:0;
				background-color: rgb(40, 40, 40);
			}
			header{
				height: 90px;

			}
			#login{
				position: relative;
				float:right;
				margin-top: 30px;
				margin-right: 30px;
				background-color: rgb(80, 90, 80);
				border: 0;
				border-radius: 5px;
				color: white;
				padding: 10px 50px;
				font-size: 18px;
				font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
				letter-spacing: 2px;
				text-decoration: none;
			}
			#login:hover{
				background-color: rgb(90, 100, 90);
			}
			#login:active{
				background-color: rgb(90, 130, 90);
			}
			footer{
				padding:20px 0;
				text-align: center;
				width: 100%;
				box-shadow: -2px 2px 5px 3px black;
				position: fixed;
				bottom: 0;
				color:rgb(180, 180, 180);
				font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
			}
			#inside_header{
				text-align:center;
				color: white;
				font-size: 42px;
				letter-spacing: 1px;
				padding: 40px 0;
				font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
			}
			main{
				height: calc(100vh - 160px);
			}
			#main_body{
				height: calc(100% - 130px);
			}
			#select_block{
				height:50%;
				text-align: center;
			}
			#call_block{
				height:50%;
				text-align: center;
			}
			#select_type{
				position:relative;
				top:60%;
				padding: 16px 16px 16px 50px;
				border: 0;
				border-radius: 10px;
				width: 280px;
				color: white;
				background-color: rgb(100, 100, 100);
				letter-spacing: 1px;
				font-size:24px;
				font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
			}
			#select_type:after{
				position: relative;
				right: 5px;
			}
			#select_type option{
				border-radius: 10px;
			}
			#call_button{
				position:relative;
				top:50%;
				padding: 10px 117.5px;
				border-radius: 10px;
				border: 0;
				letter-spacing: 1px;
				font-size:24px;
				font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
				color: white;
				background-color: rgb(90, 130, 90);
				transition: transform 0.1s;
			}
			#call_button:hover{
				background-color: rgb(100, 150, 100);
			}
			#call_button:active{
				background-color: rgb(100, 180, 100);
				transform: translateY(-10px);
				transition: transform 0.1s;
			}
			@media screen and (max-width: 600px) {
				#login{
					padding: 10px 30px;
				}
			}
			@media screen and (max-width: 400px) {
				#login{
					padding: 10px 20px;
				}
			}
			@media screen and (max-width: 410px) {
				footer{
					font-size: small;
					padding:22px 0;
				}
			}

			@media screen and (max-width: 495px) {
				#inside_header{
					font-size: xx-large;
				}
				#select_type{
					font-size: 18px;
					padding: 16px 16px 16px 50px;
					width: 234px;
				}
				#call_button{
					font-size: 18px;
					padding: 10px 100px;
				}
			}
			@media screen and (max-width: 340px) {
				#inside_header{
					font-size: 24px;
				}
				#select_type{
					font-size: 16px;
					padding: 14px 14px 14px 40px;
					width: 191.5px;
				}
				#call_button{
					font-size: 16px;
					padding: 10px 80px;
				}
				footer{
					font-size: 12px;
				}
			}
		</style>
	</head>
	<body>
		<header>
			<a href="login" id="login">Login</a>
		</header>
		<main>
			<div id="inside_header">Emergency Services</div>
			<div id="main_body">
				<div id="select_block">
					<select name="type" id="select_type">
						<option value="police">Police</option>
						<option value="ambulance">Ambulance</option>
						<option value="fire">Fire Service</option>
					</select>
				</div>
				<div id="call_block">
					<button id="call_button">Call</button>
				</div>
			</div>
		</main>
		<footer><?php	require_once(__DIR__."/../essentials/footer.php");	?></footer>
	</body>
</html>