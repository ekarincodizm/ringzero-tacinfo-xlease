<?php
include("../../config/config.php");
$cmd = $_REQUEST['cmd'];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
if($cmd == "save"){
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
        $a1 = $value->COID;
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
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(TAL) ตั้งรายการวิทยุ (ลูกค้านอก) - เงินสด', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "1";
    }else{
        pg_query("ROLLBACK");
        echo "2";
    }
}
else if($cmd == "save2"){
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
        $a1 = $value->COID;
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
		$qry_carregis=pg_query("select * from \"RadioContract\" where \"COID\"='$a1'");
		$res_car=pg_fetch_array($qry_carregis);
		$carregis=$res_car["RadioCar"];
        $in_sql2="insert into \"FTACCheque\" (\"PostID\",\"COID\",\"TypePay\",\"AmtPay\",\"D_ChequeNo\",\"D_BankName\",\"D_BankBranch\",\"D_DateEntBank\",\"fullname\",\"carregis\") values ('$rt1','$a1','$a2','$a3','$chknum','$chkbank','$chkbrance','$chkdate','$fullname','$carregis')";
        if(!$result2=pg_query($in_sql2)){
            $status++;
        }
    }
    
    if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(TAL) ตั้งรายการวิทยุ (ลูกค้านอก) - TAC เช็ค', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "1";
    }else{
        pg_query("ROLLBACK");
        echo "2";
    }
}
else if($cmd == "save3"){
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
        $a1 = $value->COID;
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
		$qry_carregis=pg_query("select * from \"RadioContract\" where \"COID\"='$a1'");
		$res_car=pg_fetch_array($qry_carregis);
		$carregis=$res_car["RadioCar"];
        $in_sql2="insert into \"FTACTran\" (\"PostID\",\"COID\",fullname,carregis,\"TypePay\",\"AmtPay\",\"D_BankName\",\"D_BankAccount\",\"D_DatetimeEnterBank\") values ('$rt1','$a1','$fullname','$carregis','$a2','$a3','$chkbank','$chknum','$D_DatetimeEnterBank')";
        if(!$result2=pg_query($in_sql2)){
            $status++;
        }
    }
    
    if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(TAL) ตั้งรายการวิทยุ (ลูกค้านอก) - TAC TR', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "1";
    }else{
        pg_query("ROLLBACK");
        echo "2";
    }
}
?>