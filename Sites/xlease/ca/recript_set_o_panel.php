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
    $search_str = substr($n_year,2,2)."N".$n_month.$n_day;
}else{ // ประจำเดือน
    $search_str = substr($yy,2,2)."N".$mm;
}
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#C0C0C0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">เลขที่ใบเสร็จ</td>
      <td align="center">วันที่</td>
      <td align="center">จำนวนเงิน</td>
      <td align="center">Type</td>
      <td align="center">สถานะ</td>
      <td align="center">ช่องทาง</td>
   </tr>

<?php
$nub = 0;
$qry_in=pg_query("SELECT \"O_RECEIPT\", \"O_DATE\", \"O_Type\", \"O_MONEY\", \"O_BANK\", \"PayType\", \"Cancel\"
				FROM \"FOtherpay\" where \"O_RECEIPT\" LIKE '$search_str%' ORDER BY \"O_RECEIPT\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $nub++;
    $O_RECEIPT = $res_in["O_RECEIPT"];
    $O_DATE = $res_in["O_DATE"]; 
    $O_Type = $res_in["O_Type"];
    $O_MONEY = $res_in["O_MONEY"];
    $O_BANK = $res_in["O_BANK"];
    $PayType = $res_in["PayType"];
    $Cancel = $res_in["Cancel"];
    
    $qry_in2=pg_query("SELECT \"TName\" FROM \"TypePay\" where \"TypeID\" = '$O_Type'");
    if($res_in2=pg_fetch_array($qry_in2)){
        $TName = $res_in2["TName"];
    }

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
      <td align="center"><?php echo $O_RECEIPT; ?></td>
      <td align="center"><?php echo $O_DATE; ?></td>
      <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
      <td align="left"><?php echo $TName; ?></td>
      <td align="left"><?php echo $O_BANK; ?></td>
      <td align="left"><?php echo $PayType; ?></td>
   </tr>
<?php
}
?>
<tr>
    <td colspan="5"><b>ทั้งหมด <?php echo $nub; ?> รายการ</b></td>
    <td align="right"><input type="button" name="btnprint" id="btnprint" value="พิมพ์ทั้งหมด" onClick="javascript:window.open('recript_set_o_pdf_<?php echo $_SESSION['session_company_code']; ?>.php?datepicker=<?php echo "$datepicker"; ?>&mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy"; ?>&ty=<?php echo "$ty"; ?>' , 'dd14f5f4w1w4s1a5a4sd1e4e','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768')"; ></td>
</tr>
</table>