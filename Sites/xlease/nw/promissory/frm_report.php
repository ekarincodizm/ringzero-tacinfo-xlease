<?php
include("../../config/config.php");

$val=$_POST["val"];
$month=$_POST["month"];
$year=$_POST["year"];
$puruser=$_POST["puruser"];
$currentdate=nowDate();
if($year==""){
	$year=substr($currentdate,0,4);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$("#submitButton").click(function(){
		$("#submitButton").attr('disabled', true);
		$.post("process_promissory.php",{
			cmd : "checklock",
			month : '<?php echo $month;?>',
			year : '<?php echo $year;?>',
			puruser : '<?php echo $puruser;?>'
		},
		function(data){
			if(data=="1"){
				popU('pdf_report.php?month=<?php echo $month;?>&year=<?php echo $year;?>&puruser=<?php echo $puruser;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');
				$("#submitButton").attr('disabled', false);
			}else{
				alert(data);
				$("#submitButton").attr('disabled', false);
			}
		});
	});
});
function check_num(evt) {
	//ให้ใส่จุดได้  ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
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
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>รายงานตั๋วสัญญาใช้เงิน</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานตั๋วสัญญาใช้เงิน</B></legend>
				<div align="center">
					<div class="ui-widget">
					<form method="post" name="form1" action="#">
						<p align="center">
							<b>ลูกหนี้ (ผู้ซื้อตั๋ว)</b>
							<select name="puruser">
								<option value="THCAP" <?php if($puruser=="THCAP") echo "selected";?>>THCAP</option>
								<option value="TAL" <?php if($puruser=="TAL") echo "selected";?>>TAL</option>
							</select>
							<label><b> คืนตั๋วเดือน</b></label>
							<select name="month">
								<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
								<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
								<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
								<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
								<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
								<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
								<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
								<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
								<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
								<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
								<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
								<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
							</select>
							<label><b>ปี ค.ศ.</b></label>
							<input type="text" id="year" name="year" value="<?php echo $year; ?>" size="10" style="text-align:center" maxlength="4" onkeypress="return check_num(event);">
							<input type="hidden" name="val" value="1"/>
							<input type="submit" id="btn00" value="เริ่มค้น"/>
						</p>
					</form>
						<?php
						if($val=="1"){
							$qryboe=pg_query("SELECT \"boeID\", \"boeNumber\", \"payUser\", \"purchaseUser\",loan_amount,interest, \"returnDate\",\"payDate\",\"statusTicket\" FROM account.boe 
							where \"returnDate\" is not null and \"purchaseUser\"='$puruser' and EXTRACT(MONTH FROM \"returnDate\")='$month' and EXTRACT(YEAR FROM \"returnDate\")='$year' order by \"boeNumber\"");
							$numboe=pg_num_rows($qryboe);
						?>						
						<table width="900" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
						<thead>
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<th width="120">เลขที่ตั๋วสัญญา</th>
							<th width="150">เจ้าหนี้ (ผู้ออกตั๋ว)</th>
							<th width="150">ลูกหนี้ (ผู้ซื้อตั๋ว)</th>
							<th width="80">วันที่ซื้อตั๋ว</th>
							<th width="80">วันที่จ่ายคืน</th>
							<th width="100">จำนวนเงิน</th>
							<th width="100">อัตราดอกเบี้ย</th>
							<th width="">รายได้ดอกเบี้ย</th>
						</tr>
						</thead>
						<?php
						$i=0;
						$sum=0;
						while($result=pg_fetch_array($qryboe)){
							list($boeID,$boeNumber,$payUser,$purchaseUser,$loan_amount,$interest,$returnDate,$payDate,$statusTicket)=$result;
							
							
							//หารายได้ดอกเบี้ย
							$inter=pg_query("SELECT \"cal_interestTypeB\"($loan_amount,$interest,'$payDate','$returnDate')");
							$resin=pg_fetch_array($inter);
							list($boe_interest)=$resin;
							$i+=1;
							if($i%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							echo "
								<td>$boeNumber</td>
								<td align=left>$payUser</td>
								<td align=left>$purchaseUser</td>
								<td>$payDate</td>
								<td>$returnDate</td>
								<td align=right>".number_format($loan_amount,2)."</td>
								<td>$interest</td>
								<td align=right>$boe_interest</td>
								</tr>
							";
							$sum=$sum+$boe_interest;
						}
						
						if($numboe==0){
							echo "<tr><td colspan=8 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการ-</b></td></tr>";
						}else{
							echo "<tr><td colspan=7 bgcolor=\"#FFCCCC\" align=right height=25><b>รวม</b></td><td align=right bgcolor=#FFCCCC><b>".number_format($sum,2)."</b></td></tr>";
						}
						echo "</table>";
						}
						?>
						<tr>
						<td colspan="5" align="right"><input type="button" name="submitButton" id="submitButton" value="พิมพ์รายงาน"></td>
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