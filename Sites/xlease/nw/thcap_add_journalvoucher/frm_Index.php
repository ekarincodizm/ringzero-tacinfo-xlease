<?php
include("../../config/config.php");
$voucherPurposetype="JV";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <!--<title><?php echo $_SESSION["session_company_name"]; ?></title>-->
	<title>(THCAP) ทำรายการใบสำคัญรายวันทั่วไป</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">

function chktype(){
//ตรวจสอบว่าเป็นประเภทไหน 
	var r=document.getElementsByName("made");
	var i=0;
	var temp;
	while(i<r.length)
	{
		if(r[i].checked==true){
			if(r[i].value=='1'){
				temp='1';
			}
		else if(r[i].value=='2'){
			temp='2';
		}
			break;
		}
		else{i++;}
	}
	if(temp=='2'){
		//บัญชี
		addFile1();
		getValueArray1();
	}
	else{
		//บันทึกเอง
		addFile();
		getValueArray();
	}
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
/*function showall()
{	
	var ele=$('input[name="showall1"]');  
	if(($(ele).is(':checked')))
	{  
		$(ele).attr ( "checked" ,"checked" );			
		data = $.ajax({    
			url: "../thcap_add_paymentpvoucher/frm_save_channel.php",
			async:false
		}).responseText;	
		$("#purpost").html(data);
		document.add_acc.chk_ch.value = 0;	
	}
	else
	{	
		var data = "";	
		$("#purpost").html(data);		
		document.add_acc.chk_ch.value = 1;		
	}
	
}*/
function  chk_find(){
	var find;
	if(document.getElementById("to2").checked){
		find = "emp";	
	} else if (document.getElementById("to3").checked){
		find = "cus";
	} else if (document.getElementById("to4").checked){
		find = "cus_corp";
	} 
	return find; 
}
function KeyData(){
	var  cf = chk_find();
	
	$("#topayfullin").autocomplete({
			source: "../thcap_add_paymentpvoucher/s_officer.php?find="+cf,
			minLength:1
		});
}
function topayfull(no){
	document.getElementById("topayfullin").value= '';
	document.getElementById("topayfullout").value= '';	
	if(no=='0'){
		
		$("#topayfullout").show();
		$("#topayfullin").hide();
		
	}
	else{
		$("#topayfullin").show();
		$("#topayfullout").hide();
	}

}
function CheckSelect() {
//ตรวจสอบก่อนการส่งค่าไปบันทึก
	//1.ตรวจสอบเรื่องเงิน Dr. Cr. ว่าเป็นค่าว่างหรือไม่
    var x3=0;
	//1.1 รายการ ตามสูตรที่เลือก
    var text_money = window.document.getElementsByName("text_money1[]");
	//1.2 รายการ ที่เพิ่มเข้าไปเอง นอกเหนือจากสูตร
	var text_money_add = window.document.getElementsByName("text_money2[]");
    for(i = 0; i < text_money.length; i++){
        if(text_money[i].value == ''){
            x3 = x3+1;
        }
    }
	    for(i = 0; i < text_money_add.length; i++){
        if(text_money_add[i].value == ''){
            x3 = x3+1;
        }
    }
	//2.ตรวจสอบคำอธิบาย ว่าเป็นค่าว่างหรือไม่
    if(document.add_acc.text_add.value == ""){
        document.add_acc.text_add.focus();
        alert('ไม่พบคำอธิบายรายการ');
        return false;
    }else if(x3 > 0){
        alert('ไม่พบยอดเงิน');
        return false;
    }else if(document.add_acc.chk_drcr.value == "1"){
        alert('ผลรวม Dr และ Cr ไม่เท่ากัน');
        return false;
	}else if(($("#topayfullin").val() == "") &&($("#topayfullout").val() == "")){
        alert('ไม่มีข้อมูล ให้จ่าย:');
        return false;	
    }else{
         return true;
    }
}

function loadp(no){
	//ตรวจสอบว่า user เลือกแบบไหน 1.บันทึกเอง ,  2.ใช้สูตร
	if(no=='1'){		
		$("#made01").show();
		$("#made02").hide();
		$("#myShow").hide();
		$("#myDiv").empty();
		}
	else{		
		$("#made02").show();
		$("#made01").hide();
		$("#myDiv").empty();
		$("#myShow").show();
	}
}
$(document).ready(function(){
	$("#made01").show();
	$("#made02").hide();
	$("#topayfullout").show();
	$("#topayfullin").hide();
	
     $("#formula").autocomplete({
			source: "s_bookall.php",
			minLength:1
	});
	
   $("#formula").change(function(){
        $('#myDiv').empty();
    });
	
    //กด บันทึก
    $("#btn_submit").click(function(){
        if($("#text_add").val() == "" ){
            alert('ไม่พบคำอธิบายรายการ');
            $("#text_add").focus();
            return false;
        }
		if(($("#topayfullin").val() == "") &&($("#topayfullout").val() == "")){
            alert('ไม่มีข้อมูล ให้จ่าย:');
            return false;
		}
		
		var r=document.getElementsByName("made");
		var i=0;
		var temp;
		while(i<r.length)
		{
			if(r[i].checked==true){
				if(r[i].value=='1'){
					temp='1';
				}
			else if(r[i].value=='2'){
				temp='2';
			}
			break;
		}
		else{i++;}
		}
	if(temp=='2'){
	if(CheckSelect()){
			 $("#add_acc").submit();
		}
	}
	else{
	  var x1=0;
      var acid = window.document.getElementsByName("acid[]");
        for(i = 0; i < acid.length; i++){
            if(acid[i].value == ''){
			
                x1 = x1+1;
            }
        }
        
        var x2=0;
        var actype = window.document.getElementsByName("actype[]");
        for(i = 0; i < actype.length; i++){
            if(actype[i].value == ''){
                x2 = x2+1;
            }
        }
        
        var x3=0;
        var text_money = window.document.getElementsByName("text_money[]");
        for(i = 0; i < text_money.length; i++){
            if(text_money[i].value == ''){
                x3 = x3+1;
            }
        }
        
       if(x1 > 0){
            alert('พบรายการบัญชี ไม่ถูกเลิก');
            return false;
        }else if(x2 > 0){
            alert('พบสถานะ ไม่ถูกเลิก');
            return false;
        }else if(x3 > 0){
            alert('ไม่พบยอดเงิน');
            return false;
        }else if($("#chk_drcr").val() == 1){
            alert('ผลรวม Dr และ Cr ไม่เท่ากัน');
            return false;
        }else{
            $("#add_acc").submit();
        }
	}
	
    });
    
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>

<script type="text/javascript">
var gFiles = 0;
var gFiles1 = 100;
var gFiles_C=0;
var ita_array =-1;
		var HttPRequest = false;
      function doCallAjax() {	
		
		if($("#formula").val()!=""){
		HttPRequest = false;
          if (window.XMLHttpRequest) { // Mozilla, Safari,...
             HttPRequest = new XMLHttpRequest();
             if (HttPRequest.overrideMimeType) {
                HttPRequest.overrideMimeType('text/html');
             }
          } else if (window.ActiveXObject) { // IE
             try {
                HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
             } catch (e) {
                try {
                   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
             }
          } 
          
          if (!HttPRequest) {
             alert('Cannot create XMLHTTP instance');
             return false;
          }
    
            var url = 'ajax_query.php';           
			var str=document.getElementById("formula").value;
			var str1=str.split("+",2);
			 var pmeters = 'formula='+str1[1];
            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);           
            
            HttPRequest.onreadystatechange = function()
            {
                 if(HttPRequest.readyState == 3)  // Loading Request
                  {
                   document.getElementById("myShow").innerHTML = "Now is Loading...";
                  }

                 if(HttPRequest.readyState == 4) // Return Request
                  {
                   document.getElementById("myShow").innerHTML = HttPRequest.responseText;
                  }
            }
		}
	   }
	   
function addFile(){
//กด เพิ่มรายการ ในการบันทึเอง
    var li = document.createElement('div');
    li.setAttribute('id', 'file-' + gFiles);
    li.innerHTML = '<div align="left">เลือกบัญชี <select name="acid[]" id="acid" onchange="getValueArray(); "><option value="">- เลือก -</option><?php
	$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
	while($res_name=pg_fetch_array($qry_name)){
		$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
		$AcID = $res_name["accBookID"]; // เลขที่บัญชี
		$AcName = $res_name["accBookName"]; // ชื่อบัญชี
		echo "<option value=\"$AcSerial\">$AcID : $AcName</option>";
}
?></select>สถานะ <select name="actype[]" id="actype" onchange="getValueArray(); chk4700();"><option value="">- เลือก -</option><option value="1">Dr</option><option value="2">Cr</option></select>ยอดเงิน<input type="text" id="text_money" name="text_money[]" size="10" OnKeyUp="JavaScript:getValueArray();" onblur="chk4700();"><span onclick="removeFile(\'file-' + gFiles + '\'), getValueArray();" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span></div>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;
}

function removeFile(aId) {
//กด ลบรายการนี้	
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}

function addFile1(){
//กด เพิ่มรายการ ในการใช้สูตร
	var li = document.createElement('div');
    li.setAttribute('id', 'file1-' + gFiles1);
    li.innerHTML = '<div align="left">เลือกบัญชี <select name="text_accno[]" id="text_accno" onchange="getValueArray1(); "><option value="">- เลือก -</option><?php
$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
while($res_name=pg_fetch_array($qry_name)){
	$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
    $AcID = $res_name["accBookID"]; // เลขที่บัญชี
    $AcName = $res_name["accBookName"]; // ชื่อบัญชี
    echo "<option value=\"$AcSerial\">$AcID : $AcName</option>";
}
?></select> สถานะ <select name="text_drcr[]" id="text_drcr" onchange="getValueArray1(); "><option value="">- เลือก -</option><option value="1">Dr</option><option value="2">Cr</option></select> ยอดเงิน <input type="text" id="text_money2" name="text_money2[]" size="10" OnKeyUp="JavaScript:getValueArray1();""> <span onclick="removeFile(\'file1-' + gFiles1 + '\'), getValueArray1();" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span></div>';
    document.getElementById('myShow').appendChild(li);
    gFiles1++;
}

</script>

</head>
<body>
<div class="header"><h1>(THCAP) ทำรายการใบสำคัญรายวันทั่วไป</h1></div>
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td> 
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ทำรายการ- Journal Voucher</B></legend>

<script language="JavaScript" type="text/JavaScript">
function getValueArray1(){
//คิดคำนวน ของการใช้สูตร
	var a1=0;
    var a0=0;
    var sum1 = 0;
    var sum0 = 0;
    
    str = "<table cellSpacing=\"1\" cellPadding=\"3\" width=\"100%\" style=\"background-color:#ACACAC; color:#000000;\"><tr bgcolor=\"#FFFFD2\"><td align=\"center\"><b>บัญชี</b></td><td align=\"center\"><b>Dr</b></td><td align=\"center\"><b>Cr</b></td></tr>";
    
	//รายการตามสูตร
	var acname1 = window.document.getElementsByName("text_ac_name1[]");
    var acid1 = window.document.getElementsByName("text_accno1[]");
    var actype1 = window.document.getElementsByName("text_drcr1[]");
    var text_money1 = window.document.getElementsByName("text_money1[]");
	var text_ac_BookID1 = window.document.getElementsByName("text_ac_BookID1[]");
    var actype_length1 = actype1.length;
	//รวมยอดแต่ละประเภท Dr. Cr.
    for(i = 0; i < actype_length1; i++){
        if(actype1[i].value == ''){}
        else if(actype1[i].value == 1){
            sum1 = sum1 + (text_money1[i].value*1);
            a1 = a1+1;
            str += "<tr bgcolor=\"#FFFFFF\"><td>"+text_ac_BookID1[i].value+" : "+acname1[i].value+"</td><td align=\"right\">"+text_money1[i].value+"</td><td></td></tr>";
        }
    }
    for(i = 0; i < actype_length1; i++){
        if(actype1[i].value == ''){}
        else if(actype1[i].value == 2){
            sum0 = sum0 + (text_money1[i].value*1);
            a0 = a0+1;
            str += "<tr bgcolor=\"#FFFFFF\"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+text_ac_BookID1[i].value+" : "+acname1[i].value+"</td><td></td><td align=\"right\">"+text_money1[i].value+"</td></tr>";
        }
    }
	//
	//
	//รายการที่ กดเพิ่มรายการเองนิกเหนือจากสูตร
    var acname = window.document.getElementsByName("text_ac_name[]");
    var acid = window.document.getElementsByName("text_accno[]");
    var actype = window.document.getElementsByName("text_drcr[]");
    var text_money = window.document.getElementsByName("text_money2[]");
	var text_ac_BookID = window.document.getElementsByName("text_ac_BookID[]");
    var actype_length = actype.length;

   for(i = 0; i < actype_length; i++){   
        if(actype[i].value == ''){}
        else if(actype[i].value == 1){			
            var index = acid[i].selectedIndex;			
            if(index != ''){				
                select_text = document.getElementById('text_accno').options[index].text;               
                sum1 = sum1 + (text_money[i].value*1);	
                a1 = a1+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>"+select_text+"</td><td align=\"right\">"+text_money[i].value+"</td><td></td></tr>";
            }
        }
    }
    sum1 = sum1.toFixed(2);	
    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 2){
            
            var index = acid[i].selectedIndex;			
            if(index != ''){
                select_text = document.getElementById('text_accno').options[index].text;                
                sum0 = sum0 + (text_money[i].value*1);
                a0 = a0+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+select_text+"</td><td></td><td align=\"right\">"+text_money[i].value+"</td></tr>";
            }
        }
    }
    sum0 = sum0.toFixed(2);  
	//	
	//ตรวจสอบว่าค่าจากการ รวมยอด Dr.,Cr. เท่ากันหรือไม่
    if((sum1 == sum0) && a1 > 0 && a0 > 0){
        document.add_acc.chk_drcr.value = 0;
    }else{
        document.add_acc.chk_drcr.value = 1;
    }
    //
    str += "<tr bgcolor=\"#FFFFFF\"><td align=\"right\"><b>รวม</b></td><td align=\"right\"><b>"+sum1+"</b></td><td align=\"right\"><b>"+sum0+"</b></td></tr>";
    str += "</table>";
    document.getElementById('myDiv').innerHTML = str;
}

function getValueArray(){
    var a1=0;
    var a0=0;
    var sum1 = 0;
    var sum0 = 0;
    
    str = "<table cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" width=\"100%\" style=\"background-color:#ACACAC; color:#000000;\"><tr bgcolor=\"#FFFFD2\"><td align=\"center\"><b>บัญชี</b></td><td align=\"center\"><b>Dr</b></td><td align=\"center\"><b>Cr</b></td></tr>";
    
    var acid = window.document.getElementsByName("acid[]");
    var actype = window.document.getElementsByName("actype[]");
    var text_money = window.document.getElementsByName("text_money[]");
    var actype_length = actype.length;

    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 1){
            var index = acid[i].selectedIndex;			
            if(index != ''){
                select_text = document.getElementById('acid').options[index].text;                
                sum1 = sum1 + (text_money[i].value*1);
                a1 = a1+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>"+select_text+"</td><td align=\"right\">"+text_money[i].value+"</td><td></td></tr>";
            }
        }
    }
    sum1 = sum1.toFixed(2);

    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 2){            
            var index = acid[i].selectedIndex;
            if(index != ''){
                select_text = document.getElementById('acid').options[index].text;
                
                sum0 = sum0 + (text_money[i].value*1);
                a0 = a0+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+select_text+"</td><td></td><td align=\"right\">"+text_money[i].value+"</td></tr>";
            }
        }
    }
    sum0 = sum0.toFixed(2);    
    if((sum1 == sum0) && a1 > 0 && a0 > 0){
        document.add_acc.chk_drcr.value = 0;
    }else{
        document.add_acc.chk_drcr.value = 1;
    }    
    str += "<tr bgcolor=\"#FFFFFF\"><td align=\"right\"><b>รวม</b></td><td align=\"right\"><b>"+sum1+"</b></td><td align=\"right\"><b>"+sum0+"</b></td></tr>";
    str += "</table>";
    document.getElementById('myDiv').innerHTML = str;
}

