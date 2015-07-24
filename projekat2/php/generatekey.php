<?php
$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");

if (!isset($_POST["username"]) !isset($_POST["password"]))
	die("Username or password not provided");

