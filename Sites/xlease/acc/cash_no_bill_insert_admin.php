<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="  กลับ  " class="ui-button" onclick="window.location='cash_no_bill_admin.php'"></div>
<div style="float:right">&nbsp;</div>
<div style="clear:both"></div>

<fieldset><legend><B>เงินโอนไม่ผ่าน Bill Payment - Backdoor Tranpay</B></legend>

<div class="ui-widget" style="text-align:center">

<?php
$branch_id=$_SESSION["av_officeid"];
$id_user=$_SESSION["av_iduser"];
$datenow=date("Y-m-d");
$datepick = pg_escape_string($_POST['datepick']);
$bank = pg_escape_string($_POST['bank']);

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

pg_query("BEGIN WORK");

$counter = pg_escape_string($_POST['counter']);
if($counter>0){
    for($i=1; $i<=$counter; $i++){
         $hh = pg_escape_string($_POST['hh'.$i]);
         $mm = pg_escape_string($_POST['mm'.$i]);
         $bran = pg_escape_string($_POST['bran'.$i]);
         $money = pg_escape_string($_POST['money'.$i]);
         if($hh=="" || $mm=="" || $bran=="" || $money==""){
            echo "ข้อมูลรายการที่ #$i ไม่ครบถ้วน $hh:$mm | $bran | $money<br />";
         }else{

             $nub++;
             $qry_post=pg_query("select gen_pos_no('$datenow')");
             $res_genpost=pg_fetch_result($qry_post,0); //postID
             
             $sql_ipostlog="insert into \"PostLog\" (\"PostID\",\"UserIDPost\",\"PostDate\",\"paytype\") values ('$res_genpost','$id_user','$datenow','TR')";
             if(!$postlog_result=pg_query($sql_ipostlog)){
                 $status+=1;
             }
             
             $in_sql="insert into \"TranPay\" (\"branch_id\",\"tr_date\",\"tr_time\",\"pay_bank_branch\",\"terminal_id\",\"terminal_sq_no\",\"amt\",\"bank_no\",\"tran_type\",\"pay_cheque_no\",\"post_on_asa_sys\",\"PostID\") 
             values  ('$branch_id','$datepick','$hh:$mm:00','$bran','TR-ACC','0000','$money','$bank','TR','0000000','FALSE','$res_genpost')";
             if($result=pg_query($in_sql)){
                 
             }else{
                 $status+=1;
             }
         }
    }

    if($status == 0 && $nub>0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) Backdoor Tranpay', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo "บันทึกข้อมูลเรียบร้อยแล้ว<br><br>";
    }elseif($chk_date == 1){
        pg_query("ROLLBACK");
        echo "ไม่สามารถบันทึกข้อมูลได้ เนื่องจาก วันที่ $datepick ได้มีการเพิ่มข้อมูลไปแล้ว<br><br>";
    }else{
        pg_query("ROLLBACK");
        echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้<br><br>";
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