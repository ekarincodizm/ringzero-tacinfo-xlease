<?php
include("../../config/config.php");

$CallBackID = $_GET["CallBackID"];
if($CallBackID == ""){$CallBackID = $_POST["CallBackID"];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไข</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	document.getElementById("textsearchname").style.visibility = 'hidden';
    document.getElementById("user").style.visibility = 'hidden';
	
	$("#user").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	
	$("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#calltypeID").change(function(){
		//ตรวจสอบว่าอนุญาตให้แสดง form หรือไม่
		var src = $('#calltypeID option:selected').attr('value');
		$.post("checktype.php",{
			method : "checkform",
			calltypeID : src
		},
		function(data){
			
			var obj2=data.split(",");	
			var callFrom=obj2[0];
			
			//แสดงแหล่งที่ทราบข้อมูล
			if(callFrom=="1"){
				$("#statusShow").show();
				$("#showfrom").load("checktype.php?method=showfrom&calltypeID="+src+"&CallBackID="+<?php echo $CallBackID;?>);	
			}else{
				$("#statusShow").hide();
			}

		});
	});
});

function chktype()
{	
	if(document.getElementById("calltype1").checked == true)
	{	
		document.getElementById("dep").style.visibility = 'visible';
		document.getElementById("textsearchname").style.visibility = 'hidden';
		document.getElementById("user").style.visibility = 'hidden';
	}
	else
	{
		document.getElementById("dep").style.visibility = 'hidden';
		document.getElementById("textsearchname").style.visibility = 'visible';
		document.getElementById("user").style.visibility = 'visible';
	}
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm1.CusName.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อลูกค้า";
	}

	if (document.frm1.CusPhone.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เบอร์ติดต่อกลับ";
	}

	if (document.frm1.TitleCall.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อเรื่อง";
	}
	
	if(document.getElementById("calltype2").checked == true){
		if (document.frm1.user.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ พนักงานหรือแผนกที่ต้องการจะติดต่อ";
		}
	}
	if(document.getElementById("calltypeID").value == ""){
		theMessage = theMessage + "\n -->  กรุณาใส่เลือกประเภทการติดต่อ";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	} 
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

var gFiles = 0;
function addFile(){
	var li = document.createElement('div');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="callFromID[]" id="callFromID"><?php
	$qry_type=pg_query("SELECT \"callFromID\", \"callFromName\" FROM callback_from");
	while($res_type=pg_fetch_array($qry_type)){ 
		echo "<option value=\"$res_type[callFromID]\" >$res_type[callFromName]</option>";
	}?></select>&nbsp;<button onClick="removeFile(\'file-' + gFiles + '\')">ลบ</button>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;	
}

function removeFile(aId) {
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}
</script>
	
</head>
<body>
<br>
<center>
<?php
$query = pg_query("select * from public.\"callback\" where \"CallBackID\" = '$CallBackID' "); 
while($result = pg_fetch_array($query))
{
	$CusName=$result["CusName"];
	$CusPhone=$result["CusPhone"];
	$CallTitle=$result["CallTitle"];
	$CallDetial=$result["CallDetial"];
	$doerID=$result["doerID"];
	$doerStamp=$result["doerStamp"];
	$Want_dep_id=$result["Want_dep_id"];
	$Want_id_user=$result["Want_id_user"];
	$TimeCallBack=$result["TimeCallBack"]; // พนักงานที่ต้องการจะติดต่อ
	$callTypeID=$result["callTypeID"];
}
if($TimeCallBack=="")
{
	$datepicker = nowDate();
	$time_h = "00";
	$time_m = "00";
}
else
{
	$datepicker = substr($TimeCallBack,0,10);
	$time_h = substr($TimeCallBack,11,2);
	$time_m = substr($TimeCallBack,14,2);
}

$query_doerUser = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
while($result_doerUser = pg_fetch_array($query_doerUser))
{
	$doerUser = $result_doerUser["fullname"];
}

