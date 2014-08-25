<?php 
include('../../config/config.php');
include("../function/checknull.php");
session_start();
$doerID= $_SESSION["av_iduser"];
$contractID =$_POST['contractID'];
$debtDueDate=$_POST['debtDueDate'];
$payamt =$_POST['payamt'];
$note =$_POST['note'];
$stoporunstop =$_POST['clickact'];//1=stop,2=unstop
if($stoporunstop==""){
	if(isset($_POST["STOP"])){
		$stoporunstop='1';
	}
}
$doerStamp=nowDateTime();
$note=checknull($note);
$inter = pg_query("SELECT \"thcap_process_hp_vatcontrol\"('$contractID','$doerStamp','$doerID','$stoporunstop',$note)");
$status =pg_fetch_array($inter); 
list($result)=$status;
if($result){ 
	//echo 1;
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกข้อมูลเรียบร้อยแล้ว');
				window.opener.location.reload();
				self.close();";
	$script.= '</script>';
	echo $script;

}else {
	//echo 2;
	$script= '<script language=javascript>';
	$script.= " alert('ไม่สามารถบันทึกข้อมูลได้');
				window.opener.location.reload();
				self.close();";
	$script.= '</script>';
	echo $script;
	}

?>