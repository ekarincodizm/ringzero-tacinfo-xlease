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

<script language="javascript">
function fncSubmit(){
    document.getElementById('rpt_frm').submit();
    document.getElementById('btn1').disabled = true;
    document.getElementById('btn1').value = "กรุณารอสักครู่...";
}
</script>


</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td>

<div align="right"><br><input type="button" value="  Close  " onclick="javascript:window.close();"></div>        

<div class="wrapper">

<fieldset><legend><B> พิมพ์รายงาน ภาษีขาย</B></legend>
<form id="rpt_frm" name="rpt_frm" method="post" action="frm_rpt_vat_example.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
    <tr>
      <td align="center">
เดือน
<select name="mm">
<?php
$nowmonth = date("m");
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
    
}
?>    
</select>
ปี 
<select name="yy">
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
      </td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td><br><input type="button" id="btn1" name="btn1" value="   Print   " onclick="javascript:fncSubmit();"></td>
   </tr>
</table>
</form>

</div>

		</td>
	</tr>
</table>

</body>
</html>