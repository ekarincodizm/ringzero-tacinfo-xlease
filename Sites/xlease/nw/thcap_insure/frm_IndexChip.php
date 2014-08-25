<?php
session_start();
include("../../config/config.php");

$method=$_GET["method"];
$id=$_GET["auto_id"];
if($method=="noapp"){
	$upchk="UPDATE thcap_insure_checkchip
	SET \"statusApp\"='4' WHERE auto_id=$id";
	if($reschk=pg_query($upchk)){
	}else{
		$status++;
	}
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ประกันอัคคีภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){  
    $("#s1").show();
	$("#numDeed").focus();
	$("#s2").hide();
	$("#contractID").autocomplete({
        source: "s_contractold.php",
        minLength:2
    }); 
	
	$("#numDeed").autocomplete({
        source: "s_numdeed.php",
        minLength:2
    }); 
	
	$("#search1").click(function(){
		$("#s1").show();
		$("#numDeed").focus();
		$("#contractID").val('');
		$("#s2").hide();
	});
	
	$("#search2").click(function(){
		$("#s1").hide();
		$("#s2").show();
		$("#contractID").focus();
		$("#numDeed").val('');
	});
	
	var refreshId1 = setInterval(function(){
    $('#div_refresh').load('show_IndexChip.php');
	}, 2000); //Refresh 
});

function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if(document.getElementById("search2").checked){
		if (document.getElementById("contractID").value =="") {
			theMessage = theMessage + "\n -->  กรุณาระบุเลขที่สัญญา";
		}
	}
	
	if(document.getElementById("search1").checked){
		if (document.getElementById("numDeed").value =="") {
			theMessage = theMessage + "\n -->  กรุณาระบุเลขที่โฉนด";
		}
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		if(document.getElementById("search2").checked){
			document.getElementById("contractID").focus();
		}else{
			document.getElementById("numDeed").focus();
		}
		return false;
	}
}
function MM_openbrWindow(theURL,winName,features) { 
		window.open(theURL,winName,features);
}
</script>
</head>
<body>
<style type="text/css">
	A:link {
		COLOR: #FF3366; TEXT-DECORATION: underline;
	}
	A:visited {
		COLOR: #0000FF; TEXT-DECORATION: underline;
	}
	A:hover {
		COLOR: #ff6600; TEXT-DECORATION: underline;
	} 
</style>
<form name="form1" method="post" action="frm_AddChip.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:800px;">
				<div align="center"><h2>เพิ่มรายละเอียดเงินเอาประกันภัย</h2></div>
				<fieldset><legend><B>เลือกเงื่อนไขการเพิ่มรายการ</B></legend>
					<div style="padding-top:20px;">
						<table width="400" border="0"  align="center">
						<tr>
							<td width="100">
								<input type="radio" name="typesearch" id="search1" value="0" onclick="check_search()" checked> <b>ประกันใหม่ </b>
							</td>	
							<td>
								<div id="s1"><input type="text" name="numDeed" id="numDeed" size="30"> (ค้นจากเลขที่โฉนด)</div>
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" name="typesearch" id="search2" value="1" onclick="check_search()"> <b>ต่ออายุ</b> 
							</td>
							<td>
								<div id="s2"><input type="text" name="contractID" id="contractID" size="30"> (ค้นจากเลขที่สัญญาหรือเลขที่กรมธรรม์)</div>
							</td>
						</tr>
						<tr><td align="center" colspan="2"><br><input type="submit" value="  OK  " onclick="return checkdata()">&nbsp;<input type="button" value="CLOSE" onclick="javascript:window.close();"></td></tr>
						</table>
					</div>
				</fieldset><br>
			</div>	
        </td>
    </tr>
