<?php 
include("../config/config.php"); 
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="Javascript">
<!--
function selectAll(select)
{
    with (document.f_2)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
        
        if(checkval == true)          
            document.f_2.button2.disabled = false;
        else
            document.f_2.button2.disabled = true;
    }
}

function selectDisable(field){
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0){
        document.f_2.button2.disabled = false;
    }else{
        document.f_2.button2.disabled = true;
    }
    
    

}

function CheckSelect(field) {
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0) {
        return true;
    } else {
        alert('กรุณาเลือกข้อมูล');
        return false;
    }
}
// -->
</script>    

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>สร้างรายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี</b></legend>

<form method="post" action="" name="f_list" id="f_list">
<div align="right">
<b>เดือน</b>
<select name="mm">
<?php
if(empty($mm)){
    $nowmonth = date("m");
}else{
    $nowmonth = $mm;
}
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
    
}
?>    
</select>
<b>ปี</b> 
<select name="yy">
<?php
if(empty($yy)){
    $nowyear = date("Y");
}else{
    $nowyear = $yy;
}
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 5;

$s_b = $year_b+543;

while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
}
?>
</select><input type="submit" name="submit" value="ค้นหา">
</div>
</form>

<form method="post" action="frm_car_search_add.php" name="f_2" id="f_2"> 
<input type="hidden" name="mm" value="<?php echo $mm; ?>">
<input type="hidden" name="yy" value="<?php echo $yy; ?>">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"><input type="checkbox" name="aaa" onclick="javascript:selectAll('cid');"></td>
        <td align="center">ทะเบียน</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">รูปแบบ</td>
        <td align="center">ค่าบริการ</td>
    </tr>
   
