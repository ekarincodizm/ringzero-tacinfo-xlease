<?php

require_once "cal_config.inc.php";


# ตั้งค่าตัวแปร
if (!isset($_GET['op']))
  $op = '';
else
  $op = $_GET['op'];

$m = date("n");
$y = date("Y");
$d = date("j");

if (!isset($_GET['day']))
  $day = '';
else
  $day = date("j",mktime(0,0,0,$m,intval($_GET['day']),$y));
if (!isset($_GET['month']))
  $month = '';
else 
  $month = date("n",mktime(0,0,0,intval($_GET['month']),1,$y));
if (!isset($_GET['year']))
  $year = '';
else
  $year = date("Y",mktime(0,0,0,$m,$d,intval($_GET['year'])));

if (!isset($_GET['date']))
  $date = '';
else
  $date = $_GET['date'];
if (!isset($_GET['ask']))
  $ask = '';
else
  $ask = $_GET['ask'];
if (!isset($_GET['id']))
  $id = '';
else
  $id = preg_replace("/[';]/", "", $_GET['id']);


if ((date("G")+$timezone)>24) {
	$d = date("j",mktime(0,0,0,$m,date("j")+1,$y)) ;
	$m = date("n",mktime(0,0,0,$m,date("j")+1,$y)) ;
	$y = date("Y",mktime(0,0,0,$m,date("j")+1,$y)) ;
	}

if ((date("G")+$timezone)<0) {
	$d = date("j",mktime(0,0,0,$m,date("j")-1,$y)) ;
	$m = date("n",mktime(0,0,0,$m,date("j")-1,$y)) ;
	$y = date("Y",mktime(0,0,0,$m,date("j")-1,$y)) ;
}

// check if there is a week 53 to display based on weekstartday
function showWeek53($cyear) {
	//$ShowWeek53 = false ;
	$weeknum = weekNumber(31,12,$cyear) ;
	if ($weeknum>52) $ShowWeek53 = $weeknum ;
	settype($ShowWeek53,"integer");
	return $ShowWeek53 ;
}

// weeknumber according to standard ISO 8601
function weekNumber($wday,$wmonth,$wyear) {
global $weekstartday ;
	if (substr($wmonth,0,1) == "0"){ $wmonth = str_replace("0","",$wmonth);}
	if (substr($wday,0,1) == "0"){ $wday = str_replace("0","",$wday);}
	$WeekNumber = 1;
	$firstdayofyear = date("w", mktime(0,0,0,1,1,$wyear)) ;
	$dayoffset = $firstdayofyear + 6; 
	$WeekNumber = floor(((date("z", mktime(0,0,0,$wmonth,$wday,$wyear)))+$dayoffset) / 7) ;
	settype($WeekNumber,"integer");
	if ($firstdayofyear>5) $WeekNumber = $WeekNumber - 1;
	if (($firstdayofyear<5)&&($WeekNumber==53)) {
		if (date("w", mktime(0,0,0,12,31,$wyear))>4) $WeekNumber=53;
		else $WeekNumber = 1;
	}
	return $WeekNumber;
}

// variables used for dynamically generating javascript redirects
	$hd = $d ;
	$hm = $m ;
	$hy = $y ;
	if ($date){
	  if ($day=='') $hd = substr($date,8,2) ;
	  else $hd = $day ;
	  if ($month=='') $hm = substr($date,5,2) ;
	  else $hm = $month ;
	  if ($year=='') $hy = substr($date,0,4) ;
	  else $hy = $year ;
	}
	if ($op=="cal") { $hm = $month ; $hy = $year ; } 	// to handle monthly views
	// ensure it does not exceed the max or min year set so scripts cannot jump beyond the restricted dates
	if ($hy>($y+$caladvanceyear)) { $hy = $y+$caladvanceyear ; $hm = 12 ; $hd = 31 ; }
	if ($hy<$calstartyear) { $hy = $calstartyear ; $hm = 1 ; $hd = 1 ; }

?>
<html>
<head>
  <meta http-equiv="Content-Type" CONTENT="text/html; ">
  <meta http-equiv="refresh" content="60">
  <title>Calendar Events</title>
	

<?php
// ใช้สำหรับเลือกสัปดาห์
$weeknumber = weekNumber($hd,$hm,$hy) ;
echo "<script language=\"JavaScript\" type=\"text/JavaScript\">" ;
	echo "function gotoweek(selectopt) {" ;
		echo "var geti = '1' ;" ;
			echo "for (i=0;i<selectopt.options.length;i++) {" ;
				echo "if (selectopt.options[i].selected) {" ;
				echo "geti = selectopt.options[i].value ;" ;
						echo "break ;" ;
						echo "}" ;
					echo "}" ;
							echo "switch (geti) { " ;
								for ($i=1;$i<=54;++$i) {				// maximum ever number of weeks is 54!
								  echo "case \"".$i."\" : { window.location.href='frm_cal_week.php?op=week&date=" ;
								  $totaldays = ($i - $weeknumber) * 7 + $hd;
								  echo date("Y-m-d", mktime(0,0,0,$hm,$totaldays,$hy)) ;
								  echo "' ; break ;} " ;
								}
							echo "default : { window.location.href='frm_cal_week.php?op=week&date=".date("Y-m-d", mktime(0,0,0,$hm,$hd,$hy))."' ; break;}" ;
						echo "}" ;
	echo "}" ;
echo "</script>" ;

// สำหรับคลิกเลือกเดือน
echo "<script language=\"JavaScript\" type=\"text/JavaScript\">" ;
	echo "function gotomonth(selectopt) {" ;
		echo "var geti = '1' ;" ;
		echo "for (i=0;i<selectopt.options.length;i++) {" ;
			echo "if (selectopt.options[i].selected) {" ;
				echo "geti = selectopt.options[i].value ;" ;
					echo "break ;" ;
				echo "}" ;
		echo "}" ;
				echo "switch (geti) {" ;
						for ($i=1;$i<13;$i++) {
							echo "  case \"".$i."\" : { window.location.href='frm_cal_week.php?op=cal&month=".$i."&year=".$hy."' ; break ;}" ;
						}
				echo "  default : { window.location.href='frm_cal_week.php?op=cal&month=".$hm."&year=".$hy."' ; break;}" ;
			echo "  }" ;
	echo "}" ;
echo "</script>" ;

?>

</head>
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>







