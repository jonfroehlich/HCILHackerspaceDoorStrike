<?php
// Create connection
$con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
session_start();
//printVar($_SESSION);
//printVar($_SESSION['permit'];
$_SESSION['target']="acceptedUsers.php";
if(!($_SESSION['permit'])){
  //printVar("would exit");
	header("Location: login.html");
	die();
}
printVar($_GET);
$userArr=$_POST['id'];
foreach($userArr as $user){
	$query="UPDATE acceptedCards SET admin=0 WHERE card='$user'";
	mysqli_query($con, $query);
}
function printVar($var){
	echo '<br/>' . $var . '<br/>';
	print_r($var);
}
//header("Location: accessRequests.php");
die();
?>

<html>
<head>
	<title>Add users</title>
</head>
</html>