<script>
  $(document).ready(function() {
    $("button").button();

    });

  </script>
        <style type="text/css">
        body center form table tr td div {
	color: #00F;
}
        </style>

<?php 
$last_date_month = date("t",strtotime("$yy_l-$mm_l-01"));
	//echo $salary_amt;
if($salary_type =='1'){ //รายวัน
 $salary_amt = $work_day_count * $salary_per_day;
}
else if($salary_type =='2'){ //รายเดือนไม่เต็มเดือน


      $salary_amt =  ($salary_amt/$last_date_month)*$work_inc_hol_count; //เงินเดือน /จำนวนวัน ใน 1 เดือนนั้น * จำนวนวันที่ทำงาน รวมเสาร์ทิตย์ วันหยุดประจำปี 


}

$s_p_day  = ($salary_amt/$last_date_month);


?>
	
		<table align="center" border="0" cellpadding="2">
        <tr>
		    <td colspan="4" bgcolor="#33CCFF"><div style="float:left"><strong>รายละเอียดพนักงาน</strong></div> <div style="float:right"><button onclick="cal_income();" class="ui-button-text-only ui-state-default ui-corner-all">
  ให้ระบบคำนวณใหม่อีกครั้ง
</button>  </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
	        <label class="description" for="element_4">
	        <div align="right"><strong><font color="green">รหัสพนักงาน(สลิป) : </font>
            </label>
            </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="user_id_slip" class="element textarea medium" style="text-align:left" type="text" id="user_id_slip" readonly="readonly" value="<?Php print $user_id_slip ?>" size="30" /> 
		      
		   </div></td>
		    <td  bgcolor="#CCFFFF">
	        <label class="description" for="element_4">
	        <div align="right"><strong>แผนก : 
            </label>
            </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="user_dep" type="text" style="text-align:left"  id="user_dep" readonly="readonly" value="<?Php print $user_dep ?>" size="30"/>
		    </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>ชื่อ-นามสกุลพนักงาน : </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="user_fullname" type="text" style="text-align:left"  id="user_fullname" readonly="readonly" value="<?php echo $user_fullname ?>" size="30" />
		    </div></td>
		    <td bgcolor="#CCFFFF">
		      <label class="description" for="element_4">
		      <div align="right"><strong>ฝ่าย :
