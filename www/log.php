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
$_SESSION['target']="log.php";
if(!($_SESSION['permit'])){
  //printVar("would exit");
  header("Location: login.html");
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Doorstrike Log</title>
    <link href="css/bootstrap.css" rel="stylesheet"/>
    <link href="signin.css" rel="stylesheet"/>
  </head>
  <body> 
    <header class="navbar">
      <div class="container">
        <nav class="navbar navbar-default" role="navigation">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="testAdmin.php">Home</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="active"><a href="log.php">Log</a></li>
                <li><a href="acceptedUsers.php">Accepted Users</a></li>
                <li><a href="accessRequests.php">Access Requests</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">Log Out</a></li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
      </div>
    </header>
 		<div class="container">
 			<h1 style = "text-align: center"><small>Hackerspace Card Swipe Access Log</small></h1>
 			<p>&nbsp;</p>
      <div class="row">
        <div>
          <?php  
            $result = mysqli_query($con, "SELECT * FROM log");
            echo "<table border= '2'>
            <tr>
            <th width='40%'>&nbsp;Action</th>
            <th width='50&'>&nbsp;Access Request</th>
            <th width='10%'>&nbsp;Timestamp</th>
            </tr>";


            $actionArray = array();
            $accessRequestArray = array();
            $timestampArray = array();


            while($row = mysqli_fetch_array($result)){
              $actionArray[] = $row['action'];
              $accessRequestArray[] = $row["accessRequest"];
              $timestampArray[] = $row['timestamp'];
            }

            for($i = count($actionArray)-1; $i >=0; $i--){
              echo "<tr>";
              echo "<td>&nbsp;" . $actionArray[$i] . "&nbsp;</td>";
              echo "<td>&nbsp;" . $accessRequestArray[$i] . "</td>";
              echo "<td>&nbsp;" . $timestampArray[$i] . "&nbsp;</td>";
              echo "</tr>"; 
            }

            echo "</table>";
            mysqli_close($con);
          ?>
        </div>
      </div>
 		</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>