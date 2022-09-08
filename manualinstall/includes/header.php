<header>
	<div id="headerTop">
		<img id="logo" src="assets/logo.png">
		<?php 
			if(loggedIn()) {
				?>
				<div>
					<a href="#">Profile</a>
					<a href="logout.php">logout</a>
				</div>
					
				<?php
			}
			else {
				?>
					<div>
						<a href="login.php">Login</a>
						<a href="register.php">Register</a>
					</div>
				<?php
			}
		?>
		
	</div>
	<div id="headerBottom">
		<div>
			<a href="index.php">Home</a>
			<a href="categories.php">Categories</a>
		</div>
		<div id="search">
			<input type="text" name="q">
			<button type="submit">Search</button>
		</div>
	</div>
	
</header>