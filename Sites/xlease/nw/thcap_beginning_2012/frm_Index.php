<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) ทำหน้าชั่วคราวสำหรับบันทึกยอดยกมาปี 2555</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function chk(){
	var checkerror=true;
	var theMessage = "กรุณาป้อนข้อมูล ให้ครบ: \n-----------------------------------\n";
	if(document.getElementById('money').value == ""){			
			theMessage = theMessage + "\n --> กรุณาระบุยอดยกมาปี 2555 (2012-12-31)";
			checkerror= false;
	}
	if(document.getElementById('acid').value == ""){
			theMessage = theMessage + "\n --> กรุณาเลือกบัญชี";
			checkerror=false;
	}
	
	if(checkerror==false){
		alert(theMessage);
		return false;
	}
	else{
		return true;
	}
}
</script>

<body>
<center><h2>(THCAP) ทำหน้าชั่วคราวสำหรับบันทึกยอดยกมาปี 2555</h2></center>
<br>
<!--1.กรอบการเพิ่มข้อมูล สำหรับบันทึกยอดยกมาปี 2555-->
<center><fieldset  style="width:60%">
	<legend><font color="black"><b>บันทีกยอดยกมาปี 2555 (2012-12-31)</font></b></font></legend>
<br>
<form name="frmpost" method="post" action="process_addbeginning.php">
<table  align="center" width="80%" border="0" >
	<tr>	
		<!--2.ให้ user คีย์ข้อมูล เลือกบัญชี-->
		<td align="right" ><b> เลือกบัญชี :<b></td>
		<td align="left" ><select name="acid" id="acid">
		<option value="">- เลือก -</option>
		<?php
			$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
				while($res_name=pg_fetch_array($qry_name))
				{
					$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
					$AcID = $res_name["accBookID"]; // เลขที่บัญชี
					$AcName = $res_name["accBookName"]; // ชื่อบัญชี
					echo "<option value=\"$AcSerial\">$AcID : $AcName</option>";
				}
		?>
			</select>
		</td>	
	</tr>
	<tr>
		<!--3.ให้ user คีย์ข้อมูล ยอดยก-->
		<td align="right"><b>ยอดยกปี 2555(2012-12-31) :<b></td>
		<td align="left">
			<input type="text" id="money" name="money" size="30" style="text-align:right">   บาท</td>
		
	</tr>
	<tr></tr>
	<tr>
	<td colspan="2"  align="center">	
	<input type="submit" value="บันทึกข้อมูล" id="save" onclick="return chk();">
	</td></tr>
</table>
</form>
</fieldset></center>
<br>
<?php //ประวัติการทำรายการ 30 รายการล่าสุด
	include('frm_history_limit.php');?>
</div>
</body>
</html>