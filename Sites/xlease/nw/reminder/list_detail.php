<?php

// ---------------------------------------------------------------------------------------------
// +0 เพื่อแปลงเป็นตัวเลข จาก 01 จะเป็น 1 เป็นการตัด 0 ตัวหน้าออกถ้ามี
// ---------------------------------------------------------------------------------------------
	$dayeng = date('l',strtotime($reminder_job_date)); // วัน เช่า monday, friday
	$reminder_job_date=date('Y-m-d', strtotime($reminder_job_date));

	// ---------------------------------------------------------------------------------------------
	// หาวันที่ว่าเป็นวันอะไร และเป็นที่เท่าไหร่ของเดือน
	// ---------------------------------------------------------------------------------------------
	if ($dayeng == 'Monday'){
		$dayth = 'วันจันทร์';
	} else if ($dayeng == 'Tuesday'){
		$dayth = 'วันอังคาร';
	} else if ($dayeng == 'Wednesday'){
		$dayth = 'วันพุธ';
	} else if ($dayeng == 'Thursday'){
		$dayth = 'วันพฤหัสบดี';
	} else if ($dayeng == 'Friday'){
		$dayth = 'วันศุกร์';
	} else if ($dayeng == 'Saturday'){
		$dayth = 'วันเสาร์';
	} else if ($dayeng == 'Sunday'){
			$dayth = 'วันอาทิตย์';
	}
