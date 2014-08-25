<?php
include("../../config/config.php");
include("MoneyFunction.php");
set_time_limit(5400);
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
		<input type="hidden" name="check_print" value="map">
		<input type="submit" id="printL" value="พิมพ์">
	</form>
</div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#5E99CC">
      <td>เลขที่สัญญา</td>
      <td>ดอกเบี้ยคงเหลือที่ยังไม่จ่าย</td>
   </tr>
<?php
$t = 0;
$qry=pg_query("select * from public.\"Fp\" where \"P_ACCLOSE\" = 'TRUE' order by \"IDNO\" ");
$numone = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $IDNO = $res["IDNO"];
    
	$money = SearchMoney("$IDNO"); // เข้า function เพื่อหาดอกเบี้ยคงเหลือที่ยังไม่จ่าย
	
	if($money > 0) // ถ้ามากกว่า 0 แสดงว่ายังมีเงินค้างชำระอยู่
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
      <td align="right"><?php echo number_format($money,2); ?></td>
   </tr>
<?php
	}
}
?>
</table>
<?php
echo "ดอกเบี้ยคงเหลือที่ยังไม่จ่าย : ";
echo "$t รายการ";
?>