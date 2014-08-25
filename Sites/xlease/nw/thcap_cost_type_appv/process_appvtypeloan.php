<?php
include("../../config/config.php");
include("../../nw/function/checknull.php");
$appvID = $_SESSION["av_iduser"];
$appvStamp = nowDateTime();
pg_query("BEGIN WORK");
$status=0;
$costtype=pg_escape_string($_POST["costtype"]);
$autoid=pg_escape_string($_POST["autoid"]);

//$resultappv=$_POST["resultappv"]; เนื่องจาก การทำงานหน้านี้ ถูกเรียกใช้ โดย frm_appv.php เท่านั้น เมื่อไม่มีการส่งค่ามาเลย comment ส่วนนี้
//ตรวจสอบว่ากด ปุม อนุมัติ/ไม่อนุมัติ
$appvcheck=pg_escape_string($_POST["appv"]);
if($appvcheck==""){
$appvcheck=pg_escape_string($_POST["unappv"]);
}
if($appvcheck=="อนุมัติ"){
	$resultappv='1';//   เมื่อกดอนุมัติ
}else if($appvcheck=="ไม่อนุมัติ"){
	$resultappv='0';//   เมื่อกดไม่อนุมัติ
}
$sql_query = pg_query("select *  from \"thcap_cost_type_temp\" where \"autoid\"='$autoid'");				
$result_query  = pg_fetch_array($sql_query);
$costname=$result_query["costname"];
$typeloansuse=$result_query["typeloansuse"];
$doerid=$result_query["doerid"];
$note=$result_query["note"];
$status_costtype=$result_query["status_costtype"];

$appvtrue="yes";

$typeloansuse = checknull($typeloansuse);
$note = checknull($note);
//ตรวจสอบ level
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];

if($emplevel<=1){}
else{
	if($appvID==$doerid){$appvtrue="no";}
	/*else{
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel_doerid=$leveluser["emplevel"];
		if($emplevel_doerid<=1){}
		else{$appvtrue="no";}}*/

}
if($appvtrue=="yes"){

	if($costtype=='0'){//เพิ่มข้อมูลใหม่	
		//บันทึกข้อมูลลงตารราง
		if($resultappv=='1'){
			$in_sql="INSERT INTO \"thcap_cost_type\"( \"costname\", \"typeloansuse\", \"note\",\"status_costtype\") 
			VALUES ('$costname',$typeloansuse,$note,'$status_costtype')";	
			if(!$result=pg_query($in_sql)){
				$status++;
			}
		}	
		$sql_query = pg_query("select max(\"costtype\") as \"max\" from \"thcap_cost_type\"");	
		$result_query  = pg_fetch_array($sql_query);
		$max=$result_query["max"];
		//อัปเดดข้อมูลลงตารราง
		$update_sql="update \"thcap_cost_type_temp\" set \"costtype\"='$max',\"approved\"='$resultappv',\"appvid\"='$appvID',\"appvstamp\"='$appvStamp' where \"autoid\"='$autoid'";
		if(!$result=pg_query($update_sql)){
			$status++;
		}
	}
	else{
		if($resultappv=='1'){
			$update_sql="update \"thcap_cost_type\" set \"costname\"='$costname',\"typeloansuse\"=$typeloansuse,\"note\"=$note ,\"status_costtype\"='$status_costtype' 
			where \"costtype\"='$costtype'";
			if(!$result=pg_query($update_sql)){
				$status++;
			}
		}
		$update_sql="update \"thcap_cost_type_temp\" set \"approved\"='$resultappv',\"appvid\"='$appvID',\"appvstamp\"='$appvStamp' where \"autoid\"='$autoid'";
		if(!$result=pg_query($update_sql)){
			$status++;
		}
	}
}
$script= '<script language=javascript>';
if($appvtrue=="yes"){
	if($status==0){
		pg_query("COMMIT");	
		//echo 1;		
		$script.= " alert('บันทึกรายการเรียบร้อย');";
	}
	else{
		pg_query("ROLLBACK");    
		//echo 2;
		$script.= " alert('ไม่สามารถบันทึกได้  กรุณาดำเนินการอีกครั้งในภายหลัง');";
	}
}
else{
	pg_query("ROLLBACK"); 
	//echo 3;	
   $script.= " alert('ผิดพลาดเนื่องจาก ผู้ขอ กับ ผู้อนุมัติ ต้องเป็นคนละคนกัน');";
}
if($autoid!=0){
	$script.= 'window.opener.location.reload();
			   window.close();';
}
else{
	$script.= 'window.location.reload();';
}
$script.= '</script>';
echo $script;
?>