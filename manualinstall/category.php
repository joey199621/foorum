<?php require_once("common.php"); ?>

<?php
	$id = null;
	if(isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id']))
	{
		$id = $_GET['id'];
	}
	else {
		// todo consider a 404 page
		header("Location:index.php");
		die();
	}

	$stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
	$stmt->bindValue(":id", $id, PDO::PARAM_INT);
	$stmt->execute();
	$category = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!$category){
		header("Location:categories.php");
		die();
	}

	// get topics by last message date
	$stmt = $db->prepare("SELECT topic.*, 
		(SELECT MAX(message_date) FROM topic_message WHERE topic_id = topic.id
			) as m 


		FROM topic
		WHERE category_id = :category_id
		ORDER BY m DESC ");

	$stmt->bindValue(":category_id", $id, PDO::PARAM_INT);
	$stmt->execute();

	$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

	<div class="fluid">
		<a id="newTopicLink" href="newtopic.php">+ New topic in <?=$category["name_en-US"]?></a>
		<div id="topics">
		
			<?php 
				if($topics) {
					foreach ($topics as $topic) {
						?>
						<a href="topic.php?id=<?=$topic["id"]?>"><?=$topic["title"]?></a>
						<?php
					}
				}
				else {
					?>
					<p>No topic in this category</p>
					<?php
				}

			?>
		</div>
	</div>
	
</body>
</html>