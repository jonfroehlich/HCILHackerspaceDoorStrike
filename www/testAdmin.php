<?php
 $con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
session_start();
//printVar($_SESSION);
//printVar($_SESSION['permit']);
$_SESSION['target']="testAdmin.php";
if(!($_SESSION['permit'])){
  //printVar("would exit");
  header("Location: login.html");
  die();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!--
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    -->
    <title>Doorstrike Admin</title>
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
                <!--<span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>-->
              </button>
              <a class="navbar-brand active" href="testAdmin.php">Home</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li><a href="log.php">Log</a></li>
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
 			<h1 style = "text-align: center"><small>Hackerspace Card Swipe Admin Page</small></h1>
 			<p>
 				Welcome.
 			</p> 
 			<p>
 				Documentation, source code, and all other resources necessary to rebuild the door strike in the event of a catastrophic system failure are available at the links below.
 			</p>
      <?php  
        $statQuery="SELECT status FROM status WHERE 1=1";
        $savedstatQuery=mysqli_query($con, $statQuery);
        $savedstatRow=mysqli_fetch_array($savedstatQuery);
        $savedstat=$savedstatRow['status'];
        echo "<p>The current door status is: ".$savedstat."</p>";
      ?>
      <p>&nbsp;
      </p>
 		</div>
    <div class="row">
        <div class="col-md-2 col-md-offset-5">
          <a href = "https://docs.google.com/document/d/15j9zit9bLJ8XCp5VGA7Awrj1_wnl-QX-iKCoGyTXrNc/edit" class="btn btn-lg btn-primary" target = "_blank">
          Documentation
          </a>
        </div>
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>