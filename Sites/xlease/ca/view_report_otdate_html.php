<?php
session_start();
include("../config/config.php");

$nowdate = nowDate();//ดึง วันที่จาก server
$nowdate=pg_query("select conversiondatetothaitext('$nowdate')");  
$nowdate=pg_fetch_result($nowdate,0);

$_SESSION["av_iduser"];
$tday=pg_escape_string($_GET["report_ot_Date"]);
$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);

$qry_any=pg_query("select * from \"PayTypeFromAnyPlace\";");
$set_anyplace=pg_fetch_result($qry_any,0);
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

<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div class="wrapper">

<div align="right"><?php echo "วันที่พิมพ์&nbsp;&nbsp;$nowdate";?></div>

<!-- //////////////////////////// รับเงินสดค่าอื่นๆ ////////////////////////////////////////// รับเงินสดค่าอื่นๆ ////////////////////////////////////////// รับเงินสดค่าอื่นๆ ////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="12" align="left" style="font-weight:bold;">รายงาน รับเงินสดค่าอื่นๆ ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#C0C0C0">
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
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$sum_wall = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CA') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    $num++;
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    if( $res_ss["TName"] == "วิทยุสื่อสาร" ){
        $sum_wall += $res_fr["O_MONEY"];
    }
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd_light\">";
    }else{
        echo "<tr class=\"even_light\">";
    }
	//หาชื่อพนักงานที่ออกใบเสร็จ
	$qryname=pg_query("SELECT fullname
	FROM \"FCash\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailTranpay\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACTran\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'");
	list($user_name)=pg_fetch_array($qryname);
	if($user_name==""){
		$user_name="ไม่พบผู้ออกใบเสร็จ";
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
		<td align="left"><?php echo $user_name; ?></td>
	</tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;"></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมเฉพาะค่าวิทยุ <?php echo number_format($sum_wall,2); ?> | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="12" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<!-- //////////////////////////// รับเช็คค่าอื่นๆ ////////////////////////////////////////// รับเช็คค่าอื่นๆ ////////////////////////////////////////// รับเช็คค่าอื่นๆ ////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="12" align="left" style="font-weight:bold;">รายงาน รับเช็คค่าอื่นๆ ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#C0C0C0">
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
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$sum_wall = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CU') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    $num++;
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);    
    
    if( $res_ss["TName"] == "วิทยุสื่อสาร" ){
        $sum_wall += $res_fr["O_MONEY"];
    }
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd_light\">";
    }else{
        echo "<tr class=\"even_light\">";
    }
	//หาชื่อพนักงานที่ออกใบเสร็จ
	$qryname=pg_query("SELECT fullname
	FROM \"FCash\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailTranpay\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACTran\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'");
	list($user_name)=pg_fetch_array($qryname);
	if($user_name==""){
		$user_name="ไม่พบผู้ออกใบเสร็จ";
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
		<td align="left"><?php echo $user_name; ?></td>  
	</tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;"></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมเฉพาะค่าวิทยุ <?php echo number_format($sum_wall,2); ?> | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="12 align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

<!-- ////////////////////////////////// รับเงินจากที่อื่น (ค่าอื่นๆ) ////////////////////////////////// รับเงินจากที่อื่น (ค่าอื่นๆ) //////////////////////////////////////// รับเงินจากที่อื่น (ค่าอื่นๆ) ////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="12" align="left" style="font-weight:bold;">รายงาน รับเงินจากที่อื่น (ค่าอื่นๆ) ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#C0C0C0">
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
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$sum_wall = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",\"PayType\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_memo\"<>'TR-ACC' OR \"O_memo\" is null OR \"O_memo\"='') GROUP BY \"O_RECEIPT\",\"PayType\" ORDER BY \"PayType\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
        
        
    if(!in_array($res_fr["PayType"],$arr_set_anyplace)){
        continue;
    }
    
    $num++;
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    if( $res_ss["TName"] == "วิทยุสื่อสาร" ){
        $sum_wall += $res_fr["O_MONEY"];
    }
    
    $summary+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd_light\">";
    }else{
        echo "<tr class=\"even_light\">";
    }
	//หาชื่อพนักงานที่ออกใบเสร็จ
	$qryname=pg_query("SELECT fullname
	FROM \"FCash\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailTranpay\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACTran\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'");
	list($user_name)=pg_fetch_array($qryname);
	if($user_name==""){
		$user_name="ไม่พบผู้ออกใบเสร็จ";
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
		<td align="left"><?php echo $user_name; ?></td> 
	</tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;"></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ | รวมเฉพาะค่าวิทยุ <?php echo number_format($sum_wall,2); ?> | รวมยอดเงิน <?php echo number_format($summary,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="12" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

<!-- ////////////////////////////////// รับเงินโอน (ค่าอื่นๆ) ///////////////////////////////// รับเงินโอน (ค่าอื่นๆ) ///////////////////////////////////////// รับเงินโอน (ค่าอื่นๆ) ////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="12" align="left" style="font-weight:bold;">รายงาน รับเงินโอน (ค่าอื่นๆ) ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#C0C0C0">
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
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$summary = 0;
$sum_wall = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",\"PayType\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"PayType\" <> 'OC') GROUP BY \"O_RECEIPT\",\"PayType\" ORDER BY \"PayType\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
        
    if(in_array($res_fr["PayType"],$arr_set_anyplace)){
        continue;
    }
    
    $num++;
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    if( $res_ss["TName"] == "วิทยุสื่อสาร" ){
        $sum_wall += $res_fr["O_MONEY"];
    }
    
    $summary+=$res_fr["O_MONEY"];
    $summary7+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd_light\">";
    }else{
        echo "<tr class=\"even_light\">";
    }
	//หาชื่อพนักงานที่ออกใบเสร็จ
	$qryname=pg_query("SELECT fullname
	FROM \"FCash\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailTranpay\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACTran\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'");
	list($user_name)=pg_fetch_array($qryname);
	if($user_name==""){
		$user_name="ไม่พบผู้ออกใบเสร็จ";
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
		<td align="left"><?php echo $user_name; ?></td>   
		</tr>
<?php
}
}

