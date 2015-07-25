<?php
if (!isset($_POST["message"]) || !isset($_POST["token"]))
	die("Message or token not received");

$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");

function validateToken($token) {
	if ($token == "")
		return false;

	global $db;

	$query = $db->prepare("SELECT * FROM keystbl WHERE token = :token");
	$query->execute(array(":token" => $token));
	if ($query->fetchObject())
		return true;
	else
		return false;
}

function getUser($token) {
	global $db;

	$query = $db->prepare("SELECT * FROM keystbl WHERE token = :token");
	$query->execute(array(":token" => $token));
	return $query->fetchObject()->user;
}

$message = $_POST["message"];
$token = $_POST["token"];

if (!validateToken($token))
	echo "INVALID_TOKEN";
else {
	$query = $db->prepare("INSERT INTO messages(message, user) VALUES (:msgtext, :user)");
	$query->execute(array(":msgtext" => $message, ":user" => getUser($token)));
	$count = $db->query("SELECT * FROM idtbl")->fetchObject()->id;
	$count++;
	$db->query("UPDATE idtbl SET id = $count");

	echo "SUCCESS";
}