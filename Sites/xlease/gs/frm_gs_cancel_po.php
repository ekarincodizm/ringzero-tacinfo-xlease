<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$nowdate = nowDate();//ดึง วันที่จาก server
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>แก้ไขข้อมูล</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

$qry=pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" where ref_id = '$id';");
if($res=pg_fetch_array($qry)){
    $auto_id = $res["auto_id"];
}else{
    $status += 1;
}

$aj_id=pg_query("select account.gen_no('$nowdate','AJ');");
$res_aj_id=pg_fetch_result($aj_id,0);

$qry11=pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" ORDER BY \"auto_id\" DESC Limit 1");
if($res11=pg_fetch_array($qry11)){
    $get_auto_id1 = $res11["auto_id"]+1;
}

$in_sql="insert into account.\"AccountBookHead\" (\"auto_id\",\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") values ('$get_auto_id1','AJ','$res_aj_id','$nowdate','ยกเลิก $id','','$id');";
if($result=pg_query($in_sql)){
    
}else{
    $status += 1;
}

if( !empty($auto_id) ){
    $qry2=pg_query("SELECT * FROM account.\"AccountBookDetail\" where autoid_abh = '$auto_id' order by \"auto_id\" ASC;");
    while($res2=pg_fetch_array($qry2)){
        $autoid_abh = $res2["autoid_abh"];
        $AcID = $res2["AcID"];
        $AmtDr = $res2["AmtDr"];
        $AmtCr = $res2["AmtCr"];
        $RefID = $res2["RefID"];
        
        $qry=pg_query("SELECT \"auto_id\" FROM account.\"AccountBookDetail\" ORDER BY \"auto_id\" DESC Limit 1");
        if($res=pg_fetch_array($qry)){
            $get_auto_id = $res["auto_id"]+1;
        }
        
        $in_sql1="insert into account.\"AccountBookDetail\" (\"auto_id\",\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values ('$get_auto_id','$get_auto_id1','$AcID','$AmtCr','$AmtDr','$RefID');";
        if($result1=pg_query($in_sql1)){
            
        }else{
            $status += 1;
        }
        
    }
}

$up_sql="UPDATE gas.\"PoGas\" SET \"status_po\"='false' WHERE \"poid\"='$id';";
if($up_result=pg_query($up_sql)){

}else{
    $status += 1;
}

if($status == 0){
    pg_query("COMMIT");
    echo "ยกเลิกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถยกเลิกได้ กรุณาลองใหม่อีกครั้ง";
}
?>

<br>
<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_maker.php'">
</div>
</fieldset>


        </td>
    </tr>
</table>

</body>
</html>