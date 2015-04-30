<?php

session_start();
?>

<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
<title>CMEE Advisor Home Page</title>
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
            <li><a href="advisorSetAvail.php">Set Availability</a></li>
            <li class="divider"></li>
            <li><a href="advisorHome.php">Home</a></li>
            <li class="divider"></li>
            <li><a href="advisorIndex.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
      <div class="titleBar">
             <h2>CMEE Advisor Home Page</h2>
      </div>
       
    </div>
  </nav>

<div class="container">
<div class="tableBar">
<?php

echo("<html><head><title></title>Set Availability For:</head><body>");

$debug = false;
include('CommonMethods.php');
$COMMON = new Common($debug); // common methods
$sql = "select fName, lName from advisors";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
$advName;
$advFullName;
$advFName;
$advLName;
$space=' ';
$maj;
$num=0;

$sqlRange = "SELECT MAX(`date`) FROM  `apptTimes` WHERE 1 GROUP BY  `date`";
$rsGetRange = $COMMON->executeQuery($sqlRange,$_SERVER["SCRIPT_NAME"]);
$fetchRange = mysql_fetch_row($rsGetRange);

$startDay = $fetchRange[0];
//echo("$startDay<br>");
$endDay = "";

while ($fetchRange = mysql_fetch_row($rsGetRange))
{
	$endDay = $fetchRange[0];	
}

$sdate=Date("$startDay");
$cdate=$sdate;
$edate=Date("$endDay");

$Tsql = "SELECT time_format(`time`,'%h:%i %p'), `time` FROM `times` WHERE 1;";
$Trs = $COMMON->executeQuery($Tsql, $_SERVER["SCRIPT_NAME"]);

$array = array();

$count = 0;

while ($trow = mysql_fetch_row($Trs)){
	$array[$count]=$trow;
	$count++;
}

echo("<form action='updateAdvisorAvail.php' method='post' name='form1'>");
echo("<select name='advisor'>");

while($row = mysql_fetch_row($rs))
{
     $advFullName=$row[0].$space.$row[1];
     $advName=$row[0] . " " . $row[1];
     echo("<option value='");
     echo("$advName'");
     echo(">" . $advName . "</option>");
}
echo("</select>");
echo("<br>");

echo(" On ");//same as 
echo("<input type='checkbox' name='mon' value='1' />Monday ");
echo("<input type='checkbox' name='tue' value='2' />Tuesday ");
echo("<input type='checkbox' name='wed' value='3' />Wednesday ");
echo("<input type='checkbox' name='thr' value='4' />Thursday ");
echo("<input type='checkbox' name='fri' value='5' />Friday ");

echo("<br>");
echo(" Between: ");
echo("<select name='startdate'>");
while($cdate < $edate){
	
	$i=0;
	while($i<7){
		echo("<option value='");
		echo("$cdate'");
		echo(">" . $cdate . "</option>");
		$cdate = date('Y-m-d', strtotime($cdate . ' + 1 day'));
		$i=$i+1;
	}
	$cdate = date('Y-m-d', strtotime($cdate . ' + 1 day'));
	$cdate = date('Y-m-d', strtotime($cdate . ' + 1 day'));
}
echo("</select>");

$cdate=$sdate;
echo(" And: ");
echo("<select name='enddate'>");
while($cdate < $edate){
	
	$i=0;
	while($i<7){
		echo("<option value='");
		echo("$cdate'");
		echo(">" . $cdate . "</option>");
		$cdate = date('Y-m-d', strtotime($cdate . ' + 1 day'));
		$i=$i+1;
	}
	$cdate = date('Y-m-d', strtotime($cdate . ' + 1 day'));
	$cdate = date('Y-m-d', strtotime($cdate . ' + 1 day'));
}
echo("</select>");


$sql = "select major from majors";
echo("<table width='100%'><tr><th>Start Time</th><th>Capacity</th><th>Major</th></tr>");

while($num < $count){
	echo("<tr><td>"); echo($array[$num][0]); echo("</td><td>");
	echo("<select name='cap$num'>");
	$i=0;
	while($i<12){
		echo("<option value='");
		echo("$i'");
		echo(">" . $i . "</option>");
		$i=$i+1;
	}
	echo("</select>");
	echo("</td><td>");
	echo("<select name='major$num'><option value=any>any</option>");
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	while($row = mysql_fetch_row($rs)){
		$maj=$row[0];
		echo("<option value='");
		echo("$maj'");
		echo(">" . $maj . "</option>");
	}
	echo("</select>");
	echo("</td></tr>");
	$num = $num + 1;
}

echo("</table>");

echo("<button class='btn btn-sm btn-primary' type='submit' >Update Availability</button>");
echo("</form>");

?>
</div>
</div>

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

</body>
</html>