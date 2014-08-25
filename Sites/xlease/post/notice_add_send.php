<?php
session_start();
include("../config/config.php");  
$get_userid = $_SESSION["av_iduser"];
$officeid=$_SESSION["av_officeid"];
$idno = $_POST['idno'];
$tmoney = $_POST['tmoney'];
$signDate = $_POST['signDate'];
$text_name = $_POST['text_name'];
$text_money = $_POST['text_money'];
$nowdate = Date('Y-m-d');
$keyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$txtPayment = $_POST['txtPayment'];
$txtLawyer = $_POST['txtLawyer']; //ค่าทนายรวมค่าดอกเบี้ยล่าช้า add ปกติ
$txtPenalty = $_POST['txtPenalty']; //ค่ามิเตอร์ + ปรับ add ปกติ
$txtAct = $_POST['txtAct']; //ค่า พรบ. add ปกติ
$txtInsurance = $_POST['txtInsurance']; //ค่าประกันภัย add ปกติ
$txtOther= $_POST['txtOther'];

$txtPayment_old = $_POST['txtPayment_old'];
$txtLawyer_old = $_POST['txtLawyer_old'];  //ค่าทนายรวมค่าดอกเบี้ยล่าช้า add ตาราง log_nt
$txtPenalty_old = $_POST['txtPenalty_old']; //ค่ามิเตอร์ + ปรับ add ตาราง log_nt
$txtAct_old = $_POST['txtAct_old']; //ค่า พรบ. add ตาราง log_nt
$txtInsurance_old = $_POST['txtInsurance_old']; //ค่าประกันภัย add ตาราง log_nt

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>    
    
</head>
<body>

<fieldset><legend><B>ออก NT</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;
$genid=pg_query("select generate_id('$nowdate','$officeid',3)");
$r_genid=pg_fetch_result($genid,0);

//อัพเดทค่าทนายในตาราง Fp
$up_sql = "Update \"Fp\" SET \"P_LAWERFEEAmt\"='$tmoney' ,\"P_LAWERFEE\"='TRUE' WHERE \"IDNO\"='$idno'";
if(!$result=pg_query($up_sql)){
    $status++;
}

//ยกเลิกข NT เก่าก่อน
$qry_notice=pg_query("select * from \"NTHead\" where \"IDNO\"='$idno' and \"cancel\"='FALSE'");
$num_notice=pg_num_rows($qry_notice);
if($num_notice>0){
	$result_cancel=pg_query("Update \"NTHead\" SET \"cancel\"='true' WHERE \"IDNO\"='$idno'"); 
	if(!$result_cancel){
		$status++;
	}
}
$in_sql="insert into \"NTHead\" (\"NTID\",\"IDNO\",\"do_date\",\"to_date\",\"makerid\",\"CusState\") values ('$r_genid','$idno','$nowdate','$signDate','$get_userid','0')";
if( !pg_query($in_sql) ){
    $status++;
}

//insert ข้อมูลในตาราง nw_statusNT สำหรับเก็บสถานะว่ารออนุมัติ
$ins_sts="insert into \"nw_statusNT\"(\"NTID\", \"IDNO\", \"statusNT\") values ('$r_genid','$idno','0')";
if(!pg_query($ins_sts) ){
    $status++;
}

/* ==================================================================================== */
$qry_fp=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno';");
if($res_fp=pg_fetch_array($qry_fp)){
    $asset_type = $res_fp["asset_type"];
    $asset_id = $res_fp["asset_id"];
    $P_STDATE = $res_fp["P_STDATE"];
    $CusID = $res_fp["CusID"];
    $P_MONTH = $res_fp["P_MONTH"];
    $P_VAT = $res_fp["P_VAT"];
    $sum_mv = $P_MONTH+$P_VAT;
}

$qry_fa1=pg_query("select * from \"Fa1\" WHERE \"CusID\"='$CusID';");
if($res_fa1=pg_fetch_array($qry_fa1)){
    $A_FIRNAME = trim($res_fa1["A_FIRNAME"]);
    $A_NAME = trim($res_fa1["A_NAME"]);
    $A_SIRNAME = trim($res_fa1["A_SIRNAME"]);
}

