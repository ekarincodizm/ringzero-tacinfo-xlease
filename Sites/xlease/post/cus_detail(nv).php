<?php
session_start();
include("../config/config.php");
//$arr_idno = $_SESSION["arr_idno"];
$g_idno = $_GET['idno'];
$g_cusid = $_GET['scusid'];

$search_top = $g_idno;
do{
    $qry_top=pg_query("select * from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_top=pg_fetch_array($qry_top);
    $CusID=$res_top["CusID"];
    $arr_idno[$res_top["IDNO"]]=$CusID;
    $search_top=$res_top["IDNO"];
}while(!empty($search_top));

$qry_top=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$g_idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$g_idno]=$CusID;

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
    <link type="text/css" rel="stylesheet" href="act.css"></link>
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

<div class="title_top">ข้อมูลลูกค้า</div>

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
    
    $g_cusid = $v;
    $g_idno = $i;
    
    //กำหนดสี ให้กับข้อมูลล่าสุด
    if($_SESSION["ses_idno"] == $g_idno){
        $bgcolor = "#FFFFFF";
    }else{
        $bgcolor = "#FFFFFF";
    }
    //จบ กำหนดสี
    


$qry_1=pg_query("select * from \"Fp\" WHERE (\"IDNO\"='$g_idno') AND (\"CusID\"='$g_cusid');");
if($res_1=pg_fetch_array($qry_1)){
    $asset_id = $res_1['asset_id'];
    $asset_type = $res_1['asset_type'];
    $TranIDRef1 = $res_1['TranIDRef1'];
    $TranIDRef2 = $res_1['TranIDRef2'];
    $P_TOTAL = $res_1['P_TOTAL'];
    $P_MONTH = $res_1['P_MONTH'];
    $P_VAT = $res_1['P_VAT'];
    $P_BEGIN = $res_1['P_BEGIN'];
    $P_BEGINX = $res_1['P_BEGINX'];
    $P_STDATE = $res_1['P_STDATE'];
    $P_FDATE = $res_1['P_FDATE'];
    $P_StopVatDate = $res_1['P_StopVatDate']; if(empty($P_StopVatDate)) $P_StopVatDate = "-";
    $P_CLDATE = $res_1['P_CLDATE']; if(empty($P_CLDATE)) $P_CLDATE = "-";
}

if($asset_type == 1){
    $qry_2=pg_query("select * from \"VCarregistemp\" WHERE (\"IDNO\"='$g_idno');");
    if($res_2=pg_fetch_array($qry_2)){
        $C_CARNAME = $res_2['C_CARNAME'];
        $C_YEAR = $res_2['C_YEAR'];
        $C_COLOR = $res_2['C_COLOR'];
        $C_REGIS = $res_2['C_REGIS'];
        $C_CARNUM = $res_2['C_CARNUM'];
        $C_MARNUM = $res_2['C_MARNUM'];
        $C_TAX_ExpDate = $res_2['C_TAX_ExpDate'];
    }
}else{
    $qry_2=pg_query("select * from \"FGas\" WHERE (\"GasID\"='$asset_id');");
    if($res_2=pg_fetch_array($qry_2)){
        $gas_name = $res_2['gas_name'];
        $gas_number = $res_2['gas_number'];
        $gas_type = $res_2['gas_type'];
        $car_regis = $res_2['car_regis'];
        $car_regis_by = $res_2['car_regis_by'];
        $car_year = $res_2['car_year'];
        $carnum = $res_2['carnum'];
        $marnum = $res_2['marnum'];
    }
}

