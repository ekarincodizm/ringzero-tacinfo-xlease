<?php
session_start();
include("../config/config.php");
$idno = $_POST['idno'];
$tmoney = $_POST['tmoney'];
$signDate = $_POST['signDate'];
$text_name = $_POST['text_name'];
$text_money = $_POST['text_money'];
$txtLawyer2 = $_POST['txtLawyer'];
$txtPenalty2 = $_POST['txtPenalty'];
$txtAct2 = $_POST['txtAct'];
$txtInsurance2 = $_POST['txtInsurance'];
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
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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

//คำนวณหาค่าทนาย
$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
$res_FpFa1=pg_fetch_array($qry_FpFa1);
$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];

$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];

$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
while($resbf=pg_fetch_array($qry_before)){
    $sumamt2+=$resbf["CalAmtDelay"];
}

$qry_amt=@pg_query("select * ,'$signDate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$signDate') "); //รายการที่คำนวณ
while($res_amt=@pg_fetch_array($qry_amt)){
    $s_amt=pg_query("select \"CalAmtDelay\"('$signDate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);
	$sumamt2+=$res_s;
}

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}
$sum_amt=round($sumamt2-$otherpay_amt) ;
//จบคำนวณหาค่าทนายรวมค่าดอกเบี้ยล่าช้า

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
    <td align="left">
	<?php 
		if($st_dueno == $st_dueno_lasts){
			echo "ค่างวดที่ $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2) ." เป็นเงิน"; 
		}else{
			echo "ค่างวดที่ $st_dueno - $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2) ." เป็นเงิน"; 
		}
	?>
	</td>
    <td align="right">
		<input type="text" style="text-align:right" name="txtPayment" id="txtPayment" value="<?php echo round($sum_mv*$nubs,2); ?>" onkeyup="javascript:updateSum()">
		<input type="hidden" name="txtPayment_old" value="<?php echo round($sum_mv*$nubs,2);?>">
		<?php $sumall += ($sum_mv*$nubs);?>
    </td>
</tr>
<?php
if($txtLawyer2 != ""){ $showjava0 = 1;
?>
<tr class="even">
    <td align="left">ค่าทนายรวมค่าดอกเบี้ยล่าช้า</td>
    <td align="right">
		<input type="text" style="text-align:right" name="txtLawyer" id="txtLawyer" value="<?php if($txtLawyer2 == ""){ echo round($tmoney+$sum_amt,2);}else{ echo $txtLawyer2;} ?>" onkeyup="javascript:updateSum()">
		<input type="hidden" name="txtLawyer_old" value="<?php echo round($tmoney+$sum_amt,2);?>">
		<?php $sumall += ($tmoney+$sum_amt);?>
    </td>
</tr>
<?php 
}else{
//echo round($tmoney+$sum_amt,2);
?>
	<input type="hidden" name="txtLawyer_old" value="<?php echo round($tmoney+$sum_amt,2);?>">
<?php
}

if($asset_type == 1 AND $sumamt > 0 AND $txtPenalty2 != ""){ $showjava1 = 1; ?>
<tr class="odd">
    <td align="left">ค่ามิเตอร์+ปรับ</td>
    <td align="right">
		<input type="text" style="text-align:right" name="txtPenalty" id="txtPenalty" value="<?php if($txtPenalty2 == ""){ echo round(($sumamt+1000),2); }else{ echo $txtPenalty2;}?>" onkeyup="javascript:updateSum()">
		<input type="hidden" name="txtPenalty_old" value="<?php echo round(($sumamt+1000),2);?>">
		<?php $sumall += ($sumamt+1000);?>
    </td>
</tr>
<?php 
}else{ 
?>
	<input type="hidden" name="txtPenalty_old" value="<?php echo round(($sumamt+1000),2);?>">
<?php 
}
if($CollectCus1 > 0 AND $txtAct2 != ""){ $showjava2 = 1; 
?>
<tr class="even">
    <td align="left">ค่า พรบ.</td>
    <td align="right">
		<input type="text" style="text-align:right" name="txtAct" id="txtAct" value="<?php if($txtAct2 == ""){ echo round($CollectCus1,2);}else{ echo $txtAct2;} ?>" onkeyup="javascript:updateSum()">
		<input type="hidden" name="txtAct_old" value="<?php echo round($CollectCus1,2);?>">
		<?php $sumall += $CollectCus1;?> 
    </td>
</tr>
<?php }else{ ?>
	<input type="hidden" name="txtAct_old" value="<?php echo round($CollectCus1,2);?>">
<?php 
}
if($CollectCus2 > 0 AND $txtInsurance2 !=""){ $showjava3 = 1; ?>
<tr class="odd">
    <td align="left">ค่าประกันภัย</td>
    <td align="right">
		<input type="text" style="text-align:right" name="txtInsurance" id="txtInsurance" value="<?php if($txtInsurance2 == ""){ echo round($CollectCus2,2);}else{ echo $txtInsurance2;} ?>" onkeyup="javascript:updateSum()">
		<input type="hidden" name="txtInsurance_old" value="<?php echo round($CollectCus2,2);?>">
		<?php $sumall += $CollectCus2; ?>
    </td>
</tr>
<?php }else{ ?>
    <input type="hidden" name="txtInsurance_old" value="<?php echo round($CollectCus2,2);?>">
<?php 
}
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
			<td align="right">
				<input type="text" style="text-align:right" name="txtOther[]" id="txtOther<?php echo $in; ?>" value="<?php echo round($text_money[$i],2); ?>" onkeyup="javascript:updateSum()">
			<?php $sumall += $text_money[$i]; ?>
			</td>
</tr>
<?php
        }
    }
}

?>
<tr class="odd">
    <td align="left"><b>รวมเป็นเงินทั้งสิ้น</b></td>
    <td align="right">
	<span id="showsummary"><?php echo number_format($sumall,2); ?></span>
	</td>
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

<script type="text/javascript">
function updateSum(){
    var sum = 0;
    sum += parseFloat( $('#txtPayment').val() );
    
<?php if($showjava0 == 1){?>
		sum += parseFloat( $('#txtLawyer').val() );
<?php }?>

<?php if($showjava1 == 1){ ?>
    sum += parseFloat( $('#txtPenalty').val() );
<?php } ?>

<?php if($showjava2 == 1){ ?>
    sum += parseFloat( $('#txtAct').val() );
<?php } ?>

<?php if($showjava3 == 1){ ?>
    sum += parseFloat( $('#txtInsurance').val() );
<?php } ?>

<?php
if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        if(!empty($text_name[$i]) && !empty($text_money[$i])){
        $in2 += 1;
?>
        sum += parseFloat( $('#txtOther<?php echo $in2; ?>').val() );
<?php
        }
    }
}
?>
    
    var rsum = sum.toFixed(2);
    console.log(rsum);
    $('#showsummary').text( rsum );
}
</script> 

</body>
</html>