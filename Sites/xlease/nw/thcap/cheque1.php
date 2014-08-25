<?php
include("../../config/config.php");
$ConID=$_GET["ConID"];
$type=$_GET["type"];

$namecus="";
$qury_cus=pg_query("select \"thcap_fullname\"  as name from \"vthcap_ContactCus_detail\" where \"contractID\"='$ConID' and \"CusState\"='0'");
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
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$("#orderDate1").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#receiveDate1").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
});
function includedata(){
	if(document.getElementById('bankOutRegion1').checked==true){
		document.getElementById('bank1').value=1;
		alert(document.getElementById('bank1').value);
	}else{
		document.getElementById('bank1').value=0;
		alert(document.getElementById('bank1').value);
	}
}
function includedata2(data){
	if(document.getElementById('bankOutRegion'+data).checked==true){
		document.getElementById('bank'+data).value=1;
		alert(document.getElementById('bank'+data).value);
		alert(data);
	}else{
		document.getElementById('bank'+data).value=0;
		alert(document.getElementById('bank'+data).value);
		alert(data);
	}
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
function chk_ChqNo_id(no){	
	var elem = $('input[name=bankChqNo'+no+']');
	var elem1 = $('input[name=bankOutID'+no+']');
	var row = $(elem).length;
	var i = 0;
	var input_id = undefined;
	while(i<row)
	{	
		ChqNo_id(no);
		i++;
	}
}
function ChqNo_id(no){
	
	var valueChqNo=document.getElementById('bankChqNo'+no).value;
	var valuebankOutID=document.getElementById('bankOutID'+no).value;
	if(valueChqNo=='')
	{	
		document.getElementById('bankChqNo'+no).style.backgroundColor="#b3ffc1";
	}
	else
	{	
		$.post('chk_ChqNo_id.php',{ChqNo:valueChqNo,bankOutID:valuebankOutID},
		function(data){		
			if(data=='1')
			{
				document.getElementById('bankChqNo'+no).style.backgroundColor="#b3ffc1";
			}
			else if(data=='2')
			{	
				document.getElementById('bankChqNo'+no).style.backgroundColor="#ffbaba";
			}
		});
	}
}
</script>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<div style="float:right"><input type="button" value=" Back " class="ui-button" onclick="window.location='frm_reccheque.php';"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>(THCAP) ใส่รายการรับเช็ค</B></legend>

<div class="ui-widget">
<form name="frm">
<div style="font-weight:bold; margin:10px 0px 10px 0px">เลขที่สัญญา : <span style="color:#0000FF"><?php echo "$ConID"; ?></span></div>
<div style="font-weight:bold; margin:10px 0px 10px 0px">ลูกค้า : <span style="color:#0000FF"><?php echo "$namecus"; ?></span></div>
<div><span style="background-color:#F2FFF2;"><b>เป็นเช็คค้ำประกันหนี้ </b></span> </div>
<div style="padding:5px 0 5px;"><u><b>หมายเหตุ</b></u><br><font color="red">
- เช็คชำระค่างวด-ค่าเช่า ล่วงหน้า คือ เช็คเพื่อให้นำเข้าเมื่อถึงกำหนดชำระค่างวด-ค่าเช่า ล่วงหน้า หากเข้าแล้วเด้งจะมีความผิดทางกฎหมาย<br>
- เช็คค้ำประกันหนี้ คือ เช็คที่ในกรณีที่ ลูกหนี้ทาง FACTORING ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></div>
<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#F2FFF2; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr bgcolor="#FAFAD2">
	<td colspan="3"></td>
	
	<td colspan="2" align="right">ต่างเขตสำนักบัญชี (ต่างจังหวัด)</br>
	<a onclick="javascript:selectAll('bankOutRegion');" style="cursor:pointer;" id="bankOutRegiona"><u>เลือกทั้งหมด</u></a>
	<a onclick="javascript:unselect('bankOutRegion');" style="cursor:pointer;" id="bankOutRegiond"><u>นำออกทั้งหมด</u></a></td>	
	
	<td align="center">เช็คชำระล่วงหน้า<br>
	<a onclick="javascript:selectAll('postchq','inschq');" style="cursor:pointer;" id="postchqa"><u>เลือกทั้งหมด</u></a>
	<a onclick="javascript:unselect('postchq','inschq');" style="cursor:pointer;" id="postchqd"><u>นำออกทั้งหมด</u></a></td>
	
	<td align="center">เช็คค้ำประกัน</br>
	<a onclick="javascript:selectAll('inschq','postchq');" style="cursor:pointer;" id="inschqa"><u>เลือกทั้งหมด</u></a>
	<a onclick="javascript:unselect('inschq','postchq');" style="cursor:pointer;" id="inschqd"><u>นำออกทั้งหมด</u></a></td>	
	
</tr>
<tr bgcolor="#66CC99"><td colspan="7"><b>เช็ค#1</b></td>
</tr>
<tr style="font-weight:bold">
    <td width="75">เลขที่เช็ค</td>
    <td width="93">วันที่สั่งจ่าย</td>
    <td width="93">วันที่รับเช็ค</td>
    <td width="365">ธนาคาร</td>
    <td width="105">จำนวนเงิน(บาท)</td>
	<td width="120"></td>
	<td></td>
</tr>
</table>
<div id='TextBoxesGroup'>
    <div id="TextBoxDiv1">

<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr bgcolor="#F2FFF2" valign="top">
    <td width="75"><input type="text" name="bankChqNo1" id="bankChqNo1" size="10"   onkeyup="chk_ChqNo_id('1');"></td>
    <td width="93"><input type="text" name="receiveDate1" id="receiveDate1" size="10" readonly></td>
	<td width="93"><input type="text" name="orderDate1" id="orderDate1" size="10" readonly></td>
	<td width="365">
				<select id="bankOutID1" name="bankOutID1" onchange="chk_ChqNo_id('1');">
					<?php
					$qry_fp=pg_query("select * from \"BankProfile\"");
					while($res_fp=pg_fetch_array($qry_fp)){
						$bankID =$res_fp["bankID"];
						$bankName=$res_fp["bankName"];
						echo "<option value=\"$bankID\">$bankName</option>";
					}
					?>
				</select><br>
				สาขา :<input type="text" name="bankOutBranch1" id="bankOutBranch1">
				<input type="checkbox" name="bankOutRegion1" id="bankOutRegion1">ต่างเขตสำนักบัญชี (ต่างจังหวัด)
	</td>
    <td width="105"><input id="bankChqAmt1" name="bankChqAmt1" type="text" style="text-align:right" size="15"></td>
	<td width="120"><input type="radio" name="check1" id="postchq1" value="1" >เป็นเช็คชำระค่างวด - ค่าเช่า ล่วงหน้า</td>
	<td><input type="radio" name="check1" id="inschq1" value="1" >เช็คค้ำประกันหนี้</td>
</tr>
</table>

    </div>
</div>

<div style="margin-top:20px">
<div style="float:left">
<input type="hidden" id="postchqall" value="0">
<input type="hidden" id="inschqall" value="0">
<input type="hidden" id="bankOutRegionaall" value="0">
<input type="button" value="บันทึกข้อมูล" id="submitButton"></div>
<div style="float:right"><input type="button" value="+ เพิ่มรายการ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></div>
<div style="clear:both"></div>
</div>



</div>

 </fieldset>

        </td>
    </tr>
</table>

<script type="text/javascript">
var counter = 1;
$(document).ready(function(){
$("#postchqd").hide();
$("#inschqd").hide();
$("#bankOutRegiond").hide();
    $('#addButton').click(function(){
	
	//ตรวจสอบก่อนว่าเช็คชำระค่างวด-ค่าเช่า ล่วงหน้า ทั้งหมด และ เช็คค้ำประกันหนี้ ทั้งหมด ถูกติ๊กอยู่ไม่ ถ้าใช่ ให้ติ๊กรายการที่เพิ่มใหม่ด้วย
	var allpost;
	var allins;
	if($("#postchqall").val() == 1){
		allpost='checked';
	}else{
		allpost='';
	}
	
	if($("#inschqall").val() == 1){
		allins='checked';
	}else{
		allins='';
	}
	if($("#bankOutRegionaall").val() == 1){
		allpost='checked';
	}else{
		allpost='';
	}
	
	//จำนวนเงินล่าสุดที่กรอก
	var nowdate=$("#receiveDate"+ counter).val();
	var chqAMT=$("#bankChqAmt"+ counter).val();
	var arr = nowdate.split("-");
	
	var years = parseInt(arr[0]);
	var month = parseInt(arr[1]);
	var dayint = parseInt(arr[2]);
	var daystr = arr[2];
	var day = '';
	if(month<12){
		month+=1;
		if(month<10){
			month='0'+month;
		}
		if(dayint<10){
			day = daystr;
		} else if (dayint>9&&dayint<29){
			day = dayint;
		}else if(dayint>28){
			day = 28;
		}
	}else{
		month='01';
		years+=1;
		if(dayint<10){
			day = daystr;
		} else if (dayint>9&&dayint<29){
			day = dayint;
		}else if(dayint>28){
			day = 28;
		}
	}
	var newdate=years+'-'+month+'-'+day;
	alert('อัตโนมัติ : วันที่สั่งจ่ายเช็คใบถัดไป คือ ' + newdate);
    counter++;
    console.log(counter);
	var c = counter-1;//id ของข้อมูลก่อนหน้า
	var lastbank = document.getElementById('bankOutID'+c).value;//ข้อมูล bank ก่อนหน้า
	var lastbankOutBranch = document.getElementById('bankOutBranch'+c).value;//ข้อมูล สาขาbank ก่อนหน้า
	var lastorderDate = document.getElementById('orderDate'+c).value;//ข้อมูล วันที่รับเช็ค ก่อนหน้า
	
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    table = '<br><table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#F2FFF2; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ ' <tr bgcolor="#66CC99"><td colspan="7"><b>เช็ค#' + counter + '</b></td></tr>'
	+ '	<tr style="font-weight:bold">'
	+ '		<td width="75">เลขที่เช็ค</td>'
	+ '		<td width="93">วันที่สั่งจ่าย</td>'
	+ '		<td width="93">วันที่รับเช็ค</td>'
	+ '		<td width="365">ธนาคาร</td>'
	+ '		<td width="105">จำนวนเงิน (บาท)</td>'
	+ '     <td width="120"></td>'
	+ '     <td></td>'
	+ '	</tr>'
	+ '	</table>'
	+ ' <table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
    + ' <tr bgcolor="#F2FFF2" valign=top>'
    + ' <td width="75"><input type="text" name="bankChqNo'+ counter +'" id="bankChqNo'+ counter +'" size="10"  onkeyup="chk_ChqNo_id('+counter+');"></td>'
	+ '	<td width="93"><input type="text" name="receiveDate'+ counter +'" id="receiveDate'+ counter +'" size="10" readonly value="'+newdate+'"></td>'
	+ '	<td width="93"><input type="text" name="orderDate'+ counter +'" id="orderDate'+ counter +'" size="10" readonly value="'+lastorderDate+'"></td>'	
	+ ' <td width="365">'
    + '<div id="loadlast'+counter+'">'	
	+ '</div>'	
	+ ' <br>สาขา :<input type=text name="bankOutBranch' + counter + '" id="bankOutBranch' + counter + '" value="'+lastbankOutBranch+'">'
	+ ' <input type="checkbox" name="bankOutRegion' + counter + '" id="bankOutRegion' + counter + '">ต่างเขตสำนักบัญชี (ต่างจังหวัด)'
    + ' </td>'
	+ ' <td width="105"><input id="bankChqAmt' + counter + '" name="bankChqAmt' + counter + '" value="'+ chqAMT +'" type="text" style="text-align:right" size="15"></td>'
    + ' <td width="120"><input type="radio" name="check' + counter + '" id="postchq' + counter + '" value="1" '+allpost+'>เป็นเช็คชำระค่างวด - ค่าเช่า ล่วงหน้า</td>'
	+ ' <td><input type="radio" name="check' + counter + '" id="inschq' + counter + '" value="1"  '+allins+'>เช็คค้ำประกันหนี้</td>'
	+ ' </tr>'
    + ' </table>';
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");

		$("#orderDate"+counter).datepicker({
			showOn: 'button',
			buttonImage: 'calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
		$("#receiveDate"+counter).datepicker({
			showOn: 'button',
			buttonImage: 'calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});		
		$("#loadlast"+counter).load("frm_lastbank.php?lastbank="+lastbank,'&nocount=bankOutID'+counter);
    });
    
	$("#removeButton").click(function(){
        if(counter==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
    });
    
    $("#submitButton").click(function(){
        $("#submitButton").attr('disabled', true);
        var payment = [];
        for( i=1; i<=counter; i++ ){
            if ( $('#bankChqNo'+i).val() == ""){
                alert('กรุณากรอกเลขที่เช็ค'+i);
                $('#bankChqNo'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			if ( $('#orderDate'+i).val() == ""){
                alert('กรุณาเลือกวันที่สั่งจ่าย'+i);
                $('#orderDate'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			if ( $('#receiveDate'+i).val() == ""){
                alert('กรุณาเลือกวันที่รับเช็ค'+i);
                $('#receiveDate'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			var c1 = $('#bankChqAmt'+ i).val();
            if ( isNaN(c1) || c1 == "" || c1 == 0){
                alert('ข้อมูลจำนวนเงินไม่ถูกต้อง'+i);
                $('#bankChqAmt'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
			
			var bankcheck;
			if($('#bankOutRegion'+i).attr("checked")==true){
				bankcheck=1;
			}else if($('#bankOutRegion'+i).attr("checked")==false){
				bankcheck=0;
			}
			
			if($("#postchq"+i).attr("checked") == true){
				$("#postchq"+i).val('1');
			}else{
				$("#postchq"+i).val('0');
			}
			
			if($("#inschq"+i).attr("checked") == true){
				$("#inschq"+i).val('1');
			}else{
				$("#inschq"+i).val('0');
			}
			
            payment[i] = {orderDate: $("#orderDate"+ i).val(),receiveDate: $("#receiveDate"+ i).val(),bankChqNo : $("#bankChqNo"+ i).val() , bankOutID : $("#bankOutID"+ i).val(), bankOutBranch : $("#bankOutBranch" + i).val(), bankOutRegion : bankcheck, bankChqAmt : $("#bankChqAmt"+i).val(),postchq : $("#postchq"+i).val(),inschq : $("#inschq"+i).val()};
        }
        
		
        $.post("api.php",{
            cmd : "save" , 
            ConID : '<?php echo $ConID; ?>', 
			type : '<?php echo $type; ?>', 
            payment : JSON.stringify(payment) 
        },
        function(data){
            if(data == "1"){	
                alert("บันทึกรายการเรียบร้อย");
                location.href = "frm_reccheque.php";
                $("#submitButton").attr('disabled', false);
            }else{
				alert(data);
				$("#submitButton").attr('disabled', false);
            }
        });
    });
    
});
function selectAll(name,sidename){	
	for( i=1; i<=counter; i++ )
	{
		eval("document.frm."+name+i+".checked=true");
	}
	$("#"+name+'a').hide();
	$("#"+name+'d').show();
	
	$("#"+sidename+'a').show();
	$("#"+sidename+'d').hide();
	
	$("#"+name+'all').val(1);
	$("#"+sidename+'all').val(0);	
}
function unselect(name,sidename){
	for( i=1; i<=counter; i++ )
	{
		eval("document.frm."+name+i+".checked=false");
	}
	$("#"+name+'a').show();
	$("#"+name+'d').hide();
	
	$("#"+sidename+'a').show();
	$("#"+sidename+'d').hide();
	
	$("#"+name+'all').val(0);
	
}

</script>
</form>

</body>
</html>