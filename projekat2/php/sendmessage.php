<?php
if (!isset($_POST["message"]))
	die("No message received");

$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");
$query = $db->prepare("INSERT INTO messages(message) VALUES (:msgtext)");
$query->execute(array(":msgtext" => $_POST["message"]));
$count = $db->query("SELECT * FROM idtbl")->fetchObject()->id;
$count++;
$db->query("UPDATE idtbl SET id = $count");

echo "SUCCESS";