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
$_SESSION['target']="accessRequests.php";
if(!($_SESSION['permit'])){
  //printVar("would exit");
	header("Location: login.html");
	die();
};
printVar($_POST);
$userArray=$_POST['id'];
foreach ($userArray as $user) {
	$user=$_GET['id'];
	$query="SELECT Name FROM accessRequests WHERE Card=\"$user\"";
	printVar($query);
	$savedpassQuery=mysqli_query($con, $query);
	$savedpassRow=mysqli_fetch_array($savedpassQuery);
	$savedName=$savedpassRow['Name'];
	printVar($savedName);
	$whitelist="INSERT INTO `acceptedCards` (`card`, `name`) VALUES ('$user', '$savedName')";
	printVar($whitelist);
	mysqli_query($con, $whitelist);
	$removeFromReq="DELETE FROM accessRequests where Card=\"$user\"";
	mysqli_query($con, $removeFromReq);
}
function printVar($var){
	echo '<br/>' . $var . '<br/>';
	print_r($var);
}
// header("Location: accessRequests.php");
// die();
?>

<html>
<head>
	<title>Add users</title>
</head>
</html>