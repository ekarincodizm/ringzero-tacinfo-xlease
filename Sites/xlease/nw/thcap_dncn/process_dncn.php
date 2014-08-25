<?php
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$addUser = $_SESSION["av_iduser"];
$contractID = pg_escape_string($_POST["contractID"]);
$method = pg_escape_string($_REQUEST["method"]);
$from_menu = pg_escape_string($_REQUEST["from_menu"]); // มาจากเมนูอะไร

// ================================================================================================================
// ประเภทที่คืนเงิน
// ================================================================================================================
$type = $_POST["type"]; // ประเภทที่คืนเงิน 1 - บอกว่าเป็นการคืนเงินพัก หรือคืนเงินค้ำประกัน / 2 - บอกว่าคืนเงินประกันสัญญา หรือเงินมัดจำ
$type0 = pg_escape_string($type[0]); // ดัก injection ของ จำนวนเงินที่คืน *** ที่ต้องดักตรงนี้เพราะว่า ดักตั้งแต่รับค่า array ไม่ได้ ต้องดักที่สมาชิกแต่ละตัวใน array เลย

$typemoney = pg_escape_string($_POST["typemoney"]); // $type = 1 บอกว่าเป็นการคืนเงินพัก หรือคืนเงินค้ำประกัน  / $type = 2 บอกประเภทหนี้ที่จะคืนเงิน
$typemoney = checknull($typemoney); // กรณีเป็นค่าว่าง = NULL กรณีไม่เป็นค่าว่าง = 'ค่าที่เก็บ'

// ================================================================================================================
// รับรหัส debtID หนี้ที่จะลด (กรณี $type0 = 2)
// ================================================================================================================
$debtid = $_POST["debtid"]; // หนี้ของประเภทเงินที่จะคืน
$debtid0 = pg_escape_string($debtid[0]); // ดัก injection ของ จำนวนเงินที่คืน *** ที่ต้องดักตรงนี้เพราะว่า ดักตั้งแต่รับค่า array ไม่ได้ ต้องดักที่สมาชิกแต่ละตัวใน array เลย
$debtid0 = checknull($debtid0); // กรณีเป็นค่าว่าง = NULL กรณีไม่เป็นค่าว่าง = 'ค่าที่เก็บ'

// ================================================================================================================
// จำนวนเงินที่คืนรวมภาษี (ถ้ามี)
// ================================================================================================================
$amt = $_POST["amt"]; //จำนวนเงินที่คืน
$amt0 = pg_escape_string($amt[0]); // ดัก injection ของ จำนวนเงินที่คืน *** ที่ต้องดักตรงนี้เพราะว่า ดักตั้งแต่รับค่า array ไม่ได้ ต้องดักที่สมาชิกแต่ละตัวใน array เลย

// ================================================================================================================
// จำนวน vat ถ้ามี
// ================================================================================================================
// การคืนเงินหากคืนจากเงินพัก เงินค้ำประกันชำระหนี้จะไม่มี VAT แต่หากคืนจากเงินประกันสัญญา เงินมัดจำ จะมี VAT
// ในกรณีที่มี VAT หน้า form จะต้องเป็นคนส่งจำนวน VAT มา ถ้าไม่ส่งมา หรือส่งมาเป็น 0 ถือว่าไม่มี
$vat = $_POST["vat"];
$vat0 = pg_escape_string($vat[0]); // ดัก injection ของ จำนวนเงินที่คืน *** ที่ต้องดักตรงนี้เพราะว่า ดักตั้งแต่รับค่า array ไม่ได้ ต้องดักที่สมาชิกแต่ละตัวใน array เลย

echo '$type0='.$type0.'<br>';
echo '$debtid0='.$debtid0.'<br>';
echo '$vat0='.$vat0.'<br>';
echo '$amt0='.$amt0.'<br>';

// ================================================================================================================
// จำนวนเงินก่อนภาษี = จำนวนเงินที่คืน - จำนวน vat
// ================================================================================================================
$netamt = $amt0 - $vat0;


$dcNoteDate=pg_escape_string($_POST["dcNoteDate"]); //วันที่ออกรายการมีผล
$dcNoteDescription=pg_escape_string($_POST["dcNoteDescription"]); //เหตุผลในการคืน
$select_printchk = $_POST["select_print"];

$currentDate = nowDate(); // วันที่ปัจจุบัน
$nowDateTime = nowDateTime(); // วันเวลาปัจจุบัน
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function popWindow(wName){
	features = 'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740';
	pop = window.open('',wName,features);
	if(pop.focus){ pop.focus(); }
	return true;
}
</script>
<?php
pg_query("BEGIN WORK");
$status = 0;

