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
    <title><?php echo $_SESSION['session_company_name']; ?></title>
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

<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

<fieldset><legend><B>ตารางชำระเงิน ทางบัญชี</B></legend>
<br />
<table width="600" border="0" cellSpacing="0" cellPadding="3" align="center">
    <tr>
        <td>
<form name="frm_1" method="get" action="frm_print_accpayment.php" target="_blank">
รายปีลูกหนี้
<b>เลือกปี</b>
<select name="year">
<option value="">เลือก</option>
<?php
$nowyear = date("Y");
$year_a = $nowyear + 10; 
$year_b =  $nowyear - 10;

$s_b = $year_b+543;

while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
} 
?>
</select>
<input type="submit" value=" Print ">
</form>
        </td>
    </tr>
    <tr>
        <td>
<form name="frm_1" method="post" action="frm_print_accpayment.php?mode=1" target="_blank">
รายบุลคล
<b>ระบุ IDNO</b>
<input name="idno_names" type="hidden" id="idno_names" value="" />
<input type="text" id="idno" name="idno" size="50" value="">
<input type="submit" value=" Print ">
</form>
        </td>
    </tr>
</table>

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
        return "gdata_idno.php?q=" + this.value;
    });    
}    

make_autocom("idno","idno_names");
</script>

</body>
</html>