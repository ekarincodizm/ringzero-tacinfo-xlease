<?php
@ini_set('display_errors', '1');
include("../config/config.php");

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0

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

$getid = trim($_POST["getid"]);
$type = $_POST["type"];
$arr_name = directoryToArray($type,true);

foreach($arr_name as $key_name => $value_name) {
    $pos = strrpos($value_name, $getid);
    if ($pos === false) {
        //echo "not $value_name";
    }else{
        $data_sub = $value_name;
        $data_sub = str_replace('.pdf','',$data_sub);
        //$data_sub = str_replace($type.'_','',$data_sub);
        $data_sub = str_replace($type.'/','',$data_sub);

        $data_create = filemtime($value_name);
        $data_create = date("Y-m-d H:i:s",$data_create);
        $inub += 1;
        $show .= "$inub. <input type=\"checkbox\" id=\"chkcopy\" name=\"chkcopy[]\" value=\"$data_sub\"> <a href=\"$value_name\" target=\"_blank\">$value_name</a> -  <font size=1>สร้างเมื่อ $data_create</font><br>";
    }
}

if(empty($show)){
    echo "ไม่พบไฟล์";
}else{
    echo $show;
}
?>