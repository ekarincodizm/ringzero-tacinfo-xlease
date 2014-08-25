<?php
session_start();
include("../../config/config.php");
$cmd = $_REQUEST['cmd'];
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$yearnow=date('Y');
if($cmd == "checktpID"){
    $tpID = $_GET['tpID'];
    $qry_tp=pg_query("select * from account.\"thcap_typePay\" WHERE \"tpID\" = '$tpID'");
    if($res_tp=pg_fetch_array($qry_tp)){
        echo $tpType=$res_tp["tpType"];
    }
}elseif($cmd == "loaddue"){
    $tpID = $_GET['tpID'];
	$id=$_GET['id'];
	
    $qry_tp=pg_query("select * from account.\"thcap_typePay\" WHERE \"tpID\" = '$tpID'");
    if($res_tp=pg_fetch_array($qry_tp)){
       $tpRefType=trim($res_tp["tpRefType"]);
    }
	if($tpRefType=='D'){
		echo "<select name=\"d$id\" id=\"d$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">-วัน-</option>";
			for($i=1;$i<=31;$i++){
				if($i<10){
					$day="0".$i;
				}else{
					$day=$i;
				}
				echo "<option value=$day>$day</option>";
			}
		echo "</select>";
		echo "<select name=\"m$id\" id=\"m$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">----เดือน----</option>";
		echo "<option value=\"01\">มกราคม</option>";
		echo "<option value=\"02\">กุมภาพันธ์</option>";
		echo "<option value=\"03\">มีนาคม</option>";
		echo "<option value=\"04\">เมษายน</option>";
		echo "<option value=\"05\">พฤษภาคม</option>";
		echo "<option value=\"06\">มิถุนายน</option>";
		echo "<option value=\"07\">กรกฎาคม</option>";
		echo "<option value=\"08\">สิงหาคม</option>";
		echo "<option value=\"09\">กันยายน</option>";
		echo "<option value=\"10\">ตุลาคม</option>";
		echo "<option value=\"11\">พฤศจิกายน</option>";
		echo "<option value=\"12\">ธันวาคม</option>";
		echo "</select>";
		echo "<b> ปี ค.ศ.</b><input type=\"text\" name=\"y$id\" id=\"y$id\" size=\"10\" value=\"$yearnow\" style=\"text-align:center;\" maxlength=\"4\" onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
	}else if($tpRefType=='W'){
		echo "<b>รายสัปดาห์</b><br><select name=\"s$id\" id=\"s$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">-สัปดาห์ที่-</option>";
			for($i=1;$i<=4;$i++){
				echo "<option value=\"W$i\">$i</option>";
			}
		echo "</select>";
		echo "<select name=\"m$id\" id=\"m$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">----เดือน----</option>";
		echo "<option value=\"01\">มกราคม</option>";
		echo "<option value=\"02\">กุมภาพันธ์</option>";
		echo "<option value=\"03\">มีนาคม</option>";
		echo "<option value=\"04\">เมษายน</option>";
		echo "<option value=\"05\">พฤษภาคม</option>";
		echo "<option value=\"06\">มิถุนายน</option>";
		echo "<option value=\"07\">กรกฎาคม</option>";
		echo "<option value=\"08\">สิงหาคม</option>";
		echo "<option value=\"09\">กันยายน</option>";
		echo "<option value=\"10\">ตุลาคม</option>";
		echo "<option value=\"11\">พฤศจิกายน</option>";
		echo "<option value=\"12\">ธันวาคม</option>";
		echo "</select>";		
		echo "<b> ปี ค.ศ.</b><input type=\"text\" name=\"y$id\" id=\"y$id\" size=\"10\" value=\"$yearnow\" style=\"text-align:center;\" maxlength=\"4\"  onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
	}else if($tpRefType=='M'){
		echo "<b>รายเดือน</b><select name=\"m$id\" id=\"m$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">----เดือน----</option>";
		echo "<option value=\"01\">มกราคม</option>";
		echo "<option value=\"02\">กุมภาพันธ์</option>";
		echo "<option value=\"03\">มีนาคม</option>";
		echo "<option value=\"04\">เมษายน</option>";
		echo "<option value=\"05\">พฤษภาคม</option>";
		echo "<option value=\"06\">มิถุนายน</option>";
		echo "<option value=\"07\">กรกฎาคม</option>";
		echo "<option value=\"08\">สิงหาคม</option>";
		echo "<option value=\"09\">กันยายน</option>";
		echo "<option value=\"10\">ตุลาคม</option>";
		echo "<option value=\"11\">พฤศจิกายน</option>";
		echo "<option value=\"12\">ธันวาคม</option>";
		echo "</select>";
		echo "<b> ปี ค.ศ.</b><input type=\"text\" name=\"y$id\" id=\"y$id\" size=\"10\" value=\"$yearnow\" style=\"text-align:center;\" maxlength=\"4\" onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
	}else if($tpRefType=='Y'){
		echo "<b>รายปี</b>ปี ค.ศ.<input type=\"text\" name=\"y$id\" id=\"y$id\" size=\"10\" value=\"$yearnow\" style=\"text-align:center;\" maxlength=\"4\" onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
	}else if($tpRefType=='L'){
		echo "<b>ช่วงใดๆ เริ่มตั้งแต่</b><br><select name=\"d$id\" id=\"d$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">-วัน-</option>";
			for($i=1;$i<=31;$i++){
				if($i<10){
					$day="0".$i;
				}else{
					$day=$i;
				}
				echo "<option value=$day>$day</option>";
			}
		echo "</select>";
		echo "<select name=\"m$id\" id=\"m$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">----เดือน----</option>";
		echo "<option value=\"01\">มกราคม</option>";
		echo "<option value=\"02\">กุมภาพันธ์</option>";
		echo "<option value=\"03\">มีนาคม</option>";
		echo "<option value=\"04\">เมษายน</option>";
		echo "<option value=\"05\">พฤษภาคม</option>";
		echo "<option value=\"06\">มิถุนายน</option>";
		echo "<option value=\"07\">กรกฎาคม</option>";
		echo "<option value=\"08\">สิงหาคม</option>";
		echo "<option value=\"09\">กันยายน</option>";
		echo "<option value=\"10\">ตุลาคม</option>";
		echo "<option value=\"11\">พฤศจิกายน</option>";
		echo "<option value=\"12\">ธันวาคม</option>";
		echo "</select>";
		echo "<b> ปี ค.ศ.</b><input type=\"text\" name=\"y$id\" id=\"y$id\" size=\"10\" value=\"$yearnow\" style=\"text-align:center;\" maxlength=\"4\" onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
		
		echo "<br><b>ถึงวันที่</b><br>";
		echo "<select name=\"dd$id\" id=\"dd$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">-วัน-</option>";
			for($i=1;$i<=31;$i++){
				if($i<10){
					$day="0".$i;
				}else{
					$day=$i;
				}
				echo "<option value=$day>$day</option>";
			}
		echo "</select>";
		echo "<select name=\"mm$id\" id=\"mm$id\" onchange=\"javascript:amtDue($id,'$tpRefType')\">";
		echo "<option value=\"\">----เดือน----</option>";
		echo "<option value=\"01\">มกราคม</option>";
		echo "<option value=\"02\">กุมภาพันธ์</option>";
		echo "<option value=\"03\">มีนาคม</option>";
		echo "<option value=\"04\">เมษายน</option>";
		echo "<option value=\"05\">พฤษภาคม</option>";
		echo "<option value=\"06\">มิถุนายน</option>";
		echo "<option value=\"07\">กรกฎาคม</option>";
		echo "<option value=\"08\">สิงหาคม</option>";
		echo "<option value=\"09\">กันยายน</option>";
		echo "<option value=\"10\">ตุลาคม</option>";
		echo "<option value=\"11\">พฤศจิกายน</option>";
		echo "<option value=\"12\">ธันวาคม</option>";
		echo "</select>";
		echo "<b> ปี ค.ศ.</b><input type=\"text\" name=\"yy$id\" id=\"yy$id\" size=\"10\" value=\"$yearnow\" style=\"text-align:center;\" maxlength=\"4\" onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
	}else if($tpRefType=='ID'){
		echo "<b>ตามหนังสือหรือรหัสใบ</b><input type=\"text\" name=\"bookin$id\" id=\"bookin$id\" size=\"20\" style=\"text-align:center;\"  onkeyup=\"javascript:amtDue($id,'$tpRefType')\">";
	}
}else if($cmd == "checkRefType"){
	$tpID = $_GET['tpID'];
	$qry_tp=pg_query("select * from account.\"thcap_typePay\" WHERE \"tpID\" = '$tpID'");
    if($res_tp=pg_fetch_array($qry_tp)){
       echo $tpRefType=trim($res_tp["tpRefType"]);
    }
}elseif($cmd == "checkfixed"){
	$day=$_GET['inday'];
	$month=$_GET['intmount'];
	$year=$_GET['intyear'];
	$tpID=$_GET['tpID'];
	$invoicedate=$year."-".$month."-".$day;
	
	$qry_amt=pg_query("SELECT \"tpAmtFixed\" FROM account.\"thcap_typePay_fixed\" where \"tpID\"='$tpID' and '$invoicedate' between \"tpEffDate\" and \"tpEndDate\"");
	$num_rows=pg_num_rows($qry_amt);
	if($num_rows==0){
		$qry_amt2=pg_query("SELECT \"tpAmtFixed\" FROM account.\"thcap_typePay_fixed\" where \"tpID\"='$tpID' and \"tpEndDate\" is null");
		$num_rows2=pg_num_rows($qry_amt2);
		if($num_rows2==0){
			echo $tpAmtFixed=0;
		}else{
			if($res_amt2=pg_fetch_array($qry_amt2)){
				echo $tpAmtFixed=$res_amt2["tpAmtFixed"];
			}
		}
	}else{
		if($res_amt=pg_fetch_array($qry_amt)){
			echo $tpAmtFixed=$res_amt["tpAmtFixed"];
		}
	}
}elseif($cmd == "save"){
    pg_query("BEGIN WORK");
    $status = 0;
    $payment = json_decode(stripcslashes($_POST["payment"]));
    $ConID = $_POST["ConID"];
    $nowdate = nowDate();
    $iduser = $_SESSION["av_iduser"];
	$descript = $_POST["descript"]; 
	if($descript==""){
		$descript="NULL";
	}else{
		$descript="'".$descript."'";
	}

	$vat=7;
	$wht=3;
    foreach($payment as $key => $value){
        $a1 = $value->setdate;
        $a2 = $value->tpID;
        $a3 = $value->invoiceAmt;
		$a4 = $value->resultset;
		$a5 = $value->refdoc;
		$a6 = $value->DueDate;
      
        if( empty($a1) and empty($a2) and empty($a3) and empty($a4) and empty($a5) and empty($a6)){
                continue;   
        }
		$qry_type=pg_query("select * from account.\"thcap_typePay\" WHERE \"tpID\" = '$a2'");
		if($res_type=pg_fetch_array($qry_type)){
				$tpRefType=$res_type["tpRefType"];
		}
		if($tpRefType=='RUNNING'){
			$qry_tp=pg_query("select * from account.thcap_invoice WHERE \"invoiceTypePay\" = '$a2' order by \"invoiceTypePayRef\" DESC limit(1)");
			$numrow=pg_num_rows($qry_tp);
			if($numrow==0){
				$a5=1;
			}else{
				if($res_tp=pg_fetch_array($qry_tp)){
					$invoiceTypePayRef=$res_tp["invoiceTypePayRef"];
					$a5=$invoiceTypePayRef+1;
				}
			}
		}else{
			$a5=$a5;
		}
		
		$amtvat=($a3*$vat)/100;
		$amtwht=($a3*wht)/100;
		
		$qry=pg_query("select account.thcap_ins_invoice(
						'$ConID',
						'MG',
						'THCAP',
						'$a1',
						'$a6',
						'$a2',
						'$a5', 
						NULL,
						'$a4',
						'$a3',
						'$vat',
						'$amtvat',
						'$wht',
						$amtwht,
						'$id_user',
						'$currentdate',
						$descript 
						)"); 
		
    }
    
    if($status == 0){
        pg_query("COMMIT");
        echo "1";
	   //echo $a5;
    }else{
        pg_query("ROLLBACK");
	    echo "2";
    }
}
?>