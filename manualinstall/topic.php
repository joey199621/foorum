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


	// get topic
	$stmt = $db->prepare("SELECT * FROM topic WHERE id = :id");
	$stmt->bindValue(":id", $id, PDO::PARAM_INT);
	$stmt->execute();
	$topic = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!$topic) {
		header("Location:index.php");
		die();
	}

	// count messages on this topic
	$stmt = $db->prepare("SELECT count(*) c FROM topic_message WHERE topic_id = :id");
	$stmt->bindValue(":id", $id, PDO::PARAM_INT);
	$stmt->execute();

	$count = $stmt->fetch(PDO::FETCH_ASSOC)["c"];

	$page = 1;
	
	if(isset($_GET["page"]) && !empty($_GET["page"]) && ctype_digit($_GET["page"]))
	{
		$page = $_GET["page"];
	}
	$offset = $page * MESSAGES_PER_PAGE - MESSAGES_PER_PAGE;
	
	// get messages
	$stmt = $db->prepare("SELECT * FROM topic_message WHERE topic_id = :topic_id ORDER BY message_date LIMIT :l OFFSET :o");
	$stmt->bindValue(":topic_id", $id, PDO::PARAM_INT);
	$stmt->bindValue(":l", MESSAGES_PER_PAGE, PDO::PARAM_INT);
	$stmt->bindValue(":o", $offset, PDO::PARAM_INT);
	$stmt->execute();

	$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if(!$messages && $page > 1) {
		header("Location:topic.php?id=".$id);
		die();
	}
	else if(!$messages) {
		header("Location:index.php");
		die();
	}
	// print_r($messages);
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
		<h1><?=$topic["title"]?></h1>
		<?php 
			foreach($messages as $message)
			{
				?>
				<p><?=$message["content"]?></p>
				<?php
			}
		?>

	</div>

	
	
</body>
</html>