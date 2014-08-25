<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
if( empty($_POST['select_date']) ){
    $cdate=date("Y-m-d");
}else{ 
    $cdate = pg_escape_string($_POST['select_date']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>
<fieldset><legend><B>อนุมัติยกเลิกใบเสร็จ</B></legend>

<div align="center">
<form name="frm_app_cc" method="post" action="">
<b>เลือกวันที่</b>
<input name="select_date" type="text" readonly="true" value="<?php echo $cdate; ?>"/>
<input name="button2" type="button" onclick="displayCalendar(document.frm_app_cc.select_date,'yyyy-mm-dd',this)" value="ปฏิทิน" /><input type="submit" value="ค้นหา" />
</form>
</div>

<div style="font-weight:bold;">รายการขอยกเลิกใบเสร็จ วันที่ <?php echo $cdate; ?></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">no.</td>
      <td align="center">รหัสยกเลิกใบเสร็จ</td>
      <td align="center">จำนวนเงิน</td>
      <td align="center">เหตุผล</td>
      <td align="center">สถานะ</td>
      <td align="center">อนุมัติ</td>
   </tr>

<?php
$vat = 0;
$qry_cc=pg_query("select * from \"CancelReceipt\" WHERE c_date='$cdate' ORDER BY c_receipt ASC");
$numrow_cc=pg_num_rows($qry_cc);
while($res_cc=pg_fetch_array($qry_cc)){
    
    $SIDNO = $res_cc['IDNO'];
    
    /*
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$SIDNO' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        $vat = $res_cc1['VatValue'];
    }*/
    
    $n++;
	if($res_cc["admin_approve"]=='t'){
	    $sta="อนุมัติยกเลิกใบเสร็จแล้ว";
        $btn = "-";
	}else{
	    $sta="รอการอนุมัติ";
        $btn = "<a href=\"approve_cancel_recprocess.php?cid=$res_cc[c_receipt]&rid=$res_cc[ref_receipt]&memo=$res_cc[c_memo]\" title=\"อนุมัติรายการนี้ $res_cc[ref_receipt]\"><u>อนุมัติ</u></a>";
    }
    
    if($res_cc["admin_approve"]=='t'){
        echo "<tr class=\"ered\">";
    }else{
    
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
    
    }
?>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $res_cc["c_receipt"]; ?></td>
        <td align="right"><?php echo number_format($res_cc["c_money"],2); ?></td>
        <td align="left"><?php echo $res_cc["c_memo"]; ?></td>
        <td align="left"><?php echo $sta; ?></td>
        <td align="center"><?php echo $btn; ?></td>
    </tr>
<?php
}
?>
</table>

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>