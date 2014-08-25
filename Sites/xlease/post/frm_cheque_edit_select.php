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
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">

<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 

<fieldset><legend><B>แก้ไขรายการเช็ค</B></legend>

 <form name="search" method="post" action="frm_cheque_edit_detail.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>เลขที่เช็ค</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno" name="idno" size="80" value="<?php echo $_POST['h_arti_id']; ?>">
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
        return "gcheque.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("idno","idno_names");
</script>

</body>
</html>