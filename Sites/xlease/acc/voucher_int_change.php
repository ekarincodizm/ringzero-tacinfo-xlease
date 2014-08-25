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
    
    $("#vcid").change(function(){
        var src = $('#vcid option:selected').val();
        if(src != ""){
            $("#show_detail").show();
            $.post('voucher_process_show.php',{
                src: src
            },
            function(data){
                if(data.success){
                    $("#show_detail").html(data.message);
                }else{
                    $("#show_detail").html(data.message);
                }
            },'json');
        }else{
            $("#show_detail").hide();
        }
    });

    $('#cash_change').bind('keypress', function(e) {
        return ( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57)) ? false : true ;
    });
    
    $('#frm1').submit(function(){
        if( $('#cash_change').val() == '' ){
            alert('กรุณากรอก จำนวนเงิน');
            $('#title').focus();
            return false;
        }
    return true;
    });
    
    $("#id").autocomplete({
        source: "voucher_int_change_id.php",
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

<fieldset><legend><B>Internal Payment Voucher รับเข้า</B></legend>

<div style="margin:10px;">

<div class="ui-widget">

<form name="frm1" id="frm1" action="voucher_int_change_insert.php" method="post">

<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
    <td width="20%"><b>วันที่</b></td><td> <?php echo "$now_date"; ?></td>
</tr>
<tr>
    <td><b>เลือกรายการ</b></td>
    <td>
<select name="vcid" id="vcid">
<option value="">เลือก</option>
<?php
$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"finish\" = 'FALSE' AND \"cancel\" = 'FALSE' AND \"qpprove_id\" is not null ORDER BY \"vc_id\" ASC ");
$qry_num = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $vc_id = $res_name["vc_id"];
    $vc_detail = $res_name["vc_detail"]; $arr_vc_detail = explode("\n",$vc_detail);
    $cash_amt = $res_name["cash_amt"];
    $cq_amt = $res_name["cq_amt"];
    $acb_id = $res_name["acb_id"];
    
    if(substr($acb_id,0,1) != "I"){
        continue;
    }
    
    echo "<option value=\"$vc_id\">$vc_id</option>";
}
?>
</select>
    </td>
</tr>
<tr>
    <td><b>เลือกบัญชี</b></td>
    <td>
<select name="select_type" id="select_type">
<?php
$qry_name=pg_query("SELECT * FROM account.\"AcTable\" ORDER BY \"AcName\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $AcID = $res_name["AcID"];
    $AcName = $res_name["AcName"];
    echo "<option value=\"$AcID\">$AcName</option>";
}
?>
</select>
    </td>
</tr>
<tr>
    <td><b>จำนวนเงินที่ใช้จริง</b></td><td> <input id="cash_change" name="cash_change" size="20" value="" /> บาท.</td>
</tr>

<tr>
    <td align="right">&nbsp;</td><td><input type="submit" id="btn1" class="ui-button" value="บันทึก"/></td>
</tr>
<tr>
    <td></td><td><div id="show_detail"></div></td>
</tr>
</table>

</form>

<div style="clear:both;">&nbsp;</div>

<div style="width:300px; float:right; text-align:center;" class="BoxYellow">
<form name="frm1" id="frm1" action="voucher_change_print.php" method="GET" target="_blank">
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