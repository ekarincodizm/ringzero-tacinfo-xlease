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
        $qry_fp2=pg_query("select \"CusID\",\"P_TransferIDNO\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$P_TransferIDNO'");
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
</style>

</head>

<body>

<div class="title_top">รายละเอียดเช็ค</div>

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
        $bgcolor = "#FFFFFF";
    }
    //จบ กำหนดสี
    
    
$qry_vcon=pg_query("select \"full_name\" from \"VContact\" WHERE  \"IDNO\"='$idno'");
if($re_vcon=pg_fetch_array($qry_vcon)){
      $full_name = $re_vcon["full_name"];
}
?>

<div id="tabs-<?php echo $idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">
<div align="right" style="font-weight:bold; padding-top:5px; padding-bottom:5px;"><?php echo $full_name; ?> | IDNO <?php echo $idno; ?></div>

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
$qry_vcus=pg_query("select \"TypePay\",\"ChequeNo\",\"PostID\",\"IsPass\",\"BankName\",\"BankBranch\",
\"DateOnCheque\",\"CusAmount\",\"DateEnterBank\"
 from \"VDetailCheque\" WHERE  \"IDNO\"='$idno' order by \"DateOnCheque\",\"ChequeNo\" ");
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
        <td><?php echo $resvc["ChequeNo"]; ?></td>
        <td><?php echo $resvc["BankName"]; ?></td>
        <td><?php echo $resvc["BankBranch"]; ?></td>
        <td><?php echo $resvc["DateOnCheque"]; ?></td>
        <td align="left"><?php echo $resname["TName"]; ?></td>
        <td align="right"><?php echo number_format($resvc["CusAmount"],2); ?></td>
        <td><?php echo $resvc["DateEnterBank"]; ?></td>
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

</div>

</div>

<?php
}
?>

</div>

</body>
</html>