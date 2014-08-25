<?php
include("../../config/config.php");
$strSort2 = pg_escape_string($_GET["sort2"]);
if($strSort2 == ""){$strSort2 = "doerStamp";}
$strOrder2 = pg_escape_string($_GET["order2"]);
if($strOrder2 == ""){$strOrder2 = "DESC";}

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
		\"appvName\",
		\"appvStamp\"
	FROM 
		account.thcap_dncn_payback 
	WHERE 
		\"dcNoteStatus\" <> '9' AND 
		\"dcType\" = '2'
	ORDER BY 
		\"$strSort2\" $strOrder2
");
$row_waitapp2 = pg_num_rows($qry_waitapp2);
$strNewOrder2 = $strOrder2 == 'DESC' ? 'ASC' : 'DESC';

//- สีพื้นหลังตาราง
$bgcolor_sectb = '#EEE5DE'; // #XXXXXX , red/blue/....
//- สี colomn
$bgcolor_sectb_column = '#C1CDC1';  // #XXXXXX , red/blue/....			
//- สีพื้นหลังข้อมูลให้สลับกันเพื่อง่ายต่อการอ่านและแยกแยะข้อมูล
$bgcolor_TR2_1 = "#E0EEE0"; // สีพื้นหลังข้อมูล 1
$bgcolor_TR2_2 = "#F0FFF0"; // สีพื้นหลังข้อมูล 2	
//- สีพื้นหลังข้อมูลเมื่อ Mouse อยู่ด้านบนหรือเคลื่อนผ่าน
$bgcolor_HL2 = "#FFFF99"; 	

$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการอนุมัติคืนเงินลูกค้าทั้งหมด</title>

 <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?php echo $rootpath; ?>/nw/thcap/act.css"></link>
		<link type="text/css" href="<?php echo $rootpath; ?>/jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-1.4.2.min.js"></script>
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/javascript">

