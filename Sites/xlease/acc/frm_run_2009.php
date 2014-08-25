<?php 
include("../config/config.php");
$accdate = nowDate();//ดึง วันที่จาก server
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#btnsubmit').click(function(){
        $("#btnsubmit").attr('disabled', true);
        $("#divshow").empty();
        $("#divshow").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');

        $.post('frm_run_2009_submit.php',{
            datepicker: $('#datepicker').val()
        },
        function(data){
            if(data.success){
                $("#divshow").html(data.message);
                $("#btnsubmit").attr('disabled', false);
            }else{
                $("#divshow").html(data.message);
                $("#btnsubmit").attr('disabled', false);
            }
        },'json');
    });

    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>

    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div>
 
<div style="float:left">
<input name="button" type="button" onclick="window.location='frm_year_2009.php'" value="รับรู้ปี 2009 soy">
<input name="button" type="button" onclick="window.location='frm_run_2009.php'" value="Run รับรู้ปี 2009 soy" disabled>
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>
 
<fieldset><legend><b>Run รับรู้ปี 2009 soy</b></legend>

<div style="margin:5px">
<b>เลือกวันที่ Run รับรู้รายได้</b>&nbsp;<input type="text" id="datepicker" name="datepicker" value="<?php echo $accdate; ?>" size="15">&nbsp;<input type="button" name="btnsubmit" id="btnsubmit" value="ประมวลผล">
</div>
<div style="clear:both;"></div>

<div id="divshow" style="margin:5px"></div>

</fieldset>

</div> 

        </td>
    </tr>
</table>

</body>
</html>