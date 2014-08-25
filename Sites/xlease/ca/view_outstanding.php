<?php
session_start();
SET_TIME_LIMIT(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
$nowdate = Date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    </head>
<body>
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<div style="margin:5px 0px 5px 0px">

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน ค้างค่างวด ประจำวันที่ <?php echo $nowdate; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">สีรถ</td>
        <td align="center">จำนวนงวดที่ค้าง</td>
        <td align="center">จำนวนวันที่ค้างถึงปัจจุบัน</td>
    </tr>

<?php
$qry_fr=pg_query("select a.\"IDNO\",COUNT(a.\"DueNo\") as \"SumDueNo\",MAX(a.\"daydelay\") as \"daydelay\",b.\"full_name\",b.\"C_COLOR\",b.\"asset_type\",b.\"C_REGIS\",b.\"car_regis\" from \"VRemainPayment\" a
left join \"VContact\" b on a.\"IDNO\"=b.\"IDNO\"
GROUP BY a.\"IDNO\",b.\"full_name\",b.\"C_COLOR\",b.\"asset_type\",b.\"C_REGIS\",b.\"car_regis\" ORDER BY \"SumDueNo\" DESC,\"daydelay\" DESC ");
$num=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){

    $IDNO = $res_fr["IDNO"];
    if($res_fr["asset_type"] == 1) $show_regis = $res_fr["C_REGIS"]; else $show_regis = $res_fr["car_regis"];

    
        $i+=1;
        if($res_fr["SumDueNo"]>2){
            $i=0;
            echo "<tr class=\"red\">";
        }elseif($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "<a href=\"../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding\" target=\"_blank\" title=\"ดูตารางการชำระ $full_name\">$IDNO</a>"; ?></td>
        <td align="left"><?php echo $res_fr["full_name"]; ?></td>
        <td align="left"><?php echo $show_regis; ?></td>
        <td align="left"><?php echo $res_fr["C_COLOR"]; ?></td>
        <td align="center"><?php echo $res_fr["SumDueNo"]; ?></td>
        <td align="center"><?php echo $res_fr["daydelay"]; ?></td>
    </tr>
<?php
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td align="left" colspan="3">ทั้งหมด <?php echo $num; ?> รายการ</td>
        <td colspan="3" align="right"><a href="report_outstanding_pdf.php" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="10" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

</div>

        </td>
    </tr>
</table>

</body>
</html>