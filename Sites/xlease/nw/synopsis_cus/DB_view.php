<?php
include('../../config/config.php');
 list($Cusid,$name,$idcard) = explode("#",$_POST['sname']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ข้อมูลสัญญาทั้งหมดของลูกค้า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body bgcolor="#DDDDDD" >


<table width="98%" cellspacing="0" frame="border" cellpadding="0" align="center" >
	<tr bgcolor="#B0E2FF">
		<td height="25px" width="50%">
			<font size="5"> รหัสลูกค้า : <?php echo $Cusid; ?></font>
		</td>
		<td height="25px" width="50%" align="right">
			<font size="5"> ชื่อ-นามสกุล : <?php echo $name; ?></font>
		</td>
	</tr>
	<tr bgcolor="#5CACEE" height="10px"><td colspan="2"></td></tr>
	<tr bgcolor="#36648B" height="5px"><td colspan="2"></td></tr>		
</table>
		
<table width="98%" border="2" cellspacing="0" bordercolor="" cellpadding="2" align="center" >		
	<tr bgcolor="#8DB6CD">
        <td colspan="2" align="center">
			<font size="4">สรุปข้อมูลสัญญาทั้งหมดของลูกค้า</font>
        </td>
    </tr>
    <tr valign="top" bgcolor="#FFFFFF">
        <td colspan="2" height="300px" valign="top" align="center" >
			<?php include('table_blog1.php');  ?>
        </td>
    </tr>
	<tr bgcolor="#8DB6CD">
        <td valign="middle" align="center">
			<font size="4">Blog header 2</font>
        </td>
		<td valign="middle" align="center">
			<font size="4">blog header 3</font>
        </td>
    </tr>
	<tr bgcolor="#FFFFFF">
		<td height="300px" valign="middle">
			<center>....blog 2</center>
		</td>
		<td height="300px" valign="middle">
			<center>....blog 3</center>
		</td>
	</tr>
</table>

</body>
</html>
 