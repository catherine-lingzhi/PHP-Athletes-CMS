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

	function slug($string){
		$string = preg_replace('~[^\\pL\d]+~u', '-', $text);
		$string = trim($string, '-');
		$string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
		$string = strtolower($string);
		$string = preg_replace('~[^-\w]+~', '', $string);

  		if (empty($string))
  		{
    		return 'n-a';
  		}

  		return $string;
	}
	
	
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
		if(isset($_POST['submit'])){
			if(!empty($_POST['name']) && !empty($_POST['country']) && !empty($_POST['category']) && !empty($_POST['content'])){
	    		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
	    		$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);   
	    		$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);  
	    		$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	    		$email = $_SESSION['email'];
	    		$athlete_slug = slug($name);
	    		$image_dir = "";

	    		if(strlen($name) >= 1 && strlen($country) >= 1 && strlen($sport) >= 1 && strlen($content) >= 1){
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
								$image->resize(400, 300);
	    						$image->save($image_dir);							   
							}						
						}
					}

			    	$query = "INSERT INTO athletes (name, country, category, content, image_dir, email, athlete_slug) VALUES (:name, :country, :category, :content, :image_dir, :email, :athlete_slug)";
			    	$statement = $db->prepare($query);

			    	$statement->bindValue(':name', $name);
			    	$statement->bindValue(':country', $country);
			    	$statement->bindValue(':category', $category);
			    	$statement->bindValue(':content', $content);
			    	$statement->bindValue(':image_dir', $image_dir);
			    	$statement->bindValue(':email', $email);
			    	$statement->bindValue(':athlete_slug', $athlete_slug);
			    	$statement ->execute();
			    	header("Location: index.php"); 
	    		}	  
			}
			else{
			$errorMessage = "Your post has errors.";
			}
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
<?php if(!isset($errorMessage)):?>
	<?php include('header.php'); ?>
	<main>
		<div class="container">
		<h2>Post Athletes</h2>
		<form method="post" enctype="multipart/form-data">
			<div class="form-group"	>
				<label for="name">Name:</label>
				<input type="text" class="form-control" name="name"><br>
			</div>
			<div class="form-group">
				<label for="name">Country:</label>
				<input type="text" id="country" class="form-control" name="country"><br>
			</div>	
			<div class="form-group">
				<label for="name">Category:</label>
				<input type="text" id="sport" class="form-control" name="category"><br>
			</div>	
			<div class="form-group">
				<label for="content">Content:</label>
				<textarea id="content" name="content" class="form-control" placeholder="Simple Introduction"></textarea><br>
			</div>
			<div class="form-group">
				<label for="image">Upload Image (Option):</label>
				<input type="file" name="file" class="form-control"><br>
			</div>
			<button type="submit" name="submit" class="btn btn-primary">Upload</button><br><br>		
		</div>
		</form>
		<script>
      CKEDITOR.replace( 'content' );
    </script>
	</main>
<?php else:?>
	<h1><?=$errorMessage ?></h1><br>
	<p>The title and the content must be at least one character.</p><br>
	<a href="index.php">Return Home</a><br><br>
<?PHP endif ?>
	<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>