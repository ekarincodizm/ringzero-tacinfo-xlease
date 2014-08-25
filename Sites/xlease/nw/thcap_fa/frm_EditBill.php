<?php
session_start();
include("../../config/config.php");
$prebillID=pg_escape_string($_GET["prebillID"]);

//ตรวจสอบว่ารายการนี้รออนุมัติอยู่หรือไม่ ถ้าใช่ ให้แจ้ง
$qrychk=pg_query("select \"prebillIDMaster\" from thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillID' and \"statusApp\"='2' and \"edittime\"<>'0'");
if(pg_num_rows($qrychk)>0){ //แสดงว่ามีรายการรออนุมัติอยู่
	echo "<div align=center><h2>รายการนี้รออนุมัติอยู่ กรุณาทำรายการหลังจากอนุมัติแล้ว</h2></div>";
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) แก้ไขรายละเอียดบิลขอสินเชื่อ</title>   

	<script language="javascript" type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">
	<script language="javascript" type="text/javascript" src="js/jquery.coolfieldset.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.coolfieldset.css" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<!---- หน้าต่าง Popup รูปภาพ ---->
	<!-- Add jQuery library -->
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
<script>
$(document).ready(function(){
	$(".address").hide(); //เริ่มต้นซ่อนสถานที่รับเช็ค หากต้องการเปลี่ยนแปลงสถานะที่ ส่วนนี้จะแสดง
	
	$(".pdforpic").fancybox({
	   minWidth: 500,
	   maxWidth: 800,
	   'height' : '600',
	   'autoScale' : true,
	   'transitionIn' : 'none',
	   'transitionOut' : 'none',
	   'type' : 'iframe'
	});
	
	$("#changeadd").click(function(){
		if($("#changeadd").is(':checked')){
			$(".address").show();
		}else{
			$(".address").hide();
		}
	});
	
	$("#dateInvoice").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#dateBill").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#dateAssign1").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#userSalebill").autocomplete({
        source: "listcustype3.php",
        minLength:1
    });
	
	$("#userDebtor").autocomplete({
        source: "listcustype3.php",
        minLength:1
    });
	$("#searchplace").autocomplete({
        source: "listcustype3.php",
        minLength:1
    });
});
//function ลบไฟล์
function del(code){
	if(confirm('ยืนยันลบไฟล์นี้')==true){
		$("#file"+code).remove();		
	}
}
function checkuser(){
	if($('#userSalebill').val()!=""){
		$.post("checkuser.php",{
			id : document.form1.userSalebill.value			
		},
		function(data){	
			if(data=='0'){
				$("#statusSale").val('0');
			}else{
				$("#statusSale").val('1');
			}
		});			
	}
}
function checkuserDebtor(){
	if($('#userDebtor').val()!=""){
		$.post("checkuser.php",{
			id : document.form1.userDebtor.value			
		},
		function(data){	
			if(data=='0'){
				$("#statusDebt").val('0');
			}else{
				$("#statusDebt").val('1');
			}
		});			
	}
}
/*function sentvalue(){
	$.post("address.php",{
			id : document.form1.userDebtor.value		
		},
		function(data){		
			if(data=='-'){
				$("#statusDebt").val('0');
			}else{
				$("#statusDebt").val('1');
				$("#searchplace").val(document.form1.userDebtor.value);
				$("#placeReceiveChq1").text(data);
			}
		}
	);
}*/
function sentvalue2(){
	$.post("address.php",{
			id : document.form1.searchplace.value			
		},
		function(data){		
			$("#placeReceiveChq1").text(data);
		}
	);
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<?php
//ค้นหาข้อมูลบิลเบื้องต้น
$qrydata = pg_query("select \"dateInvoice\",\"numberInvoice\",\"userDebtor\",\"userDebtorName\",\"userSalebill\",
			\"userSalebillName\",\"totalTaxInvoice\",\"dateBill\",\"dateAssign\",\"placeReceiveChq\",\"taxInvoice\",\"note\",\"edittime\"
			from vthcap_fa_prebill_edit where \"prebillID\"='$prebillID'");
if($resdata=pg_fetch_array($qrydata)){
	$dateInvoice = $resdata["dateInvoice"]; // วันที่ใบแจ้งหนี้
	$numberInvoice = $resdata["numberInvoice"]; // เลขที่ใบแจ้งหนี้
	$userDebtor= $resdata["userDebtor"]; // รหัสลูกหนี้
	$userDebName = $resdata["userDebtorName"]; // ชื่อลูกหนี้
	$userSalebill = $resdata["userSalebill"]; // รหัสผู้ขาย
	$userSaleName = $resdata["userSalebillName"]; // ชื่อผู้ขาย
	$totalTaxInvoice = $resdata["totalTaxInvoice"]; // จำนวนเงินในบิล
	$dateBill = $resdata["dateBill"]; // วันที่วางบิล
	$dateAssign = $resdata["dateAssign"]; // วันที่นัดรับเช็ค
	$placeReceiveChq = $resdata["placeReceiveChq"]; // สถานที่รับเช็ค
	$taxInvoice = $resdata["taxInvoice"]; // จำนวนเงินที่นัดรับเช็คครั้งที่ 1
	$note = $resdata["note"]; // หมายเหตุ
	$edittime = $resdata["edittime"]; // ครั้งล่าสุดที่ทำรายการ
	
	$userSalebill=$userSalebill."#".$userSaleName; //ชื่อผู้ขาย
	$userDebtor=$userDebtor."#".$userDebName; //ชื่อลูกหนี้
}
?>
<form name="form1" method="post" action="process_fa.php" enctype="multipart/form-data">
<div style="width:850px;margin:0 auto;">
<fieldset><legend><b>แก้ไขข้อมูล</b></legend>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>       
		<div class="header"><h1></h1></div>
		<div class="wrapper">			
			<div style="padding:5px;"></div>
			<!--เพิ่มบิลขอสินเชื่อ-->
			<div>
			<table width="60%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#CDB7B5">
			<tr><td colspan="3" bgcolor="#EED5D2">&nbsp;</td></tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" width="156"><b>ผู้ขายบิล</b></td>
				<td width="10" align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="hidden" name="statusSale" id="statusSale"><input type="text" size="40" name="userSalebill" id="userSalebill" value="<?php echo $userSalebill; ?>"><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>ชื่อลูกหนี้ของผู้ขายบิล</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="hidden" name="statusDebt" id="statusDebt"><input type="text" size="40" name="userDebtor" id="userDebtor" value="<?php echo $userDebtor; ?>"><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>วันที่ใบแจ้งหนี้</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="dateInvoice" id="dateInvoice" size="15" value="<?php echo $dateInvoice;?>"> <b>(ปี ค.ศ.)</b><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>เลขที่ใบแจ้งหนี้</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="numberInvoice" id="numberInvoice" value="<?php echo $numberInvoice;?>"><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>ยอดในใบแจ้งหนี้รวมภาษี</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="totalTaxInvoice" id="totalTaxInvoice" style="text-align:right" value="<?php echo $totalTaxInvoice;?>" onKeyPress="checknumber(event);" > <b>บาท</b><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>วันที่วางบิล</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="dateBill" id="dateBill" size="15"value="<?php echo $dateBill; ?>"> <b>(ปี ค.ศ.)</b><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td colspan="3" align="center">
					<div id='TxtGroup1' style="padding-top:5px;">
					<div id="TxtDiv1">
					<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="" align="center">
						<tr>
						<td align="right" width="155"><b>วันที่นัดรับเช็ค</b></td>
						<td align="center">:</td>
						<td><input type="hidden" name="prebillOld[]" id="prebillOld1" value="<?php echo $prebillID; ?>"><input type="text" name="dateAssign[]" id="dateAssign1" size="15" value="<?php echo $dateAssign; ?>"> <b>(ปี ค.ศ.)</b><font color="red"><b>*</b></font>
						<input type="button" value="+ เพิ่ม" id="addButton1"><input type="button" value="- ลบ" id="removeButton1"></td>
						</tr>
						<tr><td align="right" width="155">จำนวนเงินครั้งที่ 1</td>
						<td align="center">:</td><td><input type="text" name="recmoney[]" id="recmoney1" style="text-align:right" onKeyPress="checknumber(event);" value="<?php echo $taxInvoice; ?>"> <font color="red"><b>*</b></font></td></tr>
					</table>
					</div>
					</div>
					
				</td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" valign="top"><b>สถานที่รับเช็คปัจจุบัน</b></td>
				<td align="center" valign="top">:</td>
				<td bgcolor="#FFE4E1"><textarea name="placeReceiveChq" id="placeReceiveChq" cols="30" rows="3" readonly><?php echo $placeReceiveChq; ?></textarea><input type="checkbox" name="changeadd" id="changeadd" checked value="1">เปลี่ยนสถานที่</td>
			</tr>
			<tr align="left" bgcolor="#EED5D2" class="address">
				<td align="right"><b>เปลี่ยนสถานที่ (ค้นจากชื่อ)</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="searchplace" id="searchplace" size="50" onfocus="javascript : sentvalue2()" onblur="javascript : sentvalue2()"><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2" class="address">
				<td colspan="2"></td>
				<td bgcolor="#FFE4E1">
					<table>
						<tr><td valign="top"><input type="radio" name="checkplace" id="checkplace1" value="1" checked>ที่อยู่บริษัท</td><td><textarea name="placeReceiveChq1" id="placeReceiveChq1" cols="30" rows="3" readonly></textarea></td></tr>
						<tr><td valign="top"><input type="radio" name="checkplace" id="checkplace2" value="2">ที่อยู่อื่นๆ</td><td><textarea name="placeReceiveChq2" id="placeReceiveChq2" cols="30" rows="3"></textarea></td></tr>
					</table>
				</td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" valign="top"><b>หมายเหตุ</b></td>
				<td align="center" valign="top">:</td>
				<td bgcolor="#FFE4E1" valign="top"><textarea name="note" id="note" cols="30" rows="3"><?php echo $note;?></textarea><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" valign="top"><b>ไฟล์บิลที่มี</b></td>
				<td align="center" valign="top">:</td>
				<td bgcolor="#FFE4E1" valign="top">
				<?php
				//แสดงไฟล์บิลทั้งหมดที่มี
				$qryfile=pg_query("SELECT \"file\"  FROM thcap_fa_prebill_file WHERE \"prebillID\"='$prebillID' and \"edittime\"='$edittime' ORDER BY \"file\"");
				$i=1;
				while($resfile=pg_fetch_array($qryfile)){
					$filename=$resfile["file"]; //ชื่อบิล
					echo "<div id=\"file$i\"><input type=\"hidden\" name=\"fileold[]\" value=\"$filename\"><a class=\"pdforpic\" href=\"../upload/fa_prebill/$filename\" data-fancybox-group=\"gallery\" title=\"แสดงบิล\"><font color=blue><u>ไฟล์บิล $i</u></font></a>&nbsp;&nbsp;&nbsp;<img src=\"images/delete.gif\" style=\"cursor:pointer\" onclick=\"del($i)\"></div>";
					$i++;
				}
				?>
				
				</td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td colspan="3" align="center">
					<div id='TextGroup1'>
					<div id="TextDiv1">
					<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="" align="center">
						<td align="right" width="155"><b>เพิ่มไฟล์สแกนบิล</b></td>
						<td align="center">:</td>
						<td bgcolor="#FFE4E1"><input type="file" name="my_field[]" id="upload1"><input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton"></td>
					</table>
					</div>
					</div>
				</td>
			</tr>
			<tr><td colspan="3" bgcolor="#EED5D2">&nbsp;</td></tr>
			<tr align="center">
				<td colspan=3><input type="hidden" name="edittime" value="<?php echo $edittime; ?>"><input type="hidden" name="prebillID" value="<?php echo $prebillID; ?>"><input type="hidden" name="method" value="edit"><input name="submit" type="submit" id="submitbutton" value="บันทึก"><input type="reset" value="ยกเลิก"/></td>
			</tr>
			</table>
			</div>
			<!--จบรายการเพิ่มบิลขอสินเชื่อ-->
		</div>
	</td>
</tr>
</table>  
</fieldset> 
</div>
</form>
<script>
$(document).ready(function(){
var counter=1;
	$('#addButton').click(function(){
    counter++;
    console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextDiv' + counter);
    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr height="30" bgcolor="#EED5D2">'
	+ '		<td align="right" width="160">'+ counter +'</td>'
	+ '		<td colspan="2" bgcolor="#FFE4E1"><input type="file" name="my_field[]" id="upload'+ counter +'"><font color=red><b>*</b></font></td>'
	+ '	</tr>'
	+ '	</table>'
	
	

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextGroup1");

	
    });
	$("#removeButton").click(function(){
        if(counter==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextDiv" + counter).remove();
        counter--;
        console.log(counter);
    });
	
	var countadd=1;
	<?php 	
	//แสดงข้อมูลวันที่วางบิลและจำนวนเงินกรณีมีมากกว่า 1 รายการ
	$qryeach=pg_query("SELECT \"prebillID\",\"dateAssign\",\"taxInvoice\"  FROM thcap_fa_prebill_temp 
	WHERE \"prebillIDMaster\"='$prebillID' and \"prebillID\" <> '$prebillID' and \"edittime\"=(SELECT MAX(\"edittime\")  FROM thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillID' and \"statusApp\"='1') and \"stsprocess\"<>'D' ORDER BY \"prebillID\"");
	$numeach=pg_num_rows($qryeach);
	while($reseach=pg_fetch_array($qryeach)){			
		$prebillID_each=$reseach["prebillID"];
		$dateAssign_each=$reseach["dateAssign"];
		$taxInvoice_each=$reseach["taxInvoice"];
		?>
		countadd++;
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TxtDiv' + countadd);
		table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
		+ '	<tr height="30" bgcolor="#EED5D2">'
		+ '		<td align="right" width="160">ครั้งที่ '+ countadd +'</td>'
		+ '		<td colspan="2" bgcolor="#FFE4E1"><input type="hidden" name="prebillOld[]" id="prebillOld'+ countadd +'" value="<?php echo $prebillID_each; ?>"><input type="text" name="dateAssign[]" id="dateAssign'+ countadd +'" size="15" value="<?php echo $dateAssign_each;?>"> จำนวนเงิน <input type="text" name="recmoney[]" id="recmoney'+ countadd +'" style="text-align:right" value="<?php echo $taxInvoice_each;?>" onKeyPress="checknumber(event);" > <font color="red"><b>*</b></font></td>'
		+ '	</tr>'
		+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TxtGroup1");
		
		$("#dateAssign"+countadd).datepicker({
			showOn: 'button',
			buttonImage: 'calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});

	
	<?php
	}
	?>
	$('#addButton1').click(function(){
	$("#showrecmoney").show();
    countadd++;
    console.log(countadd);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TxtDiv' + countadd);
    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr height="30" bgcolor="#EED5D2">'
	+ '		<td align="right" width="160">ครั้งที่ '+ countadd +'</td>'
	+ '		<td colspan="2" bgcolor="#FFE4E1"><input type="text" name="dateAssign[]" id="dateAssign'+ countadd +'" size="15"> จำนวนเงิน <input type="text" name="recmoney[]" id="recmoney'+ countadd +'" style="text-align:right" onKeyPress="checknumber(event);" > <font color="red"><b>*</b></font></td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TxtGroup1");
		
		$("#dateAssign"+countadd).datepicker({
			showOn: 'button',
			buttonImage: 'calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});

	
    });
	$("#removeButton1").click(function(){
		if(countadd==2){
			$("#showrecmoney").hide(); //ซ่อนจำนวนเงินครั้งที่ 1 ด้วย
		}
		
        if(countadd==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TxtDiv" + countadd).remove();
        countadd--;
        console.log(countadd);
    });
	
	$("#submitbutton").click(function(){
		
		checkuser();
		checkuserDebtor();
		
		var chkConfirm = confirm("ยืนยันการบันทึกข้อมูล")
		if(chkConfirm == false)
		{
			return false;
		}

		if(document.form1.userSalebill.value==""){
			alert("กรุณาระบุผู้ขายบิล");
			document.form1.userSalebill.focus();
			return false;
		}else{
			if(document.form1.statusSale.value=="0"){
				alert("ชื่อผู้ขายบิลไม่ถูกต้อง กรุณาระบุใหม่");
				document.form1.userSalebill.focus();
				return false;
			}
		}
		
		if(document.form1.userDebtor.value==""){
			alert("กรุณาระบุชื่อลูกหนี้");
			document.form1.userDebtor.focus();
			return false;
		}else{
			if(document.form1.statusDebt.value=="0"){
				alert("ชื่อลูกหนี้ไม่ถูกต้อง กรุณาระบุใหม่");
				document.form1.userDebtor.focus();
				return false;
			}
		}
		
		if(document.form1.dateInvoice.value==""){
			alert("กรุณาระบุวันที่ใบแจ้งหนี้");
			document.form1.dateInvoice.focus();
			return false;
		} 
		if(document.form1.numberInvoice.value==""){
			alert("กรุณาระบุเลขที่ใบแจ้งหนี้");
			document.form1.numberInvoice.focus();
			return false;
		}
		if(document.form1.totalTaxInvoice.value==""){
			alert("กรุณาระบุยอดในใบแจ้งหนี้รวมภาษี");
			document.form1.totalTaxInvoice.focus();
			return false;
		}
		if(document.form1.dateBill.value==""){
			alert("กรุณาระบุวันที่วางบิล");
			document.form1.dateBill.focus();
			return false;
		}
		if(document.getElementById("dateAssign1").value==""){
			alert("กรุณาระบุวันที่นัดรับเช็ค");
			document.getElementById("dateAssign1").focus();
			return false;
		}

		if(countadd>1){
			var totalmoney=parseFloat($("#totalTaxInvoice").val());
			var summoney=0;
			for( i=1; i<=countadd; i++ ){
				var dateass=$("#dateAssign"+i).val(); //วันที่นัดรับเช็ค
				var recmoney=$("#recmoney"+i).val(); //จำนวนเงิน
				
				if (dateass == ""){
					alert('กรุณาระบุวันที่นัดรับเช็คครั้งที่ '+i);
					$('#dateAssign'+ i).focus();
					return false;
				}
				if (recmoney == ""){
					alert('กรุณาระบุจำนวนเงินครั้งที่ '+i);
					$('#recmoney'+ i).focus();
					return false;
				}
				summoney=parseFloat(summoney)+parseFloat(recmoney);
			}

			if(summoney>totalmoney){
				alert('จำนวนเงินที่นัดรับเช็คจะต้องไม่เกินยอดในใบแจ้งหนี้รวมภาษี ');
				return false;
			}
			
		}
		else{
			var totalmoney=parseFloat($("#totalTaxInvoice").val());//ยอดในใบแจ้งหนี้รวมภาษี
			var recmoney=parseFloat($("#recmoney1").val()); //จำนวนเงินครั้งที่ 1
			if(totalmoney != recmoney){
				alert('จำนวนเงินที่นัดรับเช็คจะต้องท่ากับยอดในใบแจ้งหนี้รวมภาษี ');
				return false;
			}
		}
		//ถ้ามีการเปลี่ยนแปลงที่อยู่
		if($("#changeadd").is(':checked')){
			if(document.getElementById("checkplace1").checked==true){
				if(document.form1.placeReceiveChq1.value==""){
					alert("กรุณาระบุที่อยู่บริษัท");
					return false;
				}
			}
			if(document.getElementById("checkplace2").checked==true){
				if(document.form1.placeReceiveChq2.value==""){
					alert("กรุณาระบุที่อยู่อื่นๆ");
					document.form1.placeReceiveChq2.focus();
					return false;
				}
			}
		}
		if(document.form1.note.value==""){
			alert("กรุณาระบุหมายเหตุ");
			document.form1.note.focus();
			return false;
		}
		
		if(counter>1){
			for( i=1; i<=counter; i++ ){
				var upfile=$("#upload"+i).val();
				if (upfile == ""){
					alert('กรุณาระบุไฟล์ที่สแกนบิล'+i);
					$('#upload'+ i).focus();
					return false;
				}
			}
		}
	});
	
	if($("#changeadd").is(':checked')){
		$(".address").show();
	}else{
		$(".address").hide();
	}
});
</script>      
</body>
</html>
<?php
}
?>