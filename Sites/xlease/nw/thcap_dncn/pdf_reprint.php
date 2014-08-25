<?php
session_start();
require('../../thaipdfclass.php');
include("../../config/config.php");
$appUser = $_SESSION["av_iduser"];
$dcNoteID = pg_escape_string($_GET["dcNoteID"]);
$select_print = $_POST["select_print"];
if($select_print==""){
	if($dcNoteID==""){
		$dcNoteID = pg_escape_string($_POST["dcNoteID"]);
		$select_print[0]=$dcNoteID;
	}
	else{
		$select_print[0]=$dcNoteID;
	}
}
class PDF extends ThaiPDF
{

}
$pdf=new ThaiPDF('P' ,'mm','a4');
$pdf->SetThaiFont();
$pdf->SetFont('AngsanaNew','B',20); 

for($i=0;$i< count($select_print);$i++){
	
	$dcNoteID=$select_print[$i];
	for($p_ja=1;$p_ja<=2;$p_ja++){ //ใส่ไว้เพื่อให้ print รายงานออกมา 2 ชุด คือ สำเนา ,ต้นฉบับ

		$pdf->AddPage();

		if($p_ja=="1"){
			$pdf->Image("images/11.png",60,100,100);  //ต้นฉบับ
			$txth="(ต้นฉบับ)";
		}
		else{
			$pdf->Image("images/12.png",60,100,100);  //สำเนา
			$txth="(สำเนา)";
		}
		/* ============================================= */
		//เป็น คืนเงินพัก/เงินค้ำ = 1, ส่วนลด =2, คืนเงินที่ชำระเกิน หรือเงินมัดจำ = 3

		$qry_chksubjectStatus = pg_query("select \"subjectStatus\" from account.thcap_dncn where \"dcNoteID\" ='$dcNoteID'");
		list($chk) = pg_fetch_array($qry_chksubjectStatus);
		if($chk=='1' || $chk=='3'){ // subjectStatus
			$query=pg_query("SELECT * FROM account.\"thcap_dncn_payback\"
			where \"dcNoteID\" = '$dcNoteID' ");
		}
		else if($chk=='2'){ // subjectStatus
			$query=pg_query("SELECT * FROM account.\"thcap_dncn_discount\"
			where \"dcNoteID\" = '$dcNoteID' ");
		}
		$row_no = pg_num_rows($query);

		if($row_no > 0){
			$result = pg_fetch_array($query);
			//วันที่มีผล
			$qry_dcdatenote = pg_query("SELECT \"dcNoteDate\"::date  from account.thcap_dncn where \"dcNoteID\" ='$dcNoteID'");
			list($dcNoteDate) = pg_fetch_array($qry_dcdatenote );
			//ชื่อลูกค้า
			$qry_dcMainCusName = pg_query("SELECT \"dcMainCusName\"  from account.thcap_dncn_details where \"dcNoteID\" ='$dcNoteID'");
			list($dcMainCusName ) = pg_fetch_array($qry_dcMainCusName );	
			//เลขที่สัญญา
			$contractID = $result["contractID"];
			//ที่อยู่  กรุณาส่ง  ให้หาที่ thcap_mg_contract ก่อน หาไม่พบ ไปหา thcap_lease_contract
			$qry_chkadd = pg_query("SELECT sentaddress from thcap_mg_contract where \"contractID\" ='$contractID'");
			list($sentaddress) = pg_fetch_array($qry_chkadd);
			if($sentaddress==""){
				$qry_chkadd = pg_query("select \"sentaddress\" from \"thcap_lease_contract\" where \"contractID\" ='$contractID'");
				list($sentaddress) = pg_fetch_array($qry_chkadd);
			}
			//ที่อยู่    เอาที่อยู่ตามสัญญา  สถานะเป็นผู้กู้หลัก
			$qry_chkadd_send = pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\" where \"contractID\" ='$contractID' and \"CusState\"='0'");
			list($sentaddress_send) = pg_fetch_array($qry_chkadd_send);

			$appvStamp = $result["appvStamp"];//;วันที่ออกใบลดหนี้
			$appvName = $result["appvName"];//ผู้อนุมัติ
			
			$dcNoteAmtNET = $result["dcNoteAmtNET"];
			$dcNoteAmtVAT = $result["dcNoteAmtVAT"];
			$dcNoteAmtALL = $result["dcNoteAmtALL"];
			$dcNoteRev = $result["dcNoteRev"];
			
			$debtID = $result["debtID"]; // รหัสหนี้		
			$byChannel = $result["typeChannel"]; //ประเภทเงินที่ขอคืน

			$returnTranToCusName = $result["returnTranToCusName"]; // โอนเงินให้กับใคร
			$returnTranToBankName = $result["returnTranToBankName"]; // โอนเงินเข้าธนาคารอะไร
			$returnTranToAccNo = $result["returnTranToAccNo"]; // เลขที่บัญชีปลายทางที่รับเงิน
			$returnChqDate = $result["returnChqDate"]; // วันที่บนเช็ค
			$returnChqCusName = $result["returnChqCusName"]; // จ่ายเช็คให้กับ
			$returnChqNo = $result["returnChqNo"]; // เลขที่เช็ค

			// ลูกค้าที่รับเงิน
			$payToCusName = "$returnTranToCusName$returnChqCusName";

			//--- หาช่องทางการจ่าย
			$qry_Channel = pg_query("
				SELECT 
					CASE
						WHEN d.\"isTranPay\" = 1 THEN 
							CASE
								WHEN a.\"returnChqNo\" IS NOT NULL THEN (('เช็คธนาคาร '::text || d.\"BAccount\"::text) || '-'::text) || d.\"BName\"::text
								ELSE (('โอนเงินจาก '::text || d.\"BAccount\"::text) || '-'::text) || d.\"BName\"::text
							END
						ELSE (d.\"BAccount\"::text || '-'::text) || d.\"BName\"::text
					END AS \"payChannelName\"
				FROM 
					account.thcap_dncn a
				LEFT JOIN \"BankInt\" d ON 
					a.\"byChannel\"::text = d.\"BID\"::text
				WHERE 
					a.\"dcNoteID\" = '$dcNoteID'
			");
			$payChannelName = pg_result($qry_Channel,0); // ช่องทางการจ่ายออก
			//--- จบการหาช่องทางการจ่าย
			
			// หาข้อมูลเกี่่ยวกับการลดหนี้ หรือคืนหนี้ที่ชำระเกิน หรือคืนเงินมัดจำ
			if($debtID != '' || $debtID != NULL) {
				// หารหัสประเภทค่าใช้จ่าย และค่าอ้างอิง
				$qry_typePayID = pg_query("select \"typePayID\", \"typePayRefValue\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
				list($typePayID, $typePayRefValue) = pg_fetch_array($qry_typePayID);

				// รายละเอียดประเภทค่าใช้จ่าย
				$qry_type=pg_query("select \"tpDesc\", \"tpFullDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
				while($res_type=pg_fetch_array($qry_type))
				{
					$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
					$tpFullDesc=trim($res_type["tpFullDesc"]); // รายละเอียดแบบเต็ม
				}
				
				// คำอธิบายรายการกรณีที่เป็นหนี้
				$tpDesc = "$tpDesc $tpFullDesc $typePayRefValue";
			}

			if($chk=="1"){ // คืนเงิน 

				//เงินค้ำประกันการชำระหนี้
				$qry_chkchannel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
				list($chkbyChannelget) = pg_fetch_array($qry_chkchannel);
				//เงินพักรอตัดรายการ
				$qry_chkchannel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
				list($chkbyChannelhold) = pg_fetch_array($qry_chkchannel);
				//ตรวจสอบว่าเป้นประเภทใด						
				if($chkbyChannelget == $byChannel){	//ถ้าเป็น เงินค้ำประกันการชำระหนี้		

					$qry_channel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','$byChannel')");
					list($byChannel) = pg_fetch_array($qry_channel);
					
					//รายละเอียดประเภทการขอคืน
					$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel'");
					list($tpDesc) = pg_fetch_array($qry_txtchannel);
					
					$typePayID = $byChannel;
				}else if($chkbyChannelhold == $byChannel){ //ถ้าเป็น เงินพักรอตัดรายการ

					$qry_channel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','$byChannel')");
					list($byChannel) = pg_fetch_array($qry_channel);
					
					//รายละเอียดประเภทการขอคืน
					$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel'");
					list($tpDesc) = pg_fetch_array($qry_txtchannel);
					
					$typePayID = $byChannel;
				}else{ // ถ้าเป็นการคืนหนี้ที่ได้รับชำระมาเกิน หรือคืนเงินมัดจำ
				
					$typePayID = $typePayID; // ตรงกับที่หามาก่อนหน้านี้
					$tpDesc = $tpDesc; // ตรงกับที่หามาก่อนหน้านี้
				}

				//กรณีขอคืนเงินหลังจากปรับปรุงใหม่จะมี column แสดงชื่อรายการที่เลือกว่าคืนเงินพักหรือเงินค้ำ
				if($result["typeChannelName"]!=""){
					$tpDesc=$result["typeChannelName"];
				}
			}
			//------------------- PDF -------------------//

			$pdf->SetFont('AngsanaNew','B',20);  
			$txtreceipt=iconv('UTF-8','windows-874',$txth);
			$pdf->Text(180,12.2,$txtreceipt); //ต้นฉบับ หรือสำเนา
		  
			$dcNote_id=iconv('UTF-8','windows-874',$dcNoteID);
			$pdf->Text(165,25,$dcNote_id); // รหัสเอกสาร

			$pdf->SetFont('AngsanaNew','B',15);
			$date=iconv('UTF-8','windows-874',$dcNoteDate);
			$pdf->Text(158,31,$date); // วันที่มีผล

			$pdf->SetFont('AngsanaNew','',12);  
			$name=iconv('UTF-8','windows-874',$dcMainCusName);
			$pdf->Text(36,34,$name); //ชื่อลูกค้า

			$contractID=iconv('UTF-8','windows-874',$contractID);
			$pdf->Text(102,34,$contractID); //เลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',14); 
			$pdf->SetXY(33,37);
			$address2=iconv('UTF-8','windows-874',$sentaddress_send);
			$pdf->MultiCell(160,5,$address2,0,'L',0); //ที่อยู่ ผู็กู้หลัก

			$row = 67; 

			$pdf->SetXY(17,$row);
			$no=iconv('UTF-8','windows-874',$typePayID);
			$pdf->MultiCell(16,3,$no,0,'C',0); //no

			$pdf->SetXY(31,$row);
			$detail=iconv('UTF-8','windows-874',$tpDesc);
			$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียด

			$pdf->SetXY(95,$row);
			$amount=iconv('UTF-8','windows-874',number_format($dcNoteAmtNET,2));
			$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

			$pdf->SetXY(122,$row);
			$vat=iconv('UTF-8','windows-874',number_format($dcNoteAmtVAT,2));
			$pdf->MultiCell(23,3,$vat,0,'C',0); //ภาษีมูลค่าเพิ่ม

			/*$pdf->SetXY(145,$row);
			if($whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($dcNoteAmtALL,2));}
			else{$vat2=iconv('UTF-8','windows-874','-');}
			$pdf->MultiCell(28,3,$vat2,0,'C',0); //ภาษีหัก ณ ที่จ่าย
			*/

			$pdf->SetXY(170,$row);
			$total=iconv('UTF-8','windows-874',number_format($dcNoteAmtALL,2));
			$pdf->MultiCell(25,3,$total,0,'R',0); //รวม



			//##########################รวมด้านล่าง
			$pdf->SetXY(95,109);
			$amount=iconv('UTF-8','windows-874',number_format($dcNoteAmtNET,2));
			$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

			$pdf->SetXY(122,109);
			$vat=iconv('UTF-8','windows-874',number_format($dcNoteAmtVAT,2));
		
			$pdf->MultiCell(23,3,$vat,0,'C',0); //รวมภาษีมูลค่าเพิ่ม

			/*$pdf->SetXY(145,110);
			if($sum_whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($sum_whtAmt,2));}
			else{$vat2=iconv('UTF-8','windows-874','-');}
			$pdf->MultiCell(28,3,$vat2,0,'C',0); //รวมภาษีหัก ณ ที่จ่าย
			*/
			$pdf->SetXY(170,109);
			$total=iconv('UTF-8','windows-874',number_format($dcNoteAmtALL,2));
			$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด

			if($payChannelName != "")
			{ // ช่องทางการจ่ายออก
				$pdf->SetXY(117,125);
				$pChannel=iconv('UTF-8','windows-874',"ช่องทางการจ่ายออก : $payChannelName");
				$pdf->MultiCell(80,3,$pChannel,0,'L',0); // ช่องทางการจ่ายออก
			}

			if($payToCusName != "")
			{ // โดยจ่ายให้กับ
				$pdf->SetXY(117,130);
				$pChannel=iconv('UTF-8','windows-874',"โดยจ่ายให้กับ : $payToCusName");
				$pdf->MultiCell(80,3,$pChannel,0,'L',0); // โดยจ่ายให้กับ
			}

			if($returnTranToAccNo != "")
			{ // เลขที่บัญชีปลายทาง
				$pdf->SetXY(117,135);
				$pChannel=iconv('UTF-8','windows-874',"เลขที่บัญชีปลายทาง : $returnTranToAccNo");
				$pdf->MultiCell(80,3,$pChannel,0,'L',0); // เลขที่บัญชีปลายทาง
			}

			if($returnChqNo != "")
			{ // เลขที่เช็ค
				$pdf->SetXY(117,135);
				$pChannel=iconv('UTF-8','windows-874',"เลขที่เช็ค : $returnChqNo");
				$pdf->MultiCell(80,3,$pChannel,0,'L',0); // เลขที่เช็ค
			}

			if($returnChqDate != "")
			{ // วันที่บนเช็ค
				$pdf->SetXY(117,140);
				$pChannel=iconv('UTF-8','windows-874',"วันที่บนเช็ค : $returnChqDate");
				$pdf->MultiCell(80,3,$pChannel,0,'L',0); // วันที่บนเช็ค
			}

			if($returnTranToBankName != "")
			{ // โดยโอนเข้า
				$pdf->SetXY(117,140);
				$pChannel=iconv('UTF-8','windows-874',"โดยโอนเข้า : $returnTranToBankName");
				$pdf->MultiCell(80,3,$pChannel,0,'L',0); // โดยโอนเข้า
			}

			$pdf->SetXY(135,161);
			$co=iconv('UTF-8','windows-874',$appvName);
			$pdf->MultiCell(50,2.6,$co,0,'C',0); //ผู้อนุมัติ
			
			$date=iconv('UTF-8','windows-874',$appvStamp);
			$pdf->Text(55,123,$date); // วันที่ออกเอกสาร
			
			
			//###########กรุณาส่ง

			$pdf->SetXY(50,240);
			$name=iconv('UTF-8','windows-874',$dcMainCusName);
			$pdf->MultiCell(150,3,$name,0,'L',0); //ชื่อลูกค้า


			$pdf->SetXY(50,245);
			$address=iconv('UTF-8','windows-874',$sentaddress);
			$pdf->MultiCell(60,5,$address,0,'L',0); //ที่อยู่ ที่ ส่งจดหมาย
		} else {
			echo "พบปัญหาในการทำ PDF";
		}
	} // จบ for ย่อย
} // จบ for หลัก

$pdf->Output();
?>