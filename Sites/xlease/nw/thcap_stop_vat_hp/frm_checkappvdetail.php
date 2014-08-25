<?php include('../../config/config.php');
$contractID=$_GET["idno"];
$fullname=$_GET["fullname"];
$payamt=$_GET["payamt"];
$debtDueDate=$_GET["debtDueDate"];

//ดึง ข้อมูล ต่าง ๆ
$query_detail= pg_query("select a.\"FullName\" as \"FullName\",c.\"debtDueDate\" as \"debtDueDate\" ,
				c.\"typePayAmt\" as \"typePayAmt\",c.\"typePayRefValue\" as \"typePayRefValue\" from \"thcap_ContactCus\" a
				LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
				LEFT JOIN \"thcap_v_lease_table\" c on a.\"contractID\" =c.\"contractID\" 
				where \"CusState\" = '0' and c.\"debtDueDate\" <= current_date and a.\"contractID\"='$contractID'
				and \"receiptID\" is null ");

//$numrows = pg_num_rows($query_detail);
$no=0;
?>

<script type="text/javascript">
/*function stopvat() {

$.post("process_appv.php",{
				  contractID : '<?php echo $contractID; ?>',
				  debtDueDate: '<?php echo $debtDueDate; ?>',
				  payamt : '<?php echo $payamt; ?>',
				  note: $('#note').val(),
				  clickact:'1'
			},
			function(data){	
				if(data==1){
					alert("บันทึกข้อมูลเรียบร้อยแล้ว");
					window.opener.location.reload();
					self.close();
				}else{
					alert("ไม่สามารถบันทึกข้อมูลได้ ");
				}
			});
}*/
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>


<body>
<div style="text-align:center"><h2>อนุมัติ STOP VAT HP</h2></div>
<div><b>เลขที่สัญญา :</b> <?php echo $contractID?></div>
<div><b>ชื่อผู้กู้หลัก/ผู้เช่าซื้อหลัก :</b> <?php echo $fullname;?></div>
<div style=\"padding-top:10px\"><b>รายการที่ค้างชำระ:</b></div>  
<table width="70%" cellSpacing="1" cellPadding="2" bgcolor="#EEEED1" align="left">
	<tr align="center" bgcolor="#CDCDB4">
		<th>รายการที่</th>
		<th>งาดที่</th>
		<th>วันที่ครบกำหนดจ่าย</th>
		<th>ยอดที่ต้องจ่ายรวม VAT</th>
	</tr>
<?php 
	while($result = pg_fetch_array($query_detail)){
	$no+=1;
	$typePayRefValue= $result["typePayRefValue"]; 
	$debtDueDate= $result["debtDueDate"]; 
	$typePayAmt= $result["typePayAmt"]; 
	
	if($no%2==0)
				{
					echo "<tr bgcolor=\"#FFFFE0\" height=20 align=\"center\">";
				}
				else
				{
					echo "<tr bgcolor=\"#FFEC8B\" height=20 align=\"center\">";
				}
	echo "<td align=\"center\">$no</td>";
	echo "<td align=\"center\">$typePayRefValue</td>";
	echo "<td align=\"center\">$debtDueDate</td>";
	echo "<td align=\"center\">".number_format($typePayAmt,2)."</td></tr>";
}
echo "<tr><td colspan=\"3\" align=\"center\" ><b>รวม</b></td>";
echo "<td align=\"center\"><b>".number_format($payamt,2)."</b></td></tr>";
?>
</table>
<form name="my" method="post" action="process_appv.php">
<div style="padding-top:10px;width:400px;">
<fieldset><legend><b>หมายเหตุ</b></legend>
<textarea name="note" id="note" cols="60" rows="4" >
</textarea>
</fieldset>
</div>
<div style="text-align:center;padding:20px">
	<input type="submit" name="STOP" value="STOP VAT">
	<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID; ?>">
	<input type="hidden" name="debtDueDate" id="debtDueDate" value="<?php echo $debtDueDate; ?>">
	<input type="hidden" name="payamt" id="payamt" value="<?php echo $payamt; ?>">
	<input type="button"  value="UNSTOP VAT" hidden>
	<input type="button" onclick="window.close();" value="ปิดหน้านี้">
</form>
</div>
</body>