if($method=="add"){ //กรณี (THCAP) ขอคืนเงินลูกค้า
	$byChannel=pg_escape_string($_POST['byChannel']); //ช่องทางการจ่าย
	$proviso_return=pg_escape_string($_POST['proviso_return']);  //ประเภทการคืน โดยธนาคาร หรือเช็ค
	$returnChqNo=pg_escape_string($_POST['returnChqNo']); //เลขที่เช็ค
	$returnChqDate=pg_escape_string($_POST['returnChqDate']); //วันที่บนเช็ค
	$temp_returnTranToCus = pg_escape_string($_POST['returnTranToCus']);
	$temp_returnTranToBank = pg_escape_string($_POST['returnTranToBank']);
	list($returnTranToCus,$returnTranToCusName)=explode('#',$temp_returnTranToCus); //เจ้าของบัญชี
	list($returnTranToBank,$returnTranToBankName)=explode('#',$temp_returnTranToBank); //รหัสธนาคาร
	$returnTranToAccNo=pg_escape_string($_POST['returnTranToAccNo']); //เลขที่บัญชีปลายทาง	
	$temp_returnChqCus = pg_escape_string($_POST['returnChqCus']);	
	list($returnChqCus,$returnChqCusName)=explode('#',$temp_returnChqCus); //ออกเช็คให้
	
	if($byChannel==""){
		$byChannel="null";
		$returnChqNo="null";
		$returnChqDate="null";
	}else{
		$byChannel=checknull($byChannel);
		if($proviso_return==1){ //คืนโดยโอนธนาคาร
			$returnChqNo="null"; //ที่ไม่เรียกใช้ function checknull เนื่องจากบางครั้งค่าอาจค้างอยู่ทำให้มีค่า
			$returnChqDate="null";
			$returnChqCus="null";
			$returnTranToCus=checknull($returnTranToCus);
			$returnTranToBank=checknull($returnTranToBank);
			$returnTranToAccNo=checknull($returnTranToAccNo);
		}else{ //คืนโดยเช็ค
			$returnChqNo=checknull($returnChqNo);
			$returnChqDate=checknull($returnChqDate);
			$returnTranToCus="null";
			$returnTranToBank="null";
			$returnTranToAccNo="null";
			$returnChqCus=checknull($returnChqCus);
		}
	}
	
	//gen เลข CN 
	//thcap_gen_documentID('เลขที่สัญญา','วันที่ออกรายการมีผล','3') 3=เอาเลขที่ CN (รหัส CreditNote)
	$qrycn=pg_query("SELECT \"thcap_gen_documentID\"('$contractID','$dcNoteDate','3')");
	list($dcNoteID)=pg_fetch_array($qrycn);
	
	if($dcNoteDate == $currentDate)
	{ // ถ้าวันที่ออกรายการมีผล เป็นวันเดียวกับวันปัจจุบัน
		$dcNoteDate = $nowDateTime;
	}
	elseif($dcNoteDate < $currentDate)
	{ // ถ้าวันที่ออกรายการมีผล น้อยกว่าวันปัจจุบัน
		$dcNoteDate = $dcNoteDate." 23:59:59";
	}
	
	//หาชื่อของพนักงาน
	$qryname=pg_query("select ta_get_user_fullname('$addUser')");
	list($doerName)=pg_fetch_array($qryname);
	
	// หาชื่อผู้กู้หลักปัจจุบัน
	$qry_cusMain = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
	while($res_cusMain = pg_fetch_array($qry_cusMain))
	{
		if($cusMain == "")
		{
			$cusMain = $res_cusMain["thcap_fullname"];
		}
		else
		{
			$cusMain = $cusMain.", ".$res_cusMain["thcap_fullname"];
		}
	}
	$cusMain = checknull($cusMain);
	
	// หาชื่อผู้กู้ร่วม
	$qry_cusJoin = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '1' ");
	while($res_cusJoin = pg_fetch_array($qry_cusJoin))
	{
		if($cusJoin == "")
		{
			$cusJoin = $res_cusJoin["thcap_fullname"];
		}
		else
		{
			$cusJoin = $cusJoin.", ".$res_cusJoin["thcap_fullname"];
		}
	}
	$cusJoin = checknull($cusJoin);
	
	// หาชื่อผู้ค้ำประกัน
	$qry_cusGua = pg_query("SELECT thcap_get_guarantor_details('$contractID')");
	list($cusGua)=pg_fetch_array($qry_cusGua);
	$cusGua = checknull($cusGua);
	
	
	//insert ข้อมูลเพื่อเก็บว่าใครเป็นคนทำรายการ
	$ins_detail="INSERT INTO account.thcap_dncn_details(\"dcNoteID\", \"dcNoteRev\", \"doerID\", \"doerName\", \"doerStamp\", \"dcMainCusName\", \"dcCoCusName\", \"dcGuaCusName\", \"dcNoteUltimateDate\")
    VALUES ('$dcNoteID', 1, '$addUser','$doerName', '$nowDateTime', $cusMain, $cusJoin, $cusGua, '$dcNoteDate')";
	if($resins_detail=pg_query($ins_detail)){
	}else{
		$status++;
	}
	
	// แยก subject ตาม type
	if ($type0 == 1){
		$vsubjectstatus = 1; // คืนจากเงินพัก หรือเงินค้ำประกัน
	} elseif ($type0 == 2) {
		$vsubjectstatus = 3; // คืนจากเงินที่ชำระไว้เกิน หรือเงินมัดจำ
	} else {
		echo "ไม่รู้จักตัวแปร $type0 ดังกล่าว โปรดแจ้งให้ฝ่ายไอที ตรวจสอบ";
		$status++;
	}
	
	//insert ข้อมูลในตารางหลัก
	$ins="
		INSERT INTO account.thcap_dncn(
            \"dcNoteID\", 
			\"dcType\", 
			\"dcCompID\",
			\"dcNoteDate\", 
			\"dcNoteDescription\", 
			\"dcNoteAmtNET\", 
			\"dcNoteAmtVAT\", 
			\"dcNoteAmtALL\",
			\"contractID\",
			\"byChannel\",
			\"debtID\",
			\"subjectStatus\",
			\"typeChannel\",
			\"returnChqNo\",
			\"returnChqDate\",
			\"returnTranToCus\",
			\"returnTranToBank\",
			\"returnTranToAccNo\",
			\"returnChqCus\"
		)
		VALUES (
			'$dcNoteID', -- dcNoteID
			2, -- dcType
			'THCAP', -- dcCompID
			'$dcNoteDate', -- dcNoteDate
			'$dcNoteDescription', -- dcNoteDescription
			$netamt, -- dcNoteAmtNET
			$vat0, -- dcNoteAmtVAT
			$amt0, -- dcNoteAmtALL
			'$contractID', -- contractID
			$byChannel, -- byChannel
			$debtid0, -- debtID
			'$vsubjectstatus', -- subjectStatus
			$typemoney, -- typeChannel
			$returnChqNo, -- returnChqNo
			$returnChqDate, -- returnChqDate
			$returnTranToCus, -- returnTranToCus
			$returnTranToBank, -- returnTranToBank
			$returnTranToAccNo, -- returnTranToAccNo
			$returnChqCus -- returnChqCus
		)
	";			
	if($resins=pg_query($ins)){
	}else{
		$status++;
	}
			
}
// ถ้าเป็นการพิมพ์ใบ
else if($method=="print"){

	$dcNoteID=pg_escape_string($_GET["dcNoteID"]);
	if($dcNoteID!=""){$select_printchk[0]=$dcNoteID;}	
	?>
	<form name ="my" action="pdf_reprint.php" method="post" method="post"  target="_blank">
		<?php
		for($i=0;$i<count($select_printchk);$i++){
	
			$dcNoteID=$select_printchk[$i];	
			echo "<input name=\"select_print[]\" id=\"select_print$i\" value=\"$dcNoteID\" ></td>"; 		
	
	
			//หาว่าเป็นการ print ครั้งที่เท่าไหร่
			$qrymax=pg_query("select max(\"printTime\") from account.thcap_dncn_reprint where \"dcNoteID\"='$dcNoteID'");
			list($printTime)=pg_fetch_array($qrymax);
	
			if($printTime==""){ //กรณียังไม่มีการเพิ่มข้อมูล
				$printTime=0;
			}else{
				$printTime=$printTime+1;
			}
			
			// ถ้ามาจากเมนู "(THCAP) ใบลดหนี้รอพิมพ์ส่ง"
			if($from_menu == "waitPrintSend")
			{
				// ตรวจสอบ Concurrency
				if($printTime > 0)
				{
					$status++;
					pg_query("ROLLBACK");
					echo "<script type=\"text/javascript\">";
					echo "alert('ไม่สามารถพิมพ์ได้ เนื่องจากมีบางรายการเคยพิมพ์ไปก่อนหน้านี้แล้ว กรุณาลองใหม่อีกครั้ง!!');";
					echo "opener.location.reload(true);";
					echo "self.close();";
					echo "</script>";
					exit;
				}
			}
	
			//insert ข้อมูลว่ามีการ print แล้ว 1 ครั้ง
			$ins="INSERT INTO account.thcap_dncn_reprint(
			\"dcNoteID\", id_user, \"printStamp\", \"printTime\")
			VALUES ( '$dcNoteID', '$addUser', '$nowDateTime', '$printTime')";
	
			if($res=pg_query($ins)){
			}else{
				$status++;
			}
	}
}

if($status == 0){
	pg_query("COMMIT");	
	if($method=="print"){
		?>	
		<input name="print" type="submit" value="พิมพ์" hidden />
		<?php echo "<script type=\"text/javascript\">";
				echo "document.forms['my'].print.click();";
				echo "opener.location.reload(true);
				        self.close();";
		        echo "</script>";?>
		</form >
	<?php }else{
		echo "<div style=\"text-align:center;padding-top:50px\"><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_Index.php'>";	
	}
}else{
	pg_query("ROLLBACK");
	echo "<div style=\"text-align:center;padding-top:50px\"><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></div>";
	if($method!="print"){
	echo "<meta http-equiv='refresh' content='3; URL=frm_Index.php'>";
	}
}	
											
?>
