<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$app_date = Date('Y-m-d H:i:s');
$contractID = pg_escape_string($_GET["contractID"]);
$autoID = pg_escape_string($_GET["ini_auto_id"]);
$conDate = pg_escape_string($_GET["conDate"]);
$conEndDate = pg_escape_string($_GET["conEndDate"]);
$conType = pg_escape_string($_GET["conType"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
 <title>(THCAP) ใส่ต้นทุนสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function SubmitForm(){
	var checkIni1 = document.getElementById('RcheckIni1');
	var checkIni2 = document.getElementById('RcheckIni2');
	
	if(checkIni1.checked){
		document.getElementById('detailIni').style.visibility = "visible";
		document.getElementById("TableiniCostType").style.visibility = 'visible';
	} if (checkIni2.checked){
		document.getElementById('detailIni').style.visibility = "hidden";
		document.getElementById("TableiniCostType").style.visibility = 'hidden';
		
	}

}
var nubIniCost=1;

$(document).ready(function(){
$('#AddField').click(function()
	{
		
		nubIniCost++;
		if(nubIniCost == 1)
		{
			document.getElementById("TableiniCostType").style.visibility = 'visible';
			document.getElementById("rowinicost").value = nubIniCost;
		}
		else  if(nubIniCost > 1)
		{	
			console.log(nubIniCost);
			var newiniCostDiv = $(document.createElement('div')).attr("id", 'iniCostDiv' + nubIniCost);
			table = '<table table align="center" bgcolor="#CCCCCC">'
			+ '	<tr>'
			+ '		<td>'
			+ '			<label name="iniTypeLabel'+ nubIniCost +'"><b>ประเภทต้นทุนสัญญา: </b></label>'
			+ '		</td>'
			+ '		<td>'
			+ '			<select name="iniType'+ nubIniCost +'" id="iniType'+ nubIniCost +'" >'
			+ '			<option value="">กรุณาเลือกประเภท</option>'
			<?php
							$squery = pg_query("select * from thcap_cost_type where ta_array1d_check(\"typeloansuse\",'$conType' )='1' and \"typeloansuse\" is not null and costtype<>'0' 
												union
												select * from thcap_cost_type where \"typeloansuse\" is null and costtype<>'0' ");		
							while($res=pg_fetch_array($squery)){
							$costType=$res['costtype'];
							$costName=$res['costname'];
			?>								
			+'				<option value="<?php echo $costType; ?>"><?php echo $costName; ?></option>'
						<?php
							}
						?>
			+ '			</select>'
			+ '		</td>'
			+ '		<td>'
			+ '		<label name="MoneyLabel'+nubIniCost+'"><b>จำนวนเงิน: </b></label> <input type="text" name="sumIniCost'+ nubIniCost +'" id="sumIniCost'+ nubIniCost +'" style="text-align:right;" onkeypress="check_num(event)" oncontextmenu="return false"/> <label name="bath'+ nubIniCost +'">บาท</label>	'
			+ '		</td>'
			+'	</tr>'
			+'</table>'
			
			newiniCostDiv.html(table);

			newiniCostDiv.appendTo("#iniCostGroup1");
				
			document.getElementById("rowinicost").value = nubIniCost;
		}
    }
	);

	$("#removeField").click(function(){
		
		if(nubIniCost==1){
            document.getElementById("TableiniCostType").style.visibility = 'visible';
			document.FormInput.iniTypeLabel1.value = "";
			document.FormInput.iniType1.value = "";
			document.FormInput.MoneyLabel1.value = "";
			document.FormInput.sumIniCost1.value = "";
			document.FormInput.bath1.value = "";
        }
        if(nubIniCost==0){
            //alert("ห้ามลบ !!!");
			nubIniCost=1;
			document.getElementById("rowinicost").value = nubIniCost;
            return false;
        }
        $("#iniCostDiv" + nubIniCost).remove();
        nubIniCost--;
        console.log(nubIniCost);
        updateSummary();
		
		document.getElementById("rowinicost").value = nubIniCost;
    });
});
function validate(){
	var Message = "check";
	var Noerror = Message;
	var rowinicost = document.getElementById("rowinicost").value;
	var i=1;

	for(i=1;i<=rowinicost;i++){
	if(document.getElementById("iniType"+i).value==""){
		Message = "กรุณาระบุประเภทต้นทุนเริ่มแรกของสัญญา";
	}
	else if(document.getElementById("sumIniCost"+i).value==""){
			Message = "กรุณาระบุจำนวนต้นทุนเริ่มแรกของสัญญา";
		}
	}
	if(Message==Noerror){
		return true;
		} else {
			alert(Message);
			return false;
			}
}
function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
};
</script>
<body>
	<div><h3 align="center">(THCAP) ใส่ต้นทุนสัญญา</h3></div>
	<div>
		<table bgcolor="#CCCCCC" align="center">
			<tr>
				<td><b>สัญญาเลขที่: <b></td>
				<td><?php echo "<font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID" ?> </u></font></td>
			</tr>
			<tr>
				<td><b>ประเภทสัญญา: <b></td>
				<td><?php echo $conType; ?> </td>
			</tr>
			<tr>
				<td><b>วันที่ทำสัญญา: <b></td>
				<td><?php echo $conDate; ?> </td>
			</tr>
			<tr>
				<td><b>วันที่ครบกำหนดสัญญา: <b></td>
				<td><?php echo $conEndDate; ?> </td>
			</tr>
		</table>
	</div>
	<form id="FormInput" action="Insert_Appv.php" method="post">
	<div style="position:relative; margin-left:auto; margin-righ:auto;">
		<table table align="center" >
			<tr>
				<td>
					<input type="radio" name="RcheckIni" id="RcheckIni1" value="1" onchange="SubmitForm();" checked />มีต้นทุนสัญญา
					<input type="radio" name="RcheckIni" id="RcheckIni2" value="0" onchange="SubmitForm();" />ไม่มีต้นทุนสัญญา
				</td>
			</tr>
		</table>
	</div>
	
	<div>
	<div>
		<table table align="center">
		<tr>
			<td>
				<input type="button" value="+" id="AddField"/> <input type="button" value="-" id="removeField"/>
			</td>
		</tr>
		</table>
	</div>
		<div id="detailIni">
			<div>
			<table id="TableiniCostType" table align="center" bgcolor="#CCCCCC">
				<tr>
					<td>
						<label name="iniTypeLabel1"><b>ประเภทต้นทุนสัญญา: </b></label>
					</td>
					<td>
						<select name="iniType1" id="iniType1">
						<option value="">กรุณาเลือกประเภท</option>
						<?php 
							$squery = pg_query("select * from thcap_cost_type where ta_array1d_check(\"typeloansuse\",'$conType' )='1' and \"typeloansuse\" is not null and costtype<>'0' 
												union
												select * from thcap_cost_type where \"typeloansuse\" is null and costtype<>'0' " );		
							while($res=pg_fetch_array($squery)){
								$costType=$res['costtype'];
								$costName=$res['costname'];			
								
								echo "<option value=\"$costType\">$costName</option>";
							}
						?>
						</select>
					</td>
					<td>
						<label name="MoneyLabel1"><b>จำนวนเงิน: </b></label> <input type="text" name="sumIniCost1" id="sumIniCost1" style="text-align:right;" onkeypress="check_num(event)" oncontextmenu="return false"/> <label name="bath1">บาท</label>
					</td>
				</tr>
			</table>
			</div>
			<div id="iniCostGroup1">
				<div id='iniCostDiv1'>
				</div>
			</div>
		</div>
		<input type="hidden" name="rowiniCost" id="rowinicost" value="1">
		<input type="hidden" name="contractID" value="<?php echo $contractID; ?>">
		<div>
			<table align="center" >
				<tr>
				<td>
				<input type="submit" value="บันทึก" onclick="return validate();"/> <input type="button" value="ยกเลิก" onclick="window.close();"/>
				</td>
				</tr>
			<table>
		</div>
	</form>
	</div>
</body>
</html>