<?php
include("../../config/config.php");
$ConID=$_GET["ConID"];

$namecus="";
$qury_cus=pg_query("select \"CusPreName\"||\"CusFirstName\"||' '||\"CusLastName\"  as name from \"thcap_cus_temp\" where \"ConID\"='$ConID'");
$num_cus=pg_num_rows($qury_cus);
$nub=1;
while($rescus=pg_fetch_array($qury_cus)){
	$name=$rescus["name"];
	if($nub == $num_cus){
		$namecus= $namecus.$name;
	}else{
		if($nub%7 == 0){
			$addbr = "<br>";
		}else{
			$addbr = "";
		}
		$namecus= $namecus.$name.",$addbr";
	}
	$nub++;
}
$yearnow=date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
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

</head>
<body>

<table width="1100" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<div style="float:right"><input type="button" value=" Back " class="ui-button" onclick="window.location='frm_IndexSetDebt.php';"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>ตั้งหนี้รายสัญญา</B></legend>

<div class="ui-widget">

<div style="font-weight:bold; margin:10px 0px 10px 0px">เลขที่สัญญา : <span style="color:#0000FF"><?php echo "$ConID"; ?></span></div>
<div style="font-weight:bold; margin:10px 0px 10px 0px">ลูกค้า : <span style="color:#0000FF"><?php echo "$namecus"; ?></span></div>

<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FFC1C1; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr bgcolor="#F8F8F8"><td colspan="5">
<b>วันที่ตั้งหนี้</b> 
<select name="inday1" id="inday1" onchange="javascript:checkdate(1)">
			<option value="">--วัน--</option>
			<?php
			for($i=1;$i<=31;$i++){
				if($i<10){
					$day="0".$i;
				}else{
					$day=$i;
				}
				echo "<option value=$day>$day</option>";
			}
			?>
		</select>
		<select name="intmount1" id="intmount1" onchange="javascript:checkdate(1)">
			<option value="">----เดือน----</option>
			<option value="01">มกราคม</option>
			<option value="02">กุมภาพันธ์</option>
			<option value="03">มีนาคม</option>
			<option value="04">เมษายน</option>
			<option value="05">พฤษภาคม</option>
			<option value="06">มิถุนายน</option>
			<option value="07">กรกฎาคม</option>
			<option value="08">สิงหาคม</option>
			<option value="09">กันยายน</option>
			<option value="10">ตุลาคม</option>
			<option value="11">พฤศจิกายน</option>
			<option value="12">ธันวาคม</option>
		</select>
		<b>ปี ค.ศ.</b><input type="text" name="intyear1" id="intyear1" size="10" value="<?php echo $yearnow;?>" style="text-align:center;" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)" onchange="javascript:checkdate(1)">
</td></tr>
<tr style="font-weight:bold">
    <td width="220">ค่าใช้จ่ายที่เรียกเก็บ</td>
    <td width="130">จำนวนเงิน (บาท)</td>
	<td width="130">วันที่ครบกำหนดชำระ</td>
    <td width="200">เหตุผลที่ตั้งหนี้</td>
	<td>Ref.ของค่าใช้จ่าย</td>   
</tr>
</table>
<div id='TextBoxesGroup1'>
    <div id="TextBoxDiv1">
