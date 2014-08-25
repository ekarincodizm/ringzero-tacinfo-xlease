<?php
include("../config/config.php");
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
    var myarray;
    var mystring;
            
    $("#gj_id").focus();
    
    $("#gj_id").autocomplete({
        source: "edit_abh_search_gj.php",
        minLength:1,
        close: function(event, ui){
            $("#divshow").empty();
            mystring = $('#gj_id').val();
            myarray = mystring.split("#");
            $("#divshow").load("edit_abh_panel.php?gj_id="+ myarray[0] );
        }
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

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>แก้ไขสมุดบัญชี</B></legend>

<div class="ui-widget">

<div><b>ระบุ GJ ID</b>&nbsp;<input type="text" id="gj_id" name="gj_id" size="70"></div>

<div id="divshow" style="padding: 5px 5px 5px 5px"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>