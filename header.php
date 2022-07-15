	<header>     
		<h1>Athletes Content Management System</h1>
	</header>
	<nav>
		<ul>
	    	<li><a href="index.php">Home</a></li>
	    	<li><a href="post.php">Post Athletes</a></li>
	    	<li><a href="categories.php">Categories</a></li>
	    	<?php if(!isset($_SESSION['email'])) :?>
				<li><a href="login.php">Login</a></li>
				<li><a href="signup.php">Sign Up</a></li>		
			<?php endif ?>	    	
	    	<li><a href="logout.php">Login Out</a></li>	    	 
	    	<?php if(isset($_SESSION['email'])) :?>
				<li>Hi, <?= $_SESSION['email'] ?>!</li>	
			<?php endif ?>	 	    	     
		</ul>
	</nav>
