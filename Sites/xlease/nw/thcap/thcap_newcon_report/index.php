<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
include("../../../config/config.php");

$now_year = date('Y');
$now_month = date('m');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายงานการเปิดสัญญา</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

$(document).ready(function(){
    $('#btn1').click(function(){
		 $("#panel1").text("จัดหาข้อมูล กรุณารอซักครู่...");
		//ตรวจสอบว่า ค้หนหาจาก :
		if(document.getElementById("rdoconDate").checked == true){
		//วันที่ทำสัญญา
			var find_date = $("#rdoconDate").val();
		}else if(document.getElementById("rdoconStartDate").checked == true){
		//วันที่เริ่มกู้/รับของ
			var find_date = $("#rdoconStartDate").val();			
		}
		 
		if(document.getElementById("ra1").checked == true){
			var type = $("#ra1").val();
			var Ystart = $("#year").val();
			var Mstart = $("#month").val();
		}else if(document.getElementById("ra2").checked == true){
			var type = $("#ra2").val();
			var Ystart = $("#year").val();	
		}			
		var contype = "";
		var consum = $("#consum").val();
		for(i=1;i<=consum;i++){	
			if($("#contype"+i).attr("checked") == true){
				contype = contype+"@"+$("#contype"+i).val();
			}	
		}
        $("#panel1").load("frm_report_tb.php?type="+type+"&year="+Ystart+"&month="+Mstart+"&contype="+contype+"&find_date="+find_date);		
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
});

function changecondition(){
	//แสดงรายเดือน
	if(document.getElementById("ra1").checked == true){
		$("#mtxt").show();
		$("#month").show();
	}else if(document.getElementById("ra2").checked == true){
		$("#mtxt").hide();
		$("#month").hide();
	}

}

function sorttb(field,sby,typedate){	
	$("#panel1").text("จัดเรียงข้อมูล กรุณารอซักครู่...");
	var fsort = field;
	var order = sby;
	if(document.getElementById("ra1").checked == true){
			var type = $("#ra1").val();
			var Ystart = $("#year").val();
			var Mstart = $("#month").val();
		}else if(document.getElementById("ra2").checked == true){
			var type = $("#ra2").val();
			var Ystart = $("#year").val();	
		}			
		var contype = "";
		var consum = $("#consum").val();
		for(i=1;i<=consum;i++){	
			if($("#contype"+i).attr("checked") == true){
				contype = contype+"@"+$("#contype"+i).val();
			}	
		}
	$("#panel1").load("frm_report_tb.php?type="+type+"&year="+Ystart+"&month="+Mstart+"&contype="+contype+"&sort="+fsort+"&order="+order+"&find_date="+typedate);	
}
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>

<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
		<form name="frm1">
			<fieldset style="background-color:#EEE9BF"><legend><h3>(THCAP) รายงานการเปิดสัญญา</h3></legend>
				<table width="95%" border="0" cellspacing="1" cellpadding="0" align="center">
						<tr>							
							<td width="15%" align="right" valign="top">								
								<span id="selectcontype"  style="cursor:pointer;"><u><font color="#0000CC"><B>แสดงเฉพาะ :</B></font></u></span>
								<input type="hidden" id="clear" value="Y"/>
							</td>
							<td colspan="3">
									
										<?php $qry_contype = pg_query("SELECT \"conType\" as contype FROM thcap_contract_type order by contype ASC");
												$con=0;
											  while($re_contype = pg_fetch_array($qry_contype)){
												$con++;
													$contype = $re_contype['contype'];
													echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" checked>$contype ";
											  }
												
										?>
										<input type="hidden" value="<?php echo $con; ?>" id="consum">		
										
							</td>						
						</tr>
						<tr>
							<td  align="right">
									แสดงตาม : 
							</td>
							<td width="30%"> 
									<input type="radio" id="ra1" name="ratype" value="sm" onchange="changecondition()" checked>รายเดือน
									<input type="radio" id="ra2" name="ratype" value="sy" onchange="changecondition()">รายปี
							</td>				
							<td colspan="2">
									<font id="mtxt">เดือน : </font>									
									<select id="month" name="month">
										<option value="01" <?php if($now_month == '01'){ echo "selected";} ?>>มกราคม</option>
										<option value="02" <?php if($now_month == '02'){ echo "selected";} ?>>กุมภาพันธ์</option>
										<option value="03" <?php if($now_month == '03'){ echo "selected";} ?>>มีนาคม</option>
										<option value="04" <?php if($now_month == '04'){ echo "selected";} ?>>เมษายน</option>
										<option value="05" <?php if($now_month == '05'){ echo "selected";} ?>>พฤษภาคม</option>
										<option value="06" <?php if($now_month == '06'){ echo "selected";} ?>>มิถุนายน</option>
										<option value="07" <?php if($now_month == '07'){ echo "selected";} ?>>กรกฎาคม</option>
										<option value="08" <?php if($now_month == '08'){ echo "selected";} ?>>สิงหาคม</option>
										<option value="09" <?php if($now_month == '09'){ echo "selected";} ?>>กันยายน</option>
										<option value="10" <?php if($now_month == '10'){ echo "selected";} ?>>ตุลาคม</option>
										<option value="11" <?php if($now_month == '11'){ echo "selected";} ?>>พฤศจิกายน</option>
										<option value="12" <?php if($now_month == '12'){ echo "selected";} ?>>ธันวาคม</option>
									</select>
									
									<font id="ytxt">ปี : </font>									
									<select id="year" name="year">
									<?php
										  for($i=10 ; $i >= 0 ; $i--)
										  {
											$this_year = $now_year - $i;
											$this_year_th = $this_year + 543; ?>
											<option value="<?php echo $this_year ?>" <?php  if($now_year == $this_year){ echo "selected"; } ?> ><?php echo $this_year_th; ?></option>
									<?php } ?>
									</select>
							</td>						
						</tr>
						<tr>
							<td  align="right">ค้นหาจาก :</td>
							<td width="40%"> 
									<input type="radio" id="rdoconDate" name="rdo_Date" value="conDate"  checked>วันที่ทำสัญญา
									<input type="radio" id="rdoconStartDate" name="rdo_Date" value="conStartDate">วันที่เริ่มกู้/รับของ
							</td>				
						</tr>						
						<tr>
							<td></td>
							<td align="center"  height="50px;">
									<input style="width:70px; height:30px;" type="button" id="btn1" value="รายงาน" >
									
							</td>
							<td align="center" width="30%">
								<input style="width:70px; height:30px;" type="button" value="ปิด" onclick="window.close();">
							</td>
							<td></td>
						
						</tr>				
				</table>
			</fieldset>
		</form>	
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center">
			<div id="panel1" style="padding-top: 5px;"></div>
		</td>
	</tr>
</table>	
</html>