</label>
            </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="user_div" type="text" style="text-align:left"  id="user_div" readonly="readonly" value="<?Php print $user_div ?>" size="30"/>
		      </div></td>
	      </tr>

		  <tr>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>วันรับเข้าทำงาน : </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="user_start" type="text" style="text-align:left"  id="user_start" readonly="readonly" value="<?php echo $user_start ?>" size="30" />
		      </div></td>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>ตำแหน่ง :</strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
             <input name="user_pos" type="text" style="text-align:left"  id="user_pos" readonly="readonly" value="<?Php print $user_pos ?>" size="30"/>
	        </div></td>
	      </tr>
          <tr>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>เพศ : </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
            <input name="wk" type="text" style="text-align:left"  id="wk" readonly="readonly" value="<?Php if($u_sex=='0'){ echo "หญิง" ;} else if($u_sex=='1'){ echo "ชาย" ;} ?>" size="30"/>
		      
           
		      </div></td>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>วันหยุด :</strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
             <input name="wk" type="text" style="text-align:left"  id="wk" readonly="readonly" value="<?Php if($wk6=='1')$wke .=" เสาร์" ; 
			 if($wk0=='1')$wke .= " อาทิตย์" ;
			 if($wk1=='1')$wke .= " จันทร์" ;
			 if($wk2=='1')$wke .= " อังคาร" ;
			 if($wk3=='1')$wke .= " พุธ" ;
			 if($wk4=='1')$wke .= " พฤหัสบดี" ;
			 if($wk5=='1')$wke .= " ศุกร์" ;
			 
			 echo trim($wke);
			 
			 
			 ?>" size="30"/>
           
	        </div></td>
	      </tr>
          
        
          
          		  <tr>
          		    <td bgcolor="#CCFFFF"><div align="right"><strong>ธนาคาร : </strong></div></td>
          		    <td bgcolor="#F2F2F2"><div align="left">
          		      <input name="bank_name" type="text" style="text-align:left"  id="bank_name" readonly="readonly" value="<?php echo $bank_name ?>" size="30" />
        		      </div></td>
          		    <td bgcolor="#CCFFFF"><label class="description" for="element_4">
          		      <div align="right"><strong>บริษัท :
          		        </label>
   		            </strong></div></td>
          		    <td bgcolor="#F2F2F2"><div align="left">
          		      <input name="comp_name" type="text" style="text-align:left"  id="comp_name" readonly="readonly" value="<?Php print $comp_name ?>" size="30"/>
        		      </div></td>
	      </tr>
          <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>หมายเลขบัญชี : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="bank_acc_no" type="text" style="text-align:left"  id="bank_acc_no" readonly="readonly" value="<?php echo $bank_acc_no ?>" size="30" />
		              </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>ประเภทบัญชี : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="bank_acc_type2" type="text" style="text-align:left"  id="bank_acc_type2"  readonly="readonly"   value="<?php if($bank_acc_type=='1'){ echo "ออมทรัพย์" ;} else if($bank_acc_type=='2'){ echo "กระแสรายวัน" ;}else if($bank_acc_type=='3'){ echo "เงินฝากประจำ" ;} ?>" size="30"/>
                      
		            </div></td>
          </tr>
          <tr>
            <td bgcolor="#CCFFFF"><div align="right"><strong>สถานะการทำงาน: </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">
		              <input name="work_status2" type="text" style="text-align:left"  id="work_status2" readonly="readonly" value="<?Php if($work_status=='1'){ echo "ทำงาน" ;} 
    else if($work_status=='2'){ echo "ลาออก" ;}
    else if($work_status=='3'){ echo "เกษียณ" ;}
	else if($work_status=='4'){ echo "พักงาน" ;} ?>" size="30" />
		              </div></td>
            <td bgcolor="#CCFFFF"><div align="right"><strong>ประเภทการรับเงินเดือน : </strong></div></td>
		            <td bgcolor="#F2F2F2"><div align="left">

		            
                     <input name="salary_type2" type="text" style="text-align:left"  id="salary_type2"  readonly="readonly"   value="<?Php if($salary_type=='0'){ echo "เงินเดือน เต็มเดือน" ;} 
    else if($salary_type=='1'){ echo "รายวัน "+ number_format($salary_per_day,2)+" บาท" ;}
    else if($salary_type=='2'){ echo "เงินเดือน ไม่เต็มเดือน" ;} ?>" size="30"/>
		            
 
		            </div></td>
          </tr>
		  <tr bgcolor="#33CCFF">
		    <td colspan="4"><div align="left"><strong>รายการรับ (บาท)</strong></div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF" >
	        <label class="description" for="element_4">
	        <div align="right"><strong>
	   
            จำนวนเงินเดือน : <!--<br />
            (มีการปรับเงินเดือนจากเดือนที่แล้ว 
            <input name="sa_ch_cb" id="sa_ch_cb" type="checkbox" value="1" />) : title="มีการปรับเงินเดือนจากเดือนที่แล้ว หรือไม่ ให้ติ้กเฉพาะเดือนที่ปรับขึ้นเท่านั้น เพื่อใช้สำหรับคำนวณภาษี"--> </strong>	         
              </label>
            </div></td>
		    <td bgcolor="#F2F2F2"><div align="left"><font color="red"> </font>
	        <input name="salary_amt" type="text" style="text-align:right"  id="salary_amt" value="<?Php print number_format($salary_amt,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		    </div></td>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>ค่าครองชีพ :</strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="cost_of_living" type="text" style="text-align:right"  id="cost_of_living" value="<?Php print number_format($cost_of_living,2)  ?>"  size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		    </div></td>
	      </tr>
		  
		  <tr>
		    <td bgcolor="#CCFFFF">
	        
	        <div align="right"><strong>เบี้ยขยัน(เดือนที่แล้ว) : </strong>	          <strong>
            </label>
            </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="diligent" type="text" style="text-align:right"  id="diligent" value="<?Php print number_format($diligent,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		      </div></td>
		    <td bgcolor="#CCFFFF">
		      <label class="description" for="element_4">
	        <div align="right"><strong>ค่าล่วงเวลา (OT) :</strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left"><font color="red"> </font>
		      <input name="ot" type="text" style="text-align:right"  id="ot" value="<?Php print number_format($ot,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      
		      <div align="right"><strong>ค่าคอมมิชชั่น : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="commission" type="text" style="text-align:right"  id="commission" value="<?Php print number_format($commission,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		      </div></td><td bgcolor="#CCFFFF">
		 	      
	 	        <div align="right"><strong>ค่าโทรศัพท์ : </strong> <strong>
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="tel_income" type="text" style="text-align:right"  id="tel_income" value="<?Php print number_format($tel_income,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	      </div></td>
		    
	      </tr>
		 	  <tr>
		 	    <td bgcolor="#CCFFFF">
		 	      
		 	      <div align="right"><strong>ค่ายานพาหนะ(ค่าน้ำมัน) : </strong> <strong>
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="fare" type="text" style="text-align:right"  id="fare" value="<?Php print number_format($fare,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	      </div></td>
		 	    <td bgcolor="#CCFFFF">
		      
	        <div align="right"><strong>ค่าอื่นๆ : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="other_income" type="text" style="text-align:right"  id="other_income" value="<?Php print number_format($other_income,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		      </div></td>
 	      </tr>
		 	  <tr>
		 	    <td bgcolor="#CCFFFF">
		 	      
		 	      <div align="right"><strong>ค่าเสื่อมยานพาหนะ : </strong> 
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="depreciation" type="text" style="text-align:right"  id="depreciation" value="<?Php print number_format($depreciation,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	      </div></td>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong>โบนัส : </strong></label></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="bonus" type="text" style="text-align:right"  id="bonus" value="<?Php print number_format($bonus,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
	 	        </div></td>
 	      </tr>
          <tr>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong>รายได้ต่อวัน : </strong> 
		 	        </label>
 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><input name="s_p_day" type="text" style="text-align:right"  id="s_p_day" readonly="readonly" value="<?php echo number_format($s_p_day,2) ?>" size="30" /></td>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong><font color="green">จำนวนรายรับรวม :</font></strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="total_income" type="text" style="text-align:right"  id="total_income" value="<?Php print number_format($total_income,2)  ?>" size="30" readonly="readonly"/>
	 	        </div></td>
 	      </tr>
		 	  <tr>
		 	    <td colspan="4" bgcolor="#33CCFF"><div align="left"><strong>รายการหัก (บาท)</strong></div></td>
 	      </tr>
		 	  <tr>
		 	    <td bgcolor="#CCFFFF">
		 	      
		 	      <div align="right"><strong>ภาษี(TAX) (คำนวณเอง <input name="user_cal_tax" id="user_cal_tax" type="checkbox" value="1" />) : </strong> <strong>
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="tax" type="text" style="text-align:right"  id="tax" value="<?Php print number_format($tax,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	      </div></td>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong>สาย/ออกก่อนเวลา(เดือนที่แล้ว) : </strong> <strong>
		 	      </label>
	 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="fine_late" class="element textarea medium" type="text" style="text-align:right"  id="fine_late" value="<?Php print number_format($fine_late,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	     
		 	      </div></td>
 	      </tr>
		 	  <tr>
		 	    <td bgcolor="#CCFFFF">
		 	      
		 	      <div align="right"><strong>ค่าประกันสังคม (<?php echo number_format( $social_rate,2)  ?>%) : </strong> <strong>
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="social_amt" type="text" style="text-align:right"  id="social_amt" value="<?Php print number_format($social_amt,2)  ?>" size="30" readonly="readonly"/>
		 	      </div></td>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong>รายการหักอื่นๆ(Other) : </strong> <strong>
		 	      </label>
		 	      </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="other_deduct" type="text" style="text-align:right"  id="other_deduct" value="<?Php print number_format($other_deduct,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	      </div></td>
 	      </tr>
		 	  <tr>
		 	    <td bgcolor="#CCFFFF">
		 	      
		 	      <div align="right"><strong>ค่าปรับเอกสารไม่ครบ  (ฝ่ายขาย) :  </strong> <strong>
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="fine_incomplete" type="text" style="text-align:right"  id="fine_incomplete" value="<?Php print number_format($fine_incomplete,2)  ?>" size="30" onkeyup="dokeyup(this,event);cal_income();" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"/>
		 	      </div></td>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong><font color="#FF0000">จำนวนเงินหักรวม : </font></strong> <strong>
		 	      </label>
		 	      </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="total_deduct" type="text" style="text-align:right"  id="total_deduct" value="<?Php print number_format($total_deduct,2)  ?>" size="30" readonly="readonly" />
		 	      </div></td>
 	      </tr>
	
		 	  <tr>
		 	    <td colspan="4" bgcolor="#33CCFF"><div align="left"><strong>จำนวนเงิน (TOTAL DED.) (บาท)</strong></div></td>
 	      </tr>
		 	  <tr>
		 	    <td bgcolor="#CCFFFF">
		 	      
		 	      <div align="right"><strong><font color="blue">รับสุทธิเดือนนี้ : </font></strong> <strong>
		 	        </label>
		 	        </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="total_net" type="text" style="text-align:right"  id="total_net" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"onkeyup="dokeyup(this,event);" value="<?Php print number_format($total_net,2)  ?>" size="30" readonly="readonly"/>
		 	    </div></td>
		 	    <td bgcolor="#CCFFFF"><div align="right"><strong>ชำระโดย : </strong> <strong>
		 	      </label>
		 	      </strong></div></td>
		 	    <td bgcolor="#F2F2F2"><div align="left">
		 	      <input name="pay_type2" type="text" style="text-align:left"  id="pay_type2" readonly="readonly" value="<?Php if($pay_type=='1'){ echo "โอนเข้าธนาคาร" ;}
		          else if($pay_type=='0'){ echo "เงินสด" ;} ?>" size="30" />
		 	      </div></td>
 	      </tr>

		  <tr>
		    <td colspan="4" bgcolor="#33CCFF"><div align="left"><strong>รายการ สะสม ถึงปัจจุบัน (บาท) (คำนวณเอง <input name="user_cal_acc" onclick="user_cal_acc()" id="user_cal_acc" type="checkbox" value="1" />) </strong></div></td>
	      </tr>
          <?php 
		  
//วนลูปคำนวณ เงินสะสม
//ไม่เอาเดือนปัจจุบัน
$mm_l22= $mm_l-1;


				$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no ,salary_per_day,tax_exc_sal_col ,bonus,t_total_tax_so,user_status 
	   FROM \"hr_payroll_report\" where month
BETWEEN 1 AND $mm_l22 and user_id_sys='$id_user' and year='$yy_l' order by month ");	   


	   $nub2=pg_num_rows($qry_fr2); 

						
			
			$t_total_tax_so=0;
	 				$t_salary_amt=0;
					$t_cost_of_living= 0;
				    $t_diligent= 0;
				    $t_ot= 0;
				    $t_commission= 0;
				    $t_other_income= 0;
				    $t_fare= 0;
				    $t_tel_income= 0;
				    $t_depreciation= 0;
					
					$t_total_income= 0;
				    $t_tax= 0;
				    $t_fine_late= 0;
				    $t_social_amt= 0;
				    $t_other_deduct= 0;
				    $t_fine_incomplete= 0;
				    $t_total_deduct= 0;
				    $t_total_net= 0;
					if($nub2>0){ //ถ้ามีข้อมูล
			while($sql_row42=pg_fetch_array($qry_fr2)){

					$salary_amt = $sql_row42['salary_amt'];
					$cost_of_living = $sql_row42['cost_of_living'];
					$diligent = $sql_row42['diligent'];
					$ot = $sql_row42['ot'];
					$commission = $sql_row42['commission'];
					$other_income = $sql_row42['other_income'];
					$fare = $sql_row42['fare'];
					$depreciation= $sql_row42['depreciation']; 
					$tel_income= $sql_row42['tel_income']; 
				    $total_income= $sql_row42['total_income']; 
				    $tax= $sql_row42['tax']; 
				    $fine_late= $sql_row42['fine_late']; 
				    $social_amt= $sql_row42['social_amt']; 
				    $other_deduct= $sql_row42['other_deduct']; 
				    $fine_incomplete= $sql_row42['fine_incomplete']; 
				    $total_deduct= $sql_row42['total_deduct']; 
				    $total_net= $sql_row42['total_net']; 
					
					//บวกกันแต่ละเดือน
					$t_total_tax_so+=$total_tax_so;
	 				$t_salary_amt+=$salary_amt;
					$t_cost_of_living+=$cost_of_living;
				    $t_diligent+=$diligent;
				    $t_ot+=$ot;
				    $t_commission+=$commission;
				    $t_other_income+=$other_income;
				    $t_fare+=$fare;
				    $t_tel_income+=$tel_income;
				    $t_depreciation+=$depreciation;
					
					$t_total_income+=$total_income;
				    $t_tax+=$tax;
				    $t_fine_late+=$fine_late;
				    $t_social_amt+=$social_amt;
				    $t_other_deduct+=$other_deduct;
				    $t_fine_incomplete+=$fine_incomplete;
				    $t_total_deduct+=$total_deduct;
				    $t_total_net+=$total_net;
					
			}
			}




if($mm_l==1){//เมื่อเป็นเดือนมกราคม ให้คิดเงินสะสมใหม่
	
	$t_total_tax_so=0;
	 $t_salary_amt=0;
					$t_cost_of_living= 0;
				    $t_diligent= 0;
				    $t_ot= 0;
				    $t_commission= 0;
				    $t_other_income= 0;
				    $t_fare= 0;
				    $t_tel_income= 0;
				    $t_depreciation= 0;
					
					$t_total_income= 0;
				    $t_tax= 0;
				    $t_fine_late= 0;
				    $t_social_amt= 0;
				    $t_other_deduct= 0;
				    $t_fine_incomplete= 0;
				    $t_total_deduct= 0;
				    $t_total_net= 0;
}
?>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>เงินเดือนสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_salary_amt" type="text" style="text-align:right"  id="t_salary_amt" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_salary_amt,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF"><label class="description" for="element_4">
	        <div align="right"><strong>ค่าครองชีพสะสม :</strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left"><font color="red"> </font>
		      <input name="t_cost_of_living" type="text" style="text-align:right"  id="t_cost_of_living" value="<?Php print number_format($t_cost_of_living,2)  ?>" size="30" readonly="readonly" />
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>เบี้ยขยันสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_diligent" type="text" style="text-align:right"  id="t_diligent" value="<?Php print number_format($t_diligent,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF">
	        <div align="right"><strong>ค่าล่วงเวลาสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_ot" type="text" style="text-align:right"  id="t_ot" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_ot,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>ค่าคอมมิชชั่นสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_commission" type="text" style="text-align:right"  id="t_commission" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_commission,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF"><label class="description" for="element_4">
	        <div align="right"><strong>ค่าอื่นๆสะสม :</strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left"><font color="red"> </font>
		      <input name="t_other_income" type="text" style="text-align:right"  id="t_other_income" value="<?Php print number_format($t_other_income,2)  ?>" size="30" readonly="readonly" />
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>ค่ายานพาหนะสะสม  : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_fare" type="text" style="text-align:right"  id="t_fare" value="<?Php print number_format($t_fare,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF">
	        <div align="right"><strong>ค่าโทรศัพท์สะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_tel_income" type="text" style="text-align:right"  id="t_tel_income" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_tel_income,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF"><div align="right"><strong>ค่าเสื่อมยานพาหนะสะสม :</strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_depreciation" type="text" style="text-align:right"  id="t_depreciation" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_depreciation,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF"><div align="right"><strong><font color="green">จำนวนรายรับรวมสะสม :</font></strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_total_income" type="text" style="text-align:right"  id="t_total_income" value="<?Php print number_format($t_total_income,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>ภาษี(TAX)สะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_tax" type="text" style="text-align:right"  id="t_tax" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_tax,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF">
	        <div align="right"><strong>สาย/ออกก่อนเวลาสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_fine_late" type="text" style="text-align:right"  id="t_fine_late" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_fine_late,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>ค่าประกันสังคมสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_social_amt" type="text" style="text-align:right"  id="t_social_amt" value="<?Php print number_format($t_social_amt,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF">
	        <div align="right"><strong>รายการหักอื่นๆสะสม : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_other_deduct" type="text" style="text-align:right"  id="t_other_deduct" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_other_deduct,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong>ค่าปรับเอกสารไม่ครบสะสม   : </strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_fine_incomplete" type="text" style="text-align:right"  id="t_fine_incomplete" onchange="dokeyup(this,event);" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);cal_income();" value="<?Php print number_format($t_fine_incomplete,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF">
	        <div align="right"><strong><font color="#FF0000">จำนวนเงินหักรวมสะสม : </font></strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_total_deduct" type="text" style="text-align:right"  id="t_total_deduct" value="<?Php print number_format($t_total_deduct,2)  ?>" size="30" readonly="readonly" />
	        </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#CCFFFF">
		      <div align="right"><strong><font color="red">ภาษีและประกันสังคมสะสม : </font></strong> <strong>
		        </label>
		        </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_total_tax_so" type="text" style="text-align:right"  id="t_total_tax_so" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"onkeyup="dokeyup(this,event);" value="<?Php print number_format($t_total_tax_so,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
		    <td bgcolor="#CCFFFF"><div align="right"><strong><font color="blue">รับสุทธิสะสม : </font></strong> <strong>
		      </label>
		      </strong></div></td>
		    <td bgcolor="#F2F2F2"><div align="left">
		      <input name="t_total_net" type="text" style="text-align:right"  id="t_total_net" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"onkeyup="dokeyup(this,event);" value="<?Php print number_format($t_total_net,2)  ?>" size="30" readonly="readonly"/>
		      </div></td>
	      </tr>
	
			  <tr>
			    <td bgcolor="#CCFFFF"><label class="description" for="element_4">
			      <div align="right">หมายเหตุ :
			      </label>
			      </div></td>
			    <td colspan="2" bgcolor="#F2F2F2"><div align="left">
			      <textarea class="element textarea small" name="salary_note" style="width:98%" id="salary_note"><?Php print $salary_note ?></textarea>
			      </div></td>
                  <td colspan="2" bgcolor="#F2F2F2"><div align="left">
			     <input id="close" class="button_text" type="button" value="บันทึก" onclick="ins_salary()" style='width:100px; height:50px'/>
			      </div></td>
		      </tr>
			  </table>
		<center><input id="close2" class="button_text" type="button" value="  พิมพ์สลิปเงินเดือน  " onClick="window.open('frm_slip_pdf.php?mm=<?php echo $mm_l ?>&yy=<?php echo $yy_l ?>&id_user=<?php echo $id_user ?>')" style='width:150px; height:50px'/></center>
<input name="social_rate" type="hidden" id="social_rate" value="<?php echo $social_rate ?>" />
<input name="sick_leave_remain" type="hidden" id="sick_leave_remain" value="<?php echo $sick_leave_remain ?>" />
				<input name="vacation_leave_remain" type="hidden" id="vacation_leave_remain" value="<?php echo $vacation_leave_remain ?>" />
                <input name="busi_leave_remain" type="hidden" id="busi_leave_remain" value="<?php echo $busi_leave_remain ?>" />
				<input name="salary_type" type="hidden" id="salary_type" value="<?php echo $salary_type ?>" />
                <input name="work_status" type="hidden" id="work_status" value="<?php echo $work_status ?>" />
                <input name="pay_type" type="hidden" id="pay_type" value="<?php echo $pay_type ?>" />
                <input name="bank_acc_type" type="hidden" id="bank_acc_type"  value="<?php echo $bank_acc_type ?>" />
                
                

<script type="text/javascript">
<?php 
//เมื่อเป็นเดือนปัจจุบัน และ user บันทึกแล้ว ไม่ต้องคำนวณใหม่
if($mm_l==date('m') && $yy_l == date('Y') && $f_user_app==1){ ?>

<?php }else{ ?>
cal_income();
<?php } ?>
function cal_income(){
	
	var salary_amt = document.getElementById('salary_amt').value.replace(/,/g,'') ;
	var cost_of_living = document.getElementById('cost_of_living').value.replace(/,/g,'');
	var diligent = document.getElementById('diligent').value.replace(/,/g,'');
	var ot = document.getElementById('ot').value.replace(/,/g,'');
	var commission = document.getElementById('commission').value.replace(/,/g,'');
	var other_income = document.getElementById('other_income').value.replace(/,/g,'');
	var fare = document.getElementById('fare').value.replace(/,/g,'');
	var tel_income = document.getElementById('tel_income').value.replace(/,/g,'');
	var depreciation = document.getElementById('depreciation').value.replace(/,/g,'');
	
	var social_rate = document.getElementById('social_rate').value.replace(/,/g,'') ;
	
	var tax = document.getElementById('tax').value.replace(/,/g,'');
	var fine_incomplete = document.getElementById('fine_incomplete').value.replace(/,/g,'');
	var fine_late = document.getElementById('fine_late').value.replace(/,/g,'');
	var other_deduct = document.getElementById('other_deduct').value.replace(/,/g,'');
	var bonus = document.getElementById('bonus').value.replace(/,/g,'');
	var t_total_tax_so = document.getElementById('t_total_tax_so').value.replace(/,/g,'');
	var s_p_day = document.getElementById('s_p_day').value.replace(/,/g,'');
	
	if(salary_amt==''){
	  salary_amt = 0;
  }
    if(cost_of_living==''){
	 cost_of_living = 0;
  }
    if(salary_amt==''){
	  salary_amt = 0;
  }
    if(diligent==''){
	   diligent = 0;
  }
    if(ot==''){
	   ot = 0;
  }
    if(commission==''){
	   commission = 0;
  }
    if(other_income==''){
	 other_income = 0;
  }
     if( fare==''){
	   fare = 0;
  }
     if(tel_income==''){
	   tel_income = 0;
  }
     if(depreciation==''){
	   depreciation = 0;
  }
     if(tax==''){
	   tax = 0;
  }
     if(fine_incomplete==''){
	   fine_incomplete = 0;
  }
     if(fine_late==''){
	   fine_late = 0;
  }
      if(other_deduct==''){
	   other_deduct = 0;
  }
       if(bonus==''){
	     bonus = 0;
  }
   if(t_total_tax_so==''){
	     t_total_tax_so = 0;
  }
	salary_amt =  parseFloat(salary_amt);
	cost_of_living =  parseFloat(cost_of_living); 
	diligent =  parseFloat(diligent); 
	ot =  parseFloat(ot); 
	commission =  parseFloat(commission); 
	other_income =  parseFloat(other_income); 
	fare =  parseFloat(fare); 
	tel_income =  parseFloat(tel_income); 
	depreciation =  parseFloat(depreciation); 
	
	tax =  parseFloat(tax); 
	fine_incomplete =  parseFloat(fine_incomplete); 
	fine_late =  parseFloat(fine_late); 
	other_deduct =  parseFloat(other_deduct); 
	
	social_rate = parseFloat(social_rate);
	bonus =  parseFloat(bonus); 
	t_total_tax_so =  parseFloat(t_total_tax_so); 
	s_p_day =  parseFloat(s_p_day); 
	//social_amt =  parseFloat(social_amt);
	var salary_amt_social = (salary_amt+cost_of_living) ; 
	if(salary_amt_social>15000)
	salary_amt_social = 15000 ;
	
	var cal_social = salary_amt_social*(social_rate/100);
// คำนวณ ภาษี

	if(document.getElementById("user_cal_tax").checked==false){ //ให้ระบบคำนวณให้
	var tax_exp_deduct_percent = <?php echo $tax_exp_deduct_percent ?>; // % ค่าใช้จ่าย
	var tax_exp_deduct_max = <?php echo $tax_exp_deduct_max ?>; // ค่าใช้จ่าย สูงสุด
	var tax_private_deductible = <?php echo $tax_private_deductible ?>; //ค่าลดหย่อนส่วนตัว
	var salary_tax = (salary_amt+cost_of_living) ; // รายได้ประจำ
	<?php 
	
	
	if(($month_new_user!=12) && ($yy_l==$year_new_user)){//เมื่อเป็นพนักงานใหม่ ?>
	var month_remain = 13-<?php echo $month_new_user ?> ; //เดือนคงเหลือ
	<?php }else { ?>
	//พนักงานเก่า
	var month_remain = 12;
	
	<?php } ?>
	
	var salary_tax_year = (month_remain*salary_tax); // รายได้ประจำ ต่อปี
	var tax_exp_deduct = (tax_exp_deduct_percent/100)*salary_tax_year;
	if(tax_exp_deduct>tax_exp_deduct_max)//ถ้าค่าใช้จ่ายมากกว่า 60000
	tax_exp_deduct = tax_exp_deduct_max ;
	var tax_social_deduct =  (month_remain*cal_social);//ค่าลดหย่อนประกันสังคม ต่อปี
	var salary_tax_year_after_deduct = salary_tax_year - tax_exp_deduct - tax_social_deduct - tax_private_deductible; // รายได้สุทธิ หลังหัก ค่าใช้จ่าย 60000 ค่าประกันสังคม ค่าลดหย่อนส่วนตัว
	//var salary_tax_year_net = 10000;
	//var tax_percent = 10;
	//var tax_amt = (salary_tax_year_net*(tax_percent/100));
	var tax_amt = calculateTax(salary_tax_year_after_deduct);
	var tax_per_month = tax_amt/month_remain ;
	}else{
		tax_per_month =tax ;
	}
	var cal_income =  parseFloat((salary_amt+cost_of_living+diligent+ot+commission+other_income+fare+tel_income+depreciation+bonus ));
	var cal_deduct =  parseFloat((tax_per_month+cal_social+fine_incomplete+fine_late+other_deduct));
	s_p_day = (salary_amt+cost_of_living)/<?php echo $last_date_month ?> ;
	//alert(fine_incomplete);
	var cal_net =  parseFloat(cal_income-cal_deduct);
	if(cal_net>=0){
	 document.getElementById('total_income').value=addCommas(cal_income.toFixed(2));
	 document.getElementById('social_amt').value=addCommas(cal_social.toFixed(2));
	 document.getElementById('total_deduct').value=addCommas(cal_deduct.toFixed(2));
	 document.getElementById('total_net').value=addCommas(cal_net.toFixed(2));
	  document.getElementById('s_p_day').value=addCommas(s_p_day.toFixed(2));
	 if(document.getElementById("user_cal_tax").checked==false){ //ให้ระบบคำนวณให้
	 document.getElementById('tax').value=addCommas(tax_per_month.toFixed(2));
	 }

	}else {
		//alert(' จำนวนเงินสุทธิต้องมากกว่า 0 !!');
	// document.getElementById('total_income').value='0';	
	}
	if(document.getElementById("user_cal_acc").checked==false){ //ให้ระบบคำนวณให้
	cal_acc();
	
	}
	//document.getElementById('show_acc').style.display ='none';
	//document.getElementById('saveForm').style.display ='none';
}
	function cal_acc(){
	
	var salary_amt = document.getElementById('salary_amt').value.replace(/,/g,'') ;
	var cost_of_living = document.getElementById('cost_of_living').value.replace(/,/g,'');
	var diligent = document.getElementById('diligent').value.replace(/,/g,'');
	var ot = document.getElementById('ot').value.replace(/,/g,'');
	var commission = document.getElementById('commission').value.replace(/,/g,'');
	var other_income = document.getElementById('other_income').value.replace(/,/g,'');
	var fare = document.getElementById('fare').value.replace(/,/g,'');
	var tel_income = document.getElementById('tel_income').value.replace(/,/g,'');
	var depreciation = document.getElementById('depreciation').value.replace(/,/g,'');
	
	var social_amt = document.getElementById('social_amt').value.replace(/,/g,'') ;
	
	var tax = document.getElementById('tax').value.replace(/,/g,'');
	var fine_incomplete = document.getElementById('fine_incomplete').value.replace(/,/g,'');
	var fine_late = document.getElementById('fine_late').value.replace(/,/g,'');
	var other_deduct = document.getElementById('other_deduct').value.replace(/,/g,'');
	var bonus = document.getElementById('bonus').value.replace(/,/g,'');
	var t_total_tax_so = document.getElementById('t_total_tax_so').value.replace(/,/g,'');
	
	
	if(salary_amt==''){
	  salary_amt = 0;
  }
    if(cost_of_living==''){
	 cost_of_living = 0;
  }
    if(salary_amt==''){
	  salary_amt = 0;
  }
    if(diligent==''){
	   diligent = 0;
  }
    if(ot==''){
	   ot = 0;
  }
    if(commission==''){
	   commission = 0;
  }
    if(other_income==''){
	 other_income = 0;
  }
     if( fare==''){
	   fare = 0;
  }
     if(tel_income==''){
	   tel_income = 0;
  }
     if(depreciation==''){
	   depreciation = 0;
  }
     if(tax==''){
	   tax = 0;
  }
     if(fine_incomplete==''){
	   fine_incomplete = 0;
  }
   if(fine_late==''){
	   fine_late = 0;
  }
      if(other_deduct==''){
	     other_deduct = 0;
  }
     if(bonus==''){
	     bonus = 0;
  }
   if(t_total_tax_so==''){
	     t_total_tax_so = 0;
  }
  
	salary_amt =  parseFloat(salary_amt); 
	cost_of_living =  parseFloat(cost_of_living); 
	diligent =  parseFloat(diligent); 
	ot =  parseFloat(ot); 
	commission =  parseFloat(commission); 
	other_income =  parseFloat(other_income); 
	fare =  parseFloat(fare); 
	tel_income =  parseFloat(tel_income); 
	depreciation =  parseFloat(depreciation); 
	
	tax =  parseFloat(tax); 
	fine_incomplete =  parseFloat(fine_incomplete); 
	fine_late =  parseFloat(fine_late); 
	other_deduct =  parseFloat(other_deduct); 
	
	social_amt =  parseFloat(social_amt);
	//social_amt =  parseFloat(social_amt);
	bonus =  parseFloat(bonus); 
	t_total_tax_so =  parseFloat(t_total_tax_so); 
	var cal_income =  parseFloat($('#total_income').val().replace(/,/g,''));
	var cal_deduct =  parseFloat($('#total_deduct').val().replace(/,/g,''));
	//alert(fine_incomplete);
	var cal_net =   parseFloat($('#total_net').val().replace(/,/g,''));
	if(cal_net>=0){
		document.getElementById('t_total_tax_so').value=addCommas( parseFloat(<?php echo $t_total_tax_so ?>+social_amt+tax).toFixed(2));
	 document.getElementById('t_total_income').value=addCommas( parseFloat(<?php echo $t_total_income ?>+cal_income+bonus).toFixed(2));
	 document.getElementById('t_social_amt').value=addCommas( parseFloat(<?php echo $t_social_amt ?>+social_amt).toFixed(2));
	 document.getElementById('t_total_deduct').value=addCommas( parseFloat(<?php echo $t_total_deduct ?>+cal_deduct).toFixed(2));
	 document.getElementById('t_total_net').value=addCommas( parseFloat(<?php echo $t_total_net ?>+cal_net+bonus).toFixed(2));

	 document.getElementById('t_salary_amt').value=addCommas(parseFloat(<?php echo $t_salary_amt ?>+salary_amt).toFixed(2));
	 document.getElementById('t_cost_of_living').value=addCommas(parseFloat(<?php echo $t_cost_of_living ?>+cost_of_living).toFixed(2));
	 document.getElementById('t_diligent').value=addCommas(parseFloat(<?php echo $t_diligent ?>+diligent).toFixed(2));
	 document.getElementById('t_ot').value=addCommas(parseFloat(<?php echo $t_ot ?>+ot).toFixed(2));
	 document.getElementById('t_commission').value=addCommas(parseFloat(<?php echo $t_commission ?>+commission).toFixed(2));
	 document.getElementById('t_other_income').value=addCommas(parseFloat(<?php echo $t_other_income ?>+other_income).toFixed(2));
	 document.getElementById('t_fare').value=addCommas(parseFloat(<?php echo $t_fare ?>+fare).toFixed(2));
	 document.getElementById('t_tel_income').value=addCommas(parseFloat(<?php echo $t_tel_income ?>+tel_income).toFixed(2));
	 document.getElementById('t_depreciation').value=addCommas(parseFloat(<?php echo $t_depreciation ?>+depreciation).toFixed(2));
	 
	 document.getElementById('t_tax').value=addCommas(parseFloat(<?php echo $t_tax ?>+tax).toFixed(2));
	 document.getElementById('t_fine_incomplete').value=addCommas(parseFloat(<?php echo $t_fine_incomplete ?>+fine_incomplete).toFixed(2));
	 document.getElementById('t_fine_late').value=addCommas(parseFloat(<?php echo $t_fine_late ?>+fine_late).toFixed(2));
	 document.getElementById('t_other_deduct').value=addCommas(parseFloat(<?php echo $t_other_deduct ?>+other_deduct).toFixed(2));
	}else {
		alert(' จำนวนเงินสุทธิต้องมากกว่า 0 !!');
	// document.getElementById('t_total_income').value='0';	
	}
	
	
}
	
function user_cal_acc(){
	
	if(document.getElementById("user_cal_acc").checked==false){ //ให้ระบบคำนวณให้
	cal_income();
	document.getElementById('t_total_tax_so').readOnly=true;
	 document.getElementById('t_total_income').readOnly=true;
	 document.getElementById('t_social_amt').readOnly=true;
	 document.getElementById('t_total_deduct').readOnly=true;
	 document.getElementById('t_total_net').readOnly=true;

	 document.getElementById('t_salary_amt').readOnly=true;
	 document.getElementById('t_cost_of_living').readOnly=true;
	 document.getElementById('t_diligent').readOnly=true;
	 document.getElementById('t_ot').readOnly=true;
	 document.getElementById('t_commission').readOnly=true;
	 document.getElementById('t_other_income').readOnly=true;
	 document.getElementById('t_fare').readOnly=true;
	 document.getElementById('t_tel_income').readOnly=true;
	 document.getElementById('t_depreciation').readOnly=true;
	 
	 document.getElementById('t_tax').readOnly=true;
	 document.getElementById('t_fine_incomplete').readOnly=true;
	 document.getElementById('t_fine_late').readOnly=true;
	 document.getElementById('t_other_deduct').readOnly=true;
	}else{
		
		
	
	
	document.getElementById('t_total_tax_so').readOnly=false;
	 document.getElementById('t_total_income').readOnly=false;
	 document.getElementById('t_social_amt').readOnly=false;
	 document.getElementById('t_total_deduct').readOnly=false;
	 document.getElementById('t_total_net').readOnly=false;

	 document.getElementById('t_salary_amt').readOnly=false;
	 document.getElementById('t_cost_of_living').readOnly=false;
	 document.getElementById('t_diligent').readOnly=false;
	 document.getElementById('t_ot').readOnly=false;
	 document.getElementById('t_commission').readOnly=false;
	 document.getElementById('t_other_income').readOnly=false;
	 document.getElementById('t_fare').readOnly=false;
	 document.getElementById('t_tel_income').readOnly=false;
	 document.getElementById('t_depreciation').readOnly=false;
	 
	 document.getElementById('t_tax').readOnly=false;
	 document.getElementById('t_fine_incomplete').readOnly=false;
	 document.getElementById('t_fine_late').readOnly=false;
	 document.getElementById('t_other_deduct').readOnly=false;
	}
}
	
	function ins_salary(){

	

 $.post("salary_api.php", { 
  cmd: 'update',
  					user_id_slip: $('#user_id_slip').val(),
					user_fullname: $('#user_fullname').val(),
					user_dep: $('#user_dep').val(),
					user_div: $('#user_div').val(),
					user_pos: $('#user_pos').val(),
					user_start: $('#user_start').val(),
					bank_name: $('#bank_name').val(),
					comp_name: $('#comp_name').val(),
					bank_acc_type: $('#bank_acc_type').val(),
					salary_type: $('#salary_type').val(),
  
            		salary_amt: $('#salary_amt').val().replace(/,/g,''),
					cost_of_living: $('#cost_of_living').val().replace(/,/g,''),
					diligent: $('#diligent').val().replace(/,/g,''),
					ot: $('#ot').val().replace(/,/g,''),
					commission: $('#commission').val().replace(/,/g,''),
					
					fare: $('#fare').val().replace(/,/g,''),
					
					tel_income: $('#tel_income').val().replace(/,/g,''),
					depreciation: $('#depreciation').val().replace(/,/g,''),
					other_income: $('#other_income').val().replace(/,/g,''),
					total_income: $('#total_income').val().replace(/,/g,''),
					social_rate: $('#social_rate').val().replace(/,/g,''),
					tax: $('#tax').val().replace(/,/g,''),
					fine_incomplete: $('#fine_incomplete').val().replace(/,/g,''),
					fine_late: $('#fine_late').val().replace(/,/g,''),
					
					other_deduct: $('#other_deduct').val().replace(/,/g,''),
					total_deduct: $('#total_deduct').val().replace(/,/g,''),
					
					
				
				    sick_leave_remain: $('#sick_leave_remain').val().replace(/,/g,''), 
			
				    vacation_leave_remain: $('#vacation_leave_remain').val().replace(/,/g,''), 
				
				    busi_leave_remain: $('#busi_leave_remain').val().replace(/,/g,''),
					


					pay_type: $('#pay_type').val(),
				    salary_note: $('#salary_note').val(),
					social_rate: $('#social_rate').val().replace(/,/g,''), 
					social_amt: $('#social_amt').val().replace(/,/g,''),
					
					total_net: $('#total_net').val().replace(/,/g,''), 
				    t_salary_amt: $('#t_salary_amt').val().replace(/,/g,''), 
					t_cost_of_living: $('#t_cost_of_living').val().replace(/,/g,''), 
				    t_diligent: $('#t_diligent').val().replace(/,/g,''), 
				    t_ot: $('#t_ot').val().replace(/,/g,''), 
				    t_commission: $('#t_commission').val().replace(/,/g,''), 
				    t_other_income: $('#t_other_income').val().replace(/,/g,''), 
				    t_fare: $('#t_fare').val().replace(/,/g,''), 
				    t_tel_income: $('#t_tel_income').val().replace(/,/g,''), 
				    t_depreciation: $('#t_depreciation').val().replace(/,/g,''),
					
					t_total_income: $('#t_total_income').val().replace(/,/g,''), 
				    t_tax: $('#t_tax').val().replace(/,/g,''), 
				    t_fine_late: $('#t_fine_late').val().replace(/,/g,''), 
				    t_social_amt: $('#t_social_amt').val().replace(/,/g,''), 
				    t_other_deduct: $('#t_other_deduct').val().replace(/,/g,''), 
				    t_fine_incomplete: $('#t_fine_incomplete').val().replace(/,/g,''), 
				    t_total_deduct: $('#t_total_deduct').val().replace(/,/g,''), 
				    t_total_net: $('#t_total_net').val().replace(/,/g,''),
					bonus: $('#bonus').val().replace(/,/g,''),
					t_total_tax_so: $('#t_total_tax_so').val().replace(/,/g,''),
					bank_acc_no: $('#bank_acc_no').val(),

				    user_id_sys: '<?php echo $id_user ?>',
					
					 
					
					
					id_user: '<?php echo $id_user ?>',
					mm: '<?php echo $mm_l ?>',
					yy: '<?php echo $yy_l ?>'


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
	 
	 
	 function calculateTax(netIncome)
{
	
    if(isNaN(netIncome))
    {
        return NaN;
    }
	
    var spread = new Array(<?php echo $tax_rate1 ?>,-1);
    var revenue = new Array();
    var taxRate = new Array(<?php echo $tax_percent1 ?>);
    var incomeTax = new Array();
    
    for(i=0; i<spread.length; i++)
    {
        var sumOfRevenue = 0;
        for(j=0; j<i; j++)
        {
            sumOfRevenue += revenue[j];
        }
        
        if((netIncome-sumOfRevenue) >= spread[i])
        {
            if(i<(spread.length - 1))
            {
                revenue[i] = spread[i];
            }
            else
            {
                revenue[i] = (netIncome-sumOfRevenue);
            }
        }
        else
        {
            if(i<(spread.length - 1))
            {
                revenue[i] = (netIncome-sumOfRevenue);
            }
            else
            {
                revenue[i] = 0;
            }
        }
        incomeTax[i] = revenue[i]*taxRate[i];
    }
    
    var sumOfIncomeTax = 0;
    for(i in incomeTax)
    {
        sumOfIncomeTax += incomeTax[i];
    }
    return sumOfIncomeTax;
}
	 </script>