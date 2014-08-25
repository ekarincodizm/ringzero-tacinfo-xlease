<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
$ntid= $_GET['ntid'];
$show= $_GET['show'];

$qry_fr=pg_query("select \"IDNO\",\"remark\" from \"NTHead\" WHERE \"NTID\"='$ntid' ");
if($res_fr=pg_fetch_array($qry_fr)){
    $IDNO = $res_fr["IDNO"];
    $remark = $res_fr["remark"];
    
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
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function confirmappv(){
	if(confirm('ยืนยันการอนุมัติ')==true){return true;}
	else{return false;}	
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div style="background-color:#D0D0D0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:0px; text-align:left">
<b>NTID</b> : <?php echo "$ntid"; ?> <b>IDNO</b> : <?php echo $IDNO; ?><br />
<b>ชื่อ/สกุล</b> : <?php echo $full_name; ?> <b>เลขทะเบียน</b> : <?php echo $show_regis; ?>
</div>

<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:left">
<b>เหตุผล</b> : <br /><?php echo nl2br($remark); ?>
</div>
<?php if($show=='1'){
?>
	<center>
	<form name="post1" method="post" action="notice_approve_send.php">
		<input name="appv" type="submit" value="อนุมัติ" onclick="return confirmappv()"/>
		<input name="idno" type="hidden"  id="idno" value="<?php echo $IDNO; ?>">
		<input name="unappv" type="button" value="ไม่อนุมัติ" onclick="if(confirm('กรุณายืนยันการปฎิเสธการอนุมัติ ')==true){ 
		javascript:popU('frm_confirm_cancel.php?idno=<?php echo $IDNO; ?>&NTID=<?php echo $ntid; ?>','','width=630,height=360') }" />
		<input name="ref" id="ref" type="button"  onclick="RefreshMe()" hidden />
		<input name="close" type="button" value="ปิด" onclick="window.close()"/>
	</form>	</center>
<?php }?>
</body>
</html>