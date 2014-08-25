<?php
include("../config/config.php");

$idno = $_GET['idno'];
$idno2 = $_GET['idno2'];
if( empty($idno) || empty($idno2) ){
    echo "ไม่พบข้อมูล IDNO !";
    exit;
}

$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
if($res_FpFa1=pg_fetch_array($qry_FpFa1)){
    $s_payment_nonvat = $res_FpFa1["P_MONTH"];
    $s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
    $s_fp_ptotal = $res_FpFa1["P_TOTAL"];
}
    $money_all_in_vat = $s_payment_all*$s_fp_ptotal;
    $money_all_no_vat = $s_payment_nonvat*$s_fp_ptotal;
?>
<table cellpadding="5" cellspacing="1" border="0" width="100%" bgcolor="#D0D0D0">
<tr style="font-weight:bold" bgcolor="#80BFFF" align="center">
    <td>งวดที่</td>
    <td>วันครบ<br />กำหนด</td>
    <td>วันที่ชำระ</td>
    <td>เลขที่<br />ใบเสร็จ</td>
    <td>ค่างวด</td>
    <td>VAT</td>
    <td>ยอดค้างรวม VAT<br /><?php echo number_format($money_all_in_vat,2); ?></td>
    <td>ยอดค้างไม่รวม VAT<br /><?php echo number_format($money_all_no_vat,2); ?></td>
    <td>เงินต้น</td>
    <td>ดอกผล</td>
    <td>ดอกผล<br />สะสม</td>
    <td>ดอกผล<br />ค้างรับ</td>
</tr>


<?php
$qry=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\"='$idno' ORDER BY \"DueNo\" ASC");
while($res=pg_fetch_array($qry)){
    $DueNo = $res["DueNo"];
    $DueDate = $res["DueDate"];
    $R_Date = $res["R_Date"];
    $R_Receipt = $res["R_Receipt"];
    $R_Money = $res["R_Money"];
    $VatValue = $res["VatValue"];
    $Remine = $res["Remine"];
    $waitincome = $res["waitincome"];
    $Priciple = $res["Priciple"];
    $Interest = $res["Interest"];
    $accint = $res["accint"];
?>
<tr bgcolor="#FFFFFF">
    <td align="center"><?php echo "$DueNo"; ?></td>
    <td align="center"><?php echo "$hh $DueDate"; ?></td>
    <td align="center"><?php echo "$R_Date"; ?></td>
    <td align="center"><?php echo "$R_Receipt"; ?></td>
    <td align="right"><?php echo number_format($s_payment_nonvat,2); ?></td>
    <td align="right"><?php echo number_format($VatValue,2); ?></td>
    <td align="right"><?php echo number_format( $money_all_in_vat-($DueNo*$s_payment_all) ,2);; ?></td>
    <td align="right"><?php echo number_format($Remine,2); ?></td>
    <td align="right"><?php echo number_format($Priciple,2); ?></td>
    <td align="right"><?php echo number_format($Interest,2); ?></td>
    <td align="right"><?php echo number_format($accint,2); ?></td>
    <td align="right"><?php echo number_format($waitincome,2); ?></td>
</tr>
<?php
}
?>
</table>

<div style="float:left; margin:10px 0px 10px 0px"></div>
<div style="float:right; text-align:right; margin:10px 0px 10px 0px">
<?php
$countautoid = 0;
$qry_catid=@pg_query("SELECT COUNT(\"auto_id\") AS countautoid FROM account.\"AccountBookHead\" WHERE \"ref_id\" = 'REF#$idno' AND \"cancel\"='FALSE'");
if($res_catid=@pg_fetch_array($qry_catid)){
    $countautoid = $res_catid["countautoid"];
}
if($countautoid > 0){
    echo "<span id=\"resultchk\" style=\"margin:5px; background-color:#FFFFD2\">รายการนี้ได้ทำการบันทึกไปแล้ว หากท่านต้องการบันทึกซ้ำ ให้กดปุ่ม 'บันทึก' อีกครั้ง!</span>";
}
?>
<span id="result"></span>&nbsp;<input type="submit" name="btn1" id="btn1" value="บันทึก" class="ui-button">
</div>
<div style="clear:both"></div>

<script type="text/javascript">
$('#btn1').click(function(){
    $("#result").empty();
    $("#resultchk").empty();
    $("#result").html('<img src="../images/progress.gif" border="0" width="16" height="16" alt="กำลังโหลด...">');
    
    var v_old = $("#tb_old").val();
    var resstr1=v_old.split("#");
    var v_new = $("#tb_new").val();
    var resstr2=v_new.split("#");
    
    $.post('update_refinance_save.php',{
        str1: resstr1[0],
        str2: resstr2[0]
    },
    function(data){
        if(data.success){
            $("#result").html(data.message);
        }else{
            $("#result").html(data.message);
        }
    },'json');
});
</script>