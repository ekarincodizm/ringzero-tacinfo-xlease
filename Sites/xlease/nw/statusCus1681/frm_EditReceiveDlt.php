<?php
session_start();
include("../../config/config.php");

$tacXlsRecID = $_GET['tacXlsRecID']; //เลขที่ใบเสร็จ
$car = $_GET['tacID']; //เลขที่สัญญา
$makerID = $_GET['makerID']; //ผู้ทำรายการก่อนแก้ไข

//ตรวจสอบว่าพนักงานที่ทำรายการก่อนหน้าเป็นสาขาใด
$qryoffice=pg_query("select office_id from \"fuser\" where \"id_user\"='$makerID'");
list($office_id)=pg_fetch_array($qryoffice);

//ตรวจสอบว่าใบเสร็จเป็นของสาขาใด
if(strstr($tacXlsRecID,'J')){
	$office_id=2; //หมายถึงของสาขาจรัญ
}

//ค้นหาข้อมูลมาแสดง
$qry_con=pg_query("SELECT \"tacOldRecID\", \"tacTempDate\" FROM \"tacReceiveTemp\" 
WHERE \"tacID\"='$car' and \"tacXlsRecID\"='$tacXlsRecID'
GROUP BY \"tacOldRecID\", \"tacTempDate\"");
if($rescon=pg_fetch_array($qry_con)){
	$tacOldRecID=$rescon["tacOldRecID"];
	$tacTempDate=$rescon["tacTempDate"];
}

//หาจำนวนเงินในใบเสร็จที่เลือกแก้ไข
$qryrec=pg_query("select \"O_MONEY\" from \"FOtherpay\" where (\"O_Type\"='165' OR \"O_Type\"='307') and (\"O_DATE\" > '2012-01-01') and (\"O_RECEIPT\" = '$tacXlsRecID')");
list($O_MONEY)=pg_fetch_array($qryrec);

$currentDate=date(nowDateTime());
$ycurent=substr($currentDate,0,4);

