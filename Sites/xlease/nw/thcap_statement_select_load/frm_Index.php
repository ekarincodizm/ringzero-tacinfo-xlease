<?php
session_start();
include("../../config/config.php");
$sbj_serial=$_GET["sbj_serial"];
$currentdate=nowDate();

if($datepicker==""){
	$datepicker=$currentdate;
	$datefrom=$currentdate;
	$dateto=$currentdate;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) STATEMENT BANK</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/number.js"></script>
<script type="text/JavaScript">
$(document).ready(function(){
	$("#datepicker").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#datefrom").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#dateto").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});

</script>
<script type="text/JavaScript">
function check(){
	var i=0;
	var checkerror=true;
	var theMessage = "กรุณาเลือกเงื่อนไข ที่จะค้นหาให้ครบ: \n-----------------------------------\n";
	var selectdate=false;
	var r=document.getElementsByName("date1");
	while(i<r.length){
		if(r[i].checked==true){
			selectdate=true;
			if(r[i].value=='3'){
			  if(($("#datefrom").val())>($("#dateto").val())){
					checkerror=false;
					theMessage = theMessage + "\n --> วันที่เริ่มค้นหาต้องน้อยกว่าหรือเท่ากับวันที่สิ้นสุด";
				}
			}
			else if(r[i].value=='2'){
				if($("#month").val()==''){
					checkerror=false;
					theMessage = theMessage + "\n --> กรุณาเลือกเดือนที่จะค้นหา";				
				}
			}
			break;}
		else{i++;}
	}
	if(($("#bankint").val()!='')&&(selectdate==true)&&(checkerror==true) ){
		$("#detail").load("frm_stament_select.php?date1="+r[i].value+"&bankint="+$("#bankint").val()+"&datefrom="+$("#datefrom").val()+"&dateto="+$("#dateto").val()+"&datepicker="+$("#datepicker").val()+"&year="+$("#year").val()+"&month="+$("#month").val())  ;
		}
	else{
		if($("#bankint").val()==''){
			theMessage = theMessage + "\n --> กรุณาเลือกช่องทาง";	
		}
		
	alert(theMessage);
	}
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

</script>
</head>
<body>
<div  align="center">
			<h2>(THCAP) STATEMENT BANK</h2>
</div>
<div style="text-align:center;"><h3>แสดงรายการที่ Load เข้าระบบ</h3></div>
<div style="text-align:right;"><input type="button" value=" Close " onclick="window.close();"></div>
<form name="frm1" action="frm_stament_select.php" method="post"> 
<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
<table align="center" >
	<tr>
		<td><b>ช่องทาง :</b></td>
		<td><select name="bankint" id="bankint">
				<?php 	
						$sql_bank = pg_query("select \"BBranch\", \"BID\", \"BName\", \"BAccount\" from \"BankInt\" where \"isLoadStatementAble\" = '1'");
							echo "<option value=\"\">- เลือกช่องทาง-</option>";
						while($re_bank = pg_fetch_array($sql_bank)){
							if($re_bank["BBranch"]!=""){
								$branch=", $re_bank[BBranch]";
							}
							echo "<option value=\"".$re_bank["BID"]."\">".$re_bank["BName"].",".$re_bank["BAccount"]."$branch";
							if($bankint==$re_bank["BID"]){ echo "selected"; }
							echo "</option>";
							
						} 
				?></select>
		</td>		
		<td><b>ค้นหาจาก:</b></td>
		<td><input type="radio" id="date1" name="date1"  value="1" <?php if($date1=="" || $date1=="1"){ echo "checked"; }?>/></td>
		<td>ตามวันที่ :</td>
		<td>
			<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
	
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td><input type="radio" id="date2" name="date1"  value="2"<?php if($date1=="2"){ echo "checked"; }?> /></td>
		<td>ตามเดือน:</td>
		<td><select name="month" id="month"> 
				<option value="">--เลือกเดือน--</option>
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
		<td>ปี  </td>
		<td><select name="year" id="year"> 	

				<?php $datenow1 = nowDate();
				list($year,$month,$day)=explode("-",$datenow1);
				$yearback = $year -10;
				for($t=$yearback;$t<=$year;$t++){
				if($t == $year){ ?> 
				<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
				<?php		}else{ ?>
				<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
				<?php  
								}
				} 
				?>	
			</select></td>
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td><input type="radio" id="date3" name="date1"  value="3" <?php if($date1=="3"){ echo "checked"; }?>/></td>
		<td>ตามช่วง: จาก </td>
		<td>
			<input type="text" id="datefrom" name="datefrom" value="<?php echo $datefrom; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
		</td>
		<td>ถึง</td>
		<td>
			<input type="text" id="dateto" name="dateto" value="<?php echo $dateto; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
		
		</td>
	</tr>
	<tr><td colspan="8" align="center">
	<input type="hidden" name="val" value="1"/>
	<input type="button" id="Search"  value="ค้นหา" onclick="check();"/></td></tr>

</table>
</fieldset><br>
</form>
<div name="detail" id="detail">
</div>
</body>
</html>