if($Want_dep_id != "") // ถ้าเป็นแผนก
{
	$calltype = "1";
}
elseif($Want_id_user != "") // ถ้าเป็นพนักงาน
{
	$calltype = "2";
	
	$query_user = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$Want_id_user' ");
	while($result_user = pg_fetch_array($query_user))
	{
		$went_user = $result_user["fullname"];
		$user_group = $result_user["user_group"]; // รหัสกลุ่ม
	}
	
	$query_dep = pg_query("select * from public.\"department\" where \"dep_id\" = '$user_group' ");
	while($result_dep = pg_fetch_array($query_dep))
	{
		$went_dep = "แผนก:".$result_dep["dep_name"];
	}
	
	$fulluser = "$Want_id_user $went_user $went_dep";
}
?>
<fieldset><legend><B>แก้ไข</B></legend>
<center>
<form method="post" name="frm1" action="save_edit_callback.php">
<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
	<tr>
		<td align="right" width="190">ชื่อลูกค้า :</td><td><input type="text" name="CusName" id="CusName" size="30" value="<?php echo $CusName; ?>"></td><td></td>
	</tr>
	<tr>
		<td align="right">เบอร์ติดต่อกลับ :</td><td><input type="text" name="CusPhone" id="CusPhone" size="30" value="<?php echo $CusPhone; ?>"></td><td></td>
	</tr>
	<tr>
		<td align="right">พนักงานหรือแผนกที่ต้องการจะติดต่อ :</td>
		<td>
			<input type="radio" name="calltype" id="calltype1" value="1" <?php if($calltype=="1" || $calltype==""){echo "checked";} ?> onchange="chktype()">แผนก &nbsp;
			<br>
			<input type="radio" name="calltype" id="calltype2" value="2" <?php if($calltype=="2"){echo "checked";} ?> onchange="chktype()">พนักงาน &nbsp;
		</td>
		<td>
			<select name="dep" id="dep">
				<?php
					$qry_no = pg_query("select * from public.\"department\" order by \"dep_name\" ");
					while($res_no=pg_fetch_array($qry_no))
					{
						$dep_id = trim($res_no["dep_id"]);
						$dep_name = trim($res_no["dep_name"]);
				?>
						<option value="<?php echo $dep_id; ?>" <?php if($Want_dep_id==$dep_id){echo "selected";} ?>><?php echo $dep_name; ?></option>
				<?php
					}
				?>
			</select>
			<br>
			<font id="textsearchname">ค้นหาพนักงาน </font><input type="text" name="user" id="user" size="50" value="<?php echo $fulluser; ?>">
		</td>
	</tr>
	<tr>
		<td align="right">ชื่อเรื่องที่ลูกค้าจะติดต่อ :</td><td><input type="text" name="TitleCall" id="TitleCall" size="30" value="<?php echo $CallTitle; ?>"></td><td></td>
	</tr>
	<tr>
		<td align="right">ประเภทการติดต่อ :</td>
		<td>
			<select name="calltypeID" id="calltypeID">
				<option value="">---เลือก---</option>
				<?php
					$qrytype=pg_query("select * from callback_type");
					while($restype=pg_fetch_array($qrytype)){
						$callTypeID2=$restype["callTypeID"];
						$callTypeName=$restype["callTypeName"];
						?>
						<option value="<?php echo $callTypeID2;?>" <?php if($callTypeID2==$callTypeID) echo "selected";?>><?php echo $callTypeName;?></option>
						<?php
					}
				?>
			</select>
		</td><td></td>
	</tr>
	<?php
		//ค้นหาว่ามีแหล่งที่ทราบข้อมูลหรือไม่
		$qrychkfrom=pg_query("SELECT \"callFromID\" FROM callback_details_from where \"CallBackID\"='$CallBackID'");
		$numfrom=pg_num_rows($qrychkfrom);
	?>
	<tr id="statusShow">
		<td align="right" valign="top">
		แหล่งที่ทราบข้อมูล :
		</td>
		<td id="showfrom">
			<?php 
			while($reschk=pg_fetch_array($qrychkfrom)){
				list($callFromIDchk)=$reschk;
				echo "<div>";
				$qryfrom=pg_query("SELECT \"callFromID\", \"callFromName\" FROM callback_from where \"callFromID\"='$callFromIDchk'");
				echo "<select name=\"callFromID[]\" id=\"callFromID\">";
				
				while($resfrom=pg_fetch_array($qryfrom)){
					list($callFromID,$callFromName)=$resfrom;
					?>
					<option value="<?php echo $callFromID;?>" <?php if($callFromID==$callFromIDchk) echo "selected";?>><?php echo $callFromName;?></option>
					<?php
					
				}
				echo "</select>";
				
				echo "</div>";			
			}
			
			?>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">รายละเอียดที่ลูกค้าจะติดต่อ :</td><td colspan="2"><textarea name="DetailCall" cols="50" rows="5"><?php echo $CallDetial; ?></textarea></td>
	</tr>
	<tr>
		<td align="right" valign="top">วันเวลาที่สะดวกให้ติดต่อกลับ :</td>
		<td colspan="2">
			<input type="radio" name="TimeCallBack_select" value="t1" <?php if($TimeCallBack == ""){echo "checked=\"checked\"";} ?>> สะดวกให้ติดต่อกลับทันที
			<br>
			<input type="radio" name="TimeCallBack_select" value="t2" <?php if($TimeCallBack != ""){echo "checked=\"checked\"";} ?>> สะดวกให้ติดต่อกลับ วันที่ <input type="text" name="datepicker" id="datepicker" value="<?php echo $datepicker; ?>" style="text-align:center" size="15" readonly> &nbsp;&nbsp; เวลา 
			<select name="time_h">
				<?php
					for($h=0;$h<24;$h++)
					{
						if(strlen($h)==1)
						{
							$h = "0".$h;
						}
						
						if($time_h == $h)
						{
							echo "<option value=\"$h\" selected>$h</option>";
						}
						else
						{
							echo "<option value=\"$h\">$h</option>";
						}
					}
				?>
			</select>
			 : 
			<select name="time_m">
				<?php
					for($m=0;$m<60;$m++)
					{
						if(strlen($m)==1)
						{
							$m = "0".$m;
						}
						
						if($time_m == $m)
						{
							echo "<option value=\"$m\" selected>$m</option>";
						}
						else
						{
							echo "<option value=\"$m\">$m</option>";
						}
					}
				?>
			</select> น.
		</td>
	</tr>
	<tr>
		<td align="center" colspan="3">
			<input type="hidden" name="CallBackID" value="<?php echo $CallBackID; ?>">
			<input type="submit" value="บันทึกการแก้ไข" onclick="return validate();"> &nbsp;&nbsp;
			<input type="button" value="ยกเลิก/ปิด" onclick="window.close();">
		</td>
	</tr>
</table>

</form>
</center>
</fieldset>
</center>
</body>

<script type="text/javascript">
$(document).ready(function(){
	chktype();
});
</script>

</html>