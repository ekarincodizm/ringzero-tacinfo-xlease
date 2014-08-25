<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$mm = $_GET['mm'];
$yy = $_GET['yy'];
$ty = $_GET['ty'];

$month_shot = array('1'=>'มกราคม', '2'=>'กุมภาพันธ์', '3'=>'มีนาคม', '4'=>'เมษายน', '5'=>'พฤษภาคม', '6'=>'มิถุนายน', '7'=>'กรกฏาคม', '8'=>'สิงหาคม' ,'9'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
?>

<table width="700" cellpadding="5" cellspacing="1" border="0" bgcolor="#D0D0D0">
<tr bgcolor="#6AB5FF" style="font-weight:bold" align="center">
    <td>acb_id</td>
    <td>acb_date</td>
    <td>acb_detail</td>
</tr>
<?php
$qry = pg_query("SELECT * FROM account.\"AccountBookHead\" WHERE \"type_acb\"='$ty' AND EXTRACT(MONTH FROM \"acb_date\")='$mm' AND EXTRACT(YEAR FROM \"acb_date\")='$yy' AND \"cancel\"='FALSE' ORDER BY \"acb_id\" ASC");
while($res=pg_fetch_array($qry)){
    $k++;
    $acb_id = $res['acb_id'];
    $acb_date = $res['acb_date'];
    $acb_detail = nl2br($res['acb_detail']);
    
?>
<tr style="font-size:11px" bgcolor="#ffffff" valign="top">
    <td><?php echo "$acb_id"; ?></td>
    <td align="center"><?php echo "$acb_date"; ?></td>
    <td><?php echo "$acb_detail"; ?></td>
</tr>
<?php
}

if($k==0){
    echo "<tr><td colspan=3 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>