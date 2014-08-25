<?php 
include("../../config/config.php");

include("function_payroll.php");
include("../../core/core_functions.php");
$id_user = $_REQUEST[id_user];

$yy = $_GET['yy'];
$mm = $_GET['mm'];

//$yy = '2012';
//$mm = '01';



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
function show_add(){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('holiday_popup.php?cmd=add&yy='+$("#yy").val());
    $('#dialog').dialog({
        title: 'เพิ่มวันหยุดประจำปี ',
        resizable: false,
        modal: true,  
        width: 550,
        height: 190,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
function show_edit(id,id_user){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('holiday_popup.php?cmd=edit&id='+id);
    $('#dialog').dialog({
        title: 'แก้ไขวันหยุดประจำปี ',
        resizable: false,
        modal: true,  
        width: 550,
        height: 190,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
	function del(id){
  

	if(confirm('ยืนยันการลบข้อมูล!!')==true)
		{
		$.post("holiday_api.php",{
			
			
			id :id,
			cmd :'del'
			
		},
		function(data){
		
				alert("ลบรายการเรียบร้อยแล้ว!!");
				$("#panel").load("holiday_dtl.php?yy="+ $("#yy").val());
				 
			//window.location.reload() ;
		});
		}
	}
  </script><br />
  <button onclick="show_add()"  class="ui-button-text-only ui-state-default ui-corner-all">
   เพิ่มวันหยุด
</button> 
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">วันหยุดประจำปี</td>
                <td align="center">รายละเอียด</td>
          
                <td align="center">จัดการ</td>
			</tr>
            			<?php

				
			$qry_fr=pg_query("SELECT pub_id,pub_holiday, \"desc\" FROM hr_public_holiday where pub_holiday::character varying LIKE '$yy-%' order by pub_holiday ");	
			 

				
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
					$pub_holiday = $sql_row4['pub_holiday'];
					$desc = $sql_row4['desc'];					
					$id = $sql_row4['pub_id'];
					

				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
<td align="center"><?php echo $i; ?></td>
				<td align="center"><?php echo $pub_holiday; ?></td>
                <td align="left"><?php echo  $desc; ?></td>
                  
                
     
					<td align="center"><button onclick="show_edit('<?php echo $id ?>')" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
				    แก้ไข
</button> <button onclick="del('<?php echo $id ?>')"  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
    ลบ
</button> </td>
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=4 align=center ><b>- ไม่มีวันหยุดประจำปี -</b></td></tr>";
			}
			?>
			</table>