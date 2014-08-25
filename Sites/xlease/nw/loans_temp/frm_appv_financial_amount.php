<?php
include('../../config/config.php');
$iduser = $_SESSION["av_iduser"];
$contractAutoID = pg_escape_string($_GET["contractAutoID"]);
$menu= pg_escape_string($_GET['namemenu']); //ใช้ ตรวจสอบว่า ถูกเรียกใช้จากเมนูไหน
$lookonly = pg_escape_string($_GET['lonly']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<?php if($menu =="check") {?>
	<title>ตรวจสอบ ผูกสัญญาวงเงินชั่วคราว</title>
<?php } else {?>
	<title>อนุมัติ ผูกสัญญาวงเงินชั่วคราว</title>
<?php }?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<?php if($menu =="check") {?>
	<center><h2>ตรวจสอบ ผูกสัญญาวงเงินชั่วคราว</h2></center>
<?php } else {?>
	<center><h2>อนุมัติ ผูกสัญญาวงเงินชั่วคราว</h2></center>
<?php }?>
<body >
<?php
echo $namemenu;
$query_main = pg_query("select * from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
while($result = pg_fetch_array($query_main))
{
	$contractID = $result["contractID"]; // เลขที่สัญญา
	$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
	$conLoanAmt = $result["conLoanAmt"]; // จำนวนเงินกู้
	$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
	$doerUser = $result["doerUser"]; // ผู้ทำรายการ
	$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
	$conCompany = $result["conCompany"]; // รหัสบริษัท
	$conLoanIniRate = $result["conLoanIniRate"]; // อัตราดอกเบี้ยที่ตกลงตอนแรก
	$conLoanMaxRate = $result["conLoanMaxRate"]; // อัตราดอกเบี้ยสูงสุด
	$conTerm = $result["conTerm"]; // ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
	$conMinPay = $result["conMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due
	$conPenaltyRate = $result["conPenaltyRate"]; // ค่าติดตามทวงถามปัจจุบัน
	$conDate = $result["conDate"]; // วันที่ทำสัญญา
	$conStartDate = $result["conStartDate"]; // วันที่รับเงินที่ขอกู้
	$conEndDate = $result["conEndDate"]; // วันที่สิ้นสุดการกู้ที่ระบุไว้ในสัญญา
	$conFirstDue = $result["conFirstDue"]; // Due แรก
	$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
	$conFreeDate = $result["conFreeDate"]; // วันที่พ้นกำหนดห้ามปิดบัญชีก่อนกำหนด (Default = กึ่งหนึ่งของระยะเวลาทั้งสัญญา)
	$conClosedDate = $result["conClosedDate"]; // วันที่ปิดบัญชีจริง
	$conClosedFee = $result["conClosedFee"]; // % ค่าปรับปิดบัญชีก่อนกำหนด คิดจากยอดกู้
	$conStatus = $result["conStatus"]; // NCB...
	$conFlow = $result["conFlow"]; // สถานะสัญญา / internal
	$rev = $result["rev"]; // เปลี่ยนแปลงสัญญาครั้งที่
	$conCreditRef = $result["conCreditRef"]; // สัญญากู้นี้ใช้วงเงินไหน วงเงินเท่าไหร่
	$CusIDarray = $result["CusIDarray"]; // ประเภทลูกค้า และ รหัสลูกค้า
	$addrTempID = $result["addrTempID"]; // รหัสที่อยู่ของตาราง thcap_addrContractID_temp
	$editNumber = $result["editNumber"]; // จำนวนครั้งที่แก้ไข
	$connote = $result["conNote"];//หมายเหตุ
	if($conCredit != "")
	{
		$conCreditText = number_format($conCredit,2)." บาท";
	}
	else
	{
		$conCreditText = "";
	}
}

// หาที่อยู่
$query_addr = pg_query("select * from public.\"thcap_addrContractID_temp\" where \"tempID\" = '$addrTempID' ");
while($result_addr = pg_fetch_array($query_addr))
{
	$addsType = $result_addr["addsType"]; // รหัสสถานะที่อยู่
	$A_NO = $result_addr["A_NO"];
	$A_SUBNO = $result_addr["A_SUBNO"];
	$A_BUILDING = $result_addr["A_BUILDING"];
	$A_ROOM = $result_addr["A_ROOM"];
	$A_FLOOR = $result_addr["A_FLOOR"];
	$A_VILLAGE = $result_addr["A_VILLAGE"];
	$A_SOI = $result_addr["A_SOI"];
	$A_RD = $result_addr["A_RD"];
	$A_TUM = $result_addr["A_TUM"];
	$A_AUM = $result_addr["A_AUM"];
	$A_PRO = $result_addr["A_PRO"];
	$A_POST = $result_addr["A_POST"];
}
$textAddr = "";
$textAddr .= "$A_NO";
if($A_SUBNO != "" && $A_SUBNO != "-" && $A_SUBNO != "--"){$textAddr .= " หมู่$A_SUBNO";}
if($A_BUILDING != "" && $A_BUILDING != "-" && $A_BUILDING != "--"){$textAddr .= " อาคาร$A_BUILDING";}
if($A_ROOM != "" && $A_ROOM != "-" && $A_ROOM != "--"){$textAddr .= " ห้อง$A_ROOM";}
if($A_FLOOR != "" && $A_FLOOR != "-" && $A_FLOOR != "--"){$textAddr .= " ชั้น$A_FLOOR";}
if($A_VILLAGE != "" && $A_VILLAGE != "-" && $A_VILLAGE != "--"){$textAddr .= " หมู่บ้าน$A_VILLAGE";}
if($A_SOI != "" && $A_SOI != "-" && $A_SOI != "--"){$textAddr .= " ซอย$A_SOI";}
if($A_RD != "" && $A_RD != "-" && $A_RD != "--"){$textAddr .= " ถนน$A_RD";}
if($A_TUM != "" && $A_TUM != "-" && $A_TUM != "--"){$textAddr .= " ตำบล$A_TUM";}
if($A_AUM != "" && $A_AUM != "-" && $A_AUM != "--"){$textAddr .= " อำเภอ$A_AUM";}
if($A_PRO != "" && $A_PRO != "-" && $A_PRO != "--"){$textAddr .= " $A_PRO";}
if($A_POST != "" && $A_POST != "-" && $A_POST != "--" && $A_POST != "0"){$textAddr .= " $A_POST";}
// จบการหาที่อยู่

$count=0;

?>
<table width="900"  cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#ECFAFF" id="tble" border="0">
<tr>
	<td width="25%"><br></td>
	<td width="45%"><br></td>
	<input type="hidden" name="valuechk" id="valuechk">
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right">เลขที่สัญญา : </td>
	<td><?php echo $contractID; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	
	<td align="right">ประเภทสินเชื่อ : </td>
	<td><?php echo $conType; ?></td>	
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	
	<td align="right">บริษัท : </td>
	<td><?php echo $conCompany; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right">วงเงินที่ปล่อย : </td>
	<td><?php echo $conCreditText; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	
	<td align="right">ดอกเบี้ยคุม : </td>
	<td><?php echo "$conLoanIniRate %"; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
		<td align="right">วันที่ทำสัญญาวงเงิน : </td>
	<td><?php echo $conDate; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	
	<td align="right">ผู้กู้หลัก : </td>
	<?php
		$haveCusMain = "no";
		$qry_cusMain = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusMainType\", a.\"contractID\"
								FROM thcap_contract_temp a
								WHERE a.\"autoID\" = '$contractAutoID'
								order by \"cusMainType\" ");
		while($res_cusMain = pg_fetch_array($qry_cusMain))
		{
			$cusMainType = $res_cusMain["cusMainType"]; // ประเภทลูกค้า
			if($cusMainType == "0") // ถ้าเป็นผู้กู้หลัก
			{
				$haveCusMain = "yes";
				$qry_cusMainID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '0') AS \"cusMainID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID' ");
				while($res_cusMainID = pg_fetch_array($qry_cusMainID))
				{
					$cusMainID = $res_cusMainID["cusMainID"];
					
					// หาชื่อ
					$qry_cusMainName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$cusMainID' ");
					while($res_cusMainName = pg_fetch_array($qry_cusMainName))
					{
						$cusMainName = $res_cusMainName["full_name"];
						$cusTypemain = $res_cusMainName["type"]; // ประเภทลูกค้าหลัก
					}
					//เพิ่มการ link ข้อมูล
					checktypecus($cusTypemain,$cusMainName,$cusMainID);
				}
			}
		}
		
		if($haveCusMain == "no")
		{
			echo "<td></td>";
		}
	?>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
	<?php // หา ผู้กู้ร่วม
		$haveCusJoin = "no";
		$J = 0;
		$qry_cusJoin = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusJoinType\", a.\"contractID\"
								FROM thcap_contract_temp a
								WHERE a.\"autoID\" = '$contractAutoID'
								order by \"cusJoinType\" ");
		while($res_cusJoin = pg_fetch_array($qry_cusJoin))
		{
			$cusJoinType = $res_cusJoin["cusJoinType"]; // ประเภทลูกค้า
			if($cusJoinType == "1") // ถ้าเป็นผู้กู้ร่วม
			{
				$haveCusJoin = "yes";
				$qry_cusJoinID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '1') AS \"cusJoinID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID' ");
				while($res_cusJoinID = pg_fetch_array($qry_cusJoinID))
				{
					$J++;
					$cusJoinID = $res_cusJoinID["cusJoinID"];
					
					// หาชื่อ
					$qry_cusJoinName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$cusJoinID' ");
					while($res_cusJoinName = pg_fetch_array($qry_cusJoinName))
					{
						$cusJoinName = $res_cusJoinName["full_name"];
						$cusTypejoin = $res_cusJoinName["type"]; // ประเภทลูกค้าร่วม
						
					}					
					$count+=1;
					if($menu =="check") 
					{
						if($count%2==0){
						echo "<tr>";
						}else { 
						echo "<tr bgcolor=\"#BFEFFF\" >";}
					}else{
						echo "<tr>";
					}
					echo "<td align=\"right\">ผู้กู้ร่วม คนที่ $J :</td>";
					//เพิ่มการ link ข้อมูล
					checktypecus($cusTypejoin,$cusJoinName,$cusJoinID);
					if($menu =="check") 
					{ 	
						$chk="chk".$count;
						$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
						echo "<td align=\"right\">$check</td>";
					}					
					echo"</tr>";
				}
			}
		}
		
		if($haveCusJoin == "no")
		{	 $count+=1;
			if($menu =="check") 
			{
				if($count%2==0){
					echo "<tr>";
				}else { 
					echo "<tr bgcolor=\"#BFEFFF\" >";}
			}else{
				echo "<tr>";
			}
			echo "<td align=\"right\">ผู้กู้ร่วม : </td><td></td>";
			if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}			
			echo "</tr>";
		}
	?>
	<?php // หา ผู้ค้ำประกัน
		$haveCusGuarantor = "no";
		$G = 0;
		$qry_cusGuarantor = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusGuarantorType\", a.\"contractID\"
								FROM thcap_contract_temp a
								WHERE a.\"autoID\" = '$contractAutoID'
								order by \"cusGuarantorType\" ");
		while($res_cusGuarantor = pg_fetch_array($qry_cusGuarantor))
		{
			$cusGuarantorType = $res_cusGuarantor["cusGuarantorType"]; // ประเภทลูกค้า
			if($cusGuarantorType == "2") // ถ้าเป็นผู้ค้ำประกัน
			{
				$haveCusGuarantor = "yes";
				$qry_cusGuarantorID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '2') AS \"cusGuarantorID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID' ");
				while($res_cusGuarantorID = pg_fetch_array($qry_cusGuarantorID))
				{
					$G++;
					$cusGuarantorID = $res_cusGuarantorID["cusGuarantorID"];
					
					// หาชื่อ
					$qry_cusGuarantorName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$cusGuarantorID' ");
					while($res_cusGuarantorName = pg_fetch_array($qry_cusGuarantorName))
					{
						$cusGuarantorName = $res_cusGuarantorName["full_name"];
						$cusTypeGuarantor = $res_cusGuarantorName["type"]; // ประเภทผู้คำ้ประกัน
						
					}
					$count+=1;
					if($menu =="check") 
					{
						if($count%2==0){
							echo "<tr>";
						}else { 
							echo "<tr bgcolor=\"#BFEFFF\" >";}
					}else{
						echo "<tr>";
					}
					echo "<td align=\"right\">ผู้ค้ำประกัน คนที่ $G :</td>";
					//เพิ่มการ link ข้อมูล
					checktypecus($cusTypeGuarantor,$cusGuarantorName,$cusGuarantorID);
					if($menu =="check") 
					{ 	
						$chk="chk".$count;
						$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
						echo "<td align=\"right\">$check</td>";
					}			
					echo "</tr>";
				}
			}
		}
		
		if($haveCusGuarantor == "no")
		{
			$count+=1;
			if($menu =="check") 
			{
				if($count%2==0){
					echo "<tr>";
				}else { 
					echo "<tr bgcolor=\"#BFEFFF\" >";}
			}else{
				echo "<tr>";
			}
			echo "<td align=\"right\">ผู้ค้ำประกัน : </td><td></td>";
			if($menu =="check") 
			{ 	
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}			
			echo "</tr>";
		}
	?>
	
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#BFEFFF\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right" valign="top">รายละเอียดที่อยู่ :</td>
	<td><textarea cols="50" name="address" id="address" rows="5" style="background-color: #DDDDDD;" readonly ><?php echo $textAddr;?></textarea></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
	
