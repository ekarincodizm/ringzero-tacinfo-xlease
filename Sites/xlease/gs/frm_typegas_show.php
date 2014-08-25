<?php 
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

<table width="1200" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div align="right"><a href="frm_typegas_add.php"><img src="add.png" border="0" width="16" height="16" align="absmiddle"> เพิ่มรายการใหม่</a></div>
<fieldset><legend><B>บริษัท Gas</B></legend>


<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">รหัสบริษัท</td>
        <td align="center">ชื่อบริษัท</td>
        <td align="center">รุ่น</td>
        <td align="center">ราคาต้นทุน</td>
        <td align="center">ราคาขายเฉพาะถัง</td>
        <td align="center">ราคาขายเฉพาะอุปกรณ์</td>
        <td align="center">ที่อยู่</td>
        <td align="center">เบอร์โทร</td>
        <td>Edit</td>
    </tr>
   
<?php
$qry_name=pg_query("SELECT * FROM \"GasCompany\" ORDER BY \"coid\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $id = $res_name["coid"];
    $name = $res_name["coname"];
    $model = $res_name["model"];  
    $cost = $res_name["cocost"];
    $price_tank = $res_name["price_tank"];
    $price_device = $res_name["price_device"];
    $address = $res_name["address"];
    $phone = $res_name["phone"];

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo "$id"; ?></td>
        <td align="left"><?php echo "$name"; ?></td>
        <td align="left"><?php echo "$model"; ?></td>
        <td align="right"><?php echo number_format("$cost",2); ?></td>
        <td align="right"><?php echo number_format("$price_tank",2); ?></td>
        <td align="right"><?php echo number_format("$price_device",2); ?></td>
        <td align="left"><?php echo $address; ?></td>
        <td align="left"><?php echo $phone; ?></td>
        <td align="center"><a href="frm_typegas_edit.php?id=<?php echo "$id";?>&model=<?php echo "$model";?>"><img src="edit.png" border="0" width="16" height="16" align="absmiddle"></a></td>
    </tr>
 <?php
        }
?>
</table>

</fieldset>

<div align="center"><br><input type="button" value="กลับหน้าหลัก" onclick="location.href='../list_menu.php'"></div>

        </td>
    </tr>
</table>

</body>
</html>