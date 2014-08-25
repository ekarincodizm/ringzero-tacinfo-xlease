<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$auto_id=$_GET["auto_id"]; 
$statusapp=$_GET["stsapp"];  
if($statusapp==""){//มาจาก showdetaillink.php
	$auto_id=$_POST["auto_id"]; 
	if(isset($_POST["app_ap"])){
		$statusapp=1;//กดอนุมัติ
	}else if(isset($_POST["app_unapp"])){
		$statusapp=0;//กดไม่อนุมัติ
	}
}

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
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
pg_query("BEGIN WORK");
$status = 0;

if($statusapp==1){ //กรณีอนุมัติ ต้องดูก่อนว่าอนุมัติเพิ่มข้อมูลหรือแก้ไข
	$qry_link=pg_query("select * from \"temp_linksecur\" where \"auto_id\"='$auto_id'");
	$res_link=pg_fetch_array($qry_link);
	$number_running=$res_link["number_running"];
	$note=trim($res_link["note"]);
	$edittime=trim($res_link["edittime"]);
			
	if($edittime==0){ //กรณีเพิ่มข้อมูล
		//insert ใน temp_linksecur
		$insnw="INSERT INTO nw_linksecur(
				\"numid\", \"note\")
			VALUES ('$number_running', '$note')";
		if($res_nw=pg_query($insnw)){
		}else{
			$status++;
		}
			
		//หาข้อมูลลูกค้าใน temp มาเก็บใน nw
		$qry_tempsecur=pg_query("select * from \"temp_linknumsecur\" where auto_id='$auto_id'");
		while($res_tempsecur=pg_fetch_array($qry_tempsecur)){
			$securID=$res_tempsecur["securID"];
				
			//insert ลงในตาราง nw
			$inssecur="INSERT INTO nw_linknumsecur(\"numid\", \"securID\") VALUES ('$number_running', '$securID')";
			if($res_secur=pg_query($inssecur)){
			}else{
				$status++;
			}
		}
			
		//หาข้อมูล IDNO ใน temp มาเก็บใน nw
		$qry_tempIDNO=pg_query("select * from \"temp_linkIDNO\" where auto_id='$auto_id'");
		while($res_tempIDNO=pg_fetch_array($qry_tempIDNO)){
			$IDNO=$res_tempIDNO["IDNO"];
			$guaranteeDate2=$res_tempIDNO["guaranteeDate"]; 
			if($guaranteeDate2==""){
				$guaranteeDate="null";
			}else{
				$guaranteeDate="'".$guaranteeDate2."'";				}
				
			//insert ลงในตาราง nw
			$insIDNO="INSERT INTO \"nw_linkIDNO\"(\"numid\", \"IDNO\",\"guaranteeDate\") VALUES ('$number_running', '$IDNO',$guaranteeDate)";
			if($res_IDNO=pg_query($insIDNO)){
			}else{
				$status++;
			}
		}
			
		//update ใน temp_securities ว่าได้มีการ update แล้ว
		$up_temp="update \"temp_linksecur\" set \"user_app\"='$app_user', 
				\"stampDateApp\"='$app_date', 
				\"statusApp\"='1'
				where \"auto_id\" = '$auto_id'";
		if($res_temp=pg_query($up_temp)){
		}else{
			$error4=$up_temp;
			$status++;
		}	
	}else{ //กรณีแก้ไข
		//update ใน  nw_linksecur
		$up_nw="UPDATE nw_linksecur
				SET \"note\"='$note' where \"numid\" = '$number_running'";
		if($res_nw=pg_query($up_nw)){
		}else{
			$error4=$res_nw;
			$status++;
		}	
				
		//update ใน  nw_linknumsecur โดยลบข้อมูลเก่าออกก่อนแล้ว add เข้าใหม่
		$del="DELETE FROM nw_linknumsecur WHERE \"numid\" = '$number_running'";
		if($resdel=pg_query($del)){
		}else{
			$status++;
		}
			
		//ดึงข้อมูลใน  temp มา insert ใน nw
		$qry_tempsecur=pg_query("select * from \"temp_linknumsecur\" where auto_id='$auto_id'");
		while($res_tempsecur=pg_fetch_array($qry_tempsecur)){
			$securID=$res_tempsecur["securID"];
									
			//insert ข้อมูลใหม่ลงในตาราง nw
			$insIDNO="INSERT INTO nw_linknumsecur(\"numid\", \"securID\") VALUES ('$number_running', '$securID')";
			if($res_IDNO=pg_query($insIDNO)){
			}else{
				$status++;
			}
		}
			
		//update ใน  nw_linkIDNO โดยลบข้อมูลเก่าออกก่อนแล้ว add เข้าใหม่
		$delidno="DELETE FROM \"nw_linkIDNO\" WHERE \"numid\" = '$number_running'";
		if($resdelidno=pg_query($delidno)){
		}else{
			$status++;
		}
			
		//ดึงข้อมูลใน  temp มา insert ใน nw
		$qry_tempidno=pg_query("select * from \"temp_linkIDNO\" where auto_id='$auto_id'");
		while($res_tempsecur=pg_fetch_array($qry_tempidno)){
			$IDNO=$res_tempsecur["IDNO"];
			$guaranteeDate=$res_tempsecur["guaranteeDate"];
			if($guaranteeDate==""){ 
				$guaranteeDate="null";
			}else{ 
				$guaranteeDate="'".$guaranteeDate."'"; 
			}						
			//insert ข้อมูลใหม่ลงในตาราง nw
			$insIDNO="INSERT INTO \"nw_linkIDNO\"(\"numid\", \"IDNO\",\"guaranteeDate\") VALUES ('$number_running', '$IDNO',$guaranteeDate)";
			if($res_IDNO=pg_query($insIDNO)){
			}else{
				$status++;
			}
		}
			
		//update ใน temp_linksecur ว่าได้มีการ update แล้ว
		$up_temp="update \"temp_linksecur\" set \"user_app\"='$app_user', 
				\"stampDateApp\"='$app_date', 
				\"statusApp\"='1'
				where \"auto_id\" = '$auto_id'";
		if($res_temp=pg_query($up_temp)){
		}else{
			$error4=$up_temp;
			$status++;
		}	
	}
}else{ //กรณีไม่อนุมัติ
		$up_temp="update \"temp_linksecur\" set \"user_app\"='$app_user', 
					\"stampDateApp\"='$app_date', 
					\"statusApp\"='0'
					where \"auto_id\" = '$auto_id'";
		if($res_temp=pg_query($up_temp)){
		}else{
			$status++;
		}	
}
if($status == 0){

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(ALL) ยืนยันการเชื่อมโยงหลักทรัพย์ค้ำประกัน', '$app_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_ApproveLink.php'>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{
	pg_query("ROLLBACK");
	echo $insnw;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
	//echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_ApproveLink.php'\">";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}
?>
</td>
</tr>
</table>
</body>
</html>