<?php
include("../config/config.php");

$userid = $_SESSION["av_iduser"];
$nowdate=date("Y-m-d");

$cs_memo=pg_escape_string($_POST["cc_memo"]);
$check_postid=pg_escape_string($_POST["check_postid"]);
$fullname=pg_escape_string($_POST["fullname"]);

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

<fieldset><legend><B>ยกเลิกใบเสร็จ - เงินโอนที่ออกผิดเลขที่สัญญา</B></legend>
<div align="center">  
<?php
$qry_dttp2=pg_query("select \"ReceiptNo\" from \"DetailTranpay\" WHERE \"PostID\"='$check_postid' ");
while( $res_dttp2=pg_fetch_array($qry_dttp2) ){
    $ReceiptNo = $res_dttp2["ReceiptNo"];
    $subtype=substr($ReceiptNo,2,1);
    
    if($subtype == "R"){
        $qry_plog=pg_query("select * from \"Fr\" WHERE \"R_Receipt\" = '$ReceiptNo' ");
        if($reslog=pg_fetch_array($qry_plog)){
            $cs_idno=$reslog["IDNO"];
            $cs_money=$reslog["R_Money"];
            $cs_r_prndate=$reslog["R_Prndate"];
            $cs_r_recdate=$reslog["R_Date"];
            $cs_paytype=$reslog["PayType"];
            
            $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$cs_idno' LIMIT(1)");
            if($res_cc1=pg_fetch_array($qry_cc1)){
                $vat = $res_cc1['VatValue'];
            }
            
            $cs_money += $vat;
        }
    }elseif($subtype == "N" || $subtype == "K"){
        $qry_plog=pg_query("select * from \"FOtherpay\" WHERE \"O_RECEIPT\" = '$ReceiptNo' ");
        if($reslog=pg_fetch_array($qry_plog)){
            $cs_idno=$reslog["IDNO"];
            $cs_money=$reslog["O_MONEY"];
            $cs_r_prndate=$reslog["O_PRNDATE"];
            $cs_r_recdate=$reslog["O_DATE"];
            $cs_paytype=$reslog["PayType"];
        }
    }elseif($subtype == "V"){
        /*
        $qry_plog=pg_query("select * from \"FVat\" WHERE \"V_Receipt\" = '$ReceiptNo' ");
        if($reslog=pg_fetch_array($qry_plog)){
            $cs_idno=$reslog["IDNO"];
            $cs_money=$reslog["VatValue"];
            $cs_r_prndate=$reslog["V_PrnDate"];
            $cs_r_recdate=$reslog["V_Date"];
            $cs_paytype="";
        }
        */
    }

    $qry_c_no=pg_query("select gen_c_no('$nowdate')");
    $res_cno=pg_fetch_result($qry_c_no,0);
    
    $qry = "insert into \"CancelReceipt\" (c_receipt,\"IDNO\",c_date,c_money,ref_prndate,ref_recdate,ref_receipt,paytypefrom,return_to,c_memo,postuser) values ('$res_cno','$cs_idno','$nowdate','$cs_money','$cs_r_prndate','$cs_r_recdate','$ReceiptNo','$cs_paytype','$res_cno','@#$check_postid#$cs_memo','$userid')";
    $result=pg_query($qry);
    if(!$result){
        $status++;
    }
}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br />$qry";
}

if($status == 0){
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