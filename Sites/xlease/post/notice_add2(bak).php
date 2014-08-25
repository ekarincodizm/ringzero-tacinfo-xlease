<?php
session_start();
include("../config/config.php");
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
    
<script type="text/javascript">
function submitform1(){
    document.frm_1.submit();
}
function submitform2(){
    document.frm_2.submit();
}
</script>     

</head>
<body>

<fieldset><legend><B>ออก NT</B></legend>

<form name="frm_1" action="notice_add_send.php" method="post">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<table width="100%">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="80%"><?php echo $idno; ?></td>
</tr>
<tr>
    <td><b>ค่าทนาย</b></td>
    <td><?php echo $tmoney; ?><input type="hidden" name="tmoney" value="<?php echo $tmoney; ?>" /></td>
</tr>
<tr>
    <td><b>คิดถึงวันที่</b></td>
    <td><?php echo $signDate; ?><input type="hidden" name="signDate" value="<?php echo $signDate; ?>" /></td>
</tr>
</table>
<br>

<?php

/* ========================================================================================================= */
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

$qry_uif=pg_query("select \"NTID\" from \"NTHead\" WHERE \"IDNO\"='$idno' AND \"CusState\"='0';");
if($res_uif=pg_fetch_array($qry_uif)){
    $NTID = $res_uif["NTID"];
}

/* ========================================================================================================= */

?>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center" width="75%">รายละเอียด</td>
        <td align="center" width="25%">ยอดเงิน</td>
    </tr>
    <tr class="odd">
        <td align="left"><?php echo "ค่างวดที่ $st_dueno - $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2) ." เป็นเงิน"; ?></td>
        <td align="right"><?php echo number_format($sum_mv*$nubs,2); $sumall += ($sum_mv*$nubs); ?></td>
    </tr>
    <tr class="even">
        <td align="left">ค่าทนายรวมค่าดอกเบี้ยล่าช้า</td>
        <td align="right"><?php echo number_format($tmoney+$sum_amt,2); $sumall += ($tmoney+$sum_amt); ?></td>
    </tr>

<?php if($asset_type == 1 AND $sumamt > 0){ ?>
    <tr class="odd">
        <td align="left">ค่ามิเตอร์+ปรับ</td>
        <td align="right"><?php echo number_format(($sumamt+1000),2); $sumall += ($sumamt+1000); ?></td>
    </tr>
<?php } ?>

<?php if($CollectCus1 > 0){ ?>
    <tr class="even">
        <td align="left">ค่า พรบ.</td>
        <td align="right"><?php echo number_format($CollectCus1,2); $sumall += $CollectCus1; ?></td>
    </tr>
<?php } ?>

<?php if($CollectCus2 > 0){ ?>
    <tr class="odd">
        <td align="left">ค่าประกันภัย</td>
        <td align="right"><?php echo number_format($CollectCus2,2); $sumall += $CollectCus2; ?></td>
    </tr>
<?php } ?>
    
<?php
if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        if(!empty($text_name[$i]) && !empty($text_money[$i])){
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="left"><?php echo "$text_name[$i]"; ?><input type="hidden" id="text_name" name="text_name[]" value="<?php echo $text_name[$i]; ?>"><input type="hidden" id="text_money" name="text_money[]" value="<?php echo $text_money[$i]; ?>"></td>
        <td align="right"><?php echo number_format($text_money[$i],2); $sumall += $text_money[$i]; ?></td>
    </tr>
<?php
        }
    }
}

?>
    <tr class="odd">
        <td align="left"><b>รวมเป็นเงินทั้งสิ้น</b></td>
        <td align="right"><?php echo number_format($sumall,2); ?></td>
    </tr>
</table>

</form>

<form name="frm_2" action="notice_add.php" method="post">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<input type="hidden" name="tmoney" value="<?php echo $tmoney; ?>">
<input type="hidden" name="signDate" value="<?php echo $signDate; ?>">

<?php
if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        if(!empty($text_name[$i]) && !empty($text_money[$i])){
?>
<input type="hidden" id="text_name" name="text_name[]" value="<?php echo $text_name[$i]; ?>">
<input type="hidden" id="text_money" name="text_money[]" value="<?php echo $text_money[$i]; ?>">
<?php
        }
    }
}
?>
</form>

<table width="100%">
    <tr>
        <td align="left"><input type="button" value="  แก้ไข  " onclick="javascript:submitform2();"></td>
        <td align="right"><input type="button" value="บันทึกข้อมูล" onclick="javascript:submitform1();"></td>
    </tr>
</table>

</fieldset> 


</body>
</html>