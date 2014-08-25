<?php 
include("../config/config.php"); 
$company = pg_escape_string($_POST['company']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
	</tr>
	<tr>
		<td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>
<div class="wrapper">

<fieldset><legend><a href="frm_maker.php"><B>Maker</a> > ประกันภัยภาคสมัครใจ - แสดงรายการ</B></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="frm_maker_select_unforce_show.php">
เลือกบริษัทประกัน 
<SELECT NAME="company" onchange="document.frm_fuc1.submit()";>
    <option value="">เลือก</option>
<?php
$qry_inf=pg_query("select * from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $InsCompany = $res_inf["InsCompany"];
    $InsFullName = $res_inf["InsFullName"];
    if($_POST['company'] == $InsCompany){
?>       
    <option value="<?php echo "$InsCompany"; ?>" selected><?php echo "$InsFullName"; ?></option>
<?php
    }else{
?>
    <option value="<?php echo "$InsCompany"; ?>"><?php echo "$InsFullName"; ?></option>        
<?php        
    }
}
?>
</SELECT>
</form>
</div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">InsID</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ-สกุล</td>
        <td align="center">StartDate</td>
        <td align="center">Premium</td>
        <td align="center">CoPayInsAmt</td>
        <td align="center">NetPremium</td>
        <td align="center">OutStanding</td>
    </tr>
<?php
if( isset($_POST['company']) ){
    $qry_if=pg_query("select * from \"insure\".\"InsureUnforce\" WHERE \"Company\"='".pg_escape_string($_POST[company])."' AND \"Cancel\"='FALSE' AND \"CoPayInsReady\"='FALSE' AND \"CoPayInsID\" is not null ORDER BY \"InsID\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $InsUFIDNO = $res_if["InsUFIDNO"];
        $IDNO = $res_if["IDNO"];
        $Premium = $res_if["Premium"];
        $StartDate = $res_if["StartDate"];
        $InsID = $res_if["InsID"];
        $CoPayInsAmt = $res_if["CoPayInsAmt"];
            $summary += $CoPayInsAmt;
        $CoPayInsID = $res_if["CoPayInsID"];
        $NetPremium = $res_if["NetPremium"];
        
        $rs=pg_query("select insure.\"outstanding_insureunforce\"('$InsUFIDNO')");
        $outstanding=pg_fetch_result($rs,0);
        
        $qry_name=pg_query("select full_name from insure.\"VInsUnforceDetail\" WHERE \"InsUFIDNO\"='$InsUFIDNO'");
        if($res_name=pg_fetch_array($qry_name)){
            $full_name = $res_name["full_name"];    
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="left"><?php echo "$InsID"; ?></td>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="center"><?php echo "$StartDate"; ?></td>
        <td align="right"><?php echo number_format($Premium,2); ?></td>
        <td align="right"><?php echo number_format($CoPayInsAmt,2); ?></td>
        <td align="right"><?php echo number_format($NetPremium,2); ?></td>
        <td align="right"><?php echo number_format($outstanding,2); ?></td>
    </tr>
<?php        
    }
    if($rows == 0){
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="center" colspan=10>ไม่พบข้อมูล</td>
    </tr>
<?php
    }
    $qry_rm=pg_query("select \"Remark\" from \"insure\".\"PayToInsure\" WHERE \"PayID\"='$CoPayInsID'");
    if($res_name=pg_fetch_array($qry_rm)){
        $Remark = $res_name["Remark"];    
    }    
}
?>
</table>

<?php
if($rows > 0){
?>
<form name="frm_remark1" method="post" action="frm_maker_select_unforce_update_remark.php">
<input name="payid" type="hidden" value="<?php echo $CoPayInsID; ?>">
<table width="100%" border="0">
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="left" width="50%"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" width="50%"><b>รวมเงินที่ต้องชำระ</b> <?php echo number_format($summary,2); ?></td>
    </tr>
    <tr align="left">
        <td colspan=2><br><b>หมายเหตุ</b><br><textarea name="remark" rows="5" cols="90" style="font-size:11px;"><?php echo $Remark; ?></textarea></td>
    </tr>
    <tr align="left">
        <td colspan=2><input name="button" type="submit" value="บันทึกหมายเหตุ"></td>
    </tr>
</table>
</form>
<?php
    echo "<div align=\"right\"><br><a href=\"frm_maker_select_unforce_print.php?company=$company\" target=\"_blank\"><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"\"> <b>สั่งพิมพ์</b></a></div>";
}
?>
</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>


</body>
</html>