<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr bgcolor="#FFF0F0" valign="top">
    <td width="220"><!--ค่าใช้จ่ายที่เรียกเก็บ-->
		<select name="tpID1" id="tpID1" onchange="javascript:checktpID(1,1)">
			<option value="">--เลือก--</option>
			<?php
			$qrytp=pg_query("select * from account.\"thcap_typePay\" where \"tpType\" <> 'LOCKED' order by \"tpSort\"");
			while($restp=pg_fetch_array($qrytp)){
				$tpID=$restp["tpID"];
				$tpDesc=$restp["tpDesc"];
				echo "<option value=\"$tpID\">$tpID,$tpDesc</option>";
			}
			?>
			
		</select>
	</td>
	<td width="130">
		<input type="text" name="invoiceAmt1" id="invoiceAmt1" size="15" style="text-align:right;">
	</td>
	<td width="130">
		<select name="DueD1" id="DueD1">
			<option value="">-----วัน-----</option>
			<?php
			for($i=1;$i<=31;$i++){
				if($i<10){
					$day="0".$i;
				}else{
					$day=$i;
				}
				echo "<option value=$day>$day</option>";
			}
			?>
		</select><br>
		<select name="DueM1" id="DueM1">
			<option value="">----เดือน----</option>
			<option value="01">มกราคม</option>
			<option value="02">กุมภาพันธ์</option>
			<option value="03">มีนาคม</option>
			<option value="04">เมษายน</option>
			<option value="05">พฤษภาคม</option>
			<option value="06">มิถุนายน</option>
			<option value="07">กรกฎาคม</option>
			<option value="08">สิงหาคม</option>
			<option value="09">กันยายน</option>
			<option value="10">ตุลาคม</option>
			<option value="11">พฤศจิกายน</option>
			<option value="12">ธันวาคม</option>
		</select><br>
		<input type="text" name="DueY1" id="DueY1" size="10" value="<?php echo $yearnow;?>" style="text-align:center;" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)" ><b>ปี ค.ศ.</b>
	</td>
	<td width="200">
		<input type="text" name="resultset1" id="resultset1" size="30">
	</td>
    <td>
		<span id="type_detail1"></span>	
		<span id="discount1"></span>
		<input type="hidden" name="refdoc1" id="refdoc1">
		<input type="hidden" name="sendDate1" id="sendDate1">
		<input type="hidden" name="reftype1" id="reftype1">
	</td>
</tr>
</table>
    </div>
</div>

<div style="margin-top:20px">
<div style="float:left"><input type="button" value="+ เพิ่มวันที่ตั้งหนี้" id="addGroup"></div>
<div style="float:right"><input type="button" value="+ เพิ่มรายการ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></div>
<div style="clear:both"></div>
<br>
<div style="float:left"><span><b>หมายเหตุผู้ทำรายการตั้งหนี้</b></span><br><textarea name="descrip" id="descript" cols="50" rows="4"></textarea><br><input type="button" value="บันทึกข้อมูล" id="submitButton"></div>
<div style="clear:both"></div>
</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

