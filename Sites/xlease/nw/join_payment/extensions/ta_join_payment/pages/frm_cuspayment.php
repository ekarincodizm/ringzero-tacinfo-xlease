<?php
session_start();

require_once("../../sys_setup.php");
include("../../../../../config/config.php");

$id_p_user=$_SESSION["av_iduser"];
   $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$id_p_user'");
   $res_userprofile=pg_fetch_array($res_profile);
   $_SESSION["fullname_user"] = $res_userprofile["fullname"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ค้นหาข้อมูลค่าเข้าร่วม</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    
    <script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

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
function ck_null(){

	if(document.getElementById('idno').value==""){alert("กรุณาใส่ข้อความที่จะค้นหา!!");return false;}

}
</script>
    
</head>
<body>
 
<fieldset><legend><B>ค้นหาข้อมูลค่าเข้าร่วม</B></legend>

 <form name="search" method="post" action="ta_join_payment_view_new.php" onSubmit="JavaScript:return ck_null();" >
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td><b>IDNO,ชื่อ/สกุล,ทะเบียนรถ</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno" name="idno" size="100" value="<?php echo $_POST['h_arti_id']; ?>">
        <input type="submit" name="submit" value="   ค้นหา   " >
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
