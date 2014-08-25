<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");
include('../../../../thcap_fa/class.upload.php');
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdateTime = date("YmdHis");
$amount =str_replace(',','',$_REQUEST[amount]);
$user_id = $_SESSION["av_iduser"];
$status =0;

$change_pay_type=$_REQUEST['type1'];
$id =$_REQUEST[id_main];
$pay_date= $_POST[datepicker];

$memo = $_POST[reason];
$pay_type =$_POST['pay_type'];

pg_query("BEGIN WORK");

$query1 =	"INSERT INTO \"ta_join_add_money_app\" (id_main, amount, pay_type, pay_date, change_pay_type, memo, 
            create_datetime, create_by) 
							VALUES(
							           '$id',
									   '$amount',
									   '$pay_type',
									   '$pay_date',
									   '$change_pay_type',
									   '$memo',
									   '$info_currentdatetimesql2',
									   '$user_id'
									   )";

if($res_inss=pg_query($query1)){	
		}else{
			$status=$status+1;
			//echo $query1;
		}

$qrymax=pg_query("select max(\"id\") from ta_join_add_money_app");
	list($maxID)=pg_fetch_array($qrymax);
	// upload files
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : '../upload/');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
							
	$files = array();
	foreach ($_FILES["my_field"] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
				$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
							
		foreach ($files as $file) {
			$handle = new Upload($file);
						   
			if($handle->uploaded) {
				// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
				$handle->Process($dir_dest);    							 
								
				if ($handle->processed) {
					$pathfile=$handle->file_dst_name;
	
					$Board_oldfile = $pathfile;			
					$Board_newfile = md5_file("../upload/$pathfile", FALSE);		
										
					$Board_cuttext = split("\.",$pathfile);
					$Board_nubtext = count($Board_cuttext);
					$Board_newfile = "$Board_newfile.".$Board_cuttext[$Board_nubtext-1];
										
					$Board_newfile = $nowdateTime."_".$Board_newfile; // ใส่วันเวลาไว้หน้าไฟล์
										
					$Boardfile = "'$Board_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
										
					$flgRename = rename("../upload/$Board_oldfile", "../upload/$Board_newfile");
					if($flgRename)
					{
						//echo "บันทึกสำเร็จ";
					}
					else
					{
						echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
						$status++;
					}
										
					$ins="INSERT INTO ta_join_file_path(\"id\", file) VALUES ('$maxID', $Boardfile);";					
					if($resup=pg_query($ins)){
					}else{
						$status++;
					}						
				}else{
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $handle->error . '';
					echo '</fieldset>';
					$status++;
				}
			}
		}










				
		

echo "<script>";
	    		if($statu==0){
					//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ขอเพิ่มเงินเข้าระบบเข้าร่วม $maxID', '$add_date')");
				//ACTIONLOG---
					
					pg_query("COMMIT");
					echo "alert('บันทึกรายการเรียบร้อยแล้ว!! กรุณารอการอนุมัติ!!');
		window.location.href='ta_join_add_money.php?idno_names=$id';";
		
				}else { 
				pg_query("ROLLBACK");
				echo "alert('ไม่สามารถบันทึกรายการได้!!');";
				}

 echo "</script> ";

	 

 

	 
?>