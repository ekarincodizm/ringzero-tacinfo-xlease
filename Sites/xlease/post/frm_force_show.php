<?php 
session_start(); 
include("../config/config.php");

//$arr_idno = $_SESSION["arr_idno"];
$idno = pg_escape_string($_GET["idno"]);
$insfid = pg_escape_string($_GET['insfid']);

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
    padding-bottom: 3px;
    text-align: right;
}
H1 {font-family:tahoma; color : #333333; font-size:28px;}
A { font-size:12px; text-decoration:none;}
A:hover { color : #8B8B8B; font-size:12px; text-decoration:none;}
A:visited { color : #333333; font-size:12px; text-decoration:none;} 
input,select{font-family:tahoma; color : #333333; font-size:12px;}
.header{
    text-align:center;       
}
.wrapper{
    width:700; float:center; padding:5px;
}
legend{
    font-family: Tahoma;
    font-size: 14px;    
    color: #0000CC;
}
legend A{ color : #0000CC; font-size: 14px; text-decoration:none;}
legend A:hover{ color : #0000CC; font-size: 14px; text-decoration:none;}
legend A:visited{ color : #0000CC; font-size: 14px; text-decoration:none;}
fieldset{
    padding:3px;
}
.text_gray{
    color:gray;
}
.text_comment{
    color:red;
    font-size: 11px;
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

<div class="title_top">ประกันภัย (พรบ)</div>

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
?>

<div id="tabs-<?php echo $idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">

<?php
$get_insfidno = array();
$qry_ac=pg_query("select \"InsFIDNO\" from \"insure\".\"InsureForce\" WHERE \"IDNO\"='$idno' ORDER BY \"InsFIDNO\" ASC");
while($res_ac=pg_fetch_array($qry_ac)){
    $get_insfidno[] = $res_ac["InsFIDNO"]; 
}

$num_arrays = count($get_insfidno);
$num_arrays_lob = $num_arrays-1;

if($num_arrays > 0){ //ตรวจสอบว่าพบข้อมูลหรือไม่ หากพบทำต่อ...

foreach ($get_insfidno as $number_index => $data_insfidno){}

if(!empty($insfid)){
    $tn_array = array_search($insfid,$get_insfidno);
}else{
    $tn_array = $num_arrays_lob;
}

$tt1 = $tn_array-1;
$tt2 = $tn_array+1;
 

// ---------------------------- //


if(!empty($insfid)){
    $qry_dc=pg_query("select A.*,B.* from \"insure\".\"InsureForce\" A 
    LEFT OUTER JOIN \"VContact\" B ON A.\"IDNO\"=B.\"IDNO\" 
    WHERE A.\"IDNO\"='$idno' AND A.\"InsFIDNO\"='$insfid' ");
}else{
    $qry_dc=pg_query("select A.*,B.* from \"insure\".\"InsureForce\" A 
    LEFT OUTER JOIN \"VContact\" B ON A.\"IDNO\"=B.\"IDNO\" 
    WHERE A.\"IDNO\"='$idno' AND A.\"InsFIDNO\"='$get_insfidno[$number_index]' ");
}
if($res_if=pg_fetch_array($qry_dc)){
    $full_name = $res_if["full_name"];
    $c_regis = $res_if["C_REGIS"];
    $car_regis = $res_if["car_regis"];
    $asset_type = $res_if["asset_type"];    if($asset_type == 1){ $regis = $c_regis; }else{ $regis = $car_regis; }
    $car_num = $res_if["C_CARNUM"];
    $InsFIDNO = $res_if["InsFIDNO"];
    $InsID = $res_if["InsID"];
    $InsMark = $res_if["InsMark"];
    $Company = $res_if["Company"];
    $StartDate = $res_if["StartDate"];
    $EndDate = $res_if["EndDate"];
    $NetPremium = $res_if["NetPremium"];
    $Premium = $res_if["Premium"];
    $Discount = $res_if["Discount"];
    $CoPayInsReady = $res_if["CoPayInsReady"]; if($CoPayInsReady == 't'){ $copay = "ชำระแล้ว"; }else{ $copay = "ยังไม่ชำระ"; }
    $CollectCus = $res_if["CollectCus"];
    $Cancel = $res_if["Cancel"];
}

$outins=pg_query("select \"insure\".outstanding_insforce('$InsFIDNO')");
$out_ins=pg_fetch_result($outins,0);

$qry_bname=pg_query("select \"InsFullName\" from \"insure\".\"InsureInfo\" WHERE \"InsCompany\"='$Company' ");
if($res_bname=pg_fetch_array($qry_bname)){
    $InsFullName = $res_bname["InsFullName"]; 
}
?>

<div style="float:right; padding-right:3px;">
<?php
if($tt1>=0){
    echo "<a href=\"frm_force_show.php?idno=$idno&insfid=$get_insfidno[0]\"> <b>&lt;&lt;</b> </a>";
    echo "<a href=\"frm_force_show.php?idno=$idno&insfid=$get_insfidno[$tt1]\"> <b>&lt;</b> </a>";
}
if(!empty($insfid)){    
    echo " <i>$insfid</i> ";
}else{
    echo " <i>$get_insfidno[$number_index]</i> ";
}
if($tt2<=$num_arrays_lob){
    echo "<a href=\"frm_force_show.php?idno=$idno&insfid=$get_insfidno[$tt2]\"> <b>&gt;</b> </a>";
    echo "<a href=\"frm_force_show.php?idno=$idno&insfid=$get_insfidno[$num_arrays_lob]\"> <b>&gt;&gt;</b> </a>"; 
}
?>
</div>

<div>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
    <tr align="left">
        <td width="15%"><b>ชื่อผู้เช่า</b></td>
        <td width="35%"><b></b> <?php echo $full_name." (".$idno.")"; ?></td>
        <td width="30%"><b>ทะเบียนรถ</b></td>
        <td width="20%"><?php echo $regis; ?></td>
    </tr>
    <tr align="left">
        <td><b>เลขถัง</b></td>
        <td><b></b> <a href="../up/frm_show.php?id=<?php echo $car_num; ?>&type=reg&mode=2" target="_blank"><u><?php echo $car_num; ?></u></a></td>
    </tr>
    <tr align="left">
        <td><b>รหัสกรมธรรม์</b></td>
        <td><a href="../up/frm_show.php?id=<?php echo $InsFIDNO; ?>&type=insfo&mode=1" target="_blank"><u><?php echo $InsFIDNO; ?></u></a></td>
    </tr>
    <tr align="left">
        <td><b>เลขกรมธรรม์</b></td>
        <td><?php echo $InsID; ?></td>
        <td><b>เลขเครื่องหมาย</b></td>
        <td><?php echo $InsMark; ?></td> 
    </tr>
    <tr align="left">
        <td><b>บริษัทประกัน</b></td>
        <td><?php echo $InsFullName; ?></td>
        <td><b>วันที่เริ่มคุ้มครอง</b></td>
        <td><?php echo $StartDate; ?></td>
    </tr>
    <tr align="left">
        <td></td>
        <td></td>
        <td><b>วันที่หมดอายุ</b></td>
        <td><?php echo $EndDate; ?></td>
    </tr>
    <tr align="left">
        <td><b>ค่าเบิ้ยสุทธิ</b></td>
        <td><?php echo number_format($NetPremium,2); ?> <span class="text_gray">บาท.</span></td>
        <td><b>ยอดค้างชำระ</b></td>
        <td><?php echo number_format($out_ins,2); ?> <span class="text_gray">บาท.</span></td>
    </tr>
    <tr align="left">
        <td><b>ค่าเบี้ยประกัน</b></td>
        <td><?php echo number_format($Premium,2); ?> <span class="text_gray">บาท.</span></td>
    </tr>
    <tr align="left">
        <td><b>ส่วนลด</b></td>
        <td><?php echo number_format( $Discount,2); ?> <span class="text_gray">บาท.</span></td>
        <td><b>สถานะการชำระให้บริษัทประกัน</b></td>
        <td><?php echo $copay; ?></td>
    </tr>
    <tr align="left">
        <td><b>เบิ้ยที่ต้องชำระ</b></td>
        <td><?php echo number_format($CollectCus,2); ?> <span class="text_gray">บาท.</span></td>
        <?php if($Cancel != 'f'){ ?>              
        <td colspan=2 bgcolor="#FFFFCC" align="center"><FONT COLOR="#ff0000"><b>&gt;&gt;&gt; รายการนี้ถูกยกเลิก &lt;&lt;&lt;</b></FONT></td>
        <?php } ?>
    </tr>
</table>
</div>

<div style="clear:both;">&nbsp;</div>

<div>
<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#F0F0F0"  align="center" style="padding-top:15px;">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>วันที่ชำระ</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>จำนวนเงิน</td>
        <td>สถานะการชำระ</td>
    </tr>
<?php

$qry_vcus=pg_query("select \"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"PayType\" from \"FOtherpay\" WHERE  \"RefAnyID\"='$InsFIDNO' AND \"O_Type\"='103' AND \"Cancel\"='false' order by \"O_DATE\"  ");
while($resvc=pg_fetch_array($qry_vcus)){
    $vcus_nub+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>     
    <td><?php echo $resvc["O_DATE"]; ?></td>
    <td><?php echo $resvc["O_RECEIPT"]; ?></td>
    <td align="right"><?php echo number_format($resvc["O_MONEY"],2); ?></td>
    <td><?php echo $resvc["PayType"]; ?></td>
</tr>
<?php
}
if($vcus_nub==0){
?>
<tr align="center" valign="middle">
    <td colspan="4">- ไม่พบข้อมูล -</td>
</tr>   
<?php } ?>
</table>
</div>

<?php }else{ ?>
    <div align="center">- ไม่พบข้อมูล -</div>
<?php 
}
?>

</div>
</div>

<?php
}
?>

</div>

</body>
</html>