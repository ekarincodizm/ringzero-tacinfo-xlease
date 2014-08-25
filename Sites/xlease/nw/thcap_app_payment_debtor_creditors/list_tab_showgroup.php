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

if($tab_id == '0'){
	// เอาทุกสัญญา where เลยไม่มี
	$where = "";
} else {
	// สำหรับใช้ where เอาเฉพาะสัญญาใดๆ
	$where = "AND( \"thcap_get_contractType\"(\"contractID\") = '$tab_id' OR subStr(\"contractID\" ,0,3)='$tab_id')";
}
?>

<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>วันที่สัญญามีผล</th>
		<th>ชื่อผู้กู้/ผู้ซื้อหลัก</th>
		<th>วันที่เวลาที่ใบสำคัญมีผล</th>
		<th>จุดประสงค์</th>
		<th>จำนวนเงินคงเหลือที่ต้องชำระ</th>
		<th>จำนวนเงิน</th>
		<th>ผู้ทำรายการ</th>
		<th>เวลาที่ทำรายการ</th>
		<th>&nbsp;</th>
	</tr>
	<?php
	//แสดงรายการรออนุมัติทั้งหมด
	$qry_main = pg_query("SELECT * FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE  \"appvStatus\"='9'  $where  ORDER BY \"doerStamp\" ASC");
	$no=0;	
	
	while($res_main = pg_fetch_array($qry_main))
	{	
			$auto_id = $res_main["auto_id"];
			$contractID = $res_main["contractID"];
			$fullname = $res_main["fullname"];
			$voucherDate = $res_main["voucherDate"];
			$voucherTime = $res_main["voucherTime"];
			$voucherPurpose = $res_main["voucherPurpose"];//จุดประสงค์
			$ChannelAmt = $res_main["ChannelAmt"];
			$doerID = $res_main["doerID"];
			$doerStamp = $res_main["doerStamp"];
			//$contractID = $res_main["contractID"];
			
			//วันที่สัญญามีผล
			$qry_conStartDate = pg_query("select \"conStartDate\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
			$conStartDate = pg_fetch_result($qry_conStartDate,0);			
			
			if($ChannelAmt !=''){$ChannelAmt=number_format($ChannelAmt,2);}
			//จุดประสงค์
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
			// หาจำนวนเงินลงทุน โดยใส่ parameter type = 3 (เงินลงทุนรวมภาษีมูลค่าเพิ่ม (ถ้ามี) ก่อนหักเงินดาวน์ (ถ้ามี))
			$qry = pg_query("SELECT \"thcap_get_iniinvestmentamt\"('$contractID','3')");
			list($res_amount) = pg_fetch_array($qry);			
			
			//จำนวนเงินที่ต้องชำระไปแล้ว
			$res_ChannelAmt=0;
			$qry_paid = pg_query("SELECT \"thcap_get_all_payment_paid_for_contract\"('$contractID')");
			list($res_ChannelAmt) = pg_fetch_array($qry_paid);			
			
			//จำนวนเงินคงเหลือที่ต้องชำระ
			$net=$res_amount-$res_ChannelAmt;
			$net=number_format($net,2);
			
			//ชื่อผู้ทำรายการ
			$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$doerID' ");
			$doerName = pg_fetch_result($qry_doername,0);
			
			$no+=1;
			if($no%2==0)
			{
				echo "<tr class=\"odd\" align=\"center\" height=25>";				
			}
			else
			{
				echo "<tr class=\"even\" align=\"center\" height=25>";				
			}
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";	
			echo "<td align=\"center\">$conStartDate</td>";
			echo "<td align=\"left\">$fullname</td>";
			echo "<td align=\"center\">$voucherDate $voucherTime</td>";
			echo "<td align=\"left\">$purpose_name</td>";
			echo "<td align=\"right\">$net</td>";
			echo "<td align=\"right\">$ChannelAmt</td>";
			echo "<td align=\"center\">$doerName</td>";
			echo "<td align=\"center\">$doerStamp</td>";			
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_approved.php?auto_id=$auto_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=900')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ทำรายการ</u></font></a></td>";	
			echo "</tr>";
	}
	if($no==0){
		echo "<tr class=\"odd\"><td align=\"center\" colspan=\"10\"><h2> ไม่พบรายการ  </h2></td></tr>"; 	
	}
	?>
</table>