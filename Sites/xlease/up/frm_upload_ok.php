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
<div align="center">
<?php

if(is_file($_FILES["file"]["tmp_name"])){
    $chkcopy = $_POST["chkcopy"];
    $getid = trim($_POST["getid"]);
    $type = $_POST["type"];
    $dir = "$type"; //Folder เก็บไฟล์
    $extension_whitelist = array("pdf", "PDF");

    $path_info = pathinfo($_FILES["file"]["name"]);
    $file_extension = $path_info["extension"];
    $is_valid_extension = false;
    foreach ($extension_whitelist as $extension) {
        if ($file_extension == $extension) {
            $is_valid_extension = true;
            break;
        }
    }

    if($is_valid_extension){
        if(empty($chkcopy)){ // ตรวจสอบการเซฟทับ
        
        $gen = $type."_".$getid; //กำหนดชื่อ
        
        do{
            if(file_exists("" . $dir . "/" . $gen .".". $file_extension . "")){ //เช็คชื่อซ้ำ
                $gen_number += 1;
                $arr_gen = explode("__",$gen);
                $arr_nub = count($arr_gen);
                if($arr_nub > 1){
                    $gen = $arr_gen[0]."__".$gen_number;
                }else{
                    $gen = $gen."__".$gen_number;
                }
                $regu = 1;
            }else{
                $regu = 2;
            }
        }while($regu==1);
        
        }else{
            $count_copy = count($chkcopy);
            if($count_copy > 1){
                header("Refresh: 0; url=frm_upload.php");
                echo "<script language=Javascript>alert ('ห้ามเลือกไฟล์เซฟทับเกิน 1 ไฟล์ !');</script>";
                exit();
            }
            $gen = $chkcopy[0];
        }
        
        if( move_uploaded_file($_FILES["file"]["tmp_name"],"" . $dir . "/" . $gen .".". $file_extension . "") ){
            $FileName =  "" . $dir . "/" . $gen .".". $file_extension . "";
            if( file_exists($FileName) ){
			
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$userlog', '(ALL) ทำการอัพโหลดไฟล์', '$datelog')");
				//ACTIONLOG---
			
                echo "อัพโหลดไฟล์ สำเร็จ<br>ที่อยู่ไฟล์ : <a href=\"$FileName\" target=\"_blank\">".$FileName."</a>";
            }else{
                echo "อัพโหลดไฟล์ ไม่สำเร็จ";
            }
        }else{
            echo "อัพโหลดไฟล์ ไม่สำเร็จ";
        }
        
    }else{
        header("Refresh: 0; url=frm_upload.php");
        echo "<script language=Javascript>alert ('ชนิดไฟล์ ไม่ถูกต้อง !');</script>";
        exit();
    }

}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_upload.php'">

</div>
</div>

        </td>
    </tr>
</table>

</body>
</html>