<?php
/******************************************/
/* functions to control and display time  */
/******************************************/
function show12hour($hour24) {
	if ($hour24=='') return '' ;
	if (intval(substr($hour24,0,2)) < 12){
  	  if (intval(substr($hour24,0,2)) == "0") 
		$hour12 = substr_replace($hour24,"12",0,2);
	  else $hour12 = $hour24 ;
	  $hrnum = intval(substr($hour12,0,2)) ;
	  $hrstr = strval($hrnum) ;
	  $hour12 = substr_replace($hour12,$hrstr,0,2)."am" ;		
	  }
	else {
	  if (intval(substr($hour24,0,2)) > 12)
	    $hrnum = intval(substr($hour24,0,2)) - 12 ;
	  else 
	    $hrnum = intval(substr($hour24,0,2)) ;
	  $hrstr = strval($hrnum) ;
	  $hour12 = substr_replace($hour24,$hrstr,0,2)."pm" ;
	  }
	return $hour12 ;
}

function convert12to24($hour12,$dayperiod) {
	if ($hour12=='') return '' ;
	if (($dayperiod=='am')&&($hour12=="12")) $hour24 = "00" ;
	else if ($dayperiod=='am') $hour24 = $hour12 ;
	if ($dayperiod=='pm') {
	  if (intval(substr($hour12,0,2)) < 12) {
	    $hrnum = intval(substr($hour12,0,2)) + 12 ;
	    $hrstr = strval($hrnum) ;
	    $hour24 = $hrstr ;	
	    }
	  else $hour24 = $hour12 ;
	  }
	return $hour24 ;
}

// Display time (orientation - 0: horizontal, 1: vertical, 2: with text)
function showtime($atime,$btime,$orientation) {
global $time12hour;
	if ($orientation==0) {
  	echo "<b>" ;
  	if ($time12hour==1) echo show12hour($atime) ;
  	else echo $atime ;
  	if ($btime!='') {
		echo "&nbsp;-&nbsp;" ;
  		if ($time12hour==1) echo show12hour($btime) ;
		else echo $btime ;
  		echo " &nbsp; &nbsp; </b>" ;
		}
  	else echo " &nbsp; &nbsp; </b>" ;
 }
	else if ($orientation==1) {
		if ($time12hour==1) echo show12hour($atime) ;
		else echo $atime ;
		if ($btime!='') {
			echo "<br/>" ;
			if ($time12hour==1) echo show12hour($btime) ;
			else echo $btime ;
	  		}
 }
	else if ($orientation==2) {
	  if ($atime!="") {
  		echo "&nbsp; &nbsp; &nbsp; <b>".translate("From")." : </b>" ;
  		if ($time12hour==1) echo show12hour($atime) ;
  		else echo $atime ;
  		echo "\n";
	  if ($btime!="") {
  	    echo "&nbsp;&nbsp;<b>".translate("To")." : </b>" ;
  	    if ($time12hour==1) echo show12hour($btime) ;
  	    else echo $btime ;
  	    echo "\n";
	    }
	}
 }
}

/**********************************************************/
/* functions to calculate calendar numbers for month view */
/**********************************************************/

// Reduce the number to within the week range of 7 days
function dayinweek($num)
  {
  while ($num>7) $num = $num-7 ;
  return $num ;
  }

// Provide the number of blank days before the first day of month based on week start day
function blankdays($wsd,$fsd) {
  if ($wsd==$fsd) return 0 ;
  else if ($wsd>$fsd) return (7-$wsd+$fsd) ;
  else if ($fsd>$wsd) return ($fsd-$wsd) ;
}