function chk4700(){
    var nubbb = 0;
    
    var arr_acid = window.document.getElementsByName("acid[]");
    var arr_actype = window.document.getElementsByName("actype[]");
    var arr_text_money = window.document.getElementsByName("text_money[]");
    var actype_length = arr_actype.length;
    
    for(i = 0; i < actype_length; i++){
        if(arr_acid[i].value == 4700 && arr_actype[i].value == 1 && arr_text_money[i].value != ""){
            nubbb++;
        }
    }
    
    if(nubbb > 0){
        $("#text_add").attr("readonly", "readonly");
        //$("#text_add").val('');
        $("#divenshow").show();
        $("#hidchk").val('1');
    }else{
        $("#text_add").attr("readonly", "");
        $("#divenshow").hide();
        $("#hidchk").val('0');
    }
}

function changepurpose(){
		//ล้างข้อมูล ที่ให้ key เอง
		clean_key();			
		//value ชอง จุดประสงค์			
		var voucherPurpose = $('#voucherPurpose').val();
		//ช่อง from รหัสช่องทางที่เงินออก			
		$.post('../thcap_add_paymentpvoucher/list_fromChannel.php',{voucherPurpose:voucherPurpose},function(data){	
		$('#fromChannel').html(data);
		});
	
		$.post('../thcap_add_paymentpvoucher/list_withMedium.php',{voucherPurpose:voucherPurpose},function(data){	
		$('#withMedium').html(data);		
		});
		//ช่อง from ช่องทางการรับเงิน:
		$.post('../thcap_add_paymentpvoucher/list_toChannel.php',{voucherPurpose:voucherPurpose},function(data){     
		$('#toChannel').html(data);
		});	
		selectChannel();
		selecttoChannel();

}
function selectChannel(){	
	var fromChannel = $('#fromChannel').val();	
	fromChannel = fromChannel.split("#");
	fromChannel=fromChannel[0];	
		$.post('../thcap_add_paymentpvoucher/list_fromChannelType.php',{fromChannel:fromChannel},function(data){		
		$('#fromChannelType').html(data);
		});	
}

