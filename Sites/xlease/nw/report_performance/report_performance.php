<?php
	$curdate=date("Y-m-d");
	$s_page=$_GET['s_page'];
	$word=$_GET['word'];
	$type=$_GET['type'];
	$date=$_GET['date'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานประเมินการทำงาน</title>
<link href="act.css" rel="stylesheet" type="text/css">
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#tbxSearch").autocomplete({
        source: "find_staff.php",
        minLength:1
    });
});

$(document).ready(function(){
    $("#tbxeachday").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	$("#spanSearch").click(function(){
		var word = $("#tbxSearch").val();
		var type = $("input[@name=testGroup]:checked").val();
		var sdate = $("#tbxeachday").val();
		var s_page = $("#pagehidden").val();
		//alert(s_page);
		if(word!='')
		{
			$("#showcontent").load('result.php?word='+word+'&type='+type+'&date='+sdate+'&s_page='+s_page);
		}
	});
	$(".ui-datepicker-trigger").click(function(){
		$("#rdoeachday").attr("checked","checked");
	});
	$("#spanSearch").click();
});
</script>
<script type="text/javascript">
function textboxStatus(){
	if($("input[@name=testGroup]:checked").val()=='2')
	{
		document.getElementById('tbxeachday').value=$("#datehidden").val();
		document.getElementById('pagehidden').value='';
	}
	else
	{
		document.getElementById('tbxeachday').value='';
		document.getElementById('pagehidden').value='';
	}
}
</script>
</head>
<body>
<input type="hidden" id="datehidden" value="<?php echo $curdate; ?>">
<input type="hidden" id="pagehidden" value="<?php echo $s_page; ?>">
	<div align="center">
    	<div id="container">
        	<div id="divclose" onClick="javascript:window.close()"><img src="images/Close.png"></div>
        	<div id="header">ตรวจสอบการเปิดเมนูรายพนักงาน</div>
            <hr>
            <div id="divcondition">*เริ่มเก็บข้อมูลและใช้วัดได้จริงตั้งแต่วันที่ 18 กรกฎาคม ค.ศ. 2012</div>
            <form name="frm_search" id="frm_search" action="">
                <div id="divsearch">
                    <span>ชื่อพนักงาน : </span>
                    <span><input type="text" name="tbxSearch" id="tbxSearch" value="<?php echo $word; ?>"></span>
                    <span>แสดงข้อมูลแบบ : </span>
                    <span id="spanshowdatatype">
                        <span><input type="radio" name="rdoshowtype" id="rdoshowall" value="1" onChange="textboxStatus();" <?php if($type=="1" || $type==""){echo "checked";} ?>></span>
                        <span>แสดงทุกวัน</span>
                        <span><input type="radio" name="rdoshowtype" id="rdoeachday" value="2" onChange="textboxStatus();" <?php if($type=="2"){echo "checked";} ?>></span>
                        <span>เฉพาะวันที่ : </span>
                        <span><input type="text" name="tbxeachday" id="tbxeachday" <?php if($type=="2" && $date!=""){echo "value=\"$date\"";} ?> disabled></span>
                        <span id="spanSearch">ค้นหา</span>
                    </span>
                </div>
            </form>
            <div id="showcontent"></div>
        </div>
    </div>
</body>
</html>