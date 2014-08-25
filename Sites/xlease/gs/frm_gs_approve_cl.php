<?php 
include("../config/config.php"); 
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

<fieldset><legend><B>ยกเลิกอนุมัติ</B></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="">
เลือกบริษัท
<SELECT NAME="company" onchange="document.frm_fuc1.submit()";>
    <option value="">เลือก</option>
<?php
$qry_inf=pg_query("select * from gas.\"Company\" ORDER BY \"coname\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $coid = $res_inf["coid"];
    $coname = $res_inf["coname"];
    if($_POST['company'] == $coid){
?>  
    <option value="<?php echo "$coid"; ?>" selected><?php echo "$coname"; ?></option>
<?php
    }else{
?>
    <option value="<?php echo "$coid"; ?>"><?php echo "$coname"; ?></option>        
<?php
    }
}
?>
</SELECT>
</form>
</div>

<br>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่</td>
        <td align="center">บริษัท</td>
        <td align="center">รุ่น/ประเภท</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
    </tr>

<?php
if( isset($_POST['company']) ){
    
$qry=pg_query("SELECT * FROM gas.\"PoGas\" WHERE status_pay = 't' AND status_approve = 't' AND bill is null AND invoice is not null AND idcompany='".pg_escape_string($_POST[company])."' ORDER BY \"idno\" ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $payid = $res["payid"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
    
    $costofgas = round($costofgas, 2);
    $vatofcost = round($vatofcost, 2);
    
    $s_costofgas += $costofgas;
    $s_vatofcost += $vatofcost;
    $s_all += $costofgas+$vatofcost;
    
    $qry2=pg_query("SELECT modelname FROM gas.\"Model\" WHERE modelid = '$idmodel' ");
    if($res2=pg_fetch_array($qry2)){
        $modelname = $res2["modelname"];
    }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$id"; ?></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$date"; ?></td>
        <td align="center"><?php echo "$idcompany"; ?></td>
        <td align="center"><?php echo "$modelname"; ?></td>
        <td align="center"><?php echo "$bill"; ?></td>
        <td align="center"><?php echo "$invoice"; ?></td>
        <td align="right"><?php echo number_format($costofgas,2); ?></td>
        <td align="right"><?php echo number_format($vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($costofgas+$vatofcost,2); ?></td>
    </tr>
<?php        
    }
    if($rows == 0){
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="center" colspan=10>ไม่พบข้อมูล</td>
    </tr>
<?php
    }else{
?>
    <tr>
        <td align="right" colspan="7"><b>รวม</b></td>
        <td align="right"><?php echo number_format($s_costofgas,2); ?></td>
        <td align="right"><?php echo number_format($s_vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($s_all,2); ?></td>
    </tr>
<?php        
    }
}
?>
</table>

<?php
if($rows > 0){
?>

<div align="center">
    <form name="approval" method="post" action="frm_gs_approve_cl_send.php">
        <input type="hidden" name="payid" value="<?php echo $payid; ?>">
        <input type="submit" name="submit" value="ยกเลิกรายการนี้">
    </form>
</div>

<?php
}
?>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>