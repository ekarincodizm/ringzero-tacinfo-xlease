<?php
include("../../config/config.php");

$show = $_GET["show"];
$CallBackID = $_GET["CallBackID"];
if($CallBackID == ""){$CallBackID = $_POST["CallBackID"];}
$DetailCallBack = $_POST["DetailCallBack"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>บันทึกการติดต่อกลับ</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm1.DetailCallBack.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ รายละเอียดการติดต่อกลับ";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
		if(document.getElementById("close").checked==true){
			if(document.getElementById("resultno").checked==true){
				var t=0;
				for(i=0;i<document.getElementById("numrej").value;i++){
					if(document.getElementById("rej"+i).checked==true)
					{
						t++;
					}
				}
				if(t>0){
					return true;
				}else{
					alert("กรุณาเลือกเหตุผลในการปฏิเสธ");
					return false;
				}
			}else{
				 return true;
			}
		}else{
			return true;
		}
	} 
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}


$(document).ready(function(){
	$("#textresult").hide();
	$("#resultShow").hide();
	$("#resultno").click(function(){ 
		$("#textresult").show();
	});
		
	$("#resultyes").click(function(){
		$("#textresult").hide();
	});
	
	$("#resultwait").click(function(){
		$("#textresult").hide();
	});
	
	$("#close").click(function(){
		if($('#close') .attr( 'checked')==true){
			$("#resultShow").show();
			$("#timeback").hide();		
		}else{
			$("#resultShow").hide();
			$("#timeback").show();
		}
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
});
</script>
	
</head>
<body>
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
	$callTypeID=$result["callTypeID"];
}

$query_doerUser = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
while($result_doerUser = pg_fetch_array($query_doerUser))
{
	$doerUser = $result_doerUser["fullname"];
}

if($Want_dep_id != "")
{
	$query_dep = pg_query("select * from public.\"department\" where \"dep_id\" = '$Want_dep_id' ");
	while($result_dep = pg_fetch_array($query_dep))
	{
		$went = "แผนก : ".$result_dep["dep_name"];
	}
}
elseif($Want_id_user != "")
{
	$query_dep = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$Want_id_user' ");
	while($result_dep = pg_fetch_array($query_dep))
	{
		$went = $result_dep["fullname"];
	}
}
//save_afterCallback.php
?>
<br>
<center>
<form name="frm1" method="post" action="save_afterCallback.php">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>บันทึกการติดต่อกลับ</B></legend>
			<center>
				<table width="600" border="0">
					<tr>
						<td align="right" width="150"><b>ชื่อลูกค้า :</b></td>
						<td><?php echo $CusName; ?></td>
					</tr>
					<tr>
						<td align="right"><b>เบอร์ติดต่อกลับ :</b></td>
						<td><?php echo $CusPhone; ?></td>
					</tr>
					<tr>
						<td align="right"><b>บุคคลที่ต้องการจะติดต่อ :</b></td>
						<td><?php echo $went; ?></td>
					</tr>
					<tr>
						<td align="right"><b>พนักงานที่รับเรื่อง :</b></td>
						<td><?php echo $doerUser; ?></td>
					</tr>
					<tr>
						<td align="right"><b>วันเวลาที่รับเรื่อง :</b></td>
						<td><?php echo $doerStamp; ?></td>
					</tr>
					<tr>
						<td align="right"><b>เรื่อง :</b></td>
						<td><?php echo $CallTitle; ?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>รายละเอียด :</b></td>
						<td><textarea name="DetailCall" cols="30" rows="4" disabled><?php echo $CallDetial; ?></textarea></td>
					</tr>
					<tr>
						<td align="right"><b>ประเภทการติดต่อ :</b></td>
						<td>
							<?php
								if($callTypeID!=""){
									$qrytype=pg_query("select * from callback_type where \"callTypeID\"='$callTypeID'");
									if($restype=pg_fetch_array($qrytype)){
										echo $restype["callTypeName"];
									}
								}
								?>
							
						</td><td></td>
					</tr>
					<?php
					//ค้นหาแหล่งที่ทราบข้อมูล
					$qrychkfrom=pg_query("SELECT \"callFromName\" FROM callback_details_from a
					left join callback_from b on a.\"callFromID\"=b.\"callFromID\" where \"CallBackID\"='$CallBackID'");
					$numchkfrom=pg_num_rows($qrychkfrom);
					if($numchkfrom>0){
					?>
					<tr id="statusShow"><td align="right" valign="top"><b>แหล่งที่ทราบข้อมูล :</b></td>
					<td id="showfrom">
					<?php
					$t=1;
					while($reschk=pg_fetch_array($qrychkfrom)){		
						list($callFromName)=$reschk;	
						if($t!=$numchkfrom){
							echo "$callFromName,";
						}else{
							echo "$callFromName";
						}
						$t++;
					}
					?>
					</td></tr>
					<?php
					}
					
					if($callTypeID!=""){ //ถ้าว่างแสดงว่าเป็นข้อมูลเก่า
						//ตรวจสอบว่าประเภทการติดต่อนี้มีบังคับให้สรุปงานหรือไม่
						$qrytype=pg_query("select \"callResult\",\"callReject\" from callback_type where \"callTypeID\"='$callTypeID'");
						$res=pg_fetch_array($qrytype);
						list($callResult,$callReject)=$res;
						if($callResult=="1"){ //บังคับให้มีการจบงาน
							echo "<tr><td align=\"right\" valign=\"top\"></td><td><input type=\"checkbox\" id=\"close\" name=\"close\" value=\"1\"> ปิดงาน</td>";
							echo "<tr id=\"resultShow\"><td align=\"right\" valign=\"top\"></td><td id=\"showResult\">";
							echo "
							<input type=\"radio\" name=\"stsResult\" id=\"resultyes\" value=\"1\" checked>สำเร็จ 
							<input type=\"radio\" name=\"stsResult\" id=\"resultno\" value=\"0\">ปฏิเสธ";
							echo "<div id=textresult>";
							echo "<div><u><b>เหตุผลที่ปฏิเสธ</b></u></div>";
							$qryrejec=pg_query("select \"callRejID\",\"callRejName\" from callback_reject where \"callTypeID\"='$callTypeID'");
							$numrej=pg_num_rows($qryrejec);
							$p=0;
							while($resrej=pg_fetch_array($qryrejec)){
								list($callRejID,$callRejName)=$resrej;
								echo "<div><input type=\"checkbox\" name=rej[] id=\"rej$p\" value=$callRejID>$callRejName</div>";
								$p++;
							}
							echo "<input type=\"hidden\" name=\"numrej\" id=\"numrej\" value=\"$numrej\"></div>";
							
							echo "</td></tr>";				
						}
					}
					?>

					<tr>
						<td align="right" valign="top"><b>รายละเอียดการติดต่อกลับ :</b></td>
						<td><textarea name="DetailCallBack" cols="50" rows="5"><?php echo $DetailCallBack; ?></textarea></td>
					</tr>
					<?php
					if($callResult=="1"){
					?>
					<tr id="timeback">
						<td align="right" valign="top"><b>วันเวลาที่ให้ติดต่อกลับ :</b></td>
						<td>
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
					<?php }?>
					<tr>
					
						<td align="center" colspan="2"><br>
						<input type="hidden" name="callResult" value="<?php echo $callResult; ?>">
						<input type="hidden" name="CallBackID" value="<?php echo $CallBackID; ?>">
						<input type="hidden" name="show" value="<?php echo $show; ?>">
						<input type="submit" value="บันทึก" onclick="return validate();"> &nbsp;&nbsp;
						<input type="button" value="กลับ" onclick="window.location='my_callback.php?show=<?php echo $show; ?>'"></td>
					</tr>
				</table>
			</center>
			</fieldset>
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>