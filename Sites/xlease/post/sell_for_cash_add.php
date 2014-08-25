<?php
include("../config/config.php");
$idno = $_GET['idno'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    
    $("#panel").hide();
    $("#panel2").hide();
    
    $("#names").autocomplete({
        source: "s_name.php",
        minLength:2
    });

    $('#birds').keyup(function(){
        $("#panel").show();
        $("#panel2").hide();
        $("#panel").load("panel-user-detail.php?idno="+ $("#names").val());
    });
    
    $('#btn00').click(function(){
        $("#panel").show();
        $("#panel2").hide();
        $("#panel").load("panel-user-detail.php?idno="+ $("#names").val());
    });
    
    $('#btn0').click(function(){
        $("#names").val('ไม่พบข้อมูล');
        $("#panel").hide();
        $("#panel2").show();
        $("#panel").load("panel-user-detail.php?idno="+ $("#names").val());
    });
    
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    
    $("#otdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    
    $('#price').keyup(function(){
        
        $("#non_vat").val( $.CurrencyFormatted(($("#price").val() * 100) / 107) );
        $("#vat").val( $.CurrencyFormatted($("#price").val()-$("#non_vat").val()) );
    });
    
    
$.extend({ 
    CurrencyFormatted: function (amount) {
        var i = parseFloat(amount);
        if(isNaN(i)) { i = 0.00; }
        var minus = '';
        if(i < 0) { minus = '-'; }
        i = Math.abs(i);
        i = parseInt((i + .005) * 100);
        i = i / 100;
        s = new String(i);
        if(s.indexOf('.') < 0) { s += '.00'; }
        if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
        s = minus + s;
        return s;
    }
});
/*
    $('#btn1').click(function(){
            $.post('process_add_sellforcash.php',{
                idno: $('#idno').val(),
                names: $('#names').val(),
                fnames: $('#fnames').val(),
                anames: $('#anames').val(),
                snames: $('#snames').val(),
                no: $('#no').val(),
                sno: $('#sno').val(),
                soi: $('#soi').val(),
                rd: $('#rd').val(),
                tam: $('#tam').val(),
                aum: $('#aum').val(),
                pro: $('#pro').val(),
                post: $('#post').val(),
                san: $('#san').val(),
                age: $('#age').val(),
                card: $('#card').val(),
                idcard: $('#idcard').val(),
                otdate: $('#otdate').val(),
                by: $('#by').val(),
                occ: $('#occ').val(),
                contact: $('#contact').val(),
                datepicker: $('#datepicker').val(),
                paytype: $('#paytype').val(),
                price: $('#price').val(),
                non_vat: $('#non_vat').val(),
                vat: $('#vat').val()
            },
            function(data){
                if(data.success){
                    alert(data.message);
                }else{
                    alert(data.message);
                }
            },'json');
        
    return false;
    });
*/
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
<body id="mm">

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<table width="100%">
<tr>
    <td align="left">
<input type="button" value="ย้อนกลับ"onclick="window.location='sell_for_cash.php'">
    </td>
    <td align="right">
<input type="button" value="ปิดหน้านี้" onclick="javascript:window.close();">
    </td>
</tr>
</table>

<fieldset><legend><B>ขายสด รถยึด</B></legend>

<div align="center">

<div class="ui-widget">

<form method="post" action="process_add_sellforcash.php" />

<input type="hidden" id="idno" name="idno" value="<?php echo $idno; ?>">
<table width="80%" cellpadding="3" cellspacing="3" border="0">
<tr align="left">
    <td><b>ผู้ซื้อใหม่ชื่อ</b></td>
    <td colspan="3"><input id="names" name="names" size="45" /><input type="button" id="btn00" value="ค้นหา"/><input type="button" id="btn0" value="เพิ่มข้อมูลใหม่"/></td>
</tr>
<tr align="center">
    <td colspan="4">

<div id="panel"></div>

<div id="panel2">
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td width="20%"><b>คำนำหน้าชื่อ</b></td>
    <td width="30%" colspan="3"><input id="fnames" name="fnames" size="10" /></td>
</tr>
<tr align="left">
    <td width="20%"><b>ชื่อ</b></td>
    <td width="30%"><input id="anames" name="anames" size="20" /></td>
    <td width="20%"><b>สกุล</b></td>
    <td width="30%"><input id="snames" name="snames" size="20" /></td>
</tr>
<tr align="left">
    <td><b>บ้านเลขที่</b></td>
    <td><input id="no" name="no" size="10" /></td>
    <td><b>SUBNO</b></td>
    <td><input id="sno" name="sno" size="10" /></td>
</tr>
<tr align="left">
    <td><b>ซอย</b></td>
    <td><input id="soi" name="soi" size="10" /></td>
    <td><b>ถนน</b></td>
    <td><input id="rd" name="rd" size="10" /></td>
</tr>
<tr align="left">
    <td><b>ตำบล</b></td>
    <td><input id="tam" name="tam" size="10" /></td>
    <td><b>อำเภอ</b></td>
    <td><input id="aum" name="aum" size="10" /></td>
</tr>
<tr align="left">
    <td><b>จังหวัด</b></td>
    <td><input id="pro" name="pro" size="10" /></td>
    <td><b>รหัสไปรษณีย์</b></td>
    <td><input id="post" name="post" size="10" /></td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFCA">
<tr align="left">
    <td width="20%"><b>สัญชาิติ</b></td>
    <td width="30%"><input id="san" name="san" size="20" /></td>
    <td width="20%"><b>อายุ</b></td>
    <td width="30%"><input id="age" name="age" size="10" /></td>
</tr>
<tr align="left">
    <td><b>บัตร</b></td>
    <td><input id="card" name="card" size="20" /></td>
    <td><b>หมายเลขบัตร</b></td>
    <td><input id="idcard" name="idcard" size="20" /></td>
</tr>
<tr align="left">
    <td><b>วันที่ออกบัตร</b></td>
    <td><input type="text" id="otdate" name="otdate" value="<?php echo nowDate(); ?>"></td>
    <td><b>สถานที่ออกบัตร</b></td>
    <td><input id="by" name="by" size="20" /></td>
</tr>
<tr align="left">
    <td><b>อาชีพ</b></td>
    <td colspan="3"><input id="occ" name="occ" size="20" /></td>
</tr>
<tr align="left">
    <td><b>ที่ติดต่อได้</b></td>
    <td colspan="3"><textarea id="contact" name="contact" rows="4" cols="50"></textarea></td>
</tr>
</table>
</div>

    </td>
</tr>
<tr align="left">
    <td><b>วันที่</b></td>
    <td colspan="3"><input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15"></td>
</tr>
<tr align="left">
    <td><b>สาขาที่ขาย</b></td>
    <td colspan="3">
<select id="paytype" name="paytype">
    <option value="JR">จรัญสนิทวงศ์</option>
    <option value="NV">นวมินทร์</option>
    <option value="TV">ติวานนท์</option>
</select>
    </td>
</tr>
<tr align="left">
    <td width="20%"><b>ราคาขาย</b></td>
    <td width="30%"><input id="price" name="price" size="15" /></td>
    <td width="20%"><b>ก่อน VAT</b></td>
    <td width="30%"><input id="non_vat" name="non_vat" size="15" /></td>
</tr>
<tr align="left">
    <td><b></b></td>
    <td></td>
    <td><b>VAT</b></td>
    <td><input id="vat" name="vat" size="15" /></td>
</tr>
<tr>
    <td colspan="4" align="center"><input type="submit" id="btn1" class="ui-button" value="ยืนยัน"/></td>
</tr>
</table>

</form>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>