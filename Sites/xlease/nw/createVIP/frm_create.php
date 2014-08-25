<?php
include("../../config/config.php");
$idno = $_GET['idno'];

if(empty($idno)){
    exit;
}

	$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\" ='$idno' ");
	$res_name=pg_fetch_array($qry_name);
	$CusID = $res_name["CusID"];
    $IDNO=$res_name["IDNO"];
    $name=$res_name["full_name"];
	$c_regis = $res_name["C_REGIS"];
	
	$qry_create=pg_query("select * from \"nw_createVIP\" where \"IDNO\" = '$IDNO'");
	$numrows=pg_num_rows($qry_create);
   
?>
<form name="form1" method="post" action="process_create.php">
<hr width="80%" color="#CCCCCC"><br>
<table width="80%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center">
<tr>
	<td style="font-weight:bold;"align="right">เลขที่สัญญา :</td>
    <td bgcolor="#FFFFFF"><?php echo $IDNO;?><input type="hidden" name="IDNO" value="<?php echo $IDNO;?>"></td>
	<td align="right"style="font-weight:bold;">ชื่อผู้เช่าซื้อ:</td>
    <td bgcolor="#FFFFFF"><?php echo $name; ?> (<?php echo $CusID;?>)</td>
</tr>
<tr>	
	<td align="right"style="font-weight:bold;">ทะเบียนรถ :</td>
    <td bgcolor="#FFFFFF"><?php echo $c_regis; ?></td>
	<td align="right"style="font-weight:bold;">สถานะ :</td>
    <td bgcolor="#FFFFFF"><font color="red"><b>
		<?php 
		if($numrows == 0){
			echo "ธรรมดา";
		}else{
			echo "VIP";
		}
		?>
		</b></font>
	</td>
</tr>
<tr>
	<td bgcolor="#FFFFFF" align="center" colspan="4" height="50">
		<input type="submit" value="เพิ่มรายการ" <?php if($numrows > 0) echo "disabled";?>>
		<input type="submit" value="ลบรายการ" <?php if($numrows == 0) echo "disabled";?>>
		<?php 
			if($numrows==0){
				$method="add";
			}else{
				$method="delete";
			}
		?>
		<input type="hidden" name="method" value="<?php echo $method;?>">
	</td>
</tr>
</table>
</form>