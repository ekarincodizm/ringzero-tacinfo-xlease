<?php
session_start();
include("../../config/config.php");
$receiptID=$_GET['receiptID']; //รหัสใบเสร็จ
$bychannel=$_GET['bychannel'];  //ช่องทางการจ่าย



//--** Page นี้สำหรับใช้ในการแยกการเปิดหน้าต่างของค่าอ้างอิงการจ่ายนั้นๆ (byChannelRef) ว่าหากเป็นการจ่ายประเภทไหนให้ค่า อ้างอิงไปหน้าดูรายละเอียดไหนตามประเภทการจ่ายนั้นๆ
//-========================================================================-
//-							Query หาค่าต่างๆ									   -
//-========================================================================-
		//หาค่าอ้างอิงการจ่าย
		$qry_channel = pg_query("SELECT \"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' AND \"byChannel\" = '$bychannel' ");
		list($channelref) = pg_fetch_array($qry_channel);
		
		
		//ตรวจสอบว่าเป้นเงินโอนหรือไม่
			//ใบเสร็จที่ยังไม่ถูกยกเลิก
		$qry_istranspay = pg_query("	SELECT * 
										FROM \"thcap_v_receipt_otherpay\" a
										LEFT JOIN \"BankInt\" b ON a.\"byChannel\" = b.\"BID\"
										WHERE a.\"receiptID\" = '$receiptID' AND b.\"isTranPay\" = '1'
									");
		$row_istranspay = pg_num_rows($qry_istranspay);
			//ใบเสร็จที่ถูกยกเลิกแล้ว
		$qry_istranspay_can = pg_query("	SELECT * 
										FROM \"thcap_v_receipt_otherpay_cancel\" a
										LEFT JOIN \"BankInt\" b ON a.\"byChannel\" = b.\"BID\"
										WHERE a.\"receiptID\" = '$receiptID' AND b.\"isTranPay\" = '1'
									");
		$row_istranspay_can = pg_num_rows($qry_istranspay_can);
		
		//ตรวจสอบว่าเป็นการจ่ายด้วยเงินพักรอตัดรายการหรือเงินค้ำประกันการชำระหนี้หรือไม่
			//-หารหัสเงินค้ำประกัน
			$sqlchannel997 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('','1')");
			list($rechannel997) = pg_fetch_array($sqlchannel997);
			//-หารหัสเงินพักรอตัดรายการ
			$sqlchannel998 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('','1')");
			list($rechannel998) = pg_fetch_array($sqlchannel998);
		
//-========================================================================-		
?>
 <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
 <script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php	
			
         //หากเป็นเงินพักรอตัดรายการหรือเงินค้ำประกันการชำระหนี้แสดงว่าค่า REF นั้นจะเป็นเลขที่สัญญาให้ link ไปยังตารางผ่อนชำระแทน
			if($bychannel == $rechannel997 || $bychannel == $rechannel998){ 
				echo "<script type='text/javascript'>popU('../thcap_installments/frm_Index.php?show=1&idno=$channelref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')</script>";	
				echo "<script type='text/javascript'>window.close();</script>";
				exit();	
		 //หากไม่ใช่แสดงว่าเป็นช่องทางการจ่ายอื่น เช่นเงินโอนหรือเช็ค	
			}else{
				//หากเป็นเงินโอน	
				IF($row_istranspay > 0 || $row_istranspay_can > 0){ 		
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_transpay_detail.php?revTranID=$channelref\">";
					exit();	
				//หากไม่ใช่เงินโอน
				}else{
					echo "<center> <h1>ขออภัย ยังไม่มีหน้าต่างแสดงช่องทางนี้ครับ </h1>";
					echo "<input type=\"button\" value=\" ปิด \" onclick=\"window.close();\"></center>";
					exit();	
				}
			}
?>			