</tr>
<form name="my" method="post" action="processcontract_check.php">

<?php
	if($menu =="check") 
	{ 	?>
		<tr>
		<td align="right" valign="top">หมายเหตุการตรวจสอบ :</td>
		<td><textarea cols="50" name="note" id="note" rows="5" ></textarea><font color="red">
		<span id="require">*</span><span name="f_note" id="f_note"></span></font></td>
			
		<?php	
	}?>		
	</tr>	
		

<input type="hidden" name="type" id="type" value="appv_financial">
<input type="hidden" name="contractAutoID" id="contractAutoID" value="<?php echo $contractAutoID; ?>">
<?php		
	if($menu =="check") 
	{ 	
		$True1="<tr><td colspan=\"2\" align=\"center\">
		<input type=\"submit\" name=\"appv\" value=\"ถูกต้อง\" onclick=\"return ChecktrueOrfalse('$contractAutoID',true);\"> &nbsp;&nbsp;&nbsp; 
		<input type=\"submit\" name=\"unappv\" value=\"ไม่ถูกต้อง/มีข้อสงสัย\" onclick=\"return ChecktrueOrfalse('$contractAutoID',false);\"> &nbsp;&nbsp;&nbsp; 
		<input type=\"submit\" name=\"unappv\" value=\"ออก\" onclick=\"javascript:window.close();\"></td></tr>"; 
		echo "<td>$True1</td>";
	} 
	else{	
	?>
</form>
	
	<form method="post" action="process_appv.php">
	<tr>
		<td align="right" valign="top">หมายเหตุการอนุมัติ :</td>
		<td><textarea cols="50" name="note" id="note" rows="5" <?php if($lookonly == 'true'){ ?> style="background-color: #DDDDDD;" readonly <?php } ?>><?php if($lookonly == 'true'){echo $connote ;}?></textarea>
		<font color="red">
		<span id="require">*</span><span name="f_note" id="f_note"></span></font></td>
		
	</tr>	
	<?php if($lookonly != 'true'){ ?>
	<tr>
		<td colspan="2" align="center">		
			<input type="hidden" name="contractAutoID" id="contractAutoID" value="<?php echo $contractAutoID; ?>">
			<input type="hidden" name="doerStamp" id="doerStamp" value="<?php echo $doerStamp; ?>">
			<input name="appv" type="submit" value="อนุมัติ" onclick="return Checktruenoteapp()" />
			<input name="unappv" type="submit" value="ไม่อนุมัติ" onclick="return Checktruenoteapp()"/>
		</form>
		</td>
	</tr>
	<?php } 
	}?>
