<?php
include("../../config/config.php");

$CusName = $_POST["CusName"];
$CusPhone = $_POST["CusPhone"];
$calltype = $_POST["calltype"];
$dep = $_POST["dep"];
$user = $_POST["user"];
$TitleCall = $_POST["TitleCall"];
$DetailCall = $_POST["DetailCall"];
$TimeCallBack_select = $_POST["TimeCallBack_select"]; // สถานะวันเวลาที่สะดวกให้ติดต่อกลับ
$datepicker = $_POST["datepicker"]; // วันที่สะดวกให้ติดต่อกลับ
$time_h = $_POST["time_h"]; // ชั่วโมงที่สะดวกให้ติดต่อกลับ
$time_m = $_POST["time_m"]; // นาทีที่สะดวกให้ติดต่อกลับ

if($datepicker==""){$datepicker = nowDate();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>บันทึกการติดต่อจากลูกค้า</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
	$("#statusShow").hide();  //ซ่อนแหล่งข้อมูลที่ทราบไว้
	$("#resultShow").hide();  //การปิดงาน
	
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
				$("#callFrom").val(1);
				$("#statusShow").show();
				$("#showfrom").load("checktype.php?method=showfrom&calltypeID="+src);	
			}else{
				$("#callFrom").val(0);
				$("#statusShow").hide();
			}

		});
	});
	
	$("#validate").click(function(){
		
		if(document.frm1.CusName.value==""){
			alert("กรุณาใส่ ชื่อลูกค้า");
			document.frm1.CusName.focus();
			return false;
		}else if(document.frm1.CusPhone.value==""){
			alert("กรุณาใส่ เบอร์ติดต่อกลับ");
			document.frm1.CusPhone.focus();
			return false;
		}
		if(document.getElementById("calltype2").checked==true){
			if (document.frm1.user.value=="" || document.frm1.user.value=="ไม่พบข้อมูล") {
				alert("กรุณาใส่ พนักงานหรือแผนกที่ต้องการจะติดต่อ");
				document.frm1.user.focus();
				return false;
			}
		}
		if(document.frm1.TitleCall.value==""){
			alert("กรุณาใส่ ชื่อเรื่อง");
			document.frm1.TitleCall.focus();
			return false;
		}else if(document.frm1.calltypeID.value==""){
			alert("กรุณาใส่เลือกประเภทการติดต่อ");
			document.frm1.calltypeID.focus();
			return false;
		}
		//กรณีไม่ใช่ค่าว่าง ให้ตรวจสอบว่ามีการเลือกแหล่งที่มาหรือไม่
		if(document.frm1.calltypeID.value!=""){
			if($("#callFrom").val()=="1"){ //แสดงว่าบังคับให้ระบุข้อมูล				
				var p=0;
				if(gFiles==0){
					if(document.frm1.callFromID.value==""){
						p=p+1;
					}
				}else{
					for(b=0;b<document.frm1.callFromID.length;b++){					
						if(document.frm1.callFromID[b].value==""){
							p=p+1;
						}
					}	
				}
				if(p>0){
					alert("กรุณาเลือกแหล่งที่ทราบข้อมูล");
					return false;
				}
			}
			
		}
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

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>

</head>
<body>
<br>
<center>

<form name="frm1" method="post" action="save_beforCallback.php">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>บันทึกการติดต่อจากลูกค้า</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right" width="190">ชื่อลูกค้า :</td><td><input type="text" name="CusName" id="CusName" size="30" value="<?php echo $CusName; ?>"></td><td></td>
					</tr>
					<tr>
						<td align="right">เบอร์ติดต่อกลับ :</td><td><input type="text" name="CusPhone" id="CusPhone" size="30" value="<?php echo $CusPhone; ?>"></td><td></td>
					</tr>
					<tr>
						<td align="right" valign="top">พนักงานหรือแผนกที่ต้องการจะติดต่อ :</td>
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
										<option value="<?php echo $dep_id; ?>" <?php if($dep==$dep_id){echo "selected";} ?>><?php echo $dep_name; ?></option>
								<?php
									}
								?>
							</select>
							<br>
							<font id="textsearchname">ค้นหาพนักงาน </font><input type="text" name="user" id="user" size="50" value="<?php echo $user; ?>">
						</td>
					</tr>
					<tr>
						<td align="right">ชื่อเรื่องที่ลูกค้าจะติดต่อ :</td><td><input type="text" name="TitleCall" id="TitleCall" size="30" value="<?php echo $TitleCall; ?>"></td><td></td>
					</tr>
					<tr>
						<td align="right">ประเภทการติดต่อ :</td>
						<td><input type="hidden" name="callFrom" id="callFrom"><!--สำหรับเก็บว่า type นี้มีแหล่งที่มาหรือไม่-->
							<select name="calltypeID" id="calltypeID">
								<option value="">---เลือก---</option>
								<?php
								$qrytype=pg_query("select * from callback_type");
								while($restype=pg_fetch_array($qrytype)){
									$callTypeID=$restype["callTypeID"];
									$callTypeName=$restype["callTypeName"];
									echo "<option value=$callTypeID>$callTypeName</option>";
								}
								?>
							</select>
						</td><td></td>
					</tr>
					<tr id="statusShow"><td align="right" valign="top">แหล่งที่ทราบข้อมูล :</td><td id="showfrom" colspan="2"></td></tr>
					<tr>
						<td align="right" valign="top">รายละเอียดที่ลูกค้าจะติดต่อ :</td><td colspan="2"><textarea name="DetailCall" cols="50" rows="5"><?php echo $DetailCall; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top">วันเวลาที่สะดวกให้ติดต่อกลับ :</td>
						<td colspan="2">
							<input type="radio" name="TimeCallBack_select" value="t1" <?php if($TimeCallBack_select == "t1" || $TimeCallBack_select == ""){echo "checked=\"checked\"";} ?>> สะดวกให้ติดต่อกลับทันที
							<br>
							<input type="radio" name="TimeCallBack_select" value="t2" <?php if($TimeCallBack_select == "t2"){echo "checked=\"checked\"";} ?>> สะดวกให้ติดต่อกลับ วันที่ <input type="text" name="datepicker" id="datepicker" value="<?php echo $datepicker; ?>" style="text-align:center" size="15" readonly> &nbsp;&nbsp; เวลา 
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
							<br>
							<input type="submit" value="บันทึก" id="validate"> &nbsp;&nbsp;
							<input type="button" value="เริ่มใหม่" onclick="window.location='frm_Index.php'"> &nbsp;&nbsp;
							<input type="button" value="ปิด" onclick="window.close();">
						</td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>HISTORY</B></legend>
			<center>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th height="30">รายการที่</th>
						<th>เรื่อง</th>
						<th>ชื่อลูกค้า</th>
						<th>เบอร์ติดต่อกลับ</th>
						<th>วันเวลาที่บันทึก</th>
						<th>บุคคลที่ต้องการจะติดต่อ</th>
						<th>วันเวลาที่สะดวกให้ติดต่อกลับ</th>
						<th>รายละเอียด</th>
						<th>แก้ไข</th>
					</tr>
					<?php 
					$query = pg_query("select * from public.\"callback\" where \"CallBackStatus\" IN('1','3') order by \"doerStamp\" ");
					$numrows = pg_num_rows($query);
					$i=1;
					while($result = pg_fetch_array($query))
					{
						$CallBackID=$result["CallBackID"]; // รหัสการติดต่อ
						$CusName=$result["CusName"]; // ชื่อลูกค้า
						$CusPhone=$result["CusPhone"]; // เบอร์ติดต่อกลับไปหาลูกค้า
						$CallTitle=$result["CallTitle"]; // ชื่อเรื่องที่จะติดต่อ
						$doerStamp=$result["doerStamp"]; // เวลาที่ลูกค้าติดต่อเข้ามา
						$Want_dep_id=$result["Want_dep_id"]; // แผนกที่ต้องการจะติดต่อ
						$Want_id_user=$result["Want_id_user"]; // พนักงานที่ต้องการจะติดต่อ
						$TimeCallBack=$result["TimeCallBack"]; // พนักงานที่ต้องการจะติดต่อ
						
						if($TimeCallBack == "") // ถ้าวันเวลาที่สะดวกให้ติดต่อกลับเป็นค่าว่าง
						{
							$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
							$chk_date = 0; // ใช้ในการตรวจสอบเงื่อนไขในการกำหนดสี
						}
						else
						{
							$chk_date = 1; // ใช้ในการตรวจสอบเงื่อนไขในการกำหนดสี
						}
						
						if($Want_dep_id != "") // ถ้ารหัสแผนกไม่ว่าง
						{
							$query_dep = pg_query("select * from public.\"department\" where \"dep_id\" = '$Want_dep_id' ");
							while($result_dep = pg_fetch_array($query_dep))
							{
								$went = "แผนก : ".$result_dep["dep_name"];
							}
						}
						elseif($Want_id_user != "") // ถ้ารหัสพนักงานไม่ว่าง
						{
							$query_dep = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$Want_id_user' ");
							while($result_dep = pg_fetch_array($query_dep))
							{
								$went = $result_dep["fullname"];
							}
						}
						
						if($chk_date == 1)
						{
							if(date($TimeCallBack) <= date('Y-m-d H:m:s'))
							{
								echo "<tr style=\"font-size:11px;\" bgcolor=\"#FFCCCC\">";
							}
							else
							{
								if($i%2==0){
									echo "<tr class=\"odd\">";
								}else{
									echo "<tr class=\"even\">";
								}
							}
						}
						else
						{
							echo "<tr style=\"font-size:11px;\" bgcolor=\"#FFCCCC\">";
						}
						echo "<td align=center height=25>$i</td>";
						echo "<td>$CallTitle</td>";
						echo "<td>$CusName</td>";
						echo "<td align=center>$CusPhone</td>";
						echo "<td align=center>$doerStamp</td>";
						echo "<td>$went</td>";
						echo "<td align=center>$TimeCallBack</td>";
						echo "<td align=center><a onclick=\"javascript:popU('detail_callback.php?CallBackID=$CallBackID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						echo "<td align=center><a onclick=\"javascript:popU('edit_CallBack.php?CallBackID=$CallBackID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=400')\"><img src=\"images/edit.png\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						echo "</tr>";	
						$i++;
					} //end while

					if($numrows==0){
						echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
					}else{
						$i=$i-1;
						echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=9><b>รายการที่ยังไม่ได้รับการติดต่อกลับทั้งหมด $i รายการ</b></td><tr>";
					}
					?>
				</table>
			</center>
			</fieldset>
		</td>
	</tr>
</table>
</form>
</center>
</body>

<script type="text/javascript">
$(document).ready(function(){
	chktype();
});
</script>

</html>