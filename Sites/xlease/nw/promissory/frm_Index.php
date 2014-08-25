<?php
session_start();
include("../../config/config.php");	
$txtsearch=$_POST["txtsearch"];
$method=$_REQUEST["method"];	 
$boeID=$_REQUEST["boeID"];	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>จัดการตั๋วสัญญาใช้เงิน</title>
<script type="text/javascript">
$(document).ready(function(){
	$("#payDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#returnDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#submitButton").click(function(){
		$("#submitButton").attr('disabled', true);
		if($("#boeNumber").val()==""){
			alert('กรุณากรอกเลขที่ตั๋วสัญญา');
			$('#boeNumber').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#payUser").val()==""){
			alert('กรุณากรอกชื่อผู้ออกตั๋ว');
			$('#payUser').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#purchaseUser").val()==""){
			alert('กรุณากรอกชื่อผู้ซื้อตั๋ว');
			$('#purchaseUser').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#loan_amount").val()==""){
			alert('กรุณากรอกยอดกู้');
			$('#loan_amount').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#interest").val()==""){
			alert('กรุณากรอกดอกเบี้ย');
			$('#interest').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#payDate").val()==""){
			alert('กรุณาระบุวันที่ออกตั๋ว');
			$('#payDate').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}
		
		$.post("process_promissory.php",{
			cmd : $("#cmd").val(),
			boeID : $("#boeID").val(), 
			boeNumber : $("#boeNumber").val(),
			payUser :$("#payUser").val(),
			purchaseUser :$("#purchaseUser").val(),
			loan_amount :$("#loan_amount").val(),
			interest :$("#interest").val(),
			payDate :$("#payDate").val(),
			returnDate :$("#returnDate").val(),
			receivewhtref:$("#receivewhtref").val(),
			receivewhtamt:$("#receivewhtamt").val(),
			receivepaybackamt:$("#receivepaybackamt").val(),
			receivechqno:$("#receivechqno").val(),
		},
		function(data){
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
				location.href = "frm_Index.php";
				$("#submitButton").attr('disabled', false);
			}else if(data == "2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				$("#submitButton").attr('disabled', false);
			}
		});
	});
	
	$("#cancelvalue").click(function(){
		$("#boeNumber").val('');
		$("#payUser").val('');
		$("#purchaseUser").val('');
		$("#loan_amount").val('0.00');
		$("#interest").val('0.00');
		$("#payDate").val('');
		$("#returnDate").val('');
	});
});
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('boeNumber').focus();">
<?php
if($method=="lock"){

	$update="UPDATE account.boe SET \"statusTicket\"='FALSE' WHERE \"boeID\"='$boeID'";
	if($resup=pg_query($update)){
		$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
		$user_id = $_SESSION["av_iduser"];
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) ล็อกตั๋วสัญญาใช้เงินภายใน', '$add_date')");
		//ACTIONLOG---
	}else{
		echo $resup;
	}
	echo "<meta http-equiv='refresh' content='0; URL=frm_Index.php'>";
}else if($method=="edit"){
	//ดึงข้อมูลขึ้นมาเพื่อแก้ไข
	$qryedit=pg_query("select * from account.boe where \"boeID\"='$boeID'");
	$resedit=pg_fetch_array($qryedit);
	$txthead="แก้ไขตั๋วสัญญา";
	echo "<input type=\"hidden\" name=\"cmd\" id=\"cmd\" value=\"edit\">";
	echo "<input type=\"hidden\" name=\"boeID\" id=\"boeID\" value=\"$boeID\">";
}else{
	$txthead="เพิ่มตั๋วสัญญา";
	echo "<input type=\"hidden\" name=\"cmd\" id=\"cmd\" value=\"add\">";
}
?>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">จัดการตั๋วสัญญาใช้เงิน</h1>
	</div>

	<div id="warppage"  style="width:850px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.close();"><u>X ปิดหน้าต่าง</u></span></div>
		<fieldset><legend><B>ค้นหา</B></legend>
		<form name="frm_edit" method="post" action="frm_Index.php">
			<div style="padding:20px;"> 
			<table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-weight:bold;" align="center">
			<tr height="30" bgcolor="#FFFFFF">
				<td align="center">ค้นหาจาก เลขที่ตั๋วสัญญา,ชื่อผู้ซื้อตั๋ว,ชื่อผู้ออกตั๋ว : <input type="text" name="txtsearch" id="txtsearch" size="30" value="<?php echo $txtsearch;?>"><input type="submit" value="ค้นหา"></td>
			</tr>
			</table>
			</div>
		</form>	
		</fieldset><br>
		<table width="900" border="0" cellpadding="1" cellspacing="1" bgcolor="#D7F0FD">
			<tr bgcolor="#0B98CE" align="center">
				<th width="100">เลขที่ตั๋วสัญญา</th>
				<th width="80">ชื่อผู้ออกตั๋ว</th>
				<th width="80">ชื่อผู้ซื้อตั๋ว</th>
				<th width="100">ยอดกู้</th>
				<th width="40">ดอกเบี้ย</th>
				<th width="80">วันที่ออกตั๋ว</th>
				<th width="80">วันที่คืนตั๋ว</th>
				<th width="80">ธนาคารและเลขที่เช็ค</th>
				<th width="80">จำนวนเงินที่คืน</th>
				<th width="80">จำนวนเงิน ภาษีหัก ณ ที่จ่าย</th>
				<th></th>
			</tr>
			<?php
				if($txtsearch==""){
					$qry_search=pg_query("select * from account.boe order by \"boeID\"");
				}else{
					$qry_search=pg_query("select * from account.boe
					where \"boeNumber\" like '%$txtsearch%' or \"payUser\" like '%$txtsearch%' or \"purchaseUser\" like '%$txtsearch%' order by \"boeID\"");
				}
				$numrow=pg_num_rows($qry_search);
				//ดึงข้อมูลขึ้นมาแสดง
				$i=0;
				while($res_search=pg_fetch_array($qry_search)){
					$boeID=$res_search["boeID"];
					$boeNumber=$res_search["boeNumber"];
					$payUser=$res_search["payUser"];
					$purchaseUser=$res_search["purchaseUser"];
					$loan_amount=$res_search["loan_amount"];
					$interest=$res_search["interest"];
					$payDate=$res_search["payDate"];
					$returnDate=$res_search["returnDate"];
					$statusTicket=$res_search["statusTicket"];
					$receivepaybackamt=$res_search["receivepaybackamt"];
					$receivewhtamt=$res_search["receivewhtamt"];
					$receivechqno=$res_search["receivechqno"];
					
					if($statusApp==2){
						$txtstatus="กำลังรออนุมัติแก้ไข";
					}else{
						$txtstatus="-";
					}
					
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\" height=25>";
					}else{
						echo "<tr class=\"even\" align=\"center\" height=25>";
					}
					echo "<td>$boeNumber</td>";
					echo "<td align=left>$payUser</td>";
					echo "<td align=left>$purchaseUser</td>";
					echo "<td align=right>$loan_amount</td>";
					echo "<td align=right>$interest</td>";
					echo "<td>$payDate</td>";
					echo "<td>$returnDate</td>";
					echo "<td>$receivechqno</td>";
					echo "<td>$receivepaybackamt</td>";
					echo "<td>$receivewhtamt</td>";
					echo "<td>";
					echo "<input type=\"hidden\" name=\"lockboe\" id=\"lockboe\" value=\"$boeID\">";
					if($statusTicket=='t'){
						echo "<img src=\"images/edit.png\" width=\"16\" height=\"16\" border=\"0\" title=\"แก้ไข\" style=\"cursor:pointer;\" onclick=\"location.href='frm_Index.php?boeID=$boeID&method=edit'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						echo "<img src=\"images/unlock2.png\" width=\"21\" height=\"18\" border=\"0\" title=\"Lock ตั๋ว\" style=\"cursor:pointer;\" onclick=\"if(confirm('ยืนยันการ Lock ตั๋ว!!')){location.href='frm_Index.php?boeID=$boeID&method=lock'}\">";
					}else{
						echo "<img src=\"images/edit2.png\" width=\"16\" height=\"16\" border=\"0\" title=\"แก้ไขไม่ได้\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						echo "<img src=\"images/lock.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Lock แล้ว\" >";
					}
					echo "</td>";
					echo "</tr>";
				}
				if($numrow==0){
					echo "<tr><td colspan=8 height=50 align=center bgcolor=#FFFFFF>--ไม่พบข้อมูล--</td></tr>";
				}
			?>
		</table><br>
		<!-- เพิ่มข้อมูลตั๋ว -->
		<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFCCCC" align="center">
		<tr align="left">
			<td colspan="3" bgcolor="#FFFFFF" height="30"><img src="images/add.png" width="16" height="16" border="0">
			<?php 
			echo $txthead;
			if($method=="edit"){
				echo "<div style=\"float:right\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" value=\"เพิ่มตั๋วสัญญา\" onclick=\"location.href='frm_Index.php'\"></div>";
			}
			?>
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><br>
				<table width="400" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFCCCC" align="center">
				<tr bgcolor="#FFCECE">
					<td width="100" align="right"><b>เลขที่ตั๋วสัญญา</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" name="boeNumber" id="boeNumber" value="<?php echo $resedit["boeNumber"];?>"><font color="red"><b>*</b></font></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>ชื่อผู้ออกตั๋ว</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" name="payUser" id="payUser" size="40" value="<?php echo $resedit["payUser"];?>"><font color="red"><b>*</b></font></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>ชื่อผู้ซื้อตั๋ว</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" name="purchaseUser" id="purchaseUser"size="40" value="<?php echo $resedit["purchaseUser"];?>"><font color="red"><b>*</b></font></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>ยอดกู้</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" name="loan_amount" id="loan_amount" onkeypress="return check_number(event);" value="<?php echo $resedit["loan_amount"];?>" style="text-align:right;"><font color="red"><b>*</b></font></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>ดอกเบี้ย</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" name="interest" id="interest" onkeypress="return check_number(event);" value="<?php echo $resedit["interest"];?>" style="text-align:right;"><font color="red"><b>*</b></font></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>วันที่ออกตั๋ว</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" id="payDate" name="payDate" size="15" style="text-align:center" value="<?php echo $resedit["payDate"];?>"><font color="red"><b>*</b></font></td>
				</tr>
				<tr bgcolor="#AB82FF">
					<td align="right"><b>วันที่คืนตั๋ว</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#AB82F0"><input type="text" id="returnDate" name="returnDate" size="15" style="text-align:center" value="<?php echo $resedit["returnDate"];?>"></td>
				</tr>
				<tr bgcolor="#AB82FF">
					<td align="right"><b>เลขที่รับเช็ค</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#AB82F0"><input type="text" id="receivechqno" name="receivechqno" size="15" style="text-align:center" onkeypress="return check_number(event);" value="<?php echo $resedit["receivechqno"];?>"></td>
				</tr>
				<tr bgcolor="#AB82FF">
					<td align="right"><b>จำนวนเงินต้นดอกเบี้ยรับคืน (ก่อนหัก ภาษีหัก ณ ที่จ่าย)</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#AB82F0"><input type="text" id="receivepaybackamt" name="receivepaybackamt" size="15" style="text-align:center" onkeypress="return check_number(event);"value="<?php echo $resedit["receivepaybackamt"];?>"></td>
				</tr>
				<tr bgcolor="#AB82FF">
					<td align="right"><b>จำนวนเงิน ภาษีหัก ณ ที่จ่าย</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#AB82F0"><input type="text" id="receivewhtamt" name="receivewhtamt" size="15" style="text-align:center" onkeypress="return check_number(event);" value="<?php echo $resedit["receivewhtamt"];?>"></td>
				</tr>
				<tr bgcolor="#AB82FF">
					<td align="right"><b>เลขที่ใบ ภาษีหัก ณ ที่จ่าย</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#AB82F0"><input type="text" id="receivewhtref" name="receivewhtref" size="15" style="text-align:center" value="<?php echo $resedit["receivewhtref"];?>">
				    (เช่น KBANK-000001)</td>
				</tr>
				</table>
				<div style="padding:10px;text-align:center;">
					<input type="button" value="บันทึก" id="submitButton">
					<input type="button" id="cancelvalue" value="ยกเลิก">
				</div>
				<br>
			</td>
		</tr>
		</table>
	</div>
</div>
</body>
</html>
