<?php require_once("common.php"); ?>

<?php 
	// get topics by last message date
	$stmt = $db->prepare("SELECT topic.*, 
		(SELECT MAX(message_date) FROM topic_message WHERE topic_id = topic.id
			) as m 


		FROM topic
		ORDER BY m DESC ");
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
		<a id="newTopicLink" href="newtopic.php">+ New topic</a>
		<div id="topics">
		
			<?php 
				if($topics) {
					foreach ($topics as $topic) {
						?>
						<a href="topic.php?id=<?=$topic["id"]?>"><?=$topic["title"]?></a>
						<?php
					}
				}

			?>
		</div>
	</div>
	
</body>
</html>