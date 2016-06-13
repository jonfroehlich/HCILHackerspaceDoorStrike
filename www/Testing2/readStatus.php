<?php
// Create connection
      $con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
?>
//returns the current status of the door system as read from the status table
<?php  
            $result = mysqli_query($con, "SELECT * FROM status");
            echo mysqli_fetch_array($result)['status'];


            

?>