<?php
require_once("config/config.php"); 
$iduser = $_SESSION['uid'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title><?php echo $_SESSION["session_company_name"]; ?></title>

<style type="text/css">
body {
    font-family: tahoma;
    font-size: 11px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 5px;
    padding-bottom: 5px;
}
H1{
    font-size: 16px;
    color: #585858;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
H2{
    font-size: 22px;
    color: #888800;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}

.wrapper{
	width:700; border: solid 0px;
}

.menu{
	margin:3px; text-align:center;
}

a:link, a:visited, a:hover {
    color: #585858;
    text-decoration: none;
}
a:hover {
    color: #ACACAC;
    text-decoration: none;
}

/* ====================== */
.roundedcornr_box {
   background: #ffffff;
   width: 700px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(img/roundedcornr_br.png) no-repeat bottom right;
}

.roundedcornr_top div, .roundedcornr_top, 
.roundedcornr_bottom div, .roundedcornr_bottom {
   width: 100%;
   height: 15px;
   font-size: 1px;
}
.roundedcornr_content {
    margin: 0 15px;
}
</style>

<link type="text/css" href="jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}

$(function(){
    $(window).bind("beforeunload",function(event){
        var msg="คุณกำลังปิดหน้าต่า่งหลัก หน้าต่างโปรแกรมที่เกี่ยวข้องจะปิดตัวทั้งหมด ?";
        $(window).bind("unload",function(event){
            event.stopImmediatePropagation();
            // แทรก ajax code ลบ session หรืออื่น ๆ
            closeAll();
        });
        return msg;
    });
});

function loadurl(dest) {
try {
    xmlhttp = window.XMLHttpRequest?new XMLHttpRequest():
    new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e) { /* do nothing */ }

xmlhttp.onreadystatechange = triggered;
xmlhttp.open("GET", dest);
xmlhttp.send(null);

}
function triggered() {
    if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)) {
    document.getElementById("loaddiv").innerHTML = xmlhttp.responseText;}
}

var refreshId = setInterval(function(){
    $('#loaddiv').fadeOut("slow").load('list_menu_data.php').fadeIn("slow");
}, 630000); //630000

</script>
    
</head>

<body>

<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<h2><?php echo $_SESSION["session_company_name"]; ?></h2>
<hr/>
<div class="wrapper">

<div id="loaddiv">

<table width="100%" border="0" align="center">
    <tr>
        <td align="left">
<?php 
if(!empty($iduser)){
?>
        <b>เข้าสู่ระบบโดย</b> <?php echo $_SESSION['user_login']; ?><br><b>เข้าสู่ระบบครั้งล่าสุดเมื่อ</b> <?php echo date( "d/m/Y H:i:s", strtotime( $_SESSION['lasttime_login']) ); ?>
<?php
}
?>
        </td>
        <td align="right">
<?php
if(empty($iduser)){
    echo "<A HREF=\"index.php\"><font color=\"#ff0000\"><b>เข้าสู่ระบบ</b></font></A>";
}else{
    echo "<A HREF=\"change_pass.php\"><font color=\"#ff0000\"><b>เปลี่ยนรหัสผ่าน</b></font></A> | <A HREF=\"logout.php\"><font color=\"#ff0000\"><b>ออกจากระบบ</b></font></A>";
}
?>
        </td>
    <tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="menu">
<tr>
<?php

if(empty($iduser)){
    echo "<div style=\"padding: 30px; color:#ff0000; font-size:13px; text-align: center;\">เนื่องจากท่านไม่มีการทำงานต่อเนื่องในระยะเวลาที่กำหนด ระบบจึงปิดตัวเองเพื่อความปลอดภัย<br>หากท่านต้องการทำงาน กรุณา LOGIN เข้าระบบใหม่</div>";
}else{

$code = md5(uniqid(rand().time(), true));

$result=pg_query("SELECT A.*,B.* FROM f_usermenu A 
INNER JOIN f_menu B on A.id_menu=B.id_menu 
WHERE (A.id_user='$iduser') AND (B.status_menu='1') AND (A.status=true) ORDER BY A.id_menu ASC");
while($arr_menu = pg_fetch_array($result)){
    $nub+=1;
    $menu_id = $arr_menu["id_menu"];                                                                                                      
    $menu_name = $arr_menu["name_menu"];
    $menu_path = $arr_menu["path_menu"];
?>
<td width="25%"><!--
<A HREF="#" onclick="javascript:popU('<?php echo $menu_path; ?>','<?php echo $menu_id."_".$code; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768');"><IMG SRC="images/icon_menu/<?php echo $menu_id; ?>.gif" WIDTH="80" HEIGHT="80" BORDER="0" ALT=""><br><?php echo $menu_name; ?></A>
-->
<A HREF="#" onclick="javascript:popU('<?php echo $menu_path; ?>','<?php echo $menu_id."_".$code; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768'); javascript:loadurl('list_menu_data.php');"><IMG SRC="images/icon_menu/<?php echo $menu_id; ?>.gif" WIDTH="80" HEIGHT="80" BORDER="0" ALT=""><br><?php echo $menu_name; ?></A>
</td>

<?php
if($nub == 4){
    echo "</tr><tr>";
    $nub = 0;
}

}
}
?>
    </tr>
</table>
</div>

</div>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>
</html>