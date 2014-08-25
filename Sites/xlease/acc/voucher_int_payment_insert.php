<?php
session_start();
include("../config/config.php");

$now_date = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];
$title = pg_escape_string($_POST['title']);
$details = pg_escape_string($_POST['details']);
$vender = pg_escape_string($_POST['vender']);
$type1 = pg_escape_string($_POST['type1']);
$cash_type = pg_escape_string($_POST['cash_type']);
$cash_amt = pg_escape_string($_POST['cash_amt']);
$type2 = pg_escape_string($_POST['type2']);
$cq_type = pg_escape_string($_POST['cq_type']);
$acid_bank = pg_escape_string($_POST['acid_bank']);
$cq_id = pg_escape_string($_POST['cq_id']);
$cq_date = pg_escape_string($_POST['cq_date']);
$cq_amt = pg_escape_string($_POST['cq_amt']);

$title = pg_escape_string($_POST['title']);
$details = pg_escape_string($_POST['details']);
$insert_detail = "$title\n$details";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:13px;
}
</style>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" onclick="window.location='voucher_int_payment.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Internal Payment Voucher</B></legend>

<div style="margin:10px;" align="center">

<div class="ui-widget">

<?php
$qry_name=pg_query("SELECT * FROM account.\"vender\" WHERE \"VenderID\"='$vender' ");
if($res_name=pg_fetch_array($qry_name)){
    $type_vd = $res_name["type_vd"];
    $vd_name = $res_name["vd_name"];
    $acid = $res_name["acid"];
}

if(empty($acid)){
    echo "ผิดผลาด : ไม่พบ Account ID ของ Vender : $type_vd $vd_name";
}else{

pg_query("BEGIN WORK");

//Insert voucher
$rs=pg_query("select account.\"gen_ivp_no\"('$now_date')");
$vp_id=pg_fetch_result($rs,0);

$gj_id=pg_query("select account.gen_igj_no('$now_date');");
$res_gj_id=pg_fetch_result($gj_id,0);


if($type1 == 1 && $type2 == ""){
    $in_sql="insert into account.\"voucher\" (\"vc_id\",\"vc_type\",\"vc_detail\",\"cash_amt\",\"maker_id\",\"acb_id\",\"print_date\",\"VenderID\",\"compID\",\"comBranch\") values  ('$vp_id','P','$insert_detail','$cash_amt','$user_id','$res_gj_id','$now_date','$vender','TAL','BK01')";
}elseif($type2 == 1 && $type1 == ""){
    $in_sql="insert into account.\"voucher\" (\"vc_id\",\"vc_type\",\"vc_detail\",\"acid_bank\",\"cq_id\",\"cq_date\",\"cq_amt\",\"maker_id\",\"acb_id\",\"print_date\",\"VenderID\",\"compID\",\"comBranch\") values  ('$vp_id','P','$insert_detail','$acid_bank','$cq_id','$cq_date','$cq_amt','$user_id','$res_gj_id','$now_date','$vender','TAL','BK01')";
}elseif($type2 == 1 && $type1 == 1){
    $in_sql="insert into account.\"voucher\" (\"vc_id\",\"vc_type\",\"vc_detail\",\"cash_amt\",\"acid_bank\",\"cq_id\",\"cq_date\",\"cq_amt\",\"maker_id\",\"acb_id\",\"print_date\",\"VenderID\",\"compID\",\"comBranch\") values  ('$vp_id','P','$insert_detail','$cash_amt','$acid_bank','$cq_id','$cq_date','$cq_amt','$user_id','$res_gj_id','$now_date','$vender','TAL','BK01')";
}

$result=pg_query($in_sql);
if(!$result){
    $status += 1;
}
//จบ Insert voucher

//Insert BookHead
$result=pg_query("insert into account.\"IntAccHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('IGJ','$res_gj_id','$now_date','$insert_detail','$vp_id');");
if(!$result){
    $status += 1;
}

$auto_id=pg_query("select currval('account.\"IntAccHead_auto_id_seq\"');");
$res_auto_id=pg_fetch_result($auto_id,0);
//จบ Insert BookHead

//Insert BookDetail
$sum_amt_all = $cash_amt+$cq_amt;
if($type1 == 1 && $type2 == ""){
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$acid','$sum_amt_all','0','$vp_id');");
    if(!$result){
        $status += 1;
    }
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$cash_type','0','$cash_amt','$vp_id');");
    if(!$result){
        $status += 1;
    }
}elseif($type2 == 1 && $type1 == ""){
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$acid','$sum_amt_all','0','$vp_id');");
    if(!$result){
        $status += 1;
    }
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$cq_type','0','$cq_amt','$vp_id');");
    if(!$result){
        $status += 1;
    }
}elseif($type2 == 1 && $type1 == 1){
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$acid','$sum_amt_all','0','$vp_id');");
    if(!$result){
        $status += 1;
    }
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$cash_type','0','$cash_amt','$vp_id');");
    if(!$result){
        $status += 1;
    }
    $result=pg_query("insert into account.\"IntAccDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$cq_type','0','$cq_amt','$vp_id');");
    if(!$result){
        $status += 1;
    }
}
//จบ Insert BookDetail

if($status == 0){
    pg_query("COMMIT");
    echo "เพิ่มข้อมูลเรียบร้อยแล้ว<br /><br />";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถเพิ่มข้อมูลได้<br /><br />";
}
?>

<input name="button" type="button" onclick="javascript:window.open('voucher_payment_print.php?id=<?php echo "$vp_id"; ?>' , 'PO97M<?php echo "$vp_id"; ?>','menuber=no,toolbar=yes,location=no,scrollbars=no, status=no,resizable=no,width=800,height=600')" value="พิมพ์" />

<?php
}
?>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>