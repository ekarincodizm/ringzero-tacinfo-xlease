<?php 
include("../../config/config.php");


$cmd = $_GET['cmd'];
$set_group_id = $_GET['id'];

$app_date = Date('Y-m-d H:i:s');

			
	if($cmd=="edit"){
				
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
	}
				
			?>
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" align="center">
         
				<td align="right">% การหักประกันสังคม ปัจจุบัน </td>
                <td align="left"><input type="text" name="current_social_rate" id="current_social_rate2" size="20" style="text-align:right" value="<?php echo number_format($current_social_rate,2); ?>" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" ></td>
                </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">%หักค่าใช้จ่าย</td>
                <td align="left"><input type="text" name="tax_exp_deduct_percent" id="tax_exp_deduct_percent2" size="20"style="text-align:right"  value="<?php echo number_format($tax_exp_deduct_percent,2); ?>" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/></td>
          </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">จำนวนเงินสูงสุดที่นำมา หักค่าใช้จ่าย</td>
                <td align="left"><input type="text" name="tax_exp_deduct_max" id="tax_exp_deduct_max2" size="20" style="text-align:right" value="<?php echo number_format($tax_exp_deduct_max,2); ?>" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/></td>
          </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">ค่าลดหย่อนส่วนตัว</td>
                <td align="left"><input type="text" name="tax_private_deductible" id="tax_private_deductible2" size="20" style="text-align:right" value="<?php echo number_format($tax_private_deductible,2); ?>" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/></td>
          </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">จำนวนวันที่ลาคลอด  แล้วให้เงินเดือน</td>
                <td align="left"><input type="text" name="maternity_leave_salary" id="maternity_leave_salary2" size="20" style="text-align:right" value="<?php echo number_format($maternity_leave_salary); ?>" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/></td>
          </tr>
                <tr style="font-weight:bold;" valign="middle" align="center">
				<td align="right"><strong>Set_Group_ID</strong></td>
                <td align="left"><input type="text" name="set_group_id" id="set_group_id2" style="text-align:right" readonly="readonly" size="20" value="<?php echo $set_group_id; ?>" /></td>
                </tr>
			


			</table><div style="float:right"><button onclick="<?Php if($cmd=="add")echo "add();" ; else if($cmd=="edit")echo "edit();"; ?>" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  <?Php if($cmd=="add")echo "เพิ่ม" ; else if($cmd=="edit")echo "แก้ไข"; ?>
</button> </div>

<script type="text/javascript">
  $("button").button();
function add(){

	
		$.post("sys_setting_dtl_api.php",{
			
			current_social_rate :$("#current_social_rate2").val().replace(/,/g,''),
			tax_exp_deduct_percent :$("#tax_exp_deduct_percent2").val().replace(/,/g,''),
			tax_exp_deduct_max :$("#tax_exp_deduct_max2").val().replace(/,/g,''),
			tax_private_deductible :$("#tax_private_deductible2").val().replace(/,/g,''),
			maternity_leave_salary :$("#maternity_leave_salary2").val().replace(/,/g,''),
			set_group_id :$("#set_group_id2").val(),
			cmd :'add'
			
		},
		function(data){
			//alert(data);
			if(data=='0'){
				alert("บันทึกรายการเรียบร้อย");
				$("#panel").load("sys_setting_dtl.php");
				 $('#dialog').remove();
			}else{
				
				alert("ไม่สามารถทำรายการได้");
			}
			//window.location.reload() ;
		});
	};
	
	function edit(){


	
		$.post("sys_setting_dtl_api.php",{
			
			current_social_rate :$("#current_social_rate2").val().replace(/,/g,''),
			tax_exp_deduct_percent :$("#tax_exp_deduct_percent2").val().replace(/,/g,''),
			tax_exp_deduct_max :$("#tax_exp_deduct_max2").val().replace(/,/g,''),
			tax_private_deductible :$("#tax_private_deductible2").val().replace(/,/g,''),
			maternity_leave_salary :$("#maternity_leave_salary2").val().replace(/,/g,''),
			set_group_id :$("#set_group_id2").val(),
			cmd :'edit'
			
		},
		function(data){
		if(data=='0'){
				alert("แก้ไขรายการเรียบร้อย");
				$("#panel").load("sys_setting_dtl.php");
				 $('#dialog').remove();
				 
				 }else{
				
				alert("ไม่สามารถทำรายการได้");
			}
			//window.location.reload() ;
		});
	};
	


</script>