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


	// add a reply. need to be logged in

	if(loggedIn() && !$topic["locked"] && isset($_POST['reply']) && !empty($_POST['reply'])
	&& isset($_POST["csrf"]) && isset($_SESSION["token"]) && $_POST['csrf'] == $_SESSION['token'])
	{
		$stmt = $db->prepare("INSERT INTO topic_message (topic_id, author, content, lang) VALUES (:topic_id, :author, :content, 'en-US')");
		$stmt->bindValue(":topic_id", $id, PDO::PARAM_INT);
		$stmt->bindValue(":author", $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(":content", $_POST['reply']);
		$stmt->execute();

		// prevent resend data, redirect to last page
		$lastPage = ceil(($count+1) / MESSAGES_PER_PAGE);

		header("Location:topic.php?id=".$id."&page=".$lastPage);
		die();
	}

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
		<div id="topicTitle">
			<h1><?=$topic["title"]?></h1>
			<div>
				<p>By User</p>
				<p>At 1h03</p>
			</div>
		</div>
		<?php 
			foreach($messages as $key => $message)
			{
				?>
				<div class="topicMessage <?php if($key % 2 <> 0) echo "even"; ?>">
					<div class="topicMessageInfos">
						<p>By User<p>
						<p>At this time</p>
					</div>
					<div class="topicMessageMain">
						<?=$message["content"]?>
					</div>

					
				</div>
				<?php
			}
		?>

		<form method="POST" action="topic.php?id=<?=$id?>" id="topicReply">
			<textarea name="reply"></textarea>
			<button type="submit">send</button>
			<input type="hidden" name="csrf" value="<?=$_SESSION["token"]?>">
		</form>
	</div>

	
	
</body>
</html>