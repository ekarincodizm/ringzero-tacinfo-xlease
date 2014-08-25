<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    </head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="header"><h1>AV.LEASING</h1></div>
<div class="wrapper">

<fieldset><legend><B>แก้ไขประเภทการอัพโหลดไฟล์</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#969696">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">รหัสประเภท</td>
        <td align="center">ชื่อประเภท</td>
        <td align="center">เป็นหมวดย่อยของ</td>
        <td align="center">ชื่อ Schema</td>
        <td align="center">เลือก Table</td>
        <td align="center">Fields ค้นหา</td>
        <td align="center">Fields แสดงผล</td>
        <td align="center">แก้ไข</td>
        <td align="center">รายชื่อไฟล์</td>
    </tr>

<?php
$qry_dc=pg_query("select * from \"DocumentType\" ORDER BY id ASC");

$rows = pg_num_rows($qry_dc);
while($res_if=pg_fetch_array($qry_dc)){
    $id = $res_if["id"];
    $name = $res_if["name"];
    $sub = $res_if["sub"]; if(empty($sub)) $sub = "ประเภทหลัก";
    $table = $res_if["table"]; if(empty($table)) $table = "-";
    $fieldsearch = $res_if["fieldsearch"]; if(empty($fieldsearch)) $fieldsearch = "-";
    $fieldshow = $res_if["fieldshow"]; if(empty($fieldshow)) $fieldshow = "-";
    $schema = $res_if["schema"]; if(empty($schema)) $schema = "-";
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center"><?php echo $id; ?></td>
    <td align="left"><?php echo $name; ?></td>
    <td align="left"><?php echo $sub; ?></td>
    <td align="left"><?php echo $schema; ?></td>
    <td align="left"><?php echo $table; ?></td>
    <td align="left"><?php echo $fieldsearch; ?></td>
    <td align="left"><?php echo $fieldshow; ?></td>
    <td align="center"><a href="frm_edit_type.php?id=<?php echo $id; ?>">แก้ไข</a></td>
    <td align="center"><a href="frm_show_list.php?type=<?php echo $id; ?>">แสดง</a></td>
</tr>

<?php
}
?>    
    
</table>

</fieldset>

</div>

<div align="center">
<input type="button" value="  Close  " onclick="javascript:window.close();">
</div>
        </td>
    </tr>
</table>

</body>
</html>