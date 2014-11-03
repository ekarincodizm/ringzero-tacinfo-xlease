<?php
include("../../config/config.php");
$month = Date('m');
$nowYear = date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานส่วนลด</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
$(document).ready(function(){	
	$("#d1").show();
	$("#d2").hide();
	
	$("#datecon").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	$('#btnserach').click(function(){
		$("#showarea").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังดำเนินการ โปรดรอสักครู่...">');
		var contype = "";	
		var j;
		j=0;
		for(i=1;i<=4;i++){	
			if($("#con"+i).attr("checked") == true){
				contype = contype+"@"+$("#con"+i).val();
			}else{
				j++; 
			}	
		}

		if(j==4){ //กรณีที่ไม่มีการ check ทั้ง 4 รายการ
			alert("กรุณาเลือกเงื่อนไขในการแสดงรายการ");
			$("#showarea").hide();
			return false;
		}else{
			$("#showarea").show();
			if(document.getElementById("op1").checked == true){	//กรณีเลือกแบบแสดงวันที่
				var option = $("#op1").val();
				var datecon = $("#datecon").val();
				$("#showarea").load("frm_reportDetail.php?datecon="+datecon+"&option="+option+"&contype="+contype);
			}else if(document.getElementById("op2").checked == true){	//กรณีเลือกแบบแสดงเดือนปี
				var option = $("#op2").val();
				var mm = $("#month1").val();	
				var yy = $("#year1").val();
				$("#showarea").load("frm_reportDetail.php?month="+mm+"&year="+yy+"&option="+option+"&contype="+contype);
			}else if(document.getElementById("op3").checked == true){	//กรณีเลือกแบบแสดงปี
				var option = $("#op3").val();	
				var yy = $("#year1").val();
				$("#showarea").load("frm_reportDetail.php?year="+yy+"&option="+option+"&contype="+contype);
			}
		}
	 });
});

function option(){
	if(document.getElementById("op1").checked == true){	
		$("#d1").show();
		$("#d2").hide();		
	}else if(document.getElementById("op2").checked == true){	
		$("#d1").hide();
		$("#d2").show();
			$("#month1_text").show();
			$("#month1").show();
			$("#year1").show();
	}else if(document.getElementById("op3").checked == true){	
		$("#d1").hide();
		$("#d2").show();
			$("#month1_text").hide();		
			$("#month1").hide();
			$("#year1").show();
	}

}

</script>
</head>
<body bgcolor="">
<form name="frm" method="post">
<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
        <td align="center"><H1>(THCAP) รายงานส่วนลด</H1></td>
	</tr>		
	<tr>
		<td align="center">
			<fieldset  style="width:60%;" >
				<table align="center" width="100%" >
					<tr>
						<td align="center">
							<fieldset  style="width:80%;background-color:#FFE1EE" ><legend><span style="background-color:#FFDAB9;font-weight:bold;">เลือกช่วงเวลา</span></legend>
							<table align="center" width="100%" border="0">
							<tr>
								<td align="center" height="50">
									<input type="radio" id="op1" name="op1" value="day"  onchange="option();" checked>แสดงแบบรายวัน 
									<input type="radio" id="op2" name="op1" value="my"  onchange="option();">แสดงแบบรายเดือน 	
									<input type="radio" id="op3" name="op1" value="year"  onchange="option();">แสดงแบบรายปี 				
								</td>
							</tr>			
							<tr id="d1" height="30">
								<td align="center">								
									<font id="txtEdate">วันที่ :</font><input type="text" name="datecon" id="datecon" value="<?php echo date("Y-m-d");?>" size="10">						
								</td>
							</tr>				
							<tr id="d2" height="30">
								<td align="center">
										<font id="month1_text">เดือน :</font>
											<select name="month1" id="month1">
													<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
													<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
													<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
													<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
													<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
													<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
													<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
													<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
													<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
													<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
													<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
													<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
												</select>	
										<font>ปี :</font>
											<select name="year1" id="year1">
												<?php
													for($y=1900; $y<=($nowYear+10); $y++)
													{
														if($y == $nowYear)
														{
															echo "<option value=\"$y\" selected>$y</option>";
														}
														else
														{
															echo "<option value=\"$y\">$y</option>";
														}
													}
												?>
											</select>

								</td>					
							</tr>
							</table>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td align="center">
							<table align="center" width="100%" >
							<tr><td align="right"><b>เงื่อนไขในการแสดง :</b></td>
							<td><input type="checkbox" name="contype[]" id="con1" value="8" checked>แสดงรายการระหว่างรออนุมัติ</td>
							<td><input type="checkbox" name="contype[]" id="con2" value="0" checked>แสดงรายการที่ไม่อนุมัติ</td>
							</tr>
							<tr><td></td><td><input type="checkbox" name="contype[]" id="con3" value="1" checked>แสดงรายการที่อนุมัติและลูกค้ามีการจ่ายแล้ว</td><td><input type="checkbox" name="contype[]" id="con4" value="2" checked>แสดงรายการที่อนุมัติและลูกค้ายังไม่ได้จ่าย</td></tr>
							</table><hr>			
						</td>						
					</tr>
					<tr>
						<td align="center">
							<input type="button" value="ค้นหา" id="btnserach" >
						</td>					
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<div id="showarea" style="padding-top:20px;"></div>
		</td>
	</tr>
</table>		
</form>
</body>