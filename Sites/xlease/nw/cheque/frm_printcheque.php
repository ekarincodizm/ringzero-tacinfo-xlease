<?php
include("../../config/config.php");

$currentdate=nowDate();

if($datepicker==""){
	$datepicker=$currentdate;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>พิมพ์เช็คจ่าย</title>
<script type="text/javascript">
$(document).ready(function(){
		
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	
	$("#btn1").click(function(){
		$("#type_detail").load("process_cheque.php?method=sentprint&datepicker="+$("#datepicker").val());
	});
});

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<div style="text-align:center"><h2>พิมพ์เช็คจ่าย</h2></div>       
		<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
		<div style="clear:both;"></div>
		<fieldset><legend><B>ค้นหาเช็ค</B></legend>
			<table align="center">
			<tr height="30">
				
				<td id="conday">&nbsp;
					<label><b>วันที่ทำรายการ</b></label>
					<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">&nbsp;<input type="button" id="btn1" value="ค้นหา"/>
				</td>
			</tr>			
			</table>
			<span id="type_detail" style="padding:10px;">
				<?php
				//qry ข้อมูล ณ วันที่ปัจจุบัน
				$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"IDNO\",\"cusPay\",\"moneyPay\",\"datePay\",c.\"BAccount\",
				c.\"BName\",\"chequeNum\",c.\"BCompany\",a.\"typeChq\",a.\"note\",d.\"fullname\",\"keyStamp\",\"statusPay\" from cheque_pay a
				left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
				left join \"BankInt\" c on a.\"BAccount\"=c.\"BAccount\"
				left join \"Vfuser\" d on a.\"keyUser\"=d.\"id_user\"
				where a.\"appStatus\"='1' and date(\"keyStamp\")='$datepicker' and \"appStatus\"='1' and \"statusPay\"='TRUE' order by \"keyStamp\",a.\"typePay\"");
				
				$num_rows=pg_num_rows($qrychq);
				echo "
					<table width=\"950\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" bgcolor=\"#CECECE\">
					<tr style=\"font-weight:bold;color:#FFFFFF\" valign=\"top\" bgcolor=\"#026F38\" align=\"center\">
						<th>ประเภท<br>การสั่งจ่าย</th>
						<th>เช็คเลขที่</th>
						<th>เลขที่บัญชี</th>
						<th width=80>เลขที่สัญญา</th>
						<th width=120>สั่งจ่าย</th>
						<th>ประเภทเช็ค</th>
						<th>จำนวนเงิน</th>
						<th>วันที่สั่งจ่าย</th>
						<th>ดูเพิ่มเติม</th>
						<th>พิมพ์</th>
					</tr>
				";
				$i=0;
				$sum=0;
				while($reschq=pg_fetch_array($qrychq)){
					list($chqpayID,$typeName,$IDNO,$cusPay,$moneyPay,$datePay,$BAccount,$BName,$chequeNum,$BCompany,$typeChq,$note,$keyuser,$keyStamp,$statusPay,$typePay)=$reschq;
					if($IDNO=="") $IDNO="-";
					if($BName=="") $BName="-";
					if($BCompany=="")$BCompany="-";
								
					if($typeChq=="1"){
						$typeChqname="ปกติ";
					}else if($typeChq=="2"){
						$typeChqname="A/C PAYEE ONLY";
					}else{
						$typeChqname="&Co.";	
					}
								
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=#D6FEEA align=\"center\">";
					}else{
						echo "<tr bgcolor=#FFFFFF align=\"center\">";
					}
								
					echo "
						<td>$typeName</td>
						<td>$chequeNum</td>
						<td>$BAccount</td>
						<td>$IDNO</td>
						<td  align=left>$cusPay</td>
						<td>$typeChqname</td>
						<td align=right>".number_format($moneyPay,2)."</td>
						<td align=center>$datePay</td>
						<td>
							<img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('showdetail.php?chqpayID=$chqpayID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')\" style=\"cursor: pointer;\">
						</td>
						<td align=center><a href=\"pdf_printcheque.php?chqpayID=$chqpayID\" target=\"_blank\"><img src=\"images/icoPrint.png\" width=17 height=14 title=\"พิมพ์รายงาน\"></a></td>
						</tr>
					";
					$sum=$sum+$moneyPay;
				}
				if($num_rows=="0"){
						echo "<tr><td colspan=11 bgcolor=\"#FFFFFF\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
				}else{
					echo "<tr align=right bgcolor=\"#A0FCEA\"><td colspan=6>รวมเงิน</td><td>".number_format($sum,2)."</td><td colspan=4></td></tr>";
				}
				echo "</table>";
				?>
			</span>	
		</fieldset>
	</td>
</tr>
</table>
</body>
</html>