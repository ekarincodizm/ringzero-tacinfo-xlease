<?php 
include("../../config/config.php");



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
function show_add(id){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('sys_setting_popup.php?cmd=add&id='+id);
    $('#dialog').dialog({
        title: 'เพิ่มข้อมูล ',
        resizable: false,
        modal: true,  
        width: 550,
        height: 270,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
function show_edit(id){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('sys_setting_popup.php?cmd=edit&id='+id);
    $('#dialog').dialog({
        title: 'แก้ไขข้อมูล ',
        resizable: false,
        modal: true,  
        width: 550,
        height: 270,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
	function del(id){
  

	if(confirm('ยืนยันการลบข้อมูล!!')==true)
		{
		$.post("sys_setting_dtl_api.php",{
			
			
			set_group_id :id,
			cmd :'del'
			
		},
		function(data){
		if(data=='0'){
				alert("ลบรายการเรียบร้อยแล้ว!!");
				$("#panel").load("sys_setting_dtl.php");
				 	 }else{
				
				alert("ไม่สามารถทำรายการได้");
			}
			//window.location.reload() ;
		});
		}
	}
  </script><br />
              			<?php


			
			$qry_fr=pg_query("SELECT set_group_id 
	   FROM \"hr_payroll_setting\" group by set_group_id order by set_group_id ");	
			 
				
			$nub=pg_num_rows($qry_fr);
			
			?>
  <button onclick="show_add('<?php echo ($nub+1) ?>')"  class="ui-button-text-only ui-state-default ui-corner-all">
   เพิ่มข้อมูล
</button> 
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
          
             <td align="center">รหัสกลุ่มการตั้งค่า ( Set_Group_ID )</td>
				<td align="center">รายละเอียด</td>
              
				<td align="center">จัดการ</td>
			</tr>
            			<?php

			while($sql_row4=pg_fetch_array($qry_fr)){
					$set_group_id = $sql_row4['set_group_id'];
					
					
					
						$i+=1;
				if($i%2==0){
					$tr = "<tr class=\"odd\" align=center>";
				}else{
					$tr = "<tr class=\"even\" align=center>";
				}
					
					

					
					echo $tr ;
					

			
			?>

<td align="center"><?php echo $set_group_id; ?></td>
				<td align="center"><?php 
				
				
                $qry_fr3=pg_query("SELECT set_value 
	   FROM \"hr_payroll_setting\" where set_group_id = '$set_group_id' order by set_seq ");

			$nub3=pg_num_rows($qry_fr3); 
			$s_c =0;
			if($nub3>0){
			while($sql_row43=pg_fetch_array($qry_fr3)){

					$set_value[$s_c] = $sql_row43['set_value'];
					$s_c++;
					
			}
			//ค่า setting ต่างๆ เรียงลำดับตามฐานข้อมูล
			$current_social_rate = $set_value[0];
			$tax_exp_deduct_percent = $set_value[1];
			
			$tax_exp_deduct_max = $set_value[2];
			$tax_private_deductible = $set_value[3];
			$maternity_leave_salary= $set_value[4];
			}
	
				
			?>
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" >
			
			<tr style="font-weight:bold;" valign="middle" align="center">
         
				<td align="right">% การหักประกันสังคม  </td>
                <td align="left"><input type="text" name="current_social_rate" id="current_social_rate" style="text-align:right" size="20" readonly="readonly" value="<?php echo number_format($current_social_rate,2); ?>" ></td>
                </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">% หักค่าใช้จ่าย</td>
                <td align="left"><input type="text" name="tax_exp_deduct_percent" id="tax_exp_deduct_percent" size="20"  style="text-align:right" readonly="readonly"  value="<?php echo number_format($tax_exp_deduct_percent,2); ?>" /></td>
          </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">จำนวนเงินสูงสุดที่นำมา หักค่าใช้จ่าย</td>
                <td align="left"><input type="text" name="tax_exp_deduct_max" id="tax_exp_deduct_max" size="20"  style="text-align:right" readonly="readonly"  value="<?php echo number_format($tax_exp_deduct_max,2); ?>" /></td>
          </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">ค่าลดหย่อนส่วนตัว</td>
                <td align="left"><input type="text" name="tax_private_deductible" id="tax_private_deductible" size="20"  style="text-align:right" readonly="readonly"  value="<?php echo number_format($tax_private_deductible,2); ?>" /></td>
          </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">จำนวนวันที่ลาคลอด  แล้วให้เงินเดือน</td>
                <td align="left"><input type="text" name="maternity_leave_salary" id="maternity_leave_salary" size="20"  style="text-align:right" readonly="readonly" value="<?php echo number_format($maternity_leave_salary); ?>" /></td>
          </tr>
			


			</table>
    </td>
       
                <td align="center"><button onclick="show_edit('<?php echo $set_group_id ?>')" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  แก้ไข
</button> <button onclick="del('<?php echo $set_group_id ?>')"  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
   ลบ
</button> </td>
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=3 align=center ><b>- ไม่มีข้อมูล -</b></td></tr>";
			}
			?>
			</table>