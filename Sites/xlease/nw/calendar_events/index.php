<?php 
session_start(); 
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="css/calendar_events.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<!-- Add jQuery library -->
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="../../jquery_fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="../../jquery_fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="../../jquery_fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="../../jquery_fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
	<link rel="stylesheet" type="text/css" href="../../jquery_fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="../../jquery_fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
	<script type="text/javascript" src="../../jquery_fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

<script type="text/javascript">
$(function(){
    $("#box_tab").tabs();
});

$(document).ready(function() {
});

//========= เปิดหน้าจอบันทึกข้อมูลการนัดหมาย  (frm_add_events.php)=========//
function form_modal(){	
		  $('body').append('<div id="dialog-form"></div>');
		  $('#dialog-form').load('frm_event.php');
		  $('#dialog-form').dialog({
		    title: 'บันทึกการนัดหมาย',
		    resizable: false,
		    modal: true,  
		    width: 650,
		    height: 560,
			close: function(ev, ui){
						$('#dialog-form').remove();
					}
				});	
		}
		
$(function() {
		$( "button:first" ).button({
			icons: {
				primary: "ui-icon-locked"
			},
			//text: false
		}).next().button({
			icons: {
				primary: "ui-icon-gear"
			}
		}).next().button({
			icons: {
				primary: "ui-icon-gear",
			}
		
		});
	});
</script>
</head>
<body>
<div style="display:block;align:center;">
	<table border="1"> 
	<tr>
		<button id="btn_list_events_all" onclick="form_modal();">เพิ่มการนัดหมาย</button> 
		<button id="btn_list_events_all" >ค้นหา</button> 
		<button id="btn_list_events_all">ตั้งค่า</button>
	</tr>
	</table>
<div>
<br>
	<div id="box_tab" style="display:block;width:800px;height:500px;valign:top;"> <!-- เริ่ม tabs -->
		<ul>
		<?php
				echo "<li><a href=\"#show\">แสดงการนัดหมายส่วนตัว (Private)</a></li>";
				echo "<li><a href=\"#add\">แสดงการนัดหมายส่วนกลาง (Public)</a></li>";
		?>
		</ul>
		<div id="show" name="show" style="display:block;width:800px;height:500px;valign:top;">
		<?php  $shared = 0;  include("list_calendar_events.php"); ?>
		</div>

		<div id="add" name="add" style="display:block;width:800px;height:500px;valign:top;">
		<?php $shared = 1; include("list_calendar_events.php"); ?>
		</div>
	</div>
</body>
</html>