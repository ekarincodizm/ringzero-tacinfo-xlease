<?php
include("../../config/config.php");
$EMPID = pg_escape_string($_GET['empID']);
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
if(document.getElementById("tb_empname").value=="")
{
alert('กรุณากรอก ข้อมูล พนักงานให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("tb_emplname").value=="")
{
alert('กรุณากรอก ข้อมูล พนักงานให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("tb_emppost").value=="")
{
alert('กรุณากรอก ข้อมูล พนักงานให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("tb_empphone").value=="")
{
alert('กรุณากรอก ข้อมูล พนักงานให้ครบหากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
else
{
return true;
}
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($EMPID != "" && $EMPID !="i"){

$qry_name=pg_query("select * from \"fu_empcontact\" inner join \"fu_company\" 
on \"fu_empcontact\".\"comID\"=\"fu_company\".\"comID\" 
WHERE \"fu_empcontact\".\"empconID\" = '$EMPID'");
$result=pg_fetch_array($qry_name);
$nrows=pg_num_rows($qry_name);
if($nrows == 0){
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_empcontact_search.php\">";
	echo "<script type='text/javascript'>alert('ขออภัย ไม่พบข้อมูลที่ท่านค้นหา')</script>";
	exit();
}
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

}else if($EMPID == "i"){

$name=null;
$empID=null;
$lname=null;
$post=null;
$phone=null;
$email=null;
$mobile=null;
$date=null;
$COMID2=pg_escape_string($_GET['comid']);
$date= date("Y-m-d H:m:s");


	$objQuery1 = pg_query("SELECT * FROM \"fu_company\" where \"comID\" = '$COMID2'"); 
		$result1 =pg_fetch_array($objQuery1);
		$COMID=$result1['comID'];
		$comname=$result1['com_name'];

}else{
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_empcontact_search.php\">";
	echo "<script type='text/javascript'>alert('ขออภัย ไม่พบข้อมูลที่ท่านค้นหา')</script>";
	exit();
}


if($EMPID == "i"){?>

<center><legend><h2>เพิ่มผู้ติดต่อ</h2></legend></center>

<?php 

}else if($EMPID != "i"){ ?>

<center><legend><h2>ข้อมูลผู้ติดต่อ</h2></legend></center>

<?php 
} 
?>
<form name="frm" method="post" action="fu_empcontact_query.php">
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">

<?php if($empID != ""){ ?>

<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b>รหัสผู้ติดต่อ:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$empID"; ?></td>
	<input type="hidden" name="hd_empid" id="hd_empid" value="<?php echo "$empID"; ?>">
</tr>

<?php } ?>


<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>ชื่อ-นามสกุล :</b></td>
    <td bgcolor="#FFFFFF">
	<input type="text" name="tb_empname" id="tb_empname" value="<?php echo "$name"; ?>">-
	<input type="text" name="tb_emplname" id="tb_emplname" value="<?php echo "$lname"; ?>">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ตำแหน่ง :</b></td>
	<td bgcolor="#FFFFFF"><input type="text" name="tb_emppost"  id="tb_emppost" value="<?php echo "$post"; ?>">*
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>จากบริษัท:</b></td>
	<td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo "$COMID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<?php echo "$comname"; ?></td>
	<input type="hidden" id="hdcomid" name="hdcomid" value="<?php echo "$COMID"; ?>">
</tr>

<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>โทรศัพท์ :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_empphone"  id="tb_empphone" value="<?php echo "$phone"; ?>" OnKeyPress="check_num(event)">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>มือถือ :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_mobile"  id="tb_mobile" value="<?php echo "$mobile"; ?>" OnKeyPress="check_num(event)"></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>E-mail:</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_empmail"  id="tb_empmail" value="<?php echo "$email"; ?>"></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่บันทึก :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>

<tr bgcolor="#BCE6FC">
<td></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value=" บันทึก "  style="width:100px; height:30px;" onClick="return checkList()"></td>
</tr>
</table>
</form>
