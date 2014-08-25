<?php 
session_start();  
include("../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$id = pg_escape_string($_GET['id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบ TypePay</h1></div>

<div class="wrapper">
<div align="right"><a href="frm_typepay_show.php"><img src="full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div>
<fieldset><legend><B>แก้ไขข้อมูล TypePay</B></legend>

<?php
$qry_name=pg_query("SELECT * FROM \"TypePay\" where \"TypeID\" = '$id' ");
if($res_name=pg_fetch_array($qry_name)){
    $TName = $res_name["TName"];
    $UseVat = $res_name["UseVat"];
    $TypeRec = $res_name["TypeRec"];   
    $TypeDep = $res_name["TypeDep"];
}
?>

<form id="frm_1" name="frm_1" method="post" action="frm_typepay_edit_ok.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="left">
      <td width="20%"><b>TypeID</b></td>
      <td width="80%" class="text_gray"><?php echo "$id"; ?><input type="hidden" name="id" value="<?php echo "$id"; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>TName</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="tname" size="50" value="<?php echo "$TName"; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>UseVat</b></td>
      <td colspan="3" class="text_gray">
      <?php
        if($UseVat == 't'){
            $chk1 = "checked"; $chk2 = "";
        }else{
            $chk2 = "checked"; $chk1 = ""; 
        }
      ?>
        <input type="radio" name="uservat" value="TRUE" <?php echo "$chk1"; ?>>TRUE
        <input type="radio" name="uservat" value="FALSE" <?php echo "$chk2"; ?>>FALSE
      </td>
   </tr>
   <tr align="left">
      <td><b>ประเภทใบเสร็จ</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="typerec" size="20" value="<?php echo "$TypeRec"; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>ฝ่ายที่แสดง</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="typepay" size="20" value="<?php echo "$TypeDep"; ?>"></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="บันทึก"></td>
   </tr>
</table>
</form>

</div>
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>