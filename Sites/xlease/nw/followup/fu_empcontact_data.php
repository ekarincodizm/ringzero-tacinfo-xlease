<?php
include("../../config/config.php");
$EMPID = pg_escape_string($_GET['empID']);
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($EMPID != ""){

$qry_name=pg_query("select * from \"fu_empcontact\" inner join \"fu_company\" 
on \"fu_empcontact\".\"comID\"=\"fu_company\".\"comID\" 
WHERE \"fu_empcontact\".\"empconID\" = '$EMPID'");

$result=pg_fetch_array($qry_name);

$nrows=pg_num_rows($qry_name);
if(!$nrows){
echo "<script type='text/javascript'>alert('ไม่พบข้อมูล')</script>";
}else{
$name=trim($result["empcon_name"]);
$lname=trim($result["empcon_lname"]);
$empID=trim($result["empconID"]);
$post=trim($result["empcon_position"]);
$phone=trim($result["empcon_phone"]);
$mobile=trim($result["empcon_moblie"]);
$email=trim($result["empcon_email"]);
$date=trim($result["empcon_Date_submit"]);
$COMID=trim($result["comID"]);
$comname=trim($result["com_name"]);
?>
<center><legend><h2>ข้อมูลผู้ติดต่อ</h2></legend></center>
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>รหัสผู้ติดต่อ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$empID"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ชื่อ-นามสกุล :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$name"." "."$lname"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ตำแหน่ง :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$post"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>จากบริษัท :</b></td>
	<td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo "$COMID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
     <u><?php echo "$comname"?>(<?php echo "$COMID"; ?>)</u></td>
</tr>

<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>โทรศัพท์ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$phone"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"height="35" align="right"><b>มือถือ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$mobile"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>E-mail :</b></td>
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
