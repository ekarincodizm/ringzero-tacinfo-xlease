<?php
set_time_limit(0);
include("../config/config.php");
$nowdate = nowDate();//ดึง วันที่จาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#btnshow').click(function(){
       if(document.getElementById("search2").checked){
			if (document.getElementById("year").value =="") {
				alert('กรุณาระบุปี ค.ศ.');
                $('#year').focus();
                $("#btnshow").attr('disabled', false);
                return false;
			}
		}
		var search;
		var years;
		if(document.getElementById("search2").checked){
			search=2;
			years=$('#year').val();
		}else{
			search=1;
			years=0;
		}
		$('#panel').empty();
		$('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$("#panel").load("frm_capitalbalance_show.php?search="+search+"&years="+years+"&date="+ $("#datepicker").val() );
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

function check_search(){
	if(document.getElementById("search1").checked){
		document.getElementById("year").value ='';
		document.getElementById("year").disabled =true;
	}else if(document.getElementById("search2").checked){
		document.getElementById("year").disabled =false;
		document.getElementById("year").focus();
		
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
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>รายงานเงินต้นคงเหลือ</B></legend>

<div align="center">
<div><b>วันที่</b>&nbsp;<input type="text" id="datepicker" name="datepicker" value="<?php echo $nowdate; ?>" size="15" style="text-align:center"></div>
<div style="padding-top:15px;">
<input type="radio" name="typesearch" id="search1" value="1" onclick="check_search()" checked><input type="hidden" name="sent" id="sent" value="s1"> แสดงทั้งหมด
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="typesearch" id="search2" value="2" onclick="check_search()">แยกตามปี <input type="text" name="year" id="year" style="text-align:center;" size="10" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)" disabled> (ปี ค.ศ.)
</div>
<div style="padding:15px;">
<input type="button" id="btnshow" value=" ค้นหา " /></p>
</div>
<div id="panel" align="left" style="margin-top:10px"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>