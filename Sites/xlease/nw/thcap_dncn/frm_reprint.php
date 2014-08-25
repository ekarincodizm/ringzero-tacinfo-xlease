<?php
$includethis = '1';
include("../../config/config.php");
$statusapp = '1';

$dcNoteID=pg_escape_string($_GET["dcNoteID"]);

	$qry_waitapp2 = pg_query("
		SELECT
			\"contractID\",
			\"doerStamp\",
			\"doerID\",
			\"debtID\",
			\"dcNoteAmtALL\",
			\"dcNoteID\",
			\"typeChannel\" as \"byChannel\",
			\"typeChannelName\",
			\"byChannelName\",
			\"dcNoteStatus\",
			\"dcNoteDate\"
		FROM 
			account.thcap_dncn_payback	
		WHERE
			\"dcNoteID\" = '$dcNoteID'
			
		UNION
		
		SELECT
			\"contractID\",
			\"doerStamp\",
			\"doerID\",
			\"debtID\",
			\"dcNoteAmtALL\",
			\"dcNoteID\",
			NULL as \"byChannel\",
			NULL as \"typeChannelName\",
			NULL as \"byChannelName\",
			\"dcNoteStatus\",
			\"dcNoteDate\"
		FROM 
			account.thcap_dncn_discount 
		WHERE 
			\"dcNoteID\" = '$dcNoteID'
	");
	$row_waitapp2 = pg_num_rows($qry_waitapp2);

// --=========== จบ ตารางที่ 2  ประวัติการคืนเงินลูกค้า ================================================================================================--

//--=========== ตั้งค่า ================================================================================================-- 						
		//-- ตารางที่ 2 -- //
			$sectb_header = 'Re-print';
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
							
		//-- ตารางที่ 2 -- //		
			//- ความหนาของกรอบ ตารางที่ 2 
				$cellspacing_sectb = '1';
			//- ระยะห่างระหว่างกรอบกับตัวอักษร ตารางที่2	
				$cellpadding_sectb = '1';	
	
	//--==    สี     *****************************************************
		//- สี body
			$bgcolor_body = '#FFFFFF'; // #XXXXXX , red/blue/....
							
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
		$('#dialog').load('../thcap_dncn/popup_app.php?idapp='+idapp+'&appstate='+statusapp+'&print=1');
		$('#dialog').dialog({
			title: 'รายละเอียด ',
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
	<form name="frm" method="post">
		<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
				<tr>
					<td>				
						<?php
						$stsshow=pg_escape_string($_POST["stsshow"]);
						?>
						<table align="center" bgcolor="<?php echo $bgcolor_sectb; ?>" frame="box" width="99%" cellspacing="<?php echo $cellspacing_sectb; ?>" cellpadding="<?php echo $cellpadding_sectb; ?>">					
								<tr bgcolor="<?php echo $bgcolor_sectb_column; ?>">
									<th width="10%">เลขที่สัญญา</th>
									<th width="15%">ชื่อผู้กู้หลัก</th>
									<th width="15%">วันที่มีผล</th>
									<th width="10%">วันที่ทำรายการ</th>
									<th width="15%">ผู้ทำรายการ</th>
									<th width="10%">ประเภทเงินที่ขอคืน</th>
									<th width="10%">จำนวนเงิน</th>
									<th width="10%">ช่องทางการคืนเงิน</th>
									<th width="8%">สถานะการอนุมัติ</th>
									<th width="7%">เพิ่มเติม</th>
									<th width="">พิมพ์</th>
								</tr>
						<?php
							// --=========== วนเรียกข้อมูลรายการอนุมัติคืนเงินลูกค้า ================================================================================================--	
								//-- หากมีข้อมูล
								if($row_waitapp2 != 0){	
									$i = 0; //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
									while($re_waitapp2 = pg_fetch_array($qry_waitapp2)){
										//เลขที่สัญญา
											$conid = $re_waitapp2["contractID"];	
										//-- หาชื่อผู้กู้หลัก
											$qry_maincus = pg_query("SELECT \"thcap_fullname\" FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$conid' AND \"CusState\" = '0'");
											list($maincus_fullname) = pg_fetch_array($qry_maincus);
										//-- หาผู้กู้ร่วม
											$qry_namecoopall = pg_query("SELECT \"thcap_get_coborrower_details\"('$conid')");
											list($namecoopall) = pg_fetch_array($qry_namecoopall);
										//วันที่ทำรายการ
											$doerStamp = $re_waitapp2["doerStamp"];
										//ชื่อผู้ทำรายการ
											$doerID = $re_waitapp2["doerID"];
											$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$doerID'");
											list($doer_fullname) = pg_fetch_array($qry_username);
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
											
											//กรณีขอคืนเงินหลังจากปรับปรุงใหม่จะมี column แสดงชื่อรายการที่เลือกว่าคืนเงินพักหรือเงินค้ำ
											if($re_waitapp2["typeChannelName"]!=""){
												$tpDesc=$re_waitapp2["typeChannelName"];
											}
											
										// ถ้า $tpDesc เป็น NULL แสดงว่าอาจเป็นคารคืนเงืนจากการชำระหนี้ที่ชำระเกินมา หรือคืนเงินมัดจำ
											$debtID = $re_waitapp2["debtID"];
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
											
										//จำนวนเงิน
											$dcNoteAmtALL = $re_waitapp2["dcNoteAmtALL"];
										//รหัสการคืนเงิน
											$dcNoteID = $re_waitapp2["dcNoteID"];
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
										//เป็นส่วนลด =2 หรือ คืนเงินพัก/เงินค้ำ =1 ที่ ช่อง เพิ่มเติม จะดูรายละเอียด เมื่อ เป็น คืนเงิน
										$qry_chksubj = pg_query("SELECT \"subjectStatus\" FROM account.\"thcap_dncn\" where  \"dcNoteID\" = '$dcNoteID' ");
										list($subjectStatus) = pg_fetch_array($qry_chksubj);

										//วันที่มีผล
										$dcNoteDate=$re_waitapp2["dcNoteDate"];
										
										// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
											$i++;									
											if($i%2==0){
												$bgcolor_TR2 = $bgcolor_TR2_1; // สีพื้นหลังข้อมูล
											}else{
												$bgcolor_TR2 = $bgcolor_TR2_2; // สีพื้นหลังข้อมูล
											} 
						?>
										<tr bgcolor="<?php echo $bgcolor_TR2; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL2; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR2; ?>'" align="center">
													<td align="left">
														<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
														<font color="red"><u><?php echo $conid;?><u></font>
													</td>
													<td align="left"><?php echo "$maincus_fullname"?></td>								
													<td align="center"><?php echo $dcNoteDate;?></td>
													<td align="center"><?php echo "$doerStamp"?></td>
													<td align="left"><?php echo "$doer_fullname"?></td>
													<td align="center"><?php echo "$tpDesc"?></td>
													<td align="right"><?php echo number_format("$dcNoteAmtALL",2) ?></td>	
													<td align="center"><?php echo $re_waitapp2["byChannelName"]; ?></td>	
													<td align="center"><?php echo $status; ?></td>
													<?php if ($subjectStatus=='1' || $subjectStatus=='3'){ ?>
													<td align="center"><img src="../thcap/images/detail.gif" style="cursor:pointer;" onclick="detailapp('<?php echo $dcNoteID; ?>','0');"><?php } else if($subjectStatus=='2') {?>
													<td align="center"><?php } ?>
													</td>		
													<td align="center"><img src="../thcap/images/icoPrint.png" style="cursor:pointer;" onclick="javascript:popU('../thcap_dncn/process_dncn.php?method=print&dcNoteID=<?php echo $dcNoteID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"></td>		
												</tr>
					<?php			
										unset($maincus_fullname);
										unset($namecoopall);
										unset($tpDesc);
										unset($dcNoteAmtALL);
									}
							   }else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"9\"><h2>ไม่มีรายการ </h2></td></tr>"; }?>			
						</table>
					<!-- สิ้นสุดตารางสอง -->
					</td>
				</tr>	
		</table>		
	</form>
</body>