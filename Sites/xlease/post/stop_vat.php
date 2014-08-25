<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
	 <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>  

<script language=javascript>
$(document).ready(function(){

    $("#idno").autocomplete({
        source: "gdata.php",
        minLength:2
    });
});

</script>	
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center" valign="top">
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<fieldset><legend><B>ค้นหาข้อมูล</B></legend>

 <form name="search" method="post" action="stop_vat_show.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>ตรวจสอบ IDNO</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno" name="idno" size="100" value="<?php echo $_POST['h_arti_id']; ?>">
        <input type="submit" name="submit" value="   ค้นหา   ">
      </td>
   </tr>
</table>
</form>

</fieldset> 

</div>
        </td>
    </tr>
</table>         
 
<script type="text/javascript">
/*
function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata_old.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("idno","idno_names");
*/
</script>

</body>
</html>