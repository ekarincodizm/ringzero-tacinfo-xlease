<?php
session_start();
include("../config/config.php");  

$sedt_idno=trim($_REQUEST["idno"]);
//นำค่าที่ได้มา substr ว่าเป็นสัญญาใหม่หรือเก่า
$check=substr($sedt_idno,3,1);

if($check=="-"){ //แสดงว่าเป็นเลขที่สัญญาเก่า
	$edt_idno=substr($sedt_idno,0,9);
}else{
	$edt_idno=substr($sedt_idno,0,15);
}

$_SESSION["ses_idno"]=$edt_idno;

if(empty($edt_idno)){
    $edt_idno = $_POST["idno"];
	//นำ idno ที่ได้มาตัดเอาเฉพาะ idno
	$edt_idno2=explode(":",$edt_idnostr);
	$edt_idno=trim($edt_idno2[0]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
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


<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>

</head>

<body>

<div class="header"><h1>ตารางการชำระเงินลูกค้า</h1></div>
<div class="wrapper">
<?php
     
$qry_fp=pg_query("select A.*,B.* from \"Fp\" A 
LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" 
where A.\"IDNO\" ='$_SESSION[ses_idno]'");
$res_fp=pg_fetch_array($qry_fp);
$fp_stopvatDate=trim($res_fp["P_StopVatDate"]); if($fp_stopvatDate=="") $fp_stopvatDate="ไม่ได้ระบุ";
$fp_cusid=trim($res_fp["CusID"]);
$fp_carid=trim($res_fp["asset_id"]);
$fp_stdate=$res_fp["P_STDATE"];
  
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
$P_TransferIDNO=$res_fp["P_TransferIDNO"];
  
  
$qryvc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$_SESSION[ses_idno]'");
$resvc=pg_fetch_array($qryvc);
  
$C_COLOR=$resvc["C_COLOR"];
$C_CARNAME=$resvc["C_CARNAME"];
  
$_SESSION["ses_cudid_contact"]=$resvc["CusID"];
  
$RadioID=$resvc["RadioID"];

$fp_cyear=$resvc["C_YEAR"];
$fp_taxexpdate=$resvc["C_TAX_ExpDate"];
$strYear = date("Y",strtotime($fp_taxexpdate));
$strMonth = date("m",strtotime($fp_taxexpdate));
$strDate = date("d",strtotime($fp_taxexpdate));
$fp_taxexpdate = $strYear."-".$strMonth."-".$strDate; 
 
 if($resvc["C_REGIS"]==""){
     $rec_regis=$resvc["car_regis"];
     $rec_cnumber="<b>เลขถังแก๊ส</b> ".$resvc["gas_number"];
     $res_band="<b>ยี่ห้อแก๊ส</b> ".$resvc["gas_name"];
 }else{
     $rec_regis=$resvc["C_REGIS"];
     $rec_cnumber=$resvc["C_CARNUM"];
     $res_band="<b>ยี่ห้อรถ</b> ".$resvc["C_CARNUM"];
}

$s_payment = $fp_pvat+$fp_pmonth;
$s_fullname = trim($res_fp["A_FIRNAME"])." ".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]); 
?>
 
 <form method="post" action="frm_cal_cuspayment.php" name="f_list" id="f_list"> 

 <?php
    $lastdate=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$_SESSION[ses_idno]') order by \"DueDate\" desc LIMIT(1)  ");
    $reslast=pg_fetch_array($lastdate);
?>
    <input type="hidden" name="last_date" value="<?php echo $reslast["DueDate"]; ?>" />
<?php
    $qry_st=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$_SESSION[ses_idno]') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)  ");
    $resone=pg_fetch_array($qry_st);
    
    $s_date = nowDate();
    $_SESSION["ses_date"]=$s_date;
    
