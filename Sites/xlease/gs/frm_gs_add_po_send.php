<?php
session_start();
include("../config/config.php");

$g_name = pg_escape_string($_POST['g_name']);
$g_type = pg_escape_string($_POST['g_type']);
$date_post = pg_escape_string($_POST['date_post']);
$date_install = pg_escape_string($_POST['date_install']);
$carnum = pg_escape_string($_POST['carnum']);
$marnum = pg_escape_string($_POST['marnum']);
$memo = pg_escape_string($_POST['memo']);
$nowdate = Date('Y-m-d');

$qry_inf=pg_query("select costofgas from gas.\"Model\" WHERE modelid = '$g_type' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $costofgas = $res_inf["costofgas"];
    
    $qry_c=pg_query("select amt_before_vat('$costofgas')");
    $res_c1=pg_fetch_result($qry_c,0);
    $res_c1 = round($res_c1,2);
    
    $vat = $costofgas-$res_c1;
    $vat = round($vat,2);
}

pg_query("BEGIN WORK");

$qry_poid=pg_query("select gas.gen_id('$nowdate',1,1)");
$gen_poid=pg_fetch_result($qry_poid,0);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<fieldset><legend><B>ออก PO</B></legend>
<div align="center">
<?php
$in_sql="INSERT INTO gas.\"PoGas\" (poid,podate,date_install,idcompany,idmodel,costofgas,vatofcost,memo,carnum,marnum) VALUES ('$gen_poid','$date_post','$date_install','$g_name','$g_type','$res_c1','$vat','$memo','$carnum','$marnum')";
if($result=pg_query($in_sql)){
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้";
}
?>

<br><br>

<input type="button" value="พิมพ์เอกสาร" onClick="javascript:window.open('frm_pdf_purchase.php?id=<?php echo $gen_poid; ?>','','menubar=no,toolbar=no,location=no,scrollbars=no,status=no,resizable=no,width=1024,height=768,top=220,left=650 ' )"; >

<input type="button" value="  Back  " onclick="location.href='frm_gs_add_po.php'">
</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>