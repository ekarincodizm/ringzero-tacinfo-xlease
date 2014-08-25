<?php
include("../../config/config.php");
$COMIDD = pg_escape_string($_GET['COMID']);
?>
<script language="JavaScript">
	function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
  }
} 
</script>

<script type="text/javascript">
function checkList()
{
if(document.getElementById("tb_comname").value=="")
{
alert('กรุณากรอก ข้อมูล  บริษัท ให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("tb_comadd").value=="")
{
alert('กรุณากรอก ข้อมูล  บริษัท ให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("tb_comphone").value=="" )
{
alert('กรุณากรอก ข้อมูล  บริษัท ให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
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
if($COMIDD != "" && $COMIDD !="i"){
	$qry_name=pg_query("select * from \"fu_company\" WHERE \"comID\" = '$COMIDD'");
	$result=pg_fetch_array($qry_name);
	$nrows=pg_num_rows($qry_name);
	if($nrows == 0){
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_company_search.php\">";
		echo "<script type='text/javascript'>alert('ขออภัย ไม่พบข้อมูลที่ท่านค้นหา')</script>";
		exit();
	}
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

}else if($COMIDD =="i"){
	$name=null;
	$COMID=null;
	$address=null;
	$com_phone=null;
	$fax=null;
	$email=null;
	$business=null;
	$type=null;
	$avg=null;
	$date= date("Y-m-d H:m:s");
	$conID= null;

}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
	exit();
}
if($COMIDD == "i"){?>

<center><legend><h2>เพิ่มบริษัทลูกค้า</h2></legend></center>

<?php 

}else{ ?>

<center><legend><h2>แก้ไขข้อมูลบริษัทลูกค้า</h2></legend></center>

<?php 
} 
?>
<form name="frm" method="post" action="fu_company_query.php">


<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">

<?php if($COMIDD == "i"){


$qry_name2=pg_query("select * from \"fu_company\" order by \"comID\" DESC limit 1");
$result5=pg_fetch_array($qry_name2);
$nrows2=pg_num_rows($qry_name2);
?>
<tr>
<td valign="top"  align="right">*บริษัทที่เพิ่มล่าสุด :</td>
<td><?php echo $result5['com_name']; ?>  เพิ่มวันที่ : <?php echo $result5['com_date']; ?></td>
</tr>
<?php }else{ ?>
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b>รหัสบริษัท :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$COMID"; ?></td>
	<input type="hidden" name="hd_comid" id="hd_comid" value="<?php echo "$COMID"; ?>">
</tr>
<?php } ?>

<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>ชื่อบริษัท :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_comname" id="tb_comname" value="<?php echo "$name"; ?>">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ที่อยู่บริษัท :</b></td>
    <td bgcolor="#FFFFFF"><textarea rows="10" cols="100" name="tb_comadd"><?php echo "$address"; ?></textarea>*

	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ประเภทธุรกิจ :</b></td>
    <td bgcolor="#FFFFFF">
	<select name="lmName1">
			<option value="<?php echo "$type"; ?>"><?php echo "$type"; ?></option>
			<option value="บริษัทจำกัด">บริษัทจำกัด</option>
			<option value="ห้างหุ้นส่วนจำกัด">ห้างหุ้นส่วนจำกัด</option>
			<option value="ห้างหุ้นส่วนสามัญ">ห้างหุ้นส่วนสามัญ</option>
			<option value="สมาคม">สมาคม</option>
			<option value="มูลนิธิ">มูลนิธิ</option>	
			<option value="วัด">วัด</option>
			<option value="อื่นๆ">อื่นๆ</option>			
		  </select></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>สินค้าหรือบริการ :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_combu"  id="tb_combu" value="<?php echo "$business"; ?>"></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>รายได้เฉลี่ย 3 ปีล่าสุด :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_comavg"  id="tb_comavg" value="<?php echo "$avg"; ?>" OnKeyPress="check_num(event)"></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เบอร์โทรศัพท์ :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_comphone"  id="tb_comphone" value="<?php echo "$com_phone"; ?>" OnKeyPress="check_num(event)">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>FAX :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_fax"  id="tb_fax" value="<?php echo "$fax"; ?>" OnKeyPress="check_num(event)"></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>E-mail:</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_commail"  id="tb_commail" value="<?php echo "$email"; ?>"></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่บันทึก :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>

<tr bgcolor="#BCE6FC">
<td></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value=" บันทึก " style="width:100px; height:30px;" onclick="return checkList();"></td>
</tr>



</table>
</form>

