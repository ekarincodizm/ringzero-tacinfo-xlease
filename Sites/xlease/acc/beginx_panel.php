<?php
include("../config/config.php");

$mm = pg_escape_string($_GET['mm']);
$yy = pg_escape_string($_GET['yy']);

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
?>

<style type="text/css">
.odd{
    background-color:#FFFFFF;
    font-size:12px
}
.even{
    background-color:#F0F0F0;
    font-size:12px
}
</style>

<div><b>แสดงข้อมูลของเดือน <?php echo $month[$mm]; ?> ปี <?php echo $yy+543; ?></b></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#5E99CC">
      <td>IDNO</td>
      <td>ชื่อ</td>
      <td>วันทำสัญญา</td>
      <td>ชื่อรถ</td>
      <td>ทะเบียน</td>
      <td>ต้นทุนทางบัญชี</td>
      <td>#</td>
   </tr>
<?php
$qry=pg_query("SELECT * FROM \"VContact\" WHERE \"P_ACCLOSE\"='false' AND EXTRACT(MONTH FROM \"P_STDATE\")='$mm' AND EXTRACT(YEAR FROM \"P_STDATE\")='$yy' ORDER BY \"IDNO\" ASC ");
while($res=pg_fetch_array($qry)){
    $IDNO = $res["IDNO"];
    $full_name = $res["full_name"];
    $P_STDATE = $res["P_STDATE"];
    $C_CARNAME = $res["C_CARNAME"];
    $C_REGIS = $res["C_REGIS"];
    $P_BEGINX = $res["P_BEGINX"];
    
    $irow+=1;
    if($irow%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
      <td><?php echo "$IDNO"; ?></td>
      <td><?php echo "$full_name"; ?></td>
      <td><?php echo "$P_STDATE"; ?></td>
      <td><?php echo "$C_CARNAME"; ?></td>
      <td><?php echo "$C_REGIS"; ?></td>
      <td align="right"><?php echo number_format($P_BEGINX,2); ?></td>
      <td align="center"><input type="button" name="btnedit" id="btnedit" value="แก้ไข" onclick="javascript:showedit('<?php echo "$IDNO"; ?>','<?php echo $P_BEGINX; ?>')"></td>
   </tr>
<?php
}
?>
</table>