if($num>0){
?>
<tr>
    <td colspan="12" align="right" style="font-weight:bold;">รวมย่อย ทั้งหมด <?php echo $n; ?> รายการ | รวมเฉพาะค่าวิทยุ <?php echo number_format($sum_wall,2); ?> | รวมเงิน <?php echo number_format($summary,2); ?></td>
</tr>
<?php
}
?>

<!-- ////////////////////////////// CCA //////////////////////////////////// CCA /////////////////////////////////////// CCA ///////////////////////////////////// -->

<?php
$n = 0;
$summary = 0;
$num2 = 0;
$sum_wall = 0;
$qry_fq=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CCA') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    $num++;
    $num2++;
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_fr[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);

    if( $res_ss["TName"] == "วิทยุสื่อสาร" ){
        $sum_wall += $res_fr["O_MONEY"];
    }
    
    $summary+=$res_fr["O_MONEY"];
    $summary7+=$res_fr["O_MONEY"];
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd_light\">";
    }else{
        echo "<tr class=\"even_light\">";
    }
	//หาชื่อพนักงานที่ออกใบเสร็จ
	$qryname=pg_query("SELECT fullname
	FROM \"FCash\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT fullname
	FROM \"DetailTranpay\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'
	union 
	SELECT c.fullname
	FROM \"FTACTran\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[O_RECEIPT]'");
	list($user_name)=pg_fetch_array($qryname);
	if($user_name==""){
		$user_name="ไม่พบผู้ออกใบเสร็จ";
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
		<td align="left"><?php echo $user_name; ?></td>
	</tr>
<?php
}
}

if($num2>0){
?>
<tr>
    <td colspan="12" align="right" style="font-weight:bold;">รวมย่อย ทั้งหมด <?php echo $n; ?> รายการ | รวมเฉพาะค่าวิทยุ <?php echo number_format($sum_wall,2); ?> | รวมเงิน <?php echo number_format($summary,2); ?></td>
</tr>
<?php
}

if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;"></td>
        <td colspan="6" align="right" style="font-weight:bold;">ทั้งหมด <?php echo $num; ?> รายการ | รวมยอดเงิน <?php echo number_format($summary7,2); ?></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="12" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>


