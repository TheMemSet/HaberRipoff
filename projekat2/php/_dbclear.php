<?php
if (!isset($_GET["password"]) || $_GET["password"] !== "carrots") {
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL /php/dbclear.php was not found on this server.</p>
<hr>
<address>Apache/2.4.9 (Win64) PHP/5.5.12 Server at localhost Port 80</address>
</body></html>
<?php
}
else {
	$db = new PDO("mysql:host=localhost;dbname=haber", "root", "");
	$db->query("USE haber; DELETE FROM messages; USE haber; ALTER TABLE messages AUTO_INCREMENT = 1; USE haber; UPDATE idtbl SET id=0;");
	echo "All evidence destroyed";
}