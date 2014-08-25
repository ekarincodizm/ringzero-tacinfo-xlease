<?php
session_start();

include("../config/config.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$cmd = $_REQUEST['cmd'];

if($cmd == "checkacclose"){
    $idno = $_GET['idno'];
    $qry_fp=pg_query("select \"P_ACCLOSE\" from \"Fp\" WHERE \"IDNO\" = '$idno'");
    if($res_fp=pg_fetch_array($qry_fp)){
        echo $P_ACCLOSE=$res_fp["P_ACCLOSE"];
    }
}elseif($cmd == "loaddue"){
    $id = $_GET['id'];
    $idno = $_GET['idno'];

    $qry_vcus=pg_query("select COUNT(\"IDNO\") AS cidno from \"VCusPayment\" WHERE \"IDNO\" = '$idno' AND \"R_Date\" is null");
    if($res_vcus=pg_fetch_array($qry_vcus)){
        
        $count_idno=$res_vcus["cidno"];
        
        echo "จำนวนงวด <select id=\"cbDue$id\" name=\"cbDue$id\" onchange=\"javascript:amtDue($id,$count_idno,'$idno')\">";
        echo "<option value=\"0\">0</option>";
        for($i=1; $i<=$count_idno; $i++){
            echo "<option value=\"$i\">$i</option>";
        }
        echo "</select>";
    }
}elseif($cmd == "loaddueamt"){
    $idno = $_GET['idno'];
    $qry_fp=pg_query("select \"P_MONTH\",\"P_VAT\" from \"Fp\" WHERE \"IDNO\" = '$idno'");
    if($res_fp=pg_fetch_array($qry_fp)){
        $P_MONTH=$res_fp["P_MONTH"];
        $P_VAT=$res_fp["P_VAT"];
        $sum = $P_MONTH+$P_VAT;
        echo "$sum";
    }
}elseif($cmd == "loaddiscount"){
    $id = $_GET['id'];
    $idno = $_GET['idno'];
    $nowdate = nowDate();
    
    $rs=pg_query("select \"discount_close\"('$nowdate','$idno')");
    $discount_close=pg_fetch_result($rs,0);
    $discount_close = round($discount_close,2);
    
    echo ' ส่วนลด <input type="text" style="text-align:right" name="txtDiscount'.$id.'" id="txtDiscount'.$id.'" size="12" value="'.$discount_close.'" onkeyup="javascript:updateAmount('.$id.')">';
}elseif($cmd == "load134"){
    $id = $_GET['id'];
    echo 'จำนวน <input type="text" name="txtkr'.$id.'" id="txtkr'.$id.'" size="5" value="1" style="text-align:right" onkeyup="javascript:updateAmount134('.$id.')">';
}elseif($cmd == "load_join1"){
    $id = $_GET['id'];
    echo '<input type="button" name="txtkr'.$id.'" id="txtkr'.$id.'" size="5" value="คำนวณค่าเข้าร่วม" onclick="windowOpen(\'../nw/join_cal/join_cal.php?idno='.$_GET[idno].'&inputName='.$_GET[inputName].'&change_pay_type='.$_GET[change_pay_type].'&page_name='.$_GET[page_name].'&pay_date='.$_GET[pay_date].'\');" >';
}elseif($cmd == "load134amt"){
    $idno = $_GET['idno'];
    $qry_vcus=pg_query("select amt from corporate.\"VCorpContact\" WHERE \"IDNO\" = '$idno' ");
    if($res_vcus=pg_fetch_array($qry_vcus)){
        echo $amt = $res_vcus["amt"];
    }else{
        echo 0;
    }
}elseif($cmd == "save"){
    pg_query("BEGIN WORK");
    $status = 0;
    $payment = json_decode(stripcslashes($_POST["payment"]));
    $cusid = $_POST["cusid"];
    $nowdate = nowDate();
    $iduser = $_SESSION["av_iduser"];

    $rs=pg_query("select \"gen_pos_no\"('$nowdate')");
    $rt1=pg_fetch_result($rs,0);

    $in_sql="insert into \"PostLog\" (\"PostID\",\"UserIDPost\",\"PostDate\",\"paytype\",\"AcceptPost\") values  ('$rt1','$iduser','$nowdate','CA','false')";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
    
    foreach($payment as $key => $value){
        $a1 = $value->idno;
        $a2 = $value->typepay;
        $a3 = $value->amount;
        if($a2 == 0){
            if( empty($a1) or empty($a3) ){
                continue;
            }
        }else{
            if( empty($a1) or empty($a2) or empty($a3) ){
                continue;
            }   
        }
        $in_sql2="insert into \"FCash\" (\"PostID\",\"CusID\",\"IDNO\",\"TypePay\",\"AmtPay\") values ('$rt1','$cusid','$a1','$a2','$a3')";
        if(!$result2=pg_query($in_sql2)){
            $status++;
        }
    }
    
    if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ลงรายการชำระเงินสด', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "1";
    }else{
        pg_query("ROLLBACK");
        echo "2";
    }
}elseif($cmd == "savetac"){
    pg_query("BEGIN WORK");
    $status = 0;
    $payment = json_decode(stripcslashes($_POST["payment"]));
    $cusid = $_POST["cusid"];
    $nowdate = nowDate();
    $iduser = $_SESSION["av_iduser"];
	$chknum=$_POST["chknum"];
	$chkbank=$_POST["chkbank"];
	$chkbrance=$_POST["chkbrance"];
	$chkdate=$_POST["chkdate"];

    $rs=pg_query("select \"gen_pos_no\"('$nowdate')");
    $rt1=pg_fetch_result($rs,0);

    $in_sql="insert into \"PostLog\" (\"PostID\",\"UserIDPost\",\"PostDate\",\"paytype\",\"AcceptPost\") values  ('$rt1','$iduser','$nowdate','TC','false')";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
	
    //customer name
    $qry_user=pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$cusid'");
	$resuser=pg_fetch_array($qry_user);
	$fullname=$resuser["full_name"];
	
    foreach($payment as $key => $value){
        $a1 = $value->idno;
        $a2 = $value->typepay;
        $a3 = $value->amount;
        if($a2 == 0){
            if( empty($a1) or empty($a3) ){
                continue;
            }
        }else{
            if( empty($a1) or empty($a2) or empty($a3) ){
                continue;
            }   
        }
		//carregis
		$qry_carregis=pg_query("select \"car_regis\",\"C_REGIS\" from \"Fp\" a
		left join \"VCarregistemp\" b on a.\"IDNO\"=b.\"IDNO\"
		left join \"FGas\" c on a.asset_id=c.\"GasID\"
		where a.\"IDNO\"='$a1'");
		$res_car=pg_fetch_array($qry_carregis);
		$car_regis=$res_car["car_regis"];
		$C_REGIS=$res_car["C_REGIS"];
		if($C_REGIS==""){
			$carregis=$car_regis;
		}else{
			$carregis=$C_REGIS;
		}
		
        $in_sql2="insert into \"FTACCheque\" (\"PostID\",\"COID\",\"TypePay\",\"AmtPay\",\"D_ChequeNo\",\"D_BankName\",\"D_BankBranch\",\"D_DateEntBank\",\"fullname\",\"carregis\") values ('$rt1','$a1','$a2','$a3','$chknum','$chkbank','$chkbrance','$chkdate','$fullname','$carregis')";
        if(!$result2=pg_query($in_sql2)){
            $status++;
        }
    }
    
    if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ลงรายการชำระเงิน TAC-เช็ค', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "1";
    }else{
        pg_query("ROLLBACK");
        echo "2";
    }
}elseif($cmd == "savetactr"){
    pg_query("BEGIN WORK");
    $status = 0;
    $payment = json_decode(stripcslashes($_POST["payment"]));
    $cusid = $_POST["cusid"];
    $nowdate = nowDate();
    $iduser = $_SESSION["av_iduser"];
	$chknum=$_POST["chknum"];
	$chkbank=$_POST["chkbank"];
	$t_hr=$_POST["t_hr"];
	$t_m=$_POST["t_m"];
	$t_s=$_POST["t_s"];
	$chkdate=$_POST["chkdate"];
	$D_DatetimeEnterBank=$chkdate." "."$t_hr:$t_m:$t_s";

    $rs=pg_query("select \"gen_pos_no\"('$nowdate')");
    $rt1=pg_fetch_result($rs,0);

    $in_sql="insert into \"PostLog\" (\"PostID\",\"UserIDPost\",\"PostDate\",\"paytype\",\"AcceptPost\") values  ('$rt1','$iduser','$nowdate','TT','false')";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
	
    //customer name
    $qry_user=pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$cusid'");
	$resuser=pg_fetch_array($qry_user);
	$fullname=$resuser["full_name"];
	
    foreach($payment as $key => $value){
        $a1 = $value->idno;
        $a2 = $value->typepay;
        $a3 = $value->amount;
        if($a2 == 0){
            if( empty($a1) or empty($a3) ){
                continue;
            }
        }else{
            if( empty($a1) or empty($a2) or empty($a3) ){
                continue;
            }   
        }
		//carregis
		$qry_carregis=pg_query("select \"car_regis\",\"C_REGIS\" from \"Fp\" a
		left join \"VCarregistemp\" b on a.\"IDNO\"=b.\"IDNO\"
		left join \"FGas\" c on a.asset_id=c.\"GasID\"
		where a.\"IDNO\"='$a1'");
		$res_car=pg_fetch_array($qry_carregis);
		$car_regis=$res_car["car_regis"];
		$C_REGIS=$res_car["C_REGIS"];
		if($C_REGIS==""){
			$carregis=$car_regis;
		}else{
			$carregis=$C_REGIS;
		}
		
        $in_sql2="insert into \"FTACTran\" (\"PostID\",\"COID\",fullname,carregis,\"TypePay\",\"AmtPay\",\"D_BankName\",\"D_BankAccount\",\"D_DatetimeEnterBank\") values ('$rt1','$a1','$fullname','$carregis','$a2','$a3','$chkbank','$chknum','$D_DatetimeEnterBank')";
        if(!$result2=pg_query($in_sql2)){
            $status++;
        }
    }
    
    if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ลงรายการชำระเงิน TAC-TR', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "1";
    }else{
        pg_query("ROLLBACK");
        echo "2";
    }
}
?>