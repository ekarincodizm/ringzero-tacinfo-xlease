<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);

if(empty($idno)){
    exit;
}
?>

<table width="90%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#DDDDDD" align="center">
<tr style="font-weight:bold;" valign="middle" bgcolor="#CCCCCC" align="center">
	<td width="50">IDNO</td>
    <td>ชื่อ/สกุล</td>
    <td>เลขทะเบียนรถ</td>
    <td>รหัสผู้เช่าซื้อ (REF 1)</td>
	<td>รหัสควบคุมเลขที่ (REF 2)</td>
</tr>

<?php

	$qry_name=pg_query("select \"IDNO\",\"full_name\",\"C_REGIS\",\"TranIDRef1\",\"TranIDRef2\" from \"VContact\" WHERE \"IDNO\" ='$idno' ");

	$res_name=pg_fetch_array($qry_name);
    $IDNO=$res_name["IDNO"];
    $name=$res_name["full_name"];
	$c_regis = $res_name["C_REGIS"];
    $ref1 = $res_name["TranIDRef1"];
	$ref2 = $res_name["TranIDRef2"];
?>
<tr bgcolor="#F0F0F0" height="50">
	<td align="center" width="150"><?php echo "$IDNO"; ?></td>
    <td><?php echo "$name"; ?></td>
	<td align="center" ><font color="#0000CC"><b><?php echo "$c_regis"; ?></b></font></td>
    <td align="center" ><font color="#0000CC"><b><?php echo "$ref1"; ?></b></font></td>
    <td align="center" ><font color="#0000CC"><b><?php echo "$ref2"; ?></b></font></td>
</tr>
</table>
<table width="90%" align="center" border="0"><tr height="50"><td colspan="5"><input type="button" onclick="window.open('print_bill_pdf.php?IDNO=<?php echo $IDNO;?>')" value="พิมพ์ Bill Payment"></td></tr></table>