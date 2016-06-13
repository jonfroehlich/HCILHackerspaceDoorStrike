<?php
header('Content-Type: text/json');
$con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
session_start();
//$_SESSION['tagert']="getWhitelist.php";
if(!($_SESSION['permit'])){
  //printVar("would exit");
	header("Location: login.html");
	die();
}
$result = mysqli_query($con, "SELECT * FROM accessRequests");
$out=array();
while($row = mysqli_fetch_array($result)){
	$out[] = $row;
}
echo json_encode($out);
?>
