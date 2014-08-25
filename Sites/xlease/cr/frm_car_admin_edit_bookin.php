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
    
<script>
function confirm1(delUrl,ID) {
  if (confirm("คุณต้องการยกเลิกการรับเล่มเข้า ของ "+ ID +" ใช่หรือไม่ ?")) {
    document.location = delUrl;
  }
}
</script>    

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
        <td>
<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>แก้ไขรายการรับเล่มเข้า</b></legend>

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

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">CRID</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td> 
        <td align="center">#</td>
    </tr>
   
<?php
if( isset($mm) and isset($yy) ){
       
        $qry_name=pg_query("SELECT * FROM carregis.\"CarTaxDue\" where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' AND \"BookIn\"='true' ORDER BY \"IDNO\" ASC ");
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
                if($TypeDep == '101'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            $BookIn = $res_name["BookIn"];
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"]; 
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];   
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"]; 
			$C_StartDate = $res_name2["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate));
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }   
        } 
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="center"><?php echo "$IDCarTax"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$C_StartDate"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center">
            <?php if($ApointmentDate == "-"){ ?>
                -
            <?php }else{ ?>
                <a href="#" onclick="javascript:confirm1('frm_car_admin_editbookin_false.php?cid=<?php echo "$IDCarTax";?>','<?php echo "$IDCarTax";?>');" >ยกเลิก</a>
            <?php } ?>
        </td>
    </tr>
 <?php
        }
}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="10"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
    </tr>                                                                      
<?php }else{ ?>
    <tr>
        <td colspan="10" align="center">- ไม่พบข้อมูล -</td>
    </tr>
<?php } ?>
</table>

</div>

        </td>
	</tr>
</table>

</body>
</html>