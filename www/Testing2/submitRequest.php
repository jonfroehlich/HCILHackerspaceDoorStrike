<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Create connection
$con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//submit a new request given name, card from log, and email
//please note there are better ways to do this which can lead to easier implementation for future reference
$savedName=$_GET['name'];
$savedEmailName=$_GET['emailName'];
$savedEmailDomain=$_GET['emailDomain'];
$savedEmailFin=$_GET['emailFin'];
$savedEmail=$savedEmailName."@".$savedEmailDomain.".".$savedEmailFin;
$result = mysqli_query($con, "SELECT * FROM log");$logResult=mysqli_query($con, $logQuery);
$accessRequestArray = array();
while($row = mysqli_fetch_array($result)){
    $accessRequestArray[] = $row["accessRequest"];
}
//printVar($accessRequestArray);
$lastCard=array_pop($accessRequestArray);
printVar($savedEmail);
printVar($savedName);
printVar($lastCard);
function printVar($var){
	echo '<br/>' . $var . '<br/>';
	print_r($var);
}
$query="INSERT INTO accessRequests (card, name, email) VALUES ( '$lastCard', '$savedName', '$savedEmail')";
printVar($query);
mysqli_query($con, $query);
//$command=escapeshellcmd('/var/www/Testing2/alertAdmins.py');
//$output=shell_exec($command);
printVar("Attemtping to call alertAdmins.py");
exec('python /var/www/Testing2/alertAdmins.py 2>&1');
exec('mkdir /var/www/Testing2/testdir 2>&1');
header("Location: requestSent.html");
die();
?>
<html>
<head>
	<title>submittingRequest</title>
</head>
</html>


