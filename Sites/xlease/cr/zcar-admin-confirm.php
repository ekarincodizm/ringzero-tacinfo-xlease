<?php
include("../config/config.php");
$id = pg_escape_string($_GET['idaddcar']);
$type = pg_escape_string($_GET['type']);
?>

<script type="text/javascript">
$(document).ready(function(){
    $("#btn11").click(function(){
        $.post('zcar-admin-confirm-send.php',{
            user: $('#amuser').val(),
            pass: $('#ampass').val(),
            sid: '<?php echo $id; ?>',
            type: '<?php echo $type; ?>'
        },
        function(data){
            if(data.success){
                $('#dialog').dialog( "close" );
                mystring = $('#tbsearch').val();
                myarray = mystring.split("|");
                var cregis = encodeURIComponent ( myarray[0] );
                $("#panel").load("zcar-panel.php?regis="+ cregis);
            }else{
                alert(data.message);
            }
        },'json');
    });
});
</script>
<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
    <td>Admin Username</td>
    <td><input type="text" name="amuser" id="amuser" size="20"></td>
</tr>
<tr>
    <td>Admin Password</td>
    <td><input type="password" name="ampass" id="ampass" size="20"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type="button" name="btn11" id="btn11" value="ยืนยัน"></td>
</tr>
</table>

