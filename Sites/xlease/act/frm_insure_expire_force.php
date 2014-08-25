<?php 
include("../config/config.php"); 
$mm = pg_escape_string($_POST['mm']);
$yy = pg_escape_string($_POST['yy']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="Javascript">
function selectAll(select)
{
    with (document.frm_2)
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
            document.frm_2.button2.disabled = false;
        else
            document.frm_2.button2.disabled = true;
    }
}

function selectDisable(field){
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0){
        document.frm_2.button2.disabled = false;
    }else{
        document.frm_2.button2.disabled = true;
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
</script>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div style="float:left"><input type="button" name="btnclose" id="btnclose" value="ประกันภัยภาคบังคับ (พรบ.)" onclick="javascript:window.location='frm_insure_expire_force.php' " disabled><input type="button" name="btnclose" id="btnclose" value="ประกันภัยภาคสมัครใจ" onclick="javascript:window.location='frm_insure_expire.php' "></div>
<div style="float:right"><input type="button" name="btnclose" id="btnclose" value="Close" onclick="javascript:window.close()"></div>
<div style="clear:both"></div>

<fieldset><legend><b>กรมธรรม์หมดอายุ - ประกันภัยภาคบังคับ (พรบ.)</b></legend>

<form method="post" action="" name="f_list" id="f_list">
<div align="right" style="margin-bottom:5px">
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

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">เลขกรมธรรม์</td>
        <td align="center">วันที่คุ้มครอง</td>
        <td align="center">วันสิ้นสุด</td>
        <td align="center">Code</td>
        <td align="center">ค่าเบิ้ย</td>
        <td align="center"><a href="#" onclick="javascript:selectAll('cid');"><u>เลือกทั้งหมด</u></a></td>
    </tr>

<form name="frm_2" id="frm_2" method="post" action="frm_insure_expire_letter_force.php" onsubmit="return CheckSelect(document.frm_2.cid);">    
    
<?php
if( isset($_POST['mm']) AND isset($_POST['yy']) ){
    
    $mm = pg_escape_string($_POST['mm']);
    $yy = pg_escape_string($_POST['yy']);
    
    $qry_if=pg_query("select * from \"insure\".\"InsureForce\" WHERE EXTRACT(MONTH FROM \"EndDate\")='$mm' AND EXTRACT(YEAR FROM \"EndDate\")='$yy' AND \"Cancel\"='FALSE'
     ORDER BY \"Company\",\"EndDate\",\"InsFIDNO\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $InsFIDNO = $res_if["InsFIDNO"];
        $IDNO = $res_if["IDNO"];
        $InsID = $res_if["InsID"];
        $StartDate = $res_if["StartDate"];
        $EndDate = $res_if["EndDate"];
        $Code = $res_if["Code"];
        $Premium = $res_if["Premium"]; $Premium = round($Premium,2);
        $Company = $res_if["Company"];
        
        $qry_if2=pg_query("select \"full_name\" from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_if2=pg_fetch_array($qry_if2)){
            $full_name = $res_if2["full_name"];
        }
        
        $sumall_Premium += $Premium;
        
        $nub += 1;
        if( ($Company != $old_Company) AND $nub!=1 ){
            echo "
            <tr bgcolor=\"#FFFFFF\" style=\"font-size:12px;\">
                <td colspan=2><b>บริษัท</b> $old_Company <b>ทั้งหมด</b> $io รายการ</td>
                <td align=\"right\" colspan=6><b>รวมเงิน ".number_format($sum_company_Premium,2)."</b></td>
                <td colspan=1></td>
            </tr>";
            
            $io = 0;
            $sum_company_Premium = 0;
            $sum_company_Premium += $Premium;
            $old_Company = $Company;
        }else{
            $sum_company_Premium += $Premium;
            $old_Company = $Company;
        }
        
        $io += 1;
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$InsFIDNO"; ?></td>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$InsID"; ?></td>
        <td align="center"><?php echo "$StartDate"; ?></td>
        <td align="center"><?php echo "$EndDate"; ?></td>
        <td align="center"><?php echo "$Code"; ?></td>
        <td align="right"><?php echo number_format($Premium,2); ?></td>
        <td align="center"><input type="checkbox" id="cid" name="cid[]" value="<?php echo "$InsFIDNO"; ?>" onclick="selectDisable(document.frm_2.cid);"></td>
    </tr>
<?php        
    }
    if($rows == 0){
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="center" colspan=10>ไม่พบข้อมูล</td>
    </tr>
<?php
    }else{
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td colspan=2><b>บริษัท</b> <?php echo $old_Company; ?> <b>ทั้งหมด</b> <?php echo $io; ?> รายการ</td>
        <td align="right" colspan=6><b>รวมเงิน <?php echo number_format($sum_company_Premium,2); ?></b></td>
        <td colspan="1"></td>
    </tr>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="right" colspan=8><b>รวมทั้งสิ้น <?php echo number_format($sumall_Premium,2); ?></b></td>
        <td colspan="1"></td>
    </tr>
    <tr>
        <td align="center" width="100">ทั้งหมด <?php echo $rows; ?> รายการ</td>
        <td align="center" width="100"><a href="frm_insure_expire_force_pdf.php?yy=<?php echo $yy; ?>&mm=<?php echo $mm; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>พิมพ์หน้านี้</b></a></td>
        <td colspan="6"></td>
        <td align="center" width="100"><input name="button2" id="button2" type="submit" value="ออกจดหมาย" disabled></td>
    </tr>
<?php
    }
}
?>


</table>

</form>

		</td>
	</tr>
</table>

</body>
</html>