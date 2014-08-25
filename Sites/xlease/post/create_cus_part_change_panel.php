<?php
include("../config/config.php");

$regis = $_GET['regis'];
$nowdate = nowDate();

$qry_name=pg_query("select * from \"Fc\" WHERE \"C_REGIS\" = '$regis'");
if($res_name=pg_fetch_array($qry_name)){
    $C_REGIS=$res_name["C_REGIS"];
    $C_CARNUM=$res_name["C_CARNUM"];
    $C_COLOR=$res_name["C_COLOR"];
}
?>

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
    <td width="15%"><b>ทะเบียนรถ</b></td>
    <td width="35%"><?php echo $C_REGIS; ?></td>
    <td width="15%"><b>เลขตัวถัง</b></td>
    <td width="35%"><?php echo $C_CARNUM; ?></td>
</tr>
<tr>
    <td><b>สีรถ</b></td>
    <td><?php echo $C_COLOR; ?></td>
    <td></td>
    <td></td>
</tr>
</table>
</div>

<?php
$qry_name=pg_query("select * from \"UNContact\" WHERE \"C_REGIS\" = '$regis'");
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $arr_idno .= $IDNO.",";
    
    $qry_vcorp=pg_query("select * from corporate.\"VCorpContact\" WHERE \"IDNO\" = '$IDNO'");
    if($res_vcorp=pg_fetch_array($qry_vcorp)){
        $TypeContact=$res_vcorp["TypeContact"];
        $AcClose=$res_vcorp["AcClose"];
        if(!$AcClose || $AcClose == 'FALSE' || $AcClose == 'f'){
            $last_idno = $IDNO;
        }
        
        $qry_tc=pg_query("select * from corporate.\"type_corp\" WHERE \"contact_code\" = '$TypeContact'");
        if($res_tc=pg_fetch_array($qry_tc)){
            $dtl_code=$res_tc["dtl_code"];
        }
    }
    
?>
<div class="wbox"><b>IDNO</b> <?php echo "$IDNO"; ?> <b>ชื่อ/สกุล</b> <?php echo "$full_name"; ?> <b>รูปแบบ</b> <?php echo "$TypeContact $dtl_code"; ?><hr />

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#E0E0E0" style="font-weight:bold; text-align:center">
    <td width="25%">Invoice NO</td>
    <td width="25%">DueDate</td>
    <td width="25%">RefReceipt</td>
    <td width="25%">Amt</td>
</tr>
<?php
$qry_corp=pg_query("select * from corporate.\"corpinvoice\" WHERE \"IDNO\" = '$IDNO' AND \"Cancel\"='false' ORDER BY \"DueDate\" ASC");
$numrow_corp = pg_num_rows($qry_corp);
while($res_corp=pg_fetch_array($qry_corp)){
    $inv_no=$res_corp["inv_no"];
    $DueDate=$res_corp["DueDate"];
    $amt=$res_corp["amt"];
    $RefReceipt=$res_corp["RefReceipt"];
echo "
<tr>
    <td>$inv_no</td>
    <td>$DueDate</td>
    <td>$RefReceipt</td>
    <td align=right>".number_format($amt,2)."</td>
</tr>";
}
if($numrow_corp == 0){
    echo "<tr><td colspan=4 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>
</div>

<?php
}
?>

<div align="right"><input type="button" name="btnnext" id="btnnext" value="ไปขั้นตอนต่อไป »" class="ui-button"></div>

<script type="text/javascript">
$(document).ready(function(){
    $("#btnnext").click(function(){
        $.post('create_cus_part_change_check.php',{
            regis: '<?php echo $C_REGIS; ?>',
            idno: '<?php echo $arr_idno; ?>'
        },
        function(data){
            if(data.success){
                $("#panel").empty();
                $("#panel").load("create_cus_part_change_selectcus.php?regis=<?php echo $regis; ?>&lastidno=<?php echo $last_idno; ?>");
            }else{
                alert(data.message);
            }
        },'json');
    });
});
</script>