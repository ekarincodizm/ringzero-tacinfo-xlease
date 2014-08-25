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
	$where = "AND \"thcap_get_contractType\"(\"contractID\") = '$tab_id' ";
}
?>

<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>วันที่สัญญามีผล</th>
		<th>ชื่อผู้กู้/ผู้ซื้อหลัก</th>
		<th>จำนวนเงินลงทุน</th>		
		<th>จำนวนเงินที่ชำระแล้ว</th>
		<th>จำนวนเงินคงเหลือที่ต้องชำระ</th>
		<th>เลขที่ voucher</th>
		<th>&nbsp; </th>
	</tr>
	<?php
	//แสดงรายการสัญญาทั้งหมด conStartDate เริ่ม ตั้งแต่ปี  2014 
	
	$qry_main = pg_query("SELECT \"contractID\",\"conStartDate\" FROM \"thcap_contract\" WHERE \"conStartDate\" >='2014-01-01' $where order by \"contractID\" asc");
	$no=0;	
	
	while($res_main = pg_fetch_array($qry_main))
	{	
			$auto_id="";
			$ChannelAmt="";
			$voucherID="";
			$contractID = $res_main["contractID"];
			$conStartDate = $res_main["conStartDate"];
			
			//ชื่อผู้กู้หลัก
			$qrythcap_NLname = pg_query("select \"thcap_NLname\",\"thcap_fullname\" from \"vthcap_ContactCus_detail\"
			where  \"contractID\" = '$contractID' and \"CusState\" = '0'");
			list($thcap_NLname,$thcap_fullname) = pg_fetch_array($qrythcap_NLname);		
			$f_name=$thcap_NLname;
			if($f_name==''){
				$f_name=$thcap_fullname;
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
			////ตรวจสอบว่ามีรายการการรออนุมัติหรือไม่
			$qry_wait = pg_query("SELECT \"auto_id\" FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE \"contractID\" ='$contractID' AND \"appvStatus\"='9' ");
			$numrows_wait = pg_num_rows($qry_wait);
			
			if($res_ChannelAmt !=0){
				$qry_detail_list = pg_query("SELECT \"auto_id\",\"ChannelAmt\",\"voucherID\"  FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE \"contractID\" ='$contractID' AND \"appvStatus\"='1' order by \"doerStamp\" desc limit(1)");
				list($auto_id,$ChannelAmt,$voucherID) = pg_fetch_array($qry_detail_list);
			}
						
			//ถ้ายอดลงทุน = ยอดที่ชำระแล้ว รายการนั้นจะไท่แสดง
			if($res_ChannelAmt==$res_amount){}
			else if($res_ChannelAmt < $res_amount){
			$net=number_format($net,2);
			if($res_amount !=''){$res_amount=number_format($res_amount,2);}
			$no+=1;
			if($no%2==0)
			{
				echo "<tr class=\"odd\" align=\"center\" height=25>";
				$color='odd';
			}
			else
			{
				echo "<tr class=\"even\" align=\"center\" height=25>";
				$color='even';
			}
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";	
			echo "<td align=\"center\">$conStartDate</td>";
			echo "<td align=\"left\">$f_name</td>";		
			echo "<td align=\"right\">$res_amount</td>";			
			echo "<td align=\"right\">".number_format($ChannelAmt,2)."</td>";
			echo "<td align=\"right\">$net</td>";
			echo "<td align=\"center\">$voucherID</td>";
			echo "<td align=\"center\"><img src=\"../thcap/images/add.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_add.php?contractID=$contractID&sendfrom_noconid=0','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1800,height=1000');\" style=\"cursor:pointer;\"></td>";
					
			echo "</tr>";
			if($res_ChannelAmt !=0){
				$qry_detail_list_1 = pg_query("SELECT \"auto_id\",\"ChannelAmt\",\"voucherID\"  FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE \"contractID\" ='$contractID' 
				AND \"appvStatus\"='1' AND \"auto_id\" <>'$auto_id'");
				$numrows_1 = pg_num_rows($qry_detail_list_1);
				if($numrows_1>0){
					while($res_detail = pg_fetch_array($qry_detail_list_1)){
						$auto_id = $res_detail["auto_id"];
						$ChannelAmt = $res_detail["ChannelAmt"];
						$voucherID = $res_detail["voucherID"];
						echo "<tr class=$color align=\"center\" height=25>";
						echo "<td align=\"right\" colspan=\"6\">".number_format($ChannelAmt,2)."</td>";
						echo "<td></td>";
						echo "<td align=\"center\" >$voucherID</td>";
						echo "<td ></td>";
						echo "</tr>";
					}//จบ while
				}//จบ มีรายการที่ อนุมัติแล้ว
				}
			}	
	}	
	?>	
</table>
