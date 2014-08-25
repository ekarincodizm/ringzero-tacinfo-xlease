<?php
session_start();
require_once("../../config/config.php");
require_once("function_reminder.php");

$focusdate = pg_escape_string($_GET['focusdate']); 
$get_userid=pg_escape_string($_GET['doerid']);
$tabid=pg_escape_string($_GET['tabid']);

// กำหนดสี
$color_red = '#FA8072';
$color_orange = '#FF6600';
$color_green = '#228B22';

// ---------------------------------------------------------------------------------------------
// ถ้าไม่มีการกำหนดเวลามาให้ใช้เป็นวันทีปัจจุบัน
// ---------------------------------------------------------------------------------------------
	if ($focusdate == '--' || $focusdate == '' ||$focusdate == NULL) {
		$focusdate=nowDate();
}
	
if($tabid=='1'){//งานที่ยังไม่ได้ดำเนินการก่อนหน้านี้
		//จะไม่แสดง รายการที่ ปิดไปแล้ว
		
		$date_day= date ("Y-m-d", strtotime("+1 day", strtotime($focusdate)));
		$str_text='ก่อนวันที่ '.$date_day;
}
	else{ 
		
		$str_text='ในวันที่ '.$focusdate;
}
//ค้นหาว่า เลือกค้นหาวันที่เท่าไร
if($tabid=='1'){
	$after_date_save=$date_day;}
else if($tabid=='2'){
	$after_date_save=$focusdate;}
else if($tabid=='3'){
	$after_date_save= date ("Y-m-d", strtotime("-1 day", strtotime($focusdate)));}
else if($tabid=='4'){
	$after_date_save= date ("Y-m-d", strtotime("-2 day", strtotime($focusdate)));
}
	
//วันที่มีผลการหบุดการแจ้งเตือน
$date_stop= date ("Y-m-d", strtotime("+1 day", strtotime($focusdate)));

?>
<script type="text/javascript">
	function confirm_date(date){
		if(confirm('บันทึกการเตือนนี้ จะหยุดการแจ้งเตือน ตั้งแต่ '+date+' เป็นต้นไป')==true){
			return true;
		}
		else {
			return false;
		}
		
	}
</script>
<fieldset><legend><b>งานที่จะต้องดำเนินการ<?php echo $str_text;?>( <font color='<?php echo $color_red;?>'>สีแดง-ยังไม่ดำเนินการ </font>/ <font color='<?php echo $color_orange;?>'>สีส้ม-ระหว่างดำเนินการ</font> / <font color='<?php echo $color_green;?>'>สีเขียว-เสร็จแล้ว</font>)</b></legend>
<div style="background-color: #ffffff; padding: 2px">
	<?php	
		// ---------------------------------------------------------------------------------------------
		// ค้นหารายการที่ตรงกับเงื่อนไขที่จะต้องแสดง
		// ---------------------------------------------------------------------------------------------
		$qry_fuc = getreminderquery($focusdate,$get_userid,$tabid); // สร้าง qiery จาก function
		
		$numr=pg_num_rows($qry_fuc);
		if($numr==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }

		while($res_fuc_getreminder=pg_fetch_array($qry_fuc)){
			$table= $res_fuc_getreminder["table"];			
			if($table=='reminder'){
				$reminder_type= $res_fuc_getreminder["reminder_type"];
				$main_reminder= $res_fuc_getreminder["reminder_id"];
				$reminder_doerstamp= $res_fuc_getreminder["reminder_doerstamp"];
				
				while($reminder_doerstamp <=$focusdate){
					$reminder_job_date=$reminder_doerstamp;
					if($reminder_type=='4'){//ทุกวัน	
						
						include("list_detail.php");
						
					}
					else if($reminder_type=='3'){//เตือนเฉพาะวันที่
						str_replace("-","",$reminder_doerstamp);
						$reminder_ref= $res_fuc_getreminder["reminder_ref"];
						if($reminder_ref==$replace_str){
							include("list_detail.php");
						}
					}
					else if($reminder_type=='2'){						
						$reminder_ref= $res_fuc_getreminder["reminder_ref"];						
						$qry=pg_query("SELECT \"reminder_typeweek\"('$reminder_doerstamp'::date, '$reminder_ref'::text)");
						$re=pg_fetch_array($qry);
						list($resu)=$re;
						
						if($resu=='t'){
							include("list_detail.php");
						}
					}
					else if($reminder_type=='1'){//เตือนทุกวันที่
						$reminder_ref= $res_fuc_getreminder["reminder_ref"];
						list($year,$month,$day)=explode("-",$reminder_doerstamp);						
						if($day==$reminder_ref){
							include("list_detail.php");
						}
					}
					$reminder_doerstamp= date ("Y-m-d", strtotime("+1 day", strtotime($reminder_doerstamp)));
				}
			}
			else if($table=='reminder_job'){
				$main_reminder= $res_fuc_getreminder["main_reminder_id"];
				$reminder_job_date= $res_fuc_getreminder["reminder_job_date"];				
				include("list_detail.php");
			}
		}
		
		?>
		</div>
</fieldset>	

