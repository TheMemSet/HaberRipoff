<?php
$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");

function generateKey() {
	$characters = "1234567890abcdef";
	$key = "";
	for ($i = 0; $i < 32; $i++)
		$key .= $characters[rand(0, 15)];
	return $key;
}

function checkCredentials($username, $password) {
	global $db;

	$query = $db->prepare("SELECT * FROM users WHERE username = :username AND password = SHA1(:password)");
	$query->execute(array(":username" => $username, ":password" => $password));
	if ($query->fetchObject())
		return true;
	else
		return false;
}

function clearPrevious($username) {
	global $db;

	$db->prepare("DELETE FROM keystbl WHERE user = :username")->execute(array(":username" => $username));
}

if (!isset($_POST["username"]) || !isset($_POST["password"]))
	echo json_encode(array("text" => "INVALID_LOGIN"));
else {
	$username = $_POST["username"];
	$password = $_POST["password"];
	if (!checkCredentials($username, $password))
		echo json_encode(array("text" => "INVALID_LOGIN"));
	else {
		clearPrevious($username);
		$token = generateKey();
		echo json_encode(array("text" => "LOGIN_SUCCESSFUL", "token" => $token));
		$query = $db->prepare("INSERT INTO keystbl(user, token) VALUES (:user, :token)");
		$query->execute(array(":user" => $username, ":token" => $token));
	}
}
