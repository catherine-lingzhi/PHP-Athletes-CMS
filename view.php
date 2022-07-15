<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

    require('connect.php');
    require('vendor/autoload.php');   

	use Gregwar\Captcha\PhraseBuilder;

	if(isset($_GET['id'])){
    	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  
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
<html>
<head>
	<meta charset="utf-8">
    <title>CMS Project</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>

	<main>
    <?php if (isset($_GET['id'])): ?>
        <form method="post">            
			<div class="post">				
				<h2><?= $row['name']?></h2>
				<p>
					<small>
						<?= date_format(new datetime($row['create_time']), "F j, Y, g:i a")?> 
						<a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
					</small>
				</p>			
				<div class="content">
					<p><?= $row['content'] ?></p>
				</div>
				<div class="comments">
					<h4>Comments:</h4><br>				
					<?php while($c_row = $c_statement->fetch()): ?>			
						<h5><?=$c_row['comment'] ?></h5>
						<p>
							<small>posted by <?=$c_row['user'] ?> - 
							<?= date_format(new datetime($c_row['comment_time']), "F j, Y, g:i a")?>	
							</small>
						</p>
					<?php endwhile ?>
			
				</div>			
			</div>      
        </form>
    <?php endif ?>

        <form method="post">
        	<label for="comment">Make a Comment:</label><br>
        	<input type="hidden" name="id" value="<?= $row['id'] ?>">
        	<input type="hidden" name="comment_id" value="<?= $c_row['comment_id'] ?>">
        	<?php if(!isset($_SESSION['email'])) :?>
				<label for="user">User Name:</label>
				<input type="text" name="user"/>	
			<?php endif ?>	        		
        	<textarea id="comment" name="comment" rows="10" cols="127"></textarea><br> 
        	<img src="captcha.php"/><br>
        	<input type="text" name="captcha" placeholder="type the code to varify" /><br><br>    	
        	<input type="submit" name="add_comment" value="Add Comment"/>
        	<?php if(isset($error)) :?>
				<p><?=$error ?></p>	
			<?php endif ?>
        </form>	    
	</main>

	<?php include('footer.php'); ?>
</body>
</html>