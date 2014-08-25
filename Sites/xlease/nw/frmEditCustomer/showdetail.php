<?php
session_start();
include("../../config/config.php");	
$IDNO=pg_escape_string($_GET["IDNO"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script> 
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<?php
//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from \"ContactCus_Temp\" where \"IDNO\"='$IDNO' and \"statusApp\"='2'");
$num_check=pg_num_rows($qry_check); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว
if($num_check == 0){
	$rescheck=pg_fetch_array($qry_check);
	echo "<div style=\"text-align:center\"><h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว</h2>";
	echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
}else{
?>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เปรียบเทียบข้อมูลการแก้ไขการผูกคนกับสัญญา +</h1>
	</div>
	<div><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u>เลขที่สัญญา : <?php echo $IDNO;?></u></font></span></div>

<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>	
	<?php
	for($i=0;$i<2;$i++){
		if($i==0){
			$qry_sec=pg_query("select a.\"CusID\",b.\"full_name\" from \"ContactCus\" a
			left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
			where \"IDNO\" ='$IDNO' and \"CusState\"='0'");
			$txt="ข้อมูลเก่า";
		}else{
			$qry_sec=pg_query("select a.\"CusID\",b.\"full_name\",\"resultcancel\" from \"ContactCus_Temp\" a
			left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
			where \"IDNO\" ='$IDNO' and \"CusState\"='0' and \"statusApp\"='2'");
			$txt="ข้อมูลใหม่";
		}
		$numsec=pg_num_rows($qry_sec);
		if($res_sec=pg_fetch_array($qry_sec));
		$CusID=trim($res_sec["CusID"]);
		$full_name=trim($res_sec["full_name"]);
		$CusState=trim($res_sec["CusState"]);
		$resultcancel=trim($res_sec["resultcancel"]);
	?>
	<td valign="top">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" ">
		<tr><td colspan="4"><b>(<?php echo $txt;?>)</b></td></tr>
		<?php
		if($numsec>0){
		?>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">ผู้เช่าซื้อ : </td>
			<td bgcolor="#FFFFFF"><?php echo "$full_name ($CusID)"?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">ผู้ค้ำ : </td>
			<td bgcolor="#FFFFFF">
				<?php 
				if($i==0){
					$qry_sec2=pg_query("select a.\"CusID\",b.\"full_name\",a.\"CusState\" from \"ContactCus\" a
					left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
					where \"IDNO\" ='$IDNO' and \"CusState\"<>'0' order by \"CusState\"");
				}else{
					$qry_sec2=pg_query("select a.\"CusID\",b.\"full_name\",a.\"CusState\" from \"ContactCus_Temp\" a
					left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
					where \"IDNO\" ='$IDNO' and \"CusState\"<>'0' and \"statusApp\"='2' order by \"CusState\"");
				}
				while($res_sec2=pg_fetch_array($qry_sec2)){
					$CusID2=trim($res_sec2["CusID"]);
					$full_name2=trim($res_sec2["full_name"]);
					$CusState2=trim($res_sec2["CusState"]);
				echo "$full_name2($CusID2) ผู้ค้ำคนที่ $CusState2<br><br>";
				}
				?>
			</td>
		</tr>
		<?php
		//กรณีไม่พบข้อมูลเก่าแสดงว่าเป็นการเพิ่มข้อมูล
		}else{
			echo "<tr colspan=\"2\"><td bgcolor=\"#FFFFFF\" width=\"400\" height=250 align=center><h2>ไม่พบข้อมูล</h2></td></tr>";
		}
		?>
	</table>
	</td>
	<?php 
	}
	?>
</tr>
<tr height="30" bgcolor="#FFFFFF">
	<td align="right" valign="top"><b>เหตุผลที่แก้ไข :</b></td>
	<td><textarea name="note" id="note" cols="40" rows="5" readonly="true" ><?php echo $resultcancel;?></textarea></td>
</tr>
<tr><td align="center" height="50" colspan="2">
<form method="post" action="process_approve.php">
	<input type="hidden" name="IDNO" id="IDNO" value="<?php echo $IDNO; ?>">
<!--input type="button" value="อนุมัติ" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_approve.php?IDNO=<?php echo $IDNO; ?>&stsapp=1'}">&nbsp;<input type="button" value="ไม่อนุมัติ" onclick="if(confirm('ยืนยันการไม่อนุมัติ!!')){location.href='process_approve.php?IDNO=<?php echo $IDNO; ?>&stsapp=0'}"-->
	<br>
	<input name="appv" type="submit" value="อนุมัติ" />
	<input name="unappv" type="submit" value="ไม่อนุมัติ" />
	
</form>
&nbsp;
<br><input type="button" value="ปิดหน้านี้" onclick="window.close();"></td></tr>
</table>
<?php
}
?>
</body>
</html>
