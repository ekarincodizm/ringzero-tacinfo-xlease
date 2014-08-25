<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");
if(isset($_POST['select_date'])){
    $cdate = pg_escape_string($_POST['select_date']);
}else{
    $cdate = nowDate();//ดึง วันที่จาก server
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION["session_company_name"]; ?></h1></div>
<div class="wrapper">

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน รับเช็คประจำวัน 

<div align="center">
<form name="frm_app_cc" method="post" action="">
<b>เลือกวันที่</b>
<input name="select_date" type="text" readonly="true" value="<?php echo $cdate; ?>"/>
<input name="button2" type="button" onclick="displayCalendar(document.frm_app_cc.select_date,'yyyy-mm-dd',this)" value="ปฏิทิน" /><input type="submit" value="ค้นหา" />
</form>
</div>
        </td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ลำดับที่</td>
        <td align="center">เลขที่เช็ค</td>
        <td align="center">Bank</td>
        <td align="center">สาขา</td>
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ค่า</td>
        <td align="center">ยอดเงิน</td>
        <td></td>
    </tr>

<?php
$qry_fr=pg_query("select * from \"VDetailCheque\" WHERE \"ReceiptDate\"='$cdate' AND \"Accept\"='true' ORDER BY \"PostID\" ");
$num=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){
    $nub+=1;
    $ChequeNo = $res_fr["ChequeNo"];
    $BankName = $res_fr["BankName"];
    $BankBranch = $res_fr["BankBranch"];
    $IDNO = $res_fr["IDNO"];
    $TypePay = $res_fr["TypePay"];
    $CusAmount = $res_fr["CusAmount"]; $CusAmount = round($CusAmount,2);
    $sum_CusAmount += $CusAmount;
    
    $qry_vc=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $TName = $res_vc["TName"];
    }
    
    if($nub!=1){
        if($old_id != $ChequeNo){
    
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo $tmp_s_lum; ?></td>
        <td align="center"><?php echo $tmp_ChequeNo; ?></td>
        <td align="center"><?php echo $tmp_BankName; ?></td>
        <td align="left"><?php echo $tmp_BankBranch; ?></td>
        <td align="center"><?php echo $tmp_IDNO; ?></td>
        <td align="center"><?php echo $tmp_TName; ?></td>
        <td align="right"><?php echo number_format($tmp_CusAmount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($tmp_sum_rows,2); ?></td>
    </tr>
<?php
        }else{
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo $tmp_s_lum; ?></td>
        <td align="center"><?php echo $tmp_ChequeNo; ?></td>
        <td align="center"><?php echo $tmp_BankName; ?></td>
        <td align="left"><?php echo $tmp_BankBranch; ?></td>
        <td align="center"><?php echo $tmp_IDNO; ?></td>
        <td align="center"><?php echo $tmp_TName; ?></td>
        <td align="right"><?php echo number_format($tmp_CusAmount,2); ?></td>
        <td align="right"></td>
    </tr>
<?php            
        }
    }

        if($old_id != $ChequeNo){
            $lum += 1;
            $s_lum = $lum;
            $old_id = $ChequeNo;
            
            if($nub == 1){
                $sum_rows += $CusAmount;
            }else{
                $sum_rows = 0;
                $sum_rows += $CusAmount;
            }
        }else{
            $s_lum = "";
            $old_id = $old_id;
            $sum_rows += $CusAmount;
        }
    
    $tmp_s_lum = $s_lum;
    $tmp_ChequeNo = $ChequeNo;
    $tmp_BankName = $BankName;
    $tmp_BankBranch = $BankBranch;
    $tmp_IDNO = $IDNO;
    $tmp_TName = $TName;
    $tmp_CusAmount = $CusAmount;
    $tmp_sum_rows = $sum_rows;
}
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo $tmp_s_lum; ?></td>
        <td align="center"><?php echo $tmp_ChequeNo; ?></td>
        <td align="center"><?php echo $tmp_BankName; ?></td>
        <td align="left"><?php echo $tmp_BankBranch; ?></td>
        <td align="center"><?php echo $tmp_IDNO; ?></td>
        <td align="center"><?php echo $tmp_TName; ?></td>
        <td align="right"><?php echo number_format($tmp_CusAmount,2); ?></td>
        <td align="right" style="font-weight:bold;"><?php echo number_format($tmp_sum_rows,2); ?></td>
    </tr>

<?php 
if($num > 0){
?>
    <tr style="font-weight:bold;">
        <td colspan="4">ทั้งหมด <?php echo $lum; ?> รายการ</td>
        <td colspan="4" align="right">ผลรวม <?php echo number_format($sum_CusAmount,2); ?></td>
    </tr>
    <tr bgcolor="#ffffff"><td colspan="8" align="right"><a href="cheq_day_pdf.php?d=<?php echo $cdate; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td></tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="10" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<div align="center"><br><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

        </td>
    </tr>
</table>

</body>
</html>