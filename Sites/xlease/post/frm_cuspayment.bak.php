<?php
session_start();
$_SESSION["ses_idno"] = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>    

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}

$(function(){
    $(window).bind("beforeunload",function(event){
        closeAll();
        return msg;
    });
});
</script>
    
</head>
<body>
 
 <?php include "menu.php"; ?>
 
<fieldset><legend><B>ค้นหาข้อมูล</B></legend>

 <form name="search" method="post" action="frm_viewcuspayment.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td><b>ตรวจสอบ IDNO</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno" name="idno" size="100" value="<?php echo $_POST['h_arti_id']; ?>">
        <input type="submit" name="submit" value="   ค้นหา   " onclick="javascript:reloadFrame(1);">
      </td>
   </tr>
</table>
</form>

</fieldset>
 
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
        return "gdata.php?q=" + this.value;
    });    
}    
 
make_autocom("idno","idno_names");
</script>

</body>
</html>