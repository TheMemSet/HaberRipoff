<?php
if (!isset($_POST["latest"]))
	die("Query invalid");

$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");
$count = $db->query("SELECT * FROM idtbl")->fetchObject()->id - $_POST["latest"];
$count = min($count, 30);
$query = $db->query("SELECT * FROM messages ORDER BY id DESC LIMIT $count");

$messages = array();

while ($row = $query->fetchObject()) 
	$messages[] = array("text" => $row->message, "id" => $row->id);

echo json_encode(array_reverse($messages));