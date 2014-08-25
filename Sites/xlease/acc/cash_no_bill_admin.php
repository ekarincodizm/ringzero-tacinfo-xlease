<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">

function ChangeCount(id){
    if($('#money'+ id).val() == ""){
        $('#countpay'+ id).val(1)
        alert('กรุณาใส่ ยอดค่างวด (รวม VAT) ก่อนค่ะ !');
    }else{
        $('#divmoney'+ id).val($('#countpay'+ id).val()*$('#money'+ id).val());
    }
}

function ChangeMoney(id){
    if($('#money'+ id).val() != ""){
        $('#divmoney'+ id).val($('#countpay'+ id).val()*$('#money'+ id).val());
    }
}

    function chkDate(){
        $.post('process_check_date.php',{
            datepick: $('#datepick').val(),
            bank: $('#bank').val()
        },
        function(data){
            if(data.success){
                
            }else{
                alert(data.message);
            }
        },'json');
    }

    function chkValue(){
        var num = counter-1;
        var al;
        var n;
        var sbmt = 0;
        
        if($('#datepick').val() == ""){
            alert('ไม่พบ วันที่โอน');
            return false;
        }
                
        for(i=1; i<=num; i++){
            n = 0;
            al = "";
            al = "ผิดผลาด ในรายการที่ #"+ i +" ดังนี้\n";
            if($('#bran'+ i).val() == ""){
                al += "- รหัสสาขาที่โอน\n";
                n++;
            }
            if($('#money'+ i).val() == ""){
                al += "- ยอดค่างวด\n";
                n++;
            }
            
            if(n > 0){
                sbmt++;
                alert(al);
            }
        }
        if(sbmt > 0){
            return false;
        }else{
            document.f_list.submit();
        }
        
    }

var counter = 2;
$(document).ready(function(){

    $('#addButton').click(function(){
        
        var newTextBoxDiv = $(document.createElement('div'))
            .attr("id", 'TextBoxDiv' + counter);
        
table = "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\" style=\"border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px\">"
+ " <tr bgcolor=\"#CEE7FF\"><td colspan=\"6\"><b>รายการที่ #" + counter + "</b></td></tr>"
+ " <tr>"
+ " <td width=\"10%\" align=\"right\"><b>เวลาโอน</b></td>"
+ " <td width=\"25%\">ชั่วโมง "
+ " <select name=\"hh" + counter + "\" id=\"hh" + counter + "\">"
+ " <?php
for($i=0; $i<24; $i++){
echo "<option value=$i>$i</option>";
}
?>"
+ " </select>"
+ " นาที "
+ " <select name=\"mm" + counter + "\" id=\"mm" + counter + "\">"
+ " <?php
for($i=0; $i<60; $i++){
echo "<option value=$i>$i</option>";
}
?>"
+ " </select>"
+ " </td>"
+ " <td width=\"15%\" align=\"right\"><b>รหัสสาขาที่โอน </b></td>"
+ " <td width=\"10%\"><input type=\"text\" id=\"bran" + counter + "\" name=\"bran" + counter + "\" size=\"15\"></td>"
+ " <td width=\"20%\" align=\"right\"><b>จำนวนเงิน </b></td>"
+ " <td width=\"20%\"><input type=\"text\" id=\"money" + counter + "\" name=\"money" + counter + "\" size=\"15\" style=\"text-align:right\" onkeyup=\"JavaScript:ChangeMoney(" + counter + ");\"> บาท.</td>"
+ " </tr>"
+ " </table>";

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
        $('#counter').val(counter);
        counter++;
    });
    
    $("#removeButton").click(function(){
        if(counter==2){
            alert("ห้ามลบ !!!");
            return false;
        }
        $('#counter').val(counter);
        counter--;
        $("#TextBoxDiv" + counter).remove();
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
    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>เงินโอนไม่ผ่าน Bill Payment - Backdoor Tranpay</B></legend>

<div class="ui-widget">

<form name="f_list" id="f_list" action="cash_no_bill_insert_admin.php" method="post">

<div style="padding: 10px 0 10px 0">
<b>ธนาคาร</b> : <select name="bank" id="bank">
<?php
$qry_bank=pg_query("select * from \"bankofcompany\" ");
while($res_bank=pg_fetch_array($qry_bank)){
    $bankname = $res_bank["bankname"];
    $bankno = $res_bank["bankno"];
    echo "<option value=\"$bankno\">$bankname</option>\n";
}
?>
</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>วันที่โอน</b> : <input name="datepick" id="datepick" type="text" readonly="true" size="15" style="text-align:center;" value="<?php echo nowDate(); ?>"><input name="btndate" id="btndate" type="button" onclick="displayCalendar(document.f_list.datepick,'yyyy-mm-dd',this);" value="ปฏิทิน"></div>

<div id='TextBoxesGroup'>
    <div id="TextBoxDiv1">

<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr bgcolor="#CEE7FF"><td colspan="6"><b>รายการที่ #1</b></td></tr>
<tr>
    <td width="10%" align="right"><b>เวลาโอน</b></td>
    <td width="25%">ชั่วโมง 
<select name="hh1" id="hh1">
<?php
for($i=0; $i<24; $i++){
    echo "<option value=\"$i\">$i</option>";
}
?>
</select>
     นาที 
<select name="mm1" id="mm1">
<?php
for($i=0; $i<60; $i++){
    echo "<option value=\"$i\">$i</option>";
}
?>
</select>
    </td>
    <td width="15%" align="right"><b>รหัสสาขาที่โอน </b></td>
    <td width="10%"><input type="text" id="bran1" name="bran1" value="" size="15"></td>
    <td width="20%" align="right"><b>จำนวนเงิน </b></td>
    <td width="20%"><input type="text" id="money1" name="money1" size="15" style="text-align:right" onkeyup="JavaScript:ChangeMoney(1);"> บาท.</td>
</tr>
</table>

    </div>
</div>

<div style="float:left"><input type="button" value="บันทึกข้อมูล" id="submitButton" onclick="JavaScript:chkValue();"></div>
<div style="float:right"><input type="button" value="+ เพิ่มรายการ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></div>
<div style="clear:both"></div>
<input type="hidden" id="counter" name="counter" value="1">
</form>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>