<tr>



	<td><br></td>
</tr>	
</table>
<!--ตาราง รายชื่อคนตรวจสอบ-->
 <table align="center" width="50%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
		<tr align="center" bgcolor="#79BCFF">
			<th>ตรวจสอบครั้งที่</th>
			<th>รายชื่อผู้ตรวจสอบ</th>
			<th>วันที่ตรวจสอบ</th>
			<th>หมายเหตุ</th>
			<th>ผลการตรวจสอบ</th>		
		</tr>
	<?php 
		//ตรวจสอบ level ของ ผู้ใช้งาน
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];
		
		$query_main = pg_query("select * from \"thcap_contract_check_temp\" where \"ID\" = '$contractAutoID' order by  \"appvStamp\" desc");
		$row_check = pg_num_rows($query_main);
		$no=$row_check;
		if($row_check>0){
			while($result = pg_fetch_array($query_main))
			{   
				$autoIDSelect=$result ["autoID"];
				$appvID= $result ["appvID"];
				$appvStamp=$result ["appvStamp" ];
				$note=$result ["note"];
				$Approved=$result ["Approved"];
			
			
				if($Approved=="1"){$Approved="ถูกต้อง";}
				else{$Approved="ไม่ถูกต้อง";}
			
				if(($emplevel<=1) or $appvID==$iduser)
				{
					$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$appvID' ");
					$nameuser = pg_fetch_array($query_fullname);
					$fullname=$nameuser["fullname"];
				}
				else{ 
					//$fullname="ผู้ตรวจสอบลำดับที่ ".$row_check;
					$fullname="T".($appvID+2556);//เลขที่ผู้ใช้ที่ตรวจสอบ+2556  เพื่อไม่ให้ ผู้ใช้เห็นว่าใครเป็นคนตรวจสอบ
					$row_check-=1;
				}
			
			echo "<tr bgcolor=\"#B2DFEE\">";	
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\">$fullname</td>";
			echo "<td align=\"center\">$appvStamp</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_contract_check/frm_note_contract_check.php?autoIDCheck=$autoIDSelect','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>หมายเหตุ</u></font></a></td>";
			echo "<td align=\"center\">$Approved</td>";		
			echo "</tr>";	
			$no-=1;
			}
		} else {
			echo "<tr bgcolor=\"#B2DFEE\">";
			echo "<td align=\"center\" colspan=\"5\"><font color=\"#FF0000\">ไม่มีผู้ตรวจสอบสัญญา!!</font></td>";
			echo "</tr>";
		}
