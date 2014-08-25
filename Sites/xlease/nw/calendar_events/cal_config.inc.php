<?php
$userlogin = 1 ;	// note that if $userlogin = 0, $userview must be 0 but if $userlogin = 1, $userview can be anything.
$userview = 0 ;		// each user views user's events only 
			// requires login and not applicable to administrators; 1 = yes, 0 = no 
$publicview = 1 ;	// allow public to view calendar without login but not allowed to add events; 1 = yes, 0 = no
			// note that $userview = '0' if you want public to view events when $publicview = 1. 
$charset = 'UTF-8';


$caldefault = 2;



$popupevent = 1; 			// is event in popup-screen(1) or just url(0)
$popupeventheight = '400'; 	// height of the popup-screen 
$popupeventwidth = '400';  	// width of the popup-screen 
$addeventwin_w = '500' ;  	// add event window width size for displaying event information. 
$addeventwin_h = '550' ;  	// popup window height size for displaying event information. 

$caleventapprove = 1; 		// automatically approve events (approved by admin) entered in user calendar; 1 = yes, 0 = no
$caleventadminapprove = 1; 	// automatically approve events entered in admin calendar; 1 = yes, 0 = no

$administrationok = 1; 	// allow administration url

$addeventok = 1; 		// allow add event 
$viewcatsok = 1; 		// allow view categories
$viewdayok = 1;  		// allow view by day 
$viewweekok = 1; 		// allow view by week
$viewcalok = 1;  		// allow view month
$viewevlistok = 1;	// allow view of events listing forward
$eventcatfilter = 1;	// allow events to be viewed filtered by categories

$allowsearch = 1;		// allow search 
$vieweventok = 1;     	// search on view individual view 
$searchcatsok = 1;    	// search on view of categories 
$searchdayok = 1;     	// search on view events by day 
$searchweekok = 1;    	// search on view events by week 
$searchmonthok = 1;   	// search on view events by month 

$allowuserdel = 1 ;	// allow user to delete own events in user calendar; 1 = yes, 0 = no 
$showuserentry = 0 ;    // show username of event entered in user calendar, shown within "< >"; 1 = yes, 0 = no

$viewtodaydate = 1;   	// view today date at the top 
$notimeentry = 0;		// not have time entry in creating/displaying events.; 1 = yes, 0 = no 
$time12hour = 0	;	// show the time as 12-hour format; 1 = yes, 0 = no

$showeventstats = 1 ;   // whether to show event statistics at top; 1 = yes, 0 = no 
$showcompanyname = 0 ;  // whether to show company name at top; 1 = yes, 0 = no 
$showcalendarname = 1 ; // whether to show calendar name at top; 1 = yes, 0 = no 
$weekstartday = 0 ;     // define day the week starts; 0=sunday, 1=monday ... 6=saturday 
$calstartyear = 2008 ;  // year for which calendar is valid
$caladvanceyear = 2 ;   // number of years more from current year for which calendar is valid, from 0 or more 

$timezone = 0 ;		// difference in timezone hours with respect to host server time from user's client time. 

$defaultcat = 'General';	// default category for adding event

$mailevent = 0 ;	// whether to email every event added to calendar by user to $emailrcpt; 0=no, 1=yes 
$emailrcpt = 'ardsri.s@gmail.com' ;	// receipient's email address for events posted if $mailevent is active 

$limitmthevt = 4 ;	// Display sum of events for month view for events >= $limitmthevt in a day; 0=off, 1=always 
$shortdesclen = 650 ;	// text length to display for short description in week and day calendar views. 
$limitrow = 10 ;		// Limit the number of rows displayed per page in historical items.

?>
