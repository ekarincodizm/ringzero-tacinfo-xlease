<?php
include("../../config/config.php");
$TAGID = pg_escape_string($_GET['TAGID']);
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($TAGID != ""){

$qry_name=pg_query("select * from  public.\"ContactHistory\" 

where \"tagID\" = '$TAGID'");

$result=pg_fetch_array($qry_name);

$nrows=pg_num_rows($qry_name);
if(!$nrows){
echo "<script type='text/javascript'>alert('ไม่พบข้อมูล')</script>";
exit();
}else{
$conID=trim($result["conID"]);
$conname=trim($result["con_name"]);
$comID=trim($result["comID"]);
$comname=trim($result["com_name"]);
$comphone=trim($result["com_phone"]);
$tagID=trim($result["tagID"]);
$tagname=trim($result["tag_name"]);
$tagdetail=trim($result["tag_detail"]);
$timealert=trim($result["datetime_alert"]);
$timetag=trim($result["tag_datetime"]);
$empconID=trim($result["empconID"]);
$empconname=trim($result["full_name"]);
$emplname=trim($result["empcon_lname"]);
$id_user=trim($result["id_user"]);
$thaiaceemp=trim($result["thaiace_emp"]);
$status=trim($result["tag_status"]);
$condate=trim($result["con_date"]);
?>
<center><legend><h2> ข้อมูลการติดตาม </h2></legend></center>
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>รหัสการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$tagID"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ชื่อการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$tagname"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>รายละเอียดการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><textarea  readonly="readonly" rows="10" cols="100"><?php echo "$tagdetail"; ?></textarea></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>การสนทนาที่เกี่ยวข้อง :</b></td>
    <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo "$conID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
	<u><?php echo "$conname"." "."("."$conID".")"; ?></u></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>วันที่สนทนา :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$condate"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>บริษัทที่ติดตาม :</b></td>
    <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo "$comID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
   <u><?php echo "$comname"?> (<?php echo "$comID"; ?>)</u></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>เบอร์ติดต่อบริษัท :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$comphone"?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>ผู้ติดต่อ :</b></td>
    <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo "$empconID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<u><?php echo "$empconname"; ?>(<?php echo "$empconID"; ?>)</u></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>พนักงานที่สนาทนา ของ Thaiace  :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$thaiaceemp"." "; ?>(<?php echo "$id_user"; ?>)</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>วันที่/เวลา <br>ที่ให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$timealert"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>วันที่/เวลา <br> ที่จะติดต่อกลับ</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$timetag"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>สถานะการติดตาม : </b></td>
	<?php if($status == 0){?>
    <td bgcolor="#FFFFFF"> รอเวลาการติดต่อกลับ </td>
	<?php }else if($status == 1){ ?>
	 <td bgcolor="#FFFFFF"> เลื่อนการติดต่อ </td>
	 <?php }else if($status == 2){ ?>
	 <td bgcolor="#FFFFFF"> ติดต่อเสร็จสิ้น</td>
	 <?php }else if($status == 3){ ?>
	 <td bgcolor="#FFFFFF"> ยกเลิกการติดต่อ</td>
	 <?php } ?>
</tr>
</table>

<?php  
	}}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}?>
