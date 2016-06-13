<?php
session_start();
$_SESSION['target']="testAdmin.php";
//printVar($_SESSION);
//printVar($_SESSION['permit']);
$_SESSION['permit']=0;
//printVar($_SESSION['permit']);
header("Location: login.html");
die();

 function printVar($var){
    echo '<br/>' . $var . '<br/>';
    print_r($var);
}
?>
