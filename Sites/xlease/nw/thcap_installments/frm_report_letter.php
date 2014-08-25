<?php
session_start();
include("../../config/config.php");

$idno = pg_escape_string($_GET["idno"]);
$status=1;

//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk=pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$idno'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}

//ค้นหาชื่อผู้กู้หลัก
$qry_namemain=pg_query("select \"thcap_fullname\", \"type\" from  \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" ='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
	$typecus=trim($resnamemain["type"]);
}

//ค้นหาชื่อผู้กู้ร่วม
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" = '1'");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name)){
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
$i++;
}

$qry_top=pg_query("select \"CusID\", \"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
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

<div class="title_top">ประวัติการส่งจดหมาย</div>

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
?>

<div id="tabs-<?php echo $idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">
<!--<div align="right">
	<form method="post" name="frmprint" action="frm_print_otherpay.php">
		<input type="hidden" name="idno" value="<?php echo $idno; ?>">
		<input type="submit" value="พิมพ์">
	</form>
</div>-->
<div align="right" style="font-weight:bold; padding-top:3px; padding-bottom:3px;">ผู้กู้หลัก : <?php echo $name3; if($nameco != ""){?> | ผู้กู้ร่วม : <?php echo $nameco;}?></div>

<?php
if($typecus==2){ //กรณีผู้กู้หลักเป็นนิติบุคคล ให้แสดงส่วนนี้ด้วย
?>
<fieldset><legend><b>ใบกำกับภาษีรอนำส่ง</b></legend>
<?php include("../thcap/frm_letter_tax_notsent.php");?>
</fieldset>
<?php
}
?>
<fieldset><legend><b>ประวัติการส่งจดหมาย</b></legend>
<?php include("../thcap/frm_lt_report_list_dt.php");?>
</fieldset>

</div>
</div>

<?php
}
?>

</body>
</html>