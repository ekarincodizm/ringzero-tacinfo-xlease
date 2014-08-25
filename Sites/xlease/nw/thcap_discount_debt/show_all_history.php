<?php
	if($includepage == '0'){
	$includethis = '0';
	$statusapp = '0';
}else{
	$includethis = '1';
	include("../../config/config.php");
	$statusapp = '1';
}
// --=========== ประวัติส่วนลด ================================================================================================--
	// -- จัดเรียงข้อมูลตามที่เลือก {
		$strSort2 = $_GET["sort2"];
			if($strSort2 == ""){$strSort2 = "appvStamp";}
		$strOrder2 = $_GET["order2"];
			if($strOrder2 == ""){$strOrder2 = "DESC";}
	//   } จบจัดเรียงข้อมูลตามที่เลือก ---
	if($strSort2=="doerID" or $strSort2=="appvID"){
		$qry_waitapp2 = pg_query("SELECT * FROM account.\"thcap_dncn_discount\" where \"dcNoteStatus\" not in('8','9') AND \"dcType\" = '2' order by (select fullname from \"Vfuser\" where id_user = \"$strSort2\") $strOrder2 ");
	} else {
	$qry_waitapp2 = pg_query("SELECT * FROM account.\"thcap_dncn_discount\" where \"dcNoteStatus\" not in('8','9') AND \"dcType\" = '2' order by \"$strSort2\" $strOrder2 ");
	}
	$row_waitapp2 = pg_num_rows($qry_waitapp2);
	$strNewOrder2 = $strOrder2 == 'DESC' ? 'ASC' : 'DESC';
	
		//- ความกว้างของหน้ากระดาษ 
			$width_maintb = '80%';	// %,PX
		//- ระยะห่างระหว่างตารางกับหัวเรื่องหลัก
			$height_tb_header = '10'; // px
		//- ระยะห่างระหว่างตาราง
			$height_tb = '50'; // px
				
			//- ความหนาของกรอบ ตารางที่ 2 
				$cellspacing_sectb = '1';
			//- ระยะห่างระหว่างกรอบกับตัวอักษร ตารางที่2	
				$cellpadding_sectb = '1';	
	
	//--==    สี     *****************************************************
		//- สี body
			$bgcolor_body = '#FFFFFF'; // #XXXXXX , red/blue/....
			
			//- สีพื้นหลังตาราง
				$bgcolor_sectb = '#EEE5DE'; // #XXXXXX , red/blue/....
			//- สี colomn
				$bgcolor_sectb_column = '#C1CDC1';  // #XXXXXX , red/blue/....			
			//- สีพื้นหลังข้อมูลให้สลับกันเพื่อง่ายต่อการอ่านและแยกแยะข้อมูล
				$bgcolor_TR2_1 = "#E0EEE0"; // สีพื้นหลังข้อมูล 1
				$bgcolor_TR2_2 = "#F0FFF0"; // สีพื้นหลังข้อมูล 2	
			//- สีพื้นหลังข้อมูลเมื่อ Mouse อยู่ด้านบนหรือเคลื่อนผ่าน
				$bgcolor_HL2 = "#FFFF99"; 	
					
