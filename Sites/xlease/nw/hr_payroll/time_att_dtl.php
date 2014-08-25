<?php 
include("../../config/config.php");

include("function_payroll.php");
include("../../core/core_functions.php");
$id_user = $_REQUEST[id_user];


$datepicker = $_GET['datepicker'];

$yy =substr($datepicker,0,4);
$app_date = Date('Y-m-d H:i:s');

?>
<script>
  $(document).ready(function() {
    $("button").button();
/*	
	$("input[name='h_type']").change(function(){
if ($("input[name='h_type']:checked").val() == '0') {
	$("h_show").show();

}
else{
   $("h_show").hide();
}
  });
  */
  
    });
function show_add(id_user){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('time_att_popup.php?cmd=add&id_user='+id_user+'&datepicker=<?php echo $datepicker ?>');
    $('#dialog').dialog({
        title: 'เพิ่มข้อมูลเข้าออกงาน ',
        resizable: false,
        modal: true,  
        width: 600,
        height: 240,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
function show_edit(id,id_user){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('time_att_popup.php?cmd=edit&id='+id+'&id_user='+id_user);
    $('#dialog').dialog({
        title: 'แก้ไขข้อมูลเข้าออกงาน ',
        resizable: false,
        modal: true,  
        width: 600,
        height: 240,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
	function del(id,datetime,type_id,memo){
  

	if(confirm('ยืนยันการลบข้อมูล!!')==true)
		{
		$.post("time_att_api.php",{
			
			
			id :id,
			datetime_old :datetime,
			datetime :datetime,
			type_id_old :type_id,
			memo_old :memo,
			id_user :'<?php echo $id_user ?>',
			cmd :'del'
			
		},
		function(data){
		if(data=='0'){
				alert("ลบรายการเรียบร้อยแล้ว กรุณารอการอนุมัติ");
				$("#panel").load("time_att_dtl.php?datepicker="+ $("#datepicker").val() +"&id_user=<?php echo $id_user ?>");
				 	 }else{
				
				alert("ไม่สามารถทำรายการได้");
			}
			//window.location.reload() ;
		});
		}
	}
  </script><br />
              			<?php


			
			$qry_fr=pg_query("SELECT a.id,a.datetime, b.type_name,a.type_id, a.memo,a.s_p,d.approved1,d.approved2,d.non_app 
  FROM \"LogsTimeAtt$yy\" a left join \"LogsTimeAttType\" b on a.type_id=b.type_id left join \"LogsTimeAttApprove\" d on d.id_att=a.id AND d.cancel ='0' where a.user_id='$id_user' and a.datetime::date = '$datepicker' order by a.datetime ");	
			 
				
			$nub=pg_num_rows($qry_fr);
			if($nub<4){
			?>
  <button onclick="show_add('<?php echo $id_user ?>')"  class="ui-button-text-only ui-state-default ui-corner-all">
   เพิ่มข้อมูล
</button> <?php } ?>
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">วันเวลา</td>
                <td align="center">ช่วงเวลา</td>
          		<td align="center">สถานะ</td>
                <td align="center">หมายเหตุ</td>
				<td align="center">จัดการ</td>
			</tr>
            			<?php

			while($sql_row4=pg_fetch_array($qry_fr)){
					$datetime = $sql_row4['datetime'];
					$type_name = $sql_row4['type_name'];
					$type_id = $sql_row4['type_id'];
					$memo = $sql_row4['memo'];

					$id = $sql_row4['id'];
					$approved1=  $sql_row4["approved1"];
   					$approved2=  $sql_row4["approved2"];
					$non_app=  $sql_row4["non_app"];
					
					$s_p = $sql_row4["s_p"];
					
					
					
					
						$i+=1;
				if($i%2==0){
					$tr = "<tr class=\"odd\" align=center>";
				}else{
					$tr = "<tr class=\"even\" align=center>";
				}
					
					
					if($s_p!=1){
					$status_txt ="ปกติ";
					}else{
						if($approved2){
					$status_txt ="พิเศษ-อนุมัติแล้ว";
					$tr = "<tr bgcolor=#CCFFCC align=center>";
						}else{
						$status_txt = "พิเศษ-รอการอนุมัติ";	
							$tr = "<tr bgcolor=#FF9191 align=center>";
						
						}
						if($non_app){$status_txt = "พิเศษ-ไม่อนุมัติ";	
						$tr = "<tr bgcolor=#FF9191 align=center>";}
					
					
					}
					
					echo $tr ;
					

			
			?>
<td align="center"><?php echo $i; ?></td>
				<td align="center"><?php echo $datetime; ?></td>
                <td align="center"><?php echo  $type_name;?>
                 <td align="center"><?php echo $status_txt; ?></td>
                <td align="left"><?php echo $memo; ?></td>
                <td align="center"><button onclick="show_edit('<?php echo $id ?>','<?php echo $id_user ?>')" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  แก้ไข
</button> <button onclick="del('<?php echo $id ?>','<?php echo $datetime ?>','<?php echo $type_id ?>','<?php echo $memo ?>')"  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
   ลบ
</button> </td>
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=7 align=center ><b>- ไม่มีข้อมูล -</b></td></tr>";
			}
			?>
			</table>