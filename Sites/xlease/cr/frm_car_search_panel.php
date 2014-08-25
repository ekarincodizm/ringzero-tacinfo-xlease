<?php
set_time_limit(0);
session_start();
include("../config/config.php"); 
if(!empty($_GET['mm'])) { $mm = pg_escape_string($_GET['mm']);}
if(!empty($_GET['yy'])) { $yy = pg_escape_string($_GET['yy']);}


if( empty($mm) or empty($yy) ){
    echo "invalid param !";
    exit;
}
?>

<form method="post" action="frm_car_search_add.php" name="f_2" id="f_2"> 
<input type="hidden" name="mm" value="<?php echo $mm; ?>">
<input type="hidden" name="yy" value="<?php echo $yy; ?>">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"><a href="#" onclick="javascript:selectAll('cid');"><u>ทั้งหมด</u></a></td>
        <td align="center">ทะเบียน</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">รูปแบบ</td>
        <td align="center">ค่าบริการ</td>
    </tr>
   
<?php
$qry_date = $yy."-".$mm."-01";

$in = 0;
$qry_if=pg_query("SELECT \"C_REGIS\",\"IDNO\",\"C_YEAR\",\"C_StartDate\",\"C_COLOR\" FROM carregis.\"VAllCar\" ORDER BY \"C_REGIS\" ASC");
while($res_if=pg_fetch_array($qry_if)){
    $C_REGIS = $res_if["C_REGIS"];
    $IDNO = $res_if["IDNO"];
    $C_YEAR = $res_if["C_YEAR"];
    $C_StartDate = $res_if["C_StartDate"];
    $C_COLOR = $res_if["C_COLOR"];
    
    
    $due_date = "";
    $due_amount = 0;
    
    if(!empty($C_StartDate)){
        $CreateThisMonth = pg_query("select carregis.\"CreateThisMonth\"('$qry_date','$C_StartDate')");
        $res_CreateThisMonth = pg_fetch_result($CreateThisMonth,0);
        
        if($res_CreateThisMonth == 1){
            $str_type = "ค่าภาษีประจำปี";
            $str_type_code = "101";
        }elseif($res_CreateThisMonth == 2){
            $str_type = "ตรวจมิเตอร์";
            $str_type_code = "105";
            $due_amount = 300;
        }elseif($res_CreateThisMonth == 0){
            continue;
        }
        
        list($a_styear,$a_stmonth,$a_stday) = split('-',$C_StartDate);
        $due_date = $yy."-".$mm."-".$a_stday;
        
        if( checkdate($mm,$a_stday,$yy) ){
            $due_date = $due_date;
        }else{
            $lastDate = idate('d', mktime(0, 0, 0, ($mm + 1), 0, $yy));
            $due_date = $yy."-".$mm."-".$lastDate;
        }
        
    }else{
        $str_type = "";
        $str_type_code = "";
    }

    $full_name = "";
    $asset_id = "";
    $qry_name1=pg_query("SELECT full_name,asset_id FROM \"UNContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_name1=pg_fetch_array($qry_name1)){
        $full_name = $res_name1["full_name"];
        $asset_id = $res_name1["asset_id"];
    }
    
    if($res_CreateThisMonth == 1){
        $qry_ccartax=pg_query("SELECT \"C_TAX_MON\" FROM \"Fc\" WHERE \"CarID\"='$asset_id' ");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $due_amount = $res_ccartax["C_TAX_MON"];
        }
    }
    
    $count_CarTaxDue = 0;
        
    if(!empty($IDNO) AND !empty($C_StartDate) ){
        $qry_ccartax=pg_query("select COUNT(\"IDCarTax\") AS \"C_IDCarTax\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" = '$due_date'");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $count_CarTaxDue = $res_ccartax["C_IDCarTax"];
            
            if($count_CarTaxDue != 0){
                continue; //skip already item
            }
        }
    }

    $in++;
    if(empty($C_StartDate)){
        echo "<tr style=\"background-color:#FA8072; font-size:12px\">";
    }else{
        if($in%2==0){
            echo "<tr class=\"odd\" style=\"font-size:12px\">";
        }else{
            echo "<tr class=\"even\" style=\"font-size:12px\">";
        }
    }
?>
    <td align="center">
        <?php if(!empty($C_StartDate)){ ?>
        <input type="checkbox" id="cid" name="cid[<?php echo $in; ?>]" value="<?php echo "$C_REGIS"; ?>" onclick="selectDisable(document.f_2.cid);">
        <?php } ?>
    </td>
    <td align="left"><?php echo "$C_REGIS"; ?></td>
    <td align="center"><?php echo "$IDNO"; ?><input type="hidden" name="hid_idno[<?php echo $in; ?>]" value="<?php echo "$IDNO"; ?>"></td>
    <td align="left"><?php echo "$full_name"; ?></td>
    <td align="center"><?php echo "$C_StartDate"; ?></td>
    <td align="center"><?php echo "$due_date"; ?><input type="hidden" name="hid_due_date[<?php echo $in; ?>]" value="<?php echo "$due_date"; ?>"></td>
    <?php if(!empty($C_StartDate)){ ?>
    <td align="left"><?php echo "$str_type"; ?><input type="hidden" name="hid_str_type[<?php echo $in; ?>]" value="<?php echo "$str_type_code"; ?>"></td>
    <td align="center">
        <input type="text" name="hid_due_amount[<?php echo $in; ?>]" value="<?php echo "$due_amount"; ?>" size="5" style="text-align:right; font-size:11px;">
    </td>
    <?php }else{ ?>
    <td align="center" colspan="2">ไม่พบวันที่เริ่ม</td>
    <?php } ?>
</tr>
<?php
}

if($in > 0){
 ?>

    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $in; ?> <b>รายการ</b></td>
        <td align="right" colspan="6"><a href="frm_car_search_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
    </tr>                                                                      
<?php    
}else{
    ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="center" colspan="20">- ไม่พบข้อมูล -</td>
    </tr>
    <?php
}
?>
</table>

<?php
if($in > 0){
?>
<div align="center"><br>
<input name="button2" id="button2" type="submit" value="สร้างรายการ" disabled />
</div>
</form>

<?php } ?>