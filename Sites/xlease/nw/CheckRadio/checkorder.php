<?php
include("../../config/config.php");

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
		<input type="hidden" name="check_print" value="order">
		<input type="submit" id="printL" value="พิมพ์">
	</form>
</div>

<?php
echo "ทะเบียนรถที่ไม่ตรงกัน จำนวน : ";
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#5E99CC">
      <td>เลขที่สัญญา</td>
      <td>เลขที่ใบเสร็จ</td>
      <td>จำนวนเงิน</td>
   </tr>
<?php
$t = 0;
$qry=pg_query("select \"tacReceiveTemp\".\"tacID\" , \"FOtherpay\".\"O_RECEIPT\" , \"tacReceiveTemp\".\"tacMoney\" , \"VCarregistemp\".\"C_REGIS\"
				from public.\"tacReceiveTemp\" , public.\"FOtherpay\" , public.\"Fp\" , public.\"VCarregistemp\"
				where \"tacReceiveTemp\".\"tacXlsRecID\" = \"FOtherpay\".\"O_RECEIPT\"
				and \"FOtherpay\".\"IDNO\" = \"Fp\".\"IDNO\"
				and \"Fp\".\"IDNO\" = \"VCarregistemp\".\"IDNO\" ");
$numone = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $tacID = $res["tacID"];
    $O_RECEIPT = $res["O_RECEIPT"];
    $tacMoney = $res["tacMoney"];
	$C_REGIS = trim($res["C_REGIS"]);
    
	/*$qry_check=pg_query("select \"tacReceiveTemp\".\"tacID\" , \"Taxiacc\".\"CusID\"
							from public.\"tacReceiveTemp\" , public.\"Taxiacc\"
							where \"tacReceiveTemp\".\"tacID\" = \"Taxiacc\".\"CusID\"
								and \"tacReceiveTemp\".\"tacID\" = '$tacID' ");
	$numrow = pg_num_rows($qry_check);
	while($res_check=pg_fetch_array($qry_check)){
		$CusID = $res_check["CusID"];*/
	
		$query_ONID=mssql_query("select * from TacCusDtl where CusID='$tacID'");
		$num_ONID=mssql_num_rows($query_ONID);
		
		if($num_ONID != 0)
		{
			while($res_test=mssql_fetch_array($query_ONID))
			{
				$CarRegis_check = trim(iconv('WINDOWS-874','UTF-8',$res_test["CarRegis"]));
			}
	
			if($C_REGIS != $CarRegis_check) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ
			{
				$t++;
				$irow+=1;
				if($irow%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
?>
				<td align="center"><?php echo "$tacID"; ?></td>
				<td align="center"><?php echo "$O_RECEIPT"; ?></td>
				<td align="right"><?php echo number_format($tacMoney,2); ?></td>
				</tr>
<?php
			}
		}
	//}
}
//echo $t." / ".$numone;
echo "$t รายการ";
?>
</table>