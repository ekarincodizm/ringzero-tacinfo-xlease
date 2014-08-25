<?php
include("../../config/config.php");
$contractID = $_GET['IDNO'];
$stscus = $_GET['status'];
$cus = $_GET['cus'];
$cus3 = $_GET['cus3'];
$cus4 = $_GET['cus4'];

if($cus3==""){
	$cus3="";
}else{
	$cus3="and b.\"CusID\" <> '$cus3'";
}

if($cus4==""){
	$cus4="";
}else{
	$cus4="and b.\"CusID\" <> '$cus4'";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>จ่ายเช็ค</title>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0">
<tr>
	<td colspan="4">เลขที่สัญญา : <?php echo $contractID;?></td>
</tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
    <td>ที่</td>
	<td>ชื่อลูกค้า</td>
    <td>สถานะลูกค้า</td>
	<td>เลือก</td>
</tr>

<?php
//ค้นหาเลขที่สัญญาที่เกี่ยวข้อง
$qry_name=pg_query("select a.\"contractID\",b.\"CusID\",b.\"thcap_fullname\",b.\"CusState\" from \"thcap_mg_contract\" a
left join \"vthcap_ContactCus_detail\" b on a.\"contractID\"=b.\"contractID\"
where a.\"contractID\"='$contractID' and b.\"CusID\" <> '$cus' $cus3 $cus4 order by \"CusState\"");

$num_row = pg_num_rows($qry_name);
$p=1;
while($res_name2=pg_fetch_array($qry_name)){
	list($idno,$CusID,$fullname,$CusState)=$res_name2;
    if($CusState=="0"){
		$txtCus="ผู้กู้หลัก";
	}else{
		$txtCus="ผู้กู้ร่วมคนที่ $CusState";
	}

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center" valign="top"><?php echo $p; ?></td>
    <td valign="top"><?php echo "$fullname"; ?></td>
    <td valign="top" width=""><?php echo "$txtCus"; ?></td>
	<td valign="top" width="50" align="center">
	<?php
		$mixtext=$CusID."#".$fullname;
		echo "<input type=\"button\" name=\"btn1\" value=\"เลือก\" onClick=\"opener.document.form1.$stscus.value='$mixtext';window.close();\">";
	?>
	</td> 
</tr>

<?php
	$p++;
}
if($num_row == 0){
    echo "<tr><td colspan=5 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr><td colspan=5 align=left>พบข้อมูลทั้งหมด $num_row รายการ</td></tr>";
}
?>
</table>
<div style="color:red;">*หากลูกค้าท่านใดที่ถูกเลือกไปแล้วจะไม่สามารถเลือกได้อีก</div>
</body>
</html>
