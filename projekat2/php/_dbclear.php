<?php
if (!isset($_GET["password"]) || $_GET["password"] !== "carrots") {
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL /php/_dbclear.php was not found on this server.</p>
<hr>
<address>Apache/2.4.9 (Win64) PHP/5.5.12 Server at localhost Port 80</address>
</body></html>
<?php
}
else {
	$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");
	if ($_GET["mode"] == "MSGS")
		$db->query("USE haber; DELETE FROM messages; USE haber; ALTER TABLE messages AUTO_INCREMENT = 1; USE haber; UPDATE idtbl SET id=0;");
	else if ($_GET["mode"] == "ALL") {
		$db->query("USE haber; DELETE FROM messages; USE haber; ALTER TABLE messages AUTO_INCREMENT = 1; USE haber; UPDATE idtbl SET id=0;");
		$db->query("USE haber; DELETE FROM users; USE haber; ALTER TABLE users AUTO_INCREMENT = 1;");
		$db->query("USE haber; DELETE FROM keystbl; USE haber; ALTER TABLE keystbl AUTO_INCREMENT = 1;");
	}
	echo "All evidence destroyed";
}