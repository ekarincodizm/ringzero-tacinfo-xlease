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

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    
    $("#tb_search").autocomplete({
        source: "frm_car_pay_search_list.php",
        minLength:1,
        close: function(event, ui){
            $("#divshow").empty();
            $("#divshow").text('กำลังค้นหาข้อมูล กรุณารอสักครู่...');
            var tbsearch = encodeURIComponent ( $("#tb_search").val() );
            $("#divshow").load("frm_car_pay_panel.php?w="+ tbsearch);
        }
    });

    /*
    $('#tb_search').keyup(function(){
        $("#divshow").empty();
        $("#divshow").text('กำลังค้นหาข้อมูล กรุณารอสักครู่...');
        var tbsearch = encodeURIComponent ( $("#tb_search").val() );
        $("#divshow").load("frm_car_pay_panel.php?w="+ tbsearch +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val());
    });
    */
});
</script>    

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>     
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>ใส่ข้อมูลการชำระเงินให้ขนส่ง</b></legend>

<div style="float:left"><b>ค้นหาทะเบียนรถ</b> <input type="text" name="tb_search" id="tb_search" size="30"></div>
<div style="float:right"><!--
<form method="post" action="" name="f_list" id="f_list">
<div align="right">
<b>เดือน</b>
<select name="mm" id="mm">
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
<select name="yy" id="yy">
<?php
if(empty($yy)){
    $nowyear = date("Y");
}else{
    $nowyear = $yy;
}
$year_a = $nowyear + 10; 
$year_b =  $nowyear - 10;

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
</form>-->
</div>
<div style="clear:both"></div>

<div id="divshow">

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td> 
        <td></td>
    </tr>
   
<?php
if( isset($mm) and isset($yy) ){
    
        $qry_name=pg_query("select * from carregis.\"CarTaxDue\"
        where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' AND \"ApointmentDate\" is not null AND \"BookIn\"='false' ORDER BY \"IDNO\" ASC ");
    
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $TaxValue = $res_name["TaxValue"];
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
                if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            
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
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$C_StartDate"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center"><a href="#" onclick="javascript:popU('frm_car_pay_add.php?cid=<?php echo "$IDCarTax";?>','a<?php echo $IDCarTax; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=600')"><img src="add.png" border="0" width="16" height="16" align="absmiddle" alt="เพิ่มการชำระเงิน"></a> 
            <?php if(empty($TaxValue)){ ?>
                
            <?php }//else{ ?>
                <!--<a href="frm_car_pay_edit.php?cid=<?php echo "$IDCarTax";?>"><img src="edit.png" border="0" width="16" height="16" align="absmiddle" alt=""></a>-->
            <?php //} ?>
        </td>
    </tr>
 <?php
        }
}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="7"><a href="frm_car_pay_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
    </tr>                                                                      
<?php } ?>
</table>

</div>

</fieldset>

</div>
		</td>
	</tr>
</table>

</body>
</html>