<?php
include("../../config/config.php");
$voucherPurpose = pg_escape_string($_POST['voucherPurpose']);
echo "<option value=\"\">-- กรุณาเลือกช่องทาง--</option>";
$qr =  pg_query("select \"fromChannel\" from \"thcap_temp_voucher_purpose_channel\" where \"voucherPurpose\"='$voucherPurpose'");
if($qr)
{
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		$rs = pg_fetch_array($qr);
		$bankID = $rs['fromChannel'];
		$bankID = str_replace("{", "",$bankID);
		$bankID = str_replace("}", "",$bankID);		
		$arr_tb_bankID = explode(",",$bankID);		
		$nbankID=count($arr_tb_bankID);		
		$ncount=0;
		
		while($ncount < $nbankID)
		{	
			$qry_GenType = pg_query("select * from \"BankInt\" where \"BID\"= '$arr_tb_bankID[$ncount]' order by \"BID\"");
			$res_gentype=pg_fetch_array($qry_GenType);
			$fromChannelID = $res_gentype["BID"];
			$fromChannelname = $res_gentype["BName"];
			$fromChannelaccount = $res_gentype["BAccount"];	
			$ncount++;
			?>
			<option value="<?php echo "$fromChannelID".'#'."$fromChannelname-$fromChannelaccount";  ?>">
							<?php echo "$fromChannelID".'#'."$fromChannelname-$fromChannelaccount"; ?></option>
			
			<?php
			
			
		} 
			
	}
}

?>