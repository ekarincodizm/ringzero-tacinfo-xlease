<?php 
// ถ้า้ $chk_show="true"  แสดงว่า มาจากเมนู เพิ่มหลักทรัพย์, แก้ไขหลักทรัพย์
/*if ($chk_show!="true"){
	$chk_show ="";
}*/

?>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติหลักทรัพย์ประเภทที่ดิน</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>รูปแบบ</td>
				<td align="left">โฉนดที่ดินเลขที่</td>
				<td>เล่มที่</td>
				<td>หน้าที่</td>
				<td>เลขที่ดิน</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td></td>
			</tr>
			<?php
			$qry_fr=pg_query("select * from \"temp_securities\" a
				left join \"Vfuser\" b on a.\"user_add\"=b.\"id_user\"
				where \"statusApp\" = '2' and (\"guaranID\"='1' or \"guaranID\"='3') order by auto_id ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$auto_id=$res_fr["auto_id"];
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$numBook = $res_fr["numBook"]; 
				$numPage = $res_fr["numPage"];
				$numLand = $res_fr["numLand"];
				$user_add = $res_fr["user_add"];
				$stampDateAdd = $res_fr["stampDateAdd"]; 
				$edittime = $res_fr["edittime"];
				$fullname = $res_fr["fullname"];
				
				
				if($edittime=="0"){ //กรณีเป็นการเพิ่มข้อมูล
					$txttype="เพิ่มข้อมูล";
				}else{
					$txttype="แก้ไขข้อมูล";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $txttype; ?></td>
				<td align="left"><?php echo $numDeed; ?></td>
				<td align="left"><?php echo $numBook; ?></td>
				<td><?php echo $numPage; ?></td>
				<td><?php echo $numLand; ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $stampDateAdd; ?></td>
				<td>
					<?php if ($chk_show!="true"){?>
						<span onclick="javascript:popU('showdetail.php?securID=<?php echo $securID; ?>&auto_id=<?php echo $auto_id; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
					<?php } else {?>
						<span onclick="chkshow('<?php echo $securID; ?>','<?php echo $auto_id; ?>')"style="cursor: pointer;"><u>แสดงรายการ</u></span>
					<?php }?>
				</td>
				
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table><br><br>


<!--อนุมัติห้องชุด-->
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติหลักทรัพย์ประเภทห้องชุด</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#F3CC43" align="center">
				<td>รูปแบบ</td>
				<td align="left">โฉนดที่ดินเลขที่</td>
				<td>ห้องชุดเลขที่</td>
				<td>ชั้นที่</td>
				<td>อาคารเลขที่</td>
				<td>ชื่ออาคารชุด</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td></td>
			</tr>
			<?php
			$qry_fr=pg_query("select * from \"temp_securities\" a
				left join \"Vfuser\" b on a.\"user_add\"=b.\"id_user\"
				where \"statusApp\" = '2' and \"guaranID\"='2' order by auto_id ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$auto_id=$res_fr["auto_id"];
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$condoroomnum=$res_fr["condoroomnum"];
				$condofloor = $res_fr["condofloor"]; 
				$condobuildingnum = $res_fr["condobuildingnum"];
				$condobuildingname = $res_fr["condobuildingname"];
				$user_add = $res_fr["user_add"];
				$stampDateAdd = $res_fr["stampDateAdd"]; 
				$edittime = $res_fr["edittime"];
				$fullname = $res_fr["fullname"];
				
				
				if($edittime=="0"){ //กรณีเป็นการเพิ่มข้อมูล
					$txttype="เพิ่มข้อมูล";
				}else{
					$txttype="แก้ไขข้อมูล";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#FEFBF1 align=center>";
				}else{
					echo "<tr bgcolor=#FCF1CD align=center>";
				}
			?>
				<td><?php echo $txttype; ?></td>
				<td align="left"><?php echo $numDeed; ?></td>
				<td align="left"><?php echo $condoroomnum; ?></td>
				<td><?php echo $condofloor; ?></td>
				<td><?php echo $condobuildingnum; ?></td>
				<td align="left"><?php echo $condobuildingname; ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $stampDateAdd; ?></td>
				<td>
					<?php if ($chk_show!="true"){?>
						<span onclick="javascript:popU('showdetailcondo.php?securID=<?php echo $securID; ?>&auto_id=<?php echo $auto_id; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
					<?php } else {?>
						<span onclick="chkshow('<?php echo $securID; ?>','<?php echo $auto_id; ?>')"style="cursor: pointer;"><u>แสดงรายการ</u></span>
					<?php }?>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
<script type="text/javascript">

function chkshow(securID,auto_id){
 $.post('showdetailcondo.php', {securID: securID,auto_id:auto_id,chk_show:'true'},function(d){
   var new_window = window.open('','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=700');
    $(new_window.document.body).append(d);
});
}
</script>
