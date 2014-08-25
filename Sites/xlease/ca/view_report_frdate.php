<?php
session_start();
include("../config/config.php");
$_SESSION["av_iduser"];
$tday=pg_escape_string($_POST["report_fr_Date"]);
$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);

$qry_any=pg_query("select \"ptanyplace\" from \"PayTypeFromAnyPlace\";");
$set_anyplace = pg_result($qry_any,0);
$arr_set_anyplace = explode(",",$set_anyplace);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    </head>
<body>

<table width="85%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION["session_company_name"]; ?></h1></div>
<!--<div class="wrapper">-->
<div align="right"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <a href="view_report_frdate_html.php?report_fr_Date=<?php echo $tday; ?>" target="_blank">สั่งพิมพ์แบบ HTML</a></div>

<!-- ////////////////////////////// รับเงินสดค่างวด //////////////////////////////////// รับเงินสดค่างวด /////////////////////////////////////// รับเงินสดค่างวด ///////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="16" align="left" style="font-weight:bold;">รายงาน รับเงินสดค่างวด ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">Receipt</td>
        <td align="center">Date</td>
        <td align="center">Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">typepay</td>
        <td align="center">regis</td>
        <td align="center">value</td>
        <td align="center">vat</td>
        <td align="center">money</td>
        <td align="center">dc</td>
        <td align="center">sum</td>
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>
<?php
$n = 0;
$num = 0;
$sum_value = 0;
$sum_vat = 0;
$sum_money = 0;
$sum_discount = 0;
$sum_summary = 0;
$qry_fr=pg_query("select \"R_Receipt\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_Bank\"='CA') AND (\"PayType\"='OC') GROUP BY \"R_Receipt\" ORDER BY \"R_Receipt\" ASC ");
$num=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){
    
    $qry_ss=pg_query("select \"R_Date\", \"R_Bank\", \"PayType\", \"IDNO\", \"full_name\", \"assetname\", \"typepay_name\", \"regis\"
						from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_fr[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $sum_value+=$res_fr["value"];
    $sum_vat+=$res_fr["vat"];
    $sum_money+=$res_fr["money"];
    $sum_discount+=$res_fr["discount"];
    $sum_summary+=$res_fr["money"]-$res_fr["discount"];
    
	//หาผู้ออกใบเสร็จ
	$O_RECEIPT = $res_fr["R_Receipt"];
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
	
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["R_Receipt"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Date"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Bank"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["typepay_name"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["value"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["vat"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["discount"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"]-$res_fr["discount"],2); ?></td>
		<td align="left"><?php echo $fullnameuseracc; ?></td>
    </tr>
<?php
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="9" align="left" style="font-weight:bold;"><a href="report_frdate_pdf.php?type=1&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a> | ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" style="font-weight:bold;"><u>รวม</u></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_value,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_vat,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_money,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_discount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_summary,2); ?></td>
		<td></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){
?>
    <tr><td colspan="16" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<!-- ////////////////////////////// รับเช็คค่างวด //////////////////////////////////// รับเช็คค่างวด /////////////////////////////////////// รับเช็คค่างวด ///////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="16" align="left" style="font-weight:bold;">รายงาน รับเช็คค่างวด ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">Receipt</td>
        <td align="center">Date</td>
        <td align="center">Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">typepay</td>
        <td align="center">regis</td>
        <td align="center">value</td>
        <td align="center">vat</td>
        <td align="center">money</td>
        <td align="center">dc</td>
        <td align="center">sum</td>
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$sum_value = 0;
$sum_vat = 0;
$sum_money = 0;
$sum_discount = 0;
$sum_summary = 0;

$qry_fq=pg_query("select \"R_Receipt\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_Bank\"='CU')  AND (\"PayType\"='OC') GROUP BY \"R_Receipt\" ORDER BY \"R_Receipt\" ASC ");
$num=pg_num_rows($qry_fq);
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_ss=pg_query("select \"R_Date\", \"R_Bank\", \"PayType\", \"IDNO\", \"full_name\", \"assetname\", \"typepay_name\", \"regis\"
					from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_fr[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
   
   $sum_value+=$res_fr["value"];
    $sum_vat+=$res_fr["vat"];
    $sum_money+=$res_fr["money"];
    $sum_discount+=$res_fr["discount"];
    $sum_summary+=$res_fr["money"]-$res_fr["discount"];
   
   //หาผู้ออกใบเสร็จ
   $O_RECEIPT = $res_fr["R_Receipt"];
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
   
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["R_Receipt"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Date"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Bank"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["typepay_name"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["value"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["vat"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["discount"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"]-$res_fr["discount"],2); ?></td>
		<td align="left"><?php echo $fullnameuseracc; ?></td>
    </tr>
<?php
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="9" align="left" style="font-weight:bold;"><a href="report_frdate_pdf.php?type=2&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a> | ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" style="font-weight:bold;"><u>รวม</u></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_value,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_vat,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_money,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_discount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_summary,2); ?></td>
		<td></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="16" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

<!-- ////////////////////////////// รับเงินจากที่อื่น //////////////////////////////////// รับเงินจากที่อื่น /////////////////////////////////////// รับเงินจากที่อื่น ///////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="16" align="left" style="font-weight:bold;">รายงาน รับเงินจากที่อื่น ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">Receipt</td>
        <td align="center">Date</td>
        <td align="center">Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">typepay</td>
        <td align="center">regis</td>
        <td align="center">value</td>
        <td align="center">vat</td>
        <td align="center">money</td>
        <td align="center">dc</td>
        <td align="center">sum</td>
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$sum_value = 0;
$sum_vat = 0;
$sum_money = 0;
$sum_discount = 0;
$sum_summary = 0;
$qry_fq=pg_query("select \"R_Receipt\",\"PayType\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_memo\"<>'TR-ACC' OR \"R_memo\" is null  OR \"R_memo\"='') GROUP BY \"R_Receipt\",\"PayType\" ORDER BY \"PayType\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_ss=pg_query("select \"R_Date\", \"R_Bank\", \"PayType\", \"IDNO\", \"full_name\", \"assetname\", \"typepay_name\", \"regis\"
					from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_fr[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    if(!in_array($res_ss["PayType"],$arr_set_anyplace)){
        continue;
    }
    
    $num++;
    
    $sum_value+=$res_fr["value"];
    $sum_vat+=$res_fr["vat"];
    $sum_money+=$res_fr["money"];
    $sum_discount+=$res_fr["discount"];
    $sum_summary+=$res_fr["money"]-$res_fr["discount"];
    
	//หาผู้ออกใบเสร็จ
	$O_RECEIPT = $res_fr["R_Receipt"];
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
	
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["R_Receipt"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Date"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Bank"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["typepay_name"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["value"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["vat"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["discount"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"]-$res_fr["discount"],2); ?></td>
		<td align="left"><?php echo $fullnameuseracc; ?></td>
    </tr>
<?php
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="9" align="left" style="font-weight:bold;"><a href="report_frdate_pdf.php?type=3&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a> | ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" style="font-weight:bold;"><u>รวม</u></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_value,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_vat,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_money,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_discount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_summary,2); ?></td>
		<td></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="16" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

<!-- ////////////////////////////// รับเงินโอน //////////////////////////////////// รับเงินโอน /////////////////////////////////////// รับเงินโอน ///////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="16" align="left" style="font-weight:bold;">รายงาน รับเงินโอน ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">No.</td>
        <td align="center">Receipt</td>
        <td align="center">Date</td>
        <td align="center">Bank</td>
        <td align="center">PayType</td>
        <td align="center">IDNO</td>
        <td align="center">full_name</td>
        <td align="center">assetname</td>
        <td align="center">typepay</td>
        <td align="center">regis</td>
        <td align="center">value</td>
        <td align="center">vat</td>
        <td align="center">money</td>
        <td align="center">dc</td>
        <td align="center">sum</td>
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$sum_value = 0;
$sum_vat = 0;
$sum_money = 0;
$sum_discount = 0;
$sum_summary = 0;

$qry_fq=pg_query("select \"R_Receipt\",\"PayType\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"PayType\" <> 'OC') GROUP BY \"R_Receipt\",\"PayType\" ORDER BY \"PayType\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_ss=pg_query("select \"R_Date\", \"R_Bank\", \"PayType\", \"IDNO\", \"full_name\", \"assetname\", \"typepay_name\", \"regis\"
					from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_fr[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    if(in_array($res_ss["PayType"],$arr_set_anyplace)){
        continue;
    }
    
    $num++;
    $sum_value+=$res_fr["value"];
    $sum_vat+=$res_fr["vat"];
    $sum_money+=$res_fr["money"];
    $sum_discount+=$res_fr["discount"];
    $sum_summary+=$res_fr["money"]-$res_fr["discount"];
    
    $sum_value7+=$res_fr["value"];
    $sum_vat7+=$res_fr["vat"];
    $sum_money7+=$res_fr["money"];
    $sum_discount7+=$res_fr["discount"];
    $sum_summary7+=$res_fr["money"]-$res_fr["discount"];
    
	//หาผู้ออกใบเสร็จ
	$O_RECEIPT = $res_fr["R_Receipt"];
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
	
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["R_Receipt"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Date"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Bank"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["typepay_name"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["value"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["vat"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["discount"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"]-$res_fr["discount"],2); ?></td>
		<td align="left"><?php echo $fullnameuseracc; ?></td>
    </tr>
<?php
}

if($num>0){
?>

    <tr bgcolor="#8AC5FF">
        <td colspan="9" align="left" style="font-weight:bold;"></td>
        <td align="right" style="font-weight:bold;"><u>ผลรวม</u></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_value,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_vat,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_money,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_discount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_summary,2); ?></td>
		<td></td>
    </tr>
<?php
}
?>

<!-- ////////////////////////////// CCA //////////////////////////////////// CCA /////////////////////////////////////// CCA ///////////////////////////////////// -->
<?php
$sum_value = 0;
$sum_vat = 0;
$sum_money = 0;
$sum_discount = 0;
$sum_summary = 0;
$qry_fr=pg_query("select \"R_Receipt\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_Bank\"='CCA') AND (\"PayType\"='OC') GROUP BY \"R_Receipt\" ORDER BY \"R_Receipt\" ASC ");
while($res_fr=pg_fetch_array($qry_fr)){
    $num2++;
    $qry_ss=pg_query("select \"R_Date\", \"R_Bank\", \"PayType\", \"IDNO\", \"full_name\", \"assetname\", \"typepay_name\", \"regis\"
					from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_fr[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $sum_value+=$res_fr["value"];
    $sum_vat+=$res_fr["vat"];
    $sum_money+=$res_fr["money"];
    $sum_discount+=$res_fr["discount"];
    $sum_summary+=$res_fr["money"]-$res_fr["discount"];
    
    $sum_value7+=$res_fr["value"];
    $sum_vat7+=$res_fr["vat"];
    $sum_money7+=$res_fr["money"];
    $sum_discount7+=$res_fr["discount"];
    $sum_summary7+=$res_fr["money"]-$res_fr["discount"];
    
	//หาผู้ออกใบเสร็จ
	$O_RECEIPT = $res_fr["R_Receipt"];
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
	
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_fr["R_Receipt"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Date"]; ?></td>
        <td align="center"><?php echo $res_ss["R_Bank"]; ?></td>
        <td align="center"><?php echo $res_ss["PayType"]; ?></td>
        <td align="center"><?php echo $res_ss["IDNO"]; ?></td>
        <td align="left"><?php echo $res_ss["full_name"]; ?></td>
        <td align="left"><?php echo $res_ss["assetname"]; ?></td>
        <td align="left"><?php echo $res_ss["typepay_name"]; ?></td>
        <td align="left"><?php echo $res_ss["regis"]; ?></td>
        <td align="right"><?php echo number_format($res_fr["value"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["vat"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["discount"],2); ?></td>
        <td align="right"><?php echo number_format($res_fr["money"]-$res_fr["discount"],2); ?></td>
		<td align="left"><?php echo $fullnameuseracc; ?></td>
    </tr>
<?php
}

if($num2>0){
?>
    <tr bgcolor="#8AC5FF">
        <td colspan="9" align="left" style="font-weight:bold;"></td>
        <td align="right" style="font-weight:bold;"><u>ผลรวม</u></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_value,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_vat,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_money,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_discount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_summary,2); ?></td>
		<td></td>
    </tr>

<?php
}

if($num > 0){
?>
    <tr>
        <td colspan="9" align="left" style="font-weight:bold;"><a href="report_frdate_pdf.php?type=4&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a> | ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" style="font-weight:bold;"><u>รวม</u></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_value7,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_vat7,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_money7,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_discount7,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($sum_summary7,2); ?></td>
		<td></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){
?>
    <tr><td colspan="16" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<!-- ////////////////////////////// ยกเลิกใบเสร็จ //////////////////////////////////// ยกเลิกใบเสร็จ /////////////////////////////////////// ยกเลิกใบเสร็จ ///////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="16" align="left" style="font-weight:bold;">รายงาน ยกเลิกใบเสร็จ ประจำวันที่ <?php echo "$restrn"; ?></td>
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
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$sum_1 = 0;
$sum_2 = 0;

$qry_fq=pg_query("select \"ref_receipt\", \"IDNO\", \"admin_approve\", \"c_money\", \"c_receipt\", \"c_date\", \"ref_prndate\", \"ref_recdate\", \"paytypefrom\", \"c_memo\"
				from \"CancelReceipt\" WHERE (\"c_date\"='$tday') ORDER BY \"c_receipt\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $ref_receipt = $res_fr["ref_receipt"];
    
    $sub_ref = substr($ref_receipt,2,1);
    if($sub_ref == 'R'){
    $num++;
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
    
	//หาผู้ออกใบเสร็จ
	$O_RECEIPT = $res_fr["c_receipt"];
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
	
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
        <td align="right"><?php echo number_format($res_fr["c_money"]+$vat,2); ?></td>
        <td align="center"><?php echo $res_fr["ref_prndate"]; ?></td>
        <td align="center"><?php echo $res_fr["ref_recdate"]; ?></td>
        <td align="center"><?php echo $ref_receipt; ?></td>
        <td align="center"><?php echo $res_fr["paytypefrom"]; ?></td>
        <td align="center"><?php echo $show_app; ?></td>
        <td align="left"><?php echo $res_fr["c_memo"]; ?></td>
		<td align="left"><?php echo $fullnameuseracc; ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;"><a href="report_cancel_pdf.php?type=1&tday=<?php echo $tday; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a> | ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" colspan="7" style="font-weight:bold;"><u>รวมอนุมัติแล้ว</u> <?php echo number_format($sum_1,2); ?> | <u>รวมยังไม่อนุมัติ</u> <?php echo number_format($sum_2,2); ?></td>
    </tr>
<?php
}
?>

<?php 
if($num == 0){
?>
    <tr><td colspan="16" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

        </td>
    </tr>
</table>

</body>
</html>