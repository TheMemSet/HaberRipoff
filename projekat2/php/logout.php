<?php
if (!isset($_POST["token"]))
	echo "LOGOUT_UNSUCCESSFUL";

$token = $_POST["token"];
$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");

function validateToken($token) {
	global $db;

	$query = $db->prepare("SELECT * FROM keystbl WHERE token = :token");
	$query->execute(array(":token" => $token));

	if ($query->fetchObject())
		return true;
	else
		return false;
}

if (!validateToken($token))
	echo "LOGOUT_UNSUCCESSFUL";
else {
	$query = $db->prepare("DELETE FROM keystbl WHERE token = :token");
	$query->execute(array(":token" => $token));
	echo "LOGOUT_SUCCESSFUL";
}
