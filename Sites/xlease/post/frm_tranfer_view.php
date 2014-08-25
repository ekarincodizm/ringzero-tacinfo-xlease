<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    
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
-->
</style>

</head>

<body>

<div class="header"><h1>ยืนยันการโอนสิทธิ์</h1></div>
<form method="post" name="form1" action="frm_tranfer_add.php">
<div class="wrapper">
<?php
$sedt_idno=trim($_REQUEST["idno_names"]);
$edt_idno=substr($sedt_idno,0,11);

$qry_fp=pg_query("select A.*,B.* from \"Fp\" A 
                             LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" 
							 where A.\"IDNO\" ='$sedt_idno'");
  $res_fp=pg_fetch_array($qry_fp);

  $fp_cusid=trim($res_fp["CusID"]);
  $fp_carid=trim($res_fp["asset_id"]);
  $fp_stdate=$res_fp["P_STDATE"];
  $P_ACCLOSE=$res_fp["P_ACCLOSE"];
  
  $qrysdate=pg_query("select conversiondatetothaitext('$fp_stdate')");
  $trnsdate=pg_fetch_result($qrysdate,0);
  
  $fp_pmonth=$res_fp["P_MONTH"];
  $fp_pvat=$res_fp["P_VAT"];
  $fp_ptotal=$res_fp["P_TOTAL"];
  $fp_pdown=$res_fp["P_DOWN"];
  $fp_pvatofdown=$res_fp["P_VatOfDown"];
  $fp_begin=$res_fp["P_BEGIN"];
  $fp_beginx=$res_fp["P_BEGINX"];
  $fp_fdate=$res_fp["P_FDATE"];
  $fp_cusby_year=$res_fp["P_CustByYear"];
  
  
  
  $qryvc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$sedt_idno'");
  $resvc=pg_fetch_array($qryvc);
  
  $fp_cyear=$resvc["C_YEAR"];
  $fp_taxexpdate=$resvc["C_TAX_ExpDate"];
    $strYear = date("Y",strtotime($fp_taxexpdate));
    $strMonth = date("m",strtotime($fp_taxexpdate));
    $strDate = date("d",strtotime($fp_taxexpdate));
    $fp_taxexpdate = $strYear."-".$strMonth."-".$strDate; 
 
   if($resvc["C_REGIS"]=="")
	{
		
		$rec_regis=$resvc["car_regis"];
		$rec_cnumber="<b>เลขถังแก๊ส</b> ".$resvc["gas_number"];
		$res_band="<b>ยี่ห้อแก๊ส</b> ".$resvc["gas_name"];
		
		
		}
		else
		{
		
		$rec_regis=$resvc["C_REGIS"];
		$rec_cnumber="<b>เลขตัวถัง</b> ".$resvc["C_CARNUM"];
		$res_band="<b>ยี่ห้อรถ</b> ".$resvc["C_CARNUM"];
		}
        $s_payment = $fp_pvat+$fp_pmonth;
        $s_fullname = trim($res_fp["A_FIRNAME"])." ".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]); 
        
        $qry_cnum=pg_query("select COUNT(\"R_Receipt\") as ccount_num from \"VCusPayment\" WHERE  \"IDNO\"='$sedt_idno' AND \"R_Receipt\" is not null");
        $res_cnum=pg_fetch_array($qry_cnum);
            $ccount_num = $res_cnum["ccount_num"];

            
    $lastdate=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$sedt_idno') order by \"DueDate\" desc LIMIT(1)  ");
    $reslast=pg_fetch_array($lastdate);

    
    $qry_st=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$sedt_idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1) ");
    $resone=pg_fetch_array($qry_st);    
    
    $duenum_lob = $fp_ptotal-$ccount_num;
    //$_SESSION["tranfer_idno"]=$sedt_idno;
   // $_SESSION["tranfer_duenum"]=$duenum_lob;

?>
<div>
<input type="hidden" name="tranfer_cusbyyear" value="<?php echo $fp_cusby_year;?>">
<input type="hidden" name="tranfer_idno" value="<?php echo $sedt_idno;?>">
<input type="hidden" name="tranfer_duenum" value="<?php echo $duenum_lob;?>">
</div>
 <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
	<tr bgcolor="#E6FFE6" align="left" valign="top">
        <td valign="middle"><b>ชื่อ/สกุล</b> <?php echo trim($res_fp["A_FIRNAME"])." ".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]). " (".$sedt_idno.")"; ?></td>
        <td colspan="2" align="right">
 
