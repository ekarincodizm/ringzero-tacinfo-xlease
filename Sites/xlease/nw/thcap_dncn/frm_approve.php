<?php
if($includepage == '0'){
	$includethis = '0';
	$statusapp = '0';
}else{
	$includethis = '1';
	include("../../config/config.php");
	$statusapp = '1';
}
// --=========== ตารางที่ 1 เรียกรายการอนุมัติคืนเงินลูกค้า ================================================================================================--
	// -- จัดเรียงข้อมูลตามที่เลือก {
		$strSort = pg_escape_string($_GET["sort"]);
			if($strSort == ""){$strSort = "doerStamp";}
		$strOrder = pg_escape_string($_GET["order"]);
			if($strOrder == ""){$strOrder = "DESC";}
	//   } จบจัดเรียงข้อมูลตามที่เลือก ---
	$qry_waitapp = pg_query("SELECT \"contractID\",\"doerStamp\",\"doerID\",\"dcNoteAmtALL\",\"dcNoteID\",\"dcNoteRev\",\"typeChannel\" as \"byChannel\",\"typeChannelName\",\"byChannelName\",\"dcNoteDate\",\"returnChqNo\",\"byChannel\" as \"byChannel_bank\", \"dcMainCusName\"  from account.thcap_dncn_payback where \"dcNoteStatus\" = '9' AND \"dcType\" = '2' order by \"$strSort\" $strOrder");
	$row_waitapp = pg_num_rows($qry_waitapp);
	$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
	//-- INDEX (qrys1) ใช้คำในวงเล็บค้นหาน่ะ  Ctrl+F --

// --=========== จบ ตารางที่ 1  การเรียกรายการอนุมัติคืนเงินลูกค้า ================================================================================================--

