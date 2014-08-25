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
    $first_data = $chkfirst[0];

if(in_array($first_data,$chkshow)){
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

$showdata_all = $first_data.",".$is_show; //ต่อ string

if(empty($sub) AND empty($schema)){
    $in_sql="UPDATE \"DocumentType\" SET \"name\"='$name',\"sub\"='',\"table\"='$tablename',\"fieldsearch\"='$is_search',\"fieldshow\"='$showdata_all',\"schema\" = '' WHERE \"id\"='$id'";
}elseif(empty($sub)){
    $in_sql="UPDATE \"DocumentType\" SET \"name\"='$name',\"sub\"='$sub',\"table\"='$tablename',\"fieldsearch\"='$is_search',\"fieldshow\"='$showdata_all',\"schema\"='$schema' WHERE \"id\"='$id'";
}elseif(empty($schema)){
    $in_sql="UPDATE \"DocumentType\" SET \"name\"='$name',\"sub\"='$sub',\"table\"='$tablename',\"fieldsearch\"='$is_search',\"fieldshow\"='$showdata_all',\"schema\"='$schema' WHERE \"id\"='$id'";
}else{
    $in_sql="UPDATE \"DocumentType\" SET \"name\"='$name',\"sub\"='$sub',\"table\"='$tablename',\"fieldsearch\"='$is_search',\"fieldshow\"='$showdata_all',\"schema\"='$schema' WHERE \"id\"='$id'";
}

if($result=pg_query($in_sql)){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$userlog', '(ALL) แก้ไข Folder อัพโหลดไฟล์', '$datelog')");
		//ACTIONLOG---
    echo "แก้ไขข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถแก้ไขข้อมูลได้";
}

}// ปิด chack เลือก Field ซ้ำ กับ Field ชื่อ
}// ปิด check เลือกไฟล์ตั้งชื่อมากกว่า 1 ค่า
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_edit_upload.php'">

</div>
</fieldset>
</div>

        </td>
    </tr>
</table>

</body>
</html>