function smallmonth($smmonth,$smyear,$showyear,$ud,$um,$uy,$showeventcount) {
global $EVENTS_TB,$USER_TB,$CAT_TB,$mth,$week,$todayclr,$viewcalok,$userid,$userview,$userlogin,$uname,$ugroup,$weekstartday,$overlibbgclr,$catview,$notimeentry ;

  // number of days in month
  $firstday = date ("w", mktime(12,0,0,$smmonth,1,$smyear));
  $nr = date("t",mktime(12,0,0,$smmonth,1,$smyear));
  echo "\n\n<table class='smallcalmth' align='center'>";
  echo "<tr>";
  echo "<td align='center' width='98%'><div class='smallcalmth'>";
  if ($viewcalok==1) {
    echo "<a class='smallcalmth' href=\"#\" onclick='Javascript:top.opener.window.location = \"calendar.php?op=cal&amp;month=".$smmonth."&amp;year=".$smyear."\";'>" ;
    }
    echo $mth[$smmonth] ;
  if ($viewcalok==1) echo "</a> " ;
  if ($showyear) echo $smyear;
  echo "</div></td></tr></table>\n\n";

  echo "<table align='center' class='smallcalmth'>";
  echo "<tr>";

  // make the header days of week
  for ($i=intval($weekstartday)+1;$i<=(intval($weekstartday) + 7);$i++){
    echo "<td align='center' width='14%'>";
    if (dayinweek($i) == 1)
      echo "<div class='dayfont'>".substr($week[dayinweek($i)],0,3)."</div></td>"; // sunday
    else    
      echo "<div class='dayfont'>".substr($week[dayinweek($i)],0,3)."</div></td>"; // rest of week
    }
  echo "</tr>\n\n<tr>";

  // print initial blank squares
  for ($i=1;$i<=blankdays(intval($weekstartday),$firstday);$i++)  echo "<td>&nbsp;</td>";
  $a=0;
  for ($i=1;$i<=$nr;$i++){
    // now get eventual events on $i 
    $query = "select id,user,title,starttime,endtime from ".$EVENTS_TB." left join ".$CAT_TB." on ".$EVENTS_TB.".cat=".$CAT_TB.".cat_id where day='$i' and month='$smmonth' and year='$smyear' and approved='1' " ;

    if ($catview!=0) $query = $query." and ".$EVENTS_TB.".cat=$catview " ;
    if (($userview==1)&&($userlogin==1)) {  // user view user specific events only
    if ($ugroup!=0) $query = $query." and ".$EVENTS_TB.".user='".$uname."' ";
    }

  $query = $query." order by day,month,year,starttime,title ASC";
  $result = mysql_query($query);
  $devtcnt = mysql_num_rows($result) ;

  echo "<td align='center' ";
  if ($i == $ud && $smmonth == $um && $smyear == $uy) // highlight today's day
    echo "class='todayclr' ";
  elseif ($devtcnt!=0)
    echo "class='smallcalmth' ";
  echo "valign='top'>\n";

  if ($devtcnt!=0) {
    // overlib line
    echo "<a href=\"javascript:void(0);\" onmouseover=\"return overlib('";
    echo "<table border=0 cellspacing=0 cellpadding=0>" ;
    while ($row = mysql_fetch_object($result)){
	echo "<tr><td align=center valign=top>" ;
      echo "<table class=eventborder cellspacing=0 cellpadding=0><tr>";
	
	if ($notimeentry==0) {
	  echo "<td align=center class=eventtimeborder valign=top>" ;
	  echo "<div class=smallcalevtime>";
	  if ($row->starttime=='') echo "&nbsp; &nbsp;" ;
	  else showtime($row->starttime,$row->endtime,1);
	  echo "</div>" ;
	  echo "</td>" ;
	}
	echo "<td align=left valign=top width=80% class=eventborder>" ;
	echo "<div class=smallcalev>";
      echo subquot(stripslashes($row->title));
	echo "</div>" ;

	echo "</td></tr>" ;
	echo "</table>" ;
    }
  echo "</td></tr>";
  echo "</table>" ;
  // overlib line
  echo "',FGCOLOR,'$overlibbgclr',TEXTSIZE,'1',WIDTH,'120',VAUTO,HAUTO);\" onmouseout=\"return nd();\">";
  }

  echo "<div class='datenumfont'>".$i."</div>" ;
  if ($devtcnt!=0) echo "</a>";
  echo "</td>\n";
  $a++;
  if (blankdays(intval($weekstartday),date("w",mktime(0,0,0,$smmonth,$i,$smyear))) == 6) {
    echo "</tr>\n\n";
    if (($a!=0)&&($i!=$nr)) echo "<tr>";
    $a = 0;
    }
  }
  if ($a!=0) {
    for ($i=$a;$i<7;$i++) echo "<td>&nbsp;</td>";
    echo "</tr>";
  }

  if ($showeventcount) {
    echo "<tr><td align='center' valign='top' colspan='7'>" ;
    // get total number of events for month
    $tquery = "select id from ".$EVENTS_TB." where ".$EVENTS_TB.".month='".$smmonth."' and ".$EVENTS_TB.".year='".$smyear."' and ".$EVENTS_TB.".approved='1' " ;
    $normuser = false ;
    if (($userview==1)&&($userlogin==1)&&($uname!="")) {  // view user specific events only
      $uquery = "select group_id from ".$USER_TB." where ".$USER_TB.".user_id=".$userid ;
      $uresult = mysql_query($uquery) ;
      $urow = mysql_fetch_object($uresult);
      if ($urow->group_id!=0) {
        $normuser = true ;
        $tquery = "select * from ".$EVENTS_TB.",".$USER_TB." where ".$EVENTS_TB.".month='".$smmonth."' and ".$EVENTS_TB.".year='".$smyear."' and ".$EVENTS_TB.".approved='1' and ".$EVENTS_TB.".user=".$USER_TB.".username and ".$USER_TB.".user_id=".$userid ;
        }
      }
    $tresult = mysql_query($tquery);
    $trows = mysql_num_rows($tresult) ;
    echo "<table class='txtbox' cellspacing='0' cellpadding='0'><tr><td align='center'><div class='normalfont'> &nbsp;".$trows." ".translate("events")."&nbsp; </div></td></tr></table>" ;
    echo "</td></tr>" ;
    }
  echo "</table>";
}


