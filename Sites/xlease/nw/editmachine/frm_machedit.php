<?php
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขเครื่องยนต์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<div align="right"><input name="button" type="button" onclick="window.location='index.php'" value=" ย้อนกลับ " /></div> 

<fieldset><legend><B>แก้ไขเครื่องยนต์</B></legend>

<script>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.frm_edit.f_mar.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกเลขเครื่องยนต์";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}
</script>




<?php
$idno=trim($_POST["idno_names"]);

$qry_fp=pg_query("select * from \"Fp\" where (\"IDNO\" ='$idno') ");
$res_fp=pg_fetch_array($qry_fp);
  
if(empty($res_fp["IDNO"])){
    echo "LOCKED "."$idno<br>";
    echo $resback="<input type=\"button\" value=\"BACK\" onclick=\"javascript:history.back()\"  />";
}else{
  $fp_cusid=trim($res_fp["CusID"]);
  $fp_carid=trim($res_fp["asset_id"]);
  $fp_stdate=$res_fp["P_STDATE"];
  $fp_pmonth=$res_fp["P_MONTH"];   
  $fp_pvat=$res_fp["P_VAT"];
  $fp_ptotal=$res_fp["P_TOTAL"];
  $fp_pdown=$res_fp["P_DOWN"];
  $fp_pvatofdown=$res_fp["P_VatOfDown"];
  $fp_begin=$res_fp["P_BEGIN"];
  $fp_beginx=$res_fp["P_BEGINX"];
  $fp_fdate=$res_fp["P_FDATE"];	
  $fp_cusby_year=$res_fp["P_CustByYear"];
  $asset_type=$res_fp["asset_type"];

if($asset_type == 1){

$qry_car=pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$idno' ");
if($res_fc=pg_fetch_array($qry_car)){
    $fc_carid=trim($res_fc["CarID"]);
    $fc_name=trim($res_fc["C_CARNAME"]);
    $fc_year=trim($res_fc["C_YEAR"]);
    $fc_regis=trim($res_fc["C_REGIS"]);
    $fc_color=trim($res_fc["C_COLOR"]);
    $fc_num=trim($res_fc["C_CARNUM"]);
    $fc_mar=trim($res_fc["C_MARNUM"]);
    $fc_mi=trim($res_fc["C_Milage"]); 
    $fc_expert=trim($res_fc["exp_date"]);
    $fc_mon=trim($res_fc["C_TAX_MON"]);
    $fc_startdate=trim($res_fc["C_StartDate"]);
    $fc_regis_by=trim($res_fc["C_REGIS_BY"]);
}

}else{
    
    $qry_car=pg_query("select * from \"FGas\" where \"GasID\" ='$fp_carid' ");
    if($res_fc=pg_fetch_array($qry_car)){
        $fc_regis=trim($res_fc["car_regis"]);
        $fc_regis_by=trim($res_fc["car_regis_by"]);
        $fc_year=trim($res_fc["car_year"]);
        $fc_mar=trim($res_fc["marnum"]);
        $fc_num=trim($res_fc["carnum"]);
    }
    
}
?>

<form name="frm_edit" method="post" action="edit_machine.php" onsubmit="return validate(this);">
    <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
	<input type="hidden" name="assettype" value="<?php echo $asset_type; ?>" />
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="left">
      <td width="25%"><b>เลขที่สัญญา</b></td>
      <td width="75%" class="text_gray"><?php echo $idno; ?></td>
   </tr>
<?php
if($asset_type == 1){
?>
    <tr align="left">
      <td><b>ยี่ห้อรถ</b></td>
      <td class="text_gray"><?php echo $fc_name; ?></td>
   </tr>
<?php
}
?>
   <tr align="left">
      <td><b>รุ่นปี</b></td>
      <td class="text_gray"><?php echo $fc_year; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขตัวถัง</b></td>
      <td class="text_gray"><?php echo $fc_num; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขเครื่องยนต์</b></td>
      <td class="text_gray"><input type="text" name="f_mar" value="<?php echo $fc_mar; ?>" /></td>
   </tr>
   <tr align="left">
      <td><b>ทะเบียน</b></td>
      <td class="text_gray"><?php echo $fc_regis; ?></td>
   </tr>
   <tr align="left">
      <td><b>จังหวัดที่จดทะเบียน</b></td>
      <td class="text_gray"><?php echo $fc_regis_by; ?></td>
   </tr>

   <tr align="center">
      <td colspan=2><br><input name="submit" type="submit" value="บันทึก" /></td>
   </tr>
</table>
</form>

<?php
}
?>
 </fieldset> 

</div>
        </td>
    </tr>
</table>         


</body>
</html>