<?php
include("../../config/config.php");
if($datepicker==""){
	$datepicker=nowDate();
}
$month = Date('m');
$nowYear = date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายการเงินโอน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#d1").show();
	$("#d2").hide();
		
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$('#btn1').click(function(){
		var accsumnum = $("#accsumnum").val();
		var acctype = "";
		var checknum = 0;

		//ตรวจสอบว่ามีบัญชีที่ติ้กถูกอะไรบ้าง
		for(i=1;i<=accsumnum;i++){
			if($("#acctype"+i).attr("checked") == true){
				acctype = acctype+"@"+$("#acctype"+i).val();
				checknum++;
			}	
		}

		if(checknum == 0){
			alert("- กรุณาเลือกประเภทบัญชีที่ต้องการดูข้อมูล! -");
			return false;
		}else{
			$("#panel").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
			
			//ตรวจสอบว่าเลือกเงื่อนไขใด
			if(document.getElementById("contype1").checked == true){//เมื่อเลือก วันที่นำเงินเข้าธนาคาร
				var option = $("#contype1").val();
				var date = $("#datepicker").val();
				$("#panel").load("frm_report_trans_show.php?acctype="+ acctype +"&date=" + date+"&option="+option);
			}else if(document.getElementById("contype2").checked == true){//เมื่อเลือก เดือน-ปี ที่นำเงินเข้าธนาคาร
				var option = $("#contype2").val();
				var mm = $("#month1").val();	
				var yy = $("#year1").val();
				$("#panel").load("frm_report_trans_show.php?acctype="+ acctype +"&yy="+yy+"&mm="+mm+"&option="+option);
			}else if(document.getElementById("contype3").checked == true){//เมื่อเลือก ปี ที่นำเงินเข้าธนาคาร
				var option = $("#contype3").val();
				var yy = $("#year1").val();
				$("#panel").load("frm_report_trans_show.php?acctype="+ acctype +"&yy="+yy+"&option="+option);
			}
			//จบการตรวจสอบว่าเลือกเงื่อนไขใด
		}	
    });
});

function selectAll(select){
    with (document.frm)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
//เลือก radio แสดงตาม
function option(){
	if(document.getElementById("contype1").checked == true){//วันที่นำเงินเข้าธนาคาร
		$("#d1").show();
		$("#d2").hide();		
	}else if(document.getElementById("contype2").checked == true){//เดือน-ปี ที่นำเงินเข้าธนาคาร
		$("#d1").hide();
		$("#d2").show();
			$("#month1_text").show();
			$("#month1").show();
			$("#year1").show();
	}else if(document.getElementById("contype3").checked == true){//ปีที่นำเงินเข้าธนาคาร
		$("#d1").hide();
		$("#d2").show();
			$("#year1").show();
			$("#month1_text").hide();
			$("#month1").hide();
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
</style>
    
</head>
<body id="mm">

<table width="1200" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>(THCAP) รายงานเงินโอน</B></legend>
				<div align="center">
					<div class="ui-widget">
						<form method="post" name="frm">
						<p align="center">
							<label for="birds"><b><a style="cursor:pointer;" onclick="javascript:selectAll('acctype');"><u>แสดงบัญชี</u></a> :</b></label>
							<?php $qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1");
								  $accnum = 0;
								  while($re_acc = pg_fetch_array($qry_acc)){
									 $accnum++;
									 $BAccount = $re_acc['BAccount'];
									 $BName = $re_acc['BName'];
									 $BID = $re_acc['BID'];
										echo "<input type=\"checkbox\" name=\"acctype[]\" id=\"acctype$accnum\" value=\"$BID\" checked> $BAccount-$BName"		;					  
								  }								  
							?> 
						</p>
						<fieldset  style="width:50%;background-color:#FAEBD7;padding:10px;" ><legend><span style="background-color:#FFDAB9;font-weight:bold;">แสดงตาม</span></legend>
						<table align="center" width="100%" border="0">
						<tr>
							<td align="center" height="50">
							<input type="radio" id="contype1" name="contype1" value="1"  checked onchange="option();">วันที่นำเงินเข้าธนาคาร
							<input type="radio" id="contype2" name="contype1" value="2"  onchange="option();">เดือน-ปี ที่นำเงินเข้าธนาคาร
							<input type="radio" id="contype3" name="contype1" value="3"  onchange="option();">ปีที่นำเงินเข้าธนาคาร
							</td>
						</tr>
						<tr id="d1" height="30">
							<td align="center">
								<font id="txtEdate">วันที่ :</font><input type="text" name="datepicker" id="datepicker" value="<?php echo date("Y-m-d");?>" size="10">
							</td>
						</tr>
						<tr id="d2" height="30">
							<td align="center">
									<font id="month1_text">เดือน :</font>
										<select name="month1" id="month1">
												<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
												<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
												<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
												<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
												<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
												<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
												<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
												<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
												<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
												<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
												<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
												<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
											</select>	
									<font>ปี :</font>
										<select name="year1" id="year1">
											<?php
												for($y=1900; $y<=($nowYear+10); $y++)
												{
													if($y == $nowYear)
													{
														echo "<option value=\"$y\" selected>$y</option>";
													}
													else
													{
														echo "<option value=\"$y\">$y</option>";
													}
												}
											?>
										</select>
							</td>
						</tr>
						</table>
						</fieldset>
						<input type="button" id="btn1" value="เริ่มค้น"/>
						</form>	
						<input type="hidden" id="accsumnum" value="<?php echo  $accnum ?>">		
						<div id="panel"></div>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>

</body>
</html>