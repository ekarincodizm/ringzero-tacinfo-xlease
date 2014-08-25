<?php
include("../config/config.php");
$idno = trim($_GET['idno']);

if(empty($idno)){
    echo "ไม่พบ IDNO !";
    exit;
}

$qry_name = pg_query("select * from \"VContact\" WHERE \"IDNO\"='$idno'");
$numrows = pg_num_rows($qry_name);

if($numrows == 0){
    echo "ไม่พบข้อมูล !";
    exit;
}

if($res_name = pg_fetch_array($qry_name)){
    $full_name = $res_name["full_name"];
    $C_CARNAME = $res_name["C_CARNAME"];
    $asset_type = $res_name["asset_type"];
    $C_COLOR = $res_name["C_COLOR"];
    
    if($asset_type == 1){
        $regis = $res_name["C_REGIS"];
    }else{
        $regis = $res_name["car_regis"];
    }
    
    $qry_fp = pg_query("select \"repo\",\"P_LAWERFEEAmt\" from \"Fp\" WHERE \"IDNO\"='$idno'");
    if($res_fp = pg_fetch_array($qry_fp)){
        $repo = $res_fp["repo"];
        $P_LAWERFEEAmt = $res_fp["P_LAWERFEEAmt"];
    }
    
}

if($repo != 't'){
    echo "เลขที่สัญญานี้ ไม่ได้ถูกยึดเข้าบริษัท";
    exit;
}
?>

<script type="text/javascript">
$(document).ready(function(){
    
    $('#btn_submit').click(function(){
        $.post('seiz_car_return_submit.php',{
            idno: '<?php echo $idno; ?>'
        },
        function(data){
            if(data.success){
                $('#panel').empty();
                $('#panel').text(data.message);
                $('#idno').val('');
                $('#idno').focus();
            }else{
                alert(data.message);
            }
        },'json');
    });
    
    $('#btn_cancel').click(function(){
        $('#panel').empty();
        $('#idno').val('');
        $('#idno').focus();
    });
});
</script>

<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#DDEEFF" align="left">
<tr>
    <td width="50%"><b>ชื่อสกุล :</b> <?php echo "$full_name"; ?></td>
    <td width="25%"><b>รถ :</b> <?php echo "$C_CARNAME"; ?></td>
    <td width="25%"><b>ทะเบียน :</b> <?php echo "$regis"; ?></td>
</tr>
<tr>
    <td><b>เลขที่สัญญา :</b> <?php echo "$idno"; ?></td>
    <td><b>สี :</b> <?php echo "$C_COLOR"; ?></td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td><b>ยอด NT :</b> <?php echo number_format($P_LAWERFEEAmt,2); ?> บาท.</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td colspan="3" align="center">
<?php
if($P_LAWERFEEAmt > 0){
    echo "รายการนี้ไม่สามารถคืนรถยึดให้ลูกค้าได้ เนื่องจากมียอดค้าง NT";
}else{
?>
    <input type="button" name="btn_submit" id="btn_submit" value="บันทึก">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="btn_cancel" id="btn_cancel" value=" ยกเลิก ">
<?php
}
?>
    </td>
</tr>
</table>