//--=========== จบการตั้งค่า ================================================================================================-- 	
$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการอนุมัติส่วนลด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?php echo $rootpath; ?>/nw/thcap/act.css"></link>
		<link type="text/css" href="<?php echo $rootpath; ?>/jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-1.4.2.min.js"></script>
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	
});
function detailapp(idapp,statusapp){	
		$('body').append('<div id="dialog"></div>');
		$('#dialog').load('popup_app.php?idapp='+idapp+'&appstate='+statusapp);
		$('#dialog').dialog({
			title: 'รายละเอียดส่วนลด ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 670,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});	
};

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
</head>
<body body bgcolor="<?php echo $bgcolor_body; ?>">
	<center><h1>ประวัติการอนุมัติส่วนลด</h1></center>
	<form name="frm" method="post">
		<table width="<?php echo $width_maintb; ?>" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<table align="center" bgcolor="<?php echo $bgcolor_sectb; ?>" frame="box" width="99%" cellspacing="<?php echo $cellspacing_sectb; ?>" cellpadding="<?php echo $cellpadding_sectb; ?>">					
								<tr bgcolor="<?php echo $bgcolor_sectb_column; ?>">
									<th width="5%">รายการที่</th>
									<th width="10%"><a href='show_all_history.php?sort2=dcNoteID&order2=<?php echo $strNewOrder2 ?>'><u>เลขที่ใบ</u></th>
									<th width="10%"><a href='show_all_history.php?sort2=contractID&order2=<?php echo $strNewOrder2 ?>'><u>เลขที่สัญญา</u></th>
									<th width="10%">รายการ</th>
									<th width="10%">ค่าอ้างอิง</th>
									<th width="6%"><a href='show_all_history.php?sort2=doerStamp&order2=<?php echo $strNewOrder2 ?>'><u>วันที่ทำรายการ</u></th>
									<th width="10%"><a href='show_all_history.php?sort2=doerID&order2=<?php echo $strNewOrder2 ?>'><u>ผู้ทำรายการ</u></th>
									<th width="7%"><a href='show_all_history.php?sort2=dcNoteAmtNET&order2=<?php echo $strNewOrder2 ?>'><u>ส่วนลดก่อน VAT</u></th>
									<th width="7%"><a href='show_all_history.php?sort2=dcNoteAmtVAT&order2=<?php echo $strNewOrder2 ?>'><u>ส่วนลด VAT</u></th>
									<th width="7%"><a href='show_all_history.php?sort2=dcNoteAmtALL&order2=<?php echo $strNewOrder2 ?>'><u>รวมส่วนลด</u></th>
									<th width="10%"><a href='show_all_history.php?sort2=appvID&order2=<?php echo $strNewOrder2 ?>'><u>ผู้อนุมัติ</u></th>
									<th width="6%"><a href='show_all_history.php?sort2=appvStamp&order2=<?php echo $strNewOrder2 ?>'><u>วันเวลาที่อนุมัติ</u></th>
									<th width="6%"><a href='show_all_history.php?sort2=dcNoteStatus&order2=<?php echo $strNewOrder2 ?>'><u>สถานะการอนุมัติ</u></th>
									<th width="6%">เพิ่มเติม</th>
								</tr>
						<?php
							// --=========== วนเรียกข้อมูลรายการอนุมัติส่วนลด ================================================================================================--	
								//-- หากมีข้อมูล
								if($row_waitapp2 != 0){	
									$i = 0; //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
									$num=0; //ตัวนับจำนวนรายการ
									while($re_waitapp2 = pg_fetch_array($qry_waitapp2)){
										$num++;
										//เลขที่สัญญา
											$conid = $re_waitapp2["contractID"];
										//รหัสส่วนลด
											$dcNoteID = $re_waitapp2["dcNoteID"];
										//dcNoteRev
											$dcNoteRev = $re_waitapp2["dcNoteRev"];
										//-- หาชื่อผู้กู้หลัก
											$qry_maincus = pg_query("SELECT \"dcMainCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
											$maincus_fullname = pg_fetch_result($qry_maincus,0);
										//-- หาผู้กู้ร่วม
											$qry_cocus = pg_query("SELECT \"dcCoCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
											$namecoopall = pg_fetch_result($qry_cocus,0);
										//วันที่ทำรายการ
											$doerStamp = $re_waitapp2["doerStamp"];
										//ชื่อผู้ทำรายการ
											$doerID = $re_waitapp2["doerID"];
											$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$doerID'");
											list($doer_fullname) = pg_fetch_array($qry_username);
										//ชื่อผู้อนุมัติ
											$appvID = $re_waitapp2["appvID"];
											$qry_appvUsername = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$appvID'");
											list($appv_fullname) = pg_fetch_array($qry_appvUsername);
										//วันเวลาที่อนุมัติ
											$appvStamp = $re_waitapp2["appvStamp"];
										//ประเภทเงินที่ขอคืน
											
											$byChannel = $re_waitapp2["byChannel"];	
											//เงินค้ำประกันการชำระหนี้
											$qry_chkchannel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$conid','1')");
											list($chkbyChannelget) = pg_fetch_array($qry_chkchannel);
											//เงินพักรอตัดรายการ
											$qry_chkchannel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$conid','1')");
											list($chkbyChannelhold) = pg_fetch_array($qry_chkchannel);
											//ตรวจสอบว่าเป้นประเภทใด						
											if($chkbyChannelget == $byChannel){	//ถ้าเป็น เงินค้ำประกันการชำระหนี้										
												$qry_channel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$conid','$byChannel')");
												list($byChannel) = pg_fetch_array($qry_channel);
											}else if($chkbyChannelhold == $byChannel){ //ถ้าเป็น เงินพักรอตัดรายการ
												$qry_channel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$conid','$byChannel')");
												list($byChannel) = pg_fetch_array($qry_channel);	
											}
											//รายละเอียดประเภทการขอคืน
											$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel' ");
											list($tpDesc) = pg_fetch_array($qry_txtchannel);
										//ส่วนลดก่อน VAT
											$dcNoteAmtNET = $re_waitapp2["dcNoteAmtNET"];
										//ส่วนลด VAT
											$dcNoteAmtVAT = $re_waitapp2["dcNoteAmtVAT"];
										//ส่วนลดรวม
											$dcNoteAmtALL = $re_waitapp2["dcNoteAmtALL"];
										// รหัสหนี้
											$debtID = $re_waitapp2["debtID"];
										// หารหัสประเภทค่าใช้จ่าย
										$qry_typePayID = pg_query("select \"typePayID\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
										$typePayID = pg_fetch_result($qry_typePayID,0);
											
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
												// หางวด
												$qry_typePayRefValue = pg_query("select \"typePayRefValue\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
												$typePayRefValue = pg_fetch_result($qry_typePayRefValue,0);
											
												$tpDesc = "$tpDesc $tpFullDesc $typePayRefValue";
											}
										}
										//สถานะการอนุมัติ
											IF($re_waitapp2["dcNoteStatus"] == '0'){
												$status = 'ไม่อนุมัติ';
											}else IF($re_waitapp2["dcNoteStatus"] == '1'){
												$status = 'อนุมัติ';
											}else IF($re_waitapp2["dcNoteStatus"] == '2'){
												$status = 'ยกเลิก';
											}else{
												$status = 'ไม่ระบุสถานะ';
											}
										
										// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
											$i++;									
											if($i%2==0){
												$bgcolor_TR2 = $bgcolor_TR2_1; // สีพื้นหลังข้อมูล
											}else{
												$bgcolor_TR2 = $bgcolor_TR2_2; // สีพื้นหลังข้อมูล
											} 
						?>
										<tr bgcolor="<?php echo $bgcolor_TR2; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL2; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR2; ?>'" align="center">
													<td align="center"><?php echo "$num"; ?></td>
													<td align="center"><span onclick="javascript:popU('../thcap_dncn/popup_dncn.php?idapp=<?php echo $dcNoteID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=700')" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo "$dcNoteID"; ?><u></font></td>
													<td align="center">
														<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
														<font color="red"><u><?php echo "$conid"?><u></font>
													</td>
													<td align="left"><?php echo "$tpDesc"; ?></td>
													<td align="left"><?php echo "$tpFullDesc $typePayRefValue"; ?></td>
													<td align="center"><?php echo "$doerStamp"; ?></td>
													<td align="left"><?php echo "$doer_fullname"; ?></td>
													<td align="right"><?php echo number_format($dcNoteAmtNET,2); ?></td>								
													<td align="right"><?php echo number_format($dcNoteAmtVAT,2); ?></td>
													<td align="right"><?php echo number_format($dcNoteAmtALL,2); ?></td>	
													<td align="left"><?php echo "$appv_fullname"; ?></td>
													<td align="left"><?php echo "$appvStamp" ?></td>
													<td align="center"><?php echo $status; ?></td>
													<td align="center"><img src="<?php echo $rootpath; ?>nw/thcap_discount_debt/images/detail.gif" style="cursor:pointer;" onclick="detailapp('<?php echo $dcNoteID; ?>','0');"></td>		
										</tr>
					<?php
									} echo "<tr bgcolor=\"#68BEFF\"><td colspan=\"14\" align=\"left\"><strong> รวม $num รายการ</strong></td></tr>";
							   }else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"14\"><h2> ไม่ประวิตการอนุมัติ </h2></td></tr>"; }?>
			</table>
		</table>
	</form>
</body>
</html>



	
