<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ค้นหาลูกค้าที่ซ้ำ โดยกรองจาก เลขที่บัตร</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#btn00').click(function(){
        $("#btn00").attr('disabled', true);
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        //$("#panel").load("panel-cash-day-radio-all.php?datepicker="+ $("#datepicker").val() );
		$("#panel").load("CardSearchCustomer.php");
        $("#btn00").attr('disabled', false);
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

.longtext{overflow:scroll;}
</style>
    
</head>
<body id="mm">

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<?php

$sql_migrate=pg_query("select distinct \"N_IDCARD\" , \"CountRows\" as total
	from
	(
	SELECT COUNT('N_IDCARD') AS \"CountRows\", \"N_IDCARD\"
	FROM \"Fn\"
	GROUP BY \"N_IDCARD\"
	order by \"CountRows\"
	) as test
	where \"CountRows\" > '1'
	order by \"N_IDCARD\" ");
	
$row_nigrate=pg_num_rows($sql_migrate);

?>

<fieldset><legend><B>ค้นหาลูกค้าที่ซ้ำ โดยกรองจาก เลขที่บัตร (เหลือลูกค้าที่ซ้ำกันอีกประมาณ <?php echo $row_nigrate ?> คน)</B></legend>

<div align="center">

<div class="ui-widget">

<p align="center">

<input type="button" id="btn00" value="เริ่มค้น"/></p>

<div id="panel"></div>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>