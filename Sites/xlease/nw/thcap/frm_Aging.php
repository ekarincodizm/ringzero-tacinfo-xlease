<?php
include("../../config/config.php");

$val = pg_escape_string($_POST["val"]);
$typeshow = pg_escape_string($_POST["typeshow"]);

$nowdate = pg_escape_string($_POST["datepicker"]);//วันที่ที่สนใจ
$contypechk = $_POST['contype']; //ประเภทสัญญาที่จะให้แสดง

$afterIncome = pg_escape_string($_POST["afterIncome"]); // ถ้าเป็น on คือ ใช้ยอดลูกหนี้หลังหักรายได้ตั้งพักรอการรับรู้

//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($sendpdf==""){
		$sendpdf = pg_escape_string($contypechk[$con]);
	}else{
		$sendpdf = $sendpdf."@".pg_escape_string($contypechk[$con]);
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	//เมื่อกด ข้อความ  "แสดงเฉพาะ :" 
	$("#selectcontype").click(function(){
	
		var ele_contype = $("input[name=contype[]]");
		if($("#clear").val()== 'Y'){
			$("#clear").val('N');
		}
		else{
			$("#clear").val('Y');
		}
		if($("#clear").val() == 'Y')
		{  	var num=0;
			//ติ้ก ถูกทั้งหมด
			for (i=0; i< ele_contype.length; i++)
			{
				$(ele_contype[i]).attr ( "checked" ,"checked" );
			}
		}
		else
		{ 	//เอาติ้ก ถูก ออก ทั้งหมด
			for (i=0; i< ele_contype.length; i++)
			{
				$(ele_contype[i]).removeAttr('checked');
			}
		}
	
	});
	
	$("#btn00").click(function(){		
		var elem=$('input[name="contype[]"]');
		var checknum = 0;
		for( i=0; i<elem.length; i++ ){
			if($(elem[i]).attr("checked") == true){
				checknum++;
			}
		}
		if(checknum == 0){
			alert("- กรุณาเลือกประเภทที่ต้องการ! -");
		}else if($('#datepicker').val()==""){
			alert("กรุณาเลือกวันที่ที่สนใจ");
		}else{
			$('#form1').submit();
		}
    });
});

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<form method="post" name="form1" id="form1" action="#">
<div style="text-align:center"><h2>(THCAP) Aging ตามระยะเวลาค้างชำระ</h2></div>       
<div style="width:700px;margin:0 auto;padding:15px;">
<div style="text-align:right"><input type="button" value="  Close  " onclick="window.close();"></div>
<fieldset><legend>เงื่อนไขการค้นหา</legend>
	<div class="ui-widget" style="text-align:center;">
		<p>
		<input type="hidden" id="clear" value="Y"/>
		<span id="selectcontype" style="cursor:pointer;"><u><font color="#0000CC"><B>แสดงเฉพาะ :</B></font></u></span>
		<?php 
		//แสดงประเภทสัญญา
			$qry_contype = pg_query("SELECT \"conType\" as contype FROM thcap_contract_type ORDER BY contype ASC");
				$con=0;
			  while($re_contype = pg_fetch_array($qry_contype)){
				$con++;
				$contype = $re_contype['contype'];
				if($contypechk != ""){
					if(in_array($contype,$contypechk)){ $checked = "checked"; }else{ $checked = "";}
				}else{
					$checked = "checked";
				}
					echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" $checked>$contype ";
			  }
		?>
		</p>
		<div style="text-align:center">
			<div>
			<b>รูปแบบแสดงข้อมูล </b>
			<select name="typeshow" id="typeshow">
				<option value="month_level" <?php if($typeshow=='month_level'){ echo "selected"; } ?>>ช่วงเดือน (จัดชั้นลูกหนี้)</option>
				<option value="month_audit" <?php if($typeshow=='month_audit'){ echo "selected"; } ?>>ช่วงเดือน (ความสามารถในการชำระ)</option>
				<option value="month_audit_eq_level" <?php if($typeshow=='month_audit_eq_level'){ echo "selected"; } ?>>ช่วงเดือน (ความสามารถในการชำระ) [ตัดช่วงแบบจัดชั้น]</option>
				<option value="day" <?php if($typeshow=='day'){ echo "selected"; } ?>>ช่วงวันที่</option>
				<option value="month" <?php if($typeshow=='month'){ echo "selected"; } ?>>ช่วงเดือน (2012)</option>
			</select>
			</div>
			<div style="padding:10px;" id="typeday">
				วันที่ที่สนใจ <input type="text" id="datepicker" name="datepicker" value="<?php echo $nowdate; ?>" size="15" style="text-align:center">
				<br>
				<input type="checkbox" name="afterIncome" <?php if($afterIncome == "on"){echo "checked";} ?>> ใช้ยอดลูกหนี้หลังหักรายได้ตั้งพักรอการรับรู้
			</div>
			<hr>
			<div>
			<input type="hidden" name="val" value="1"/>
			<input type="button" id="btn00" value="เริ่มค้น"/>
			</div>
		</div>
	</div>
</fieldset>
</div>
</form>
<?php
//กรณีกดค้นหาให้แสดงส่วนนี้
if($val==1){
	if($typeshow=='day'){
		include "frm_Aging_day.php";
	}else if($typeshow=='month'){
		include "frm_Aging_month.php";
	}else if($typeshow=='month_audit'){
		include "frm_Aging_month_audit.php";
	}else if($typeshow=='month_audit_eq_level'){
		include "frm_Aging_month_audit_eq_level.php";
	}else if($typeshow=='month_level'){
		include "frm_Aging_month_level.php";
	}
}
?>
</body>
</html>