<?php
include("../config/config.php");

$now_date = nowDate();//ดึง วันที่จาก server
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
    
    $('#title').focus();
    
    $("#show1-1").hide();
    $("#show1-2").hide();
    $("#show2-1").hide();
    $("#show2").hide();
    $("#show3").hide();
    $("#show4").hide();
    $("#show5").hide();
    
    $("#cq_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    
    $('#type1').click(function(){
        if( $('#type1').attr( 'checked' ) ){
            $("#show1-1").show();
            $("#show1-2").show();
        }else{
            $("#show1-1").hide();
            $("#show1-2").hide();
        }
    });
    
    $('#type2').click(function(){
        if( $('#type2').attr( 'checked' ) ){
            $("#show2-1").show();
            $("#show2").show();
            $("#show3").show();
            $("#show4").show();
            $("#show5").show();
        }else{
            $("#show2-1").hide();
            $("#show2").hide();
            $("#show3").hide();
            $("#show4").hide();
            $("#show5").hide();
        }
    });
    
    $('#cash_amt').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
    });
    
    $('#cq_id').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57)) ? false : true ;
    });
    
    $('#cq_amt').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
    });

    
    $('#frm1').submit(function(){
        if( $('#title').val() == '' ){
            alert('กรุณากรอก เรื่องที่เบิก');
            $('#title').focus();
            return false;
        }else if( $('#details').val() == '' ){
            alert('กรุณากรอก รายละเอียด');
            $('#details').focus();
            return false;
        }else if( !$('#type1').attr( 'checked' ) && !$('#type2').attr( 'checked' ) ){
            alert('กรุณาเลือก รูปแบบ');
            return false;
        }else if( $('#type1').attr( 'checked' ) && $('#type2').attr( 'checked' ) ){
            if( $('#cash_amt').val() == '' ){
                alert('กรุณากรอก จำนวน เงินสด');
                $('#cash_amt').focus();
                return false;
            }else if( $('#cq_id').val() == '' ){
                alert('กรุณากรอก เลขที่เช็ค');
                $('#cq_id').focus();
                return false;
            }else if( $('#cq_amt').val() == '' ){
                alert('กรุณากรอก ยอดเงินในเช็ค');
                $('#cq_amt').focus();
                return false;
            }
        }else if( $('#type1').attr( 'checked' ) && !$('#type2').attr( 'checked' ) ){
            if( $('#cash_amt').val() == '' ){
                alert('กรุณากรอก จำนวน เงินสด');
                $('#cash_amt').focus();
                return false;
            }
        }else if( !$('#type1').attr( 'checked' ) && $('#type2').attr( 'checked' ) ){
            if( $('#cq_id').val() == '' ){
                alert('กรุณากรอก เลขที่เช็ค');
                $('#cq_id').focus();
                return false;
            }else if( $('#cq_amt').val() == '' ){
                alert('กรุณากรอก ยอดเงินในเช็ค');
                $('#cq_amt').focus();
                return false;
            }
        }
    return true;
    });
    
    $("#id").autocomplete({
        source: "voucher_int_payment_id.php",
        minLength:2
    });
    
    $('#btn001').click(function(){
        if( $('#id').val() == '' ){
            alert('กรุณากรอก เลขที่สำคัญ');
            $('#id').focus();
            return false;
        }
    });

});




</script>

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:13px;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
.BoxYellow {
    margin: 0 auto;
    padding:5px 5px 5px 5px;
    font-size: 12px;
    font-weight: bold;
    color: #666666;
    text-align: center;
    line-height: 20px;
    BORDER-RIGHT: #FCC403 1px solid; BORDER-TOP: #FCC403 1px solid; BORDER-LEFT: #FCC403 1px solid; WIDTH: 500px; BORDER-BOTTOM: #FCC403 1px solid; HEIGHT: auto; BACKGROUND-COLOR: #FFFFD5
}
</style>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

<fieldset><legend><B>Internal Payment Voucher</B></legend>

<div style="margin:10px;">

<div class="ui-widget">

