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

    $('#dialog').load('tax_setting_popup.php?cmd=add&id='+id);
    $('#dialog').dialog({
        title: 'เพิ่มข้อมูล ',
        resizable: false,
        modal: true,  
        width: 800,
        height: 500,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
function show_edit(id){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('tax_setting_popup.php?cmd=edit&id='+id);
    $('#dialog').dialog({
        title: 'แก้ไขข้อมูล ',
        resizable: false,
        modal: true,  
        width: 800,
        height: 500,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
	function del(id){
  

	if(confirm('ยืนยันการลบข้อมูล!!')==true)
		{
		$.post("tax_setting_api.php",{
			
			
			set_tax_id :id,
			cmd :'del'
			
		},
		function(data){
			
		  if(data.success){
			  
				alert("ลบรายการเรียบร้อยแล้ว!!");
				$("#panel").load("tax_setting_dtl.php");
				 	 }else{
				
				alert("ไม่สามารถทำรายการได้");
			
			//window.location.reload() ;
					 }
					 },'json');

		
	}
	}
  </script><br />
              			<?php


			
			$qry_fr=pg_query("SELECT id 
	   FROM \"hr_payroll_tax\" group by id order by id ");	
			 
				
			$nub=pg_num_rows($qry_fr);
			
			?>
  <button onclick="show_add('<?php echo ($nub+1) ?>')"  class="ui-button-text-only ui-state-default ui-corner-all">
   เพิ่มข้อมูล
</button> 
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
          
             <td align="center">รหัสการตั้งค่า ( Set_Tax_ID )</td>
				<td align="center">รายละเอียด</td>
              
				<td align="center">จัดการ</td>
			</tr>
            			<?php

			while($sql_row4=pg_fetch_array($qry_fr)){
					$set_tax_id = $sql_row4['id'];
					
					
					
						$i+=1;
				if($i%2==0){
					$tr = "<tr class=\"odd\" align=center>";
				}else{
					$tr = "<tr class=\"even\" align=center>";
				}
					
					

					
					echo $tr ;
					

			
			?>

<td align="center"><?php echo $set_tax_id; ?></td>
				<td align="center">
				
				<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" >
			
			<tr style="font-weight:bold;" valign="middle" align="center">
         
				<td align="right">ขั้นเงินได้สุทธิตั้งแต่</td>
                <td align="right">ขั้นเงินได้สุทธิถึง</td>
                <td align="right">ช่วงเงินได้สุทธิ</td>
                <td align="right">อัตราภาษีร้อยละ</td>
                <td align="right">ภาษีแต่ละขั้นเงินได้</td>
                
                <td align="right">ภาษีสะสมสูงสุดของขั้น</td>
                
                 </tr>
				<?php 
				
				
                $qry_fr3=pg_query("SELECT tax_begin, tax_end, tax_rate, tax_percent, tax_step, tax_max
	   FROM \"hr_payroll_tax\" where id = '$set_tax_id' ");

			$nub3=pg_num_rows($qry_fr3); 
			$s_c =0;
			if($nub3>0){
			while($sql_row43=pg_fetch_array($qry_fr3)){

					$tax_begin = $sql_row43['tax_begin'];
					$tax_end = $sql_row43['tax_end'];
					$tax_rate = $sql_row43['tax_rate'];
					$tax_percent = $sql_row43['tax_percent'];
					$tax_step = $sql_row43['tax_step'];
					$tax_max = $sql_row43['tax_max'];
					
					
	
				
			?>

                
                <tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right"><input type="text" name="tax_begin" id="tax_begin" style="text-align:right" size="15" readonly="readonly" value="<?php echo number_format($tax_begin,2); ?>" ></td>

                <td align="right"><input type="text" name="tax_end" id="tax_end" size="15"  style="text-align:right" readonly="readonly"  value="<?php echo number_format($tax_end,2); ?>" /></td>

                <td align="right"><input type="text" name="tax_rate" id="tax_rate" size="15"  style="text-align:right" readonly="readonly"  value="<?php echo number_format($tax_rate,2); ?>" /></td>

                <td align="right"><input type="text" name="tax_percent" id="tax_percent" size="15"  style="text-align:right" readonly="readonly"  value="<?php echo number_format($tax_percent,2); ?>" /></td>

                <td align="right"><input type="text" name="tax_step" id="tax_step" size="15"  style="text-align:right" readonly="readonly" value="<?php echo number_format($tax_step,2); ?>" /></td>
                 <td align="right"><input type="text" name="tax_max" id="tax_max" size="15"  style="text-align:right" readonly="readonly" value="<?php echo number_format($tax_max,2); ?>" /></td>
          </tr>
			<?php 		}

			}
	 ?>


			</table>
    </td>
       
                <td align="center"><button onclick="show_edit('<?php echo $set_tax_id ?>')" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  แก้ไข
</button> <button onclick="del('<?php echo $set_tax_id ?>')"  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
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