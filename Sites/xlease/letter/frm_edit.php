<?php
include("../config/config.php");
$auto_id = pg_escape_string($_GET['auto_id']);
?>

<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center" width="80%">
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
		<td width="50">ลำดับที่</td>
		<td>ชื่อประเภทของจดหมาย</td>
		<td width="150">สถานะการใช้งาน</td>
		<td width="150">แก้ไข</td>
	</tr>

<?php
if($auto_id != ""){
	$querytype = pg_query("select * from letter.\"type_letter\" where \"auto_id\" = '$auto_id' order by \"auto_id\" DESC");
}else{
	$querytype = pg_query("select * from letter.\"type_letter\" order by \"auto_id\" DESC");
}
	$num_row = pg_num_rows($querytype);
	$p=1;
	while($res_name=pg_fetch_array($querytype)){
		$auto_id = $res_name["auto_id"];
		$name = $res_name["type_name"];
		$status = $res_name["is_use"];
		if($status == 't'){
			$print_txt = "อนุญาตให้ไช้";
		}else{
			$print_txt = "ไม่อนุญาิตให้ใช้";
		}
        
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }

?>
	<td align="center"><?php echo $p;?></td>
		<td>&nbsp;&nbsp;<?php echo $name;?></td>
		<td align="center"><?php echo $print_txt;?></td>
		<td align="center"><input type="button" value="เลือก" onclick="window.location='edit_type_letter.php?auto_id2=<?php echo $auto_id;?>'"></td>
	</tr>

<?php
$p++;
} //end while

if($num_row == 0){
?>
<tr><td colspan="2" align="center">ไม่พบข้อมูล</td></tr>
<?php }?>
</table>