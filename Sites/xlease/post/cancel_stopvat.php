<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");
//$nowdate = Date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function confirm1(Url,name){
  if (confirm("ยืนยันการยกเลิกหยุด VAT")){
    popU(Url,name,'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300');
  }
}
</script>


    </head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
<div class="wrapper">

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายการ Stop VAT</td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">StopVAT Date</td>
        <td align="center">เงินรับฝาก</td>
        <td align="center">Cancel</td>
    </tr>

<?php
$qry_fr=pg_query("select * from \"Fp\" WHERE \"P_StopVat\"='true' AND \"P_ACCLOSE\"='false' ORDER BY \"IDNO\" ASC");
$numrow = pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){
    $IDNO = $res_fr["IDNO"];
    $P_StopVatDate = $res_fr["P_StopVatDate"];
  
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $asset_type = $res_vc["asset_type"];
        $dp_balance = $res_vc["dp_balance"]; if(empty($dp_balance)) $dp_balance = 0;
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }
    
    $newFileName = md5(uniqid(rand().time(), true));
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo $IDNO; ?></td>
        <td align="left"><?php echo $full_name; ?></td>
        <td align="left"><?php echo $show_regis; ?></td>
        <td align="center"><?php echo $P_StopVatDate; ?></td>
        <td align="right"><?php echo number_format($dp_balance,2); ?></td>
        <td align="center"><a href="#" onclick="javascript:confirm1('cancel_stopvat_add.php?idno=<?php echo $IDNO; ?>&bl=<?php echo $dp_balance; ?>','<?php echo $newFileName; ?>');">ยกเลิก StopVat</a></td>
    </tr>
<?php
}
?>

<?php 
if($numrow > 0){
?>
    <tr>
        <td align="left" colspan="3">ทั้งหมด <?php echo $numrow; ?> รายการ</td>
        <td colspan="3" align="right"><a href="notice_pdf.php" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
    </tr>
<?php
}
?>
<?php 
if($numrow == 0){   
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