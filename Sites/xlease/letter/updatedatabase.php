<?php
include("../config/config.php");
$start=pg_escape_string($_POST["start"]);

?>
<form method="post" action="updatedatabase.php">
<div style="text-align:center;"><b>Update ฐานข้อมูลในการส่งจดหมายให้ถูกต้อง</b></div><br>
<div style="text-align:center;"><input type="hidden" name="start" value="1"><input type="submit" value="เริ่ม"  style="width:150px;height:50px;"></div>
</form>
<?php
pg_query("BEGIN WORK");
$status = 0;

if($start=="1"){
	$qry=pg_query("SELECT a.\"IDNO\", a.address_id FROM letter.\"SendDetail\" a
	inner join (select max(\"address_id\") as address,\"IDNO\" from letter.\"SendDetail\" group by \"IDNO\") b on a.address_id=b.address
	where a.\"IDNO\" <> '' group by a.\"IDNO\", address_id order by \"IDNO\"");
	
	while($res=pg_fetch_array($qry)){
		list($IDNO,$address_id)=$res;
		
		//update address_id ให้เป็น true ให้หมด
		$up="UPDATE letter.cus_address SET \"Active\"='TRUE' WHERE address_id='$address_id';";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}
	if($status == 0){
		pg_query("COMMIT");
		echo "<center>อัพเดทข้อมูลเรียบร้อยแล้ว</center>";
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถอัพเดทข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
	}


}
?>
