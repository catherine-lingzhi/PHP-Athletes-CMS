<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	require('connect.php');
	require('function.php');

	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();
	$rows = $statement->fetchAll();

	foreach($rows as $r){
		$userArray[] = strtolower($r['email']);
	}

	if(isset($_POST['signup'])){
		if(empty($_POST['email']) || empty($_POST['password'])){
			$error = "Empty Input";
		}
		else{
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$repeat_pwd = filter_input(INPUT_POST, 'repeat_pwd', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$isRepeatedEmail = in_array(strtolower($email), $userArray);

			if(!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) || $isRepeatedEmail){
				$error = "Invalid Email";
			}
			else if($password !== $repeat_pwd){
				$error = "Pasword are not matched.";
			}
			else{
				$query = "INSERT INTO users (email, password, user_type) VALUES (:email, :password, :user_type)";

				$statement = $db->prepare($query);	
				
				$statement->bindValue(':email', $email);
				$statement->bindValue(':password', $hashedPassword);
				$statement->bindValue(':user_type', $user_type);
				$statement ->execute();
				alert('You signed up successfully, please login you account!');
				
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	<main>
		<div class="container">
		<h2>Sign Up</h2>
		<form method="post">  
		  <div class="form-group">
		    <label for="user_type">User Type: </label>
		    <select class="form-control" name="user_type" id="user_type">		    
		      <option value="user">User</option>
		      <option value="admin">Admin</option>		 
		    </select><br>
		  </div> 
		  <div class="form-group">
		    <label for="email">Email address</label>
		    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"><br>
		  </div> 
		  <div class="form-group">
		    <label for="password">Password: </label>
			<input type="password" id="password" class="form-control" name="password"><br>
		  </div>
		  <div class="form-group">
		    <label for="repeat_pwdrepeat_pwd">Repeat Password: </label>
			<input type="password" name="repeat_pwd" id="repeat_pwdrepeat_pwd" class="form-control"><br>
		  </div>
		  <?php if(isset($error)) :?>
		  	<div class="error">
				<p><?=$error ?></p>
			</div>
		  <?php endif ?>
		  <button type="submit" name="signup" class="btn btn-primary">Sign Up</button><br><br>
		</form>		
	</div>
	</main>
	<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>