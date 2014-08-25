<?php
if($includepage == '0'){
	$includethis = '0';
	$statusapp = '0';
}else{
	$includethis = '1';
	include("../../config/config.php");
	$statusapp = '1';
}
// --=========== ตารางที่ 1 เรียกรายการอนุมัติส่วนลด ================================================================================================--
	// -- จัดเรียงข้อมูลตามที่เลือก {
		$strSort = $_GET["sort"];
			if($strSort == ""){$strSort = "doerStamp";}
		$strOrder = $_GET["order"];
			if($strOrder == ""){$strOrder = "ASC";}
	//   } จบจัดเรียงข้อมูลตามที่เลือก ---
	$qry_waitapp = pg_query("SELECT * FROM account.\"thcap_dncn_discount\" where \"dcNoteStatus\" = '8' AND \"dcType\" = '2' order by \"$strSort\" $strOrder");
	$row_waitapp = pg_num_rows($qry_waitapp);
	$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
	//-- INDEX (qrys1) ใช้คำในวงเล็บค้นหาน่ะ  Ctrl+F --

// --=========== จบ ตารางที่ 1  การเรียกรายการอนุมัติส่วนลด================================================================================================--

// --=========== ตารางที่ 2 ประวัติส่วนลด ================================================================================================--
	// -- จัดเรียงข้อมูลตามที่เลือก {
		$strSort2 = $_GET["sort2"];
			if($strSort2 == ""){$strSort2 = "doerStamp";}
		$strOrder2 = $_GET["order2"];
			if($strOrder2 == ""){$strOrder2 = "DESC";}
	//   } จบจัดเรียงข้อมูลตามที่เลือก ---

	$qry_waitapp2 = pg_query("SELECT * FROM account.\"thcap_dncn_discount\" where \"dcNoteStatus\" not in('8','9') AND \"dcType\" = '2' order by \"appvStamp\" DESC limit 30");
	$row_waitapp2 = pg_num_rows($qry_waitapp2);
	$strNewOrder2 = $strOrder2 == 'DESC' ? 'ASC' : 'DESC';

// --=========== จบ ตารางที่ 2  ประวัติส่วนลด ================================================================================================--


//--=========== ตั้งค่า ================================================================================================-- 

	//--== หัวเรื่อง *****************************************************
		//- หัวกระดาษ (Title)		
			$Menu_title = '(THCAP) อนุมัติส่วนลด'; //-ชื่อหัวกระดาษ
		//- หัวเรื่องหลัก
			$Menu_header_show = $includethis; //-แสดงหัวเรื่องหลักหรือไม่ 0 ซ่อน 1 แสดง ( ใส่ตัวแปรกรณีถูก include )
			$Menu_header = '(THCAP) อนุมัติส่วนลด'; //-ชื่อหัวเรื่องหลัก
			$position_Menu_header = 'center'; //- ตำแหน่งของหัวเรื่องหลัก left,center,right
			$fontsize_Menu_header = '5'; //- ขนาดของตัวอักษรหัวเรื่องหลัก
			$fontcolor_Menu_header = 'black'; //- สีของตัวอักษรหัวเรื่องหลัก #XXXXXX , red/blue/....
			
		//-- ตารางที่ 1 -- //
			$onetb_header = 'รายการรออนุมัติ';
			$position_onetb_header = 'left'; 
			$fontsize_onetb_header = '3';
			$fontcolor_onetb_header = 'black'; 			
		//-- ตารางที่ 2 -- //
			$sectb_header = 'ประวัติการอนุมัติ 30 รายการล่าสุด';
			$sectb_Alldata = 'รายการทั้งหมด';
			$position_sectb_header = 'left';
			$fontsize_sectb_header = '3';
			$fontcolor_sectb_header = 'black';
			
	//--== ขนาด *****************************************************
	
		//- ความกว้างของหน้ากระดาษ 
			$width_maintb = '80%';	// %,PX
		//- ระยะห่างระหว่างตารางกับหัวเรื่องหลัก
			$height_tb_header = '10'; // px
		//- ระยะห่างระหว่างตาราง
			$height_tb = '50'; // px
			
		//-- ตารางที่ 1 -- //	
			//- ความหนาของกรอบ ตารางที่ 1 
				$cellspacing_onetb = '1';
			//- ระยะห่างระหว่างกรอบกับตัวอักษร ตารางที่1	
				$cellpadding_onetb = '1';
		//-- ตารางที่ 2 -- //		
			//- ความหนาของกรอบ ตารางที่ 2 
				$cellspacing_sectb = '1';
			//- ระยะห่างระหว่างกรอบกับตัวอักษร ตารางที่2	
				$cellpadding_sectb = '1';	
	
	//--==    สี     *****************************************************
		//- สี body
			$bgcolor_body = '#FFFFFF'; // #XXXXXX , red/blue/....
			
		//-- ตารางที่ 1 -- //	
			//- สีพื้นหลังตาราง
				$bgcolor_onetb = '#EEE5DE'; // #XXXXXX , red/blue/....
			//- สี colomn
				$bgcolor_onetb_column = '#9AC0CD';  // #XXXXXX , red/blue/....
			//- สีพื้นหลังข้อมูลให้สลับกันเพื่อง่ายต่อการอ่านและแยกแยะข้อมูล
				$bgcolor_TR1_1 = "#B2DFEE"; // สีพื้นหลังข้อมูล 1
				$bgcolor_TR1_2 = "#BFEFFF"; // สีพื้นหลังข้อมูล 2
			//- สีพื้นหลังข้อมูลเมื่อ Mouse อยู่ด้านบนหรือเคลื่อนผ่าน
				$bgcolor_HL1 = "#FFFF99"; 	
				
		//-- ตารางที่ 2 -- //
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
<title><?php echo $Menu_title; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?php echo $rootpath; ?>/nw/thcap/act.css"></link>
		<link type="text/css" href="<?php echo $rootpath; ?>/jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-1.4.2.min.js"></script>
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
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

