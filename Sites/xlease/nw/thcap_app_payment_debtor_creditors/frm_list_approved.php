<br><br>
<?php
//ตรวจสอบว่า สัญญานั้นมีอยู่ในระบบหรือไม่
$sql = pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\"='$contractID'");
$numrows_conid = pg_num_rows($sql);
	
	if(($numrows_conid==0)){}
	else{
	// หาจำนวนเงินลงทุน โดยใส่ parameter type = 3 (เงินลงทุนรวมภาษีมูลค่าเพิ่ม (ถ้ามี) ก่อนหักเงินดาวน์ (ถ้ามี))
		$qry = pg_query("SELECT \"thcap_get_iniinvestmentamt\"('$contractID','3')");
		list($res_amount) = pg_fetch_array($qry);
	}
//วันที่สัญญามีผล
$qry_conStartDate = pg_query("select \"conStartDate\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
$conStartDate = pg_fetch_result($qry_conStartDate,0);
if($conStartDate==''){$conStartDate='-';}	
//หารายการที่อนุมัติแล้วทั้งหมด
$qry_sum = pg_query("SELECT \"thcap_get_all_payment_paid_for_contract\"('$contractID')");
list($res_ChannelAmt) = pg_fetch_array($qry_sum);	
//จำนวนเงินคงเหลือที่ต้องชำระ
$net=$res_amount-$res_ChannelAmt;
?>
<div style="margin-top:10px;"align="center">
<table align="center" cellpadding="3" cellspacing="0" border="0" width="100%">

<tr><td align="center">จำนวนเงินที่ต้องชำระทั้งหมด :</td><td><b><?php echo number_format($res_amount,2);?></b> บาท.</td>
	<td align="center">จำนวนเงินที่ชำระไปแล้ว :</td><td><b><?php echo number_format($res_ChannelAmt,2);?></b> บาท.</td>
	<td align="center">จำนวนเงินคงเหลือที่ต้องชำระ :</td><td><b><?php echo number_format($net,2);?></b> บาท.</td>
<table width="95%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr bgcolor="#FFFFFF">	
	<td colspan="4" align="left" height="15"><u><b>หมายเหตุ</b></u>
		<div><span style="background-color:#FFDEAD;">&nbsp;&nbsp;&nbsp;</span> รายการสีครีม คือ ข้อมูลที่รออนุมัติ</div>
		<div style="padding-top:5px;"><span style="background-color:#EE3B3B;">&nbsp;&nbsp;&nbsp;</span> รายการสีแดง คือ ข้อมูลที่ถูกยกเลิก</div>		
	</td>
	</tr>
<table>				
<tr><td colspan="6"><table align="center">
		<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่ voucher</th>
		<th>เลขที่สัญญา</th>
		<th>วันที่สัญญามีผล</th>
		<th>ชื่อผู้กู้/ผู้ซื้อหลัก</th>
		<th>วันเวลาที่ใบสำคัญมีผล</th>
		<th>จุดประสงค์</th>
		<th>จำนวนเงิน</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>สถานะ</th>		
	</td></tr>
	
</tr>
</tr>
<?php 
	$sql_appv = pg_query("select *  from \"thcap_temp_payment_debtor_creditors_cost\" where  \"contractID\"='$contractID' AND \"appvStatus\" in ('1','9')");
	$no=0;	
	
	while($res_main = pg_fetch_array($sql_appv))
	{	
			$auto_id = $res_main["auto_id"];
			$voucherID = $res_main["voucherID"];
			$contractID = $res_main["contractID"];
			$fullname = $res_main["fullname"];
			$voucherDate = $res_main["voucherDate"];
			$voucherTime = $res_main["voucherTime"];
			$voucherPurpose = $res_main["voucherPurpose"];//จุดประสงค์
			$ChannelAmt = $res_main["ChannelAmt"];
			$doerID = $res_main["doerID"];
			$doerStamp = $res_main["doerStamp"];
			$appvStatus = $res_main["appvStatus"];
			
			$no+=1;
			//สถานะ  $appvStatus
			if($appvStatus=='1'){
				$appvStatus="อนุมัติ"; 
				$qry = pg_query("select \"voucherID\" from \"v_thcap_temp_voucher_details_all_active\" where \"voucherID\"='$voucherID'");
				$num2=pg_num_rows($qry);
				if($num2==0){
					$appvStatus="ถูกยกเลิก";
					echo "<tr  bgcolor=\"#EE3B3B\"  align=\"center\" style=\"font-size: 11px;\" height=25>";	}
				else{
					if($no%2==0)
					{
						echo "<tr class=\"odd\" align=\"center\" height=25>";				
					}
					else
					{
						echo "<tr class=\"even\" align=\"center\" height=25>";				
					}	
				}	
			}
			else if($appvStatus=='9'){
				$appvStatus="รออนุมัติ";
				echo "<tr  bgcolor=\"#FFDEAD\"  align=\"center\"  style=\"font-size: 11px;\" height=25>";	
			}
			else{
				
			
			}
			
			if($ChannelAmt !=''){$ChannelAmt=number_format($ChannelAmt,2);}
			//จุดประสงค์
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
			//ชื่อผู้ทำรายการ
			$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$doerID' ");
			$doerName = pg_fetch_result($qry_doername,0);
			
			
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\">$voucherID</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";
			echo "<td align=\"center\">$conStartDate</td>";			
			echo "<td align=\"left\">$fullname</td>";
			echo "<td align=\"center\">$voucherDate".' '.$voucherTime."</td>";			
			echo "<td align=\"left\">$purpose_name</td>";
			echo "<td align=\"right\">$ChannelAmt</td>";
			echo "<td align=\"center\">$doerName</td>";
			echo "<td align=\"center\">$doerStamp</td>";
			echo "<td align=\"center\">$appvStatus</td>";
			
			echo "</tr>";
	}
	if($no==0){
		echo "<tr class=\"odd\"><td align=\"center\" colspan=\"10\"><h2> ไม่พบรายการ  </h2></td></tr>"; 	
	}
	
?>
</table>
</div>