</table>  
<!-- แสดงรายการที่รออนุมัติ -->
<div id="div_refresh">
	<table width="950" border="0"  align="center" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
	<tr bgcolor="#79BCFF" align="center">
		<th>สถานะรายการ</th>
		<th>เลขที่โฉนด</th>
		<th>เลขที่สัญญา</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>	
		<th>สถานะอนุมัติ</th>
		<th>ผู้อนุมัติ</th>
		<th>วันเวลาที่อนุมัติ</th>
		<th>รายละเอียด</th>	
		<th></th>					
	</tr>
	<?php
	//ดึงข้อมูลรายการที่ยังไม่ได้ส่งคำขอ
	$qrychip=pg_query("SELECT auto_id, \"refDeedContract\", b.\"fullname\" as addUser, \"addStamp\", \"statusApp\", c.\"fullname\" as appUser, \"appStamp\", 
	\"statusInsure\" 
	FROM thcap_insure_checkchip a
	left join \"Vfuser\" b on a.\"addUser\"= b.\"id_user\"
	left join \"Vfuser\" c on a.\"appUser\"= c.\"id_user\"
	where \"statusApp\" not in('3','4')");
	$numrowchip=pg_num_rows($qrychip);
	while($reschip=pg_fetch_array($qrychip)){
		list($auto_id,$refDeedContract,$addUser,$addStamp,$statusApp,$appUser,$appStamp,$statusInsure)=$reschip;
		//echo $auto_id;
		if($statusInsure=="0"){
			$txtinsure="ประกันใหม่";
							
			//ค้นหาเลขที่โฉนด
			$qrynumdeed=pg_query("SELECT \"numDeed\" FROM nw_securities where \"securID\"='$refDeedContract'");
			list($numDeed)=pg_fetch_array($qrynumdeed);
							
			$contractID="-";
		}else{
			$txtinsure="ต่ออายุ";
			$numDeed="-";
			$contractID=$refDeedContract;
		}
						
		//สถานะอนุมัติ
		if($statusApp=="0"){
			$txtapp="ไม่อนุมัติ";
		}else if($statusApp=="1"){
			$txtapp="อนุมัติ";
		}else{
			$txtapp="รออนุมัติ";
		}
		$i+=1;
		if($i%2==0){
			$color="class=\"odd\"";
		}else{
			$color="class=\"even\"";
		}
		if($statusApp==0){
			$method="noapp";
		}else{
			$method="";
		}
		
		echo "
			<tr $color>
				<td align=center><b>$txtinsure</b></td>
				<td>$numDeed</td>
				<td align=center>$contractID</td>
				<td>$addUser</td>
				<td align=center>$addStamp</td>	
				<td align=center>$txtapp</td>
				<td>$appUser</td>
				<td align=center>$appStamp</td>
				<td align=center><img src=\"images/detail.gif\" style=\"cursor:pointer\" onClick=\"MM_openbrWindow('show_detailchkChip.php?auto_id=$auto_id&method=$method','','scrollbars=yes,width=900,height=700, left = 0, top = 0')\" value=\"แสดงรายการ\"></td>	";
						
				if($statusApp==0){ //ให้รับทราบเพื่ีอให้หายไปจากหน้านี้
					echo "<td align=center><span style=\"cursor:pointer;\" onclick=\"if(confirm('ยืนยันการรับทราบ!!')){location.href='frm_IndexChip.php?auto_id=$auto_id&method=$method'}\"><u>รับทราบ</u></span></td>";
				}else if($statusApp==1){ //ให้สามารถเลือกแจ้งงานต่อได้
					if($statusInsure=="0"){ //กรณีเป็นประกันใหม่
						//ต้องตรวจสอบก่อนว่ามีข้อมูลสินทรัพย์หรือยังถ้ายังจะยังสร้างใบคำขอไม่ได้
						$qrydeed=pg_query("select * from nw_securities_detail where \"securID\"='$refDeedContract'");
						$numdeed=pg_num_rows($qrydeed);
						if($numdeed>0){
							echo "<td align=center><a href=\"frm_Request.php?auto_id=$auto_id&statusreq=$statusInsure\">สร้างใบคำขอ</a></td>";
						}else{
							echo "<td align=center>รอข้อมูลสินทรัพย์</td>";
						}
					}else{
						echo "<td align=center><a href=\"frm_CreateRequest.php?auto_id=$auto_id&statusreq=$statusInsure&idno=$contractID\">สร้างใบคำขอ</a></td>";
					}
				}else{ //ไม่สามารถทำอะไรได้รออนุมัติข้อมูลก่อน
					echo "<td align=center>-</td>";
				}
		echo"</tr>";
	}
	if($numrowchip==0){
		echo "<tr height=30><td colspan=10 align=center bgcolor=#FFFFFF>--ไม่พบข้อมูล--</td></tr>";
	}
?>
</table>
</div>        
</form>
</body>
</html>