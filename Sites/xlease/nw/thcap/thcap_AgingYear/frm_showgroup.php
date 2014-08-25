<?php 
//แสดงข้อมูลโดย Group ตามปีที่ทำสัญญา
include("../../../config/config.php");
include("../../function/nameMonth.php");

$datepicker = $_GET['datepicker'];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
$sendget="";
$i=0;
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=""){
		$i++;
		if($i==1){
			$sendget=$contypechk[$con];
		}else{
			$sendget = $sendget."@".$contypechk[$con];
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>(THCAP) Aging ตามระยะเวลาค้างชำระ แสดงตามปีลูกหนี้</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link href="list_tab.css" rel="stylesheet" type="text/css" />

<script language=javascript>
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&datepicker='+'<?php echo $datepicker;?>'+'&contype='+'<?php echo $sendget;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?datepicker='+'<?php echo $datepicker;?>'+'&contype='+'<?php echo $sendget;?>',function(){
		list_tab_menu('0');
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&datepicker='+'<?php echo $datepicker;?>'+'&contype='+'<?php echo $sendget;?>');
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="padding:50px 0px 0px 0px;"></div>
<div style="text-align:center"><h2>(THCAP) Aging ตามระยะเวลาค้างชำระ แสดงตามปีลูกหนี้</h2></div>       
<div style="width:700px;margin:0 auto;padding:15px;">
<div style="text-align:right"><input type="button" value="  Close  " onclick="window.close();"></div>
<fieldset><legend>เงื่อนไขการแสดงข้อมูล</legend>
	<div class="ui-widget" style="text-align:center;">
		<p>
		<b>แสดงเฉพาะ :</b>
		<?php 
		//แสดงประเภทสัญญา
			$qry_contype = pg_query("SELECT distinct(\"conType\") as contype FROM thcap_contract ORDER BY contype");
				$con=0;
			  while($re_contype = pg_fetch_array($qry_contype)){
				$con++;
				$contype = $re_contype['contype'];
				if($contypechk != ""){
					if(in_array($contype,$contypechk)){ $checked = "checked"; }else{ $checked = "";}
				}else{
					$checked = "checked";
				}
					echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" $checked disabled>$contype ";
			  }			
		?>	
		</p>
		<div style="text-align:center">
			<div>
			<b>รูปแบบแสดงข้อมูล </b>
			<select name="typeshow" id="typeshow" disabled>
				<option value="month" <?php if($typeshow=='month'){ echo "selected"; } ?>>ช่วงเดือน</option>
			</select>
			วันที่ที่สนใจ <input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
			</div>
			<div style="text-align:right;padding-top:10px;"><a href="excel_Aging_month_year.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendget; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(Export Excel)</span></a><a href="pdf_Aging_month_year.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendget; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
			<div>
			</div>
		</div>
	</div>
</fieldset>
</div>
<div id="tab_showgroup" style="width:1150px;margin:0 auto;"></div>

</body>
</html>