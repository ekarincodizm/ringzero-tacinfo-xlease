<?php
session_start();
include("../../config/config.php");
$auto_id = pg_escape_string($_GET['auto_id']);

$query_detail_nt = pg_query("select \"NT_ID\",\"NT_enddate\",\"note_ask\" from \"thcap_cancel_nt_temp\" where \"auto_id\" = '$auto_id'");
$result_detail = pg_fetch_array($query_detail_nt);
$NT_ID= $result_detail["NT_ID"];
$NT_enddate= $result_detail["NT_enddate"];
$note_ask= $result_detail["note_ask"];

$query_detailpdf = pg_query("select * from \"thcap_pdf_nt\" where \"NT_ID\" = '$NT_ID'");
$result = pg_fetch_array($query_detailpdf);
$numrows = pg_num_rows($query_detailpdf);
if($numrows >0){
	$NT_ID= $result["NT_ID"];
	$contractID= $result["contractID"];
	
	$unpaid_detailall_amt= $result["unpaid_detailall_amt"];
	
	$arraypay_next= $result["arraypay_next"];
	$amountpay_next= $result["amountpay_next"];
	$amountpay_all= $result["amountpay_all"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"><link>
	<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>   

</head>
<script type="text/JavaScript">
</script>
<body>

<fieldset><legend><B>รายละเอียด</B></legend>
<table width="100%">
<tr>
    <td><b>เลขที่ NT:</b></td>
    <td><?php echo $NT_ID; ?></td>
	<td><b>เลขที่สัญญา:</b></td>
	<td><?php echo $contractID; ?></td>
</tr>
<tr>
    <?php
		$query_s = pg_query("SELECT \"arrayfirst_unpaid\"[1] as \"pay_s\",\"arrayfirst_unpaid\"[2] as \"date_s\"  
			from \"thcap_pdf_nt\" where \"NT_ID\"='$NT_ID'");	
			$res = pg_fetch_array($query_s);
			$pay_s = $res["pay_s"];
			$date_s = $res["date_s"];
		
		$query_e = pg_query("SELECT \"arrayend_unpaid\"[1] as \"pay_e\",\"arrayend_unpaid\"[2] as \"date_e\"  
			from \"thcap_pdf_nt\" where \"NT_ID\"='$NT_ID'");	
			$res = pg_fetch_array($query_e);
			$pay_e = $res["pay_e"];
			$date_e = $res["date_e"];
	?>
	<td><b>งวดที่เริ่มค้าง:</b></td>
    <td><?php echo "งวดที่ ".$pay_s." ประจำวันที่  ".$date_s; ?></td>
	<td><b>ถึง:</b></td>
	<td><?php echo "งวดที่ ".$pay_e." ประจำวันที่  ".$date_e; ?></td>
</tr>
<tr>
	<td><b>รายละเอียด:</b></td>
    <?php 
		$query_no = pg_query("SELECT generate_subscripts(\"arrayunpaid_detailall\",1) as \"no\"  from \"thcap_pdf_nt\" where \"NT_ID\"='$NT_ID'");
		while($res_no = pg_fetch_array($query_no))
		{
			$no = $res_no["no"];
			$query_list = pg_query("SELECT \"arrayunpaid_detailall\"['$no'][1] as \"name\",\"arrayunpaid_detailall\"['$no'][2] as \"valuee\"  
			from \"thcap_pdf_nt\" where \"NT_ID\"='$NT_ID'");			
			$res = pg_fetch_array($query_list);
			$detail_name = $res["name"];
			$detail_value = $res["valuee"];?>
			<tr>
				<td></td>
				<td><?php echo $no.". ".$detail_name;?></td>
				<td><?php echo number_format($detail_value,2)."   บาท";?></td>				
			</tr>
<tr>
	<?php	}
	?>
	

</tr>

<tr>
    <td colspan="2" align="right"><b>รวมเป็น:</b></td>  
	<td><b><?php echo number_format($unpaid_detailall_amt,2)."   บาท"; ?></b></td>
	
</tr>
<tr>
<?php
$query_n = pg_query("SELECT \"arraypay_next\"[1] as \"pay_n\",\"arraypay_next\"[2] as \"date_n\"  
			from \"thcap_pdf_nt\" where \"NT_ID\"='$NT_ID'");	
			$res = pg_fetch_array($query_n);
			$pay_n = $res["pay_n"];
			$date_n = $res["date_n"];

?>
    <td><b>เรียกเก็บเพิ่มอีก 1 งวด ประจำงาด:</b></td>
    <td><?php echo "งวดที่ ".$pay_n." ประจำวันที่  ".$date_n; ?></td>
	<td><b>จำนวนเงิน:</b></td>
	<td><?php echo number_format($amountpay_next,2); ?></td>
</tr>
<tr>
    <td><b>ดังนั้นยอดรวมทั้งสิ้น :</b></td>
    <td><?php echo number_format($amountpay_all,2); ?></td>
	<td><b>บาท</b></td>
</tr>
<form name="frm" method="post" action="process_appv_cancel.php">
<tr>
	<input type="text" id="auto_id"  name="auto_id" value='<?php echo $auto_id;?>' hidden>
    <td><b>วันที่ ยกเลิกการออก NT :</b></td>
    <td><?php echo $NT_enddate ;?></td>
	
</tr>
<tr>	
    <td  valign="top"><b>หมายเหตุการขอยกเลิก :</b></td>
    <td><textarea name="note_ask" id="note_ask" cols="40" rows="3"><?php echo $note_ask;?></textarea></td>	
</tr>
<tr>	
    <td  valign="top"><b>หมายเหตุการ :</b></td>
    <td><textarea name="note_appv" id="note_appv" cols="40" rows="3"></textarea></td>	
</tr>
<tr>
    <td colspan="4" align="center">
	<input id="appv" name="appv" type="submit" value="อนุมัติ">
	<input id="noappv" name="noappv" type="submit" value="ไม่อนุมัติ"></td>
</form>	
</tr>
</table>
</fieldset> 

</body>
<?php } ?>
</html>