<?php
session_start();
include("../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$date_add = pg_escape_string($_POST['datepicker']);
$text_add = pg_escape_string($_POST['text_add']);
$check_1999 = in_array(1999,$_POST['acid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"><input type="button" value=" กลับ " onclick="javascript:window.location='add_acc_manual_aj.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>บันทึกเอง</B></legend>

<div align="center">

<?php
$c_dr=0;
$c_cr=0;

for($i=0;$i<count($_POST["acid"]);$i++){

    if($_POST['actype'][$i] == 1){
        $dr += pg_escape_string($_POST['text_money'][$i]);
        $c_dr += 1;
    }else{
        $cr += pg_escape_string($_POST['text_money'][$i]);
        $c_cr += 1;
    }

}

$dr = round($dr,2);
$cr = round($cr,2);

if($c_dr<1 or $c_cr<1){
    echo "ต้องมี Dr และ Cr อย่างน้อย 1 รายการ";
}elseif($dr != $cr){
    echo "ยอดเงิน Dr และ Cr ไม่ตรงกัน [$dr ~ $cr]";
}else{
    
    pg_query("BEGIN WORK");
    $status = 0;
    $gj_id=pg_query("select account.gen_no('$date_add','AJ');");
    $res_gj_id=pg_fetch_result($gj_id,0);
    if(empty($res_gj_id)){
        $status++;
    }
    
    if($check_1999){
        $in_sql="insert into account.\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('AJ','$res_gj_id','$date_add','$text_add','VATB');";
    }else{
        $in_sql="insert into account.\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\") values ('AJ','$res_gj_id','$date_add','$text_add');";
    }
    if(!$result=pg_query($in_sql)){
        $status++;
    }
    
    $auto_id=pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
    $res_auto_id=pg_fetch_result($auto_id,0);
    if(empty($res_auto_id)){
        $status++;
    }
    
    for($i=0;$i<count($_POST["acid"]);$i++){
        
        $adds_acid = pg_escape_string($_POST['acid'][$i]);
        $adds_money = pg_escape_string($_POST['text_money'][$i]);
        if($_POST['actype'][$i] == 1){
            $in_sql="insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values ('$res_auto_id','$adds_acid','$adds_money','0');";
            if(!$result=pg_query($in_sql)){
                $status++;
            }
        }else{
            $in_sql="insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values ('$res_auto_id','$adds_acid','0','$adds_money');";
            if(!$result=pg_query($in_sql)){
                $status++;
            }
        }

    }
    
    if($status==0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกบัญชีปรับปรุง', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
    }else{
        pg_query("ROLLBACK");
        echo "ไม่สามารถเพิ่มข้อมูลได้";
    }

}

?>

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>