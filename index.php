<!-------f----------

    Assignment 3
    Name: Lingzhi Luo
    Date: 2022-05-24
    Description: The home page that lists the title, date/time stamp and excerpt of the 5 most recently posted blog entries in reverse chronological order. 

------------------->
<?php
	require('connect.php');
	$query = "SELECT * FROM athletes ORDER BY edit_time DESC";
	$statement = $db->prepare($query);
	$statement->execute();
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
		<?php while($row = $statement->fetch()): ?>
			<div class="post">
				<h2><a href="view.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></h2>
				<p>Country: <?= $row['country'] ?></p>
				<p>Sport:<?= $row['sport'] ?></p>
				<img src="<?=$row['image_dir'] ?>" alt="<?=$row['image_dir'] ?>">
				<p>
					<small>Create Time:
						<?= date_format(new datetime($row['edit_time']), "F j, Y, g:i a") ." - "?> 
						<a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
					</small>
				</p>			
				<div class="content">
					<?php if(strlen($row['content']) > 200):?>
						<p><?= substr($row['content'], 0, 200) . "..."?><a href="view.php?id=<?= $row['id'] ?>">Read more</a></p>
					<?PHP else:?>
						<p><?= $row['content'] ?></p>
					<?php endif ?>
				</div>
			</div>
		<?php endwhile ?>		
	</main>

	<?php include('footer.php'); ?>
</body>
</html>