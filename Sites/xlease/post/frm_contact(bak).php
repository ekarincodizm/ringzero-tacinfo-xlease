<?php
session_start();
include("../config/config.php");

//$arr_idno = $_SESSION["arr_idno"];

//$cusid = $_GET["cusid"];
$idno = $_GET["idno"];

$search_top = $idno;
do{
    $qry_top=pg_query("select * from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_top=pg_fetch_array($qry_top);
    $CusID=$res_top["CusID"];
    $arr_idno[$res_top["IDNO"]]=$CusID;
    $search_top=$res_top["IDNO"];
}while(!empty($search_top));

$qry_top=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;

if(!empty($P_TransferIDNO)){
    do{
        $qry_fp2=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$P_TransferIDNO'");
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
    padding-bottom: 3px;
    text-align: right;
}
</style>

</head>

<body>

<div class="title_top">ที่ติดต่อลูกค้า</div>

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

$qry_vcus=pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"CusID\"='$cusid'");
if($resvc=pg_fetch_array($qry_vcus)){
        $N_ContactAdd = $resvc["N_ContactAdd"];
            $N_ContactAdd= str_replace("\n", "<br>\n", "$N_ContactAdd"); 
}

$qry_vcus2=pg_query("select * from \"VContact\" WHERE  \"CusID\"='$cusid' AND \"IDNO\"='$idno' ");
if($resvc2=pg_fetch_array($qry_vcus2)){
        $IDNO = $resvc2["IDNO"];
        $full_name = $resvc2["full_name"];
        $asset_type = $resvc2["asset_type"];
        $C_REGIS = $resvc2["C_REGIS"];
        $car_regis = $resvc2["car_regis"];
            if($asset_type == 1) $showregis=$C_REGIS; else $showregis=$car_regis;
}
?>
<div id="tabs-<?php echo $idno; ?>">
<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="<?php echo $bgcolor; ?>"  align="center">
<tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="left" valign="middle">
    <td colspan=4>ข้อมูลลูกค้า</td>
</tr>
<tr>
    <td align="left" valign="top" width="20%"><b>IDNO : </b></td>
    <td align="left" valign="top" colspan=3><?php echo "$IDNO"; ?></td>
</tr>
<tr>
    <td align="left" valign="top"><b>ชื่อ/สกุล : </b></td>
    <td align="left" valign="top"><?php echo "$full_name"; ?></td>
    <td align="left" valign="top" width="15%"><b>ทะเบียนรถ : </b></td>
    <td align="left" valign="top"><?php echo "$showregis"; ?></td>
</tr>
<tr>
    <td align="left" valign="top"><b>ที่ติดต่อลูกค้า : </b></td>
    <td align="left" valign="top" colspan=3><?php echo "$N_ContactAdd"; ?></td>
</tr>
</table>
</div>
<?php } ?>

</div><!-- จบ เริ่ม tabs -->


</body>
</html>