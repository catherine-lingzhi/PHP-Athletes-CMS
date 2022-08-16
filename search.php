<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();
	require('connect.php');
	require('function.php');

	if(isset($_POST['submit_search'])){		
		$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if(empty($search)){
			$error="Search input is empty, try agian!";
		}else{
			$_SESSION['search']	= $search;
		}
			
	}else{
	
		$search = $_SESSION['search'];
	}

	$query = "SELECT * FROM athletes WHERE category LIKE '%$search%' OR name LIKE '%$search%' OR country LIKE '%$search%' OR content LIKE '%$search%' OR email LIKE '%$search%'";
	$statement = $db->prepare($query);
	$statement->execute();
	$rows = $statement->fetchAll();
	$results_count = count($rows);

	$limit = 3;	

	if(!isset($_GET['page'])){
		$page = 1;
    }
    else{
    	$page = $_GET['page'];
    }			
		
	if($results_count > 0){
		$page_num = ceil($results_count / $limit);		
	} 
	else{
		$error = "No results found, try agian!";
	}

	$offset = ((int)$page - 1) * (int)$limit;	

 	$limitquery = "SELECT * FROM athletes WHERE category LIKE '%$search%' OR name LIKE '%$search%' OR country LIKE '%$search%' OR content LIKE '%$search%' OR email LIKE '%$search%' LIMIT $offset, $limit";
	$stmt = $db->prepare($limitquery);
	$stmt->execute();
	$athletes = $stmt->fetchAll();
	$athletesCount = count($athletes);

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
		<div class="container">
		<?php if(isset($search) && !isset($error)):?>		
			<div class= "col-md-12">
			<?php if(isset($athletes) && $athletesCount > 0):?>
				<h5>There are <?=$results_count ?> search Reuslts for "<?=$search ?>"</h5>	
				<ul>					
					<?php foreach($athletes as $row): ?>		
						<li><a href="view.php?id=<?= $row['id'] ?>&name=<?=slug($row['name'])?>"><?= $row['name'] ?></a> </li>
					<?php endforeach ?>				
				</ul>
			<?php endif?>
			</div>
		
			<div class="pagination">	
				<?php if(isset($page_num) && isset($page)):?>
					<ul class="pagination">
					<?php if($page > 1):?>
					    <li class="page-item">
					      <a class="page-link" href="search.php?page=<?= (int)$page-1  ?>" aria-label="Previous">
					        <span>&laquo; Previous </span>
					      </a>
					    </li>
					<?php endif?>
				    <?php for($i = 1; $i<= $page_num; $i++) : ?>
				    	<li class="page-item"><a class="page-link" href="search.php?page=<?= $i ?>"> <?= $i?></a></li>
				    <?php endfor ?>
				    <?php if($page_num > $page):?>
				    <li class="page-item">
				      <a class="page-link"href="search.php?page=<?=(int)$page+1 ?>" aria-label="Next">
				        <span aria-hidden="true"> Next &raquo;</span>
				      </a>
				    </li>
					<?php endif?>
				  </ul>
				<?php endif?>			
			</div>
		<?php elseif(isset($error)):?>
		<div class="error">
			<p><?=$error?></p>
		</div>
		<?php endif?>		
		</div>
	</main>
	<?php include('footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>