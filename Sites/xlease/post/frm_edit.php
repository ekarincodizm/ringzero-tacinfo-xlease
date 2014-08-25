<?php
session_start();
include("../config/config.php");
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$srcid = trim(pg_escape_string($_POST["h_id"]));
if(empty($srcid)){
	$edt_idno = pg_escape_string($_GET["idnog"]);
}else{
	$subid=$srcid;		 
	$edt_idno=$subid;
}
//ดึงข้อมูลเลขที่สัญญาใน Fp_Fa1 มาแสดง
$qry_lt2=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$edt_idno'  and \"edittime\"='0' and \"CusState\"='0'");
$numr_lt2=pg_num_rows($qry_lt2);
						
if($numr_lt2==0)
{
	$add_lt="---ใช้ตามที่อยู่ลูกค้า---";											 
}
else
{
	$res_lt2=pg_fetch_array($qry_lt2);
	$fs_no=trim($res_lt2["A_NO"]);
	$fs_subno=trim($res_lt2["A_SUBNO"]);
	$fs_soi=trim($res_lt2["A_SOI"]); 
	$fs_rd=trim($res_lt2["A_RD"]);
	$fs_tum=trim($res_lt2["A_TUM"]);
	$fs_aum=trim($res_lt2["A_AUM"]);
	$fs_province=trim($res_lt2["A_PRO"]);
	$fs_post=trim($res_lt2["A_POST"]);
	$A_ROOM=trim($res_lt2["A_ROOM"]); //ห้อง
	$A_FLOOR=trim($res_lt2["A_FLOOR"]); //ชั้น
	$A_BUILDING=trim($res_lt2["A_BUILDING"]); //อาคาร/สถานที่
	$A_VILLAGE=trim($res_lt2["A_BAN"]); //หมู่บ้าน

	if($fs_subno!="" and $fs_subno!="-" and $fs_subno!="--"){
		$subno="ม.$fs_subno";
	}
	if($fs_soi!="" and $fs_soi!="-" and $fs_soi!="--"){
		$soi="ซ.$fs_soi";
	}
	if($fs_rd!="" and $fs_rd!="-" and $fs_rd!="--"){
		$road="ถ.$fs_rd";
	}
	if($fs_province=="กรุงเทพมหานคร" || $fs_province=="กรุงเทพ" || $fs_province=="กรุงเทพฯ" || $fs_province=="กทม."){
		if($fs_tum!="" and $fs_tum!="-" and $fs_tum!="--"){
			$txttum="แขวง".$fs_tum;
		}
		if($fs_aum!="" and $fs_aum!="-" and $fs_aum!="--"){
			$txtaum="เขต".$fs_aum;
		}
		$txtpro="$fs_province";

	}else{
		if($fs_tum!="" and $fs_tum!="-" and $fs_tum!="--"){
			$txttum="ต.".$fs_tum;
		}
		if($fs_aum!="" and $fs_aum!="-" and $fs_aum!="--"){
			$txtaum="อ.".$fs_aum;
		}
		$txtpro="จ.$fs_province";
	}
	if($A_ROOM != "" and $A_ROOM != "-" and $A_ROOM != "--"){ //ห้อง
		$txtA_ROOM = "ห้อง ".$A_ROOM;
	}
	if($A_FLOOR != "" and $A_FLOOR != "-" and $A_FLOOR != "--"){ //ชั้น
		$txtA_FLOOR = "ชั้น ".$A_FLOOR;
	}
	if($A_BUILDING != "" and $A_BUILDING != "-" and $A_BUILDING != "--"){ //อาคาร/สถานที่
		$txtA_BUILDING = $A_BUILDING;
	}
	if($A_VILLAGE != "" and $A_VILLAGE != "-" and $A_VILLAGE != "--"){ //หมู่บ้าน
		$txtA_VILLAGE = "หมู่บ้าน".$A_VILLAGE;
	}
	
	$add_lt = "$fs_no $txtA_ROOM $txtA_FLOOR $subno $A_BUILDING $txtA_VILLAGE $soi $road $txttum $txtaum $txtpro $fs_post";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>

<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>

<script type="text/javascript">

function refreshListBox() // refreshทั้งหมด
{  
	
	//alert(' xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');  

	var dataColorList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataAssetsList  
		  url: "dataForColorList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  data:"selectColor="+$(f_carcolor).val(), // ส่งตัวแปร GET ชื่อ list1
		  async: false  
	}).responseText;
		$("select#f_carcolor").html(dataColorList); // นำค่า dataAssetsList มาแสดงใน listbox ที่ชื่อ assets..
	
}

function calcfunc() {
	var val1 = parseFloat(document.frm_edit.f_ptotal.value); //จำนวนงวด
	var val2 = parseFloat(document.frm_edit.f_pmonth.value); //ค่างวด

	var val_begin = parseFloat(document.frm_edit.f_pbegin.value); //เงินต้นลูกค้า

	var res_mt=val1*val2;
	parseFloat(document.frm_edit.resbs.value=val1*val2);

	if(val_begin > res_mt){
		//alert(" ยอดจัดไฟแนนซ์ น้อยกว่า เงินต้นลูกค้า ");
		document.frm_edit.res_txt.value="ยอดจัดไฟแนนซ์ น้อยกว่า เงินต้นลูกค้า";
	}else{
		/*alert("  ok ");*/
		document.frm_edit.res_txt.value=" ";
	} 
}

$(document).ready(function(){
	$("#btnaddnew").hide();
	$("#updatelistbox").hide();
	
	$("#addidno").change(function(){
		var src = $('#addidno option:selected').attr('value');
		if(src== "1"){
			$("#btnaddnew").hide();
			$("#f_letter").val('---ใช้ตามที่อยู่ลูกค้า---')
		}else if(src=="2"){
			$("#btnaddnew").show();
			$("#f_letter").val('');
		}else{
			$("#btnaddnew").hide();
			$("#f_letter").val('<?php echo $add_lt;?>');
		}
		
	});
	
	$("#f_brithday").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
			
    });
	
	$("#f_fri_name").autocomplete({
        source: "s_title.php",
        minLength:1
    });
	
	//กรณีเลือกคนไทย
	$('#cus1').click(function(){
		if($("#cus1").is(':checked')){	
			$('#showedit').show();
			$('#f_cardid').css('color', '#000000');
			if($('#hdidcardchk').val()=='true'){
				$('#f_cardid').css('background-color', '#66FF66');
			}else{
				$('#f_cardid').css('background-color', '#FF3030');
			}
			$('#f_cardid').attr('readonly', false); //ให้เลขที่บัตรประชาชนสามารถกรอกได้
		}
	});
	
	//กรณีเลือกชาวต่างชาติ
	$('#cus2').click(function(){
		if($("#cus2").is(':checked')){	
			$('#showedit').hide();
			$('#hdidcardchk').val('true');
			$('#f_cardid').css('color', '#DDDDDD');
			$('#f_cardid').css('background-color', '#DDDDDD');			
			$('#f_cardid').attr('readonly', true); //ให้เลขที่บัตรประชาชนไม่สามารถกรอกได้
		}
	});
	
	//กรณีเลือกบริษัท
	$('#cus3').click(function(){
		if($("#cus3").is(':checked')){
			$('#showedit').hide();
			$('#hdidcardchk').val('true');
			$('#f_cardid').css('color', '#DDDDDD');
			$('#f_cardid').css('background-color', '#DDDDDD');
			$('#f_cardid').attr('readonly', true); //ให้เลขที่บัตรประชาชนไม่สามารถกรอกได้
		}
	});
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>


