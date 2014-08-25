<?php 
include("../config/config.php");
$cid = pg_escape_string($_GET['id']);

$qry=pg_query("SELECT * FROM gas.\"PoGas\" WHERE poid = '$cid' ");
if($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $bill = $res["bill"];
    $invoice = $res["invoice"];
}

$qry_md=pg_query("SELECT * FROM gas.\"Model\" WHERE modelid = '$idmodel' ");
if($res_md=pg_fetch_array($qry_md)){
    $modelname = $res_md["modelname"];
}

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


<fieldset><legend><B>แก้ไขข้อมูล</B></legend>

<form name="frm_2" id="frm_2" method="post" action="frm_gs_edit_bill_send.php">

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td width="10%"><b>ID</b></td>
        <td width="90%" colspan="3"><?php echo $id; ?><input type="hidden" name="id" value="<?php echo $id; ?>"></td>
    </tr>
    <tr>
        <td><b>เลขที่สัญญา</b></td>
        <td><?php echo $idno; ?></td>
        <td><b>วันที่ทำรายการ</b></td>
        <td><?php echo $date; ?></td>
    </tr>
    <tr>
        <td><b>บริษัท</b></td>
        <td><?php echo $idcompany; ?></td>
        <td><b>รุ่น/ประเภท</b></td>
        <td><?php echo $modelname; ?></td>
    </tr>
    <tr>
        <td><b>ราคาทุน</b></td>
        <td><?php echo number_format($costofgas,2); ?> บาท.</td>
        <td><b>Vat</b></td>
        <td><?php echo number_format($vatofcost,2); ?> บาท.</td>
    </tr>
    <tr>
        <td><b>ผลรวม</b></td>
        <td colspan="3"><?php echo number_format($costofgas+$vatofcost,2); ?> บาท.</td>
    </tr>
    <tr>
        <td><b>เลขที่ ใบกำกับ</b></td>
        <td><input type="text" name="invoice" size="30" value="<?php echo $invoice; ?>"></td>
        <td><b>เลขที่ ใบเสร็จ</b></td>
        <td><input type="text" name="bill" size="30" value="<?php echo $bill; ?>"></td>
    </tr>
    <tr>
        <td></td>
        <td><br><input name="button" id="button" type="submit" value=" บันทึก "> <input type="button" value="  Back  " onclick="location.href='frm_gs_edit.php'"></td>
    </tr>
</table>

</form>

</fieldset>


        </td>
    </tr>
</table>

</body>
</html>