$qry_name=pg_query("select * from \"Taxiacc\" where \"CusID\"='$car'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$CusID=trim($res_name["CusID"]); 
	$statusNT=trim($res_name["statusNT"]); 
    $cutAccount=trim($res_name["cutAccount"]); $cutAccount=number_format($cutAccount,2);
	$statusLock=trim($res_name["statusLock"]); 
	$checkDate=trim($res_name["checkDate"]); 
	$radiostop=trim($res_name["radiostop"]);
	if($radiostop=="1900-01-01"){
		$radiostop="";
	}
	
	$NTDate=trim($res_name["NTDate"]); 
	if($NTDate=="1900-01-01"){
		$NTDate="";
	}

	$cutYear=trim($res_name["cutYear"]);
	if($cutYear==0){
		$cutYear="";
	}
	$ntrec=trim($res_name["ntrec"]); $ntrec=number_format($ntrec,2);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดแก้ไขข้อมูล</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){  
	$('#date2').hide();
	$("#tacTempDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#tacXlsRecID").autocomplete({
        source: "s_fotherpay_edit.php",
        minLength:1
    });
	
	$("#tacID").autocomplete({
        source: "s_cusid.php",
        minLength:1
    });
	
});
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.getElementById("payMoney1").focus();
		return false;
	}
	return true;
}
function check_year(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.getElementById("yearPay1").focus();
		return false;
	}
	return true;
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function chkreceive(){
	$.post('showhidden.php',{
            recxlease: $('#tacXlsRecID').val(),
            status: '1'
	},
	function(data){
		if(data=="0"){
			$("#showhidden").text(' (ไม่พบจำนวนเงินในใบเสร็จ)');
			$('#recmoney').val('NO');
		}else{	
			$('#recmoney').val(data);
			$("#showhidden").text(' (จำนวนเงินในใบเสร็จ : '+ addCommas(parseFloat(data).toFixed(2)) +')');
		}
	});
}
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
</script>
</head>
<body>
<?php
if($numrows ==0){
	echo "<h2>ไม่พบข้อมูลสัญญา: $car</h2>";
}else{
?>
<div align="center"><h2>แก้ไขสัญญา <?php echo $car;?> เลขที่ใบเสร็จ <?php echo $tacXlsRecID;?></h2></div>
<div style="float:left;"><input type="checkbox" name="checkdel" id="checkdel" value="0"><span style="background-color:yellow;"><b>ขอลบข้อมูลทั้งหมด</b></span></div>
<div style="float:right;"><input type="button" value="X ปิด" onclick="window.close();"></div>
<div style="clear:both;"></div>
<table width="100%" cellSpacing="1" cellPadding="1" border="0" bgcolor="#F0F0F0" align="center">
<tr>
	<td colspan="3">
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
		<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25">
			<th>สัญญาเลขที่</th>
			<th>สัญญารับโอนไป</th>
			<th>รหัสเครื่องวิทยุ</th>
			<th>รหัสวิทยุ</th>
			<th>ทะเบียนรถยนต์</th>
			<th>ชื่อ-นามสกุลลูกค้า</th>
		</tr>
		<?php
		$sql=mssql_query("select a.CusID,a.PreName,a.Name,a.SurName,b.RadioONID,b.RentPrice,b.RadioOff,convert(varchar,b.RadioOffDate,103) as RadioOffDate,b.RadioID,a.CarRegis from TacCusDtl a
		left join TacRadio b on a.CusID=b.CusID 
		where RadioONID <> '0' and a.CusID='$CusID' order by a.CusID",$conn); 
		if($res = mssql_fetch_array($sql)){
			$PreName=trim(iconv('WINDOWS-874','UTF-8',$res["PreName"]));
			$Name=trim(iconv('WINDOWS-874','UTF-8',$res["Name"]));
			$SurName=trim(iconv('WINDOWS-874','UTF-8',$res["SurName"]));
			$fullname=$PreName.$Name." ".$SurName;
			$RadioONID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioONID"]));
			$RadioOff=trim(iconv('WINDOWS-874','UTF-8',$res["RadioOff"]));
			$RadioOffDate=trim(iconv('WINDOWS-874','UTF-8',$res["RadioOffDate"]));
			$RentPrice=$res["RentPrice"];
			$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioID"]));
			$CarRegissql=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));
			
			$dd=substr($RadioOffDate,0,2);
			$mm=substr($RadioOffDate,3,2);
			$yy=substr($RadioOffDate,6,4);
			$yy=$yy+543;
			$RadioOffDate=$dd."-".$mm."-".$yy;
			
			//หาสัญญารับโอนไป อันดับแรกต้องหาก่อนว่า RadioONID นี้มีทั้งหมดกี่สัญญา แล้วดูว่าปัจจุบันอยู่ที่สัญญาเท่าไหร่ แล้วให้นำสัญญาถัดไปมาแสดง
			$query_ONID=mssql_query("select CusID,RadioOff from TacRadio where RadioONID='$RadioONID' order by EffectDate ASC");
			$num_ONID=mssql_num_rows($query_ONID);
			$x=1;
			$CusIDNew="";
			if($num_ONID != 0){
				while($res_ONID=mssql_fetch_array($query_ONID)){
					$CusID2=trim(iconv('WINDOWS-874','UTF-8',$res_ONID["CusID"]));
					$RadioOff2=trim(iconv('WINDOWS-874','UTF-8',$res_ONID["RadioOff"]));
						
					if($CusID==$CusID2){  //กรณีไม่ใช่ record สุดท้าย จะสามารถกำหนดค่า $CusIDNew = record ถัดไปได้
						$y=$x+1;
					}
					if($y==$x){ //แทนค่า $CusIDNew = record ถัดไป
						$CusIDNew=$CusID2;
					}
						
					$x++;
				}
				if($CusIDNew==""){ //กรณีมีแค่ 1 record หรือ เป็น record สุดท้าย จะพบว่ายังไม่โอนไปที่ไหนเลย
					$CusIDNew="<font color=red>ยังไม่โอน</font>";
				}
			}else{
				$CusIDNew="<font color=red>ไม่พบข้อมูล</font>";
			}
		}
		//หาทะเบียนรถยนต์จาก Taxiacc
		$qrycarregis=pg_query("select carregis from \"Taxiacc\" where \"CusID\"='$CusID'");
		list($carregis)=pg_fetch_array($qrycarregis);

		if($carregis==""){
			$CarRegis=$CarRegissql;
		}else{
			$CarRegis=$carregis;
		}
		
		//หาเลขที่สัญญาล่าสุดของทะเบียนรถคันนี้
		$CarRegiscutspace=ereg_replace('[[:space:]]+', '', trim($CarRegis)); //ตัดช่องว่างออก
		$qrycarregis=pg_query("select \"IDNO\" from \"Fp\" a
		left join \"Fc\" b on a.\"asset_id\"=b.\"CarID\"
		left join \"FGas\" c on a.\"asset_id\"=c.\"GasID\"
		where replace(replace(\"C_REGIS\",' ',''),'-','')='$CarRegiscutspace'
		or replace(replace(\"car_regis\",' ',''),'-','')='$CarRegiscutspace' 
		order by \"P_STDATE\" DESC limit 1");
		$numcar=pg_num_rows($qrycarregis);
		list($IDNO)=pg_fetch_array($qrycarregis);
		
		echo "<tr bgcolor=#DBF2FD>";
		echo "<td align=center><span onclick=\"javascript:popU('frm_PaymentChk.php?car=$CusID','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" title=\"รายละเอียดรับชำระแทน 1681\" style=\"cursor:pointer\"><u>$CusID</u></span></td>";
		echo "<td align=center>$CusIDNew</td>";
		echo "<td align=center>$RadioONID</td>";
		echo "<td align=center>$RadioID</td>";
		if($numcar>0){
			echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><u>$CarRegis</u></a></td>";
		}else{
			echo "<td align=center>$CarRegis</td>";
		}
		echo "<td>$fullname</td>";
		echo "</tr>";
		
		if($payDate==""){
			$payDate=$currentDate;
		}
		if($xlsjrDate==""){
			$xlsjrDate=$currentDate;
		}
		if($tacTempDate==""){
			$tacTempDate=$currentDate;
		}
		
		?>
		<tr><td colspan="6" bgcolor="#FFCCCC" height="25"><b>แก้ไขข้อมูลการชำระ</b></td></tr>
		<tr bgcolor="#FFECEC"><td colspan="6">
			<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
				<tr>
					<td>
						<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="50"></td>
							<td width="120" align="right"><b>เลขที่สัญญา</b></td><td width="10">:</td><td><input type="text" name="tacID" id="tacID" value="<?php echo $CusID;?>"></td>
						</tr>
						<tr>
							<td width="50"></td>
							<td width="120" align="right"><b>เลขที่ใบเสร็จ</b></td><td width="10">:</td>
							<td>
							<?php
							if($office_id=="1"){
								echo "<input type=\"text\" name=\"tacXlsRecID\" id=\"tacXlsRecID\" value=\"$tacXlsRecID\" onkeyup=\"javascript : chkreceive()\" onfocus=\"javascript : chkreceive()\"><span id=\"showhidden\"> (จำนวนเงินในใบเสร็จ : ".number_format($O_MONEY,2).")</span>";
							}else{
								echo "<input type=\"text\" name=\"tacXlsRecID\" id=\"tacXlsRecID_J\" value=\"$tacXlsRecID\">";
							}
							?>
							<input type="hidden" name="recmoney" id="recmoney" value="<?php echo $O_MONEY; ?>">
							</td>
						</tr>
						<tr id="date1">
							<td></td>
							<td align="right"><b>วันที่ชำระ</b></td><td width="10">:</td><td>
							<input type="text" id="tacTempDate" name="tacTempDate" value="<?php echo $tacTempDate;?>" size="15" style="text-align: center;" readonly>
							</td>
						</tr>
						<tr id="date2">
							<td></td>
							<td align="right"><b>วันที่ชำระ</b></td><td width="10">:</td><td>
							<input type="text" id="tacTempDate2" name="tacTempDate2" value="<?php echo $tacTempDate;?>" size="15" style="text-align: center;" readonly>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</tr>
		<tr bgcolor="#FFCCCC">
			<td colspan="6">
			<b>เลขที่ใบเสร็จ TAC :</b> <input type="text" name="tacOldRecID" id="tacOldRecID" value="<?php echo $tacOldRecID;?>">
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<table width="100%" style="background-color:#FFECEC; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
				<tr>
					<td>
						<div id='TextBoxesGroup'></div>
					</td>
				</tr>
				<input type="hidden" name="numall" id="counter">
				<input type="hidden" name="office_id" id="office_id" value="<?php echo $office_id;?>">
				</table>
			</div>
			</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<table width="100%" align="center" border="0">
<tr height="50">
	<td width="">
	<div style="float:left"><input type="submit" value="บันทึกแก้ไขรายการ" id="submitButton"> ยอดรับชำระใบนี้รวม : <span id="divsummery" style="font-weight:bold;"><?php echo number_format($sumtacMoney,2);?></span> บาท<input type="hidden" name="divsummery2" id="divsummery2" value="<?php echo $sumtacMoney;?>"></div>
	<div style="float:right"><input type="button" value="+ เพิ่มรายการ" id="addButton"></div>
	<div style="clear:both;"></div>
	</td>
</tr>
</table>

<script type="text/javascript">

var counter=0;
$(document).ready(function(){
	<?php 	
	$qry_all=pg_query("SELECT * FROM \"tacReceiveTemp\" 
	WHERE \"tacID\"='$car' and \"tacXlsRecID\"='$tacXlsRecID'");
	$numall=pg_num_rows($qry_all);
	
	$sumtacMoney=0;
	while($resall=pg_fetch_array($qry_all)){			
		$tacOldRecID=$resall["tacOldRecID"];
		$tacTempDate=$resall["tacTempDate"];
		$tacMoney=$resall["tacMoney"];					
		$tacMonth=$resall["tacMonth"];

		list($y,$m,$d)=explode("-",$tacMonth);
					
		$sumtacMoney=$sumtacMoney+$tacMoney;
	?>
		counter++;	
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
		
		table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FFECEC;border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
		+ ' <tr>'
		+ ' <td><b>เงินที่จ่าย (บาท)</b> <input type="text" name="payMoney[]" id="payMoney'+ counter +'" value="<?php echo $tacMoney;?>" style="text-align:right;" onkeyup="javascript:updateSummary()">'
		+ '	<b>เดือนที่จ่าย </b>'
		+ '	<select name="month[]" id="month'+ counter +'">'
		+ '		<option value="01">มกราคม</option>'
		+ '		<option value="02">กุมภาพันธ์</option>'
		+ '		<option value="03">มีนาคม</option>'
		+ '		<option value="04">เมษายน</option>'
		+ '		<option value="05">พฤษภาคม</option>'
		+ '		<option value="06">มิถุนายน</option>'
		+ '		<option value="07">กรกฏาคม</option>'
		+ '		<option value="08">สิงหาคม</option>'
		+ '		<option value="09">กันยายน</option>'
		+ '		<option value="10">ตุลาคม</option>'
		+ '		<option value="11">พฤศจิกายน</option>'
		+ '		<option value="12">ธันวาคม</option>'
		+ '	</select>'
		+ '	<b>ปีที่จ่าย (ค.ศ.)<b><input type="text" name="yearPay[]" id="yearPay'+ counter +'" maxlength="4" size="10" value="<?php echo $y;?>"></td>'
		+ ' <td width="10%"><input type="button" value="ลบรายการนี้" onclick="removerow('+counter+')" id="del'+ counter +'"></td>'
		+ ' </tr>'
		+ ' </table>';

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
		$('#month'+counter+' option[value="<?php echo $m;?>"]').attr('selected','selected');
        updateSummary();
        			
<?php } ?>	
	$('#counter').val(counter);
	
	$('#addButton').click(function(){
		//จำนวนเงินล่าสุดที่กรอก
		var nowmoney=$('input[name="payMoney[]"]:last').val();

		//เดือนที่เลือกล่าสุด
		var selectmonth=$('select[name="month[]"]:last').val(); 
		
		var selectyear=$('input[name="yearPay[]"]:last').val();
		var dateStr=selectmonth+"/01/"+selectyear;
		
		//หาเดือนถัดไปต่อจากเดือนที่เลือก
		var datenext=calendarAddMonth(dateStr, 1);
		
		var t = datenext.split("-");
		var nextyear=t[0];
		var nextmonth=t[1];
		
		//กรณีที่มีการลบข้อมูลจนหมด ให้ค่าที่เพิ่มเป็นค่าว่าง
		if($('input[name="payMoney[]"]').length==0){
			nowmoney='';
			nextyear="";
		}
		if(isNaN(nextyear)){
			nextyear="";
		}
		
		counter=$('#cou+nter').val();
		counter++;
		
		console.log(counter);
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);

		table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FAFAD2;border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
		+ ' <tr>'
		+ ' <td><b>เงินที่จ่าย (บาท)</b> <input type="text" name="payMoney[]" id="payMoney'+ counter +'" value="'+nowmoney+'" style="text-align:right;" onkeyup="javascript:updateSummary()">'
		+ '	<b>เดือนที่จ่าย </b>'
		+ '	<select name="month[]" id="month'+ counter +'">'
		+ '		<option value="01">มกราคม</option>'
		+ '		<option value="02">กุมภาพันธ์</option>'
		+ '		<option value="03">มีนาคม</option>'
		+ '		<option value="04">เมษายน</option>'
		+ '		<option value="05">พฤษภาคม</option>'
		+ '		<option value="06">มิถุนายน</option>'
		+ '		<option value="07">กรกฏาคม</option>'
		+ '		<option value="08">สิงหาคม</option>'
		+ '		<option value="09">กันยายน</option>'
		+ '		<option value="10">ตุลาคม</option>'
		+ '		<option value="11">พฤศจิกายน</option>'
		+ '		<option value="12">ธันวาคม</option>'
		+ '	</select>'
		+ '	<b>ปีที่จ่าย (ค.ศ.)<b><input type="text" name="yearPay[]" id="yearPay'+ counter +'" maxlength="4" size="10" value='+nextyear+'></td>'
		+ ' <td width="10%"><input type="button" value="ลบรายการนี้" onclick="removerow('+counter+')" id="del'+ counter +'"></td>'
		+ ' </tr>'
		+ ' </table>';

		newTextBoxDiv.html(table);

		newTextBoxDiv.appendTo("#TextBoxesGroup");
		$('#month'+counter+' option[value='+nextmonth+']').attr('selected','selected');
		updateSummary();
		
		$('#counter').val(counter);
    });
	
	$('#checkdel').click(function(){
		var elepay=$('input[name="payMoney[]"]');
		var eleyear=$('input[name="yearPay[]"]');
		var elemonth=$('select[name="month[]"]');
		
		if($("#checkdel").is(':checked')){
			$("#checkdel").val('1');  // checked
			
			$('#tacID').attr('readonly', true); //ให้เลขที่สัญญาไม่สามารถกรอกได้
			if($('#office_id').val()=="1"){
				$('#tacXlsRecID').attr('readonly', true); 
			}else{
				$('#tacXlsRecID_J').attr('readonly', true); 
			}
			
			$('#date1').hide();
			$('#date2').show();
			
			$('#tacOldRecID').attr('readonly', true); 
			
			var j=0;
			for(i=0; i<elepay.length; i++){
				j++;
				$(elepay[i]).attr('readonly', true); 
				$(elemonth[i]).attr('disabled','disabled');
				$(eleyear[i]).attr('readonly', true); 
				$('#del'+j).attr('disabled','disabled');
			}
			$('#addButton').attr('disabled','disabled');
		}else{
			$("#checkdel").val('0');  // unchecked
			
			$('#tacID').attr('readonly', false); //ให้เลขที่สัญญาสามารถกรอกได้
			if($('#office_id').val()=="1"){
				$('#tacXlsRecID').attr('readonly', false); 
			}else{
				$('#tacXlsRecID_J').attr('readonly', false); 
			}
			
			$('#date1').show();
			$('#date2').hide();
			
			$('#tacOldRecID').attr('readonly', false);
			
			var j=0;
			for(i=0; i<elepay.length; i++){
				j++;
				$(elepay[i]).attr('readonly', false); 
				$(elemonth[i]).removeAttr('disabled'); 
				$(eleyear[i]).attr('readonly', false); 
				$('#del'+j).removeAttr('disabled');
			}
			
			$('#addButton').removeAttr('disabled');
		}
	});
	
    $("#submitButton").click(function(){
		var elepay=$('input[name="payMoney[]"]');
		var eleyear=$('input[name="yearPay[]"]');
		var elemonth=$('select[name="month[]"]');
		
		if($('#checkdel').val()==0){ //กรณีไม่ได้ยกเลิกรายการ
			if( $('#tacID').val() == "" ){
				alert('กรุณากรอกเลขที่สัญญา !');
				$('#tacID').focus();
				return false;
			}
		
			if($('#office_id').val()=="1"){
				if($('#tacXlsRecID').val() == ""){
					alert('กรุณากรอกเลขที่ใบเสร็จ !');
					$('#tacXlsRecID').focus();
					return false;
				}else{	
					if($("#recmoney").val()=="NO"){
						alert("เลขที่ใบเสร็จไม่ถูกต้อง หรือถูกใช้ไปแล้ว กรุณาตรวจสอบ!!");
						return false;
					}else{
						if(elepay.length>0){
							if(parseFloat($("#divsummery2").val())!=parseFloat($("#recmoney").val())){
								alert("จำนวนเงินรวมไม่ตรงกับใบเสร็จ กรุณาตรวจสอบ!!");
								return false;
							}
						}
					}	
				}
			}else{
				if($('#tacXlsRecID_J').val() == ""){
					alert('กรุณากรอกเลขที่ใบเสร็จ !');
					$('#tacXlsRecID').focus();
					return false;
				}
			}
		
			for(i=0; i<elepay.length; i++){
				if( $(elepay[i]).val() == "" ){
					alert('กรุณากรอกเงินที่จ่าย !');
					$(elepay[i]).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
			}
		
			var payment = [];
			for( i=0; i<elepay.length; i++ ){
				var c1 = $(eleyear[i]).val();
				if ( isNaN(c1) || c1 == "" || c1 == 0){
					alert('กรุณากรอกปีที่จ่าย !');
					$(eleyear[i]).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}

				payment[i] = {paymoney : $(elepay[i]).val() , month: $(elemonth[i]).val() , yearpay : $(eleyear[i]).val()};
		   }
		}
	   
        $.post("api.php",{
			method : 'edit',
            cusid : $("#tacID").val(),
			tacXlsRecID : $("#tacXlsRecID").val(),
			tacXlsRecID_J : $("#tacXlsRecID_J").val(),
			tacTempDate : $("#tacTempDate").val(),
			tacOldRecID : $("#tacOldRecID").val(),
			tacID_Old : '<?php echo $car; ?>',
			tacXlsRecID_Old : '<?php echo $tacXlsRecID; ?>',
			checkdel : $("#checkdel").val(),
            payment : JSON.stringify(payment) 
        },
        function(data){
            if(data == "1"){
                alert("บันทึกรายการเรียบร้อย");
                opener.location.reload(true);
				self.close();
            }else{
                alert(data);
            }
        });
		

    });  
});

//สำหรับคำนวณยอดรวม
function updateSummary(){
	var elem=$('input[name="payMoney[]"]');
    var sss = 0;

    for( i=0; i<elem.length; i++ ){
        var c1 = $(elem[i]).val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        sss += parseFloat(c1);
		
    }
    $("#divsummery").text(addCommas(sss.toFixed(2)));
	$("#divsummery2").val(sss.toFixed(2));
}

function removerow(count){
	var elem=$('input[name="payMoney[]"]');
	if(elem.length==1){
		alert("ห้ามลบ");
	}else{
		$("#TextBoxDiv" + count).remove();
		updateSummary();	
	}
}
//สำหรับบวกเดือน
function calendarAddMonth(dateStr, month){ 
	   //Create date object from input date
	    var date = new Date(dateStr);     
	   //Add month
	    date.setMonth(date.getMonth()+month); 
		var remonth=date.getMonth()+1;
		var redate=date.getDate();
		if(remonth<10){
			remonth="0"+remonth;
		}
		
		if(redate<10){
			redate="0"+redate;
		}
		
	    
	    return date.getFullYear()+"-"+(remonth)+"-"+redate;
}  
</script>
<?php }?>
</body>
</html>
