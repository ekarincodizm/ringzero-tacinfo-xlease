<?php
session_start();
include("../../config/config.php");

$method = $_REQUEST['method'];

$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;

if($method == "add"){	
	//นำเลขที่เช็คที่ได้มาตรวจสอบอีกครั้งว่าค่าที่ได้มีในฐานข้อมูลหรือไม่
	$account = pg_escape_string($_POST["account"]);  
	$queryacc=pg_query("SELECT * FROM \"BankInt\" where \"BAccount\"='$account'");
	$numrowacc=pg_num_rows($queryacc);
	
	//ตรวจสอบว่ามีการนำเช็คเล่มนี้ของธนาคารนี้เข้่าฐานข้อมูลหรือยัง
	$chequebook = pg_escape_string($_POST["chequebook"]); 
	$checknum=pg_query("SELECT * FROM \"cheque_detail\" a
	left join \"cheque_order\" b on a.\"detailID\"=b.\"detailID\" 
	where \"chequebook\"='$chequebook' and \"BAccount\"='$account'");
	$numcheck=pg_num_rows($checknum);
	if($numrowacc==0){
		$status=-1;
	}else if($numcheck > 0){
		$status=-2;
	}else{
		$start = pg_escape_string($_POST["start"]); 
		$end = pg_escape_string($_POST["end"]);
		
		
		$ins=pg_query("SELECT \"Add_cheque\"($start,$end,$chequebook,'$account','$id_user')");
		if($result=pg_fetch_array($ins)){
		}else{
			$status++;
		}
	}	
		
	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) เช็ค-ซื้อเช็คเข้า', '$currentdate')");
		//ACTIONLOG---
	
		pg_query("COMMIT");
		echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
		if($method=="edit"){
			echo "<meta http-equiv='refresh' content='2; URL=frm_AddNumcheque.php'>";
		}else{
			echo "<meta http-equiv='refresh' content='2; URL=frm_AddNumcheque.php'>";
		}
	}else if($status==-1){
		echo "<div style=\"padding:20px;text-align:center\"><b>ข้อมูลเลขที่บัญชีธนาคารไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง</b></div>";
		echo "<meta http-equiv='refresh' content='4; URL=frm_AddNumcheque.php'>";
	}else if($status==-2){
		echo "<div style=\"padding:20px;text-align:center\"><b>เช็คเล่มนี้ได้มีการบันทึกก่อนหน้านี้แล้ว กรุณาทำรายการใหม่อีกครั้ง</b></div>";
		echo "<meta http-equiv='refresh' content='4; URL=frm_AddNumcheque.php'>";
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2>ข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
		if($method=="edit"){
			echo "<meta http-equiv='refresh' content='3; URL=frm_AddNumcheque.php'>";
		}else{
			echo "<meta http-equiv='refresh' content='3; URL=frm_AddNumcheque.php'>";
		}
	}
}else if($method=="checkpay"){ //ตรวจสอบว่าลูกค้ามาเบิกเช็คจากธนาคารหรือยัง
	$check=pg_escape_string($_POST["check"]);
	for($i=0;$i<sizeof($check);$i++){
		//ให้ update ว่าเบิกเช็คไปแล้ว
		$up="UPDATE cheque_pay SET \"takeCheque\"='2', \"checkUser\"='$id_user', \"checkStamp\"='$currentdate' WHERE \"chqpayID\"='$check[$i]'";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}
	
	if($status==0){
		pg_query("COMMIT");
		echo "<div style=\"text-align:center;padding:20px;\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></div>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_CheckPay.php'>";	
	}else{
		pg_query("ROLLBACK");
		echo "<div style=\"text-align:center;padding:20px;\"><h2>ข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_CheckPay.php'>";
	}		
}else if($method=="checknum"){
	$chequebook=pg_escape_string($_POST["chequebook"]);
	$account=pg_escape_string($_POST["account"]);
	
	//ตรวจสอบว่ากำลังรออนุมัติอยู่หรือไม่
	$qry_num=pg_query("SELECT * FROM \"cheque_detail\" a
	left join \"cheque_order\" b on a.\"detailID\"=b.\"detailID\" 
	where \"chequebook\"='$chequebook' and \"BAccount\"='$account'");
	$numcheck=pg_num_rows($qry_num);
	
	if($numcheck==0){
		echo "1";
	}else{
		echo "2";
	}
}else if($method=="searchname"){
	$IDNO=pg_escape_string($_POST["IDNO"]);
	//ค้นหาชื่อผู้เช่าซื้อ โดยนำ IDNO มาตรวจสอบก่อนว่ามีจริงหรือไม่
	$qryidno=pg_query("select \"full_name\" from \"Fp\" a 
	left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
	where a.\"IDNO\"='$IDNO'");
	$numidno=pg_num_rows($qryidno);
	if($numidno==0){
		echo "999999999";
	}else{
		$rescus=pg_fetch_array($qryidno);
		list($full_name)=$rescus;
		echo $full_name;
	}
}else if($method=="searchguide"){
	$IDNO=pg_escape_string($_POST["IDNO"]);
	//ค้นหาชื่อผู้แนะนำโดยนำ IDNO มาตรวจสอบก่อนว่ามีจริงหรือไม่
	$qryidno=pg_query("select \"GuidePeople\" from \"nw_IDNOGuidePeople\" where \"IDNO\"='$IDNO'");
	$numidno=pg_num_rows($qryidno);
	if($numidno==0){
		echo "999999999";
	}else{
		$rescus=pg_fetch_array($qryidno);
		list($full_name)=$rescus;
		echo $full_name;
	}
}else if($method=="addpay"){
	$account=pg_escape_string($_POST["account"]);
	$typePay=pg_escape_string($_POST["typePay"]);
	$IDNO=pg_escape_string($_POST["IDNO"]); 
	$cusPay=pg_escape_string($_POST["cusPay"]);
	$typeChq=pg_escape_string($_POST["typeChq"]);
	$moneyPay=pg_escape_string($_POST["moneyPay"]);
	$datePay=pg_escape_string($_POST["datePay"]);
	
	$note=$_POST["note"]; if($note==""){ $note="null";}else{ $note="'".$note."'"; }
	
	//ตรวจสอบว่าข้อมูลนี้กำลังรออนุมัติอยู่หรือไม่ เนื่องจากนโยบายมีอยู่ว่าถ้าเลขที่สัญญาไหนมีการจ่ายเช็คค่า refinance หรือค่าแนะนำแล้วจะไม่สามารถจ่ายเช็คได้อีก
	if($typePay!="3"){
		$qrycheck=pg_query("select * from cheque_pay where \"IDNO\"='$IDNO' and \"typePay\"='$typePay' and \"appStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
	}else{
		$numcheck=0;
	}
	if($numcheck==0){ //แสดงว่ายังไม่มีการบันทึกข้อมูล
		//ตรวจสอบว่าธนาคารถูกต้องหรือไม่
		$qrybank=pg_query("select * from \"BankInt\" where \"BAccount\"='$account' and \"isChq\"='1'"); //isChq='1' คือบัญชีที่มีรายการเช็ค
		$numbank=pg_num_rows($qrybank);
		if($numbank==0){ //ไม่มีเลขที่บัญชีนี้อยู่จริง
			$check=1;
			$txtstatus="เลขที่บัญชี";
		}else{
			if($IDNO!=""){
				//ตรวจสอบว่ามี IDNO นี้อยู่จริงหรือไม่
				$qryidno=pg_query("select * from \"Fp\" where \"IDNO\"='$IDNO'");
				$numidno=pg_num_rows($qryidno);
				if($numidno==0){
					$check=1;
					$txtstatus="เลขที่สัญญา";
				}else{
					$check=0;
				}
			}else{
				$check=0; //กรณีเป็นค่าอื่นๆ
			}
		}
				
		if($check==0){		
			if($typePay==1){
				//ตรวจสอบว่าเลขที่สัญญานี้มีการจ่ายเช็ค refinance แล้วหรือยัง
				$qryref=pg_query("select * from cheque_pay where \"IDNO\"='$IDNO' and \"typePay\"='1' and \"appStatus\"='1' and \"statusPay\"='TRUE'");
				$numref=pg_num_rows($qryref);
				if($numref>0){ //แสดงว่ามีการจ่ายเช็ค refinance ให้เลขที่สัญญานี้แล้ว
					$check=2;
					$txtcheck="Refinance";
				}else{
					$cusname=$_POST["cusname"];
					list($cusName,$cusPay)=explode("#",$cusname);
					
					$check=0; //สามารถบันทึกข้อมูลได้
				}			
			}else if($typePay==2){
				//ตรวจสอบว่าเลขที่สัญญานี้มีค่าแนะนำหรือไม่
				//ค้นหาชื่อผู้แนะนำโดยนำ IDNO มาตรวจสอบก่อนว่ามีจริงหรือไม่
				$qryidnogui=pg_query("select \"GuidePeople\" from \"nw_IDNOGuidePeople\" where \"IDNO\"='$IDNO'");
				$numidnogui=pg_num_rows($qryidnogui);
				if($numidnogui==0){ //แสดงว่าไม่มีค่าแนะนำ
					$check=4;
				}else{ //กรณีมีค่าแนะนำให้ตรวจสอบขั้นตอนต่อไป
					//ตรวจสอบว่าเลขที่สัญญานี้มีการจ่ายเช็ค ค่าแนะนำ แล้วหรือยัง
					$qryref=pg_query("select * from cheque_pay where \"IDNO\"='$IDNO' and \"typePay\"='2' and \"appStatus\"='1' and \"statusPay\"='TRUE'");
					$numref=pg_num_rows($qryref);
					if($numref>0){ //แสดงว่ามีการจ่ายเช็ค ค่าแนะนำ ให้เลขที่สัญญานี้แล้ว
						$check=2;
						$txtcheck="ค่าแนะนำ";
					}else{
						//ให้หาชื่อผู้แนะนำ
						$qryname=pg_query("select \"GuidePeople\" from \"nw_IDNOGuidePeople\" where \"IDNO\"='$IDNO'");
						$resname=pg_fetch_array($qryname);
						list($cusPay)=$resname;
						
						$check=0; //สามารถบันทึกข้อมูลได้
					}	
				}
			}
		}
	}else{
		$check=3; //แสดงว่ากำลังรออนุมัติ
	}
	
	if($check==1){
		echo "<div style=\"text-align:center;padding:20px;\"><h2><u>$txtstatus</u> ไม่ถูกต้องกรุณาทำรายการใหม่อีกครั้ง</h2></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_AddPaycheque.php'>";
	}else if($check==2){
		echo "<div style=\"text-align:center;padding:20px;\"><h2>เลขที่สัญญานี้ได้จ่ายเช็ค <u>$txtcheck</u> แล้วกรุณาตรวจสอบ</h2></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_AddPaycheque.php'>";
	}else if($check==3){
		echo "<div style=\"text-align:center;padding:20px;\"><h2>เลขที่สัญญานี้กำลังรออนุมัติประเภทการสั่งจ่ายนี้อยู่ กรุณาตรวจสอบ</h2></div>";
		echo "<meta http-equiv='refresh' content='5; URL=frm_AddPaycheque.php'>";
	}else if($check==4){
		echo "<div style=\"text-align:center;padding:20px;\"><h2>เลขที่สัญญานี้ไม่มีค่าแนะนำ กรุณาตรวจสอบ</h2></div>";
		echo "<meta http-equiv='refresh' content='5; URL=frm_AddPaycheque.php'>";
	}else{ //บันทึกข้อมูลตามปกติ
		if($IDNO==""){ $IDNO="null";}else{ $IDNO="'".$IDNO."'"; }
		$ins="INSERT INTO cheque_pay(
				\"BAccount\", \"typePay\", \"IDNO\", \"cusPay\", \"typeChq\", 
				\"moneyPay\", \"datePay\", note, \"keyUser\", \"keyStamp\", \"appStatus\")
				VALUES ('$account', '$typePay', $IDNO, '$cusPay', '$typeChq', 
						'$moneyPay', '$datePay', $note, '$id_user', '$currentdate', '2')";
		if($resin=pg_query($ins)){
		}else{
			$status++;
		}
		
		if($status==0){
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ขอจ่ายเช็ค', '$currentdate')");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "<div style=\"text-align:center;padding:20px;\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></div>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_AddPaycheque.php'>";	
		}else{
			pg_query("ROLLBACK");
			echo "<div style=\"text-align:center;padding:20px;\"><h2>ข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></div>";
			echo "<meta http-equiv='refresh' content='3; URL=frm_AddPaycheque.php'>";
		}
	}
}else if($method=="cancelchq"){
	$chqpayID=$_POST["chqpayID"];
	$result=$_POST["result"];
	
	$ins="INSERT INTO cheque_cancel(
            \"chqpayID\", \"cancelUser\", \"cancelStamp\", \"cancelResult\", 
            \"cancelStatus\")
    VALUES ('$chqpayID','$id_user', '$currentdate', '$result', 
            '2')";
	if($resin=pg_query($ins)){
	}else{
		$status++;
	}
	
	if($status==0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ขอยกเลิกเช็คจ่าย', '$currentdate')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<div style=\"text-align:center;padding:20px;\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></div>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_chqCancel.php'>";	
	}else{
		pg_query("ROLLBACK");
		echo "<div style=\"text-align:center;padding:20px;\"><h2>ข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_chqCancel.php'>";
	}
}else if($method=="sentreport"){
	$condate=$_REQUEST["condate"];
	
	if($condate=="1"){
		$datepicker=$_REQUEST["datepicker"];
		$conday="and date(\"keyStamp\")='$datepicker'";
	}else{
		$month=$_REQUEST["month"];
		$year=$_REQUEST["year"];
		
		$conday="and EXTRACT(MONTH FROM \"keyStamp\")='$month' and EXTRACT(YEAR FROM \"keyStamp\")='$year'";
	}
	
	$typePay=$_REQUEST["typePay"];
	
	if($typePay!=""){
		$contypepay="and a.\"typePay\"='$typePay'";
	}
	
	$company=$_REQUEST["company"];
	if($company!=""){
		$concompany="and replace(c.\"BCompany\",' ','')='$company'";
	}
	
	$cheque=$_REQUEST["cheque"];
	if($cheque!=""){
		//แยกชื่อธนาคารกับสาขาออกจากกัน
		list($cheque1,$cheque2)=explode("/",$cheque);
		$concheque="and replace(c.\"BName\",' ','')='$cheque1' and replace(c.\"BBranch\",' ','')='$cheque2'";
	}
	
	$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"IDNO\",\"cusPay\",\"moneyPay\",\"datePay\",c.\"BAccount\",
		c.\"BName\",\"chequeNum\",c.\"BCompany\",a.\"typeChq\",a.\"note\",d.\"fullname\",\"keyStamp\",\"statusPay\" from cheque_pay a
		left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
		left join \"BankInt\" c on a.\"BAccount\"=c.\"BAccount\"
		left join \"Vfuser\" d on a.\"keyUser\"=d.\"id_user\"
		where a.\"appStatus\"='1' $conday $contypepay $concompany $concheque order by \"keyStamp\",a.\"typePay\"");
	
	$num_rows=pg_num_rows($qrychq);
		echo "<form method=\"post\" action=\"pdf_reportSummary.php\" target=\"_blank\">";
		echo "<input type=\"hidden\" name=\"condate\" value=\"$condate\">";
		echo "<input type=\"hidden\" name=\"datepicker\" value=\"$datepicker\">";
		echo "<input type=\"hidden\" name=\"month\" value=\"$month\">";
		echo "<input type=\"hidden\" name=\"typePay\" value=\"$typePay\">";
		echo "<input type=\"hidden\" name=\"company\" value=\"$company\">";
		echo "<input type=\"hidden\" name=\"cheque\" value=\"$cheque\">";
		echo "<input type=\"hidden\" name=\"year\" value=\"$year\">";
		echo "<div align=right><input type=\"image\" src=\"images/icoPrint.png\" title=\"พิมพ์รายงาน\"></div>";
		echo "</form>";
		echo "
			<table width=\"950\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" bgcolor=\"#CECECE\">
			<tr style=\"font-weight:bold;color:#FFFFFF\" valign=\"top\" bgcolor=\"#026F38\" align=\"center\">
				<th>ประเภท<br>การสั่งจ่าย</th>
				<th>เช็คเลขที่</th>
				<th>เลขที่บัญชี</th>
				<th width=80>เลขที่สัญญา</th>
				<th width=120>สั่งจ่าย</th>
				<th>ประเภทเช็ค</th>
				<th>จำนวนเงิน</th>
				<th>วันที่สั่งจ่าย</th>
				<th>ผู้ทำรายการ</th>
				<th>วันที่ทำรายการ</th>
				<th>สถานะเช็ค</th>
			</tr>
		";
		$i=0;
		$sum=0;
		while($reschq=pg_fetch_array($qrychq)){
			list($chqpayID,$typeName,$IDNO,$cusPay,$moneyPay,$datePay,$BAccount,$BName,$chequeNum,$BCompany,$typeChq,$note,$keyuser,$keyStamp,$statusPay,$typePay)=$reschq;
			if($IDNO=="") $IDNO="-";
			if($BName=="") $BName="-";
			if($BCompany=="")$BCompany="-";
						
			if($typeChq=="1"){
				$typeChqname="ปกติ";
			}else if($typeChq=="2"){
				$typeChqname="A/C PAYEE ONLY";
			}else{
				$typeChqname="&Co.";	
			}
						
			if($statusPay=="t"){
				$statuschq="ปกติ";
			}else{
				$statuschq="ยกเลิก";
			}
						
			$i+=1;
			if($i%2==0){
				echo "<tr bgcolor=#D6FEEA align=\"center\">";
			}else{
				echo "<tr bgcolor=#FFFFFF align=\"center\">";
			}
						
			echo "
				<td>$typeName</td>
				<td>$chequeNum</td>
				<td>$BAccount</td>
				<td>$IDNO</td>
				<td  align=left>$cusPay</td>
				<td>$typeChqname</td>
				<td align=right>".number_format($moneyPay,2)."</td>
				<td align=center>$datePay</td>
				<td align=left>$keyuser</td>
				<td align=center>$keyStamp</td>
				<td>$statuschq</td>
				</tr>
			";
			$sum=$sum+$moneyPay;
		}
		if($num_rows=="0"){
				echo "<tr><td colspan=11 bgcolor=\"#FFFFFF\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
		}else{
			echo "<tr align=right bgcolor=\"#A0FCEA\"><td colspan=6>รวมเงิน</td><td>".number_format($sum,2)."</td><td colspan=4></td></tr>";
		}
		echo "</table>";
}else if($method=="sentprint"){
	$datepicker=$_REQUEST["datepicker"];
	$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"IDNO\",\"cusPay\",\"moneyPay\",\"datePay\",c.\"BAccount\",
				c.\"BName\",\"chequeNum\",c.\"BCompany\",a.\"typeChq\",a.\"note\",d.\"fullname\",\"keyStamp\",\"statusPay\" from cheque_pay a
				left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
				left join \"BankInt\" c on a.\"BAccount\"=c.\"BAccount\"
				left join \"Vfuser\" d on a.\"keyUser\"=d.\"id_user\"
				where a.\"appStatus\"='1' and date(\"keyStamp\")='$datepicker' and \"appStatus\"='1' and \"statusPay\"='TRUE' order by \"keyStamp\",a.\"typePay\"");
	
		$num_rows=pg_num_rows($qrychq);
		echo "
			<table width=\"950\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" bgcolor=\"#CECECE\">
			<tr style=\"font-weight:bold;color:#FFFFFF\" valign=\"top\" bgcolor=\"#026F38\" align=\"center\">
				<th>ประเภท<br>การสั่งจ่าย</th>
				<th>เช็คเลขที่</th>
				<th>เลขที่บัญชี</th>
				<th width=80>เลขที่สัญญา</th>
				<th width=120>สั่งจ่าย</th>
				<th>ประเภทเช็ค</th>
				<th>จำนวนเงิน</th>
				<th>วันที่สั่งจ่าย</th>
				<th>ดูเพิ่มเติม</th>
				<th>พิมพ์</th>
			</tr>
		";
		$i=0;
		$sum=0;
		while($reschq=pg_fetch_array($qrychq)){
			list($chqpayID,$typeName,$IDNO,$cusPay,$moneyPay,$datePay,$BAccount,$BName,$chequeNum,$BCompany,$typeChq,$note,$keyuser,$keyStamp,$statusPay,$typePay)=$reschq;
			if($IDNO=="") $IDNO="-";
			if($BName=="") $BName="-";
			if($BCompany=="")$BCompany="-";
						
			if($typeChq=="1"){
				$typeChqname="ปกติ";
			}else if($typeChq=="2"){
				$typeChqname="A/C PAYEE ONLY";
			}else{
				$typeChqname="&Co.";	
			}
									
			$i+=1;
			if($i%2==0){
				echo "<tr bgcolor=#D6FEEA align=\"center\">";
			}else{
				echo "<tr bgcolor=#FFFFFF align=\"center\">";
			}
						
			echo "
				<td>$typeName</td>
				<td>$chequeNum</td>
				<td>$BAccount</td>
				<td>$IDNO</td>
				<td  align=left>$cusPay</td>
				<td>$typeChqname</td>
				<td align=right>".number_format($moneyPay,2)."</td>
				<td align=center>$datePay</td>
				<td>
					<img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('showdetail.php?chqpayID=$chqpayID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')\" style=\"cursor: pointer;\">
				</td>
				<td align=center><a href=\"pdf_printcheque.php?chqpayID=$chqpayID\" target=\"_blank\"><img src=\"images/icoPrint.png\" width=17 height=14 title=\"พิมพ์รายงาน\"></a></td>
				</tr>
			";
			$sum=$sum+$moneyPay;
		}
		if($num_rows=="0"){
				echo "<tr><td colspan=11 bgcolor=\"#FFFFFF\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
		}else{
			echo "<tr align=right bgcolor=\"#A0FCEA\"><td colspan=6>รวมเงิน</td><td>".number_format($sum,2)."</td><td colspan=4></td></tr>";
		}
		echo "</table>";
}

?>
