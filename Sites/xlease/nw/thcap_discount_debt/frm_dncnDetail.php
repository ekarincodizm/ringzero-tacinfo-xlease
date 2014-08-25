<?php
include("../../config/config.php");
$contractID=pg_escape_string($_GET["contractID"]);

if($contractID!=""){
?>

<div style="margin-top:0px;"><?php include('../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
<div style="padding:10px 0px"><?php  include('data_debt.php'); //หนี้อื่นๆที่ค้างชำระ ?></div>
<!--<div style="padding:10px 0px"><?php  include('create_request.php'); //ทำรายการ ?></div>-->

<?php } ?>