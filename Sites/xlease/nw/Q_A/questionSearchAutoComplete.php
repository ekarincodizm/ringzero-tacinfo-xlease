<?php
include("../../config/config.php");
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
$sql = "select * from \"qaQuestion\" where \"questionName\" like '%$q%' or \"questionPoster\" like '%$q%' or \"questionType\" like '%$q%' or \"questionContent\" like '%$q%'";
$dbquery = pg_query($sql);
while ($result = pg_fetch_array( $dbquery )) {
$id = $result["questionID"]; // ฟิลที่ต้องการส่งค่ากลับ
$questionName =$result["questionName"];
// ป้องกันเครื่องหมาย '
$questionName = str_replace("'", "'", $questionName);
// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
$display_name ="รหัส : ".$id." | "."คำถาม : ".mb_substr($questionName,0,50);
echo "<li onselect=\"this.setText('$questionName').setValue('$id'); document.forms['frm_search'].submit();\">$display_name</li>";
}
?>