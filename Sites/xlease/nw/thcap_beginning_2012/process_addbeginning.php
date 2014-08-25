<?php 
include("../../config/config.php");
include("../../nw/function/checknull.php");
$doerID = $_SESSION["av_iduser"];
$doerStamp = nowDateTime();
pg_query("BEGIN WORK");
$status=0;
$datahave="";
$acid=pg_escape_string($_POST["acid"]);
$money =pg_escape_string($_POST["money"]);
$money = str_replace(",", "",$money);
//1.ตรวจสอบว่า มีการ post ค่า serial รหัสบัญชี  มาจริง  
if(($acid!="") and ($money !="")){
//2.ตรวจสอบว่า  serial รหัสบัญชีั้  มีในระบบ จริง โดย ดึง ข้อมูล เลขที่บัญชี มาด้วย
	$qry_accBook=pg_query("SELECT \"accBookserial\",\"accBookID\" FROM account.\"V_all_accBook\" where \"accBookserial\"='$acid'");
	$numrows = pg_num_rows($qry_accBook);
	if($numrows>0){
		$result_accbook = pg_fetch_array($qry_accBook);
		$accBookserial=$result_accbook["accBookserial"]; 
		$accBookID=$result_accbook["accBookID"];
		//ตรวจสอบว่า เคยทำการบันทึกแล้วหรือไม่
		$qry_auto_id=pg_query("SELECT \"auto_id\" FROM account.\"thcap_ledger_detail\" where \"accBookserial\"='$accBookserial'
		and \"is_ledgerstatus\"='1' and \"ledger_stamp\"::date='2012-12-31'	");
		$numrows = pg_num_rows($qry_auto_id);
		
		if($numrows>0){
			$datahave="insysledger_detail";//มีข้อมูลในระบบแล้วในระบบ
		}
		else{		
			$in_sql="INSERT INTO account.\"thcap_ledger_detail\"( \"auto_id_ref\", \"ledger_stamp\", \"accBookserial\",\"abd_accbookid\",\"is_ledgerstatus\",
			\"ledger_balance\",\"doerid\",\"doerstamp\" ) 
			VALUES ('0','2012-12-31','$accBookserial','$accBookID','1','$money','$doerID','$doerStamp')";	
			if(!$result=pg_query($in_sql)){
				$status++;
			}	
		}			
	}
	else{
		$datahave="notsysaccbook";//ไม่มีข้อมูลจริงในระบบ	
	}
}
else{
	$datahave="no";//มีข้อมูล ที่ส่งมา
}

//ตรวจสอบการทำงานว่า ทำงานได้สมบูรณ์ หรือไม่ จะได้แจ้ง ให้ user ทราบว่า (alert)
$script= '<script language=javascript>';
if($datahave==""){
	if($status==0){
		pg_query("COMMIT");
		$script.= " alert('บันทึกรายการเรียบร้อย');";
	}
	else{
		pg_query("ROLLBACK");
		$script.= " alert('ไม่สามารถบันทึกได้  กรุณาดำเนินการอีกครั้งในภายหลัง');";
		
	}
}
else if($datahave=="insysledger_detail"){
	pg_query("ROLLBACK");	
	$script.= " alert('ไม่สามารถบันทึกได้  เนื่องจาก บัญชีนี้มีการ คีย์ข้อมูล ยอดยกมา ปี 2555(2012-12-31) แล้ว');";
}
else if($datahave=="notsysaccbook"){
	pg_query("ROLLBACK");
	$script.= " alert('ไม่สามารถบันทึกได้  เนื่องจาก บัญชีนี้ ไม่มีอยู่ในระบบ');";
}
else if($datahave=="no"){
	pg_query("ROLLBACK");
	$script.= " alert('ไม่สามารถบันทึกได้  ข้อมูลที่ส่งมามีบางรายการเป็นค่าว่าง');";
}
$script.= 'location.href="frm_Index.php";';
$script.= '</script>';
echo $script;


?>