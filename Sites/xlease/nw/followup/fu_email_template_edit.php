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
	

$("#editbox").hide();
	$("#edit").click(function(){ 
		if($('#edit') .attr( 'checked')==true){
			$("#editbox").show();
		}else{
			$("#editbox").hide();

		
		}
	});
	
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
$temID = pg_escape_string($_GET['temID']);


$qry_name1=pg_query("select * from \"fu_template\" WHERE \"temID\" = '$temID'");
$result1=pg_fetch_array($qry_name1); 
$temdetail1 = $result1['tem_detail'];
$temdetail2 = str_replaceout($temdetail1);

	

?>

<body>
<center><legend><h2>... E-Mail Template ...</h2></legend></center>
<form name="frm" method="post" action="fu_email_template_query_edit.php" enctype="multipart/form-data">

	<hr width="850">
		<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>Template:</b></td>
					<td bgcolor="#FFFFFF">
						<input type="text" name="temname" id="temname" value="<?php echo $result1['tem_name'];?>">
						<input type="hidden" name="hdtemid" id="hdtemid" value="<?php echo $temID?>">
					</td>
			</tr>
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>หัวเรื่อง :</b></td>
					<td bgcolor="#FFFFFF">
						<input type="text" name="header" id="header" value="<?php echo $result1['tem_header'];?>">
					</td>
			</tr>
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ข้อความ :</b></td>
						<td bgcolor="#FFFFFF"><textarea rows="10" cols="100" name="temdetail" id="temdetail"><?php echo $temdetail2 ?></textarea>
							
						</td>
			</tr>
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ไฟล์แนบเก่า:</b>
					<input type="button" value="ลบไฟล์แนบ" onclick="parent.location.href='fu_email_template_query_delfile.php?temID=<?php echo $temID ?>'"></td>
					</td>
					<td bgcolor="#FFFFFF">
					
						<?php
						if(empty($result1['tem_file'])){
						
							echo 'ไม่มีไฟล์แนบ';
						}else{
						
						$qry_name2 = pg_query("select * from \"fu_template\" WHERE \"temID\" = '$temID'");
						$result2=pg_fetch_array($qry_name2);						
						$ff = $result2["tem_file"];
						$file=explode("/",$ff);						
						
						for($i=1;$i<sizeof($file);$i++){
						?>							
						<a href="fileupload/<?php echo $file[$i];?>" target="_blank"><?php echo $file[$i];?>
						<br>
						<?php }} ?>	
							<input type="hidden" name="filehd" id="filehd" value="<?php echo $ff;?>">
						
			</tr>		
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ไฟล์แนบ ใหม่:</b></td>
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
							<textarea rows="5" cols="100" name="temcode" id="temcode"><?php echo $result1['tem_encode'];?></textarea>
						</td>-->
										
			</tr>
			<tr bgcolor="#BCE6FC">
					   <td valign="top" height="35" align="right"><b>ผู้ส่ง :</b></td>
					   <td bgcolor="#FFFFFF">ชื่อ : <?php echo $result1['tem_sendname'];?> Email : <?php echo $result1['tem_send_email'];?></td>
					   <input type="hidden" name="hdsendname" id="hdsendname" value="<?php echo $result1['tem_sendname'];?>">
					   <input type="hidden" name="hdsendemail" id="hdsendemail" value="<?php echo $result1['tem_send_email'];?>">
			</tr>		 
			<tr bgcolor="#BCE6FC">
						<td valign="top" height="35" align="right"><b>แก้ไขผู้ส่ง :</b></td>	
						<td bgcolor="#FFFFFF"><input type="checkbox" name="edit" id="edit" value="edityes">แก้ไขผู้ส่ง</td>
			</tr>		 		
	<tr bgcolor="#BCE6FC" name="editbox" id="editbox">
			<?php 

				$qry_name3=pg_query("select * from \"Vfuser\"  WHERE \"id_user\" = '$id_user1'");
				$result3=pg_fetch_array($qry_name3); 
				$thaiacename1 = $result["fullname"];

			?>
			<input type="hidden" name="hdiduser" id="hdiduser" value="<?php echo "$id_user1"; ?>">
    <td valign="top" height="35" align="right"></td>
		<td bgcolor="#FFFFFF">
			<input type="radio" name="send" id="send" value="senduser" checked>ชื่อตาม User :<?php echo "$thaiacename1"." "; ?>(<?php echo "$id_user1"; ?>)
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
				<span id="sending"><br>
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

