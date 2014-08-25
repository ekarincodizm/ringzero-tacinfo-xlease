<?php		
			$qry_fr=pg_query("SELECT f.id,f.\"amount\",f.pay_date,f.approve_status,f.create_by,f.create_datetime,f.\"memo\",m.car_license,m.cpro_name,m.idno,m.id as tt FROM \"ta_join_add_money_app\" f left join \"VJoinMain\" m on m.id = f.\"id_main\" 
			WHERE f.approve_status=0 order by f.create_datetime ");
			$nub=pg_num_rows($qry_fr);




			echo "จำนวนทั้งหมด $nub รายการ"; ?>
			<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">

			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">เลขที่สัญญา</td>
                <td align="center">เลขทะเบียนรถ</td>
                <td align="center">ชื่อลูกค้า</td>
                <td align="center">วันที่ชำระ</td>
                <td align="center">จำนวนเงิน</td>
				<td align="center">ผู้ขออนุมัติ</td>
				<td align="center">วันเวลาที่ขอ</td>
				<td>รายละเอียด</td>
			</tr>
			<?php

			while($sql_row4=pg_fetch_array($qry_fr)){
				$id = $sql_row4['id'];
				$tt = $sql_row4['tt'];
				$cpro_name = $sql_row4['cpro_name'];
				$pay_date = $sql_row4['pay_date'];
				$car_license = $sql_row4['car_license'];
				$create_datetime =$sql_row4['create_datetime']; 
				$reason =$sql_row4['memo']; 
				$approve_status = $sql_row4['approve_status'];
				$O_MONEY =$sql_row4['amount']; 
				$create_by = $sql_row4['create_by'];
				$idno = trim($sql_row4['idno']);

				$dt = $create_datetime;
				$by = $create_by;
		
				$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
				$res_userprofile=pg_fetch_array($res_profile);
				$by=  $by."-".$res_userprofile["fullname"];

				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				?>
				<td align="center"><?php echo $i; ?></td>
				<td><u><a href="javascript:popU('ta_join_payment_view_new.php?id=<?php print $tt ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $idno ?></a></u></td>
				<td align="left"><?php echo $car_license; ?></td>
				<td align="left"><?php echo $cpro_name; ?></td>
                <td align="center"><?php echo $pay_date; ?></td>
                <td align="right"><?php echo number_format($O_MONEY); ?></td>
                <td align="left"><?php echo $by; ?></td>
                <td align="center"><?php echo $dt; ?></td>
				<td align="center"><img src="../images/detail.gif" style="cursor:pointer" onclick="javascript:show_p('<?php echo $id; ?>','')" height=\"19\" width=\"19\" border=\"0\"></td>
				</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=9 align=center ><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>