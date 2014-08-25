<?php
set_time_limit(0);
session_start();
include("../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
$smeter = pg_escape_string($_POST['smeter']);
$smeter1 = pg_escape_string($_POST['smeter1']);
$gdate = $yy."-".$mm."-01";

$cid = $_POST['cid'];
$hid_idno = $_POST['hid_idno'];
$hid_due_date = $_POST['hid_due_date'];
$hid_str_type = $_POST['hid_str_type'];
$hid_due_amount = $_POST['hid_due_amount'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<?php include("menu.php"); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>

<fieldset><legend><b>สร้างรายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี</b></legend>        
<div style="text-align:center">
<?php
pg_query("BEGIN WORK");
$status = 0;
$arr_error = array();

$j=0;
foreach($cid AS $k => $v){
    $idno = $hid_idno[$k];
    $due_date = $hid_due_date[$k];
    $str_type = $hid_str_type[$k];
    $due_amount = $hid_due_amount[$k];

    $g_id=pg_query("select carregis.gen_id('$gdate')");
    $res_g_id=pg_fetch_result($g_id,0);

    $in_sql="INSERT INTO carregis.\"CarTaxDue\" (\"IDCarTax\",\"IDNO\",\"TaxDueDate\",\"TypeDep\",\"CusAmt\") 
    VALUES ('$res_g_id','$idno','$due_date','$str_type','$due_amount')";
    if(!$result_in_sql=pg_query($in_sql)){
        $status++;
        $arr_error[] = "$in_sql";
    }
    //echo "$k|$v|$idno|$due_date|$str_type|$due_amount<br>";
}

if($status == 0){
    pg_query("COMMIT");
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(TAL) ทำรายการระบบทะเบียนรถ - สร้างรายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี', '$datelog')");
	//ACTIONLOG
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้\n$arr_error[0]";
}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_car_search.php'">
</div>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>