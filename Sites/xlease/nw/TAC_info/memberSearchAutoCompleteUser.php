<?php
include("config.php");
header("Content-type:text/html; charset=UTF-8"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false); 
// เชื่อมต่อฐานข้อมูล
//$link=pg_connect("localhost","root","รหัสผ่าน") or die("error".pg_error());
//pg_select_db("project",$link);
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');
setlocale(LC_ALL, 'th_TH');

$q = urldecode($_GET["q"]);
//$q= iconv('utf-8', 'tis-620', $_GET['test']);
//$pagesize = 50; // จำนวนรายการที่ต้องการแสดง
$table_db=" TrMember"; // ตารางที่ต้องการค้นหา
$find_field="type_job_name"; // ฟิลที่ต้องการค้นหา
$sql = "select * from \"TrMember\" where \"Username\" like '%$q%' or \"carregistrationnumber\" like '%$q%' or \"telephonenumber\" like '%$q%' or \"beforName\" like '%$q%' or \"firstName\" like '%$q%' or \"lastName\" like '%$q%'";
$dbquery = pg_query($sql);
while ($result = pg_fetch_array( $dbquery )) {
$id = $result["UserID"]; // ฟิลที่ต้องการส่งค่ากลับ
$name =$result["Username"]; // ฟิลที่ต้องการแสดงค่า
$carcode =$result["carregistrationnumber"];
$telephone =$result["telephonenumber"];
$beforName =$result["beforName"];
$firstname =$result["firstName"];
$lastName =$result["lastName"];
// ป้องกันเครื่องหมาย '
$name = str_replace("'", "'", $name);
// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
$display_name ="รหัส : ".$id." | "."ชื่อผู้ใช้ : ".$name." | ชื่อสมาชิก : ".$beforName." ".$firstname."  ".$lastName;
echo "<li onselect=\"this.setText('$id').setValue('$id'); document.forms['frmMain'].submit();\">$display_name</li>";
}
?>