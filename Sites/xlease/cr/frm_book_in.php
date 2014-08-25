<?php 
include("../config/config.php"); 
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>     

<script language="Javascript">
function showdetails(){ //แสดงข้อมูลก่อนยืนยันอีกครั้ง
	var elem=$('input[name="cid[]"]'); //รายการที่เลือก
	var elemidno=$('input[name="idno[]"]'); //เลขที่สัญญา
	var elemname=$('input[name="full_name[]"]'); //ชื่อลูกค้า
	var elemregis=$('input[name="show_regis[]"]'); //ทะเบียนรถ
	var txtcid='';
	for( i=0; i<elem.length; i++ ){
		if($(elem[i]).attr( 'checked')){
			txtcid=txtcid+'เลขที่สัญญา : '+$(elemidno[i]).val()+',ชื่อลูกค้า : '+$(elemname[i]).val()+',ทะเบียนรถ : '+$(elemregis[i]).val()+'\r\n\n';
		}
	}
	txtcid='รายการที่เลือกมีัดังนี้\r\n\n'+txtcid;
	if(confirm(txtcid)==true)
	{
		document.frm_2.submit();
	}else{
		return false;
	}

}
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
    }
}

function CheckSelect(field) {
    var temp=0;
    for (i = 0; i < field.length; i++){
        if( field[i].checked == true ) temp = temp+1;
    }
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
<?php include("menu.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>

<fieldset><legend><b>รายการรับเล่มเข้า</b></legend>
<!--
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
</form>
-->
<form name="frm_2" id="frm_2" method="post" action="frm_book_in_send.php">

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"><a href="#" onclick="javascript:selectAll('cid');"><u>ทั้งหมด</u></a></td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่เริ่ม</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td>
    </tr>
   
<?php
//if( isset($mm) and isset($yy) ){
    
        //$qry_name=pg_query("select * from carregis.\"CarTaxDue\" where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' AND \"ApointmentDate\" is not null AND \"BookIn\"='false' ORDER BY \"IDNO\" ASC ");

        $qry_name=pg_query("SELECT A.\"IDCarTax\",A.\"IDNO\",A.\"ApointmentDate\",A.\"TaxDueDate\",A.\"TypeDep\",B.\"asset_id\",B.\"full_name\",B.\"asset_type\",B.\"C_REGIS\",B.\"car_regis\",B.\"C_StartDate\"  from carregis.\"CarTaxDue\" A LEFT OUTER JOIN \"VContact\" B on B.\"IDNO\"=A.\"IDNO\" WHERE A.\"ApointmentDate\" is not null AND A.\"BookIn\"='false' ORDER BY B.\"C_REGIS\" ASC ");

        $rows = pg_num_rows($qry_name);
		$in=0;
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
			if($IDNO==""){
				continue;
			}
            //$TaxValue = $res_name["TaxValue"];
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
                if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
                
            $asset_id = $res_name["asset_id"];
            $full_name = $res_name["full_name"];
            $asset_type = $res_name["asset_type"];
            $C_REGIS = $res_name["C_REGIS"];
            $car_regis = $res_name["car_regis"];
			$C_StartDate = $res_name["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate)); 
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
/*
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"];
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        } 
*/     
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><input type="checkbox" id="cid" name="cid[]" value="<?php echo "$IDCarTax"; ?>"></td>
        <td align="center"><?php echo "$IDNO"; ?><input type="hidden" name="idno[]" value="<?php echo $IDNO;?>"></td>
        <td align="left"><?php echo "$full_name"; ?><input type="hidden" name="full_name[]" value="<?php echo $full_name;?>"></td>
        <td align="left"><?php echo "$show_regis"; ?><input type="hidden" name="show_regis[]" value="<?php echo $show_regis;?>"></td>
        <td align="center"><?php echo "$C_StartDate"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td> 
        </td>
    </tr>
 <?php
        }
//}

if($in > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="20"><b>ทั้งหมด</b> <?php echo $in; ?> <b>รายการ</b></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td align="center" colspan="20"><br><input name="button" id="button" type="submit" value="ยืนยันรายการที่เลือก" onClick="return showdetails();"><br><br></td>
    </tr>                                                                  
<?php } ?>
</table>

</form>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>