<?php
include("../../config/config.php");

$currentdate=nowDate();

if($datepicker==""){
	$datepicker=$currentdate;
}
if($year==""){
	$year=substr($currentdate,0,4);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายงานเช็คจ่าย</title>
<script type="text/javascript">
$(document).ready(function(){
	
	$("#conday").show();
	$("#conmonth").hide();
		
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#condate").change(function(){
		var src = $('#condate option:selected').attr('value');
        if ( src == "1" ){
			$("#conday").show();
			$("#conmonth").hide();
			$("#month").val('');
			$("#year").val('');
			$("#con").val('1');
		}else if(src == "2"){
			$("#conday").hide();
			$("#conmonth").show();
			$("#con").val('2');
		}
	});
	
	$("#btn1").click(function(){
		var condate1 = $('#condate option:selected').attr('value');
		var typePay1 = $('#typePay option:selected').attr('value');
		var company1 = $('#company option:selected').attr('value');
		var cheque1 = $('#cheque option:selected').attr('value');
		var month1 = $('#month option:selected').attr('value');
		
		if(condate1==2){
			if($("#year").val()==""){
				alert('กรุณาระบุปี ค.ศ.');
				$('#year').focus();
				return false;
			}
		}
		$("#type_detail").load("process_cheque.php?method=sentreport&datepicker="+$("#datepicker").val()+"&condate="+condate1+"&typePay="+typePay1+"&company="+company1+"&cheque="+cheque1+"&month="+month1+"&year="+$("#year").val());
	});
});

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function acceptOnlyDigit(event,el){
   var e=window.event?window.event:event;
   var keyCode=e.keyCode?e.keyCode:e.which?e.which:e.charCode;  
    //0-9 (numpad,keyboard)
   if ((keyCode>=96 && keyCode<=105)||(keyCode>=48 && keyCode<=57)){
    return true;
   }
   //backspace,delete,left,right,home,end
   if (',8,46,37,39,36,35,'.indexOf(','+keyCode+',')!=-1){
    return true;
   }  
   return false;
 }
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<div style="text-align:center"><h2>รายงานเช็คจ่าย</h2></div>       
		<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
		<div style="clear:both;"></div>
		<fieldset><legend><B>รายงานเช็คจ่าย</B></legend>
			<table align="center">
			<tr height="30">
				<td width="45%" align="right">
					<b>รายงาน</b>
					<select name="condate" id="condate">
						<option value="1">ประจำวัน</option>
						<option value="2">ประจำเดือน</option>
					</select>
				</td>
				<td id="conday">&nbsp;
					<label><b>วันที่</b></label>
					<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
				</td>
				<td id="conmonth">&nbsp;
					<label id="txtmonth"><b>เดือน</b></label>
					<select name="month" id="month">
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
					<label id="txtyear"><b>ปี ค.ศ.</b></label>
					<input type="text" id="year" name="year" value="<?php echo $year; ?>" size="10" style="text-align:center" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)">
				</td>
			</tr>
			<tr height="30">
				<td colspan="2" align="center">
					<b>ประเภทการสั่งจ่าย</b>
					<select name="typePay" id="typePay">
						<option value="">--ทั้งหมด--</option>
						<?php
						//ดึงข้อมูลจากตาราง cheque_typepay
						$qrytype=pg_query("SELECT \"typePay\", \"typeName\" FROM cheque_typepay order by \"typePay\"");
						while($restype=pg_fetch_array($qrytype)){
							list($typePay2,$typeName2)=$restype;
							?>
							<option value="<?php echo $typePay2;?>"><?php echo $typeName2;?></option>
						<?php
						}
						?>
					</select>
					
					<b>ชื่อบริษัท</b>
					<select name="company" id="company">
						<option value="">--ทั้งหมด--</option>
						<?php
						$qrycompany=pg_query("SELECT \"BCompany\" FROM \"BankInt\" where \"isChq\"='1'  group by \"BCompany\"  order by \"BCompany\"");
						while($rescompany=pg_fetch_array($qrycompany)){
							list($BCompany)=$rescompany;
							//ทำให้ไม่มีช่องว่าง เนื่องจากถ้ามีช่องว่างจะไม่สามารถส่งค่าไป qry ได้
							$BCompany2=ereg_replace('[[:space:]]+', '', trim($BCompany)); //ตัดช่องว่างออก
							?>
							<option value="<?php echo $BCompany2;?>"><?php echo $BCompany;?></option>
						<?php
						}
						?>
					</select>
					
					<b>เช็คธนาคาร</b>
					<select name="cheque" id="cheque">
						<option value="">--ทั้งหมด--</option>
						<?php
						$qrychq=pg_query("SELECT \"BName\",\"BBranch\" FROM \"BankInt\" where \"isChq\"='1' group by \"BName\",\"BBranch\" order by \"BName\"");
						while($reschq=pg_fetch_array($qrychq)){
							list($BName,$BBranch)=$reschq;
							//ทำให้ไม่มีช่องว่าง เนื่องจากถ้ามีช่องว่างจะไม่สามารถส่งค่าไป qry ได้
							$BName2=ereg_replace('[[:space:]]+', '', trim($BName))."/".ereg_replace('[[:space:]]+', '', trim($BBranch)); //ตัดช่องว่างออก
							?>
							<option value="<?php echo $BName2;?>"><?php echo $BName." สาขา".$BBranch;?></option>
						<?php
						}
						?>
					</select>
				</td>
			</tr>		
			<tr height="30">
				<td colspan="2" align="center">
					<input type="hidden" name="con" id="con"/>
					<input type="button" id="btn1" value="เริ่มค้น"/>
				</td>
			</tr>		
			</table>
			<span id="type_detail"></span>	
		</fieldset>
	</td>
</tr>
</table>
</body>
</html>