<?php
session_start();
include("../config/config.php");
$_SESSION["av_iduser"];
$tday=pg_escape_string($_POST["report_ot_Date"]);
$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    </head>
<body>

<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION["session_company_name"]; ?></h1></div>
<div class="wrapper">
<div align="right"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <a href="view_report_otdate_html.php?report_ot_Date=<?php echo $tday; ?>" target="_blank">สั่งพิมพ์แบบ HTML</a></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน รับเงินสดค่าอื่นๆ ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">O_Receipt</td>
        <td align="center">O_Date</td>
        <td align="center">O_Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">TName</td>
        <td align="center">regis</td>
        <td align="center">money</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CA' OR \"O_BANK\"='CCA') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
$num=pg_num_rows($qry_fq);
while($res_fr=pg_fetch_array($qry_fq)){
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["O_RECEIPT"]; ?></td>
        <td align="center"><?php echo $res_ss["O_DATE"]; ?></td>
        <td align="center"><?php echo $res_ss["O_BANK"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["TName"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["O_MONEY"],2); ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left"><a href="report_otdate_pdf.php?type=1&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="11" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน รับเช็คค่าอื่นๆ ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">O_Receipt</td>
        <td align="center">O_Date</td>
        <td align="center">O_Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">TName</td>
        <td align="center">regis</td>
        <td align="center">money</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CU') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
$num=pg_num_rows($qry_fq);
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);    
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["O_RECEIPT"]; ?></td>
        <td align="center"><?php echo $res_ss["O_DATE"]; ?></td>
        <td align="center"><?php echo $res_ss["O_BANK"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["TName"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["O_MONEY"],2); ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left"><a href="report_otdate_pdf.php?type=2&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="11" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน รับเงินจากที่อื่น (ค่าอื่นๆ) ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">O_Receipt</td>
        <td align="center">O_Date</td>
        <td align="center">O_Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">TName</td>
        <td align="center">regis</td>
        <td align="center">money</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",\"PayType\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_memo\"<>'TR-ACC' OR \"O_memo\" is null OR \"O_memo\"='') AND (\"PayType\"<>'OC') GROUP BY \"O_RECEIPT\",\"PayType\" ORDER BY \"PayType\" ASC ");
$num=pg_num_rows($qry_fq);
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["O_RECEIPT"]; ?></td>
        <td align="center"><?php echo $res_ss["O_DATE"]; ?></td>
        <td align="center"><?php echo $res_ss["O_BANK"]; ?></td>
        <td align="center"><?php echo $res_fr["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["TName"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["O_MONEY"],2); ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left"><a href="report_otdate_pdf.php?type=3&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="11" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน รับเงินโอน (ค่าอื่นๆ) ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">O_Receipt</td>
        <td align="center">O_Date</td>
        <td align="center">O_Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">TName</td>
        <td align="center">regis</td>
        <td align="center">money</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",\"PayType\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_memo\" ='TR-ACC') AND (\"PayType\"<>'OC') GROUP BY \"O_RECEIPT\",\"PayType\" ORDER BY \"PayType\" ASC ");
$num=pg_num_rows($qry_fq);
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["O_RECEIPT"]; ?></td>
        <td align="center"><?php echo $res_ss["O_DATE"]; ?></td>
        <td align="center"><?php echo $res_ss["O_BANK"]; ?></td>
        <td align="center"><?php echo $res_fr["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["TName"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["O_MONEY"],2); ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left"><a href="report_otdate_pdf.php?type=4&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="11" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>


<!-- ////////////////////////////////////////////////////////////////// CANCEL //////////////////////////////////////////////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="15" align="left" style="font-weight:bold;">รายงาน ยกเลิกใบเสร็จ ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">ReceiptID</td>
        <td align="center">IDNO</td>
        <td align="center">Cancel_Date</td>
        <td align="center">Money</td>
        <td align="center">PrintDate</td>
        <td align="center">RecDate</td>
        <td align="center">Ref_Receipt</td>
        <td align="center">PayType</td>
        <td align="center">Admin_Approve</td>
        <td align="center">Memo</td>
    </tr>

<?php
$n = 0;
$num = 0;
$sum_1 = 0;
$sum_2 = 0;

$qry_fq=pg_query("select * from \"CancelReceipt\" WHERE (\"c_date\"='$tday') ORDER BY \"c_receipt\" ASC ");
$num=pg_num_rows($qry_fq);
while($res_fr=pg_fetch_array($qry_fq)){
    
    $ref_receipt = $res_fr["ref_receipt"];
    
    $sub_ref = substr($ref_receipt,2,1);
    if($sub_ref != 'R'){
        
    $SIDNO = $res_fr["IDNO"];
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$SIDNO' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        //$vat = $res_cc1['VatValue'];
    }
    
    if($res_fr["admin_approve"] == 't'){
        $show_app = "อนุมัติแล้ว";
        $sum_1+=$res_fr["c_money"]+$vat;
    }else{
        $show_app = "ยังไม่อนุมัติ";
        $sum_2+=$res_fr["c_money"]+$vat;
    }
    
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["c_receipt"]; ?></td>
        <td align="center"><?php echo $res_fr["IDNO"]; ?></td>
        <td align="center"><?php echo $res_fr["c_date"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["c_money"],2); ?></td>
        <td align="center"><?php echo $res_fr["ref_prndate"]; ?></td>
        <td align="center"><?php echo $res_fr["ref_recdate"]; ?></td>
        <td align="center"><?php echo $ref_receipt; ?></td>
        <td align="center"><?php echo $res_fr["paytypefrom"]; ?></td>
        <td align="center"><?php echo $show_app; ?></td>
        <td align="left"><?php echo $res_fr["c_memo"]; ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;"><a href="report_cancel_pdf.php?type=2&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a> | ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" colspan="6" style="font-weight:bold;"><u>รวมอนุมัติแล้ว</u> <?php echo number_format($sum_1,2); ?> | <u>รวมยังไม่อนุมัติ</u> <?php echo number_format($sum_2,2); ?></td>
    </tr>
<?php
}
?>

<?php 
if($num == 0){   
?>
    <tr><td colspan="15" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

        </td>
    </tr>
</table>

</body>
</html>