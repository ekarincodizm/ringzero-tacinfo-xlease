<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$auto_id=$_GET["auto_id"];
$statusreq=$_GET["statusreq"];

//ค้นหาเลขที่โฉนดขึ้นมาแสดง
$qry=pg_query("select \"refDeedContract\" from thcap_insure_checkchip where auto_id='$auto_id'");
list($refDeedContract)=pg_fetch_array($qry);

$qry2=pg_query("select \"numDeed\" from nw_securities where cast(\"securID\" as character varying(20))='$refDeedContract'");
list($numDeed)=pg_fetch_array($qry2);

$txtshow="ประกันใหม่เลขที่โฉนด $numDeed";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
$(document).ready(function(){

    $("#idno").autocomplete({
        source: "s_contract.php",
        minLength:2
    });
});

</script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1>สร้างใบคำขอ</h1></div>

<div class="wrapper">
<div align="right"><input type="button" value="  กลับ  " onclick="location='frm_IndexChip.php'"></div> 
<fieldset><legend><B>ค้นหาเลขที่สัญญา (<?php echo $txtshow;?>)</B></legend>

<form name="search" method="post" action="frm_CreateRequest.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>เลขที่สัญญา</b>
        <input type="text" id="idno" name="idno" size="60" value="<?php echo $_POST['h_arti_id']; ?>">
        <input type="hidden" name="auto_id" value="<?php echo $auto_id;?>">
		<input type="hidden" name="statusreq" value="<?php echo $statusreq;?>">
		<input type="submit" name="submit" value="   NEXT   ">
      </td>
   </tr>
</table>
</form>

</fieldset> 

</div>
        </td>
    </tr>
</table>         


</body>
</html>