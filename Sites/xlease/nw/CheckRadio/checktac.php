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
	<form action="pdf_tac.php" method="post" name="form1" target="_blank">
		<input type="hidden" name="check_print" value="tac">
		<input type="submit" id="printL" value="พิมพ์">
	</form>
</div>

<?php
echo "ค้างชำระตกหล่น จำนวน : ";
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#5E99CC">
      <td>เลขที่สัญญา</td>
   </tr>
<?php
$t = 0;
$irow = 0;
$qry=pg_query("select distinct \"tacReceiveTemp\".\"tacID\" from public.\"tacReceiveTemp\" order by \"tacID\" ");
$numone = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $tacID = $res["tacID"];
	
		$query_ONID=mssql_query("select * from TacInvoice where CusID='$tacID' and RecNo='' ");
		$num_ONID=mssql_num_rows($query_ONID);
		
		if($num_ONID > 0)
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
				</tr>
<?php
		}
	//}
}
//echo $t." / ".$numone;
echo "$t รายการ";
?>
</table>