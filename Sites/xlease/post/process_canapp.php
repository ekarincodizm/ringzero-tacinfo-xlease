<?php
include("../config/config.php");
include("../nw/function/checknull.php");
?>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
	window.opener.document.forms[0].ref.click();	
	self.close();
}
</script> 
<?php
$NTID = $_POST["NTID"]; //รหัส NT
$idno = $_POST["idno"]; //เลขที่สัญญา	
$note = checknull($_POST["note"]); //เหตุผลที่ไม่อนุมัติ
$user_id = $_SESSION["av_iduser"];//ผู้ทำรายการขณะนั้น
$status = 0;

pg_query("BEGIN");


	$qry=pg_query("select * from \"NTHead\" where \"remark\"is null and \"cancelid\" is null and \"cancel_date\" is null and \"IDNO\"='$idno' and cancel='FALSE'");
	$num_qry=pg_num_rows($qry);
	
	if($num_qry > 0){
		echo "<center><div style=\"padding:10px\"><h2>รายการนี้ไม่ได้รับการอนุมัติก่อนหน้านี้แล้ว</h2></div><p>";
		echo "<input type=\"button\" value=\" ปิด \" onclick=\"window.close();\"></center>";
		exit();
	}else{
	//echo "select * from \"NTHead\" where cancel='FALSE' and \"IDNO\"='$idno'";
		//ต้องตรวจสอบก่อนว่าได้รับการอนุมัติหรือยัง
		$qry_app=pg_query("select * from \"NTHead\" where cancel='FALSE' and \"IDNO\"='$idno'");
		$num_app=pg_num_rows($qry_app);
		if($num_app ==0){ //แสดงว่ามีการอนุมัติก่อนหน้านี้แล้ว
			echo "<center><div style=\"padding:10px\"><h2>รายการนี้ได้รับการอนุมัติก่อนหน้านี้แล้ว1111</h2></div>";
			echo "<input type=\"button\" value=\" ปิด \" onclick=\"window.close();\"></center>";
			exit();
		}else{
			//ดึงเลขกลุ่มล่าสุดเพื่อใช้ในการระบุกลุ่มของ NT
				$qry_ingroup = pg_query("SELECT MAX(\"ntgroup\") as \"ntgnum\" FROM \"NTHead_log_notappvcancel\" ");
				list($numgroup) = pg_fetch_array($qry_ingroup);
				IF($numgroup == ""){
					$numgroup = '0';
				}else{
					$numgroup++;
				}
		
			//ดึงข้อมูลเก่าก่อนจะทำการเปลี่ยนแปลง
			$qry_notice=pg_query("select * from \"NTHead\" where \"IDNO\" = '$idno' and \"cancel\"='FALSE' ");		
			while($res_cancel2=pg_fetch_array($qry_notice)){
				$NTID=$res_cancel2["NTID"];
				$remarkold=$res_cancel2["remark"];
				$cancelidold=$res_cancel2["cancelid"];
				$cancel_dateold=$res_cancel2["cancel_date"];
				
				//เพิ่มข้อมูลลงตาราง NTHead_log_notappvcancel
				$qry_in = pg_query("INSERT INTO \"NTHead_log_notappvcancel\"( \"NTID\", remark_old, cancelid_old, cancel_date, app_user, app_date,noteapp,ntgroup)
						VALUES ('$NTID', '$remarkold', '$cancelidold','$cancel_dateold','$user_id' , LOCALTIMESTAMP(0), $note,$numgroup)");
				IF($qry_in){}else{ $status++;}
					
				$qry_nwstatus=pg_query("select \"statusOld\" from \"nw_statusNT\" where \"NTID\"='$NTID'");
				list($statusOld)=pg_fetch_array($qry_nwstatus);
				$num_nw=pg_num_rows($qry_nwstatus);
				if($num_nw>0){
					$result2=pg_query("Update \"nw_statusNT\" SET \"statusNT\"='$statusOld' WHERE \"NTID\"='$NTID'");
					if(!$result2){
						$status++;
					}
				}
			}		
			
			$result2=pg_query("Update \"NTHead\" SET \"remark\"=null, \"cancelid\"=null, \"cancel_date\"=null WHERE \"IDNO\"='$idno' and cancel='FALSE'");
			if(!$result2){
				$status++;
			}
			
			if($status == 0){
				pg_query("COMMIT");
				echo "<center>บันทึกข้อมูลเรียบร้อยแล้ว<br /><br />";		
				echo "<input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\"></center>";
				
			}else{
				pg_query("ROLLBACK");
				echo "<center>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  ปิด  \" onclick=\"javascript:window.close();\"></center>";
			}
		}
	}
	
?>