if($asset_type == 1){
    $qry_fc=pg_query("select * from \"VCarregistemp\" WHERE \"IDNO\"='$idno';");
    if($res_fc=pg_fetch_array($qry_fc)){
        $C_CARNAME = $res_fc["C_CARNAME"];
        $C_REGIS = $res_fc["C_REGIS"];
    }
}else{
    $qry_fc=pg_query("select * from \"FGas\" WHERE \"GasID\"='$asset_id';");
    if($res_fc=pg_fetch_array($qry_fc)){
        $gas_number = $res_fc["gas_number"];
    }
}


$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null and \"IDNO\"='$idno';");
while($resvc=pg_fetch_array($qry_vcus)) {
    $p += 1;
    $DueDate = $resvc["DueDate"];
    $DueNo = $resvc["DueNo"];

    $s_amt=pg_query("select \"CalAmtDelay\"('$signDate','$DueDate',$sum_mv)"); 
    $res_amt=pg_fetch_result($s_amt,0);
    
    if($p == 1){
        $start_due = $DueDate;
        $st_dueno = $DueNo;
        
        $in_sql7="update \"NTHead\" set \"remine_date\"='$start_due' WHERE \"NTID\"='$r_genid'";
        if( pg_query($in_sql7) ){

        }else{
            $status++;
        }
        
    }
    
   
    
    list($n_year,$n_month,$n_day) = split('-',$DueDate);
    list($b_year,$b_month,$b_day) = split('-',$signDate);
    list($a_year,$a_month,$a_day) = split('-',$nowdate);

    $date_1 = mktime(0, 0, 0, $n_month, $n_day, $n_year);
    $date_2 = mktime(0, 0, 0, $b_month, $b_day, $b_year);
    $date_3 = mktime(0, 0, 0, $a_month, $a_day, $a_year);
    
    if($date_1 < $date_2){
            $nub += 1;
            $st_dueno_last = $DueNo;
            $sum_amt += $res_amt;
    }
    
    if($date_1 < $date_3){
            $nubs += 1;
            $st_dueno_lasts = $DueNo;
            $sum_amts += $res_amt;
    }
    
}

$st_dueno_last2 = $st_dueno_lasts+1;
$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null AND \"IDNO\"='$idno' AND \"DueNo\"='$st_dueno_last2';");
if($resvc=pg_fetch_array($qry_vcus)) {
    $get_DueDate = $resvc["DueDate"];
}

if(!empty($get_DueDate)){
    $get_DueDate_thai=pg_query("select conversiondatetothaitext('$get_DueDate')");
    $get_DueDate_thai_show=pg_fetch_result($get_DueDate_thai,0);
}

$qry_fp=pg_query("select sum(\"CusAmt\") as sumamt from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$idno' AND \"cuspaid\"='false';");
if($res_fp=pg_fetch_array($qry_fp)){
    $sumamt = $res_fp["sumamt"];
}

$qry_if=pg_query("select \"CollectCus\" from insure.\"InsureForce\" WHERE \"IDNO\"='$idno' AND \"CusPayReady\"='false';");
if($res_if=pg_fetch_array($qry_if)){
    $CollectCus1 = $res_if["CollectCus"];
}

$qry_uif=pg_query("select \"CollectCus\" from insure.\"InsureUnforce\" WHERE \"IDNO\"='$idno' AND \"CusPayReady\"='false';");
if($res_uif=pg_fetch_array($qry_uif)){
    $CollectCus2 = $res_uif["CollectCus"];
}

$qry_vcus1=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null AND \"IDNO\"='$idno' AND \"DueNo\"='$st_dueno_lasts';");
if($resvc1=pg_fetch_array($qry_vcus1)) {
    $get_PDate = $resvc1["DueDate"];
}

if(!empty($get_PDate)){
    $get_PDate_thai=pg_query("select conversiondatetothaitext('$get_PDate')");
    $get_PDate_thai_show=pg_fetch_result($get_PDate_thai,0);
}

$cccc = split(' ',trim($get_PDate_thai_show));
$p_due_thai_show_list = $cccc[1]." ".$cccc[2]." ".$cccc[3];


$start_due_thai=pg_query("select conversiondatetothaitext('$start_due')");
$start_due_thai_show=pg_fetch_result($start_due_thai,0);

