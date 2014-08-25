<?php
include("../config/config.php");
$idno = $_GET['idno'];

if(!Empty($idno)){

$qry_name=@pg_query("select * from \"VContact\" WHERE \"CusID\"='$idno'");
$numrows = @pg_num_rows($qry_name);
if($res_name=@pg_fetch_array($qry_name)){
    $CusID=$res_name["CusID"];
    
    $qry_name=@pg_query("select * from \"Fa1\" WHERE \"CusID\"='$CusID'");
    if($res_name=@pg_fetch_array($qry_name)){
        $A_NAME=$res_name["A_NAME"];
        $A_SIRNAME=$res_name["A_SIRNAME"];
        $A_PAIR=$res_name["A_PAIR"];
        $A_NO=$res_name["A_NO"];
        $A_SUBNO=$res_name["A_SUBNO"];
        $A_SOI=$res_name["A_SOI"];
        $A_RD=$res_name["A_RD"];
        $A_TUM=$res_name["A_TUM"];
        $A_AUM=$res_name["A_AUM"];
        $A_PRO=$res_name["A_PRO"];
        $A_POST=$res_name["A_POST"];
    }
    
?>

<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td><b>IDNO</b></td><td colspan="3"><?php echo "$idno"; ?></td>
</tr>
<tr align="left">
    <td width="20%"><b>ชื่อ</b></td><td width="30%"><?php echo "$A_NAME"; ?></td>
    <td width="20%"><b>สกุล</b></td><td width="30%"><?php echo "$A_SIRNAME"; ?></td>
</tr>
<tr align="left">
    <td><b>ที่อยู่</b></td><td colspan="3"><?php echo "$A_NO $A_SUBNO ซอย $A_SOI ถนน $A_RD ตำบล $A_TUM อำเภอ $A_AUM"; ?></td>
</tr>
<tr align="left">
    <td><b>จังหวัด</b></td><td><?php echo "$A_PRO"; ?></td>
    <td><b>รหัสไปรษณีย์</b></td><td><?php echo "$A_POST"; ?></td>
</tr>
</table>
<?php
}else{
?>
<!--
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td width="20%"><b>คำนำหน้าชื่อ</b></td>
    <td width="30%" colspan="3"><input id="fnames" name="fnames" size="10" /></td>
</tr>
<tr align="left">
    <td width="20%"><b>ชื่อ</b></td>
    <td width="30%"><input id="anames" name="anames" size="20" /></td>
    <td width="20%"><b>สกุล</b></td>
    <td width="30%"><input id="snames" name="snames" size="20" /></td>
</tr>
<tr align="left">
    <td><b>บ้านเลขที่</b></td>
    <td><input id="no" name="no" size="10" /></td>
    <td><b>SUBNO</b></td>
    <td><input id="sno" name="sno" size="10" /></td>
</tr>
<tr align="left">
    <td><b>ซอย</b></td>
    <td><input id="soi" name="soi" size="10" /></td>
    <td><b>ถนน</b></td>
    <td><input id="rd" name="rd" size="10" /></td>
</tr>
<tr align="left">
    <td><b>ตำบล</b></td>
    <td><input id="tam" name="tam" size="10" /></td>
    <td><b>อำเภอ</b></td>
    <td><input id="aum" name="aum" size="10" /></td>
</tr>
<tr align="left">
    <td><b>จังหวัด</b></td>
    <td><input id="pro" name="pro" size="10" /></td>
    <td><b>รหัสไปรษณีย์</b></td>
    <td><input id="post" name="post" size="10" /></td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFCA">
<tr align="left">
    <td width="20%"><b>สัญชาิติ</b></td>
    <td width="30%"><input id="san" name="san" size="20" /></td>
    <td width="20%"><b>อายุ</b></td>
    <td width="30%"><input id="age" name="age" size="10" /></td>
</tr>
<tr align="left">
    <td><b>บัตร</b></td>
    <td><input id="card" name="card" size="20" /></td>
    <td><b>หมายเลขบัตร</b></td>
    <td><input id="idcard" name="idcard" size="20" /></td>
</tr>
<tr align="left">
    <td><b>วันที่ออกบัตร</b></td>
    <td><input type="text" id="otdate" name="otdate" value="<?php echo nowDate(); ?>"></td>
    <td><b>สถานที่ออกบัตร</b></td>
    <td><input id="by" name="by" size="20" /></td>
</tr>
<tr align="left">
    <td><b>อาชีพ</b></td>
    <td colspan="3"><input id="occ" name="occ" size="20" /></td>
</tr>
<tr align="left">
    <td><b>ที่ติดต่อได้</b></td>
    <td colspan="3"><textarea id="contact" name="contact" rows="4" cols="50"></textarea></td>
</tr>
</table>
-->
<?php
}
}
?>