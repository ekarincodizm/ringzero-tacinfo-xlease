<?php
include("../config/config.php");

$idno=$_SESSION["ses_idno"];

if(empty($idno)){
    header("Location: frm_cuspayment.php");
}

if(empty($_POST["signDate"])){
    $ssdate = nowDate();
}else{
    $ssdate=$_POST["signDate"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8">
    <meta http-equiv="Pragma" content="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>    

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}

$(function(){
    $(window).bind("beforeunload",function(event){
        closeAll();
    });
});
</script>    

<style type="text/css">
<!--
body {font-family:tahoma; color : #333333; font-size:12px;}
H1 {font-family:tahoma; color : #333333; font-size:28px;}
A { font-size:12px; text-decoration:none;}
A:hover { color : #8B8B8B; font-size:12px; text-decoration:none;}
A:visited { color : #333333; font-size:12px; text-decoration:none;} 
input,select{font-family:tahoma; color : #333333; font-size:12px;}
.header{
    text-align:center;       
}
.wrapper{
    width:700; float:center; padding:0px;
}
legend{
    font-family: Tahoma;
    font-size: 14px;    
    color: #0000CC;
}
legend A{ color : #0000CC; font-size: 14px; text-decoration:none;}
legend A:hover{ color : #0000CC; font-size: 14px; text-decoration:none;}
legend A:visited{ color : #0000CC; font-size: 14px; text-decoration:none;}
fieldset{
    padding:3px;
}
.text_gray{
    color:gray;
}
.text_comment{
    color:red;
    font-size: 11px;
}
.odd{
    background-color:#FFFFD7;
    font-size:11px
}
.even{
    background-color:#FFFFCA;
    font-size:11px
}
.result {
    /*font-size: 11px;
    line-height: 20px;*/
    height: 400px;
    width: 100%;
    overflow: auto;
    border: 0px solid #C0C0C0;
    background-color: #FFFFFF;
    padding: 0px;
}
-->
</style>

<script language="JavaScript">
       var HttPRequest = false;

       function doCallAjax() { 
          HttPRequest = false;
          if (window.XMLHttpRequest) { // Mozilla, Safari,...
             HttPRequest = new XMLHttpRequest();
             if (HttPRequest.overrideMimeType) {
                HttPRequest.overrideMimeType('text/html');
             }
          } else if (window.ActiveXObject) { // IE
             try {
                HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
             } catch (e) {
                try {
                   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
             }
          } 
          
          if (!HttPRequest) {
             alert('Cannot create XMLHTTP instance');
             return false;
          }
    
            var url = 'ajax_query.php';
            var pmeters = 'signDate='+document.getElementById("signDate").value;
            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);
            
            HttPRequest.onreadystatechange = function()
            {

                 if(HttPRequest.readyState == 3)  // Loading Request
                  {
                   document.getElementById("mySum").innerHTML  = "Now is Loading...";
                  }

                 if(HttPRequest.readyState == 4) // Return Request
                  {
                   document.getElementById("mySum").innerHTML  = HttPRequest.responseText;
                  }
                
            }

            /*
            HttPRequest.onreadystatechange = call function .... // Call other function
            */

       }
</script>

</head>
<body>

<?php include "menu.php"; ?>

<?php
$list_idno=$_SESSION["ses_list_idno"];
$stdate=$_SESSION["ses_h_start"];
$s_payment_nonvat=$_SESSION["ses_payment_nonvat"];
$s_payment_vat=$_SESSION["ses_payment_vat"];
$s_payment_all=$_SESSION["ses_payment_all"];
$f_date=$_SESSION["ses_start_date"];
$f_dateth=$_SESSION["ses_start_dateth"];
$ldate=$_SESSION["ses_last_date"];
$fullname=$_SESSION["ses_a_fullname"];
$regis=$_SESSION["ses_regis"];
$r_number=$_SESSION["ses_r_number"];
$s_year=$_SESSION["ses_year"];
$s_expdate=$_SESSION["ses_expdate"];
$s_ccolor=$_SESSION["ses_ccolor"];
$s_ccarname=$_SESSION["ses_ccarname"];
$s_radioid=$_SESSION["ses_radioid"];
$s_fp_ptotal=$_SESSION["ses_fp_ptotal"];
$s_LAWERFEE=$_SESSION["ses_LAWERFEE"];
$s_ACCLOSE=$_SESSION["ses_ACCLOSE"];
$s_StopVat=$_SESSION["ses_StopVat"];
$s_dp_balance=$_SESSION["ses_dp_balance"];
?>
 
<div class="wrapper">

<form method="post" action="frm_close_cuspayment.php" name="f_list" id="f_list">

<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td align="left" valign="middle" colspan="5">
            <b>คำนวณปิดบัญชี เริ่มวันที่</b>
            <input type="text" size="12" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo $ssdate; ?>" />
            <input name="button2" type="button" onclick="displayCalendar(document.f_list.signDate,'yyyy-mm-dd',this)" value="ปฏิทิน" /><input name="btnButton" id="btnButton" type="submit" value="คำนวณ" />
        </td>
    </tr>
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td valign="middle" colspan="3"><b>ชื่อ/สกุล</b> <?php echo $fullname. " (".$idno.")"; ?></td>
        <td align="right" colspan="1"><input type="button" value="พิมพ์รายงาน" onclick="window.open('frm_print_cal_cuspayment.php?idno=<?php echo $idno; ?>&date=<?php echo $ssdate; ?>&f_date=<?php echo $f_date; ?>&stdate=<?php echo $stdate; ?>&ldate=<?php echo $ldate; ?>')"></td>
    </tr>
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td align="left" valign="middle"><b>วันทำสัญญา</b> <?php echo $f_dateth; ?><br><b>ทะเบียน</b> <?php echo $regis; ?><br><b>เลขตัวถัง</b> <a href="../up/frm_show.php?id=<?php echo $r_number; ?>&type=reg&mode=2" target="_blank"><u><?php echo $r_number; ?></u></a></td>
        <td valign="middle"><b>RadioID</b> <?php echo $s_radioid; ?><br><b>ประเภทรถ</b> <?php echo $s_ccarname; ?><br><b>สีรถ</b> <?php echo $s_ccolor; ?></td>
        <td align="right" valign="middle"><b>ค่างวดไม่รวม VAT</b> <?php echo number_format($s_payment_nonvat,2); ?><br><b>VAT</b> <?php echo number_format($s_payment_vat,2); ?><br><b>ค่างวดรวม VAT</b> <u><?php echo number_format($s_payment_all,2); ?></u></td>
        <td align="right" valign="middle">
        <b>Deposit Balance</b> <span style="color:red; font-weight:bold;"><?php echo number_format($s_dp_balance,2); ?></span><br>
        <b>จำนวนงวดทั้งหมด</b> <?php echo $s_fp_ptotal; ?><br>
        <b>วันที่หมดอายุภาษี</b> <?php echo $s_expdate; ?><br>
        <b>ปีรถ</b> <?php echo $s_year; ?></td>
    </tr>

<?php
$sum_outstanding1 = 0;
$qry_inf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $sum_outstanding1 = $res_inf["sum_outstanding"];
}

$sum_outstanding2 = 0;
$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding2 = $res_inuf["sum_outstanding"];
}

$qry_amt=pg_query("select \"CusAmt\",\"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDNO\"='$idno' ");
$nub_amt = pg_num_rows($qry_amt);

if($nub_amt > 0 OR $sum_outstanding1 > 0 OR $sum_outstanding2 > 0){
?>
    <tr bgcolor="#FFC0C0" align="left">
        <td colspan="4"><b>ยอดค้าง</b></td>
    </tr>
<?php
}

while($res_amt=pg_fetch_array($qry_amt)){
    $CusAmt = $res_amt["CusAmt"];
    $TypeDep = $res_amt["TypeDep"];
    
    $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
    if($res_nn=pg_fetch_array($qry_nn)){
        $TName = $res_nn["TName"];
    }
    
    if($CusAmt != 0){
?>
    <tr bgcolor="#FFC0C0" align="left">
        <td><?php echo $TName; ?></td><td colspan="3"><?php echo number_format($CusAmt,2); ?></td>
    </tr>
<?php
    }
}
?>    
    
<?php if($sum_outstanding1 != 0){ ?>
    <tr bgcolor="#FFC0C0" align="left">
        <td>ประกันภัยภาคบังคับ (พรบ.)</td><td colspan="3"><?php echo number_format($sum_outstanding1,2); ?></td>
    </tr>
<?php
    }
    if($sum_outstanding2 != 0){
?>
    <tr bgcolor="#FFC0C0" align="left">
        <td>ประกันภัยภาคสมัครใจ</td><td colspan="3"><?php echo number_format($sum_outstanding2,2); ?></td>
    </tr>
<?php
    }
?>
    
<?php
if($s_LAWERFEE == 't' || $s_ACCLOSE == 't' || $s_StopVat == 't'){
?>
    <tr bgcolor="#E6FFE6">
        <td align="center" colspan="4">
<?php 
if($s_LAWERFEE == 't'){
    echo '<img src="picflash1.gif" border="0" width="120" height="50">';
}
if($s_ACCLOSE == 't'){
    echo '<img src="picflash2.gif" border="0" width="120" height="50">';
}
if($s_StopVat == 't'){
    echo '<img src="picflash3.gif" border="0" width="120" height="50">';
}
?>
        </td>
    </tr>
<?php
}
?>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0"  align="left">
    <tr bgcolor="#A8D3FF" style="font-size:11px; font-weight:none"  align="center" valign="middle">
        <td width="7%">DueNo.</td>
        <td width="7%">DueDate<br />(วันนัดจ่าย)</td>
        <td width="8%">R_Date<br />(วันทีี่จ่าย)</td>
        <td width="7%">daydelay<br />(วันจ่ายล่าช้า)</td>
        <td width="8%">caldelay<br />(ยอดจ่ายล่าช้า)</td>
        <td width="10%">R_Receipt<br />(เลขที่ใบเสร็จ)</td>
        <td width="7%">PayType</td>
        <td width="7%">V_Receipt<br />(เลขที่ใบvat)</td>
        <td width="7%">V_date<br />(วันที่จ่ายvat)</td>
        <td width="6%">ค่างวดรวม<br>vat</td>
        <td width="6%">ยอดต้อง<br>ชำระ</td>
        <td width="10%">ยอดค้างเช่าซื้อ<br>ทั้งหมด รวม vat</td>
        <td width="10%">ยอดค้างเช่าซื้อ<br>ทั้งหมด ไม่รวม vat</td>
    </tr>


<?php
$count_idno = count($list_idno);
for($b=0; $b<$count_idno; $b++){ // วนลูป IDNO ทั้งหมด
    $b_plus=$b+1;

    $qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
    $res_VCusPayment=pg_fetch_array($qry_VCusPayment);
    $stdate=$res_VCusPayment["DueDate"];

    $qry_VCusPayment_last=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$list_idno[$b]') order by \"DueDate\" desc LIMIT(1)");
    $res_VCusPayment_last=pg_fetch_array($qry_VCusPayment_last);
    $ldate=$res_VCusPayment_last["DueDate"];

    $qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$list_idno[$b]'");
    $res_FpFa1=pg_fetch_array($qry_FpFa1);
        $s_payment_nonvat = $res_FpFa1["P_MONTH"];
        $s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
        $s_fp_ptotal = $res_FpFa1["P_TOTAL"];

    $money_all_in_vat = $s_payment_all*$s_fp_ptotal;
    $money_all_no_vat = $s_payment_nonvat*$s_fp_ptotal;


    $qry_fullname=pg_query("select \"full_name\" from \"VContact\" WHERE \"IDNO\"='$list_idno[$b]'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $full_name=$res_fullname["full_name"];
    }
    
    if($b==0){
        echo "<tr style=\"font-size:12px; background-color:#F0F0F0; font-weight:bold\">
        <td colspan=11>ลำดับที่ $b_plus : $full_name ($list_idno[$b])</td>
        <td align=right><b>".number_format($money_all_in_vat,2). "</b></td>
        <td align=right><b>".number_format($money_all_no_vat,2). "</b></td>
        </tr>";
    }else{
        echo "<tr style=\"font-size:12px; background-color:#F0F0F0; font-weight:bold\">
        <td colspan=11>ลำดับที่ $b_plus : $full_name ($list_idno[$b])</td>
        <td align=right><b>".number_format($tmp_1,2). "</b></td>
        <td align=right><b>".number_format($tmp_2,2). "</b></td>
        </tr>";
    }
    
    if(($b_plus) != $count_idno){
    
    $qry_before=pg_query("select * from \"VCusPayment\" WHERE (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)");
    while($resbf=pg_fetch_array($qry_before)){
?>  
    <tr style="font-size:11px; background-color:#B3DBAE;" align=center>
        <td width="7%"><?php echo $resbf["DueNo"]; ?></td>
        <td width="7%"><?php echo $resbf["DueDate"]; ?></td>
        <td width="8%"><?php echo $resbf["R_Date"]; ?></td>
        <td width="7%"><?php echo $resbf["daydelay"]; ?></td>
        <td width="8%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%"><?php echo $resbf["R_Receipt"]; ?></td>
        <td width="7%"><?php if(empty($resbf['R_Bank']) && empty($resbf['PayType'])){ }else{ echo "$resbf[R_Bank] / $resbf[PayType]"; } ?></td>
        <td width="7%"><?php echo $resbf["V_Receipt"]; ?></td>
        <td width="7%"><?php echo $resbf["V_Date"]; ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["R_Money"]+$resbf["VatValue"],2); ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($resbf["DueNo"]*$s_payment_all) ,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat),2); ?></td>
    </tr>
<?php
    $tmp_1 = $money_all_in_vat-($resbf["DueNo"]*$s_payment_all);
    $tmp_2 = $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat);
}

    }else{
        
$qry_before=pg_query("select * from \"VCusPayment\" WHERE (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)");
while($resbf=pg_fetch_array($qry_before)){
?>  
    <tr style="font-size:11px; background-color:#B3DBAE;" align=center>
        <td width="7%"><?php echo $resbf["DueNo"]; ?></td>
        <td width="7%"><?php echo $resbf["DueDate"]; ?></td>
        <td width="8%"><?php echo $resbf["R_Date"]; ?></td>
        <td width="7%"><?php echo $resbf["daydelay"]; ?></td>
        <td width="8%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%"><?php echo $resbf["R_Receipt"]; ?></td>
        <td width="7%"><?php if(empty($resbf['R_Bank']) && empty($resbf['PayType'])){ }else{ echo "$resbf[R_Bank] / $resbf[PayType]"; } ?></td>
        <td width="7%"><?php echo $resbf["V_Receipt"]; ?></td>
        <td width="7%"><?php echo $resbf["V_Date"]; ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["R_Money"]+$resbf["VatValue"],2); ?></td>
        <td width="6%" align="right"><?php echo number_format($resbf["CalAmtDelay"],2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($resbf["DueNo"]*$s_payment_all) ,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat),2); ?></td>
    </tr>
<?php
    $sumamt+=$resbf["CalAmtDelay"];
    $sumamt2+=$resbf["CalAmtDelay"];
    $last_DueDate = $resbf["DueDate"];
}


$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($last_DueDate)));
$qry_amt=@pg_query("select * ,'$ssdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]')  AND (\"DueDate\" BETWEEN '$DateUpdate' AND '$ldate') "); 
while($res_amt=@pg_fetch_array($qry_amt)){
    $s_amt=pg_query("select \"CalAmtDelay\"('$ssdate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);
?>  
  <tr style="font-size:11px; background-color:#C6FFC6;" align=center>
        <td width="7%"><?php echo $res_amt["DueNo"]; ?></td>
        <td width="7%"><?php echo $res_amt["DueDate"]; ?></td>
        <td width="8%"><?php echo $ssdate; ?></td>
        <td width="7%"><?php echo $res_amt["dateA"]; ?></td>
        <td width="8%" align="right"><?php echo number_format($res_s,2); ?></td>        
        <td width="10%"><?php echo $res_amt["R_Receipt"]; ?></td>
        <td width="7%"><?php if(empty($res_amt['R_Bank']) && empty($res_amt['PayType'])){ }else{ echo "$res_amt[R_Bank] / $res_amt[PayType]"; } ?></td>
        <td width="7%"><?php echo $res_amt["V_Receipt"]; ?></td>
        <td width="7%"><?php echo $res_amt["V_Date"]; ?></td>
        <td width="6%" align="right"><?php echo number_format($s_payment_all,2); ?></td>
        <td width="6%" align="right"><?php echo number_format($s_payment_all+$res_s,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_in_vat-($res_amt["DueNo"]*$s_payment_all) ,2); ?></td>
        <td width="10%" align=right><?php echo number_format( $money_all_no_vat-($res_amt["DueNo"]*$s_payment_nonvat),2); ?></td>
   </tr>
<?php
    $sum=$s_payment_all+$res_s;
    $x_sum=$x_sum+$sum;
    $sumamt2+=$res_s;
}


    }//จบ แบ่งรายปัจจุบัน

}//จบ วนลูป IDNO ทั้งหมด

