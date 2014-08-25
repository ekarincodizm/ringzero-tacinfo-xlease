<?php
session_start();

include("../config/config.php");

$id_user=$_SESSION["av_iduser"];
$recid = trim(pg_escape_string($_POST["idno_names"]));

$qry_chk=pg_query("select \"admin_approve\", \"statusApprove\", \"c_memo\" from \"CancelReceipt\" WHERE ref_receipt='$recid' order by c_receipt DESC");
$res_chk=pg_fetch_array($qry_chk);
$numrow_chk=pg_num_rows($qry_chk);
if($numrow_chk > 0){
    
    $ms_cc=$res_chk["admin_approve"];
	$statusApprove=$res_chk["statusApprove"];
    
    if($ms_cc=='f'){
        $status_cc = "<font color=\"#FFFF00\">ส่งข้อมูลขอยกเลิกใบเสร็จไปแล้ว รอการอนุมัติ";
		$txt_cc=$res_chk["c_memo"];
		$bt_sent = "";
		$show_select_type = 1;
    }else if($ms_cc=='t' and $statusApprove=='t'){
        $status_cc = "<font color=\"#ff0000\">ยกเลิกใบเสร็จไปแล้ว</font>";
		$txt_cc=$res_chk["c_memo"];
		$bt_sent = "";
		$show_select_type = 1;
    }else if($ms_cc=='t' and $statusApprove=='f'){
        $status_cc = "<font color=\"#ff0000\">ไม่อนุมัติยกเลิกใบเสร็จ</font>";
		$txt_cc = "";
		$bt_sent = '<input type="submit" name="submit" value="   บันทึก   ">';
		$show_select_type = 0;
    }

}else{
    $status_cc = "<font color=\"#008000\">ยังไม่มีการยกเลิกใบเสร็จ</font>";
    $txt_cc = "";
    $bt_sent = '<input type="submit" name="submit" value="   บันทึก   ">';
    $show_select_type = 0;
}

//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" onclick="window.location='frm_cc_rec.php'" value=" ย้อนกลับ " /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
<div style="clear:both"></div>

<div class="wrapper">

