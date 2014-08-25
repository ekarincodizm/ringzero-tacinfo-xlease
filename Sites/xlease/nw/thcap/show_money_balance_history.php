<?php
include("../../config/config.php");

$contractID = $_GET['contractID']; //เลขที่สัญญา
$moneyType = $_GET['moneyType']; //ประเภทเงิน (เงินพัก/เงินค้ำ)

//ใช้ function คำนวณหาการใช้เงิน
$q = "SELECT thcap_get_money_balance('$contractID','$moneyType')";
$qr = pg_query($q);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แสดงประวัติ<?php echo $moneyType_name; ?></title>
<link href="act.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center">
	<fieldset style="width:1000px;">
    	<legend><b>ประวัติ<?php echo $moneyType_name; ?> :: เลขที่สัญญา <?php echo $contractID; ?></b></legend>
        <div style="width:960px; margin-top:15px;">
			<div align="left">
				<font style="background-color:#90ee90;">&nbsp;&nbsp;&nbsp;</font><font color="#888888"> * รายการ สีเขียว หมายถึง ใบเสร็จรับเงิน</font>
				<br>
				<font style="background-color:#FFAAAA;">&nbsp;&nbsp;&nbsp;</font><font color="#888888"> * รายการ สีชมพู หมายถึง Debit Note หรือ Credit Note</font>
			</div>
        	<table border="0" cellpadding="5" cellspacing="1" width="100%">
            	<tr bgcolor="#79BCFF">
					<th>วันที่ได้รับเงิน</th>
					<th>วันที่ใบเสร็จ</th>
					<th>จำนวนเงิน</th>
					<th>คงเหลือ</th>
					<th>เลขที่ใบเสร็จ</th>
                </tr>
                <?php
				if($qr)
				{	
					$i = 0;
					list($result) = pg_fetch_array($qr);
					//หากข้อมูลที่ได้จาก function เป็น f แสดงว่าไม่มีการใช้เงินพัก/เงินค้ำในสัญญานี้
					IF($result != 'f'){ 
						// ตัด @ ออกเพื่อแบ่งแถวข้อมูล
						$databeforesort = explode('@',$result);
						//วนแสดงข้อมูลตามจำนวนแถวข้อมูล
						for($count = 0;$count < sizeof($databeforesort);$count++){
							// แบ่งข้อมูลออกจากกันโดยการตัดเครื่องหมาย # จะได้ วันที่บนใบเสร็จ,จำนวนเงิน,คงเหลือ,รหัสใบเสร็จ ตามลำดับ
							list($receiveDate,$moneyAmt,$money_balance,$receiptID,$doctype,$receiptDate) = explode('#',$databeforesort[$count]);
							
							// กำหนดสีของรายการ และ popup ที่จะลิ้งเมื่อคลิกที่เลขที่เอกสารที่ต้องการ
							if($doctype == "1")
							{ // ถ้าเป็น ใบเสร็จรับเงิน
								$myStyle = "style=\"font-size:11px; background-color:#90ee90;\"";
								$myOnClick = "<a onclick=\"javascript:popU('Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><u>$receiptID</u></a>";
							}
							elseif($doctype == "2")
							{ // ถ้าเป็น Debit Note หรือ Credit Note
								$myStyle = "style=\"font-size:11px; background-color:#FFAAAA;\"";
								$myOnClick = "<a onclick=\"javascript:popU('../thcap_dncn/popup_app.php?idapp=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><u>$receiptID</u></a>";
							}
							else
							{
								$myStyle = "";
								$myOnClick = "";
							}
						
							echo "<tr $myStyle>";
							echo "
								<td align=\"center\">$receiveDate</td>
								<td align=\"center\">$receiptDate</td>
								<td align=\"right\">".number_format($moneyAmt,2)."</td>
								<td align=\"right\">".number_format($money_balance,2)."</td>
								<td align=\"center\">$myOnClick</td>
								</tr>
							";
							$i++;
						}
					}			
				}
				?>
            </table>
        </div>
    </fieldset>
</div>
</body>
</html>