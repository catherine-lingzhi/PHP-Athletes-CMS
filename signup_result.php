<?php
	require('connect.php');
	require('authenticate.php');

	if(isset($_POST['signup'])){	
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$repeatPwd = filter_input(INPUT_POST, 'repeatPwd', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if(empty($email) || empty($username) || empty($password) || empty($repeatPwd)){
			$error = "Input is Empty.";
		}		

		if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
			$error = "Username is invalid.";
		}

		if(!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
			$error = "Emali is invalid.";
		}	

		if($password !== $repeatPwd){
			$error = "Password is not match.";
		}

	    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

		$statement = $db->prepare($query);
	
		$statement->bindValue(':username', $username);
		$statement->bindValue(':email', $email);
		$statement->bindValue(':password', $password);
	
		$statement ->execute();
		header("location: index.php");
	}
	else{
		header("location: signup.php");
	}

?>