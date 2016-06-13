<?php
// Create connection
session_start();
//printVar($_SESSION);
//printVar($_SESSION['permit']);
if(!($_SESSION['permit'])){
  //printVar("would exit");
  header("Location: login.html");
  die();
}
$con=mysqli_connect("192.168.1.10","root","password","doorMaster");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$result = mysqli_query($con, "SELECT * FROM acceptedCards");
echo "<table border='4'>
<tr>
<th>Current Access</th>
</tr>";
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['name'] . "</td>";
  echo "</tr>";
}


$result = mysqli_query($con, "SELECT * FROM log");
echo "<table border='4'>
<tr>
<th>action</th>
<th>accessRequest</th>

<th>timestamp</th>
</tr>";

while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['action'] . "</td>";
  echo "<td>" . $row['accessRequest'] . "</td>";
  echo "<td>" . $row['timestamp'] . "</td>";
  echo "</tr>";
}

echo "</table>";

mysqli_close($con);
?>
