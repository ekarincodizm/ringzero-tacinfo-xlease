<?php
include('../../config/config.php');

$tempID = $_GET["tempID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>รายละเอียด</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<!--<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>-->
<script language="javascript" type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>

<!---- หน้าต่าง Popup รูปภาพ ---->

<!-- Add jQuery library -->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		
			$('.fancyboxa').fancybox({
				minWidth: 450,
				maxWidth: 450
						
			});
			$('.fancyboxb').fancybox({	
				minWidth: 450,
				maxWidth: 450
			  });
			
			$(".pdforpic").fancybox({
			   minWidth: 500,
			   maxWidth: 800,
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

function chklist(appstate){
	if(document.frm1.app_note.value==""){
		alert('กรุณากรอกหมายเหตุด้วย');
		return false;
	}else{
		document.frm1.appv.value=appstate;
		document.frm1.submit();
	}
}
	</script>
<!---- จบหน้าต่าง Popup รูปภาพ ---->
</head>
<center><h2>รายละเอียด</h2></center>
<body >
<?php
$query_main = pg_query("select * from public.\"thcap_changeRate_temp\" where \"Approved\" is null and \"tempID\" = '$tempID' ");
while($result = pg_fetch_array($query_main))
{
	$tempID = $result["tempID"];
	$contractID = $result["contractID"]; // เลขที่สัญญา
	$oldRate = $result["oldRate"]; // อัตราดอกเบี้ยปัจจุบัน
	$newRate = $result["newRate"]; // อัตราดอกเบี้ยใหม่
	$effectiveDate = $result["effectiveDate"]; // วันเวลาที่เริ่มมีผล
	$doerID = $result["doerID"]; // ผู้ทำรายการ
	$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
	$remark = $result["remark"]; // หมายเหตุ
}

$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
while($result_name = pg_fetch_array($qry_name))
{
	$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
}
?>
<form name="frm1" action="process_appv_changeRateTemp.php" method="POST">
<table width="900" border="0" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#ECFAFF" id="tble">
<tr>
	<td width="50%"><br></td>
	<td width="50%"><br></td>
	<input type="hidden" name="valuechk" id="valuechk">
</tr>
<tr>
	<td align="right">เลขที่สัญญา : </td>
	<td>
		<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;">
			<font color="red"><u><?php echo $contractID; ?></u></font>
		</span>
	</td>
</tr>
<tr>
	<td align="right">อัตราดอกเบี้ยปัจจุบัน : </td>
	<td><?php echo $oldRate; ?></td>	
</tr>
<tr>
	<td align="right">อัตราดอกเบี้ยใหม่ : </td>
	<td><?php echo $newRate; ?></td>
</tr>
<tr>
	<td align="right">วันเวลาที่เริ่มมีผล : </td>
	<td><?php echo $effectiveDate; ?></td>
</tr>
<tr>
	<td align="right">ผู้ทำรายการ : </td>
	<td><?php echo $fullname; ?></td>
</tr>
<tr>
	<td align="right">วันเวลาที่ทำรายการ : </td>
	<td><?php echo $doerStamp; ?></td>
</tr>
<tr>
	<td align="right">เหตุผลในการปรับอัตราดอกเบี้ย : </td>
	<td><textarea Readonly><?php echo $remark; ?></textarea></td>
</tr>

<?php
	// หาว่ามีไฟล์แนบหรือไม่
	$qrySearchFile = pg_query("select * from public.\"thcap_changeRate_file_temp\" where \"tempID\" = '$tempID' and \"Approved\" is null ");
	$numrowsSearchFile = pg_num_rows($qrySearchFile);
	if($numrowsSearchFile > 0)
	{
		$f = 0;
		while($resFile = pg_fetch_array($qrySearchFile))
		{
			$f++;
			$tempID = $resFile["tempID"]; // folder ย่อย
			$pathFile = $resFile["pathFile"]; // ชื่อไฟล์
?>
			<tr>
				<td align="right">ไฟล์แนบที่ <?php echo $f; ?> : </td>
				<td><a class="fancyboxa" href="upload_reqchgintrate/<?php echo $tempID; ?>/<?php echo $pathFile; ?>" data-fancybox-group="gallery" title="<?php echo "ไฟล์แนบที่ $f";?>"><u> แสดงไฟล์แนบ </u></a></td>
			</tr>
<?php
		}
	}
?>
<tr>
	<td align="right">หมายเหตุ : <font color="red">*</font></td>
	<td><textarea name="app_note"></textarea></td>
</tr>	

<tr>
	<input type="hidden" name="appv" >
	<input type="hidden" name="tempID" value="<?php echo $tempID;?>">
	<td colspan="2" align="center"><input type="button" value="อนุมัติ" onclick="return chklist(1);"> &nbsp;&nbsp;&nbsp; 
	<input type="button" value="ไม่อนุมัติ" onclick="return chklist(2);"> &nbsp;&nbsp;&nbsp; <input type="button" value="ออก" onclick="javascript:window.close();"></td>
</tr>
<tr>
	<td><br></td>
</tr>	
</table>
</form>
</body>