$aaaa = split(' ',trim($start_due_thai_show));

$start_due_thai_show_list = $aaaa[1]." ".$aaaa[2]." ".$aaaa[3];

/* ==================================================================================== */
if($st_dueno == $st_dueno_lasts){
	$y1="ค่างวดที่ $st_dueno_lasts ($p_due_thai_show_list) : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2);
}else{
	$y1="ค่างวดที่ $st_dueno - $st_dueno_lasts ($start_due_thai_show_list - $p_due_thai_show_list) : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2);
}

$y1_m=$txtPayment;
$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y1','$y1_m','true')";
if(pg_query($in_sql2) ){

}else{
    $status++;
}
$insnull=0;
if($txtPayment == $txtPayment_old){
}else{
	//เก็บในตาราง log
	$y1_m=$txtPayment_old;
	$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y1','$y1_m','true','$keyDate','$get_userid')";
	if(pg_query($in_sql2) ){

	}else{
		$status++;
	}
	$insnull++;
}

/*-------------------------ค่าทนายรวมค่าดอกเบี้ยล่าช้า--------------------------------*/
$y2="ค่าทนายรวมค่าดอกเบี้ยล่าช้า";
//$y2_m=($tmoney+$sum_amt);
if($txtLawyer != ""){ //กรณีที่มีการเลือกค่าทนายให้ add ในฐานปกติแต่ถ้าพบว่าไม่มีค่ีาให้ add ในฐานใหม่ด้วย
	$y2_m=$txtLawyer;
	$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y2','$y2_m','true')";
	if(pg_query($in_sql2) ){

	}else{
		$status++;
	}
}
if($txtLawyer == $txtLawyer_old){
}else{
	//add ค่าทนายเก่าในตาราง log
	$y2_m=$txtLawyer_old;
	$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y2','$y2_m','true','$keyDate','$get_userid')";
	if(pg_query($in_sql2) ){

	}else{
		$status++;
	}
	$insnull++;
}
/*-------------------------จบค่าทนายรวมค่าดอกเบี้ยล่าช้า--------------------------------*/

