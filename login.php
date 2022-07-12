<?php
	require('connect.php');
	session_start();

	if(isset($_POST['login'])){
		if(empty($_POST['email']) || empty($_POST['password'])){
			$error = "Empty Input";
		}
		else{
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);			
			
			$correctPwd = "";

			try{
				$query = "SELECT * FROM users WHERE email = :email";

				$statement = $db->prepare($query);	
					
				$statement->bindValue(':email', $email);			
	
				$statement ->execute();

				$row = $statement ->fetch();

				$correctPwd = $row['password'];				
			}
			catch(PDOException $e){
				$error = $e->getMessage();
			}
			
			if(password_verify($password, $row['password']) == true){
				$_SESSION['login'] = true;
				$_SESSION['email'] = $email;
				header("location: index.php");
			}
			else{
				$error="Wrong password.";
			}					
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	<main>	
		<h2>Login</h2>
		<form method="post">
			<label for="email">Email: </label>
			<input type="text" name="email" placeholder="email"><br>		
			<label for="password">Password: </label>
			<input type="password" name="password" placeholder="password"><br>
			<input type="submit" name="login" value="Login"><br>
			<?php if(isset($error)) :?>
				<p><?=$error ?></p>
			<?php endif ?>
		</form>
	</main>
	<?php include('footer.php'); ?>
</body>
</html>
