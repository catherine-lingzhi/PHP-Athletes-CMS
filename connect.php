<!-------f----------

    Assignment 3
    Name: Lingzhi Luo
    Date: 2022-05-24
    Description: The connect file is used to connect database of myphpadmin. 

------------------->
<?php
	define('DB_DSN','mysql:host=localhost;dbname=serverside;charset=utf8');
    define('DB_USER','serveruser');
    define('DB_PASS','gorgonzola7!'); 

    try{
    	$db = new PDO(DB_DSN, DB_USER, DB_PASS);
    }
    catch (PODException $e){
    	print "Error: " . $e->getMessage();
    	die();
    }
?>