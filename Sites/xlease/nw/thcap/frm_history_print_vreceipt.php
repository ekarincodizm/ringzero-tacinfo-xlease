<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) พิมพ์ใบกำกับภาษี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>	
</head>
<body>

<div style="padding-top:50px;"></div>
<center>
<fieldset style="padding:15px;width:95%;" >
	<legend><font color="black"><b><h2>ประวัติการพิมพ์ใบกำกับภาษี</h2></b></font></legend>
    <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center">
    	<tr style="background-color:#CCCCCC;">
			<th align="center">รายการที่</th>
        	<th align="center">เลขที่สัญญา</th>
            <th align="center">ผู้กู้หลัก</th>
            <th align="center">เลขที่ใบกำกับภาษี</th>
            <th align="center">วันที่จ่าย</th>
            <th align="center">จำนวนเงินที่จ่าย</th>
            <th align="center">ผู้พิมพ์</th>
			<th align="center">วันที่/เวลาที่พิมพ์</th>
			<th align="center">เหตุผลที่ขอพิมพ์</th>
        </tr>
        <?php
				$i = 0;
					//ค้นหาเลขที่สัญญา
					$qry_con=pg_query("	SELECT a.\"id\",b.\"contractID\" as contract,b.\"taxinvoiceID\",b.\"taxpointDate\",a.\"reprint_datetime\",a.\"reprint_reason\",c.\"fullname\" as \"fullnameuserprint\"
										FROM \"thcap_reprint_log\" a 
										LEFT JOIN \"thcap_v_taxinvoice_details\" b ON a.\"receipt_id\" = b.\"taxinvoiceID\"
										LEFT JOIN \"Vfuser\" c ON a.\"reprint_user\" = c.\"id_user\"
										WHERE a.\"receipt_id\" IN (select \"taxinvoiceID\" from \"thcap_v_taxinvoice_details\")
										GROUP BY a.\"id\",b.\"contractID\",b.\"taxinvoiceID\",b.\"taxpointDate\",a.\"reprint_datetime\",a.\"reprint_reason\",\"fullnameuserprint\"
										ORDER BY a.\"reprint_datetime\" DESC");
					
					
					$numcon=pg_num_rows($qry_con);
					
					if($numcon>0){ //แสดงว่ามีข้อมูล
						$status=1;
					}else{
						$status=0;
					}
				
				
				if($status==1){
					while($result=pg_fetch_array($qry_con)){	
						$logid=trim($result["id"]); //รหัสการขอพิมพ์ใบกำกับ
						$contractID=trim($result["contract"]); //เลขที่สัญญา
						$taxinvoiceID=trim($result["taxinvoiceID"]); //รหัสใบกำกับภาษี
						$taxpointDate=trim($result["taxpointDate"]); //วันที่บนใบกำกับ
						$fullnameuserprint=trim($result["fullnameuserprint"]); //ผู้ขอพิมพ์
						$reprint_datetime=trim($result["reprint_datetime"]); //วันที่ขอพิมพ์
						$reprint_reason=trim($result["reprint_reason"]); //เหตุผลการพิมพ์
						$i++;
						
						//หาชื่อลูกค้าที่เป้นผู้กู้หลัก
						$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = 0");
						list($full_namecus) = pg_fetch_array($qry_cusname);
						
						//หาจำนวนเงิน
						$qry_con1=pg_query("select sum(\"debtAmt\") as \"debtAmt1\" from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$taxinvoiceID' ");
						$result2=pg_fetch_array($qry_con1);	
						$sum_debtAmt = $result2["debtAmt1"];
						
						
						if($i%2==0){
							echo "<tr bgcolor=\"#EEEEEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=\"center\">";
						}else{
							echo "<tr bgcolor=\"#DDDDDD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\" align=\"center\">";
						}
				
						
						echo "
						<td>$i</td>
						<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
						<u>$contractID</u></font></span></td>
						<td align=\"left\">$full_namecus</td>
						<td>$taxinvoiceID</td>
						<td>$taxpointDate</td>
						<td align=right>".number_format($sum_debtAmt,2)."</td>
						<td align=\"left\">$fullnameuserprint</td>
						<td>$reprint_datetime</td>
						<td><img src=\"images/detail.gif\" onclick=\"popU('frm_note_reprint.php?logid=".$logid."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350')\" style=\"cursor:pointer;\"></td>
						";		
						echo "</tr>";
					}	
					
				}else{
					echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=9><h2>-ไม่พบข้อมูลการพิมพ์กำกับภาษี-</h2></td></tr>";
				}
		?>
    </table>
</fieldset>
</center>


</body>
</html>	