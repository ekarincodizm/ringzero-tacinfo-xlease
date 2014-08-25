<?php
include("../../config/config.php");
$lastbank=$_GET["lastbank"];
$nocount=$_GET["nocount"];
?>
<select id=<?php echo $nocount;?> name=<?php echo $nocount;?>  onchange="chk_ChqNo_id(counter);">
	<?php
	$qry_fp=pg_query("select * from \"BankProfile\"");
	 while($res_fp=pg_fetch_array($qry_fp)){
		$bankID =$res_fp["bankID"];
		$bankName=$res_fp["bankName"];						
		echo "<option value=$bankID ";
		if($res_fp["bankID"]==$lastbank){ echo "selected=\"selected\""; }							
		echo "</option>$bankName";
	}
	?>
</select>
