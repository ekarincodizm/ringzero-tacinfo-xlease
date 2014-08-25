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


<fieldset><legend><B>ชำระเิงิน</B></legend>

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
        <td align="center">สถานะชำระเงิน</td>
        <td align="center">สถานะอนุมัติ</td>
        <td align="center">ชำระเงิน</td>
    </tr>

<form name="frm_2" id="frm_2" method="post" action="1.php">
<?php
$qry=pg_query("SELECT * FROM \"GasPo\" WHERE status_pay = 'f' ORDER BY \"idno\" ASC ");
$rows = pg_num_rows($qry);  
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $model = $res["model"];
    $cost = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
    $status_pay = $res["status_pay"]; if($status_pay == 't') $status_pay = "จ่ายแล้ว"; else $status_pay = "ยังไม่จ่าย";
    $status_approve = $res["status_approve"]; if($status_approve == 't') $status_approve = "อนุมัติแล้ว"; else $status_approve = "ยังไม่อนุมัติ";
    
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
        <td align="center"><?php echo "$model"; ?></td>
        <td align="center"><?php echo "$bill"; ?></td>
        <td align="center"><?php echo "$invoice"; ?></td>
        <td align="right"><?php echo number_format($cost,2); ?></td>
        <td align="right"><?php echo number_format($vatofcost,2); ?></td>
        <td align="center"><?php echo "$status_pay"; ?></td>
        <td align="center"><?php echo "$status_approve"; ?></td>
        <td align="center">
<?php
if($invoice == '-'){
    echo '<a href="frm_gs_pay_invoice.php?id='.$id.'"><font color=#ff0000>ใส่ใบกำกับ</font></a>';
}elseif($invoice != '-' AND $bill == '-' AND $status_approve == 'อนุมัติแล้ว'){
    echo '<a href="frm_gs_pay_bill.php?id='.$id.'"><font color=#008000>ใส่ใบเสร็จ</font></a>';
}elseif($invoice != '-' AND $bill == '-' AND $status_approve == 'ยังไม่อนุมัติ'){
    echo '<font color=#E7C505>รออนุมัติ</font>';
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
        <td align="center" colspan="12"><br>- ไม่พบข้อมูล -<br><br></td>
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