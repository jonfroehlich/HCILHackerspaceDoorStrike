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
if(!($_SESSION['permit'] or $COOKIE["loggedin"])){
  //printVar("would exit");
  header("Location: login.html");
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Accepted Users</title>
  <link href="css/bootstrap.css" rel="stylesheet"/>
  <link href="signin.css" rel="stylesheet"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://trirand.com/blog/jqgrid/js/jquery.js" type="text/javascript"></script>
<script src="http://trirand.com/blog/jqgrid/js/jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="http://trirand.com/blog/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
<script type="text/javascript">
$.jgrid.no_legacy_api = true;
$.jgrid.useJSON = true;
</script>
<script src="http://trirand.com/blog/jqgrid/js/ui.multiselect.js" type="text/javascript"></script>
<script src="http://trirand.com/blog/jqgrid/js/jquery.jqGrid.js" type="text/javascript"></script>
<script src="http://trirand.com/blog/jqgrid/js/jquery.tablednd.js" type="text/javascript"></script>
<script src="http://trirand.com/blog/jqgrid/js/jquery.contextmenu.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
  jQuery("#list").jqGrid({ 
      url: "getWhitelist.php",
        datatype: "json",
        mtype: "GET",
      colNames:['Name','Card', 'Email', 'Admin'], 
      colModel:[ 
          {name:'name',index:'name', width:300}, 
          {name:'card',index:'card', width:300},
          {name:'email',index:'email',width:300},
          {name:'admin',index:'admin',width:100}
      ], 
      rowNum:100, 
      height: 240,
      sortname: 'id', 
      autowidth: true,
      shrinkToFit: false,
      sortorder: "desc", 
      multiselect: true, 
      caption:"" 
    });
}); 
</script>
<script>
  $(document).ready(function(){
    $('#MyButton').click(function(){
    selected = jQuery("#list").jqGrid('getGridParam','selarrrow');
    var out=[];
    for(i=0; i<selected.length; i++){
      out[out.length]=jQuery('#list').jqGrid ('getCell', selected[i], 'card');
    }
    $.post("removeUser.php", {id: out}, function(data){location.reload();});
    //window.location.href='removeUser.php?id='+out[0];
    // $.post("addUser.php", {dataArray: out}, success: function(){
    //   window.location.href='adduser.php';
    // }
    });
    $('#makeAdmin').click(function(){
    selected = jQuery("#list").jqGrid('getGridParam','selarrrow');
    var out=[];
    for(i=0; i<selected.length; i++){
      out[out.length]=jQuery('#list').jqGrid('getCell', selected[i], 'card');
    }
    //window.location.href='quickRemove2.php?id='+out[0];
    $.post("makeAdmin.php", {id: out}, function(data){location.reload();});
    });

    $('#removeAdmin').click(function(){
    selected = jQuery("#list").jqGrid('getGridParam','selarrrow');
    var out=[];
    for(i=0; i<selected.length; i++){
      out[out.length]=jQuery('#list').jqGrid('getCell', selected[i], 'card');
    }
    $.post("removeAdmin.php", {id: out}, function(data){location.reload();});
    //window.location.href='addAdmin.php?id='+out[0];
    // $.post("addUser.php", {dataArray: out}, success: function(){
    //   window.location.href='adduser.php';
    // }
    });
    });
</script>
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
              <li><a href="log.php">Log</a></li>
              <li class="active"><a href="acceptedUsers.php">Accepted Users</a></li>
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
    <h1 style = "text-align: center"><small>Hackerspace Card Swipe Requests for Access</small></h1>
    <p>&nbsp;</p>
    <div class="row">
      <div class="col-md-4 col-md-offset-4">   
        <button class="btn btn-lg btn-primary btn-block" id="MyButton">Remove Selected Users</button>
        <button class="btn btn-lg btn-primary btn-block" id="makeAdmin">Make User Admin</button>
        <button class="btn btn-lg btn-primary btn-block" id="removeAdmin">Remove Admin Status</button>
      </div>
      <div class="cold-md-4 cold-md-offset-2">
	<table id="list" class="table"><tr><td></td></tr></table> 
      </div>
    </div>
  </div>
</body>
</html>