<?php
	require_once("../foorum_config.php");
	session_start();

	// todo, only on maintenance mode
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	// todo change hard coded values here
	// todo: default fetch assoc
	$db = new PDO('mysql:host=localhost;dbname=foorum', "root", "root");

	// get Foorum settings
	$stmt = $db->prepare("SELECT * FROM foorum_setting_value
						INNER JOIN foorum_settings
						ON foorum_settings.id = foorum_setting_id");
	$stmt->execute();

	$settings = [];

	while($setting = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$settings[ $setting["name"] ] = $setting;
	}

	if(isset($settings["theme"]) && file_exists("css/themes/".$settings["theme"]["value"]))
		define("CSS_ROOT", "css/themes/".$settings["theme"]["value"]);
	else
		define("CSS_ROOT", "css/themes/default");

	if(isset($settings["lang"]) && file_exists("langs/".$settings["lang"]["value"]))
		define("LANG_ROOT", "langs/".$settings["lang"]["value"]);
	else
		define("LANG_ROOT", "langs/en-US");

	include(LANG_ROOT."/main.php");

	function loggedIn() {
		return isset($_SESSION['user_id']);
	}

?>