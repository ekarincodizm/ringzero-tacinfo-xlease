<?php
include("../../config/config.php");
include("../function/nameMonth.php");

// ============================================================================================
// รับค่าที่ผู้ใช้งานเลือกจากหน้าหลัก
// ============================================================================================
$datepicker = $_GET['datepicker']; //วันที่สนใจ
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
// ============================================================================================
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
<link href="list_tab.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&datepicker='+'<?php echo $datepicker; ?>'+'&contype='+'<?php echo $sendget;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?datepicker='+'<?php echo $datepicker; ?>'+'&contype='+'<?php echo $sendget;?>',function(){
		list_tab_menu('0');
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&datepicker='+'<?php echo $datepicker; ?>'+'&contype='+'<?php echo $sendget;?>');
}

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
	
</head>
<body>

<center>
<h2>(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (บัญชี)-ตามปีลูกหนี้</h2>
</center>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>เงื่อนไขในการแสดง</B></legend>
				<table  width="100%" >
					<tr>
						<td align="center">
							แสดงเฉพาะ : 
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
						</td>					
					</tr>
					<tr>
						<td>
							<p align="center">
							<label><b>วันที่</b></label>
							<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
							<input type="hidden" name="val" value="1"/>
						</p>
						</td>
					</tr>
				</table>
				<div align="right"><a href="debt_due_acc_year_excel.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendget; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์ EXCEL)</span></a><a href="debt_due_acc_year_pdf.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendget; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์ PDF)</span></a></div>
			</fieldset>
			<div id="tab_showgroup"></div>
		</td>
	</tr>
</table>
</body>
</html>