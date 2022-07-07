<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Categories</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	<main>
		<form class="category">
			<label for="categories">Categories:</label>
			<select name="categories" id="categories">
			  <option value="sport">Sport</option>
			  <option value="country">country</option>
			</select> 
		</form>
	</main>
	<?php include('footer.php'); ?>
</body>
</html>
