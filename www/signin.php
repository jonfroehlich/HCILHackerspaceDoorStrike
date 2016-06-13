<?php
	//include '/includes/databaseconnect.inc.php';
 	  $con=mysqli_connect("192.168.1.10","root","password","doorMaster");
      // Check connection
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
    session_start();
    printVar($_SESSION['target']);
    if(!$_SESSION['target']){
            $_SESSION['target']="testAdmin.php";
        }
	if(!$_SESSION){
	  	$_SESSION['permit'] = 0;
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign in</title>
</head>
<body>
<?php
	printVar("PhpStarted...");
	printVar($_GET);
    if($_GET['username']){
		$inputUser=$_GET['username'];
		printVar("In if loop...");
        $query="SELECT Password FROM adminLogin WHERE Username=\"$inputUser\"";
        if(!mysqli_query($con, $query)){         
        	 printVar("whatdawhat?!");          
             header("Location: index.html");
             die();
         }
        $passQuery="SELECT Password FROM adminLogin WHERE Username=\"$inputUser\"";
        $savedpassQuery=mysqli_query($con, $passQuery);
        $savedpassRow=mysqli_fetch_array($savedpassQuery);
        $savedpass=$savedpassRow['Password'];
        $saltQuery="SELECT Salt FROM adminLogin WHERE Username=\"$inputUser\"";
        $saltQueryRes=mysqli_query($con, $saltQuery); 
        $saltRow=mysqli_fetch_array($saltQueryRes);
        $salt=$saltRow['Salt'];
        printVar($savedpass);
        printVar($_GET['password']);
        printVar($_SESSION);
        //$hashedPass=crypt($_GET['password']. $salt);
        //if($hashedPass==crypt($savedpass. $salt){
        if($_GET['password']==$savedpass){
            printVar("good swipe");
            $_SESSION['permit']=1;
            printVar($_SESSION['permit']);
            printVar($_SESSION['target']);
            header("Location: ".$_SESSION['target']);
            die();
    	}
        else{
            echo "<p>bad pass</p>";
            $_SESSION['permit']=0;
            header("Location: index.html");
            die();
        }
    }
    else{
       echo "<p>no info</p>";
       header("Location: index.html");
       die();
    }

    function printVar($var){
    	echo '<br/>' . $var . '<br/>';
    	print_r($var);
    }
?>
</body>
</html>
