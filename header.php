<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
	<header> 
		<div class="container">
			<div class="login_bar d-flex justify-content-start">
	      		<ul class="nav">               
	      		<?php if(!isset($_SESSION['email'])) :?>
					<li class="nav-item"><a href="login.php">Login </a></li>
					<li class="nav-item"><a href="signup.php">Sign Up</a></li>	
				<?php endif ?> 		    		 
					<li class="nav-item"><a href="user.php">Users Info</a></li>
					<?php if(isset($_SESSION['email'])) :?>
					<li class="nav-item"><a href="logout.php">Login Out</a></li>	
					<li class="nav-item user_email">Hi,<?= substr($_SESSION['email'], 0, strpos($_SESSION['email'], '@')) ?> !</li>	
				<?php endif ?>      
	    		</ul>
	    	</div>	 			
		</div>

		<div class="container-fluid heading">						
        	<h1 class="text-center">High Athletes Soprt Agent</h1>      
    	</div>
    </header>

	<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
		<div class="container">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
		  	<span class="navbar-toggler-icon"></span>
			</button>		
			<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
		  		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
		    		<li class="nav-item">
		      			<a class="nav-link active" aria-current="page" href="index.php">Home</a>
		    		</li>
		    		<li class="nav-item">
		      			<a class="nav-link" href="post.php">Post Athletes</a>
		    		</li>
		    		<li class="nav-item">
		      			<a class="nav-link" href="searchcategory.php">Categories</a>
		    		</li>
		    		<li class="nav-item">
		      			<a class="nav-link" href="categories.php">Edit Categories</a>
		    		</li>	    	
		  		</ul>
		  
				<form class="d-flex" role="search" method="post" action="search.php">
					<input class="form-control me-2" name="search" placeholder="Search" aria-label="Search">							
					<button class="btn btn-outline-success" type="submit" name="submit_search">Search</button> 
				</form>
			</div>
		</div>
	</nav>
		
	