/*-------------------------ค่ามิเตอร์+ปรับ--------------------------------*/
$y3="ค่ามิเตอร์+ปรับ";
if($txtPenalty != ""){
	if($asset_type == 1 AND $sumamt > 0){
		$y3_m=$txtPenalty;
		$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y3','$y3_m','true')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
if($txtPenalty == $txtPenalty_old){
}else{
	//add ค่าในตาราง log
	if($asset_type == 1 AND $sumamt > 0){
		$y3_m=$txtPenalty_old;
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y3','$y3_m','true','$keyDate','$get_userid')";
		if(pg_query($in_sql2) ){

		}else{
			$status++;
		}
		$insnull++;
	}
}
/*-------------------------จบค่ามิเตอร์+ปรับ--------------------------------*/

/*-------------------------ค่า พรบ.--------------------------------*/
$y4="ค่า พรบ.";
if($txtAct != ""){
	if($CollectCus1 > 0){	
		$y4_m=$txtAct;
		$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y4','$y4_m','true')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
if($txtAct == $txtAct_old){
}else{
	//add ค่าในตาราง log
	
	if($CollectCus1 > 0){
		$y4_m=$txtAct_old;
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values('$r_genid','$y4','$y4_m','true','$keyDate','$get_userid')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
		$insnull++;
	}
}

/*-------------------------จบค่า พรบ.--------------------------------*/

/*-------------------------ค่าประกันภัย--------------------------------*/
$y5="ค่าประกันภัย";
if($txtInsurance != ""){
	if($CollectCus2 > 0){
		$y5_m=$txtInsurance;
		$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y5','$y5_m','true')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
if($txtInsurance == $txtInsurance_old){
}else{
	//add ค่าในตาราง log
	if($CollectCus2 > 0){
		$y5_m=$txtInsurance_old;
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values ('$r_genid','$y5','$y5_m','true','$keyDate','$get_userid')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
		$insnull++;
	}
}
/*-------------------------จบค่าประกันภัย--------------------------------*/

/*-------------------------ค่าเพิ่มเติม--------------------------------*/
if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        $y6="$text_name[$i]";
        //$y6_m=$text_money[$i];
        $y6_m=$txtOther[$i];
        $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y6','$y6_m','true')";
        if( pg_query($in_sql2) ){

        }else{
            $status++;
        }
		
		//เก็บในตาราง log ด้วย
		/*
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y6','$y6_m','true','$keyDate','$get_userid')";
        if( pg_query($in_sql2) ){

        }else{
            $status++;
        }
		*/
		
    }
}

if(!empty($get_DueDate)){
    $y7="หากชำระล่าช้ากว่า วันที่ $get_DueDate_thai_show จะต้องชำระค่างวดเพิ่มอีก 1 งวด";
    $y7_m=$sum_mv;
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y7','$y7_m','false')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
	
	/*$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y7','$y7_m','false','$keyDate','$get_userid')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
	*/
}

if($insnull==0){
	$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values ('$r_genid','0','0','true','$keyDate','$get_userid')";
	if( pg_query($in_sql2) ){
	}else{
		$status++;
	}
}

/* =================================ผู้ค้ำ================================================ */

$qry_fa1=pg_query("select * from \"ContactCus\" WHERE \"IDNO\"='$idno' AND \"CusState\" > 0 ORDER BY \"CusState\" ASC;");
while($res_fa1=pg_fetch_array($qry_fa1)){
    $get_CusID = trim($res_fa1["CusID"]);
    $get_CusState = trim($res_fa1["CusState"]);

    $genid=pg_query("select generate_id('$nowdate','$officeid',3)");
    $r_genid=pg_fetch_result($genid,0);
    
    $in_sql="insert into \"NTHead\" (\"NTID\",\"IDNO\",\"do_date\",\"to_date\",\"makerid\",\"CusState\") values  ('$r_genid','$idno','$nowdate','$signDate','$get_CusID','$get_CusState')";
    if( pg_query($in_sql) ){

    }else{
        $status++;
    }
	
	//insert ข้อมูลในตาราง nw_statusNT สำหรับเก็บสถานะว่ารออนุมัติ

	$ins_sts="insert into \"nw_statusNT\"(\"NTID\", \"IDNO\", \"statusNT\") values ('$r_genid','$idno','0')";
	if(!pg_query($ins_sts) ){
		$status++;
	}
    
/* =========== */
if($st_dueno == $st_dueno_lasts){
	$y1="ค่างวดที่ $st_dueno_lasts ($p_due_thai_show_list) : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2);
}else{
	$y1="ค่างวดที่ $st_dueno - $st_dueno_lasts ($start_due_thai_show_list - $p_due_thai_show_list) : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2);
}
//$y1_m=($sum_mv*$nubs);
$y1_m=$txtPayment;
$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y1','$y1_m','true')";
if( pg_query($in_sql2) ){

}else{
    $status++;
}
//keep to log
//เก็บในตาราง log
if($txtPayment == $txtPayment_old){
}else{
	$y1_m=$txtPayment_old;
	$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y1','$y1_m','true','$keyDate','$get_userid')";
	if(pg_query($in_sql2) ){

	}else{
		$status++;
	}
}

/***********************ค่าทนายรวมค่าดอกเบี้ยล่าช้า***********************/
$y2="ค่าทนายรวมค่าดอกเบี้ยล่าช้า";
if($txtLawyer != ""){
	//$y2_m=($tmoney+$sum_amt);
	$y2_m=$txtLawyer;
	$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y2','$y2_m','true')";
	if( pg_query($in_sql2) ){

	}else{
		$status++;
	}
}
if($txtLawyer == $txtLawyer_old){
}else{
	$y2_m=$txtLawyer_old;
	$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y2','$y2_m','true','$keyDate','$get_userid')";
	if( pg_query($in_sql2) ){

	}else{
		$status++;
	}
}
/***********************จบค่าทนายรวมค่าดอกเบี้ยล่าช้า***********************/

/***********************ค่ามิเตอร์+ปรับ***********************/
$y3="ค่ามิเตอร์+ปรับ";
if($txtPenalty != ""){
	if($asset_type == 1 AND $sumamt > 0){
		//$y3_m=($sumamt+1000);
		$y3_m=$txtPenalty;
		$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y3','$y3_m','true')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
if($txtPenalty == $txtPenalty_old){
}else{
	if($asset_type == 1 AND $sumamt > 0){
		$y3_m=$txtPenalty_old;
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y3','$y3_m','true','$keyDate','$get_userid')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
/***********************จบค่ามิเตอร์+ปรับ***********************/

/***********************ค่า พรบ.***********************/
$y4="ค่า พรบ.";
if($txtAct != ""){
	if($CollectCus1 > 0){
		//$y4_m=$CollectCus1;
		$y4_m=$txtAct;
		$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y4','$y4_m','true')";
		if( pg_query($in_sql2) ){
	
		}else{
			$status++;
		}
	}
}
if($txtAct == $txtAct_old){
}else{
	if($CollectCus1 > 0){
		$y4_m=$txtAct_old;
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y4','$y4_m','true','$keyDate','$get_userid')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
/***********************จบค่า พรบ.***********************/

/***********************ค่าประกันภัย***********************/
$y5="ค่าประกันภัย";
if($txtInsurance != ""){
	if($CollectCus2 > 0){
		//$y5_m=$CollectCus2;
		$y5_m=$txtInsurance;
		$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y5','$y5_m','true')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}
if($txtInsurance == $txtInsurance_old){
}else{
	if($CollectCus2 > 0){
		$y5_m=$txtInsurance_old;
		$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y5','$y5_m','true','$keyDate','$get_userid')";
		if( pg_query($in_sql2) ){

		}else{
			$status++;
		}
	}
}

/***********************จบค่าประกันภัย***********************/
if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        if(!empty($text_name[$i]) && !empty($text_money[$i])){
            $y6="$text_name[$i]";
            //$y6_m=$text_money[$i];
            $y6_m=$txtOther[$i];
            $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y6','$y6_m','true')";
            if( pg_query($in_sql2) ){

            }else{
                $status++;
            }
			
			/*$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y6','$y6_m','true','$keyDate','$get_userid')";
            if( pg_query($in_sql2) ){

            }else{
                $status++;
            }
			*/
        }
    }
}

if(!empty($get_DueDate)){
    $y7="หากชำระล่าช้ากว่า วันที่ $get_DueDate_thai_show จะต้องชำระค่างวดเพิ่มอีก 1 งวด";
    $y7_m=$sum_mv;
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y7','$y7_m','false')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
	
	/*$in_sql2="insert into \"logs_NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\",\"keyDate\",\"id_user\") values  ('$r_genid','$y7','$y7_m','false','$keyDate','$get_userid')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
	*/
}



$in_sql7="update \"NTHead\" set \"remine_date\"='$start_due' WHERE \"NTID\"='$r_genid'";
if( pg_query($in_sql7) ){

}else{
    $status++;
}


/* =========== */

    
}


if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) สร้าง NT', '$keyDate')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้ในขณะนี้";   
}
?>


<!--<form name="frm_1" action="notice_add_pdf.php" method="post" target="_blank">-->
<input type="hidden" name="idno" value="<?php echo $idno; ?>" />
<input type="hidden" name="tmoney" value="<?php echo $tmoney; ?>" />
<input type="hidden" name="signDate" value="<?php echo $signDate; ?>" />
<input type="hidden" name="txtPayment" value="<?php echo $txtPayment; ?>" />
<input type="hidden" name="txtLawyer" value="<?php echo $txtLawyer; ?>" />
<input type="hidden" name="txtPenlty" value="<?php echo $txtPenlty; ?>" />
<input type="hidden" name="txtAct" value="<?php echo $txtAct; ?>" />
<input type="hidden" name="txtInsurance" value="<?php echo $txtInsurance; ?>" />
<?php
for($i=0;$i<count($text_name);$i++){
    echo "<input type=\"hidden\" name=\"text_name[]\" value=\"$text_name[$i]\" />";
}
for($i=0;$i<count($text_money);$i++){
    echo "<input type=\"hidden\" name=\"text_money[]\" value=\"$txtOther[$i]\" />";
}
?>
<input type="submit" value="  ปิด  " onclick="javascript:RefreshMe();" />
<!--</form>-->

</div>
</fieldset> 

</body>
</html>