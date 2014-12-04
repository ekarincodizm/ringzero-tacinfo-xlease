<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]); // เลขที่สัญญา
$assetDetailID = pg_escape_string($_GET["assetDetailID"]); // รหัส PK ของสินทรัพย์

// หารายละเอียดรถ
$q = "
		SELECT
			a.\"ForceID\",
			c.\"astypeName\",
			d.\"brand_name\",
			e.\"model_name\",
			CASE WHEN b.\"astypeID\" = '10' THEN f.\"motorcycle_no\" ELSE g.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN b.\"astypeID\" = '10' THEN b.\"productCode\" ELSE g.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN b.\"astypeID\" = '10' THEN f.\"regiser_no\" ELSE g.\"regiser_no\" END AS \"regiser_no\",
			CASE WHEN b.\"astypeID\" = '10' THEN h.\"car_color\" ELSE i.\"car_color\" END AS \"car_color\",
			a.\"Company\",
			a.\"Code\",
			a.\"StartDate\",
			a.\"EndDate\",
			a.\"Capacity\",
			a.\"Discount\"
		FROM
			insure.\"thcap_InsureForce\" a
		LEFT JOIN
			\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_astype\" c ON b.\"astypeID\" = c.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" e ON b.\"model\" = e.\"modelID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" f ON b.\"assetDetailID\" = f.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" g ON b.\"assetDetailID\" = g.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10_color\" h ON f.\"car_color\" = h.\"auto_id\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car_color\" i ON g.\"car_color\" = i.\"auto_id\"
		WHERE
			a.\"contractID\" = '$contractID' AND
			a.\"assetDetailID\" = '$assetDetailID' AND
			a.\"Cancel\" = FALSE
		ORDER BY \"ForceID\" DESC LIMIT 1
	";
