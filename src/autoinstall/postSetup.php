<?php
	session_start();
	
	if(isset($_POST['dbhost']) && !empty($_POST['dbhost'])
	&& isset($_POST['dbuser']) && !empty($_POST['dbuser'])
	&& isset($_POST['dbpass']) && !empty($_POST['dbpass'])
	&& isset($_POST['adminpass']) && !empty($_POST['adminpass'])) {
		echo "Your Foorum is being created, please wait...";


		$db = new PDO('mysql:host='.$_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);

		$dbExists = false;
		$dbName = "foorum";
		$i = 1;

		do {
			try {
				$db->query("CREATE DATABASE $dbName");
				$dbExists = false;
			}
			catch(Exception $e) {
				if(str_contains($e->getMessage(), 'database exists')) {
					$dbName = "foorum".$i++;
					$dbExists = true;
				}
			}

		} while($dbExists);

		$db->query("USE $dbName");
		// db created, create tables
		$db->query("CREATE TABLE users(
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			pseudo VARCHAR(25) NOT NULL,
			email VARCHAR(255) NOT NULL,
			password VARCHAR(500) NOT NULL
		)");

		$db->query("CREATE TABLE categories(
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name_en-us` VARCHAR(255) NOT NULL,
			`description_en-US` TEXT
		)");

		$db->query("CREATE TABLE topic (
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				author INT NOT NULL,
				title VARCHAR(255) NOT NULL,
				lang VARCHAR(5) NOT NULL DEFAULT 'en-US',
				date_topic DATETIME DEFAULT CURRENT_TIMESTAMP,
				category_id INT NOT NULL,
				locked INT NOT NULL DEFAULT 0,
				solved INT NOT NULL DEFAULT 0,
				FOREIGN KEY (author) REFERENCES users(id) ON DELETE CASCADE,
				FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
			)");


		$db->query("CREATE TABLE topic_message (
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				author INT NOT NULL,
				message_date DATETIME DEFAULT CURRENT_TIMESTAMP,
				content TEXT NOT NULL,
				lang VARCHAR(5) DEFAULT 'en-US',
				topic_id INT NOT NULL,
				FOREIGN KEY (author) REFERENCES users(id) ON DELETE CASCADE,
				FOREIGN KEY (topic_id) REFERENCES topic(id) ON DELETE CASCADE
			)");


		$db->query("CREATE TABLE foorum_settings (
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name_en VARCHAR(255) NOT NULL, 
				description_en TEXT NOT NULL,
				name VARCHAR(100) NOT NULL
				
		)");


		$db->query("CREATE TABLE foorum_setting_value (
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				foorum_setting INT NOT NULL,
				value VARCHAR(255),
				FOREIGN KEY (foorum_setting) REFERENCES foorum_settings(id) ON DELETE CASCADE
				
			)");

		// add data (admin user)
		$stmt = $db->prepare("INSERT INTO users (pseudo, email, password) VALUES ('admin', '', :password)");
		$stmt->bindValue(":password", password_hash($_POST['adminpass'],PASSWORD_DEFAULT));
		$stmt->execute();

		$_SESSION['admin_id'] = $db->lastInsertId();


		// create config file
		$configFile = file_get_contents("foorum_config.php");
		$configFile = str_replace("{{DBHOST}}", $_POST['dbhost'], $configFile);
		$configFile = str_replace("{{DBNAME}}", $dbName, $configFile);
		$configFile = str_replace("{{DBUSER}}", $_POST['dbuser'], $configFile);
		$configFile = str_replace("{{DBPASS}}", $_POST['dbpass'], $configFile);

		file_put_contents("../config/foorum_config.php", $configFile);

		$_SESSION['first_login'] = true;

		header("Location:../admin");
		die();
		

	}



?>