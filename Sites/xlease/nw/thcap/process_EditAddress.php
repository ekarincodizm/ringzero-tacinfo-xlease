<?php
include("../../config/config.php");
include("../function/checknull.php");
include("../function/randomDigit.php");
include('class.upload.php');

$add_user = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate = date("Y-m-d");

$tempID=$_REQUEST["tempID"];
$method=$_REQUEST["method"];
if($tempID==""){
	$tempID = pg_escape_string($_POST["tempID"]);
	$method = pg_escape_string($_POST["method"]);
}
if(isset($_POST["appv"])){
	$stsapp="1";//อนุมัติ
}else{
	$stsapp="0";//ไม่อนุมัติ
}

// หาเลขที่สัญญา
if($tempID != "")
{
	$qry_contractID = pg_query("select \"contractID\" from \"thcap_addrContractID_temp\" where \"tempID\"='$tempID' ");
	$contractID = pg_fetch_result($qry_contractID,0);
}
else
{
	$contractID = $_REQUEST["contractID"];
	if($contractID == ""){$contractID = pg_escape_string($_POST["contractID"]);}
}

//ข้อมูลสำหรับตรวจสอบ
$f_no = pg_escape_string($_POST["f_no"]);
$f_subno = pg_escape_string($_POST["f_subno"]);
$f_soi = pg_escape_string($_POST["f_soi"]);
$f_rd = pg_escape_string($_POST["f_rd"]);
$f_tum = pg_escape_string($_POST["f_tum"]);
$f_aum = pg_escape_string($_POST["f_aum"]);
$f_province = pg_escape_string($_POST["A_PRO"]);
$f_post = pg_escape_string($_POST["f_post"]);
$f_room = pg_escape_string($_POST["f_room"]);
$f_floor = pg_escape_string($_POST["f_floor"]);
$f_building = pg_escape_string($_POST["f_building"]);
$f_ban = pg_escape_string($_POST["f_ban"]);
$radio1 = pg_escape_string($_POST["radio1"]); // 1:แก้ไขที่อยู่เฉพาะสัญญานี้ || 2:แก้ไขที่อยู่ทุกสัญญาที่เกี่ยวข้อง
$contractEditToo = pg_escape_string($_POST["contractEditToo"]); // สัญญาที่เกี่ยวข้อง ที่จะแก้ไขด้วย
$effectiveDate = pg_escape_string($_POST["effectiveDate"]); // วันที่ที่มีผลบังคับใช้

//ข้อมูลสำหรับบันทึก
$f_no2=checknull($_POST["f_no"]);
$f_subno2=checknull($_POST["f_subno"]);
$f_soi2=checknull($_POST["f_soi"]);
$f_rd2=checknull($_POST["f_rd"]);
$f_tum2=checknull($_POST["f_tum"]);
$f_aum2=checknull($_POST["f_aum"]);
$f_province2=checknull($_POST["A_PRO"]);
$f_post2=checknull($_POST["f_post"]);
$f_room2=checknull($_POST["f_room"]);
$f_floor2=checknull($_POST["f_floor"]);
$f_building2=checknull($_POST["f_building"]);
$f_ban2=checknull($_POST["f_ban"]);
$sizefile=sizeof($_FILES["request_file"]);

