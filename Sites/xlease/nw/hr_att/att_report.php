<?php
include("../../config/config.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>รายงานการลงเวลาเข้าออกงาน</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
  <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript">
$(function(){
    
    //$('#showtype1').hide();
    $('#showtype1').hide();

    $('#ty').change(function(){
        if($('#ty').val() == 1){
			$('#name').val('');
            $('#showtype1').show();
            $('#showtype2').hide();
        }else if($('#ty').val() == 2){
			$('#name').val('');
            $('#showtype1').hide();
            $('#showtype2').show();
        }else{
            $('#showtype1').hide();
            $('#showtype2').hide();
        }
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
		
		$("#panel").load("att_report_show.php?datepicker="+ $("#datepicker").val() +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val() +"&ty="+ $("#ty").val()+"&id_user="+ x[0]);

		}
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

    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>


        
<fieldset><legend><B>รายงานการลงเวลาเข้าออกงาน</B></legend>

<div style="margin:5px">

<b>เลือกรูปแบบ</b>
<select name="ty" id="ty">
    <option value="2">ประจำเดือน</option>
    <option value="1">ประจำวัน</option>
    
</select>


<span id="showtype1">
<input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15" style="text-align:center">
</span>

<span id="showtype2">
<b>เดือน</b>
<select name="mm" id="mm">
<?php
$nowmonth = date("m");
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
}
?>    
</select>
<b>ปี</b>
<select name="yy" id="yy">
<?php
$nowyear = date("Y");
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 5;

$s_b = $year_b+543;

while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
} 
?>
</select>
 &nbsp;</span>
<b> รหัส/ชื่อ-นามสกุล/ชื่อเล่น :</b>&nbsp;
			<input id="name" name="name" size="60" />
<!-- <input type="button" name="btn1" id="btn1" value="แสดง"> -->

</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>