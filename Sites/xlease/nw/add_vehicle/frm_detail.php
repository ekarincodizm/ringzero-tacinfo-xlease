<?php
include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
$cartempid = $_GET["cartempid"];
$qry_sel = pg_query("SELECT \"CarIDtemp\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS\", \"C_REGIS_BY\", 
			   \"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
			   \"C_TAX_MON\",\"C_StartDate\", \"RadioID\", \"CarType\", \"C_CAR_CC\",file,\"fc_type\",\"fc_brand\",\"fc_model\",\"fc_category\",\"fc_newcar\",\"fc_gas\"
		  FROM \"Fc_temp\" where \"CarIDtemp\" = '$cartempid' ");
			$re_Sel = pg_fetch_array($qry_sel);
			
			$C_CARNAME =$re_Sel['C_CARNAME'];
			$C_YEAR =$re_Sel['C_YEAR'];
			$C_REGIS =$re_Sel['C_REGIS'];
			$C_REGIS_BY =$re_Sel['C_REGIS_BY'];
			$C_COLOR =$re_Sel['C_COLOR'];
			$C_CARNUM =$re_Sel['C_CARNUM'];
			$C_MARNUM =$re_Sel['C_MARNUM'];
			$C_Milage =$re_Sel['C_Milage'];
			$C_TAX_ExpDate =$re_Sel['C_TAX_ExpDate'];
			$C_TAX_MON =$re_Sel['C_TAX_MON'];
			$C_StartDate =$re_Sel['C_StartDate'];
			$RadioID =$re_Sel['RadioID'];
			$CarType =$re_Sel['CarType'];
			$C_CAR_CC =$re_Sel['C_CAR_CC'];
			$file =$re_Sel['file'];
			
			
			$fp_fc_type = $re_Sel["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
			$fp_fc_brand = $re_Sel["fc_brand"]; //ยี่ห้อ
			$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '".$fp_fc_brand."' ");
			list($brandname) = pg_fetch_array($qry_sel_brand);

			$fp_fc_model = $re_Sel["fc_model"]; //รุ่น
			$fp_fc_category = $re_Sel["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
			$fp_fc_newcar = $re_Sel["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว
			$fp_fc_gas = $re_Sel["fc_gas"]; //ระบบแก๊สรถยนต์
			if($fp_fc_type != ""){
				//หาประเภท
				$qry_sel_type = pg_query("select \"astypeName\" FROM \"thcap_asset_biz_astype\" WHERE \"astypeID\" = '$fp_fc_type' ");
				list($fp_type) = pg_fetch_array($qry_sel_type);
				//หายี่ห้อ
				$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '$fp_fc_brand' ");
				list($fp_band) = pg_fetch_array($qry_sel_brand);
				//หารุ่น
				$qry_sel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '$fp_fc_model' ");
				list($fp_model) = pg_fetch_array($qry_sel_model);
			}else{
				$fp_type = 'รถยนต์';
				//$fp_band = $C_CARNAME; //ใช้ $C_CARNAME เลยไม่ได้ เนื่องจาก  $C_CARNAME มีทั้งยี่ห้อรุ่นรวมกัน (ปรับปรุง 24/4/56)
				$fp_band=$brandname;
			}
			//แปลงสถานะรถเป็น text
			if($fp_fc_newcar == '1'){
				$newcar = 'รถใหม่';			
			}else if($fp_fc_newcar == '2'){
				$newcar = 'รถใช้แล้ว';
			}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>รายละเอียดเพิ่มรถยนต์</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../book_car_check/act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../post/fancybox/lib/jquery-1.7.2.min.js"></script>  
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="../../post/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="../../post/fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
<link rel="stylesheet" type="text/css" href="../../post/fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
<link rel="stylesheet" type="text/css" href="../../post/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
<script type="text/javascript" src="../../post/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
<link rel="stylesheet" type="text/css" href="../../post/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
<script type="text/javascript" src="../../post/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
<script type="text/javascript" src="../../post/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>   
<script language=javascript>
$(document).ready(function(){

	$(".fancybox-effects-a").fancybox({
					minWidth: 300,
				   maxWidth: 700,
				   'height' : '600',
				   'autoScale' : true,
				   'transitionIn' : 'none',
				   'transitionOut' : 'none',
				   'type' : 'iframe'
	});
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body bgcolor="#DFE6EF">
<form name="frm" method="post" action="process_add.php" enctype="multipart/form-data">
<table width="700" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center"> 			 
			<table width="650" frame="box" cellSpacing="0" cellPadding="5" align="center" bgcolor="#528B8B">
							<tr><td align="center"><font color="white"><h3>รายละเอียด</h3></font></td></tr>			
			</table>			
		</td>
    </tr>
	 <tr>
        <td align="center"> 
			<table width="650" cellSpacing="0" cellPadding="1" frame="box"  align="center" bgcolor="#79CDCD">
				<tr>
					<td align="right">ประเภทรถ :</td>
					<td colspan="6"><input type="text" Readonly  size="10px" name="fp_model" id="fp_model" value="<?php echo $fp_type; ?>" ></td>
				</tr>
				<tr>					
					<td align="right">ยี่ห้อ :</td>
					<td colspan="6"><input type="text" Readonly  size="44px" name="C_CARNAME" id="C_CARNAME" value="<?php echo $fp_band; ?>" ></td>
				</tr>
				<tr>					
					<td align="right">รุ่น :</td>
					<td colspan="6"><input type="text" Readonly  size="44px" name="fp_model" id="fp_model" value="<?php echo $fp_model; ?>" ></td>
				</tr>
				<tr>
					<td width="130" align="right">ทะเบียนรถ :</td>
					<td width="100" ><input type="text" Readonly  size="10px" name="C_REGIS" value="<?php echo $C_REGIS; ?>"></td>
					<td width="70" align="right">จังหวัด :</td>
					<td colspan="2"><input type="text" Readonly  size="20px" name="C_REGIS_BY"  value="<?php echo $C_REGIS_BY; ?>"></td>
					<td></td>	
				</tr>
					<td align="right">ปีรถ :</td>
					<td><input type="text" Readonly  size="5px" value="<?php echo $C_YEAR; ?>" maxlength="4" name="C_YEAR"></td>
					<td align="right">สีรถ :</td>
					<td width="100"><input type="text" Readonly  size="10px" name="C_COLOR" value="<?php echo $C_COLOR; ?>"></td>
					<td width="130" align="right">ความจุเครื่องยนต์ :</td>
					<td><input type="text" Readonly  size="10px" value="<?php echo $C_CAR_CC; ?>" name="C_CAR_CC"> cc.</td>				
				<tr>
					<td align="right">เลขตัวถัง :</td>
					<td colspan="3"><input type="text" Readonly  size="25px" name="C_CARNUM" value="<?php echo $C_CARNUM; ?>"></td>
					<td align="right">รหัสเครื่องยนต์ :</td>
					<td colspan="2"><input type="text" Readonly  size="25px" name="C_MARNUM" value="<?php echo $C_MARNUM; ?>"></td>
				</tr>
				<tr>
					<td align="right">เลขไมล์ :</td>
					<td><input type="text" Readonly  size="10px" value="<?php echo $C_Milage; ?>" name="C_Milage"></td>					
				</tr>
				<tr>
					<td align="right">วันที่หมดอายุภาษี:</td>
					<td><input type="text" Readonly  size="10px" name="C_TAX_ExpDate" id="C_TAX_ExpDate"  value="<?php echo $C_TAX_ExpDate; ?>"></td>	
					<td align="right">ค่าภาษี:</td> 
					<td><input type="text" Readonly  size="10px" name="C_TAX_MON" value="<?php echo $C_TAX_MON; ?>"></td>		
				</tr>
				<tr>
					<td align="right">เลขวิทยุ :</td>
					<td colspan="5"><input type="text" Readonly  size="20px" name="RadioID" value="<?php echo $RadioID; ?>"> (เลขวิทยุ หรือ โทรศัพท์ที่ลูกค้าใช้ในรถ)</td>					
				</tr>
				<tr>
					<td align="right">ประเภทรถยนต์ :</td>
					<td colspan="5">
						<?php
						if($CarType == '0'){
							$txttype= "รถนั่งทั่วไป";
						}else if($CarType == '1'){
							$txttype= "แท็กซี่บริษัท";
						}else if($CarType == '2'){
							$txttype= "แท็กซี่เขียวเหลือง";
						}else if($CarType == '3'){
							$txttype= "แท็กซี่สีอื่นๆ";
						}
						?>
						<input type="text" value="<?php echo $txttype;?>" Readonly>
					</td>					
				</tr>
				<tr>
					<td align="right">วันที่จดทะเบียน :</td>
					<td colspan="5"><input type="text" Readonly  size="10px" id="dateregis" name="C_StartDate" value="<?php echo $C_StartDate; ?>"></td>									
				</tr>
				<tr>
					<td align="right">ชนิดรถ :</td>
					<td colspan="6"><input type="text" Readonly  size="10px" name="fp_fc_category" id="fp_fc_category" value="<?php echo $fp_fc_category; ?>" ></td>
				</tr>
				<tr>					
					<td align="right">เป็นรถ :</td>
					<td colspan="6"><input type="text" Readonly  size="10px" name="newcar" id="newcar" value="<?php echo $newcar; ?>" ></td>
				</tr>
				<tr>					
					<td align="right">ระบบแก๊สรถยนต์ :</td>
					<td colspan="6"><input type="text" Readonly  size="10px" name="gas_system" id="gas_system" value="<?php echo $fp_fc_gas; ?>" ></td>
				</tr>
				<tr>
					 <TD align="right">เอกสารแนบ :</TD>
					 <TD colspan="3">
					 <?php if($file != ""){ ?>	
							<a class="fancybox-effects-a" href="<?php echo $file; ?>" title="<?php echo $file ?>"><img src="../manageCustomer/images/detail.gif"></a>
					<?php }else{ ?>									
							-
					<?php } ?></TD>
				</tr>
				<tr>
					<td colspan="10"><hr width="550px"></td>
				</tr>
				<tr align="center">
					<td colspan="10"><input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;"></td>
				</tr>		
			</table>		
		</td>
    </tr>
	<tr>
		<td>
			<div style="padding-top:25px;"></div>
		</td>
	</tr>	
</table>
</form>
</body>