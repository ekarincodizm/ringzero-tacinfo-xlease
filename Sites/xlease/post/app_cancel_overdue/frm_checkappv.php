<?php 
include('../../config/config.php');

$id=pg_escape_string($_GET["id"]);
//ดึง ข้อมูล ต่าง ๆ
$qry_main = pg_query("select * from carregis.\"CarTaxDue_reserve\" where \"Approved\"=9 and \"auto_id\"='$id' order by \"doerStamp\" desc	");
$res_main = pg_fetch_array($qry_main);
$auto_id = $res_main["auto_id"];
$IDCarTax = $res_main["IDCarTax"];
$IDNO = $res_main["IDNO"];
$TypeDep = $res_main["TypeDep"];
$CusAmt = $res_main["CusAmt"];
$cuspaid = $res_main["cuspaid"];		
$doerID = $res_main["doerID"];
$doerStamp = $res_main["doerStamp"];
$remark_doer = $res_main["remark_doer"];

if($remark_doer==""){$remark_doer='ไม่ได้ระบุหมายเหตุ';}
$CusAmt=number_format($CusAmt,2);	
//รายการ $TypeDep			
$qry_TName=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
$TName=pg_fetch_array($qry_TName);
$Pay_name= ($TName["TName"]);

//การชำระเงิน 
if($cuspaid	=='t'){
	$status_cuspaid	="ชำระแล้ว";
}elseif($cuspaid=='f'){
	$status_cuspaid	="ยังไม่ชำระ";
}
//ผู้ทำรายการ
$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
$fullnameuser = pg_fetch_array($query_fullnameuser);
$doerfullname=$fullnameuser["fullname"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script language="JavaScript">

function chk()
{    
    if(document.getElementById("note").value==""){ 
		alert("กรุณาระบุหมายเหตุ");
		return false;
	}
	else{ return true;}
}
</script>

<body>
<div style="text-align:center"><h2>อนุมัติยกเลิกรายการค้างชำระ</h2></div>
<center>
<form name="my" method="post" action="process_appv.php">
<fieldset  style="width:500px;"><legend><font color="black"><b>รายละเอียด </legend>
	<table align="center" border="0"  >
		<tr><td align="right"><b>เลขที่สัญญาเช่าซื้อ :</b></td><td> <?php echo $IDNO;?></td></tr>
		<tr><td align="right"><b>เลขที่:</b></td><td> <?php echo $IDCarTax;?></td></tr>
		<tr><td align="right"><b>รายการ :</b></td><td> <?php echo $Pay_name;?></td>	</tr>
		<tr><td align="right"><b>ยอดเงินที่เก็บกับลูกค้า :</b></td><td> <?php echo $CusAmt;?></td></tr>
		<tr><td align="right"><b>การชำระของลูกค้า :</b></td><td> <?php echo $status_cuspaid;?></td></tr>
		<tr><td align="right"><b>ผู้ที่ทำรายการ :</b></td><td> <?php echo $doerfullname;?></td></tr>
		<tr><td align="right"><b>วันที่ทำรายการ :</b></td><td> <?php echo $doerStamp;?></td></tr>
		<tr><td align="right" valign="top"><b>หมายเหตุการขอยกเลิก :</b></td><td><textarea cols="50" rows="3" readonly><?php echo $remark_doer;?></textarea></td></tr>
		<tr><td align="right" valign="top"><b>หมายเหตุ :</b></td><td>
		<textarea name="note" id="note" cols="50" rows="4"></textarea></td></tr>
	</table>
</fieldset>

<?php
//ดึง ข้อมูล  ที่ตาราง carregis."DetailCarTax" เพื่อ ทดสอบ ว่าจะ ลบ ข้อมูล หริอไม่
$qry_dataDetailCarTax=pg_query("select \"IDCarTax\",\"Cancel\" from carregis.\"DetailCarTax\" WHERE \"Cancel\" = 'false' AND \"IDCarTax\"='$IDCarTax'");
$numrow_dataDetailCarTax=pg_num_rows($qry_dataDetailCarTax);

if($numrow_dataDetailCarTax > 0)
{
	echo "<br><font color=\"#FF0000\"><b>* รายการดังกล่าวมีการบันทึกว่ามีต้นทุนในการดำเนินงาน</b></font>";
}
?>

<div style="text-align:center;padding:20px">	
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">	
	<input type="hidden" name="payamt" id="payamt" value="<?php echo $payamt; ?>">
	<input name="appv" type="submit" value="อนุมัติ" onclick="return chk()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="unappv" type="submit" value="ไม่อนุมัติ" onclick="return chk()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" onclick="window.close();" value="ปิดหน้านี้">
</div>
</form>
</center>
</body>