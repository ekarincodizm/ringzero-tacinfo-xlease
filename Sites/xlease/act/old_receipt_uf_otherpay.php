<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);
$id = pg_escape_string($_GET['id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"><input type="button" value="  กลับ  " class="ui-button" onclick="window.location='old_receipt_uf.php'"></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ตัดใบเสร็จประกันเก่า - สมัครใจ</B></legend>

<div align="center">

<div class="ui-widget">

<div style="text-align:left; font-size:15px; font-weight:bold; color:#585858">ID: <?php echo $id; ?></div>

<form name="frm1" id="frm1" action="old_receipt_uf_otherpay_update.php" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>Select</td>
    <td>O_DATE</td>
    <td>O_RECEIPT</td>
    <td>O_Type</td>
    <td>PayType</td>
    <td>O_MONEY</td>
</tr>
<?php

$query=pg_query("select * from \"FOtherpay\" WHERE \"IDNO\"='$idno' AND \"O_Type\"='102' ORDER BY \"O_DATE\" ASC");
while($resvc=pg_fetch_array($query)){
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $O_MONEY = $resvc['O_MONEY'];
    $O_Type = $resvc['O_Type'];
    $PayType = $resvc['PayType'];
    
    
    $query_name=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$O_Type'");
    if($res_name=pg_fetch_array($query_name)){
        $TName = $res_name['TName'];
    }
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
    <td align="center"><input type="radio" name="chk" id="chk" value="<?php echo $O_RECEIPT; ?>"></td>
    <td><?php echo $O_DATE; ?></td>
    <td><?php echo $O_RECEIPT; ?></td>
    <td><?php echo $TName; ?></td>
    <td><?php echo $PayType; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
</tr>
<?php
}
?>
</table>

<div align="center" style="padding-top:10px; padding-bottom:10px;"><input type="submit" name="btn1" id="btn1" value=" บันทึก " class="ui-button"></div>

</form>
</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>