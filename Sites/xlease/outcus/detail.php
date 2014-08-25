<?php
include("../config/config.php");

$idno = $_GET['idno'];

function showtype($id){
    if($id == 0) $name = "ลูกค้านอก";
    if($id == 1) $name = "เช่าซื้อรถ";
    if($id == 2) $name = "เช่าซื้อถังแก๊ส";
    if($id == 3) $name = "ลูกค้าเข้าร่วม";
    return $name;
}


$qry_name=pg_query("select * from \"UNContact\" WHERE \"IDNO\" = '$idno'");
if($res_name=pg_fetch_array($qry_name)){
    $CusID=$res_name["CusID"];
    $C_REGIS=trim($res_name["C_REGIS"]);
    $C_CARNUM=trim($res_name["C_CARNUM"]);
    $C_COLOR=trim($res_name["C_COLOR"]);
    $C_YEAR=$res_name["C_YEAR"];
    $C_TAX_ExpDate=$res_name["C_TAX_ExpDate"];
    $asset_id=$res_name["asset_id"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value=" Back " class="ui-button" onclick="window.location='select.php';"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>รายละเอียด</B></legend>

<div class="ui-widget">

<div>
<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#E0E0E0">
    <td><b>เลขที่สัญญา</b></td>
    <td colspan="3"><?php echo $idno; ?></td>
</tr>
<tr bgcolor="#E0E0E0">
    <td width="25%"><b>ทะเบียนรถ</b></td>
    <td width="25%"><?php echo $C_REGIS; ?></td>
    <td width="25%"><b>เลขตัวถัง</b></td>
    <td width="25%"><?php echo $C_CARNUM; ?></td>
</tr>
<tr bgcolor="#E0E0E0">
    <td><b>สี</b></td>
    <td><?php echo $C_COLOR; ?></td>
    <td><b>ปีรถ</b></td>
    <td><?php echo $C_YEAR; ?></td>
</tr>
<tr bgcolor="#E0E0E0">
    <td><b>วันต่อภาษี</b></td>
    <td colspan="3"><?php echo $C_TAX_ExpDate; ?></td>
</tr>
</table>
</div>

<div style="margin-top: 10px">

<table cellpadding="3" cellspacing="1" width="100%" border="0" bgcolor="#E0E0E0">
<tr style="font-weight:bold; background-color:#8CC6FF" align="center">
    <td>วันทำสัญญา</td>
    <td>IDNO</td>
    <td>ชื่อ/สกุล</td>
    <td>รูปแบบ</td>
    <td>วันที่งวดแรก</td>
</tr>
<?php
$qry_name=pg_query("select * from \"UNContact\" WHERE \"C_CARNUM\" = '$C_CARNUM' ORDER BY \"P_STDATE\" ASC");
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $asset_type=$res_name["asset_type"];
    $P_STDATE=$res_name["P_STDATE"];
    $P_FDATE=$res_name["P_FDATE"];

     if(empty($P_STDATE)) $P_STDATE = "ไม่พบข้อมูล";
     if(empty($P_FDATE)) $P_FDATE = "ไม่พบข้อมูล";
?>

<tr bgcolor="#FFFFFF">
    <td><?php echo "$P_STDATE"; ?></td>
    <td>
    <?php 
    if($asset_type == 1 OR $asset_type == 2){
    ?>
<span title="ตารางการชำระเงิน" onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&menu=outcus','<?php echo "$IDNO_dad4d4as4da5sd4asd4asd"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;"><u><?php echo $IDNO; ?></u></span>
    <?php
    }elseif($asset_type == 0){
    ?>
<span title="ลูกค้านอก" onclick="javascript:popU('../post/ex_outcus.php?idno=<?php echo $IDNO; ?>','<?php echo "$IDNO_dad4d4as4da5sd4asd4asd"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;"><u><?php echo $IDNO; ?></u></span>
    <?php
    }elseif($asset_type == 3){
    ?>
<span title="ลูกค้าเข้าร่วม" onclick="javascript:popU('../post/ex_vcorpdetail.php?idno=<?php echo $IDNO; ?>','<?php echo "$IDNO_dad4d4as4da5sd4asd4asd"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;"><u><?php echo $IDNO; ?></u></span>
    <?php
    }
    ?>
    </td>
    <td><?php echo "$full_name"; ?></td>
    <td><?php echo showtype($asset_type); ?></td>
    <td><?php echo "$P_FDATE"; ?></td>
</tr>

<?php
}
?>
</table>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>