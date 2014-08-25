<?php
include("../../config/config.php");
$auto_id = pg_escape_string($_GET['auto_id']);
$rootpath = redirect($_SERVER['PHP_SELF'],''); 
?>

<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center" width="40%">
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
		<td width="50">ลำดับที่</td>
		<td>ชื่อประเภทของจดหมาย</td>
		<td>แก้ไข</td>			
	</tr>

<?php
$chk_auto_id=is_numeric($auto_id);
if($chk_auto_id){
	if($auto_id != ""){
		$querytype = pg_query("select \"auto_id\",\"sendName\",\"ref_temp\" from \"thcap_letter_head\" where \"auto_id\" = '$auto_id' order by \"auto_id\" DESC");
	
	}else{
		$querytype = pg_query("select \"auto_id\",\"sendName\",\"ref_temp\" from \"thcap_letter_head\" order by \"auto_id\" DESC");
	}

	$num_row = pg_num_rows($querytype);
	
	while($res_name=pg_fetch_array($querytype)){
		$name = $res_name["sendName"];
		$auto_id = $res_name["auto_id"];
		$ref_temp = $res_name["ref_temp"];
		
		if($ref_temp!=''){
			$qry_status_temp=pg_query("SELECT auto_id FROM thcap_letter_head_temp where \"auto_id\"='$ref_temp' and \"status\"='9'");
			$num_temp=pg_num_rows($qry_status_temp);
		}				
		$i+=1;
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
?>
	<td align="center"><?php echo $i;?></td>
		<td>&nbsp;&nbsp;<?php echo $name;?></td>
		<?php if($num_temp >0){ ?>
			<td align=center>การแก้ไขรอการอนุมัติ</td>
		<?php }else {?>
				<td align=center><img src="<?php echo $rootpath."nw/thcap_typesendletter/images/edit.png"?>" width=16 height=16 style="cursor:pointer;" 
				onclick="javascript:popU('<?php echo $rootpath."nw/thcap_typesendletter/frm_add_type.php?autoid=$auto_id&method=edit"?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')"></td>
				<?php }?>
	</tr>

<?php

} //end while
}
if($num_row == 0){
?>
<tr><td colspan="2" align="center">ไม่พบข้อมูล</td></tr>
<?php }?>
</table>