// --=========== ตารางที่ 2 ประวัติการคืนเงินลูกค้า ================================================================================================--
	// -- จัดเรียงข้อมูลตามที่เลือก {
		$strSort2 = pg_escape_string($_GET["sort2"]);
			if($strSort2 == ""){$strSort2 = "appvStamp";}
		$strOrder2 = pg_escape_string($_GET["order2"]);
			if($strOrder2 == ""){$strOrder2 = "DESC";}
	//   } จบจัดเรียงข้อมูลตามที่เลือก ---

	$qry_waitapp2 = pg_query("
		SELECT 
			\"contractID\",
			\"doerStamp\",
			\"doerID\",
			\"debtID\",
			\"dcNoteAmtALL\",
			\"dcNoteID\",
			\"dcNoteRev\",
			\"typeChannel\" as \"byChannel\",
			\"dcNoteStatus\",
			\"typeChannelName\",
			\"byChannelName\",
			\"dcNoteDate\",
			\"returnChqNo\",
			\"byChannel\" as \"byChannel_bank\",
			\"dcMainCusName\",
			\"appvName\",
			\"appvStamp\"
		FROM
			account.thcap_dncn_payback
		WHERE
			\"dcNoteStatus\" <> '9' AND
			\"dcType\" = '2'
		ORDER BY
			\"$strSort2\" $strOrder2
		LIMIT 30");
	$row_waitapp2 = pg_num_rows($qry_waitapp2);
	$strNewOrder2 = $strOrder2 == 'DESC' ? 'ASC' : 'DESC';

// --=========== จบ ตารางที่ 2  ประวัติการคืนเงินลูกค้า ================================================================================================--


//--=========== ตั้งค่า ================================================================================================-- 

	//--== หัวเรื่อง *****************************************************
		//- หัวกระดาษ (Title)		
			$Menu_title = '(THCAP) อนุมัติคืนเงินลูกค้า'; //-ชื่อหัวกระดาษ
		//- หัวเรื่องหลัก
			$Menu_header_show = $includethis; //-แสดงหัวเรื่องหลักหรือไม่ 0 ซ่อน 1 แสดง ( ใส่ตัวแปรกรณีถูก include )
			$Menu_header = '(THCAP) อนุมัติคืนเงินลูกค้า'; //-ชื่อหัวเรื่องหลัก
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
			$position_sectb_header = 'left';
			$fontsize_sectb_header = '3';
			$fontcolor_sectb_header = 'black';
			
	//--== ขนาด *****************************************************
	
		//- ความกว้างของหน้ากระดาษ 
			$width_maintb = '1500';	// %,PX
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
$(document).ready(function(){

	
});

function detailapp(idapp,statusapp){	
		$('body').append('<div id="dialog"></div>');
		$('#dialog').load('popup_app.php?idapp='+idapp+'&appstate='+statusapp);
		$('#dialog').dialog({
			title: 'รายละเอียดการคืนเงิน ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 570,
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
		<table width="98%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<tr>
				<td align="center">
				<!-- หาก $Menu_header_show มีค่าเป็น 1 ให้แดสงหัวเรื่องหลัก -->
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
									<th width="10%"><a href='frm_approve.php?sort=dcNoteID&order=<?php echo $strNewOrder ?>'><u>รหัส CreditNote</u></th>
									<th width="10%"><a href='frm_approve.php?sort=contractID&order=<?php echo $strNewOrder ?>'><u>เลขที่สัญญา</u></th>
									<th width="15%">ชื่อผู้กู้หลัก</th>
									<th width="7%"><a href='frm_approve.php?sort=dcNoteDate&order=<?php echo $strNewOrder ?>'><u>วันที่มีผล</u></th>
									<th width="7%"><a href='frm_approve.php?sort=doerStamp&order=<?php echo $strNewOrder ?>'><u>วันที่ทำรายการ</u></th>
									<th width="15%"><a href='frm_approve.php?sort=doerID&order=<?php echo $strNewOrder ?>'><u>ผู้ทำรายการ</u></th>
									<th width="15%"><a href='frm_approve.php?sort=typeChannel&order=<?php echo $strNewOrder ?>'><u>ประเภทเงินที่ขอคืน</u></th>
									<th width="10%"><a href='frm_approve.php?sort=dcNoteAmtALL&order=<?php echo $strNewOrder ?>'><u>จำนวนเงิน</u></th>
									<th width="25%">ช่องทางการคืนเงิน</th>
									<th width="10%">ทำรายการอนุมัติ</th>
									
								</tr>
						<?php //- INDEX (qrys1)
							// --=========== วนเรียกข้อมูลรายการอนุมัติคืนเงินลูกค้า ================================================================================================--	
								//-- หากมีข้อมูล
								if($row_waitapp != 0){
									$i = 0;	 //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
									while($re_waitapp = pg_fetch_array($qry_waitapp)){
										//เลขที่สัญญา
											$conid = $re_waitapp["contractID"];
										//รหัสการคืนเงิน
											$dcNoteID = $re_waitapp["dcNoteID"];
										// dcNoteRev
											$dcNoteRev = $re_waitapp["dcNoteRev"];
										//-- หาชื่อผู้กู้หลัก
												$maincus_fullname = $re_waitapp["dcMainCusName"];
												
										//-- หาผู้กู้ร่วม
										/*		$qry_cocus = pg_query("SELECT \"dcCoCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
												$namecoopall = pg_fetch_result($qry_cocus,0);*/
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
											//รายละเอียดประเภทการขอคืน
											$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel' ");
											list($tpDesc) = pg_fetch_array($qry_txtchannel);
										//จำนวนเงิน
											$dcNoteAmtALL = $re_waitapp["dcNoteAmtALL"];
										
										//กรณีขอคืนเิงินหลังจากปรับปรุงใหม่จะมี column แสดงชื่อรายการที่เลือกว่าคืนเงินพักหรือเงินค้ำ
										if($re_waitapp["typeChannelName"]!=""){
											$tpDesc=$re_waitapp["typeChannelName"];
										}
										//ช่องทางการคืนเงิน
										if($re_waitapp["returnChqNo"]!=""){
											$byChanneltable =$re_waitapp["byChannel_bank"];
											$qry_returnChqNo = pg_query("SELECT \"BAccount\",\"BName\" FROM \"BankInt\" where  \"BID\" = '$byChanneltable' ");
											list($BAccount,$BName) = pg_fetch_array($qry_returnChqNo);
											$listaccount=$re_waitapp["byChannelName"].' ธนาคาร  '.$BAccount.'-'.$BName;
										
										}
										else{$listaccount=$re_waitapp["byChannelName"];}
										
										// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
											$i++;									
											if($i%2==0){
												$bgcolor_TR1 = $bgcolor_TR1_1; // สีพื้นหลังข้อมูล
											}else{
												$bgcolor_TR1 = $bgcolor_TR1_2; // สีพื้นหลังข้อมูล
											} 
						?>
										<tr bgcolor="<?php echo $bgcolor_TR1; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL1; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR1; ?>'" align="center">
													<td align="center"><span onclick="javascript:popU('popup_dncn.php?idapp=<?php echo $dcNoteID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=600')" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo "$dcNoteID"; ?><u></font></td>	
													<td align="left">
														<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
														<font color="red"><u><?php echo "$conid"?><u></font>
													</td>
													<td align="left"><?php echo "$maincus_fullname"?></td>								
													<td align="center"><?php echo $re_waitapp["dcNoteDate"];?></td>
													<td align="center"><?php echo "$doerStamp"?></td>
													<td align="left"><?php echo "$doer_fullname"?></td>
													<td align="center"><?php echo "$tpDesc"?></td>
													<td align="right"><?php echo number_format("$dcNoteAmtALL",2) ?></td>	
													<td align="center"><?php echo $listaccount; ?></td>	
													<td align="center"><img src="<?php echo $rootpath; ?>/nw/thcap/images/detail.gif" onclick="detailapp('<?php echo $dcNoteID; ?>','<?php echo $statusapp; ?>');" style="cursor:pointer;"></td>		
										</tr>
										
										
					<?php
								}
							   }else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"12\"><h2> ไม่พบรายการขออนุมัติ </h2></td></tr>"; }?>			
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
						<?php
						//ประวัติการอนุมัติ 30 รายการล่าสุด
						include("frm_histority_limit.php"); ?>
					<!-- สิ้นสุดตารางสอง -->
					</td>
				</tr>		
		</table>		
	</form>
</body>