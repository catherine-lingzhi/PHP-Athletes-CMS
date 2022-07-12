<?php
	require('connect.php');

	if(isset($_POST['signup'])){
		if(empty($_POST['email']) || empty($_POST['password'])){
			$error = "Empty Input";
		}
		else{
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			if(!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
				$error = "Invalid Email";
			}
			else{
				$query = "INSERT INTO users (email, password) VALUES (:email, :password)";

				$statement = $db->prepare($query);	
				
				$statement->bindValue(':email', $email);
				$statement->bindValue(':password', $hashedPassword);
	
				$statement ->execute();
				header("location: index.php");
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	<main>
		<h2>Sign Up</h2>
		<form method="post">	
			<label for="email">Email: </label>
			<input type="text" name="email" placeholder="email"><br>		
			<label for="password">Password: </label>
			<input type="password" name="password" placeholder="password"><br>		
			<input type="submit" name="signup" value="Sign Up"><br>
			<?php if(isset($error)) :?>
				<p><?=$error ?></p>
			<?php endif ?>
		</form>
	</main>
	<?php include('footer.php'); ?>
</body>
</html>