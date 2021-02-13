<!DOCTYPE html>
<html lang="en">
	<head>
		<?php	require_once(__DIR__."/../essentials/head.php");	?>
		<style>
			*{
				outline: none;
				font-family: "Helvetica Neue",Helvetica,"Segoe UI",Tahoma,Arial,sans-serif;
				color: white;
				font-weight: 700;
			}
			html {
				height: 100%;
				width: 100%;
			}
			body{
				margin:0;
				background: linear-gradient(to bottom,rgba(42, 94, 232, 0.75) 0%,rgba(42, 94, 232, 1) 60%);
				background-color: rgb(255, 255, 255);
				height: 100%;
				width: 100%;
				color: white;
			}
			header{
				margin: 0 0 5px 0;
				padding: 0;
				height: 80px;
				width: 100%;
				display: block;
				box-shadow: 2px 2px 5px 1px rgb(50,50,50);
			}
			main{
				width: 100%;
				height: calc(100% - 85px);
				overflow-y: auto;
				overflow-x: hidden;
			}
			nav{
				width: 90%;
				height: 100%;
				margin: 0 auto;
				padding: 0;
				display: flex;
				flex-direction: row;
				align-items: center;
			}
			nav > div{
				margin: 10px 25px;
				text-align: center;
				display: inline-block;
			}
			nav > div > a:hover{
				color: rgb(200, 200, 220);
			}
			a{
				text-decoration: unset;
			}
			#logo_nav > a{
				display:flex;
				justify-content: center;
				align-items: center;
				margin-bottom: 6px;
			}
			#logo_nav img{
				padding-right: 10px;
			}
			#logo_text{
				font-size: 23px;
				font-family: cursive;
			}
			#logout_button{
				float:right;
				background-color: rgb(0, 0, 0, 0);
				border: 1.5px solid white;
				border-radius: 5px;
				padding: 10px 30px;
				font-size: large;
				letter-spacing: 0.5px;
				text-decoration: none;
			}
			#logout_button:hover{
				background-color: white;
				color: rgba(42, 94, 232, 0.75);
			}
			#logout_button:active{
				color: rgba(42, 94, 232, 1);
				transform: scale(1.05);
				transition: transform,color 0.05s ease-in-out;
			}
			#logout_nav{
				margin-left: auto;
			}
			.main_container{
				width: 85%;
				height: 85%;
				position: relative;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
			}
			.main_container > h1{
				text-align: center;
			}
			.s-table{
				display: flex;
				justify-content: center;
				align-items: center;
				width: 100%;
				flex-direction: column;
			}
			.s-table_row{
				display: flex;
				justify-content: center;
				align-items: center;
				flex-direction: column;
				width: 100%;
				border: 0;
				border-radius: 10px;
				margin-bottom: 40px;
				background-color: rgb(0,0,200, 0.2);
				box-shadow: 2px 2px 5px 1px black;
			}
			.s-table_item{
				display: flex;
				justify-content: center;
				align-items: center;
				width: 100%;
			}
			.s-table_row > .s-table_item:first-child > *:nth-child(1){
				border-radius: 10px 0 0 0;
			}
			.s-table_row > .s-table_item:first-child > *:nth-child(2){
				border-radius: 0 10px 0 0;
			}
			.s-table_item > *:nth-child(1) {
				flex: 0.3;
				border-right: 2px solid rgba(150,150,190,1);
			}
			.s-table_item > *:nth-child(2) {
				flex: 0.8;
				/* border-left: 1px solid rgba(150,150,190,1); */
			}
			.s-table_item > *{
				text-align: center;
				padding: 10px 0;
				border-bottom: 2px solid rgba(150,150,190,1);
			}
			.s-table_row > .s-table_item:nth-last-child(2) > *{
				border-bottom: 0;
			}
			.s-table_footer{
				display: flex;
				justify-content: center;
				align-items: center;
				width: 100%;
			}
			.s-table_footer > * {
				flex: 1;
				padding: 15px 0;
				text-align: center;
			}
			.s-table_footer > *:last-child{
				border-radius: 0 0 10px 0;
			}
			#accept_call{
				background-color: rgb(50, 140, 50);
				border-radius: 0 0 0 10px;
			}
			#deny_call{
				background-color: rgb(160, 50, 50);
			}
			#ignore_call{
				background-color: rgb(110,110,110);
				border-radius: 0 0 10px 0;
			}
			#accept_call:hover{
				background-color: rgb(50, 160, 50);
			}
			#deny_call:hover{
				background-color: rgb(180, 50, 50);
			}
			#ignore_call:hover{
				background-color: rgb(120,120,120);
			}
			#call_nav{
				display: none;
			}
			@media screen and (max-width: 530px) {
				#logout_button{
					float:unset;
					background-color: unset;
					border: unset;
					border-radius: unset;
					padding: unset;
					font-size: unset;
					letter-spacing: unset;
				}
				#logout_button:hover{
					background-color: unset;
					color: rgb(200, 200, 220);
				}
			}
			@media screen and (max-width: 720px) {
				nav > div{
					margin: auto;
				}
				#logo_nav{
					display: none;
				}
			}
			@media screen and (max-width: 850px){
				.responsive_column{
					display:none;
				}
			}
		</style>
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
			<div class="main_container">
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
							<a id="accept_call" href="#">Accept</a>
							<a id="deny_call" href="#">Deny</a>
							<a id="ignore_call" href="#">Ignore</a>
						</div>
					</div>
				</div>
			</div>
		</main>
	</body>
</html>