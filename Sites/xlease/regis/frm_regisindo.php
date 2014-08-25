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
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
$(document).ready(function(){

    $("#idno").autocomplete({
        source: "gdata_idno.php",
        minLength:2
    });
});

</script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">
<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
<fieldset><legend><B>แก้ไขทะเบียน</B></legend>

 <form name="search" method="post" action="frm_regisedit.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><div><b>ค้นหาได้จาก</b> เลขที่สัญญา, ชื่อ-นามสกุลผู้เช่าซื้อ, ทะเบียนรถยนต์ , เลขตัวถัง</div>
        <div>
		<input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno" name="idno" size="60" value="<?php echo $_POST['h_arti_id']; ?>">
        <input type="submit" name="submit" value="   ค้นหา   ">
		</div>
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