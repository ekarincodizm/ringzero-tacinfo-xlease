<?php
require_once("../../config/config.php");

/*
=========================================================================================
	รายละเอียด $tab_id
=========================================================================================
	0 - แสดงทั้งหมด
	XX - แสดงตามประเภทสัญญานั้นๆ
*/
$tab_id = pg_escape_string($_GET["tabid"]);
$all = pg_escape_string($_GET["all"]);
if($all !='1'){
	$select_limit=" limit 30" ;
}
if($tab_id == '0'){
	// เอาทุกสัญญา where เลยไม่มี
	$where = "SELECT * FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE  \"appvStatus\" <> '9'  
			ORDER BY \"appvStamp\" DESC $select_limit";
} else {
	// สำหรับใช้ where เอาเฉพาะสัญญาใดๆ
	$where = "SELECT * FROM (SELECT * FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE  \"appvStatus\" <> '9'  
									ORDER BY \"appvStamp\" DESC $select_limit) a  WHERE
									( \"thcap_get_contractType\"(\"contractID\") = '$tab_id' OR subStr(\"contractID\" ,0,3)='$tab_id')
									ORDER BY \"doerStamp\" ASC";
}


?>

<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>เลขที่ voucher</th>
		<th>เลขที่สัญญา</th>
		<th>วันที่สัญญามีผล</th>
		<th>ชื่อผู้กู้/ผู้ซื้อหลัก</th>		
		<th>วันที่เวลาที่ใบสำคัญมีผล</th>		
		<th>จุดประสงค์</th>
		<th>จำนวนเงิน</th>
		<th>ผู้ทำรายการ</th>
		<th>เวลาที่ทำรายการ</th>
		<th>ผู้อนุมัติรายการ</th>
		<th>เวลาที่อนุมัติรายการ</th>
		<th>ผลการอนุมัติ</th>
	</tr>
<?php

	//แสดงรายการรออนุมัติทั้งหมด
	$qry_main = pg_query("$where");	
	$no=0;	
	while($res_main = pg_fetch_array($qry_main))
	{	
			$contractID = $res_main["contractID"];
			$fullname = $res_main["fullname"];
			$voucherDate = $res_main["voucherDate"];
			$voucherTime = $res_main["voucherTime"];
			$voucherPurpose = $res_main["voucherPurpose"];//จุดประสงค์
			$ChannelAmt = $res_main["ChannelAmt"];
			$doerID = $res_main["doerID"];
			$doerStamp = $res_main["doerStamp"];
			$appvID = $res_main["appvID"];
			$appvStamp = $res_main["appvStamp"];
			$appvStatus = $res_main["appvStatus"];
			$voucherID = $res_main["voucherID"];
			
			//ชื่อผู้ทำรายการ
			$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$doerID' ");
			$doerName = pg_fetch_result($qry_doername,0);
			
			
			//ชื่อผู้ทำรายการ
			$qry_appname = pg_query("select fullname from \"Vfuser\" where id_user = '$appvID' ");
			$appName = pg_fetch_result($qry_appname,0);
			
			if($ChannelAmt !=''){$ChannelAmt=number_format($ChannelAmt,2);}
			
			//วันที่สัญญามีผล
			$qry_conStartDate = pg_query("select \"conStartDate\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
			$conStartDate = pg_fetch_result($qry_conStartDate,0);	
			//จุดประสงค์
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
			$no+=1;
			//สถานะ
			if($appvStatus==1){
				$strappvstatus='อนุมัติ';
				if($no%2==0)
				{
					echo "<tr  bgcolor=\"#EEE9E9\" align=\"center\" height=25>";				
				}
				else
				{
					echo "<tr bgcolor=\"#FFFAFA\" align=\"center\" height=25>";				
				}				
			}else if($appvStatus==0){
				$strappvstatus='ไม่อนุมัติ';
				echo "<tr  bgcolor=\"#EE3B3B\" align=\"center\" height=25>";	
				
			}
			
			
			echo "<td align=\"center\">$no</td>";
			$voucherType=1;
			if($voucherType =='1'){
				echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
			}
			else if($voucherType =='2'){
				echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
			}
			else if($voucherType =='3'){
				echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
			}
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";	
			echo "<td align=\"center\">$conStartDate</td>";
			echo "<td align=\"left\">$fullname</td>";			
			echo "<td align=\"center\">$voucherDate $voucherTime</td>";			
			echo "<td align=\"left\">$purpose_name</td>";
			echo "<td align=\"right\">$ChannelAmt</td>";
			echo "<td align=\"center\">$doerName</td>";
			echo "<td align=\"center\">$doerStamp</td>";
			echo "<td align=\"center\">$appName</td>";
			echo "<td align=\"center\">$appvStamp</td>";
			echo "<td align=\"center\">$strappvstatus</td>";
			echo "</tr>";
	}
		if($no==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=13 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=13><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}
	
?>
</table>