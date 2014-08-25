<?php
include("../../config/config.php");

$chksh = trim($_REQUEST["chksh"]);
$condate = $_REQUEST["condate"];

if($chksh=="shday"){
	$sortText = "&datepicker=".$_REQUEST['datepicker'];
	$datepicker = $_REQUEST["datepicker"];
	if($datepicker!=""){
		
		if($condate==1){
			$txtcondate="วันที่สั่งงาน";	
			$conditiondate="\"AssignDate\"::date='$datepicker'";
		}else if($condate==2){
			$txtcondate="วันที่กำหนดส่งงาน";
			$conditiondate="\"DeadlineDate\"::date='$datepicker'";
		}
		$datetext = "วันที่ ".$datepicker;
	}
} else if($chksh=="shmonth"){	
	$sortText = "&slbxSelectMonth=".$_REQUEST['slbxSelectMonth']."&slbxSelectYear=".$_REQUEST["slbxSelectYear"];
	$slbxSelectMonth = $_REQUEST["slbxSelectMonth"];
	$slbxSelectYear = $_REQUEST["slbxSelectYear"];
	if($slbxSelectMonth!="" and $slbxSelectYear!=""){
		if($condate==1){
			if($slbxSelectMonth=="not"){
				$txtcondate="วันที่สั่งงาน";	
				$conditiondate="EXTRACT(YEAR FROM \"AssignDate\")='$slbxSelectYear'";
				$datetext = "เดือน  ทุกเดือน  ปี ".$slbxSelectYear;
			}else {
				$txtcondate="วันที่สั่งงาน";	
				$conditiondate="EXTRACT(MONTH FROM \"AssignDate\")='$slbxSelectMonth' and EXTRACT(YEAR FROM \"AssignDate\")='$slbxSelectYear'";
				$datetext = "เดือน ".$slbxSelectMonth." ปี ".$slbxSelectYear;
			}
		}else if($condate==2){
			if($slbxSelectMonth=="not"){
				$txtcondate="วันที่กำหนดส่งงาน";
				$conditiondate="EXTRACT(YEAR FROM \"DeadlineDate\")='$slbxSelectYear'";
				$datetext = "เดือน  ทุกเดือน   ปี ".$slbxSelectYear;
			} else {
				$txtcondate="วันที่กำหนดส่งงาน";
				$conditiondate="EXTRACT(MONTH FROM \"DeadlineDate\")='$slbxSelectMonth' and EXTRACT(YEAR FROM \"DeadlineDate\")='$slbxSelectYear'";
				$datetext = "เดือน ".$slbxSelectMonth." ปี ".$slbxSelectYear;
			}
		}
	}
	
} else if($chksh=="shdateTodate"){
	$sortText = "&startdate=".$_REQUEST['startdate']."&todate=".$_REQUEST["todate"];
	$startdate = $_REQUEST["startdate"];
	$todate = $_REQUEST["todate"];
	if($startdate!="" and $todate!=""){
		if($condate==1){
			$txtcondate="วันที่สั่งงาน";	
			$conditiondate="date(\"AssignDate\")>='$startdate' and date(\"AssignDate\")<='$todate'";
		}else if($condate==2){
			$txtcondate="วันที่กำหนดส่งงาน";
			$conditiondate="date(\"DeadlineDate\")>='$startdate' and date(\"DeadlineDate\")<='$todate'";
		}
	}
	$datetext = "ระหว่าง วันที่".$startdate." ถึง".$todate;
}

$user_id = $_SESSION["av_iduser"];
$emlevel_qry=pg_query("select emplevel from fuser where id_user='$user_id'");
$user_emlevel=pg_fetch_result($emlevel_qry,0);

//เงือนไขในการเรียงดำลับข้อมูล
$Strsort=pg_escape_string($_GET['sort']);
if($Strsort==""){$Strsort="DoerStamp";}
$Strorder=pg_escape_string($_GET['order']);
if($Strorder==""){$strorder="DESC";}

if($Strorder=="DESC"){
	$NewStrorder="ASC";
} else {
	$NewStrorder="DESC";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการสั่งงานตรวจสอบ-วางบิลเก็บช็ค</title>
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
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	  $("#startdate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	  $("#todate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function checkCon(){
	
	if($('#chksh1').attr('checked')){
		if($("#datepicker").val() == ""){
			alert("กรุณาเลือกวันที่ค้นหา");
			return false;
		} else { 
			return true;
		}
	} else if($("#chksh3").attr('checked')){
				if($("#startdate").val() == ""){
					alert("กรุณาเลือกวันที่เริ่มต้นของการค้นหา");
					return false;
				}else if($("#todate").val() == ""){
					alert("กรุณาเลือกวันที่สุดท้ายของการค้นหา");
					return false;
				} else if($("#todate").val()<$("#startdate").val()){
					alert("ข้อมูลในการค้นหาไม่ถูกต้อง");
					return false;
				} else {
					return true;
				}
	}
}
</script>
    
<style type="text/css">
#Search{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:60%;
}
#list{
margin-left:auto;
margin-right:auto;
margin-top:20px;
}
#main{
margin-left:auto;
margin-right:auto;
width:80%;
}
</style>
    