function checktypecus($cusType,$cusMain,$cusID){
	if($cusType == 1)
	{
		echo "<td>$cusMain<a onclick=\"javascript:popU('../manageCustomer/showdetail2.php?CusID=$cusID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
	}
	elseif($cusType == 2)
	{
		$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusID' ");
		$corp_regis = pg_fetch_result($qry_corp_regis,0);
		echo "<td>$cusMain<a onclick=\"javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
	}
	else{echo "<td>$cusMain</td>";}
}
	?>	
</table>

</body>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function ChecktrueOrfalse(contractAutoID,Approved) //ตรวจสอบการ ติกถูกต้อง ว่าครบหรือไม่
{   if(document.getElementById("note").value==''){alert('กรุณาป้อนข้อมูล หมายเหตุ');return false;	}
    else{
		var j=1;
		var ncount=1;
		while(j<=<?php echo $count?>)
		{ 
			if(document.getElementById("chk"+j).checked == true)
			{
				ncount+=1;	   
			}
		j++;
		}
		if(ncount!=j)
		{ 
			alert('กรุณาตรวจสอบข้อมูลให้ครบ');
			return false;
		}	
		else{ return true;		
		}
	}	
}
function Checktruenoteapp(){
	if(document.getElementById("note").value==''){alert('กรุณาป้อนข้อมูล หมายเหตุ');return false;	}
    else{return true;		
	}
}
</script>