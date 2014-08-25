<?php
include("../../config/config.php");
include("function_payroll.php");
$app_date = Date('Y-m-d H:i:s');
$yy = $_GET['yy'];
$mm = $_GET['mm'];
if($mm=='0')$datetime_s ="$yy-";
else $datetime_s ="$yy-$mm-";
?>


<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}


	function show_p(id){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('att_app_popup.php?id='+id+'&yy=<?php echo $yy ?>');
    $('#dialog').dialog({
        title: 'รายละเอียด '+id,
        resizable: false,
        modal: true,  
        width: 650,
        height: 300,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
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
		<div class="header"><h1>อนุมัติการลงเวลาแบบพิเศษ</h1></div>
		<div class="wrapper">
			<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="9" align="left" style="font-weight:bold;">
                	<?php
			$qry_fr=pg_query("SELECT a.id,a.datetime,a.memo,b.type_name,c.ac,d.approved1,d.approved2 FROM \"LogsTimeAtt$yy\" a left join \"LogsTimeAttType\" b on a.type_id=b.type_id left 
			join \"Vfuser\" c on a.user_id=c.id_user left join \"LogsTimeAttApprove\" d on d.id_att=a.id and d.cancel ='0' WHERE a.s_p ='1' and ( d.approver1_id !='".$_SESSION["av_iduser"]."' or d.approved1 is null ) 
			and d.approved2 is null and d.non_app is null and a.datetime::character varying LIKE '$datetime_s%' order by a.datetime ");
$nub=pg_num_rows($qry_fr);



			echo "จำนวนทั้งหมด $nub รายการ"; ?>
                
                </td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
			
                <td align="center">ชื่อ-นามสกุลพนักงาน</td>
                <td align="center">วันเวลา</td>
				<td align="center">ช่วงเวลา</td>
                <td align="center">ประเภทการร้องขอ</td>
                <td align="center">ผู้ร้องขอ</td>
				<td align="center">หมายเหตุ</td>
                <td align="center">สถานะ</td>
               
				<td>รายละเอียด</td>
			</tr>
			<?php
			
			while($sql_row4=pg_fetch_array($qry_fr)){
				//$cpro_name = $sql_row4['cpro_name'];
					$id = $sql_row4['id'];
					//$user_id = $sql_row4['user_id'];
					$datetime= $sql_row4['datetime'];
					$type_name =$sql_row4['type_name']; 
					$memo =$sql_row4['memo']; 
					$ac = $sql_row4['ac'];
					$approved1=  $sql_row4["approved1"];
   					$approved2=  $sql_row4["approved2"];
					
					//หาประเภทการร้องขอ และผู้ร้องขอ 
		$qry_fr22=pg_query("SELECT transaction_by,transaction_type FROM \"LogsTimeAttMgt\" where datetime_old='$datetime' and id_ref = '$id' order by id desc limit 1");	
			 if($sql_row42=pg_fetch_array($qry_fr22)){
					$transaction_by=  $sql_row42["transaction_by"];
					$transaction_type=  $sql_row42["transaction_type"];
			 }
				/*	$res_profile=pg_query("select approved1,approver1_id,approved2,approver2_id from \"LogsTimeAttApprove\" where id_att='$id'");
   $res_userprofile=pg_fetch_array($res_profile);
   $approved1=  $res_userprofile["approved1"];
   $approved2=  $res_userprofile["approved2"];*/
   
   
    if($approved1=='f' || $approved1=='')$app_text="รอการอนุมัติครั้งที่ 1";
	//else if($approved1=='t' && $approved2=='f' )$app_text="อนุมัติครั้งที่ 1 แล้ว";
	else if($approved1=='t' && $approved2=='')$app_text="รอการอนุมัติครั้งที่ 2";
	//else if($approved1=='t' && $approved2=='t')$app_text="อนุมัติครั้งที่ 2 แล้ว";
	
	if($transaction_type==""){
		$transaction_type_txt = "";
	}else if($transaction_type=="1"){
		$transaction_type_txt = "เพิ่ม";
	}else if($transaction_type=="2"){
		$transaction_type_txt = "แก้ไข";
	}else if($transaction_type=="3"){
		$transaction_type_txt = "ลบ";
	}
	
	if($transaction_by!="")$transaction_req = getUserFName($transaction_by);
	else $transaction_req = "";
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
            <td align="center"><?php echo $i; ?></td>
				
				<td align="left"><?php echo $ac; ?></td>
                <td align="left"><?php echo substr($datetime,0,19); ?></td>
                <td align="left"><?php echo $type_name; ?></td>
                <td align="left"><?php echo $transaction_type_txt; ?></td>
                <td align="left"><?php echo $transaction_req; ?></td>
                <td align="center"><?php echo $memo; ?></td>
      
                <td align="center"><?php echo $app_text; ?></td>
				<td align="center">
					<span onclick="javascript:show_p('<?php echo $id; ?>')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
				</td>
				
			</tr>
			<?php
			$transaction_type_txt ="";
			}
			if($nub == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

