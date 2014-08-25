<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script>
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$receiptTempID = pg_escape_string($_POST["TempID"]);
$noteAppv = pg_escape_string($_POST["noteAppv"]);

$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime();

pg_query("BEGIN");
$status = 0;

// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_chk = pg_query("select * from \"blo_receipt_temp\" where \"receiptTempID\" = '$receiptTempID' and \"appvStatus\" = '9'");
$count_chk = pg_num_rows($qry_chk);
if($count_chk == 0)
{
	echo "<center><h1><font color=\"#FF0000\">บันทึกผิดพลาด!! มีการทำรายการไปก่อนหน้านี้แล้ว</font></h1></center>";
	?>
	<center><input type="button" value="ตกลง" onClick="RefreshMe();"></center>
	<?php
}
else
{
	if(isset($_POST["appv"]))
	{ //อนุมัติ
		$qry_blo = pg_query("select * , \"receiptStamp\"::date as \"receiptDate\" , ta_array1d_count(\"costsID\"::character varying[]) as \"count_costsID\"
							from \"blo_receipt_temp\" where \"receiptTempID\" = '$receiptTempID' ");
		while($res_blo = pg_fetch_array($qry_blo))
		{
			$receiptTempID = $res_blo["receiptTempID"];
			$receiptDate = $res_blo["receiptDate"];
			$contractID = $res_blo["contractID"];
			$CusID = $res_blo["CusID"];
			$doerID = $res_blo["doerID"];
			$doerStamp = $res_blo["doerStamp"];
			$CusFullAddress = $res_blo["CusFullAddress"];
			$costsID = $res_blo["costsID"];
			$netAmt = $res_blo["netAmt"];
			$vatAmt = $res_blo["vatAmt"];
			$costsAmt = $res_blo["costsAmt"];
			$whtAmt = $res_blo["whtAmt"];
			$count_costsID = $res_blo["count_costsID"];
			
			// หาชื่อเต็มลูกค้า
			$qry_cus = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$CusID' ");
			$CusName = pg_result($qry_cus,0);
			
			// หาชื่อเต็มพนักงานที่ทำรายการ
			$qry_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
			$doerName = pg_result($qry_doer,0);
		}
		
		$qry_ins = "insert into \"blo_receipt\"(\"receiptID\", \"receiptTempID\", \"receiptStamp\", \"contractID\", \"costsID\", \"netAmt\",
						\"vatAmt\", \"costsAmt\", \"whtAmt\", \"CusID\", \"CusFullName\", \"CusFullAddress\", \"doerID\", \"doerFullName\")
					select \"blo_gen_documentID\"('$contractID'), \"receiptTempID\", \"receiptStamp\", \"contractID\", \"costsID\", \"netAmt\",
						\"vatAmt\", \"costsAmt\", \"whtAmt\", \"CusID\", '$CusName', \"CusFullAddress\", \"doerID\", '$doerName'
						from \"blo_receipt_temp\" where \"receiptTempID\" = '$receiptTempID' and \"appvStatus\" = '9' ";
		if($result = pg_query($qry_ins)){
		}
		else{
			$status++;
			echo $qry_ins;
		}
		
		$qry_appv = "update \"blo_receipt_temp\"
						set \"appvStatus\" = '1', \"appvRemark\" = '$noteAppv', \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time'
						where \"receiptTempID\" = '$receiptTempID' and \"appvStatus\" = '9'";
		if($result = pg_query($qry_appv)){
		}
		else{
			$status++;
		}
	}
	elseif(isset($_POST["unappv"]))
	{ //ไม่อนุมัติ
		$qry_unappv = "update \"blo_receipt_temp\"
						set \"appvStatus\" = '0', \"appvRemark\" = '$noteAppv', \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time'
						where \"receiptTempID\" = '$receiptTempID' and \"appvStatus\" = '9'";
		if($result = pg_query($qry_unappv)){
		}
		else{
			$status++;
		}
	}
	
	if($status == 0)
	{
		//ACTIONLOG
			$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(BLO) อนุมัติรับชำระเงิน', '$logs_any_time')";
			if($result = pg_query($sqlaction)){}else{$status++;}
		//ACTIONLOG---
		
		pg_query("COMMIT");
		
		echo "<center><h1><font color=\"#0000FF\">บันทึกสำเร็จ</font></h1></center>";
		?>
		<center><input type="button" value="ตกลง" onClick="RefreshMe();"></center>
		<?php
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h1><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h1></center>";
		?>
		<center><input type="button" value="ตกลง" onClick="RefreshMe();"></center>
		<?php
	}
}
?>