function selecttoChannel(){	
	var toChannel = $('#toChannel').val();	
	toChannel = toChannel.split("#");
	toChannel=toChannel[0];
	$.post('../thcap_add_paymentpvoucher/list_fromChannelType.php',{fromChannel:toChannel},function(data){		
	$('#toChannelType').html(data);
	});
}
</script>

<form method="post" name="add_acc" id="add_acc" action="process_add_acc_send.php">
<input type="hidden" id="chk_drcr" name="chk_drcr">
<input type="hidden" name="noaddFile"  id="noaddFile" size="54">
<?php include('../thcap_add_paymentpvoucher/frm_show_detail.php');?>
<div style="float:right"><input type="button" value="เพิ่มรายการ" class="ui-button" id="btn_add" name="btn_add" onclick= "chktype();"></div>
<br>
<br>
<div id="addpt" name="addpt">
	<fieldset style="width:99%"><legend>บันทึกรายการ Channel</legend>
	<!--div> 
		<input type =checkbox name="showall1" onChange="showall()">แสดง/ซ่อน การบันทึก
	</div-->	
	<div id="purpost">
		<?php 	include('../thcap_add_paymentpvoucher/frm_save_channel.php');?>
	</div>
	<div id="ch">	
	</div>
</div>
</fieldset>
</form>
<div style="float:left"><input type="button" value="บันทึก" class="ui-button" id="btn_submit" name="btn_submit"></div>

<div style="clear:both;"></div>
</fieldset>
        </td>
    </tr>
</table>
<?php 
	//รายการรออนุมัติทั้งหมด
	$sendfrom='1';
	include('../thcap_appv_jv/jv_wait.php');
	
	?>
</body>
</html>