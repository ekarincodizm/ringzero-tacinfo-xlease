<?php
include("../../config/config.php");
$CONTID = pg_escape_string($_GET['CONTID']);
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($CONTID != ""){

$qry_name=pg_query("select * from  public.\"fu_conversation\" fcon 
join \"fu_company\" fc on fcon.\"comID\" = fc.\"comID\" 
join \"fu_empcontact\" fe on fcon.\"empconID\" = fe.\"empconID\" 
join \"Vfuser\" vf on fcon.\"id_user\" = vf.\"id_user\"
where fcon.\"conID\" = '$CONTID'");

$result=pg_fetch_array($qry_name);

$nrows=pg_num_rows($qry_name);
if(!$nrows){
echo "<script type='text/javascript'>alert('ไม่พบข้อมูล')</script>";
exit();
}else{
$name=trim($result["con_name"]);
$comname=trim($result["com_name"]);
$empname=trim($result["empcon_name"]);
$emplname=trim($result["empcon_lname"]);
$empID=trim($result["empconID"]);
$comID=trim($result["comID"]);
$detail=trim($result["con_detail"]);
$date=trim($result["con_date"]);
$id_user=trim($result["id_user"]);
$thaiacename=trim($result["fullname"]);
?>
<center><legend><h2>ดูการสนทนา</h2></legend></center>
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>รหัสการสนทนา:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$CONTID"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ชื่อการสนทนา:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$name"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>รายละเอียดการสนทนา:</b></td>
    <td bgcolor="#FFFFFF"><textarea  rows="15" cols="100" readonly="readonly"><?php echo "$detail"; ?></textarea></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>บริษัทที่สนทนา :</b></td>
    <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo "$comID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
    <u><?php echo "$comname"; ?> (<?php echo "$comID"; ?>)</u></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ผู้ติดต่อ :</b></td>
    <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo "$empID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
   <u> <?php echo "$empname"." "."$emplname"." "; ?>(<?php echo "$empID"; ?>)</u></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>พนักงานที่สนาทนา ของ Thaiace  :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$thaiacename"." "; ?>(<?php echo "$id_user"; ?>)</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>วันที่บันทึก :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
<?php 

$qry_name=pg_query("select count(\"conID\") as count from \"fu_tag\" WHERE \"conID\" = '$CONTID'");
$result=pg_fetch_array($qry_name); 
$count = $result["count"];

?>
    <td valign="top" align="right"><b>การติดตามที่เกี่ยวข้อง :</b></td>
    <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_tag_showlist.php?conID=<?php echo "$CONTID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<u><?php echo "$count"; ?>ครั้ง</u></td> 
</tr>
</table>

<?php  
	}}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}?>