<script type="text/javascript">
var counter = 1;
var counter2 = 1;
$(document).ready(function(){
	$('#addButton').click(function(){
    counter++;
    console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr bgcolor="#FFF0F0" valign="top">'
	+ '		<td width="220"><!--ค่าใช้จ่ายที่เรียกเก็บ-->'
	+ '			<select name="tpID'+counter +'" id="tpID'+counter +'" onchange=\"javascript:checktpID('+counter +','+ counter2 +')\">'
	+ '				<option value="">--เลือก--</option>'
	+ '				<?php
					$qrytp=pg_query("select * from account.\"thcap_typePay\" where \"tpType\" <> 'LOCKED' order by \"tpSort\"");
					while($restp=pg_fetch_array($qrytp)){
						$tpID=$restp["tpID"];
						$tpDesc=$restp["tpDesc"];
						echo "<option value=$tpID>$tpID,$tpDesc</option>";
					}
					?>'				
	+ '			</select>'
	+ '		</td>'
	+ '		<td width="130">'
	+ '			<input type="text" name="invoiceAmt'+counter +'" id="invoiceAmt'+counter +'" size="15" style="text-align:right;">'
	+ '		</td>'
	+ '		<td width="130">'
	+ '			<select name="DueD'+counter +'" id="DueD'+counter +'">'
	+ '				<option value="">-----วัน-----</option>'
	+ '				<?php
					for($i=1;$i<=31;$i++){
						if($i<10){
							$day="0".$i;
						}else{
							$day=$i;
						}
						echo "<option value=$day>$day</option>";
					}
					?>'
	+ '			</select><br>'
	+ '			<select name="DueM'+counter +'" id="DueM'+counter +'">'
	+ '				<option value="">----เดือน----</option>'
	+ '				<option value="01">มกราคม</option>'
	+ '				<option value="02">กุมภาพันธ์</option>'
	+ '				<option value="03">มีนาคม</option>'
	+ '				<option value="04">เมษายน</option>'
	+ '				<option value="05">พฤษภาคม</option>'
	+ '				<option value="06">มิถุนายน</option>'
	+ '				<option value="07">กรกฎาคม</option>'
	+ '				<option value="08">สิงหาคม</option>'
	+ '				<option value="09">กันยายน</option>'
	+ '				<option value="10">ตุลาคม</option>'
	+ '				<option value="11">พฤศจิกายน</option>'
	+ '				<option value="12">ธันวาคม</option>'
	+ '			</select><br>'
	+ '			<input type="text" name="DueY'+counter +'" id="DueY'+counter +'" size="10" value="<?php echo $yearnow;?>" style="text-align:center;" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)" ><b>ปี ค.ศ.</b>'
	+ '		</td>'
	+ '		<td width="200">'
	+ '			<input type="text" name="resultset'+ counter +'" id="resultset'+ counter +'" size="30">'
	+ '         <input type="hidden" name="refdoc'+ counter +'" id="refdoc'+ counter +'">'
	+ '		</td>'	
	+ '		<td>'
	+ '			<span id="type_detail'+ counter +'"></span>'
	+ '			<span id="discount'+ counter +'"></span>'
	+ '			<input type="hidden" name="sendDate'+ counter +'" id="sendDate'+ counter +'">'
	+ '			<input type="hidden" name="reftype'+ counter +'" id="reftype'+ counter +'">'
	+ '		</td>'
	+ '	</tr>'
	+ '	</table>'
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup1");

	
    });
	
    $('#addGroup').click(function(){
    counter++;
    console.log(counter);
	counter2++;	
	var newTextBoxDivGroup = $(document.createElement('div')).attr("id", 'TextBoxesGroup' + counter);
    table = '<br><br><table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FFC1C1;border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ ' <tr bgcolor="#F8F8F8"><td colspan="5">'
	+ '<b>วันที่ตั้งหนี้</b> '
	+ '<select name="inday'+ counter2 +'" id="inday'+ counter2 +'" onchange="javascript:checkdate('+counter+')">'
	+ '			<option value="">--วัน--</option>'
	+ '			<?php
				for($i=1;$i<=31;$i++){
					if($i<10){
						$day="0".$i;
					}else{
						$day=$i;
					}
					echo "<option value=$day>$day</option>";
				}
				?>'
	+ '		</select>'
	+ '		<select name="intmount'+ counter2 +'" id="intmount'+ counter2 +'" onchange="javascript:checkdate('+counter+')">'
	+ '			<option value="">----เดือน----</option>'
	+ '			<option value="01">มกราคม</option>'
	+ '			<option value="02">กุมภาพันธ์</option>'
	+ '			<option value="03">มีนาคม</option>'
	+ '			<option value="04">เมษายน</option>'
	+ '			<option value="05">พฤษภาคม</option>'
	+ '			<option value="06">มิถุนายน</option>'
	+ '			<option value="07">กรกฎาคม</option>'
	+ '			<option value="08">สิงหาคม</option>'
	+ '			<option value="09">กันยายน</option>'
	+ '			<option value="10">ตุลาคม</option>'
	+ '			<option value="11">พฤศจิกายน</option>'
	+ '			<option value="12">ธันวาคม</option>'
	+ '		</select>'
	+ '		<b>ปี ค.ศ.</b><input type="text" name="intyear'+ counter2 +'" id="intyear'+ counter2 +'" size="10" value="<?php echo $yearnow;?>" style="text-align:center;" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)" onchange="javascript:checkdate('+counter+')">'
	+ ' </td></tr>'
	+ '	<tr style="font-weight:bold">'
	+ '	<td width="220">ค่าใช้จ่ายที่เรียกเก็บ</td>'
	+ '		<td width="130">จำนวนเงิน (บาท)</td>'
	+ '		<td width="130">วันที่ครบกำหนดชำระ</td>'
	+ '		<td width="200">เหตุผลที่ตั้งหนี้</td>'
	+ '		<td>Ref.ของค่าใช้จ่าย</td>'   
	+ '	</tr>'
	+ '	<tr bgcolor="#FFF0F0" valign="top">'
	+ '		<td width="220"><!--ค่าใช้จ่ายที่เรียกเก็บ-->'
	+ '			<select name="tpID'+ counter +'" id="tpID'+ counter +'" onchange=\"javascript:checktpID('+ counter +','+ counter2 +')\">'
	+ '				<option value="">--เลือก--</option>'
	+ '				<?php
					$qrytp=pg_query("select * from account.\"thcap_typePay\" where \"tpType\" <> 'LOCKED' order by \"tpSort\"");
					while($restp=pg_fetch_array($qrytp)){
						$tpID=$restp["tpID"];
						$tpDesc=$restp["tpDesc"];
						echo "<option value=$tpID>$tpID,$tpDesc</option>";
					}
					?>'				
	+ '			</select>'
	+ '		</td>'
	+ '		<td width="130">'
	+ '			<input type="text" name="invoiceAmt'+ counter +'" id="invoiceAmt'+ counter +'" size="15" style="text-align:right;">'
	+ '		</td>'
	+ '		<td width="130">'
	+ '			<select name="DueD'+ counter +'" id="DueD'+ counter +'">'
	+ '				<option value="">-----วัน-----</option>'
	+ '				<?php
					for($i=1;$i<=31;$i++){
						if($i<10){
							$day="0".$i;
						}else{
							$day=$i;
						}
						echo "<option value=$day>$day</option>";
					}
					?>'
	+ '			</select><br>'
	+ '			<select name="DueM'+ counter +'" id="DueM'+ counter +'">'
	+ '				<option value="">----เดือน----</option>'
	+ '				<option value="01">มกราคม</option>'
	+ '				<option value="02">กุมภาพันธ์</option>'
	+ '				<option value="03">มีนาคม</option>'
	+ '				<option value="04">เมษายน</option>'
	+ '				<option value="05">พฤษภาคม</option>'
	+ '				<option value="06">มิถุนายน</option>'
	+ '				<option value="07">กรกฎาคม</option>'
	+ '				<option value="08">สิงหาคม</option>'
	+ '				<option value="09">กันยายน</option>'
	+ '				<option value="10">ตุลาคม</option>'
	+ '				<option value="11">พฤศจิกายน</option>'
	+ '				<option value="12">ธันวาคม</option>'
	+ '			</select><br>'
	+ '			<input type="text" name="DueY'+ counter +'" id="DueY'+ counter +'" size="10" value="<?php echo $yearnow;?>" style="text-align:center;" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)" ><b>ปี ค.ศ.</b>'
	+ '		</td>'
	+ '		<td width="200">'
	+ '			<input type="text" name="resultset'+ counter +'" id="resultset'+ counter +'" size="30">'
	+ '         <input type="hidden" name="refdoc'+ counter +'" id="refdoc'+ counter +'">'
	+ '		</td>'	
	+ '		<td>'
	+ '			<span id="type_detail'+ counter +'"></span>'
	+ '			<span id="discount'+ counter +'"></span>'
	+ '			<input type="hidden" name="sendDate'+ counter +'" id="sendDate'+ counter +'">'
	+ '			<input type="hidden" name="reftype'+ counter +'" id="reftype'+ counter +'">'
	+ '		</td>'
	+ '	</tr>'
	+ '	</table>'

        newTextBoxDivGroup.html(table);

        newTextBoxDivGroup.appendTo("#TextBoxesGroup1");

	
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
		
        $("#submitButton").attr('disabled', true);
        var payment = [];
		var DueD;
		var DueM;
		var DueY;
		var setdate="";
		var DueDate="";
		
		for( i=1; i<=counter; i++ ){
			DueD=$("#DueD"+i).val();
			DueM=$("#DueM"+i).val();
			DueY=$("#DueY"+i).val();
			DueDate=DueY+'-'+DueM+'-'+DueD;
			
			if ( $("#tpID"+i).val() == ""){
                alert('กรุณาเลือกค่าใช้จ่ายที่เรียกเก็บ'+i);
                $('#tpID'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			var c1 = $('#invoiceAmt'+ i).val();
            if ( isNaN(c1) || c1 == ""){
                alert('ข้อมูลจำนวนเงินไม่ถูกต้อง'+i);
                $('#invoiceAmt'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			if ( DueD == "" || DueM=="" || DueY==""){
                alert('กรุณาระบุวันที่ครบกำหนดชำระ'+i);
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			var reftype=$('#reftype'+i).val();
			if(reftype=='D'){
				if($('#d'+i).val() == ""){
					alert('กรุณาระบุวันที่ของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#m'+i).val() == ""){
					alert('กรุณาระบุเดือนของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#y'+i).val() == ""){
					alert('กรุณาระบุปีของ Ref'+i);
					$('#y'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
				
			}else if(reftype=='W'){
				if($('#s'+i).val() == ""){
					alert('กรุณาสัปดาห์ของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#m'+i).val() == ""){
					alert('กรุณาระบุเดือนของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#y'+i).val() == ""){
					alert('กรุณาระบุปีของ Ref'+i);
					$('#y'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
			}else if(reftype=='M'){
				if($('#m'+i).val() == ""){
					alert('กรุณาระบุเดือนของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#y'+i).val() == ""){
					alert('กรุณาระบุปีของ Ref'+i);
					$('#y'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}		
			}else if(reftype=='Y'){
				if($('#y'+i).val() == ""){
					alert('กรุณาระบุปีของ Ref'+i);
					$('#y'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
			}else if(reftype=='L'){
				if($('#d'+i).val() == ""){
					alert('กรุณาระบุวันที่เริ่มต้นของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#m'+i).val() == ""){
					alert('กรุณาระบุเดือนเริ่มต้นของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#y'+i).val() == ""){
					alert('กรุณาระบุปีเริ่มต้นของ Ref'+i);
					$('#y'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#dd'+i).val() == ""){
					alert('กรุณาระบุวันสิ้นสุดของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#mm'+i).val() == ""){
					alert('กรุณาระบุเดือนสิ้นสุดของ Ref'+i);
					$("#submitButton").attr('disabled', false);
					return false;
				}
				if($('#yy'+i).val() == ""){
					alert('กรุณาระบุปีสิ้นสุดของ Ref'+i);
					$('#yy'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
			}else if(reftype=='ID'){
				if($('#bookin'+i).val() == ""){
					alert('กรุณาระบุ Ref ตามหนังสือหรือรหัสใบ'+i);
					$('#bookin'+ i).focus();
					$("#submitButton").attr('disabled', false);
					return false;
				}
			}
			
			payment[i] = {setdate : $("#sendDate"+ i).val() , tpID: $("#tpID"+ i).val(), invoiceAmt: $("#invoiceAmt"+ i).val(), resultset: $("#resultset"+ i).val() , refdoc: $('#refdoc'+i).val(),DueDate:DueDate};
		}
        
        $.post("api_debt.php",{
            cmd : "save" , 
            ConID : '<?php echo $ConID; ?>', 
			descript :$("#descript").val(),
            payment : JSON.stringify(payment) 
        },
        function(data){
            if(data == "1"){
                alert("บันทึกรายการเรียบร้อย");
                location.href = "frm_IndexSetDebt.php";
                $("#submitButton").attr('disabled', false);
            }else{
				//alert(data);
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
                $("#submitButton").attr('disabled', false);
            }
        });

    });
    
});
function checktpID(id,id2){
    checkSelectCB(id,id2);
    updateSummary();
}
function checkSelectCB(id,id2){
    $("#type_detail"+ id).hide();
    $("#discount"+ id).hide();
	var date;
	
	var d1=$("#inday"+id2).val();
	var m1=$("#intmount"+id2).val();
	var y1=$("#intyear"+id2).val();
	
	if(d1=="" || m1=="" || y1==""){
		alert("กรุณาระบุวัน-เดือน-ปี ที่ตั้งหนี้");
		$("#tpID"+ id).val("");
	}else{
		if( $("#tpID"+ id).val() != "" ){ 
			$.get('api_debt.php?cmd=checktpID&tpID='+ $("#tpID"+ id).val(), function(data){
					if(data == 'NONE'){
						$("#type_detail"+ id).load("api_debt.php?cmd=loaddue&id="+ id +"&tpID="+$("#tpID"+ id).val());
						$("#type_detail"+ id).show();
						$("#invoiceAmt"+ id).val(0);
						$("#invoiceAmt"+ id).attr("readonly", "");
					}else if(data=='FIXED'){
						$("#type_detail"+ id).load("api_debt.php?cmd=loaddue&id="+ id +"&tpID="+$("#tpID"+ id).val());
						$("#type_detail"+ id).show();
						$.get('api_debt.php?cmd=checkfixed&tpID=' +$("#tpID"+ id).val()+ '&inday='+ $("#inday"+id2).val()+'&intmount='+ $("#intmount"+id2).val()+'&intyear='+ $("#intyear"+id2).val(), function(data2){
							$("#invoiceAmt"+ id).val(data2);
							$("#invoiceAmt"+ id).attr("readonly", "readonly");
						});
					}else if(data=='VAR'){
						$("#type_detail"+ id).load("api_debt.php?cmd=loaddue&id="+ id +"&tpID="+$("#tpID"+ id).val());
						$("#type_detail"+ id).show();
						$("#invoiceAmt"+ id).val(0);
						$("#invoiceAmt"+ id).attr("readonly", "");
					}else if(data=='PER'){
						$("#type_detail"+ id).load("api_debt.php?cmd=loaddue&id="+ id +"&tpID="+$("#tpID"+ id).val());
						$("#type_detail"+ id).show();
						$("#invoiceAmt"+ id).val(0);
						$("#invoiceAmt"+ id).attr("readonly", "");
					}
				var d=$("#inday"+id2).val();
				var m=$("#intmount"+id2).val();
				var y=$("#intyear"+id2).val();
				
				date=y+'-'+m+'-'+d;
				$("#sendDate"+ id).val(date);
			});
			$.get('api_debt.php?cmd=checkRefType&tpID='+ $("#tpID"+ id).val(), function(data2){
				$("#reftype"+ id).val(data2);
			});
		}else{
			$("#invoiceAmt"+ id).hide();
			$("#resultset"+ id).hide();
		}
	}
}

function updateSummary(){
    var sss = 0;
    for( i=1; i<=counter; i++ ){
        var c1 = $('#invoiceAmt'+ i).val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        sss += parseFloat(c1);
    }
    $("#divsummery").text(sss.toFixed(2));
}

function checkdate(){
	for( i=1; i<=counter2; i++ ){
		var d1=$('#inday'+i).val();
		var m1=$('#intmount'+i).val();
		var y1=$('#intyear'+i).val();
		
		if(d1!="" && m1!="" && y1 !=""){
			for( p=1; p<=counter2; p++ ){
				var d2=$('#inday'+p).val();
				var m2=$('#intmount'+p).val();
				var y2=$('#intyear'+p).val();
				
				if(i==p){
					continue;
				}else if((d1==d2 && m1==m2 && y1==y2)){
					alert("วันที่ซ้ำกันกรุณาเลือกใหม่");
					$('#inday'+p).val("");
					$('#intmount'+p).val("");
					$('#intyear'+p).val("");
					break;
				}
			}
		}else{
			continue;
		}
	}
}
function amtDue(id,tpRefType){
	if(tpRefType=='D'){
		if($('#d'+id).val() != ""){
			var d=$('#d'+id).val();
		}
		if($('#m'+id).val() != ""){
			var m=$('#m'+id).val();
		}
		if($('#y'+id).val() != ""){
			var y=$('#y'+id).val();
		}
		refdoc2=y+m+d;
	}else if(tpRefType=='W'){
		if($('#s'+id).val() != ""){
			var s=$('#s'+id).val();
		}
		if($('#m'+id).val() != ""){
			var m=$('#m'+id).val();
		}
		if($('#y'+id).val() != ""){
			var y=$('#y'+id).val();
		}
		refdoc=y+m+s;
	}else if(tpRefType=='M'){
		if($('#m'+id).val() != ""){
			var m=$('#m'+id).val();
		}
		if($('#y'+id).val() != ""){
			var y=$('#y'+id).val();
		}		
		refdoc2=y+m;
	}else if(tpRefType=='Y'){
		if($('#y'+id).val() != ""){
			var y=$('#y'+id).val();
		}
		refdoc2=y;	
	}else if(tpRefType=='L'){
		if($('#d'+id).val() != ""){
			var d=$('#d'+id).val();
		}
		if($('#m'+id).val() != ""){
			var m=$('#m'+id).val();
		}
		if($('#y'+id).val() != ""){
			var y=$('#y'+id).val();
		}
		if($('#dd'+id).val() != ""){
			var dd=$('#dd'+id).val();
		}
		if($('#mm'+id).val() != ""){
			var mm=$('#mm'+id).val();
		}
		if($('#yy'+id).val() != ""){
			var yy=$('#yy'+id).val();
		}
		refdoc2=y+m+d+'-'+yy+mm+dd;
	}else if(tpRefType=='ID'){
		if($('#bookin'+id).val() != ""){
			var bookin=$('#bookin'+id).val();
		}
		refdoc2=bookin;
	}
				
	$('#refdoc'+id).val(refdoc2);

}
</script>


</body>
</html>