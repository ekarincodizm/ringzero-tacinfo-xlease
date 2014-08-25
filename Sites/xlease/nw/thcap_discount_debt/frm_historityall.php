<?php
include("../../config/config.php");
$sort = pg_escape_string($_GET["descOrascby"]);
$orderby = pg_escape_string($_GET["orderby"]);
$cur_path_ins = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');
if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "b.\"doerStamp\" ";
}
$query = pg_query("select a.\"dcNoteID\" ,a.\"contractID\",c.\"typePayID\",c.\"typePayRefValue\",c.\"typePayRefDate\",c.\"typePayAmt\",c.\"doerID\"
		,c.\"doerStamp\" ,a.\"dcNoteDate\",b.\"doerID\",b.\"doerStamp\",a.\"dcNoteAmtNET\",a.\"dcNoteAmtVAT\",a.\"dcNoteAmtALL\",d.\"dcNoteStatus\"  
		from public.\"vthcap_otherpay_debt_current\"  c
		left join \"account\".\"thcap_dncn\" a on  a.\"debtID\"=c.\"debtID\"
		left join \"account\".\"thcap_dncn_details\" b on a.\"dcNoteID\"=b.\"dcNoteID\"
		left join \"account\".\"thcap_dncn_discount\" d on a.\"dcNoteID\"=d.\"dcNoteID\"
		where  b.\"doerStamp\" is not null and d.\"dcNoteStatus\" not in('8','9') AND d.\"dcType\" = '2'
		order by  $orderby $sort ");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ขอส่วนลด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>


<body>
	<center><h1>ประวัติการการขอส่วนลดทั้งหมด</h1></center>
<br>
<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<table align="center" bgcolor="#EEE5DE" frame="box" width="99%" >					
		<tr bgcolor="#CDC5BF">
		<th>รายการที่</th>		
		<th><a href='frm_historityall.php?orderby=<?php echo "a.\"contractID\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลขที่สัญญา</u></th>
        <th>รายการ</th>
		<th>ค่าอ้างอิงของค่าใช้จ่าย</th>
		<th><a href='frm_historityall.php?orderby=<?php echo "c.\"typePayRefDate\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ตั้งหนี้</u></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "c.\"typePayAmt\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>จำนวนหนี้ค้างจ่าย</u></th>	
		<th><a href='frm_historityall.php?orderby=<?php echo "a.\"dcNoteAmtNET\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>จำนวนเงินที่ขอส่วนลด</u></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "a.\"dcNoteAmtVAT\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>จำนวน VAT</u></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "a.\"dcNoteAmtALL\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>จำนวนเงิน</u></th>	
		<th><a href='frm_historityall.php?orderby=<?php echo "a.\"dcNoteDate\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ส่วนลดมีผล</u></th>
		<th>ผู้ทำการขอส่วนลด</th>
		<th><a href='frm_historityall.php?orderby=<?php echo "b.\"doerStamp\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันเวลาที่ขอส่วนลด</u></th>
		<th>เหตุผล</th>
		<th>สถานะการอนุมัติ</th>
		</tr>
		<?php
		
		$numrows = pg_num_rows($query);
		$i=0;
		while($result = pg_fetch_array($query))
		{
			$i++;
			$dcNoteID=trim($result["dcNoteID"]);
			$typePayID=trim($result["typePayID"]); // รหัสประเภทค่าใช้จ่าย	
			$contractID=trim($result["contractID"]);
			$typePayRefValue=trim($result["typePayRefValue"]); 
			$typePayRefDate=trim($result["typePayRefDate"]); 
			$typePayAmt=trim($result["typePayAmt"]);		
			$dcNoteDate=trim($result["dcNoteDate"]); 
			$doerID=trim($result["doerID"]); 
			$doerStamp=trim($result["doerStamp"]); 
			$dcNoteAmtNET=trim($result["dcNoteAmtNET"]); 
			$dcNoteAmtVAT=trim($result["dcNoteAmtVAT"]); 
			$dcNoteAmtALL=trim($result["dcNoteAmtALL"]); 			
			$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerID' ");
			$nameuser = pg_fetch_array($query_fullname);
			$fullname=$nameuser["fullname"];
			
			$qry_type=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			
			$due = ""; // กำหนดวันดิวเป็นค่าว่าง เพื่อไม่ให้เก็บค่าเก่ามาใช้
							
			if($typePayID == "1003")
			{
				//-----------------ตัดส่วนเกินออก
				$search = strpos($typePayRefValue,"-");
				if($search)
				{
					$subtypePayRefValue = explode("-", $typePayRefValue);
					$typePayRefValue = $subtypePayRefValue[0];
				}
				//-----------------จบการตัดส่วนเกินออก
				$qry_due=pg_query("select \"ptDate\" from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
				while($res_due=pg_fetch_array($qry_due))
				{
					$ptDate=trim($res_due["ptDate"]); // วันดิว
					$due = "($ptDate)";
				}
			}
			else
			{
				$due = "";
			}
			//สถานะการอนุมัติ
			IF($result["dcNoteStatus"] == '0'){
				$status = 'ไม่อนุมัติ';
			}else IF($result["dcNoteStatus"] == '1'){
				$status = 'อนุมัติ';
			}else IF($result["dcNoteStatus"] == '2'){
				$status = 'ยกเลิก';
			}else{
				$status = 'ไม่ระบุสถานะ';
			}
			if($i%2==0){
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}else{
				echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
			echo "<td align=\"center\">$i</td>";		
			echo "<td align=\"center\"><a onclick=\"javascript:popU('$cur_path_ins/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";
			echo "<td align=\"center\">$tpDesc</td>";		
			echo "<td align=\"center\">$typePayRefValue $due</td>";
			echo "<td align=\"center\">$typePayRefDate</td>";
			echo "<td align=right>".number_format($typePayAmt,2)."</td>";	
			echo "<td align=right>".number_format($dcNoteAmtNET,2)."</td>";	
			echo "<td align=right>".number_format($dcNoteAmtVAT,2)."</td>";	
			echo "<td align=right>".number_format($dcNoteAmtALL,2)."</td>";			
			echo "<td align=\"center\">$dcNoteDate</td>";		
			echo "<td align=\"center\">$fullname</td>";
			echo "<td align=\"center\">$doerStamp</td>";		
			echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_reason.php?dcNoteID=$dcNoteID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
			echo "<td align=\"center\">$status</td>";
			echo "</tr>";
			}
		if($numrows==0){
			echo "<tr bgcolor=\"#CDC5BF\" height=50><td colspan=\"14\" align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC5BF\" height=30><td colspan=\"14\"><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
		}?>		
		</table>
	</table>
</fieldset>
</body>
</html>