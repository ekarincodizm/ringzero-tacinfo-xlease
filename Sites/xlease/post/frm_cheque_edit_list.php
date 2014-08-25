<?php
session_start();
include("../config/config.php");  

$idno = $_POST["idno_names"];

$qry_vcon=pg_query("select * from \"VContact\" WHERE  \"IDNO\"='$idno'");
if($re_vcon=pg_fetch_array($qry_vcon)){
      $full_name = $re_vcon["full_name"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td> 

<div class="wrapper">

<div style="float:left"><input name="button" type="button" onclick="window.location='frm_cheque_edit_select.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
<div style="clear:both"></div>

<fieldset><legend><B>แก้ไขรายการเช็ค</B></legend>

<div align="left" style="font-weight:bold; margin-top:5px; margin-bottom:10px"><?php echo $full_name; ?> | IDNO <?php echo $idno; ?></div>

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>เลขที่เช็ค</td>
        <td>ธนาคาร</td>
        <td>สาขา</td>
        <td>วันที่บนเช็ค</td>
        <td>ประเภท</td>
        <td>ยอดเงิน</td>
        <td>วันที่นำเช็คเข้า Bank</td>
        <td>สถานะ</td>
    </tr>
<?php
$sss_chq = "";
$stat = 0;
$qry_vcus=pg_query("select * from \"VDetailCheque\" WHERE  \"IDNO\"='$idno' order by \"DateOnCheque\",\"ChequeNo\" ");
$rows = pg_num_rows($qry_vcus);
if($rows > 0){
while($resvc=pg_fetch_array($qry_vcus)) { 
        
        $qry_name=pg_query("select \"TName\" from \"TypePay\" WHERE  \"TypeID\"='$resvc[TypePay]' ");
        $resname=pg_fetch_array($qry_name);
        
        $qry_return=pg_query("select \"IsReturn\",\"Accept\" from \"FCheque\" WHERE  \"ChequeNo\"='$resvc[ChequeNo]' AND \"PostID\"='$resvc[PostID]' ");
        $res_return=pg_fetch_array($qry_return);
        
        if($res_return["Accept"] == 't'){
        
        if($res_return["IsReturn"] == 't'){
            $show_ispass = "ส่งคืน";
        }else{
            if($resvc["IsPass"] == 't'){ $show_ispass = "ผ่าน"; }
            else{ $show_ispass = "ไม่ผ่าน"; }
        }
        
        if($sss_chq != $resvc["ChequeNo"]){
            if($stat == 0){
                echo "<tr class=\"odd\">";
                $stat = 1; 
            }else{
                echo "<tr class=\"even\">";
                $stat = 0;
            }
        }else{
             if($stat == 0){
                echo "<tr class=\"even\">";
                $stat = 0; 
            }else{
                echo "<tr class=\"odd\">";
                $stat = 1;
            }
        }
        $sss_chq = $resvc["ChequeNo"];
        
        $op +=1;
?>     
        <td>
<?php
if($show_ispass == "ไม่ผ่าน"){
?>
    <a href="frm_cheque_edit_detail.php?id=<?php echo $resvc["PostID"]; ?>&cid=<?php echo $resvc["ChequeNo"]; ?>"><u><?php echo $resvc["ChequeNo"]; ?></u></a>
<?php 
}else{
    echo $resvc["ChequeNo"];
}
?>
        </td>
        <td><?php echo $resvc["BankName"]; ?></td>
        <td><?php echo $resvc["BankBranch"]; ?></td>
        <td align="center"><?php echo $resvc["DateOnCheque"]; ?></td>
        <td align="left"><?php echo $resname["TName"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CusAmount"],2); ?></td>
        <td align="center"><?php echo $resvc["DateEnterBank"]; ?></td>
        <td><?php echo $show_ispass; ?></td>
    </tr>
        
<?php
    }
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

</div>
        </td>
    </tr>
</table>

</body>
</html>