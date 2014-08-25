<?php
session_start();
include("../../config/config.php");
$language_user=$_SESSION['language'];
$industry = $_POST["industry"];
$type = $_GET["type"];


if($language_user=='TH'){	
	include("../../language/landTH.php");
}
else if($language_user=='LO'){	
	include("../../language/landLO.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $land_industrial_pageAdd_title; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm1.industry.value=="") {
		theMessage = theMessage + "\n -->  <?php echo $land_industrial_pageAdd_theMessage_industry;?>";		
	}
	
	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<form name="frm1" method="post" action="process_add.php">
<input type="hidden" name="type" value="<?php echo $type; ?>">
	<center><h2><?php echo $land_industrial_pageAdd_h2; ?></h2></center>
	<center>
	<?php echo $land_industrial_pageAdd_txt_industry; ?><input type="text" name="industry" size="40" value="<?php echo $industry; ?>">
	<br><br>
	<input type="submit" name="add" value="<?php echo  $land_global_btn_OK; ?>" onclick="return validate();"> &nbsp;&nbsp;&nbsp; 
	<input type="button" value="<?php echo$land_global_btn_cancel.'/'.$land_global_btn_off; ?>" onclick="javascript:window.close();">
	</center>
</form>
</body>
</html>