<?php
	require('connect.php');
	$query = "SELECT * FROM athletes ORDER BY edit_time DESC LIMIT 5";
	$statement = $db->prepare($query);
	$statement->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login In</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	<main>	
		<h2>Login In</h2>
		<form action="login.inc.php" method="post">
			<input type="text" name="name" placeholder="Username/Email"><br>
			<input type="password" name="password" placeholder="Password"><br>
			<input type="password" name="repassword" placeholder="Repeat password"><br>
			<input type="submit" name="signup" value="Login In"><br>	
		</form>
	</main>
	<?php include('footer.php'); ?>
</body>
</html>
