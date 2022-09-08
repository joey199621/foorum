<?php 
	session_start();
	if(!isset($_SESSION['admin_id'])) {
		header("Location:login.php");
		die();
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	Hello, admin
</body>
</html>