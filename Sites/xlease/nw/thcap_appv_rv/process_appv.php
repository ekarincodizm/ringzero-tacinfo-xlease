<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');

$user_id = $_SESSION["av_iduser"];
$prevoucherdetailsid = pg_escape_string($_POST['prevoucherdetailsid']);
$appv_remark = pg_escape_string($_POST['appv_remark']); 
$appv_status = pg_escape_string($_POST['appv_status']);
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

</script>
<?php 
$lostData = 0;
$status = 0;
	pg_query("Begin");
	
	if(!empty($prevoucherdetailsid) and !empty($user_id) and !empty($appv_remark)) {
		
		if($appv_status == "1"){
		
			$qry = "select \"thcap_process_voucherApprove\"($prevoucherdetailsid,'$user_id',2,'$appv_remark')";
			
			if($res_qry=pg_query($qry)){
				$voucherid = pg_fetch_result($res_qry,0);
			}else{
				$status++;
			}
			
		}else if($appv_status == "0"){
		
			$qry = "select \"thcap_process_voucherApprove\"($prevoucherdetailsid,'$user_id',0,'$appv_remark')";
		
			if(pg_query($qry)){
			}else{
				$status++;
			}
		}
	}else {
		$lostData++;
	}
	
	if($lostData>0){
		pg_query("ROLLBACK");
		echo "<br><b><center><font color=\"red\">ผิดผลาด ข้อมูลไม่สมบูรณ์!</font></b></center><br>";
		echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"window.close();\" /></center>";		
	}else{
		if($status>0){
			pg_query("ROLLBACK");
			echo "<br><b><center><font color=\"red\">ผิดผลาด ไม่สามารถบันทึกได้!</font></b></center><br>";
			echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"window.close();\" /></center>";	
		}else{
			//ACTIONLOG
				if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) อนุมัติใบสำคัญรับ', LOCALTIMESTAMP(0))")); else $status++;
			//ACTIONLOG---
			pg_query("COMMIT");
						
			if($appv_status == "1"){?>			
			<form name ="frm1" action="../thcap_receive_voucher/pdf_receive_voucher.php" method="post"  target="_blank">
			  <?php  echo "<input name=\"select_print[]\" id=\"select_print0\" value=\"$voucherid\" hidden>"; ?>
			  <input name="reprint"  value="appprint" hidden />
			  <input name="print" type="submit" value="พิมพ์" hidden />
			</form>
			<?php 
			echo "<script type=\"text/javascript\">";
			echo "document.forms['frm1'].print.click();";
			echo "javascript:popU('../thcap_appv/frm_tag.php?voucherID=$voucherid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=300');";
			echo "opener.location.reload(true); self.close();";
			echo "</script>";	
			}
			else{
				echo "<script type=\"text/javascript\">";
				echo "opener.location.reload(true);";
				echo "</script>";	
				echo "<br><b><center>บันทึกรายการเรียบร้อย </b></center><br>";
				echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"window.close();\" /></center>";	
			}
			
		}
	}

	
?>