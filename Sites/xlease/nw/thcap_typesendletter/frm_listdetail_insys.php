<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center" width="40%">
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
		<td width="50">ลำดับที่</td>
		<td>ชื่อประเภทของจดหมาย</td>	
		<td>แก้ไข</td>		
		</tr>
		<?php 
		$rootpath = redirect($_SERVER['PHP_SELF'],''); 
		
		$qryspecial=pg_query("SELECT auto_id, \"sendName\",\"ref_temp\" FROM thcap_letter_head  order by  auto_id ");
		$numspec=pg_num_rows($qryspecial);
		while($resspec=pg_fetch_array($qryspecial)){
				$num_temp=0;
				list($auto_id,$sendName,$ref_temp)=$resspec;
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
				<td>&nbsp;&nbsp;<?php echo $sendName;?></td>
				<?php if($num_temp >0){ ?>
					<td align=center>การแก้ไขรอการอนุมัติ</td>
				<?php }else {?>
				<td align=center><img src="<?php echo $rootpath."nw/thcap_typesendletter/images/edit.png"?>" width=16 height=16 style="cursor:pointer;" 
				onclick="javascript:popU('<?php echo $rootpath."nw/thcap_typesendletter/frm_add_type.php?autoid=$auto_id&method=edit"?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')"></td>
				<?php }?>
		</tr>
		<?php
		}//end while
		if($numspec==0){
			echo "<tr bgcolor=#FFFFFF height=30><td colspan=3 align=center><b>ไม่พบรายการ</b></td><tr>";
		}
	?>
</table>