/************************/
/* get event statistics แสดงปฏิทินการนัดหมาย */
/************************/
function getEventStats($day_i,$month_i,$year_i){
global $CAT_TB,$EVENTS_TB,$USER_TB,$showeventstats,$userid,$userview,$userlogin,$catview,$ugroup,$uname,$caleventapprove;
  // now get number of events on $day_i approved
  if ($showeventstats==1) {
     $query = "select count(id) from ".$EVENTS_TB." left join ".$CAT_TB." on ".$EVENTS_TB.".cat=".$CAT_TB.".cat_id where day='$day_i' and month='$month_i' and year='$year_i' and approved='1' " ;
     if (($userview==1)&&($userlogin==1)) {  // view user specific events only
     if ($ugroup!=0) $query = $query." and ".$EVENTS_TB.".user='".$uname."' ";
     }
  $result = mysql_query($query);
  $row = mysql_fetch_row($result) ;
  echo "<b>".$row[0]."</b> ".translate("confirmed events for today")." <br/>" ;
  if ($caleventapprove==0) {
    // now get number of events on $day_i not approved
    $query = "select count(id) from ".$EVENTS_TB." left join ".$CAT_TB." on ".$EVENTS_TB.".cat=".$CAT_TB.".cat_id where approved='0' order by day,month,year ASC";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result) ;
    echo "<b>".$row[0]."</b> ".translate("events awaiting approval") ;
    }
  }
}

/*************************************/
/* Function to replace "' to &quot;  ถ้าเอาออกจะไม่แสดงเต็มตาราง */
/*************************************/
function subquot($in) {
  // used by smallmonth
  $dnotfound = false ;
  $snotfound = false ;
  $out = $in ;
  $dpos = strpos($in,"\"");
  if ($dpos===false) $dnotfound = true ;
  while (!$dnotfound) {
	$out = substr($in,0,$dpos)."&quot;".substr($in,$dpos+1,strlen($in)-$dpos-1);
	$in = $out ;
	$dpos = strpos($in,"\"");
	$spos = strpos($in,"'");
	if ($dpos===false) $dnotfound = true ;
	if ($spos===false) $snotfound = true ;
  }
  $spos = strpos($in,"'");
  if ($spos===false) $snotfound = true ;
  while (!$snotfound) {
	$out = substr($in,0,$spos)."&amp;#39;".substr($in,$spos+1,strlen($in)-$spos-1);
	$in = $out ;
	$spos = strpos($in,"'");
	if ($spos===false) $snotfound = true ;
  }
  return $out ;
}