?>
    <input type="hidden" name="h_start" value="<?php echo $resone["DueDate"]; ?>" />
 <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
    <tr bgcolor="#E6FFE6" align="left" valign="top">
        <td valign="middle"><b>ชื่อ/สกุล</b> <?php echo trim($res_fp["A_FIRNAME"])." ".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]). " (".$_SESSION[ses_idno].")"; ?></td>
		<td align="center" colspan="2"><font color="red"><b>STOP VAT วันที่ : <?php echo $fp_stopvatDate;?></b></font></td>
		<td align="right" colspan="1"><input type="button" value="พิมพ์รายงาน" onclick="window.open('frm_print_cuspayment.php?idno=<?php echo $_SESSION[ses_idno]; ?>')"></td>
    </tr>
	<tr bgcolor="#E6FFE6" align="left" valign="top">
        <td align="left" valign="middle"><b>วันทำสัญญา</b> <?php echo $trnsdate; ?><br><b>ทะเบียน</b> <?php echo $rec_regis; ?><br><b>เลขตัวถัง</b> <a href="../up/frm_show.php?id=<?php echo $rec_cnumber; ?>&type=reg&mode=2" target="_blank"><u><?php echo $rec_cnumber; ?></u></a></td>
        <td valign="middle"><b>RadioID</b> <?php echo $RadioID; ?><br><b>ประเภทรถ</b> <?php echo $C_CARNAME; ?><br><b>สีรถ</b> <?php echo $C_COLOR; ?></td>
        <td align="right" valign="middle"><b>ค่างวดไม่รวม VAT</b> <?php echo number_format($fp_pmonth,2); ?><br><b>VAT</b> <?php echo number_format($fp_pvat,2); ?><br><b>ค่างวดรวม VAT</b> <u><?php echo number_format($fp_pvat+$fp_pmonth,2); ?></u></td>
        <td align="right" valign="middle"><b>จำนวนงวดทั้งหมด</b> <?php echo $fp_ptotal; ?><br><b>วันที่หมดอายุภาษี</b> <?php echo $fp_taxexpdate; ?><br><b>ปีรถ</b> <?php echo $fp_cyear; ?></td>
    </tr>
    <tr bgcolor="#79BCFF" align="center" valign="middle">
        <td colspan="4"><b>ตารางแสดงการชำระค่างวด</b></td>
    </tr>
</table>

    <?php
    $_SESSION["ses_scusid"]=$fp_cusid;
    $_SESSION["ses_h_start"]=$resone["DueDate"];
    $_SESSION["ses_regis"]=$rec_regis;
    $_SESSION["ses_r_number"]=$rec_cnumber;
    $_SESSION["ses_payment_nonvat"]=$fp_pmonth;
    $_SESSION["ses_payment_vat"]=$fp_pvat;
    $_SESSION["ses_payment_all"]=$fp_pvat+$fp_pmonth;
    $_SESSION["ses_start_date"]=$fp_stdate;
    $_SESSION["ses_start_dateth"]=$trnsdate;
    $_SESSION["ses_last_date"]=$reslast["DueDate"];
    $_SESSION["ses_a_fullname"]=trim($res_fp["A_FIRNAME"])." ".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]);
    $_SESSION["ses_year"]=$fp_cyear;
    $_SESSION["ses_expdate"]=$fp_taxexpdate;
    $_SESSION["ses_ccolor"]=$C_COLOR;
    $_SESSION["ses_ccarname"]=$C_CARNAME;
    $_SESSION["ses_radioid"]=$RadioID;
    ?>


<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0"  align="center">
    <tr bgcolor="#A8D3FF" style="font-size:11px; font-weight:none;"  align="center" valign="middle">
        <td>DueNo.</td>
        <td>DueDate<br />(วันนัดจ่าย)</td>
        <td>R_Date<br />(วันทีี่จ่าย)</td>
        <td>daydelay<br />(วันจ่ายล่าช้า)</td>
        <td>caldelay<br />(ยอดจ่ายล่าช้า)</td>
        <td>R_Receipt<br />(เลขที่ใบเสร็จ)</td>
        <td>PayType</td>
        <td>V_Receipt<br />(เลขที่ใบvat)</td>
        <td>V_date<br />(วันที่จ่ายvat)</td>
        <td>ค่างวดรวม<br>vat</td>
        <td>ยอดต้อง<br>ชำระ</td>
    </tr>
<?php


