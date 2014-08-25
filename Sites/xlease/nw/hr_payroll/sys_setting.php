<?php 
include("../../config/config.php");

include("function_payroll.php");
include("../../core/core_functions.php");



$sex_id = $_GET['sex_id'];


$app_date = Date('Y-m-d H:i:s');

?>

<script type="text/javascript">


function update(){

	var salary_per_day =0;
	var social_rate = $('#social_rate').val().replace(/,/g,'');
	if($('#salary_type').val()=='1')
	salary_per_day = $('#salary_per_day').val().replace(/,/g,'');
	
	
	if(social_rate==''){
	  social_rate = 0;
  }

  if(salary_per_day==''){
	  salary_per_day = 0;
  }
  if($('#set_group_id').val()==''){alert(' ไม่พบรหัส Set_Group_ID !!');return false;}
  if($('#hr_payroll_tax_id').val()==''){alert(' ไม่พบรหัส Set_Tax_ID !!');return false;}

var week_holiday_s = "" ;
    if($('input[id=wk6]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk6]:checked').val() + "#";}
	if($('input[id=wk0]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk0]:checked').val() + "#";}
	if($('input[id=wk1]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk1]:checked').val() + "#";}
	if($('input[id=wk2]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk2]:checked').val() + "#";}
	if($('input[id=wk3]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk3]:checked').val() + "#";}
	if($('input[id=wk4]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk4]:checked').val() + "#";}
	if($('input[id=wk5]').is(':checked')){week_holiday_s = week_holiday_s + $('input[id=wk5]:checked').val() + "#";}
 $.post("sys_setting_api.php", { 
  cmd: 'update',
            		max_late: $('#max_late').val(),
					late_fine: $('#late_fine').val(),
					late_time_start: $('#late_time_start').val(),
					late_time_end: $('#late_time_end').val(),
					clock_out: $('#clock_out').val(),
					midday_exp: $('input[id=midday_exp]:checked').val(),
					start_work: $('#start_work').val(),
					
					clock_out_sat: $('#clock_out_sat').val(),
					att_all: $('#att_all').val(),
					late_time_sp_start: $('#late_time_sp_start').val(),
					late_time_sp_end: $('#late_time_sp_end').val(),
					att_all_full_month_rate: $('#att_all_full_month_rate').val(),
					week_holiday: week_holiday_s,
					
					late_time_af: $('#late_time_af').val(),
					
					max_late_af: $('#max_late_af').val(),
					late_fine_af: $('#late_fine_af').val(),
					u_sex: $('input[name=u_sex]:checked').val(),
					bf_clock_out1: $('#bf_clock_out1').val(), 
					bf_clock_out_start: $('#bf_clock_out_start').val(), 
				    bf_clock_out_end: $('#bf_clock_out_end').val(), 
				    bf_clock_out_amt: $('#bf_clock_out_amt').val(), 
				    sick_leave: $('#sick_leave').val(), 
				    sick_leave_remain: $('#sick_leave_remain').val(), 
				    vacation_leave: $('#vacation_leave').val(), 
				    vacation_leave_remain: $('#vacation_leave_remain').val(), 
				    busi_leave: $('#busi_leave').val(), 
				    busi_leave_remain: $('#busi_leave_remain').val(),

					bank_name: $('#bank_name').val(),
					comp_name: $('#comp_name').val(),
					bank_acc_type: $('#bank_acc_type').val(),
					salary_type: $('#salary_type').val(),
					
					att_cal_month_bef: $('#att_cal_month_bef').val(),
					work_status: $('#work_status').val(),
					user_status: $('input[name=user_status]:checked').val(),
					salary_per_day: salary_per_day,
					pay_type: $('input[name=pay_type]:checked').val(), 
				    user_note: $('#user_note').val(),
					set_group_id: $('#set_group_id').val(),
					hr_payroll_tax_id: $('#hr_payroll_tax_id').val(),
					social_rate: social_rate, 
					u_sex: $('input[name=u_sex]:checked').val(),
					
					
					
					id: '<?php echo $sex_id ?>'


  },
  function(data){
             if(data.success){ 
                alert(data.message);
                //location.reload();
            }else{
                alert(data.message);
            }
   },"json");
     };

function show_p_day(obj){
	
	if(obj.value==1){
		
		$('#sp2').show()
	}else $('#sp2').hide()
	
}
 
</script>


        
     
        <style type="text/css">
        body center form table tr td div {
	color: #00F;
}
        </style>

