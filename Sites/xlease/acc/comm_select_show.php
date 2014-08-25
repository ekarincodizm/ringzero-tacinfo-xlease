<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);

if(empty($idno)){ exit; }

$qry = pg_query("SELECT * FROM \"VContact\" WHERE \"IDNO\"='$idno' ");
if($res = pg_fetch_array($qry)){
    $full_name = $res['full_name'];
    $asset_type = $res['asset_type'];
    $C_REGIS = $res['C_REGIS'];
    $car_regis = $res['car_regis'];
    if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }
}

$qry2 = pg_query("SELECT \"Comm\" FROM \"Fp\" WHERE \"IDNO\"='$idno' ");
if($res2 = pg_fetch_array($qry2)){
    $Comm = $res2['Comm'];
}
?>

<script type="text/javascript">
$(document).ready(function(){
    $('#btn1').click(function(){
        $('#btn1').attr('disabled', true);
        $.post('comm_select_save.php',{
            comm: $('#comm').val(),
            idno: '<?php echo $idno; ?>'
        },
        function(data){
            if(data.success){
                //alert(data.message);
                $('#result').empty();
                $('#result').text(data.message);
                $('#result').fadeOut(4000);

            }else{
                alert(data.message);
                $('#btn1').attr('disabled', false);
            }
        },'json');
    });
});
</script>

<div id="result">

<table width="100%" cellpadding="5" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr>
    <td width="20%"><b>IDNO : </b></td><td width="80%"><?php echo $idno; ?></td>
</tr><tr>
    <td><b>ชื่อ/สกุล : </b></td><td><?php echo $full_name; ?></td>
</tr><tr>
    <td><b>ทะเบียน : </b></td><td><?php echo $regis; ?></td>
</tr><tr>
    <td><b>Commission : </b></td><td><input type="text" name="comm" id="comm" value="<?php echo "$Comm"; ?>" style="text-align:right"> บาท.</td>
</tr><tr>
    <td>&nbsp;</td><td><input type="button" name="btn1" id="btn1" value="บันทึุก"></td>
</tr>
</table>

</div>