// หากมีคำสั่งให้อนุมัติเลยโดยไม่ต้องรอ 
$autoapp = pg_escape_string($_POST["autoapp"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แก้ไขที่อยู่สัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    window.opener.location.reload();
    self.close();
}
</script> 
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<div style="padding-top: 20px;"></div>        
		<div class="ui-widget" align="center">
			<?php
			pg_query("BEGIN WORK");
			$status = 0;
			
			if($method=="approve"){ //กรณีเป็นการอนุมัติ
				//$stsapp=$_GET["stsapp"];
				
				//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
				$qry_check=pg_query("select * from \"thcap_addrContractID_temp\" where \"tempID\"='$tempID' and \"statusApp\" ='2' and \"addsType\"='3'");
				$num_check=pg_num_rows($qry_check); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว
				if($num_check == 0){
					echo "<div style=\"text-align:center\"><h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว</h2>";
					echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
				}else{
					if($stsapp=="1")
					{ //อนุมัติ
						//update ตาราง temp
						$uptemp="UPDATE \"thcap_addrContractID_temp\"
						SET \"statusApp\"='1', \"appUser\"='$add_user', \"appStamp\"='$add_date'
						WHERE (\"tempID\"='$tempID' or \"withContractEdit\"='$tempID') and \"addsType\"='3' and \"statusApp\"='2'";
						if($resuptemp=pg_query($uptemp)){
						}else{
							$status++;
						}
						
						// update ที่อยู่ของวันปัจจุบัน
						$updateAddress = "select \"thcap_update_contract_address\"(); ";
						if($resupdateAddress = pg_query($updateAddress)){
						}else{
							$status++;
						}
					}
					else
					{ //กรณีไม่อนุมัติ
						//update ตาราง temp
						$uptemp="UPDATE \"thcap_addrContractID_temp\"
						SET \"statusApp\"='0', \"appUser\"='$add_user', \"appStamp\"='$add_date'
						WHERE (\"tempID\"='$tempID' or \"withContractEdit\"='$tempID') and \"addsType\"='3' and \"statusApp\"='2'";
						
						if($resuptemp=pg_query($uptemp)){
						}else{
							$status++;
						}
					}
				}
				if($status == 0){
					pg_query("COMMIT");
					//pg_query("ROLLBACK");
					echo "<b>บันทึกข้อมูลเรียบร้อยแล้ว</b><br>";
					echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
				}else{
					pg_query("ROLLBACK");
					echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br>";
					echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
				}
			}else{ //กรณีขอแก้ไขที่อยู่
				//ตรวจสอบว่าเลขที่สัญญานี้รออนุมัติอยู่หรือไม่
				$qrychk=pg_query("SELECT * from \"thcap_addrContractID_temp\" where \"contractID\"='$contractID' and \"statusApp\"='2'");
				$numchk=pg_num_rows($qrychk);
				if($numchk == 0){ //แสดงว่าไม่มีการรออนุมัติอยู่ สามารถบันทึกได้เลย
					//ดึงข้อมูลเก่าขึ้นมาเพื่อตรวสอบ
					$qryaddr=pg_query("select * from \"thcap_addrContractID\" WHERE \"contractID\"='$contractID' and \"addsType\"='3'");
					if($resaddr=pg_fetch_array($qryaddr)){   
						$A_NO=$resaddr["A_NO"];
						$A_SUBNO=$resaddr["A_SUBNO"];
						$A_BUILDING=$resaddr["A_BUILDING"];
						$A_ROOM=$resaddr["A_ROOM"];
						$A_FLOOR=$resaddr["A_FLOOR"];
						$A_BAN=$resaddr["A_VILLAGE"];
						$A_SOI=$resaddr["A_SOI"];
						$A_RD=$resaddr["A_RD"];
						$A_TUM=$resaddr["A_TUM"];
						$A_AUM=$resaddr["A_AUM"];
						$A_PRO=$resaddr["A_PRO"];
						$A_POST=$resaddr["A_POST"];
					}

					if(($A_NO==$f_no and $A_SUBNO==$f_subno and $A_SOI==$f_soi and $A_RD==$f_rd and $A_TUM==$f_tum
					and $A_AUM==$f_aum and $A_PRO==$f_province and $A_POST==$f_post and $A_ROOM==$f_room and $A_FLOOR==$f_floor
					and $A_BUILDING==$f_building and $A_BAN==$f_ban) and $sizefile==0){
						//กรณีค่าเท่ากันทุกค่าแสดงว่าไม่มีการแก้ไขไม่ต้องบันทึก
					}else{ //มีการแก้ไขข้อมูล	
						//ดึงครั้งที่แก้ไขมาบันทึก
						$qrymax=pg_query("SELECT max(edittime) FROM \"thcap_addrContractID_temp\"
						where \"contractID\"='$contractID' and \"addsType\"='3' ");
						list($maxedittime)=pg_fetch_array($qrymax);
						
						if($maxedittime==""){
							$edittime=0;
						}else{
							$edittime=$maxedittime+1;
						}
								
						// set variables
						$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'upload_chgcontractadds');
						$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
							
						$files = array();
						foreach ($_FILES["request_file"] as $k => $l) {
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
									$random6degit=randomDigit(6);
									$prepend = date("YmdHi")."_".$contractID."_".$random6degit;
								
									$handle->file_name_body_pre = $prepend;
								
									//เรียกใช้ function process โดยส่ง path ของการเก็บไฟล์ upload ไปด้วย
									$handle->process($dir_dest);    
								
									if ($handle->processed) 
									{
										$pathfile=$handle->file_dst_name;						
										
										$ins="INSERT INTO \"thcap_addrContractID_temp\"(
											\"contractID\", \"addsType\", edittime, \"A_NO\", \"A_SUBNO\", 
											\"A_BUILDING\", \"A_ROOM\", \"A_FLOOR\", \"A_VILLAGE\", \"A_SOI\", \"A_RD\", 
											\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"addUser\", \"addStamp\", \"statusApp\", filerequest, \"effectiveDate\", \"updated\")
										VALUES ('$contractID', '3', '$edittime', $f_no2, $f_subno2, 
											$f_building2, $f_room2, $f_floor2, $f_ban2, $f_soi2, $f_rd2,
											$f_tum2, $f_aum2, $f_province2, $f_post2, '$add_user', '$add_date', '2', '$pathfile', '$effectiveDate', '0')
										RETURNING \"tempID\" ";
										if($resup=pg_query($ins)){
											$tempID = pg_fetch_result($resup,0);
										}else{
											$status++;
										}
										
										// ถ้ามีสัญญาที่เกี่ยวข้องที่จะแก้ไขไปพร้อมกันด้วย
										if($radio1 == "2" && $contractEditToo != "")
										{
											$contractEditToo_array = explode(",",$contractEditToo);
											
											foreach($contractEditToo_array as $contractEditToo_uni)
											{
												// หาว่าเป็นการแก้ไขครั้งที่เท่าไหร่
												$qrymax=pg_query("SELECT max(edittime) FROM \"thcap_addrContractID_temp\"
																where \"contractID\"='$contractEditToo_uni' and \"addsType\"='3' ");
												list($maxedittime)=pg_fetch_array($qrymax);
												if($maxedittime==""){
													$edittime=0;
												}else{
													$edittime=$maxedittime+1;
												}
												
												$ins="INSERT INTO \"thcap_addrContractID_temp\"(
													\"contractID\", \"addsType\", edittime, \"A_NO\", \"A_SUBNO\", 
													\"A_BUILDING\", \"A_ROOM\", \"A_FLOOR\", \"A_VILLAGE\", \"A_SOI\", \"A_RD\", 
													\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"addUser\", \"addStamp\", \"statusApp\", filerequest,
													\"withContractEdit\", \"effectiveDate\", \"updated\")
												VALUES ('$contractEditToo_uni', '3', '$edittime', $f_no2, $f_subno2, 
													$f_building2, $f_room2, $f_floor2, $f_ban2, $f_soi2, $f_rd2,
													$f_tum2, $f_aum2, $f_province2, $f_post2, '$add_user', '$add_date', '2', '$pathfile',
													'$tempID', '$effectiveDate', '0') ";
												if($resup=pg_query($ins)){
												}else{
													$status++;
													echo $ins;
												}
											}
										}
									}
									else
									{
										echo '<fieldset>';
										echo '  <legend>file not uploaded to the wanted location</legend>';
										echo '  Error: ' . $handle->error . '';
										echo '</fieldset>';
										$status++;
									}
								}
							}
						}
						
							$txtshowsucess = 'บันทึกข้อมูลเรียบร้อยแล้ว';
						
						//กรณีให้อนุมัติอัตโนมัติ {
							if($autoapp == 't'){
							
									$qry_sec=pg_query("select * from \"thcap_addrContractID_temp\" 
									where \"contractID\" ='$contractID' and \"statusApp\" ='2' and \"addsType\"='3'");
									if($resaddr=pg_fetch_array($qry_sec));
										$A_NO=checknull($resaddr["A_NO"]);
										$A_SUBNO=checknull($resaddr["A_SUBNO"]);
										$A_BUILDING=checknull($resaddr["A_BUILDING"]);
										$A_ROOM=checknull($resaddr["A_ROOM"]);
										$A_FLOOR=checknull($resaddr["A_FLOOR"]);
										$A_VILLAGE=checknull($resaddr["A_VILLAGE"]);
										$A_SOI=checknull($resaddr["A_SOI"]);
										$A_RD=checknull($resaddr["A_RD"]);
										$A_TUM=checknull($resaddr["A_TUM"]);
										$A_AUM=checknull($resaddr["A_AUM"]);
										$A_PRO=checknull($resaddr["A_PRO"]);
										$A_POST=checknull($resaddr["A_POST"]);
										$filerequest=checknull($resaddr["filerequest"]);
									
									
									//ตรวจสอบก่อนว่ามีข้อมูลหรือยัง 
									$qrychk=pg_query("select * from \"thcap_addrContractID\" WHERE \"contractID\"='$contractID' and \"addsType\"='3'");
									$numchk=pg_num_rows($qrychk);
									if($numchk>0){ //กรณีมีข้อมูลแล้วให้ update
										$upadd="UPDATE \"thcap_addrContractID\"
										SET \"A_NO\"=$A_NO, \"A_SUBNO\"=$A_SUBNO, \"A_BUILDING\"=$A_BUILDING, 
											\"A_ROOM\"=$A_ROOM, \"A_FLOOR\"=$A_FLOOR, \"A_VILLAGE\"=$A_VILLAGE, \"A_SOI\"=$A_SOI, \"A_RD\"=$A_RD, 
											\"A_TUM\"=$A_TUM, \"A_AUM\"=$A_AUM, \"A_PRO\"=$A_PRO, \"A_POST\"=$A_POST, filerequest=$filerequest
										WHERE \"contractID\"='$contractID' and \"addsType\"='3'";
										if($resup=pg_query($upadd)){
										}else{
											$status++;
										}
									}else{ //กรณียังไม่มีข้อมูลให้ insert
										$inadd="INSERT INTO \"thcap_addrContractID\"(
												\"contractID\", \"addsType\", \"A_NO\", \"A_SUBNO\", \"A_BUILDING\", \"A_ROOM\", 
												\"A_FLOOR\", \"A_VILLAGE\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", 
												\"A_POST\", filerequest)
										VALUES ('$contractID', '3', $A_NO, $A_SUBNO, $A_BUILDING, $A_ROOM, 
												$A_FLOOR, $A_VILLAGE, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, 
												$A_POST, $filerequest);
										";
										if($resin=pg_query($inadd)){
										}else{
											$status++;
										}
									}
									
									//update ตาราง temp
									$uptemp="UPDATE \"thcap_addrContractID_temp\"
									SET \"statusApp\"='1', \"appUser\"='000', \"appStamp\"='$add_date'
									WHERE \"contractID\"='$contractID' and \"addsType\"='3' and \"statusApp\"='2'";
									if($resuptemp=pg_query($uptemp)){
									}else{
										$status++;
									}
							
							
								$txtshowsucess = '';
							
							}
						
						//จบการอนุมัติอัตโนมัติ }
						
						
						
						
						
						
						
						
						if($status == 0){
							pg_query("COMMIT");
							//pg_query("ROLLBACK");
							
							if($autoapp == 't'){
								echo "<script type='text/javascript'>alert('บันทึกข้อมูลพร้อมอนุมัติเรียบร้อยแล้ว')</script>";
								echo "	<script type='text/javascript'>
											opener.location.reload(true);	
											self.close();
										
										</script>";
							}else{
								echo "<b>$txtshowsucess</b>";
								echo "<meta http-equiv='refresh' content='3; URL=frm_EditAddress.php'>";
							}	
						}else{
							pg_query("ROLLBACK");
							echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
						}
					}else{ //กรณีมีการรออนุมัติอยู่
						echo "<b>เลขที่สัญญานี้ <u>รออนุมัติอยู่</u> กรุณาตรวจสอบ</b>";
						echo "<meta http-equiv='refresh' content='3; URL=frm_EditAddress.php'>";
					}	
				}
			?>
		</div>
	</td>
</tr>
</table>

</body>
</html>