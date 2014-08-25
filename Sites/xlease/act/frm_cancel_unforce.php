<?php include("../config/config.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>   
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
	</tr>
	<tr>
		<td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>

<div class="wrapper">

<fieldset><legend><B>ประกันภัยภาคสมัครใจ - ยกเลิกกรมธรรม์</B></legend>

<form name="search" method="post" action="">
    <input name="h_arti_id" type="hidden" id="h_arti_id" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>" />
    <b>ค้นหาเลขกรมธรรม์ , เลขรับแจ้ง , รหัสประกัน</b> : <input type="text" id="car_id" name="car_id" size="50" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>"/>
    <input type="submit" name="submit" value="   ค้นหา   ">
</form>

<?php

if( !empty($_POST['h_arti_id']) ){

$qry_in=pg_query("select * from \"insure\".\"InsureUnforce\" WHERE (\"InsUFIDNO\"='".pg_escape_string($_POST[h_arti_id])."')");
if($res_in=pg_fetch_array($qry_in)){
    $InsUFIDNO = $res_in["InsUFIDNO"];
    $IDNO = $res_in["IDNO"];
    $InsID = $res_in["InsID"];
    //$TempInsID = $res_in["TempInsID"];      
    $Company = $res_in["Company"];
    $StartDate = $res_in["StartDate"];
    $EndDate = $res_in["EndDate"];
    $CollectCus = $res_in["CollectCus"];
    $CoPayInsID = $res_in["CoPayInsID"];
    $CoPayInsReady = $res_in["CoPayInsReady"];
    $Remark = $res_in["Remark"];
}

$qry_ct=pg_query("select * from insure.\"VInsUnforceDetail\" WHERE (\"InsUFIDNO\"='$InsUFIDNO')");
if($res_ct=pg_fetch_array($qry_ct)){
    $full_name = $res_ct["full_name"];
    $car_num = $res_ct["C_CARNUM"];
    $car_cname = $res_ct["C_CARNAME"];
    //$c_regis = $res_ct["C_REGIS"];          if($c_regis == ""){ $c_regis = "-"; }
    //$car_regis = $res_ct["car_regis"];      if($c_regis == ""){ $c_regis = "-"; }
    //$cus_id = $res_ct["CusID"];
    //$asset_id = $res_ct["asset_id"];
    //$asset_type = $res_ct["asset_type"];    if($asset_type == 1){ $regis = $c_regis; }else{ $regis = $car_regis; }       
    $C_COLOR = $res_ct["C_COLOR"];
}

    $c_com=pg_query("select \"insure\".outstanding_insureunforce('$InsUFIDNO')");
    $res_comms=pg_fetch_result($c_com,0);

?>

<form id="frm_1" name="frm_1" method="post" action="frm_cancel_unforce_ok.php">
<input name="InsUFIDNO" type="hidden" value="<?php echo "$InsUFIDNO"; ?>" />
<input name="CoPayInsReady" type="hidden" value="<?php echo "$CoPayInsReady"; ?>" />
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="left">
    <tr align="left">
      <td width="20%"><b>รหัสประกัน</b></td>
      <td width="30%"><a href="../up/frm_show.php?id=<?php echo $InsUFIDNO; ?>&type=insuf&mode=1" target="_blank"><u><?php echo $InsUFIDNO; ?></u></a></td>
      <td width="20%"><b>เลขที่กรมธรรม์</b></td>
      <td width="30%"><?php echo $InsID; ?>
   </tr>
   </tr>
   <tr align="left">
      <td><b>ชื่อ/สกุล</b></td>
      <td colspan="3"><?php echo $full_name." (".$IDNO.")"; ?></td>
   </tr>
   <tr align="left">
      <td><b>ประเภทรถ</b></td>
      <td><?php echo $car_cname; ?></td>
      <td><b>สีรถ</b></td>
      <td><?php echo $C_COLOR; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขถัง</b></td>
      <td colspan="3"><a href="../up/frm_show.php?id=<?php echo $car_num; ?>&type=reg&mode=2" target="_blank"><u><?php echo $car_num; ?></u></a></td>
   </tr>
   <tr align="left">
      <td><b>บริษัทประกัน</b></td>
      <td colspan="3"><?php echo $Company; ?></td>
   </tr>
   <tr align="left">
      <td><b>วันที่เริ่มคุ้มครอง</b></td>
      <td colspan="3"><?php echo $StartDate; ?></td>
   </tr>
   <tr align="left">
      <td><b>ค่าเบิ้ยที่เก็บ</b></td>
      <td colspan="3"><?php echo number_format($CollectCus,2); ?> บาท.</td>
   </tr>
    <tr align="left">
      <td><b>ค่าเบิ้ยที่ค้างชำระ</b></td>
      <td colspan="3"><?php echo number_format($res_comms,2); ?> บาท.</td>
   </tr>
   <tr align="left">
        <td><b>เพิ่มหมายเหตุ</b></td>
        <td colspan="3"><textarea name="remark" rows="5" cols="50" style="font-size:11px;"></textarea></td>
    </tr>
   <tr align="left">
        <td><b>หมายเหตุ</b></td>
        <td colspan="3"><textarea name="hiddenremark" rows="5" cols="50" style="font-size:11px; background-color:#E0E0E0" readonly><?php echo $Remark; ?></textarea></td>
    </tr>
</table>
</fieldset>

<?php
if($CollectCus == $res_comms){
?>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="ยกเลิกกรมธรรม์"></td>
   </tr>
</table>
<?php
}else{
    echo "<br><br><font color=#ff0000><b>ไม่สามารถยกเลิกกรมธรรม์รายการนี้ได้<br>กรุณายกเลิกใบเสร็จก่อน...</b></font>";    
}
?>
</form>

<?php } ?>

</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
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
        return "gdata_unforce_cancel.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("car_id","h_arti_id");
</script>

</body>
</html>