<?php
session_start();
include("../config/config.php");

$now_date = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];
$select = pg_escape_string($_POST['select']);
$count_select = count($select);
$cur_select = array_keys($select);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" /> 
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" class="ui-button" onclick="window.location='voucher_approve.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Approve Voucher</B></legend>

<div style="margin:10px;" align="center">

<div class="ui-widget">

<?php
pg_query("BEGIN WORK");

$status = 0;

for($i=0;$i<$count_select;$i++){
    $exp_select = explode("#",$select[$cur_select[$i]]);
    if($exp_select[0] == "L" && isset($_POST['button2']) ){
        $up_sql1=pg_query("UPDATE account.\"voucher\" SET \"qpprove_id\"='$user_id' WHERE \"vc_id\"='$exp_select[1]';");
        if(!$up_sql1){
            $status += 1;
        }
    }elseif($exp_select[0] == "R" && isset($_POST['button3']) ){
        $up_sql2=pg_query("UPDATE account.\"voucher\" SET \"qpprove_id\"='$user_id',\"cancel\"='TRUE' WHERE \"vc_id\"='$exp_select[1]';");
        if(!$up_sql2){
            $status += 1;
        }

        //Head
        $gj_id=@pg_query("select account.\"gen_no\"('$now_date','GJ')");
        $res_gj_id=@pg_fetch_result($gj_id,0);
        
        $result=pg_query("insert into account.\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('GJ','$res_gj_id','$now_date','ยกเลิกรายการ $exp_select[1]','$exp_select[1]');");
        if(!$result){
            $status += 1;
        }
        $auto_id=pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
        $res_auto_id=pg_fetch_result($auto_id,0);
        //จบ Head
        
        $qry_name2=pg_query("SELECT * FROM account.\"AccountBookDetail\" WHERE \"RefID\" = '$exp_select[1]' ORDER BY \"auto_id\" ASC");
        while($res_name2=pg_fetch_array($qry_name2)){
            $AcID = $res_name2["AcID"];
            $AmtDr = $res_name2["AmtDr"];
            $AmtCr = $res_name2["AmtCr"];
            
            $result=pg_query("insert into account.\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$res_auto_id','$AcID','$AmtCr','$AmtDr','$exp_select[1]');");
            if(!$result){
                $status += 1;
            }
            
        }
        
    }
}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br />";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้<br /><br />";
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