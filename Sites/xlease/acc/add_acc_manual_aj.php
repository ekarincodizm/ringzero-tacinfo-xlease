<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#btn_submit").click(function(){
        
        if($("#text_add").val() == ""){
            alert('ไม่พบคำอธิบายรายการ');
            $("#text_add").focus();
            return false;
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
        buttonImage: 'calendar.gif',
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
var gFiles = 0;
function addFile(){
    var li = document.createElement('div');
    li.setAttribute('id', 'file-' + gFiles);
    li.innerHTML = '<div align="left">เลือกบัญชี <select name="acid[]" id="acid" onchange="getValueArray();"><option value="">- เลือก -</option><?php
$qry_name=pg_query("SELECT * FROM account.\"AcTable\" ORDER BY \"AcID\" ASC");
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $AcName = $res_name["AcName"];
    echo "<option value=\"$AcID\">$AcID : $AcName</option>";
}
?></select> สถานะ <select name="actype[]" id="actype" onchange="getValueArray();"><option value="">- เลือก -</option><option value="1">Dr</option><option value="0">Cr</option></select> ยอดเงิน <input type="text" id="text_money" name="text_money[]" size="10" OnKeyUp="JavaScript:getValueArray();"> <span onclick="removeFile(\'file-' + gFiles + '\'), getValueArray();" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span></div>';
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

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"><input type="button" value="บันทึกเอง" class="ui-button" disabled><input type="button" value="ใช้สูตรทางบัญชี" onclick="javascript:window.location='add_acc_formula_aj.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>บันทึกเอง - AJ</B></legend>

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
        else if(actype[i].value == 0){
            
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
</script>

<form method="post" name="add_acc" id="add_acc" action="add_acc_manual_send_aj.php">
<input type="hidden" id="chk_drcr" name="chk_drcr">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td align="left" width="15%"><b>วันที่</b></td>
        <td width="85%"><input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15"></td>
    </tr>
     <tr>
        <td align="left"><b>คำอธิบายรายการ</b></td>
        <td><textarea id="text_add" name="text_add" rows="5" cols="50"></textarea></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>

<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<div id="files-root">
<div align="left">เลือกบัญชี
<select name="acid[]" id="acid" onchange="getValueArray();">
<option value="">- เลือก -</option>
<?php
$qry_name=pg_query("SELECT * FROM account.\"AcTable\" ORDER BY \"AcID\" ASC");
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $AcName = $res_name["AcName"];
    echo "<option value=\"$AcID\">$AcID : $AcName</option>";
}
?>
</select>
สถานะ
<select name="actype[]" id="actype" onchange="getValueArray();">
    <option value="">- เลือก -</option>
    <option value="1">Dr</option>
    <option value="0">Cr</option>
</select>
ยอดเงิน
<input type="text" id="text_money" name="text_money[]" size="10" OnKeyUp="JavaScript:getValueArray();">
</div>

</div>
</div>
            </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><div id="myDiv"></div></td>
    </tr>
</table>

</form>

<div style="float:left"><input type="button" value="บันทึก" class="ui-button" id="btn_submit" name="btn_submit"></div>
<div style="float:right"><input type="button" value="เพิ่มรายการ" class="ui-button" id="btn_add" name="btn_add" onclick="addFile(), getValueArray();"></div>
<div style="clear:both;"></div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>