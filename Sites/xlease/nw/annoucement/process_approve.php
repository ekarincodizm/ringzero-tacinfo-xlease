<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$annId=$_REQUEST["annId"]; 
$cid=$_POST["cid"]; 
$curdate = nowDate();
$val=$_REQUEST["val"];
$stscancel=$_POST["stscancel"]; 
$resultnotapp=$_POST["resultnotapp"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server


$chknewemp=$_POST["chknewemp"];
$newempdep=$_POST["newempdep"];
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

//ตรวจสอบว่าข้อมูลได้ถูก approve ไปหรือยัง
$qry_check=pg_query("select * from nw_annoucement where \"annId\"='$annId' and \"statusApprove\"='FALSE'");
$numcheck=pg_num_rows($qry_check);
if($numcheck==0){  //ถูก approve แล้ว
	echo "<div style=\"padding:20px;text-align:center\"><h2>มีการทำรายการก่อนหน้านี้แล้วค่ะ<h2></div>";
	//echo "<meta http-equiv='refresh' content='3; URL=approve_ann.php'>";
}else{
	if(sizeof($cid) == 0){ //กรณีไม่เลือกให้ ยกเลิกทั้งหมดเลยทั้งกรณีที่มีข้อมูลแล้วหรือไม่มีข้อมูล
		$upd="update \"nw_annouceuser\" set \"statusAccept\"='0' where \"annId\"='$annId'";
		if($resins=pg_query($upd)){
		}else{
			$status++;
		}
	}else{
		//ค้นหา user ทั้งหมดที่ขออนุมัติ
		$qry_user=pg_query("select * from \"nw_annouceuser\" where \"annId\"='$annId' and \"statusAccept\"='1'  order by \"id_user\"");
		$num=pg_num_rows($qry_user);
		while($result=pg_fetch_array($qry_user)){
			$id_user2=$result["id_user"];
			
			//ตรวจสอบว่า user ตรงกับที่อนุมัติหรือไม่
			if(in_array($id_user2,$cid)){ //กรณีตรงกับให้ update ว่าอนุมัติ
				echo "รหัส $id_user2 อนุมัติ<br>";
				$upd="update \"nw_annouceuser\" set \"statusAccept\"='1' where \"annId\"='$annId' and \"id_user\"='$cid[$i]'";
			}else{
				echo "รหัส $id_user2 ไม่อนุมัติ<br>";
				$upd="update \"nw_annouceuser\" set \"statusAccept\"='0' where \"annId\"='$annId' and \"id_user\"='$id_user2'";
			}
			if($resins=pg_query($upd)){
			}else{
				$status++;
			}
		}
	}
	
	if($chknewemp == "1"){
			if($newempdep==""){
				$newbie = "UPDATE nw_annouceuser_newbie SET \"statusAccept\"='1',dep_id='allemp' WHERE \"annId\"='$annId' and \"statusAccept\"='0'";
			}else{
				$newbie = "UPDATE nw_annouceuser_newbie SET \"statusAccept\"='1',dep_id='$newempdep' WHERE \"annId\"='$annId' and \"statusAccept\"='0'";
			}
				if($renewbie=pg_query($newbie)){
				}else{
					$status++;
				}	
	}else{

			$newbie = "UPDATE nw_annouceuser_newbie SET \"statusAccept\"='3' WHERE \"annId\"='$annId' and \"statusAccept\"='0'";

				if($renewbie=pg_query($newbie)){
				}else{
					$status++;
				}
	}

	if($val=="1"){
		?>
		<form method="post" name="frm1" action="process_approve.php">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr>
			<td><b>ระบุเหตุผลที่ยกเลิกประกาศ</b></td>
		</tr>
		<tr>
			<td><textarea name="resultnotapp" id="resultnotapp" cols="40" rows="4"></textarea></td>
		</tr>
		<tr><td align="center">
			<input type="hidden" name="val" value="2">
			<input type="hidden" name="stscancel" value="1">
			<input type="hidden" name="annId" value="<?php echo $annId;?>">
			<input type="submit" value="ตกลง" onclick="return checkdata();">
		</td></tr>
		</table>
		</form>
		<?php
	}
	if($val=="2"){
		if($stscancel=="1"){
			$resultnot="'".$resultnotapp."'";
			$statusCancel="TRUE";
		}else{
			$resultnot="null";
			$statusCancel="FALSE";
		}
		$update="update \"nw_annoucement\" 
				set \"statusApprove\"='TRUE', 
					\"annApprove\"='$id_user', 
					\"approveDate\"='$curdate',
					\"resultnotapp\"=$resultnot,
					\"statusCancel\"='$statusCancel'
					where \"annId\" = '$annId'";
		if($result=pg_query($update)){
		}else{
			$ins_error=$result;
			$status++;
		}
			
			$newbie = "UPDATE nw_annouceuser_newbie SET \"statusAccept\"='3' WHERE \"annId\"='$annId' and \"statusAccept\"='0'";

			if($renewbie=pg_query($newbie)){
			}else{
				$status++;
			}

		if($status == 0){
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) อนุมัติ Annoucement', '$datelog')");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
			if($stscancel=="1"){
				echo "<FORM METHOD=GET ACTION=\"#\">";
				echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
				echo "</FORM>";
			}else{
				echo "<meta http-equiv='refresh' content='2; URL=approve_ann.php'>";
			}
		}else{
			pg_query("ROLLBACK");
			echo $ins_error."<br>";
			echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
			if($stscancel=="1"){
				echo "<FORM METHOD=GET ACTION=\"#\">";
				echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
				echo "</FORM>";
			}else{
				echo "<meta http-equiv='refresh' content='2; URL=approve_ann.php'>";
			}
		}
	}
}
?>
</td>
</tr>
</table>
</body>
</html>