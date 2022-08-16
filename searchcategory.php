<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php
	session_start();

	require('connect.php');
	require('function.php');

	$query = "SELECT * FROM categories";
	$statement = $db->prepare($query);
	$statement->execute();
	$search_category = $statement->fetchAll();
	$category_count = count($search_category);
	
	if(isset($_POST['categories_search'])){		

		if(!empty($_POST['categories'])){			
			$categories = filter_input(INPUT_POST, 'categories', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			
			if($categories === "All Cateogries"){					
				$query = "SELECT * FROM athletes";
				$statement = $db->prepare($query);
				$statement->execute();	
				$allrow = $statement->fetchAll();
				$allrowcount = count($allrow);				
			}
			else{
				$query = "SELECT * FROM athletes WHERE category = :categories";
				$statement = $db->prepare($query);
				$statement->bindValue(":categories", $categories);
				$statement->execute();
				$selectedrow = $statement->fetchAll();
				$selectedcount = count($selectedrow);		
			}				
		}
	}	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Search By Categories</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>	
	<?php include('header.php'); ?>
	<main>		
		<div class="container">
			<h2>Search By Category</h2>
			<form method = "post" class="category">
				<div class="form-group">
					<label for="categories">Categories:</label>
					<select name="categories" id="categories" class="form-control" onchange="catgoryChange()">			
					  	<option value="">--select a category--</option>
					  	<option value="All Cateogries">All Categories</option>
						<?php if($category_count > 0): ?>
							<?php foreach($search_category as $r):?>		
							<option value="<?= $r['category_name'] ?>"><?= $r['category_name'] ?></option>
							<?php endforeach?>
						<?php endif ?>
					</select><br><br>
				</div>
				<button type="submit" name="categories_search" class="btn btn-primary">Search</button><br><br>
			</form>

			<div>				
				<?php if(isset($categories)):?>
				<h5><?=$categories?></h5>
				<ol>	
					<?php if(isset($allrowcount) && $allrowcount > 0): ?>					
						<?php foreach($allrow as $r):?>			
						<li><a href="view.php?id=<?= $r['id'] ?>&name=<?=slug($r['name']) ?>"><?= $r['name'] ?></a></li>
						<?php endforeach?>
					<?php elseif(isset($selectedrow) && $selectedcount > 0): ?>				
						<?php foreach($selectedrow as $r):?>			
						<li><a href="view.php?id=<?= $r['id'] ?>&name=<?=slug($r['name']) ?>"><?= $r['name'] ?></a></li>
						<?php endforeach?>
					<?php else:?>						
						<p>No Results found!</p>					
					<?php endif ?>
				<?php endif?>
				</ol>
			</div>			
		</div>
	</main>
	<?php include('footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script>
		$("#categories").chosen();
	</script>
</html>

