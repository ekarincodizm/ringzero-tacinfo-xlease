<?php
session_start();
$c_code=$_SESSION["session_company_code"]; 
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>(THCAP) พิมพ์สัญญา BH</title>
<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>

<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4">(THCAP) พิมพ์สัญญา BH</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
<div  style="text-align:right;"><a href="frm_Index.php">กลับ</a></div>
<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  
    <?php
   $contractID=$_POST["idno_names"];
   
	// ค้นหารหัสผู้กู้หลัก
	$qry_cusMain = pg_query("select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
	$cusMainID = pg_fetch_result($qry_cusMain,0);
	
	// ค้นหาข้อมูลผู้กู้หลัก
	$qry_cusNane = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$cusMainID' ");
	$cusNane = pg_fetch_result($qry_cusNane,0);
    
	
	$idshow = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=750')\" style=\"cursor:pointer\"><font color=\"blue\"><u>".$contractID."</u></font></a>";
	
	//ค้นหาข้อมูลตัวรถ
	$qry_carDetail = pg_query("select b.* from \"thcap_contract_asset\" a, \"thcap_asset_biz_detail_10\" b
								where a.\"assetDetailID\" = b.\"assetDetailID\"
								and a.\"contractID\" = '$contractID'");
	while($res_carDetail = pg_fetch_array($qry_carDetail))
	{
		$assetDetailID = $res_carDetail["assetDetailID"]; // รหัสสินทรัพย์
		$C_REGIS = $res_carDetail["regiser_no"]; // ทะเบียนรถ
	}
	
	$qry_assetDetail = pg_query("select * from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
	while($res_assetDetail = pg_fetch_array($qry_assetDetail))
	{
		$asset_brand = $res_assetDetail["brand"]; // รหัสยี่ห้อ
		$asset_model = $res_assetDetail["model"]; // รหัสรุ่น
	}
	
	// หายี่ห้อ
	$qry_assetBrand = pg_query("select \"brand_name\" from \"thcap_asset_biz_brand\" where \"brandID\" = '$asset_brand' ");
	$fc_brand = pg_fetch_result($qry_assetBrand,0);
	
	// หารุ่น
	$qry_assetModel = pg_query("select \"model_name\" from \"thcap_asset_biz_model\" where \"modelID\" = '$asset_model' ");
	$fc_model = pg_fetch_result($qry_assetModel,0);

	if($C_REGIS == ""){ $C_REGIS = 'ไม่ระบุ'; }
	if($C_REGIS_BY == ""){ $C_REGIS_BY = 'ไม่ระบุ'; }
	if($fc_brand == ""){ $fc_brand = 'ไม่ระบุ'; }
	if($fc_model == ""){ $fc_model = 'ไม่ระบุ'; }
  ?>
  
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div style="padding:10px 0px 10px 0px;background-Color:#E6FFE6;border-style: solid; border-color:black;">
	
	<b> ชื่อ/สกุล</b> (<font color="#FF1493"><u><a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusMainID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><?php echo $cusMainID; ?></a></u></font>) <?php echo $cusNane. " (".$idshow.") "; ?>			
   <p>
    <b>ทะเบียน: </b><?php echo $C_REGIS; ?>
	<b>จังหวัด: </b><?php echo $C_REGIS_BY; ?>
	<b>ยี่ห้อ: </b><?php echo $fc_brand; ?>
	<b>รุ่น: </b><?php echo $fc_model; ?>
  </div>
  

	<div class="style5" style="width:auto; height:100px; padding:10px 0px 0px 10px;">
		<!--<a onclick="javascipt:popU('popup_chk_car_print.php?ID=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=750')" style="cursor:pointer;" ><font color="blue"><u>PRINT สัญญา (BH)</u></font></a>-->
		<a href="pdf/pdf_contract_BH.php?ID=<?php echo $contractID; ?>" target="_blank">PRINT สัญญา (BH)</a>
		<br />
		<a href="pdf/pdf_guarantee_BH.php?ID=<?php echo  $contractID; ?>" target="_blank">PRINT คนค้ำสัญญา (BH)</a>
	</div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
