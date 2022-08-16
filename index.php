<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

	require('connect.php');
	require('function.php');

	if(isset($_POST['submit_sort'])){
		if(!empty($_POST['sort'])){
			$sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
		}
	}
	else{
		$sort = "create_time";
	}

	$query = "SELECT * FROM athletes ORDER BY $sort DESC";
	$statement = $db->prepare($query);
	$statement->execute();
	$rows = $statement->fetchALl();
	$count = count($rows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Index Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	
	<main>
		<div class="container">
			<h2>Home</h2>
			<?php if(isset($_SESSION['email'])):?>				
				<form method="post">					
					<select name="sort"  class="custom-select my-1 mr-sm-2">			
			  		<option selected value="create_time">Create Time</option>
			  		<option value="country">Country</option>
			  		<option value="name">Name</option>
					</select>					
					<button type="submit" name="submit_sort" class="btn btn-outline-primary btn-sm">Sort</button><br><br>								
				</form>	
				<h5>Sort By <?=$sort ?>:</h5><br>
			<?php endif?>	

			<?php if($count > 0):?>
				<?php foreach($rows as $row):?>				
					<p><a href="view.php?id=<?= $row['id']?>&name=<?=slug($row['name']) ?>"><?= $row['name'] ?></a></p>											
			<?php endforeach?>
			<?php endif?>			
		</div>		
	</main>
	
	<?php include('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>	
</body>
</html>