<?php
include("../../config/config.php");

$abh_autoid = pg_escape_string($_GET["abh_autoid"]);

// หาข้อมูลหัวบัญชี
	$qry_m = pg_query("select \"abh_id\", \"abh_stamp\"::date, \"abh_detail\", \"GJ_typeID\" from account.\"all_accBookHead\" where  \"abh_autoid\" = '$abh_autoid'");

	$a_id = pg_fetch_result($qry_m,0);
	$as_date = pg_fetch_result($qry_m,1);
	$abh_detail = pg_fetch_result($qry_m,2);
	$GJ_typeID_old = pg_fetch_result($qry_m,3);
// จบการหาข้อมูลหัวบัญชี

// รายละเอียดรายการบัญชี
$qry_vacc = pg_query("select * from account.\"V_all_AccountBook\" where abh_id='$a_id' order by \"abd_bookType\" ");
$row_vacc = pg_num_rows($qry_vacc);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <!--<title><?php echo $_SESSION["session_company_name"]; ?></title>-->
	<title>(THCAP) แก้ไขบัญชีสมุดรายวันทั่วไป</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    
    $("#divenshow").hide();
    $("#sp_show").hide();
    
    $(".static_class1").click(function(){
        if($(this).val()==="2"){
            $("#sp_show").show();
            $("#paybuy").focus();
        }else{
            $("#sp_show").hide();
        }
    });

    
    $("#btn_submit").click(function(){
        
        if($("#text_add").val() == "" && $("#hidchk").val() == 0){
            alert('ไม่พบคำอธิบายรายการ');
            $("#text_add").focus();
            return false;
        }
        
        if($("#hidchk").val() == 1){
            if( $("#buyfrom").val() == "" ){
                alert('ไม่พบ ซื้อจาก');
                $("#buyfrom").focus();
                return false;
            }
            if( $("#buyreceiptno").val() == "" ){
                alert('ไม่พบ เลขที่ใบเสร็จใบกำกับ');
                $("#buyreceiptno").focus();
                return false;
            }
            if( $("#tohpid").val() == "" ){
                alert('ไม่พบ เข้าสัญญาเช่าซื้อเลขที่');
                $("#tohpid").focus();
                return false;
            }
        }
        
        var x1=0;
        var acid = window.document.getElementsByName("acid[]");
        for(i = 0; i < acid.length; i++){
            if(acid[i].value == ''){
                x1 = x1+1;
            }
        }
        
        var x2=0;
        var actype = window.document.getElementsByName("actype[]");
        for(i = 0; i < actype.length; i++){
            if(actype[i].value == ''){
                x2 = x2+1;
            }
        }
        
        var x3=0;
        var text_money = window.document.getElementsByName("text_money[]");
        for(i = 0; i < text_money.length; i++){
            if(text_money[i].value == ''){
                x3 = x3+1;
            }
        }
        
        if(x1 > 0){
            alert('พบรายการบัญชี ไม่ถูกเลิก');
            return false;
        }else if(x2 > 0){
            alert('พบสถานะ ไม่ถูกเลิก');
            return false;
        }else if(x3 > 0){
            alert('ไม่พบยอดเงิน');
            return false;
        }else if($("#chk_drcr").val() == 1){
            alert('ผลรวม Dr และ Cr ไม่เท่ากัน');
            return false;
        }else{
            $("#add_acc").submit();
        }
    });
    
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
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
</style>

<script type="text/javascript">
var gFiles = '<?php echo $row_vacc; ?>';
function addFile(){
    var li = document.createElement('div');
    li.setAttribute('id', 'file-' + gFiles);
    li.innerHTML = '<div align="left">เลือกบัญชี <select name="acid[]" id="acid" onchange="getValueArray(); chk4700();"><option value="">- เลือก -</option><?php
$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
while($res_name=pg_fetch_array($qry_name)){
	$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
    $AcID = $res_name["accBookID"]; // เลขที่บัญชี
    $AcName = $res_name["accBookName"]; // ชื่อบัญชี
    echo "<option value=\"$AcSerial\">$AcID : $AcName</option>";
}
?></select> สถานะ <select name="actype[]" id="actype" onchange="getValueArray(); chk4700();"><option value="">- เลือก -</option><option value="1">Dr</option><option value="2">Cr</option></select> ยอดเงิน <input type="text" id="text_money" name="text_money[]" size="10" OnKeyUp="JavaScript:getValueArray();" onblur="chk4700();"> <span onclick="removeFile(\'file-' + gFiles + '\'), getValueArray();" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span></div>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;
}
function removeFile(aId) {
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}
</script>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>แก้ไขบัญชี - <?php echo $a_id; ?></B></legend>

