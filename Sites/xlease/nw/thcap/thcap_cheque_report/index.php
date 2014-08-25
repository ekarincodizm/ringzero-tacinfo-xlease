<?php
include("../../../config/config.php");
$month = Date('m');
$nowYear = date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานเช็ค</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
$(document).ready(function(){	

	$("#datecon").datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	 $('#btnserach').click(function(){
			
			$("#showarea").load("กำลังดำเนินการ โปรดรอซักครู่...");
			
			if($("#contype1").attr("checked") == true){
				var condition = $("#contype1").val();
			}else{
				var condition = $("#contype2").val();
			}
	
			if(document.getElementById("op1").checked == true){	
				var option = $("#op1").val();
				var datecon = $("#datecon").val();
				$("#showarea").load("table_report.php?condition="+condition+"&option="+option+"&datecon="+datecon+"&opstatus="+$("#opstatus").val());
			}else if(document.getElementById("op2").checked == true){	
				var option = $("#op2").val();
				var mm = $("#month1").val();	
				var yy = $("#year1").val();
				$("#showarea").load("table_report.php?condition="+condition+"&option="+option+"&yy="+yy+"&mm="+mm+"&opstatus="+$("#opstatus").val());
			}else if(document.getElementById("op3").checked == true){	
				var option = $("#op3").val();	
				var yy = $("#year1").val();
				$("#showarea").load("table_report.php?condition="+condition+"&option="+option+"&yy="+yy+"&opstatus="+$("#opstatus").val());
			}
			
			
			
			
	 });
	 
	 
	 	$("#d1").hide();
		$("#d2").show();
	 
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
        <td align="center"><H1>(THCAP) รายงานเช็ค </H1></td>
	</tr>		
	<tr>
		<td align="center">
			<fieldset  style="width:50%;" >
				<table align="center" width="100%" >
					<tr>
						<td align="center">
							<div style="padding:10px;"><b>แสดงตาม:</b>
							<input type="radio" id="contype1" name="radio1" value="bankChqDate" checked>วันที่บนเช็ค&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="contype2" name="radio1" value="giveTakerDate">วันที่นำเช็คเข้าธนาคาร
							</div>
						</td>
					</tr>
					<tr>
						<td align="center">
						<fieldset  style="width:80%;background-color:#FAEBD7" ><legend><span style="background-color:#FFDAB9;font-weight:bold;">เลือกช่วงเวลา</span></legend>
						<table align="center" width="100%" border="0">
						<tr>
							<td align="center" height="50">
							<input type="radio" id="op3" name="op1" value="year"  onchange="option();">แสดงเฉพาะปี
							<input type="radio" id="op2" name="op1" value="my" checked onchange="option();">แสดงเฉพาะเดือน-ปี						
							<input type="radio" id="op1" name="op1" value="day"  onchange="option();">แสดงเฉพาะวัน
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
						<tr>
							<td align="center">
								<font>แสดงเฉพาะสถานะ :</font>
								<select id="opstatus" name="opstatus">
									<?php 
										$qry_sel_status = pg_query("SELECT \"namestatus\" FROM \"finance\".\"V_thcap_receive_cheque_chqManage\" group by \"namestatus\"");
											echo "<option value=\"\">ทั้งหมด</option>";
										while($result_sel_status = pg_fetch_array($qry_sel_status)){
											echo "<option value=\"".$result_sel_status["namestatus"]."\">".$result_sel_status["namestatus"]."</option>";
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
							<input type="button" value="ค้นหา" id="btnserach" >
						</td>					
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<div id="showarea"></div>
		</td>
	</tr>
</table>		
</form>
</body>