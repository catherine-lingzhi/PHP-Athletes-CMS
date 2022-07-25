<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-24

------------------->
<?php
	session_start();
			
	require('connect.php');	
	
	if(!isset($_SESSION['email'])){
		header("Location: login.php"); 
	}
	else{
		if(isset($_POST['update']) && isset($_POST['email']) && isset($_POST['user_type']) && isset($_POST['password']) &&  isset($_POST['id'])){      	      			
			if(!empty($_POST['email']) && !empty($_POST['user_type']) && !empty($_POST['password'])){
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        		$user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

				$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);	     		
        		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); 

        		if(trim($user_type) !== "admin" && trim($user_type) !== "user"){
        			$errorMessage = "User Type must be user or admin.";
        		}

				if($email && $id){
	        		$query = "UPDATE users SET email = :email, password = :password, user_type = :user_type WHERE user_id = :user_id";

	        		$statement = $db->prepare($query);
	        		$statement->bindValue(':email', $email); 
	        		$statement->bindValue(':user_type', $user_type);
	        		$statement->bindValue(':password', $hashedPassword);    
	        		$statement->bindValue(':user_id', $id, PDO::PARAM_INT);
	        		$statement->execute();
	        		header("Location: user.php"); 
	        		exit(); 
				}
				else{
					$errorMessage = "Invalid Email.";
				}    				
			}
			else{
				$errorMessage = "Empty Input.";
			}			
		}
		else if(isset($_POST['delete']) && isset($_GET['id'])){
	    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	  		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	    	$query = "DELETE FROM users WHERE user_id = :user_id LIMIT 1";

	    	$statement = $db->prepare($query);
	    	$statement->bindValue(':user_id', $id, PDO::PARAM_INT);

	    	$statement->execute();   
	  
        	header("Location: user.php");
		}
    	else if(isset($_GET['id'])){
	    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	  		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	    	$query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";

	    	$statement = $db->prepare($query);
	    	$statement->bindValue(':user_id', $id, PDO::PARAM_INT);

	    	$statement->execute();   
	    	$row = $statement->fetch();	
		}
		else{
			$id = false;
		}    		
	}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Users</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"> 		
 	</script>    
</head>
<body>
	<?php include('header.php'); ?>
	<?php if(!isset($errorMessage) && $id):?>
	<main>	
		<div class="container">
			<h2>Edit Users</h2>	
			<form method="post">							
				<input type="hidden" name="id" value="<?= $row['user_id'] ?>">
				<div class="form-group">
					<label for="user_type">User Type</label>
					<input class="form-control" name="user_type" value="<?= $row['user_type'] ?>"><br>
				</div>

				<div class="form-group">
					<label for="email">Email</label>
					<input class="form-control" type="email" name="email" value="<?= $row['email'] ?>"><br>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" name="password"><br>
				</div>				
				<button type="submit" id="update" name="update" class="btn btn-primary">Update</button>
				<button type="submit" id="delete" name="delete" class="btn btn-primary">Delete</button>	<br><br>
						
			</form>
		</div>
	</main>
	<?php else:?>
	<h1><?=$errorMessage ?></h1><br>
	<a href="index.php">Return Home</a><br><br>
	<?PHP endif ?>
	<?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>	
</body>
</html>