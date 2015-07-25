<?php
$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");

function checkIfTaken($username) {
	global $db;

	$query = $db->prepare("SELECT * FROM users WHERE username = :username");
	$query->execute(array(":username" => $username));
	if ($query->fetchObject())
		return true;
	else
		return false;
}

if (!isset($_POST["username"]) || !isset($_POST["password"]))
	echo "INVALID_CREDENTIALS";
else {
	$username = $_POST["username"];
	$password = $_POST["password"];
	if (checkIfTaken($username))
		echo "USERNAME_TAKEN";
	else {
		echo "REGISTRATION_SUCCESSFUL";
		$query = $db->prepare("INSERT INTO users(username, password) VALUES (:username, SHA1(:password))");
		$query->execute(array(":username" => $username, ":password" => $password));
	}
}