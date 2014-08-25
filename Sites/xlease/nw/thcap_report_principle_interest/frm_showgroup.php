<?php
include("../../config/config.php");
include("../function/nameMonth.php");

// ============================================================================================
// รับค่าที่ผู้ใช้งานเลือกจากหน้าหลัก
// ============================================================================================
$checkoption = $_GET["op1"];
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

IF($checkoption == 'my'){
	$checked1 = "checked";
	$selectMonth = $_GET["month"]; // เดือนที่เลือก
	$selectYear = $_GET["year"]; // ปีที่เลือก
	$where = " EXTRACT(MONTH FROM \"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";

}else if($checkoption == 'y'){
	$checked2 = "checked";
	$selectYear = $_GET["year"]; // ปีที่เลือก
	$where = " EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานเงินต้นดอกเบี้ยรับ</title>
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
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&op1='+'<?php echo $checkoption;?>'+'&month='+'<?php echo $selectMonth; ?>'+'&year='+'<?php echo $selectYear;?>'+'&contype='+'<?php echo $sendget;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?op1='+'<?php echo $checkoption;?>'+'&month='+'<?php echo $selectMonth; ?>'+'&year='+'<?php echo $selectYear;?>'+'&contype='+'<?php echo $sendget;?>',function(){
		list_tab_menu('0');
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&op1='+'<?php echo $checkoption;?>'+'&month='+'<?php echo $selectMonth; ?>'+'&year='+'<?php echo $selectYear;?>'+'&contype='+'<?php echo $sendget;?>');
}
$(document).ready(function(){
	<?php if($checkoption == 'y'){ ?>
			$("#d1").hide();
			$("#d2").show();		
	<?php }else{ ?>
			$("#d1").show();
			$("#d2").show();
	<?php } ?>		
});

function popU(U,N,T){
	newWindow = window.open(U, N, T);
}
function option(){
	if(document.getElementById("op1").checked == true){	
		$("#d1").show();
		$("#d2").show();
	}else if(document.getElementById("op2").checked == true){	
		$("#d1").hide();
		$("#d2").show();		
	}
}
</script>
	
</head>
<body>

<center>
<h2>แสดงรายงานเงินต้นดอกเบี้ยรับตามปีลูกหนี้</h2>
</center>

<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>เลือกช่วงเวลาที่แสดง</B></legend>
				<table  width="100%" >
					<tr>
						<td align="center" height="50">
						<input type="radio" id="op1" name="op1" value="my" <?php echo "$checked1";  ?> disabled>แสดงเฉพาะเดือน-ปี						
						<input type="radio" id="op2" name="op1" value="y" <?php echo "$checked2"; ?>  disabled>แสดงเฉพาะปี
						</td>
					</tr>
					<tr align="center" >						
						<td>
							<span  id="d1" >
								เดือน :
								<select name="month"><option><?php echo $selectMonth; ?></option></select>
								&nbsp;&nbsp;&nbsp;
							</span>
							<span  id="d2" >
								ปี :
								<select name="year"><option><?php echo $selectYear; ?></option></select>
							</span>
						</td>					
					</tr>
					<tr>
						<td align="center">
							แสดงเฉพาะ : 
							<?php 
							//แสดงประเภทสัญญา
							$qry_contype = pg_query("SELECT \"conType\" as contype FROM thcap_contract_type ORDER BY contype asc");
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
				</table>
			</fieldset>
			<div id="tab_showgroup"></div>
		</td>
	</tr>
</table>
</body>
</html>