$qry_3=pg_query("select * from \"ContactCus\" WHERE (\"IDNO\"='$g_idno') AND \"CusState\"='0';");
if($res_3=pg_fetch_array($qry_3)){
    $CusID = $res_3['CusID'];
    
    if(!empty($CusID)){
        $qry_4=pg_query("select * from \"Fa1\" WHERE (\"CusID\"='$CusID');");
        if($res_4=pg_fetch_array($qry_4)){
            $A_FIRNAME = $res_4['A_FIRNAME'];
            $A_NAME = $res_4['A_NAME'];
            $A_SIRNAME = $res_4['A_SIRNAME'];
            $A_NO = $res_4['A_NO'];
            $A_SUBNO = $res_4['A_SUBNO'];
            $A_SOI = $res_4['A_SOI'];
            $A_RD = $res_4['A_RD'];
            $A_TUM = $res_4['A_TUM'];
            $A_AUM = $res_4['A_AUM'];
            $A_PRO = $res_4['A_PRO'];
            $A_POST = $res_4['A_POST'];
        }
    }
    
}
    
?>
 
<div id="tabs-<?php echo $g_idno; ?>">

 <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
 <tr>
    <td>
 
<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#E8FFE8">
<tr align="left" bgcolor="#CEFFCE">
    <td><b><i>ผู้เช่า</i></b></td>
</tr>
<tr align="left">
    <td><i>ชื่อ/สกุล : </i><?php echo "$A_FIRNAME $A_NAME  $A_SIRNAME";?></td>
</tr>
<tr align="left">
    <td><i>ที่อยู่ : </i>
<?php
echo "$A_NO หมู่ที่.$A_SUBNO ซอย.$A_SOI ถนน.$A_RD ตำบล.$A_TUM อำเภอ.$A_AUM จังหวัด.$A_PRO รหัสไปรษณีย์.$A_POST";
?>
    </td>
</tr>
</table>

<br>

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#DFEFFF">

<?php
$qry_5=pg_query("select * from \"ContactCus\" WHERE (\"IDNO\"='$g_idno') AND \"CusState\">'0' ORDER BY \"CusState\" ASC;");
while($res_5=pg_fetch_array($qry_5)){
    $CusID = $res_5['CusID'];
    $CusState = $res_5['CusState'];
    
    if(!empty($CusID)){
        $qry_6=pg_query("select * from \"Fa1\" WHERE (\"CusID\"='$CusID');");
        if($res_6=pg_fetch_array($qry_6)){
            $A_FIRNAME = $res_6['A_FIRNAME'];
            $A_NAME = $res_6['A_NAME'];
            $A_SIRNAME = $res_6['A_SIRNAME'];
            $A_NO = $res_6['A_NO'];
            $A_SUBNO = $res_6['A_SUBNO'];
            $A_SOI = $res_6['A_SOI'];
            $A_RD = $res_6['A_RD'];
            $A_TUM = $res_6['A_TUM'];
            $A_AUM = $res_6['A_AUM'];
            $A_PRO = $res_6['A_PRO'];
            $A_POST = $res_6['A_POST'];
        }
    }
?>

<tr align="left" bgcolor="#C1E0FF">
    <td><b><i>ผู้ค้ำ # </i><?php echo $CusState; ?></b></td>
</tr>
<tr align="left">
    <td><i>ชื่อ/สกุล : </i><?php echo "$A_FIRNAME $A_NAME  $A_SIRNAME";?></td>
</tr>
<tr align="left">
    <td><i>ที่อยู่ : </i>
<?php
echo "$A_NO หมู่ที่.$A_SUBNO ซอย.$A_SOI ถนน.$A_RD ตำบล.$A_TUM อำเภอ.$A_AUM จังหวัด.$A_PRO รหัสไปรษณีย์.$A_POST";
?>
    </td>
</tr>

<?php
}
?>

</table>

<br>

