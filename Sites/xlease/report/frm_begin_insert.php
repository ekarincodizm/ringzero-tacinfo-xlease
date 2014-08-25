<?php
session_start();
include("../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$aid = $_POST['aid'];
$ayear = $_POST['ayear'];
$counter = $_POST['counter'];
$insert_year = "$ayear-01-01";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="  Back  " onclick="javascript:window.location='frm_begin.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both"></div>
        
<fieldset><legend><B>ตั้งบัญชียกมา 1/1</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

if($counter == 0 OR $counter == ""){
    echo "ไม่พบรายการ ไม่สามารถบันทึกได้";
}else{

if(empty($aid)){
    $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"ref_id\") values ('AA','AAST-ยอดยกมา','$insert_year','START')";
    if(!$res_in_sql=pg_query($in_sql)){
        $ms = "Insert BookHead ผิดผลาด";
        $status++;
    }

    $atid=pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
    $aid=pg_fetch_result($atid,0);
    if(empty($aid)){
        $ms = "Query BookHead ผิดผลาด";
        $status++;
    }
}

for($i=1; $i<=$counter; $i++){
    $typeac = $_POST['typeac'.$i];
    $amtdr = $_POST['amtdr'.$i];
    $amtcr = $_POST['amtcr'.$i];
    
    if(!empty($typeac) AND ($amtdr != 0 OR $amtcr !=0) ){
        
        $qry_ck=pg_query("SELECT COUNT(\"auto_id\") as ckid FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$aid' AND \"AcID\"='$typeac'");
        if($res_ck=pg_fetch_array($qry_ck)){
            $ckid = $res_ck["ckid"];
        }
        
        if($ckid > 0){
            $ms = "พบรายการ ACID ซ้ำ";
            $status++;
            break;
        }
        
        $qry_in="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$aid','$typeac','$amtdr','$amtcr')";
        if(!$res_in=@pg_query($qry_in)){
            $ms = "บันทึกผิดผลาด";
            $status++;
        }
    }else{
        $ms = "ข้อมูลไม่ครบถ้วน";
        $status++;
    }
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่มบัญชียกมา', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้ $ms";
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