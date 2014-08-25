<?php 
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    
</head>
<body>

<?php include("menu.php"); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>

<?php
if(isset($_POST['h_arti_id'])){

    $qry_ct=pg_query("select \"full_name\",\"C_REGIS\",\"car_regis\",\"asset_type\",\"IDNO\",\"CusID\",\"C_COLOR\",\"C_CARNAME\" 
	from \"VContact\" WHERE (\"IDNO\"='".pg_escape_string($_POST[h_arti_id])."')");
    if($res_ct=pg_fetch_array($qry_ct)){
        $full_name = $res_ct["full_name"];
        $c_regis = $res_ct["C_REGIS"];
        $car_regis = $res_ct["car_regis"];
        $asset_type = $res_ct["asset_type"];    if($asset_type == 1){ $regis = $c_regis; }else{ $regis = $car_regis; }
        $G_IDNO = $res_ct["IDNO"];
        $G_CusID = $res_ct["CusID"];
        $C_COLOR = $res_ct["C_COLOR"];
        $C_CARNAME = $res_ct["C_CARNAME"];
    
        $_SESSION["sescr_idno"] = $G_IDNO;
        $_SESSION["sescr_scusid"] = $G_CusID;
    }
    
    $typepayshow = "<select name=\"typepay\"><option value=\"\">เลือก</option>";
    $qry_inf=pg_query("select \"TypeID\",\"TName\" from \"TypePay\" WHERE \"TypeDep\"='C' ORDER BY \"TypeID\" ASC");
    while($res_inf=pg_fetch_array($qry_inf)){
        $TypeID = $res_inf["TypeID"];
        $TName = $res_inf["TName"];
        $typepayshow .= "<option value=\"$TypeID\">$TName</option>";
    }
    $typepayshow .= "</select>";

}
?>

<script>
function validate(){

    var theMessage = "";
    var noErrors = theMessage;

    if (document.frm1.money.value == "") {
        theMessage = theMessage + "\n - กรุณากรอกจำนวนเงิน";       
    }

    // If no errors, submit the form
    if (theMessage == noErrors) {
        return true;
    } else {
        // If errors were found, show alert message
        alert(theMessage);
        return false;
    }
}
</script>

<fieldset><legend><B>สร้างรายการชำระเงินค่าอื่นๆ</B></legend>

<form name="search" method="post" action="">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>ชื่อผู้เช่า, เลขที่สัญญา, ทะเบียนรถ</b>
        <input name="h_arti_id" type="hidden" id="h_arti_id" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>" />
        <input type="text" id="car_id" name="car_id" size="80" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
        <input type="submit" name="submit_search" value="   ค้นหา   ">
      </td>
   </tr>
