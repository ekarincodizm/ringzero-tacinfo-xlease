<?php
require_once("config/config.php");
$_SESSION["av_officeid"];
$iduser = $_SESSION['uid'];

$code = md5(uniqid(rand().time(), true));

$datetime = date('Y-m-d h:i:s');

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
?>

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

$result=pg_query("SELECT A.*,B.* FROM f_usermenu A 
INNER JOIN f_menu B on A.id_menu=B.id_menu 
WHERE (A.id_user='$iduser') AND (B.status_menu='1') AND (A.status=true) ORDER BY A.id_menu ASC");
while($arr_menu = pg_fetch_array($result)){
    $nub+=1;
    $menu_id = $arr_menu["id_menu"];                                                                                                      
    $menu_name = $arr_menu["name_menu"];
    $menu_path = $arr_menu["path_menu"];
?>

<td width="25%">
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