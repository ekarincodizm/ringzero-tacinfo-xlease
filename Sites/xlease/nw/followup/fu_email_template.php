<?php
include("../../config/config.php");
session_start();
$id_user1 = $_SESSION["av_iduser"];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	

	
	$("#id_user").hide();
	$("#sending").hide();
	$("input[type='radio']").change(function(){

	if($(this).val()=="sendtype"){
		$("#id_user").hide();
		$("#sending").show();
		$("#id_user").val("");
	}
	else if($(this).val()=="senduserlist"){
		$("#id_user").show();
		$("#sending").hide();
		$("#sending").val("");
		$("#sendname").val("");
		$("#sendemail").val("");
	}
	else{
		
        $("#id_user").hide();
		$("#sending").hide();
		$("#sendname").val("");
		$("#sendemail").val("");
		$("#id_user").val("");			
    }

});
});


	function fncCreateElement(){
		
	   var mySpan = document.getElementById('mySpan');
		
		
		var myElement1 = document.createElement('input');
		myElement1.setAttribute('type',"file");
		myElement1.setAttribute('name',"fileup[]");		
		mySpan.appendChild(myElement1);			
		
	}
	
function checkList()
{
if(document.getElementById("temname").value=="")
{
alert('กรุณากรอกชื่อ Templater ด้วยครับ');
return false;
}
if(document.getElementById("temdetail").value=="")
{
alert('กรุณากรอก รายละเอียดของ template ด้วยครับ -');
return false;
}
if(document.getElementById("header").value=="")
{
alert('กรุณากรอก หัวเรื่องของ template ด้วยครับ -');
return false;
}
else
{
return true;
}
}
</script>


<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

?>

<body>
<center><legend><h2>... E-Mail Template ...</h2></legend></center>
<form name="frm" method="post" action="fu_email_template_query.php" enctype="multipart/form-data">

	<hr width="850">
		<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>Template:</b></td>
					<td bgcolor="#FFFFFF">
						<input type="text" name="temname" id="temname">
					</td>
			</tr>
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>หัวเรื่อง :</b></td>
					<td bgcolor="#FFFFFF">
						<input type="text" name="header" id="header">
					</td>
			</tr>
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ข้อความ :</b></td>
						<td bgcolor="#FFFFFF"><textarea rows="10" cols="100" name="temdetail" id="temdetail"></textarea>
							
						</td>
			</tr>
			
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ไฟล์แนบ:</b></td>
						<td bgcolor="#FFFFFF">
							<input type="file" name="fileup[]" id="fileup" >
							<input name="btnButton" id="btnButton" type="button" value="+" onClick="JavaScript:fncCreateElement();">
						<br>
							<span id="mySpan"></span>
						</td>
										
			</tr>
			<!--<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>รหัสไฟล์ที่มีการ encode:</b></td>
						<td bgcolor="#FFFFFF">
							<textarea rows="5" cols="100" name="temcode" id="temcode"></textarea>
						</td>
										
			</tr>-->
			
<tr bgcolor="#BCE6FC">
			<?php 

				$qry_name=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$id_user1'");
				$result=pg_fetch_array($qry_name); 
				$thaiacename1 = $result["fullname"];

			?>
			
    <td valign="top" height="35" align="right"><b>ผู้ส่ง :</b></td>
		<td bgcolor="#FFFFFF">
			<input type="radio" name="send" id="send" value="senduser" checked>ชื่อตาม User :<?php echo "$thaiacename1"." "; ?>(<?php echo "$id_user1"; ?>)
				<input type="hidden" name="hdiduser" id="hdiduser" value="<?php echo "$id_user1"; ?>">
				<p>
			<input type="radio" name="send" id="send" value="senduserlist">ชื่อตามรายชื่อ 
				<p>
				<select name="id_user" id="id_user">
				
					<?php 
					$qry_name4=pg_query("select * from \"Vfuser\"");

						while($result4=pg_fetch_array($qry_name4)){?>
							<option value="<?php echo $result4['id_user'];?>"><?php echo $result4['fullname'];?></option>
					<?php } ?>
				</select>
				<p>
			<input type="radio" name="send" id="send" value="sendtype">กำหนดเอง 
		<span id="sending">
		<br>
		<table width="300" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="left">
		
			<tr><td align="right">ชื่อ :</td><td><input type="text" name="sendname" id="sendname"></td></tr>
			<tr><td align="right">E-mail :</td><td><input type="text" name="sendemail" id="sendemail"></td></tr>
		</table>	
		</span>
		</td>
</tr>

<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b></b></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value="บันทึก" onclick="return checkList();"></td>
</tr>
</table>



</body>
</form>

