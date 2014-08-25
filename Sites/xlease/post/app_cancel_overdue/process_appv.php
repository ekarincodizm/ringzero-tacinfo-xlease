<?php 
include('../../config/config.php');
include("../../nw/function/checknull.php"); ?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
$appStamp=nowDateTime();
$appid= $_SESSION["av_iduser"];

$autoid =pg_escape_string($_POST['id']);
$note=pg_escape_string($_POST['note']);
$note=checknull($note);

pg_query("BEGIN");
$status=0;


if(isset($_POST["appv"])){
	$status_app="1";//อนุมัติ
}else{
	$status_app="0";//ไม่อนุมัติ
}
//select 
$qry_IDCarTax = pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue_reserve\" where  \"auto_id\"='$autoid'");
$res_IDCarTax = pg_fetch_array($qry_IDCarTax);
$IDCarTax = $res_IDCarTax["IDCarTax"];

//update  status appid apptime
$updetail="UPDATE carregis.\"CarTaxDue_reserve\" 
			SET \"appvID\"='$appid' ,
			\"appvStamp\"='$appStamp' ,
			\"Approved\"='$status_app',
			\"remark_app\"=$note 
			WHERE \"auto_id\" = '$autoid'  and \"Approved\"='9' RETURNING  \"auto_id\"";

	$result_temp = pg_query($updetail);
	if($result_temp){
		$abh_autoid_temp = pg_fetch_result($result_temp,0);
	}else{
		$status++;
	}
	if($abh_autoid_temp ==''){$status++;}
	
//delect ถ้า อนุมัติ
if($status_app=='1'){
	$Delete_CarTaxDue="DELETE FROM carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDCarTax\" ='$IDCarTax'";
	$resu_CarTaxDue=pg_query($Delete_CarTaxDue);
	if($resu_CarTaxDue){}else{ $status++;}
	$qry_IDCarTax_chk = pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue\" where   \"cuspaid\" = 'false' AND \"IDCarTax\" ='$IDCarTax'");
	$numrows2=pg_num_rows($qry_IDCarTax_chk);
	if($numrows2>0){$status++;}
}
if($status == 0)
{
	pg_query("COMMIT");
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกข้อมูลเรียบร้อยแล้ว');";	
}
else
{
	pg_query("ROLLBACK");
	$script= '<script language=javascript>';
	$script.= " alert('ไม่สามารถบันทึกข้อมูลได้');";
				
}
$script.= "window.opener.location.reload();
				self.close();";
$script.= '</script>';
echo $script;

?>