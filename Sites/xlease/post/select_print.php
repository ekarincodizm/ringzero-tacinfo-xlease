<?php
session_start();
$c_code=$_SESSION["session_company_code"]; 
include("../config/config.php");
$idno=trim(pg_escape_string($_POST["h_id"]));
//$c_code="THA";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<!-- InstanceEndEditable -->

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
<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    self.close( )	
    wnd[N] = window.open(U, N, T);
}
</script>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  
    <?php
   //$idnos=$_POST["idno_names"];
   //$idno=substr($idnos,0,11);

   
   
   //ค้นหาข้อมูลเลขที่สัญญา
    $qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
	$res_FpFa1=pg_fetch_array($qry_FpFa1);
    $Cusidshow = trim($res_FpFa1["CusID"]);
	$fullname = trim($res_FpFa1["A_FIRNAME"])." ".trim($res_FpFa1["A_NAME"])." ".trim($res_FpFa1["A_SIRNAME"]);
	$asset_id = trim($res_FpFa1["asset_id"]);
	
	$idshow = "<a onclick=\"javascript:popU('frm_viewcuspayment.php?idno_names=$idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=700')\" style=\"cursor:pointer\"><font color=\"blue\"><u>".$idno."</u></font></a>";
	
	//ค้นหาข้อมูลตัวรถ
	$qry_Fc = pg_query("SELECT \"C_REGIS\", \"C_REGIS_BY\", \"fc_brand\", \"fc_model\", \"C_CARNAME\" FROM \"Fc\" WHERE \"CarID\" = '$asset_id' ");
	$row_Fc = pg_num_rows($qry_Fc);
	if($row_Fc > 0){ //หากมีข้อมูลแสดงว่าเป็นรถธรรมดา
			$re_Fc = pg_fetch_array($qry_Fc);
			$C_REGIS = $re_Fc["C_REGIS"]; //ทะเบียน
			$C_REGIS_BY = $re_Fc["C_REGIS_BY"]; //จังหวัดทะเบียน
			$fc_brand = $re_Fc["fc_brand"]; //ยี่ห้อ
			$fc_model = $re_Fc["fc_model"]; //รุ่น
			
			if($fc_brand == ""){
				$fc_brand = $re_Fc["C_CARNAME"]; //ยี่ห้อ
			}else{
				//หายี่ห้อจากตาราง brand
				$qry_brand = pg_query("SELECT \"brand_name\" FROM \"thcap_asset_biz_brand\" where \"brandID\" = '$fc_brand' ");
				list($fc_brand) = pg_fetch_array($qry_brand);
				//หารุ่นจากตาราง model
				$qry_model = pg_query("SELECT \"model_name\" FROM \"thcap_asset_biz_model\" where \"modelID\" = '$fc_model' ");
				list($fc_model) = pg_fetch_array($qry_model);
			}
		
		
	}else{ //หากไม่มีข้อมูลแสดงว่าเป็นรถติดแก๊ส
			$qry_Fgas = pg_query("SELECT \"car_regis\", \"car_regis_by\", \"fc_brand\", \"fc_model\" FROM \"FGas\" WHERE \"GasID\" = '$asset_id' ");
			$re_Fgas = pg_fetch_array($qry_Fgas);
			$C_REGIS = $re_Fgas["car_regis"]; //ทะเบียน
			$C_REGIS_BY = $re_Fgas["car_regis_by"]; //จังหวัดทะเบียน
			$fc_brand = $re_Fgas["fc_brand"]; //ยี่ห้อ
			$fc_model = $re_Fgas["fc_model"]; //รุ่น
			
			if($fc_brand != ""){
				//หายี่ห้อจากตาราง brand
				$qry_brand = pg_query("SELECT \"brand_name\" FROM \"thcap_asset_biz_brand\" where \"brandID\" = '$fc_brand' ");
				list($fc_brand) = pg_fetch_array($qry_brand);
				//หารุ่นจากตาราง model
				$qry_model = pg_query("SELECT \"model_name\" FROM \"thcap_asset_biz_model\" where \"modelID\" = '$fc_model' ");
				list($fc_model) = pg_fetch_array($qry_model);
			}	

	}
	

	if($C_REGIS == ""){ $C_REGIS = 'ไม่ระบุ'; }
	if($C_REGIS_BY == ""){ $C_REGIS_BY = 'ไม่ระบุ'; }
	if($fc_brand == ""){ $fc_brand = 'ไม่ระบุ'; }
	if($fc_model == ""){ $fc_model = 'ไม่ระบุ'; }
	
	//เช็ค ว่ามีข้อมูลครบหรือไม่ 
	$qry_chk = pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$idno'");
	$row_chk = pg_num_rows($qry_chk);
	$res_fc = pg_fetch_array($qry_chk);

	$fc_color=trim($res_fc["C_COLOR"]);//10
	$fc_num=trim($res_fc["C_CARNUM"]);//6
	$fc_mar=trim($res_fc["C_MARNUM"]);//7
	$fc_mi=trim($res_fc["C_Milage"]);//	13		
	$fp_fc_type = $res_fc["fc_type"]; // ประเภท รถยนต์/จักรยายนต์  1
	$fp_fc_brand = $res_fc["fc_brand"]; //ยี่ห้อ 2
	$fp_fc_model = $res_fc["fc_model"]; //รุ่น 3
	$fp_fc_category = $res_fc["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง //11
	$fp_fc_newcar = $res_fc["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	12
	$fp_fc_gas = $res_fc["fc_gas"]; //ระบบแก๊ส 14	
	$fc_year=trim($res_fc["C_YEAR"]); //5
	$fc_regis=trim($res_fc["C_REGIS"]);//8
	$fcs_regis_by=trim($res_fc["C_REGIS_BY"]);//9
	$result="NO";
	if(($fp_fc_type=='')or (fc_color=="")or ($fc_num=="")or ($fc_mar=="")or ($fc_mi=="")or ($fp_fc_brand=='')or 
	($fp_fc_model=='')or ($fp_fc_newcar=='')or ($fc_year=="")or ($fc_regis=="")or ($fcs_regis_by==""))
	{}
	else
	{
		if($fp_fc_type==13)
		{
			if(($fp_fc_category =="")or ($fp_fc_gas="")){ }	
			else{	$result="YES"; }
		}
		else
		{   
			$result="YES";
		}	
	}	
  ?>
  
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div style="padding:10px 0px 10px 0px;background-Color:#E6FFE6;border-style: solid; border-color:black;">
	
	<b> ชื่อ/สกุล</b> (<font color="#FF1493"><u><a style="cursor:pointer;" onclick="javascipt:popU('../nw/search_cusco/index.php?cusid=<?php echo $Cusidshow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><?php echo $Cusidshow; ?></a></u></font>) <?php echo $fullname. " (".$idshow.") "; ?>			
   <p>
    <b>ทะเบียน: </b><?php echo $C_REGIS; ?>
	<b>จังหวัด: </b><?php echo $C_REGIS_BY; ?>
	<b>ยี่ห้อ: </b><?php echo $fc_brand; ?>
	<b>รุ่น: </b><?php echo $fc_model; ?>
  </div>
  

<div class="style5" style="width:auto; height:100px; padding:10px 0px 0px 10px;">
  <a href="pdf_viewprint_<?php echo $c_code; ?>.php?ID=<?php echo  $idno; ?>" target="_blank">PRINT สัญญา</a>
    <br />
   <a href="pdf_grtprint_<?php echo $c_code; ?>.php?ID=<?php echo  $idno; ?>" target="_blank">PRINT คนค้ำสัญญา</a>
   <p>
   <br>
   <?php 
   if($result=="NO") { ?>
   <a onclick="javascipt:popU('popup_chk_car_print.php?ID=<?php echo $idno; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=750')" style="cursor:pointer;" ><font color="blue"><u>PRINT สัญญา (ฟอร์มสัญญารูปแบบใหม่)</u></font></a>
    <!-- <a href="pdf/pdf_contract.php?ID=<?php echo  $idno; ?>" target="_blank">PRINT สัญญา (ฟอร์มสัญญารูปแบบใหม่)</a>-->
	<br />
	
	<?php } else{ ?>
	<a href="pdf/pdf_contract.php?ID=<?php echo  $idno; ?>" target="_blank">PRINT สัญญา (ฟอร์มสัญญารูปแบบใหม่)</a>
	<br />
	<?php } ?>
	
   <a href="pdf/pdf_guarantee.php?ID=<?php echo  $idno; ?>" target="_blank">PRINT คนค้ำสัญญา (ฟอร์มคนค้ำสัญญารูปแบบใหม่)</a>
   
   <p>
   <br>
   
   
   
   </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
