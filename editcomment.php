<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();
			
	require('connect.php');	
	require('function.php');
	
	if(!isset($_SESSION['email'])){
		header("Location: login.php"); 
	}
	else{
		if(isset($_POST['update']) && isset($_POST['comment']) && isset($_POST['id']) && isset($_POST['comment_id']) && isset($_POST['user'])){
        	$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
        	$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);     		
        	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        	$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
        	$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);        
			$slug = slug($user);
			if(strlen($user) > 0 && strlen($comment) > 0 && $comment_id && $id){
				try{
		        	$query = "UPDATE comments SET comment = :comment, user = :user WHERE comment_id = :comment_id";

		        	$statement = $db->prepare($query);
		        	$statement->bindValue(':comment', $comment);
		        	$statement->bindValue(':user', $user); 	        	   
		        	$statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
		        	$statement->execute();
		        	header("Location: view.php?id={$id}&commentby={$slug}"); 
		        	exit(); 
	        	}catch(PDOException $e){
	        		echo $errorMessage = $e->getMessage();
				}
	        }
			else{
				$errorMessage = "Your comment has errors.";
			}    
		}
		else if(isset($_POST['delete']) && isset($_GET['comment-id'])){
	    	$comment_id = filter_input(INPUT_GET, 'comment-id', FILTER_SANITIZE_NUMBER_INT);
	  		$comment_id = filter_input(INPUT_GET, 'comment-id', FILTER_VALIDATE_INT);
	  		if($comment_id){
				$query = "DELETE FROM comments WHERE comment_id = :comment_id LIMIT 1";

		    	$statement = $db->prepare($query);
		    	$statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);

		    	$statement->execute();		  		
	        	header("Location: view.php?id={$_SESSION['id']}&delete-comment-id={$comment_id}");
		  	}	    	
		}
    	else if(isset($_GET['comment-id'])){
	    	$comment_id = filter_input(INPUT_GET, 'comment-id', FILTER_SANITIZE_NUMBER_INT);
	  		$comment_id = filter_input(INPUT_GET, 'comment-id', FILTER_VALIDATE_INT);

	  		if($comment_id){
		    	$query = "SELECT * FROM comments WHERE comment_id = :comment_id LIMIT 1";

		    	$statement = $db->prepare($query);
		    	$statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);

		    	$statement->execute();   
		    	$row = $statement->fetch();	
		    	$_SESSION['id'] = $row['id'];	  			
	  		}
		}
		else{
			$comment_id = false;
		}    		
	}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Comments</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"> 		
 	</script>    
</head>
<body>
	<?php include('header.php'); ?>
	
	<main>	
		<div class="container">
		<?php if(!isset($errorMessage)):?>
			<h2>Edit Comments</h2>	
			<form method="post">	
				<input type="hidden" name="id" value="<?= $row['id'] ?>">				
				<input type="hidden" name="comment_id" value="<?= $row['comment_id'] ?>">
				<input type="hidden" name="user" value="<?= $row['user'] ?>">
				<div class="form-group">
					<label for="comment">Comment</label>
					<textarea id="comment" class="form-control" rows="8" name="comment"><?= $row['comment'] ?></textarea><br> 
				</div>
				<button type="submit" id="update" name="update" class="btn btn-primary">Update</button>
				<button type="submit" id="delete" name="delete" class="btn btn-primary">Delete</button>	<br><br>				
			</form>
		<?php else:?>
			<div class="error">
				<p><?=$errorMessage ?></p>
				<a href="view.php?id=<?=$_SESSION['id'] ?>">Return</a>
			</div>
		<?PHP endif ?>			
		</div>
	</main>
	<?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>	
</body>
</html>