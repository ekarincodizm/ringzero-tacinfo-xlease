<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$NTID = $_GET["NTID"];
$IDNO = $_GET["IDNO"];
$cusname = $_GET["cusname"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>อนุมัติ NT</title>
<script type="text/javascript">
function check_search(){
	if(document.getElementById("status_approve1").checked){
		document.getElementById("s1").disabled =true;
	}else if(document.getElementById("status_approve2").checked){
		document.getElementById("s1").disabled =false;
	}
}
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.getElementById("status_approve2").checked) {
		if(document.getElementById("s1").value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุเหตุผลที่ไม่อนุมัติ";
		}
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		document.form1.result.focus();
		return false;
	}
}
</script>   
</head>
<body>
<div  align="center"><h2>อนุมัติ NT โดยพิจารณาจากข้อมูลปัจจุบันกับข้อมูลที่เปลี่ยนแปลงไป</h2></div>
<form method="post" name="form1" action="process_approve.php">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
<tr>
	<td colspan="2" bgcolor="#FFFFFF" height="75">
	<input type="hidden" name="IDNO" value="<?php echo $IDNO?>">
	<input type="hidden" name="NTID" value="<?php echo $NTID?>">
	<input type="hidden" name="cusname" value="<?php echo $cusname?>">
	<b>IDNO :</b> <?php echo $IDNO;?> <br>
	<b>NTID :</b> <?php echo $NTID;?> <br>
	<b>ผู้เช่าซื้อ :</b> <?php echo $cusname;?><br>
	
	<?php
		$query_nthead=pg_query("select \"do_date\" from \"NTHead\" where \"NTID\" = '$NTID'");
		if($res_nt=pg_fetch_array($query_nthead)){
			$do_date = $res_nt["do_date"];
		}
		$nowdate= nowDate();
		
		$date = (strtotime($nowdate) - strtotime($do_date)) / ( 60 * 60 * 24 );
	?>
	<b>วันที่ออก NT :</b> <?php echo $do_date;?>&nbsp;&nbsp;&nbsp;<b>วันที่ปัจจุบัน: </b><?php echo $nowdate;?> &nbsp;&nbsp;&nbsp;<b>เป็นเวลา: </b><?php echo $date;?> วัน<br>
	<hr>
	<b>รายการที่เกี่ยวข้อง</b></br>
	<?php
		$query = pg_query("select a.\"NTID\",a.\"CusState\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\" from \"NTHead\" a
						left join \"ContactCus\" b on a.\"IDNO\" = b.\"IDNO\" and a.\"CusState\" = b.\"CusState\"
						left join \"Fa1\" c on b.\"CusID\" = c.\"CusID\"
						where a.cancel='FALSE' and a.\"remark\" is null and a.\"CusState\" != '0' and a.\"IDNO\"='$IDNO' group by a.\"NTID\",a.\"CusState\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\" order by a.\"NTID\"");

		while($res_co = pg_fetch_array($query)){
			$NTID2 = $res_co["NTID"]; 
			$cusname2 = trim($res_co["A_FIRNAME"]).trim($res_co["A_NAME"])."  ".trim($res_co["A_SIRNAME"]);
			$CusState = $res_co["CusState"];
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NTID :</b> $NTID2 --->";
			echo "<b>ผู้ค้ำคนที่ $CusState:</b> $cusname2<br>";

		}
	?><br>
	</td>
</tr>

<tr align="center" bgcolor="#79BCFF">
	<th height="25" width="50%">ข้อมูลปัจจุบัน</th>
	<th bgcolor="#C7C7C7">ข้อมูลเดิม</th>
</tr>
<tr bgcolor="#FFFFFF">
	<!-- ข้อมูลปัจจุบัน/ข้อมูลที่ใช้จริง -->
	<td valign="top" >
		<table align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0" width="100%">
			<tr align="center" bgcolor="#CCECFD">
				<th height="30" width="60">รายการที่</th>
				<th>รายการ</th>
				<th width="100">ค่าใช้จ่าย(บาท)</th>
			</tr>
			<?php 
			$query = pg_query("select * from \"NTDetail\" where \"NTID\" = '$NTID' order by autoid"); 
			$numrows = pg_num_rows($query);
			$i=1;
			while($result = pg_fetch_array($query)){
				$Detail = $result["Detail"];
				$Amount = $result["Amount"]; 
				
				$Amount = number_format($Amount,2);
				echo "<tr class=\"odd\">";
				echo "<td align=center valign=top height=25>$i</td>";
				echo "<td valign=top>$Detail</td>";
				echo "<td valign=top align=right>$Amount</td>";
				echo "</tr>";	
				$i++;
			} //end while

			if($numrows==0){
				echo "<tr bgcolor=#FFFFFF height=50><td colspan=3 align=center><b>ไม่พบรายการ</b></td><tr>";
			}else{
				$i=$i-1;
				echo "<tr bgcolor=\"#CCECFD\" height=30><td colspan=3><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
			}
			?>
		</table>
	</td>
	
	<!-- ข้อมูลเดิมที่ถูกเปลี่ยนแปลง -->
	<td valign="top">
		<table align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0" width="100%">
			<tr align="center" bgcolor="#D8D8D8">
				<th height="30" width="60">รายการที่</th>
				<th>รายการ</th>
				<th width="100">ค่าใช้จ่าย(บาท)</th>
			</tr>
			<?php 
			$query = pg_query("select * from \"logs_NTDetail\" where \"NTID\" = '$NTID' order by autoid"); 
			$numrows = pg_num_rows($query);
			$i=1;
			while($result = pg_fetch_array($query)){
				$Detail = $result["Detail"]; if($Detail=='0') $numrows=0;
				$Amount = $result["Amount"]; if($Amount=='0') $numrows=0;
				
				$Amount = number_format($Amount,2);
				if($Detail!='0' and $Amount !='0'){
					echo "<tr bgcolor=#F8F8F8>";
					echo "<td align=center valign=top height=25>$i</td>";
					echo "<td valign=top>$Detail</td>";
					echo "<td valign=top align=right>$Amount</td>";
					echo "</tr>";	
				}
				$i++;
			} //end while

			if($numrows==0){
				echo "<tr bgcolor=#F4F4F4 height=50><td colspan=3 align=center><b>ไม่พบรายการ</b></td><tr>";
			}else{
				$i=$i-1;
				echo "<tr bgcolor=\"#D8D8D8\" height=30><td colspan=3><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
			}
			?>
		</table>
	</td>
<tr>
<tr>
	<td align="center">
		<input type="radio" name="status_approve" id="status_approve1" value="1" onclick="check_search()" checked> อนุมัติ  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="status_approve" id="status_approve2" value="2" onclick="check_search()"> ไม่อนุมัติ
	</td>
	<td></td>
</tr>
<tr>
	<td align="center" >
		<table width="50%" align="center">
			<tr>
				<td valign="top"><b>เหตุผลที่ไม่อนุมัติ</b></td>
			</tr>
			<tr>
				<td><textarea name="result" cols="50" rows="4" id="s1" disabled></textarea></td>
			</tr>
		</table>
	</td>
	<td></td>
</tr>
<tr>
	<td>
		<table width="50%" align="center">
			<tr>
				<td align="center">
					<input type="submit" value="  บันทึก  " onclick="return checkdata();">
					<input type="button" value="  ยกเลิก  " onclick="javascript:window.close();">
				</td>
			</tr>
		</table>
	</td>
	<td></td>
</tr>
</table>
</form>
</body>
</html>
