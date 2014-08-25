<?php
include("../../config/config.php");
$COMID = pg_escape_string($_GET['COMID']);

?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($COMID != ""){
//ค้นหาชื่อ-นามสกุล
$qry_name=pg_query("select * from \"fu_company\" WHERE \"fu_company\".\"comID\" = '$COMID'");
$result=pg_fetch_array($qry_name);
$nrows=pg_num_rows($qry_name);
if(!$nrows){
echo "<script type='text/javascript'>alert('ไม่พบข้อมูล')</script>";
}else{
$name=trim($result["com_name"]);
$COMID=trim($result["comID"]);
$address=trim($result["com_address"]);
$com_phone=trim($result["com_phone"]);
$fax=trim($result["com_fax"]);
$email=trim($result["com_email"]);
$business=trim($result["com_business"]);
$type=trim($result["com_type"]);
$avg=trim($result["com_avg_income"]);
$date=trim($result["com_date"]);
?>
<center><legend><h2> ข้อมูลบริษัท </h2></legend></center>
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>รหัสบริษัท :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$COMID"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ชื่อบริษัท :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$name"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ที่อยู่บริษัท :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$address"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ประเภทธุรกิจ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$type"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>สินค้าหรือบริการ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$business"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"height="35" align="right"><b>รายได้เฉลี่ย 3 ปีล่าสุด :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$avg"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>เบอร์โทรศัพท์ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$com_phone"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>FAX :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$fax"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>E-mail:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$email"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>วันที่บันทึก :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>

</table>

<?php  
	}}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}?>
