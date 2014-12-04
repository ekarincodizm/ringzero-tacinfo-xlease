<?php
set_time_limit(0);
include("../../config/config.php");

$receiptTypeID = pg_escape_string($_GET["receiptTypeID"]);
$receiptTypeName = pg_escape_string($_GET["receiptTypeName"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ตัดใบเสร็จค่าใช้จ่ายเก่า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

	<style type="text/css">
		.odd{
			background-color:#EDF8FE;
			font-size:12px
		}
		.even{
			background-color:#D5EFFD;
			font-size:12px
		}
	</style>
	
	<script>
		function popU(U,N,T)
		{
			newWindow = window.open(U, N, T);
		}
	</script>

</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<div style="clear:both;"></div>

			<fieldset><legend><B>ตัดใบเสร็จค่าใช้จ่ายเก่า - <?php echo $receiptTypeName; ?></B></legend>
				<div align="center">
					<div class="ui-widget">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<td>เลขที่สัญญา</td>
							<td>วันที่ชำระ</td>
							<td>เลขที่ใบเสร็จ</td>
							<td>PayType</td>
							<td>จำนวนเงิน</td>
							<td>&nbsp;</td>
						</tr>
						<?php
						$query=pg_query("
											SELECT
												\"IDNO\",
												\"O_DATE\",
												\"O_RECEIPT\",
												\"O_MONEY\",
												\"PayType\"
											FROM
												\"FOtherpay\"
											WHERE
												\"RefAnyID\" IS NULL AND
												\"Cancel\" = FALSE AND
												\"O_Type\" = '$receiptTypeID' AND
												\"O_DATE\" >= '2013-01-01'
											ORDER BY
												\"IDNO\",
												\"O_DATE\"
										");
						$i = 0;
						while($resvc=pg_fetch_array($query))
						{
							$i++;
							
							$IDNO = $resvc['IDNO']; // เลขที่สัญญา
							$O_DATE = $resvc['O_DATE']; // วันที่ชำระ
							$O_RECEIPT = $resvc['O_RECEIPT']; // เลขที่ใบเสร็จ
							$O_MONEY = $resvc['O_MONEY']; // จำนวนเงิน
							$PayType = $resvc['PayType'];

							if($i%2==0){
								echo "<tr class=\"odd\" align=\"left\">";
							}else{
								echo "<tr class=\"even\" align=\"left\">";
							}
						?>
							<td align="center"><span onClick="popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=650')" style="cursor:pointer;" title="ดูตารางการชำระ"><font color="blue"><u><?php echo $IDNO;?></u></font></span></td>
							<td align="center"><?php echo $O_DATE; ?></td>
							<td align="center"><?php echo $O_RECEIPT; ?></td>
							<td align="center"><?php echo $PayType; ?></td>
							<td align="right"><?php echo number_format($O_MONEY,2); ?></td>
							<td align="center"><input type="button" style="cursor:pointer;" name="btn1" value="ทำรายการนี้" onClick="popU('frm_select_charges_debt.php?id=<?php echo $O_RECEIPT; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')"></td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td align="left" colspan="6">รวมทั้งสิ้น <b><?php echo number_format($i,0); ?></b> รายการ</td>
						</tr>
						</table>
					</div>
				</div>
			</fieldset>
		</td>
	</tr>
</table>

</body>
</html>