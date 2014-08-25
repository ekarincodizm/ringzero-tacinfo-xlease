<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$type = pg_escape_string($_GET['type']);
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

<script type="text/javascript">
$(document).ready(function(){
    $('#passwd').focus();
    $('#btn1').click(function(){
        $.post('frm_recid_check.php',{
            passwd: $('#passwd').val()
        },
        function(data){
            if(data.success){
                document.location='frm_recprint_<?php if($type == "vat"){ echo "vat_"; } echo $_SESSION['session_company_code']; ?>.php?id=<?php echo $id; ?>';
            }else{
                $('#passwd').val('');
                $('#passwd').focus();
                alert(data.message);
            }
        },'json');
    });
});
</script>

</head>
<body>

<div style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:center; line-height: 25px">
<b>กรุณากรอกรหัสผ่านของท่าน เพื่อยืนยันการพิมพ์</b><br />
<input type="password" name="passwd" id="passwd" size="20"><br />
<input type="button" name="btn1" id="btn1" value="ยืนยันการพิมพ์">
</div>

</body>
</html>