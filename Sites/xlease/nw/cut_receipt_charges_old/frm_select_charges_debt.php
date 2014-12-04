<?php
include("../../config/config.php");

$O_RECEIPT = pg_escape_string($_GET['id']); // เลขที่ใบเสร็จ

// หาข้อมูลใบเสร็จ
$qryDetail = pg_query("
						SELECT
							\"IDNO\",
							\"O_DATE\",
							\"O_MONEY\",
							\"O_Type\",
							\"PayType\"
						FROM
							\"FOtherpay\"
						WHERE
							\"O_RECEIPT\" = '$O_RECEIPT'
					");
$IDNO = pg_fetch_result($qryDetail,0); // เลขที่สัญญา
$O_DATE = pg_fetch_result($qryDetail,1); // วันที่ชำระ
$O_MONEY = pg_fetch_result($qryDetail,2); // จำนวนเงิน
$O_Type = pg_fetch_result($qryDetail,3); // รหัสค่าใช้จ่าย
$PayType = pg_fetch_result($qryDetail,4);

// หาชื่อค่าใช้จ่าย
$query_name=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$O_Type'");
if($res_name=pg_fetch_array($query_name)){
	$TName = $res_name['TName'];
}
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

	<script>
		function popU(U,N,T)
		{
			newWindow = window.open(U, N, T);
		}
		
		function validate()
		{
			var countList = document.getElementById("countList").value; // จำนวนใบเสร็จที่มีให้เลือก
			var haveSelect = false; // เลือกใบเสร็จแล้วหรือยัง
			
			for(var i=1; i<=countList; i++)
			{
				if(document.getElementById("chk"+i).checked)
				{
					haveSelect = true;
				}
			}
			
			if(haveSelect == false)
			{
				alert('กรุณาเลือก ค่าใช้จ่าย');
				return false;
			}
			else
			{
				return true;
			}
		}
	</script>
</head>
<body>

<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>ตัดใบเสร็จค่าใช้จ่ายเก่า</B></legend>
				<div align="center">
					<div class="ui-widget">
						<div style="text-align:left; font-size:15px; font-weight:bold; color:#585858">
							เลขที่สัญญา : <span onClick="popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=650')" style="cursor:pointer;" title="ดูตารางการชำระ"><font color="blue"><u><?php echo $IDNO;?></u></font></span>
							<br/>
							เลขที่ใบเสร็จ : <?php echo $O_RECEIPT; ?>
							<br/>
							วันที่ชำระ : <?php echo $O_DATE; ?>
							<br/>
							ค่าใช้จ่าย : <?php echo $TName; ?>
							<br/>
							จำนวนเงิน : <?php echo number_format($O_MONEY,2); ?>
						</div>
						<br/>
						<form name="frm1" id="frm1" action="old_receipt_otherpay_update.php" method="post">
							<input type="hidden" name="O_RECEIPT" value="<?php echo $O_RECEIPT; ?>">
							<input type="hidden" name="idno" value="<?php echo $IDNO; ?>">
							<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
								<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
									<td>เลือก</td>
									<td>รหัสค่าใช้จ่าย</td>
									<td>เลขที่สัญญา</td>
									<td>วันที่ครบกำหนด / วันที่ทำรายการ</td>
									<td>วันที่นัดลูกค้า</td>
									<td>ค่าใช้จ่าย</td>
									<td>จำนวนเงิน</td>
									<td>ยอดคงเหลือ ที่ยังไม่มีใบเสร็จ</td>
								</tr>
								
								<?php
								$query=pg_query("
													SELECT
														\"IDCarTax\",
														\"IDNO\",
														\"TaxDueDate\",
														\"ApointmentDate\",
														\"CusAmt\",
														\"TypeDep\",
														\"CusAmt\" - (select case when sum(\"O_MONEY\") is not null then sum(\"O_MONEY\") else 0.00 end from \"FOtherpay\" where \"RefAnyID\" = \"CarTaxDue\".\"IDCarTax\" and \"Cancel\" = false) AS \"Balance\"
													FROM
														carregis.\"CarTaxDue\"
													WHERE
														\"cuspaid\" = FALSE AND
														\"IDNO\" = '$IDNO' AND
														\"CusAmt\" > '0.00'
													ORDER BY
														\"TaxDueDate\"
												");
								$i = 0;
								while($resvc=pg_fetch_array($query))
								{
									$i++;
									$IDCarTax = $resvc['IDCarTax'];
									$IDNO = $resvc['IDNO'];
									$TaxDueDate = $resvc['TaxDueDate']; // วันที่ครบกำหนด/วันที่ทำรายการ
									$ApointmentDate = $resvc['ApointmentDate']; // วันที่นัดลูกค้า
									$CusAmt = $resvc['CusAmt'];
									$TypeDep = $resvc['TypeDep'];
									$Balance = $resvc['Balance'];
									
									$query_name=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypeDep'");
									$TName = pg_fetch_result($query_name,0);
									
									//ตรวจสอบว่าอยู่ระหว่างการขออนุมัติหรือไม่
									$qry_wait_app=pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue_reserve\" WHERE \"IDCarTax\" = '$IDCarTax' AND \"Approved\"='9' ");
									$nub_wait_app = pg_num_rows($qry_wait_app);
									if($nub_wait_app > 0)
									{
										$canSelect = "title=\"อยู่ระหว่างรอการอนุมัติยกเลิก\" disabled";
									}
									else
									{
										$canSelect = "";
									}
									
									if($i%2==0){
										echo "<tr class=\"odd\" align=\"left\">";
									}else{
										echo "<tr class=\"even\" align=\"left\">";
									}
								?>
									<td align="center"><input type="radio" name="chk" id="chk<?php echo $i; ?>" value="<?php echo $IDCarTax; ?>" <?php echo $canSelect; ?> ></td>
									<td align="center"><?php echo $IDCarTax; ?></td>
									<td align="center"><?php echo $IDNO; ?></td>
									<td align="center"><?php echo $TaxDueDate; ?></td>
									<td align="center"><?php echo $ApointmentDate; ?></td>
									<td align="center"><?php echo $TName; ?></td>
									<td align="right"><?php echo number_format($CusAmt,2); ?></td>
									<td align="right"><?php echo number_format($Balance,2); ?></td>
								<?php
									echo "</tr>";
								}
								?>
							</table>
							<input type="hidden" name="countList" id="countList" value="<?php echo $i; ?>" />

							<div align="center" style="padding-top:10px; padding-bottom:10px;">
								<input type="submit" name="btn1" id="btn1" value=" บันทึก " class="ui-button" onClick="return validate();"/>
							</div>
						</form>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>

</body>
</html>