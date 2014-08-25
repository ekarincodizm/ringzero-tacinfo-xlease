<?php
session_start();
include("../config/config.php");
$get_id_user = $_SESSION["av_iduser"];
$cid = pg_escape_string($_REQUEST['cid']);
$nowdate = date("Y/m/d");

pg_query("BEGIN WORK");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
</head>
<body>
<?php include("menu.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>

<fieldset><legend><B>รายการรับเล่มเข้า</B></legend>

<div align="center">

<?php
for($i=0;$i<count($cid);$i++){

    $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"BookIn\" = 'true',\"BookInDate\" = '$nowdate' WHERE \"IDCarTax\"='$cid[$i]'";
    if($result=pg_query($in_sql)){
        $status = 0;
    }else{
        $status = 1;
    }

}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>
<br>
<input type="button" value="  Back  " onclick="location.href='frm_book_in.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>