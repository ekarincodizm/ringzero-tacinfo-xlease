<?php
session_start();
include("../config/config.php");
$userlog=$_SESSION["av_iduser"];
$datelog=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    </head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="header"><h1>AV.LEASING</h1></div>
<div class="wrapper">
<fieldset>
<div align="center">
<?php
$id = $_POST['id'];
$name = $_POST['name'];
$sub = $_POST['sub'];
$schema = $_POST['schema'];
$tablename = $_POST['tablename'];
$chksearch = $_POST['chksearch'];
$chkfirst = $_POST['chkfirst'];
$chkshow = $_POST['chkshow'];

$sql = pg_query("SELECT COUNT(id) FROM \"DocumentType\" WHERE \"id\"='$id' ");
$res = pg_fetch_array($sql);
$records = $res[0];

if($records > 0){
    echo "รหัสประเภทซ้ำ !<br>กรุณาตรวจสอบและแก้ไขอีกครั้ง";
}else{

$count_search = count($chksearch);
foreach($chksearch as $key_search => $value_search) {
    $i1+=1;
    if($count_search==$i1)
        $is_search .= $value_search;
    else
        $is_search .= $value_search.",";
}
 
$count_first = count($chkfirst);
if($count_first > 1){
    echo "ห้ามเลือก Fields ใช้ตั้งชื่อไฟล์ มากกว่า 1 ค่า";
}else{
    $first_date = $chkfirst[0];
    
if(in_array($first_date,$chkshow)){
    echo "Fields ใช้แสดงตอนค้นหา - ห้ามเลือก Fields ที่ใช้ตั้งชื่อไฟล์ซ้ำ";
}else{

$count_show = count($chkshow);
foreach($chkshow as $key_show => $value_show) {
    $i2+=1;
    if($count_show==$i2)
        $is_show .= $value_show;
    else
        $is_show .= $value_show.",";
}

$showdata_all = $first_date.",".$is_show;

if(!is_dir($id)){
    mkdir($id,0777);
    chmod($id,0777);
}

if(is_dir($id)){

if(empty($sub) AND empty($schema)){
    $in_fn="insert into \"DocumentType\" (\"id\",\"name\",\"table\",\"fieldsearch\",\"fieldshow\") values ('$id','$name','$tablename','$is_search','$showdata_all')";
}elseif(empty($sub)){
    $in_fn="insert into \"DocumentType\" (\"id\",\"name\",\"table\",\"fieldsearch\",\"fieldshow\",\"schema\") values ('$id','$name','$tablename','$is_search','$showdata_all','$schema')";
}elseif(empty($schema)){
    $in_fn="insert into \"DocumentType\" (\"id\",\"name\",\"sub\",\"table\",\"fieldsearch\",\"fieldshow\") values ('$id','$name','$sub','$tablename','$is_search','$showdata_all')";
}else{
    $in_fn="insert into \"DocumentType\" (\"id\",\"name\",\"sub\",\"table\",\"fieldsearch\",\"fieldshow\",\"schema\") values ('$id','$name','$sub','$tablename','$is_search','$showdata_all','$schema')";
}

if($result=pg_query($in_fn)){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$userlog', '(ALL) เพิ่ม Folder อัพโหลดไฟล์', '$datelog')");
		//ACTIONLOG---
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    echo "ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง";
}

}else{//ปิด check is_dir
    echo "ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง";
}
}// ปิด check เลือกชื่อไฟล์ มากกว่า 1 ชื่อ
}// ปิด check ชื่อไฟล์ ซ้ำกับ ชื่อที่โชว์
}// ปิด check รหัสประเภทซ้ำ
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_add_upload.php'">

</div>
</fieldset>
</div>

        </td>
    </tr>
</table>

</body>
</html>