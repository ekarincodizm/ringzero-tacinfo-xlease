<?php
include("../../config/config.php");

$fpayrefvalue = $_GET['fpayrefvalue'];
$fpayid = $_GET['fpayid'];
$contractID=$_GET["contractID"];
$datepicker = $_GET['datepicker'];
$fpayamp = $_GET['fpayamp'];
$vat_inc = $_GET['vat_inc'];
$cre_fr=pg_query("select thcap_checkContractVAT('$contractID', '$datepicker');"); 
$vat_rate=pg_fetch_result($cre_fr,0);  // vat %
$remark = $_GET["remark"]; // เหตุผล

$maturityDatepicker = $_GET["maturityDatepicker"]; // วันที่ครบกำหนดชำระ

$remark = str_replace("spacebar", " ", $remark);

$qrytype=pg_query("select \"tpDesc\",\"ableVAT\" from account.\"thcap_typePay\" where \"tpID\" = '$fpayid'");
while($restype=pg_fetch_array($qrytype)){
	$ableVAT=$restype["ableVAT"];
	$tpDesc=$restype["tpDesc"];
}
	
if($vat_inc==1 && $ableVAT==1){//รวม vat
	$vatAmt = ( $fpayamp * $vat_rate / (100+$vat_rate)) ; // ภาษีมูลค่าเพิ่ม 
	$vatAmt = round($vatAmt, 2); // ป้องกันเรื่อง ลบกันแล้วได้ x.5 ทั้งคู่ทำให้ปัดขึ้นทั้งคู่แล้วไม่ตรง
	$AmtExtVat = $fpayamp - $vatAmt;
	$AmtCusPay = $fpayamp; //จำนวนเงินที่ลูกค้าต้องจ่าย
}
else if($vat_inc==2 && $ableVAT==1){//ไม่รวม vat แต่ต้องคิด vat
	$vatAmt = ( $fpayamp * $vat_rate / 100) ; // ภาษีมูลค่าเพิ่ม 
	$vatAmt = round($vatAmt, 2); // ป้องกันเรื่อง ลบกันแล้วได้ x.5 ทั้งคู่ทำให้ปัดขึ้นทั้งคู่แล้วไม่ตรง
	$AmtExtVat = $fpayamp ;
	$AmtCusPay = $fpayamp + $vatAmt; //จำนวนเงินที่ลูกค้าต้องจ่าย
}
	 
if($ableVAT==0){ //ไม่คิด Vat
	$vatAmt =0;
	$AmtExtVat = $fpayamp; //จำนวนเงินที่ลูกค้าต้องจ่าย
	$AmtCusPay = $fpayamp; //จำนวนเงินที่ลูกค้าต้องจ่าย
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

<form name="frm1" id="frm1" action="" method="post">

<table width="100%" cellpadding="0" cellspacing="1" border="0">
<tr>
    <td width="50%" align="right" >ประเภทหนี้ : </td><td width="50%">&nbsp; <?php echo $tpDesc ?> </td>
   
   
</tr>
<tr>
   
    <td align="right">เลขอ้างอิงหนี้ : </td><td>&nbsp;  <?php echo $fpayrefvalue ?><span id="ref1"></span></td>
    
   
</tr>
<tr>
   
    <td align="right">วันที่ตั้งหนี้ : </td><td>&nbsp;  <?php echo $datepicker ?></td>
    
   
</tr>
<tr>
    <td align="right">วันที่ครบกำหนดชำระ : </td><td>&nbsp;  <?php if($maturityDatepicker != ""){echo $maturityDatepicker;}else{echo "ไม่มีวันครบกำหนดชำระ";} ?></td>
</tr>
<tr>
   
    <td align="right"><b>จำนวนเงินก่อน VAT : </b></td><td>&nbsp;  <b><font color=blue><?php echo number_format($AmtExtVat,2) ?></font></b></td>
    
   
</tr>
<tr>
   
    <td align="right"><b>ภาษีมูลค่าเพิ่ม (<?php echo $vat_rate ?>%) : </b></td><td>&nbsp;  <b><font color=red><?php echo number_format($vatAmt,2) ?></font></b></td>
    
   
</tr>
<tr>
   
    <td align="right"><b>จำนวนเงินที่ลูกค้าต้องจ่ายสุทธิ : </b></td><td>&nbsp;  <b><font color=green><?php echo number_format($AmtCusPay,2) ?></font></b></td>
    
   
</tr>
<tr>
    <td align="right"><b>เหตุผล : </b></td><td>&nbsp;  <textarea id="remark" name="remark" readOnly><?php echo $remark; ?></textarea></td>
</tr>
</table>

<div style="text-align:right; margin-top:10px">
  
  <input type="button" name="btn_save" id="btn_save" value="บันทึกข้อมูล"  /><input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ยกเลิก">
			
</div>
</form>
</fieldset>
<script type="text/javascript">
$("#ref1").text($("#fpayrefvalue").val());
$('#btn_save').click(function(){
  
$("#btn_save").attr('disabled', true);
	
	
		$.post("process_setdebtloan.php",{
			cmd : "add",
			contractID :'<?php echo $contractID;?>',
			fpayid : $("#fpayid").val(), 
			fpayrefvalue :$("#fpayrefvalue").val(),
			datepicker :$("#datepicker").val(),
			remark :$("#remark").val(),
			fpayamp :'<?php echo $AmtCusPay;?>',
			maturityDatepicker :'<?php echo $maturityDatepicker;?>'
		},
		function(data){
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
				location.href = "frm_setDebtLoanTime.php?contractID=<?php echo $contractID?>&show=1";
				$("#btn_save").attr('disabled', false);
			}else if(data == "2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				$("#btn_save").attr('disabled', false);
			}else if(data == "3"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้ เนื่องจากตั้งหนี้ซ้ำ!");
				$("#btn_save").attr('disabled', false);
			}
		});
	});
	

  
      
			
   


  
</script>
