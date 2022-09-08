<?php require_once("common.php"); ?>
<?php 
	include(LANG_ROOT."/login.php");

	

	if(isset($_POST['email']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
	&& isset($_POST["password"]) && !empty($_POST["password"])
	&& isset($_POST["csrf"]) && isset($_SESSION['token']) && $_POST['csrf'] == $_SESSION['token'])
	{
		$stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
		$stmt->bindValue(":email", $_POST['email']);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if($user && password_verify($_POST["password"], $user["password"])) {
			$_SESSION["user_id"] = $user["id"];
			header("Location:index.php");
			die();
		}
		else {
			// wrong credentials, set session and refresh (to prevent resend data if user refreshes page)
			$_SESSION['loginError'] = true;
			header("Location:login.php");
			die();
		}
	}

	$_SESSION['token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="<?=CSS_ROOT?>/main.css">
	<link rel="stylesheet" type="text/css" href="<?=CSS_ROOT?>/layout.css">
	<link rel="stylesheet" type="text/css" href="<?=CSS_ROOT?>/color.css">
	<link rel="stylesheet" type="text/css" href="<?=CSS_ROOT?>/login.css">
	
</head>
<body>
	<?php include("includes/header.php"); ?>

	<form id="loginForm" method="POST" action="login.php">
		<?php 
			if(isset($_SESSION['loginError'])){
				?>
				<p>Credentials error</p>
				<?php
				unset($_SESSION['loginError']);
			}
		?>
		<input placeholder="Email" type="email" name="email">
		<input placeholder="Password" type="password" name="password">
		<!-- <button type="submit"><?=L_LOGIN?></button> -->
		<button type="submit">Login</button>
		<input type="hidden" name="csrf" value="<?=$_SESSION["token"]?>">
	</form>
</body>
</html>