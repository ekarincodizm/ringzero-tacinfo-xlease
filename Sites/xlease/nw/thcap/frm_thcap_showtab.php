<?php
session_start();  
include("../../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$id = pg_escape_string($_GET["id"]);
if($id=="")
{
	$id = pg_escape_string($_POST["tpID2"]);
}
$page_title = "(THCAP) จัดการประเภทค่าใช้จ่าย";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<title><?php echo $page_title; ?></title>
    <LINK href="../images/styles.css" type=text/css rel=stylesheet>
    <link type="text/css" href="../images/jqueryui/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../images/jqueryui/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="../images/jqueryui/js/jquery-ui-1.8.16.custom.min.js"></script>

</head>

<body>

<div class="roundedcornr_box" style="width:900px">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<h1>(THCAP) จัดการประเภทค่าใช้จ่าย </h1>
<div id="maintabs">
    <ul>
         <li><a href="frm_thcap_edit2.php?id=<?php echo "$id";?>">รายละเอียดประเภทค่าใช้จ่าย</a> </li>
         <li><a href="frm_thcap_edit3.php?id=<?php echo "$id";?>">รายละเอียดความสัมพันธ์ทางบัญชี</a> </li>	 
    </ul>
</div>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

<script>
$(function(){
    $( "#maintabs" ).tabs({
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html("");
            }
        }
    });
});
</script>

</body>
</html>