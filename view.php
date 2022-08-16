<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
    require('connect.php');
    require ('vendor/autoload.php');
    require('function.php'); 
	use Gregwar\Captcha\PhraseBuilder;

	session_start();

	if(isset($_GET['id'])){
    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

  		if($id){
	    	$query = "SELECT * FROM athletes WHERE id = :id LIMIT 1";

	    	$statement = $db->prepare($query);
	    	$statement->bindValue(':id', $id, PDO::PARAM_INT);
	    	$statement->execute();   
	    	$row = $statement->fetch();	
	    	$_SESSION['slug'] = slug($row['name']);

	    	$c_query = "SELECT * FROM comments WHERE id = :id ORDER BY comment_time DESC";
	    	$c_statement = $db->prepare($c_query);
	    	$c_statement->bindValue(':id', $id);

	    	$c_statement->execute();
	    	$c_rows = $c_statement->fetchAll();  			
  		}
	}

	$comment = null;
	$user = null;
	if(isset($_POST['add_comment'])){ 
		$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$captcha = filter_input(INPUT_POST, 'phrase', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
		
		if(isset($_SESSION['email'])){
			$user = substr($_SESSION['email'], 0, strpos($_SESSION['email'], '@'));
		}
		else{		
			$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
		}	
	
		if(!empty(trim($comment)) && !empty($user) && !empty(trim($captcha)) &&!empty($id) && $id){
			$slug = slug($user);
			if(isset($_SESSION['phrase']) && PhraseBuilder::comparePhrases($_SESSION['phrase'], $captcha)) {	
				
				$query = "INSERT INTO comments (id, comment, user) VALUES (:id, :comment, :user)";
				$statement = $db->prepare($query);

		  		$statement->bindValue(':id', $id);
		  		$statement->bindValue(':comment', $comment);
		  		$statement->bindValue(':user', $user);
		  		$statement ->execute();	
		  		header("Location: view.php?id={$id}&name={$_SESSION['slug']}&commentby={$slug}");
			}	
			else{
				$error="Wrong captcha";
			}
			unset($_SESSION['phrase']);
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
	<title>View Athletes</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>

	<main>
		<div class="container">
    	<?php if (isset($_GET['id']) && $id): ?>            				
			<h2><?= $row['name']?></h2>
			<?php if(!empty($row['image_dir'])):?>
				<img src="<?=$row['image_dir'] ?>" alt="<?=$row['image_dir'] ?>">
			<?php endif ?>
			<p><small>Created By: <?= substr($row['email'], 0, strpos($row['email'], '@')) ?></small></p>
			<p><small>Category: <?= $row['category']?></small></p>
			<p>
				<small>Create Time:
					<?= date_format(new datetime($row['create_time']), "F j, Y, g:i a")?> 					
				</small>
			</p>
			<p>
				<small>Update Time:
					<?= date_format(new datetime($row['update_time']), "F j, Y, g:i a") ?> 
					<?php if(isset($_SESSION['email'])):?>
						<a href="edit.php?id=<?= $row['id']?>&name=<?=slug($row['name'])?>"> - Edit</a>
					<?php endif ?>
				</small>
			</p>				
			<p><?= $row['content'] ?></p>			
					
			<?php if(count($c_rows) > 0): ?>
				<h5><?=count($c_rows) ?> Comments:</h5><br>
				<ol>	
				<?php foreach($c_rows as $c_row):?>				
					<li><?=$c_row['comment'] ?>
					<p>
						<small>posted by <?=$c_row['user'] ?> - 
						<?= date_format(new datetime($c_row['comment_time']), "F j, Y, g:i a")?>
						<?php if(isset($_SESSION['user_type']) && strtolower($_SESSION['user_type']) ==="admin"):?>
						<a href="editcomment.php?comment-id=<?= $c_row['comment_id'] ?>&commentby=<?= slug($c_row['user']) ?>"> - Edit</a>
						<?php endif ?>	
						</small>
					</p></li>
				<?php endforeach ?>
			</ol>
			<?php endif?>
		</div>		

		<div class="container">
        <form method="post">         	
        	<input type="hidden" name="id" value="<?= $row['id'] ?>">
        	<input type="hidden" name="comment_id" value="<?= $c_row['comment_id'] ?>">
        	<?php if(!isset($_SESSION['email'])) :?>
        		<div class="form-group">
				<label for="user">User Name:</label>
				<input type="text" class="form-control" id="user" name="user" value="<?= $user?>"/><br>
				</div>	
			<?php endif ?>	
			<div class="form-group">
				<label for="comment">Comments:</label>      		
        		<textarea id="comment" class="form-control" rows="8" name="comment" ><?= $comment?></textarea><br> 
        	</div> 
        	<div class="form-row" >      
	        	<img class="col-2" src="session.php" alt="captcha"/>
	        	<input class="col-6" id="captcha" type="text" class="form-control" name="phrase" placeholder="type the code to varify" /><br><br> 
        	</div>        
        	<?php if(isset($error)) :?>
        		<div class="error">
					<p><?=$error ?></p>	
				</div>
			<?php endif ?>
        	<button type="submit" name="add_comment" class="btn btn-primary">Add Comment</button><br><br>	
        </form> 
        <?php endif ?>	
        </div>         
	</main>

	<?php include('footer.php'); ?>
</body>
</html>