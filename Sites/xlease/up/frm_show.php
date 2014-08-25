<?php
ob_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    </head>
<body>

<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="wrapper">
<fieldset><legend><b>File List</b></legend>
<div align="left">

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

function showpdf($id,$type,$mode){
    //$file_url = $type."/".$type."_".$id.".pdf";
	
	$file_url = $type."/".$type."_".$id;
	$file_url_check = $type."/".$type."_".$id;
	
	$d = 1;
	do{ // เช็คดูก่อนว่าเคยมีการ upload file ชื่อเดียวกันซ้ำไปหรือเปล่า ถ้ามีให้เอาไำฟล์ล่าสุดมาใช้
		if( file_exists($file_url_check."__".$d.".pdf") )
		{
			$file_url = $file_url_check."__".$d;
			$d++;
			$stop_check = 1;
		}
		else
		{
			$stop_check = 2;
		}
	}while($stop_check==1);

	$file_url = $file_url.".pdf";
	
    if( is_file($file_url) ){
        $arr_name = directoryToArray($type,true);
        
        if($mode == 2){
            foreach($arr_name as $key_name => $value_name) {
                $pos = strrpos($value_name, $id);
                if ($pos === false) {
                    //echo "not $value_name";
                }else{
                    //$data_create = filemtime($value_name);
                    //$data_create = date("Y-m-d H:i:s",$data_create);
                    $array_items[] = "$value_name";
                }
            }
        }elseif($mode == 1){
            $array_items = $file_url;
        }
        return $array_items;
    }else{
        return $array_items;
    }
}

$id = $_GET['id'];
$type = $_GET['type'];
$mode = $_GET['mode'];

if( empty($id) OR empty($type) OR empty($mode) ){
    echo "<center>ผิดผลาด !</center>";
}else{

$data_show = showpdf($id,$type,$mode);
$c_data = count($data_show);
if($c_data == 0){
    echo "<center>ไม่พบไฟล์ !</center>";
}elseif( $c_data > 1 ){
    echo "<ol>";
    foreach($data_show as $key_show => $value_show) {
        $data_create = filemtime($value_show);
        $data_create = date("Y-m-d H:i:s",$data_create);
        echo "<li><a href=\"$value_show\" target=\"_blank\">$value_show</a> - สร้างเมื่อ $data_create</li>";
    }
    echo "</ol>";
}else{
    ob_end_clean();
    if($mode == 1){
        header("Location: $data_show");
    }else{
        header("Location: $data_show[0]");
    }
    
    
}

}//ปิดตรวจสอบ papameter
?>

</div>
</fieldset>
</div>

        </td>
    </tr>
</table>

</body>
</html>