<?php
function directoryToArray($directory, $recursive) {
     $array_items = array();
     if ($handle = opendir($directory)) {
         while (false !== ($file = readdir($handle))) {
             if ($file != "." && $file != "..") {
                 if (is_dir($directory. "/" . $file)) {
                     if($recursive) {
                         $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                     }
                     $file = $directory . "/" . $file;
                     $array_items[] = preg_replace("/\/\//si", "/", $file);
                 } else {
                     $file = $directory . "/" . $file;
                     $array_items[] = preg_replace("/\/\//si", "/", $file);
                 }
             }
         }
         closedir($handle);
     }
     return $array_items;
}

$type = $_GET["type"];
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

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="header"><h1>AV.LEASING</h1></div>
<div class="wrapper">

<fieldset><legend><B>รายชื่อไฟล์ของ <?php echo $type; ?> </B></legend>

<?php

$arr_name = directoryToArray($type,true);
echo '
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#969696">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
    <td>ลำดับ</td><td>ชื่อไฟล์</td><td>สร้างเมื่อ</td><td></td>
</tr>
';
foreach($arr_name as $key_name => $value_name) {
    $pos = strrpos($value_name, $getid);

        $data_sub = $value_name;
        //$data_sub = str_replace('.pdf','',$data_sub);
        $data_sub = str_replace($type.'_','',$data_sub);
        $data_sub = str_replace($type.'/','',$data_sub);

        $data_create = filemtime($value_name);
        $data_create = date("Y-m-d H:i:s",$data_create);
        $inub += 1;
        $show .= "<tr bgcolor=\"#ffffff\"><td>$inub</td><td><a href=\"$value_name\" target=\"_blank\">$data_sub</a></td><td>สร้างเมื่อ $data_create</td><td><a href=\"rm.php?type=$type&name=$value_name\">ลบ</a></td></tr>";

}

if(empty($show)){
    echo "<tr bgcolor=\"#ffffff\"><td colspan=5 align=center>ไม่พบไฟล์</td></tr>";
}else{
    echo $show;
}

echo '</table>';

?>

</fieldset>

</div>

<div align="center">
<input type="button" value="  กลับ  " onclick="location.href='frm_edit_upload.php'">
</div>
        </td>
    </tr>
</table>

</body>
</html>