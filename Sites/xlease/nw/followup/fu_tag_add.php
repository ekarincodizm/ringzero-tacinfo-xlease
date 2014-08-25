<?php
include("../../config/config.php");
session_start();
$id_user1 = $_SESSION["av_iduser"];

$conID = pg_escape_string($_GET['CONTID']);
$comID = pg_escape_string($_GET['comid']);


?>
 <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />   
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
alert('กรุณากรอก ชื่อเรื่องการติดตาม ');
return false;
}
if(document.getElementById("tb_tagdetail").value=="")
{
alert('กรุณากรอก รายละเอียดการติดตาม หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("alDate").value > document.getElementById("tagDate").value)
{
alert('กรุณากรอก วันที่แจ้งเตือนให้น้อยกว่าวันที่ติดตาม ');
return false;
}
if(document.getElementById("conid").value =="")
{
alert('กรุณากรอก รายละเอียดการติดตาม หากไม่่มีข้อมูลให้ใช้เครื่องหมาย - ');
return false;
}
else
{
return true;
}
}
</script>

<?php 
$date= nowDate();
$tagdate = nowDate();
$aldate = nowDate();
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<form name="frm" method="post" action="fu_tagadd_query.php">
<center><legend><h2>เพิ่มการติดตาม</h2></legend></center>
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right">ชื่อการติดตาม :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_tagname" id="tb_tagname" value="">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>รายละเอียดการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><textarea name="tb_tagdetail" id="tb_tagdetail" rows="10" cols="100"></textarea>*
	</td>
</tr>
<tr bgcolor="#BCE6FC">

<?php if(empty($conID)){ ?>
	<td valign="top"  align="right"><b>การสนทนาที่เกี่ยวข้อง :</b></td>
    <td bgcolor="#FFFFFF"><select name="hdconid" id="hdconid">

			<?php
			$objQuery = pg_query("SELECT * FROM \"fu_conversation\" where \"comID\" = '$comID' order by \"conID\"");
			while($objResuut = pg_fetch_array($objQuery)){
			 
			?>
			<option value="<?php echo $objResuut["conID"];?>"><?php echo $objResuut["con_name"];?></option>
			
			<?php } ?>
	</select></td>

<?php }else{ ?>
    <td valign="top"  align="right"><b>การสนทนาที่เกี่ยวข้อง :</b></td>
   <td bgcolor="#FFFFFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	
	<input type="hidden" name="hdconid" id="hdconid" value="<?php echo "$conID" ?>">

			<?php
			$objQuery = pg_query("SELECT * FROM \"fu_conversation\" where \"conID\" = '$conID' order by \"conID\"");
			$objResuut = pg_fetch_array($objQuery)
			 
			?>
			( <?php echo $objResuut["conID"];?> ) <?php echo $objResuut["con_name"];?>
	</td>
<?php	} ?>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่ให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF">
	<input type="text" size="12" readonly="true" style="text-align:center;" id="alDate" name="alDate" value="<?php echo $date ?>" onchange="chkdate()"/> &nbsp
	
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เวลาให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="alerthours">
			<?php
			for($i=0;$i<25;$i++){
			if($i<10){ $i="0".$i;}			?>
			<option value="<?php echo $i; ?>"><?php echo "$i";?></option>
			
			<?php } ?>
			
				
		  </select> :
	<select name="alertmin">
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
	<input type="text" size="12" readonly="true" style="text-align:center;" id="tagDate" name="tagDate" value="<?php echo $date ?>" onchange="chkdate()"/> &nbsp
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เวลาที่ ติดต่อกลับ:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="taghours">
			<?php
			for($i=0;$i<25;$i++){
			if($i<10){ $i="0".$i;}			?>
			<option value="<?php echo $i; ?>"><?php echo "$i";?></option>
			
			<?php } ?>
			
				
		  </select> :
	<select name="tagmin">
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
	<td bgcolor="#FFFFFF">รอเวลาการติดต่อกลับ</td>
</tr>

<tr bgcolor="#BCE6FC">
<td></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value=" บันทึก " style="width:100px; height:30px;" onclick="return checkList();"></td>
</tr>
</table>
</form>