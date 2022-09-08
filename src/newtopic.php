<?php require_once("common.php"); ?>

<?php 
	
	if(!loggedIn()) {
		header("Location:index.php");
		die();
	}

	if(isset($_POST['content']) && !empty($_POST['content'])
		&& isset($_POST['category_id']) && !empty($_POST['category_id'])
		&& isset($_POST['title']) && !empty($_POST['title'])
	&& isset($_POST["csrf"]) && isset($_SESSION["token"]) && $_POST['csrf'] == $_SESSION['token'])
	{

		$stmt = $db->prepare("INSERT INTO topic (category_id, author, title, lang) VALUES (:category_id, :author, :title, 'en-US')");
		$stmt->bindValue(":category_id", $_POST["category_id"], PDO::PARAM_INT);
		$stmt->bindValue(":author", $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(":title", $_POST["title"]);

		try {
			$stmt->execute();

			$topic_id = $db->lastInsertId();

			$stmt = $db->prepare("INSERT INTO topic_message (topic_id, author, content, lang)
				VALUES (:topic_id, :author, :content, 'en-US')");
			$stmt->bindValue(":topic_id", $topic_id, PDO::PARAM_INT);
			$stmt->bindValue(":author", $_SESSION["user_id"], PDO::PARAM_INT);
			$stmt->bindValue(":content", $_POST["content"]);

			$stmt->execute();

			header("Location:topic.php?id=".$topic_id);
			die();

		}
		catch(Exception $e)
		{
			// probably an error with category input
			// todo manage the error, display it
		}

		

		
	}

	$stmt = $db->prepare("SELECT * FROM categories");
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	

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
	
</head>
<body>
	<?php include("includes/header.php"); ?>

	<div class="fluid" id="topic">
		
		<form method="POST" action="newtopic.php" id="topicReply">
			<select name="category_id">
				<?php 
					if($categories)
						foreach($categories as $c)
						{
							?>
							<option value="<?=$c["id"]?>"><?=$c["name_en-US"]?></option>
							<?php
						}

				?>
			</select>
			<input placeholder="Title" type="text" name="title">
			<textarea placeholder="Message" name="content"></textarea>
			<button type="submit">send</button>
			<input type="hidden" name="csrf" value="<?=$_SESSION["token"]?>">
		</form>
		
		
	</div>

	
	
</body>
</html>