<?php
$includethis = '1';
include("../../config/config.php");
$statusapp = '1';

// --=========== ตารางที่ 1 เรียกรายการรอพิมพ์ส่ง================================================================================================--
	/*$qry_waitapp = pg_query("SELECT \"contractID\",\"doerStamp\",\"doerID\",\"dcNoteAmtALL\",\"dcNoteID\", \"dcNoteRev\",\"typeChannel\" as \"byChannel\",\"typeChannelName\",\"byChannelName\" FROM account.thcap_dncn_payback 
	where \"dcNoteStatus\" = '1' AND \"dcType\" = '2' AND \"dcNoteID\" NOT IN (select \"dcNoteID\" FROM  account.thcap_dncn_reprint) order by \"doerStamp\" DESC");*/
	
	$qry_waitapp = pg_query("
		SELECT 
			'1' as \"type\",
			\"contractID\",
			\"doerStamp\",
			\"doerID\",
			\"debtID\",
			\"dcNoteAmtALL\",
			\"dcNoteID\",
			\"dcNoteRev\",
			\"typeChannel\" as \"byChannel\",
			\"typeChannelName\",
			\"byChannelName\",
			\"dcNoteDate\"
		FROM
			account.thcap_dncn_payback 
		WHERE 
			\"dcNoteStatus\" = '1' AND 
			\"dcType\" = '2' AND 
			\"dcNoteID\" NOT IN (select \"dcNoteID\" FROM  account.thcap_dncn_reprint)
			
		UNION
	
		SELECT 
			'2' as \"type\",
			\"contractID\",
			\"doerStamp\",
			\"doerID\",
			\"debtID\",
			\"dcNoteAmtALL\",
			\"dcNoteID\",
			\"dcNoteRev\",
			NULL as \"byChannel\",
			NULL as \"typeChannelName\",
			NULL as \"byChannelName\",
			\"dcNoteDate\" 
		FROM 
			account.thcap_dncn_discount 
		WHERE
			\"dcNoteStatus\" = '1' AND 
			\"dcType\" = '2' AND 
			\"dcNoteID\" NOT IN (select \"dcNoteID\" FROM  account.thcap_dncn_reprint) 	
		ORDER BY 
			\"doerStamp\" DESC
	");
	$row_waitapp = pg_num_rows($qry_waitapp);

	//-- INDEX (qrys1) ใช้คำในวงเล็บค้นหาน่ะ  Ctrl+F --

// --=========== จบ ตารางที่ 1  การเรียกรายการอพิมพ์ส่ง================================================================================================--

//--=========== ตั้งค่า ================================================================================================-- 

	//--== หัวเรื่อง *****************************************************
		//- หัวกระดาษ (Title)		
			$Menu_title = '(THCAP) ใบลดหนี้รอพิมพ์ส่ง'; //-ชื่อหัวกระดาษ
		//- หัวเรื่องหลัก
			$Menu_header_show = $includethis; //-แสดงหัวเรื่องหลักหรือไม่ 0 ซ่อน 1 แสดง ( ใส่ตัวแปรกรณีถูก include )
			$Menu_header = '(THCAP) ใบลดหนี้รอพิมพ์ส่ง'; //-ชื่อหัวเรื่องหลัก
			$position_Menu_header = 'center'; //- ตำแหน่งของหัวเรื่องหลัก left,center,right
			$fontsize_Menu_header = '5'; //- ขนาดของตัวอักษรหัวเรื่องหลัก
			$fontcolor_Menu_header = 'black'; //- สีของตัวอักษรหัวเรื่องหลัก #XXXXXX , red/blue/....
			
		//-- ตารางที่ 1 -- //
			$onetb_header = 'รายการใบลดหนี้รอพิมพ์ส่ง';
			$position_onetb_header = 'left'; 
			$fontsize_onetb_header = '3';
			$fontcolor_onetb_header = 'black'; 	
						
	//--== ขนาด *****************************************************
	
		//- ความกว้างของหน้ากระดาษ 
			$width_maintb = '95%';	// %,PX
		//- ระยะห่างระหว่างตารางกับหัวเรื่องหลัก
			$height_tb_header = '10'; // px
		//- ระยะห่างระหว่างตาราง
			$height_tb = '50'; // px
			
		//-- ตารางที่ 1 -- //	
			//- ความหนาของกรอบ ตารางที่ 1 
				$cellspacing_onetb = '1';
			//- ระยะห่างระหว่างกรอบกับตัวอักษร ตารางที่1	
				$cellpadding_onetb = '1';
					
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
		$('#dialog').load('popup_app.php?idapp='+idapp+'&appstate='+statusapp+'&print=1');
		$('#dialog').dialog({
			title: 'รายละเอียดการคืนเงิน ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 400,
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
	<form name="frm" action="process_dncn.php" method="post" target="_blank">
		<table width="<?php echo $width_maintb; ?>" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
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
						<table align="center" width="100%">
							<tr>
								<td align="<?php echo $position_onetb_header; ?>">						
									<font color="<?php echo $fontcolor_onetb_header ?>" size="<?php echo $fontsize_onetb_header ?>px"><b><?php echo $onetb_header ?></b></font>
								</td>
								<td align="right"><input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
								</td>
							</tr>
						</table>		
						<table align="center" bgcolor="<?php echo $bgcolor_onetb; ?>" frame="box" width="100%" cellspacing="<?php echo $cellspacing_onetb; ?>" cellpadding="<?php echo $cellpadding_onetb; ?>">	
								<tr bgcolor="<?php echo $bgcolor_onetb_column; ?>">
									<th width="10%">รหัส Cradit Note</th>
									<th width="7%">ประเภท</th>
									<th width="10%">เลขที่สัญญา</th>
									<th width="15%">ชื่อผู้กู้หลัก</th>
									<th width="10%">วันที่มีผล</th>
									<th width="10%">วันที่ทำรายการ</th>
									<th width="15%">ผู้ทำรายการ</th>
									<th width="12%">ประเภทเงินที่ขอคืน</th>
									<th width="10%">จำนวนเงิน</th>
									<th width="10%">ช่องทางการคืนเงิน</th>
									<th width="10%">รายละเอียด</th>
									<th><span id="selectAll" style="cursor:pointer;"><u><font color="blue">เลือกรายการ</font></u></span></th>
									
									
								</tr>
						<?php //- INDEX (qrys1)
							// --=========== วนเรียกข้อมูล================================================================================================--	
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
											//ตรวจสอบว่าเป็นประเภทใด						
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
											
											// หาคำอธิบายรายการ กรณีขอคืนเงิน ที่คืนจากเงินที่ชำระหนี้ไว้เกิน หรือคืนเงืนมัดจำ
											$debtID = $re_waitapp["debtID"];
											if($tpDesc == "" && ($debtID != '' || $debtID != NULL)){
												// หารหัสประเภทค่าใช้จ่าย และค่าอ้างอิง
												$qry_typePayID = pg_query("select \"typePayID\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
												$typePayID = pg_fetch_result($qry_typePayID,0);
																												
												// รายละเอียดประเภทค่าใช้จ่าย
												$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
												while($res_type=pg_fetch_array($qry_type))
												{
													$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
												}
												$tpDesc = "$tpDesc";
											}
											
											//กรณีขอคืนเงินหลังจากปรับปรุงใหม่จะมี column แสดงชื่อรายการที่เลือกว่าคืนเงินพักหรือเงินค้ำ
											if($re_waitapp["typeChannelName"]!=""){
												$tpDesc=$re_waitapp["typeChannelName"];
											}
										//จำนวนเงิน
											$dcNoteAmtALL = $re_waitapp["dcNoteAmtALL"];
										//dcNoteDate วันที่มีผล
											$dcNoteDate = $re_waitapp["dcNoteDate"];

										// ประเภทเงินที่ขอคืน
											$qry_returnChqNo = pg_query("SELECT \"returnChqNo\",\"byChannel\" FROM account.\"thcap_dncn\"
											where  \"dcNoteID\" ='$dcNoteID' ");
											list($returnChqNo,$byChannel_Chq) = pg_fetch_array($qry_returnChqNo);
											if($returnChqNo !=""){
												$qry_returnChqNo = pg_query("SELECT \"BAccount\",\"BName\" FROM \"BankInt\" where  \"BID\" = '$byChannel_Chq' ");
												list($BAccount,$BName) = pg_fetch_array($qry_returnChqNo);
												$listChannelName=$re_waitapp["byChannelName"].' ธนาคาร  '.$BAccount.'-'.$BName;
										}
										else{$listChannelName=$re_waitapp["byChannelName"];}

										// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
											$i++;									
											if($i%2==0){
												$bgcolor_TR1 = $bgcolor_TR1_1; // สีพื้นหลังข้อมูล
											}else{
												$bgcolor_TR1 = $bgcolor_TR1_2; // สีพื้นหลังข้อมูล
											} 
											if($re_waitapp["type"]=='1') {
												$type="คืนเงินลูกค้า";	
											}
											elseif($re_waitapp["type"]=='2'){
												$type="ส่วนลด";	
											}
						?>
										<tr bgcolor="<?php echo $bgcolor_TR1; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL1; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR1; ?>'" align="center">
													<td align="center"><?php echo $dcNoteID;?></td>
													<td align="center"><?php echo $type;?></td>	
													<td align="left">
														<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
														<font color="red"><u><?php echo "$conid"?><u></font>
													</td>
													<td align="left"><?php echo "$maincus_fullname"?></td>								
													<td align="center"><?php echo $dcNoteDate;?></td>
													<td align="center"><?php echo "$doerStamp"?></td>
													<td align="left"><?php echo "$doer_fullname"?></td>
													<td align="center"><?php echo "$tpDesc"?></td>
													<td align="right"><?php echo number_format("$dcNoteAmtALL",2) ?></td>	
													<td align="center"><?php echo $listChannelName; ?></td>	
													<?php
													if($re_waitapp["type"]=='1') { ?>
														<td align="center"><img src="<?php echo $rootpath; ?>/nw/thcap/images/detail.gif" onclick="detailapp('<?php echo $dcNoteID; ?>','<?php echo $statusapp; ?>');" style="cursor:pointer;"></td>	
													<?php }elseif($re_waitapp["type"]=='2'){?>
														<td align="center"></td>	
													<?php } 
													
													echo "<td align=\"center\"><input type=\"checkbox\" name=\"select_print[]\" id=\"select_print$i\" value=\"$dcNoteID\"></td>";?>														
															
										</tr>
					<?php
									unset($tpDesc);
									unset($dcNoteAmtALL);
					
									}
							   }else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"12\"><h2> ไม่พบรายการขออนุมัติ </h2></td></tr>"; }?>			
						</table>
					<!-- สิ้นสุดตารางแรก -->				
					</td>
				</tr>
				<!-- ระยะห่างระหว่างตาราง -->
				<tr><td><div style="padding-top:<?php echo $height_tb; ?>px;"></div></td></tr>
				<!-- จบระยะห่างระหว่างตาราง -->	
		</table>
		<input type="hidden" id="AllorClear" value="A"/>
		<input type="hidden" id="method" name="method" value="print"/>
		<input type="hidden" name="from_menu" value="waitPrintSend"/>
	</form>
</body>
<script>
$("#selectAll").click(function(){
	var select = $("input[name=select_print[]]");
	var chkBT = $("#AllorClear").val();
	var num = 0;
	
	if(chkBT=="A"){
		for(i=0; i<select.length; i++){
			$(select[i]).attr("checked","checked");
		}
		$("#AllorClear").val('C');
	}else{
		for(i=0; i<select.length; i++){
			$(select[i]).removeAttr("checked");
		}
		$("#AllorClear").val('A');
	}
});

function validate(frm,method){
	
	var select = $("input[name=select_print[]]:checked");
	var ErrorMessage = "Error Message! \n";
	var Error = 0;
	if(select.length<1){
		ErrorMessage += "กรุณาเลือกรายการที่ต้องการ Print";
		Error++;
	}

	if(Error>0){
		alert(ErrorMessage);
		return false;
	}else{
		if(method == "PDF"){			
			frm.submit();
		}
	} 
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>