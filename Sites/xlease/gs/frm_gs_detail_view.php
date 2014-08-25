<?php 
include("../config/config.php"); 

$id = pg_escape_string($_GET['id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>


<fieldset><legend><B>รายละเอียด</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">POID</td>
        <td align="center">IDNO</td>
        <td align="center">V_DueNo</td>
        <td align="center">R_Date</td>
        <td align="center">R_Receipt</td>
        <td align="center">ยอดเงิน</td>
        <td align="center">O_DATE</td>
        <td align="center">O_RECEIPT</td>
        <td align="center">O_MONEY</td>
    </tr>
<?php
$qry=pg_query("SELECT * FROM gas.\"VCusPay\" where poid = '$id' ORDER BY poid ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $poid = $res["poid"];
    $idno = $res["idno"];
    $V_DueNo = $res["V_DueNo"]; if($V_DueNo == 0) $V_DueNo = "ดาวน์"; else $V_DueNo = "ซื้อสด";
    $R_Date = $res["R_Date"];
    $R_Receipt = $res["R_Receipt"];
    $amount = $res["amount"];
    $O_DATE = $res["O_DATE"];
    $O_RECEIPT = $res["O_RECEIPT"];
    $O_MONEY = $res["O_MONEY"];
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo "$poid"; ?></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$V_DueNo"; ?></td>
        <td align="center"><?php echo "$R_Date"; ?></td>
        <td align="center"><?php echo "$R_Receipt"; ?></td>
        <td align="right"><?php echo number_format($amount,2); ?></td>
        <td align="center"><?php echo "$O_DATE"; ?></td>
        <td align="center"><?php echo "$O_RECEIPT"; ?></td>
        <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    </tr>
<?php
}

if($rows == 0){
?>
    <tr bgcolor="#ffffff">
        <td align="center" colspan=20><br>- ไม่พบข้อมูล -<br><br></td>
    </tr>

<?php
}
?>
</table>

</fieldset>


        </td>
    </tr>
</table>

</body>
</html>