// ---------------------------------------------------------------------------------------------
// แสดงข้อความรูปแบบการเตือน
// ---------------------------------------------------------------------------------------------			
						
			$qry_reminder=pg_query("SELECT * FROM \"reminder\"  WHERE \"reminder_id\"='$main_reminder'");			
			$res_fuc=pg_fetch_array($qry_reminder);
			if($res_fuc["reminder_type"] == 1) {
				$reminder_type_desc = 'เตือนทุกๆวันที่ '.$res_fuc["reminder_ref"].'ของทุกเดือน และสิ้นสุดการเตือนเมื่อ '.$res_fuc["reminder_expiredate"];
			} else if($res_fuc["reminder_type"] == 2) {
			if($res_fuc["reminder_ref"]%10 == 0) // แก้ไขข้อมูล สัปดาห์ตามจริง
				$weekth = 'ทุกสัปดาห์';
			else if($res_fuc["reminder_ref"]%10 == 9) // แก้ไขข้อมูล สัปดาห์ตามจริง
				$weekth = 'สัปดาห์สุดท้าย';
			else
				$weekth = $res_fuc["reminder_ref"]%10;
				$reminder_type_desc = 'เตือนทุกๆวัน '.$dayth.' ที่ '.$weekth.' ของทุกเดือน และสิ้นสุดการเตือนเมื่อ '.$res_fuc["reminder_expiredate"];
			} else if($res_fuc["reminder_type"] == 3) {
				$reminder_type_desc = 'เตือนเฉพาะวันที่ '.$res_fuc["reminder_ref"].' และสิ้นสุดการเตือนเมื่อ '.$res_fuc["reminder_expiredate"];
			}
			else if($res_fuc["reminder_type"] == 4) {
				$reminder_type_desc = 'เตือนทุกวัน';
			}
			else  {
				$reminder_type_desc = '--';
			}	
			// ---------------------------------------------------------------------------------------------
			//  ทำลิ้งให้เลขที่สัญญาอัตโนมัติหากพบเจอในข้อความที่ตรงกับ format เลขที่สัญญา
			// ---------------------------------------------------------------------------------------------
			// ข้อมูลที่จะ replace
			$reminder_details = $res_fuc["reminder_details"];
			
			// ลิ้งไป (THCAP) ตารางแสดงการผ่อนชำระ หากเจอ format ของเลขที่สัญญา
			//$reminder_details_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})/'; // format เลขที่สัญญา xx-xxxx-xxxxxxx		
			
			
			// สิ่งที่จะ replace format เลขที่สัญญา xx-xxxx-xxxxxxx
			/*$reminder_details_replace = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4'."</u></font></span>";*/
			
			// ที่ replace ได้
			//$reminder_details = preg_replace($reminder_details_format,$reminder_details_replace, $reminder_details);		
			
			
			// ---------------------------------------------------------------------------------------------
			// ค้นหาชื่อผู้ทำรายการ
			// ---------------------------------------------------------------------------------------------
			$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[reminder_doerid]')");
			$res_fun=pg_fetch_array($qry_fun);
							
			// ---------------------------------------------------------------------------------------------
			// กำหนดสีของรายการ ถ้ายังไม่ได้ดำเนินการ เป็นสีส้ม ถ้าดำเนินการแล้ว เป็นสีเขียว
			// ---------------------------------------------------------------------------------------------
			$re_reminder='';
			if($tabid=='1'){
						
				if($focusdate > nowDate()){				
					$qry_status=pg_query("SELECT *
										FROM \"reminder\" 
										WHERE 
										(\"reminder_status\"='1' or
										\"reminder_canceluserstamp\" ::date > '$reminder_job_date') AND
										\"reminder_id\" ='$main_reminder' ");
				
					$num_row=pg_num_rows($qry_status);
					if($num_row>0){						
						
						$qry_status=pg_query("SELECT \"reminder_job_status\"
										FROM \"reminder_job\" 
										WHERE \"reminder_job_id\" IN
										(SELECT MAX(reminder_job_id)
										FROM \"reminder_job\" 
										WHERE 
										\"reminder_id\"= '$main_reminder' AND
										\"reminder_job_date\"::date ='$reminder_job_date' -- 1เฉพาะ job การติดตามของวันที่ที่สนใจ										
										)
									");
					$num_row_1=pg_num_rows($qry_status);
					if($num_row_1>0){
						$re_reminder='true';
						$res_status=pg_fetch_array($qry_status);
						
					}
					}else{
						$re_reminder='false';
					}					
				}
				else{
					$qry_status=pg_query("SELECT \"reminder_job_status\"
										FROM \"reminder_job\" 
										WHERE \"reminder_job_id\" IN
										(SELECT MAX(reminder_job_id)
										FROM \"reminder_job\" 
										WHERE 
										\"reminder_id\"= '$main_reminder' AND
										\"reminder_job_date\"::date ='$reminder_job_date' -- 1เฉพาะ job การติดตามของวันที่ที่สนใจ										
										)
									");
					$num_row=pg_num_rows($qry_status);
					if($num_row>0){
						$re_reminder='true';
					}else{
						$re_reminder='false';
					}
					$res_status=pg_fetch_array($qry_status);
				}
				
			}else{
				if($focusdate > nowDate()){
					$qry_status=pg_query("
										SELECT MAX(reminder_job_status) as reminder_job_status
										FROM \"reminder_job\"
										WHERE
										\"reminder_id\"='$main_reminder' AND
										\"reminder_job_date\"='$reminder_job_date' -- 2เฉพาะ job การติดตามของวันที่ที่สนใจ
							");
				}else{
					$qry_status=pg_query("SELECT \"reminder_job_status\" 
										FROM \"reminder_job\" 
											WHERE \"reminder_job_id\" IN
											(SELECT MAX(reminder_job_id)
												FROM \"reminder_job\" 
												WHERE 
												\"reminder_id\"='$main_reminder' AND
												\"reminder_job_date\" ='$reminder_job_date' -- 3เฉพาะ job การติดตามของวันที่ที่สนใจ
											)
										");								
				}
				$res_status=pg_fetch_array($qry_status);
			}
					
										
					if((($tabid=='1')and($res_status["reminder_job_status"] == '1')) or ($re_reminder=='false')){}						
					else{	
						
						
						if(($res_status["reminder_job_status"] == '-1') or ($res_status["reminder_job_status"] == '' and ($focusdate > nowDate())))
							$colorstatus = $color_red; // สีแดง
						else if($res_status["reminder_job_status"] == '0')
							$colorstatus = $color_orange; //สีส้ม
						else if($res_status["reminder_job_status"] == '1')
							$colorstatus = $color_green; // สีเขียว
						?>
						<div style="background-color: <?php echo $colorstatus; ?>">
						<div style="float:left; padding:2px">ผู้บันทึก : <b><?php echo $res_fun["fullname"]; ?></div>
						<div style="float:right; padding:2px">วันที่บันทึก : <b><?php echo $res_fuc["reminder_doerstamp"]; ?></b></div>
						</br>
						<div style="float:left; padding:2px"></b> การแจ้งเตือนประจำวัน <?php echo $reminder_job_date; ?>(รายการนี้มีรูปแบบการเตือนคือ <b><?php echo $reminder_type_desc; ?></b>)</div>
						<div style="clear:both;"></div>
						</div>
						<?php if($focusdate > nowDate()){ ?>
							<!--div style="background-color: #F0F0F0; padding:2px"><?php echo $reminder_details; ?></div-->
						<?php } 
									
						$qry_job=pg_query("SELECT * from \"reminder_job\" 
											WHERE 
													\"reminder_id\"='$main_reminder' AND
													\"reminder_job_date\"='$reminder_job_date' -- เฉพาะ job การติดตามของวันที่ที่สนใจ
													
												ORDER BY \"reminder_job_doerstamp\",\"reminder_job_status\" asc");
						$num_row_1=pg_num_rows($qry_job);
						if($num_row_1==0){
							$qry_job=pg_query("SELECT * from \"reminder\" 
											WHERE  \"reminder_id\"='$main_reminder' ");
						}						
						while($res_job=pg_fetch_array($qry_job)){
							
							// ---------------------------------------------------------------------------------------------
							//  ทำลิ้งให้เลขที่สัญญาอัตโนมัติหากพบเจอในข้อความที่ตรงกับ format เลขที่สัญญา
							// ---------------------------------------------------------------------------------------------
							// ข้อมูลที่จะ replace
							$reminder_job_doerremark = $res_job["reminder_job_doerremark"];
							if($reminder_job_doerremark==''){$reminder_job_doerremark = $res_job["reminder_details"];}
							// ลิ้งไป (THCAP) ตารางแสดงการผ่อนชำระ หากเจอ format ของเลขที่สัญญา
							//$reminder_job_doerremark_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})/'; // format เลขที่สัญญา xx-xxxx-xxxxxxx	
							
							//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
							$reminder_job_doerremark_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/'; 
							
							// สิ่งที่จะ replace
							/*$reminder_job_doerremark_replace = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4'."</u></font></span>";*/
							
							$reminder_job_doerremark_replace = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";
							// ที่ replace ได้
							
							$reminder_job_doerremark = preg_replace($reminder_job_doerremark_format,$reminder_job_doerremark_replace, $reminder_job_doerremark);
							// ค้นหาชื่อผู้ทำรายการ
							if($res_job["reminder_job_doerid"]==''){
								$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_job[reminder_doerid]')");
								$res_fun=pg_fetch_array($qry_fun);
								$reminder_job_doerstamp=$res_job["reminder_doerstamp"];
							}
							else{
								$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_job[reminder_job_doerid]')");
								$res_fun=pg_fetch_array($qry_fun);
								$reminder_job_doerstamp=$res_job["reminder_job_doerstamp"];
							}
							// แสดงรายการดำเนินงาน
							echo "<div style=\"float:right; padding:2px\">ผู้อัพเดทรายการ : ".$res_fun["fullname"]." วันที่บันทึก : <b>".$reminder_job_doerstamp."</b></div>";
							echo "</br><div style=\"background-color: #F0F0F0;\">".$reminder_job_doerremark."</div>";
							echo "<div style=\"clear:both;\"></div>";
						}
							
						// ---------------------------------------------------------------------------------------------
						// ในกรณีที่การเตือนงานครั้งนี้ยังไม่จบ (reminder_job_status == 1) จะต้องทำรายการ อัพเดทรายการได้ไปเรื่อยๆ
						// ---------------------------------------------------------------------------------------------
						if($res_status["reminder_job_status"] != '1'){
							$nameform=$res_fuc["reminder_id"]; ?>
							
									<form name='<?php echo $nameform ;?>' method="post" action="save_reminder_update.php">
										รายละเอียด : 	
								<?php	echo "<input type=\"radio\" name=\"rd_status\" value=\"0\" checked>ยังอยู่ระหว่างดำเนินการ
										<input type=\"radio\" name=\"rd_status\" value=\"1\">ดำเนินการเรียบร้อยแล้ว (ปิดงาน)
										<input type=\"radio\" name=\"rd_status\" value=\"2\">ดำเนินการเรียบร้อยแล้ว (ปิดงาน  และ หยุดการแจ้งเตือน )
										</br>
										<TEXTAREA NAME=\"txt_remark\" ROWS=\"3\" COLS=\"130\"></TEXTAREA>
										<INPUT TYPE=\"submit\" VALUE=\"บันทึกอัพเดท\">
										<INPUT TYPE=\"hidden\" NAME=\"userid\" VALUE=\"".$get_userid."\">
										<INPUT TYPE=\"hidden\" NAME=\"reminder_id\" VALUE=\"".$res_fuc[reminder_id]."\">
										<INPUT TYPE=\"hidden\" NAME=\"reminder_date\" VALUE=\"".$reminder_job_date."\">
									</form>
								";
						}
						else if($res_status["reminder_job_status"] == '1'){ 
							
							$nameform=$res_fuc["reminder_id"];
							$qry_chk=pg_query("select \"reminder_status\" from \"reminder\" WHERE \"reminder_id\"='$nameform'");
							$res_chk=pg_fetch_array($qry_chk);
							//ปิดประเด็นแล้ว สีเขียว ?>
							<form name='<?php echo $nameform ;?>' method="post" action="stop_reminder.php">
									<INPUT TYPE="hidden" NAME="userid" VALUE="<?php echo $get_userid; ?>">
									<INPUT TYPE="hidden" NAME="reminder_id" VALUE="<?php echo $res_fuc["reminder_id"]; ?>">
									<INPUT TYPE="hidden" NAME="reminder_date_stop" VALUE="<?php echo $reminder_job_date; ?>">
									<INPUT TYPE="hidden" NAME="reminder_after_date" VALUE="<?php echo $after_date_save; ?>">
									<?php if($res_chk["reminder_status"]=='1') { ?>
									<input name="submitButton" value="หยุดการแจ้งเตือน" type="submit" onclick="return confirm_date('<?php echo $date_stop;?>');"/>
									<?php } ?>
							</form>
					<?php }
					echo "<div style=\"background-color: #FFFFFF; clear:both; height:10px\"></div>";		
					}	
					