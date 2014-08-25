<?php
include("../config/config.php");
$gj_id = pg_escape_string($_GET['gj_id']);
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

<script type="text/javascript">
$(document).ready(function(){
    $('#btnsubmit').click(function(){
        $.post('edit_abh_submit.php',{
            aid: $('#aid').val()
        },
        function(data){
            if(data.success){
                alert(data.message);
            }else{
                alert(data.message);
            }
        },'json');
    });
});
</script>

    
</head>
<body>

<?php
$qry_1=pg_query("select * from account.\"AccountBookHead\" WHERE acb_id='$gj_id'");
if($res_1=pg_fetch_array($qry_1)){
    $auto_id = $res_1["auto_id"];
    $acb_date = $res_1["acb_date"];
    $acb_detail = $res_1["acb_detail"];
?>

<div style="font-weight:bold; text-align:right"><?php echo "วันที่ $acb_date"; ?></div>
<form name="frm_1" id="frm_1" action="edit_abh_submit.php" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" bgcolor="#C0C0C0">
<tr bgcolor="#ACACAC" style="font-weight:bold; text-align:center">
    <td width="15%">รหัสบัญชี</td>
    <td width="55%">ชื่อ</td>
    <td width="15%">Dr</td>
    <td width="15%">Cr</td>
</tr>

<?php
    $qry_2=pg_query("select * from account.\"AccountBookDetail\" WHERE autoid_abh='$auto_id' ORDER BY \"auto_id\" ASC ");
    $qry_num_2=pg_num_rows($qry_2);
    while($res_2=pg_fetch_array($qry_2)){
        $auto_id = $res_2["auto_id"];
        $AcID = $res_2["AcID"];
        $AmtDr = $res_2["AmtDr"];
        $AmtCr = $res_2["AmtCr"];
        
        $qry_3=pg_query("select \"AcName\" from account.\"AcTable\" WHERE \"AcID\"='$AcID'");
        if($res_3=pg_fetch_array($qry_3)){
            $AcName = $res_3["AcName"];
        }
        
?>
<tr bgcolor="#FFFFFF">
    <td align="center"><input type="hidden" name="aid[]" id="aid" value="<?php echo "$auto_id"; ?>">
        <select name="acid[]" id="acid">
        <?php
        $qry_4=pg_query("select \"AcID\",\"AcName\" from account.\"AcTable\" ORDER BY \"AcID\" ASC ");
        while($res_4=pg_fetch_array($qry_4)){
            $sl_AcID="";
            $sl_AcName="";
            $sl_AcID = $res_4["AcID"];
            $sl_AcName = $res_4["AcName"];
            if($sl_AcID == $AcID){
                echo "<option value=\"$sl_AcID\" selected>$sl_AcID:$sl_AcName</option>";
            }else{
                echo "<option value=\"$sl_AcID\">$sl_AcID:$sl_AcName</option>";
            }
        }
        ?>
        </select>
    </td>
    <td><?php echo $AcName; ?></td>
    <td align="right"><input type="text" name="dr[]" id="dr" value="<?php echo round($AmtDr,2); ?>" style="text-align:right" size="15"></td>
    <td align="right"><input type="text" name="cr[]" id="cr" value="<?php echo round($AmtCr,2); ?>" style="text-align:right" size="15"></td>
</tr>
<?php    
    }
?>

</table>

<div align="right" style="padding-top: 5px"><input type="submit" name="btnsubmit1" id="btnsubmit1" value="บันทึก"></div>

</form>
<?php
    if($qry_num_2 == 0){
        echo "ไม่พบข้อมูล";
    }
}else{
    echo "ผิดผลาด [$gj_id]";
}
?>

</body>
</html>