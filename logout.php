<?php
	require_once(__DIR__."/server-libs/website.php");

	session_start();
	session_destroy();

	header("Location: login");