$search_top = $_SESSION["ses_idno"];
do{
     
$qry_toplv=pg_query("select \"IDNO\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
$res_toplv=pg_fetch_array($qry_toplv); 
    $top_idno[]=$res_toplv["IDNO"];

$search_top=$res_toplv["IDNO"];

}while(!empty($search_top));

$count_toplv = count($top_idno)-1;
if($count_toplv > 0){
for($ig=$count_toplv;$ig>0;$ig--){
    $top_idno2 = $top_idno[$ig-1];

    $qry_vc2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$top_idno2'");
    $res_vc2=pg_fetch_array($qry_vc2);
      
    $full_name=$res_vc2["full_name"];
    
    $numberstep +=1;

    echo "<tr bgcolor=\"#E6FFE6\"><td colspan=\"12\"><b><font color=\"#0000FF\">[$numberstep]</font> ชื่อ/สกุล</b> $full_name ($top_idno2)</td></tr>"; //-------------- SHOW NAME TOP ------------//   

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$top_idno2' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $ddbb[] = $re_mny["sum_money_otherpay"];
}

$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Date\" is not null and \"IDNO\"='$top_idno2' order by \"DueDate\" ");
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
    $d_number+=1;
    
    $get_chk_date = $resvc["V_Date"];
?>     
        <td><?php echo "$d_number [$resvc[DueNo]]"; ?></td>
        <td><?php echo $resvc["DueDate"]; ?></td>
        <td><?php echo $resvc["R_Date"]; ?></td>
        <td><?php echo $resvc["daydelay"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
        <td><?php echo $resvc["R_Receipt"]; ?></td>
        <td><?php echo $resvc["PayType"]; ?></td>
        <td><?php echo $resvc["V_Receipt"]; ?></td>
        <td><?php echo $resvc["V_Date"]; ?></td>
        <td align="right"><?php echo number_format($resvc["R_Money"]+$resvc["VatValue"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
    </tr>    
<?php
$get_duenumber = $resvc["DueNo"];
$sumamt+=$resvc["CalAmtDelay"];
//$d_number = $resvc["DueNo"];
}    
}// ปิด for
} 
//=============

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$_SESSION[ses_idno]' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $ddbb[] = $re_mny["sum_money_otherpay"];
}


$qry_vc2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$_SESSION[ses_idno]'");
    $res_vc2=pg_fetch_array($qry_vc2);
      
    $full_name=$res_vc2["full_name"];
    
    $numberstep +=1;

    echo "<tr bgcolor=\"#E6FFE6\"><td colspan=\"12\"><b><font color=\"#0000FF\">[$numberstep]</font> ชื่อ/สกุล</b> $full_name ($_SESSION[ses_idno])</td></tr>"; //-------------- SHOW NAME TOP ------------//
    
$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Date\" is not null and \"IDNO\"='$_SESSION[ses_idno]' order by \"DueDate\" ");
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
    $d_number+=1;
    $get_chk_date = $resvc["V_Date"];
