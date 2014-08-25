<?php
include("../config/config.php");
$car = $_GET['car'];

if(empty($car)){
    exit;
}

	$qry_name=pg_query("select * from \"Fc\" WHERE \"CarID\" ='$car' ");

	$res_name=pg_fetch_array($qry_name);
	$CarID=$res_name["CarID"];
	$C_REGIS = $res_name["C_REGIS"];
    $C_CARNAME=$res_name["C_CARNAME"];
    $C_COLOR=$res_name["C_COLOR"];
    $RadioID=$res_name["RadioID"];
?>
<form name="form1" method="post" action="process_editradio.php">
<hr width="80%" color="#CCCCCC"><br>
<table width="40%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center">
<tr>
	<td width="100" style="font-weight:bold;"align="right">CarID :</td>
    <td bgcolor="#FFFFFF"><?php echo $CarID;?><input type="hidden" name="CarID" value="<?php echo $CarID;?>"></td>
</tr>
<tr>
	<td align="right"style="font-weight:bold;">ทะเบียนรถ :</td>
    <td bgcolor="#FFFFFF"><?php echo $C_REGIS; ?></td>
</tr>

<tr>
	<td align="right"style="font-weight:bold;">ยี่ห้อ :</td>
	<td bgcolor="#FFFFFF"><?php echo $C_CARNAME; ?> <b>สี :</b> <?php echo $C_COLOR;?></td>
</tr>
<tr><td align="right"style="font-weight:bold;">รหัสวิทยุ :</td><td bgcolor="#FFFFFF"><input type="text" name="RadioID" value="<?php echo $RadioID;?>" size="30" <?php if($C_REGIS == "ป้ายแดง"){ echo "disabled";}?>></td></tr>
</table>
<table width="40%" align="center" border="0">
<?php 
if($C_REGIS == "ป้ายแดง"){
	echo "<tr><td align=\"center\"style=\"font-weight:bold;\" ><font color=red size=3>ไม่สามารถบันทึกได้ เนื่องจากยังไม่มีทะเบียนรถ</font></td></tr>
";
}
?>
<tr height="50">
<td align="center"><input type="submit" value="บันทึกรายการ" onclick="return checkdata();" <?php if($C_REGIS == "ป้ายแดง"){ echo "disabled";}?>></td>
</tr>
</table>
</form>