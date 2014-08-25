<?php 
include("../config/config.php"); 
$payid = pg_escape_string($_POST['payid']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    
<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>
    
</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><b>รายการชำระให้บริษัทแก๊ส</b></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="">
<b>เลือก PayID</b> 
<SELECT NAME="payid" onchange="document.frm_fuc1.submit()";>
    <option value="">เลือก</option>
<?php
$qry_inf=pg_query("select * from gas.\"PayToGas\" WHERE \"Cancel\"='false' AND idauthority is not null ORDER BY \"payid\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $payids = $res_inf["payid"];
    if($_POST['payid'] == $payids){
?>
    <option value="<?php echo "$payids"; ?>" selected><?php echo "$payids"; ?></option>
<?php
    }else{
?>
    <option value="<?php echo "$payids"; ?>"><?php echo "$payids"; ?></option>
<?php
    }
}
?>
</SELECT>
</form>
</div>

<form name="frmMain">
<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
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
if( isset($payid) ){
    $qry_if=pg_query("select * from gas.\"PoGas\" WHERE \"payid\"='$payid' ORDER BY \"poid\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res=pg_fetch_array($qry_if)){
        $id = $res["poid"];
        $idno = $res["idno"];
        $date = $res["podate"];
        $date_install = $res["date_install"];
        $idcompany = $res["idcompany"];
        $idmodel = $res["idmodel"];
        $costofgas = $res["costofgas"];
        $vatofcost = $res["vatofcost"];
        $bill = $res["bill"];
        $invoice = $res["invoice"];
        $status_pay = $res["status_pay"];
        
        $costofgas = round($costofgas, 2);
        $vatofcost = round($vatofcost, 2);
        
        $s_costofgas += $costofgas;
        $s_vatofcost += $vatofcost;
        $s_all += $costofgas+$vatofcost;
        
        $qry_name=pg_query("SELECT modelname FROM gas.\"Model\" WHERE \"modelid\" = '$idmodel' ");
        if($res_name=pg_fetch_array($qry_name)){
            $modelname = $res_name["modelname"];
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><a href="#" onclick="javascript:popU('frm_gs_detail_view.php?id=<?php echo "$id"; ?>','<?php echo "frm_gs_detail_view".$id;?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=200')"><u><?php echo "$id"; ?></u></a></td>
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
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="center" colspan=20>ไม่พบข้อมูล</td>
    </tr>
<?php
    }
    $qry_rm=pg_query("select * from gas.\"PayToGas\" WHERE \"payid\"='$payid'");
    if($res_name=pg_fetch_array($qry_rm)){
        $Remark = $res_name["Remark"];
        $cash = $res_name["cash"];    
        $CQBank = $res_name["CQBank"];    
        $CQID = $res_name["CQID"];    
        $CQDate = $res_name["CQDate"];
        $CQAmt = $res_name["CQAmt"];    
    }    
}
?>


<?php
    if($rows > 0){
?>


    <tr bgcolor="#ffffff">
        <td colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td colspan="6" align="right"><b>รวมเงินที่ต้องชำระ</b></td>
        <td align="right"><b><?php echo number_format($s_costofgas,2); ?></b></td>
        <td align="right"><b><?php echo number_format($s_vatofcost,2); ?></b></td>
        <td align="right"><b><?php echo number_format($s_all,2); ?></b></td>
    </tr>
    <tr align="left" bgcolor="#ffffff">
        <td colspan=20><br><b>หมายเหตุ</b><br><textarea name="add_remark" id="add_remark" rows="5" cols="90" style="font-size:11px;"><?php echo $Remark; ?></textarea></td>
    </tr>
</table>
   </form>
   
<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#ffffff">
<tr>
    <td><u><b>รายละเอียด</b></u></td>
</tr>
<tr>
    <td>
<?php if( empty($CQBank) ){ ?>
    <b>รูปแบบ :</b> เงินสด<br>
    <b>ยอดเงิน :</b> <?php echo number_format("$cash",2); ?> บาท.
<?php }else{ ?>
    <b>รูปแบบ :</b> เช็ค<br>
    <b>ธนาคาร :</b> <?php echo "$CQBank"; ?> | 
    <b>เลขที่เช็ค :</b> <?php echo "$CQID"; ?><br>
    <b>วันที่ออกเช็ค :</b> <?php echo "$CQDate"; ?> | 
    <b>จำนวนเงิน :</b> <?php echo number_format("$CQAmt",2); ?><br>
<?php } ?>
    </td>
</tr>
</table>

<?php
        echo "<div align=\"right\"><br><a href=\"frm_pays_show_print.php?payid=$payid\" target=\"_blank\"><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"\"> <b>สั่งพิมพ์</b></a></div>";
    }
?>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>