$(document).ready(function(){  
	window.opener.location.reload();});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function detailapp(idapp,statusapp){	
		$('body').append('<div id="dialog"></div>');
		$('#dialog').load('popup_app.php?idapp='+idapp+'&appstate='+statusapp);
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
</script>
<body>
<center><h1>ประวัติการอนุมัติคืนเงินลูกค้าทั้งหมด</h1></center>
	<table align="center" bgcolor="#EEE5DE" frame="box" width="99%" cellspacing="1" cellpadding="1">					
		<tr bgcolor="<?php echo $bgcolor_sectb_column; ?>">
			<th width="7%"><a href='frm_historityall.php?sort2=dcNoteID&order2=<?php echo $strNewOrder2 ?>'><u>รหัส CreditNote</u></th>
			<th width="10%"><a href='frm_historityall.php?sort2=contractID&order2=<?php echo $strNewOrder2 ?>'><u>เลขที่สัญญา</u></th>
			<th width="15%">ชื่อผู้กู้หลัก</th>
			<th width="7%"><a href='frm_historityall.php?sort2=dcNoteDate&order2=<?php echo $strNewOrder2 ?>'><u>วันที่มีผล</th>
			<th width="7%"><a href='frm_historityall.php?sort2=doerStamp&order2=<?php echo $strNewOrder2 ?>'><u>วันที่ทำรายการ</u></th>
			<th width="15%"><a href='frm_historityall.php?sort2=doerID&order2=<?php echo $strNewOrder2 ?>'><u>ผู้ทำรายการ</u></th>
			<th width="10%"><a href='frm_historityall.php?sort2=typeChannel&order2=<?php echo $strNewOrder2 ?>'><u>ประเภทเงินที่ขอคืน</u></th>
			<th width="10%"><a href='frm_historityall.php?sort2=dcNoteAmtALL&order2=<?php echo $strNewOrder2 ?>'><u>จำนวนเงิน</u></th>
			<th width="10%">ช่องทางการคืนเงิน</th>
			<th width="15%"><a href='frm_historityall.php?sort2=appvName&order2=<?php echo $strNewOrder2 ?>'><u>ผู้อนุมัติรายการ</u></th>
			<th width="7%"><a href='frm_historityall.php?sort2=appvStamp&order2=<?php echo $strNewOrder2 ?>'><u>วันที่อนุมัติรายการ</u></th>
			<th width="8%"><a href='frm_historityall.php?sort2=dcNoteStatus&order2=<?php echo $strNewOrder2 ?>'><u>สถานะการอนุมัติ</u></th>
			<th width="7%">เพิ่มเติม</th>
		</tr>
		<?php
		// --=========== วนเรียกข้อมูลรายการอนุมัติคืนเงินลูกค้า ================================================================================================--	
			//-- หากมีข้อมูล
				if($row_waitapp2 != 0){	
					$i = 0; //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
						while($re_waitapp2 = pg_fetch_array($qry_waitapp2)){
							//เลขที่สัญญา
							$conid = $re_waitapp2["contractID"];
							//รหัสการคืนเงิน
							$dcNoteID = $re_waitapp2["dcNoteID"];
							// dcNoteRev
							$dcNoteRev = $re_waitapp2["dcNoteRev"];
							//-- หาชื่อผู้กู้หลัก
							$qry_maincus = pg_query("SELECT \"dcMainCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
							$maincus_fullname = pg_fetch_result($qry_maincus,0);
							
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
											
							//จำนวนเงิน
							$dcNoteAmtALL = $re_waitapp2["dcNoteAmtALL"];
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
											
							// หาคำอธิบายรายการ กรณีขอคืนเงิน
							if($re_waitapp2["typeChannelName"]!=""){
								$tpDesc=$re_waitapp2["typeChannelName"];
							}
							
							// หาคำอธิบายรายการ กรณีขอคืนเงิน ที่คืนจากเงินที่ชำระหนี้ไว้เกิน หรือคืนเงืนมัดจำ
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
							
							//ช่องทางการคืนเงิน
							if($re_waitapp2["returnChqNo"]!=""){
								$byChanneltable =$re_waitapp2["byChannel_bank"];
								$qry_returnChqNo = pg_query("SELECT \"BAccount\",\"BName\" FROM \"BankInt\" where  \"BID\" = '$byChanneltable' ");
								list($BAccount,$BName) = pg_fetch_array($qry_returnChqNo);
								$listaccount=$re_waitapp2["byChannelName"].' ธนาคาร  '.$BAccount.'-'.$BName;
										
							}
							else{$listaccount=$re_waitapp2["byChannelName"];}
													
							// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
							$i++;									
							if($i%2==0){
								$bgcolor_TR2 = $bgcolor_TR2_1; // สีพื้นหลังข้อมูล
							}else{
								$bgcolor_TR2 = $bgcolor_TR2_2; // สีพื้นหลังข้อมูล
							} 
						?>
						<tr bgcolor="<?php echo $bgcolor_TR2; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL2; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR2; ?>'" align="center">
							<td align="center"><span onclick="javascript:popU('popup_dncn.php?idapp=<?php echo $dcNoteID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=650')" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo "$dcNoteID"; ?><u></font></td>
							<td align="left">
							<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
							<font color="red"><u><?php echo "$conid"?><u></font>
							</td>
							<td align="left"><?php echo "$maincus_fullname"?></td>								
							<td align="center"><?php echo $re_waitapp2["dcNoteDate"];?></td>
							<td align="center"><?php echo "$doerStamp"?></td>
							<td align="left"><?php echo "$doer_fullname"?></td>
							<td align="center"><?php echo "$tpDesc"?></td>
							<td align="right"><?php echo number_format("$dcNoteAmtALL",2) ?></td>	
							<td align="center"><?php echo $listaccount; ?></td>
							<td align="left"><?php echo $re_waitapp2["appvName"];?></td>
							<td align="center"><?php echo $re_waitapp2["appvStamp"];?></td>
							<td align="center"><?php echo $status; ?></td>
							<td align="center"><img src="<?php echo $rootpath; ?>/nw/thcap/images/detail.gif" style="cursor:pointer;" onclick="detailapp('<?php echo $dcNoteID; ?>','0');"></td>		
						</tr>
					<?php
						}
					}else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"13\"><h2> ไม่ประวัติการอนุมัติ </h2></td></tr>"; }?>			
	</table>
</body>
</html>
