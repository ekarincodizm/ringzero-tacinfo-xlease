<?php
session_start();
include("../config/config.php");  

//$arr_idno = $_SESSION["arr_idno"];

$idno = pg_escape_string($_GET["idno"]);

$search_top = $idno;
do{
    $qry_top=pg_query("select \"CusID\",\"IDNO\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_top=pg_fetch_array($qry_top);
    $CusID=$res_top["CusID"];
    $arr_idno[$res_top["IDNO"]]=$CusID;
    $search_top=$res_top["IDNO"];
}while(!empty($search_top));

$qry_top=pg_query("select \"CusID\",\"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;

if(!empty($P_TransferIDNO)){
    do{
        $qry_fp2=pg_query("select A.\"CusID\",\"P_TransferIDNO\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$P_TransferIDNO'");
        $res_fp2=pg_fetch_array($qry_fp2);
        $CusID=$res_fp2["CusID"];
        $arr_idno[$P_TransferIDNO]=$CusID;
        $P_TransferIDNO=$res_fp2["P_TransferIDNO"];
    }while(!empty($P_TransferIDNO));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
});
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>


<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
.odd{
    background-color:#EDF8FE;
    font-size:11px
}
.even{
    background-color:#D5EFFD;
    font-size:11px
}
.red{
    background-color:#FFD9EC;
    font-size:11px
}
</style>

</head>

<body>

<div class="title_top">รายการชำระค่าอื่นๆ</div>

<div id="tabs"> <!-- เริ่ม tabs -->
<ul>
<?php
//สร้าง list รายการ โอนสิทธิ์
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    echo "<li><a href=\"#tabs-$i\">$i</a></li>";
}
?>
</ul>

<?php
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    
    $cusid = $v;
    $idno = $i;
    
    //กำหนดสี ให้กับข้อมูลล่าสุด
    if($_SESSION["ses_idno"] == $idno){
        $bgcolor = "#FFFFFF";
    }else{
        $bgcolor = "#FFFFFF"; // FFD2D2
    }
    //จบ กำหนดสี

$qry_vcon=pg_query("select \"full_name\" from \"VContact\" WHERE  \"IDNO\"='$idno'");
if($re_vcon=pg_fetch_array($qry_vcon)){
     $full_name = $re_vcon["full_name"];
}
?>

<div id="tabs-<?php echo $idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">
<div align="right">
	<form method="post" name="frmprint" action="frm_print_otherpay.php">
		<input type="hidden" name="idno" value="<?php echo $idno; ?>">
		<input type="submit" value="พิมพ์">
	</form>
</div>
<div align="right" style="font-weight:bold; padding-top:3px; padding-bottom:3px;"><?php echo $full_name; ?> | IDNO <?php echo $idno; ?></div>

<fieldset><legend><b>รายการชำระค่าอื่นๆ</b></legend>

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>วันที่ชำระ</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>รหัส</td>
        <td>รายการ</td>
        <td>PayType</td>
        <td>เลขที่อ้างถึง</td>
        <td>ยอดเงิน</td>
		<td>คำอธิบายในระบบเก่า</td>
    </tr>
<?php

$qry_vcus=pg_query("select \"O_Type\",\"O_DATE\",\"O_RECEIPT\",\"O_BANK\",\"PayType\",
\"RefAnyID\",\"O_MONEY\",\"O_memo\"
 from \"FOtherpay\" WHERE  \"IDNO\"='$idno' AND \"Cancel\"='false' ORDER BY \"O_DATE\",\"O_RECEIPT\" ASC");
$rows = pg_num_rows($qry_vcus);
if($rows > 0){
while($resvc=pg_fetch_array($qry_vcus)) {
        
        $qry_name=pg_query("select \"TName\" from \"TypePay\" WHERE  \"TypeID\"='$resvc[O_Type]' ");
        $resname=pg_fetch_array($qry_name);
        
        if($resvc["O_Type"] == "200" || $resvc["O_Type"] == "299"){
            echo "<tr class=\"red\">";
        }else{
        
            $i+=1;
            if($i%2==0){
                echo "<tr class=\"odd\">";
            }else{
                echo "<tr class=\"even\">";
            }
        
        }
?>     
        <td><?php echo $resvc["O_DATE"]; ?></td>
        <td><?php echo $resvc["O_RECEIPT"]; ?></td>
        <td><?php echo $resvc["O_Type"]; ?></td>
        <td align="left"><?php echo $resname["TName"]; ?></td>
        <td>
        <?php 
        if(empty($resvc['O_BANK']) && empty($resvc['PayType'])){
            
        }else{
            echo "$resvc[O_BANK] / $resvc[PayType]";
        }
        ?>
        </td>
        <td><?php echo $resvc["RefAnyID"]; ?></td>
        <td align="right"><?php echo number_format($resvc["O_MONEY"],2); ?></td>
		<td><?php echo $resvc["O_memo"]; ?></td>
    </tr>
        
<?php
    }
}else{
?>
    <tr>
        <td align="center" colspan="10">ไม่พบข้อมูล</td>
    </tr>
<?php
}
?>
</table>

</fieldset>

<br>

<fieldset><legend><b>รายการที่มี VAT</b></legend>

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>วันที่ชำระ</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>รหัส</td>
        <td>รายการ</td>
        <td>PayType</td>
        <td>มูลค่า</td>
        <td>VAT</td>
        <td>รวม</td>
    </tr>
<?php

$qry_vcus=pg_query("select * from \"VFrNotPaymentButUseVat\" WHERE  \"IDNO\"='$idno' ORDER BY \"R_Date\",\"R_Receipt\" ASC");
$rows = pg_num_rows($qry_vcus);
while($resvc=pg_fetch_array($qry_vcus)) {
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>     
        <td><?php echo $resvc["R_Date"]; ?></td>
        <td><?php echo $resvc["R_Receipt"]."/".$resvc["V_Receipt"]; ?></td>
        <td><?php echo $resvc["R_DueNo"]; ?></td>
        <td align="left"><?php echo $resvc["typepay_name"]; ?></td>
        <td align="center">
        <?php 
        if(empty($resvc['R_Bank']) && empty($resvc['PayType'])){
            
        }else{
            echo "$resvc[R_Bank] / $resvc[PayType]";
        }
        ?>
        </td>
        <td align="right"><?php echo number_format($resvc["value"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["vat"],2); ?></td>
        <td align="right"><?php echo number_format($resvc["money"],2); ?></td>
    </tr>
        
<?php
}
if($rows == 0){
?>
    <tr>
        <td align="center" colspan="10">ไม่พบข้อมูล</td>
    </tr>
<?php
}
?>
</table>

</fieldset>

</div>
</div>

<?php
}
?>

</body>
</html>