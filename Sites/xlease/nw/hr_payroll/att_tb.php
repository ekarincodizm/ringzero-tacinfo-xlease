
 <style type="text/css">

table.t2 tr:hover td {
	background-color:#FFFFCC;
}

</style>



 <table id="<?php if($tb22==1) echo "t22" ?>" class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
			 <td align="center">วันที่</td>
             <td align="center">รายละเอียด</td>
                
               
				<td align="center">เข้า(เช้า)</td>
				<td align="center">ออก(พักเที่ยง)</td>
                <td align="center">เข้า(บ่าย)</td>
               
				<td>ออก(เย็น)</td>
              <td colspan="2" >ลา</td>
              <td title="จำนวนชั่วโมงที่ทำงาน">ชั่วโมงที่ทำงาน</td>
              <td title="เบี้ยขยัน">เบี้ยขยัน</td>
               <td title="หักมาสาย">หักมาสาย</td>
			</tr>
<?php
 
	$strStartDate = "$yy-$mm-01";
	$endM = date("t",strtotime("$yy-$mm-01")); // วันที่สุดท้ายของเดือน

	$strEndDate = "$yy-$mm-$endM";
	
	//$strStartDate = "2012-08-08";
	//$strEndDate = "2012-08-09";
	$intWorkDay = 0;
	$intHoliday = 0;
	$intPublicHoliday = 0;
	$int_late_mor =0;
	$int_late_af =0;
	$int_late_ev =0;
	$late_fine_amt =0;
	$total_late_n_c =0; //จำนวนมาสายแบบปกติ สะสม 3 ครั้ง
	$total_late_a_c =0; //จำนวนมาสายแบบพิเศษ สะสม 1 ครั้ง
	$lar_all_day_c =0;
	$busi_leave_c=0;
	$sick_leave_c=0;
	$f_app =0;//วันนั้นมีการรออนุมัติหรือไม่
	$vacation_leave_c=0;
		$total_late_m_n_c =0; //จำนวนมาสายแบบปกติ สะสม 3 ครั้ง กลางวัน total_late_midday_normal_count
	$total_late_m_a_c =0; //จำนวนมาสายแบบพิเศษ สะสม 1 ครั้ง กลางวัน
	$all_con=0;//เบี้ยขยันถูกเงื่อนไข
	$work_day_count=0; //วันที่มาทำงาน
	$t_h_all = 0;
	$t_m_all =0;
	$intTotalDay = ((strtotime($strEndDate) - strtotime($strStartDate))/  ( 60 * 60 * 24 )) + 1; 
	$k=0;
	while (strtotime($strStartDate) <= strtotime($strEndDate)) {
		
		$DayOfWeek = date("w", strtotime($strStartDate));
		
		 $i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				$c_week_hol =0;
		for($w=0;$w<($hol_week_count);$w++){
			
			if($DayOfWeek == $hol_week_code[$w] )  // 0 = Sunday, 6 = Saturday;
		{
			$intHoliday++;
			$c_week_hol++;
			//echo "$strStartDate = <font color=red>Holiday</font><br>";
			$hol_txt= "วันหยุด";
			$hol_tooltip= "วันหยุด";
			echo "<tr  bgcolor=#FF7D7D align=center>";
			
			
		}
				
				
			}
	
		
		
		
		
		if(CheckPublicHoliday($strStartDate)!="")
		{
			$intPublicHoliday++;
			//echo "$strStartDate = <font color=orange>Public Holiday</font><br>";
			$hol_txt= "วันหยุดประจำปี";
			$hol_tooltip= CheckPublicHoliday($strStartDate);
			echo "<tr bgcolor=orange align=center>";
			
				
		}
		else
		{
			if($c_week_hol==0){
			$intWorkDay++;
			$hol_txt= "";
			$hol_tooltip= "วันทำงานปกติ";
			}
			
			//echo "$strStartDate = <b>Work Day</b><br>";
		}
		//$DayOfWeek = date("l", strtotime($strStartDate)); // return Sunday, Monday,Tuesday....

			$qry_fr2=pg_query("SELECT a.leave_type,b.type_name,a.memo,a.leave_time_type,a.h_amt FROM \"hr_user_leave\" a left join hr_leave_type b on a.leave_type = b.type_id where a.leave_date = '$strStartDate' and a.id_user = '$id_user' ");
			$nub3=pg_num_rows($qry_fr2); //ลา

			if($nub3>0){
				
				
				
							if($sql_row5=pg_fetch_array($qry_fr2)){
					$leave_type = $sql_row5['leave_type'];
					$type_name = $sql_row5['type_name'];
					$leave_memo = $sql_row5['memo'];
					$leave_time_type = $sql_row5['leave_time_type'];
					$h_amt = $sql_row5['h_amt'];
					
						if($leave_type!='5'){ //ไม่ใช่ลากิจบริษัท
							
						
					if($leave_time_type=='1'){//ลา 1 วัน
					
					$lar_n=1; //ลาทั้งวัน
					$lar_des = $type_name;
							if($leave_type=='1')$busi_leave_c++;
							if($leave_type=='2')$sick_leave_c++;
							if($leave_type=='3')$vacation_leave_c++;
							$bg_col = "#CCCCFF";
					}else if($leave_time_type=='0'){ // ลาเป็นชั่วโมง
					
							$dt_51 = $type_name;
							$dt_52 = $h_amt." ชั่วโมง ";
							$memo5 = $leave_memo;
							$lar_h_n=1; //ลาแบบ ชั่วโมง
					
							$late_fine_amt = $late_fine_amt + $late_fine; //จำนวนเงินที่ปรับ
							$total_late_a_c ++;  //เก็บสะสม มาสายแบบพิเศษ ให้โอกาส 1ครั้ง
							$lar_all_day_c=1;
							if($leave_type=='1')$busi_leave_c+=($h_amt)/8;
							if($leave_type=='2')$sick_leave_c+=($h_amt)/8;
							if($leave_type=='3')$vacation_leave_c+=($h_amt)/8;
							$bg_col = "#CCCCFF";
							}
							
							$all_con=1; //ไม่ให้เบี้ยขยัน
						
							}
							}else{ //ลากิจบริษัท
								
								$hol_txt= "ลากิจบริษัท";
			$hol_tooltip= "ลากิจที่ไปทำงานเกี่ยวกับบริษัท";
			echo "<tr  bgcolor=#FF7D7D align=center>";
								
							}
			}
		
		$qry_fr=pg_query("SELECT a.id,a.datetime,a.memo,a.type_id,c.ac,d.approved1,d.approved2,d.non_app,a.img_id,a.s_p FROM \"LogsTimeAtt$yy\" a left 
			join \"Vfuser\" c on a.user_id=c.id_user left join \"LogsTimeAttApprove\" d on d.id_att=a.id WHERE a.datetime::character varying LIKE '$strStartDate%' and a.user_id='$id_user' order by a.datetime ");
	
			$nub=pg_num_rows($qry_fr); 

			if($nub>0){
			while($sql_row4=pg_fetch_array($qry_fr)){
				//$cpro_name = $sql_row4['cpro_name'];
					$id = $sql_row4['id'];
					//$user_id = $sql_row4['user_id'];
					$datetime= $sql_row4['datetime'];
					//echo $datetime."<br>";
					$type_id =$sql_row4['type_id']; 
					
					$memo =$sql_row4['memo']; 
					$ac = $sql_row4['ac'];
					$approved1=  $sql_row4["approved1"];
   					$approved2=  $sql_row4["approved2"];
					$non_app=  $sql_row4["non_app"];
					$img_id=  $sql_row4["img_id"];
					$date = substr($datetime,0,10);
					$s_p = $sql_row4["s_p"];
					if($s_p!=1){//ปกติ
					$time = substr($datetime,11,8);
					$time2 = substr($datetime,0,19);
					}else{
						if($approved2){//อนุมัติการลงเวลาแบบพิเศษแล้ว
					$time = substr($datetime,11,8);
					$time2 = substr($datetime,0,19);
					$bg_col = "#58FAD0";
				
						}else{
						$time = "รอการอนุมัติ";	
							$bg_col = "#81F7F3";
						$f_app =1;
						}
						
						
						if($non_app){
					    $time = "ไม่อนุมัติ";	
						$bg_col = "#F78181";
						$f_app =1;
						}
						
				
					}

						if($type_id==1){$dt_1 = $time;$memo1 = $memo;$dt_1c = $time2;$bg_col11 =$bg_col;}
						else if($type_id==2){$dt_2 = $time;$memo2 = $memo;$dt_2c = $time2;$bg_col22 =$bg_col;}
						else if($type_id==3){$dt_3 = $time;$memo3 = $memo;$dt_3c = $time2;$bg_col33 =$bg_col;}
						else if($type_id==4){$dt_4 = $time;$memo4 = $memo;$dt_4c = $time2;$bg_col44 =$bg_col;}
						//else { 
							/*
							if($dt_1=="" && $dt_4==""){
								$lar_all_day_c =1;
	
							}
							*/

							
						//}
						

				
						
	}
	
				//$bg_col = "#81F7F3";
				
	//if($date_old=="")$date_old = $date;
		//if($date!=$date_old ){
		//$date_old = $date;
	
	if($lar_n!=1){	
$t1=$dt_1c;//เช้า
$t_h="";$t_m="";
//เช็คเวลาทำงาน รอบ 2
	if($dt_1c!='' && $dt_2c!='' && $dt_3c!='' && $dt_4c!='' && $f_app==0){	
	
if($midday_exp==1){ //เช็คเกอร์ ไม่นับช่วงกลางวัน
	$t2=$dt_4c;//เย็น
	
	$time_n1=dateDiv($t1,$t2);
	$t_h = $time_n1['H'];
	$t_m = $time_n1['M'];
}else{//ไม่ใช่เช็คเกอร์
	
$t2=$dt_2c;//เที่ยง
$time_n1=dateDiv($t1,$t2);//ชั่วโมงช่วงเช้า

$t1=$dt_3c;//บ่าย
$t2=$dt_4c;//เย็น
$time_n2=dateDiv($t1,$t2); //ชั่วโมงช่วงบ่าย
$t_h = $time_n1['H']+$time_n2['H'];
$t_m = $time_n1['M']+$time_n2['M'];
}
//echo  $strStartDate." ".$t_m."<br>";
if($t_m>59){
	$t_h=$t_h+1;
	$t_m = $t_m-59;
}

$t_h_txt = $t_h.":".$t_m;
$t_h_all = $t_h_all+$t_h;
$t_m_all = $t_m_all+$t_m;

	}else{
		
		$t_h_txt="";
		$f_t_h = 1;
	}

//เช็คมาสายตอนบ่าย
if($midday_exp!=1){ //เช็คเกอร์ ไม่นับช่วงกลางวัน
if(($dt_2c=="" || $dt_3c=="") && $c_late3!=1){ //รออนุมัติ หรือไม่่แสดงเวลา
	 $c_late3 =1;  ;
		 $bg_col1 = "bgcolor=#FFCCCC";
		 $f_late3=1;
		 $int_late_af++;//จำนวนสายช่วงบ่าย
		
		$late_fine_amt = $late_fine_amt + $late_fine_af;//ค่าปรับมาสายตอนบ่าย
}else{
$t1=$dt_2c;
$t2=$dt_3c;

$time_n3=dateDiv($t1,$t2);
//เกิน 1.05
//if($time_n3['H']>=1){

	 if($time_n3['M']>($late_time_af) && $time_n3['H']>=1){ //$late_time_af = 5
	 
		$c_late3 =1;  ;
		 $bg_col1 = "bgcolor=#FFCCCC";
		 $f_late3=1;
		 $int_late_af++;//จำนวนสายช่วงบ่าย

		$late_fine_amt = $late_fine_amt + $late_fine_af;//ค่าปรับมาสายตอนบ่าย
		 //$total_late_fine_amt +=  $late_fine_amt;
		 
		
	 }
	 
//}
}
}
//เช็คตอนเย็น
//$bf_clock_out1= $sql_row4['bf_clock_out1']; 
					//$bf_clock_out_start= $sql_row4['bf_clock_out_start']; 
				  //  $bf_clock_out_end= $sql_row4['bf_clock_out_end']; 
				   // $bf_clock_out_amt= $sql_row4['bf_clock_out_amt']; 

if($dt_4c=="" ){ //รออนุมัติ หรือไม่่แสดงเวลา
$f_late4=1;
$int_late_ev++;//จำนวนสายช่วงเย็น
$late_fine_amt = $late_fine_amt + $bf_clock_out_amt;//จำนวนเงินที่ปรับ
$total_late_n_c ++;  //เก็บสะสม มาสายแบบธรรมดา ให้โอกาส 3ครั้ง
$bg_col1 = "bgcolor=#FFCCCC";
$late_fine_c++;
}else{
$t2=$date." ".$bf_clock_out1; //16.00
$t1=$dt_4c;
//echo $t1."<br>";
//echo$t2;
//echo $t1,$t2;
$time_n5=dateDiv($t1,$t2);//ถ้าเป็นบวก แสดงว่า กลับก่อน 16.00
//หักเงินตอนเย็น 
if($time_n5['H']==0 && $time_n5['M']>=0 && $time_n5['S']>=0){

	$late_fine_c++;
	$bg_col1 = "bgcolor=#FFCCCC";
	$f_late4=1;
	$total_late_a_c ++;  //เก็บสะสม มาสายแบบพิเศษ ให้โอกาส 1ครั้ง
	$int_late_ev++;//จำนวนสายช่วงเย็น
	
	$late_fine_amt = $late_fine_amt + $bf_clock_out_amt;//ค่าปรับกลับก่อน ตอนเย็น

}else {
//วันเสาร์
if($DayOfWeek==6 && $u_sex=="0"){$t2=$date." ".$clock_out_sat; // เวลาเลิกงาน เฉพาะวันเสาร์
$t1=$dt_4c;
$time_n6=dateDiv($t1,$t2);

//print_r($time_n6);
if($time_n6['M']>0){//ออกก่อนเวลา
$f_late4=1;
$late_fine_c++;
$int_late_ev++;//จำนวนสายช่วงเย็น
$late_fine_amt = $late_fine_amt + $bf_clock_out_amt;//จำนวนเงินที่ปรับ
$total_late_n_c ++;  //เก็บสะสม มาสายแบบธรรมดา ให้โอกาส 3ครั้ง
$bg_col1 = "bgcolor=#FFCCCC";
}

}
else {

$t2=$date." ".$bf_clock_out_start; //17.00
$t1=$dt_4c;
//echo $t1."<br>";
//echo$t2;
$time_n5=dateDiv($t2,$t1);//ถ้าเป็นบวก แสดงว่าเกิน 17.00

$t2=$date." ".$bf_clock_out_end; //17.44 หรือ 17.29
$t1=$dt_4c;
//echo $t1."<br>";
//echo$t2;
$time_n52=dateDiv($t1,$t2); //ถ้าเป็นบวก แสดงว่าออกก่อน 17.44
//หักเงินตอนเย็น  17-17.44

if($time_n5['H']==0 && $time_n5['M']>=0 && $time_n5['S']>=0 && $time_n52['H']==0 && $time_n52['M']>=0 && $time_n52['S']>=0){ //ปรับแบบธรรมดา สะสม

	$bg_col1 = "bgcolor=#FFCCCC";
	$late_fine_c++;
	$late_fine_amt = $late_fine_amt + $bf_clock_out_amt;//ค่าปรับกลับก่อน ตอนเย็น
	$total_late_n_c ++;  //เก็บสะสม มาสายแบบธรรมดา ให้โอกาส 3ครั้ง
	$f_late4=1;	
	$int_late_ev++;//จำนวนสายช่วงเย็น
}
}
}
}



if($dt_1c=="" ){ //รออนุมัติ หรือไม่่แสดงเวลา

	$f_late1 = 1;
	$late_fine_c++;
	$bg_col1 = "bgcolor=#FFCCCC";
	$int_late_mor++;//จำนวนสายช่วงเช้า
 //หักเงินแบบสะสมธรรมดา

	
	$late_fine_amt = $late_fine_amt + $late_fine ;//จำนวนเงินที่ปรับ
	
	$total_late_n_c ++;  //เก็บสะสม มาสายแบบธรรมดา ให้โอกาส 3ครั้ง
}else{
//เช็คเบี้ยขยันตอนเช้า
$t2=$date." ".$start_work;
$t1=$dt_1c;
//echo $t1."<br>";
//echo$t2;
$time_n4=dateDiv($t1,$t2);
//ได้เบี้ยขยัน
if($time_n4['H']==0 && $time_n4['M']>=0 && $dt_51=="" && $f_late3==0 && $f_late4==0){//ได้เบี้ยขยันภาคเช้า ไม่ลา ไม่สาย
$get_allo = $att_all;
$total_get_allo +=$get_allo ;
$int_att_full_month++;
}

$t2=$date." ".$late_time_start; //8.31
$t1=$dt_1c;
//echo $t1."<br>";
//echo$t2;
$time_n5=dateDiv($t2,$t1);
	//ปรับเงิน ช่วงเช้า ถ้ามาสาย
	
	$t2=$date." ".$late_time_end; //9.00
$t1=$dt_1c;
//echo $t1."<br>";
//echo$t2;
$time_n52=dateDiv($t1,$t2);
//echo print_r($time_n52).$dt_1c;
//if($time_n5['H']==0 && $time_n5['M']>=0 && $time_n5['S']>=0){ //ถ้าเป็นบวก แสดงว่ามาสาย เช้า
if($time_n5['H']==0 && $time_n5['M']>=0 && $time_n5['S']>=0 && $time_n52['H']==0 && $time_n52['M']>=0 && $time_n52['S']>=0){ //ปรับแบบธรรมดา สะสม	 เช้า
	
	//echo $late_fine."<br>";
	$f_late1 = 1;
	$late_fine_c++;
	$bg_col1 = "bgcolor=#FFCCCC";
	$int_late_mor++;//จำนวนสายช่วงเช้า
 //หักเงินแบบสะสมธรรมดา

	
	$late_fine_amt = $late_fine_amt + $late_fine ;//จำนวนเงินที่ปรับ
	
	$total_late_n_c ++;  //เก็บสะสม มาสายแบบธรรมดา ให้โอกาส 3ครั้ง

}

if($time_n52['H']<=0 && $time_n52['M']<0 ){ //ปรับแบบพิเศษ เข้างานเกิน 9.00
	$f_late1 = 1;
	$late_fine_c++;
	$bg_col1 = "bgcolor=#FFCCCC";
	$int_late_mor++;//จำนวนสายช่วงเช้า
	$late_fine_amt = $late_fine_amt + $late_fine; //จำนวนเงินที่ปรับ
	$total_late_a_c ++;  //เก็บสะสม มาสายแบบพิเศษ ให้โอกาส 1ครั้ง
	
}
else {

//$late_fine_amt ="";
//$f_late1 =0;

}
}

//$att2 = explode(".",$dt_2);
//$att3 = explode(".",$dt_3);

	//echo "<hr>";
	
}
$c_late3 =0;  ;
	$total_late_fine_amt +=  $late_fine_amt; //รวมค่าปรับสะสมทั้งเดือน
	//if($total_late_n_c<($max_late+1))$late_fine_amt="";//น้อยกว่า 3 ครั้ง ยังไม่ปรับ
	//if($total_late_n_c==($max_late+1) || $total_late_a_c==2)$late_fine_amt = $total_late_fine_amt;
	//else if($total_late_n_c>($max_late+1))$late_fine_amt = $late_fine;
	
	
if(($dt_1!="" || $dt_4!="") && $tb22==0){
								$work_day_count ++;
								if($work_day_count==1){
							
								$start_date_work = date("d",strtotime("$strStartDate")); // วันแรกที่ทำงาน
								$last_date_month = date("t",strtotime("$yy-$mm-01"));
									
								$work_inc_hol_count =	($last_date_month - $start_date_work) +1; //จำนวนวันที่ทำงาน รวมเสาร์อาทิตย์ และวันหยุดประจำปี
							
								}
	
							}
																	
					
// ปิด $lar_all_day_c!=1
if($dt_1=='')$dt_1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if($dt_2=='')$dt_2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if($dt_3=='')$dt_3="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if($dt_4=='')$dt_4="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//if($strStartDate=='2012-09-03')echo $bg_col11."1";
	?>
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}</script>
            <td align="center"><?php echo $i; ?></td>
				<td align="center"><?php echo $strStartDate;//echo $t1." ".$t2; ?></td>
                <td align="center" title="<?php echo $hol_tooltip  ?>"><?php echo $hol_txt; ?></td>
				<?php if($lar_n==1){ ?>
                 <td colspan="8" <?php if($lar_n==1){ ?>bgcolor="#CCCCFF" <?php } ?>><?php if($lar_n==1){ echo $lar_des; } ?></td>
                 <?php }else{ ?>
            <td <?php if($memo1!=""){?> bgcolor="<?php echo $bg_col11; ?>" title="<?php echo " $dt_1 (" .$memo1 ?>)" <?php } if($f_late1==1) echo $bg_col1 ;?> align="center" ><a href="javascript:popU('time_att_mgt.php?name=<?php echo $id_user ?>&datepicker=<?php echo $strStartDate;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=500');" ><strong><?php echo $dt_1 ?></strong>  &nbsp;</a></td>
                <td <?php if($memo2!=""){?> bgcolor="<?php echo $bg_col22; ?>" title="<?php echo " $dt_2 (" .$memo2 ?>)" <?php } ?> align="center"><a href="javascript:popU('time_att_mgt.php?name=<?php echo $id_user ?>&datepicker=<?php echo $strStartDate;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=500');" ><strong><?php echo $dt_2 ?></strong>  &nbsp;</a></td>
              <td <?php if($memo3!=""){?> bgcolor="<?php echo $bg_col33; ?>" title="<?php echo " $dt_3 (" .$memo3 ?>)" <?php } if($f_late3==1) echo $bg_col1 ;?>  align="center"><a href="javascript:popU('time_att_mgt.php?name=<?php echo $id_user ?>&datepicker=<?php echo $strStartDate;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=500');" ><strong><?php echo $dt_3 ?></strong>  &nbsp;</a></td>
			  <td <?php if($memo4!=""){?> bgcolor="<?php echo $bg_col44; ?>" title="<?php echo " $dt_4 (" .$memo4 ?>)" <?php }  if($f_late4==1) echo $bg_col1 ;?> align="center"><a href="javascript:popU('time_att_mgt.php?name=<?php echo $id_user ?>&datepicker=<?php echo $strStartDate;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=500');" ><strong><?php echo $dt_4 ?></strong> &nbsp;</a></td>
              <td <?php if($memo5!=""){?> bgcolor="<?php echo $bg_col55; ?>" title="<?php echo " $dt_51 $dt_52 (" .$memo5 ?>)" <?php } ?> align="left"><?php echo $dt_51 ?></td><td <?php if($memo5!=""){?> bgcolor="<?php echo $bg_col; ?>" title="<?php echo " $dt_51 $dt_52 (" .$memo5 ?>)" <?php } ?>  align="left"><?php echo $dt_52 ?></td>
				<td align="right"><?php echo $t_h_txt; ?></td>
                <td align="right"><?php echo $get_allo; ?></td>
               <?php } ?>
                <td align="right"><?php echo $late_fine_amt; ?></td>
			</tr>
           
            <?php
			$dt_1="";$memo1="";$dt_1c="";
			$dt_2="";$memo2="";$dt_2c="";
			$dt_3="";$memo3="";$dt_3c="";
			$dt_4="";$memo4="";$dt_4c="";
			$dt_51="";$memo5="";$dt_52="";
			$t_h="";$t_m="";
			$get_allo="";
			$late_fine_amt=0;
			$f_late1=0;
			 $f_late3=0;
			 $f_late4=0;
			 $lar_n=0;
			 $f_app =0;
			 $bg_col1 = "bgcolor=#CCFFCC";
			//}
			}
			else{
				
				if($hol_txt== "" && $lar_n==1){ // วันทำงาน แต่ ลา หักเงิน
				$late_fine_c++;
				$total_late_n_c++;
				$late_fine_amt = $late_fine;
				$total_late_fine_amt +=  $late_fine;
				}
				
				$dt_1="";$memo1="";$dt_1c="";
			$dt_2="";$memo2="";$dt_2c="";
			$dt_3="";$memo3="";$dt_3c="";
			$dt_4="";$memo4="";$dt_4c="";
			$dt_51="";$memo5="";$dt_52="";
				?>
			 <td align="center"><?php echo $i; ?></td>
				<td align="center"><?php echo $strStartDate; ?></td>
                 <td align="center" title="<?php echo $hol_tooltip  ?>"><?php echo $hol_txt; ?></td>
				
                
              <td colspan="8" <?php if($lar_n==1){ ?>bgcolor="#CCCCFF" <?php } ?>><?php if($lar_n==1){ echo $lar_des; }if($date_now >= $strStartDate) {?><a href="javascript:popU('time_att_mgt.php?name=<?php echo $id_user ?>&datepicker=<?php echo $strStartDate;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=500');" ><?php if($lar_n==1){ }else echo "..."; ?></a> <?php } ?></td>
                 <td align="right"><?php echo $late_fine_amt; ?></td>
			</tr>	
				
				<?php
			}
			$get_allo="";
			$late_fine_amt="";
			$strStartDate = date ("Y-m-d", strtotime("+1 day", strtotime($strStartDate)));
			$lar_all_day_c =0;
			$lar_n=0;
			}
			//echo $total_late_a_c;
			if(($total_late_n_c<=$max_late) && $total_late_a_c<=1 && $int_late_af<=$max_late_af)$total_late_fine_amt =0; //เมื่อเข้าเงื่อนไข สายไม่เกิน ช่วงเช้า+เย็น และ ช่วงกลางวัน
		//รวมเบี้ยขยัน
		if($intWorkDay==$int_att_full_month && $all_con==0)$total_all = $total_get_allo*$att_all_full_month_rate; 
		else $total_all = $total_get_allo;
		if($f_t_h!=1){
			$t_h_all2 = floor($t_m_all/60); //เอาจำนวนนาที มาบวกกัน แล้วแปลงเป็นชั่วโมง
$t_h_all = $t_h_all+$t_h_all2 ;
$t_m_all = ($t_m_all%60); //เศษนาทีคือ mod 60
			
			$t_h_txt_all="$t_h_all:$t_m_all"; }
			?>
      
				
                
                <td colspan="9" bgcolor="#99FFCC" align="right"> <strong>รวมตามเงื่อนไข:</strong></td>
				 <td align="right" bgcolor="#99FFCC" ><strong><?php echo $t_h_txt_all ?></strong></td>
                 <td align="right" bgcolor="#99FFCC" ><strong><?php echo number_format($total_get_allo);if($intWorkDay==$int_att_full_month && $all_con==0)echo " (".number_format($total_all).")"; ?></strong></td>
                 <td align="right" bgcolor="#99FFCC" ><strong><?php echo number_format($total_late_fine_amt); ?></strong></td>
			</tr>
             </table> <?php if($tb22!=1){?><div style="float:right"><b>หมายเหตุ</b> ชั่วโมงในการทำงาน 8:50 = จำนวน 8 ชั่วโมง 50 นาที</div><?php }$f_t_h=0; ?>
