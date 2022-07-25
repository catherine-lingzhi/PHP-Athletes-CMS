<?php
	require('connect.php');
	session_start();

	if(isset($_POST['login'])){
		if(empty($_POST['email']) || empty($_POST['password'])){
			$error = "Empty Input, try again!";
		}
		else{
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		
			if(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
				try{
					$query = "SELECT * FROM users WHERE email = :email";

					$statement = $db->prepare($query);	
					
					$statement->bindValue(':email', $email);	
					$statement ->execute();

					$row = $statement ->fetch();

					$correctPwd = $row['password'];	
					$user_type=	$row['user_type'];

					if(password_verify($password, $correctPwd) == true){			
						$_SESSION['email'] = $email;
						$_SESSION['user_type'] = $user_type;				
						header("location: index.php");
					}
					else{
						$error="Wrong password, try again.";
					}			
				}
				catch(PDOException $e){
					$error = $e->getMessage();
				}		
	
			}
			else{
				$error="Invalid Email, try again.";
			}					
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login in</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>	
	<main>
	<div class="container">	
		<h2>Login</h2>
		<form method="post" >
		  <div class="form-group">
		    <label for="exampleInputEmail1">Email address</label>
		    <input type="email" name = "email" class="form-control col-6" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">		
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Password</label>
		    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
		  </div>  		 
		  <button type="submit" name="login" class="btn btn-primary">Login</button>
		<?php if(isset($error)) :?>
			<p><?=$error ?></p>
		<?php endif ?>			  
		</form>			
	</div>
	</main>
	<?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>
