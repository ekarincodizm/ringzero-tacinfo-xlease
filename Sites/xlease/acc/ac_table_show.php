<?php 
session_start();
include("../config/config.php");
$yy = pg_escape_string($_POST['yy']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  

<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>    
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="wrapper">
 
<fieldset><legend><b>แสดงเลขที่บัญชี</b></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">AcID</td>
        <td align="center">AcName</td>
        <td align="center">AcType</td>
        <td align="center">Status</td>
        <td align="center">Delable</td>
        <td align="center">ShowOnFS</td>
        <td align="center"></td>
    </tr>
    
<?php
$qry_name=pg_query("SELECT * FROM account.\"AcTable\" ORDER BY \"AcID\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $AcName = $res_name["AcName"];
    $AcType = $res_name["AcType"];
    $Status = $res_name["Status"];
    $Delable = $res_name["Delable"];
    $ShowOnFS = $res_name["ShowOnFS"];

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center"><?php echo "$AcID"; ?></a></td>
    <td align="left"><?php echo "$AcName"; ?></td>
    <td align="center"><?php echo "$AcType"; ?></td>
    <td align="center"><?php echo "$Status"; ?></td>
    <td align="center"><?php echo "$Delable"; ?></td>
    <td align="center"><?php echo "$ShowOnFS"; ?></td>
    <td align="center"><a href="#" onclick="javascript:popU('ac_table_edit.php?id=<?php echo "$AcID"; ?>','<?php echo "actable_edit"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=400')">แก้ไข</a></td>
</tr>

<?php
}
?>
    
</table>


</div>
		</td>
	</tr>
</table>

</body>
</html>