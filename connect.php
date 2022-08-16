<!-------f----------

    Project
    Name: Lingzhi Luo
    Date: 2022-07-05
    Description: The connect file is used to connect database of myphpadmin. 

------------------->
<?php

    // Connect http://highathletes.byethost16.com
    // define('DB_DSN','mysql:host=sql213.byethost16.com;dbname=b16_32389503_serverside;charset=utf8');
    // define('DB_USER','b16_32389503');
    // define('DB_PASS','xmy0221'); 

    //Connect the local host
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