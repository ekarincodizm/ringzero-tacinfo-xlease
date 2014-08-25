<?php 
include("../config/config.php");
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script type="text/javascript">
$(document).ready(function(){
    $("#com").change(function(){
        $("#divdate").html('<img src="../images/progress.gif" border="0" width="16" height="16" alt="กำลังโหลด...">');
        $("#divdate").load('frm_pay_unforce_process.php?type=getdate&com='+ $("#com").val());
    });
    $("#btnshow").click(function(){
        $("#divshow").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#divshow").load('frm_pay_unforce_show.php?com='+ $("#com").val() +'&date='+ $("#date").val());
    });
});
</script>
        
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>

<fieldset><legend><b>ประกันภัยภาคสมัครใจ</b></legend>

<div style="margin-top:5px">
<b>เลือกบริษัท</b> 
<SELECT NAME="com" id="com">
    <option value="">เลือก</option>
<?php
$qry=pg_query("SELECT \"Company\" FROM insure.\"VListUnForcePayBy\" GROUP BY \"Company\" ORDER BY \"Company\" ASC ");
while($res=pg_fetch_array($qry)){
    $Company = $res["Company"];
?>
    <option value="<?php echo "$Company"; ?>" ><?php echo "$Company"; ?></option>        
<?php        
}
?>
</SELECT>
<span id="divdate">
<b>เลือกวันที่</b> 
<SELECT NAME="datesample" id="datesample">
    <option value="">เลือก</option>
</SELECT>
</span>
<input type="button" name="btnshow" id="btnshow" value="แสดง">
</div>

<div id="divshow" style="margin-top:5px"></div>

<?php
    if($rows > 0){
        /*
?>
<table width="100%" border="0">
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="left" width="50%"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" width="50%"><b>รวมเงินที่ต้องชำระ</b> <?php echo number_format($summary,2); ?></td>
    </tr>
    <tr align="left">
        <td colspan=2><br><b>หมายเหตุ</b><br><textarea name="remark" rows="5" cols="90" style="font-size:11px;"><?php echo $Remark; ?></textarea></td>
    </tr>
</table>

<?php
//echo "<div align=\"right\"><br><a href=\"frm_pay_force_print.php?payid=$select_payid\" target=\"_blank\"><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"\"> <b>สั่งพิมพ์</b></a></div>";
*/
    }
?>

        </td>
    </tr>
</table>

</body>
</html>