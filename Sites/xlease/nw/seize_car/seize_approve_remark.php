<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$IDNO= $_GET['idno'];
$NTID= $_GET['ntid'];
$seizeID = $_GET["seizeID"];
$show= $_GET['show'];//ถ้า เท่ากับ 1= จะแสดง ปุ่ม อนุมัต/ไม่อนุมัติ

if($seizeID != "" AND $IDNO == "" AND $NTID == ""){
	$qry_fr=pg_query("select * from \"nw_seize_car\" WHERE \"seizeID\"='$seizeID'");
}else{
	$qry_fr=pg_query("select * from \"nw_seize_car\" WHERE \"NTID\"='$NTID' and \"IDNO\" = '$IDNO' and \"status_approve\" = '1'");
}	

if($res_fr=pg_fetch_array($qry_fr)){
    $IDNO = $res_fr["IDNO"];
    $remark = $res_fr["seize_result"];
	$yellow_date = $res_fr["yellow_date"];
	$sizeID = $res_fr["seizeID"];//ใช้ กรณีที่กดปุ่มอนุมัติ/ไม่อนุมัติ
    
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $C_COLOR = $res_vc["C_COLOR"];
        $asset_type = $res_vc["asset_type"];
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }
    
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div style="background-color:#D0D0D0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:0px; text-align:left">
<b>NTID</b> : <?php echo "$NTID"; ?> <b>IDNO</b> : <?php echo $IDNO; ?> <b>วันที่ได้รับใบเหลือง</b> : <?php echo $yellow_date; ?><br />
<b>ชื่อ/สกุล</b> : <?php echo $full_name; ?> <b>เลขทะเบียน</b> : <?php echo $show_regis; ?>
</div>

<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:left">
<b>เหตุผล</b> : <br /><?php echo nl2br($remark); ?>
</div>
<?php if($show=='1'){?>
<!--เพิ่มปุ่ม อนุมัติ/ไม่อนุมัติ-->
	<form name="my_seize" method="post" action="seize_approve_send.php">
		<input type="hidden" name="sizeID" id="sizeID" value="<?php echo $sizeID; ?>">
		<center><input name="seize_appv" type="submit" value="อนุมัติ" />
		<input name="seize_unappv" type="submit" value="ไม่อนุมัติ" /></center>
	</form>
<?php }?>
</body>
</html>