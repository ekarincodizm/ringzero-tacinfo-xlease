<?php
include("../../config/config.php");
$contractID=$_POST["id"];
$show=$_POST["show"];
if($contractID=="" and $show==""){
	$contractID=$_GET["contractID"];
	$show=$_GET["show"];
}

//นำเลขที่สัญญาไป query ดูว่ามีเลขที่สัญญานี้หรือไม่
$qrycheck=pg_query("SELECT * FROM thcap_contract where \"contractID\" = '$contractID'");
$numcheck=pg_num_rows($qrycheck);
if($numcheck==0){
	$show=0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตั้งหนี้เงินกู้ชั่วคราว</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#maturityDiv").hide();

	$("#id").autocomplete({
       // source: "s_thcap.php?condition=1",
       // minLength:2
	   source: "s_idall.php",
        minLength:1
    });
	
	$("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#maturityDatepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#submitButton").click(function(){
			if($("#fpayid").val()==""){
			alert('กรุณาเลือกประเภทหนี้ที่จะตั้ง');
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#fpayrefvalue").val()==""){
			alert('กรุณาระบุเลขที่อ้างอิงหนี้');
			$('#fpayrefvalue').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if(document.getElementById("maturityDate_have").checked == false && document.getElementById("maturityDate_no").checked == false){
			alert('กรุณารุะบุวันที่ครบกำหนดชำระ');
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#fpayamp").val()==""){
			alert('กรุณารุะบุจำนวนเงิน');
			$('#fpayamp').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}
		
		// ตรวจสอบ format วันที่ตั้งหนี้
		var datepickerChk = document.getElementById("datepicker").value;
		if(datepickerChk.length != 10 || datepickerChk.substring(4,5) != '-' || datepickerChk.substring(7,8) != '-')
		{
			alert('รูปแบบ วันที่ตั้งหนี้ ไม่ถูกต้อง รูปแบบที่ถูกต้อง เช่น 1999-12-31 ');
			return false;
		}
		
		// ตรวจสอบ format วันที่ครบกำหนดชำระ
		if(document.getElementById("maturityDate_have").checked == true)
		{
			var maturityDatepickerChk = document.getElementById("maturityDatepicker").value;
			
			if(maturityDatepickerChk.length != 10 || maturityDatepickerChk.substring(4,5) != '-' || maturityDatepickerChk.substring(7,8) != '-')
			{
				alert('รูปแบบ วันที่ครบกำหนดชำระ ไม่ถูกต้อง รูปแบบที่ถูกต้อง เช่น 1999-12-31');
				return false;
			}
		}
	
	// ถ้ามีวันที่ครบกำหนด
	if(document.getElementById("maturityDate_have").checked == true)
	{
		$('body').append('<div id="dialog"></div>');
		$('#dialog').load('popup-conf.php?fpayid='+$("#fpayid").val()+'&datepicker='+$("#datepicker").val()+'&fpayamp='+$("#fpayamp").val().replace(/,/g,'')+'&vat_inc='+$('input:radio[name=vat_inc]:checked').val()+'&contractID=<?php echo $contractID?>'+'&maturityDatepicker='+$("#maturityDatepicker").val()+'&remark='+encode_hex($("#remark").val()));
		$('#dialog').dialog({
			title: 'ยืนยันการบันทึกข้อมูล ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 300,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});
	}
	else // ถ้าไม่มีวันที่ครบกำหนด
	{
		$('body').append('<div id="dialog"></div>');
		$('#dialog').load('popup-conf.php?fpayid='+$("#fpayid").val()+'&datepicker='+$("#datepicker").val()+'&fpayamp='+$("#fpayamp").val().replace(/,/g,'')+'&vat_inc='+$('input:radio[name=vat_inc]:checked').val()+'&contractID=<?php echo $contractID?>'+'&remark='+encode_hex($("#remark").val()));
		$('#dialog').dialog({
			title: 'ยืนยันการบันทึกข้อมูล ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 300,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});
	}

	/*	$("#submitButton").attr('disabled', true);
		if($("#fpayid").val()==""){
			alert('กรุณาเลือกประเภทหนี้ที่จะตั้ง');
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#fpayrefvalue").val()==""){
			alert('กรุณาระบุเลขที่อ้างอิงหนี้');
			$('#fpayrefvalue').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#fpayamp").val()==""){
			alert('กรุณารุะบุจำนวนเงิน');
			$('#fpayamp').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}
	
		$.post("process_setdebtloan.php",{
			cmd : "add",
			contractID :'<?php //echo $contractID;?>',
			fpayid : $("#fpayid").val(), 
			fpayrefvalue :$("#fpayrefvalue").val(),
			datepicker :$("#datepicker").val(),
			fpayamp :$("#fpayamp").val(),
			
		},
		function(data){
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
				location.href = "frm_setDebtLoanTime.php?contractID=<?php //echo $contractID?>&show=1";
				$("#submitButton").attr('disabled', false);
			}else if(data == "2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				$("#submitButton").attr('disabled', false);
			}else if(data == "3"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้ เนื่องจากตั้งหนี้ซ้ำ!");
				$("#submitButton").attr('disabled', false);
			}
		});
	});
	*/
	
});
$("#cancelvalue").click(function(){
		$("#fpayid").val('');
		$("#fpayrefvalue").val('');
		$("#fpayamp").val('0.00');
	});
});
//ฟังก์ชั่นสำหรับแปลงตัวอักษรเป็นรหัส HEX เพื่อให้ส่งข้อมูลผ่าน url ได้
function encode_hex(str){
	str = str.replace(/%/g,'%25');
	str = str.replace(/\ /g,'%20');
	str = str.replace(/\!/g,'%21');
	str = str.replace(/\"/g,'%22');
	str = str.replace(/\#/g,'%23');
	str = str.replace(/\$/g,'%24');
	str = str.replace(/\&/g,'%26');
	str = str.replace(/\'/g,'%27');
	str = str.replace(/\(/g,'%28');
	str = str.replace(/\)/g,'%29');
	str = str.replace(/\*/g,'%2A');
	str = str.replace(/\+/g,'%2B');
	str = str.replace(/\,/g,'%2C');
	str = str.replace(/\-/g,'%2D');
	str = str.replace(/\./g,'%2E');
	str = str.replace(/\//g,'%2F');
	str = str.replace(/\:/g,'%3A');
	str = str.replace(/\;/g,'%3B');
	str = str.replace(/\</g,'%3C');
	str = str.replace(/\=/g,'%3D');
	str = str.replace(/\>/g,'%3E');
	str = str.replace(/\?/g,'%3F');
	str = str.replace(/\@/g,'%40');
	str = str.replace(/\[/g,'%5B');
	str = str.replace(/\\/g,'%5C');
	str = str.replace(/\]/g,'%5D');
	str = str.replace(/\^/g,'%5E');
	str = str.replace(/\_/g,'%5F');
	str = str.replace(/\{/g,'%7B');
	str = str.replace(/\|/g,'%7C');
	str = str.replace(/\}/g,'%7D');
	
	return str;
}
function check(){
	if(document.form1.id.value==""){
		alert("กรุณาระบุเลขที่สัญญา");
		document.form1.id.focus();
		return false;		
	}else{
		return true;
	}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่านั้น!!");
		document.getElementById("fpayamp").focus();
		return false;
	}
	return true;
}

function maturityChk()
{
	if(document.getElementById("maturityDate_have").checked == true)
	{
		$("#maturityDiv").show();
	}
	else if(document.getElementById("maturityDate_no").checked == true)
	{
		$("#maturityDiv").hide();
	}
	else
	{
		$("#maturityDiv").hide();
	}
}
</script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

<script language="JavaScript">
<!--
function windowOpen() {
var
myWindow=window.open('search2.php','windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
//-->
function showFullDept(){
	var sel = $('#fpayid').val();
	var desc = $('#ref'+ sel).val();
	$("#showDesc").css('color','#ff0000');
	$("#showDesc").html('('+ desc +')');
}


</script>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>
<form method="post" name="form1" action="#">
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<div style="clear:both; padding-bottom: 10px;"></div>
			<fieldset><legend><B>(THCAP) ตั้งหนี้เงินกู้ชั่วคราว</B></legend>
				<div class="ui-widget" align="center">
					<div style="margin:0;padding-bottom:10px;">
						<b>ค้นหาเลขที่สัญญา : </b><input type="text" id="id" name="id" size="40" value="<?php echo $contractID;?>"/>
						<input type="hidden" name="show" value="1">
						<input type="submit" id="btn1" value="ค้นหา" onclick="return check();" /><!--<input name="openPopup" type="button" id="openPopup" onClick="Javascript:windowOpen();" value="ค้นหาจากชื่อผู้กู้หลัก/ร่วม" />-->
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>

<?php
if($show==1){
		//ค้นหาชื่อผู้กู้หลักจาก mysql
			$db1="ta_mortgage_datastore";
			$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
			where  \"contractID\"='$contractID' and \"CusState\" ='0'");
			if($resnamemain=pg_fetch_array($qry_namemain)){
				$name3=trim($resnamemain["thcap_fullname"]);
			}
?>
	<div id="panel" style="padding-top: 10px;">
	<center><div style="width:970px;" ><?php include("Data_contract_detail.php")?></div></center>
	<div style="margin-top:10px;"></div>
	<!-- เพิ่มข้อมูลหนี้คงค้าง -->
		<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFCCCC" align="center">
		<tr align="left">
			<td colspan="3" bgcolor="#FFFFFF" height="25"><img src="images/add.png" width="16" height="16" border="0"> เพิ่มรายการตั้งหนี้</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><div align="center"><font color="red"><b>หมายเหตุ </b>: รายการที่ไม่มี VAT ระบบจะจัดสรรเรื่อง VAT ให้อัตโนมัติ </font><br><br>
				</div>
			  <table width="500" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFCCCC" align="center">
			  <tr bgcolor="#FFCECE">
					<td width="100" align="right"><b>ประเภทหนี้</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8">
						<?php
						//ตรวจสอบว่าเป็นประเภทไหน
						$qrytypecon=pg_query("select \"conType\" from \"thcap_contract\" where \"contractID\"='$contractID'");
						list($conType)=pg_fetch_array($qrytypecon);
						?>
						<select name="fpayid" id="fpayid" onchange="showFullDept();">
						<option value="">-เลือกประเภท-</option>
						<?php
							$qrytype=pg_query("select \"tpID\",\"tpDesc\" from account.\"thcap_typePay\" where \"tpConType\"='$conType' and \"ableInvoice\"='1' ");
							while($restype=pg_fetch_array($qrytype)){
								$tpID=$restype["tpID"];
								$tpDesc=$restype["tpDesc"];
								echo "<option value=$tpID>$tpDesc</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<?php
							$qrytype=pg_query("select \"tpID\",\"tpFullDesc\" from account.\"thcap_typePay\" where \"tpConType\"='$conType' and \"ableInvoice\"='1' ");
							while($restype=pg_fetch_array($qrytype)){
								$tpID=$restype["tpID"];
								$tpFullDesc=$restype["tpFullDesc"];
								
								echo "<input type=\"hidden\" id=\"ref$tpID\" value=\"$tpFullDesc\"/>";
							}
						?>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>เลขอ้างอิงหนี้</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" name="fpayrefvalue" id="fpayrefvalue"> <span id="showDesc" name="showDesc"></span></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>วันที่ตั้งหนี้</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8"><input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate()?>" size="15" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>วันที่ครบกำหนดชำระ</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8">
						<input type="radio" name="maturityDate" id="maturityDate_no" onChange="maturityChk();" value="n"> ไม่มีวันครบกำหนดชำระ
						<input type="radio" name="maturityDate" id="maturityDate_have" onChange="maturityChk();" value="h"> กำหนดวันครบกำหนดชำระ
						<div id="maturityDiv"><input type="text" id="maturityDatepicker" name="maturityDatepicker" value="<?php echo nowDate()?>" size="15" style="text-align:center"></div>
					</td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>จำนวนเงิน</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8" width="300"><input type="text" name="fpayamp" id="fpayamp" onKeyUp="dokeyup(this,event);" onChange="dokeyup(this,event);" value="0.00"><input type="radio" name="vat_inc" id="vat_inc1" value="1" checked>รวม VAT	<input type="radio" name="vat_inc" id="vat_inc2" value="2" >ไม่รวม VAT</td>
				</tr>
				<tr bgcolor="#FFCECE">
					<td align="right"><b>เหตุผล</b></td>
					<td width="10"><b>:</b></td>
					<td bgcolor="#FFE8E8" width="300"><textarea name="remark" id="remark"></textarea></td>
				</tr>
				</table>
				<div style="padding:10px;text-align:center;"><input type="hidden" name="contractID" value="<?php echo $contractID;?>"><input type="button" value="บันทึก" id="submitButton"><input type="button" id="cancelvalue" value="ยกเลิก"></div>
				<br>
			</td>
		</tr>
		</table><br>
<?php } ?>	
	
	
	
	
		<div>
		<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<tr>
				<td>
					<div class="wrapper">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
						<tr bgcolor="#FFFFFF">
							<td colspan="11" align="center" style="font-weight:bold;">รายการขอตั้งหนี้ที่รออนุมัติ</td>
						</tr>
						<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
							<td>เลขที่สัญญา</td>
							<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
							<td>รายละเอียด<br>ค่าใช้จ่าย</td>
							<td>ค่าอ้างอิงของ<br>ค่าใช้จ่าย</td>
							<td>วันที่ตั้งหนี้</td>
							<td>วันที่ครบกำหนดชำระ</td>
							<td>จำนวนหนี้</td>
							<td>ผู้ตั้งหนี้</td>
							<td>วันเวลาที่ตั้งหนี้</td>
							<td>หมายเหตุ</td>
							<td>สถานะ</td>
						</tr>
						<?php
						$qry_fr=pg_query("select * from \"thcap_v_otherpay_debt_realother\" a
							left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
							where \"debtStatus\" = '9' order by \"debtID\" ");
						$nub=pg_num_rows($qry_fr);
						while($res_fr=pg_fetch_array($qry_fr)){
							$debtID=$res_fr["debtID"];
							$contractID1=$res_fr["contractID"];
							$typePayID=$res_fr["typePayID"];
							$typePayRefValue=$res_fr["typePayRefValue"];
							$typePayRefDate=$res_fr["typePayRefDate"];
							$typePayAmt=$res_fr["typePayAmt"];
							$fullname=$res_fr["fullname"];
							$doerStamp=$res_fr["doerStamp"];
							$debtDueDate=$res_fr["debtDueDate"]; // วันที่ครบกำหนดชำระ
							
							// หารายละเอียดค่าใช้จ่ายนั้นๆ
							$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
							while($res_tpDesc = pg_fetch_array($qry_tpDesc))
							{
								$tpDescShow = $res_tpDesc["tpDesc"];
							}
							
							$i+=1;
							if($i%2==0){
								echo "<tr class=\"odd\" align=center>";
							}else{
								echo "<tr class=\"even\" align=center>";
							}
						?>
							<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID1?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID1;?></u></font></span></td>
							<td><?php echo $typePayID; ?></td>
							<td><?php echo $tpDescShow; ?></td>
							<td><?php echo $typePayRefValue; ?></td>
							<td><?php echo $typePayRefDate; ?></td>
							<td><?php if($debtDueDate != ""){echo $debtDueDate;}else{echo "ไม่มีวันครบกำหนดชำระ";} ?></td>
							<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
							<td align="left"><?php echo $fullname; ?></td>
							<td><?php echo $doerStamp; ?></td>
							<td align="center"><span onclick="javascript:popU('show_remark.php?debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
							<td align="center">รออนุมัติ</td>
						</tr>
						<?php
						} //end while
						if($nub == 0){
							echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
						}
						?>
						</table>
					</div>
				</td>
			</tr>
			</table>
	</div><br><br>
	
	
<?php if($show==1){ ?>
	
		<table width="950" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
		<tr align="left">
			<td colspan="10" bgcolor="#FFFFFF" height="25">เลขที่สัญญา : 
			<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span>
			&nbsp;&nbsp;ชื่อผู้กู้หลัก : <font color="red"><?php echo $name3;?></font>
			</td>
		</tr>
		<tr align="center" bgcolor="#0B98CE" >
			<th width="70">รหัสประเภท<br>ค่าใช้จ่าย</th>
			<th>รายการ</th>
			<th width="90">ค่าอ้างอิง<br>ของค่าใช้จ่าย</th>
			<th width="70">วันที่ตั้งหนี้</th>
			<th width="70">วันที่ครบกำหนดชำระ</th>
			<th width="110">จำนวนหนี้ (บาท)</th>
			<th width="180">ผู้ตั้งหนี้</th>
			<th width="120">วันเวลาตั้งหนี้</th>
			<th>หมายเหตุ</th>
			<th>สถานะของหนี้</th>
		</tr>
		<?php
		$qry=pg_query("select * from thcap_v_otherpay_debt_realother a
		left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\" where \"contractID\"='$contractID' order by \"debtID\" DESC");
		$numrows=pg_num_rows($qry);
		$i=0;
		$sum=0;
		while($result=pg_fetch_array($qry)){
			$typePayID=$result["typePayID"];
			$typePayRefValue=$result["typePayRefValue"];
			$typePayRefDate=$result["typePayRefDate"];
			$typePayAmt=$result["typePayAmt"];
			$fullname=$result["fullname"];
			$doerStamp=$result["doerStamp"];
			$doerStamp=substr($doerStamp,0,19);
			$doerID=$result["doerID"];
			$debtStatus=$result["debtStatus"];
			$debtID=$result["debtID"];
			$debtDueDate=$result["debtDueDate"]; // วันที่ครบกำหนดชำระ
			
			if($debtDueDate != ""){$debtDueDateText = $debtDueDate;}else{$debtDueDateText = "ไม่มีวันครบกำหนดชำระ";}
			
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDescview = trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			
			if($doerID=="000"){
				$fullname="อัตโนมัติโดยระบบ";
			}
			
			if($debtStatus == "0"){
				$txtdebt="ยกเลิก";
			}else if($debtStatus == "1"){
				$txtdebt="ยังไม่จ่าย/จ่ายไม่ครบ";
			}else if($debtStatus == "2"){
				$txtdebt="จ่ายครบแล้ว";
			}else if($debtStatus == '5'){
				$txtdebt = 'ลดหนี้เป็น 0.00';
			}else if($debtStatus == "9"){
				$txtdebt="รออนุมัติ";
			}
			$i+=1;
			
			if($debtStatus=="1"){
				if($i%2==0){
					echo "<tr bgcolor=#FFD2D2 align=\"center\">";
				}else{
					echo "<tr bgcolor=#FFC0C0 align=\"center\">";
				}
			}else{
				if($i%2==0){
					echo "<tr class=\"odd\" align=\"center\">";
				}else{
					echo "<tr class=\"even\" align=\"center\">";
				}
			}
			
			if($debtStatus=="2")
			{
				echo "
					<td>$typePayID</td>
					<td>$tpDescview</td>
					<td>$typePayRefValue</td>
					<td>$typePayRefDate</td>
					<td>$debtDueDateText</td>
					<td align=right>".number_format($typePayAmt,2)."</td>
					<td align=left>$fullname</td>
					<td>$doerStamp</td>
					<td align=\"center\"><span onclick=\"javascript:popU('show_remark.php?debtID=$debtID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>
					<td><span onclick=\"javascript:popU('View_Receipt.php?debtID=$debtID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')\" style=\"cursor:pointer;color:#0000FF\"><u>$txtdebt</u></span></td>
				</tr>
				";
			}
			else
			{
				echo "
					<td>$typePayID</td>
					<td>$tpDescview</td>
					<td>$typePayRefValue</td>
					<td>$typePayRefDate</td>
					<td>$debtDueDateText</td>
					<td align=right>".number_format($typePayAmt,2)."</td>
					<td align=left>$fullname</td>
					<td>$doerStamp</td>
					<td align=\"center\"><span onclick=\"javascript:popU('show_remark.php?debtID=$debtID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>
					<td>$txtdebt</td>
				</tr>
				";
			}
			
			$sum+=$typePayAmt;
		}
		if($numrows==0){
			echo "<tr><td colspan=10 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบข้อมูลหนี้คงค้าง-</b></td></tr>";
		}else{
			echo "<tr bgcolor=\"#CCCCCC\" align=right><td colspan=5 align=right><b>รวม</b></td><td><b>".number_format($sum,2)."</b></td><td colspan=4></td></tr>";
		}
		?>
		</table>
		
		
	</div>
<?php
}
?>
<br>
<div width="1100px">
	<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">การตั้งหนี้เงินกู้ที่ได้ทำการอนุมัติ 30 รายการล่าสุด<input type="button" value="แสดงประวัติทั้งหมด" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
				<td>รายละเอียด<br>ค่าใช้จ่าย</td>
				<td>ค่าอ้างอิงของ<br>ค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>วันที่ครบกำหนดชำระ</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ตั้งหนี้</td>
				<td>วันเวลาตั้งหนี้ </td>
				<td>ผู้อนุมัติหนี้ </td>
				<td>วันเวลาทำรายการอนุมัติ </td>
				<td>เหตุผล</td>
				<td>ผลการอนุมัติ</td>				
			</tr>
			<?php
			$qry_fr1=pg_query("select * from \"thcap_temp_otherpay_debt\" a
				left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
				where \"debtStatus\" != '9' and \"appvID\" != '000' order by \"appvStamp\" DESC limit 30 ");
			$nub=pg_num_rows($qry_fr1);
			while($res_fr=pg_fetch_array($qry_fr1)){
				$debtIDshow = $res_fr["debtID"];
				$doerID=$res_fr["doerID"];
				$doerStamp=$res_fr["doerStamp"];
				$appvID=$res_fr["appvID"];
				$appvStamp=$res_fr["appvStamp"];
				$debtStatus=$res_fr["debtStatus"];
				$fullname=$res_fr["fullname"];
				$contractID=$res_fr["contractID"];
				$typePayID=$res_fr["typePayID"];
				$typePayRefValue=$res_fr["typePayRefValue"];
				$typePayRefDate=$res_fr["typePayRefDate"];
				$typePayAmt=$res_fr["typePayAmt"];
				$debtDueDate=$res_fr["debtDueDate"]; // วันที่ครบกำหนดชำระ
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDesc = $res_tpDesc["tpDesc"];
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
				}else{
					echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
				}
				
			if($debtStatus == '0'){
				$appstatus = 'ยกเลิก';
			}else if($debtStatus == '1'){
				$appstatus = 'อนุมัติ';
			}else if($debtStatus == '2'){
				$appstatus = 'อนุมัติ (จ่ายครบแล้ว)';
			}else if($debtStatus == '3'){
				$appstatus = 'waive รายการ (ยกเว้นหนี้)';
			}else if($debtStatus == '4'){
				$appstatus = 'ยกเลิกใบเสร็จ';
			}else{
				$appstatus = 'ไม่สามารถระบุได้';
			}		
	
			$sqlappuser = pg_query("SELECT  fullname  FROM \"Vfuser\" where id_user = '$appvID'");
			$reappuser = pg_fetch_result($sqlappuser,0);
	
				
			?>	
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td><?php echo $typePayID; ?></td>
				<td><?php echo $tpDesc; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td><?php if($debtDueDate != ""){echo $debtDueDate;}else{echo "ไม่มีวันครบกำหนดชำระ";} ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $doerStamp; ?></td>
				<td align="left"><?php echo $reappuser; ?></td>
				<td align="center"><?php echo $appvStamp; ?></td>
				<td><span onclick="javascript:popU('show_remark.php?debtID=<?php echo $debtIDshow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=300')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
				<td align="center"><?php echo $appstatus; ?></td>
			</tr>
			<?php } ?>
			<tr bgcolor="#D6D6D6">
				<td colspan="13" align="right" >จำนวนแสดง : <?php echo $nub; ?>  รายการ</td>
			</tr>
			</table><br>
	</div>
</div>
</body>
</html>