<fieldset><legend><B>ยกเลิกใบเสร็จ</B></legend>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script> 
<?php
$resType=substr($recid,2,1);
if($resType=="R"){
    
    $showimagestatus = 0;
    $qry_last=pg_query("select \"IDNO\",\"R_DueNo\",\"R_memo\" from \"Fr\" WHERE \"R_Receipt\"='$recid' ORDER BY \"R_DueNo\" DESC ");
    if( $res_last=pg_fetch_array($qry_last) ){
        $last_IDNO = $res_last["IDNO"];
        $last_R_DueNo = $res_last["R_DueNo"];
        $last_R_memo = $res_last["R_memo"];
    }
    
    $qry_total=pg_query("select \"P_TOTAL\" from \"Fp\" WHERE \"IDNO\"='$last_IDNO' ");
    if( $res_total=pg_fetch_array($qry_total) ){
        $P_TOTAL = $res_total["P_TOTAL"];
    }
    
    if($last_R_DueNo == $P_TOTAL){
        $showimagestatus = 1;
    }
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
	  $qry_name=pg_query("select \"C_REGIS\", \"car_regis\", \"gas_number\", \"gas_name\", \"C_CARNUM\", \"full_name\"
						from \"VContact\" WHERE \"IDNO\"='$ids' ");
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
$showmsgerror = 0;

$qry_plog=pg_query("select \"R_Receipt\" from \"Fr\" WHERE \"R_Receipt\" = '$recid'  ");
$numr=pg_num_rows($qry_plog);

$qry_plog=pg_query("select \"IDNO\", \"R_DueNo\", \"R_Receipt\", \"R_Money\", \"PayType\", \"Cancel\", \"R_Bank\", \"R_Prndate\", \"R_Date\", \"R_memo\"
					from \"Fr\" WHERE \"R_Receipt\" = '$recid' LIMIT 1");
if($reslog=pg_fetch_array($qry_plog)){
    
    $k_idno = $reslog["IDNO"];
    $k_dueno = $reslog["R_DueNo"];
    
    if($k_dueno < 99 AND $k_dueno != 0){
        $qry_cc1=pg_query("select \"VatValue\" from \"FVat\" WHERE \"IDNO\"='$k_idno' AND \"V_DueNo\"='$k_dueno' ");
        if($res_cc1=pg_fetch_array($qry_cc1)){
            $vat = $res_cc1['VatValue'];
        }
    }elseif($k_dueno == 99 OR $k_dueno == 0){
        $qry_cc1=pg_query("select \"V_memo\",\"VatValue\" from \"FVat\" WHERE \"IDNO\"='$k_idno' AND \"V_DueNo\"='$k_dueno' ");
        if($res_cc1=pg_fetch_array($qry_cc1)){
            $V_memo = $res_cc1['V_memo'];
            if($V_memo == "$recid"){
                $vat = $res_cc1['VatValue'];
            }else{
                $vat = 0;
                $showmsgerror = 1;
            }
        }
    }elseif($k_dueno > 99){
        $qry_cc3=pg_query("select \"UseVat\" from \"TypePay\" WHERE \"TypeID\"='$k_dueno' ");
        if($res_cc3=pg_fetch_array($qry_cc3)){
            $UseVat = $res_cc3['UseVat'];
            if($UseVat == "t"){
                $qry_cc1=pg_query("select \"VatValue\" from \"FVat\" WHERE \"IDNO\"='$k_idno' AND \"V_DueNo\"='$k_dueno' ");
                if($res_cc1=pg_fetch_array($qry_cc1)){
                    $vat = $res_cc1['VatValue'];
                }else{
                    $vat = 0;
                }
            }
        }
    }
    /*
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$reslog[IDNO]' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        $vat = $res_cc1['VatValue'];
    }
    */
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
	$_SESSION["ses_idno"]=$reslog["IDNO"];
?>
	<td align="center"><a onclick="javascript:popU('../post/frm_cal_cuspayment.php?menu=','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')" style="cursor:pointer;"><font color="#0000FF"><u><?php echo $reslog["IDNO"]?></u></font></a></td>
    <!--td align="center"><?php echo $reslog["IDNO"]; ?></td-->
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
	<input type="hidden" name="s_cmoney" id="s_cmoney" value="<?php echo ($reslog["R_Money"]+$vat)*$numr; ?>" />
    <input type="hidden" name="s_bank" id="s_bank" value="<?php echo $reslog["R_Bank"]; ?>" />
	<input type="hidden" name="s_ref_prndate" id="s_ref_prndate" value="<?php echo $reslog["R_Prndate"]; ?>" />
    <input type="hidden" name="s_ref_recdate" id="s_ref_prndate" value="<?php echo $reslog["R_Date"]; ?>" />
	<input type="hidden" name="s_ref_recid" id="s_ref_recid" value="<?php echo $reslog["R_Receipt"]; ?>" />
	<input type="hidden" name="s_paytype" id="s_paytype" value="<?php echo $reslog["PayType"]; ?>" />
    <input type="hidden" name="s_memo" id="s_memo" value="<?php echo $reslog["R_memo"]; ?>" />
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
        <td colspan="6"><input name="r_type" type="radio" value="c_money" <?php if(($last_R_memo == "TR-ACC" OR $last_R_memo == "Bill Payment") and $emplevel > 1){ echo "disabled"; } ?> />จ่ายเป็นเงินสด</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="6"><br></td>
    </tr>
<?php
}

if($showimagestatus == 1){
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="6" align="center"><img src="cancelreceipt.gif" border="0" width="329" height="30"></td>
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

 if($showmsgerror == 1){
     echo "<div style=\"margin:5px 5px 5px 5px ;text-align:center; font-weight:bold; color:#ff0000; font-size:14px\">- ข้อมูล Vat ผิดผลาด กรุณาติดต่อผู้ดูแลระบบ -</div>";
 }
 
        } //ปิด while
    } // ปิด else
}
elseif($resType=="N" || $resType=="K"){ //===================== จบ TYPE R ==========================//
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
      $qry_name=pg_query("select \"C_REGIS\", \"car_regis\", \"gas_number\", \"gas_name\", \"C_CARNUM\", \"full_name\"
						from \"VContact\" WHERE \"IDNO\"='$ids' ");
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

$qry_plog=pg_query("select \"O_RECEIPT\" from \"FOtherpay\" WHERE \"O_RECEIPT\" = '$recid' ");
$numr=pg_num_rows($qry_plog);

$qry_plog=pg_query("select \"IDNO\", \"O_DATE\", \"O_RECEIPT\", \"O_MONEY\", \"O_Type\", \"O_PRNDATE\", \"O_BANK\", \"PayType\", \"O_memo\"
					from \"FOtherpay\" WHERE \"O_RECEIPT\" = '$recid' LIMIT(1) ");
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
	<input type="hidden" name="s_cmoney" id="s_cmoney" value="<?php echo $res_ns["O_MONEY"]*$numr; ?>" />
    <input type="hidden" name="s_bank" id="s_bank" value="<?php echo $res_ns["O_BANK"]; ?>" />
	<input type="hidden" name="s_ref_prndate" id="s_ref_prndate" value="<?php echo $res_ns["O_PRNDATE"]; ?>" />
    <input type="hidden" name="s_ref_recid" id="s_ref_recid" value="<?php echo $res_ns["O_RECEIPT"]; ?>" />
	<input type="hidden" name="s_paytype" id="s_paytype" value="<?php echo $res_ns["PayType"]; ?>" />
	<input type="hidden" name="s_ref_recdate" id="s_ref_prndate" value="<?php echo $res_ns["O_DATE"]; ?>" />
    <input type="hidden" name="s_memo" id="s_memo" value="<?php echo $res_ns["O_memo"]; ?>" />

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
}
elseif($resType=="V"){
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
      $qry_name=pg_query("select \"C_REGIS\", \"car_regis\", \"gas_number\", \"gas_name\", \"C_CARNUM\", \"full_name\"
						from \"VContact\" WHERE \"IDNO\"='$ids' ");
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

$qry_plog=pg_query("select \"V_Receipt\" from \"FVat\" WHERE \"V_Receipt\" = '$recid' ");
$numr=pg_num_rows($qry_plog);

$qry_plog=pg_query("select \"IDNO\", \"V_DueNo\", \"V_Receipt\", \"V_Date\", \"VatValue\", \"Paid_Status\", \"Cancel\", \"IDNO\", \"V_PrnDate\", \"V_memo\"
					from \"FVat\" WHERE \"V_Receipt\" = '$recid' LIMIT(1) ");
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
	<input type="hidden" name="s_cmoney" id="s_cmoney" value="<?php echo $res_v["VatValue"]*$numr; ?>" />
    <input type="hidden" name="s_bank" id="s_bank" value="" />
	<input type="hidden" name="s_ref_prndate" id="s_ref_prndate" value="<?php echo $res_v["V_PrnDate"]; ?>" />
    <input type="hidden" name="s_ref_recid" id="s_ref_recid" value="<?php echo $res_v["V_Receipt"]; ?>" />                                   
	<input type="hidden" name="s_ref_recdate" id="s_ref_prndate" value="<?php echo $res_v["V_Date"]; ?>" />
	<input type="hidden" name="s_paytype" id="s_paytype" value="" />
    <input type="hidden" name="s_memo" id="s_memo" value="<?php echo $res_v["V_memo"]; ?>" />
<?php
if($show_select_type == 0){
?>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><br></td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><input name="r_type" type="radio" value="s_money" disabled />เข้าเงินรับฝาก</td>
    </tr>
    <tr bgcolor="#FFFFFF">
        <td colspan="7"><input name="r_type" type="radio" value="c_money" checked="checked" />จ่ายเป็นเงินสด</td>
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

</body>
</html>