<!-- /////////////////////////////////// ยกเลิกใบเสร็จ /////////////////////////////// ยกเลิกใบเสร็จ /////////////////////////////////////// ยกเลิกใบเสร็จ ///////////////////////////////////// -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="13" align="left" style="font-weight:bold;">รายงาน ยกเลิกใบเสร็จ ประจำวันที่ <?php echo "$restrn"; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#C0C0C0">
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
		<td align="center">ผลการอนุมัติ</td>
        <td align="center">Memo</td>
		<td align="center">ผู้ออกใบเสร็จ</td>
    </tr>

<?php
$n = 0;
$num = 0;
$sum_1 = 0;
$sum_2 = 0;

$qry_fq=pg_query("select * from \"CancelReceipt\" WHERE (\"c_date\"='$tday') ORDER BY \"c_receipt\" ASC ");
while($res_fr=pg_fetch_array($qry_fq)){
    
    $ref_receipt = $res_fr["ref_receipt"];
    
    $sub_ref = substr($ref_receipt,2,1);
    if($sub_ref != 'R'){
        
    $num++;
    $SIDNO = $res_fr["IDNO"];
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$SIDNO' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        //$vat = $res_cc1['VatValue'];
    }
    
	// ทำรายการอนุมัติแล้วหรือยัง
    if($res_fr["admin_approve"] == 't'){
        $show_app = "อนุมัติแล้ว";
        $sum_1+=$res_fr["c_money"]+$vat;
    }else{
        $show_app = "ยังไม่อนุมัติ";
        $sum_2+=$res_fr["c_money"]+$vat;
    }
	
	// ผลการอนุมัติ
	if($res_fr["statusApprove"] == 't'){
        $status_app = "<font color=\"#0000FF\">อนุมัติ</font>";
        $sum_status_app_1+=$res_fr["c_money"]+$vat;
	}elseif($res_fr["statusApprove"] == 'f'){
        $status_app = "<font color=\"#FF0000\">ไม่อนุมัติ</font>";
        $sum_status_app_2+=$res_fr["c_money"]+$vat;
    }else{
        $status_app = "";
        $sum_status_app_3+=$res_fr["c_money"]+$vat;
    }
    
    $n++;
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd_light\">";
    }else{
        echo "<tr class=\"even_light\">";
    }
	//หาชื่อพนักงานที่ออกใบเสร็จ
	$qryname=pg_query("SELECT fullname
	FROM \"FCash\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[c_receipt]'
	union 
	SELECT fullname
	FROM \"DetailCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[c_receipt]'
	union 
	SELECT fullname
	FROM \"DetailTranpay\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"ReceiptNo\"='$res_fr[c_receipt]'
	union 
	SELECT c.fullname
	FROM \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[c_receipt]'
	union 
	SELECT c.fullname
	FROM \"FTACTran\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
	where \"refreceipt\"='$res_fr[c_receipt]'");
	list($user_name)=pg_fetch_array($qryname);
	if($user_name==""){
		$user_name="ไม่พบผู้ออกใบเสร็จ";
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
		<td align="center"><?php echo $status_app; ?></td>
        <td align="left"><?php echo $res_fr["c_memo"]; ?></td>
		<td align="left"><?php echo $user_name; ?></td>
    </tr>
<?php
}
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="5" align="left" style="font-weight:bold;">ทั้งหมด <?php echo $n; ?> รายการ</td>
        <td align="right" colspan="8" style="font-weight:bold;"><u>รวมอนุมัติแล้ว</u> <?php echo number_format($sum_1,2); ?> | <u>รวมยังไม่อนุมัติ</u> <?php echo number_format($sum_2,2); ?></td>
    </tr>
	<tr>
        <td align="right" colspan="13" style="font-weight:bold;">
			<font color="#0000FF"><u>รวมอนุมัติ</u> <?php echo number_format($sum_status_app_1,2); ?></font> | <font color="#FF0000"><u>รวมไม่อนุมัติ</u> <?php echo number_format($sum_status_app_2,2); ?></font> | <u>รวมรออนุมัติ</u> <?php echo number_format($sum_status_app_3,2); ?>
		</td>
    </tr>
<?php
}
?>

<?php 
if($num == 0){   
?>
    <tr><td colspan="12" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>    
</table>

        </td>
    </tr>
</table>

</body>
</html>