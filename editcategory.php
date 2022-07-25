<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();
			
	require('connect.php');	
	
	if(!isset($_SESSION['email'])){
		header("Location: login.php"); 
	}
	else{
		if(isset($_POST['update']) && isset($_POST['category_name']) && isset($_POST['id'])){
        	$category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);       		
        	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);        
		
			if(strlen($category_name) > 0){
	        	$query = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";

	        	$statement = $db->prepare($query);
	        	$statement->bindValue(':category_name', $category_name);     
	        	$statement->bindValue(':category_id', $id, PDO::PARAM_INT);
	        	$statement->execute();
	        	header("Location: categories.php"); 
	        	exit(); 
				}
			else{
				$errorMessage = "Your categories has errors.";
			}    
		}
		else if(isset($_POST['delete']) && isset($_GET['category_id'])){
	    	$id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);
	  		$id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

	    	$query = "DELETE FROM categories WHERE category_id = :category_id LIMIT 1";

	    	$statement = $db->prepare($query);
	    	$statement->bindValue(':category_id', $id, PDO::PARAM_INT);

	    	$statement->execute();   
	  
        	header("Location: index.php");
		}
    	else if(isset($_GET['category_id'])){
	    	$id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);
	  		$id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

	    	$query = "SELECT * FROM categories WHERE category_id = :category_id LIMIT 1";

	    	$statement = $db->prepare($query);
	    	$statement->bindValue(':category_id', $id, PDO::PARAM_INT);

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
	<title>Edit Categories</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"> 		
 	</script>    
</head>
<body>
<?php if(!isset($errorMessage) && $id):?>
	<?php include('header.php'); ?>

	<main>	
		<div class="container">
			<h2>Edit Categories</h2>	
			<form method="post">					
				<input type="hidden" name="id" value="<?= $row['category_id'] ?>">
				<div class="form-group">
					<label for="category_name">Category Name</label>
					<input class="form-control" id="category_name" name="category_name" value="<?= $row['category_name'] ?>"><br>
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