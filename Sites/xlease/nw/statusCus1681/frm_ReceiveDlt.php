<?php
session_start();
include("../../config/config.php");
$userid = $_SESSION["av_iduser"];

//ตรวจสอบว่าพนักงานเป็นสาขาใด
$qryoffice=pg_query("select office_id from \"fuser\" where \"id_user\"='$userid'");
list($office_id)=pg_fetch_array($qryoffice);

$car = $_GET['car'];

if(empty($car)){
   $car = $_POST['car'];
}

$currentDate=nowDate();
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
<script type="text/javascript">
$(document).ready(function(){
    if(<?php echo $office_id;?>=="1"){
		$('#recxlease').focus();
		$("#showdivxlease").show();
		$("#showdivxleasejr").hide();
	}else{
		$('#recxleasejr').focus();
		$("#showdivxlease").hide();
		$("#showdivxleasejr").show();
	}
	
    $("#showdivshottime").hide();
    
    $(".static_class1").click(function(){
        if($(this).val()=="2"){
            $("#showdivshottime").show();
			document.getElementById("recxlease").value="";
			document.getElementById("recxleasejr").value="";
            $("#showdivxlease").hide();
			$("#showdivxleasejr").hide();
			$('#f_radio').focus();
        }else if($(this).val()=="1"){
			document.getElementById("f_radio").value="";
			document.getElementById("recxleasejr").value="";
            $("#showdivshottime").hide();
            $("#showdivxlease").show();
			$("#showdivxleasejr").hide();
			$('#recxlease').focus();
        }else{
			document.getElementById("f_radio").value="";
			document.getElementById("recxlease").value="";
            $("#showdivshottime").hide();
            $("#showdivxlease").hide();
			$("#showdivxleasejr").show();
			$('#recxleasejr').focus();
		}
    });

    $("#payDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#xlsDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#xlsjrDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	 $("#recxlease").autocomplete({
        source: "s_fotherpay.php",
        minLength:1
    });
	
});
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่านั้น!!");
		document.getElementById("payMoney1").focus();
		return false;
	}
	return true;
}
function check_year(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่านั้น!!");
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
            recxlease: $('#recxlease').val(),
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
</script>
<?php
if($numrows ==0){
	echo "<h2>ไม่พบข้อมูลสัญญา: $car</h2>";
}else{
?>
<hr width="80%" color="#CCCCCC"><br>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center">
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
		echo "<td align=center>$CusID</td>";
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
		if($xlsDate==""){
			$xlsDate=$currentDate;
		}
		
		?>
		<tr><td colspan="6" bgcolor="#FFCCCC" height="25"><b>เพิ่มข้อมูลการชำระ</b></td></tr>
		<tr bgcolor="#FFECEC"><td colspan="6">
			<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
				<tr>
					<td>
						<input type="radio" class="static_class1" name="receive" id="receive1" value="1" <?php if($office_id=="1"){ echo "checked"; }?>> ใบเสร็จใน xlease
						<div id="showdivxlease">
							<table cellpadding="3" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="50"></td>
								<td width="120" align="right"><b>เลขที่ใบเสร็จ</b></td><td width="10">:</td><td><input type="text" name="recxlease" id="recxlease" onfocus="javascript : chkreceive()" onkeyup="javascript:chkreceive()"><span id="showhidden"></span><input type="hidden" name="recmoney" id="recmoney" ></td>
							</tr>
							<tr>
								<td></td>
								<td align="right"><b>วันที่ชำระ</b></td><td width="10">:</td><td><input type="text" id="xlsDate" name="xlsDate" value="<?php echo $xlsDate;?>" size="15" style="text-align: center;"></td>
							</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" class="static_class1" name="receive" id="receive3" value="3"  <?php if($office_id=="2"){ echo "checked"; }?>> ใบเสร็จใน xlease (จรัญ)
						<div id="showdivxleasejr">
							<table cellpadding="3" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="50"></td>
								<td width="120" align="right"><b>เลขที่ใบเสร็จ</b></td><td width="10">:</td><td><input type="text" name="recxleasejr" id="recxleasejr"></td>
							</tr>
							<tr>
								<td></td>
								<td align="right"><b>วันที่ชำระ</b></td><td width="10">:</td><td><input type="text" id="xlsjrDate" name="xlsjrDate" value="<?php echo $xlsjrDate;?>" size="15" style="text-align: center;"></td>
							</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" class="static_class1" name="receive" id="receive2" value="2"> ใบเสร็จจากใบเสร็จชั่วคราว
						<div id="showdivshottime">
							<table cellpadding="3" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="50"></td>
								<td width="120" align="right"><b>เลขที่ใบเสร็จชั่วคราว</b></td><td width="10">:</td><td><input type="text" name="f_radio" id="f_radio"></td>
							</tr>
							<tr>
								<td></td>
								<td align="right"><b>วันที่ชำระ</b></td><td width="10">:</td><td><input type="text" id="payDate" name="payDate" value="<?php echo $payDate;?>" size="15" style="text-align: center;"></td>
							</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</tr>
		<tr bgcolor="#FFCCCC">
			<td colspan="6">
			<b>เลขที่ใบเสร็จ TAC :</b> <input type="text" name="recTac" id="recTac">
			</td>
		</tr>
		<tr>
			<td colspan="6">
			<?php 
			//หาวันที่ชำระล่าสุด + 1 เดือน
			$qrymax=pg_query("SELECT date(max(\"tacMonth\")+  interval '1 month') FROM \"tacReceiveTemp\" where \"tacID\"='$CusID'");
			list($maxMonth)=pg_fetch_array($qrymax);
			list($maxy,$maxm,$maxd)=explode("-",$maxMonth);
			
			if($maxy!=""){
				$ycurent=$maxy;
			}
			?>
			<div id='TextBoxesGroup'>
			<div id="TextBoxDiv1">
				<table width="100%" style="background-color:#FFECEC; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
				<tr>
					<td>
						<b>เงินที่จ่าย (บาท)</b> <input type="text" name="payMoney1" id="payMoney1" onkeypress="return check_number(event);" value="0.00" style="text-align:right;" onkeyup="javascript:updateSummary();"> 
						<b>เดือนที่จ่าย </b>
						<select name="month1" id="month1">
							<option value="01" <?php if($maxm=="" || $maxm=="01"){ echo "selected";}?>>มกราคม</option>
							<option value="02" <?php if($maxm=="02"){ echo "selected";}?>>กุมภาพันธ์</option>
							<option value="03" <?php if($maxm=="03"){ echo "selected";}?>>มีนาคม</option>
							<option value="04" <?php if($maxm=="04"){ echo "selected";}?>>เมษายน</option>
							<option value="05" <?php if($maxm=="05"){ echo "selected";}?>>พฤษภาคม</option>
							<option value="06" <?php if($maxm=="06"){ echo "selected";}?>>มิถุนายน</option>
							<option value="07" <?php if($maxm=="07"){ echo "selected";}?>>กรกฏาคม</option>
							<option value="08" <?php if($maxm=="08"){ echo "selected";}?>>สิงหาคม</option>
							<option value="09" <?php if($maxm=="09"){ echo "selected";}?>>กันยายน</option>
							<option value="10" <?php if($maxm=="10"){ echo "selected";}?>>ตุลาคม</option>
							<option value="11" <?php if($maxm=="11"){ echo "selected";}?>>พฤศจิกายน</option>
							<option value="12" <?php if($maxm=="12"){ echo "selected";}?>>ธันวาคม</option>
						</select>
						<b>ปีที่จ่าย (ค.ศ.)<b><input type="text" name="yearPay1" id="yearPay1" onkeypress="return check_year(event);" maxlength="4" size="10" value="<?php echo $ycurent?>">
					</td>
				</tr>
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
	<td width=""><input type="submit" value="บันทึกรายการ" id="submitButton"> ยอดรับชำระใบนี้รวม : <span id="divsummery" style="font-weight:bold;">0.00</span> บาท<input type="hidden" name="divsummery2" id="divsummery2"></td>
	<td align="right"><input type="button" value="+ เพิ่มรายการ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></td>
</tr>
</table>

<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
<tr>
	<td colspan="4"><b>รายการที่ถูกเพิ่ม</b></td>
</tr>
<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25" align="center">
	<td><b>ลำดับที่</b></td>
	<td><b>เลขที่ใบเสร็จ</b></td>
	<td><b>วันที่รับชำระ</b></td>
	<td><b>จำนวนเงินทั้งหมด (บาท)</b></td>
	<td><b>เดือนที่ชำระค่าวิทยุ</b></td>
	<td><b>เลขที่ใบเสร็จ TAC</b></td>
</tr>
<?php
	$querytac=pg_query("select distinct(\"tacXlsRecID\"),\"tacTempDate\" from \"tacReceiveTemp\" where \"tacID\"='$CusID' order by \"tacTempDate\" DESC");
	$numtac=pg_num_rows($querytac);
	$i=1;
	while($restac=pg_fetch_array($querytac)){
		$tacXlsRecID=$restac["tacXlsRecID"];
		$tacTempDate=$restac["tacTempDate"];
		
		$querydetail=pg_query("select sum(\"tacMoney\") as summoney,min(\"tacMonth\") as monthmin,max(\"tacMonth\") as monthmax,\"tacOldRecID\" from \"tacReceiveTemp\" where \"tacID\"='$CusID' and \"tacXlsRecID\"='$tacXlsRecID' group by \"tacOldRecID\"");
		if($resdetail=pg_fetch_array($querydetail)){
			$tacMoney=number_format($resdetail["summoney"],2);
			$tacMonthMin=$resdetail["monthmin"];
			$ymin=substr($tacMonthMin,0,4);
			$dmin=substr($tacMonthMin,5,2);
			$tacMonthMin=$ymin."/".$dmin;
			
			$tacMoneyMax=$resdetail["monthmax"];
			$ymax=substr($tacMoneyMax,0,4);
			$dmax=substr($tacMoneyMax,5,2);
			$tacMoneyMax=$ymax."/".$dmax;
			
			$tacOldRecID=$resdetail["tacOldRecID"]; if($tacOldRecID=="") $tacOldRecID="ไม่มี";
		}
		if($i%2 == 0){
			$color="#DBF2FD";
		}else{
			$color="#AAE0FB";
		}
		echo "<tr align=center bgcolor=$color>";
		echo "<td>$i</td>";
		echo "<td>$tacXlsRecID</td>";
		echo "<td align=center>$tacTempDate</td>";
		echo "<td align=right>$tacMoney</td>";
		echo "<td>$tacMonthMin - $tacMoneyMax</td>";
		echo "<td>$tacOldRecID</td>";
		echo "</tr>";
		$i++;
	}
	if($numtac==0){
		echo "<tr align=center bgcolor=#DBF2FD>";
		echo "<td colspan=6 height=50><b>-ไม่พบข้อมูล-</b></td>";
		echo "</tr>";
	}
?>
</table>

<script type="text/javascript">
var counter = 1;
$(document).ready(function(){

    $('#addButton').click(function(){
	//จำนวนเงินล่าสุดที่กรอก
	var nowmoney=$("#payMoney"+ counter).val();
	//เดือนที่เลือกล่าสุด
	var selectmonth=$("#month"+ counter).val();
	var selectyear=$("#yearPay"+ counter).val();
	var dateStr=selectmonth+"/01/"+selectyear;
	
	//เดือนถัดไปต่อจากเดือนที่เลือก
	var datenext=calendarAddMonth(dateStr, 1);
	
	var t = datenext.split("-");
	var nextyear=t[0];
	var nextmonth=t[1];
	
    counter++;
    console.log(counter);
    var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);

    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FFECEC;border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
    + ' <tr>'
    + ' <td><b>เงินที่จ่าย (บาท)</b> <input type="text" name="payMoney'+ counter +'" id="payMoney'+ counter +'" value='+nowmoney+' style="text-align:right;" onkeyup="javascript:updateSummary()">'
	+ '	<b>เดือนที่จ่าย </b>'
	+ '	<select name="month'+ counter +'" id="month'+ counter +'">'
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
	+ '	<b>ปีที่จ่าย (ค.ศ.)<b><input type="text" name="yearPay'+ counter +'" id="yearPay'+ counter +'" maxlength="4" size="10" value='+nextyear+'></td>'
    + ' </tr>'
    + ' </table>';

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
		$('#month'+counter+' option[value='+nextmonth+']').attr('selected','selected');
		updateSummary();
    });
    
    $("#removeButton").click(function(){
        if(counter==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
        updateSummary();
    });
    
    $("#submitButton").click(function(){
        if(document.getElementById("receive1").checked){
			if( $('#recxlease').val() == "" ){
				alert('กรุณากรอกเลขที่ใบเสร็จ !');
				$('#recxlease').focus();
				return false;
			}else{
				if($("#recmoney").val()=="NO"){
					alert("เลขที่ใบเสร็จไม่ถูกต้อง หรือถูกใช้ไปแล้ว กรุณาตรวจสอบ!!");
					return false;
				}else{
					if(parseFloat($("#divsummery2").val())!=parseFloat($("#recmoney").val())){
						alert("จำนวนเงินรวมไม่ตรงกับใบเสร็จ กรุณาตรวจสอบ!!");
						return false;
					}
				}					
			}
		
		}else if(document.getElementById("receive2").checked){
			if( $('#f_radio').val() == "" ){
				alert('กรุณากรอกเลขที่ใบเสร็จ !');
				$('#f_radio').focus();
				return false;
			}
		}else if(document.getElementById("receive3").checked){
			if( $('#recxleasejr').val() == "" ){
				alert('กรุณากรอกเลขที่ใบเสร็จ !');
				$('#recxleasejr').focus();
				return false;
			}
		}
		
		
		for(i=1; i<=counter; i++){
            if( $('#payMoney'+ i).val() == "" ){
                alert('กรุณากรอกเงินที่จ่าย !');
				$('#payMoney'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
        }
		
        
        $("#submitButton").attr('disabled', true);
        var payment = [];
        for( i=1; i<=counter; i++ ){
            var c1 = $('#yearPay'+ i).val();
            if ( isNaN(c1) || c1 == "" || c1 == 0){
                alert('กรุณากรอกปีที่จ่าย !');
                $('#yearPay'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			payment[i] = {paymoney : $("#payMoney"+ i).val() , month: $("#month"+ i).val() , yearpay : $("#yearPay"+ i).val()};
        }
        
        $.post("api.php",{
            cusid : '<?php echo "$CusID"; ?>',
			tacxlsrecid1 : $("#recxlease").val(),
			tacxlsrecid2 : $("#f_radio").val(),
			tacxlsrecid3 : $("#recxleasejr").val(),
			tactempdate : $("#payDate").val(),
			tacxlsdate : $("#xlsDate").val(),
			tacxlsjrdate : $("#xlsjrDate").val(),
			tacoldrecid : $("#recTac").val(),
            payment : JSON.stringify(payment) 
        },
        function(data){
            if(data == "1"){
                alert("บันทึกรายการเรียบร้อย");
                location.href = "frm_Receive.php";
                $("#submitButton").attr('disabled', false);
            }else{
                alert(data);
                $("#submitButton").attr('disabled', false);
            }
        });
    });  
});

//สำหรับคำนวณยอดรวม
function updateSummary(){
    var sss = 0;
    for( i=1; i<=counter; i++ ){
        var c1 = $('#payMoney'+ i).val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        sss += parseFloat(c1);
		
    }
    $("#divsummery").text(addCommas(sss.toFixed(2)));
	$("#divsummery2").val(sss.toFixed(2));
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