<script language="JavaScript" type="text/JavaScript">
function getValueArray(){
    var a1=0;
    var a0=0;
    var sum1 = 0;
    var sum0 = 0;
    
    str = "<table cellSpacing=\"1\" cellPadding=\"3\" width=\"100%\" style=\"background-color:#ACACAC; color:#000000;\"><tr bgcolor=\"#FFFFD2\"><td align=\"center\"><b>บัญชี</b></td><td align=\"center\"><b>Dr</b></td><td align=\"center\"><b>Cr</b></td></tr>";
    
    var acid = window.document.getElementsByName("acid[]");
    var actype = window.document.getElementsByName("actype[]");
    var text_money = window.document.getElementsByName("text_money[]");
    var actype_length = actype.length;

    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 1){

            var index = acid[i].selectedIndex;
            if(index != ''){
                select_text = document.getElementById('acid').options[index].text;
                
                sum1 = sum1 + (text_money[i].value*1);
                a1 = a1+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>"+select_text+"</td><td align=\"right\">"+text_money[i].value+"</td><td></td></tr>";
            }
        }
    }
    sum1 = sum1.toFixed(2);

    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 2){
            
            var index = acid[i].selectedIndex;
            if(index != ''){
                select_text = document.getElementById('acid').options[index].text;
                
                sum0 = sum0 + (text_money[i].value*1);
                a0 = a0+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+select_text+"</td><td></td><td align=\"right\">"+text_money[i].value+"</td></tr>";
            }
        }
    }
    sum0 = sum0.toFixed(2);
    
    if((sum1 == sum0) && a1 > 0 && a0 > 0){
        document.add_acc.chk_drcr.value = 0;
    }else{
        document.add_acc.chk_drcr.value = 1;
    }
    
    str += "<tr bgcolor=\"#FFFFFF\"><td align=\"right\"><b>รวม</b></td><td align=\"right\"><b>"+sum1+"</b></td><td align=\"right\"><b>"+sum0+"</b></td></tr>";
    str += "</table>";
    
    document.getElementById('myDiv').innerHTML = str;
}

function chk4700(){
    var nubbb = 0;
    
    var arr_acid = window.document.getElementsByName("acid[]");
    var arr_actype = window.document.getElementsByName("actype[]");
    var arr_text_money = window.document.getElementsByName("text_money[]");
    var actype_length = arr_actype.length;
    
    for(i = 0; i < actype_length; i++){
        if(arr_acid[i].value == 4700 && arr_actype[i].value == 1 && arr_text_money[i].value != ""){
            nubbb++;
        }
    }
    
    if(nubbb > 0){
        $("#text_add").attr("readonly", "readonly");
        //$("#text_add").val('');
        $("#divenshow").show();
        $("#hidchk").val('1');
    }else{
        $("#text_add").attr("readonly", "");
        $("#divenshow").hide();
        $("#hidchk").val('0');
    }
}
</script>

