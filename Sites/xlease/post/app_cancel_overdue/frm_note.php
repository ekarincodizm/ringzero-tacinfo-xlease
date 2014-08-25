<?php 
include('../../config/config.php'); ?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
$autoID=pg_escape_string($_GET["autoid"]); 
$query_note = pg_query("select * from carregis.\"CarTaxDue_reserve\"  where \"auto_id\" ='$autoID'");
$result = pg_fetch_array($query_note);
$numrows = pg_num_rows($query_note);
if($numrows >0){
	$note= $result["remark_app"]; //หมายเหตุ
	$IDCarTax = $result["IDCarTax"];
	$IDNO = $result["IDNO"];
	$TypeDep = $result["TypeDep"];
	$CusAmt = $result["CusAmt"];
	$appvID = $result["appvID"];
	$appvStamp = $result["appvStamp"];
	$remark_doer = $result["remark_doer"];
	$cuspaid = $result["cuspaid"];	
	$doerID = $result["doerID"];
	$doerStamp = $result["doerStamp"];
	
	$CusAmt=number_format($CusAmt,2);	
	if($remark_doer==""){$remark_doer='ไม่ได้ระบุหมายเหตุ';}
	//การชำระเงิน 
	if($cuspaid	=='t'){
		$status_cuspaid	="ชำระแล้ว";
	}elseif($cuspaid=='f'){
		$status_cuspaid	="ยังไม่ชำระ";
	}
	//รายการ $TypeDep			
	$qry_TName=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
	$TName=pg_fetch_array($qry_TName);
	$Pay_name= ($TName["TName"]);
	
	//ผู้ทำอนุมัติ/ไม่อนุมัติรายการ
	$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
	$fullnameuser = pg_fetch_array($query_fullnameuser);
	$appfullname=$fullnameuser["fullname"];
	
	$qry_doer_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$doerID'");
	$rs_doer_name = pg_fetch_array($qry_doer_name);
	$doername = $rs_doer_name["fullname"]; 
}


?>
<center>
<fieldset  style="width:800px;"><legend><font color="black"><b>หมายเหตุรายละเอียดการอนุมัติ/ไม่อนุมัติรายการ </legend>
<table align="center" border="0"  >
<tr><td align="right"><b>เลขที่สัญญาเช่าซื้อ :</b></td><td> <?php echo $IDNO;?></td></tr>
		<tr><td align="right"><b>เลขที่:</b></td><td> <?php echo $IDCarTax;?></td></tr>
		<tr><td align="right"><b>รายการ :</b></td><td> <?php echo $Pay_name;?></td>	</tr>
		<tr><td align="right"><b>ยอดเงินที่เก็บกับลูกค้า :</b></td><td> <?php echo $CusAmt;?></td></tr>
		<tr><td align="right"><b>การชำระของลูกค้า :</b></td><td> <?php echo $status_cuspaid;?></td></tr>
		<tr><td align="right"><b>ผู้ที่ทำรายการ :</b></td><td> <?php echo $doername;?></td></tr>
		<tr><td align="right"><b>วันที่ทำรายการ :</b></td><td> <?php echo $doerStamp;?></td></tr>
		<tr><td align="right"  valign="top"><b>หมายเหตุการขอยกเลิก :</b></td><td><textarea cols="50" rows="3" readonly><?php echo $remark_doer;?></textarea></td></tr>
		<tr><td align="right"><b>ผู้ทำการอนุมัติ :</b></td><td> <?php echo $appfullname;?></td></tr>
		<tr><td align="right"><b>วันเวลาที่ทำการอนุมัติ :</b></td><td> <?php echo $appvStamp;?></td></tr>
		<tr><td align="right"  valign="top"><b>หมายเหตุการอนุมัติ :</b></td><td><textarea name="note" id="note" cols="50" rows="4" readonly><?php echo $note;?></textarea></td></tr>
</table>
</fieldset></center>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>