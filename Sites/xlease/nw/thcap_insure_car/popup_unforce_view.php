<?php
include("../../config/config.php");

$requestID = pg_escape_string($_GET["requestID"]);

// หารายละเอียดรถ
$q = "
		SELECT
			d.\"astypeName\",
			e.\"brand_name\",
			f.\"model_name\",
			CASE WHEN c.\"astypeID\" = '10' THEN g.\"motorcycle_no\" ELSE h.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN c.\"astypeID\" = '10' THEN c.\"productCode\" ELSE h.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN c.\"astypeID\" = '10' THEN g.\"regiser_no\" ELSE h.\"regiser_no\" END AS \"regiser_no\",
			CASE WHEN c.\"astypeID\" = '10' THEN i.\"car_color\" ELSE j.\"car_color\" END AS \"car_color\",
			k.\"InsFullName\",
			a.\"Code\",
			a.\"StartDate\",
			a.\"EndDate\",
			a.\"Invest\",
			a.\"Premium\",
			a.\"Discount\",
			a.\"CollectCus\",
			a.\"TempInsID\",
			a.\"InsUser\",
			b.\"fullname\" AS \"doerName\",
			a.\"doerStamp\",
			a.\"editTime\",
			a.\"Kind\",
			l.\"fullname\" AS \"appvName\",
			a.\"appvStamp\",
			a.\"appvStatus\",
			a.\"appvNote\",
			a.\"InsID\",
			a.\"InsDate\",
			a.\"NetPremium\"
		FROM
			insure.\"thcap_InsureUnforce_request\" a
		LEFT JOIN
			\"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
		LEFT JOIN
			\"thcap_asset_biz_detail\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_astype\" d ON c.\"astypeID\" = d.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" e ON c.\"brand\" = e.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" f ON c.\"model\" = f.\"modelID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" g ON c.\"assetDetailID\" = g.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" h ON c.\"assetDetailID\" = h.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10_color\" i ON g.\"car_color\" = i.\"auto_id\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car_color\" j ON h.\"car_color\" = j.\"auto_id\"
		LEFT JOIN
			\"insure\".\"InsureInfo\" k ON a.\"Company\" = k.\"InsCompany\"
		LEFT JOIN
			\"Vfuser\" l ON a.\"appvID\" = l.\"id_user\"
		WHERE
			a.\"requestUnforceID\" = '$requestID'
	";
$qr = pg_query($q);
$astypeName = pg_fetch_result($qr,0); // ประเภทสินค้า
$brand_name = pg_fetch_result($qr,1); // ยี่ห้อ
$model_name = pg_fetch_result($qr,2); // รุ่น
$chassis = pg_fetch_result($qr,3); // เลขตัวถัง
$engine = pg_fetch_result($qr,4); // เลขตัวเครื่อง
$regiser_no = pg_fetch_result($qr,5); // ทะเบียนรถ
$car_color = pg_fetch_result($qr,6); // สีรถ
$InsFullName = pg_fetch_result($qr,7); // บริษัทประกัน
$Code = pg_fetch_result($qr,8); // ประเภท
$StartDate = pg_fetch_result($qr,9); // วันที่เริ่ม
$EndDate = pg_fetch_result($qr,10); // วันที่หมดอายุ
$Invest = pg_fetch_result($qr,11); // ทุนประกัน
$Premium = pg_fetch_result($qr,12); // ค่าเบี้ยประกัน
$Discount = pg_fetch_result($qr,13); // ส่วนลด
$CollectCus = pg_fetch_result($qr,14); // เบี้ยที่เก็บลูกค้า
$TempInsID = pg_fetch_result($qr,15); // เลขรับแจ้ง
$InsUser = pg_fetch_result($qr,16); // ผู้รับแจ้ง
$doerName = pg_fetch_result($qr,17); // ชื่อผู้ทำรายการ
$doerStamp = pg_fetch_result($qr,18); // วันเวลาที่ทำรายการ
$editTime = pg_fetch_result($qr,19); // เบี้ยที่เก็บกับลูกค้า
$Kind = pg_fetch_result($qr,20); // ประเภทประกัน
$appvName = pg_fetch_result($qr,21); // ชื่อผู้อนุมัติ
$appvStamp = pg_fetch_result($qr,22); // วันเวลาที่อนุมัติ
$appvStatus = pg_fetch_result($qr,23); // ผลการอนุมัติ
$appvNote = pg_fetch_result($qr,24); // หมายเหตุการอนุมัติ
$InsID = pg_fetch_result($qr,25); // เลขที่ของกรมธรรม์
$InsDate = pg_fetch_result($qr,26); // วันที่รับกรมธรรม์
$NetPremium = pg_fetch_result($qr,27); // เบี้ยสุทธิ

if($editTime == "0")
{
	$editText = "ขอเพิ่มข้อมูล";
}
else
{
	$editText = "ขอแก้ไขครั้ง $editTime";
}

