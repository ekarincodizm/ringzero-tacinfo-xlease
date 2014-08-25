<?php
include("../config/config.php");
$idno = $_GET['idno'];
$nowdate = nowDate();

$qry_name=pg_query("select * from \"UNContact\" WHERE (\"IDNO\" = '$idno')");
if($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $C_CARNUM=$res_name["C_CARNUM"];
    $C_REGIS=$res_name["C_REGIS"];
    $C_COLOR=$res_name["C_COLOR"];
    $CusID=$res_name["CusID"];
    $asset_id=$res_name["asset_id"];
}
?>

<script type="text/javascript">
$(document).ready(function(){

    $("#signdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    $("#startdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    
    $("#btnsave").click(function(){
        $.post('create_cus_part_save.php',{
            idno: '<?php echo $idno; ?>',
            signdate: $('#signdate').val(),
            startdate: $('#startdate').val(),
            typecontact: $('#typecontact').val(),
            carnum: '<?php echo $C_CARNUM; ?>',
            cusid: '<?php echo $CusID; ?>',
            carid: '<?php echo $asset_id; ?>'
        },
        function(data){
            if(data.success){
                $("#panel").html('<center>'+ data.message +'</center>');
            }else{
                alert(data.message);
            }
        },'json');
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


<div class="yellowbox" style="margin-top:10px">
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
    <td width="15%"><b>ชื่อผู้เช่า</b></td>
    <td width="35%"><?php echo $full_name; ?></td>
    <td width="15%"><b>เลขตัวถัง</b></td>
    <td width="35%"><?php echo $C_CARNUM; ?></td>
</tr>
<tr>
    <td><b>ทะเบียนรถ</b></td>
    <td><?php echo $C_REGIS; ?></td>
    <td><b>สีรถ</b></td>
    <td><?php echo $C_COLOR; ?></td>
</tr>
</table>
</div>

<div class="graybox">
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
    <td width="20%"><b>วันที่ทำสัญญา</b></td>
    <td width="80%"><input type="text" id="signdate" name="signdate" value="<?php echo $nowdate; ?>" size="15" style="text-align:center"></td>
</tr>
<tr>
    <td><b>วันที่งวดแรก</b></td>
    <td><input type="text" id="startdate" name="startdate" value="<?php echo $nowdate; ?>" size="15" style="text-align:center"></td>
</tr>
<tr>
    <td><b>รูปแบบการชำระ</b></td>
    <td>
<select name="typecontact" id="typecontact">
<?php
$qry_type=pg_query("select * from corporate.\"type_corp\" ORDER BY \"contact_code\"");
while($res_type=pg_fetch_array($qry_type)){
    $contact_code=$res_type["contact_code"];
    $dtl_code=$res_type["dtl_code"];
    echo "<option value=\"$contact_code\">$contact_code, $dtl_code</option>";
}
?>
</select>
    </td>
</tr>
</table>
</div>

<div align="right"><input type="button" name="btnsave" id="btnsave" value="บันทึก" class="ui-button"></div>

