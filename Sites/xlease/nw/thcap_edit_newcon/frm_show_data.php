<?php
include("../../config/config.php");
$contractidsend = $_GET["hdcontractid"]; //เลขที่สัญญา
$coneditID  = $_GET["coneditID"]; //รหัสเลข running จากตาราง thcap_contract_edit สำหรับระบุข้อมูลที่ถูกแก้ไข
$readonlyna = $_GET["readonly"]; //หากมีค่าเป็น 't' คืออนุญาติให้ดูอย่างเดียวเท่านั้น ไม่ให้มีการแก้ไขหรืออนุมัติใดๆทั้งสิ้น
$appv = $_GET["appv"]; //หากมีค่าเป็น 't' คืออนุญาติให้มีการอนุมัติรายการที่กำลังดูอยู่ได้

//ค้นหาเหตุผลที่ไม่อนุมัติหากมี {
	$qry_note = pg_query("	SELECT \"noteapp\"
							FROM 	\"thcap_contract_edit\"
							WHERE 	\"coneditID\" = '$coneditID'
							LIMIT 1
						");
	list($noteapp) = pg_fetch_array($qry_note);	
	IF($noteapp == ""){
		$qry_note = pg_query("		SELECT  \"noteapp\"
									FROM 	\"thcap_contract_edit\"
									WHERE 	\"coneditID\" IN (
																	SELECT MAX(\"coneditID\") 
																	FROM \"thcap_contract_edit\" 
																	WHERE \"contractID\" = '$contractidsend'
																	AND \"status_app\" = '2'
															  )
									LIMIT 1
							");
		list($noteapp) = pg_fetch_array($qry_note);	
	}
// } จบการค้นหาเหตุผลที่ไม่อนุมัติ 	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่รายละเอียดสัญญา BH</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
			<link type="text/css" rel="stylesheet" href="act.css"></link>
				<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
					<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
						<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
						<script src="../../jqueryui/jQueryAlertDialogs/_assets/js/jquery.ui.draggable.js" type="text/javascript"></script>   
						<script src="../../jqueryui/jQueryAlertDialogs/_assets/js/jquery.alerts.js" type="text/javascript"></script>
						<link href="../../jqueryui/jQueryAlertDialogs/_assets/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />  
<script type="text/javascript">
$(document).ready(function(){

	//ดึงหน้าข้อมูลสัญญาและข้อมูลลูกค้ามาแสดง {
	var aaaa = '<?php echo $contractidsend; ?>';
	var bbbb = '<?php echo $readonlyna; ?>';

	$("#panel").load("../loans_temp/frm_appv_loan.php?contract="+aaaa+"&lonly=true&AppvStatus=1&readonly="+bbbb+"&ShowfromReal=t");
	$("#panelcus").load("frm_edit_newcus.php?contract="+aaaa+"&readonly="+bbbb);
	$("#panelproduct").load("frm_product_list.php?contract="+aaaa+"&readonly="+bbbb);
	
	// } จบการดึงหน้า page มาแสดง
	
	//หากกดปุ่มไม่อนุมัติ
	$("#notappButton").click(function(){
			
		$('body').append('<div id="dialog"></div>');
	
		$('#dialog').load('pop-conf.php?contractID=<?php echo $contractidsend; ?>&coneditID=<?php echo $coneditID; ?>');
		$('#dialog').dialog({
			title: 'ยืนยันการตรวจสอบ ข้อมูลไม่ถูกต้อง',
			resizable: false,
			modal: true,  
			width: 500,
			height: 280,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});	
    });	
});
function confirmappv(){
	if(confirm('ยืนยันการอนุมัติ')==true){
		return true;
	}else{
		$("#btn_save").attr('disabled', false);
		return false;
	}
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function removeitem(value_assetid,value_assetDetailID){

	$.post('check_product_autoremove.php',{
				assetDetailID: value_assetDetailID,
				assetid: value_assetid
			},
			function(data){
				if(String.trim(data) == '555'){
					alerttxt = 'สินค้านี้เป็นสินค้าที่เพิ่มและยังไม่ได้ผ่านการตรวจสอบ \nหากนำสินค้านี้ออกจะเป็นการยกเลิกอัตโนมัติ \nท่านจะต้องเพิ่มสินค้าดังกล่าวใหม่อีกครั้ง \n\n ท่าต้องการลบสินค้าชิ้นนี้หรือไม่ ?';						
				}else{					
					alerttxt = 'ยืนยันการลบสินค้า';		
				}
				jConfirm(alerttxt, 'คำเตือน', function(r) {
					if(r){
						
						$.post('process_remove_product.php',{
								conid: '<?php echo $contractidsend; ?>',
								assetDetailID: value_assetDetailID,
								assetid: value_assetid
						},
						function(data){
							if(data == '1'){
								var aaaa1 = '<?php echo $contractidsend; ?>';
								var bbbb1 = '<?php echo $readonlyna; ?>';
								$("#panel").load("../loans_temp/frm_appv_loan.php?contract="+aaaa1+"&lonly=true&AppvStatus=1&readonly="+bbbb1+"&ShowfromReal=t");
								$("#panelproduct").load("frm_product_list.php?contract="+aaaa1+"&readonly="+bbbb1);
								$('#dialog').dialog("close");
							}else{
								alert(data);
								$('#dialog').dialog("close");
							}
						});
					}	
				});
												
				
			});



		
};
</script>
</head>
<body>
	<div style="padding-top:30px;"></div>
	
	<?php if($readonlyna != 't'){ //หากไม่ใช่การดูข้อมูลอย่างเดียวแสดงว่าสามารถแก้ไขข้อมูลได้ ให้เปลี่ยน header ?>
		<div align="center" ><font size="3px;">ใส่รายละเอียดสัญญา</font></div>
	<?php }else{ ?>
		<div align="center" ><font size="3px;">ตรวจสอบรายละเอียดสัญญา</font></div>
	<?php } ?>	
		<div align="center" style="padding-top:5px;"><font size="3px;"><b><?php echo $contractidsend; ?></b></font></div>
<?php 
	//หากมีมีการไม่อนุมัติจะต้องแสดงเหตุผลด้วย
		IF($noteapp != ""){
?>	
			<div style="padding-top:10px;"></div>
				<table width="85%" align="center" bgcolor="#FFCCCC">
						<tr>
							<td>
								<!-- หมายเหตุในการไม่อนุมัติ -->
								<b>หมายเหตุ : </b><?php echo $noteapp; ?>
							</td>
						</tr>
				</table>
<?php } ?>		
	
<table width="85%" align="center">
	<tr>
		<td>
			<!-- กล่องข้อมูลสัญญา -->
			<div id="panel"></div>
		</td>
	</tr>
</table>
<div style="padding-top:10px;"></div>
<table width="80%" align="center">
	<tr>
		<td>
			<!-- กล่องข้อมูลลูกค้า -->
			<div id="panelcus"></div>
		</td>
	</tr>
</table>
<div style="padding-top:10px;"></div>
<table width="95%" align="center">
	<tr>
		<td>
			<!-- กล่องข้อมูลสินค้า -->
			<div id="panelproduct"></div>
		</td>
	</tr>
</table>
<div style="padding-top:50px;"></div>
<?php if($readonlyna != 't'){ //หากไม่ใช่การจำกัดสิทธิ์ให้ดูได้อย่างเดียวนั้น ให้แสดงปุ่ม แก้ไขได้?>
	<form name="frmsub" method="POST" action="process_adddo.php">
		<input type="hidden" name="coneditID" value="<?php echo $coneditID; ?>">
		<input type="hidden" name="contractidsend" value="<?php echo $contractidsend; ?>">
		<table width="50%" align="center">
			<tr>
				<td align="center">	
					<div ><input type="button" value=" ส่งรายการนี้ไปตรวจสอบ " onclick="chkaddr();" style="width:150px;height:70px;"></div>
				</td>
				<td align="center">	
					<div ><input type="button" value=" ปิด " onclick="window.close();" style="width:150px;height:70px;"></div>
				</td>
			</tr>
		</table>
	</form>
<script type="text/javascript">
function chkaddr(){
	
	$.post("qry_chk_addr.php", { 
					contractid: document.frmsub.contractidsend.value,					
				},
				function(data){
					if(data == 'success'){
						if(confirm('ยืนยันการส่ง')==true){
							document.frmsub.submit();
						}	
					}else{
						alert('สัญญานี้ยังไม่มีที่อยู่ กรุณาระบุรายละเอียดที่อยู่ของสัญญาให้ครบก่อน !!!');
					}
				});	

}
</script>	
<?php }else{ ?>

<table width="50%" align="center">
		<tr>
		<?php IF($appv == 't'){ //หากอนุญาติให้มีการอนุมัติได้ให้แสดงปุ่มอนุมัติได้ ?>				
			<td align="center">	
			<!--ส่งค่าแบบ FORM ใน HTML-->
			<form name="my" method="post" action="process_approve.php">
				<input type="hidden" name="coneditID" id="coneditID" value="<?php echo $coneditID;?>">
				<input type="hidden" name="cmd" id="cmd" value="app">
				<div ><input type="submit" value=" ถูกต้อง "  id="appButton" style="width:150px;height:70px;" onclick="return confirmappv()"></div>
			</form>
			</td>
			<td align="center">	
				<div ><input type="button" value=" ไม่ถูกต้อง " id="notappButton" style="width:150px;height:70px;"></div>
			</td>
		<?php } ?>
					
			<td align="center">	
				<div ><input type="button" value=" ปิด " onclick="window.close();" style="width:150px;height:70px;"></div>
			</td>
		</tr>
</table>
<?php } ?>

<div style="padding-top:50px;"></div>




		
</body>
</html>