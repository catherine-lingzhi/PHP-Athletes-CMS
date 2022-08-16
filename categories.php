<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

	require('connect.php');
	require('function.php');
	
	$query = "SELECT * FROM categories";
	$statement = $db->prepare($query);
	$statement->execute();
	$row = $statement->fetchAll();
	$count = count($row);

	foreach($row as $r){
		$categoryArray[] = strtolower($r['category_name']);
	}

	if(isset($_POST['add_category'])){
		if(!empty($_POST['new_cateogry'])){
			$category_name = filter_input(INPUT_POST, 'new_cateogry', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
			$isValidCategory = in_array(strtolower(trim($category_name)), $categoryArray);
		
			if(!$isValidCategory){
				$query = "INSERT INTO categories (category_name) VALUES (:category_name)";
				$statement = $db->prepare($query);
				$statement->bindValue(':category_name', $category_name);	
				$statement ->execute();
				header("Location: categories.php");				
			}
			else{
				$error = "Cateogry existed, try again!";
			}
 
		}
		else{
			$error = "Empty input";
		}	
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Categories</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	
	<?php include('header.php'); ?>
	<main>		
		<div class="container">
		<?php if(isset($_SESSION['email'])):?>
			<h2>Catogries</h2>
			<ol>
			<?php if($count > 0): ?>
				<?php foreach($row as $r):?>			
					<li><?= $r['category_name'] ?> - <a href="editcategory.php?category-id=<?= $r['category_id'] ?>&category=<?= slug($r['category_name']) ?>"> edit</a></li>
				<?php endforeach?>
			<?php endif ?>
			</ol>	

			<form method = "post">
				<div class="form-group">
					<input type="text" name="new_cateogry" class="form-control" placeholder="Type a new category"><br>
					<?php if(isset($error)):?>
						<p><?=$error ?></p><br>
					<?php endif?>	
					<button type="submit" name="add_category" class="btn btn-primary">Add Category</button><br><br><br>
				</div>			
			</form>				
		<?php else:?>
			<div class="error">
				<p>Only login user and admin can access cateogries page, please <a href="login.php">login</a>!</p>
			</div>		
		<?php endif?>		
		</div>
	</main>
	<?php include('footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
