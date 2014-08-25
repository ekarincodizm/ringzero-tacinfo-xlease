<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>รายละเอียดความสัมพันธ์ทางบัญชี</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>

<body>

<table width="95%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td align="center" valign="top" style="background-repeat:repeat-y">

<div class="header"><h1>(THCAP) จัดการประเภทค่าใช้จ่าย</h1></div>

<div width="100%" align="left">
	<font style="background-color:#EECC00;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 1 :: ทรัพย์สิน
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<font style="background-color:#FF5555;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 2 :: หนี้สิน
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<font style="background-color:#FF55FF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 3 :: ทุน
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<font style="background-color:#33FF33;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 4 :: รายได้
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<font style="background-color:#33BFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 5 :: รายจ่าย
</div><br>

<fieldset><legend><b>รายละเอียดความสัมพันธ์ทางบัญชี</b></legend>
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"  valign="top">รหัสประเภทค่าใช้จ่าย</td>
        <td align="center"  valign="top">รหัสประเภทบริษัท</td>
        <td align="center"  valign="top">รหัสประเภทสัญญา</td>
        <td align="center"  valign="top">ชื่อประเภทค่าใช้จ่าย</td>
        <td align="center"  valign="top">คำอธิบายประเภทค่าใช้จ่าย</td>
		<td align="center"  valign="top">เลขที่สมุดบัญชีพื้นฐาน</td>
		<td align="center"  valign="top">ชื่อสมุดบัญชีพื้นฐาน</td>
		<td align="center"  valign="top">เลขที่สมุดบัญชีคงค้าง</td>
		<td align="center"  valign="top">ชื่อสมุดบัญชีคงค้าง</td>
		<td align="center"  valign="top">เลขที่สมุดบัญชีทยอยรับรู้</td>
		<td align="center"  valign="top">ชื่อสมุดบัญชีทยอยรับรู้</td>
    </tr>
   
<?php
$qry_name=pg_query("SELECT * FROM account.\"v_thcap_typepay_acc_detials\" ORDER BY \"tpID\" ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name))
{
    $tpID = $res_name["tpID"];
    $tpCompanyID = $res_name["tpCompanyID"];
    $tpConType = $res_name["tpConType"];  
    $tpDesc = $res_name["tpDesc"];
    $tpFullDesc = $res_name["tpFullDesc"];
	$tpBasis = $res_name["tpBasis"];
	$accBookID_tpBasis = $res_name["accBookID_tpBasis"];
    $accBookName_tpBasis = $res_name["accBookName_tpBasis"];
	$tpAccrual = $res_name["tpAccrual"];
	$accBookID_tpAccrual = $res_name["accBookID_tpAccrual"];
    $accBookName_tpAccrual = $res_name["accBookName_tpAccrual"];
	$tpAmortize = $res_name["tpAmortize"];
	$accBookID_tpAmortize = $res_name["accBookID_tpAmortize"];
    $accBookName_tpAmortize = $res_name["accBookName_tpAmortize"];
	
	// หาประเภทสมุดบัญชีของ สมุดบัญชีพื้นฐาน
	if($tpBasis != "")
	{
		$qry_typeBasis = pg_query("select \"accBookType\" from account.\"all_accBook\" where \"accBookserial\" = '$tpBasis' ");
		$typeBasis = pg_result($qry_typeBasis,0);
	}
	else
	{
		$typeBasis = "";
	}
	
	// หาประเภทสมุดบัญชีของ สมุดบัญชีคงค้าง
	if($tpAccrual != "")
	{
		$qry_typeAccrual = pg_query("select \"accBookType\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAccrual' ");
		$typeAccrual = pg_result($qry_typeAccrual,0);
	}
	else
	{
		$typeAccrual = "";
	}
	
	// หาประเภทสมุดบัญชีของ สมุดบัญชีทยอยรับรู้
	if($tpAmortize != "")
	{
		$qry_typeAmortize = pg_query("select \"accBookType\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAmortize' ");
		$typeAmortize = pg_result($qry_typeAmortize,0);
	}
	else
	{
		$typeAmortize = "";
	}

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
	
	// กำหนดสี สมุดบัญชีพื้นฐาน
	if($typeBasis == "1"){$colorBasis = "style=\"background-color:#EECC00;\" ";} // ทรัพย์สิน
	elseif($typeBasis == "2"){$colorBasis = "style=\"background-color:#FF5555;\" ";} // หนี้สิน
	elseif($typeBasis == "3"){$colorBasis = "style=\"background-color:#FF55FF;\" ";} // ทุน
	elseif($typeBasis == "4"){$colorBasis = "style=\"background-color:#33FF33;\" ";} // รายได้
	elseif($typeBasis == "5"){$colorBasis = "style=\"background-color:#33BFFF;\" ";} // รายจ่าย
	else{$colorBasis = "";}
	
	// กำหนดสี สมุดบัญชีคงค้าง
	if($typeAccrual == "1"){$colorAccrual = "style=\"background-color:#EECC00;\" ";} // ทรัพย์สิน
	elseif($typeAccrual == "2"){$colorAccrual = "style=\"background-color:#FF5555;\" ";} // หนี้สิน
	elseif($typeAccrual == "3"){$colorAccrual = "style=\"background-color:#FF55FF;\" ";} // ทุน
	elseif($typeAccrual == "4"){$colorAccrual = "style=\"background-color:#33FF33;\" ";} // รายได้
	elseif($typeAccrual == "5"){$colorAccrual = "style=\"background-color:#33BFFF;\" ";} // รายจ่าย
	else{$colorAccrual = "";}
	
	// กำหนดสี สมุดบัญชีทยอยรับรู้
	if($typeAmortize == "1"){$colorAmortize = "style=\"background-color:#EECC00;\" ";} // ทรัพย์สิน
	elseif($typeAmortize == "2"){$colorAmortize = "style=\"background-color:#FF5555;\" ";} // หนี้สิน
	elseif($typeAmortize == "3"){$colorAmortize = "style=\"background-color:#FF55FF;\" ";} // ทุน
	elseif($typeAmortize == "4"){$colorAmortize = "style=\"background-color:#33FF33;\" ";} // รายได้
	elseif($typeAmortize == "5"){$colorAmortize = "style=\"background-color:#33BFFF;\" ";} // รายจ่าย
	else{$colorAmortize = "";}
?>
        <td align="center"><?php echo "$tpID"; ?></td>
        <td align="center"><?php echo "$tpCompanyID"; ?></td>
        <td align="center"><?php echo "$tpConType"; ?></td>
        <td align="left"><?php echo "$tpDesc"; ?></td>
        <td align="left"><?php echo "$tpFullDesc"; ?></td>
		<td align="center" <?php echo $colorBasis; ?>><?php echo "$accBookID_tpBasis"; ?></td>
        <td align="left" <?php echo $colorBasis; ?>><?php echo "$accBookName_tpBasis"; ?></td>
		<td align="center" <?php echo $colorAccrual; ?>><?php echo "$accBookID_tpAccrual"; ?></td>
        <td align="left" <?php echo $colorAccrual; ?>><?php echo "$accBookName_tpAccrual"; ?></td>
		<td align="center" <?php echo $colorAmortize; ?>><?php echo "$accBookID_tpAmortize"; ?></td>
        <td align="left" <?php echo $colorAmortize; ?>><?php echo "$accBookName_tpAmortize"; ?></td>
	</tr>
<?php
}
?>
</table>

<div align="center"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

		</td>
	</tr>
</table>

</body>
</html>