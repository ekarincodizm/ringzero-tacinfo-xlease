<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$datepicker = pg_escape_string($_GET['datepicker']);
$yy = pg_escape_string($_GET['yy']);
$mm = pg_escape_string($_GET['mm']);
$ty = pg_escape_string($_GET['ty']);
list($n_year,$n_month,$n_day) = split('-',$datepicker);

if($ty == 1){ // ประจำวัน
    $search_str = substr($n_year,2,2)."R".$n_month.$n_day;
}else{ // ประจำเดือน
    $search_str = substr($yy,2,2)."R".$mm;
}
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#C0C0C0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">เลขที่ใบเสร็จ</td>
      <td align="center">วันที่</td>
      <td align="center">จำนวนเงิน</td>
      <td align="center">สถานะ</td>
      <td align="center">ช่องทาง</td>
   </tr>

<?php
$nub = 0;
$qry_in=pg_query("SELECT \"R_Receipt\", \"R_Date\", \"R_Money\", \"R_Bank\", \"PayType\", \"Cancel\"
				FROM \"Fr\" where \"R_Receipt\" LIKE '$search_str%' ORDER BY \"R_Receipt\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $nub++;
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"]; 
    $R_Money = $res_in["R_Money"];
    $R_Bank = $res_in["R_Bank"];
    $PayType = $res_in["PayType"];
    $Cancel = $res_in["Cancel"];

    if($Cancel == "t"){
        echo "<tr class=\"red\">";
    }else{
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
    }
?>
      <td align="center"><?php echo $R_Receipt; ?></td>
      <td align="center"><?php echo $R_Date; ?></td>
      <td align="right"><?php echo number_format($R_Money,2); ?></td>
      <td align="center"><?php echo $R_Bank; ?></td>
      <td align="center"><?php echo $PayType; ?></td>
   </tr>
<?php
}
?>
<tr>
    <td colspan="4"><b>ทั้งหมด <?php echo $nub; ?> รายการ</b></td>
    <td align="right"><input type="button" name="btnprint" id="btnprint" value="พิมพ์ทั้งหมด" onClick="javascript:window.open('recript_set_h_pdf_<?php echo $_SESSION['session_company_code']; ?>.php?datepicker=<?php echo "$datepicker"; ?>&mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy"; ?>&ty=<?php echo "$ty"; ?>' , 'dd14f5f4w1w4s1a5a4sd1e4e','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768')"; ></td>
</tr>
</table>