/*********************************/
/* Header functions for calendar */
/*********************************/

function calHeaderFunc(){
global $viewtodaydate,$week,$mth,$d,$m,$y,$caleventapprove,$viewcalok,$viewweekok,$viewdayok,$viewcatsok,$viewevlistok,$addeventok,$addeventwin_w,$addeventwin_h,$administrationok,$catview,$uname,$publicview;

# today's date
if ($viewtodaydate == 1){
  $weekday = date ("w", mktime(12,0,0,$m,$d,$y));
  $weekday++;
  echo "\n\n<table class=todaytop align=center border=0><tr><td align=left valign=top width='38%'>" ;
  echo ucfirst($week[$weekday])." ".$d." ".ucfirst($mth[$m])." ".$y; 
  echo "</td>" ;
  echo "<td align=center valign=top width='24%'>";
  getEventStats($d,$m,$y) ;
  echo "</td>" ;
  echo "<td align=right valign=top width='38%'>";
  if (($publicview==0)||(($publicview==1)&&($uname!=""))) {
    if ($caleventapprove==0) 
	echo translate("Approval needed for posting of events.")."<br/>" ;
    else 
      echo translate("Events added will be posted immediately.")."<br/>" ;
    }
// display calyear
//echo " &nbsp; <a href='#' onclick=\"Javascript:wopen('yearcal.php?op=yearcal&amp;ycyear=$y','popupyear',610,600,'yes');\"><img src=\"images/year.gif\" border=0 alt='".translate("Current Year")."' width=16 height=16/></a>";

if ($viewcalok==1){
  echo " &nbsp; <a href='calendar.php?op=cal&amp;month=$m&amp;year=$y&amp;catview=$catview'><img src=\"images/month.gif\" border=0 alt='".translate("Current Month")."' width=16 height=16/></a>";
}
if ($viewweekok==1){
echo " &nbsp; <a href='cal_week.php?op=week&amp;catview=$catview'><img src=\"images/week.gif\" border=0 alt='".translate("Current Week")."' width=16 height=16/></a>";
}
if ($viewdayok==1){
echo " &nbsp; <a href='cal_day.php?op=day&amp;catview=$catview'><img src=\"images/today.gif\" border=0 alt='".translate("Today")."' width=16 height=16/></a>";
}
if ($viewevlistok==1){
echo " &nbsp; <a href='cal_list.php?op=evlist&amp;catview=$catview'><img src=\"images/list.gif\" border=0 alt='".translate("Coming Events")."' width=16 height=16/></a>";
}
//========= display for catagory =========//
if ($viewcatsok==1){
//echo " &nbsp; <a href='cal_cat.php?op=cats&amp;catview=$catview'><img src=\"images/category.gif\" border=0 alt='".translate("Categories")."' width=16 height=16/></a>";
}

if ((($publicview==1)&&($uname!=""))||($publicview==0)) {
  if ($addeventok==1){
    echo " &nbsp; <a href='#' onclick=\"Javascript:wopen('cal_addevent.php?op=eventform&catview=$catview','popupwin',".$addeventwin_w.",".$addeventwin_h.",'yes');\"><img src=\"images/add.gif\" border=0 alt='".translate("Add Event")."' width=16 height=16/></a>";
  }
}
//======== display for admin ========//
if ($administrationok==1){
 // echo " &nbsp; <a href=\"admin/calendar.php\"><img src=\"images/admin.gif\" border=0 alt='Administration' width=16 height=16/></a>" ;
  }

echo " &nbsp; </td></tr></table>\n\n";
}

}

?>