<script language="JavaScript">
function validForm() {
	 checkrequire();
   
	var p1 = parseFloat(document.frm_edit.f_ptotal.value); //จำนวนงวด
	var p2 = parseFloat(document.frm_edit.f_pmonth.value); //ค่างวด
	var  tp=p1*p2;
	var p_begin = parseFloat(document.frm_edit.f_pbegin.value); //เงินต้นลูกค้า
	
	if (document.frm_edit.f_name.value=="") {
		alert("กรุณาใส่ชื่อ " );         
		document.frm_edit.f_name.focus();
		return false;
	}
	if (document.frm_edit.f_surname.value=="") {
		alert(" กรุณาใส่ นามสกุล " );         
		document.frm_edit.f_surname.focus();
		return false;
	} 
	if (document.frm_edit.f_brithday.value=="") {
		alert(" กรุณาระบุวันเกิด " );         
		document.frm_edit.f_brithday.focus();
		return false;
	}
	
	if (document.frm_edit.f_age.value=="") {
		alert("   กรุณาใส่ อายุ" );         
		document.frm_edit.f_age.focus();
		return false;
	}
	
	if (document.frm_edit.f_status.value=="") {
		alert(" กรุณาระบุสถานภาพ " );         
		document.frm_edit.f_status.focus();
		return false;
	}
	
	if (document.frm_edit.f_san.value=="") {
		alert(" กรุณาใส่ สัญชาติ " ); 
		return false;
	}else{
		//กรณีเลือกคนไทย
		if($("#cus1").is(':checked')){
			//ถ้าเลือกสัญชาติที่ไม่ใช่ไทยถือว่าผิด
			if($('#f_san') .attr('value')!='ไทย' && $('#f_san') .attr('value')!='ไม่ระบุ'){
				alert(" กรุณาเลือกสัญชาติไทยหรือไม่ระบุ " ); 
				return false;
			}
		}
		
		//กรณีเลือกชาวต่างชาติ
		if($("#cus2").is(':checked')){
			//ถ้าเลือกสัญชาติไทยถือว่าผิด
			if($('#f_san') .attr('value')=='ไทย'){
				alert(" กรุณาเลือกสัญชาติอื่นที่ไม่ใช่ไทย " ); 
				return false;
			}
		}
	}
	
	//กรณีเลือกคนไทย
	if($("#cus1").is(':checked')){
		if (document.frm_edit.f_cardid.value=="") {
			alert("   กรุณาใส่ เลขที่บัตรประชาชน" );         
			document.frm_edit.f_cardid.focus();
			return false;
		}
	}

	if (document.frm_edit.f_datecard.value=="") {
		alert("   กรุณาใส่ วันที่ออกบัตร" );         
		document.frm_edit.f_datecard.focus();
		return false;
	}
	if (document.frm_edit.f_card_by.value=="") {
		alert("   กรุณาใส่ ผู้ออกบัตร" );         
		document.frm_edit.f_card_by.focus();
		return false;
	}
	if(document.frm_edit.chk_other.checked == true){
		//กรณีเลือกคนไทย
		if($("#cus1").is(':checked')){
			//ถ้าเลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
			if($('#list_other') .attr( 'value')=='หนังสือเดินทาง(ต่างประเทศ)' || $('#list_other') .attr( 'value')=='บัตรต่างด้าว'){
				alert("กรุณาเลือกประเภทบัตรให้ถูกต้อง");
				return false;
			}
		}
		//กรณีเลือกชาวต่างชาติ
		if($("#cus2").is(':checked')){
			//ถ้าไม่ได้เลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
			if($('#list_other') .attr( 'value')!='หนังสือเดินทาง(ต่างประเทศ)' 
			&& $('#list_other') .attr( 'value')!='บัตรต่างด้าว' && $('#list_other') .attr( 'value')!='other'){
				alert("กรุณาเลือกประเภทบัตรให้ถูกต้อง");
				return false;
			}
		}
		
		//กรณีเลือกบริษัท
		if($("#cus3").is(':checked')){
			//ถ้าไม่ได้เลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
			if($('#list_other') .attr( 'value')!='เลขทะเบียนนิติบุคคล' 
			&& $('#list_other') .attr( 'value')!='เลขที่การค้า' && $('#list_other') .attr( 'value')!='other'){
				alert("กรุณาเลือกประเภทบัตรให้ถูกต้อง");
				return false;
			}
		}
		
		if (document.frm_edit.N_CAPDREF.value=="") {
			alert("   กรุณากรอกหมายเลขบัตร" );         
			document.frm_edit.N_CAPDREF.focus();
			return false;
		}
		if (document.frm_edit.list_other.value=="other") {
			if (document.frm_edit.add_other.value=="") {
				alert("   กรุณากรอกประเภทบัตรอื่นๆ" );         
				document.frm_edit.add_other.focus();
				return false;
			}
		}	
	}else{
		if($("#cus2").is(':checked') || $("#cus3").is(':checked')){
			alert("กรุณาระบุประเภทบัตรอื่นๆ");
			return false;
		}
	}
	
	if (document.frm_edit.hdidcardchk.value=='false') {
		alert("เลขบัตรประชาชนของลูกค้าผิด" );         
		document.frm_edit.f_cardid.focus();
		return false;
	}
	
	if (document.frm_edit.f_no.value=="") {
		alert(" กรุณาใส่ บ้านเลขที่ " );         
		document.frm_edit.f_no.focus();
		return false;
	}
	if (document.frm_edit.f_subno.value=="") {
		if($('#f_subnochk').attr( 'checked')==false){	
			alert("  กรุณาใส่ หมู่ที่ " );         
			document.frm_edit.f_subno.focus();
			return false;
		}	
	}
	if (document.frm_edit.f_soi.value=="") {
		if($('#f_soichk').attr( 'checked')==false){
			alert("  กรุณาใส่ ซอย " );         
			document.frm_edit.f_soi.focus();
			return false;
		}	
	}
	
	if (document.frm_edit.f_rd.value=="") {
		if($('#f_rdchk').attr( 'checked')==false){
			alert("   กรุณาใส่ ถนน " );         
			document.frm_edit.f_rd.focus();
			return false;
		}	
	}
	
	if (document.frm_edit.f_tum.value=="") {
		alert("    กรุณาใส่ แขวง/ตำบล" );         
		document.frm_edit.f_aum.focus();
		return false;
	}
	
	if (document.frm_edit.f_aum.value=="") {
		alert("   กรุณาใส่ เขต/อำเภอ" );         
		document.frm_edit.f_tum.focus();
		return false;
	}
	
	if (document.frm_edit.f_province.value=="") {
		alert("   กรุณาเลือกจังหวัด" );
		return false;
	}
		
	if (document.frm_edit.f_post.value=="") {
		if($('#f_postchk').attr( 'checked')==false){
			alert("   กรุณาใส่ รหัสไปรษณีย์ " );         
			document.frm_edit.f_post.focus();
			return false;
		}	
	}
	
	if (document.frm_edit.f_country.value=="") {
		alert("  กรุณาระบุประเทศ" );         
		return false;
	}
	
	if (document.frm_edit.f_mobile.value=="") {
		if (document.frm_edit.f_telephone.value=="") {
			alert("  กรุณาระบุเบอร์มือถือหรือเบอร์บ้าน" );         
			document.frm_edit.f_telephone.focus();
			return false;
		}
	}
	
	
	if (document.frm_edit.f_extadd.value==0) {
		alert("   กรุณาเลือกที่อยู่ติดต่อ" );         
		document.frm_edit.f_name.focus();
		return false;
	}
	if(document.frm_edit.f_extadd.value==2){
		if (document.frm_edit.f_ext.value=="") {
			alert("   กรุณากรอกที่อยู่" );         
			document.frm_edit.f_ext.focus();
			return false;
		}
	}
	
	
	
	if(document.frm_edit.f_type_vehicle.value==""){
		alert("   กรุณาระบุประเภทรถ" ); 
		return false;	
	}else{
		if(document.frm_edit.f_brand){
			if(document.frm_edit.f_brand.value==""){
				alert("   กรุณาระบุยี่ห้อ" ); 
				return false;	
			}else{
				if(document.frm_edit.f_model.value==""){
					alert("   กรุณาระบุรุ่น" ); 
					return false;					
				}
			}
		}	
	}
	
	

	if (document.frm_edit.f_caryear.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ รุ่นปี" );         
		document.frm_edit.f_caryear.focus();
		return false;
	}else if (document.frm_edit.f_carnum.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ เลขตัวถัง" );         
		document.frm_edit.f_carnum.focus();
		return false;
	}else if (document.frm_edit.f_carmar.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ เลขเครื่องยนต์" );         
		document.frm_edit.f_carmar.focus();
		return false;
	}else if (document.frm_edit.f_carregis.value.replace( /\s+$/, "" )==""){
		alert(" กรุณาใส่  ทะเบียน" );         
		document.frm_edit.f_carregis.focus();
		return false;
	}else if (document.frm_edit.f_carcolor.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ สีรถ" );         
		document.frm_edit.f_carcolor.focus();
		return false;
	}else if (document.frm_edit.f_carmi.value.replace( /\s+$/, "" )==""){
		alert(" กรุณาใส่ เลขไมล์" );         
		document.frm_edit.f_carmi.focus();
		return false;
	}else if (document.frm_edit.f_exp_date.value.replace( /\s+$/, "" )==""){
		alert(" กรุณาใส่ วันต่อภาษี" );         
		document.frm_edit.f_exp_date.focus();
		return false;
	}else if (document.frm_edit.f_letter.value.replace( /\s+$/, "" )==""){
		alert("   กรุณาระบุที่อยู่สัญญา" );         
		document.frm_edit.f_letter.focus();
		return false;
	}
	
	if (document.frm_edit.gas_system.disabled==false){	
		if (document.frm_edit.gas_system.value.replace( /\s+$/, "" )==""){
			alert("   กรุณาระบุระบบแก๊สรถยนต์" );         
			return false;
		}
	}
	if(document.getElementById("package1").checked == true){
		
		if(document.frm_edit.interest1.value==""){
			alert("   กรุณาระบุอัตราดอกเบี้ย (เลือกให้ครบทุกส่วนของ package)" );         
			return false;
		}else if (document.frm_edit.capital.value==""){
			alert("   กรุณาระบุเงินต้นลูกค้า หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         
			return false;
		}else if (document.frm_edit.down_list1.value==""){
			alert("   กรุณาระบุเงินดาวน์ หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         	
			return false;
		}else if (document.frm_edit.time_list.value==""){
			alert("   กรุณาระบุจำนวนงวด หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         	
			return false;
		}else if (document.frm_edit.period_list.value==""){
			alert("   กรุณาระบุค่างวดรวม vat หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         
			return false;
		}else if (tp < p_begin){
			alert("   ยอดจัดน้อยกว่าเงินต้น" );         
			return false;
		} 
	}
	else if(document.getElementById("package2").checked == true){	
		if (document.frm_edit.f_pbegin.value.replace( /\s+$/, "" )==""){
			alert("   กรุณาระบุเงินต้นลูกค้า หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         
				return false;
		}else if (document.frm_edit.f_pdown.value.replace( /\s+$/, "" )==""){
				alert("   กรุณาระบุเงินดาวน์ หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         
				
				return false;
		}else if (document.frm_edit.f_ptotal.value.replace( /\s+$/, "" )==""){
				alert("   กรุณาระบุจำนวนงวด หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         
				
				return false;
		}else if (document.frm_edit.f_pmonth.value.replace( /\s+$/, "" )==""){
				alert("   กรุณาระบุค่างวดรวม vat หากไม่มีให้ใส่เลข 0 ( ศูนย์ )" );         
				
				return false;
		}else if (tp < p_begin){
			alert("   ยอดจัดน้อยกว่าเงินต้น" );         
			return false;
		} 
	
	}
	
	
}	
//-->
function checkID(id) {
	for(i=0, sum=0; i < 12; i++){
		sum += parseFloat(id.charAt(i))*(13-i);
	}
	if((11-sum%11)%10!=parseFloat(id.charAt(12))){
		return false;
	}else{
		return true;
	}
}


</script>

<script type="text/javascript" language="javascript">
function data_change(field){
	var check = true;
	var value = field.value; //get characters
	//check that all characters are digits, ., -, or ""
	for(var i=0;i < field.value.length; ++i)
	{
		var new_key = value.charAt(i); //cycle through characters
		if(((new_key < "0") || (new_key > "9")) && 
			!(new_key == ""))
		{
			check = false;
			break;
		}
	}     
}

<!--
function fn_cus()
{
	var s1=document.frm_edit.fh_adds.value;
	var s2="";
	var tcard="ที่อยู่ตามบัตรประชาชน"
	if(document.frm_edit.f_extadd.value==2)
	{
		//alert("ใช้ที่อยู่ปัจจุบัน");
		document.frm_edit.f_ext.disabled=false;
		document.frm_edit.f_ext.value=s1;
		document.frm_edit.f_ext.focus(); 
	}
	else if(document.frm_edit.f_extadd.value==1)
	{
		document.frm_edit.f_ext.disabled=true;
		document.frm_edit.f_ext.value=tcard;
	}
	else if(document.frm_edit.f_extadd.value==0)
	{
		document.frm_edit.f_ext.disabled=true;
		document.frm_edit.f_ext.value=s1;
	}
}
function calbrith(){
	var byear = new Date(document.frm_edit.f_brithday.value);
    var current = new Date();
    var age = current.getFullYear() - byear.getFullYear();
	var m = current.getMonth() - byear.getMonth();
	if (m < 0 || (m === 0 && current.getDate() < byear.getDate())) {
        age--;
    }
	document.frm_edit.f_age.value = age;
	
}
//-->



</script>


<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #FF0000;
	font-weight: bold;
}
-->

#warppage
{
	width:800px;
	margin-left:auto;
	margin-right:auto;
		
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
}
</style>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
	</div>
	<!-- InstanceBeginEditable name="EditRegion3" -->
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div style="padding-left:10px;"><b>แก้ไขสัญญาเช่าซื้อ <?php echo  $edt_idno; ?></b></div>
		<div class="style5" style="width:auto; height:30px; padding-left:10px;">
			<?php 
			$qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$edt_idno' ");
			$res_fp=pg_fetch_array($qry_fp);
			$num_r=pg_num_rows($qry_fp);
			if($num_r==1)
			{
				if($res_fp["LockContact"]=='t')
				{
					echo "LOCKED "."<br>";
					//echo $resback="<input type=\"button\" value=\"BACK\" onclick=\"window.location='frm_av_editidno.php'\"  />";
					$readonly="readonly";
					$disabled="disabled";
				}
				else 
				{
					$readonly="";
					$disabled="";
				}
					$fp_cusid=trim($res_fp["CusID"]);
					$fp_carid=trim($res_fp["asset_id"]);
					$fp_stdate=$res_fp["P_STDATE"];
					$fp_pmonth=$res_fp["P_MONTH"];   
					$fp_pvat=$res_fp["P_VAT"];
					$fp_ptotal=$res_fp["P_TOTAL"];
					$fp_pdown=$res_fp["P_DOWN"];
					$fp_pvatofdown=$res_fp["P_VatOfDown"];
					$fp_begin=$res_fp["P_BEGIN"];
					$fp_beginx=$res_fp["P_BEGINX"];
					$fp_fdate=$res_fp["P_FDATE"];	
					$fp_cusby_year=$res_fp["P_CustByYear"];
					$fp_creditType=trim($res_fp["creditType"]);
   
					?>
		</div>
		<div class="style5" style="width:auto;  padding-left:10px;">
			<form name="frm_edit" method="post" action="edit_cus.php" >
				<input type="hidden" name="fidno" value="<?php echo $edt_idno; ?>" />
				<input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
				<input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
	
				<table width="785" border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td colspan="6" style="background-color:#FFFFCC;">แก้ไขข้อมูลผู้ทำสัญญา </td>
					<?php
					  $qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$fp_cusid' ");
					  $res_fa1=pg_fetch_array($qry_fa1);
					  $fa1_cusid=trim($res_fa1["CusID"]);
					  $fa1_firname=trim($res_fa1["A_FIRNAME"]);
					  $fa1_name=trim($res_fa1["A_NAME"]);
					  $fa1_surname=trim($res_fa1["A_SIRNAME"]);
					  $fa1_pair=trim($res_fa1["A_PAIR"]);
					  $fa1_no=trim($res_fa1["A_NO"]);
					  $fa1_subno=trim($res_fa1["A_SUBNO"]);
					  $fa1_soi=trim($res_fa1["A_SOI"]);
					  $fa1_rd=trim($res_fa1["A_RD"]);	
					  $fa1_tum=trim($res_fa1["A_TUM"]);	
					  $fa1_aum=trim($res_fa1["A_AUM"]);
					  $fa1_pro=trim($res_fa1["A_PRO"]);	
					  $fa1_post=trim($res_fa1["A_POST"]);
					  $fa1_approved=trim($res_fa1["Approved"]); 
					  
						$fa1_firname_eng=trim($res_fa1["A_FIRNAME_ENG"]);
						$fa1_name_eng=trim($res_fa1["A_NAME_ENG"]);
						$fa1_surname_eng=trim($res_fa1["A_SIRNAME_ENG"]);
						$fa1_nickname=trim($res_fa1["A_NICKNAME"]);
						$fa1_status=trim($res_fa1["A_STATUS"]);
						$fa1_revenue=trim($res_fa1["A_REVENUE"]);
						$fa1_education=trim($res_fa1["A_EDUCATION"]);
						$fa1_country=trim($res_fa1["addr_country"]);
						$fa1_mobile=trim($res_fa1["A_MOBILE"]);
						$fa1_telephone=trim($res_fa1["A_TELEPHONE"]);
						$fa1_email=trim($res_fa1["A_EMAIL"]);
						$fa1_birthday=trim($res_fa1["A_BIRTHDAY"]);
						
					$fa1_A_SEX=trim($res_fa1["A_SEX"]);
					$fa1_A_ROOM=trim($res_fa1["A_ROOM"]);
					$fa1_A_FLOOR=trim($res_fa1["A_FLOOR"]);
					$fa1_A_BUILDING=trim($res_fa1["A_BUILDING"]);
					$fa1_A_VILLAGE=trim($res_fa1["A_VILLAGE"]);
					  
					  $qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$fp_cusid' ");
					  $res_fn1=pg_fetch_array($qry_Fn);
					  
					  //$fa1_occ=$res_fn1["N_OCC"];
					  $ext_addr=$res_fn1["N_ContactAdd"];
					  
						$N_SAN=$res_fn1["N_SAN"];
						$N_AGE=$res_fn1["N_AGE"];
						$N_CARD=trim($res_fn1["N_CARD"]);
						$N_IDCARD=$res_fn1["N_IDCARD"];
						$N_OT_DATE=$res_fn1["N_OT_DATE"];
						$N_BY=$res_fn1["N_BY"];
						$N_OCC=$res_fn1["N_OCC"];
						$N_CARDREF=$res_fn1["N_CARDREF"];
						$statuscus=$res_fn1["statuscus"];  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
					  
					  if($fa1_approved=="t")
					  {
						$dis_cusid="readonly=true";
					  }
					  else
					  {
					   $dis_cusid="";
					  }
					  
					?>
				</tr>
				<tr>
					<td colspan="6">
<table width="785" border="0" cellpadding="1" cellspacing="1">
	<tr>
		<td colspan="6">
			<input type="radio" name="statuscus" id="cus1" value="0" <?php if($statuscus==0){ echo "checked"; } ?>> คนไทย
			<input type="radio" name="statuscus" id="cus2" value="1" <?php if($statuscus==1){ echo "checked"; } ?>> ชาวต่างชาติ
			<input type="radio" name="statuscus" id="cus3" value="2" <?php if($statuscus==2){ echo "checked"; } ?>> บริษัท
		</td>
	</tr>
	<tr>
			<td>คำนำหน้าชื่อ (ไทย)</td>
			<td><input type="text" name="f_fri_name" id="f_fri_name" value="<?php echo $fa1_firname; ?>" <?php echo $readonly;?>/></td>
			<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_fri_name_eng" value="<?php echo $fa1_firname_eng; ?>" <?php echo $readonly;?>/></td>
		</tr>
		<tr>
			<td width="144">ชื่อ(ไทย)</td>
			<td width="227"><input type="text" name="f_name" value="<?php echo $fa1_name; ?>" <?php echo $readonly;?> onkeyup="passrq(this);" /><font color="red">*</font></td>
			<td width="90">ชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_name_eng" value="<?php echo $fa1_name_eng; ?>" <?php echo $readonly;?>/></td>
		</tr>
		<tr>
			<td>นามสกุล (ไทย)</td>
			<td><input type="text" name="f_surname" value="<?php echo $fa1_surname; ?>" <?php echo $readonly;?> onkeyup="passrq(this);"/><font color="red">*</font></td>
			<td>นามสกุล (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_surname_eng" value="<?php echo $fa1_surname_eng; ?>" <?php echo $readonly;?>/>
			เพศ 
				<select name="A_SEX" <?php echo $disabled;?>>
					<option value="" <?php if(trim($fa1_A_SEX)=="") echo "selected"; ?>>ไม่ระบุ</option>
					<option value="1" <?php if(trim($fa1_A_SEX)=="1") echo "selected"; ?>>หญิง</option>
					<option value="2" <?php if(trim($fa1_A_SEX)=="2") echo "selected"; ?>>ชาย</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>ชื่อเล่น</td>
			<td><input type="text" name="f_nickname" value="<?php echo $fa1_nickname; ?>" <?php echo $readonly;?>/></td>
			<td width="90">วันเกิด</td>
			<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" size="10" value="<?php echo $fa1_birthday; ?>" onchange="calbrith();passrq(this);" onkeyup="passrq(this);" size="15" <?php echo $readonly;?>/><font color="red">*</font>
			อายุ
			<input type="text" name="f_age" id="f_age" value="<?php echo $N_AGE; ?>" onfocus="calbrith();" Readonly="true" size="5" <?php echo $readonly;?>/> ปี</td>		</tr>
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227">
			<?php
			//กำหนดตัวแปรสัญชาติเป็น $f_san เพื่อนำไปใช้ใน select_nationality.php
			$f_san=$N_SAN;
			include "select_nationality.php";
			?><font color="red">*</font>
			</td>
			<td width="90">ระดับการศึกษา</td>
			<td colspan="3">
				<select name="f_education" <?php echo $disabled;?>>
					<option value="" <?php if($fa1_education==""){ echo "selected"; }?>>---เลือก---</option>
					<option value="1" <?php if($fa1_education=="1"){ echo "selected"; }?>>ต่ำกว่ามัธยมศึกษาตอนต้น</option>
					<option value="2" <?php if($fa1_education=="2"){ echo "selected"; }?>>มัธยมศึกษาตอนต้น</option>
					<option value="3" <?php if($fa1_education=="3"){ echo "selected"; }?>>มัธยมศึกษาตอนปลาย</option>
					<option value="4" <?php if($fa1_education=="4"){ echo "selected"; }?>>ปวช.</option>
					<option value="5" <?php if($fa1_education=="5"){ echo "selected"; }?>>ปวส.</option>
					<option value="6" <?php if($fa1_education=="6"){ echo "selected"; }?>>อนุปริญญา</option>
					<option value="7" <?php if($fa1_education=="7"){ echo "selected"; }?>>ปริญญาตรี</option>
					<option value="8" <?php if($fa1_education=="8"){ echo "selected"; }?>>ปริญญาโท</option>
					<option value="9" <?php if($fa1_education=="9"){ echo "selected"; }?>>ปริญญาเอก</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="144">รายได้ต่อเดือนประมาณ</td>
			<td width="227"><input type="text" name="f_revenue" id="f_revenue" value="<?php echo $fa1_revenue; ?>" onkeypress="return check_number(event);" <?php echo $readonly;?>/></td>
			<td width="90">สถานภาพ</td>
			<td colspan="3">
				<select name="f_status" <?php echo $disabled;?> onchange="passrq(this);">
					<option value="" <?php if($fa1_status==""){ echo "selected"; }?>>---เลือก---</option>
					<option value="0002" <?php if($fa1_status=="0002"){ echo "selected"; }?>>โสด</option>
					<option value="0001" <?php if($fa1_status=="0001"){ echo "selected"; }?>>สมรส</option>
					<option value="0005" <?php if($fa1_status=="0005"){ echo "selected"; }?>>สมรสไม่จดทะเบียน</option>
					<option value="0004" <?php if($fa1_status=="0004"){ echo "selected"; }?>>หย่า</option>
					<option value="0003" <?php if($fa1_status=="0003"){ echo "selected"; }?>>หม้าย</option>
					<option value="0000" <?php if($fa1_status=="0000"){ echo "selected"; }?>>ไม่ระบุ</option>
				</select>
				<font color="red">*</font>
			</td>
		</tr>
		<tr>
			<td width="144">ชื่อ คู่สมรส</td>
			<td width="227"><input type="text" name="f_pair" value="<?php echo $fa1_pair; ?>" <?php echo $readonly;?>/></td>
			<td width="90">อาชีพ</td>
			<td colspan="3"><input type="text" name="f_occ" value="<?php echo $N_OCC; ?>" <?php echo $readonly;?>/></td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
	<?php 
		if($statuscus!=1 and $statuscus!=2){
			$dig1 = substr($N_IDCARD,0,1)*13;
			$dig2 = substr($N_IDCARD,1,1)*12;
			$dig3 = substr($N_IDCARD,2,1)*11;
			$dig4 = substr($N_IDCARD,3,1)*10;
			$dig5 = substr($N_IDCARD,4,1)*9;
			$dig6 = substr($N_IDCARD,5,1)*8;
			$dig7 = substr($N_IDCARD,6,1)*7;
			$dig8 = substr($N_IDCARD,7,1)*6;
			$dig9 = substr($N_IDCARD,8,1)*5;
			$dig10 = substr($N_IDCARD,9,1)*4;
			$dig11 = substr($N_IDCARD,10,1)*3;
			$dig12 = substr($N_IDCARD,11,1)*2;
			$dig13 = substr($N_IDCARD,12,1);
			$digcheck1 = ($dig1+$dig2+$dig3+$dig4+$dig5+$dig6+$dig7+$dig8+$dig9+$dig10+$dig11+$dig12)%11;
			$digcheck2 = 11-$digcheck1;
			$digcheck3 = (String)$digcheck2;
			$checknum = strlen($digcheck3);
			if($checknum == 2){
				$dig14 = substr($digcheck3,1,2);
			}else{
				
				$dig14 = $digcheck3;
			}
			
			if($dig14 == $dig13){
				$indencard = 'true';
			}else{
				$indencard = 'false';
			}
		}

	?>	
		
		<input type="hidden" name="hdidcardchk" id="hdidcardchk" value="<?php echo $indencard; ?>">
		<tr>
			<td width="144">เลขที่บัตรประชาชน</td>
			<td width="227">
			<input type="text" <?php if($indencard=='true'){ ?> style="background-color:#66FF66" <?php }else{ ?> style="background-color:#FF3030" <?php } ?> name="f_cardid" id="f_cardid" value="<?php echo str_replace(" ","",$N_IDCARD); ?>" <?php if($N_IDCARD != null){ echo "readOnly"; } ?> maxlength="13" <?php echo $readonly;?>><font color="red">*</font>
			<?php if($indencard == 'false'){ ?> <a id="showedit" href="javascript:popU('../nw/manageCustomer/Re_indentify/frm_reiden.php?cusidd=<?php echo $fp_cusid; ?>')"><u><font color="red">แก้ไขเลขบัตรประจำตัวประชาชน</font></u></a> <?php } ?>
			</td>
		<?php if($N_CARD == "บัตรประชาชน"){ ?>
			<td width="90">บัตรแสดงตัว</td>
			<td colspan="3">
			<input type="text" name="f_card" value="<?php echo $N_CARD; ?>" readonly="true" onkeyup="passrq(this);" />		
		
			<font color="red">*</font></td>
		<?php } ?>		
		</tr>
		<tr>
			<td width="144">วันที่ออกบัตร</td>
			<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_OT_DATE; ?>" <?php echo $readonly;?>/><input name="button_otdate" type="button" onclick="displayCalendar(document.frm_edit.f_datecard,'yyyy-mm-dd',this)" value="ปฏิทิน" <?php echo $disabled;?>/><font color="red">*</font>
</td>
			<td width="90">ออกให้โดย</td>
			<td colspan="3"><input type="text" name="f_card_by" value="<?php echo $N_BY; ?>" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>
		</tr>
		<tr bgcolor="#E1E1E1">
			<td width="144">บัตรประเภทอื่นๆ</td>
			<td width="227" colspan="6"><input type="checkbox" name="chk_other" id="chk_other" value="1" <?php if($N_CARD != "บัตรประชาชน"){ echo "checked"; } ?> <?php echo $disabled;?>/></td>			
		</tr>
		
		<tr bgcolor="#E1E1E1">
		<td colspan="7">
		<table name="tb_other" id="tb_other" width="700" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="145" >ประเภท</td>
			<td width="230">
			<select name="list_other" id="list_other" onchange="hsother()" <?php echo $disabled;?>>
						<option value="บัตรข้าราชการ" <?php if($N_CARD=="บัตรข้าราชการ"){ echo "selected";} ?>>บัตรข้าราชการ</option>
						<option value="เลขทะเบียนนิติบุคคล" <?php if($N_CARD=="เลขทะเบียนนิติบุคคล"){ echo "selected";} ?>>เลขทะเบียนนิติบุคคล</option>
						<option value="เลขที่การค้า" <?php if($N_CARD=="เลขที่การค้า"){ echo "selected";} ?>>เลขที่การค้า</option>
						<option value="บัตรต่างด้าว" <?php if($N_CARD=="บัตรต่างด้าว"){ echo "selected";} ?>>บัตรต่างด้าว</option>
						<option value="หนังสือเดินทาง(ต่างประเทศ)" <?php if($N_CARD=="หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected";} ?>>หนังสือเดินทาง(ต่างประเทศ)</option>
						<option <?php if($N_CARD != "บัตรข้าราชการ" and $N_CARD != "เลขทะเบียนนิติบุคคล"  and $N_CARD != "เลขที่การค้า" and $N_CARD != "บัตรต่างด้าว" and $N_CARD != "หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected"; } ?> value="other">อื่นๆ</option>
					</select>
			</td>	
			<td width="155">หมายเลขบัตรอื่น</td>
			<td width=""><input type="text" name="N_CAPDREF" id="N_CAPDREF" value="<?php echo $N_CARDREF; ?>" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>	
		</tr>
<?php if($N_CARD != "บัตรข้าราชการ" and $N_CARD != "เลขทะเบียนนิติบุคคล"  and $N_CARD != "เลขที่การค้า" and $N_CARD != "บัตรต่างด้าว" and $N_CARD != "หนังสือเดินทาง(ต่างประเทศ)"){ ?>	
		<tr name="othertype" id="othertype">
			<td>บัตรอื่นๆ</td>
			<td><input type="text" name="add_other" id="add_other" value="<?php echo $N_CARD; ?>" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>	
		</tr>
<?php } ?>	
		</table>
		</td>
		</tr>	
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td>เลขที่</td>
			<td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>
			<td>หมู่ที่</td>
			<td colspan="3"><input type="text" name="f_subno" onkeyup="passrq(this);" <?php if(trim($fa1_subno) == ""){ echo "disabled";} ?> value="<?php echo $fa1_subno; ?>" <?php echo $readonly;?>/><font color="red">*</font>
			<input type="checkbox" id="f_subnochk" <?php if(trim($fa1_subno) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_edit.f_subno.disabled=true;document.frm_edit.f_subno.value='';document.frm_edit.f_subno.style.backgroundColor='';}else{document.frm_edit.f_subno.disabled=false;document.frm_edit.f_subno.style.backgroundColor='#FFCCCC';}" <?php echo $disabled;?>>ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30" value="<?php echo $fa1_A_ROOM; ?>" <?php echo $readonly;?>></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo $fa1_A_FLOOR; ?> "<?php echo $readonly;?>>
			</td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30" value="<?php echo $fa1_A_BUILDING; ?>" <?php echo $readonly;?>></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo $fa1_A_VILLAGE; ?>" <?php echo $readonly;?>>
			</td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td><input type="text" name="f_soi" onkeyup="passrq(this);" <?php if(trim($fa1_soi) == ""){ echo "disabled";} ?> value="<?php echo $fa1_soi; ?>" <?php echo $readonly;?>/><font color="red">*</font>
			<input type="checkbox" id="f_soichk" <?php if(trim($fa1_soi) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_edit.f_soi.disabled=true;document.frm_edit.f_soi.value='';document.frm_edit.f_soi.style.backgroundColor='';}else{document.frm_edit.f_soi.disabled=false;document.frm_edit.f_soi.style.backgroundColor='#FFCCCC';}" <?php echo $disabled;?>>ไม่มี
			</td>
			<td>ถนน</td>
			<td colspan="3"><input type="text" onkeyup="passrq(this);" <?php if(trim($fa1_rd) == ""){ echo "disabled";} ?> name="f_rd" value="<?php echo $fa1_rd; ?>" <?php echo $readonly;?>/><font color="red">*</font>
			<input type="checkbox" id="f_rdchk" <?php if(trim($fa1_rd) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_edit.f_rd.disabled=true;document.frm_edit.f_rd.value='';document.frm_edit.f_rd.style.backgroundColor='';}else{document.frm_edit.f_rd.disabled=false;document.frm_edit.f_rd.style.backgroundColor='#FFCCCC';}" <?php echo $disabled;?>>ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" onkeyup="passrq(this);" value="<?php echo $fa1_tum; ?>" <?php echo $readonly;?>/><font color="red">*</font></td>
			<td>เขต/อำเภอ</td>
			<td colspan="3"><input type="text" onkeyup="passrq(this);" name="f_aum" value="<?php echo $fa1_aum; ?>" <?php echo $readonly;?>/><font color="red">*</font></td>
		</tr>
		<tr>
			<td>จังหวัด</td>
			<td>	
				<select name="f_province" size="1" onchange="passrq(this);" <?php echo $disabled;?>>
				<?php
				if($fa1_pro=="" || $fa1_pro=="---เลือก---"){
					echo "<option value=\"\">---เลือก---</option>";
				}else{
					echo "<option value=\"\"></option>";
				}
				$query_province=pg_query("select * from \"nw_province\" order by \"proID\"");
				while($res_pro = pg_fetch_array($query_province)){
				?>
					<option value="<?php echo $res_pro["proName"];?>" <?php if($fa1_pro == $res_pro["proName"]){ echo "selected";} ?>><?php echo $res_pro["proName"];?></option>
				<?php
				}
				?>
				</select><font color="red">*</font>
			</td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3"><input type="text" onkeyup="passrq(this);" name="f_post" <?php if(trim($fa1_post) == ""){ echo "disabled";} ?> value="<?php echo $fa1_post; ?>" maxlength="5" <?php echo $readonly;?>/><font color="red">*</font>
			<input type="checkbox" id="f_postchk"  <?php if(trim($fa1_post) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_edit.f_post.disabled=true;document.frm_edit.f_post.value='';document.frm_edit.f_post.style.backgroundColor='';}else{document.frm_edit.f_post.disabled=false;document.frm_edit.f_post.style.backgroundColor='#FFCCCC';}" <?php echo $disabled;?>>ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ประเทศ</td>
			<td>
				<select name="f_country" size="1" <?php echo $disabled;?> onchange="passrq(this);">
					<option value="">----เลือก----</option>
					<?php
					$query_country=pg_query("select \"CountryCode\",\"CountryName_THAI\" from \"Country_Code\" where \"Status\" = 'TRUE'");
					while($res_country = pg_fetch_array($query_country)){
					?>
					<option value="<?php echo $res_country["CountryCode"];?>" <?php if($fa1_country==""){ if($res_country["CountryCode"]=="TH"){ echo "selected"; } }else if($res_country["CountryCode"]==$fa1_country){ echo "selected"; }?>><?php echo $res_country["CountryName_THAI"];?></option>
					<?php
					}
					?>
				</select><font color="red">*</font>
			</td>
			</td>
			<td>โทรศัพท์มือถือ</td>
			<td colspan="3"><input type="text" name="f_mobile" value="<?php echo $fa1_mobile; ?>" <?php echo $readonly;?>/></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" value="<?php echo $fa1_telephone; ?>" <?php echo $readonly;?>/></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo $fa1_email ?>" size="30" <?php echo $readonly;?>/></td>
		</tr>
		<tr>
			<td>ที่อยู่ใช้ติดต่อ</td>
			<td>
				<select name="f_extadd" onchange="fn_cus();passrq(this);" <?php echo $disabled;?>>
					<option value="0">กรุณาเลือกที่ติดต่อ</option>
					<option value="1">ใช้ที่อยู่ตามบัตรประชาชน</option>
					<option value="2">ใช้ที่อยู่ปัจุบัน</option>
				</select><font color="red">*</font>
			</td>
			<td colspan="4" valign="top" rowspan="2">
			
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<input type="hidden" name="fh_adds" value="<?php echo $ext_addr; ?>" /><textarea name="f_ext" cols="50" rows="5" disabled ><?php echo $ext_addr; ?></textarea>
			</td>
		</tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <?php	
  $qry_cc=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$edt_idno' ");
  $res_cc=pg_fetch_array($qry_cc);
  $residno_cc=$res_cc["IDNO"];
   if(empty($residno_cc))
    {
  ?>
   <?php
    }
	else
	{
	 $qry_fn=pg_query("select A.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",C.\"CusID\" 
	                   from \"ContactCus\" A
                       LEFT OUTER JOIN \"Fa1\" C on C.\"CusID\" = A.\"CusID\" 
					   where A.\"IDNO\"='$edt_idno' AND \"CusState\"!=0 order by \"CusState\" ");
					      
      while($res_fn=pg_fetch_assoc($qry_fn))
      {
	   $fullname=trim($res_fn["A_FIRNAME"])." ".trim($res_fn["A_NAME"])." ".trim($res_fn["A_SIRNAME"]);
	   $a++;                 
   ?>
	<?php
	}
   ?>
   <?php
     }
   ?>
  
</table>
					</td>
				</tr>
				
				<tr>
					<td valign="top"></td>
					<td valign="top"></td>
					<td colspan="4" height="30" valign="top"><b>ที่อยู่ในสัญญา</b>
					<select name="addidno" id="addidno" <?php echo $disabled;?>>
						<?php
							//ดึงข้อมูลเลขที่สัญญาใน Fp_Fa1 มาแสดง
							$qry_lt=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$edt_idno'  and \"edittime\"='0' and \"CusState\"='0'");
							$numr_lt=pg_num_rows($qry_lt);
							if($numr_lt>0){
								$addidno=3;
							}					
						if($addidno=="3"){
						?>
						<option value="3" id="addnow" <?php if($addidno=="3") echo "select";?>>ใช้ที่อยู่ในสัญญา</option>
						<?php }?>
						<option value="1" <?php if($addidno=="") echo "select";?>>ใช้ที่อยู่ผู้ทำสัญญา</option>
						<option value="2">ใช้ที่อยู่ใหม่</option>		
					</select>
					<input type="button" name="btnaddnew" id="btnaddnew" value="ระบุที่อยู่ใหม่" onclick="javascript:popU('frm_Idnonew.php?IDNO=<?php echo $edt_idno?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')">
					</td>
				</tr>
				<tr>
					<td valign="top">รายละเอียดสัญญา</td>
					<?php
					 $qry_contactnote=pg_query($db_connect,"select * from \"Fp_Note\" where (\"IDNO\"='$edt_idno') ");
					 $numr_contactnote=pg_num_rows($qry_contactnote);
					 if($numr_contactnote == 0){
						$add_con = "กรุณากรอกรายละเอียดสัญญา";
					 }else{
						$res_contact = pg_fetch_array($qry_contactnote);
						$add_con = $res_contact["ContactNote"];
					 }
					?>
					<td><textarea name="contactnote" cols="40"  rows="5" <?php echo $readonly;?>><?php echo $add_con;?></textarea></td>
					<td colspan="4">
						<textarea name="f_letter" id="f_letter" cols="50"  rows="5" readonly="true"><?php echo $add_lt; ?></textarea>
					</td>
				</tr>
				<tr height="40">
					<td>ค่าแนะนำ</td>
					<td colspan="5">
						<?php
							$qryguide=pg_query("SELECT \"GuidePeople\" FROM \"nw_IDNOGuidePeople\" where \"IDNO\"='$edt_idno'");
							$numguide=pg_num_rows($qryguide);
							if($numguide>0){
								$resguide=pg_fetch_array($qryguide);
								list($GuidePeople)=$resguide;
								echo "มีค่าแนะนำ ผู้แนะนำคือ $GuidePeople";
							}else{
								echo "สัญญานี้ไม่มีค่าแนะนำ";
							}
						?>
					</td>
				</tr>
				<tr><td colspan="6"  style="background-color:#FFFFCC;">ขัอมูลผู้ค้ำประกัน</td></tr>
					<?php	
						$qry_cc=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$edt_idno' ");
						$num_cc=pg_num_rows($qry_cc);
						$res_cc=pg_fetch_array($qry_cc);
						$residno_cc=$res_cc["IDNO"];
						
						if(empty($residno_cc)){
						?>	
							<tr>
								<td colspan="6">
									<div align="left" class="style6">** ยังไม่มีข้อมูลผู้ค้ำประกัน 
										<input type="button" value="เพิ่มคนค้ำประกัน" onclick="parent.location='frm_select_contactcus.php?fIDNO=<?php echo $edt_idno; ?>&num_cc=<?php echo $num_cc;?>' " tabindex="21" <?php echo $disabled;?>/>
									</div>
								</td>
							</tr>
						<?php
						}else{
							$qry_fn=pg_query("select A.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",C.\"CusID\",a.\"CusState\"
							from \"ContactCus\" A
							LEFT OUTER JOIN \"Fa1\" C on C.\"CusID\" = A.\"CusID\" 
							where A.\"IDNO\"='$edt_idno' AND \"CusState\"!=0 order by \"CusState\" ");
					      
							while($res_fn=pg_fetch_assoc($qry_fn)){
								$fullname=trim($res_fn["A_FIRNAME"])." ".trim($res_fn["A_NAME"])." ".trim($res_fn["A_SIRNAME"]);
								$cusIDCo = trim($res_fn["CusID"]);
								$CusState = trim($res_fn["CusState"]);
								$a++;                 
								?>	
							   <tr>
									<td><?php echo $CusState; ?></td>
									<td colspan="3"><?php echo "$fullname"; ?></td>
									<td width="90">
									<?php
									if($res_fp["LockContact"]=='f'){
									?>
									<a href="frm_edit_contactcus.php?cusID=<?php echo $cusIDCo; ?>&vidno=<?php echo trim($edt_idno); ?>&CusState=<?php echo $CusState;?>">แก้ไข</a>
									<?php
									}
									?>
									</td>
									<td width="91">&nbsp;</td>
							   </tr>
							<?php
							}
						   ?>
							<tr>
								<td>&nbsp;</td>
								<td colspan="3"><input type="button" value="เพิ่มคนค้ำประกัน" onclick="parent.location='frm_select_contactcus.php?fIDNO=<?php echo $edt_idno; ?>&num_cc=<?php echo $num_cc;?>' " tabindex="22" <?php echo $disabled;?>/></td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="3">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td colspan="6" style="background-color:#FFFFCC;">แก้ไขข้อมูลรถแท็กซี่</td>
							<?php 
								$qry_car=pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$edt_idno' ");
								$res_fc=pg_fetch_array($qry_car);
								$fc_carid=trim($res_fc["CarID"]);
								$fc_name=trim($res_fc["C_CARNAME"]);
								$fc_year=trim($res_fc["C_YEAR"]);
								$fc_regis=trim($res_fc["C_REGIS"]);
								$fc_radio=trim($res_fc["RadioID"]);
								$fc_cartype=trim($res_fc["CarType"]);
								if($fc_cartype==1)
								{
									$st_type="แท็กซี่บริษัท";
								}
								else if($fc_cartype==2)
								{
									$st_type="แท็กซี่เขียวเหลือง";
								}
								elseif($fc_cartype==3)
								{
									$st_type="แท็กซี่สีอื่น ๆ";
								}
								elseif($fc_cartype==4)
								{
									$st_type="รถจักรยานยนต์";
								}
								elseif($fc_cartype=="0")
								{
									$st_type="ศูนย์รถยนต์ทั่วไป";
								}
								else{
									$fc_cartype="";
									$st_type="----เลือก----";
								}
							 
								$fcs_regis_by=trim($res_fc["C_REGIS_BY"]);
								if(empty($fcs_regis_by))
								{
									$fc_regis_by="เลือก";
									$reg_value=" ";
								}
								else
								{
									$fc_regis_by=$fcs_regis_by;
									$reg_value=$fcs_regis_by;
								}
							 	 
								$fc_color=trim($res_fc["C_COLOR"]);
								$fc_num=trim($res_fc["C_CARNUM"]);
								$fc_mar=trim($res_fc["C_MARNUM"]);
								$fc_mi=trim($res_fc["C_Milage"]);
							 
								$fc_expert=trim($res_fc["exp_date"]);
							 		 
								$fc_mon=trim($res_fc["C_TAX_MON"]);	
								
								$fp_fc_type = $res_fc["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
								$fp_fc_brand = $res_fc["fc_brand"]; //ยี่ห้อ
								$fp_fc_model = $res_fc["fc_model"]; //รุ่น
								$fp_fc_category = $res_fc["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง รถรับจ้าง หรือ เอนกประสงค์
								$fp_fc_newcar = $res_fc["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	
								$fp_fc_gas = $res_fc["fc_gas"]; //ระบบแก๊ส		
							?>
						</tr>
							<tr>
								<td >ประเภทรถ </td>
								<td >
									<select name="f_type_vehicle" id="f_type_vehicle" onchange="show_brand_func();lockcat(this);passrq(this);">
										<?php 	$qry_sel_astype = pg_query("select \"astypeID\",\"astypeName\" from \"thcap_asset_biz_astype\" where \"astypeName\" LIKE 'รถ%'  AND \"astypeStatus\" = '1'");
												echo "<option value=\"\" >- เลือกประเภทรถ -</option>";
												while($re_sel_astype = pg_fetch_array($qry_sel_astype)){
													$astype_astypeID = $re_sel_astype["astypeID"];
													$astype_astypeName = $re_sel_astype["astypeName"];	
													if($astype_astypeID == $fp_fc_type){ $selected = "selected"; }else{ $selected = ""; }
													echo "<option value=\"$astype_astypeID\" $selected >$astype_astypeName</option>";
												}
										?>	
										<?php 
												$qry_sel_astype = pg_query("select \"astypeID\" from \"thcap_asset_biz_astype\" where \"astypeName\" = 'รถจักรยานยนต์'  AND \"astypeStatus\" = '1'"); 
												list($motercycle) = pg_fetch_array($qry_sel_astype);
												
												echo "<input type=\"hidden\" name=\"chk_mocy\" value=\"$motercycle\">";				
										?>										
										
									</select><font color="red">*</font>
								</td>
								<td>&nbsp;</td>
							</tr>
							<?php 
									IF($fp_fc_brand == ""){
										echo "<tr>
													<td ></td>
													<td colspan=\"2\"><font color=\"gray\">ยี่ห้อ\รุ่นเดิม : <b>$fc_name</b></font></td>
												</tr>";
									}
							
							?>
							<tr id="tr_show_brand" >
								<td >ยี่ห้อ </td>
								<td colspan="2"><span id="show_brand"></td>
							</tr>							
							<tr id="tr_show_model">
								<td >รุ่น </td>
								<td colspan="2"><span id="show_model"></td></td>
								
							</tr>
  
						<tr>
							<td height="30">รูปแบบรถ</td>
							<td>
								<select name="f_cartype" tabindex="24" <?php echo $disabled;?> onchange="passrq(this);">
									<option value="<?php echo $fc_cartype; ?>"><?php echo $st_type; ?></option> 
									<option value="1">แท็กซี่บริษัท</option>
									<option value="2">แท็กซี่เขียวเหลือง</option>
									<option value="3">แท็กซี่สีอื่น ๆ</option>
									<option value="4">รถจักรยานยนต์</option>
									<option value="0">ศูนย์รถยนต์ทั่วไป</option>
								</select>
							</td>
							<td colspan="4">&nbsp;</td>
						</tr>
  
						<tr>
							<td>รุ่นปี</td>
							<td><input type="text" name="f_caryear" value="<?php echo $fc_year; ?>" onkeyup="passrq(this);" tabindex="25" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td>เลขตัวถัง</td>
							<td><input size="30" type="text" name="f_carnum" value="<?php echo $fc_num; ?>" onkeyup="passrq(this);" tabindex="26" readonly /><input type="button" value="แก้ไขเลขตัวถัง" onclick="javascript:popU('../nw/editcarnum/frm_Index.php?idno=<?php echo $edt_idno?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=600')"></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td>เลขเครื่องยนต์</td>
							<td><input type="text" name="f_carmar" value="<?php echo $fc_mar; ?>" tabindex="27" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="4">&nbsp;</td>
						</tr>
					  
						<tr>
							<td height="30">ทะเบียน</td>
							<td><input type="text" name="f_carregis" value="<?php echo $fc_regis; ?>" tabindex="28" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="4">&nbsp;</td>
						</tr>
  
						<tr>
							<td>จังหวัดที่จดทะเบียน</td>
							<td>
								<select name="f_pprovince" size="1" <?php echo $disabled;?>>
									<?php
									if($reg_value==""){
										echo "<option>---เลือก---</option>";
									}else{
										echo "<option value=$reg_value>$fc_regis_by</option>";
									}
									$query_province=pg_query("select * from \"nw_province\" where \"proName\" != '$reg_value' order by \"proID\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
									<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$reg_value){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
								</select>	
							</td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td>สี</td>
							<td>
							<select name="f_carcolor" id="f_carcolor" >
								<?php
									$qry_carcolor = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '3'");
									while($objResuut = pg_fetch_array($qry_carcolor)){
										$carcolorname = trim($objResuut["elementsName"]);
										if($carcolorname == $fc_color){ $selected = "selected"; }else{ $selected = ""; }
										echo "<option value=\"$carcolorname\" $selected>$carcolorname</option>";
									}?>
									<font color="red">*</font>
								</select>
							<a onclick="javascript:popU('frm_addColor.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')" style="cursor:pointer;"><font color="#0000FF"><input name="add" type="button" id="add" value="เพิ่มสี" /></font></a>
							</td>
							<td colspan="4">&nbsp;</td>
						</tr>
							<tr>
								<td>ชนิดรถ </td>
								<td>
								<select name="f_useful_vehicle" id="f_useful_vehicle">
									<?php
									$qry_useful_vehicle = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '2'");
									while($objResuut = pg_fetch_array($qry_useful_vehicle)){
										$useful_vehiclename = trim($objResuut["elementsName"]);
										if($useful_vehiclename == $fp_fc_category){ $selected = "selected"; }else{ $selected = ""; }
										echo "<option value=\"$useful_vehiclename\" $selected>$useful_vehiclename</option>";
									}?>
									</select>
								</td>
							</tr>		
							<tr>
								<td>เป็นรถ </td>
								<td>
									<select name="f_status_vehicle" id="f_status_vehicle">
										<!--option value="1" <?php if($fp_fc_newcar == "1"){ echo "selected"; } ?>>รถใหม่</option>
										<option value="2" <?php if($fp_fc_newcar == "2"){ echo "selected"; } ?>>รถใช้แล้ว</option-->
									<?php
									$qry_status_vehicle = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '1'");
									while($objResuut = pg_fetch_array($qry_status_vehicle)){
										$status_vehicleID = trim($objResuut["auto_id"]);
										$status_vehiclename = trim($objResuut["elementsName"]);
										if($status_vehicleID == $fp_fc_newcar){ $selected = "selected"; }else{ $selected = ""; }
										echo "<option value=\"$status_vehicleID\" $selected>$status_vehiclename</option>";
									}?>
									</select>
								</td>
							</tr>
						<tr>
							<td>เลขไมล์</td>
							<td><input type="text" name="f_carmi" value="<?php echo $fc_mi;?>" tabindex="31" onkeyup="passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							  <td>ระบบแก๊สรถยนต์ </td>
							  <td colspan="2">
								<select name="gas_system" onchange="passrq(this);">									
									<?php
									$qry_gas = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '4'");
									echo "<option value=\"\" >เลือกรายการ</option>";
									while($objResuut = pg_fetch_array($qry_gas)){
										$gasname = trim($objResuut["elementsName"]);
										if($gasname == $fp_fc_gas){ $selected = "selected"; }else{ $selected = ""; }
										echo "<option value=\"$gasname\" $selected>$gasname</option>";
									}?>
								</select><font color="red">*</font>
							  </td>
						</tr>
						<tr>
							<td>วันที่ต่ออายุภาษี</td>
							<td><input type="text" name="f_exp_date" value="<?php echo $fc_expert; ?>" tabindex="32" <?php echo $readonly;?> onchange="passrq(this);" onkeyup="passrq(this);"/>
								<input name="button22" type="button" onclick="displayCalendar(document.frm_edit.f_exp_date,'yyyy/mm/dd',this)" value="ปฏิทิน" tabindex="33" <?php echo $disabled;?>/><font color="red">*</font></td>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td>รหัสวิทยุ</td>
							<td><input type="text" name="f_carradio" value="<?php echo $fc_radio;?>" tabindex="34" <?php echo $readonly;?>/></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="6" style="background-color:#FFFFCC;">แก้ไขข้อมูลสัญญา</td>
						</tr>
						<tr height="30">
							<td>ประเภทสินเชื่อเช่าซื้อ</td>
							<td>
								<select name="creditID" value="<?php echo $fp_creditType;?>" <?php echo $disabled;?>>
								<?php
									if($fp_creditType==""){
										echo "<option value=\"\">--------------เลือก--------------</option>";
									}else{
										$querycredit1=pg_query("select * from \"nw_credit\" where \"creditID\"='$fp_creditType'");
										if($rescredit1=pg_fetch_array($querycredit1)){
											$creditDetail1=$rescredit1["creditDetail"];
										}
										?>
										<option value="<?php echo $fp_creditType; ?>" selected><?php echo $creditDetail1; ?></option>
										<?php
									}
									$querycredit=pg_query("select * from \"nw_credit\" where \"statusUse\"='TRUE' and \"creditID\" <> '$fp_creditType'");
									while($rescredit=pg_fetch_array($querycredit)){
									?>
									<option value="<?php echo $rescredit["creditID"];?>" <?php if($rescredit["creditID"]==$fp_creditType){?> selected <?php }?>><?php echo $rescredit["creditDetail"];?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>วันที่ทำสัญญา</td>
							<td><input type="text" name="f_pstdate" value="<?php echo $fp_stdate; ?>" tabindex="35" <?php echo $readonly;?>/>
								<input name="button2" type="button" onclick="displayCalendar(document.frm_edit.f_pstdate,'yyyy-mm-dd',this)" value="ปฏิทิน" tabindex="36" <?php echo $disabled;?>/></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						
			<?php 
				  $sqlpackage=pg_query("select * from \"Fp_interest\" where \"IDNO\" = '$edt_idno' AND \"fpackID\" is not null");
				  $rowspack = pg_num_rows($sqlpackage);
				  $resqlpackage = pg_fetch_array($sqlpackage);
			  
				  if($rowspack > 0){ 
				  
				  $packid = $resqlpackage['fpackID'];
				  $sepack =pg_query("select * from \"Fp_package\" where \"fpackID\" = '$packid'");
				  $result_package = pg_fetch_Array($sepack);
				  $result_package['numtest'];
				  
			?>	
				<input type="hidden" name="hdbrand"  id="hdbrand" value="<?php echo $result_package['numtest']; ?>">
				<input type="hidden" name="hdprice_not_accessory"  id="hdprice_not_accessory" value="<?php echo $result_package['price_not_accessory']; ?>">
				<input type="hidden" name="hddown_payment" id="hddown_payment" value="<?php echo $result_package['down_payment']; ?>">
				<input type="hidden" name="hdperiod_numeric" id="hdperiod_numeric" value="<?php echo $result_package['period numeric']; ?>">
				<input type="hidden" name="hdmonth_payment" id="hdmonth_payment" value="<?php echo $result_package['month_payment']; ?>">
				<input type="hidden" name="hdinterest" id="hdinterest" value="<?php echo $result_package['interest']; ?>">
				<input type="hidden" name="hdbegin" id="hdbegin" value="<?php echo $fp_begin; ?>">
			
			<?php } ?>
								<tr>
								<td>แก้ไขการเช่าซื้อ :</td>
									<td>
										<input type="radio" name="package" id="package1" value="1" <?php if($rowspack > 0){ echo "checked"; } ?> <?php echo $disabled;?>> ตาม Package																					
										<input type="radio" name="package" id="package2" value="2" <?php if($rowspack == 0){ echo "checked"; } ?> <?php echo $disabled;?>> ระบุเอง											
									</td>			
								  </tr>
							
								<tr name="packagecar1" id="packagecar1">
									<td>ตาม Package</td>
									<td><select name="car_gen1" id="car_gen1" onchange="caldown1()" <?php echo $disabled;?>>
											<?php $sql = pg_query("select distinct \"brand\",\"numtest\" from \"Fp_package\""); ?>
											<option value="">---- เลือกรุ่นรถยนต์ ----</option>										
											<?php while($re = pg_fetch_array($sql)){ ?>
												<option value="<?php echo $re['numtest']; ?>"  <?php if($re['numtest'] == $result_package['numtest']){ ?> selected="selected" <?php  } ?>>---- <?php echo $re['brand']; ?> ----</option>								
											<?php } ?>
																			
																			
									</select></td>
								</tr>
								<tr name="packagecar2" id="packagecar2">
									<td>
										ราคารถ: 
									</td>
									<td colspan="2">
										<span name="pricecar" id="pricecar"></span><font color="red">*</font>
									</td>						 																
								</tr>
								<tr name="packagecar3" id="packagecar3">
									<td>
										เงินดาวน์: 
									</td>
									<td colspan="2">
										<span id="down_payment"></span><font color="red">*</font>
									</td>						 																
								</tr>
								<tr name="packagecar4" id="packagecar4">
									<td>
										จำนวนงวด: 
									</td>
									<td colspan="2">
										<span id="time_payment"></span><font color="red">*</font>
									</td>						 																
								</tr>
								<tr name="packagecar5" id="packagecar5">
									<td>
										ค่างวด: 
									</td>
									<td colspan="2">
										<input type="hidden" name="period_list" id="period_list" onchange="" readOnly="true"><span id="periodtext" ></span><font color="red">*</font>
									</td>						 																
								</tr>
								 <tr name="packagecar6" id="packagecar6">
									<td>
										เงินต้น:
									</td>
									<td><input type="hidden" name="capital" id="capital" <?php if($rowspack > 0){ echo "value=\"$fp_begin\""; } ?> readonly="true"><span name="captext" id="captext"></span><font color="red">*</font>
									</td>																						
								  </tr>
								 
						<tr name="manual1" id="manual1">		
							<td>ค่างวดรวม vat </td>
							<td><input type="text" name="f_pmonth" value="<?php echo $fp_pmonth+$fp_pvat; ?>" tabindex="37" onkeyup="calcfunc();calinterest();passrq(this);" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="2">ค่างวดไม่รวม vat <br />
								<input <?php echo $readonly;?> type="text" name="f_b_month" value="<?php echo $fp_pmonth; ?>"  /></td>
							<td colspan="2">vat ค่างวด
								<br />
								<input type="text" name="f_pvat" value="<?php echo $fp_pvat; ?>" <?php echo $readonly;?>/>
								<input type="hidden" name="ch_fpvat" value="<?php echo $fp_pvat; ?>" />
							</td>
						</tr>
						<tr name="manual2" id="manual2">
							<td>จำนวนงวด</td>
							<td><input type="text" name="f_ptotal" id="f_ptotal" value="<?php echo $fp_ptotal; ?>" tabindex="38" onkeyup="calinterest();passrq(this);" <?php echo $readonly;?> /><font color="red">*</font></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr name="manual3" id="manual3">
							<td>ดาวน์รวม vat </td>
							<td><input type="text" name="f_pdown" id="f_pdown" value="<?php echo $fp_pdown+$fp_pvatofdown; ?>" onkeyup="passrq(this);" tabindex="39" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="2">ดาวน์ไม่รวมvat<br />
								<input <?php echo $readonly;?> type="text" name="f_b_down" value="<?php echo $fp_pdown; ?>"  /></td>
							<td colspan="2">vat ดาวน์<br />
								<input type="text" name="f_vatofdown" value="<?php echo $fp_pvatofdown; ?>" <?php echo $readonly;?>/>
								<input type="hidden" name="ch_dvat" value="<?php echo $fp_pvatofdown; ?>" /></td>
						</tr>
						<tr  name="manual4" id="manual4">
							<td>เงินต้นลูกค้า</td>
							<td><input type="text" name="f_pbegin" id="f_pbegin" onkeyup="calinterest();passrq(this);" value="<?php echo $fp_begin; ?>" tabindex="40" <?php echo $readonly;?>/><font color="red">*</font></td>
							<td colspan="4" style="font-size:small;">ค่างวด x จำนวนงวด = <input type="text" name="resbs" style="background-color:#FFFFCC; border:thin; text-align:center; width:60px;" <?php echo $readonly;?>/><input type="text"   name="res_txt" readonly="readonly" value=""  style="border:thin; width:200px;" /> </td>
						</tr>
						<tr>
							<td>วันที่งวดแรก</td>
							<td><input name="f_startDate" type="text" readonly="true" value="<?php echo $fp_fdate; ?>" tabindex="41"/> <input name="button" type="button" onclick="displayCalendar(document.frm_edit.f_startDate,'yyyy-mm-dd',this)" value="ปฏิทิน" tabindex="42" <?php echo $disabled;?>/></td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr name="manual5" id="manual5">
						<?php $interest=pg_query("select * from \"Fp_interest\" where \"IDNO\" = '$edt_idno'");
								$interest1=pg_fetch_array($interest);
											
						?>
									<td>
										ดอกเบี้ย:
									</td>
									<td colspan="4"><input name="interestmanual" id="interestmanual" type="text"  value="<?php echo $interest1["interest"]; ?>" <?php echo $readonly;?>/>								
											(อัตราดอกเบี้ย ตามจริง
											  <input type="text" name="cal_interestmanual" id="cal_interestmaul" readOnly="true" style="background-color:#FFFFCC; border:thin; text-align:center;" /> 
											  ) 
									</td>
						</tr>
						<tr name="packagecar7" id="packagecar7">
							
										<td>
											ดอกเบี้ย:
										</td>
										<td colspan="4"><input name="interest1" id="interest1" type="text" readOnly="true" <?php echo $readonly;?>/><font color="red">*</font>
											(อัตราดอกเบี้ย ตามจริง
											  <input type="text" name="cal_interest" id="cal_interest" readOnly="true" style="background-color:#FFFFCC; border:thin; text-align:center;" /> 
											  ) 
										</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="submit" onclick="return validForm();" <?php echo $disabled;?>/></td>
							<td colspan="4"><input type="button" value="BACK" onclick="window.location='frm_av_editidno.php'" tabindex="44" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td colspan="4"><input type="button" name="updatelistbox" id="updatelistbox" value="click" onclick="refreshListBox()" /></td>
						</tr>
				</table>
			</form>
		</div>
		<?php
			//}
		} 
		else
		{
			echo "ไม่พบช้อมูล"."<br>";
			echo $resback="<input type=\"button\" value=\"BACK\" onclick=\"window.location='frm_av_editidno.php'\"  />";
		}
		?>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
<?php if($rowspack == 0){ ?>
$("#packagecar1").hide();
$("#packagecar2").hide();
$("#packagecar3").hide();
$("#packagecar4").hide();
$("#packagecar5").hide();
$("#packagecar6").hide();
$("#packagecar7").hide();
document.frm_edit.cal_interest.value="";
calinterest();
<?php }else{ ?>

$("#manual1").hide();
$("#manual2").hide();
$("#manual3").hide();
$("#manual4").hide();
$("#manual5").hide();
$("#packagecar7").hide();

var brand = $('#hdbrand').attr('value');
$("#pricecar").load("price_car.php?brand="+brand);

var brand1 = $('#hdbrand').attr('value');
var down1 = $('#hddown_payment').attr('value');
$("#down_payment").load("down_car.php?brand="+brand1+"&paks="+down1);
var time1 = $('#hdmonth_payment').attr('value');
$("#time_payment").load("month_car.php?brand="+brand1+"&down="+down1+"&time="+time1);
var val1 = parseFloat(document.frm_edit.hdbegin.value); //เงินดาวน์

$('#captext').text(addCommas(val1.toFixed(2)));

	
$.post("period_car.php",{
		 brand : $('#hdbrand').attr('value'),
		 time : $('#hdmonth_payment').attr('value'),
		 down : $('#hddown_payment').attr('value')
		 
	 },
	function(data){			
		$("#period_list").val(data);
		$("#periodtext").text(addCommas(data));
		
	});	
	
$.post("interest.php",{
			brand : $('#hdbrand').attr('value'),
			time : $('#hdmonth_payment').attr('value'),
			down : $('#hddown_payment').attr('value'),
			period : document.frm_edit.period_list.value
		},
		function(data){					
				$("#interest1").val(data);			
		});		

	
<?php } ?>
$(document).ready(function(){

		$("input[type='radio']").change(function(){
		
		if(document.getElementById("package1").checked){
		
			$("#packagecar1").show();
			$("#packagecar2").show();
			$("#packagecar3").show();
			$("#packagecar4").show();
			$("#packagecar5").show();
			$("#packagecar6").show();
			$("#packagecar7").hide();		
			
			$("#manual1").hide();
			$("#manual2").hide();
			$("#manual3").hide();
			$("#manual4").hide();
			$("#manual5").hide();
			document.frm_edit.period_list.value="";
			document.frm_edit.cal_interest.value="";
			document.frm_edit.interest1.value="";
			document.frm_edit.capital.value="";
			$('#captext').text("");
			$("#periodtext").text("");
		}else if(document.getElementById("package2").checked){
		
			$("#packagecar1").hide();
			$("#packagecar2").hide();
			$("#packagecar3").hide();
			$("#packagecar4").hide();
			$("#packagecar5").hide();
			$("#packagecar6").hide();
			$("#packagecar7").hide();
			
			$("#manual1").show();
			$("#manual2").show();
			$("#manual3").show();
			$("#manual4").show();
			$("#manual5").show();
			
			$("#car_gen1").val("");
			$("#pricecar").load("price_car.php");
			$("#down_list1").val("");
			$("#down_payment").load("down_car.php");
			$("#time_payment").load("month_car.php");
			document.frm_edit.period_list.value="";
			document.frm_edit.cal_interest.value="";
			document.frm_edit.interest1.value="";
			document.frm_edit.capital.value="";
			$('#captext').text("");
			$("#periodtext").text("");
		}
	});
});

function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
	{
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
return x1 + x2;
}

function caldown1(){
	

	var brand = $('#car_gen1 option:selected').attr('value');
	$("#pricecar").load("price_car.php?brand="+brand);
	var brand1 = $('#car_gen1 option:selected').attr('value');
	$("#down_payment").load("down_car.php?brand="+brand1);
	$("#time_payment").load("month_car.php");
	
	document.frm_edit.period_list.value="";
	document.frm_edit.cal_interest.value="";
	document.frm_edit.interest1.value="";
	$('#captext').text("");
	$("#periodtext").text("");	
};

function caldown2(){
	var brand1 = $('#car_gen1 option:selected').attr('value');
	var down = $('#down_list1 option:selected').attr('value');	
	$("#time_payment").load("month_car.php?brand="+brand1,"&down="+down);
	
	document.frm_edit.period_list.value="";
	document.frm_edit.interest1.value="";
	document.frm_edit.capital.value="";
	$('#captext').text("");
	$("#periodtext").text("");
};

function caldown3(){

	$.post("period_car.php",{
		 brand : $('#car_gen1 option:selected').attr('value'),
		 time : $('#time_list option:selected').attr('value'),
		 down : $('#down_list1 option:selected').attr('value')
		 
	 },
	function(data){	
		
		$("#period_list").val(data);
		$("#periodtext").text(addCommas(data));
	});	
	//document.frm_edit.interest.value="";
	caldown4();
	callcfunc();
	alert();
};

function caldown4(){
		
	$.post("interest.php",{
			brand : $('#car_gen1 option:selected').attr('value'),
			time : $('#time_list option:selected').attr('value'),
			down : $('#down_list1 option:selected').attr('value'),
			period : document.frm_edit.period_list.value
		},
		function(data){		
			
				$("#interest1").val(data);
				alert();
			
		});
		
	calinterest_package();	

};

function callcfunc(){

	var test = parseFloat(document.frm_edit.period_list.value);
	caldown4();

}

function calbegin(){ //คำนวณเงินต้น
		var val1 = parseFloat(document.frm_edit.down_list1.value); //เงินดาวน์
		var val2 = parseFloat(document.frm_edit.price_car.value); //ราคารถ
		parseFloat(document.frm_edit.capital.value=val2-val1);
		var val3 = val2-val1;
		$('#captext').text(addCommas(val3.toFixed(2)));		
		
};

function calinterest_package() {

		var month = parseFloat(document.frm_edit.time_list.value); //เดือน
		var period = parseFloat(document.frm_edit.period_list.value); //ยอดผ่อน
		var begin = parseFloat(document.frm_edit.capital.value); //ยอดต้น

		
		parseFloat(document.frm_edit.cal_interest.value=((((period*month)-begin)*1200)/month)/begin);

};

function calinterest() {
		var month = parseFloat(document.frm_edit.f_ptotal.value); //เดือน
		var period = parseFloat(document.frm_edit.f_pmonth.value); //ยอดผ่อน
		var begin = parseFloat(document.frm_edit.f_pbegin.value); //ยอดต้น
		parseFloat(document.frm_edit.cal_interestmanual.value=((((period*month)-begin)*1200)/month)/begin);
}
</script>
<script type="text/javascript">	
$(document).ready(function(){ 
<?php if($N_CARD == "บัตรประชาชน"){ ?>
$("#tb_other").hide();
document.frm_edit.add_other.value="";
document.frm_edit.N_CAPDREF.value="";
<?php } ?>

	$("#chk_other").click(function(){ 
		if($('#chk_other') .attr( 'checked')==true){
			$("#tb_other").show();
		}else{
			$("#tb_other").hide();
			document.frm_edit.add_other.value="";
			document.frm_edit.N_CAPDREF.value="";
		}
	});
	
	$("#list_other").click(function(){ 
		if($('#list_other') .attr( 'value')=='other'){
			$("#othertype").show();
		}else{
			$("#othertype").hide();
			document.frm_edit.add_other.value="";
			document.frm_edit.N_CAPDREF.value="";

		}
	});
	
	
});	
</script>


<script type="text/javascript">
var type1 = '<?php echo $fp_fc_type; ?>';
if(type1 != ""){
	var brandID1 = '<?php echo $fp_fc_brand; ?>';
	var model1 = '<?php echo $fp_fc_model; ?>';
	$("#show_brand").load("combo_brand_list.php?type="+type1+"&brand="+brandID1);
	$("#show_model").load("combo_model_list.php?brandID="+brandID1+"&model="+model1);
}else{
	$('#tr_show_brand').hide();
	$('#tr_show_model').hide();
}
function show_brand_func(){	
	var type = $('#f_type_vehicle option:selected').attr('value');
	if(type == ''){
		$('#tr_show_brand').hide();
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_brand').show();
		$('#tr_show_model').hide();
		$("#show_brand").load("combo_brand_list.php?type="+type);
	}	
}
function show_model_func(){
	var brandID = $('#f_brand option:selected').attr('value');
	
	if(brandID == ''){	
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_model').show();
		$("#show_model").load("combo_model_list.php?brandID="+brandID);
	}	
} 
if(document.frm_edit.f_type_vehicle.value == document.frm_edit.chk_mocy.value){ 
	document.frm_edit.f_useful_vehicle.value='';
	document.frm_edit.f_useful_vehicle.disabled = true;
	document.frm_edit.gas_system.value='';
	document.frm_edit.gas_system.disabled = true;
}
function lockcat(type){
		if(type.value == document.frm_edit.chk_mocy.value){ 
			document.frm_edit.f_useful_vehicle.value='';
			document.frm_edit.f_useful_vehicle.disabled = true;
			document.frm_edit.gas_system.value='';
			document.frm_edit.gas_system.disabled = true;
		}else{
			document.frm_edit.f_useful_vehicle.value='รถรับจ้าง';
			document.frm_edit.f_useful_vehicle.disabled = false;
			document.frm_edit.gas_system.value='';
			document.frm_edit.gas_system.disabled = false;
		}
}	

//ตรวจสอบ require field 
checkrequire();
//ตรวจสอบว่า require field ไหนยังไม่กรอกบ้าง ให้พื้นหลังเป็นสีแดง
function checkrequire(){
	if (document.frm_edit.f_name.value=="") {      
		document.frm_edit.f_name.style.backgroundColor="#FFCCCC"; 
	}
	if (document.frm_edit.f_surname.value=="") {
		document.frm_edit.f_surname.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_brithday.value=="") {      
		document.frm_edit.f_brithday.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_edit.f_age.value=="") {      
		document.frm_edit.f_age.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_edit.f_status.value=="") {      
		document.frm_edit.f_status.style.backgroundColor="#FFCCCC";
	}
	
	if (document.frm_edit.f_cardid.value=="") {       
		document.frm_edit.f_cardid.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_edit.f_datecard.value=="") {         
		document.frm_edit.f_datecard.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_edit.f_card_by.value=="") {         
		document.frm_edit.f_card_by.style.backgroundColor="#FFCCCC";
	}
	
	if(document.frm_edit.chk_other.checked == true){
		if (document.frm_edit.N_CAPDREF.value=="") {      
			document.frm_edit.N_CAPDREF.style.backgroundColor="#FFCCCC";
		}
		if (document.frm_edit.list_other.value=="other") {
			if (document.frm_edit.add_other.value=="") {      
				document.frm_edit.add_other.style.backgroundColor="#FFCCCC";
			}
		}	
	}
	if (document.frm_edit.hdidcardchk.value=='false') {       
		document.frm_edit.hdidcardchk.style.backgroundColor="#FFCCCC";
	}
	
	if (document.frm_edit.f_no.value=="") {        
		document.frm_edit.f_no.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_edit.f_subno.value=="") {
		if($('#f_subnochk').attr( 'checked')==false){	      
			document.frm_edit.f_subno.style.backgroundColor="#FFCCCC";
		}	
	}
	if (document.frm_edit.f_soi.value=="") {
		if($('#f_soichk').attr( 'checked')==false){      
			document.frm_edit.f_soi.style.backgroundColor="#FFCCCC";
		}	
	}
	if (document.frm_edit.f_rd.value=="") {
		if($('#f_rdchk').attr( 'checked')==false){      
			document.frm_edit.f_rd.style.backgroundColor="#FFCCCC";
		}	
	}
	
	if (document.frm_edit.f_tum.value=="") {      
		document.frm_edit.f_tum.style.backgroundColor="#FFCCCC";
	}
	
	if (document.frm_edit.f_aum.value=="") {         
		document.frm_edit.f_aum.style.backgroundColor="#FFCCCC";
	}
	
	if (document.frm_edit.f_province.value=="") {
		document.frm_edit.f_province.style.backgroundColor="#FFCCCC";
	}
		
	if (document.frm_edit.f_post.value=="") {
		if($('#f_postchk').attr( 'checked')==false){      
			document.frm_edit.f_post.style.backgroundColor="#FFCCCC";
		}	
	}
	
	if (document.frm_edit.f_country.value=="") {      
		document.frm_edit.f_country.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_edit.f_mobile.value=="") {
		if (document.frm_edit.f_telephone.value=="") {         
			document.frm_edit.f_telephone.style.backgroundColor="#FFCCCC";
		}
	}	
	
	if (document.frm_edit.f_extadd.value==0) {      
		document.frm_edit.f_extadd.style.backgroundColor="#FFCCCC";
	}
	if(document.frm_edit.f_extadd.value==2){
		if (document.frm_edit.f_ext.value=="") {       
			document.frm_edit.f_ext.style.backgroundColor="#FFCCCC";
		}
	}	
	
	if(document.frm_edit.f_type_vehicle.value==""){
		document.frm_edit.f_type_vehicle.style.backgroundColor="#FFCCCC";
	}else{
		if(document.frm_edit.f_brand){
			if(document.frm_edit.f_brand.value==""){
				document.frm_edit.f_brand.style.backgroundColor="#FFCCCC";
			}else{
				if(document.frm_edit.f_model.value==""){
					document.frm_edit.f_model.style.backgroundColor="#FFCCCC";
				}
			}
		}	
	}

	if (document.frm_edit.f_caryear.value.replace( /\s+$/, "" )==""){       
		document.frm_edit.f_caryear.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_carnum.value.replace( /\s+$/, "" )==""){     
		document.frm_edit.f_carnum.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_carmar.value.replace( /\s+$/, "" )==""){        
		document.frm_edit.f_carmar.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_carregis.value.replace( /\s+$/, "" )==""){       
		document.frm_edit.f_carregis.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_carcolor.value.replace( /\s+$/, "" )==""){
		document.frm_edit.f_carcolor.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_carmi.value.replace( /\s+$/, "" )==""){        
		document.frm_edit.f_carmi.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_exp_date.value.replace( /\s+$/, "" )==""){        
		document.frm_edit.f_exp_date.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_edit.f_letter.value.replace( /\s+$/, "" )==""){         
		document.frm_edit.f_letter.style.backgroundColor="#FFCCCC";
	}
	
	if (document.frm_edit.gas_system.disabled==false){	
		if (document.frm_edit.gas_system.value.replace( /\s+$/, "" )==""){     
			document.frm_edit.gas_system.style.backgroundColor="#FFCCCC";
		}
	}
	
	if(document.getElementById("package1").checked == true){
		
		if(document.frm_edit.interest1.value==""){
			document.frm_edit.interest1.style.backgroundColor="#FFCCCC";
		}
		if (document.frm_edit.capital.value==""){
			document.frm_edit.capital.style.backgroundColor="#FFCCCC";
		}
		if (document.frm_edit.down_list1){
			if (document.frm_edit.down_list1.value==""){
				document.frm_edit.down_list1.style.backgroundColor="#FFCCCC";
			}
		}	
		if (document.frm_edit.time_list){
			if (document.frm_edit.time_list.value==""){
				document.frm_edit.time_list.style.backgroundColor="#FFCCCC";
			}
		}	
		if (document.frm_edit.period_list){
			if (document.frm_edit.period_list.value==""){
				document.frm_edit.period_list.style.backgroundColor="#FFCCCC";
			} 
		}	
	}else if(document.getElementById("package2").checked == true){	
		if (document.frm_edit.f_pbegin.value.replace( /\s+$/, "" )==""){
			document.frm_edit.f_pbegin.style.backgroundColor="#FFCCCC";
		}
		if (document.frm_edit.f_pdown.value.replace( /\s+$/, "" )==""){
			document.frm_edit.f_pdown.style.backgroundColor="#FFCCCC";	
		}
		if (document.frm_edit.f_ptotal.value.replace( /\s+$/, "" )==""){
			document.frm_edit.f_ptotal.style.backgroundColor="#FFCCCC";	
		}
		if (document.frm_edit.f_pmonth.value.replace( /\s+$/, "" )==""){
			document.frm_edit.f_pmonth.style.backgroundColor="#FFCCCC";	
		}	
    
	}
}
//สำหรับตรวจว่าหาก textbox กรอกค่าแล้วให้เอาสีแดงออก แต่หากยังไม่กรอกให้ใส่สีแดง สำหรับ Require field
function passrq(object){
	if(object.value != ""){
		object.style.backgroundColor="";
	}else{
		object.style.backgroundColor="#FFCCCC";
	}	
}
</script>