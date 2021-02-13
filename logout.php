<?php
	require_once("server-libs/website.php");

	session_start();
	session_destroy();

	header("Location: login");