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
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        

<div class="wrapper">

<div style="float:right">
<input type="button" value="  Close  " onclick="javascript:window.close()">
</div> 
<div style="clear:both"></div>

<fieldset><legend><B>พิมพ์ใบเสร็จวิทยุ</B></legend>

 <form name="search" method="post" action="frm_recid_fr_type.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>เลขที่สัญญาวิทยุ, รหัสวิทยุ, ทะเบียนรถ </b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>" />
		<input name="frm_radio_fr" type="hidden"  value="frm_radio_fr" />
        <input type="text" id="idno" name="idno" size="60" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
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
        return "s_radio.php?q=" + this.value;
    });
}
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("idno","idno_names");
</script>

</body>
</html>