</head>
<body id="mm">

	<div id="main">
	<div style="text-align:center"><h2>(THCAP) รายงานการสั่งงานตรวจสอบ-วางบิลเก็บช็ค</h2></div>       
	
	<form method="post" name="form2" action="pdf_report.php">
		<div style="float:right">
			<input type="hidden" name="condatePDF" value="<?php echo $condate; ?>" >
			<input type="hidden" name="chkshPDF" value="<?php echo $chksh; ?>" >
			<input type="hidden" name="datepickerPDF" value="<?php echo $datepicker; ?>" >
			<input type="hidden" name="slbxSelectMonthPDF" value="<?php echo $slbxSelectMonth; ?>" >
			<input type="hidden" name="slbxSelectYearPDF" value="<?php echo $slbxSelectYear; ?>" >
			<input type="hidden" name="startdatePDF" value="<?php echo $startdate; ?>" >
			<input type="hidden" name="todatePDF" value="<?php echo $todate; ?>" >
			<input type="submit" name="print" id="print" value=" พิมพ์รายงาน "/>
			<input type="button" value="  Close  " onclick="window.close();">
		</div>
	</form>
	
	<form method="post" name="form1" action="frm_Report.php" onsubmit="checkCon();">
	<div style="clear:both;"></div>
		<div id="Search">
			<fieldset><legend><B>ค้นหา</B></legend>
			<table align="center" cellspacing="10">
				<tr>
					<td align="right"><b>รายงานตาม: </b></td>
					<td align="left">
						<select name="condate">
							<option value="1" <?php if($condate=="1") echo "selected";?>>วันที่สั่งงาน</option>
							<option value="2" <?php if($condate=="2") echo "selected";?>>วันที่กำหนดส่งงาน</option>
						</select>
					</td>
				</tr>
			
				<tr>
					<td align="right">
						<input type="radio" id="chksh1" name="chksh" value="shday" <?php if($chksh =="shday" || $chksh==""){ echo "checked"; } ?>>
						<label><b>วันที่: </b></label>
					</td>
					<td align="left">
						<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
					</td>
				</tr>
				<tr>
					<td align="right">
						<input type="radio" id="chksh2" name="chksh" value="shmonth" <?php if($chksh =="shmonth"){ echo "checked"; } ?>>
						<label><b>เดือน/ปี: </b></label>
					</td>
					<td>
						<select id="slbxSelectMonth" name="slbxSelectMonth" >
							<option value="not"<?php if($slbxSelectMonth =="not"){echo "selected";} ?> style="background-Color:#FFFCCC" >แสดงทั้งหมด</option>
							<option value="01"<?php if($slbxSelectMonth =='01'){echo "selected";} ?>>มกราคม</option>
							<option value="02"<?php if($slbxSelectMonth =='02'){echo "selected";} ?>>กุมภาพันธ์</option>
							<option value="03"<?php if($slbxSelectMonth =='03'){echo "selected";} ?>>มีนาคม</option>
							<option value="04"<?php if($slbxSelectMonth =='04'){echo "selected";} ?>>เมษายน</option>
							<option value="05"<?php if($slbxSelectMonth =='05'){echo "selected";} ?>>พฤษภาคม</option>
							<option value="06"<?php if($slbxSelectMonth =='06'){echo "selected";} ?>>มิถุนายน</option>
							<option value="07"<?php if($slbxSelectMonth =='07'){echo "selected";} ?>>กรกฎาคม</option>
							<option value="08"<?php if($slbxSelectMonth =='08'){echo "selected";} ?>>สิงหาคม</option>
							<option value="09"<?php if($slbxSelectMonth =='09'){echo "selected";} ?>>กันยายน</option>
							<option value="10"<?php if($slbxSelectMonth =='10'){echo "selected";} ?>>ตุลาคม</option>
							<option value="11"<?php if($slbxSelectMonth =='11'){echo "selected";} ?>>พฤศจิกายน</option>
							<option value="12"<?php if($slbxSelectMonth =='12'){echo "selected";} ?>>ธันวาคม</option>				
						</select>
						
						<select id="slbxSelectYear" name="slbxSelectYear">
						<?php 
						$datenow = date('Y');
						if($yearsh == ""){
							$datenow1 = date('Y');
						}else{
							$datenow1 = $yearsh;
						}
							$yearback = $datenow -30;														
						for($t=$yearback;$t<=$datenow+10;$t++){													  
							if($t == $datenow1 and $slbxSelectYear==""){ ?> 
								<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
					<?php	}else{ ?>
								<option value="<?php echo $t;?>" <?php if($slbxSelectYear==$t){echo "selected";}?>><?php echo $t; ?></option>																
					<?php  
							}
						} 
						?>	
						</select>	
					</td>
				</tr>
				<tr>
					<td align="right">
						<input type="radio" id="chksh3" name="chksh" value="shdateTodate" <?php if($chksh =="shdateTodate"){ echo "checked"; } ?>>
						<label><b>ระหว่างวัน : </b></label>
					</td>
					<td>
						<input type="text" id="startdate" name="startdate" value="<?php echo $startdate; ?>" size="15" readonly="true" style="text-align:center"> ถึง
						<input type="text" id="todate" name="todate" value="<?php echo $todate; ?>" size="15" readonly="true" style="text-align:center">
					<td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input type="hidden" name="val" value="1"/>						
						<input type="submit" id="btn00" value="  ค้นหา  "/>
					</td>
				</tr>
			</table>			
			</fieldset>
		</div>
		
		<div> <?php include("list_report.php");?></div>
		<div> <?php include("list_cancle_report.php");?></div>
	</div>
</form>
</body>
</html>