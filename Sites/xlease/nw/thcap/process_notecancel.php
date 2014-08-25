<?php
include("../../config/config.php");
pg_query("BEGIN WORK");
$cancelID=$_POST['cancelID'];
$resultcancel=$_POST['resultcancel'];
$status=0;
//ให้อัพเดทตาราง thcap_temp_receipt_cancel ให้มีหมายเหตุ 
		$up="update thcap_temp_receipt_cancel set result ='$resultcancel'
		where \"cancelID\"='$cancelID' ";
		
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
$script= '<script language=javascript>';
if($status==0){
	pg_query("COMMIT");
	$script.= " alert('บันทึกรายการเรียบร้อย');";
    $script.= 'window.opener.location.reload();
			   window.close();';
}
else{
	pg_query("ROLLBACK");
	$script.= " alert('ผิดพลาดไม่สามารถบันทึกข้อมูลสำเร็จ');";
}
$script.= '</script>';
echo $script;
?>