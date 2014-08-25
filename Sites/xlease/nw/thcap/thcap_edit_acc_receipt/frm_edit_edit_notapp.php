<?php
include("../../../config/config.php");
$BIDshow = $_GET['BID'];
$BDate = $_GET['date'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP)แก้ไขรายการเงินโอนที่ไม่อนุมัติ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">   
    <link type="text/css" rel="stylesheet" href="../act.css"></link>  
    <link type="text/css" rel="stylesheet" href="../../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>   
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
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
        $.post('../process_check_date.php',{
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

function chkValue()
{
	if(confirm("กรุณาตรวจสอบรายการให้ครบถ้วน หากดำเนินการอนุมัติแล้ว\n\tจะไม่สามารถเพิ่มรายการของวันนั้นๆได้อีก\n\nยืนยันการตรวจสอบหรือไม่?")==true)
	{
		var num = counter;
        var al;
        var n;
        var sbmt = 0;

		var payment = [];
        for( i=1; i<=num; i++ ){
			if($('#money'+ i) == true){
				var c1 = $('#money'+ i).val();
				if ( isNaN(c1) || c1 == "" || c1 == 0){
					alert('ข้อมูลจำนวนเงินไม่ถูกต้อง');
					$('#money'+ i).select();					
					return false;
				}
			}	
        }
		
		$.post('../process_check_date.php',{
            datepick: $('#Bankdate').val(),
            bank: $('#Bankname').val()
        },
        function(data){
            if(data == 1){
				for(i=1; i<=num; i++){
					n = 0;
					al = "";
					al = "ผิดผลาด ในรายการที่ #'+ i +' ดังนี้\n";
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
					return true;
				}
        
            }else{
                alert(data);
            }
        },'json');
	}
	else
	{
		return false;
	}
}
	
	
	
	

var counter = 1;
$(document).ready(function(){	
<?php 	$qry_bank = pg_query("SELECT * FROM finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranStatus\" IN('4') AND \"appvXID\" is not null AND \"bankRevAccID\" = '$BIDshow' AND DATE(\"bankRevStamp\") = '$BDate' order by \"revTranID\" ");
		while($re_bank = pg_fetch_array($qry_bank)){ 
			list($datedata,$timedata) = explode(" ",$re_bank['bankRevStamp']);
			list($hhdata,$mmdata,$ssdata) = explode(":",$timedata);
?>
			var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
        
				table = "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\" style=\"border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px\">"
				+ " <tr bgcolor=\"#CEE7FF\"><td colspan=\"7\"><b>รายการที่ #" + counter + "</b> <input type=\"hidden\" name=\"revTranID[]\" value=\"<?php echo $re_bank['revTranID']; ?>\"></td></tr>"
				+ " <tr>"
				+ " <td width=\"10%\" align=\"right\"><b>เวลาโอน</b></td>"
				+ " <td width=\"25%\">ชั่วโมง "
				+ " <select name=\"hh[]\" id=\"hh" + counter + "\">"
				+ " <?php
				for($i=0; $i<24; $i++){
					if($i < 10){ $num = "0".$i; }else{ $num = $i; }
					if($hhdata == $num){ $selected = "selected"; }else{  $selected = ""; }
					echo "<option value=$num $selected >$num</option>";
				}
				?>"
				+ " </select>"
				+ " นาที "
				+ " <select name=\"mm[]\" id=\"mm" + counter + "\">"
				+ " <?php
				for($i=0; $i<60; $i++){
					if($i < 10){ $num = "0".$i; }else{ $num = $i; }
					if($mmdata == $num){ $selected = "selected"; }else{  $selected = ""; }
					echo "<option value=$num $selected >$num</option>";
				}
				?>"
				+ " </select>"
				+ " </td>"
				+ " <td width=\"15%\" align=\"right\"><b>รหัสสาขาที่โอน </b></td>"
				+ " <td width=\"10%\"><input type=\"text\" id=\"bran" + counter + "\" name=\"bran[]\" size=\"15\" value=\"<?php echo $re_bank['bankRevBranch']; ?>\" ></td>"
				+ " <td width=\"20%\" align=\"right\"><b>จำนวนเงิน </b></td>"
				+ " <td width=\"20%\"><input type=\"text\" id=\"money" + counter + "\" name=\"money[]\" size=\"15\" value=\"<?php echo $re_bank['bankRevAmt']; ?>\" onkeypress=\"check_num(event)\" style=\"text-align:right\" onkeyup=\"JavaScript:ChangeMoney(" + counter + ");\"> บาท.</td>"
				+ " <td width=\"10%\"><input type=\"button\" value=\" - \" onclick=\"removerow("+counter+")\"></td>"
				+ " </tr>"
				+ " </table>";

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
        $('#counter').val(counter);
        counter++;				
<?php } ?>	

});

function removerow(count){
        $("#TextBoxDiv" + count).remove();
};


function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
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
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right">&nbsp;</div>
<div style="clear:both"></div>

<fieldset><legend><B>แก้ไขรายการโอนเงิน</B></legend>

<div class="ui-widget">

<form name="f_list" id="f_list" action="process_edit_notapp.php" method="post">

<div style="padding: 10px 0 10px 0">
<b>ธนาคาร</b> : 
<?php
$qry_bank=pg_query("select * from \"BankInt\" WHERE \"isTranPay\" = '1' AND \"BID\" = '$BIDshow' ");
while($res_bank=pg_fetch_array($qry_bank)){
	$BID = $res_bank["BID"];
    $bankname = $res_bank["BName"];
    $bankno = $res_bank["BAccount"];
    echo "<B>$bankno, $bankname</b>";
}
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<b>วันที่โอน</b> : <b><?php echo $BDate; ?></b>

<div id='TextBoxesGroup'>
</div>

<input type="hidden" name="Bankdate" id="Bankdate" value="<?php echo $BDate; ?>">
<input type="hidden" name="Bankname" id="Bankname" value="<?php echo $BIDshow; ?>">
<div align="center" ><font color="red" size="3px;"><b>* กรุณาตรวจสอบรายการทั้งหมดอีกครั้ง ก่อนยืนยันการทำรายการ<br>หากยังมีรายการค้างอยู่หรือไม่ครบถ้วน กรุณาเพิ่มให้เรียบร้อยก่อนกดยืนยัน *</b></font></div>
<div align="center" ><input type="button" value="ยืนยันข้อมูล" id="submitButton" onclick="return chkValue();"></div>

<input type="hidden" id="counter" name="counter" value="1">
</form>

</div>

 </fieldset>

        </td>
    </tr>
</table>
</body>
</html>