<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");

$now_year = date('Y');
$now_month = date('m');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-- รายงาน สถิติยอดขาย --</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

$(document).ready(function(){

	

    $('#btn1').click(function(){
		var type = $("#ra1").val();
		var report = $("#ta1").val();
		var Ystart = $("#year").val();
		var Mstart = $("#month").val();
		var consum = $("#consum").val();
		var contype = "";
		for(i=1;i<=consum;i++){	
			if($("#contype"+i).attr("checked") == true){
				contype = contype+"@"+$("#contype"+i).val();
			}	
		}
		$("#panel1").load("กำลังโหลดข้อมูล...");
        $("#panel1").load("credit_list.php?type="+ type +"&report=" + report + "&Ystart=" + Ystart + "&Mstart=" + Mstart +"&contypee=" + contype);
		document["chart"].src = "credit_chart.php?type="+ type +"&playback=" + report + "&year=" + Ystart + "&month=" + Mstart +"&contypee=" + contype;

    });
	
	 $('#printpdf').click(function(){
		var type = $("#ra1").val();
		var report = $("#ta1").val();
		var Ystart = $("#year").val();
		var Mstart = $("#month").val();
		var consum = $("#consum").val();
		var contype = "";
		for(i=1;i<=consum;i++){	
			if($("#contype"+i).attr("checked") == true){
				contype = contype+"@"+$("#contype"+i).val();
			}	
		}
       window.open("credit_pdf.php?type="+ type +"&report=" + report + "&Ystart=" + Ystart + "&Mstart=" + Mstart +"&contypee=" + contype,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800');
    });
	
	
	
	
	 $('#chart').click(function(){
		var type = $("#ra1").val();
		var report = $("#ta1").val();
		var Ystart = $("#year").val();
		var Mstart = $("#month").val();
		var consum = $("#consum").val();
		var contype = "";
		for(i=1;i<=consum;i++){	
			if($("#contype"+i).attr("checked") == true){
				contype = contype+"@"+$("#contype"+i).val();
			}	
		}
		window.open("credit_chart.php?type="+ type +"&playback=" + report + "&year=" + Ystart + "&month=" + Mstart +"&contypee=" + contype,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560');
    });
	
	
	
});


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

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;">
<span><?php echo "THAI ACE CAPITAL CO.,LTD." ?></span><br /><?php echo "ไทยเอช แคปปิตอล จำกัด"; ?></div></div>
<div style="clear:both;"></div>


<form name="frm1" method="POST" action="credit_list.php">
<fieldset><legend><h3> THCAP สถิติยอดขายสินเชื่อ </h3></legend>
<table width="850" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td>
					ประเภท : 
					<select name="ra1" id="ra1">
						<option value="a1" select>ยอดสินเชื่อ</option>
						<option value="a2">จำนวนสัญญา</option>
						<option value="a3">ยอดสินเชื่อเฉลี่ยต่อสัญญา</option>
					</select>  
				</td>
				<td>
					รายงาน :
					<select name="ta1" id="ta1">
						<option value="3" select>3 เดือนย้อนหลัง</option>
						<option value="9">6 เดือนย้อนหลัง</option>
						<option value="12">12 เดือนย้อนหลัง</option>
						<option value="24">24 เดือนย้อนหลัง</option>
						<option value="36">36 เดือนย้อนหลัง</option>
					</select>			
				 </td>
				<td>
					เริ่มจากปี : <select id="year" name="year">
					<?php
						for($i=10 ; $i >= 0 ; $i--)
						{
							$this_year = $now_year - $i;
							$this_year_th = $this_year + 543; ?>
							<option value="<?php echo $this_year ?>" <?php  if($now_year == $this_year){ echo "selected"; } ?> ><?php echo $this_year_th; ?></option>
					<?php	
						}
					?>
					</select>
				</td>
				<td >
					เริ่มจากเดือน : <select id="month" name="month">
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
				</td>
				<td colspan="" align="left" rowspan="2">
					<input style="width:60px; height:50px;" type="button" id="btn1" value="รายงาน" >
				</td>
				<td colspan="" align="center">
					<input style="width:70px; height:25px;" type="button" value="ปิด" onclick="window.close();">
				</td>
			</tr>
			<tr>
				<td colspan="4">
					แสดงเฉพาะ : 
						<?php $qry_contype = pg_query("SELECT distinct(\"conType\") as contype FROM thcap_contract ORDER BY contype");
								$con=0;
							  while($re_contype = pg_fetch_array($qry_contype)){
								$con++;
									$contype = $re_contype['contype'];
									echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" checked>$contype ";
							  }
								
						?>
						<input type="hidden" value="<?php echo $con; ?>" id="consum">		
						
				</td>
				<td align="center">
					<input style="width:70px; height:25px;" type="button" id="printpdf" value="PrintPDF">
				
				</td>
			</tr>
			
</table>
</fieldset>
		</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<div id="panel1" style="padding-top: 10px;"></div>
		</td>
	</tr>
</table>	
<table width="100%"  cellspacing="0" cellpadding="0" align="center">	
	<tr>
		<td><br></td>
	</tr>
	<tr>	
		<td align="center" style="cursor:pointer">
			<img id="chart" src="" width="1100" height="300" onclick="" />
		</td>
	</tr>	
</table>	
</html>
