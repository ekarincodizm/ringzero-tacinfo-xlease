<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$contractID=$_GET['contractID'];
$receiptID=$_GET['receiptID'];
$statusshow=$_GET['statusshow'];
$cancelID=$_GET['cancelID'];
$typePayID_new=$_GET['typePayID'];

//หา typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
list($typeID) = pg_fetch_array($select);

// หาประเภทสินเชื่อ
$creditType = pg_creditType($contractID);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายละเอียดการยกเลิก</title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function confirmapp()
{
	var numberOfConcerned = document.getElementById("numberOfConcerned").value;
	
	if(confirm('ยืนยันการอนุมัติยกเลิกใบเสร็จ!! \r\n'+'โดยจะยกเลิกใบเสร็จที่เกี่ยวข้องอีก '+numberOfConcerned +' รายการ'))
	{
		return true;
	}
	else
	{
		return false;
	}
}

$(document).ready(function(){
	$("#buttonsave").click(function(){
		if($("#resultcancel").val()==""){
			alert('กรุณาระบุเหตุผลที่ยกเลิกใบเสร็จ');
			$('#resultcancel').select();
			return false;
		}else{
			if(confirm('คุณยืนยันที่จะยกเลิกใบเสร็จเหล่านี้!!')){
				$("#buttonsave").submit();
			}else{
				return false;
			}
		}
	});
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script> 
</head>
<body>
<form method="post" name="form1" action="process_receiptcancel.php">
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#EAF9FF" align="center">
<?php
if($statusshow==1){
	//ตรวจสอบว่ารายการนี้ถูกยกเลิกหรือยัง
	$qrychkrec=pg_query("select * from thcap_temp_receipt_otherpay where \"receiptID\" = '$receiptID'");
	$numchkrec=pg_num_rows($qrychkrec);

	if($numchkrec==0){ //กรณีมีการลบก่อนหน้านี้แล้ว
		$status=-1;
		$stscheck=0;
	}else{
		//ตรวจสอบว่ามีเลขที่สัญญานั้นรออนุมัติอยู่หรือไม่
		$qrycheck=pg_query("select \"typePayID\" from thcap_temp_receipt_cancel a
		left join \"thcap_temp_receipt_otherpay\" b on a.\"receiptID\"=b.\"receiptID\"
		where a.\"contractID\" = '$contractID' and \"approveStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
		if($numcheck>0){ //กรณีมีรายการที่รออนุมัติ
			//ตรวจสอบว่ารายการที่รอยกเลิกอยู่ มียกเลิกค่างวดหรือไม่
			while($rescheck=pg_fetch_array($qrycheck)){
				$typePayID=$rescheck["typePayID"]; //รหัสจ่ายที่รออนุมัติอยู่
				
				if($typeID==$typePayID and $typeID==$typePayID_new){ //กรณีที่ รายการเดิมเป็นค่างวด และรายการใหม่เป็นค่างวดเช่นกัน จะไม่สามารถขออนุมัติได้
					$cantreq=1; //กำหนดให้หยุดไม่สามารถทำรายการได้
					break;
				}
			}
			if($cantreq==1){
				$stscheck=0;
			}else{
				$stscheck=1;
			}
		}else{
			$stscheck=1;
		}
	}
	
}else{
	//ตรวจสอบข้อมูลก่อนว่ารายการได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง
	$qrycheck=pg_query("select * from thcap_temp_receipt_cancel where \"contractID\" = '$contractID' and \"approveStatus\"='2'");
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck==0){ //เท่ากับ 0 แสดงว่ามีการอนุมัติรายการแล้ว
		$stscheck=2;
		echo "<tr><td colspan=3 align=center>
		<h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้วค่ะ</h2><br>
		<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" /></td></tr>";
	}else{
		$stscheck=1;
	}
}
if($stscheck==1){
?>
<tr>
    <td align="center" colspan="3">
	<h2>
	<?php
	if($statusshow==1){
		echo "- ยืนยันการยกเลิกใบเสร็จ -";
	}else{
		echo "อนุมัติการยกเลิกใบเสร็จ";
	}
	?>
	</h2>
	</td>
</tr>
<?php
if($statusshow==2){ //กรณีเลือกตรวจสอบตอนอนุมัติ
?>
<tr><td align="right"><span onclick="window.close();" style="cursor:pointer;"><u>X ปิดหน้านี้</u></span></td></tr>
<?php
	//ตรวจสอบก่อนว่าใบเสร็จนี้ได้ถูกลบไปแล้วหรือยัง 
	$qrychkreceipt=pg_query("select * from thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
	$nubchkrec=pg_num_rows($qrychkreceipt);
	
	if($nubchkrec==0){ 
		//ตรวจสอบว่าเป็นค่างวดที่ไม่ได้มาจากตาราง 201201 หรือไม่
		$qryreceive=pg_query("SELECT * FROM thcap_v_receipt_otherpay where \"receiptID\" = '$receiptID'");
		$numreceive=pg_num_rows($qryreceive);
		if($numreceive==0){ //แสดงว่ารายการถูกลบแล้ว
			$showreceipt=1; //ให้แสดงรายการว่ารับทราบ
		}else{
			$showreceipt='0';
		}
	}else{
		$showreceipt=0; //ให้แสดงรายละเอียดการยกเลิก
	}
}

if($showreceipt==1){
	echo "<tr><td align=center><h1><font color=red>-ยกเลิกรายการเนื่องจากไม่มีใบเสร็จนี้แล้ว-</font></h1></td></tr>";
	echo "<tr><td align=center height=50><input type=\"button\" value=\"  รับทราบ  \" onclick=\"location.href='process_receiptcancel.php?cancelID=$cancelID&method=approve3'\"></td></tr>";
}else{
?>
<tr>
    <td height="25"><b>ใบเสร็จที่ขอยกเลิก: <font color="red"><?php echo $receiptID; ?></font></b></td>
</tr>
<tr>
    <td height="25" bgcolor="#FFEFD5">
	<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px"><tr><td>
	<?php 
	$showconfig="no";
	include "Channel_detail.php"; 
	?>
	</td></tr></table>
	</td>
</tr>

<tr>
    <td>
		<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#BCE6FC" align="center">
			<tr height="25"><td colspan="6" style="background-color:#528B8B;color:#FFF;font-weight:bold;"><b>หากยกเลิกใบเสร็จ <?php echo $receiptID; ?> จะมีผลให้ยกเลิกใบเสร็จที่เกี่ยวข้องด้วยดังนี้</b></td></tr>
			<tr>
				<th width="70">รายการที่</th>
				<th>เลขที่ใบเสร็จ</th>
				<th>receiveDate</th>
				<th>ช่องทางการจ่าย</th>
				<th>จำนวนเงิน</th>
				<th></th>
			</tr>
			
		<?php
			//หาเลขที่สัญญาของใบเสร็จนี้
			$qrycontractID=pg_query("select \"contractID\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' limit (1)");
			list($contractID)=pg_fetch_array($qrycontractID);
			
			//หาวันที่ receiveDate เพื่อนำมาตรวจสอบลบค่า Gen
			$qryserial=pg_query("select \"serial\" from thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
			$numserial=pg_num_rows($qryserial);
			$resdate=pg_fetch_array($qryserial);
			$serial=$resdate["serial"];
		
			//แสดงรายการที่เกี่ยวข้องรวมทั้งค่า Gen ที่เกี่ยวข้อง
			if($numserial>0){
				$qryreceive=pg_query("select \"receiptID\", \"receiveDate\", \"genCloseMonth\", \"receiveAmount\" FROM thcap_temp_int_201201 where \"contractID\"='$contractID' 
				and \"serial\" > '$serial' order by \"serial\"");
			}else{ //กรณีเป็นค่างวดแต่ไม่ได้อยู่ในตาราง 201201
				//หาใบเสร็จค่างวดที่เกิดหลังใบเสร็จนี้
				
				// หาเลขงวด (typePayRefValue) ที่มากที่สุดในใบเสร็จนั้น
				$qry_typePayRefValue = pg_query("select max(\"typePayRefValue\"::integer) from \"thcap_v_receipt_otherpay\" where \"receiptID\" = '$receiptID' and \"typePayID\" = account.\"thcap_mg_getMinPayType\"(\"contractID\") ");
				$maxTypePayRefValue = pg_fetch_result($qry_typePayRefValue,0);
				
				
				
				$qryreceive=pg_query("SELECT \"receiptID\", \"receiveDate\",\"debtAmt\" as \"receiveAmount\", \"nameChannel\"
				  FROM thcap_v_receipt_otherpay a
				  left join thcap_temp_otherpay_debt b on a.\"debtID\"=b.\"debtID\"
				where a.\"typePayID\" = '$typeID' and a.\"contractID\"='$contractID' and a.\"typePayRefValue\"::integer > '$maxTypePayRefValue'");
			}
			$i=0;
			if($creditType != "JOINT_VENTURE")
			{
				while($resshow=pg_fetch_array($qryreceive))
				{
					$i++;
					$receiptID2=$resshow["receiptID"];
					$receiveDate=$resshow["receiveDate"];
					$receiveAmount=$resshow["receiveAmount"]; 
					if($resshow["genCloseMonth"]!=""){
						$receiptID2="GenClose วันที่ ".$resshow["genCloseMonth"];
						$receiveAmount="";
					}
					
					//หาช่องทางการรับชำระที่ thcap_v_receipt_otherpay
					$qry_annel=pg_query("select \"nameChannel\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID2'");
					list($byChannel)=pg_fetch_array($qry_annel);
					
					if($byChannel==""){$txtchannel="ไม่ระบุ";}
					else{
						$txtchannel="$byChannel";
					}

					echo "<tr bgcolor=#EAF9FF align=center>
					<td>$i</td>
					<td>$receiptID2</td>
					<td>$receiveDate</td>
					<td>$txtchannel</td>
					<td>$receiveAmount</td>
					<td>
					";
					if($resshow["receiptID"]!=""){
						echo "<img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('Channel_detail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer;\">";
					}else{
						echo "";
					}
					echo "</td></tr>";
				}
			}
			if($i==0){
				echo "<tr bgcolor=#EAF9FF align=center>
				<td colspan=6 height=50 align=center>ไม่มีใบเสร็จเกี่ยวข้อง ลบแค่ใบเสร็จเดียวคือ  <b>$receiptID</b></td></tr>";
			}
			
			?>
				<input type="hidden" name="numberOfConcerned" id="numberOfConcerned" value="<?php echo $i; ?>"> <!-- จำนวนใบเสร็จอื่นๆที่จะถูกยกเลิกไปด้วย -->
			<?php
			
			//หาเหตุผลโดยการนำเลขที่ใบเสร็จไปค้นในตาราง  thcap_temp_receipt_cancel
			$qryresult=pg_query("SELECT result FROM thcap_temp_receipt_cancel where \"receiptID\"='$receiptID' and \"approveStatus\"='2'");
			$resresult=pg_fetch_array($qryresult);
			list($result)=$resresult;
		?>
		<tr bgcolor="#F5F5F5">
			<td colspan="6"><b>::เหตุผลที่ยกเลิก::</b><br><textarea name="resultcancel" id="resultcancel" cols="50" rows="5" <?php if($result!="") echo "readonly=true";?>><?php echo $result;?></textarea></td>
		</tr>
		</table>
	</td>
</tr>
<?php
	if($statusshow==1){
?>
	<tr><td align="center" bgcolor="#FFFFFF" height="50">
	<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
	<input type="hidden" name="receiptID" value="<?php echo $receiptID;?>">
	<input type="hidden" name="receiveDate" value="<?php echo $receiveDate;?>">
	<input type="hidden" name="method" value="request">
	<input type="submit" value="บันทึก" id="buttonsave"><input type="button" onclick="window.close();" value="ปิดหน้านี้">
	</td></tr>
<?php
	}else{
?>
	<tr><td align="center" bgcolor="#FFFFFF" height="50">
		<!--input type="button" value="อนุมัติ" onclick="if(confirm('ยืนยันการอนุมัติยกเลิกใบเสร็จ!!')){location.href='process_receiptcancel.php?contractID=<?php echo $contractID;?>&receiptID=<?php echo $receiptID;?>&receiveDate=<?php echo $receiveDate;?>&cancelID=<?php echo $cancelID;?>&method=approve1'}">
		<input type="button" value="ไม่อนุมัติ" onclick="location.href='process_receiptcancel.php?contractID=<?php echo $contractID;?>&receiptID=<?php echo $receiptID;?>&receiveDate=<?php echo $receiveDate;?>&cancelID=<?php echo $cancelID;?>&method=approve0'"-->
	</td>
	<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID;?>">
		<input type="hidden" name="receiptID" id="receiptID" value="<?php echo $receiptID;?>">
		<input type="hidden" name="receiveDate" id="receiveDate" value="<?php echo $receiveDate;?>">
		<input type="hidden" name="cancelID" id="cancelID" value="<?php echo $cancelID;?>">	<td align="center" bgcolor="#FFFFFF" height="50">
		<input type="submit" name="rec_appv" value="อนุมัติ" onclick="return confirmapp();">
		<input type="submit" name="rec_unappv" value="ไม่อนุมัติ">
		<input type="button" value="ปิด" onClick="window.close();">
	</tr>
<?php
	}
	}//ปิด else กรณีแสดงรายการปกติ
}else if($stscheck=='0'){ //ไม่ให้ทำรายการต่อ
	if($status==-1){
		echo "<div align=center><h2>รายการนี้ได้ถูกยกเลิกไปก่อนหน้านี้แล้ว</h2></div>";	
	}else{
		echo "<div align=center><h2>ไม่สามารถขอยกเลิกใบเสร็จได้ เนื่องจากมีใบเสร็จค่างวดที่รออนุมัีติอยู่</h2></div>";
	}
	echo "<div align=center><input type=\"button\" value=\"ปิด\" onclick=\"window.close();\" style=\"width:100px;height:40px;\"></div>";
}

?>
</table>
</form>
</body>
</html>