<?php
session_start();
include("../config/config.php");

$idno = $_GET["idno"];

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

    $qry_6=pg_query("select \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" from \"Fa1\" WHERE (\"CusID\"='$cusid');");
    if($res_6=pg_fetch_array($qry_6)){
        $A_FIRNAME = $res_6['A_FIRNAME'];
        $A_NAME = $res_6['A_NAME'];
        $A_SIRNAME = $res_6['A_SIRNAME'];
        $full_name = "$A_FIRNAME $A_NAME  $A_SIRNAME";
    }
	//หาเบอร์มือถือ
	$qrymobile=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$cusid' and phonetype='2' order by \"doerStamp\" DESC limit 1");
	list($cusrenter_mobile)=pg_fetch_array($qrymobile);
	if($cusrenter_mobile == "0"){$customer_mobile1 = "ไม่ระบุ";}
	//หาเบอร์บ้าน
	$qrytel=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$cusid' and phonetype='1' order by \"doerStamp\" DESC limit 1");
	list($cusrenter_tel)=pg_fetch_array($qrytel);
	if($cusrenter_tel == "0"){$customer_tel = "ไม่ระบุ";}

?>
<div id="tabs-<?php echo $idno; ?>">

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="<?php echo $bgcolor; ?>"  align="center">
<tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="left" valign="middle">
    <td colspan=2>ผู้เช่า</td>
</tr>
<tr>
    <td align="left" valign="top" width="25%"><b>ชื่อ/สกุล : </b></td>
    <td align="left" valign="top" width="75%"><?php echo "$full_name"; ?></td>
</tr>
<tr>
    <td align="left" valign="top"><b>ที่ติดต่อลูกค้า : </b></td>
    <td align="left" valign="top" colspan=3><?php echo "$N_ContactAdd"; ?></td>
</tr>
<!---->
<tr>
	<td align="left" valign="top"><b>เบอร์โทรศัพท์มือถือ : </b></td>
	<td align="left" valign="top"><a href="#" onclick="javascript:popU('../nw/thcap_installments/frm_ShowPhone.php?CusID=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$cusrenter_mobile"; ?></a>
		<img src="../nw/thcap_installments/images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('../nw/thcap_installments/frm_AddPhone.php?CusID=<?php echo $cusid; ?>&type=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
	</td>
</tr>
<tr>
	<td align="left" valign="top"><b>เบอร์บ้าน : </b></td>
	<td align="left" valign="top"><a href="#" onclick="javascript:popU('../nw/thcap_installments/frm_ShowPhone.php?CusID=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$cusrenter_tel"; ?></a>
		<img src="../nw/thcap_installments/images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('../nw/thcap_installments/frm_AddPhone.php?CusID=<?php echo $cusid; ?>&type=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
	</td>
</tr>
</table>

<?php
$qry_ctc=pg_query("SELECT \"CusID\" FROM \"ContactCus\" WHERE \"IDNO\"='$idno' AND \"CusState\" <> 0 ORDER BY \"CusState\" ASC ");
while($res_ctc=pg_fetch_array($qry_ctc)){
    $x_CusID = $res_ctc["CusID"];
    
    $qry_vcus=pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"CusID\"='$x_CusID'");
    if($resvc=pg_fetch_array($qry_vcus)){
            $c_N_ContactAdd = $resvc["N_ContactAdd"];
                $c_N_ContactAdd= str_replace("\n", "<br>\n", "$c_N_ContactAdd"); 
    }
    
    $qry_6=pg_query("select \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" from \"Fa1\" WHERE (\"CusID\"='$x_CusID');");
    if($res_6=pg_fetch_array($qry_6)){
        $A_FIRNAME = $res_6['A_FIRNAME'];
        $A_NAME = $res_6['A_NAME'];
        $A_SIRNAME = $res_6['A_SIRNAME'];
        $c_full_name = "$A_FIRNAME $A_NAME  $A_SIRNAME";
    }
	
	//หาเบอร์บ้าน
	$qrytel=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$x_CusID' and phonetype='1' order by \"doerStamp\" DESC limit 1");
	list($customer_tel)=pg_fetch_array($qrytel);
	if($customer_tel == "0"){$customer_tel = "ไม่ระบุ";}
	
	//หาเบอร์มือถือ
	$qrymobile=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$x_CusID' and phonetype='2' order by \"doerStamp\" DESC limit 1");
	list($customer_mobile)=pg_fetch_array($qrymobile);
	if($customer_mobile == "0"){$customer_mobile = "ไม่ระบุ";}

    $k_nub++;
?>
<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="<?php echo $bgcolor; ?>"  align="center">
<tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="left" valign="middle">
    <td colspan=2>ผู้ค้ำ <?php echo $k_nub; ?></td>
</tr>
<tr>
    <td align="left" valign="top" width="25%"><b>ชื่อ/สกุล : </b></td>
    <td align="left" valign="top" width="75%"><?php echo "$c_full_name"; ?></td>
</tr>
<tr>
    <td align="left" valign="top"><b>ที่ติดต่อลูกค้า : </b></td>
    <td align="left" valign="top" colspan=3><?php echo "$c_N_ContactAdd"; ?></td>
</tr>
<tr>
	<td align="left" valign="top"><b>เบอร์โทรศัพท์มือถือ : </b></td>
	<td align="left" valign="top"><a href="#" onclick="javascript:popU('../nw/thcap_installments/frm_ShowPhone.php?CusID=<?php echo $x_CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$customer_mobile"; ?></a>
		<img src="../nw/thcap_installments/images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('../nw/thcap_installments/frm_AddPhone.php?CusID=<?php echo $x_CusID; ?>&type=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
	</td>
</tr>
<tr>
	<td align="left" valign="top"><b>เบอร์บ้าน : </b></td>
	<td align="left" valign="top"><a href="#" onclick="javascript:popU('../nw/thcap_installments/frm_ShowPhone.php?CusID=<?php echo $x_CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$customer_tel"; ?></a>
		<img src="../nw/thcap_installments/images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('../nw/thcap_installments/frm_AddPhone.php?CusID=<?php echo $x_CusID; ?>&type=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
	</td>
</tr>
</table>
<?php    
}
?>

</div>
<?php } ?>

</div><!-- จบ เริ่ม tabs -->


</body>
</html>