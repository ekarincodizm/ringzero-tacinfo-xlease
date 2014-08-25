<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานเงินสดประจำวัน (ทางระบบบัญชี)</title>
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
});
function loaddata()
{  
	
	var chk=0;
	$('#panel').empty();	
	if($("#by_date:checked").val() == "0"){// ค้นหาตาม เลือก วันที่ :
		$('#panel').html('<img src="images/process.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$("#panel").load("frm_list_show_account.php?datepicker="+ $("#datepicker").val());
	}
	else{	
			
			if($("#by_month:checked").val() == "1"){// ค้นหาตาม เลือก วันที่ :
				if($("#sele_by_month_month").val()==""){
					alert('กรุณาเลือกเดือน');
					chk++;
				}
			}
			if(chk==0){				
				$('#panel').html('<img src="images/process.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
				$("#panel").load("frm_list_show_month_year_account.php?sele_by_year_year="+ $("#sele_by_year_year").val()+
				'&chk_search='+ $("input[name=rdo_search]:checked").val()+
				'&sele_by_month_month='+ $("#sele_by_month_month").val()+
				'&sele_by_month_year='+ $("#sele_by_month_year").val());
			}
	}
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
<form method="post" name="form1" action="#">
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) รายงานเงินสดประจำวัน</h2><h3>(ทางระบบบัญชี)</h3></div>
			<div>
				<table width="100%">
					<tr>
						<td align="left">
							<input type="button" style="height: 3em;" value="ทางระบบบัญชี" disabled>
							<input type="button" style="height: 3em; cursor:pointer;" value="ทางระบบดำเนินงาน" onclick="window.location='frm_Index.php';">
						</td>
						<td align="right">
							<input type="button" style="cursor:pointer;" value="  Close  " onclick="window.close();">
						</td>
					</tr>
				</table>
			</div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>ค้นหาข้อมูล</B></legend>
				<div align="center">
					<div class="ui-widget">
						<table border="0">
							<tr>
								<td><b>ค้นหาจาก:</b></td>		
								<td><input type="radio" id="by_date" name="rdo_search"  value="0" <?php if($rdo_search=="" || $rdo_search=="0"){ echo "checked"; }?>/></td>
								<td align="left">
									วันที่ : </td>
								<td>
								<input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15" readonly="true" style="text-align:center">
								</td>							
							</tr>
							<tr>
								<td colspan="2"align="right">
								<input type="radio" id="by_month" name="rdo_search"  value="1" <?php if($rdo_search=="1"){ echo "checked"; }?>/></td>
								<td align="left" >
									เดือน:</td>
								<td><select name="sele_by_month_month" id="sele_by_month_month"> 
											<option value="">--เลือกเดือน--</option>
											<option value="01" <?php if($month1=="01") echo "selected";?>>มกราคม</option>
											<option value="02" <?php if($month1=="02") echo "selected";?>>กุมภาพันธ์</option>
											<option value="03" <?php if($month1=="03") echo "selected";?>>มีนาคม</option>
											<option value="04" <?php if($month1=="04") echo "selected";?>>เมษายน</option>
											<option value="05" <?php if($month1=="05") echo "selected";?>>พฤษภาคม</option>
											<option value="06" <?php if($month1=="06") echo "selected";?>>มิถุนายน</option>
											<option value="07" <?php if($month1=="07") echo "selected";?>>กรกฎาคม</option>
											<option value="08" <?php if($month1=="08") echo "selected";?>>สิงหาคม</option>
											<option value="09" <?php if($month1=="09") echo "selected";?>>กันยายน</option>
											<option value="10" <?php if($month1=="10") echo "selected";?>>ตุลาคม</option>
											<option value="11" <?php if($month1=="11") echo "selected";?>>พฤศจิกายน</option>
											<option value="12" <?php if($month1=="12") echo "selected";?>>ธันวาคม</option>
										</select>
									ปี: <select name="sele_by_month_year" id="sele_by_month_year"> 
										<?php $datenow1 = nowDate();
										list($year,$month,$day)=explode("-",$datenow1);	
										$year0=$year-10;
										if($year0<2013){
											$year0=2013;				
										}
										$year1=$year+3;
										for($t=$year0;$t<=$year1;$t++){
											if($t == $year){ ?> 
												<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
											<?php } else{ ?>
												<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
											<?php  
											}
										} 
									?>		
									</select>
									
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">
								<input type="radio" id="by_year" name="rdo_search"  value="2" <?php if($rdo_search=="2"){ echo "checked"; }?>/></td>
								<td align="left" >
									ปี: </td>
								<td><select name="sele_by_year_year" id="sele_by_year_year"> 
									<?php $datenow1 = nowDate();
										list($year,$month,$day)=explode("-",$datenow1);	
										$year0=$year-10;
										if($year0<2013){
											$year0=2013;				
										}
										$year1=$year+3;
										for($t=$year0;$t<=$year1;$t++){
											if($t == $year){ ?> 
												<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
											<?php } else{ ?>
												<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
											<?php  
											}
										} 
									?>		
									</select>
								</td>
							</tr>
							
							<tr>
								<td colspan="4" align="center" >	
								<input type="button" style="cursor:pointer;" value="ค้นหา" onClick="loaddata();"></td>	
							</tr>
						</table>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
	<tr>
		<td>
			<div id="panel" style="margin:5px"></div>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<script>
$("input[name=rdo_search]").change(function(){
	
	$("#datepicker").val('<?php echo nowDate(); ?>');
	$("#sele_by_month_month").val('');
	$("#sele_by_month_year").val('<?php echo $year; ?>');
	$("#sele_by_year_year").val('<?php echo $year; ?>');
});
</script>