<form name="frm1" id="frm1" action="voucher_int_payment_insert.php" method="post">

<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
    <td><b>วันที่</b></td><td> <?php echo "$now_date"; ?></td>
</tr>
<tr>
    <td><b>เรื่องที่เบิก</b></td><td> <input id="title" name="title" size="60" /></td>
</tr>
<tr>
    <td><b>รายละเอียด</b></td><td> <textarea name="details" id="details" rows="5" cols="70"></textarea></td>
</tr>
<tr>
    <td><b>ผู้เบิก</b></td>
    <td>
<select name="vender" id="vender">
<?php
$qry_name=pg_query("SELECT * FROM account.\"vender\" ORDER BY \"type_vd\",\"vd_name\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $VenderID = $res_name["VenderID"];
    $type_vd = $res_name["type_vd"];
    $vd_name = $res_name["vd_name"];
    echo "<option value=\"$VenderID\">$type_vd $vd_name</option>";
}
?>
</select>
    </td>
</tr>
<tr>
    <td><b>รูปแบบ</b></td><td> <input type="checkbox" name="type1" id="type1" value="1"> เงินสด</td>
</tr>
<tr id="show1-2">
    <td align="right"><i>เลือก AcTable</i></td>
    <td>
<select name="cash_type" id="cash_type">
<?php
$qry_name=pg_query("SELECT * FROM account.\"AcTable\" WHERE \"AcType\"='CASH' ORDER BY \"AcName\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $AcName = $res_name["AcName"];
    echo "<option value=\"$AcID\">$AcName</option>";
}
?>
</select>
    </td>
</tr>
<tr id="show1-1">
    <td align="right"><i>จำนวน เงินสด</i></td><td> <input id="cash_amt" name="cash_amt" size="20" /> บาท.</td>
</tr>

<tr>
    <td>&nbsp;</td><td> <input type="checkbox" name="type2" id="type2" value="1"> เช็ค</td>
</tr>
<tr id="show2-1">
    <td align="right"><i>เลือก AcTable</i></td>
    <td>
<select name="cq_type" id="cq_type">
<?php
$qry_name=pg_query("SELECT * FROM account.\"AcTable\" WHERE \"AcType\"='CUR' ORDER BY \"AcName\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $AcName = $res_name["AcName"];
    echo "<option value=\"$AcID\">$AcName</option>";
}
?>
</select>
    </td>
</tr>
<tr id="show2">
    <td align="right"><i>ธนาคาร</i></td>
    <td><select name="acid_bank" id="acid_bank">
<?php
$qry_name=pg_query("SELECT bankname FROM \"bankofcompany\" ORDER BY \"bankname\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $bankname = $res_name["bankname"];
    echo "<option value=\"$bankname\">$bankname</option>";
}
?>
</select>
    </td>
</tr>
<tr id="show3">
    <td align="right"><i>เลขที่เช็ค</i></td><td> <input id="cq_id" name="cq_id" size="20" /></td>
</tr>
<tr id="show4">
    <td align="right"><i>วันที่บนเช็ค</i></td><td> <input id="cq_date" name="cq_date" type="text" size="10" value="<?php echo $now_date; ?>" style="text-align: center;" readonly></td>
</tr>
<tr id="show5">
    <td align="right"><i>ยอดเงินในเช็ค</i></td><td> <input id="cq_amt" name="cq_amt" size="20" /> บาท.</td>
</tr>

<tr>
    <td align="right">&nbsp;</td><td><input type="submit" id="btn1" class="ui-button" value="บันทึก"/></td>
</tr>
</table>

</form>

<div style="clear:both;">&nbsp;</div>

<div style="width:300px; float:right; text-align:center;" class="BoxYellow">
<form name="frm1" id="frm1" action="voucher_payment_print.php" method="GET" target="_blank">
ป้อนเลขที่สำคัญ&nbsp;<input type="text" name="id" id="id" size="18">&nbsp;<input type="submit" id="btn001" value="พิมพ์ใหม่"/>
</form>
</div>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>