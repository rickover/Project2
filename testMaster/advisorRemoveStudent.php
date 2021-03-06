<?php

session_start();
?>

<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
<title>COEIT Advising Sign Up</title>
<!-- ============================================================== -->
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="description" content="UMBC Advising" />
<meta name="keywords" content="UMBC, Advising" />
<!-- ============================================================== -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">


    <!-- -->
    <link rel="stylesheet" href="https://cdn.rawgit.com/oneyoung/jquery-calendar/master/css/style.css" />
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/oneyoung/jquery-calendar/master/js/calendar.js"></script>
    
    <!-- Custom style for sign in -->
  <link href="css/signin.css" rel="stylesheet">

   <!-- Main Style -->
  <link href="css/main.css" rel="stylesheet">

   <!-- Timetable Style -->
  <link href="css/timetable.css" rel="stylesheet">
    
    <link rel="icon" type="image/png" href="icon.png" />
</head>


<body>

  <!--Navigation Bar-->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <img class="navbar-brand"  src="res/logo.png" >      
        </div>
        <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="advisorSetAvail.php">Add Availability</a></li>
            <li class="divider"></li>
            <li><a href="advisorChangeAvail.php">Edit Availability</a></li>
            <li class="divider"></li>
            <li><a href="advisorHome.php">Home</a></li>
            <li class="divider"></li>
            <li><a href="advisorIndex.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
      <div class="titleBar">
             <h2>COEIT Advisor Home Page</h2>
      </div>
       
    </div>
  </nav>
  
  <div class="container">
  
  <?php
  
  	include('CommonMethods.php');
    $debug = false;

    $COMMON = new Common($debug);
  
  	$studentIDforDelete = $_SESSION['studentIDforDelete'];
	
		 $sql = "SELECT `fname`, `lname`,`major` FROM `students` WHERE `studentID` = '$studentIDforDelete'";
	  $rs = $COMMON->executeQuery($sql,$_SERVER["SCRIPT_NAME"]);
	  
	  // index
	  //  0 == fname
	  //  1 == lname
	  //  2 == major
	  $studentInfoArray = mysql_fetch_row($rs);
	  $studentFname = $studentInfoArray[0]; 
	  $studentLname = $studentInfoArray[1];
	  $studentMajor = $studentInfoArray[2];
	
	
	echo("Student, <b>$studentFname $studentLname's</b>, appointment with ");
			
			//pulls appointment info from `appointments`
			$getAppt = "SELECT TIME_FORMAT(`time` , '%h:%i %p'),  DATE_FORMAT(  `date` ,  '%W %b. %d, %Y' ), `advisorID` FROM `appointments` WHERE `studentID` = '$studentIDforDelete'";
			$rsGetAppt = $COMMON->executeQuery($getAppt,$_SERVER["SCRIPT_NAME"]);
			$fetchGetAppt = mysql_fetch_row($rsGetAppt);
			
			//pulls advisor name for appointment info from `advisors`
			$getAdvisorName = "SELECT `fname`, `lname` FROM `advisors` WHERE `advisorID` = '$fetchGetAppt[2]'";
			$rsGetAdv = $COMMON->executeQuery($getAdvisorName,$_SERVER["SCRIPT_NAME"]);
			$fetchGetAdv = mysql_fetch_row($rsGetAdv);
			
		echo("<b>$fetchGetAdv[0] $fetchGetAdv[1] on ");
		
		echo("$fetchGetAppt[1] at $fetchGetAppt[0]</b> has successfully been deleted.");
	
	//updates appointments and deletes student from students
	$removeAppt = "UPDATE `appointments` SET `studentID` = NULL, `open` = 1 WHERE `studentID` = '$studentIDforDelete'";
	$rsRemove = $COMMON->executeQuery($removeAppt,$_SERVER["SCRIPT_NAME"]);
	
	$removeStudent = "DELETE FROM `students` WHERE `studentID` = '$studentIDforDelete'";
	$rsRemoveStudent = $COMMON->executeQuery($removeStudent,$_SERVER["SCRIPT_NAME"]);
	
	echo("<br><br>Please close this window by clicking the button below: ");
	
	echo("<div class='no-print'>");
echo("<br><button class='btn btn-m btn-warning' type='button' onclick='windowClose()' >Close This Window</button>");
echo("<br><br></div>");
	
  
  ?>
</div>
 <!-- /container -->


<!-- Load javascript required for Bootstrap animation-->
<script src="https://code.jquery.com/jquery.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script>
$(document).ready(function(){
		$(".advisorSlotOpen").click(function(){
			console.log("clicked");
		});
});
</script>

<script language="javascript" type="text/javascript"> 
function windowClose() { 
window.open('','_parent',''); 
window.close();
} 
</script>

<script type = "text/javascript" >
    history.pushState(null, null, 'advisorRemoveStudent.php');
    window.addEventListener('popstate', function(event) {
    	history.pushState(null, null, 'advisorRemoveStudent.php');
    });
	window.onpopstate=function()
	{
	  alert("Use of the back button has been disabled, please navigate the website using the links on the page.");
	}
</script>

</body>
</html>