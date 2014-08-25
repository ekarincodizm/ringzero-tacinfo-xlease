<?php
include("../../config/config.php");
$contractID = $_GET['contractID'];
$rootpath = redirect($_SERVER['PHP_SELF'],''); 
if(empty($contractID)){
    exit;
}
echo "<div>";
include "Data_contract_detail.php";
echo "</div>";
echo "<hr color=#CCC533>";
//แสดงที่อยู่ส่งเอกสารในสัญญาขึ้นมาแสดงด้วย
/* / B$qryaddr=pg_query("select sentaddress from \"thcap_mg_contract\" WHERE \"contractID\"='$contractID' 
union
select sentaddress from \"thcap_lease_contract\" WHERE \"contractID\"='$contractID' ");
if($resaddr=pg_fetch_array($qryaddr)){
	$addrcon=$resaddr["sentaddress"];
} */

$qry_addrCon = pg_query("select \"A_NO\",\"A_SUBNO\",\"A_BUILDING\",\"A_ROOM\",\"A_FLOOR\",\"A_VILLAGE\",\"A_SOI\",
\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
from \"thcap_addrContractID\" where \"contractID\" = '$contractID' and \"addsType\"='3' ");
if($res_c = pg_fetch_array($qry_addrCon)){
	$A_NO  =  trim($res_c['A_NO']);//เลขที่
	$A_SUBNO =  trim($res_c['A_SUBNO']);//หมู่
	$A_BUILDING =  trim($res_c['A_BUILDING']);//อาคาร / สิ่งปลูกสร้าง / ชื่ออพาร์ทเม้น
	$A_ROOM	=  trim($res_c['A_ROOM']);// หมายเลขห้อง
	$A_FLOOR =  trim($res_c['A_FLOOR']);// ชั้นที่
	$A_VILLAGE =  trim($res_c['A_VILLAGE']);// หมู้บ้าน
	$A_SOI =  trim($res_c['A_SOI']);// ซอย
	$A_RD =  trim($res_c['A_RD']);// ถนน
	$A_TUM =  trim($res_c['A_TUM']);//ตำบล
	$A_AUM =  trim($res_c['A_AUM']);//อำเภอ
	$A_PRO  =  trim($res_c['A_PRO']);// จังหวัด
	$A_POST =  trim($res_c['A_POST']);//รหัสไปร์ษณีย์
}
?>
<script type="text/javascript">
$(document).ready(function(){
	var num=$("#valuei").val();
	if(num > 0){
		for(i=0; i<num; i++){
			$("#showtype"+i).hide();
		}
	}
	
	$("#s1").hide();
	$("#headsend").hide();
	$("#submitadd").hide();
	$("#textshow").show();
		
	$("#addrcon").click(function(){
		if(document.getElementById("addrcon").checked==true){
			$("#s1").show();
		}else{
			$("#s1").hide();
		}
	});
	
	/*เรื่องที่จะส่งจดหมาย เพิ่ม auto  คอมเม้นไว้ก่อน
	$("#addhead").click(function(){ 
		$("#headsend").show();
		$("#headsend").val('');
		$("#headsend").focus();
		$("#submitadd").show();
		$("#addhead").hide();
		$("#textshow").hide();
	});
	
	$("#submitadd").click(function(){ 
		if($("#headsend").val()==""){
			alert("กรุณากรอกชื่อเรื่องที่จะส่ง");
			$("#headsend").focus();
		}else{
			$.post("process_letter.php",{
				cmd : "addhead",
				headsend : $("#headsend").val(), 
			},
			function(data){
				if(data == "1"){
					alert("บันทึกข้อมูลเรียบร้อยแล้ว");
					$("#loadspec").load("process_letter.php?cmd=showspec");
					$("#headsend").hide();
					$("#submitadd").hide();
					$("#addhead").show();
					$("#textshow").show();
				}else if(data=="2"){
					alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				}
			});
		}
	});*/
	
	$("#type_send").change(function(){
        var src = $('#type_send option:selected').attr('value');
        if ( src == "N" ){
            $("#regis_back").hide();
			 $("#officer_label").hide();
			$("#officer_name").hide();
        }else if( src == "R" ){
            $("#regis_back").hide();
			 $("#officer_label").hide();
			$("#officer_name").hide();
        }else if( src == "A" ){
			$("#regis_back").show();
			 $("#officer_label").hide();
			$("#officer_name").hide();
        }else if( src == "E" ){
           $("#regis_back").hide();
		   $("#officer_label").hide();
			$("#officer_name").hide();
        }else {
			 $("#regis_back").hide();
			$("#officer_label").show();
			$("#officer_name").show();
		}
    });
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function checkdata(){
	if(document.getElementById('type_send').value == "A"){
		if(document.getElementById('regis_back').value == ""){
			alert("กรุณากรอกเลขทะเบียน");
			document.getElementById('regis_back').focus();
			return false;
		}
	} else if(document.getElementById('type_send').value == "O"){
		if(document.getElementById('officer_name').value == ""){
			alert("กรุณากรอกชื่อเจ้าหน้าที่ทีส่งจดหมาย");
			document.getElementById('officer_name').focus();
			return false;
		}
	}
	
	var num=$("#valuei").val();
	if(num > 0){
		for(i=0; i<num; i++){
			if($('#typesend'+ i).attr('value')=='A'){
				if($("#regisback"+ i).val()==""){
					alert("กรุณากรอกเลขทะเบียน");
					$("#regisback"+ i).focus();
					return false;
				}
			} else if($('#typesend'+ i).attr('value')=='O'){
				if($("#officerName"+ i).val()==""){
					alert("กรุณากรอกชื่อเจ้าหน้าที่ทีส่งจดหมาย");
					$("#officerName"+ i).focus();
					return false;
				}
			}
		}
	}
	
	if(document.getElementById("addrcon").checked==false)
	{
		var itChk = 0;
		if(num > 0)
		{
			for(i=0; i<num; i++)
			{
				if(document.getElementById("chk"+i).checked==true)
				{
					itChk = 1;
				}
			}
			if(itChk == 0)
			{
				alert("กรุณาเลือกที่อยู่ที่จะส่ง");
				return false;
			}
		}
	}
	
	if(document.getElementById("valuechk").value==0){
		alert("เลขอ้างอิงไม่ตรงกับเรื่องที่จะส่งจดหมาย");
		return false;
	}
}
function processadd(i){
	if(document.getElementById("chk"+i).checked==true){	
		var t=parseFloat($("#nubchk").val());
		sum=t+1;
		document.getElementById("A_NO_C"+i).disabled=false;	
		document.getElementById("A_SUBNO_C"+i).disabled=false;
		document.getElementById("A_BUILDING_C"+i).disabled=false;	
		document.getElementById("A_ROOM_C"+i).disabled=false;	
		document.getElementById("A_FLOOR_C"+i).disabled=false;	
		document.getElementById("A_VILLAGE_C"+i).disabled=false;
		document.getElementById("A_SOI_C"+i).disabled=false;	
		document.getElementById("A_RD_C"+i).disabled=false;	
		document.getElementById("A_TUM_C"+i).disabled=false;	
		document.getElementById("A_AUM_C"+i).disabled=false;
		document.getElementById("A_PRO_C"+i).disabled=false;	
		document.getElementById("A_POST_C"+i).disabled=false;	
		$("#showtype"+i).show();	
		$("#nubchk").val(sum);
	}else{
		var t=parseFloat($("#nubchk").val());
		sum=t-1;
		document.getElementById("A_NO_C"+i).disabled=true;
		document.getElementById("A_SUBNO_C"+i).disabled=true;
		document.getElementById("A_BUILDING_C"+i).disabled=true;	
		document.getElementById("A_ROOM_C"+i).disabled=true;	
		document.getElementById("A_FLOOR_C"+i).disabled=true;	
		document.getElementById("A_VILLAGE_C"+i).disabled=true;
		document.getElementById("A_SOI_C"+i).disabled=true;	
		document.getElementById("A_RD_C"+i).disabled=true;	
		document.getElementById("A_TUM_C"+i).disabled=true;	
		document.getElementById("A_AUM_C"+i).disabled=true;
		document.getElementById("A_PRO_C"+i).disabled=true;	
		document.getElementById("A_POST_C"+i).disabled=true;	
		document.getElementById("typesend"+i).value='';
		$("#showtype"+i).hide();
		$("#nubchk").val(sum);
	}
}
var gFiles = 0;
function addFile(){
	var li = document.createElement('div');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typeletter[]" id="typeletter'+ (gFiles+1) +'" onchange="checktype('+ (gFiles+1) + ');" ><?php
	$qry_type=pg_query("SELECT auto_id, \"sendName\" FROM thcap_letter_head order by auto_id asc");
	while($res_type=pg_fetch_array($qry_type)){ 
		echo "<option value=\"$res_type[auto_id]\" >$res_type[sendName]</option>";
	}?></select> <b>เลขอ้างอิง:</b> <input onkeypress="KeyCode(event);" type="text" name="detailref[]" id="detailref'+ (gFiles+1) +'" onkeyup="checktype('+ (gFiles+1) + ');" onblur="checktype('+ (gFiles+1) + ');" />&nbsp;<button onClick="removeFile(\'file-' + gFiles + '\')">ลบ</button>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;	
}
function removeFile(aId){
	--gFiles;
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}
function changetype(id){
	if($('#typesend'+ id).attr('value')=='N'){
		$("#regisback"+id).hide();
		$("#officerLabel"+id).hide();
		$("#officerName"+id).hide();
	}else if($('#typesend'+ id).attr('value')=='R'){
		$("#regisback"+id).hide();
		$("#officerLabel"+id).hide();
		$("#officerName"+id).hide();
	}else if($('#typesend'+ id).attr('value')=='A'){
		$("#regisback"+id).show();
		$("#officerLabel"+id).hide();
		$("#officerName").hide();
	}else if($('#typesend'+ id).attr('value')=='E') {
		$("#regisback"+id).hide();
		$("#officerLabel"+id).hide();
		$("#officerName"+id).hide();
	}else{
		$("#regisback"+id).hide();
		$("#officerLabel"+id).show();
		$("#officerName"+id).show();
	}	
}

function KeyCode(objId)
{
	   var key;
	   key = objId.which; // Firefox 
	   if ((key >= 0  && key<= 127) && key != 13) //48-57(ตัวเลข) ,65-90(Eng ตัวพิมพ์ใหญ่ ) ,97-122(Eng ตัวพิมพ์เล็ก) , 95(_)
	   {
		  
	   }else if(key == 13){ //Enter
			key = objId.preventDefault();   
	   }
	   else
	   {
		  key = objId.preventDefault();
	   }	  
}
function checktype(CF){
	
	$.post("checktype_letter.php",{
			detailref : document.getElementById("detailref"+CF).value,
			typeletter : document.getElementById("typeletter"+CF).value
		},
		function(data){		
				if(data=='F'){
						document.getElementById("detailref"+CF).style.backgroundColor ="#FF0000";
						document.getElementById("valuechk").value=0;
				}else if(data == 'T'){
						document.getElementById("detailref"+CF).style.backgroundColor ="#33FF33";
						document.getElementById("valuechk").value=1;
				}
		});
};
function chkofficer(chk){
	var contract = chk; 
}
function officerSearch(chkField){
	
	var chk = chkField;
	
	if(chk == "N"){
		$("#officer_name").autocomplete({
			source: "s_officer.php",
			minLength:1
		});
	}else{
		$("#officerName"+chk).autocomplete({
			source: "s_officer.php",
			minLength:1
		});
	}
}

function refreshselect()
{  
	var re_load_list = $.ajax({   
		  url: "frm_re_load_list.php",		 
		  async: false  
	}).responseText;
	
	for(var a = 0; a <= gFiles; a++)
	{
		$("select#typeletter"+a).html(re_load_list); 		
	}
}
//process_letter.php
</script>
<form method="post" name="form1" action="process_letter.php">
<div align="left"><b>เลขที่สัญญา : <font color="red"><?php echo $contractID;?></font> ---></b><input type="checkbox" name="addrcon" id="addrcon" value="1">ส่งไปที่อยู่สัญญา</div>
<div align="left" id="s1">
	<div style="margin-top:10px;margin-bottom:10px;width:80%" >
		<fieldset >
		<b>เลขที่ :</b><input type="text" name="A_NO" id="A_NO" size="10" value="<?php echo $A_NO; ?>" />
		<b>หมู่ :</b><input type="text" name="A_SUBNO" id="A_SUBNO" size="5" value="<?php echo $A_SUBNO; ?>"/>
		<b>อาคาร :</b><input type="text" name="A_BUILDING" id="A_BUILDING" value="<?php echo $A_BUILDING; ?>"/>
		<b>เลขห้อง :</b><input type="text" name="A_ROOM" id="A_ROOM" size="5" value="<?php echo $A_ROOM; ?>" />
		<b>ชั้นที่ :</b><input type="text" name="A_FLOOR" id="A_FLOOR" size="5" value="<?php echo $A_FLOOR; ?>"/>
		<b>หมู้บ้าน :</b><input type="text" name="A_VILLAGE" id="A_VILLAGE" value="<?php echo $A_VILLAGE; ?>"/> <br><br>
		<b>ซอย :</b><input type="text" name="A_SOI" id="A_SOI" value="<?php echo $A_SOI; ?>"/> 
		<b>ถนน :</b><input type="text" name="A_RD" id="A_RD" value="<?php echo $A_RD; ?>"/> 
		<b>ตำบล/แขวง :</b><input type="text" name="A_TUM" id="A_TUM" value="<?php echo $A_TUM; ?>"/>
		<b>อำเภอ/เขต :</b><input type="text" name="A_AUM" id="A_AUM" value="<?php echo $A_AUM; ?>"/> <br><br>
		<b>จังหวัด :</b><input type="text" name="A_PRO" id="A_PRO" value="<?php echo $A_PRO; ?>"/> 
		<b>รหัสไปร์ษณีย์ :</b><input type="text" name="A_POST" id="A_POST" value="<?php echo $A_POST; ?>"/>
		</fieldset>
	</div>
	<div style="background-color:red">&nbsp;<font color="#FFFFFF"><b>ประเภทการส่งจดหมาย :</b></font>
		<select name="type_send" id="type_send">
			<option value="N">ส่งธรรมดา</option>
			<option value="R">ลงทะเบียน</option>
			<option value="A">ลงทะเบียนตอบรับ</option>
			<option value="E">EMS</option>
			<option value="O">ส่งโดยเจ้าหน้าที่</option>
		</select>
		<label id="officer_label" hidden ><font color="#FFFFFF"><b>  ชื่อเจ้าหน้าที่ : </b></font></label><input type="text" name="officer_name" id="officer_name" onchange="officerSearch('N');" onkeyup="officerSearch('N');" onblur="officerSearch('N');" hidden />
		<input type="hidden" id="chk_officer">
		<input onkeypress="KeyCode(event);"  type="text" name="regis_back" id="regis_back" size="25" style="display:none;" maxlength="13">
	</div>
</div>

<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#E6E29B">
<tr style="font-weight:bold;" valign="middle" bgcolor="#D0CB66" align="center">
	<td>ชื่อ - นามสกุล</td>
    <td>ที่อยู่ที่ติดต่อส่งเอกสาร</td>
    <td>ความสัมพันธ์กับสัญญา</td>
    <td>ส่งท่านนี้</td>
</tr>

<?php
//แสดงข้อมูลลูกค้าที่จะส่งจดหมาย
$qry_name=pg_query("select \"CusID\",\"thcap_fullname\",\"thcap_address\",\"relation\",
 \"A_NO\",\"A_SUBNO\",\"Building\",\"room\",\"LiveFloor\",\"Village\",\"A_SOI\",
\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
 from \"vthcap_ContactCus_detail\" 
WHERE \"contractID\" ='$contractID' order by \"CusState\"");

$num_row = pg_num_rows($qry_name);
$p=0;
while($res_name=pg_fetch_array($qry_name)){
    $CusID=$res_name["CusID"];
    $thcap_fullname=$res_name["thcap_fullname"];
    $address=$res_name["thcap_address"]; $address = nl2br($address);
    $relation=$res_name["relation"];
    
	$A_NO_cu  =  trim($res_name['A_NO']);//เลขที่
	$A_SUBNO_cu =  trim($res_name['A_SUBNO']);//หมู่
	$A_BUILDING_cu =  trim($res_name['Building']);//อาคาร / สิ่งปลูกสร้าง / ชื่ออพาร์ทเม้น
	$A_ROOM_cu	=  trim($res_name['room']);// หมายเลขห้อง
	$A_FLOOR_cu =  trim($res_name['LiveFloor']);// ชั้นที่
	$A_VILLAGE_cu =  trim($res_name['Village']);// หมู้บ้าน
	$A_SOI_cu =  trim($res_name['A_SOI']);// ซอย
	$A_RD_cu =  trim($res_name['A_RD']);// ถนน
	$A_TUM_cu =  trim($res_name['A_TUM']);//ตำบล
	$A_AUM_cu =  trim($res_name['A_AUM']);//อำเภอ
	$A_PRO_cu  =  trim($res_name['A_PRO']);// จังหวัด
	$A_POST_cu =  trim($res_name['A_POST']);//รหัสไปร์ษณีย์
	
    $i+=1;
    if($i%2==0){
        echo "<tr bgcolor=#EDEBC2>";
    }else{
        echo "<tr bgcolor=#F9F8E8>";
    }
	
?>
	<td width="20%" valign="top"><?php echo "$thcap_fullname"; ?></td>
    <td width="60%">
		<div style="margin-top:10px;margin-bottom:10px" >
		<b>เลขที่ :</b><input type="text" name="A_NO_C[]" id="A_NO_C<?php echo $p;?>" size="10" value="<?php echo $A_NO_cu; ?>" disabled="true"/>
		<b>หมู่ :</b><input type="text" name="A_SUBNO_C[]" id="A_SUBNO_C<?php echo $p;?>" size="5" value="<?php echo $A_SUBNO_cu; ?>" disabled="true"/>
		<b>อาคาร :</b><input type="text" name="A_BUILDING_C[]" id="A_BUILDING_C<?php echo $p;?>" value="<?php echo $A_BUILDING_cu; ?>" disabled="true"/>
		<b>เลขห้อง :</b><input type="text" name="A_ROOM_C[]" id="A_ROOM_C<?php echo $p;?>" size="5" value="<?php echo $A_ROOM_cu; ?>"  disabled="true"/><br><br>
		<b>ชั้นที่ :</b><input type="text" name="A_FLOOR_C[]" id="A_FLOOR_C<?php echo $p;?>" size="5" value="<?php echo $A_FLOOR_cu; ?>" disabled="true"/>
		<b>หมู้บ้าน :</b><input type="text" name="A_VILLAGE_C[]" id="A_VILLAGE_C<?php echo $p;?>" value="<?php echo $A_VILLAGE_cu; ?>" disabled="true"/> 
		<b>ซอย :</b><input type="text" name="A_SOI_C[]" id="A_SOI_C<?php echo $p;?>" value="<?php echo $A_SOI_cu; ?>" disabled="true"/> <br><br>
		<b>ถนน :</b><input type="text" name="A_RD_C[]" id="A_RD_C<?php echo $p;?>" value="<?php echo $A_RD; ?>" disabled="true"/> 
		<b>ตำบล/แขวง :</b><input type="text" name="A_TUM_C[]" id="A_TUM_C<?php echo $p;?>" value="<?php echo $A_TUM_cu; ?>" disabled="true"/> <br><br>
		<b>อำเภอ/เขต :</b><input type="text" name="A_AUM_C[]" id="A_AUM_C<?php echo $p;?>" value="<?php echo $A_AUM_cu; ?>" disabled="true"/> 
		<b>จังหวัด :</b><input type="text" name="A_PRO_C[]" id="A_PRO_C<?php echo $p;?>" value="<?php echo $A_PRO_cu; ?>" disabled="true"/>  <br><br>
		<b>รหัสไปร์ษณีย์ :</b><input type="text" name="A_POST_C[]" id="A_POST_C<?php echo $p;?>" value="<?php echo $A_POST_cu; ?>" disabled="true"/>
	</div><br>
		<div style="background-color:red" id="showtype<?php echo $p;?>">&nbsp;<font color="#FFFFFF"><b>ประเภทการส่งจดหมาย :</b></font>
		<select name="typesend[]" id="typesend<?php echo $p;?>" onchange="changetype('<?php echo $p;?>');">
			<option value="N" >ส่งธรรมดา</option>
			<option value="R">ลงทะเบียน</option>
			<option value="A">ลงทะเบียนตอบรับ</option>
			<option value="E">EMS</option>
			<option value="O">ส่งโดยเจ้าหน้าที่</option>
		</select>	
		<label id="officerLabel<?php echo $p;?>" hidden ><font color="#FFFFFF"><b>  ชื่อเจ้าหน้าที่ : </b></font></label><input type="text" name="officerName[]" id="officerName<?php echo $p;?>" onchange="officerSearch(<?php echo $p;?>);" onkeyup="officerSearch(<?php echo $p;?>);" onblur="officerSearch(<?php echo $p;?>);" hidden />
		<input type="hidden" id="chkOfficer">
		<input type="text" onkeypress="KeyCode(event);" name="regisback[]" id="regisback<?php echo $p;?>" size="25" style="display:none;" maxlength="13"></div>
	</td>
    <td align="center" valign="top"><?php echo "$relation"; ?></td>
	<td align="center" valign="top"><input type="checkbox" name="chk[]" id="chk<?php echo $p;?>" value="<?php echo $CusID;?>" onclick="processadd('<?php echo $p;?>')"></td>
</tr>
<?php
	$p++;
} //end while
?>
<tr bgcolor="#FFF0F0"><td colspan="4">

<input type="button" value="เพิ่มชื่อเรื่องที่จะส่ง" id="addhead" onclick="javascript:popU('<?php echo $rootpath.'nw/thcap_typesendletter/frm_add_type.php';?>?show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')"><span id="textshow"><font color="red"><b>* หากไม่พบเรื่องที่จะส่งจดหมาย กรุณาเพิ่มชื่อเรื่องที่จะส่งจดหมายก่อนเลือกรายการ แล้วรอการอนุมัติ</b></font></span>
<!--input type="text" onkeypress="if(event.keyCode==13) return false;" name="headsend" id="headsend" size="80"-->
<!--img src="images/save.png" width="18" height="18" id="submitadd" align="top" style="cursor:pointer;" title="เพิ่มชื่อเรื่องที่จะส่ง"-->
</td></tr>
<tr bgcolor="#FFFFFF">
	<td colspan="4"><input type="hidden" id="valuei" value="<?php echo $num_row;?>">
	<input type="hidden" name="nubchk" id="nubchk" value="0">
		<table width="100%">
		<tr>
			<button type="button" onclick="refreshselect()">โหลดเรื่องที่จะส่งจดหมาย</button><font color="red"><b>** หากไม่พบเรื่องที่จะส่งจดหมาย กรุณากด โหลดเรื่องที่จะส่งจดหมาย</b></font>
			<td valign="top" width="150"><b>เรื่องที่จะส่งจดหมาย : </b></td>
			<td>
				<div id="loadspec">
						<select name="typeletter[]" id="typeletter0" onchange="checktype(0);">
						<?php
						//ดึงภัยเพิ่มพิเศษขึ้นมาจากฐานข้อมูล
						$qryspecial=pg_query("SELECT auto_id, \"sendName\" FROM thcap_letter_head order by \"auto_id\" asc");
						$numspec=pg_num_rows($qryspecial);
						while($resspec=pg_fetch_array($qryspecial)){
							list($sendId,$sendName)=$resspec;
							echo "<option value=\"$sendId\">$sendName</option>";		
						}			
						?>	
						</select><b> เลขอ้างอิง:</b> <input onkeypress="KeyCode(event);" type="text" name="detailref[]" id="detailref0" onkeyup="checktype(0);" onblur="checktype(0);" /><button type="button" onclick="addFile()">เพิ่มรายการ</button>
						
						<div id="files-root" style="margin:0"></div>
				</div>
				<input type="hidden" id="valuechk" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top"><b>หมายเหตุ :</b></td>
			<td><textarea cols="80" rows="5" name="note"></textarea></td>
		</tr>
		<tr><td colspan="2"><hr color="#CCC533"><br>
		<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
		<input type="hidden" name="cmd" value="add">
		<input type="submit" value="บันทึก" onclick="return checkdata();"></td></tr>
		</table>
	</td>
</tr>
</table>
</form>