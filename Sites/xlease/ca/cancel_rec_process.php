<?php
include("../config/config.php");

$userid = $_SESSION["av_iduser"];
 
$nowdate=date("Y-m-d");
$fullname=pg_escape_string($_POST["fullname"]);  
$cs_type=pg_escape_string($_POST["s_sta_rec"]); //ประเภท R N V
$cs_idno=pg_escape_string($_POST["s_idno"]);
$cs_recid=pg_escape_string($_POST["s_recid"]);
$cs_cdate=pg_escape_string($_POST["s_cdate"]);
$cs_money=pg_escape_string($_POST["s_cmoney"]);
$cs_bank=pg_escape_string($_POST["s_bank"]);
$cs_r_prndate=pg_escape_string($_POST["s_ref_prndate"]); //
$cs_r_recid=pg_escape_string($_POST["s_ref_recid"]);
$cs_r_recdate=pg_escape_string($_POST["s_ref_recdate"]);
$cs_paytype=pg_escape_string($_POST["s_paytype"]); //
$cs_old_memo=pg_escape_string($_POST["s_memo"]);
$cs_memo=pg_escape_string($_POST["cc_memo"]);
$rtype=pg_escape_string($_POST["r_type"]);


$datelog=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
pg_query("BEGIN WORK");

$status = 0;
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

<div class="wrapper">

<fieldset><legend><B>ยกเลิกใบเสร็จ</B></legend>
<div align="center">  
<?php

$qry_c_no=pg_query("select gen_c_no('$nowdate')");
$res_cno=pg_fetch_result($qry_c_no,0);


//ตรวจสอบว่ามีการยกเลิกใบเสร็จใบสุดท้ายหรือไม่
$qry_chk=pg_query("select \"P_TOTAL\",\"P_SLBAK\" from \"Fr\"  a
inner join \"Fp\" b on a.\"R_DueNo\"=b.\"P_TOTAL\" and a.\"IDNO\"=b.\"IDNO\"
where \"R_Receipt\"='$cs_recid'");
$numchk=pg_num_rows($qry_chk);
if($numchk>0){  //ถ้ามากกว่า 0 แสดงว่ามีการยกเลิกงวดสุดท้ายในใบเสร็จนี้
	if($res_psl=pg_fetch_array($qry_chk)){
		$p_sl=$res_psl["P_SLBAK"];
	}
	$cs_money=$cs_money-$p_sl;
}
if($rtype=="c_money"){ // จ่ายเป็นเงินสด
    $qry = "insert into \"CancelReceipt\" (c_receipt,\"IDNO\",c_date,c_money,ref_prndate,ref_recdate,ref_receipt,paytypefrom,return_to,c_memo,postuser) 
    values ('$res_cno','$cs_idno','$cs_cdate','$cs_money','$cs_r_prndate','$cs_r_recdate','$cs_r_recid','$cs_paytype','$res_cno','$cs_memo','$userid')";
    $result=pg_query($qry);
    if(!$result){
        $status++;
    }
}else{ // เข้าเงินรับฝาก
    $qry = "insert into \"CancelReceipt\" (c_receipt,\"IDNO\",c_date,c_money,ref_prndate,ref_recdate,ref_receipt,paytypefrom,return_to,c_memo,postuser) 
    values ('$res_cno','$cs_idno','$cs_cdate','$cs_money','$cs_r_prndate','$cs_r_recdate','$cs_r_recid','$cs_paytype','','$cs_memo','$userid')";
    $result=pg_query($qry);
    if(!$result){
        $status++;
    }
}

if($status == 0){
    
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$userid', '(TAL) ขอยกเลิกใบเสร็จ', '$datelog')");
	//ACTIONLOG---
	pg_query("COMMIT");	
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br />$qry";
}

if($rtype=="c_money" && $status == 0){
?>
<hr>

<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" bgcolor="#FFFFDD">
    <tr>
        <td align="center" colspan=2><u><b>รายละเอียดการจ่ายเป็นเงินสด<b></u></td>
    </tr>
    <tr><td colspan=2><br></td></tr>
    <tr>
        <td align="left" colspan=2><b>จ่ายให้ : </b> <?php echo $fullname; ?></td>
    </tr>
    <tr>
        <td align="left"><b>รหัสยกเลิกใบเสร็จ : </b> <?php echo $res_cno; ?></td>
        <td align="left"><b>เลขที่สัญญา : </b> <?php echo $cs_idno; ?></td>
    </tr>
    <tr>
        <td align="left"><b>Ref_Printdate : </b> <?php echo $cs_r_prndate; ?></td>
        <td align="left"><b>วันที่ยกเลิก : </b> <?php echo $cs_cdate; ?></td>
    </tr>
    <tr>
        <td align="left"><b>Ref_RecDate : </b> <?php echo $cs_r_recdate; ?></td>
        <td align="left"><b>จำนวนเงิน : </b> <?php echo number_format($cs_money,2); ?> บาท.</td>
     </tr>
    <tr>
        <td align="left"><b>Ref_Receipt : </b> <?php echo $cs_r_recid; ?></td>
        <td align="left"><b>PayType : </b> <?php echo $cs_paytype; ?></td>
    </tr>
    <tr><td colspan=2><br></td></tr>
    <tr>
        <td align="left" ><b>*หมายเหตุ</b></td>
        <td align="left"><input name="button" type="button" onclick="window.location='frm_cc_rec_print.php?id=<?php echo $res_cno; ?>'" value="พิมพ์รายงาน" /></td>
    </tr>
    <tr bgcolor="#FFFFF0">
        <td align="left" colspan=2>
        <?php 
        $cs_memo= str_replace("\n", "<br>\n", "$cs_memo"); 
        echo $cs_memo;
        ?>
        </td>
    </tr>
</table>
  
<?php    
}
?>
</div>
</fieldset> 

</div>
        </td>
    </tr>
</table>         
 
<div align="center"><input name="button" type="button" onclick="window.location='frm_cancel.php'" value=" ย้อนกลับ " /></div> 

</body>
</html>