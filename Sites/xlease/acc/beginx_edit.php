<?php
include("../config/config.php");

$idno = pg_escape_string($_GET['id']);
$mm = pg_escape_string($_GET['mm']);
$yy = pg_escape_string($_GET['yy']);
$m = pg_escape_string($_GET['m']);
?>

<script type="text/javascript">
$(document).ready(function(){
    $('#btnsave').click(function(){
        $.post('beginx_save.php',{
            beginx: $('#txtbeginx').val(),
            idno: '<?php echo $idno; ?>'
        },
        function(data){
            if(data.success){
                $("#panel").load("beginx_panel.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>");
                $('#dialog').dialog("close");
            }else{
                alert(data.message);
            }
        },'json');
    });
});
</script>

<div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3">
<tr>
    <td><b>IDNO :</b></td><td><b><?php echo $idno; ?></b></td>
</tr>
<tr>
    <td>ต้นทุนทางบัญชี : </td><td><input type="text" name="txtbeginx" id="txtbeginx" size="15" value="<?php echo $m; ?>"></td>
</tr>
<tr>
    <td></td>
    <td><input type="button" name="btnsave" id="btnsave" value="แก้ไข"></td>
</tr>
</table>
</div>