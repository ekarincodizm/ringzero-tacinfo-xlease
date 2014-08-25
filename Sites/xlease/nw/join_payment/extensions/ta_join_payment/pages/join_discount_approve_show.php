<?php require_once("../../sys_setup.php");
include("../../../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
} ?>

    <style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>


			<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">เลขที่สัญญา</td>
                <td align="center">ทะเบียนรถ</td>
            <td align="center">ชื่อลูกค้า</td>
                <td align="center">จำนวนเงิน</td>
				<td align="center">ผู้ขออนุมัติ</td>
				<td align="center">วันเวลาที่ขอ</td>
                <td align="center">เหตุผล</td>
               
				<td>สถานะ</td>
			</tr>
			<?php
			$qry_fr=pg_query("SELECT m.car_license,f.\"O_RECEIPT\",f.\"O_MONEY\",cpro_name,m.idno,f.approve_status,f.create_by,f.create_datetime,f.\"O_memo\" FROM \"FOtherpayDiscount\" f left join \"VJoinMain\" m on m.idno = f.\"IDNO\" WHERE m.deleted ='0' and m.car_license_seq = 0
			 and f.approve_status!=1 order by f.approve_status , f.create_datetime ");
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
				$cpro_name = $sql_row4['cpro_name'];
					$O_RECEIPT = $sql_row4['O_RECEIPT'];
					$car_license = $sql_row4['car_license'];
					$create_datetime =$sql_row4['create_datetime']; 
					$reason =$sql_row4['O_memo']; 
					$approve_status = $sql_row4['approve_status'];
					$O_MONEY =$sql_row4['O_MONEY']; 
					$create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
					$idno = trim($sql_row4['idno']);
		

		$dt = $create_datetime;
			$by = $create_by;
		if($approve_status==0)$st="รอการอนุมัติ";else $st="ไม่อนุมัติ";
		
		
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
				<td><?php echo $idno; ?></td>
				<td align="left"><?php echo $car_license; ?></td>
				<td align="left"><?php echo $cpro_name; ?></td>
                <td align="right"><?php echo number_format($O_MONEY); ?></td>
                <td align="left"><?php echo $by; ?></td>
                <td align="center"><?php echo $dt; ?></td>
      
                <td align="left"><?php echo $reason; ?></td>
				<td align="center"><?php echo $st; ?>
               

				</td>
				
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>