<?php 
if($P_ACCLOSE == "t"){
    echo "<span style=\"color:#ff0000; font-weight:bold; font-size:14px\">รายการนี้ได้โอนสิทธิ์ไปแล้ว ไม่สามารถทำการโอนสิทธิ์ซ้ำได้</span> <input type=\"button\" value=\"ค้นหาใหม่\" onclick=\"location='frm_tranfer.php'\">";
}else{
?>
    <input type="button" value="ค้นหาใหม่" onclick="location='frm_tranfer.php'"><input type="submit" value="เริ่มการโอนสิทธิ์"/>
<?php
}
?>
        </td>
    </tr>
	<tr bgcolor="#E6FFE6" align="left" valign="top">
        <td align="left" valign="middle"><b>วันทำสัญญา</b> <?php echo $trnsdate; ?><br><b>ทะเบียน</b> <?php echo $rec_regis; ?><br><?php echo $rec_cnumber; ?></td>
        <td align="right" valign="middle"><b>จำนวนงวดทั้งหมด</b> <?php echo $fp_ptotal; ?><br><b>จำนวนงวดที่ชำระแล้ว</b> <?php echo $ccount_num; ?><br><b>จำนวนงวดที่เหลือ</b> <u><?php echo $duenum_lob; ?></u><br><b>ค่างวดรวม VAT งวดละ</b> <u><?php echo number_format($fp_pvat+$fp_pmonth,2); ?></u></td>
        <td align="right" valign="middle"><b>วันที่หมดอายุภาษี</b> <?php echo $fp_taxexpdate; ?><br><b>ปีรถ</b> <?php echo $fp_cyear; ?></td>
    </tr>
    <tr bgcolor="#79BCFF" align="center" valign="middle">
        <td colspan="3"><b>ตารางแสดงการชำระค่างวด</b></td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0"  align="center">
    <tr bgcolor="#A8D3FF" style="font-size:11px; font-weight:none;"  align="center" valign="middle">
        <td>DueNo.</td>
        <td>DueDate<br />(วันนัดจ่าย)</td>
        <td>R_Date<br />(วันที่จ่าย)</td>
        <td>daydelay<br />(วันจ่ายล่าช้า)</td>
        <td>caldelay<br />(ยอดจ่ายล่าช้า)</td>
        <td>R_Receipt<br />(เลขที่ใบเสร็จ)</td>
        <td>V_Receipt<br />(เลขที่ใบvat)</td>
        <td>V_date<br />(วันที่จ่ายvat)</td>
        <td>ค่างวดรวม<br>vat</td>
        <td>ยอดต้อง<br>ชำระ</td>
    </tr>
<?php
$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"IDNO\"='$sedt_idno' order by \"DueDate\"  ");
while($resvc=pg_fetch_array($qry_vcus)) {
    if($resvc["R_Receipt"]!=''){
        echo "<tr style=\"font-size:11px; background-color:#B3DBAE;\" align=center>";
    }else{
        $inum+=1;
        if($inum%2==0){
            echo "<tr class=\"odd\" align=center>";
        }else{
            echo "<tr class=\"even\" align=center>";
        }
    }
?>     
        <td><?php echo $resvc["DueNo"]; ?></td>
        <td><?php echo $resvc["DueDate"]; ?></td>
        <td><?php echo $resvc["R_Date"]; ?><input type="hidden" name="tranfer_rdate" value="<?php echo $resvc["R_Date"]; ?>"></td>
        <td><?php echo $resvc["daydelay"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
        <td><?php echo $resvc["R_Receipt"]; ?></td>
        <td><?php echo $resvc["V_Receipt"]; ?></td>
        <td><?php echo $resvc["V_Date"]; ?></td>
        <td align="right"><?php echo number_format($resvc["R_Money"]+$resvc["VatValue"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
    </tr>    
<?php
$sumamt+=$resvc["CalAmtDelay"]; 

if(!empty($resvc["R_Date"]))
    $tranfer_rdate=$resvc["R_Date"];
}

$dueno_last = $ccount_num+1;

$qry_dd=pg_query("select * from \"VCusPayment\" WHERE  \"IDNO\"='$sedt_idno' AND \"DueNo\"='$dueno_last' ");
if($redd=pg_fetch_array($qry_dd)){
    $tranfer_start_duedate=$redd["DueDate"];
}

$qry_dd2=pg_query("select * from \"CusPayment\" WHERE  \"IDNO\"='$sedt_idno' AND \"DueNo\"='$ccount_num' ");
if($redd2=pg_fetch_array($qry_dd2)){
    $tranfer_cus_compri=$redd2["CommPriciple"];
}

$qry_dd3=pg_query("select * from \"AccPayment\" WHERE  \"IDNO\"='$sedt_idno' AND \"DueNo\"='$ccount_num' ");
if($redd3=pg_fetch_array($qry_dd3)){
    $tranfer_acc_compri=$redd3["Priciple"];
    $tranfer_acc_commis=$redd3["Commis"];
}


$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$sedt_idno' ");
if($re_mny=pg_fetch_array($qry_moneys)) {
    $ddbb = $re_mny["sum_money_otherpay"];
}

?>
  <tr style="background-color:#FFFFAA;">
    <td bgcolor="#B3DBAE">
		<input type="hidden" name="tranfer_cus_compri" value="<?php echo $tranfer_cus_compri;?>">
		<input type="hidden" name="tranfer_acc_compri" value="<?php echo $tranfer_acc_compri;?>">
		<input type="hidden" name="tranfer_acc_commis" value="<?php echo $tranfer_acc_commis;?>">
	</td>
    <td align="left" bgcolor="#ffffff" colspan="2" style="font-size:11px;">= ยอดที่ชำระแล้ว</td>
    <td colspan="6"><div align="right"><b>ยอดค้างทั้งหมด</b></div></td>
    <td align="right"><?php echo number_format($sumamt,2); ?></td>
  </tr>
  <tr style="background-color:#FFFFAA;">
    <td bgcolor="#C6FFC6"></td>
    <td align="left" bgcolor="#ffffff" colspan="2" style="font-size:11px;">= ยอดที่คำนวณได้</td>
    <td colspan="6"><div align="right"><b>ดอกเบี้ยที่ชำระแล้ว</b></div></td>
    <td align="right"><?php echo number_format($ddbb,2); ?></td>
  </tr>
  <tr style="background-color:#FFFFAA;">
    <td bgcolor="#FFFFD7"></td>
    <td align="left" bgcolor="#ffffff" colspan="2" style="font-size:11px;">= ยอดที่ยังไม่ชำระ</td>
    <td colspan="6"><div align="right"><b>ยอดรวมที่ต้องชำระ</b></div></td>
    <td align="right"><?php echo number_format($sumamt-$ddbb,2); ?></td>
  </tr>        
</table>
<div><input type="hidden" name="tranfer_start_duedate" value="<?php echo $tranfer_start_duedate;?>"></div>
</div>
</form>

</body>
</html>
