<?php
session_start();

$userid = $_SESSION["av_iduser"];

include("../config/config.php");
 
$nowdate=date("Y-m-d");
$fullname=pg_escape_string($_POST["fullname"]);  
$sta=pg_escape_string($_POST["s_sta_rec"]);
$cs_idno=pg_escape_string($_POST["s_idno"]);
$cs_recid=pg_escape_string($_POST["s_recid"]);
$cs_cdate=pg_escape_string($_POST["s_cdate"]);
$cs_money=pg_escape_string($_POST["s_cmoney"]);
$cs_r_prndate=pg_escape_string($_POST["s_ref_prndate"]);
$cs_r_recid=pg_escape_string($_POST["s_ref_recid"]);
$cs_r_recdate=pg_escape_string($_POST["s_ref_recdate"]);
$cs_paytype=pg_escape_string($_POST["s_paytype"]);
$cs_memo=pg_escape_string($_POST["cc_memo"]);

pg_query("BEGIN WORK");

$qry_c_no=pg_query("select gen_c_no('$nowdate')");
$res_cno=pg_fetch_result($qry_c_no,0);

$rtype=pg_escape_string($_POST["r_type"]);
$dtlca=$res_cno;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<fieldset><legend><B>ยกเลิกใบเสร็จ</B></legend>
<div align="center">  
<?php
$status = 0;

if($rtype=="s_money"){ //เข้าเงินรับฝาก
    $result=pg_query("insert into \"FOtherpay\" (\"IDNO\",\"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"O_BANK\",\"O_PRNDATE\",\"PayType\") values ('$cs_idno','$cs_cdate','$res_cno','$cs_money','000','','$cs_r_prndate','$cs_paytype')");                         
    if(!$result){
        $status+=1;
    }
    $fill_return="";
}else{
    $fill_return=$dtlca;
}

$result=pg_query("insert into \"CancelReceipt\" (c_receipt,\"IDNO\",c_date,c_money,ref_prndate,ref_recdate,ref_receipt,paytypefrom,c_memo,return_to,postuser) values ('$res_cno','$cs_idno','$cs_cdate','$cs_money','$cs_r_prndate','$cs_r_recdate','$cs_r_recid','$cs_paytype','$cs_memo','$fill_return','$userid')");                       
if(!$result){
    $status+=1;
}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
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
 
<div align="center"><input name="button" type="button" onclick="window.location='frm_cc_rec.php'" value=" ย้อนกลับ " /></div> 

</body>
</html>