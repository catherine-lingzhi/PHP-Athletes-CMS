<!-------f----------

    Assignment 3
    Name: Lingzhi Luo
    Date: 2022-05-24
    Description: The post page is used to create a new post which should contain at least one character in the title and content as a authenticated user.

------------------->
<?php
	require('authenticate.php');	
	require('connect.php');
	require ('\xampp\htdocs\wd2\project\php-image-resize-master\lib\ImageResize.php');
	require ('\xampp\htdocs\wd2\project\php-image-resize-master\lib\ImageResizeException.php');
	
	use \Gumlet\ImageResize;

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

	if(isset($_POST['submit'])){
		if(!empty($_POST['name']) && !empty($_POST['country']) && !empty($_POST['sport']) && !empty($_POST['content'])){
	    	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
	    	$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);   
	    	$sport = filter_input(INPUT_POST, 'sport', FILTER_SANITIZE_FULL_SPECIAL_CHARS);  
	    	$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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

			  $query = "INSERT INTO athletes (name, country, sport, content, image_dir) VALUES (:name, :country, :sport, :content, :image_dir)";
			  $statement = $db->prepare($query);

			  $statement->bindValue(':name', $name);
			  $statement->bindValue(':country', $country);
			  $statement->bindValue(':sport', $sport);
			  $statement->bindValue(':content', $content);
				$statement->bindValue(':image_dir', $image_dir);
			  $statement ->execute();
			  header("Location: index.php"); 
	    	}	  
		}
		else{
			$errorMessage = "Your post has errors.";
		}
	} 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CMS Project</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
 			<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"> 		
 			</script>    
</head>
<body>
<?php if(!isset($errorMessage)):?>
	<?php include('header.php'); ?>

	<main>
		<form method="post" enctype="multipart/form-data">
			<label>New Athletes Post</label><br>
			<label for="name">Name:</label>
			<input type="text" id="name" name="name"><br>
			<label for="name">Country:</label>
			<input type="text" id="country" name="country"><br>	
			<label for="name">Sport:</label>
			<input type="text" id="sport" name="sport"><br>		
			<label for="content">Content:</label>
			<textarea id="content" name="content" rows="10" cols="50" placeholder="Simple Introduction"></textarea><br><br>
			<label for="image">Upload Image (Option):</label>
			<input type="file" name="file"><br>
			<input type="submit" name="submit" value="Upload">
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
</body>
</html>