<!-------f----------

    Assignment 3
    Name: Lingzhi Luo
    Date: 2022-05-24
    Description: The view page shows the detail of the posted title and all content.

------------------->
<?php
    require('connect.php');

    if(isset($_GET['id'])){
	    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	  
	    $query = "SELECT * FROM athletes WHERE id = :id LIMIT 1";

	    $statement = $db->prepare($query);
	    $statement->bindValue(':id', $id, PDO::PARAM_INT);

	    $statement->execute();   
	    $row = $statement->fetch();	
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
    <?php if (isset($id)): ?>
        <form method="post">            
			<div class="post">				
				<h2><?= $row['name']?></h2>
				<p>
					<small>
						<?= date_format(new datetime($row['edit_time']), "F j, Y, g:i a")?> 
						<a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
					</small>
				</p>			
				<div class="content">
					<p><?= $row['content'] ?></p>
				</div>			
			</div>      
        </form>

        <form method="post">
        	<label for="comment">Comment:</label><br>	
        	<textarea id="comment" name="comment" rows="10" cols="127"></textarea><br>
        	<input type="submit" name="add_comment" value="Add Comment"/>
        </form>	
    <?php endif ?>
	</main>

	<?php include('footer.php'); ?>
</body>
</html>