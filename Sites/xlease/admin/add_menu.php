<?php
session_start();
$_SESSION["av_iduser"];
$idno=pg_escape_string($_POST["idno_names"]);
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>เพิ่มเมนู</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
(function($) {
    $.extend($.expr[':'], {
         val: function(elem, i, attr) {
             return elem.value === attr[3];
         }
    });
}(jQuery));

var xmlHttp;

function createXMLHttpRequest() {
    if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } 
    else if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }
}
 
 function add_menu()
 {
   createXMLHttpRequest();
    
	var s_id = '';
	var s_name = '';
	var s_path = '';
	var s_sta = '';
	var s_desc = '';
	var s_stsuse = '';
	var s_alert = '';
	var msg = 'ตรวจพบข้อผิดพลาด\r\n-----------------------------------------------------------------------';
	var veri_msg = msg;
	if($('input[name="h_menuid[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุไอดีเมนูไม่ถูกต้องหรือไม่มีการระบุไอดีเมนู';

	}
	
	if($('input[name="h_menuname[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุชื่อเมนูไม่ถูกต้องหรือไม่มีการระบุชื่อเมนู';

	}
	
	if($('input[name="h_menupath[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุพาธเมนูไม่ถูกต้องหรือไม่มีการระบุพาธเมนู';

	}
	
	if($('input[name="h_menu_desc[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> ระบุคำอธิบายเมนู';

	}
	
	if(veri_msg!=msg)
	{
		alert(veri_msg);
		return false;
	}
	else
	{	
	
		var i = 0;
		var all_row = $('#tb_add_menu tbody tr').length;
		
		var menu_id = $('input[name="a_ids[]"]');
		var menu_name = $('input[name="a_menu_name[]"]');
		var menu_path = $('input[name="a_path[]"]');
		var menu_status = $('select[name="a_status[]"]');
		var menu_desc = $('input[name="a_menu_desc[]"]');
		var menu_status_use = $('select[name="a_menu_status_use[]"]');
		var menu_alertadmin = document.getElementsByName("a_alert[]");

		while(i<all_row)
		{
			if(s_id=='')
			{
				s_id = $(menu_id[i]).val();
			}
			else
			{
				s_id+=','+$(menu_id[i]).val();
			}
			
			if(s_name=='')
			{
				s_name = $(menu_name[i]).val();
			}
			else
			{
				s_name+=','+$(menu_name[i]).val();
			}
			
			if(s_path=='')
			{
				s_path = $(menu_path[i]).val();
			}
			else
			{
				s_path+=','+$(menu_path[i]).val();
			}
			
			if(s_sta=='')
			{
				s_sta = $(menu_status[i]).val();
			}
			else
			{
				s_sta+=','+$(menu_status[i]).val();
			}
			
			if(s_desc=='')
			{
				s_desc = $(menu_desc[i]).val();
			}
			else
			{
				s_desc+=','+$(menu_desc[i]).val();
			}
			
			if(s_stsuse=='')
			{
				s_stsuse = $(menu_status_use[i]).val();
			}
			else
			{
				s_stsuse+=','+$(menu_status_use[i]).val();
			}
			//รับค่าสถานะ isAlert จาก Checkbox 
			if(s_alert=='') //ตัวแปลยังไม่มีค่าในรอบแรก หรือ รับข้อมูลชุดเดียว
			{
				if(menu_alertadmin[i].checked==true){
					s_alert = '1';
				}else{
					s_alert = '0';
				}
			} else { //ตัวแปลมีค่าจากรอบแรกแล้ว จึงต่อสตริงให้ตัวแปลเป็น array หรือรับค่ามากกว่า 1 ชุด
				if(menu_alertadmin[i].checked==true){
					s_alert+= ',1';
				}else{
					s_alert+= ',0';
				}
			}
			i++;
			
		}
		
		xmlHttp.open("get", "add_menu_process.php?f_id="+s_id+"&fmenu_name="+s_name+"&f_path="+s_path+"&f_sta="+s_sta+"&f_desc="+s_desc+"&f_stsuse="+s_stsuse+"&f_alert="+s_alert,true); 
		 
	
										   
		xmlHttp.onreadystatechange = function () {
			if (xmlHttp.readyState == 4) {
				if (xmlHttp.status == 200) {
					displayInfo(xmlHttp.responseText);
				} else {
					displayInfo("พบข้อผิดพลาด: " + xmlHttp.statusText); 
				}
			}
		};
		xmlHttp.send(null);
	}
}

function displayInfo() {
		
	document.getElementById("divInfo").innerHTML = xmlHttp.responseText;
	
	setTimeout("urls();",2000);	
	 
}
function urls() {
		
		   
  window.location.href = 'menu_manage.php';
				
	 
}
function pre_chk_menu_id(){
	var elem = $('input[name="a_ids[]"]');
	var row = $(elem).length;
	var i = 0;
	var input_id = undefined;
	while(i<row)
	{
		input_id = $(elem[i]).attr('id');
		chk_menu_id(input_id);
		i++;
	}
}
function chk_menu_id(elem){
	var menu_id = $('#'+elem).val();
	
	if(menu_id=='')
	{
		$('#'+elem).css('background-color','#ffffff');
		$('#'+elem).parent().find('input[name="h_menuid[]"]').val('0');
	}
	else
	{
		$.post('chk_menu_id.php',{menuid:menu_id},function(data){
			if(data=='1')
			{
				var dup_row = $('input[name="a_ids[]"]:val('+menu_id+')');
				var sum_dup = $(dup_row).length;
				if(sum_dup<=1)
				{
					$('#'+elem).css('background-color','#b3ffc1');
					$('#'+elem).parent().find('input[name="h_menuid[]"]').val('1');
				}
				else
				{
					$('#'+elem).css('background-color','#ffbaba');
					$('#'+elem).parent().find('input[name="h_menuid[]"]').val('0');
				}
			}
			else if(data=='2')
			{
				$('#'+elem).css('background-color','#ffbaba');
				$('#'+elem).parent().find('input[name="h_menuid[]"]').val('0');
			}
		});
	}
}

function pre_chk_menu_name(){
	var elem = $('input[name="a_menu_name[]"]');
	var row = $(elem).length;
	var i = 0;
	var input_id = undefined;
	while(i<row)
	{
		input_id = $(elem[i]).attr('id');
		chk_menu_name(input_id);
		i++;
	}
}

function chk_menu_name(elem){
	var menu_name = $('#'+elem).val();
	
	if(menu_name=='')
	{
		$('#'+elem).css('background-color','#ffffff');
		$('#'+elem).parent().find('input[name="h_menuname[]"]').val('0');
	}
	else
	{
		$.post('chk_menu_name.php',{menu_name:menu_name},function(data){
			if(data=='1')
			{
				var dup_row = $('input[name="a_menu_name[]"]:val('+menu_name+')');
				var sum_dup = $(dup_row).length;
				if(sum_dup<=1)
				{
					$('#'+elem).css('background-color','#b3ffc1');
					$('#'+elem).parent().find('input[name="h_menuname[]"]').val('1');
				}
				else
				{
					$('#'+elem).css('background-color','#ffbaba');
					$('#'+elem).parent().find('input[name="h_menuname[]"]').val('0');
				}
			}
			else if(data=='2')
			{
				$('#'+elem).css('background-color','#ffbaba');
				$('#'+elem).parent().find('input[name="h_menuname[]"]').val('0');
			}
		});
	}
}

function pre_chk_menu_path(){
	var elem = $('input[name="a_path[]"]');
	var row = $(elem).length;
	var i = 0;
	var input_id = undefined;
	while(i<row)
	{
		input_id = $(elem[i]).attr('id');
		chk_menu_path(input_id);
		i++;
	}
}

function chk_menu_path(elem){
	var menu_path = $('#'+elem).val();
	
	if(menu_path=='')
	{
		$('#'+elem).css('background-color','#ffffff');
		$('#'+elem).parent().find('input[name="h_menupath[]"]').val('0');
	}
	else
	{
		$.post('chk_menu_path.php',{menu_path:menu_path},function(data){
			if(data=='1')
			{
				var dup_row = $('input[name="a_path[]"]:val('+menu_path+')');
				var sum_dup = $(dup_row).length;
				if(sum_dup<=1)
				{
					$('#'+elem).css('background-color','#b3ffc1');
					$('#'+elem).parent().find('input[name="h_menupath[]"]').val('1');
				}
				else
				{
					$('#'+elem).css('background-color','#ffbaba');
					$('#'+elem).parent().find('input[name="h_menupath[]"]').val('0');
				}
			}
			else if(data=='2')
			{
				$('#'+elem).css('background-color','#ffbaba');
				$('#'+elem).parent().find('input[name="h_menupath[]"]').val('0');
			}
		});
	}
}
function pre_chk_menu_desc(){
	var elem = $('input[name="a_menu_desc[]"]');
	var row = $(elem).length;
	var i = 0;
	var input_id = undefined;
	while(i<row)
	{
		input_id = $(elem[i]).attr('id');
		chk_menu_desc(input_id);
		i++;
	}
}
function chk_menu_desc(elem){
	var menu_name = $('#'+elem).val();
	
	if(menu_name=='')
	{
		$('#'+elem).css('background-color','#ffffff');
		$('#'+elem).parent().find('input[name="h_menu_desc[]"]').val('0');
	}
	else
	{
		$('#'+elem).css('background-color','#b3ffc1');
		$('#'+elem).parent().find('input[name="h_menu_desc[]"]').val('1');
	}
}

function add_new_row(){
	var cur_row = parseInt($('#run_rows').val());
	cur_row++;
	
	var new_row = '<tr style="background-color:#B7B7B7;">'
	+'<td>'
	+'<input type="text" size="10" name="a_ids[]" id="a_ids'+cur_row+'" value="" onkeyup="pre_chk_menu_id();" />'
	+'<input type="hidden" name="h_menuid[]" id="h_menuid'+cur_row+'" value="0" />'
	+'</td>'
	+'<td>'
	+'<input type="text" name="a_menu_name[]" id="a_menu_name'+cur_row+'" value="" onkeyup="pre_chk_menu_name();" />'
	+'<input type="hidden" name="h_menuname[]" id="h_menuname'+cur_row+'" value="0" />'
	+'</td>'
	+'<td>'
	+'<input type="text" size="35" name="a_path[]" id="a_path'+cur_row+'" value="" onkeyup="pre_chk_menu_path();" />'
	+'<input type="hidden" name="h_menupath[]" id="h_menupath'+cur_row+'" value="0" />'
	+'</td>'
	+'<td>'
	+'<select name="a_status[]" id="a_status'+cur_row+'">'
	+'<option value="1" selected="selected">ใช้งาน</option>'
	+'<option value="2">ระงับการใช้งาน</option>'
	+'</select>'
	+'</td>'
	+'<td>'
	+'<input type="text" size="35" name="a_menu_desc[]" id="a_menu_desc'+cur_row+'" value="" onkeyup="pre_chk_menu_desc();" />'
	+'<input type="hidden" name="h_menu_desc[]" id="h_menu_desc'+cur_row+'" value="0" />'
	+'</td>'
	+'<td>'
	+'<select name="a_menu_status_use[]" id="a_menu_status_use'+cur_row+'">'
	+'<option value="ยังใช้อยู่" selected="selected">ยังใช้อยู่</option>'
	+'<option value="ล้าสมัย">ล้าสมัย</option>'
	+'<option value="เลิกใช้">เลิกใช้</option>'
	+'</select>'
	+'</td>'
	+'<td>'
	+'<input type="checkbox"  name="a_alert[]" id="a_alert'+cur_row+'" value="1"/>'
	+'</td>'
	+'</tr>'
	
	$('#tb_add_menu tbody').append(new_row);
	$('#run_rows').val(cur_row);
}

function remove_new_row(){
	$('#tb_add_menu tbody tr:last').remove();
}
		
</script>

<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>
<div id="swarp" style="width:1000px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:1000px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "></span><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:1000px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><a href="user_manage.php">จัดการผู้ใช้</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="menu_manage.php">จัดการเมนู</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr /></div>
<div id="contentpage" style="height:auto;">
 
 <div class="style5" style="width:auto;  padding-left:10px;">
 <input type="hidden" name="run_rows" id="run_rows" value="1" />
<table id="tb_add_menu" width="930" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
    <thead>
        <tr style="background-color:#D0DCA0;">
        <td>id_menu</td>
        <td>name_menu</td>
        <td>path menu </td>
        <td>status</td>
        <td>คำอธิบายเมนู</td>
        <td>การใช้งานปัจจุบัน</td>
		<td>alert admin</td>
        </tr>
    </thead>
    <tbody>
        <tr style="background-color:#B7B7B7;">
            <td>
            	<input type="text" size="10" name="a_ids[]" id="a_ids1" value="" onkeyup="pre_chk_menu_id();" />
            	<input type="hidden" name="h_menuid[]" id="h_menuid1" value="0" />
            </td>
            <td>
            	<input type="text" name="a_menu_name[]" id="a_menu_name1" value="" onkeyup="pre_chk_menu_name();" />
                <input type="hidden" name="h_menuname[]" id="h_menuname1" value="0" />
            </td>
            <td>
            	<input type="text" size="35" name="a_path[]" id="a_path1" value="" onkeyup="pre_chk_menu_path();" />
                <input type="hidden" name="h_menupath[]" id="h_menupath1" value="0" />
            </td>
            <td>
                <select name="a_status[]" id="a_status1">
                    <option value="1" selected="selected">ใช้งาน</option>
                    <option value="2">ระงับการใช้งาน</option>
                </select>
            </td>
			<td>
            	<input type="text" size="35" name="a_menu_desc[]" id="a_menu_desc1" value="" onkeyup="pre_chk_menu_desc();"/>
                <input type="hidden" name="h_menu_desc[]" id="h_menu_desc1" value="0" />
            </td>
			<td>
                <select name="a_menu_status_use[]" id="a_menu_status_use1">
                    <option value="ยังใช้อยู่" selected="selected">ยังใช้อยู่</option>
                    <option value="ล้าสมัย">ล้าสมัย</option>
                    <option value="เลิกใช้">เลิกใช้</option>
                </select>
            </td>
			<td>
				<input type="checkbox"  name="a_alert[]" id="a_alert1" />
			</td>
        </tr>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="6" align="center">
            	<input type="button" name="btn_add_row" id="btn_add_row" onclick="add_new_row();" value="เพิ่มรายการ" style="cursor:pointer;" />
                <input type="button" name="btn_remove_row" id="btn_remove_row" onclick="remove_new_row();" value="ลบรายการ" style="cursor:pointer;" />
            </td>
        </tr>
        <tr>
        <td colspan="6"><input type="button" value="SAVE" onclick="add_menu();"  /><div id="divInfo"></div></td>
        </tr>
    </tfoot>
</table>
</div>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>
