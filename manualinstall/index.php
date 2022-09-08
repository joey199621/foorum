<?php require_once("common.php"); ?>

<?php 
	$stmt = $db->prepare("SELECT * FROM topic ORDER BY date_topic DESC");
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