// ผลการอนุมัติ
if($appvStatus == "0")
{
	$appvStatusText = "<font color=\"red\">ไม่อนุมัติ</font>";
}
elseif($appvStatus == "1")
{
	$appvStatusText = "<font color=\"green\">อนุมัติ</font>";
}
elseif($appvStatus == "9")
{
	$appvStatusText = "รออนุมัติ";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติ ประกันภัย ภาคสมัครใจ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<script type="text/javascript">		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		
		function validate()
		{
			var theMessage = "";
			var noErrors = theMessage;

			if (document.insureunforce.appvNote.value == "") {
				theMessage = theMessage + "\n - กรุณาระบุ หมายเหตุการอนุมัติ";
			}

			// If no errors, submit the form
			if (theMessage == noErrors){
				return true;
			}else{
				// If errors were found, show alert message
				alert(theMessage);
				return false;
			}
		}
	</script>
 
</head>
<body>
	<center>
		<div style="text-align:center;"><h2>อนุมัติ ประกันภัย ภาคสมัครใจ</h2></div>
		
		<form id="insureunforce" name="insureunforce" method="post" action="process_unforce_approve.php" onsubmit="return validate(this)">
			<table>
				<tr align="left">
					<td><b>ประเภทรถ</b></td>
					<td class="text_gray"><?php echo $astypeName; ?></td>
				</tr>
				<tr align="left">
					<td><b>ยี่ห้อ</b></td>
					<td class="text_gray"><?php echo $brand_name; ?></td>
				</tr>
				<tr align="left">
					<td><b>รุ่น</b></td>
					<td class="text_gray"><?php echo $model_name; ?></td>
				</tr>
				<tr align="left">
					<td><b>เลขถัง</b></td>
					<td class="text_gray"><?php echo $chassis; ?></td>
				</tr>
				<tr align="left">
					<td><b>เลขเครื่อง</b></td>
					<td class="text_gray"><?php echo $engine; ?></td>
				</tr>
				<tr align="left">
					<td><b>ทะเบียนรถ</b></td>
					<td class="text_gray"><?php echo $regiser_no; ?></td>
				</tr>
				<tr align="left">
					<td><b>สีรถ</b></td>
					<td class="text_gray"><?php echo $car_color; ?></td>
				</tr>
				<tr align="left">
					<td><b>บริษัทประกัน</b></td>
					<td><?php echo $InsFullName; ?></td>
				</tr>
				<tr align="left">
					<td><b>รหัสประเภทรถ</b></td>
					<td><?php echo $Code; ?></td>
				</tr>
				<tr align="left">
					<td><b>ประเภทประกัน</b></td>
					<td><?php echo $Kind; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันที่เริ่ม</b></td>
					<td><?php echo $StartDate; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันสิ้นสุด</b></td>
					<td><?php echo $EndDate; ?></td>
				</tr>
				<tr align="left">
					<td><b>ทุนประกัน</b></td>
					<td><input type="text" readonly="true" size="15" value="<?php echo number_format($Invest,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ค่าเบี้ยประกัน</b></td>
					<td><input type="text" readonly="true" size="15" value="<?php echo number_format($Premium,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ส่วนลด</b></td>
					<td><input type="text" readonly="true" size="15" value="<?php echo number_format($Discount,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>เบี้ยที่เก็บลูกค้า</b></td>
					<td><input type="text" readonly="true" size="15" value="<?php echo number_format($CollectCus,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>เลขรับแจ้ง</b></td>
					<td><?php echo $TempInsID; ?></td>
				</tr>
				<tr align="left">
					<td><b>ผู้รับแจ้ง</b></td>
					<td><?php echo $InsUser; ?></td>
				</tr>
				<?php
				if($editTime > 0 && ($InsID != "" || $InsDate != "" || $NetPremium != ""))
				{
				?>
					<tr align="left">
						<td><b>เลขกรมธรรม์</b></td>
						<td><?php echo $InsID; ?></td>
					</tr>
					<tr align="left">
						<td><b>วันที่รับกรมธรรม์</b></td>
						<td><?php echo $InsDate; ?></td>
					</tr>
					<tr align="left">
						<td><b>เบี้ยสุทธิ</b></td>
						<td><input type="text" readonly="true" size="15" value="<?php if($NetPremium != ""){echo number_format($NetPremium,2);} ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
					</tr>
				<?php
				}
				?>
				<tr align="left">
					<td><b>ผู้ทำรายการ</b></td>
					<td><?php echo $doerName; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันเวลาที่ทำรายการ</b></td>
					<td><?php echo $doerStamp; ?></td>
				</tr>
				<tr align="left">
					<td><b>ประเภทการทำรายการ</b></td>
					<td><?php echo $editText; ?></td>
				</tr>
				<tr align="left">
					<td><b>ผู้อนุมัติ</b></td>
					<td><?php echo $appvName; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันเวลาที่อนุมัติ</b></td>
					<td><?php echo $appvStamp; ?></td>
				</tr>
				<tr align="left">
					<td><b>ผลการอนุมัติ</b></td>
					<td><?php echo $appvStatusText; ?></td>
				</tr>
				<tr align="left">
					<td valign="top"><b>หมายเหตุการอนุมัติ</b></td>
					<td><textarea id="appvNote" name="appvNote" readOnly><?php echo $appvNote; ?></textarea></td>
				</tr>
			</table>
			
			<table>
				<tr align="center">
					<td align="center">
						<br/>
						<input type="button" value="ปิด" style="cursor:pointer;" onClick="window.close();" />
					</td>
				</tr>
			</table>
		</form>
	</center>
</body>
</html>