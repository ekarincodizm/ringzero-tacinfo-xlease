<?php 
include("../config/config.php"); 

$company = pg_escape_string($_POST['company']);
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


<fieldset><legend><B>รายการ รอทำสัญญา</B></legend>

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

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่ทำรายการ</td>
        <td align="center">วันที่ติดตั้ง</td>
        <td align="center">บริษัท</td>
        <td align="center">Model</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
    </tr>
<?php
$qry=pg_query("SELECT * FROM gas.\"PoGas\" where idcompany='$company' AND idno is null AND status_po = 't' ORDER BY \"idcompany\",\"idno\" ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"]; if(empty($idno)) $idno = "-";
    $date = $res["podate"];
    $date_install = $res["date_install"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
    
    $sum_costofgas += $costofgas;
    $sum_vatofcost += $vatofcost;
    $sum_all += $costofgas+$vatofcost;
    
    $qry_name=pg_query("SELECT modelname FROM gas.\"Model\" WHERE \"modelid\" = '$idmodel' ");
    if($res_name=pg_fetch_array($qry_name)){
        $modelname = $res_name["modelname"];
    }
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo "$id"; ?></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$date"; ?></td>
        <td align="center"><?php echo "$date_install"; ?></td>
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
    <tr bgcolor="#ffffff">
        <td align="center" colspan=20><br>- ไม่พบข้อมูล -<br><br></td>
    </tr>

<?php
}else{
?>
<tr>
    <td colspan="2" align="left"><b>ทั้งหมด <?php echo $rows; ?> รายการ</b></td>
    <td colspan="6" align="right"><b>รวม</b></td>
    <td align="right"><b><?php echo number_format($sum_costofgas,2); ?></b></td>
    <td align="right"><b><?php echo number_format($sum_vatofcost,2); ?></b></td>
    <td align="right"><b><?php echo number_format($sum_all,2); ?></b></td>
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