<?php 

				$qry_fr=pg_query("SELECT max_late, late_fine, late_time_start, 
       late_time_end, clock_out, start_work, 
       clock_out_sat, att_all, late_time_sp_start,late_time_sp_end, att_all_full_month_rate,week_holiday, 
	   late_time_af,max_late_af,late_fine_af, bf_clock_out1, bf_clock_out_start, 
       bf_clock_out_end, u_sex, bf_clock_out_amt, salary_date_cal_start, 
       sick_leave, sick_leave_remain, vacation_leave, vacation_leave_remain, 
       busi_leave, busi_leave_remain,midday_exp,work_status,user_note ,att_cal_month_bef ,set_group_id,hr_payroll_tax_id  
	   FROM \"hr_users_setting\" where id='$sex_id' ");
	
	  
	   $nub=pg_num_rows($qry_fr); 

			if($nub>0){
				while($sql_row4=pg_fetch_array($qry_fr)){

					$max_late = $sql_row4['max_late'];
					$late_fine = $sql_row4['late_fine'];
					$late_time_start = $sql_row4['late_time_start'];
					$late_time_end = $sql_row4['late_time_end'];
					$clock_out = $sql_row4['clock_out'];
					
					$start_work = $sql_row4['start_work'];
			
					$clock_out_sat = $sql_row4['clock_out_sat'];
					$att_all = $sql_row4['att_all'];
					$late_time_sp_start = $sql_row4['late_time_sp_start'];
					$late_time_sp_end = $sql_row4['late_time_sp_end'];
					$att_all_full_month_rate = $sql_row4['att_all_full_month_rate'];
					$week_holiday = $sql_row4['week_holiday'];
					
					$late_time_af = $sql_row4['late_time_af'];
					
					$max_late_af = $sql_row4['max_late_af'];
					$late_fine_af = $sql_row4['late_fine_af'];
					$u_sex = $sql_row4['u_sex'];
					$bf_clock_out1= $sql_row4['bf_clock_out1']; 
					$bf_clock_out_start= $sql_row4['bf_clock_out_start']; 
				    $bf_clock_out_end= $sql_row4['bf_clock_out_end']; 
				    $bf_clock_out_amt= $sql_row4['bf_clock_out_amt']; 
				    $salary_date_cal_start= $sql_row4['salary_date_cal_start']; 
				    $sick_leave= $sql_row4['sick_leave']; 
				    $sick_leave_remain= $sql_row4['sick_leave_remain']; 
				    $vacation_leave= $sql_row4['vacation_leave']; 
				    $vacation_leave_remain= $sql_row4['vacation_leave_remain']; 
				    $busi_leave= $sql_row4['busi_leave']; 
				    $busi_leave_remain= $sql_row4['busi_leave_remain']; 
					$midday_exp= $sql_row4['midday_exp']; 
					$work_status= $sql_row4['work_status']; 
					$user_note= $sql_row4['user_note']; 
					 $att_cal_month_bef= $sql_row4['att_cal_month_bef'];
					  $set_group_id= $sql_row4['set_group_id'];
					$hr_payroll_tax_id= $sql_row4['hr_payroll_tax_id'];
					 $hol_week_code = explodeStr2('#',$week_holiday);
					$hol_week_count = explode_Count2('#',$week_holiday);

				}	
		
			
			}
					

		
		
			
				$qry_fr2=pg_query("SELECT bank_name, comp_name, bank_acc_type, salary_type, pay_type, social_rate, 
       salary_per_day, user_status
	   FROM \"hr_payroll_report\" where id='$sex_id' ");	   
	  
	   $nub2=pg_num_rows($qry_fr2); 
				
		
					//ในส่วนของเงื่อนไขไม่มีข้อมูลเดือนที่เลือก	
			if($nub2>0){ //ถ้ามีข้อมูลเดือนก่อนหน้า
			while($sql_row42=pg_fetch_array($qry_fr2)){
					
					$bank_name = $sql_row42['bank_name'];
					$comp_name = $sql_row42['comp_name'];
					$bank_acc_type = $sql_row42['bank_acc_type'];
					$salary_type = $sql_row42['salary_type'];
					$pay_type= $sql_row42['pay_type']; 				   
					$social_rate= $sql_row42['social_rate'];     				
					$salary_per_day= $sql_row42['salary_per_day'];
					$user_status= $sql_row42['user_status'];
			
			}}

	
			
							$qry_fr3=pg_query("SELECT set_value 
	   FROM \"hr_payroll_setting\" where set_group_id = '$set_group_id' and set_name='current_social_rate' order by set_seq ");

			while($sql_row43=pg_fetch_array($qry_fr3)){

					$social_rate = $sql_row43['set_value'];
				
					
			}

	

			
				//วันหยุด
	for($w=0;$w<($hol_week_count);$w++){
			
			if($hol_week_code[$w]==0 )$wk0=1;
			else if($hol_week_code[$w]==1 )$wk1=1;
			else if($hol_week_code[$w]==2 )$wk2=1;
			else if($hol_week_code[$w]==3 )$wk3=1;
			else if($hol_week_code[$w]==4 )$wk4=1;
			else if($hol_week_code[$w]==5 )$wk5=1;
			else if($hol_week_code[$w]==6 )$wk6=1;
			
	}

