<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
?>

<script type="text/javascript">
$(document).ready(function(){
    $('#btnsubmit1').click(function(){
        var dt = $('#frm_1').serialize();
        $.post('edit_abh2_edit_save.php',{
            type: 1,
            dt: dt
        },
        function(data){
            if(data.success){
                $('#dialogedit').text(data.message);
                $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
                $("#panel").load("edit_abh2_show.php?yy="+ $("#yy").val() +"&mm="+ $("#mm").val() +"&ty="+ $("#ty").val());
            }else{
                alert(data.message);
            }
        },'json');
    });
});
</script>

<?php
$qry_1=pg_query("select * from account.\"AccountBookHead\" WHERE auto_id='$id'");
if($res_1=pg_fetch_array($qry_1)){
    $auto_id = $res_1["auto_id"];
    $acb_date = $res_1["acb_date"];
    $acb_detail = $res_1["acb_detail"];
?>

<div style="font-weight:bold; text-align:right"><?php echo "วันที่ $acb_date"; ?></div>
<form name="frm_1" id="frm_1" action="edit_abh2_edit_save.php" method="post">
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
        $g++;
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
    <td align="center">
    
    <input type="hidden" name="aid<?php echo $g; ?>" id="aid<?php echo $g; ?>" value="<?php echo "$auto_id"; ?>">
    
        <select name="acid<?php echo $g; ?>" id="acid<?php echo $g; ?>">
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
    <td align="right"><input type="text" name="dr<?php echo $g; ?>" id="dr<?php echo $g; ?>" value="<?php echo round($AmtDr,2); ?>" style="text-align:right" size="15"></td>
    <td align="right"><input type="text" name="cr<?php echo $g; ?>" id="cr<?php echo $g; ?>" value="<?php echo round($AmtCr,2); ?>" style="text-align:right" size="15"></td>
</tr>
<?php    
    }
?>
</table>

<div style="margin:5px 0px 5px 0px"><b>Detail:</b><br /><textarea name="detail" id="detail" rows="3" cols="70"><?php echo "$acb_detail"; ?></textarea>

<div align="right" style="padding-top: 5px">
<input type="hidden" name="ct" id="ct" value="<?php echo "$g"; ?>">
<input type="hidden" name="hid" id="hid" value="<?php echo "$id"; ?>">
<input type="button" name="btnsubmit1" id="btnsubmit1" value="บันทึก"></div>

</form>
<?php
    if($qry_num_2 == 0){
        echo "ไม่พบข้อมูล";
    }
}else{
    echo "ผิดผลาด [$id]";
}
?>