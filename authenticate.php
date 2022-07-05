<!-------f----------

    Assignment 3
    Name: Lingzhi Luo
    Date: 2022-05-24
    Description: The autherticate.php file is define the user and password which are anthenticated to edit and create blog, otherwise show error message.

------------------->
<?php 
  define('ADMIN_LOGIN','happyface'); 
  define('ADMIN_PASSWORD','mypass'); 

  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) 
      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN) 
      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) { 
    header('HTTP/1.1 401 Unauthorized'); 
    header('WWW-Authenticate: Basic realm="Our Blog"'); 
    exit("Access Denied: Username and password required."); 
  }    
?>