?>     
        <td><?php echo "$d_number [$resvc[DueNo]]"; ?></td>
        <td><?php echo $resvc["DueDate"]; ?></td>
        <td><?php echo $resvc["R_Date"]; ?></td>
        <td><?php echo $resvc["daydelay"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
        <td><?php echo $resvc["R_Receipt"]; ?></td>
        <td><?php echo $resvc["PayType"]; ?></td>
        <td><?php echo $resvc["V_Receipt"]; ?></td>
        <td><?php echo $resvc["V_Date"]; ?></td>
        <td align="right"><?php echo number_format($resvc["R_Money"]+$resvc["VatValue"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
    </tr>    
<?php
$get_id_chk = $_SESSION[ses_idno];
$get_duenumber5 = $resvc["DueNo"];
$sumamt+=$resvc["CalAmtDelay"];
if($d_number==0)
    $d_number = $resvc["DueNo"];
else
    $d_number = $d_number;
}
//------------ //

if( !empty($P_TransferIDNO) ){

do{
    
$qry_vc2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$P_TransferIDNO'");
$res_vc2=pg_fetch_array($qry_vc2);
//$ss_number = $numberstep;
$full_name=$res_vc2["full_name"];
$numberstep +=1;

echo "<tr bgcolor=\"#E6FFE6\"><td colspan=\"12\"><b><font color=\"#0000FF\">[$numberstep]</font> ชื่อ/สกุล</b> $full_name ($P_TransferIDNO)</td></tr>"; //-------------- SHOW NAME ------------//

$qry_fp2=pg_query("select A.*,B.* from \"Fp\" A 
                             LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" 
                             where A.\"IDNO\" ='$P_TransferIDNO'");
$res_fp2=pg_fetch_array($qry_fp2);
  
$P_TOTAL=trim($res_fp2["P_TOTAL"]);

 //---------------------------------------------//

$_SESSION["ses_idno"]=$P_TransferIDNO;
$fp_cusid=trim($res_fp2["CusID"]);
$fp_carid=trim($res_fp2["asset_id"]);
$fp_stdate=$res_fp2["P_STDATE"];
  
$qrysdate=pg_query("select conversiondatetothaitext('$fp_stdate')");
$trnsdate=pg_fetch_result($qrysdate,0);
  
$fp_pmonth=$res_fp2["P_MONTH"];   
$fp_pvat=$res_fp2["P_VAT"];
$fp_ptotal=$res_fp2["P_TOTAL"];
$fp_pdown=$res_fp2["P_DOWN"];
$fp_pvatofdown=$res_fp2["P_VatOfDown"];
$fp_begin=$res_fp2["P_BEGIN"];
$fp_beginx=$res_fp2["P_BEGINX"];
$fp_fdate=$res_fp2["P_FDATE"];    
$fp_cusby_year=$res_fp2["P_CustByYear"];
$P_TransferIDNO=$res_fp2["P_TransferIDNO"];

$qryvc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$_SESSION[ses_idno]'");
$resvc=pg_fetch_array($qryvc);
  
$C_COLOR=$resvc["C_COLOR"];
$C_CARNAME=$resvc["C_CARNAME"];
  
$_SESSION["ses_cudid_contact"]=$resvc["CusID"];
  
$fp_cyear=$resvc["C_YEAR"];
$fp_taxexpdate=$resvc["C_TAX_ExpDate"];
$strYear = date("Y",strtotime($fp_taxexpdate));
$strMonth = date("m",strtotime($fp_taxexpdate));
$strDate = date("d",strtotime($fp_taxexpdate));
$fp_taxexpdate = $strYear."-".$strMonth."-".$strDate; 
 
 if($resvc["C_REGIS"]==""){
     $rec_regis=$resvc["car_regis"];
     $rec_cnumber="<b>เลขถังแก๊ส</b> ".$resvc["gas_number"];
     $res_band="<b>ยี่ห้อแก๊ส</b> ".$resvc["gas_name"];
 }else{
     $rec_regis=$resvc["C_REGIS"];
     $rec_cnumber=$resvc["C_CARNUM"];
     $res_band="<b>ยี่ห้อรถ</b> ".$resvc["C_CARNUM"];
}

$s_payment = $fp_pvat+$fp_pmonth;
$s_fullname = trim($res_fp2["A_FIRNAME"])." ".trim($res_fp2["A_NAME"])."  ".trim($res_fp2["A_SIRNAME"]);     
    
$lastdate=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$_SESSION[ses_idno]') order by \"DueDate\" desc LIMIT(1)  ");
$reslast=pg_fetch_array($lastdate);

$qry_st=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$_SESSION[ses_idno]') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)  ");
$resone=pg_fetch_array($qry_st);   
    
$s_date = nowDate();
$_SESSION["ses_date"]=$s_date;

$_SESSION["ses_scusid"]=$fp_cusid;
$_SESSION["ses_h_start"]=$resone["DueDate"];
$_SESSION["ses_regis"]=$rec_regis;
$_SESSION["ses_r_number"]=$rec_cnumber;
$_SESSION["ses_payment_nonvat"]=$fp_pmonth;
$_SESSION["ses_payment_vat"]=$fp_pvat;
$_SESSION["ses_payment_all"]=$fp_pvat+$fp_pmonth;
$_SESSION["ses_start_date"]=$fp_stdate;
$_SESSION["ses_start_dateth"]=$trnsdate;
$_SESSION["ses_last_date"]=$reslast["DueDate"];
$_SESSION["ses_a_fullname"]=trim($res_fp2["A_FIRNAME"])." ".trim($res_fp2["A_NAME"])."  ".trim($res_fp2["A_SIRNAME"]);
$_SESSION["ses_year"]=$fp_cyear;
$_SESSION["ses_expdate"]=$fp_taxexpdate;
$_SESSION["ses_ccolor"]=$C_COLOR;
$_SESSION["ses_ccarname"]=$C_CARNAME;

// ----------- //

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$_SESSION[ses_idno]' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $ddbb[] = $re_mny["sum_money_otherpay"];
}

$P_TransferIDNO=$res_fp2["P_TransferIDNO"];

}while(!empty($P_TransferIDNO));


} // -----

if($get_id_chk == $_SESSION[ses_idno]){
    
}else{
$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"R_Date\" is not null and \"IDNO\"='$_SESSION[ses_idno]' order by \"DueNo\" ");
$nr_vcus = pg_num_rows($qry_vcus);
if($nr_vcus > 0){
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
    $d_number+=1;
    $get_chk_date = $resvc["V_Date"];
?>
        <td><?php echo "$d_number [$resvc[DueNo]]"; ?></td>
        <td><?php echo $resvc["DueDate"]; ?></td>
        <td><?php echo $resvc["R_Date"]; ?></td>
        <td><?php echo $resvc["daydelay"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
        <td><?php echo $resvc["R_Receipt"]; ?></td>
        <td><?php echo $resvc["PayType"]; ?></td>
        <td><?php echo $resvc["V_Receipt"]; ?></td>
        <td><?php echo $resvc["V_Date"]; ?></td>
        <td align="right"><?php echo number_format($resvc["R_Money"]+$resvc["VatValue"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
    </tr>    
<?php
$get_duenumber2 = $resvc["DueNo"];
$sumamt+=$resvc["CalAmtDelay"];
}
}
}


if($get_id_chk == $_SESSION[ses_idno]){
    $get_duenumber2 = $get_duenumber5;
}else{
    if(empty($get_duenumber2)) $get_duenumber2 = 0;
}

$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"DueNo\" > '$get_duenumber2' and \"IDNO\"='$_SESSION[ses_idno]' order by \"DueNo\" ");
$nr_vcus = pg_num_rows($qry_vcus);
if($nr_vcus > 0){
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
    $d_number+=1;
?>
        <td><?php echo "$d_number [$resvc[DueNo]]"; ?></td>
        <td><?php echo $resvc["DueDate"]; ?></td>
        <td><?php echo $resvc["R_Date"]; ?></td>
        <td><?php echo $resvc["daydelay"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
        <td><?php echo $resvc["R_Receipt"]; ?></td>
        <td><?php echo $resvc["PayType"]; ?></td>
        <td><?php echo $resvc["V_Receipt"]; ?></td>
        <td><?php echo $resvc["V_Date"]; ?></td>
        <td align="right"><?php echo number_format($resvc["R_Money"]+$resvc["VatValue"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["CalAmtDelay"],2); ?></td>
    </tr>    
<?php
$sumamt+=$resvc["CalAmtDelay"];
}
}

$ddbb_sum = array_sum($ddbb);
?>
  <tr style="background-color:#FFFFAA;">
    <td bgcolor="#B3DBAE"></td>
    <td align="left" bgcolor="#ffffff" colspan="2" style="font-size:11px;">= ยอดที่ชำระแล้ว</td>
    <td colspan="7"><div align="right"><b>ยอดค้างทั้งหมด</b></div></td>
    <td align="right"><?php echo number_format($sumamt,2); ?></td>
  </tr>
  <tr style="background-color:#FFFFAA;">
    <td bgcolor="#C6FFC6"></td>
    <td align="left" bgcolor="#ffffff" colspan="2" style="font-size:11px;">= ยอดที่คำนวณได้</td>
    <td colspan="7"><div align="right"><b>ดอกเบิ้ยที่ชำระแล้ว</b></div></td>
    <td align="right"><?php echo number_format($ddbb_sum,2); ?></td>
  </tr>
  <tr style="background-color:#FFFFAA;">
    <td bgcolor="#FFFFD7"></td>
    <td align="left" bgcolor="#ffffff" colspan="2" style="font-size:11px;">= ยอดที่ยังไม่ชำระ</td>
    <td colspan="7"><div align="right"><b>ยอดรวมที่ต้องชำระ</b></div></td>
    <td align="right"><?php echo number_format($sumamt-$ddbb_sum,2); ?></td>
  </tr>        
</table>
  
</form>

<table width="300" cellpadding="3" cellspacing="1" border="0" bgcolor="#FFFF40" align="center">
<tr>
    <td align="center" bgcolor="#FFFFCE">
<b>ยืนยันการหยุด VAT</b><br>
<input type="button" value="  ยืนยัน  " onclick="javascript:popU('stop_vat_date.php?idno=<?php echo "$edt_idno"; ?>&date=<?php echo $get_chk_date; ?>','<?php echo "stop_vat_date"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=no,status=no,location=no,width=500,height=280')">
<input type="button" onclick="window.location='stop_vat.php'" value="   กลับ   ">
    </td>
</tr>
</table>


</div>

</body>
</html>
