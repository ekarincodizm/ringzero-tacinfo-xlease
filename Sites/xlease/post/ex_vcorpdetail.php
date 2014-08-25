<?php
include("../config/config.php");  

$idno = $_REQUEST["idno"];

$qry = pg_query("select * from \"UNContact\" WHERE \"IDNO\"='$idno'");
if($result = pg_fetch_array($qry)){
     $full_name = $result['full_name'];
     $C_CARNAME = $result['C_CARNAME'];
     $C_CARNUM = $result['C_CARNUM'];
     $C_REGIS = $result['C_REGIS'];
     $RadioID = $result['RadioID'];
     $C_COLOR = $result['C_COLOR'];
     $P_STDATE = $result['P_STDATE'];
     $CusID = $result['CusID'];
}else{
    echo "no record.";
    exit;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.odd{
    background-color:#F0F0F0;
    font-size:12px
}
.even{
    background-color:#E0E0E0;
    font-size:12px
}
.red{
    background-color:#FFD9EC;
    font-size:12px
}
</style>

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}
</script>

</head>

<body>

<div style="float:left"><span style="font-size: 22px; color: #5E99CC; font-weight: bold; padding: 0px; margin: 0px;">ลูกค้าเข้าร่วม</span></div>
<div style="float:right">
<input type="button" name="btn1" id="btn1" value="รายละเอียดเช็ค" onclick="javascript:popU('frm_detailcheque.php?idno=<?php echo "$idno"; ?>','<?php echo "111s7e4s7d_$idno"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
<input type="button" name="btn1" id="btn1" value="รายการชำระค่าอื่นๆ" onclick="javascript:popU('frm_otherpay.php?idno=<?php echo "$idno"; ?>','<?php echo "111s7e4s7d_$idno"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
<input type="button" name="btn2" id="btn2" value="ประกันภัย (พรบ)" onclick="javascript:popU('frm_force_show.php?idno=<?php echo "$idno"; ?>','<?php echo "222s7e4s7d_$idno"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
<input type="button" name="btn3" id="btn3" value="ประกันภัยสมัครใจ" onclick="javascript:popU('frm_unforce_show.php?idno=<?php echo "$idno"; ?>','<?php echo "333s7e4s7d_$idno"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
<input type="button" name="btn4" id="btn4" value="บันทึกการติดตาม" onclick="javascript:popU('follow_up_cus.php?idno=<?php echo "$idno"; ?>&scusid=<?php echo "$CusID"; ?>','<?php echo "444s7e4s7d_$idno"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=600')">
</div>
<div style="clear:both"></div>

<div>
<table cellpadding="3" cellspacing="1" width="100%" border="0" bgcolor="#E0E0E0">
<tr bgcolor="#5E99CC">
    <td width="40%"><b>เลขที่สัญญา :</b><?php echo $idno; ?></td>
    <td><b>ชื่อ/สกุล :</b><?php echo $full_name; ?></td>
</tr>
<tr bgcolor="#5E99CC">
    <td colspan="2">
    
<b><u>รายละเอียดรถ</u></b>
<table cellpadding="3" cellspacing="0" width="100%" border="0">
<tr>
    <td width="40%">วันทำสัญญา : <?php echo $P_STDATE; ?></td>
    <td>ทะเบียน : <?php echo $C_REGIS; ?></td>
</tr>
<tr>
    <td>เลขตัวถัง : <?php echo $C_CARNUM; ?></td>
    <td>RadioID : <?php echo $RadioID; ?></td>
</tr>
<tr>
    <td>ประเภทรถ : <?php echo $C_CARNAME; ?></td>
    <td>สีรถ : <?php echo $C_COLOR; ?></td>
</tr>
</table>

    </td>
</tr>
</table>

</div>

<div>
<table cellpadding="3" cellspacing="1" width="100%" border="0" bgcolor="#E0E0E0">
<tr bgcolor="#C0C0C0" style="font-weight:bold" align="center">
    <td>inv_no</td>
    <td>DueDate</td>
    <td>amt</td>
    <td>วันที่ชำระ</td>
    <td>เลขที่ใบเสร็จ</td>
    <td>สถานะ</td>
</tr>
<?php
$qry_vcus=pg_query("select * from corporate.\"VCorpDetail\" WHERE \"IDNO\"='$idno' ORDER BY \"inv_no\" ASC");
while($resvc=pg_fetch_array($qry_vcus)){

    $i_row+=1;
    if($i_row%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>     
    <td><?php echo $resvc["inv_no"]; ?></td>
    <td><?php echo $resvc["DueDate"]; ?></td>
    <td align="right"><?php echo number_format($resvc["amt"],2); ?></td>
    <td><?php echo $resvc["O_DATE"]; ?></td>
    <td><?php echo $resvc["O_RECEIPT"]; ?></td>
    <td><?php echo $resvc["O_Type"]; ?></td>
</tr>
<?php
}

if($i_row < 1){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>
</div>

</body>
</html>