<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function checkpay(){
	$("#detail").load("show_checkpay.php?con="+$("#condition").val());
}
function selectAll(select){
    with (document.frm1)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
</script>

</head>
<body>
<form method="post" name="frm1" action="process_cheque.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			
				<fieldset><legend><B>เงื่อนไขการค้นหา</B></legend>
					<div style="padding:20px;">
						<table width="400" border="0"  align="center">
						<tr>
							<td width="100">
								เงื่อนไขการแสดงรายการ
								<select id="condition" onchange="javascript:checkpay()">
									<option value="1">เฉพาะเช็คที่ยังไม่เบิก</option>
									<option value="2">เฉพาะเช็คที่เบิกแล้ว</option>
									<option value="3">แสดงทั้งหมด</option>
								</select>
							</td>	
						</tr>
						</table>
					</div>
				</fieldset>

			<div id="detail">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<td width="100">เช็คเลขที่</td>
					<td width="100">ประเภทการสั่งจ่าย</td>
					<td width="180">สั่งจ่าย</td>
					<td width="80">จำนวนเงิน</td>
					<td width="60">วันที่สั่งจ่าย</td>
					<td width="80">รายละเอียด</td>
					<td width="60">เบิกแล้ว<br><a href="#" onclick="javascript:selectAll('check');"><u>ทั้งหมด</u></a></td>
				</tr>
				<?php
				$summoney=0;
				$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"chequeNum\",\"cusPay\",\"moneyPay\",\"datePay\" from cheque_pay a
					left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
					where \"appStatus\"='1' and \"statusPay\"='TRUE' and \"takeCheque\"='1' order by \"chequeNum\"");
				$nub=pg_num_rows($qrychq);
				while($reschq=pg_fetch_array($qrychq)){
					list($chqpayID,$typeName,$chequeNum,$cusPay,$moneyPay,$datePay)=$reschq;
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
				?>
					<td><?php echo $chequeNum; ?></td>
					<td><?php echo $typeName; ?></td>
					<td align="left"><?php echo $cusPay; ?></td>
					<td align="right"><?php echo number_format($moneyPay,2); ?></td>
					<td><?php echo $datePay; ?></td>
					<td>
						<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('showdetail.php?chqpayID=<?php echo $chqpayID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')" style="cursor: pointer;">
					</td>
					<td><input type="checkbox" name="check[]" value="<?php echo $chqpayID;?>"><input type="hidden" name="method" value="checkpay"></td>		
				</tr>
				<?php
					$summoney+=$moneyPay;
				} //end while
				if($nub == 0){
					echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
				}else{
					echo "<tr bgcolor=\"#F0F0F0\"><td colspan=\"3\" align=\"right\"><b>รวมเงิน</b></td><td align=right>".number_format($summoney,2)."</td><td colspan=\"3\"></td></tr>";
					echo "<tr bgcolor=#FFFFFF height=50 align=center><td colspan=7><input type=\"submit\" value=\" บันทึก \"><input type=\"button\" value=\"   ปิด   \" onclick=\"window.close();\"></td></tr>";
				}
				?>
				</table>
			</div>
		</div>
	</td>
</tr>
</table>
</form>
</body>
</html>