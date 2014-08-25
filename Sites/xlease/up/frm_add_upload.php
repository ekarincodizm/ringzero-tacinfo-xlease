<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");

function GetFieldName($table){
    $qry_if=pg_query("SELECT attname FROM pg_attribute, pg_type
    WHERE typname = '$table'
    AND attrelid = typrelid
    AND attname NOT LIKE '%pg.dropped%'
    AND attname NOT IN ('cmin', 'cmax', 'ctid', 'oid', 'tableoid', 'xmin', 'xmax'); ");
    $rows = pg_num_rows($qry_if);
    $x = 0;
    while ($x < $rows)
    {
        $colname = pg_fetch_row($qry_if);
        $col[$colname[0]] = $x;
        $x++;
    }
    return $col;
}    

function GetTableName(){
    $qry_if=pg_query("SELECT tablename FROM pg_tables
    WHERE tablename NOT LIKE 'pg\\_%'
    AND tablename NOT LIKE 'sql\\_%'
    ORDER BY tablename ASC; ");
    $rows = pg_num_rows($qry_if);
    $x = 0;
    while ($x < $rows)
    {
        $colname = pg_fetch_row($qry_if);
        $col[$colname[0]] = $x;
        $x++;
    }
    return $col;
}

function GetSchemaName(){
    $qry_if=pg_query("SELECT distinct schemaname FROM pg_tables 
    WHERE schemaname NOT IN ('information_schema', 'pg_catalog', 'pg_toast', 'pg_toast_temp_1', 'pg_temp_1')
    ORDER BY schemaname ASC; ");
    $rows = pg_num_rows($qry_if);
    $x = 0;
    while ($x < $rows)
    {
        $colname = pg_fetch_row($qry_if);
        $col[$colname[0]] = $x;
        $x++;
    }
    return $col;
}
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    
<script language="Javascript">
<!--
function CheckSelect(field) {
    var temp1=0;
    var temp2=0;
    var temp3=0;
    for (i = 0; i < document.fn.chksearch.length; i++){
        if( document.fn.chksearch[i].checked == true ) temp1 = temp1+1;
    }
    for (i = 0; i < document.fn.chkfirst.length; i++){
        if( document.fn.chkfirst[i].checked == true ) temp2 = temp2+1;
    }
     for (i = 0; i < document.fn.chkshow.length; i++){
        if( document.fn.chkshow[i].checked == true ) temp3 = temp3+1;
    }
    
    if(document.fn.id.value == ""){
        alert('กรุณากรอกรหัสประเภท');
        return false;
    }else if(document.fn.name.value == ""){
        alert('กรุณากรอกชื่อประเภท');
        return false;
    }else if(temp1 == 0){
        alert('เลือก Fields ใช้สำหรับค้นหา');
        return false;
    }else if(temp2 == 0){
        alert('เลือก Fields ใช้ตั้งชื่อไฟล์');
        return false;
    }else if(temp2 > 1){
        alert('Fields ใช้ตั้งชื่อไฟล์ เลือกได้ไม่เกิน 1 Fields');
        return false; 
    }else if(temp3 == 0){
        alert('เลือก Fields ใช้แสดงตอนค้นหา');
        return false;
    }else{
         return true;
    }
}

// -->
</script>    
    
    </head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="header"><h1>AV.LEASING</h1></div>
<div class="wrapper">

<fieldset><legend><b>เพิ่มประเภทการอัพโหลดไฟล์</b></legend>

<form name="cf" method="post" action="">
<table width="100%" border="0" cellSpacing="0" cellPadding="3" align="center">
<tr><td height="10"></td></tr>
<tr>
    <td width="20%"><b>รหัสประเภท</b></td>
    <td width="80%"><input type="text" name="id" size="15" maxlength="5" value="<?php echo $_POST['id']; ?>"></td>
</tr>
<tr>
    <td><b>ชื่อประเภท</b></td>
    <td><input type="text" name="name" size="50" maxlength="50" value="<?php echo $_POST['name']; ?>"></td>
</tr>
<tr>
    <td><b>เป็นหมวดย่อยของ</b></td>
    <td>
<select name="sub">
    <option value="">ไม่ใช่หมวดย่อย</option>
<?php 
$qry_inf=pg_query("select * from \"DocumentType\" WHERE sub is null ORDER BY \"id\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $Doc_id = $res_inf["id"];
    $Doc_name = $res_inf["name"];
    if($Doc_id==$_POST['sub']){
?>          
    <option value="<?php echo "$Doc_id"; ?>" selected><?php echo "$Doc_name"; ?></option>
<?php 
    }else{
?>
    <option value="<?php echo "$Doc_id"; ?>"><?php echo "$Doc_name"; ?></option>
<?php
    }
} 
?>
</select>
    </td>
</tr>
<tr>
    <td><b>ชื่อ Schema</b></td>
    <td>
<select name="schema" onchange="document.cf.submit()";>
    <option value="">ไม่มี Schema</option>
<?php
$res_tb = GetSchemaName();
foreach($res_tb as $key => $value) {
    if($key==$_POST['schema'])
        echo "<option value=\"$key\" selected>$key</option>";
    else
        echo "<option value=\"$key\">$key</option>";
}
?>
</select>
    </td>
</tr>
<tr>
    <td><b>เลือก Table</b></td>
    <td>
<select name="tablename" onchange="document.cf.submit()";>
    <option value="">เลือก</option>
<?php
$res_tb = GetTableName();
foreach($res_tb as $key => $value) {
    if($key==$_POST['tablename'])
        echo "<option value=\"$key\" selected>$key</option>";
    else
        echo "<option value=\"$key\">$key</option>";
}
?>
</select>
    </td>
</tr>
</table>
</form>

<?php
$tbn = $_POST['tablename'];

if(!empty($tbn)){

$result = GetFieldName($tbn);   
if(!empty($result)){
?>

<form name="fn" method="post" action="frm_add_upload_ok.php" onsubmit="return CheckSelect();">
<input type="hidden" id="id" name="id" value="<?php echo $_POST['id']; ?>">
<input type="hidden" id="name" name="name" value="<?php echo $_POST['name']; ?>">
<input type="hidden" name="sub" value="<?php echo $_POST['sub']; ?>">
<input type="hidden" name="schema" value="<?php echo $_POST['schema']; ?>">
<input type="hidden" name="tablename" value="<?php echo $_POST['tablename']; ?>">

<table width="100%">
<tr>
    <td width="33%" bgcolor="#D7FFD7"><b>เลือก Fields ใช้สำหรับค้นหา :</b><br><br>
<?php
foreach($result as $key => $value) {
    echo "<input type=\"checkbox\" id=\"chksearch\" name=\"chksearch[]\" value=\"$key\">$key<br>";
}
?>
    </td>
    <td width="33%" bgcolor="#FFFFD9"><b>เลือก Fields ใช้ตั้งชื่อไฟล์ : </b><br><font size="1" color="#ff0000">(อนุญาติให้เลือกได้เพียง 1 Fields)</font><br>
<?php
foreach($result as $key => $value) {
    echo "<input type=\"checkbox\" id=\"chkfirst\" name=\"chkfirst[]\" value=\"$key\">$key<br>";
}
?>
    </td>
    <td width="33%" bgcolor="#FFDFDF"><b>เลือก Fields ใช้แสดงตอนค้นหา :</b><br><font size="1" color="#ff0000">(ห้ามเลือก Fields ที่ใช้ตั้งชื่อไฟล์ซ้ำ)</font><br>
<?php
foreach($result as $key => $value) {
    echo "<input type=\"checkbox\" id=\"chkshow\" name=\"chkshow[]\" value=\"$key\">$key<br>";
}
?>
    </td>
</tr>
<tr align="center">
    <td colspan="10"><br><br><input type="submit" name="ok" value="  บันทึก  "></td>
</tr>
</table>
</form>

<?php
}// check array ไม่ว่าง
}// check โพสค่ามาหรือไม่
?>

</fieldset>

</div>

<div align="center"><br>
<input type="button" value="  Close  " onclick="javascript:window.close();">
</div>

        </td>
    </tr>
</table>

</body>
</html>