$qr = pg_query($q);
$ForceID = pg_fetch_result($qr,0); // รหัสรายการ PK
$astypeName = pg_fetch_result($qr,1); // ประเภทสินค้า
$brand_name = pg_fetch_result($qr,2); // ยี่ห้อ
$model_name = pg_fetch_result($qr,3); // รุ่น
$chassis = pg_fetch_result($qr,4); // เลขตัวถัง
$engine = pg_fetch_result($qr,5); // เลขตัวเครื่อง
$regiser_no = pg_fetch_result($qr,6); // ทะเบียนรถ
$car_color = pg_fetch_result($qr,7); // สีรถ
$Company = pg_fetch_result($qr,8); // บริษัทประกัน
$Code = pg_fetch_result($qr,9); // ประเภท
$StartDate = pg_fetch_result($qr,10); // วันที่เริ่ม
$EndDate = pg_fetch_result($qr,11); // วันที่หมดอายุ
$Capacity = pg_fetch_result($qr,12); // ขนาดเครื่องยนต์ / น้ำหนักรวม (กก.) / จำนวนที่นั่ง
$Discount = pg_fetch_result($qr,13); // ส่วนลด
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไข ข้อมูล ประกันภัย ภาคบังคับ (พรบ.)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#date_start").datepicker({
				showOn: 'button',
				buttonImage: '../thcap/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
				
			});
			
			$("#date_end").datepicker({
				showOn: 'button',
				buttonImage: '../thcap/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
				
			});
		});
		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		
		function doCallAjax()
		{
			HttPRequest = false;
			if (window.XMLHttpRequest)
			{ // Mozilla, Safari,...
				HttPRequest = new XMLHttpRequest();
				if (HttPRequest.overrideMimeType)
				{
					HttPRequest.overrideMimeType('text/html');
				}
			}
			else if (window.ActiveXObject)
			{ // IE
				try
				{
					HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e)
				{
					try
					{
						HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch(e){}
				}
			} 

			if (!HttPRequest)
			{
				alert('Cannot create XMLHTTP instance');
				return false;
			}

			var url = '../../act/ajax_sum.php';
			var pmeters = 'code='+document.getElementById("code").value+'&date_start='+document.getElementById("date_start").value+'&date_end='+document.getElementById("date_end").value;
			HttPRequest.open('POST',url,true);

			HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HttPRequest.setRequestHeader("Content-length", pmeters.length);
			HttPRequest.setRequestHeader("Connection", "close");
			HttPRequest.send(pmeters);

			HttPRequest.onreadystatechange = function()
			{
				if(HttPRequest.readyState == 3)  // Loading Request
				{
					document.getElementById("mySum").value = "Now is Loading...";
				}

				if(HttPRequest.readyState == 4) // Return Request
				{
					var codetype=document.getElementById("code").value;
					if(codetype=="1.400" || codetype=="1.401" || codetype=="1.402" || codetype=="1.403" || codetype=="1.420" || codetype=="1.421")
					{
						document.getElementById('t1').style.display = 'none';
						document.getElementById('t2').style.display = '';
						document.getElementById('t3').style.display = 'none';
						document.getElementById('capa').value="0";
					}
					else if(codetype=="1.200" || codetype=="1.201" || codetype=="1.202" || codetype=="1.203")
					{
						document.getElementById('t1').style.display = 'none';
						document.getElementById('t2').style.display = 'none';
						document.getElementById('t3').style.display = '';
						document.getElementById('capa').value="0";
					}
					else
					{
						document.getElementById('t1').style.display = '';
						document.getElementById('t2').style.display = 'none';
						document.getElementById('t3').style.display = 'none';
					}

					document.getElementById("mySum").value = HttPRequest.responseText;
					document.getElementById("summary").value = parseFloat(parseFloat(document.getElementById("mySum").value)-parseFloat(document.getElementById("discount").value)).toFixed(2);
				}

			}
		}
		
		function fncChangeMoney()
		{
			var x = parseFloat(document.insureforce.mySum.value);
			var y = parseFloat(document.insureforce.discount.value);
			var sum = 0;
			sum = parseFloat(x) - parseFloat(y);
			document.insureforce.summary.value = parseFloat(sum).toFixed(2);
		}
		
		function validate()
		{
			var theMessage = "";
			var noErrors = theMessage;

			if (document.insureforce.company.value == "") {
				theMessage = theMessage + "\n - กรุณาเลือกบริษัทประกัน";       
			}
			if (document.insureforce.code.value == "") {
				theMessage = theMessage + "\n - กรุณาเลือกประเภท";       
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
		<div style="text-align:center;"><h2>แก้ไข ข้อมูล ประกันภัย ภาคบังคับ (พรบ.)</h2></div>
		
		<form id="insureforce" name="insureforce" method="post" action="process_force_edit.php" onsubmit="return validate(this)">
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
					<td><b>เลขตัวถัง</b></td>
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
					<td>
						<select name="company">
							<option value="">เลือก</option>
							<?php 
							$qry_inf=pg_query("select \"InsCompany\",\"InsFullName\" from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
							while($res_inf=pg_fetch_array($qry_inf))
							{
								$InsCompany = $res_inf["InsCompany"];
								$InsFullName = $res_inf["InsFullName"];
							?>          
								<option value="<?php echo "$InsCompany"; ?>" <?php if($InsCompany == $Company){echo "selected";} ?>><?php echo "$InsFullName"; ?></option>
							<?php 
							} 
							?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td><b>ประเภท</b></td>
					<td>
						<select name="code" id="code" onchange="JavaScript:doCallAjax();">
							<option value="">เลือก</option>
							<?php 
							$qry_inf=pg_query("select \"IFCode\" from \"insure\".\"RateInsForce\" ORDER BY \"IFCode\" ASC");
							while($res_inf=pg_fetch_array($qry_inf))
							{
								$IFCode = $res_inf["IFCode"];
							?>          
								<option value="<?php echo "$IFCode"; ?>" <?php if($IFCode == $Code){echo "selected";} ?>><?php echo "$IFCode"; ?></option>
							<?php 
							} 
							?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td><b>วันที่เริ่ม</b></td>
					<td>
						<input name="date_start" id="date_start" type="text" readonly="true" size="15" value="<?php echo $StartDate; ?>" onchange="JavaScript:fncChange(); JavaScript:doCallAjax();" style="text-align:center;"/>   
					</td>
				</tr>
				<tr align="left">
					<td><b>วันที่หมดอายุ</b></td>
					<td>
						<input name="date_end" id="date_end" type="text" readonly="true" size="15" value="<?php echo $EndDate; ?>" onchange="JavaScript:fncChangeStop(); JavaScript:doCallAjax();" style="text-align:center;"/>
					</td>
				</tr>
				<tr align="left">
					<td id="t1"><b>ขนาดเครื่องยนต์</b></td>
					<td id="t2" style="display: none;"><b>น้ำหนักรวม (กก.)</b></td>
					<td id="t3" style="display: none;"><b>จำนวนที่นั่ง</b></td>
					<td><input type="text" name="capa" id="capa" size="15" value="<?php echo $Capacity;?>" style="text-align:right;" autocomplete="off"></td>
				</tr>
				<tr align="left">
					<td><b>ส่วนลด</b></td>
					<td><input type="text" name="discount" id="discount" size="15" maxlength="10" style="text-align:right;" value="<?php echo $Discount; ?>" onkeyup="JavaScript:fncChangeMoney();" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ค่าเบิ้ยประกัน</b></td>
					<td class="text_gray"><input type="text" readonly="true" id="mySum" name="mySum" size="15" value="0.00" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> บาท.</td>
				</tr>
				<tr align="left">
					<td><b>เบี้ยที่เก็บกับลูกค้า</b></td>
					<td class="text_gray"><input type="text" readonly="true" id="summary" name="summary" size="15" value="0.00" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> บาท.</td>
				</tr>
				<tr align="left">
					<td colspan="2"><font color="red"><b>* กรุณาตรวจสอบยอดเงินให้ละเอียดก่อนนำไปใช้</b></font></td>
				</tr>
			</table>
			
			<table>
				<tr align="center">
					<td align="center">
						<br/>
						<input type="hidden" name="ForceID" value="<?php echo $ForceID; ?>" />
						<input type="hidden" name="contractID" value="<?php echo $contractID; ?>" />
						<input type="hidden" name="assetDetailID" value="<?php echo $assetDetailID; ?>" />
						<input type="submit" name="submit" value="ยืนยันการแก้ไข" style="cursor:pointer;" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="ยกเลิก/ปิด" style="cursor:pointer;" onClick="window.close();" />
					</td>
				</tr>
			</table>
		</form>
	</center>
</body>

<script>
	doCallAjax();
	fncChangeMoney();
</script>

</html>