<?php
// Create connection
      $con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
?>
//gets name from log to display when access is granted
<?php  
            $result = mysqli_query($con, "SELECT * FROM log");
            $users = array();
            while($row = mysqli_fetch_array($result)){
            	$users[] = $row['action'];
            }
            echo strtoupper(substr($users[count($users) - 1], 16));

?>