<?php
	$db = new mysqli('localhost', 'root', '', 'db2');
	if (mysqli_connect_errno()) {
		echo '<p> Error: Could not establish connection to database</p>';
		exit;
	}
?>