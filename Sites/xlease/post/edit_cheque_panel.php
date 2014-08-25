<?php
include("../config/config.php");

$ChequeNo = $_GET['ChequeNo'];
$PostID = $_GET['PostID'];

if(empty($ChequeNo) || empty($PostID)){
    echo "กรุณาระบุเลขที่เช็ค !";
    exit;
}

$qry_name=pg_query("select * from \"VDetailCheque\" WHERE \"ChequeNo\" = '$ChequeNo' AND \"PostID\" = '$PostID' LIMIT 1");
if($res_name=pg_fetch_array($qry_name)){
    $IsPass=$res_name["IsPass"];
}

if($IsPass == "t"){
    echo "<div style=\"color:#ff0000\">ไม่สามารถแก้ไขเช็ครายการนี้ได้ เนื่องจากเช็คได้เข้าธนาคารไปแล้ว.</div>";
    exit;
}
?>
<table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#0095DD">
<tr>
    <td width="180"><b>เลือกรูปแบบการแก้ไข :</b></td>
    <td>
<select name="cbType" id="cbType">
    <option value="">--- เลือก ---</option>
    <option value="1">แก้ไขรายละเอียดของเช็ค</option>
    <option value="2">แก้ไขรายการที่เช็คจะชำระ</option>
    <option value="3">ยกเลิกเช็ค</option>
</select>
    </td>
</tr>
</table>

<div style="clear:both"></div>

<div id="panel"></div>

<script type="text/javascript">
$(document).ready(function(){
    $('#panel').hide();
});

$('#cbType').change(function(){
    if($('#cbType').val() == 1){
        $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $('#panel').load('edit_cheque_api.php?cmd=Show&t=1&ChequeNo=<?php echo $ChequeNo; ?>&PostID=<?php echo $PostID; ?>');
        $('#panel').show('slow');
    }else if($('#cbType').val() == 2){
        $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $('#panel').load('edit_cheque_api.php?cmd=Show&t=2&ChequeNo=<?php echo $ChequeNo; ?>&PostID=<?php echo $PostID; ?>');
        $('#panel').show('slow');
    }else if($('#cbType').val() == 3){
        $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $('#panel').load('edit_cheque_api.php?cmd=Show&t=3&ChequeNo=<?php echo $ChequeNo; ?>&PostID=<?php echo $PostID; ?>');
        $('#panel').show('slow');
    }else{
        $('#panel').hide();
        alert('กรุณาเลือกรายการ');
    }
});
</script>