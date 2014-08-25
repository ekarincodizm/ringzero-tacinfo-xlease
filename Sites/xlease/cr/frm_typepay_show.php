<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
	</tr>
	<tr>
		<td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบ TypePay</h1></div>
<div class="wrapper">
 <div align="right"><a href="frm_typepay_add.php"><img src="add.png" border="0" width="16" height="16" align="absmiddle"> เพิ่มรายการใหม่</a></div>
<fieldset><legend><b>แสดงรายการ</b></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">TypeID</td>
        <td align="center">TName</td>
        <td align="center">UseVat</td>
        <td align="center">ประเภทใบเสร็จ</td>
        <td align="center">ฝ่ายที่แสดง</td>
        <td>Edit</td>
    </tr>
   
<?php
$qry_name=pg_query("SELECT * FROM \"TypePay\" ORDER BY \"TypeID\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $TypeID = $res_name["TypeID"];
    $TName = $res_name["TName"];
    $UseVat = $res_name["UseVat"];  
    $TypeRec = $res_name["TypeRec"];
    $TypePay = $res_name["TypeDep"];

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo "$TypeID"; ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="center"><?php echo "$UseVat"; ?></td>
        <td align="center"><?php echo "$TypeRec"; ?></td>
        <td align="center"><?php echo "$TypePay"; ?></td>
        <td align="center"><a href="frm_typepay_edit.php?id=<?php echo "$TypeID";?>"><img src="edit.png" border="0" width="16" height="16" align="absmiddle"></a></td>
    </tr>
 <?php
        }
?>
</table>

</div>

<div align="center"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>

</body>
</html>