$oins=pg_query("select discount_close('$ssdate','$idno')");
$discount_close=pg_fetch_result($oins,0);

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}

$qry_aon=pg_query("select \"P_MONTH\",\"P_VAT\",\"P_TOTAL\" from \"Fp\" WHERE \"IDNO\" = '$idno'");
if($res_aon=pg_fetch_array($qry_aon)){
    $P_MONTH = $res_aon["P_MONTH"];
    $P_TOTAL = $res_aon["P_TOTAL"];
	$P_VAT = $res_aon["P_VAT"];
    $aons_test = ($P_MONTH+$P_VAT)*$P_TOTAL;
    $aons = round((($P_MONTH+$P_VAT)*$P_TOTAL)/1000);
    $aons = $aons+1500;
}
?>

  <tr style="background-color:#EAEAFF;">
    <td bgcolor="#B3DBAE"></td>
    <td align="left" colspan="2" bgcolor="#ffffff" style="font-size:11px;">= ยอดที่ชำระแล้ว</td>
    <td><div align="right"><b>รวม</b></div></td>
    <td><div align="right"><?php echo number_format($sumamt2,2); ?></div></td>
    <td colspan="5"><div align="right"><b>ยอดค้างทั้งหมด</b></div></td>
    <td align="right"><?php echo number_format($x_sum+$sumamt,2); ?></td>
    <td colspan="2"></td>
  </tr>
  <tr style="background-color:#EAEAFF;">
    <td bgcolor="#C6FFC6"></td>
    <td align="left" colspan="2" bgcolor="#ffffff" style="font-size:11px;">= ยอดที่คำนวณได้</td>
    <td><div align="right"><b>ชำระมา</b></div></td>
    <td><div align="right"><?php echo number_format($otherpay_amt,2); ?></div></td>
    <td colspan="5"><div align="right"><b>ดอกเบิ้ยที่ชำระแล้ว</b></div></td>
    <td align="right"><?php echo number_format($otherpay_amt,2); ?></td>
    <td colspan="2"></td>
  </tr>
  <tr style="background-color:#EAEAFF;">
    <td bgcolor="#FFFFD7"></td>
    <td align="left" colspan="2" bgcolor="#ffffff" style="font-size:11px;">= ยอดที่ยังไม่ชำระ</td>
    <td><div align="right"><b>คงค้าง</b></div></td>
    <td><div align="right"><?php echo number_format($sumamt2-$otherpay_amt,2); ?></div></td>
    <td colspan="5"><div align="right"><b>ส่วนลดปิดบัญชี</b></div></td>
    <td align="right"><?php echo number_format($discount_close,2); ?></td>
    <td colspan="2"></td>
  </tr>
  <tr style="background-color:#EAEAFF;">
    <td colspan="10"><div align="right"><b>ค่าโอน </b></div></td>
    <td align="right"><?php echo number_format($aons,2); ?></td>
    <td colspan="2"></td>
  </tr>
  <tr style="background-color:#EAEAFF;">
    <td colspan="10"><div align="right"><b>ยอดรวมที่ต้องชำระ</b></div></td>
    <td align="right"><?php echo number_format(($x_sum+$sumamt+$aons)-$otherpay_amt-$discount_close,2); ?></td>
    <td colspan="2"></td>
  </tr>

  
</table>



</form>

 </div>

</body>
</html>