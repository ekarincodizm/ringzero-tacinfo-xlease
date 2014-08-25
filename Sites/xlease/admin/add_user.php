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
<title>เพิ่มผู้ใช้งาน</title>
<script type="text/javascript" src="autocomplete.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
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
 
function save_detail() 
{
	createXMLHttpRequest();
	var elemtitle=$('input[name="a_title[]"]');
	var elemfname=$('input[name="a_fname[]"]');
	var elemlname=$('input[name="a_lname[]"]');
	var elemnickname=$('input[name="a_nickname[]"]');
	var username1 = '';
	var password1 = '';
	var title1 = '';
	var fname1 = '';
	var lname1 = '';
	var nickname1 = '';
	var grp1 = '';
	var fd1 = '';
	var office1 = '';
	var state1 = '';
	var p;
	
	var msg = 'ตรวจพบข้อผิดพลาด\r\n-----------------------------------------------------------------------';
	var veri_msg = msg;
	if($('input[name="h_username[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุ username ไม่ถูกต้องหรือไม่มีการระบุ username';

	}
	
	if($('input[name="h_pwd[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุรหัสผ่านไม่ถูกต้องหรือไม่มีการระบุรหัสผ่าน';

	}
	
	if($('input[name="a_title[]"]').length!=0)
	{
		p=0;
		for( i=0; i<elemtitle.length; i++ ){
			if($(elemtitle[i]).val()==""){
				p++;
			}
		}
		if(p>0){
			veri_msg+='\r\n\t--> กรุณาระบุคำนำหน้าหรือระบุคำนำหน้าให้ครบ';
		}

	}
	
	if($('input[name="a_fname[]"]').length!=0)
	{
		p=0;
		for( i=0; i<elemfname.length; i++ ){
			if($(elemfname[i]).val()==""){
				p++;
			}
		}
		if(p>0){
			veri_msg+='\r\n\t--> กรุณาระบุชื่อหรือระบุชื่อให้ครบ';
		}
	}
	if($('input[name="a_lname[]"]').length!=0)
	{
		p=0;
		for( i=0; i<elemlname.length; i++ ){
			if($(elemlname[i]).val()==""){
				p++;
			}
		}
		if(p>0){
			veri_msg+='\r\n\t--> กรุณาระบุนามสกุลหรือระบุนามสกุลให้ครบ';
		}
	}
	if($('input[name="a_nickname[]"]').length!=0)
	{
		p=0;
		for( i=0; i<elemnickname.length; i++ ){
			if($(elemnickname[i]).val()==""){
				p++;
			}
		}
		if(p>0){
			veri_msg+='\r\n\t--> กรุณาระบุชื่ีอเล่นหรือระบุชื่อเล่นให้ครบ';
		}		
	}
	if($('input[name="h_grp[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุกลุ่มผู้ใช้ไม่ถูกต้องหรือไม่มีการระบุกลุ่มผู้ใช้';

	}

	if($('input[name="h_fd[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุฝ่ายไม่ถูกต้องหรือไม่มีการระบุฝ่าย';

	}
	if($('input[name="h_office[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุ office ไม่ถูกต้องหรือไม่มีการระบุ office';

	}
	if($('input[name="h_status[]"]:val(0)').length!=0)
	{
		veri_msg+='\r\n\t--> มีการระบุสถานะไม่ถูกต้องหรือไม่มีการระบุสถานะ';

	}
	
	if(veri_msg!=msg)
	{
		alert(veri_msg);
		return false;
	}
	else
	{
		var i = 0;
		var all_row = $('tbody tr').length;
		
		var username = $('input[name="a_username[]"]');
		var password = $('input[name="a_password[]"]');
		var title = $('input[name="a_title[]"]');
		var fname = $('input[name="a_fname[]"]');
		var lname = $('input[name="a_lname[]"]');
		var nickname = $('input[name="a_nickname[]"]');
		var grp = $('select[name="a_gp[]"]');
		var fd = $('select[name="a_fd[]"]');
		var office = $('select[name="a_ofiice[]"]');
		var state = $('select[name="a_status[]"]');
		while(i<all_row)
		{
			if(username1=='')
			{
				username1 = $(username[i]).val();
			}
			else
			{
				username1+=','+$(username[i]).val();
			}
			
			if(password1=='')
			{
				password1 = $(password[i]).val();
			}
			else
			{
				password1+=','+$(password[i]).val();
			}
			
			if(title1=='')
			{
				title1 = $(title[i]).val();
			}
			else
			{
				title1+=','+$(title[i]).val();
			}
			
			if(fname1=='')
			{
				fname1 = $(fname[i]).val();
			}
			else
			{
				fname1+=','+$(fname[i]).val();
			}
			if(lname1=='')
			{
				lname1 = $(lname[i]).val();
			}
			else
			{
				lname1+=','+$(lname[i]).val();
			}
			if(nickname1=='')
			{
				nickname1 = $(nickname[i]).val();
			}
			else
			{
				nickname1+=','+$(nickname[i]).val();
			}
			if(grp1=='')
			{
				grp1 = $(grp[i]).val();
			}
			else
			{
				grp1+=','+$(grp[i]).val();
			}
			if(fd1=='')
			{
				fd1 = $(fd[i]).val();
			}
			else
			{
				fd1+=','+$(fd[i]).val();
			}
			if(office1=='')
			{
				office1 = $(office[i]).val();
			}
			else
			{
				office1+=','+$(office[i]).val();
			}
			if(state1=='')
			{
				state1 = $(state[i]).val();
			}
			else
			{
				state1+=','+$(state[i]).val();
			}
			
			i++;
		}
		
		
		xmlHttp.open("get", "save_user.php?f_title="+title1+"&f_fullname="+fname1+"&f_lname="+lname1+"&f_nickname="+nickname1+"&f_username="+username1+"&f_pass="+password1+"&f_gp="+grp1+"&f_fd="+fd1+"&f_office="+office1+"&f_status="+state1,true);
							   
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
}

function sum_row(){
	var all_row = $('tbody tr').length;
	
	return all_row;
}

function add_row(){
	var running = parseInt($('#running').val());
	var  row = '<tr style="background-color:#EEF2DB;">'
        +'<td>'
		+'<input type="text" name="a_username[]" id="a_username'+(running+1)+'" value="" size="10" onkeyup="pre_chk_username();" />'
		+'<input type="hidden" name="h_username[]" id="h_username'+(running+1)+'" value="0" />'
		+'</td>'
        +'<td>'
		+'<input type="text" name="a_password[]" id="a_password'+(running+1)+'"value="" size="10" onkeyup="chk_pwd();" />'
		+'<input type="hidden" name="h_pwd[]" id="h_pwd'+(running+1)+'" value="0" />'
		+'</td>'
        +'<td>'
		+'<input type="text" name="a_title[]" id="a_title'+(running+1)+'" value=""size="10" />'
		+'<input type="hidden" name="h_title[]" id="h_title'+(running+1)+'" value="0" />'
		+'</td>'
        +'<td><input type="text" name="a_fname[]" id="a_fname'+(running+1)+'" value=""size="15" /></td>'
		+'<input type="hidden" name="h_fname[]" id="h_fname'+(running+1)+'" value="0" />'
        +'<td><input type="text" name="a_lname[]" id="a_lname'+(running+1)+'" value=""size="15" /></td>'
		+'<input type="hidden" name="h_lname[]" id="h_lname'+(running+1)+'" value="0" />'
		+'<td><input type="text" name="a_nickname[]" id="a_nickname1" value=""size="10" /></td>'
		+'<input type="hidden" name="h_nickname[]" id="h_nickname'+(running+1)+'" value="0" />'
        +'<td>'
        +'<select name="a_gp[]" id="a_gp'+(running+1)+'" onchange="chk_grp();">'
		+'<option value="" >---เลือก---</option>'
        <?php
        $qry_gpuser=pg_query("select * from department order by dep_id");
        while($resg=pg_fetch_array($qry_gpuser))
         {
        ?>
         +'<option value="<?php echo $resg["dep_id"]; ?>"><?php echo $resg["dep_name"]; ?></option>'
        <?php
         }
        ?>  
        
        
        +'</select>'
		+'<input type="hidden" name="h_grp[]" id="h_grp'+(running+1)+'" value="0" />'
        +'</td>'
        +'<td>'
        +'<select name="a_fd[]" id="a_fd'+(running+1)+'" onchange="chk_fd();">'
        +'<option value="" >---เลือก---</option>'
        <?php
        $qry_dep=pg_query("select * from f_department where fstatus='TRUE' order by fdep_id");
        while($resd=pg_fetch_array($qry_dep))
         {
        ?>
         +'<option value="<?php echo $resd["fdep_id"]; ?>"><?php echo $resd["fdep_name"]; ?></option>'
        <?php
         }
        ?>  
        +'</select>'
		+'<input type="hidden" name="h_fd[]" id="h_fd'+(running+1)+'" value="0" />'
        +'</td>'
        +'<td>'
        +'<select name="a_ofiice[]" id="a_office'+(running+1)+'" onchange="chk_office();">'
		+'<option value="" >---เลือก---</option>'
        +'<option value="<?php echo $_SESSION["session_company_nv"]; ?>">NV [<?php echo $_SESSION["session_company_nv"]; ?>]</option>'
        +'<option value="<?php echo $_SESSION["session_company_jr"]; ?>">JR[<?php echo $_SESSION["session_company_jr"]; ?>]</option>'
        +'<option value="<?php echo $_SESSION["session_company_tv"]; ?>">TV[<?php echo $_SESSION["session_company_tv"]; ?>]</option>'
        +'</select>'
		+'<input type="hidden" name="h_office[]" id="h_office'+(running+1)+'" value="0" />'
        +'</td>'
        +'<td>'
        +'<select name="a_status[]" id="a_status'+(running+1)+'" onchange="chk_status();">'
		+'<option value="" >---เลือก---</option>'
        +'<option value="1">ใช้งาน</option>'
        +'<option value="0">ระงับการใช้งาน</option>'
        +'</select>'
		+'<input type="hidden" name="h_status[]" id="h_status'+(running+1)+'" value="0" />'
		+'</td>'
      	+'</tr>';
		
		$('tbody').append(row);
		$('#running').val(running+1)
}

function delete_row(){
	var all_row = sum_row();
	if(all_row>1)
	{
		$('tbody tr:last').remove();
	}
}
function pre_chk_username(){
	var elem = $('input[name="a_username[]"]');
	var row = $(elem).length;
	var i = 0;
	var input_id = undefined;
	while(i<row)
	{
		input_id = $(elem[i]).attr('id');
		chk_username(input_id);
		i++;
	}
}
function chk_username(elem){
	var username = $('#'+elem).val();
	
	if(username=='')
	{
		$('#'+elem).css('background-color','#ffffff');
		$('#'+elem).parent().find('input[name="h_username[]"]').val('0');
	}
	else
	{
		$.post('chk_username.php',{username:username},function(data){
			if(data=='1')
			{
				var dup_row = $('input[name="a_username[]"]:val('+username+')');
				var sum_dup = $(dup_row).length;
				if(sum_dup<=1)
				{
					$('#'+elem).css('background-color','#b3ffc1');
					$('#'+elem).parent().find('input[name="h_username[]"]').val('1');
				}
				else
				{
					$('#'+elem).css('background-color','#ffbaba');
					$('#'+elem).parent().find('input[name="h_username[]"]').val('0');
				}
			}
			else if(data=='2')
			{
				$('#'+elem).css('background-color','#ffbaba');
				$('#'+elem).parent().find('input[name="h_username[]"]').val('0');
			}
		});
	}
}

function chk_pwd(){
	var elem = $('input[name="a_password[]"]');
	var row = $(elem).length;
	var i = 0;
	while(i<row)
	{
		if($(elem[i]).val()=='')
		{
			$(elem[i]).css('background-color','#ffffff');
			$(elem[i]).parent().find('input[name="h_pwd[]"]').val('0');
		}
		else
		{
			$(elem[i]).css('background-color','#b3ffc1');
			$(elem[i]).parent().find('input[name="h_pwd[]"]').val('1');
		}
		i++;
	}
}

function chk_grp(){
	var elem = $('select[name="a_gp[]"]');
	var row = $(elem).length;
	var i = 0;
	while(i<row)
	{
		if($(elem[i]).val()=='')
		{
			$(elem[i]).css('background-color','#ffffff');
			$(elem[i]).parent().find('input[name="h_grp[]"]').val('0');
		}
		else
		{
			$(elem[i]).css('background-color','#b3ffc1');
			$(elem[i]).parent().find('input[name="h_grp[]"]').val('1');
		}
		i++;
	}
}
function chk_fd(){
	var elem = $('select[name="a_fd[]"]');
	var row = $(elem).length;
	var i = 0;
	while(i<row)
	{
		if($(elem[i]).val()=='')
		{
			$(elem[i]).css('background-color','#ffffff');
			$(elem[i]).parent().find('input[name="h_fd[]"]').val('0');
		}
		else
		{
			$(elem[i]).css('background-color','#b3ffc1');
			$(elem[i]).parent().find('input[name="h_fd[]"]').val('1');
		}
		i++;
	}
}
function chk_office(){
	var elem = $('select[name="a_ofiice[]"]');
	var row = $(elem).length;
	var i = 0;
	while(i<row)
	{
		if($(elem[i]).val()=='')
		{
			$(elem[i]).css('background-color','#ffffff');
			$(elem[i]).parent().find('input[name="h_office[]"]').val('0');
		}
		else
		{
			$(elem[i]).css('background-color','#b3ffc1');
			$(elem[i]).parent().find('input[name="h_office[]"]').val('1');
		}
		i++;
	}
}
function chk_status(){
	var elem = $('select[name="a_status[]"]');
	var row = $(elem).length;
	var i = 0;
	while(i<row)
	{
		if($(elem[i]).val()=='')
		{
			$(elem[i]).css('background-color','#ffffff');
			$(elem[i]).parent().find('input[name="h_status[]"]').val('0');
		}
		else
		{
			$(elem[i]).css('background-color','#b3ffc1');
			$(elem[i]).parent().find('input[name="h_status[]"]').val('1');
		}
		i++;
	}
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
<div id="swarp" style="width:1050px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "></span><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:1050px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><a href="user_manage.php">จัดการผู้ใช้</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="menu_manage.php">จัดการเมนู</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr /></div>
<div id="contentpage" style="height:auto; padding-left:10px; padding-right:10px;">
 
  <form method="post" action="save_user.php" >
  <input type="hidden" name="running" id="running" value="1" />
  <table width="100%" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
  <thead>
      <tr style="background-color:#D0DCA0;">
        <td>username</td>
        <td>password</td>
        <td>คำนำหน้า</td>
        <td>ชื่อ</td>
        <td>นามสกุล</td>
        <td>ชื่อเล่น</td>
        <td>กลุ่มผู้ใช้</td>
        <td>ฝ่าย</td>
        <td>office</td>
        <td width="113">status</td>
      </tr>
  </thead>
  <tbody>
      <tr style="background-color:#EEF2DB;">
        <td>
            <input type="text" name="a_username[]" id="a_username1" value="" size="10" onkeyup="pre_chk_username();" />
            <input type="hidden" name="h_username[]" id="h_username1" value="0" />
        </td>
        <td>
            <input type="password" name="a_password[]" id="a_password1" value="" size="10" onkeyup="chk_pwd();" />
            <input type="hidden" name="h_pwd[]" id="h_pwd1" value="0" />
        </td>
        <td>
            <input type="text" name="a_title[]" id="a_title1" value=""size="10" />
			<input type="hidden" name="h_title[]" id="h_title1" value="0" />
        </td>
        <td>
        	<input type="text" name="a_fname[]" id="a_fname1" value=""size="15" />
			<input type="hidden" name="h_fname[]" id="h_fname1" value="0" />
        </td>
        <td>
        	<input type="text" name="a_lname[]" id="a_lname1" value=""size="15" />
			<input type="hidden" name="h_lname[]" id="h_lname1" value="0" />
        </td>
        <td>
        	<input type="text" name="a_nickname[]" id="a_nickname1" value=""size="10" />
			<input type="hidden" name="h_nickname[]" id="h_nickname1" value="0" />
        </td>
        <td>
            <select name="a_gp[]" id="a_gp1" onchange="chk_grp();">
            <option value="" >---เลือก---</option>
            <?php
            $qry_gpuser=pg_query("select * from department order by dep_id");
            while($resg=pg_fetch_array($qry_gpuser))
             {
            ?>
              <option value="<?php echo $resg["dep_id"]; ?>"><?php echo $resg["dep_name"]; ?></option>
            <?php
             }
            ?>  
            
            
            </select>
            <input type="hidden" name="h_grp[]" id="h_grp1" value="0" />
        </td>
        <td>
            <select name="a_fd[]" id="a_fd1" onchange="chk_fd();">
            <option value="" >---เลือก---</option>
            <?php
            $qry_dep=pg_query("select * from f_department where fstatus='TRUE' order by fdep_id");
            while($resd=pg_fetch_array($qry_dep))
             {
            ?>
              <option value="<?php echo $resd["fdep_id"]; ?>"><?php echo $resd["fdep_name"]; ?></option>
            <?php
             }
            ?>  
            </select>
            <input type="hidden" name="h_fd[]" id="h_fd1" value="0" />
        </td>
        <td>
            <select name="a_ofiice[]" id="a_office1" onchange="chk_office();">
            	<option value="" >---เลือก---</option>
                <option value="<?php echo $_SESSION["session_company_nv"]; ?>">NV [<?php echo $_SESSION["session_company_nv"]; ?>]</option>
                <option value="<?php echo $_SESSION["session_company_jr"]; ?>">JR[<?php echo $_SESSION["session_company_jr"]; ?>]</option>
                <option value="<?php echo $_SESSION["session_company_tv"]; ?>">TV[<?php echo $_SESSION["session_company_tv"]; ?>]</option>
            </select>
            <input type="hidden" name="h_office[]" id="h_office1" value="0" />
        </td>
        
        <td>
            <select name="a_status[]" id="a_status1" onchange="chk_status();">
            	<option value="" >---เลือก---</option>
               <option value="1">ใช้งาน</option>
               <option value="0">ระงับการใช้งาน</option>
            </select>
            <input type="hidden" name="h_status[]" id="h_status1" value="0" />
      	</td>
      </tr>
	</tbody>
    <tfoot>
        <tr>
            <td colspan="2"><input type="button" value="SAVE" onclick="save_detail()"  /><div id="divInfo"></div></td>
            <td colspan="6" align="center">
                <input type="button" name="btn_add" id="btn_add" value="เพิ่มรายการ" onclick="add_row();" />
                <input type="button" name="btn_delete" id="btn_delete" value="ลบรายการ" onclick="delete_row();" />
            </td>
            <td colspan="2" align="right">
                <span style="text-align:right;">
                    <input name="button" type="button" onclick="javascript:history.back();" value="BACK" />
                </span>
            </td>
        </tr>
	</tfoot>
</table>
</form>
</div>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>
