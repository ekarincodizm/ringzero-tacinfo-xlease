<?php
include("../../config/config.php");
session_start();
$id_user1 = $_SESSION["av_iduser"];

$TAGID = pg_escape_string($_GET['TAGID']);

$datech = nowDate();
?>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />   
<link type="text/css" rel="stylesheet" href="act.css"></link>
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	$("#alDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });

	$("#tagDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
});
</script>

<script type="text/javascript">
function checkList()
{
if(document.getElementById("tb_tagname").value=="")
{
alert('กรุณากรอก ชื่อเรื่องการติดตาม -');
return false;
}
if(document.getElementById("alDate").value > document.getElementById("tagDate").value)
{
alert('กรุณากรอก วันที่แจ้งเตือนให้น้อยกว่าวันที่ติดตาม ');
return false;
}
else if(document.getElementById("tb_tagdetail").value=="")
{
alert('กรุณากรอก รายละเอียดการติดตาม หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
else
{
return true;
}
}
</script>


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
echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
exit();
}else{

if(empty($result["datetime_alert"])){
    $aldate = nowDate();
}else{
	$arldate=$result["datetime_alert"];
	list($aldate,$altime)=explode(" ",$arldate);
	list($alh,$alm,$als)=explode(":",$altime);

}

if(empty($result["tag_datetime"])){
    $tagdate = nowDate();
}else{
    $tadate=$result["tag_datetime"];
	list($tagdate,$tagtime)=explode(" ",$tadate);
	list($tagh,$tagm,$tags)=explode(":",$tagtime);
}

$conID=trim($result["conID"]);
$conname=trim($result["con_name"]);
$condate=trim($result["con_date"]);

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

}
}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
	exit();
}?>
<form name="frm" method="post" action="fu_tag_query.php">
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="250" height="25" align="right"><b>รหัสการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$tagID"; ?></td>
	<input type="hidden" name="hd_tagid" id="hd_tagid" value="<?php echo "$TAGID"; ?>">
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>ชื่อการติดตาม :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_tagname" id="tb_tagname" value="<?php echo "$tagname"; ?>">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>รายละเอียดการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><textarea name="tb_tagdetail" id="tb_tagdetail" rows="5" cols="70"><?php echo "$tagdetail"; ?></textarea>
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>การสนทนาที่เกี่ยวข้อง :</b></td>
   <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo "$conID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
	<?php echo "$conname"." "."("."$conID".")"; ?></td>
	<input type="hidden" name="hdconid" id="hdconid" value="<?php echo  "$conID"; ?>">
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>วันที่สนทนา :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$condate"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>บริษัทที่ติดตาม :</b></td>
	<td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo "$comID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
  <u> <?php echo "$comname"." "."("."$comID".")"; ?></u></td>
			
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ผู้ติดต่อ:</b></td>
	<td bgcolor="#FFFFFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo $empconID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
   <u><?php echo $empconname;?></u></td>
	</td>
		  </tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" height="35" align="right"><b>พนักงาน Thaiace  :</b></td>
		<td bgcolor="#FFFFFF"> <?php echo $thaiaceemp;?></td>
	</td>			
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่ให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF">
	<input type="text" size="12" readonly="true" style="text-align:center;" id="alDate" name="alDate" value="<?php echo $aldate; ?>" onchange="chkdate()"/> &nbsp
	
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เวลาให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="alerthours">
			<option value="<?php echo $tagh; ?>"><?php echo "$tagh";?></option>
			<?php
			for($i=0;$i<25;$i++){
			if($i<10){ $i="0".$i;}			?>
			<option value="<?php echo $i; ?>"><?php echo "$i";?></option>
			
			<?php } ?>
			
				
		  </select> :
	<select name="alertmin">
			<option value="<?php echo $tagm; ?>"><?php echo "$tagm";?></option>
			<?php
			for($z=0;$z<60;$z+=5){
			if($z<10){ $z="0".$z;} ?>
			<option value="<?php echo $z; ?>"><?php echo "$z";?></option>
			
			<?php } ?>
			
				
		  </select> (ชั่วโมง : นาที)
		  
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่ ติดต่อกลับ:</b></td>
    <td bgcolor="#FFFFFF">
	<input type="text" size="12" readonly="true" style="text-align:center;" id="tagDate" name="tagDate" value="<?php echo $tagdate; ?>" onchange="chkdate()"/> &nbsp
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เวลาที่ ติดต่อกลับ:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="taghours">
			<option value="<?php echo $alh; ?>"><?php echo "$alh";?></option>
			<?php
			for($i=0;$i<25;$i++){
			if($i<10){ $i="0".$i;}			?>
			<option value="<?php echo $i; ?>"><?php echo "$i";?></option>
			
			<?php } ?>
			
				
		  </select> :
	<select name="tagmin">
			<option value="<?php echo $alm; ?>"><?php echo "$alm";?></option>
			<?php
			for($z=0;$z<60;$z+=5){
			if($z<10){ $z="0".$z;} ?>
			<option value="<?php echo $z; ?>"><?php echo "$z";?></option>
			
			<?php } ?>
			
				
		  </select> (ชั่วโมง : นาที)
		  
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>สถานะ:</b></td>
	<td bgcolor="#FFFFFF">

	
	<?php if($status == 0){
		$stat1 = 'รอเวลาการติดต่อกลับ';
	}else if($status == 1){
		$stat1 = 'เลื่อนการติดต่อ'; 
	}else if($status == 2){
		$stat1 = 'ติดต่อเสร็จสิ้น';
	}else if($status == 3){ 
		$stat1 = 'ยกเลิกการติดต่อ';
	}else{} ?>
			<?php echo "$stat1"; ?>
</td>
</tr>

<tr bgcolor="#BCE6FC">
<td></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value=" บันทึก "  style="width:100px; height:30px;" onclick="return checkList();"></td>
</tr>
</table>
</form>