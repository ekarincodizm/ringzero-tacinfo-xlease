<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$auto_id=$_REQUEST["auto_id"]; 
$statusapp=$_REQUEST["stsapp"];  
$val=$_REQUEST["val"];
$resultnotapp=$_POST["resultnotapp"];

$method=$_REQUEST["method"];
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function checkdata(){
	if(document.frm1.resultnotapp.value==""){
		alert("กรุณาระบุเหตุผลที่ไม่อนุมัติ");
		document.frm1.resultnotapp.focus();
		return false;
	}else{
		return true;
	}
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
pg_query("BEGIN WORK");
$status = 0;

if($statusapp=="1" || $statusapp=="0"){ //อนุมัติค่าเบี้ย
	//ตรวจสอบว่าได้รับการอนุมัติก่อนหน้านี้หรือไม่
	$qrychk=pg_query("select * from thcap_insure_checkchip where \"auto_id\"='$auto_id' and \"statusApp\"='2'");
	$numchk=pg_num_rows($qrychk);
	
	if($numchk>0){ //แสดงว่ายังไม่ได้รับการอนุมัติ
		//update ข้อมูล
		
		if($statusapp=="0" and $val=="1"){
			?>
			<form method="post" name="frm1" action="process_approve.php">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr>
				<td><b>ระบุเหตุผลที่ไม่อนุมัติ</b></td>
			</tr>
			<tr>
				<td><textarea name="resultnotapp" id="resultnotapp" cols="40" rows="4"></textarea></td>
			</tr>
			<tr><td align="center">
				<input type="hidden" name="val" value="2">
				<input type="hidden" name="auto_id" value="<?php echo $auto_id;?>">
				<input type="hidden" name="stsapp" value="<?php echo $statusapp;?>">
				<input type="submit" value="ตกลง" onclick="return checkdata();">
			</td></tr>
			</table>
			</form>
			<?php
		}
		if($val=="2"){
			
			if($resultnotapp==""){
				$resultnot="null";
			}else{
				$resultnot="'".$resultnotapp."'";;
			}
			$upchk="UPDATE thcap_insure_checkchip
			SET \"statusApp\"='$statusapp', \"appUser\"='$app_user', \"appStamp\"='$app_date',\"resultnotapp\"=$resultnot WHERE auto_id='$auto_id'";
			if($reschk=pg_query($upchk)){
			}else{
				$status++;
			}
		
			if($status == 0){
				pg_query("COMMIT");
				echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
				if($statusapp=="0"){
					echo "<FORM METHOD=GET ACTION=\"#\">";
					echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
					echo "</FORM>";
				}else{
					echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
				}
			}else{
				pg_query("ROLLBACK");
				echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
				
				if($statusapp=="0"){
					echo "<FORM METHOD=GET ACTION=\"#\">";
					echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
					echo "</FORM>";
				}else{
					echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
				}
			}
		}
	}else{
		echo "<font size=4><b>รายการนี้ได้รับการอนุมัติแล้ว กรุณาตรวจสอบ</b></font><br><br>";
		if($statusapp=="0"){
			echo "<FORM METHOD=GET ACTION=\"#\">";
			echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
			echo "</FORM>";
		}else{
			echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
		}
	}		
				
}
if($method=="addreq"){//กรณีอนุมัติคำขอ
	$auto_id=$_REQUEST["auto_id"]; 
	$statusapp=$_REQUEST["stsapp2"];
	$stsinsure=$_REQUEST["stsinsure"];
	$checkchipID=$_REQUEST["checkchipID"];
	//ตรวจสอบก่อนว่าก่อนหน้านี้ได้อนุมัติข้อมูลไปหรือยัง
	$qrycheck=pg_query("select \"ContractID\" from thcap_insure_temp where auto_id=$auto_id and \"statusApprove\"='2'");
	list($contractID)=pg_fetch_array($qrycheck);
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck>0){
		//update ตาราง thcap_insure_temp ว่าอนุมัติหรือไม่อนุมัติ
		if($statusapp=="0" and $val=="1"){
			?>
			<form method="post" name="frm1" action="process_approve.php">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr>
				<td><b>ระบุเหตุผลที่ไม่อนุมัติ</b></td>
			</tr>
			<tr>
				<td><textarea name="resultnotapp" id="resultnotapp" cols="40" rows="4"></textarea></td>
			</tr>
			<tr><td align="center">
				<input type="hidden" name="val" value="2">
				<input type="hidden" name="auto_id" value="<?php echo $auto_id;?>">
				<input type="hidden" name="stsapp2" value="<?php echo $statusapp;?>">
				<input type="hidden" name="checkchipID" value="<?php echo $checkchipID;?>">
				<input type="hidden" name="method" value="addreq">
				<input type="submit" value="ตกลง" onclick="return checkdata();">
			</td></tr>
			</table>
			</form>
			<?php
		}
		
		
		if($val=="2"){
			if($resultnotapp==""){
				$resultnot="null";
			}else{
				$resultnot="'".$resultnotapp."'";;
			}
			
			
			//ตรวจสอบว่าข้อมูลเก่าหมดอายุหรือยังถ้าหมดอายุแล้วให้อัพเดทข้อมูลด้วย
			if($statusapp=="1"){ //กรณีอนุมัติ
				if($stsinsure=="1"){ //กรณีเป็นต่ออายุ
					$qrycheckdate=pg_query("select \"endDate\",\"insureNum\" from thcap_insure_temp where \"ContractID\"='$contractID' and \"statusApprove\"='1' and \"insureNum\" is not null order by edittime DESC limit 1");
					list($endDate,$insnum)=pg_fetch_array($qrycheckdate);
					$nowdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
					$endDate=$endDate." 16:00:00";
					
					if($nowdate>$endDate){ //ถ้ามากกว่าแสดงว่าหมดอายุแล้ว
						//ให้ไป update เลขที่กรมธรรม์นี้ด้วยว่าหมดอายุแล้ว
						$upcheckdate="UPDATE thcap_insure_main SET \"Active\"='FALSE' WHERE \"insureNum\"='$insnum'";
						if($rescheckdate=pg_query($upcheckdate)){
						}else{
							$status++;
						}
					}
				}
			}
			
			$upd="update thcap_insure_temp set \"statusApprove\"='$statusapp',\"appUser\"='$app_user',\"appStamp\"='$app_date',\"resultnotapp\"=$resultnot where auto_id='$auto_id'";
			if($resup=pg_query($upd)){
			}else{
				$status++;
			}
			
			if($statusapp=="0"){ //กรณีไม่อนุมัติรายการ
				$updchip="UPDATE thcap_insure_checkchip SET \"statusApp\"='1' WHERE auto_id='$checkchipID'";
				echo "$updchip";
				if($resupchip=pg_query($updchip)){
				}else{
					$status++;
				}
			}
			
			if($statusapp=="1"){ //กรณีอนุมัติรายการ
				//กำหนดเลขที่รับแจ้ง
				if($stsinsure=="0"){ //ประกันใหม่
					$qryin=pg_query("select max(\"INNUM\") from thcap_insure_new");
					list($INNUM)=pg_fetch_array($qryin);
					$nowdate=nowDate();
					list($y,$m,$d)=explode("-",$nowdate); //ดึงปีและเดือนปัจจุบันออกมา
					$y=$y+543;
					$y=substr($y,2,2);
					
					if($INNUM==""){ //กรณียังไม่มีข้อมูลให้เริ่มต้นตั้งแต่01
						$INNUM="IN/".$y.$m."001";
					}else{
						list($befor,$after)=explode("/",$INNUM);
						$y_old=substr($after,0,2);
						$m_old=substr($after,2,2);
						$runnum=substr($after,4,3);
						
						if($y==$y_old){ //ตรวจสอบว่าเป็นปีเดียวกันหรือไม่
							//ตรวจสอบว่าเป็นเดือนเดียวกันหรือไม่
							if($m==$m_old){//ถ้าเป็นเดือนเดียวกันให้รันเลขต่อ
								$runnum+=1;
								$run=sprintf("%03d", $runnum); 
								
								$INNUM="IN/$y$m$run";
							}else{ //กรณีไม่ใช่เดือนเดียวกัน
								$INNUM="IN/".$y.$m."001";
							}
						}else{
							$INNUM="IN/$y$m01";
						}
						
					}
					echo $INNUM;
					//insert ข้อมูล
					
					$insnew="INSERT INTO thcap_insure_new(\"INNUM\", auto_id) VALUES ('$INNUM', '$auto_id')";
					if($resnew=pg_query($insnew)){
					}else{
						$status++;
					}
				
				}else if($stsinsure=="1"){//ต่ออายุ
					$qryin=pg_query("select max(\"RENUM\") from thcap_insure_old");
					list($RENUM)=pg_fetch_array($qryin);
					$nowdate=nowDate();
					
					list($y,$m,$d)=explode("-",$nowdate); //ดึงปีและเดือนปัจจุบันออกมา
					$y=$y+543;
					$y=substr($y,2,2);
					
					if($RENUM==""){ //กรณียังไม่มีข้อมูลให้เริ่มต้นตั้งแต่01
						$RENUM="RE/".$y.$m."001";
					}else{
						list($befor,$after)=explode("/",$RENUM);
						$y_old=substr($after,0,2);
						$m_old=substr($after,2,2);
						$runnum=substr($after,4,3);
						
						if($y==$y_old){ //ตรวจสอบว่าเป็นปีเดียวกันหรือไม่
							//ตรวจสอบว่าเป็นเดือนเดียวกันหรือไม่
							if($m==$m_old){//ถ้าเป็นเดือนเดียวกันให้รันเลขต่อ
								$runnum+=1;
								$run=sprintf("%03d", $runnum); 
								
								$RENUM="RE/$y$m$run";
							}else{ //กรณีไม่ใช่เดือนเดียวกัน
								$RENUM="RE/".$y.$m."001";
							}
						}else{
							$RENUM="RE/$y$m001";
						}
						
					}
					echo $RENUM;
					//insert ข้อมูล
					
					$insnew="INSERT INTO thcap_insure_old(\"RENUM\", auto_id) VALUES ('$RENUM', '$auto_id')";
					if($resnew=pg_query($insnew)){
					}else{
						$status++;
					}
					
				}

			}else{ //กรณีไม่อนุมัติรายการ
				//จะไม่ทำอะไรเลย
			}
			
			if($status == 0){
				pg_query("COMMIT");
				echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
				if($statusapp=="0"){
					echo "<FORM METHOD=GET ACTION=\"#\">";
					echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
					echo "</FORM>";
				}else{
					echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
				}
			}else{
				pg_query("ROLLBACK");
				echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
				
				if($statusapp=="0"){
					echo "<FORM METHOD=GET ACTION=\"#\">";
					echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
					echo "</FORM>";
				}else{
					//echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
				}
			}
		}
	}else{
		echo "<font size=4><b>รายการนี้ได้รับการอนุมัติแล้ว กรุณาตรวจสอบ</b></font><br><br>";
		if($statusapp=="0"){
			echo "<FORM METHOD=GET ACTION=\"#\">";
			echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
			echo "</FORM>";
		}else{
			echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
		}
	}
}
?>
</td>
</tr>
</table>
</body>
</html>