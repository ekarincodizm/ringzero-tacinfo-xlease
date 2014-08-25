<?php
session_start();
include("../config/config.php");

$now_date = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];
$vcid = pg_escape_string($_POST['vcid']);
$cash_change = pg_escape_string($_POST['cash_change']);
$select_type = pg_escape_string($_POST['select_type']);
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

<div style="float:left"><input name="button" type="button" onclick="window.location='voucher_change.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Payment Voucher</B></legend>

<div style="margin:10px;" align="center">

<div class="ui-widget">

<?php
pg_query("BEGIN WORK");
$status = 0;

$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"vc_id\" = '$vcid'");
if($res_name=pg_fetch_array($qry_name)){
    $acb_id = $res_name["acb_id"];
    $cash_amt = $res_name["cash_amt"];
    $cq_amt = $res_name["cq_amt"];
    $VenderID = $res_name["VenderID"];
    $sumall = $cash_amt+$cq_amt;
}

$qry_name=pg_query("SELECT \"acid\" FROM account.\"vender\" WHERE \"VenderID\"='$VenderID' ");
if($res_name=pg_fetch_array($qry_name)){
    $vender_acid = $res_name["acid"];
}

if($cq_amt != 0 AND $cash_change < $cq_amt){
    echo "ยอดเงินไม่ถูกต้อง ยอดเงินต้องเท่ากับหรือมากกว่า ยอดเงินเช็ค !";
    exit;
}


if($sumall == $cash_change){
    $change_money = 0;
}else{
    $change_money = $cash_change-$sumall;
}

$change_money_abs = abs($change_money);

if($change_money_abs != 0){

if($sumall > $cash_change){
    $text_insert = "เงินทอน $vcid";
}elseif($sumall < $cash_change){
    $text_insert = "เบิกเงินเพิ่ม $vcid";
}

$gj_id=@pg_query("select account.\"gen_no\"('$now_date','GJ')");
$res_gj_id=@pg_fetch_result($gj_id,0);
        
//Head
$result=pg_query("insert into account.\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('GJ','$res_gj_id','$now_date','$text_insert','$vcid');");
if(!$result){
    $status += 1;
}
$auto_id=pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
$res_auto_id=pg_fetch_result($auto_id,0);

$acb_id = "$acb_id,$res_gj_id";
//Detail
$qry_name2=pg_query("SELECT * FROM account.\"AccountBookDetail\" WHERE \"RefID\" = '$vcid' ORDER BY \"auto_id\" ASC");
while($res_name2=pg_fetch_array($qry_name2)){
    $AcID = $res_name2["AcID"];
    $AmtDr = $res_name2["AmtDr"];
    $AmtCr = $res_name2["AmtCr"];
    
    $qry_name=pg_query("SELECT \"AcType\" FROM account.\"AcTable\" WHERE \"AcID\"='$AcID' ");
    if($res_name=pg_fetch_array($qry_name)){
        $AcType = $res_name["AcType"];
        if($AcType == "CUR"){
            continue;
        }
    }

    if($sumall > $cash_change){
        if($AmtDr == 0){
            $result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$AcID','$change_money_abs','0','$vcid');");
        }elseif($AmtCr == 0){
            $result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$AcID','0','$change_money_abs','$vcid');");
        }
    }elseif($sumall < $cash_change){
        if($AmtDr == 0){
            $result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$AcID','0','$change_money_abs','$vcid');");
        }elseif($AmtCr == 0){
            $result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$AcID','$change_money_abs','0','$vcid');");
        }
    }

    
    if(!$result){
        $status += 1;
    }
}

}//ปิด เช็ค ไม่เท่ากับ 0 ให้บันทึกลง detail


//Insert Acc ----------------------------------------------------------------------
$gj_id=@pg_query("select account.\"gen_no\"('$now_date','GJ')");
$res_gj_id=@pg_fetch_result($gj_id,0);

//Head
$result=pg_query("insert into account.\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('GJ','$res_gj_id','$now_date','รายการรับเข้า ลงบัญชี $vcid','$vcid');");
if(!$result){
    $status += 1;
}
$auto_id=pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
$res_auto_id=pg_fetch_result($auto_id,0);

//Detail
$result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$select_type','$cash_change','0','$vcid');");
if(!$result){
    $status += 1;
}
$result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$vender_acid','0','$cash_change','$vcid');");
if(!$result){
    $status += 1;
}
//จบ Insert Acc ----------------------------------------------------------------------


$acb_id = "$acb_id,$res_gj_id";
$qry=pg_query("UPDATE account.\"voucher\" SET \"amt_change\"='$change_money', \"acb_id\"='$acb_id', \"finish\"='TRUE' WHERE \"vc_id\"='$vcid' ");
if(!$qry){
    $status += 1;
}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br />";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้<br /><br />";
}
?>

<input name="button" type="button" onclick="javascript:window.open('voucher_change_print.php?id=<?php echo "$vcid"; ?>' , 'P4719874512M<?php echo "$vcid"; ?>','menuber=no,toolbar=yes,location=no,scrollbars=no, status=no,resizable=no,width=800,height=600')" value="พิมพ์" />

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>