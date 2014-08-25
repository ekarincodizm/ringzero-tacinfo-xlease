<?php
include("../config/config.php");

$aid = pg_escape_string($_POST['aid']);
$acid = pg_escape_string($_POST['acid']);
$dr = pg_escape_string($_POST['dr']);
$cr = pg_escape_string($_POST['cr']);
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
        
<div style="float:left"><input type="button" value="  กลับ  " onclick="javascript:window.location='edit_abh.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>แก้ไขสมุดบัญชี</B></legend>

<div class="ui-widget" align="center">

<?php
$i = 0;
foreach($aid AS $a1){
    if($dr[$i] == 0){
        $sum_cr+=round($cr[$i],2);
    }else{
        $sum_dr+=round($dr[$i],2);
    }
    $i++;
}

if(round($sum_cr,2) != round($sum_dr,2)){
    echo "ยอดเงิน Dr Cr ไม่เท่ากัน $sum_cr / $sum_dr";
}else{
    $status = 0;
    $i = 0;
    
    pg_query("BEGIN WORK");
    
    foreach($aid AS $v){
        $sql_update="UPDATE account.\"AccountBookDetail\" SET \"AcID\"='$acid[$i]',\"AmtDr\"='$dr[$i]',\"AmtCr\"='$cr[$i]' WHERE \"auto_id\"='$v'";
        $res_update=pg_query($sql_update);
        if(!$res_update){
            $status++;
        }
        $i++;
    }

    if($status == 0){
        pg_query("COMMIT");
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    }else{
        pg_query("ROLLBACK");
        echo "ไม่สามารถบันทึกข้อมูลได้";
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