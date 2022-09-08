<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	header('Content-Type: application/json; charset=utf-8');

	$data = json_decode(file_get_contents("php://input"), true);

	$privs = [];

	$privs["select"] = "N";
	$privs["insert"] = "N";
	$privs["update"] = "N";
	$privs["delete"] = "N";
	$privs["create"] = "N";

	try {

		$conn = new PDO("mysql:host=".$data["dbhost"], $data["dbuser"], $data["dbpassword"]);
		
		$stmt = $conn->prepare("SELECT * FROM mysql.user WHERE USER LIKE :user;");
		$stmt->bindValue(":user", $data["dbuser"]);
		$stmt->execute();

		$privileges = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$privs["select"] = $privileges[0]["Select_priv"];
		$privs["insert"] = $privileges[0]["Insert_priv"];
		$privs["update"] = $privileges[0]["Update_priv"];
		$privs["delete"] = $privileges[0]["Delete_priv"];
		$privs["create"] = $privileges[0]["Create_priv"];
		
		

		$ret = "ok";



	}
	catch(Exception $e) {
		
		if(str_contains($e->getMessage(), "Name or service not known") || str_contains($e->getMessage(), "No route to host"))
			$ret = "badhostname";
		else if(empty($data["dbpassword"]))
			$ret = "nopass";

		else if(str_contains($e->getMessage(), "(using password: YES)"))
			$ret = "nouser";
		else if(str_contains($e->getMessage(), "Access denied"))
			$ret = "badpass";
		else if(str_contains($e->getMessage(), "SELECT command denied")) {
			$privs["select"] = "N";
			$ret = "cantselect";
		}

		// print_r($e->getMessage());
	}
	

	echo json_encode(["ret" => $ret, "privs"=>$privs]);
?>