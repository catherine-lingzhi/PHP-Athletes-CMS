<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

	require('vendor/autoload.php'); 
	require('connect.php');
	require('function.php');

	use \Gumlet\ImageResize;

	$query = "SELECT * FROM categories";
	$statement = $db->prepare($query);
	$statement->execute();
	$search_category = $statement->fetchAll();
	$category_count = count($search_category);

	if(isset($_POST['submit'])){
		if(!empty($_POST['name']) && !empty($_POST['country']) && !empty($_POST['category']) && !empty($_POST['content'])){
    		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    		$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);   
    		$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);  
    		$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		$email = $_SESSION['email'];	    	
    		$image_dir = "";   	
    		$slug = slug($name);
    		if(strlen($name) >= 1 && strlen($country) >= 1 && strlen($category) >= 1 && strlen($content) >= 1){
				if(isset($_FILES['file']) && ($_FILES['file']['error'] === 0)){
					$filename = $_FILES['file']['name'];
					$tmp_path = $_FILES['file']['tmp_name'];
					$new_path = file_upload_path($filename);

					if(file_is_allowed_image($tmp_path, $new_path)){
					move_uploaded_file($tmp_path, $new_path);
						$file_extension = pathinfo($new_path, PATHINFO_EXTENSION);

						if($file_extension != "pdf"){
  							$basename = basename($new_path);
							$orginal_source = "uploads/$basename";
							$image_dir = substr($orginal_source, 0, strpos($orginal_source, ".")) . "_medium." . $file_extension; 		
	
							$image = new ImageResize($orginal_source); 
							$image->resize(200, 160);
    						$image->save($image_dir);	
    						unlink($orginal_source);					   
						}						
					}
				}

		    	$query = "INSERT INTO athletes (name, country, category, content, image_dir, email) VALUES (:name, :country, :category, :content, :image_dir, :email)";
		    	$statement = $db->prepare($query);

		    	$statement->bindValue(':name', $name);
		    	$statement->bindValue(':country', $country);
		    	$statement->bindValue(':category', $category);
		    	$statement->bindValue(':content', $content);
		    	$statement->bindValue(':image_dir', $image_dir);
		    	$statement->bindValue(':email', $email);			    	 
		    	$statement ->execute();
		    	header("Location: index.php"); 
    		}	  
		}
		else{
			$errorMessage = "Empty Input!";
		}
	} 		

	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CMS Project</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"> 		
 	</script>    
</head>
<body>
	<?php include('header.php'); ?>
	<main>
		<div class="container">	
		<?php if(isset($_SESSION['email'])):?>	
			<h2>Post Athletes</h2>
			<form method="post" enctype="multipart/form-data">
				<div class="form-group"	>
					<label for="name">Name:</label>
					<input type="text" id="name" class="form-control" name="name"><br>
				</div>
				<div class="form-group">
					<label for="country">Country:</label>
					<input type="text" id="country" class="form-control" name="country"><br>
				</div>	
				<div class="form-group">
					<label for="categories">Category:</label>
					<select name="category" id="categories" class="form-control">			
					  	<option value="" disabled="">--Select Category--</option>  
						<?php if($category_count > 0): ?>
							<?php foreach($search_category as $r):?>		
							<option value="<?= $r['category_name'] ?>"><?= $r['category_name'] ?></option>
							<?php endforeach?>
						<?php endif ?>
					</select><br>			
				</div>	
				<div class="form-group">
					<label for="content">Content:</label>
					<textarea id="content" name="content" class="form-control" placeholder="Simple Introduction"></textarea><br>
				</div>
				<div class="form-group">
					<label for="file">Upload Image (Option):</label>
					<input type="file" id="file" name="file" class="form-control"><br>
				</div>
				<?php if(isset($errorMessage)):?>
				<p class="error"><?=$errorMessage ?></p><br>			
				<?PHP endif ?>
				<button type="submit" name="submit" class="btn btn-primary">Post Athletes</button><br><br>		
			</form>
		
		<?php else:?>
			<div class="error">
				<p>Only login user and admin can post new athletes, please <a href="login.php">login</a> !</p>	
			</div>		
		<?php endif?>	
		</div>
				
		<script>
	      CKEDITOR.replace( 'content' );
	    </script>	
	</main>

	<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>