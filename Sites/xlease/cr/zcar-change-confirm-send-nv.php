<?php
include("../config/config.php");
/*
$user = pg_escape_string($_POST['user']);
$pass = md5($_POST['pass']);
*/
$cmd = pg_escape_string($_REQUEST['cmd']);
$id = pg_escape_string($_REQUEST['id']);
$w = pg_escape_string($_REQUEST['w']);


/*
$cmd = pg_escape_string($_GET['cmd']);
$id = pg_escape_string($_GET['id']);
$w = pg_escape_string($_GET['w']);
*/
$dateTimeNow = date("Y-m-d H:i:s");
$resid=$_SESSION["av_iduser"];

$qry_ad=pg_query("select \"id_user\" from \"fuser\" WHERE \"id_user\"='$resid' AND \"emplevel\"<='10' ");
$numrow_ad = pg_num_rows($qry_ad);

    if($numrow_ad > 0){ //เมื่อ username เป็น  admin
		// หาข้อมูลเก่า เพื่ออ้างอิง ใน LogRegisChange
		 $qry_old=pg_query("select \"a\".\"BillNumber\",\"a\".\"TaxValue\",\"a\".\"TypePay\",\"c\".\"IDNO\" ,\"c\".\"asset_id\",\"a\".\"IDCarTax\",\"a\".\"IDDetail\",\"a\".\"CoPayDate\"  
		 from \"carregis\".\"DetailCarTax\" as a 
		 left join \"carregis\".\"CarTaxDue\" as b ON \"a\".\"IDCarTax\"=\"b\".\"IDCarTax\" 
		 left join \"public\".\"Fp\" as c ON \"b\".\"IDNO\"=\"c\".\"IDNO\" 
		 where \"a\".\"IDDetail\"='$id' ");		 
		//$numrow_ad2 = pg_num_rows($qry_old);	
		while($res_old=pg_fetch_array($qry_old)){
			$old_BillNumber = $res_old["BillNumber"];
			$old_TaxValue = $res_old["TaxValue"];
			$typepay = $res_old[2];
			$idno = $res_old[3];
			$asset_id = $res_old[4];
						
			$old_value_before_del = $res_old[6].";\"".$res_old[5]."\";\"".$res_old[7]."\";".$res_old[1].";".$res_old[2].";\"".$res_old[0]."\";";
		
		}	
	
		pg_query("BEGIN WORK"); $trans = 0;  //  BEGIN TRANSACTIONS
		
        if($cmd == "bill"){
			$fieldType = 'BillNumber';
			$old_value = $old_BillNumber ;
			$sql="UPDATE carregis.\"DetailCarTax\" SET \"BillNumber\"='$w' WHERE \"IDDetail\" = '$id'";
			
			if( $result=@pg_query($sql) ){
				$data['success'] = true;
			} else {
				$trans++; // Query command failed
				$data['success'] = false;
			}
			if($data['success']==true){ //เมื่อ Update ข้อมูลสำเร็จ แล้วทำการเก็บข้อมูลอ้างอิงใน LogRegisChange
				$ins_log=pg_query("insert into carregis.\"LogRegisChange\" 	
					   (\"UserID\",\"IDNO\",\"AssetID\",\"IDDetail\",\"FieldChanged\",\"TypePay\",\"OldValue\",\"NewValue\",\"DatetimeChanged\")
					   values
					   ('$resid','$idno','$asset_id','$id','$fieldType','$typepay','$old_value','$w','$dateTimeNow')");
					 //$data['message'] =  $ins_log;
				if (!$ins_log) {
					$trans++; // Query command failed
				}
			}
        } else if($cmd == "nobill" || $cmd == "notnobill"){
			$old_value = $old_TaxValue ;
			$fieldType = 'TaxValue';
            $sql="UPDATE carregis.\"DetailCarTax\" SET \"TaxValue\"='$w' WHERE \"IDDetail\" = '$id'";
            if( $result=@pg_query($sql) ){
                 $data['success'] = true;
            } else {
				$trans++; // Query command failed
                $data['success'] = false;
            }
			if($data['success']==true){ //เมื่อ Update ข้อมูลสำเร็จ แล้วทำการเก็บข้อมูลอ้างอิงใน LogRegisChange
			
				$ins_log=pg_query("insert into carregis.\"LogRegisChange\" 	
					   (\"UserID\",\"IDNO\",\"AssetID\",\"IDDetail\",\"FieldChanged\",\"TypePay\",\"OldValue\",\"NewValue\",\"DatetimeChanged\")
					   values
					   ('$resid','$idno','$asset_id','$id','$fieldType','$typepay','$old_value','$w','$dateTimeNow')");
					 //$data['message'] =  $ins_log;
				if (!$ins_log) {
					$trans++; // Query command failed
				}
			   
			}
        } else if($cmd == "del" ){
			$old_value = $old_value_before_del ;
		
            $sql="Delete From carregis.\"DetailCarTax\" WHERE \"IDDetail\" = '$id'";
			
            if( $result=@pg_query($sql) ){
                 $data['success'] = true;
            } else {
				$trans++; // Query command failed
                $data['success'] = false;
            } // insert log  
			if($data['success']==true){ //เมื่อ Delete ข้อมูลสำเร็จ แล้วทำการเก็บข้อมูลอ้างอิงใน LogRegisChange
				$ins_log=pg_query("insert into carregis.\"LogRegisChange\" 	
					   (\"UserID\",\"IDNO\",\"AssetID\",\"IDDetail\",\"FieldChanged\",\"TypePay\",\"OldValue\",\"NewValue\",\"DatetimeChanged\")
					   values
					   ('$resid','$idno','$asset_id','$id','ALL','0','$old_value','Deleted','$dateTimeNow')");
					 
					 //$data['message'] =  $ins_log;
				if (!$ins_log) {
					$trans++; // Query command failed
				}
			}
        }

		if($trans == 0) {
			pg_query("COMMIT");  //  COMMIT
		}
		else {
			pg_query("ROLLBACK");  //  COMMIT
			$data['success'] = false;
			$data['message'] = "เกิดข้อผิดพลาดระหว่างทำรายการ => ยกเลิกการทำรายการครั้งนี้";
		}

	} else {
		$data['success'] = false;
		$data['message'] = "คุณไม่มีสิทธิ์ทำรายการ!";
}
//echo $cmd;
echo json_encode($data);
?>