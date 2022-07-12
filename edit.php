<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

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

    if(isset($_POST['update']) && isset($_POST['name']) && isset($_POST['country']) && isset($_POST['sport'])&& isset($_POST['content']) && isset($_POST['id'])){
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
        $sport = filter_input(INPUT_POST, 'sport', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	      
        $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);	
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
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

	        $query = "UPDATE athletes SET name = :name, country = :country, sport = :sport, content = :content, image_dir = :image_dir WHERE id = :id";
	        $statement = $db->prepare($query);
	        $statement->bindValue(':name', $name);        
	        $statement->bindValue(':country', $country);
	        $statement->bindValue(':sport', $sport);        
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
	  
	    $query = "DELETE FROM athletes WHERE id = :id LIMIT 1";

	    $statement = $db->prepare($query);
	    $statement->bindValue(':id', $id, PDO::PARAM_INT);

	    $statement->execute();   
	  
        header("Location: index.php");
	}
    else if(isset($_GET['id'])){
	    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	  
	    $query = "SELECT * FROM athletes WHERE id = :id LIMIT 1";

	    $statement = $db->prepare($query);
	    $statement->bindValue(':id', $id, PDO::PARAM_INT);

	    $statement->execute();   
	    $row = $statement->fetch();	
	}
	else{
		$id = false;
	}    
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Assignment 3</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
 	<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
</head>
<body>
<?php if(!isset($errorMessage) && $id):?>
	<?php include('header.php'); ?>

	<main>
		<form method="post" enctype="multipart/form-data">
			<label>Add New Athletes</label><br>
			<input type="hidden" name="id" value="<?= $row['id'] ?>">
			<label for="name">Name:</label>
			<input id="name" name="name" value="<?= $row['name'] ?>"><br>	
			<label for="country">Country:</label>
			<input id="country" name="country" value="<?= $row['country'] ?>"><br>
			<label for="sport">Sport:</label>	
			<input id="sport" name="sport" value="<?= $row['sport'] ?>"><br>	
			<label for="content">Content:</label><br>
			<textarea id="content" name="content" rows="10" cols="50"><?= $row['content'] ?></textarea><br><br>
			<label for="image">Upload Image (Option):</label>
			<input type="file" name="file"><br>			
			<input type="submit" id="update" name="update" value="Update">
			<input type="submit" id="delete" name="delete" value="Delete">
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