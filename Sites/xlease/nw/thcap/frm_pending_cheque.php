<?php
include("../../config/config.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติรายการรับเช็คทั้งหมด</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<style type="text/css">
.sortable {
	color: #000000;
	cursor:pointer;
	text-decoration:underline;
}
</style>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script> 
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
<!--<script type="text/javascript" src="scripts/jquery.tableSort.js"></script>-->
</head>
<center><h1>ประวัติรายการรับเช็คทั้งหมด</h1></center>
<body>

<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
			<th>รายการที่</th>
			<th>ประเภท</th>	
			<th>เลขที่สัญญา</th>
			<th>เลขที่เช็ค</th>
			<th>วันที่สั่งเช็ค</th>
			<th>วันที่รับเช็ค</th>
			<th>ธนาคาร</th>	
			<th>สาขา</th>
			<th>จำนวนเงิน</th>
			<th>สถานะ</th>
		</tr>
		
		
		<?php
		//$query = pg_query("select * from finance.\"thcap_receive_cheque\" where \"revChqStatus\" in ('4','8') order by \"revChqID\" ");
		$query = pg_query("select a.* from  finance.\"thcap_receive_cheque\" a
			left join finance.\"thcap_receive_cheque_keeper\" b on a.\"revChqID\"=b.\"revChqID\"	
			where a.\"revChqStatus\" in ('4','8')  order by b.\"keeperStamp\" DESC ");
		$numrows = pg_num_rows($query);
		$no=0;
		while($pending= pg_fetch_array($query))
		{   
			$no+=1;
			$PostChq=$pending ["isPostChq"];
			$InsurChq= $pending["isInsurChq"];
			$resuType="";
			if($PostChq=='1'){$resuType="เช็คชำระล่วงหน้า"; }
			else if($InsurChq=='1'){$resuType="เช็คค้ำประกัน"; }
			else{$resuType="--";}
			$revChqToCCID=$pending["revChqToCCID" ];
			$bankChqNo=$pending["bankChqNo"];
			$bankChqDate=$pending["bankChqDate"];
			$revChqStatus=$pending["revChqStatus"];
			if($revChqStatus=='8'){$revChqStatus="<font color=\"green\">ได้รับเช็ค";}
			else if($revChqStatus=='4'){$revChqStatus="<font color=\"red\">ไม่ได้รับเช็ค";}
			
			$revChqDate=$pending["revChqDate" ];//รับเช็ค
			$bankOutID=$pending["bankOutID"];//id bank
			$qry_bank=pg_query("select \"bankName\" from \"BankProfile\" where \"bankID\"='$bankOutID'");
			$resu_bankname= pg_fetch_array($qry_bank);
			$bankname=$resu_bankname["bankName"];
			$bankOutBranch=$pending["bankOutBranch"];
			$bankChqAmt=$pending["bankChqAmt"];//
			//จำนวนเงินเป็น !=null ต้องใส่ number_format 
			if($bankChqAmt!=""){ $bankChqAmt=number_format($bankChqAmt,2);}
		if($no%2==0){
			echo "<tr bgcolor=\"#EEE9E9\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\">";
		}
		
		
		echo "<td align=\"center\">$no</td>";
		echo "<td align=\"center\">$resuType</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$revChqToCCID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$revChqToCCID</u></font></a></td>";	
		echo "<td align=\"center\">$bankChqNo</td>";
		echo "<td align=\"center\">$bankChqDate</td>";
		echo "<td align=\"center\">$revChqDate</td>";
		echo "<td align=\"center\">$bankname</td>";	
		echo "<td align=\"center\">$bankOutBranch</td>";
		echo "<td align=\"right\">$bankChqAmt</td>";
		echo "<td align=\"center\">$revChqStatus</td>";
		echo "</tr>";
		}
		if($numrows==0){
		echo "<tr bgcolor=\"#CDC5BF\" height=50><td colspan=\"14\" align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#CDC5BF\" height=30><td colspan=\"14\"><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}
	?>
	</table>
</body>

