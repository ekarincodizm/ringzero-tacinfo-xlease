<?php
include("../config/config.php");
$IDNO = pg_escape_string($_GET["IDNO"]);
$O_Type = pg_escape_string($_GET["O_Type"]);
$startDate = pg_escape_string($_GET["startDate"]);
$endDate = pg_escape_string($_GET["endDate"]);
$IDCarTax = pg_escape_string($_GET["IDCarTax"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แสดงรายการ MAP ใบเสร็จ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"><link>
	<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function checkdata(){
	if(document.getElementById('idtfpen').value == ""){
			alert("กรุณากรอกเลขที่ใบสั่ง");
			document.getElementById('idtfpen').focus();
			return false;
	}else{
		return true;
	}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
$(document).ready(function(){
    $("#idno").autocomplete({
        source: "s_idno.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_create.php?idno="+ $("#idno").val());
    });
});


</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>
<form name="form1" method="post" action="process_nw_map.php">
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="clear:both; padding-bottom: 10px;"></div>
			<h2>Map ใบเสร็จ</h2>
			<div class="ui-widget" align="center">
				<div id="panel" style="padding-top: 20px;">
					<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr><td colspan="5" bgcolor="#FFFFFF">เลขที่สัญญา : <?php echo "$IDNO";?></td></tr>
					<tr align="center" bgcolor="#79BCFF">
						<th>เลือก</th>
						<th>เลขที่ใบเสร็จ</th>
						<th>วันที่จ่ายเงิน</th>
						<th>จำนวนเงิน (บาท)</th>
						<th>ประเภทการจ่าย</th>
					</tr>
					<?php
						$query_map=pg_query("select * from \"FOtherpay\" where \"IDNO\"='$IDNO' and \"O_Type\"='$O_Type' and (\"O_DATE\" between '$startDate' and '$endDate')");
						$numrows=pg_num_rows($query_map);
						$i=1;
						while($res_map=pg_fetch_array($query_map)){
							$O_RECEIPT=$res_map["O_RECEIPT"];
							$O_DATE=$res_map["O_DATE"];
							$O_MONEY=$res_map["O_MONEY"];
							$PayType=$res_map["PayType"];
								
							if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}
							?>
							<td align=center><input type="radio" name="O_RECEIPT" value="<?php echo $O_RECEIPT;?>" <?php if($i==1){?> checked <?php }?>></td>
							<?php
							echo "<td align=center>$O_RECEIPT</td>";
							echo "<td align=center>$O_DATE</td>";
							echo "<td align=right>";
							echo number_format($O_MONEY,2);
							echo "</td>";
							echo "<td align=center>$PayType</td>";
							echo "<tr>";
							$i++;
						}
						if($numrows==0){
							echo "<tr><td colspan=5 align=center height=30 bgcolor=#FFFFFF>ไม่พบรายการ</td></tr>";
						}
						?>
						<tr>
							<td colspan="5" align="center" height="30" bgcolor="#FFFFFF">
								<input type="hidden" name="IDCarTax" value="<?php echo $IDCarTax;?>">
								<input type="submit" value="Map ใบเสร็จ" <?php if($numrows == 0) echo "disabled";?>>
								<input type="submit" value="Close" onclick="javascript:window.close();">
							</td>
						</tr>
					</table>
				</div>
			</div>
        </td>
    </tr>
</table>
</form>
</body>
</html>