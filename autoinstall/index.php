<?php 
	// this script will prompt for settings and setup automatically on your server
	

	// to auto install, first thing is setup the database.
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		.valid {
			color: green;
			font-weight: bold;
		}
		.invalid {
			color: red;
			font-weight: bold;
		}
	</style>
</head>
<body>	
	<p>Hello and welcome aboard! Thanks for choosing our open source solution.</p>
	<p>This setup will guide you flawlessly through the installation of your own Foorum.</p>
	<p>We only assume the following requirements: latest version of Apache, PHP and MySQL installed, and sufficient rights on the database user to create a database, create table, select, update and delete rows.</p>
	<p>If you see this screen, Apache and PHP requirements are satisfied.</p>
	<p>All we have to do is setup a database and an admin account. The rest will be managed via the admin panel.</p>

	<fieldset>
		<legend>Database</legend>
		<p>1. The database host. It is generally an IP address or a hostname. Let it to localhost if MySQL is installed locally.</p>
		<input onkeyup="disableSucceed(event)" id="dbhostInput" type="text" name="" placeholder="Database host" value="localhost">
		<p>2. The database user and password.</p>
		<input onkeyup="disableSucceed(event)" id="dbuserInput" type="text" name="" placeholder="MySQL user" value="" placeholder="MySQL user">
		<input onkeyup="disableSucceed(event)" id="dbpasswordInput" type="password" name="" placeholder="MySQL password" >

		<p>
			<button id="testConnectionBtn">Test connection</button>
			<button disabled id="createDbBtn">Create database</button>
			<p>Please test database connection</p>
		</p>

		<p id="dbErrorP"></p>

		<p>Required SQL privileges:</p>
		<p id="selectP">Select</p>
		<p id="insertP">Insert</p>
		<p id="updateP">Update</p>
		<p id="deleteP">Delete</p>
		<p id="createP">Create</p>
	</fieldset>


	<fieldset>
		<legend>Admin account</legend>
		<p>An admin account will be created. This is the super user account. You can create more admins later.</p>
		<p>Please create a password for the user 'admin':</p>
		<input onkeyup="checkPwd(event)" type="password" id="pwd1Input" name="pwd1" placeholder="Type a password">
		<input onkeyup="checkPwd(event)" type="password" id="pwd2Input" name="pwd2" placeholder="Confirm password">

		<p>Important: if you forget the password, you will need to tamper with the database. This is explained in the documentation.</p>
		
	</fieldset>
	<form >
		
		<button disabled id="lgBtn">Let's go</button>
	</form>

	<script type="text/javascript">
		function checkPwd(event) {
			if(testSucceed && pwd1Input.value && pwd2Input.value && pwd1Input.value == pwd2Input.value) lgBtn.disabled = false
			else lgBtn.disabled = true
		}
		function disableSucceed(event) {
			createDbBtn.disabled = true
			testSucceed = false
			lgBtn.disabled = true
			checkPwd(null)
		}
		testSucceed = false;

		testConnectionBtn.addEventListener("click", function() {

			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "ajax/testConnection.php", true);
			xhttp.onreadystatechange = function() {
			   if (this.readyState == 4 && this.status == 200) {

			   	selectP.classList.remove("invalid")
				insertP.classList.remove("invalid")
				updateP.classList.remove("invalid")
				deleteP.classList.remove("invalid")
				createP.classList.remove("invalid")

				selectP.classList.remove("valid")
				insertP.classList.remove("valid")
				updateP.classList.remove("valid")
				deleteP.classList.remove("valid")
				createP.classList.remove("valid")

			      // Response
			      var response = this.responseText; 
			      ret = JSON.parse(response)
			      // alert(ret.ret)
			      if(ret.ret == "nouser")
			      {
			      	dbErrorP.innerText = "The specified MySQL user is not found"
			      	disableSucceed(null)
			      }
			      else if(ret.ret == "nopass")
			      {
			      	dbErrorP.innerText = "No password"
					disableSucceed(null)

			      }
			      else if(ret.ret == "badhostname")
			      {
			      	dbErrorP.innerText = "Bad IP or hostname"			      	
			      	disableSucceed(null)
			      }
			      else if(ret.ret == "badpass")
			      {
			      	dbErrorP.innerText = "Wrong password"
			      	disableSucceed(null)
			      }
			      else if(ret.ret == "ok") {
			      	// ok, check privileges
			      	if(
			      		ret.privs.select == 'Y' 
					&& ret.privs.insert == 'Y'
					&& ret.privs.update == 'Y'
					&& ret.privs.delete == 'Y'
					&& ret.privs.create == 'Y') {
						selectP.classList.add("valid")
						insertP.classList.add("valid")
						updateP.classList.add("valid")
						deleteP.classList.add("valid")
						createP.classList.add("valid")


			      		dbErrorP.innerText = "Connection success!"
			      		createDbBtn.disabled = false
			      		testSucceed = true
			      		checkPwd(null)
					}
					else {
						disableSucceed(null)

						// select
						if(ret.privs.select == 'Y') {
							selectP.classList.add("valid")
						} 
						else {
							selectP.classList.add("invalid")
						} 

						// insert
						if(ret.privs.insert == 'Y') {
							insertP.classList.add("valid")
						} 
						else {

							insertP.classList.add("invalid")
						}

						// update
						if(ret.privs.update == 'Y'){
							updateP.classList.add("valid")
						} 
						else {

							updateP.classList.add("invalid")
						}


						// delete
						if(ret.privs.delete == 'Y') {
							deleteP.classList.add("valid")

						} 
						else {

							deleteP.classList.add("invalid")
						}

						// create
						if(ret.privs.create == 'Y'){
							createP.classList.add("valid")
						} 
						else {

							createP.classList.add("invalid")
						}

					}



			      }


			   }
			};
			var data = {dbhost:dbhostInput.value,dbuser:dbuserInput.value,dbpassword:dbpasswordInput.value};
			xhttp.send(JSON.stringify(data));
			// xhttp.send();
		})
	</script>
</body>
</html>