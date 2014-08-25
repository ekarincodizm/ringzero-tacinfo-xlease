<?php
session_start();
include("../config/config.php");
$nowdate = Date('Y-m-d');
$idno = pg_escape_string($_GET['idno']);
$bl = pg_escape_string($_GET['bl']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>  

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function confirm1(Url,name){
  if (confirm("ยืนยันการยกเลิกหยุด VAT")){
    popU(Url,name,'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');
  }
}

function payment(){
    var num = document.getElementById('payment_num');
    var mny = document.getElementById('payment_money');
    var last = document.getElementById('payment_last');
    document.frm_1.payment_tmoney.value = num.value * mny.value;

    if(last.value==num.value){
        document.getElementById('s1').style.display = '';
    }else{
        document.getElementById('s1').style.display = 'none';
        document.frm_1.payment_smoney.value = 0;
    }
}

function datechage(){
    var signDate = document.getElementById('signDate');
    var DueDate = document.getElementById('DueDate');
    var nowdate = document.getElementById('nowdate');
    
    if(signDate.value <= DueDate.value){
        alert('วันที่ ห้ามน้อยกว่า งวดสุดท้าย');
    }
    if(signDate.value > nowdate.value){
        alert('วันที่ ห้ามมากกว่า วันที่ปัจจุบัน');
    }
    
}

</script>

    </head>
<body>

<fieldset><legend><B>Cancel Stop VAT</B></legend>
<form name="frm_1" action="cancel_stopvat_add_ok.php" method="post">
<table width="100%">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="80%"><?php echo $idno; ?></td>
</tr>
<tr>
    <td><b>เงินรับฝาก</b></td>
    <td><input type="text" size="13" style="text-align:right;" id="money" name="money" value="<?php echo $bl; ?>" style="background-color:#E0E0E0;" readonly /></td>
</tr>
<tr>
    <td><b>วันที่ออกใบเสร็จ</b></td>
    <td><input type="text" size="13" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo nowDate(); ?>" onchange="javascript:datechage();" /><input name="button2" type="button" onclick="displayCalendar(document.frm_1.signDate,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
</tr>
<tr>
    <td><b>จำนวนงวด</b></td>
    <td>
<select id="payment_num" name="payment_num" onchange="javascript:payment();">
<?php
$qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$idno'");
$res_fp=pg_fetch_array($qry_fp);
    $P_MONTH=$res_fp["P_MONTH"]; $P_MONTH = round($P_MONTH,2);
    $P_VAT=$res_fp["P_VAT"];  $P_VAT = round($P_VAT,2);
    $fp_ptotal=$res_fp["P_TOTAL"];

$qry_vcus=pg_query("select COUNT(\"R_Receipt\") as \"count_rec\" from \"VCusPayment\" WHERE \"IDNO\"='$idno' AND \"R_Receipt\" is not null");
$res_vcus=pg_fetch_array($qry_vcus);
    $count_rec=$res_vcus["count_rec"];
    
$qry_vcus=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE \"IDNO\"='$idno' AND \"R_Receipt\" is not null ORDER BY \"DueNo\" DESC");
$res_vcus=pg_fetch_array($qry_vcus);
    $DueDate=$res_vcus["DueDate"];
    
$total_for = $fp_ptotal-$count_rec;
for($i=1;$i<=$total_for;$i++){
    echo "<option value=\"$i\">$i</option>";
}
?>
</select>
เป็นเงิน <input type="text" id="payment_tmoney" name="payment_tmoney" value="<?php echo ($P_MONTH+$P_VAT); ?>" style="background-color:#E0E0E0;" readonly>
<span id="s1" style="display:none;">ส่วนลด <input type="text" id="payment_smoney" name="payment_smoney" value="0"></span>
<input type="hidden" id="payment_last" name="payment_last" value="<?php echo $total_for; ?>">
<input type="hidden" id="payment_money" name="payment_money" value="<?php echo ($P_MONTH+$P_VAT); ?>">
<input type="hidden" id="idno" name="idno" value="<?php echo $idno; ?>">
<input type="hidden" id="DueDate" name="DueDate" value="<?php echo $DueDate; ?>">
<input type="hidden" id="nowdate" name="nowdate" value="<?php echo $nowdate; ?>">
    </td>
</tr>
<tr>
    <td></td>
    <td><input type="submit" name="ok1" value="บันทึก" /></td>
</tr>
</table>

</form>

</fieldset> 

<div align="center"><br><input type="button" value="    ปิดหน้านี้    " onclick="javascript:window.close(); javascript:RefreshMe();"></div>

</body>
</html>