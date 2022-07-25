<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

    require('connect.php');
    require ('vendor/autoload.php');   

	use Gregwar\Captcha\PhraseBuilder;

	if(isset($_GET['id'])){
    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    	$query = "SELECT * FROM athletes WHERE id = :id LIMIT 1";

    	$statement = $db->prepare($query);
    	$statement->bindValue(':id', $id, PDO::PARAM_INT);

    	$statement->execute();   
    	$row = $statement->fetch();	

    	$c_query = "SELECT * FROM comments WHERE id = :id ORDER BY comment_time DESC";
    	$c_statement = $db->prepare($c_query);
    	$c_statement->bindValue(':id', $id);

    	$c_statement->execute();
	}

	if(isset($_POST['add_comment'])){ 
		 	
		if(!empty($_POST['comment']) && !empty($_POST['captcha']) &&!empty($_POST['id'])){
			$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

			$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$captcha = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);				
			
			if(isset($_SESSION['email'])){
				$user = substr($_SESSION['email'], 0, strpos($_SESSION['email'], '@'));
			}

			if(isset($_POST['user'])){
				$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				if(empty($user)){
					$error="Empty user.";
				}
			}	

			if (isset($_SESSION['phrase']) && PhraseBuilder::comparePhrases($_SESSION['phrase'], $_POST['captcha'])) {

				if(isset($user) && strlen($comment) > 0 && strlen($user) > 0 ){
				
					$query = "INSERT INTO comments (id, comment, user) VALUES (:id, :comment, :user)";
					$statement = $db->prepare($query);

			  		$statement->bindValue(':id', $id);
			  		$statement->bindValue(':comment', $comment);
			  		$statement->bindValue(':user', $user);
			  		$statement ->execute();	
			  		header("Location: view.php?id={$id}");				  	
				}
				else{
					$error = "Empty input";
				}
			}	
			else{
				$error="Wrong captcha";

			}
			unset($_SESSION['phrase']);
		}
		else{
			$error = "Empty input";;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>View Athletes</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>

	<main>
		<div class="container">
    	<?php if (isset($_GET['id'])): ?>            					
			<h2><?= $row['name']?></h2>
			<?php if(!empty($row['image_dir'])):?>
				<img src="<?=$row['image_dir'] ?>" alt="<?=$row['image_dir'] ?>">
			<?php endif ?>
			<p><small>Created By: <?= substr($row['email'], 0, strpos($row['email'], '@')) ?></small></p>
			<p>
				<small>Create Time:
					<?= date_format(new datetime($row['create_time']), "F j, Y, g:i a")?> 					
				</small>
			</p>
			<p>
				<small>Update Time:
					<?= date_format(new datetime($row['update_time']), "F j, Y, g:i a") ." - "?> 
					<?php if(isset($_SESSION['email'])):?>
						<a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
					<?php endif ?>
				</small>
			</p>				
			<p><?= $row['content'] ?></p>
		</div>			

		<div class="container">						
			<?php while($c_row = $c_statement->fetch()): ?>	
			<h4>Comments:</h4><br>			
			<h5><?=$c_row['comment'] ?></h5>
				<p>
					<small>posted by <?=$c_row['user'] ?> - 
					<?= date_format(new datetime($c_row['comment_time']), "F j, Y, g:i a")?>	
					</small>
				</p>
			<?php endwhile ?>
		<?php endif ?>			
		</div>  	

		<div class="container">
        <form method="post">         	
        	<input type="hidden" name="id" value="<?= $row['id'] ?>">
        	<input type="hidden" name="comment_id" value="<?= $c_row['comment_id'] ?>">
        	<?php if(!isset($_SESSION['email'])) :?>
        		<div class="form-group">
				<label for="user">User Name:</label>
				<input type="text" class="form-control" name="user"/>
				</div>	
			<?php endif ?>	
			<div class="form-group">      		
        		<textarea id="comment" class="form-control" row="8" name="comment"></textarea><br> 
        	</div>        
        	<img src="captcha.php" />
        	<input type="text" class="form-control" name="captcha" placeholder="type the code to varify" /><br><br>         
        	<?php if(isset($error)) :?>
				<p class="error"><?=$error ?></p>	
			<?php endif ?>
        	<button type="submit" name="add_comment" class="btn btn-primary">Add Comment</button><br><br>	
        </form> 
        </div>         
	</main>

	<?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>