?>
	<script type="text/javascript">
<?Php if($salary_type!='1'){ ?>
$('#sp2').hide();
<?Php } ?>

</script>
		<table align="center" border="0" cellpadding="2">
        <tr>
		    <td colspan="4" bgcolor="#33CCFF"><div align="left"><strong>ตั้งค่าพื้นฐานพนักงานเพศ<?php if($sex_id==1)echo "ชาย";else echo "หญิง"; ?></strong></div></td>
	      </tr>
          <tr>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>สถานภาพ : </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		     <input type="radio" name="user_status" value="โสด" <?php if($user_status=="" || $user_status=="โสด"){ echo "checked"; }?>>โสด <input type="radio" name="user_status" value="สมรส" <?php if($user_status=="สมรส"){ echo "checked"; }?>>สมรส <input type="radio" name="user_status" value="หย่าร้าง"<?php if($user_status=="หย่าร้าง"){ echo "checked"; }?>>หย่าร้าง <input type="radio" name="user_status" value="หม้าย" <?php if($user_status=="หม้าย"){ echo "checked"; }?>>หม้าย</strong>
		      </div></td>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>สถานะการทำงาน :</strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
             <select name="work_status" id="work_status" >
		                <option value="1" <?Php if($work_status=='1'){ echo "selected" ;} ?>>ทำงาน</option>
		                <option value="2" <?Php if($work_status=='2'){ echo "selected" ;} ?>>ลาออก</option>
                        <option value="3" <?Php if($work_status=='3'){ echo "selected" ;} ?>>เกษียณ</option>
		                <option value="4" <?Php if($work_status=='4'){ echo "selected" ;} ?>>พักงาน</option>
	                  </select>
	        </div></td>
	      </tr>
          <tr>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>วันหยุด : </strong></div></td>
		    <td colspan="3" bgcolor="#F2F2F2"><div align="left">
		      <input name="wk6" id="wk6" type="checkbox" value="6" <?Php if($wk6=='1'){ echo "checked" ;} ?> /> เสาร์  <input name="wk0" id="wk0" type="checkbox" value="0" <?Php if($wk0=='1'){ echo "checked" ;} ?>/> อาทิตย์ <input name="wk1" id="wk1" type="checkbox" value="1" <?Php if($wk1=='1'){ echo "checked" ;} ?>/> จันทร์ 
              <input name="wk2" id="wk2" type="checkbox" value="2" <?Php if($wk2=='1'){ echo "checked" ;} ?>/> อังคาร <input name="wk3" id="wk3" type="checkbox" value="3" <?Php if($wk3=='1'){ echo "checked" ;} ?>/> พุธ <input name="wk4" id="wk4" type="checkbox" value="4" <?Php if($wk4=='1'){ echo "checked" ;} ?>/> 
              พฤหัสบดี 
              <input name="wk5" id="wk5" type="checkbox" value="5" <?Php if($wk5=='1'){ echo "checked" ;} ?>/> ศุกร์ 
	        </div></td>
	      </tr>
          		  <tr>
          		    <td bgcolor="#CCFFFF"><div align="right"><strong>ธนาคาร : </strong></div></td>
          		    <td bgcolor="#F2F2F2"><div align="left">
          		      <input name="bank_name" type="text" id="bank_name" value="<?php echo $bank_name ?>" size="30" />
       		        </div></td>
          		    <td bgcolor="#CCFFFF"><div align="right"><label class="description" for="element_4">
          		      <strong>บริษัท : </strong>
          		        </label>
   		           </div></td>
          		    <td bgcolor="#F2F2F2"><div align="left">
          		      <input name="comp_name" type="text" id="comp_name" value="<?Php print $comp_name ?>" size="30"/>
        		      </div></td>
	      </tr>
          <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>ไม่เข้าบริษัทช่วงบ่าย :</strong></div></td>
            <td bgcolor="#F2F2F2"><div align="left">
              <input name="midday_exp" id="midday_exp" type="checkbox" value="1" <?Php if($midday_exp=='1'){ echo "checked" ;} ?> />
              ไม่เข้า </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>ประเภทบัญชี : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		             
		              <select name="bank_acc_type" id="bank_acc_type" >
		                <option value="1" <?Php if($bank_acc_type=='1'){ echo "selected" ;} ?>>ออมทรัพย์</option>
		                <option value="2" <?Php if($bank_acc_type=='2'){ echo "selected" ;} ?>>กระแสรายวัน </option>
		                <option value="3" <?Php if($bank_acc_type=='3'){ echo "selected" ;} ?>>เงินฝากประจำ</option>
	                  </select>
		            </div></td>
          </tr>
           
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เบี้ยขยัน/วัน : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="att_all" type="text" id="att_all" value="<?php echo $att_all  ?>" size="30" />
             </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนเท่าของเบี้ยขยันทั้งหมด เมื่อตรงเงื่อนไข : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="att_all_full_month_rate" type="text" id="att_all_full_month_rate" value="<?php echo $att_all_full_month_rate ?>" size="30"/>
		            </div></td>
          </tr>
          <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนสูงสุดที่สายได้ ช่วงเช้า้ี : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="max_late" type="text" id="max_late" value="<?php echo $max_late ?>" size="30" />
            </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนเงินที่ปรับ ช่วงเช้า : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_fine" type="text" id="late_fine"    value="<?php echo $late_fine ?>" size="30"/>
		            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาที่เริ่มปรับแบบธรรมดา ช่วงเช้า : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_time_start" type="text" id="late_time_start" value="<?php echo $late_time_start ?>" size="30" />
             </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาสุดท้ายที่ปรับแบบธรรมดา ช่วงเช้า : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_time_end" type="text" id="late_time_end"    value="<?php echo $late_time_end ?>" size="30"/>
		            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาที่เริ่มปรับสายแบบพิเศษ ช่วงเช้า : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_time_sp_start" type="text" id="late_time_sp_start" value="<?php echo $late_time_sp_start ?>" size="30" />
             </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาสุดท้ายที่ปรับสายแบบพิเศษ ช่วงเช้า : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_time_sp_end" type="text" id="late_time_sp_end"    value="<?php echo $late_time_sp_end ?>" size="30"/>
		            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>นาทีที่นับสาย ช่วงบ่าย : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_time_af" type="text" id="late_time_af" value="<?php echo $late_time_af ?>" size="30" />
             </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนสูงสุดที่สายได้ ช่วงบ่าย : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="max_late_af" type="text" id="max_late_af"    value="<?php echo $max_late_af ?>" size="30"/>
		            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนเงินที่ปรับ ช่วงบ่าย : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="late_fine_af" type="text" id="late_fine_af" value="<?php echo $late_fine_af ?>" size="30" />
             </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาที่ปรับแบบพิเศษ ช่วงเย็น : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="bf_clock_out1" type="text" id="bf_clock_out1"    value="<?php echo $bf_clock_out1 ?>" size="30"/>
		            </div></td>
          </tr>
          <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาที่เริ่มปรับแบบธรรมดา ช่วงเย็น : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="bf_clock_out_start" type="text" id="bf_clock_out_start" value="<?php echo $bf_clock_out_start ?>" size="30" />
            </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาสุดท้ายที่ปรับแบบธรรมดา ช่วงเย็น : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="bf_clock_out_end" type="text" id="bf_clock_out_end"    value="<?php echo $bf_clock_out_end ?>" size="30"/>
		            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนเงินที่ปรับ ช่วงเย็น : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="bf_clock_out_amt" type="text" id="bf_clock_out_amt" value="<?php echo $bf_clock_out_amt ?>" size="30" />
             </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาเข้างาน : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="start_work" type="text" id="start_work"    value="<?php echo $start_work ?>" size="30"/>
		            </div></td>
          </tr>
           
		  <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาเลิกงาน : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="clock_out" type="text" id="clock_out" value="<?php echo $clock_out ?>" size="30" />
            </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>เวลาเลิกงานวันเสาร์ : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="clock_out_sat" type="text" id="clock_out_sat"    value="<?php echo $clock_out_sat ?>" size="30"/>
		            </div></td>
          </tr>
          <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนวันลาป่วย : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="sick_leave" type="text" id="sick_leave" value="<?php echo $sick_leave ?>" size="30" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)" />
            </div></td>
           <td bgcolor="#CCFFFF"><div align="right"><strong>วันลาป่วยคงเหลือ : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="sick_leave_remain" type="text" id="sick_leave_remain" value="<?php echo $sick_leave_remain ?>" size="30" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)" />
            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนวันลากิจ : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="busi_leave" type="text" id="busi_leave" value="<?php echo $busi_leave ?>" size="30"  onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)"/>
            </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>วันลากิจคงเหลือ : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="busi_leave_remain" type="text" id="busi_leave_remain" value="<?php echo $busi_leave_remain ?>" size="30"  onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)"/>
		            </div></td>
          </tr>
           <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>จำนวนวันลาพักร้อน : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="vacation_leave" type="text" id="vacation_leave"    value="<?php echo $vacation_leave ?>" size="30"  onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)"/>
            </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>วันลาพักร้อนคงเหลือ : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		             <input name="vacation_leave_remain" type="text" id="vacation_leave_remain"    value="<?php echo $vacation_leave_remain ?>" size="30"  onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)"/>
		            </div></td>
          </tr>
           <tr>
             <td bgcolor="#CCFFFF"><div align="right"><strong>ประเภทการคำนวณเงินเดือน : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
                     <select name="salary_type" id="salary_type" onchange="show_p_day(this)" >
    <option value="0" <?Php if($salary_type=='0'){ echo "selected" ;} ?>>เงินเดือน เต็มเดือน</option>
    <option value="1" <?Php if($salary_type=='1'){ echo "selected" ;} ?>>รายวัน </option>
    <option value="2" <?Php if($salary_type=='2'){ echo "selected" ;} ?>>เงินเดือน ไม่เต็มเดือน</option>
    </select>
		            
		             <span id="sp2" >จำนวน <input name="salary_per_day" type="text" id="salary_per_day" value="<?php echo $salary_per_day ?>" size="5" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/> </span>
		            </div></td>
		            <td bgcolor="#CCFFFF"><div align="right"><strong>ชำระโดย : </strong> <strong>
		              </label>
		              </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="pay_type" type="radio" id="pay_type1" value="0" <?Php if($pay_type=='0'){ echo "checked" ;} ?> />
		              เงินสด
		              <input name="pay_type" type="radio" id="pay_type2" value="1" <?Php if($pay_type=='1'){ echo "checked" ;} ?> />
		              โอนเข้าธนาคาร </div></td>
          </tr>
            <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>% ประกันสังคม : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="social_rate" type="text" id="social_rate" value="<?php echo $social_rate ?>" size="30" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		              </div></td>
                <td bgcolor="#CCFFFF"><div align="right"><strong>เดือนที่นำเบี้ยขยัน/ค่าปรับ มาคำนวณ </strong> <strong>
            </label>
		    </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left"> <select name="att_cal_month_bef" id="att_cal_month_bef" >
    
    <option value="1" <?Php if($att_cal_month_bef=='1'){ echo "selected" ;} ?>>1</option>
    <option value="2" <?Php if($att_cal_month_bef=='2'){ echo "selected" ;} ?>>2</option>
    <option value="0" <?Php if($att_cal_month_bef=='0'){ echo "selected" ;} ?>>0</option>
    </select> เดือน ก่อนหน้านี้
</div></td>
          </tr> <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>Set_Group_ID : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="set_group_id" type="text" id="set_group_id" value="<?php echo $set_group_id ?>" size="30" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)"/>
		              </div></td>
                <td bgcolor="#CCFFFF"><div align="right"><strong>Set_Tax_ID</strong> <strong>
            </label>
		    </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left"> <input name="hr_payroll_tax_id" type="text" id="hr_payroll_tax_id" value="<?php echo $hr_payroll_tax_id ?>" size="30" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber2(event)"/>
</div></td>
          </tr>
			  <tr>
			    <td bgcolor="#CCFFFF">
			      <div align="right"><strong>หมายเหตุ :
			      </strong>
			      </div></td>
			    <td colspan="2" bgcolor="#F2F2F2"><div align="left">
			      <textarea class="element textarea small" name="user_note" style="width:98%" id="user_note"><?Php print $user_note ?></textarea>
			      </div></td>
                  <td colspan="2" bgcolor="#F2F2F2"><div align="left">
			     <input id="btn_up" class="button_text" type="button" value="บันทึก" onclick="update()" style='width:100px; height:50px'/>
			      </div></td>
		      </tr>
			  </table>
    

