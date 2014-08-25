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
<!--
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
// -->
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

if($result=pg_query("Update \"Fp\" SET \"P_LAWERFEEAmt\"='$tmoney' ,\"P_LAWERFEE\"='TRUE' WHERE \"IDNO\"='$idno';")){
    
}else{
    $status++;
}

$in_sql="insert into \"NTHead\" (\"NTID\",\"IDNO\",\"do_date\",\"to_date\",\"makerid\",\"CusState\") values  ('$r_genid','$idno','$nowdate','$signDate','$get_userid','0')";
if( pg_query($in_sql) ){

}else{
    $status++;
}

/* ==================================================================================== */
/* ==================================================================================== */
/* ==================================================================================== */
/* ==================================================================================== */
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
/* ==================================================================================== */
/* ==================================================================================== */
/* ==================================================================================== */
/* ==================================================================================== */
/* ==================================================================================== */

$y1="ค่างวดที่ $st_dueno - $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2);
$y1_m=($sum_mv*$nubs);
$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y1','$y1_m','true')";
if( pg_query($in_sql2) ){

}else{
    $status++;
}


$y2="ค่าทนายรวมค่าดอกเบี้ยล่าช้า";
$y2_m=($tmoney+$sum_amt);
$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y2','$y2_m','true')";
if( pg_query($in_sql2) ){

}else{
    $status++;
}


if($asset_type == 1 AND $sumamt > 0){
    $y3="ค่ามิเตอร์+ปรับ";
    $y3_m=($sumamt+1000);
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y3','$y3_m','true')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
}

if($CollectCus1 > 0){
    $y4="ค่า พรบ.";
    $y4_m=$CollectCus1;
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y4','$y4_m','true')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
}

if($CollectCus2 > 0){
    $y5="ค่าประกันภัย";
    $y5_m=$CollectCus2;
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y5','$y5_m','true')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
}

if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        $y6="$text_name[$i]";
        $y6_m=$text_money[$i];
        $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y6','$y6_m','true')";
        if( pg_query($in_sql2) ){

        }else{
            $status++;
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
}

/* ================================================================================= */

$qry_fa1=pg_query("select * from \"ContactCus\" WHERE \"IDNO\"='$idno' AND \"CusState\" > 0 ORDER BY \"CusState\" ASC;");
while($res_fa1=pg_fetch_array($qry_fa1)){
    $get_CusID = trim($res_fa1["CusID"]);
    $get_CusState = trim($res_fa1["CusState"]);

    $genid=pg_query("select generate_id('$nowdate','$officeid',3)");
    $r_genid=pg_fetch_result($genid,0);
    
    $in_sql="insert into \"NTHead\" (\"NTID\",\"IDNO\",\"do_date\",\"to_date\",\"makerid\",\"CusState\") values  ('$r_genid','$idno','$nowdate','$signDate','$get_userid','$get_CusState')";
    if( pg_query($in_sql) ){

    }else{
        $status++;
    }
    
/* =========== */
$y1="ค่างวดที่ $st_dueno - $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2);
$y1_m=($sum_mv*$nubs);
$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y1','$y1_m','true')";
if( pg_query($in_sql2) ){

}else{
    $status++;
}


$y2="ค่าทนายรวมค่าดอกเบี้ยล่าช้า";
$y2_m=($tmoney+$sum_amt);
$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y2','$y2_m','true')";
if( pg_query($in_sql2) ){

}else{
    $status++;
}


if($asset_type == 1 AND $sumamt > 0){
    $y3="ค่ามิเตอร์+ปรับ";
    $y3_m=($sumamt+1000);
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y3','$y3_m','true')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
}

if($CollectCus1 > 0){
    $y4="ค่า พรบ.";
    $y4_m=$CollectCus1;
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y4','$y4_m','true')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
}

if($CollectCus2 > 0){
    $y5="ค่าประกันภัย";
    $y5_m=$CollectCus2;
    $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y5','$y5_m','true')";
    if( pg_query($in_sql2) ){

    }else{
        $status++;
    }
}

if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        if(!empty($text_name[$i]) && !empty($text_money[$i])){
            $y6="$text_name[$i]";
            $y6_m=$text_money[$i];
            $in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values  ('$r_genid','$y6','$y6_m','true')";
            if( pg_query($in_sql2) ){

            }else{
                $status++;
            }
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
}



$in_sql7="update \"NTHead\" set \"remine_date\"='$start_due' WHERE \"NTID\"='$r_genid'";
if( pg_query($in_sql7) ){

}else{
    $status++;
}


/* =========== */

    
}


if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้ในขณะนี้";   
}
?>


<form name="frm_1" action="notice_add_pdf.php" method="post" target="_blank">
<input type="hidden" name="idno" value="<?php echo $idno; ?>" />
<input type="hidden" name="tmoney" value="<?php echo $tmoney; ?>" />
<input type="hidden" name="signDate" value="<?php echo $signDate; ?>" />
<?php
for($i=0;$i<count($text_name);$i++){
    echo "<input type=\"hidden\" name=\"text_name[]\" value=\"$text_name[$i]\" />";
}
for($i=0;$i<count($text_money);$i++){
    echo "<input type=\"hidden\" name=\"text_money[]\" value=\"$text_money[$i]\" />";
}
?>
<input type="submit" value="  Print  " onclick="javascript:RefreshMe();" />
</form>

</div>
</fieldset> 

</body>
</html>