<body bgcolor="<?php echo $bgcolor_body; ?>">
	<form name="frm" method="post">
		<table width="<?php echo $width_maintb; ?>" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<tr>
				<td align="center">
				<!-- หาก $Menu_header_show มีค่าเป็น 1 ให้แสดงหัวเรื่องหลัก -->
					<?php if($Menu_header_show == '1'){ ?>
						<table align="center" width="99%">
							<tr>
								<td align="<?php echo $position_Menu_header; ?>">
									<div style="padding-top:25px;"></div>
									<font color="<?php echo $fontcolor_Menu_header ?>" size="<?php echo $fontsize_Menu_header ?>px"><b><?php echo $Menu_header ?></b></font>
								</td>
							</tr>
						</table>
					<?php }	?>
					<!-- ระยะห่างระหว่างตารางกับหัวเรื่องหลัก -->
						<div style="padding-top:<?php echo $height_tb_header; ?>px;"></div>	
						
					<!-- เริ่มต้นตารางแรก -->	
						<table align="center" width="99%">
							<tr>
								<td align="<?php echo $position_onetb_header; ?>">						
									<font color="<?php echo $fontcolor_onetb_header ?>" size="<?php echo $fontsize_onetb_header ?>px"><b><?php echo $onetb_header ?></b></font>
								</td>
							</tr>
						</table>		
						<table align="center" bgcolor="<?php echo $bgcolor_onetb; ?>" frame="box" width="99%" cellspacing="<?php echo $cellspacing_onetb; ?>" cellpadding="<?php echo $cellpadding_onetb; ?>">					
								<tr bgcolor="<?php echo $bgcolor_onetb_column; ?>">
									<th width="10%"><a href='frm_approve.php?sort=dcNoteID&order=<?php echo $strNewOrder ?>'><u>เลขที่ใบ</u></th>
									
									<th width="10%"><a href='frm_approve.php?sort=contractID&order=<?php echo $strNewOrder ?>'><u>เลขที่สัญญา</u></th>
									<th width="19%">รายการ</th>
									<th width="15%">ค่าอ้างอิง</th>
									<th width="10%"><a href='frm_approve.php?sort=doerStamp&order=<?php echo $strNewOrder ?>'><u>วันที่ทำรายการ</u></th>
									<th width="15%"><a href='frm_approve.php?sort=doerID&order=<?php echo $strNewOrder ?>'><u>ผู้ทำรายการ</u></th>
									<th width="7%">ส่วนลดก่อน VAT</th>
									<th width="7%">ส่วนลด VAT</th>
									<th width="7%"><a href='frm_approve.php?sort=dcNoteAmtALL&order=<?php echo $strNewOrder ?>'><u>จำนวนเงิน</u></th>
									<th width="10%">ทำรายการอนุมัติ</th>
								</tr>
						<?php //- INDEX (qrys1)
							// --=========== วนเรียกข้อมูลรายการอนุมัติส่วนลด ================================================================================================--	
								//-- หากมีข้อมูล
								if($row_waitapp != 0)
								{
									$i = 0;	 //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
									while($re_waitapp = pg_fetch_array($qry_waitapp))
									{
										//เลขที่สัญญา
											$conid = $re_waitapp["contractID"];
										//รหัสส่วนลด
											$dcNoteID = $re_waitapp["dcNoteID"];
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
										//ประเภทเงินที่ขอคืน
											
											$byChannel = $re_waitapp["byChannel"];	
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
										//ส่วนลดก่อน VAT
											$dcNoteAmtNET = $re_waitapp["dcNoteAmtNET"];
										//ส่วนลด VAT
											$dcNoteAmtVAT = $re_waitapp["dcNoteAmtVAT"];
										//ส่วนลดรวม
											$dcNoteAmtALL = $re_waitapp["dcNoteAmtALL"];
										// รหัสหนี้
											$debtID = $re_waitapp["debtID"];
											
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
												$tpDesc = "$tpDesc $tpFullDesc $typePayRefValue";
											}
										}
										
										// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
											$i++;									
											if($i%2==0){
												$bgcolor_TR1 = $bgcolor_TR1_1; // สีพื้นหลังข้อมูล
											}else{
												$bgcolor_TR1 = $bgcolor_TR1_2; // สีพื้นหลังข้อมูล
											} 
						?>
										<tr bgcolor="<?php echo $bgcolor_TR1; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL1; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR1; ?>'" align="center">
													<td align="center"><span onclick="javascript:popU('../thcap_dncn/popup_dncn.php?idapp=<?php echo $dcNoteID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo "$dcNoteID"; ?><u></font></td>
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
													<td align="center"><img src="<?php echo $rootpath; ?>/nw/thcap/images/detail.gif" onclick="detailapp('<?php echo $dcNoteID; ?>','<?php echo $statusapp; ?>');" style="cursor:pointer;"></td>		
										</tr>
					<?php
									}
								}else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"19\"><h2> ไม่พบรายการขออนุมัติ </h2></td></tr>"; }?>
						</table>
					<!-- สิ้นสุดตารางแรก -->				
					</td>
				</tr>
				<!-- ระยะห่างระหว่างตาราง -->
				<tr><td><div style="padding-top:<?php echo $height_tb; ?>px;"></div></td></tr>
				<!-- จบระยะห่างระหว่างตาราง -->
				<tr>
					<td>
						<!-- เริ่มต้นตารางสอง -->	
						<table align="center" width="99%">
							<tr>
								<td align="<?php echo $position_sectb_header; ?>">						
									<font color="<?php echo $fontcolor_sectb_header ?>" size="<?php echo $fontsize_sectb_header ?>px"><b><?php echo $sectb_header ?></b></font> 
									<a href="javascript:popU('show_all_history.php?','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=700')"><b><u>(<font color="#0099FF" size=<?php echo $fontsize_sectb_header ?>>ทั้งหมด</font>)</u></b></a>
								</td>
							</tr>
						</table>		
						<table align="center" bgcolor="<?php echo $bgcolor_sectb; ?>" frame="box" width="99%" cellspacing="<?php echo $cellspacing_sectb; ?>" cellpadding="<?php echo $cellpadding_sectb; ?>">					
								<tr bgcolor="<?php echo $bgcolor_sectb_column; ?>">
									<th width="5%">รายการที่</th>
									<th width="10%">เลขที่ใบ</th>
									<th width="10%">เลขที่สัญญา</th>
									<th width="10%">รายการ</th>
									<th width="10%">ค่าอ้างอิง</th>
									<th width="6%">วันที่ทำรายการ</th>
									<th width="10%">ผู้ทำรายการ</th>
									<th width="7%">ส่วนลดก่อน VAT</th>
									<th width="7%">ส่วนลด VAT</th>
									<th width="7%">รวมส่วนลด</th>
									<th width="10%">ผู้อนุมัติ</th>
									<th width="6%">วันเวลาที่อนุมัติ</th>
									<th width="6%">สถานะการอนุมัติ</th>
									<th width="6%">เพิ่มเติม</th>
								</tr>
						<?php
							// --=========== วนเรียกข้อมูลรายการอนุมัติส่วนลด ================================================================================================--	
								//-- หากมีข้อมูล
								if($row_waitapp2 != 0){	
									$i = 0; //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
									$num=0; // แสดงลำดับรายการ
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
													<td align="center"><?php echo "$appvStamp"; ?></td>
													<td align="center"><?php echo $status; ?></td>
													<td align="center"><img src="<?php echo $rootpath; ?>/nw/thcap/images/detail.gif" style="cursor:pointer;" onclick="detailapp('<?php echo $dcNoteID; ?>','0');"></td>		
										</tr>
					<?php
									} echo "<tr bgcolor=\"#68BEFF\"><td colspan=\"14\" align=\"left\"><strong> รวม $num รายการ</strong></td></tr>";
							   }else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"14\"><h2> ไม่ประวิตการอนุมัติ </h2></td></tr>"; }?>
						</table>
					<!-- สิ้นสุดตารางสอง -->
					</td>
				</tr>		
		</table>		
	</form>
</body>