<?php
if($asset_type == 1){
?>
<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#FFF0F0">
<tr align="left" bgcolor="#FFCACA">
    <td colspan="4"><b><i>ข้อมูลรถ</i></b></td>
</tr>
<tr align="left">
    <td width="25%"><i>ยี่ห้อ : </i><?php echo $C_CARNAME; ?></td>
    <td width="25%"></td>
    <td width="25%"><i>ปี : </i><?php echo $C_YEAR; ?></td>
    <td width="25%"><i>สี : </i><?php echo $C_COLOR; ?></td>
</tr>
<tr align="left">
    <td colspan="2"><i>ทะเบียน : </i><?php echo $C_REGIS; ?></td>
    <td colspan="2"><i>วันหมดอายุภาษี : </i><?php echo $C_TAX_ExpDate; ?></td>
</tr>
<tr align="left">
    <td colspan="2"><i>เลขถัง : </i><?php echo $C_CARNUM; ?></td>
    <td colspan="2"><i>เลขเครื่อง : </i><?php echo $C_MARNUM; ?></td>
</tr>
</table>
<?php }else{ ?>

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#FFF0F0">
<tr align="left" bgcolor="#FFCACA">
    <td colspan="4"><b><i>ข้อมูลรถ</i></b></td>
</tr>
<tr align="left">
    <td width="25%"><i>ยี่ห้อ : </i><?php echo $gas_name; ?></td>
    <td width="25%"></td>
    <td width="25%"><i>Gas Number : </i><?php echo $gas_number; ?></td>
    <td width="25%"><i>ประเภท : </i><?php echo $gas_type; ?></td>
</tr>
<tr align="left">
    <td colspan="2"><i>ทะเบียน : </i><?php echo $car_regis; ?></td>
    <td><i>ปี : </i><?php echo $car_year; ?></td>
    <td><i>จังหวัด : </i><?php echo $car_regis_by; ?></td>
</tr>
<tr align="left">
    <td colspan="2"><i>เลขถัง : </i><?php echo $carnum; ?></td>
    <td colspan="2"><i>เลขเครื่อง><?php echo $marnum; ?></td>
</tr>
</table>
<?php } ?>
<br>

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#FFFFE6">
<tr align="left" bgcolor="#FFFFCE">
    <td colspan="4"><b><i>รายละเอียด</i></b></td>
</tr>
<tr align="left">
    <td><i>IDNO : </i><?php echo $g_idno; ?></td>
    <td><i>Ref#1 : </i><?php echo $TranIDRef1; ?></td>
    <td colspan="2"><i>Ref#2 : </i><?php echo $TranIDRef2; ?></td>
</tr>
<tr align="left">
    <td><i>วันทำสัญญา : </i><?php echo $P_STDATE; ?></td>
    <td><i>วันที่งวดแรก : </i><?php echo $P_FDATE; ?></td>
    <td colspan="2"></td>
</tr>
<tr align="left">
    <td><i>วันที่หยุด VAT : </i><?php echo $P_StopVatDate; ?></td>
    <td><i>วันที่ปิดบัญชี : </i><?php echo $P_CLDATE; ?></td>
    <td align="right"><i>ยอดจัด : </i><?php echo number_format($P_BEGIN,2); ?></td>
    <td align="right"><i>ต้นทุนทางบัญชี : </i><?php echo number_format($P_BEGINX,2); ?></td>
</tr>
<tr align="left">
    <td width="25%"><i>จำนวนงวด : </i><?php echo $P_TOTAL; ?></td>
    <td width="25%" align="right"><i>ค่างวด : </i><?php echo number_format($P_MONTH,2); ?></td>
    <td width="25%" align="right"><i>VAT : </i><?php echo number_format($P_VAT,2); ?></td>
    <td width="25%" align="right"><i>ค่างวดรวม VAT : </i><?php echo number_format(($P_MONTH+$P_VAT),2); ?></td>
</tr>

<tr align="left">
    <td align="right" colspan="2"><i>ยอดเช่าซื้อทั้งหมดไม่รวม VAT : </i><?php echo number_format(($P_TOTAL*$P_MONTH),2); ?></td>
    <td align="right" colspan="1"><i>VAT ของยอดเช่าซื้อ : </i><?php echo number_format(($P_TOTAL*$P_VAT),2); ?></td>
    <td align="right" colspan="1"><i>ยอดเช่าซื้อรวม VAT : </i><?php echo number_format(($P_TOTAL*($P_MONTH+$P_VAT)),2); ?></td>
</tr>
</table>

    </td>
 </tr>
 </table>

</div>
<?php
}
?>

</div>

</body>
</html>