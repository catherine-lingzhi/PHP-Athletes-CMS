<?php
	session_start();
	require('connect.php');

	if(isset($_POST['submit_search'])){
		if(isset($_POST['search']) && !empty($_POST['search'])){
			$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if(!empty($search)){
				$query = "SELECT * FROM athletes WHERE category LIKE '%$search%' OR name LIKE '%$search%' OR country LIKE '%$search%' OR content LIKE '%$search%' OR email LIKE '%$search%'";
				$statement = $db->prepare($query);
				$statement->execute();
				$rows = $statement->fetchAll();
				$results_count = $statement->rowCount();				
			}
		

			// $limit = 3;
			// if($results_count > 0){
   //  			$page_num = ceil($results_count / $limit);
   //  			echo $page_num. "<br>";
   //  		}

		 //    if(!isset($_GET['page'])){
   //  			$page = 1;
		 //    }
		 //    else{
		 //    	$page = $_GET['page'];
		 //    }

		 //    $offset = ((int)$page - 1) * (int)$limit;	
		    
		 //    $query = "SELECT * FROM athletes WHERE sport LIKE '%$search%' OR name LIKE '%$search%' OR country LIKE '%$search%' OR content LIKE '%$search%' OR email LIKE '%$search%' LIMIT $offset, $limit";
			// $statement = $db->prepare($query);
			// $statement->execute();

			// while($row = $statement->fetch()){
		 //    	echo $row['name'] . " ". $row['sport'] . "<br>";
		 //    }
    	}   		
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Search Result</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('header.php'); ?>
	<main>
		<div class="container-sm themed-container">
			<?php if(isset($search)):?>
			<h1>Search Reuslts for "<?=$search ?>"</h1>
			<?php endif ?>
			<ol>
				<?php if(isset($rows) && $results_count > 0):?>
				<?php foreach($rows as $row): ?>				
					<li><a href="view.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
				<?php endforeach ?>
				<?php endif?>
			</ol>
		</div>
	</main>
	<?php include('footer.php'); ?>
	    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>