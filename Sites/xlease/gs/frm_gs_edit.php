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


<fieldset><legend><B>แก้ไข</B></legend>

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
        <td align="center">วันที่</td>
        <td align="center">บริษัท</td>
        <td align="center">รุ่น/ประเภท</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
        <td align="center">แก้ไข</td>
    </tr>

<form name="frm_2" id="frm_2" method="post" action="frm_gs_cl_send.php">
<?php
$qry=pg_query("SELECT * FROM gas.\"PoGas\" WHERE idcompany='$company' AND (bill is not null OR invoice is not null) ORDER BY \"idno\" ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
                
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
        <td align="center"><?php echo "$idcompany"; ?></td>
        <td align="center"><?php echo "$idmodel"; ?></td>
        <td align="center"><?php echo "$bill"; ?></td>
        <td align="center"><?php echo "$invoice"; ?></td>
        <td align="right"><?php echo number_format($costofgas,2); ?></td>
        <td align="right"><?php echo number_format($vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($costofgas+$vatofcost,2); ?></td>
        <td align="center">
<?php
if($invoice != '-' AND $bill == '-'){
?>
<a href="frm_gs_edit_invoice.php?id=<?php echo "$id"; ?>"><img src="edit.png" border="0" width="16" height="16" alt="แก้ไข <?php echo "$id"; ?>"></a>
<?php
}elseif($invoice != '-' AND $bill != '-'){
?>
<a href="frm_gs_edit_bill.php?id=<?php echo "$id"; ?>"><img src="edit.png" border="0" width="16" height="16" alt="แก้ไข <?php echo "$id"; ?>"></a>
<?php
}
?>
        </td>
    </tr>
<?php
} // ปิด while

if($rows > 0){

}else{
?>
    <tr bgcolor="#FFFFFF">
        <td align="center" colspan="20"><br>- ไม่พบข้อมูล -<br><br></td>
    </tr>
<?php
}
?>
</form>
</table>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>