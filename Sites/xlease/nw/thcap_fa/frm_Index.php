<?php
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) FA เพิ่มบิลขอสินเชื่อ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$("#waitapprove").hide();
	$("#text1").show();
	$("#text2").hide();
	$("#showrecmoney").hide();
	
	$("#waitclick").click(function(){	
		$("#waitapprove").show();
		$("#text1").hide();
		$("#text2").show();	
	});
	$("#waitclick1").click(function(){	
		$("#waitapprove").hide();
		$("#text1").show();
		$("#text2").hide();	
	});

	$("#Image1").click(function(){	
		$("#waitapprove").show();
		$("#text1").hide();
		$("#text2").show();
	});
	
	$("#Image2").click(function(){	
		$("#waitapprove").hide();
		$("#text1").show();
		$("#text2").hide();	
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
        source: "listcustype2.php",
        minLength:1
    });
	
	$("#userDebtor").autocomplete({
        source: "listcustype2.php",
        minLength:1
    });
	$("#searchplace").autocomplete({
        source: "listcusall.php",
        minLength:1
    });
	
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
				alert('จำนวนเิงินที่นัดรับเช็คจะต้องไม่เกินยอดในใบแจ้งหนี้รวมภาษี ');
				return false;
			}
			
		}
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
		if(document.form1.note.value==""){
			alert("กรุณาระบุหมายเหตุ");
			document.form1.note.focus();
			return false;
		}
		if(document.getElementById("upload1").value==""){
			alert("กรุณาระบุไฟล์สแกนบิล");
			document.getElementById("upload1").focus();
			return false;
		}
		
		if(counter>0){
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
});
function checkuser(){
	if(document.form1.userSalebill.value!=""){
		$.post("checkuser.php",{
			id : document.form1.userSalebill.value			
		},
		function(data){		
			if(data==0){
				$("#statusSale").val('0');
			}else{
				$("#statusSale").val('1');
			}
		});			
	}
}
function sentvalue(){
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
}
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
<body onload="MM_preloadImages('images/folderopen.gif','images/folderadd.gif')">
<form name="form1" method="post" action="process_fa.php" enctype="multipart/form-data">
<table width="850" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>       
		<div class="header"><h1></h1></div>
		<div class="wrapper">
			<div align="center"><h2>(THCAP) FA เพิ่มบิลขอสินเชื่อ</h2></div>
			<div align="right"><input name="button" type="button" onclick="window.close();" value=" X ปิด " /></div>
			<!-- กรณีแสดงรายการรออนุมัติ -->
			<?php
			$qry_fr=pg_query("SELECT \"numberInvoice\",\"prebillIDMaster\", \"userSalebillName\" as \"userSalebill\",\"userDebtorName\" as \"userDebtor\",\"addUserName\"
				  FROM \"vthcap_fa_prebill_temp\" 
				  where \"statusApp\"='2' group by \"numberInvoice\",\"prebillIDMaster\", \"userSalebillName\",\"userDebtorName\",\"addUserName\"");
				$nub=pg_num_rows($qry_fr);
				
			?>
			<div id="text1"><!--ให้แสดงส่วนนี้โดย default ไว้ และแสดงกรณีเลือกปิดรายการที่รออนุมัติ-->
				<img src="images/folderadd.gif" width="19" height="16" id="Image1" style="cursor:pointer;" onmouseover="MM_swapImage('Image1','','images/folderopen.gif',1)" onmouseout="MM_swapImgRestore()">
				<span id="waitclick" style="cursor:pointer;"><b>แสดงรายการที่รออนุมัติ (รอ <font color="red"><?php echo $nub;?></font> รายการ)</b></span>				
			</div>
			<div id="text2"><!--ให้แสดงส่วนนี้ถ้าเลือกแสดงรายการที่รออนุมัติ-->
				<img src="images/folderopen.gif" width="19" height="16" id="Image2">
				<span id="waitclick1" style="cursor:pointer;"><b>ปิดรายการที่รออนุมัติ</b></span>				
			</div>
			<div id="waitapprove">
				<fieldset>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFEAEA">
				<tr bgcolor="#FFFFFF">
					<td colspan="7" style="font-weight:bold;">รายการที่รออนุมัติ</td>
				</tr>
				<tr style="font-weight:bold;color:#FFFFFF" valign="middle" bgcolor="#8B8386" align="center">
					<td>เลขที่ใบแจ้งหนี้</td>
					<td>ชื่อผู้ขายบิล</td>
					<td>ชื่อลูกหนี้ของผู้ขายบิล</td>	
					<td>ผลการอนุมัติ</td>
					<td>รายละเอียด</td>
				</tr>
				<?php
				while($res_fr=pg_fetch_array($qry_fr)){
					$numberInvoice=$res_fr["numberInvoice"];
					$userSalebill=$res_fr["userSalebill"];
					$userDebtor=$res_fr["userDebtor"];
					$fullname=$res_fr["addUserName"];
					$prebillIDMaster=$res_fr["prebillIDMaster"];
					
												
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#FFF0F5\" align=center>";
					}else{
						echo "<tr bgcolor=\"#EEE0E5\" align=center>";
					}
					
					echo "
						<td>$numberInvoice</td>
						<td align=left>$userSalebill</td>
						<td align=\"left\">$userDebtor</td>
						<td>รออนุมัติ</td>
						<td><span onclick=\"javascript:popU('frm_ShowDetail.php?prebillIDMaster=$prebillIDMaster','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>
					</tr>
					";
				
				} //end while
				if($nub == 0){
					echo "<tr><td colspan=6 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
				}
				?>
				</table>
				</fieldset>
			
			</div>
			<!--จบรายการที่รออนุมัติ-->
			<div style="padding:5px;"></div>
			<!--เพิ่มบิลขอสินเชื่อ-->
			<div>
			<fieldset><legend><B>เพิ่มบิลขอสินเชื่อ</B></legend>
			<table width="60%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#CDB7B5">
			<tr><td colspan="3" bgcolor="#EED5D2">&nbsp;</td></tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" width="156"><b>ผู้ขายบิล</b></td>
				<td width="10" align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="hidden" name="statusSale" id="statusSale"><input type="text" size="40" name="userSalebill" id="userSalebill" onfocus="javascript : checkuser()" ><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>ชื่อลูกหนี้ของผู้ขายบิล</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="hidden" name="statusDebt" id="statusDebt"><input type="text" size="40" name="userDebtor" id="userDebtor" onfocus="javascript : sentvalue()" ><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>วันที่ใบแจ้งหนี้</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="dateInvoice" id="dateInvoice" size="15"> <b>(ปี ค.ศ.)</b><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>เลขที่ใบแจ้งหนี้</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="numberInvoice" id="numberInvoice"><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>ยอดในใบแจ้งหนี้รวมภาษี</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="totalTaxInvoice" id="totalTaxInvoice" style="text-align:right" onKeyPress="checknumber(event);" > <b>บาท</b><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>วันที่วางบิล</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="dateBill" id="dateBill" size="15"> <b>(ปี ค.ศ.)</b><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td colspan="3" align="center">
					<div id='TxtGroup1' style="padding-top:5px;">
					<div id="TxtDiv1">
					<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="" align="center">
						<tr>
						<td align="right" width="155"><b>วันที่นัดรับเช็ค</b></td>
						<td align="center">:</td>
						<td><input type="text" name="dateAssign[]" id="dateAssign1" size="15"> <b>(ปี ค.ศ.)</b><font color="red"><b>*</b></font>
						<input type="button" value="+ เพิ่ม" id="addButton1"><input type="button" value="- ลบ" id="removeButton1"></td>
						</tr>
						<tr id="showrecmoney"><td align="right" width="155">จำนวนเงินครั้งที่ 1</td>
						<td align="center">:</td><td><input type="text" name="recmoney[]" id="recmoney1" style="text-align:right" onKeyPress="checknumber(event);"> <font color="red"><b>*</b></font></td></tr>
					</table>
					</div>
					</div>
					
				</td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>สถานที่รับเช็ค (ค้นจากชื่อ)</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="searchplace" id="searchplace" size="40" onkeyup="javascript : sentvalue2()" onblur="javascript : sentvalue2()"><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
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
				<td bgcolor="#FFE4E1" valign="top"><textarea name="note" id="note" cols="30" rows="3"></textarea><font color="red"><b>*</b></font></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td colspan="3" align="center">
					<div id='TextGroup1'>
					<div id="TextDiv1">
					<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="" align="center">
						<td align="right" width="155"><b>ไฟล์สแกนบิล</b></td>
						<td align="center">:</td>
						<td bgcolor="#FFE4E1"><input type="file" name="my_field[]" id="upload1"><input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton"><font color="red"><b>*</b></font></td>
					</table>
					</div>
					</div>
				</td>
			</tr>
			<tr><td colspan="3" bgcolor="#EED5D2">&nbsp;</td></tr>
			<tr align="center">
				<td colspan=3><input type="hidden" name="method" value="add"><input name="submit" type="submit" id="submitbutton" value="บันทึก"><input type="reset" value="ยกเลิก"/></td>
			</tr>
			</table>
			</fieldset> 
			</div>
			<!--จบรายการเพิ่มบิลขอสินเชื่อ-->
			
			<!--แสดงประวัติการอนุมัติ-->
			<div style="padding-top:20px;">
			<?php
			$txthead="แสดงประวัติการอนุมัติ 30 รายการล่าสุด";
			$limit="limit 30";
			include "frm_history.php";
			?>
			</div>
		</div>
	</td>
</tr>
</table>  
	 
</form>      
</body>
</html>