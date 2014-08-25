<?php
include("../../config/config.php");

$money = $_GET["money"];
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


<div align="right">
	<form action="frm_pdf.php" method="post" name="form1" target="_blank">
		<input type="hidden" name="check_print" value="map_money">
		<input type="hidden" name="money" value="<?php echo "$money"; ?>">
		<input type="submit" id="printL" value="พิมพ์">
	</form>
</div>

<?php
echo "ใบเสร็จที่มีไม่เท่ากัน และ ใบเสร็จที่มีจำนวนเงินต่างกันมากกว่า $money บาท จำนวน : ";
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#5E99CC">
      <td>เลขที่สัญญา</td>
      <td>เลขที่ใบเสร็จ</td>
      <td>จำนวนเงิน</td>
   </tr>
<?php
$t = 0;
$qry=pg_query("select * from public.\"FOtherpay\" where \"O_DATE\" >= '2012-01-01' and \"O_Type\" = '307' order by \"IDNO\" ");
$numone = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $IDNO = $res["IDNO"];
    $O_RECEIPT = $res["O_RECEIPT"];
    $O_MONEY = $res["O_MONEY"];
    
	$qry_check=pg_query("select * from public.\"tacReceiveTemp\" where \"tacXlsRecID\" = '$O_RECEIPT' ");
	$numrow = pg_num_rows($qry_check);
	if($numrow == 0) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ  ให้แสดงข้อมูลออกมา
	{
		$t++;
		$irow+=1;
		if($irow%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
?>
      <td align="center"><?php echo "$IDNO"; ?></td>
      <td align="center"><?php echo "$O_RECEIPT"; ?></td>
      <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
   </tr>
<?php
	}
	else // ถ้ามีข้อมูล ก็มาเช็คต่อว่า จำนวนเงินมีผลต่างมากกว่าที่เรากำหนดไว้หรือไม่    ถ้ามากกว่า ให้แสดงออกมาด้วย
	{
		while($res_money=pg_fetch_array($qry_check))
		{
			$tacMoney = $res_money["tacMoney"];
			if($O_MONEY - $tacMoney > $money || $tacMoney - $O_MONEY > $money)
			{
				$t++;
				$irow+=1;
				if($irow%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
?>
				<td align="center"><?php echo "$IDNO"; ?></td>
				<td align="center"><?php echo "$O_RECEIPT"; ?></td>
				<td align="right"><?php echo number_format($O_MONEY,2); ?></td>
				</tr>
<?php
			}
		}
	}
}
//echo $t." / ".$numone;
echo "$t รายการ";
?>
</table>