<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

	require('vendor/autoload.php'); 			
	require('connect.php');
	
	use \Gumlet\ImageResize;	

	// Query Category list to dropdown menu.
	$query = "SELECT * FROM categories";
	$statement = $db->prepare($query);
	$statement->execute();
	$search_category = $statement->fetchAll();
	$category_count = count($search_category);

	function file_upload_path($orginal_filename){		
		return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR. basename($orginal_filename);
	}

	function file_is_allowed_image($tmp_path, $new_path){
		$allowed_file_extensions = ['jpg', 'png', 'gif', 'pdf'];
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

		$actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
		$actual_mime_type = mime_content_type($tmp_path);
	
		$file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);
       
        return $file_extension_is_valid && $mime_type_is_valid;
	}

	if(!isset($_SESSION['email'])){
		header("Location: login.php"); 
	}
	else{
		if(isset($_POST['update']) && isset($_POST['name']) && isset($_POST['country']) && isset($_POST['category'])&& isset($_POST['content']) && isset($_POST['id'])){
        	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        	$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
        	$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	      
        	$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);	
        	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        	$file = $_FILES['file']['name'];        	

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
					
			if(isset($_POST['delete_image'])){
				unlink($image_dir);		
				$image_dir = "";
			}	
			
			if(strlen($name) >= 1 && strlen($country) >= 1 && strlen($category) >= 1 && strlen($content) >= 1 && $id){	
				
	        	$query = "UPDATE athletes SET name = :name, country = :country, category = :category, content = :content, image_dir = :image_dir WHERE id = :id";
	        	$statement = $db->prepare($query);
	        	$statement->bindValue(':name', $name);        
	        	$statement->bindValue(':country', $country);
	        	$statement->bindValue(':category', $category);        
	        	$statement->bindValue(':content', $content); 
	        	$statement->bindValue(':image_dir', $image_dir);    
	        	$statement->bindValue(':id', $id, PDO::PARAM_INT);
	        	$statement->execute();
	        	header("Location: index.php"); 
	        	exit(); 
				}
			else{
				$errorMessage = "Your post has errors.";
			}    
		}
		else if(isset($_POST['delete']) && isset($_GET['id'])){
	    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	  		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	    	$query = "DELETE FROM athletes WHERE id = :id LIMIT 1";

	    	$statement = $db->prepare($query);
	    	$statement->bindValue(':id', $id, PDO::PARAM_INT);

	    	$statement->execute();   
	  
        	header("Location: index.php");
		}
    	else if(isset($_GET['id'])){
	    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	  		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	  		if($id){
		    	$query = "SELECT * FROM athletes WHERE id = :id LIMIT 1";

		    	$statement = $db->prepare($query);
		    	$statement->bindValue(':id', $id, PDO::PARAM_INT);
		    	$statement->execute();   
		    	$row = $statement->fetch();	
		    	$image_dir = $row['image_dir'];
		    	echo $image_dir;

		    	if(isset($_POST['delete_image'])){
		    		unlink($image_dir);
		    		$image_dir= "";
		    	}		      		   			
	  		}	    
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
	<title>Edit Athletes</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"> 		
 	</script>    
</head>
<body>
	<?php include('header.php'); ?>
	<main>
		<div class="container">
		<?php if(!isset($errorMessage) && isset($row)):?>
			<h2>Edit Athletes</h2>		
			<form method="post" enctype="multipart/form-data">		
				<input type="hidden" name="id" value="<?= $row['id'] ?>">
				<div class="form-group"	>
					<label for="name">Name:</label>
					<input id="name" class="form-control" name="name" value="<?= $row['name'] ?>"><br>
				</div>
				<div class="form-group"	>	
					<label for="country">Country:</label>
					<input id="country" class="form-control" name="country" value="<?= $row['country'] ?>"><br>
				</div>
				<div class="form-group"	>
					<label for="sport">Category:</label>			
					<select name="category" id="categories" class="form-control">					
					  	<option value="" disabled="">--Select Category--</option>  
						<?php if($category_count > 0): ?>
							<?php foreach($search_category as $r):?>		
							<option value="<?= $r['category_name'] ?>"><?= $r['category_name'] ?></option>
							<?php endforeach?>
						<?php endif ?>
					</select><br>				
				</div>
				<div class="form-group"	>
					<label for="content">Content:</label><br>
					<textarea id="content" name="content" rows="15" ><?= $row['content'] ?></textarea><br>
				</div>
				<?php if(!empty($row['image_dir']) && !isset($_POST['delete_image'])):?>
					<img src="<?=$row['image_dir']?>" alt="<?=$row['name']?>"><br><br>			
					<button type="submit" id="delete_image" name="delete_image" class="btn btn-primary">Delete Image</button><br><br>
				<?php endif ?>		
				<label for="file">Upload Image (Option):</label><br>
				<input type="hidden" name="oldfile" value="<?= $row['image_dir'] ?>">
				<input type="file" id="file" name="file"><br><br>
				<button type="submit" name="update" class="btn btn-primary">Update</button>
				<button type="submit" name="delete" class="btn btn-primary">Delete</button><br><br>				
			</form>
		<?php elseif(isset($errorMessage)):?>
			<h1><?=$errorMessage ?></h1><br>
			<a href="index.php">Return Home</a><br><br>
		<?PHP endif ?>	
		 </div>	

		<script>
            CKEDITOR.replace( 'content' );
        </script> 
	</main>

	<?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>