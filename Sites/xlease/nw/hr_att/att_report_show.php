<?php

include("../../config/config.php");

$id_user = $_REQUEST[id_user];

$datepicker = $_GET['datepicker'];
$yy = $_GET['yy'];
$mm = $_GET['mm'];
$ty = $_GET['ty'];

if($ty ==2){				
				$datepicker = $yy."-".$mm."-" ;
			}

?>


<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

</script>

    <style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		
<div class="wrapper">
			<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="9" align="left" style="font-weight:bold;">
                	<?php
			$qry_fr=pg_query("SELECT a.id,a.datetime,a.memo,a.type_id,c.ac,d.approved1,d.approved2,d.non_app,a.img_id FROM \"LogsTimeAtt2012\" a left 
			join \"Vfuser\" c on a.user_id=c.id_user left join \"LogsTimeAttApprove\" d on d.id_att=a.id WHERE a.datetime::character varying LIKE '$datepicker%' and a.user_id='$id_user' order by a.datetime ");
			
			$nub=pg_num_rows($qry_fr); 
			//echo "จำนวนทั้งหมด $nub รายการ"; ?>
                
                </td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
			
                <td align="center">ชื่อ-นามสกุลพนักงาน</td>
                <td align="center">วันที่</td>
				<td align="center">เข้า(เช้า)</td>
				<td align="center">ออก(พักเที่ยง)</td>
                <td align="center">เข้า(บ่าย)</td>
               
				<td>ออก(เย็น)</td>
              <td colspan="2" >ลา</td>
			</tr>
			<?php
			
			while($sql_row4=pg_fetch_array($qry_fr)){
				//$cpro_name = $sql_row4['cpro_name'];
					$id = $sql_row4['id'];
					//$user_id = $sql_row4['user_id'];
					$datetime= $sql_row4['datetime'];
					$type_id =$sql_row4['type_id']; 
					$memo =$sql_row4['memo']; 
					$ac = $sql_row4['ac'];
					$approved1=  $sql_row4["approved1"];
   					$approved2=  $sql_row4["approved2"];
					$non_app=  $sql_row4["non_app"];
					$img_id=  $sql_row4["img_id"];
					$date = substr($datetime,0,10);
					
					if($img_id=="")//อนุมัติการลงเวลาแบบพิเศษแล้ว
					$time = substr($datetime,11,8);
					else{
						if($approved2)
					$time = substr($datetime,11,8);
					    else{
						$time = "รอการอนุมัติ";	
							
							
						}
						if($non_app)$time = "ไม่อนุมัติ";	
						
					
					
					}
					
						if($type_id==1){$dt_1 = $time;$memo1 = $memo;}
						else if($type_id==2){$dt_2 = $time;$memo2 = $memo;}
						else if($type_id==3){$dt_3 = $time;$memo3 = $memo;}
						else if($type_id==4){$dt_4 = $time;$memo4 = $memo;}
						else {
							$qry_fr2=pg_query("SELECT type_name FROM \"LogsTimeAttType\" where type_id = '$type_id' ");
			
			if($sql_row5=pg_fetch_array($qry_fr2)){
				//$cpro_name = $sql_row4['cpro_name'];
					$type_name = $sql_row5['type_name'];
							$dt_51 = $type_name;
							$dt_52 = $time;
							$memo5 = $memo;
			}
							
						}
					

	
	if($date!=$date_old){
		$date_old = $date;
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
            <td align="center"><?php echo $i; ?></td>
				
				<td align="left"><?php echo $ac; ?></td>
                <td align="center"><?php echo $date; ?></td>
                <td <?php if($memo1!=""){?> bgcolor="#CCFFCC" title="<?php echo " $dt_1 (" .$memo1 ?>)" <?php } ?> align="center"><?php echo $dt_1 ?></td>
                <td <?php if($memo2!=""){?> bgcolor="#CCFFCC" title="<?php echo " $dt_2 (" .$memo2 ?>)" <?php } ?> align="center"><?php echo $dt_2 ?></td>
              <td <?php if($memo3!=""){?> bgcolor="#CCFFCC" title="<?php echo " $dt_3 (" .$memo3 ?>)" <?php } ?> align="center"><?php echo $dt_3 ?></td>
			  <td <?php if($memo4!=""){?> bgcolor="#CCFFCC" title="<?php echo " $dt_4 (" .$memo4 ?>)" <?php } ?> align="center"><?php echo $dt_4 ?></td>
              <td <?php if($memo5!=""){?> bgcolor="#CCFFCC" title="<?php echo " $dt_51 $dt_52 (" .$memo5 ?>)" <?php } ?> align="left"><?php echo $dt_51 ?></td><td <?php if($memo5!=""){?> bgcolor="#CCFFCC" title="<?php echo " $dt_51 $dt_52 (" .$memo5 ?>)" <?php } ?>  align="left"><?php echo $dt_52 ?></td>
				
			</tr>
			<?php
			
			$dt_1="";$memo1="";
			$dt_2="";$memo2="";
			$dt_3="";$memo3="";
			$dt_4="";$memo4="";
			$dt_5="";$memo5="";
			}
			}
			if($nub == 0){
				echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table><br>
           <div align="right"><strong> หมายเหตุ </strong>พื้นหลัง<font color=green><strong>สีเขียว</strong></font>คือ มีการลงเวลาแบบ<font color=red><strong>พิเศษ</strong></font> เมื่อเอาเมาซ์ไปวางที่ข้อความจะปรากฏเหตุผลที่ลงเวลาแบบพิเศษขึ้น</div>
	  </div>
	</td>
</tr>
</table>

