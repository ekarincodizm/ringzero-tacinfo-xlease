<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$_SESSION['check_admin_confirm'] = "";

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");//always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
?>

<script type="text/javascript">
$(document).ready(function(){
    $("#btn11").click(function(){
        $.post('con_popup_check.php',{
            user: $('#amuser').val(),
            pass: $('#ampass').val(),
            sid: '<?php echo $id; ?>'
        },
        function(data){
            if(data.success){
                //alert(data.message);
                $('#alertshow'+ <?php echo $id; ?>).attr("style", "display:; color:green");
                $('#alertshow'+ <?php echo $id; ?>).text('อนุมัติแล้วโดย '+$('#amuser').val());
                $('#alertshow'+ <?php echo $id; ?>).show();
                $('#btncf'+ <?php echo $id; ?>).hide();
                $('#submitchkconfirm'+ <?php echo $id; ?>).val('1');
                $("#dialog2").dialog("close");
            }else{
                alert(data.message);
				document.getElementById("amuser").value = '';
				document.getElementById("ampass").value = '';
            }
        },'json');
    });
});

function check_num_or_textEng(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและตัวอักษรภาษาอังกฤษเท่านั้น
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key >= 48 && key <= 57 || key >= 65 && key <= 90 || key >= 97 && key <= 122 || key == 8 || key == 0 || key == 46) //48-57(ตัวเลข) ,65-90(Eng ตัวพิมพ์ใหญ่ ) ,97-122(Eng ตัวพิมพ์เล็ก)
		{
			// ถ้าเป็นตัวเลขหรือจุดหรือตัวอักษรภาษาอังกฤษสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox
		if(key >= 48 && key <= 57 || key >= 65 && key <= 90 || key >= 97 && key <= 122 || key == 8 || key == 0 || key == 46) //48-57(ตัวเลข) ,65-90(Eng ตัวพิมพ์ใหญ่ ) ,97-122(Eng ตัวพิมพ์เล็ก)
		{
			// ถ้าเป็นตัวเลขหรือจุดหรือตัวอักษรภาษาอังกฤษสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
}
</script>

<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
    <td>Admin Username</td>
    <td><input type="text" name="amuser" id="amuser" size="20" onKeyPress="check_num_or_textEng(event);"></td>
</tr>
<tr>
    <td>Admin Password</td>
    <td><input type="password" name="ampass" id="ampass" size="20" onKeyPress="check_num_or_textEng(event);"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type="button" name="btn11" id="btn11" value="ยืนยัน"></td>
</tr>
</table>