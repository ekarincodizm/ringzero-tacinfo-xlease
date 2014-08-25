<?php
session_start();
include("../config/config.php");

$recid = trim(pg_escape_string($_POST["idno_names"]));

$qry_chk=pg_query("select * from \"CancelReceipt\" WHERE ref_receipt='$recid' ");
$res_chk=pg_fetch_array($qry_chk);
$numrow_chk=pg_num_rows($qry_chk);
if($numrow_chk==1){
    
    $ms_cc=$res_chk["admin_approve"];
    
    if($ms_cc=='f'){
        $status_cc = "<font color=\"#FFFF00\">ส่งข้อมูลขอยกเลิกใบเสร็จไปแล้ว รอการอนุมัติ";
    }else{
        $status_cc = "<font color=\"#ff0000\">ยกเลิกใบเสร็จไปแล้ว</font>";
    }
    
    $txt_cc=$res_chk["c_memo"];
    $bt_sent = "";
    $show_select_type = 1;

}else{
    $status_cc = "<font color=\"#008000\">ยังไม่มีการยกเลิกใบเสร็จ</font>";
    $txt_cc = "";
    $bt_sent = '<input type="submit" name="submit" value="   บันทึก   ">';
    $show_select_type = 0;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<fieldset><legend><B>ยกเลิกใบเสร็จ</B></legend>

  
<?php
$resType=substr($recid,2,1);
if($resType=="R"){
?>

<table width="100%" border="0" cellSpacing="2" cellPadding="2" align="center" bgcolor="#79BCFF">
<?php
    $qry_p=pg_query("select \"IDNO\" from \"Fr\" WHERE \"R_Receipt\" = '$recid' ");
    $numr=pg_num_rows($qry_p);
    $s_res=pg_fetch_array($qry_p);
    $ids=$s_res["IDNO"];

if($numr==0){
?>
    <tr>
        <td colspan="6">ไม่พบข้อมูล</td>
    </tr>

<?php
}else{
?>

    <tr>
        <td colspan="6">View Rec. id #<?php echo $recid; ?> : Status = <?php echo $status_cc; ?></td>
    </tr>
	<tr>
        <td colspan="6" style="background-color:#FFFFFF;"><b>Detail Rec.</b><br />
<?php
	  $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$ids' ");
	  $rs_dtl=pg_fetch_array($qry_name);
	  
	  if($rs_dtl["C_REGIS"]==""){
          $rec_regis="ทะเบียน ".$rs_dtl["car_regis"];
          $rec_cnumber="เลขถังแก๊ส ".$rs_dtl["gas_number"];
          $res_band="ยี่ห้อแก๊ส ".$rs_dtl["gas_name"];
      }else{
          $rec_regis="ทะเบียน ".$rs_dtl["C_REGIS"];
          $rec_cnumber="เลขตัวถัง ".$rs_dtl["C_CARNUM"];
          $res_band="ยี่ห้อรถ ".$rs_dtl["C_CARNUM"];
      }
	  
	  echo "ชื่อ/นามสกุล ".$rs_dtl["full_name"]."<br>".$rec_regis;
	 ?>	 </td>
    </tr>
</table>



<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
        <td>IDNO</td>
        <td>R_DueNo</td>
        <td>R_Receipt</td>
        <td>R_Money</td>
        <td>PayType</td>
        <td>Cancel</td>
    </tr>

<?php
$qry_plog=pg_query("select * from \"Fr\" WHERE \"R_Receipt\" = '$recid' LIMIT(1) ");
$numr=pg_num_rows($qry_plog);
while($reslog=pg_fetch_array($qry_plog)){
    
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$reslog[IDNO]' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        $vat = $res_cc1['VatValue'];
    }
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center"><?php echo $reslog["IDNO"]; ?></td>
    <td align="center"><?php echo $reslog["R_DueNo"]; ?></td>
    <td align="center"><?php echo $reslog["R_Receipt"]; ?></td>
    <td align="right"><?php echo number_format($reslog["R_Money"]+$vat,2); ?></td>
    <td align="center"><?php echo $reslog["PayType"]; ?></td>
    <td align="center"><?php echo $reslog["Cancel"]; ?></td>
</tr>
  
<form method="post" action="cancel_rec_process.php">
    <input type="hidden" name="fullname" value="<?php echo $rs_dtl["full_name"]; ?>" />
	<input type="hidden" name="s_sta_rec" value="R" />
	<input type="hidden" name="s_idno" id="s_idno" value="<?php echo $reslog["IDNO"]; ?>" />
	<input type="hidden" name="s_recid" id="s_recid" value="<?php echo $reslog["R_Receipt"]; ?>" />
	<input type="hidden" name="s_cdate" id="s_cdate" value="<?php echo date("Y-m-d");?>" />
	<input type="hidden" name="s_cmoney" id="s_cmoney" value="<?php echo $reslog["R_Money"]+$vat; ?>" />
	<input type="hidden" name="s_ref_prndate" id="s_ref_prndate" value="<?php echo $reslog["R_Prndate"]; ?>" />
    <input type="hidden" name="s_ref_recdate" id="s_ref_prndate" value="<?php echo $reslog["R_Date"]; ?>" />
	<input type="hidden" name="s_ref_recid" id="s_ref_recid" value="<?php echo $reslog["R_Receipt"]; ?>" />
	<input type="hidden" name="s_paytype" id="s_paytype" value="<?php echo $reslog["PayType"]; ?>" />
<?php
if($show_select_type == 0){
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><br></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><input name="r_type" type="radio" value="s_money" checked="checked" />เข้าเงินรับฝาก</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><input name="r_type" type="radio" value="c_money" />จ่ายเป็นเงินสด</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><br></td>
    </tr>
<?php
}
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><b>ระบุเหตุผลที่ยกเลิก</b></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><textarea  name="cc_memo" cols="80" rows="3"   /><?php echo $txt_cc; ?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6" align="center"><?php echo $bt_sent; ?></td>
    </tr>
</form> 
</table>

 <?php
        } //ปิด while
    } // ปิด else
}elseif($resType=="N" || $resType=="K"){ //===================== จบ TYPE R ==========================//
?>

<table width="100%" border="0" cellSpacing="2" cellPadding="2" align="center" bgcolor="#79BCFF">
<?php
    $qry_p=pg_query("select \"IDNO\" from \"FOtherpay\" WHERE \"O_RECEIPT\" = '$recid' ");
    $numr=pg_num_rows($qry_p);
    $s_res=pg_fetch_array($qry_p);
    $ids=$s_res["IDNO"];

if($numr==0){
?>
    <tr>
        <td colspan="6">ไม่พบข้อมูล</td>
    </tr>

<?php
}else{
?>

    <tr>
        <td colspan="6">View Rec. id #<?php echo $recid; ?> : Status = <?php echo $status_cc; ?></td>
    </tr>
    <tr>
        <td colspan="6" style="background-color:#FFFFFF;"><b>Detail Rec.</b><br />
<?php
      $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$ids' ");
      $rs_dtl=pg_fetch_array($qry_name);
      
      if($rs_dtl["C_REGIS"]==""){
          $rec_regis="ทะเบียน ".$rs_dtl["car_regis"];
          $rec_cnumber="เลขถังแก๊ส ".$rs_dtl["gas_number"];
          $res_band="ยี่ห้อแก๊ส ".$rs_dtl["gas_name"];
      }else{
          $rec_regis="ทะเบียน ".$rs_dtl["C_REGIS"];
          $rec_cnumber="เลขตัวถัง ".$rs_dtl["C_CARNUM"];
          $res_band="ยี่ห้อรถ ".$rs_dtl["C_CARNUM"];
      }
      
      echo "ชื่อ/นามสกุล ".$rs_dtl["full_name"]."<br>".$rec_regis;
     ?>     </td>
    </tr>
</table>


<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
        <td>IDNO</td>
        <td>O_DATE</td>
        <td>O_RECEIPT</td>
        <td>O_MONEY</td>
        <td>O_Type</td>
        <td>O_PRNDATE</td>
    </tr>

<?php
$qry_plog=pg_query("select * from \"FOtherpay\" WHERE \"O_RECEIPT\" = '$recid' LIMIT(1) ");
$numr=pg_num_rows($qry_plog);
while($res_ns=pg_fetch_array($qry_plog)){
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center"><?php echo $res_ns["IDNO"]; ?></td>
    <td align="center"><?php echo $res_ns["O_DATE"]; ?></td>
    <td align="center"><?php echo $res_ns["O_RECEIPT"]; ?></td>
    <td align="right"><?php echo number_format($res_ns["O_MONEY"],2); ?></td>
    <td align="center"><?php echo $res_ns["O_Type"]; ?></td>
    <td align="center"><?php echo $res_ns["O_PRNDATE"]; ?></td>
</tr>

<form method="post" action="cancel_rec_process.php">
    <input type="hidden" name="fullname" value="<?php echo $rs_dtl["full_name"]; ?>" />
	<input type="hidden" name="s_sta_rec" value="N" />
	<input type="hidden" name="s_idno" id="s_idno" value="<?php echo  $res_ns["IDNO"]; ?>" />
	<input type="hidden" name="s_recid" id="s_recid" value="<?php echo $res_ns["O_RECEIPT"]; ?>" />
	<input type="hidden" name="s_cdate" id="s_cdate" value="<?php echo date("Y-m-d");?>" />
	<input type="hidden" name="s_cmoney" id="s_cmoney" value="<?php echo $res_ns["O_MONEY"]; ?>" />
	<input type="hidden" name="s_ref_prndate" id="s_ref_prndate" value="<?php echo $res_ns["O_PRNDATE"]; ?>" />
    <input type="hidden" name="s_ref_recid" id="s_ref_recid" value="<?php echo $res_ns["O_RECEIPT"]; ?>" />
	<input type="hidden" name="s_paytype" id="s_paytype" value="<?php echo $res_ns["PayType"]; ?>" />
	<input type="hidden" name="s_ref_recdate" id="s_ref_prndate" value="<?php echo $res_ns["O_DATE"]; ?>" />
   

<?php
if($show_select_type == 0){
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><br></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><input name="r_type" type="radio" value="s_money" checked="checked" />เข้าเงินรับฝาก</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><input name="r_type" type="radio" value="c_money" />จ่ายเป็นเงินสด</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><br></td>
    </tr>
<?php
}
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><b>ระบุเหตุผลที่ยกเลิก</b></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><textarea  name="cc_memo" cols="80" rows="3"   /><?php echo $txt_cc; ?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6" align="center"><?php echo $bt_sent; ?></td>
    </tr>
</form>    
</table>
 <?php
        }
    }
}elseif($resType=="V"){
?>
 
<table width="100%" border="0" cellSpacing="2" cellPadding="2" align="center" bgcolor="#79BCFF">
<?php
    $qry_p=pg_query("select \"IDNO\" from \"FVat\" WHERE \"V_Receipt\" = '$recid' ");
    $numr=pg_num_rows($qry_p);
    $s_res=pg_fetch_array($qry_p);
    $ids=$s_res["IDNO"];

if($numr==0){
?>
    <tr>
        <td colspan="6">ไม่พบข้อมูล</td>
    </tr>

<?php
}else{
?>

    <tr>
        <td colspan="6">View Rec. id #<?php echo $recid; ?> : Status = <?php echo $status_cc; ?></td>
    </tr>
    <tr>
        <td colspan="6" style="background-color:#FFFFFF;"><b>Detail Rec.</b><br />
<?php
      $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$ids' ");
      $rs_dtl=pg_fetch_array($qry_name);
      
      if($rs_dtl["C_REGIS"]==""){
          $rec_regis="ทะเบียน ".$rs_dtl["car_regis"];
          $rec_cnumber="เลขถังแก๊ส ".$rs_dtl["gas_number"];
          $res_band="ยี่ห้อแก๊ส ".$rs_dtl["gas_name"];
      }else{
          $rec_regis="ทะเบียน ".$rs_dtl["C_REGIS"];
          $rec_cnumber="เลขตัวถัง ".$rs_dtl["C_CARNUM"];
          $res_band="ยี่ห้อรถ ".$rs_dtl["C_CARNUM"];
      }
      
      echo "ชื่อ/นามสกุล ".$rs_dtl["full_name"]."<br>".$rec_regis;
     ?>     </td>
    </tr>
</table>


<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
        <td>IDNO</td>
        <td>V_DueNo</td>
        <td>V_Receipt</td>
        <td>V_Date</td>
        <td>VatValue</td>
        <td>Paid_Status</td>
        <td>Cancel</td>
    </tr>
<?php
$qry_plog=pg_query("select * from \"FVat\" WHERE \"V_Receipt\" = '$recid' LIMIT(1) ");
$numr=pg_num_rows($qry_plog);
while($res_v=pg_fetch_array($qry_plog)){
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center"><?php echo $res_v["IDNO"]; ?></td>
    <td align="center"><?php echo $res_v["V_DueNo"]; ?></td>
    <td align="center"><?php echo $res_v["V_Receipt"]; ?></td>
    <td align="center"><?php echo $res_v["V_Date"]; ?></td>
    <td align="right"><?php echo number_format($res_v["VatValue"],2); ?></td>
    <td align="center"><?php echo $res_v["Paid_Status"]; ?></td>
    <td align="center"><?php echo $res_v["Cancel"]; ?></td>
</tr>
	
<form method="post" action="cancel_rec_process.php">
    <input type="hidden" name="fullname" value="<?php echo $rs_dtl["full_name"]; ?>" />
	<input type="hidden" name="s_sta_rec" value="V" />
	<input type="hidden" name="s_idno" id="s_idno" value="<?php echo   $res_v["IDNO"]; ?>" />
	<input type="hidden" name="s_recid" id="s_recid" value="<?php echo $res_v["V_Receipt"]; ?>" />
	<input type="hidden" name="s_cdate" id="s_cdate" value="<?php echo date("Y-m-d");?>" />
	<input type="hidden" name="s_cmoney" id="s_cmoney" value="<?php echo $res_v["VatValue"]; ?>" />
	<input type="hidden" name="s_ref_prndate" id="s_ref_prndate" value="<?php echo $res_v["V_PrnDate"]; ?>" />
    <input type="hidden" name="s_ref_recid" id="s_ref_recid" value="<?php echo $res_v["V_Receipt"]; ?>" />                                   
	<input type="hidden" name="s_ref_recdate" id="s_ref_prndate" value="<?php echo $res_v["V_Date"]; ?>" />
	<input type="hidden" name="s_paytype" id="s_paytype" value="" />

<?php
if($show_select_type == 0){
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><br></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><input name="r_type" type="radio" value="s_money" checked="checked" />เข้าเงินรับฝาก</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><input name="r_type" type="radio" value="c_money" />จ่ายเป็นเงินสด</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><br></td>
    </tr>
<?php
}
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><b>ระบุเหตุผลที่ยกเลิก</b></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><textarea  name="cc_memo" cols="80" rows="3"   /><?php echo $txt_cc; ?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7" align="center"><?php echo $bt_sent; ?></td>
    </tr>
</form>    
</table>    

<?php
        }
    }
}
?>
 
</fieldset> 

</div>
        </td>
    </tr>
</table>         

<div align="center"><input name="button" type="button" onclick="window.location='frm_cc_rec.php'" value=" ย้อนกลับ " /></div> 

</body>
</html>