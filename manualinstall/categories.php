<?php require_once("common.php"); ?>

<?php 
	$stmt = $db->prepare("SELECT * FROM categories ORDER BY 'name_en-US'");
	$stmt->execute();

	$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

	

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

	<div id="topics">
		
		<?php 
			if($categories) {
				foreach ($categories as $categorie) {
					?>
					<a href="#"><?=$categorie["name_en-US"]?></a>
					<?php
				}
			}

		?>
	</div>
</body>
</html>