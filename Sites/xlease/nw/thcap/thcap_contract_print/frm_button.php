<?php
	include("../../../config/config.php");
	$conid = $_GET["conidserh"];//เลขที่สัญญา
	$contractID = $_GET["conidserh"];
	//หากประเภทของสัญญาเพื่อแยกการพิมพ์สัญญา
	$qry_typecon = pg_query("select \"thcap_get_contractType\" ('$conid') ");
	list($contype) = pg_fetch_array($qry_typecon);
	
		include ("frm_$contype.php");
	
	/*IF($contype == 'HIRE_PURCHASE'){
		
		$txtbutton_main = "พิมพ์สัญญาเช่าซื้อทรัพย์สิน";
		$txtbutton_sec = "พิมพ์สัญญาค้ำประกัน";
		
		$linkpdf_main = 'pdf_contract_thcap_hp.php';
		$linkpdf_sec = 'pdf_guarantee_thcap.php';
	
	}ELSE IF($contype == 'LEASING'){
	
		$txtbutton_main = "พิมพ์สัญญาเช่า";
		$txtbutton_sec = "พิมพ์สัญญาค้ำประกัน";
		
		$linkpdf_main = 'pdf_contract_thcap_leasing.php';
		$linkpdf_sec = 'pdf_guarantee_thcap.php';
		
	}ELSE IF($contype == 'LOAN'){
	
		$txtbutton_main = "พิมพ์สัญญาเงินกู้";
		$txtbutton_sec = "พิมพ์สัญญาค้ำประกัน";
		
		$linkpdf_main = '';
		$linkpdf_sec = 'pdf_guarantee_thcap.php';
	} */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) พิมพ์สัญญา</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<script type="text/javascript">
function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
</script>
</head>
<body>
	
	<table width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"  align="center">
		<tr align="center">
			<td colspan="2"><h2><?php echo $conid; ?></h2></td>
		</tr>
		<tr align="center">
			<td colspan="2"><h3>ประเภท: <?php echo $contype; ?></h3></td>
		</tr>
		<tr align="center">
			<td colspan="2"><div style="padding-top:10px;"></div></td>
		</tr>
		<tr align="center">			
			<td><input type="button" value="<?php echo $txtbutton_main; ?>" id="btnmain" style="width:170px;height:70px;" onclick="javascript:popU('<?php echo $linkpdf_main; ?>?contractID=<?php echo $conid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"></td>
			<td><input type="button" value="<?php echo $txtbutton_sec; ?>" id="btnsec" style="width:170px;height:70px;" onclick="javascript:popU('<?php echo $linkpdf_sec; ?>?contractID=<?php echo $conid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"></td>
		</tr>
	</table>

</body>
</html>