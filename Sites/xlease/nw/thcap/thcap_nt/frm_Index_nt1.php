<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}

include("../../../config/config.php");

$nowdate = Date('Y-m-d');
$nowtime=Date('H:i:s');
$page=pg_escape_string($_REQUEST['page']);
$val=pg_escape_string($_REQUEST["val"]);
$val_btn=pg_escape_string($_REQUEST["btn"]);
$tabid=pg_escape_string($_REQUEST["tabid"]);
if($pay==""){$pay=2;}
$sort=pg_escape_string($_GET["sort"]);
$order=pg_escape_string($_GET["order"]);
if($order=="asc"){
	$order2="desc";
}else{
	$order2="asc";
}
if($page==""){
	$page=0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) Create NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
	<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="list_tab.css" rel="stylesheet" type="text/css" /> 
<script language=javascript>
if('<?php echo $val; ?>' != ""){
	
	$(function(){
	var tab_id = $('.active').find('a').attr('id');	
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&val=<?php echo $val;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?val=<?php echo $val;?>',function(){
		list_tab_menu($('#page').val());
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
	
});
}


$(document).ready(function(){	
	$('#t1').click( function(){   
		$('#t1').css('background-color', '#ff6600');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t2').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#ff6600'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t3').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#ff6600'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t4').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#ff6600'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t5').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#ff6600'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t6').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#ff6600'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t7').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#ff6600'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t8').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#ff6600');
		$('#t9').css('background-color', '#79BCFF');
	}); 
	$('#t9').click( function(){   
		$('#t1').css('background-color', '#79BCFF');   
		$('#t2').css('background-color', '#79BCFF'); 
		$('#t3').css('background-color', '#FFB3B3'); 
		$('#t4').css('background-color', '#FFB3B3'); 
		$('#t5').css('background-color', '#FFB3B3'); 
		$('#t6').css('background-color', '#79BCFF'); 
		$('#t7').css('background-color', '#79BCFF'); 
		$('#t8').css('background-color', '#79BCFF');
		$('#t9').css('background-color', '#ff6600');
	}); 
});
function list_tab_menu(tab_id){	
	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active')

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&val=<?php echo $val;?>&sort=<?php echo $sort;?>&order=<?php echo $order;?>');

}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function chk_btn(){	
	$("#btn").val(2);
	$('#tab_showgroup').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	return true;
}
</script>
</head>
<body>

<div class="header"><h1>(THCAP) Create NT</h1></div>
	<div class="wrapper">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.close();"><u>X ปิดหน้าต่าง</u></span></div>
	<fieldset><legend><B>เงื่อนไขการแสดงรายงาน</B></legend>
	<form name="frm_edit" method="post" action="frm_Index_nt1.php">
		<div style="padding:20px;"> 
			<table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-weight:bold;" align="center">
				<tr height="30" bgcolor="#FFFFFF">
					<td align="center">
						<input type="hidden" name="page" id="page" value="<?php echo $page;?>">
						<input type="radio" name="val" value="1" <?php if($val=="" || $val=="1") echo "checked";?>>แสดงเฉพาะที่ค้าง
						<!--เนื่องจากยังไม่ support ในส่วนนี้-->
						<!--input type="radio" name="val" value="2" <?php if($val=="2") echo "checked";?>--><!--แสดงทั้งหมด-->	
						<input type="text" id="btn" name="btn" value="<?php if($val_btn=="") echo "1";?>" hidden>						
						<input type="submit" value="ค้นหา" onclick="return chk_btn()">
					</td>
				</tr>
				</table>
		</div>
	</form>	
	</fieldset><br>
	<font color="red">** ค่าเบี้ยปรับ (ถ้ามี) จะคำนวณล่วงหน้าที่ 45 วัน นับจากวันที่ออกเอกสาร NT1 </font>
	<div id="tab_showgroup" style="width:100%;margin:0 auto;"></div>
</body>
</html>