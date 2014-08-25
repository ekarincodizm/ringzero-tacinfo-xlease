<?php
include("../config/config.php"); 
$cid =pg_escape_string($_GET["cid"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
function confirm1(delUrl) {
  if (confirm("คุณต้องการลบข้อมูลใช่หรือไม่ ?")) {
    document.location = delUrl;
  }
}
</script>
    
<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script> 
</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
	</tr>
	<tr>
		<td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>

<div class="wrapper">

<fieldset><legend><B>แก้ไขข้ิอมูล</B></legend>
<br>
<div align="left"><b><u>รายละเอียดชำระเงินของรายการนี้</u></b></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ประเภท</td>
        <td align="center">เลขที่ใบเสร็จ</td>
        <td align="center">จำนวนเงินตามใบเสร็จ</td>
        <td align="center">วันที่ตามใบเสร็จ</td>
        <td align="center">แก้ไข</td>
        <td align="center">ลบ</td>
    </tr>

<?php
$qry_dt=pg_query("SELECT * FROM carregis.\"DetailCarTax\" WHERE \"IDCarTax\"='$cid' ORDER BY \"IDDetail\" ");
while($res_dt=pg_fetch_array($qry_dt)){
    $IDDetail = $res_dt["IDDetail"];
    $TaxValue = $res_dt["TaxValue"];
    $BillNumber = $res_dt["BillNumber"];
    $CoPayDate = $res_dt["CoPayDate"];
    $TypePay = $res_dt["TypePay"];
    
    $qry_name8=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
    if($res_name8=pg_fetch_array($qry_name8)){
        $TName = $res_name8["TName"];
    }
    
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="left"><?php echo "$BillNumber"; ?></td>
        <td align="right"><?php echo number_format("$TaxValue",2); ?></td>
        <td align="center"><?php echo "$CoPayDate"; ?></td>
        <td><a href="#" onclick="javascript:popU('frm_car_admin_edit_detail.php?id=<?php echo $IDDetail; ?>','<?php echo "N".$IDDetail; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')"  >แก้ไข</a></td>
        <td><a href="#" onclick="javascript:confirm1('frm_car_admin_del_detail.php?id=<?php echo $IDDetail; ?>&crid=<?php echo $cid; ?>');">ลบ</a></td>
    </tr>
<?php
}
?>
</table>

<?php

    $qry_idno=pg_query("select * from carregis.\"CarTaxDue\" WHERE (\"IDCarTax\"='$cid')");
    if($res_idno=pg_fetch_array($qry_idno)){
        $idno = $res_idno["IDNO"];
        $TaxDueDate = $res_idno["TaxDueDate"];
        $remark = $res_idno["remark"];
        $CusAmt = $res_idno["CusAmt"];
        $ApointmentDate = $res_idno["ApointmentDate"];
        $TypeDep = $res_idno["TypeDep"];
    }

    if($idno != '00-00-00000'){
        $qry_ct=pg_query("select * from \"VContact\" WHERE (\"IDNO\"='$idno')");
        if($res_ct=pg_fetch_array($qry_ct)){
            $full_name = $res_ct["full_name"];
            $c_regis = $res_ct["C_REGIS"];
            $car_regis = $res_ct["car_regis"];
            $asset_type = $res_ct["asset_type"];    if($asset_type == 1){ $regis = $c_regis; }else{ $regis = $car_regis; }       
        }
    }else{
        $full_name = "";
        $regis = "";
    }

?>

<form id="frm1" name="frm1" method="post" action="frm_car_admin_ot_edit_ok.php" onsubmit="return validate(this)">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
    <tr align="left">
      <td><b>IDCarTax</b></td>
      <td colspan="3" class="text_gray"><?php echo "$cid"; ?></td>
   </tr>
   <tr align="left">
      <td width="20%"><b>ชื่อ/สกุล</b></td>
      <td width="30%" class="text_gray"><?php if(empty($full_name)){ echo "ไม่พบข้อมูล"; }else{ echo $full_name." (".$idno.")"; } ?></td>
      <td width="20%"><b>ทะเบียนรถ</b></td>
      <td width="30%" class="text_gray"><?php if(empty($regis)){ echo "ไม่พบข้อมูล"; }else{ echo $regis; } ?></td>
   </tr>
   <tr align="left">
      <td><b>วันที่ทำรายการ</b></td>
      <td>
<input name="date_start" id="date_start" type="text" readonly="true" size="15" value="<?php echo $TaxDueDate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm1.date_start,'yyyy-mm-dd',this)" value="ปฏิทิน"/>
      </td>
      <td><b>วันที่นัดลูกค้า</b></td>
      <td>
<input name="apointmentdate" id="apointmentdate" type="text" readonly="true" size="15" value="<?php echo $ApointmentDate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm1.apointmentdate,'yyyy-mm-dd',this)" value="ปฏิทิน"/>
      </td>
   </tr>
    <tr align="left">
      <td><b>ค่าบริการที่ต้องชำระ</b></td>
      <td class="text_gray"><input type="text" name="money" size="15" value="<?php echo $CusAmt; ?>" style="text-align:right;"> บาท.</td>
   </tr>
   <tr align="left">
      <td><b>เพิ่มหมายเหตุ</b></td>
      <td colspan="3"><textarea name="remark" rows="5" cols="60" style="font-size:11px"></textarea></td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b> </td>
      <td colspan="3"><textarea name="hiddenremark" rows="5" cols="60" style="font-size:11px; background-color:#E0E0E0;" readonly><?php echo $remark; ?></textarea></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
</form>
</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>

</body>
</html>