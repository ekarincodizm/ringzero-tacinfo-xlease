<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]); // เลขที่สัญญา
$execution = pg_escape_string($_GET["execution"]); // การกระทำ

if($contractID!="")
{
?>
	<div style="margin-top:0px;"><?php include('../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
	<div style="padding:10px 0px"><?php if($execution == "edit"){include('frm_selectProductEdit.php');}else{include('frm_selectProduct.php');} //รายการสินค้า ?></div>
<?php
}
?>