<form method="post" name="add_acc" id="add_acc" action="process_account_edit.php">
<input type="hidden" id="chk_drcr" name="chk_drcr">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
	<tr>
		<td align="left" width="15%"><b>วันที่</b></td>
		<td width="85%"><input type="text" id="datepicker" name="datepicker" value="<?php echo $as_date; ?>" size="15"></td>
	</tr>
	<tr>
		<td align="left" width="15%"><b>ประเภทสมุดรายวันทั่วไป</b></td>
		<td width="85%">
			<select name="GJtype" id="GJtype">
				<?php
				$qry_General_Journal_Type = pg_query("select \"GJ_typeID\" from account.\"General_Journal_Type\" order by \"GJ_typeID\" ");
				while($res_General_Journal_Type = pg_fetch_array($qry_General_Journal_Type))
				{
					$GJ_typeID = $res_General_Journal_Type["GJ_typeID"];
					?>
					<option value="<?php echo $GJ_typeID; ?>" <?php if($GJ_typeID_old == $GJ_typeID){echo "selected";} ?>><?php echo $GJ_typeID; ?></option>
					<?php
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="left" valign="top"><b>คำอธิบายรายการ</b></td>
		<td>
			<textarea id="text_add" name="text_add" rows="5" cols="50"><?php echo $abh_detail; ?></textarea>

			<div id="divenshow" style="margin:5px 0px 5px 0px">
				<input type="hidden" name="hidchk" id="hidchk" value="0">
				
				<table cellpadding="2" cellspacing="1" border="0" width="500" bgcolor="#FFDDDD">
					<tr>
						<td>ซื้อจาก</td>
						<td><input type="text" name="buyfrom" id="buyfrom" size="45"></td>
					</tr>
					<tr>
						<td>เลขที่ใบเสร็จใบกำกับ</td>
						<td><input type="text" name="buyreceiptno" id="buyreceiptno" size="45"></td>
					</tr>
					<tr>
						<td>ชำระค่ารถโดย</td>
						<td><input type="radio" class="static_class1" name="chkbuy" id="chkbuy" value="1" checked> เงินสด <input type="radio" class="static_class1" name="chkbuy" id="chkbuy" value="2"> เช็ค <span id="sp_show"><b>เลขที่เช็ค</b> <input type="text" name="paybuy" id="paybuy" size="12"></span></td>
					</tr>
					<tr>
						<td>เข้าสัญญาเช่าซื้อเลขที่</td>
						<td><input type="text" name="tohpid" id="tohpid" size="45"></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
				<div id="files-root">
					<?php
						$gFiles = 0;
						while($res_vacc=pg_fetch_array($qry_vacc))
						{
							$abd_accBookID = $res_vacc["abd_accBookID"];
							$abd_bookType = $res_vacc["abd_bookType"];
							$abd_amount = $res_vacc["abd_amount"];
					?>
							<div id="file-<?php echo $gFiles; ?>" align="left">
								เลือกบัญชี
								<select name="acid[]" id="acid" onchange="getValueArray(); chk4700();">
									<option value="">- เลือก -</option>
					<?php
									$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
									while($res_name=pg_fetch_array($qry_name))
									{
										$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
										$AcID = $res_name["accBookID"]; // เลขที่บัญชี
										$AcName = $res_name["accBookName"]; // ชื่อบัญชี
					?>
										<option value="<?php echo $AcSerial; ?>" <?php if($abd_accBookID == $AcID){echo "selected";} ?> ><?php echo "$AcID : $AcName"; ?></option>;
					<?php
									}
					?>
								</select>
								
								สถานะ
								<select name="actype[]" id="actype" onchange="getValueArray(); chk4700();">
									<option value="">- เลือก -</option>
									<option value="1" <?php if($abd_bookType == 1){echo "selected";} ?>>Dr</option>
									<option value="2" <?php if($abd_bookType == 2){echo "selected";} ?>>Cr</option>
								</select>
								
								ยอดเงิน
								<input type="text" name="text_money[]" id="text_money" size="10" OnKeyUp="JavaScript:getValueArray();" onblur="chk4700();" value="<?php echo $abd_amount; ?>">
							
								<span onclick="removeFile('file-<?php echo $gFiles; ?>'), getValueArray();" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span>
							</div>
					<?php
							$gFiles++;
						}
					?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><div id="myDiv"></div></td>
	</tr>
</table>

<script>
	getValueArray();
	chk4700();
</script>

<input type="hidden" id="abh_autoid" name="abh_autoid" value="<?php echo $abh_autoid; ?>">
</form>

<div style="float:left"><input type="button" value="ยืนยันการแก้ไข" class="ui-button" id="btn_submit" name="btn_submit"></div>
<div style="float:right"><input type="button" value="เพิ่มรายการ" class="ui-button" id="btn_add" name="btn_add" onclick="addFile(), getValueArray();"></div>
<div style="clear:both;"></div>

</fieldset>

</td>
</tr>
</table>

</body>
</html>