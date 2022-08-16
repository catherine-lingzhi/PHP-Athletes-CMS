<!-------f----------

    CMS Project
    Name: Lingzhi Luo
    Date: 2022-07-05 

------------------->
<?php 
session_start();

session_destroy();

header("location: index.php");
exit;
?>