<?php
if( isset($mm) and isset($yy) ){

function month_lob($c_m,$c_n){
    $mmm = $c_m-$c_n;
    if($mmm < 1){
        if($mmm == 0){
            $mmm = 12;
        }else{
            $mmm = 12-abs($mmm);
        }
    }
    return $mmm;
}

function month_plus($c_m,$c_n){
    $mmm = $c_m+$c_n;
    if($mmm > 12){
        $mmm = $mmm-12;
    }
    return $mmm;
}   

$mm_4 = month_plus($mm,4);
$mm_6 = month_plus($mm,6);
$mm_8 = month_plus($mm,8);
$mm_12 = month_plus($mm,12);

$a_year = $yy;
$a_month = $mm;

$qry_if=pg_query("SELECT * FROM \"VCarregistemp\" WHERE \"C_StartDate\" is not null AND \"C_YEAR\" is not null ORDER BY \"C_REGIS\" ASC");
$rows = pg_num_rows($qry_if);
while($res_if=pg_fetch_array($qry_if)){
    $C_REGIS = $res_if["C_REGIS"];
    $CarID = $res_if["CarID"];
    $C_YEAR = $res_if["C_YEAR"];
    $C_StartDate = $res_if["C_StartDate"];
    $C_TAX_MON = $res_if["C_TAX_MON"];
	$IDNO = $res_if["IDNO"];
    $full_name = $res_if["full_name"];
    
    list($a_styear,$a_stmonth,$a_stday) = split('-',$C_StartDate);
    
    $plusyear = "";
    $plusyear = $a_year - $C_YEAR;
    if($plusyear < 7){
        $numplus = 6;
        $numplus_2 = 12;
        $numplus_3 = 6;
    }elseif($plusyear == 7){
        if($a_stmonth >= $a_month){
            $numplus = 6;
            $numplus_2 = 12;
            $numplus_3 = 6;
        }else{
            $numplus = 4;
            $numplus_2 = 8;
            $numplus_3 = 12;
        }
    }else{
        $numplus = 4;
        $numplus_2 = 8;
        $numplus_3 = 12;
    }
    
    $date_check_1 = date("Y-m-d", strtotime("+$numplus month",strtotime($C_StartDate)));
    $date_check_2 = date("Y-m-d", strtotime("+$numplus_2 month",strtotime($C_StartDate)));
    $date_check_3 = date("Y-m-d", strtotime("+$numplus_3 month",strtotime($C_StartDate)));

    list($n_styear_1,$n_stmonth_1,$n_stday_1) = split('-',$date_check_1);
    list($n_styear_2,$n_stmonth_2,$n_stday_2) = split('-',$date_check_2);
    list($n_styear_3,$n_stmonth_3,$n_stday_3) = split('-',$date_check_3);
    
    if( ($n_stday_1 != $n_stday_2) && ($n_stday_2 != $n_stday_3) ){
        while($a_stday != $n_stday_1){
            $date_check_1 = date("Y-m-d", strtotime("-1 day",strtotime($date_check_1)));
            list($new_styear_1,$new_stmonth_1,$new_stday_1) = split('-',$date_check_1);
            if($n_stmonth_1 != $new_stmonth_1){
                list($n_styear_1,$n_stmonth_1,$n_stday_1) = split('-',$date_check_1);
                break;
            }else{
                list($n_styear_1,$n_stmonth_1,$n_stday_1) = split('-',$date_check_1);
            }
        }
        
        while($a_stday != $n_stday_2){
            $date_check_2 = date("Y-m-d", strtotime("-1 day",strtotime($date_check_2)));
            list($new_styear_2,$new_stmonth_2,$new_stday_2) = split('-',$date_check_2);
            if($n_stmonth_2 != $new_stmonth_2){
                list($n_styear_2,$n_stmonth_2,$n_stday_2) = split('-',$date_check_2);
                break;
            }else{
                list($n_styear_2,$n_stmonth_2,$n_stday_2) = split('-',$date_check_2);
            }
        }
        
        while($a_stday != $n_stday_3){
            $date_check_3 = date("Y-m-d", strtotime("-1 day",strtotime($date_check_3)));
            list($new_styear_3,$new_stmonth_3,$new_stday_3) = split('-',$date_check_3);
            if($n_stmonth_3 != $new_stmonth_3){
                list($n_styear_3,$n_stmonth_3,$n_stday_3) = split('-',$date_check_3);
                break;
            }else{
                list($n_styear_3,$n_stmonth_3,$n_stday_3) = split('-',$date_check_3);
            }
        }
    }
    
    if($n_stmonth_1 == $mm || $n_stmonth_2 == $mm || $n_stmonth_3 == $mm){
        if($n_stmonth_1 == $mm){
            $date_check = $date_check_1;
        }elseif($n_stmonth_2 == $mm){
            $date_check = $date_check_2;
        }elseif($n_stmonth_3 == $mm){
            $date_check = $date_check_3;
        }
        
        list($n_styear_c,$n_stmonth_c,$n_stday_c) = split('-',$date_check);
        $date_check = $a_year."-".$n_stmonth_c."-".$n_stday_c;
        
        $C_IDCarTax = 0;
        $qry_ccartax=pg_query("select COUNT(\"IDCarTax\") AS \"C_IDCarTax\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" = '$date_check'");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $C_IDCarTax = $res_ccartax["C_IDCarTax"];
        }
        
        /*
        $TypeDep = 0;
        $qry_ccartax=pg_query("select \"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" = '$date_check'");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $TypeDep = $res_ccartax["TypeDep"];
        }
        */
        
        $TypeDepCHK = "";
        $qry_ccartax=pg_query("select \"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" != '$date_check' ORDER BY \"TaxDueDate\" DESC LIMIT 1");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $TypeDepCHK = $res_ccartax["TypeDep"];
        }
        
        if($C_IDCarTax == 0){
            $case_show = 0;
            if(empty($TypeDepCHK)){
                $show_meter = "มิเตอร์";
                $show_smeter = "300";
                $case_show = 1;
            }elseif($TypeDepCHK == '101'){
                $show_meter = "มิเตอร์";
                $show_smeter = "300";
                $case_show = 2;
            }elseif($TypeDepCHK == '105'){
                $show_meter = "มิเตอร์/ภาษี";
                $show_smeter = $C_TAX_MON;
                $case_show = 3;
            }

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>        <td align="center"><input type="checkbox" id="cid" name="cid[]" value="<?php echo "$C_REGIS"; ?>" onclick="selectDisable(document.f_2.cid);"></td>
            <td align="left"><?php echo "$C_REGIS"; ?></td>
            <td align="center"><?php echo "$IDNO"; ?></td>
            <td align="left"><?php echo "$full_name"; ?></td>
            <td align="center"><?php echo "$C_StartDate"; ?></td>
            <td align="center"><?php echo "$date_check [$numplus เดือน]"; ?></td>
            <td align="left"><?php echo "$show_meter"; ?></td>
            <td align="center"><input type="text" name="smeter[]" value="<?php echo "$show_smeter"; ?>" size="5" style="text-align:right; font-size:11px;"></td>
        </tr>
<?php
        }
    }
}
    if($in > 0){
 ?>

    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $in; ?> <b>รายการ</b></td>
        <td align="right" colspan="6"><!--<a href="frm_car_search_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a>--></td>
    </tr>                                                                      
<?php    
    }else{
    ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="center" colspan="20">- ไม่พบข้อมูล -</td>
    </tr>
    <?php
    }
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
</div>
		</td>
	</tr>
</table>

</body>
</html>