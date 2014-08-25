<?php
include("../../config/config.php");

$dcNoteID = $_GET['idapp'];
$appstatus = $_GET['appstate'];
$print = $_GET['print'];
$qry_waitapp = pg_query("SELECT * FROM account.\"thcap_dncn_discount\" where \"dcNoteID\" = '$dcNoteID'");
$re_waitapp = pg_fetch_array($qry_waitapp);
//เลขที่สัญญา
	$conid = $re_waitapp["contractID"];
//dcNoteRev
	$dcNoteRev = $re_waitapp["dcNoteRev"];
//-- หาชื่อผู้กู้หลัก
	$qry_maincus = pg_query("SELECT \"dcMainCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
	$maincus_fullname = pg_fetch_result($qry_maincus,0);
//-- หาผู้กู้ร่วม
	$qry_cocus = pg_query("SELECT \"dcCoCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
	$namecoopall = pg_fetch_result($qry_cocus,0);
//วันที่ทำรายการ
	$doerStamp = $re_waitapp["doerStamp"];
//ชื่อผู้ทำรายการ
	$doerID = $re_waitapp["doerID"];
	$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$doerID'");
	list($doer_fullname) = pg_fetch_array($qry_username);
//วันที่อนุมัติ
	$appvStamp = $re_waitapp["appvStamp"];
//ส่วนลดก่อน VAT
	$dcNoteAmtNET = $re_waitapp["dcNoteAmtNET"];
//ส่วนลด VAT
	$dcNoteAmtVAT = $re_waitapp["dcNoteAmtVAT"];
//ส่วนลดรวม
	$dcNoteAmtALL = $re_waitapp["dcNoteAmtALL"];
//เหตุผลการขอคืน
	$remark = $re_waitapp["dcNoteDescription"];
//วันที่รายการออกมีผล
	$dcNoteDate = $re_waitapp["dcNoteDate"];
	if($dcNoteDate == ""){$dcNoteDate = "วันเดียวกับวันที่อนุมัติส่วนลด";}
//เหตุผลการอนุมัติ
	$appvRemask = $re_waitapp["appvRemask"];
//สถานะการอนุมัติ
	$dcNoteStatus = $re_waitapp["dcNoteStatus"];
// รหัสหนี้
	$debtID = $re_waitapp["debtID"];

// กำหนดสถานะการอนุมัติ
if($dcNoteStatus == "0")
{
	$dcNoteStatusText = "ไม่อนุมติ";
}
elseif($dcNoteStatus == "1")
{
	$dcNoteStatusText = "อนุมัติ";
}
elseif($dcNoteStatus == "8")
{
	$dcNoteStatusText = "อยู่ระหว่างรอการอนุมัติ";
}

// หาจำนวนเงินเริ่มแรก
$qry_calDiscount = pg_query("SELECT * FROM account.thcap_dncn_discount_report where \"dcNoteID\" = '$dcNoteID'");
while($res_calDiscount = pg_fetch_array($qry_calDiscount))
{
	$netstart = $res_calDiscount["netstart"]; // ราคาก่อน VAT เริ่มแรก
	$vatstart = $res_calDiscount["vatstart"]; // ราคา VAT เริ่มแรก
	$typePayAmtSrart = $debtNetOld + $debtVatOld; // ยอดหนี้รวม เริ่มแรก
	$typePayID = $res_calDiscount["typePayID"]; // รายการ
}

// จำนวนส่วนลดที่อนุมัติไปก่อนหน้านี้
if($appstatus != "1" && $dcNoteStatus != "9")
{ // ถ้าเป็นการดูรายการประวัติการอนุมัติที่ทำรายการอนุมัติไปแล้ว
	$qry_appvDiscountBefore = pg_query("select sum(\"dcNoteAmtNET\") as \"dcNoteAmtNETappvBefore\", sum(\"dcNoteAmtVAT\") as \"dcNoteAmtVATappvBefore\", sum(\"dcNoteAmtALL\") as \"dcNoteAmtALLappvBefore\"
							from account.thcap_dncn_discount_report where \"debtID\" = '$debtID' and \"appvStamp\" <= '$appvStamp' and \"dcNoteStatus\" = '1' and \"dcNoteID\" <> '$dcNoteID' ");
}
else
{ // ถ้าเป็นการดูรายการที่กำลังจะอนุมัติ
	$qry_appvDiscountBefore = pg_query("select sum(\"dcNoteAmtNET\") as \"dcNoteAmtNETappvBefore\", sum(\"dcNoteAmtVAT\") as \"dcNoteAmtVATappvBefore\", sum(\"dcNoteAmtALL\") as \"dcNoteAmtALLappvBefore\"
							from account.thcap_dncn_discount_report where \"debtID\" = '$debtID' and \"dcNoteStatus\" = '1' and \"dcNoteID\" <> '$dcNoteID' ");
}

// จำนวนส่วนลดที่อนุมัติไปก่อนหน้านี้
//$qry_appvDiscountBefore = pg_query("select sum(\"dcNoteAmtNET\") as \"dcNoteAmtNETappvBefore\", sum(\"dcNoteAmtVAT\") as \"dcNoteAmtVATappvBefore\", sum(\"dcNoteAmtALL\") as \"dcNoteAmtALLappvBefore\"
//							from account.thcap_dncn_discount_report where \"debtID\" = '$debtID' and \"appvStamp\" <= '$appvStamp' and \"dcNoteStatus\" = '1' and \"dcNoteID\" <> '$dcNoteID' ");

$dcNoteAmtNETappvBefore = pg_result($qry_appvDiscountBefore,0); // จำนวนเงินก่อน vat ที่อนุมัติไปก่อนหน้านี้
$dcNoteAmtVATappvBefore = pg_result($qry_appvDiscountBefore,1); // จำนวนเงินหลัง vat ที่อนุมัติไปก่อนหน้านี้
$dcNoteAmtALLappvBefore = pg_result($qry_appvDiscountBefore,2); // จำนวนเงินรวม vat ที่อนุมัติไปก่อนหน้านี้

// คำนวนหาการเปลี่ยนแปลงของเงิน
$debtNetOld = $netstart - $dcNoteAmtNETappvBefore; // ราคาก่อน VAT เดิม
$debtVatOld = $vatstart - $dcNoteAmtVATappvBefore; // ราคา VAT เดิม
$typePayAmtOld = $debtNetOld + $debtVatOld; // ยอดหนี้รวม เดิม

$debtNetNew = $debtNetOld - $dcNoteAmtNET; // ราคาก่อน VAT ใหม่
$debtVatNew = $debtVatOld - $dcNoteAmtVAT; // ราคา VAT ใหม่
$typePayAmtNew = $debtNetNew + $debtVatNew; // ยอดหนี้รวม ใหม่


// รายละเอียดประเภทค่าใช้จ่าย
$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
while($res_type=pg_fetch_array($qry_type))
{
	$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
	$tpFullDesc=trim($res_type["tpFullDesc"]); // รายละเอียดแบบเต็ม
}

// หาค่าอ้างอิง
$qry_typePayRefValue = pg_query("select \"typePayRefValue\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
$typePayRefValue = pg_fetch_result($qry_typePayRefValue,0);

// หาประเภทสัญญา
$qry_type_contract = pg_query("select \"thcap_get_creditType\"('$conid') ");
$res_type_contract = pg_fetch_result($qry_type_contract,0);

if($res_type_contract == "HIRE_PURCHASE" || $res_type_contract == "LEASING")
{
	// หา รหัสของค่างวด
	$qry_getMinPayType = pg_query("select account.\"thcap_mg_getMinPayType\"('$conid') ");
	$res_getMinPayType = pg_fetch_result($qry_getMinPayType,0);
	
	// ถ้าเป็นค่างวดของ HP
	if($typePayID == $res_getMinPayType)
	{
		$tpDesc = "$tpDesc $tpFullDesc $typePayRefValue";
	}
}
?>
    
<fieldset>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>

<form name="frm1" id="frm1" action="process_appcn.php" method="post">

<table width="100%" cellpadding="0" cellspacing="1" border="0">
	<tr>
		<td width="40%" align="right" >เลขที่สัญญา : </td><td width="60%">&nbsp; <?php echo $conid ?> </td>
	</tr>
	<tr>
		<td align="right">รายการ : </td><td>&nbsp;  <?php echo $tpDesc; ?></td>
	</tr>
	<tr>
		<td align="right">ค่าอ้างอิง : </td><td>&nbsp;  <?php echo "$tpFullDesc $typePayRefValue"; ?></td>  
	</tr>
	<tr>   
		<td align="right">ชื่อผู้กู้หลัก : </td><td>&nbsp;  <?php echo $maincus_fullname; ?></td>   
	</tr>
	<tr>	   
		<td align="right">ชื่อผู้กู้ร่วม : </td><td>&nbsp; <?php echo $namecoopall; ?></td>	   
	</tr>
	<tr>	   
		<td align="right">รหัส CreditNote : </td><td>&nbsp;  <?php echo $dcNoteID; ?></td>
	</tr>
	<tr>
		<td align="right">วันที่ส่วนลดมีผล : </td><td>&nbsp;  <?php echo $dcNoteDate; ?></td>			   
	</tr>
	<tr>	   
		<td align="right">วันที่ทำรายการขอส่วนลด : </td><td>&nbsp;  <?php echo $doerStamp; ?></td>			   
	</tr>
	<?php
	if($appvStamp != "")
	{ // ถ้าวันที่ทำรายการอนุมัติไม่ว่าง
	?>
		<tr>	   
			<td align="right">วันที่ทำรายการอนุมัติ : </td><td>&nbsp;  <?php echo $appvStamp; ?></td>			   
		</tr>
	<?php
	}
	?>
	<tr>
		<td align="right">ผลการอนุมัติ : </td><td>&nbsp;  <?php echo $dcNoteStatusText; ?></td>			   
	</tr>
	<tr> 
		<td align="right">ราคาก่อน VAT เดิม : </td><td>&nbsp;  <b><font color=green><?php echo number_format($debtNetOld,2) ?></font></b></td>
	</tr>
	<tr> 
		<td align="right">ราคา VAT เดิม : </td><td>&nbsp;  <b><font color=green><?php echo number_format($debtVatOld,2) ?></font></b></td>
	</tr>
	<tr> 
		<td align="right">ยอดหนี้รวม เดิม : </td><td>&nbsp;  <b><font color=green><?php echo number_format($typePayAmtOld,2) ?></font></b></td>
	</tr>
	<tr>
		<td colspan="2"><hr width="80%"></td>
	</tr>
	<tr> 
		<td align="right">ส่วนลดก่อน VAT : </td><td>&nbsp;  <b><font color=green><?php echo number_format($dcNoteAmtNET,2) ?></font></b></td>
	</tr>
	<tr> 
		<td align="right">ส่วนลด VAT : </td><td>&nbsp;  <b><font color=green><?php echo number_format($dcNoteAmtVAT,2) ?></font></b></td>
	</tr>
	<tr> 
		<td align="right">ส่วนลดรวม : </td><td>&nbsp;  <b><font color=green><?php echo number_format($dcNoteAmtALL,2) ?></font></b></td>
	</tr>
	<tr>
		<td colspan="2"><hr width="80%"></td>
	</tr>
	<tr> 
		<td align="right">ราคาก่อน VAT ใหม่ : </td><td>&nbsp;  <b><font color=green><?php echo number_format($debtNetNew,2) ?></font></b></td>
	</tr>
	<tr> 
		<td align="right">ราคา VAT ใหม่ : </td><td>&nbsp;  <b><font color=green><?php echo number_format($debtVatNew,2) ?></font></b></td>
	</tr>
	<tr> 
		<td align="right">ยอดหนี้รวม ใหม่ : </td><td>&nbsp;  <b><font color=green><?php echo number_format($typePayAmtNew,2) ?></font></b></td>
	</tr>
	<tr>
		<td align="right">รายละเอียด : </td><td>&nbsp;  <textarea id="remark" name="remark" readOnly><?php echo $remark; ?></textarea></td>
	</tr>
<?php if($appstatus == '1' and $print!=1){ ?> 	
	<tr>
		<td colspan="2"><hr width="80%"></td>
	</tr>
		<tr>
		<td colspan="2" align="center"><b><u>การอนุมัติ</u><b><br></td>
	</tr>
	<tr>	
		<td align="right">เหตุผล : </td><td>&nbsp;  <textarea id="appremark" name="appremark" ></textarea></td>
	</tr>
<?php }else if($appstatus != '1' AND $dcNoteStatus != '9'){ ?>
	<tr>
		<td colspan="2"><hr width="80%"></td>
	</tr>
		<tr>
		<td colspan="2" align="center"><b><u>เหตุผลการอนุมัติ</u><b><br></td>
	</tr>
	<tr>	
		<td align="right">เหตุผล : </td><td>&nbsp;  <textarea id="appremark" name="appremark" Readonly ><?php echo  $appvRemask; ?></textarea></td>
	</tr>

<?php } ?> 
</table>

<div style="text-align:right; margin-top:10px">
 <?php if($appstatus == '1'){ 
	if($print!='1'){
	?> 
	<!--input type="button" name="btn_app" id="btn_app" value="อนุมัติ" onclick="app();" />
	<input type="button" name="btn_notapp" id="btn_notapp" value="ไม่อนุมัติ" onclick="notapp();" /-->	
	<input type="submit" name="btn_app" id="btn_app" value="อนุมัติ" onClick="return app()" />
	<input type="submit" name="btn_notapp" id="btn_notapp" value="ไม่อนุมัติ" onClick="return notapp()" />
	<input type="hidden" name="dcNoteID" id="dcNoteID" value="<?php echo $dcNoteID;?>">
	<?php
	}
	?>
	<input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ปิด">
 <?php } ?>			
</div>
</form>
</fieldset>
<script type="text/javascript">
function app(){
		if(confirm("ยืนยันการอนุมัติ")==true){
		    return true;
		}
		else{
			return false;
		}
}

function notapp(){
	if($("#appremark").val() == ""){	
		alert("กรอกเหตุผลการปฎิเสธอนุมัติด้วยครับ");	
		return false;
	}
	else{
		if(confirm("ปฎิเสธการอนุมัติ")==true){
			$("#btn_notapp").attr('disabled', true);
			return true;
		}
		else{ return false;
		}
	}
};
</script>