</table>
</form>
<?php
if(isset($_POST['h_arti_id'])){
?>

<script type="text/javascript">
var gFiles = 0;
function addFile() {
    if(gFiles < 11){
    var li = document.createElement('DIV');
    li.setAttribute('id', 'file-' + gFiles);
    li.innerHTML = '<?php echo $typepayshow; ?><input type="button" value="ลบ" onclick="removeFile(\'file-' + gFiles + '\')">';
    document.getElementById('files-root').appendChild(li);
    gFiles++;
    }
}
function removeFile(aId) {
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}
</script>
      
      
<!-- ======================================= -->
      
<form id="frm1" name="frm1" method="post" action="frm_other_add_ok.php" onsubmit="return validate(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td><b>ชื่อ/สกุล</b></td>
      <td class="text_gray"><?php if(empty($full_name)){ echo "ไม่พบข้อมูล (ลูกค้านอก ให้ระบุ <u>ชื่อ/สกุล</u> ในช่องหมายเหตุด้านล่าง)"; }else{ echo $full_name." (".pg_escape_string($_POST['h_arti_id']).")"; } ?>
      <input type="hidden" name="gidno" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
      </td>
      <td><b>ทะเบียนรถ</b></td>
      <td class="text_gray"><?php if(empty($regis)){ echo "ไม่พบข้อมูล (ลูกค้านอก ให้ระบุ <u>ทะเบียนรถ</u> ในช่องหมายเหตุด้านล่าง)"; }else{ echo $regis; } ?></td>
   </tr>
   <tr align="left">
        <td><b>ประเภทรถ</b></td>
      <td colspan="0" class="text_gray"><?php echo $C_CARNAME; ?></td>
      <td><b>สีรถ</b></td>
      <td colspan="0" class="text_gray"><?php echo $C_COLOR; ?></td>
   </tr>
</table>   
   
   
   
   
<!-- ======================================= -->
<br />
<div>&nbsp;&nbsp;<b><u>รายการที่ผ่านมา</u></b></div>
<table width="98%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">วันที่</td>
        <td align="center">จำนวนเงิน</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td>
        <td align="center">สถานะ</td>
    </tr>
<?php

    $qry_name=pg_query("select A.\"IDCarTax\",A.\"IDNO\",A.\"CusAmt\",B.\"TypePay\",A.\"ApointmentDate\",B.\"TaxValue\",A.\"TaxDueDate\" from carregis.\"CarTaxDue\" A INNER JOIN carregis.\"DetailCarTax\" B on A.\"IDCarTax\" = B.\"IDCarTax\" 
        where (\"IDNO\" = '".pg_escape_string($_POST[h_arti_id])."') AND \"ApointmentDate\" is not null ORDER BY \"IDNO\",\"TaxDueDate\" ASC ");

        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"];
            $TypePay = $res_name["TypePay"];
            $ApointmentDate = $res_name["ApointmentDate"];
            $TaxValue = $res_name["TaxValue"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            
        $qry_name2=pg_query("select \"asset_id\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\" from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"];
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        }else{
            $full_name = "ไม่พบข้อมูล";
            $show_regis = "ไม่พบข้อมูล";
        }
        
        $TName = "";
        $pieces = explode(",", $TypePay);
        for($i=0; $i<count($pieces);$i++){
                $get_type = $pieces[$i];
                $qry_name4=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$get_type' ");
                if($res_name4=pg_fetch_array($qry_name4)){
                    if(count($pieces) == $i+1){  
                        $TName .= $res_name4["TName"];
                    }else{
                        $TName .= $res_name4["TName"].",";
                    }
                }
        }
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="right"><?php echo number_format($CusAmt,2); ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center">
            <?php if( empty($TaxValue) ){ ?>
                <img src="clock.png" border="0" width="16" height="16" align="absmiddle" alt="รอข้อมูลการชำระเงิน">
            <?php }else{ ?>
                <img src="accept.png" border="0" width="16" height="16" align="absmiddle" alt="สำเร็จ">
            <?php } ?>
        </td>
    </tr>
<?php
}
if($rows == 0){
?>
<tr>
    <td align="center" colspan="20">- ไม่พบข้อมูล -</td>
</tr>
<?php    
}
?>
</table>
<br />
   
   
<div>&nbsp;&nbsp;<b><u>เพิ่มข้อมูลใหม่</u></b></div>

<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left" valign="top">
      <td><b>เลือกประเภทบริการ</b></td>
      <td colspan="3">
<div id="files-root">
<?php 
    echo $typepayshow;
?>
<!--<input type="button" value="เพิ่ม" onclick="addFile();"> (เลือกได้สูงสุด 12 ประเภทบริการ)-->
</div>
      </td>
   </tr>

   <tr align="left">
      <td><b>วันที่นัดลูกค้า</b></td>
      <td colspan="3">
<input name="apointmentdate" id="apointmentdate" type="text" readonly="true" size="15" value="<?php echo date("Y/m/d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm1.apointmentdate,'yyyy/mm/dd',this)" value="ปฏิทิน"/>
      </td>
   </tr>
    <tr align="left">
      <td><b>รวมค่าบริการ</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="money" size="15"> บาท.</td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b></td>
      <td colspan="3" class="text_gray"><textarea name="remark" rows="4" cols="50"></textarea></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
</form>
 <?php } ?>

</fieldset>
 
		</td>
	</tr>
</table>

<script type="text/javascript">
function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("car_id","h_arti_id");
</script>

</body>
</html>