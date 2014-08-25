<?php
include("../../config/config.php");

$datepicker2 = $_REQUEST[datepicker];
if($datepicker2=="")
$datepicker2 = nowDate();

$name= $_REQUEST[name];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ปรับปรุงเวลาเข้าออกงาน</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
  <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script src="../../jqueryui/js/datetime_picker.js" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript">

$(function(){


	
	 $('#datepicker').change(function(){
	var x = $("#name").val().split('#');
		
		$("#panel").load("time_att_dtl.php?datepicker="+ $("#datepicker").val() +"&id_user="+ x[0]);
		
		
	});	

    
   var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear();
    

	  $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd',

     defaultDate: y+'-'+m+'-'+s,
     dayNames: ['อาทิตย์','จันทร์','อังคาร',
                        'พุธ','พฤหัสบดี','ศุกร์','เสาร์'],
     dayNamesMin: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],
     monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม',
                        'เมษายน','พฤษภาคม','มิถุนายน',
                        'กรกฎาคม','สิงหาคม','กันยายน',
                        'ตุลาคม','พฤศจิกายน','ธันวาคม'],
     monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.',
                         'พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.',
                         'พ.ย.','ธ.ค.']
    });


   /* 
    $('#btn1').click(function(){
        if($('#ty').val() == ""){
            alert('กรุณาเลือกรูปแบบก่อนค่ะ');
            return false;
        }
        $('#panel').empty();
        $("#panel").load("join_discount_report_show.php?datepicker="+ $("#datepicker").val() +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val() +"&ty="+ $("#ty").val());
    });*/
	
	 $("#name").autocomplete({
		source: "user_data.php",       
        minLength:2,
		close: function(event, ui) { var x = $("#name").val().split('#');
		
		$("#panel").load("time_att_dtl.php?datepicker="+ $("#datepicker").val() +"&id_user="+ x[0]);

		}
    });
   
    

    
});
</script>

<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>


        
<fieldset><legend><B>ปรับปรุงเวลาเข้าออกงาน</B></legend>

<div style="margin:5px">




<span id="showtype1"><b> วันที่ : </b>
<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker2; ?>" size="10" style="text-align:center">
</span>


 &nbsp;</span>
<b> รหัส/ชื่อ-นามสกุล/ชื่อเล่น : </b>&nbsp;
			<input id="name" name="name" size="60" value="<?php echo $name ?>" />
<!-- <input type="button" name="btn1" id="btn1" value="แสดง"> -->

</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>
<script type="text/javascript">

<?php if($name!=""){ ?>

		$("#panel").load("time_att_dtl.php?datepicker="+ $("#datepicker").val() +"&id_user=<?php echo $name ?>");
		<?php } ?>
		
		</script>
</body>
</html>