<?php
include("../../config/config.php");

$nowDate = nowDate();
$next_year_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));

$contractID = pg_escape_string($_GET["contractID"]); // เลขที่สัญญา
$assetDetailID = pg_escape_string($_GET["assetDetailID"]); // รหัส PK ของสินทรัพย์

// หารายละเอียดรถ
$q = "
		SELECT
			b.\"astypeName\",
			c.\"brand_name\",
			d.\"model_name\",
			CASE WHEN a.\"astypeID\" = '10' THEN e.\"motorcycle_no\" ELSE f.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN a.\"astypeID\" = '10' THEN a.\"productCode\" ELSE f.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN a.\"astypeID\" = '10' THEN e.\"regiser_no\" ELSE f.\"regiser_no\" END AS \"regiser_no\",
			CASE WHEN a.\"astypeID\" = '10' THEN g.\"car_color\" ELSE h.\"car_color\" END AS \"car_color\"
		FROM
			\"thcap_asset_biz_detail\" a
		LEFT JOIN
			\"thcap_asset_biz_astype\" b ON a.\"astypeID\" = b.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" d ON a.\"model\" = d.\"modelID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" e ON a.\"assetDetailID\" = e.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" f ON a.\"assetDetailID\" = f.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10_color\" g ON e.\"car_color\" = g.\"auto_id\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car_color\" h ON f.\"car_color\" = h.\"auto_id\"
		WHERE
			a.\"assetDetailID\" = '$assetDetailID'
	";
$qr = pg_query($q);
$astypeName = pg_fetch_result($qr,0); // ประเภทสินค้า
$brand_name = pg_fetch_result($qr,1); // ยี่ห้อ
$model_name = pg_fetch_result($qr,2); // รุ่น
$chassis = pg_fetch_result($qr,3); // เลขตัวถัง
$engine = pg_fetch_result($qr,4); // เลขตัวเครื่อง
$regiser_no = pg_fetch_result($qr,5); // ทะเบียนรถ
$car_color = pg_fetch_result($qr,6); // สีรถ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>บันทึก ข้อมูล ประกันภัย ภาคสมัครใจ</title>
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
		
		function fncChange()
		{
			var StartDateF = new Date(document.insureforce.date_start.value);
			var StopDateF = new Date(document.insureforce.date_end.value);
			if (StopDateF <= StartDateF)
			{
				alert('วันที่เริ่ม ต้องน้อยกว่าวันที่หมดอายุ');
			}
			else
			{
				var myDate = new Date(document.insureforce.date_start.value);
				var date = myDate.getDate();
				if(date<10){date="0"+date;}
				var month = myDate.getMonth()+1;
				if(month<10){month="0"+month;}
				var year = myDate.getFullYear()+1;
				if(year < 1000){year+=1900;}
				document.insureforce.date_end.value = year+"-"+month+"-"+date;
			}
		}

		function fncChangeStop()
		{
			var StartDateF = new Date(document.insureforce.date_start.value);
			var StopDateF = new Date(document.insureforce.date_end.value);
			if (StopDateF <= StartDateF)
			{
				alert('วันที่เริ่ม ต้องน้อยกว่าวันที่หมดอายุ');
				var myDate = new Date(document.insureforce.date_start.value);
				var date = myDate.getDate()+1;
				if(date<10){date="0"+date;}
				var month = myDate.getMonth()+1;
				if(month<10){month="0"+month;}
				var year = myDate.getFullYear();
				if(year < 1000){year+=1900;}
				document.insureforce.date_end.value = year+"-"+month+"-"+date;
			}
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
			else if(window.ActiveXObject)
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

			if(!HttPRequest)
			{
				alert('Cannot create XMLHTTP instance');
				return false;
			}

			var url = '../../act/ajax_query.php';
			var pmeters = 'company='+document.getElementById("company").value;
			HttPRequest.open('POST',url,true);

			HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HttPRequest.setRequestHeader("Content-length", pmeters.length);
			HttPRequest.setRequestHeader("Connection", "close");
			HttPRequest.send(pmeters);

			HttPRequest.onreadystatechange = function()
			{
				if(HttPRequest.readyState == 3)  // Loading Request
				{
					document.getElementById("myShow").innerHTML = "Now is Loading...";
				}

				if(HttPRequest.readyState == 4) // Return Request
				{
					document.getElementById("myShow").innerHTML = HttPRequest.responseText;
				}
			}
		}
		
		function checkdata()
		{
			var f = document.insureforce;
			var errMsg = "";
			var objFocus="";
			var achars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			var bchars="0123456789.";

			if (f.company.value == "")
			{
				errMsg += "- บริษัทประกัน\n";
				if (!objFocus)
					objFocus = f.company;
			}
			
			if (f.code.value == "")
			{
				errMsg += "- รหัสประเภทรถ\n";
				if (!objFocus)
					objFocus = f.code;
			}

			if (f.invest.value == "")
			{
				errMsg += "- ทุนประกัน\n";
				if (!objFocus)
					objFocus = f.invest;
			}else if(f.invest.value.length != 0){ 
				for (var i=0;i<f.invest.value.length;i++){ 
					temp=f.invest.value.substring(i,i+1)
					if(bchars.indexOf(temp)==-1){ 
						errMsg += "ทุนประกัน ไม่ถูกต้อง โปรดกรอก (0-9)\n"; 
						if (!objFocus)
							objFocus = f.invest; break;
					}
				}
			}

			if (f.premium.value == "")
			{
				errMsg += "- ค่าเบี้ยประกัน\n";
				if (!objFocus)
					objFocus = f.premium;
			}else if(f.premium.value.length != 0){ 
				for (var i=0;i<f.premium.value.length;i++){ 
					temp=f.premium.value.substring(i,i+1)
					if(bchars.indexOf(temp)==-1){ 
						errMsg += "ค่าเบี้ยประกัน ไม่ถูกต้อง โปรดกรอก (0-9)\n"; 
						if (!objFocus)
							objFocus = f.premium; break;
					}
				}
			}

			if (f.discount.value == "")
			{
				errMsg += "- ส่วนลด\n";
				if (!objFocus)
					objFocus = f.discount;
			}else if(f.discount.value.length != 0){ 
				for (var i=0;i<f.discount.value.length;i++){ 
					temp=f.discount.value.substring(i,i+1)
					if(bchars.indexOf(temp)==-1){ 
						errMsg += "ส่วนลด ไม่ถูกต้อง โปรดกรอก (0-9)\n"; 
						if (!objFocus)
							objFocus = f.discount; break;
					}
				}
			}

			if (f.summary.value == "")
			{
				errMsg += "- เบี้ยที่เก็บลูกค้า\n";
				if (!objFocus)
					objFocus = f.summary;
			}

			if (f.tempinsid.value == "")
			{
				errMsg += "- เลขรับแจ้ง\n";
				if (!objFocus)
					objFocus = f.tempinsid;
			}
			
			if (f.insuser.value == "")
			{
				errMsg += "- ผู้รับแจ้ง\n";
				if (!objFocus)
					objFocus = f.insuser;
			}
			
			if (errMsg == "")
			{
				f.btnsubmit.disabled = 1;
				return true;
			}
			else
			{
				errMsg = "กรุณากรอก:\n" + errMsg;
				alert(errMsg);
				objFocus.focus();
				return false;
			}
		}
		
		function fncChangeMoney()
		{   
			var a = 0;
			var b = 0;
			var c;
			
			a = document.insureforce.premium.value;
			b = document.insureforce.discount.value;
			c = a - b;

			document.insureforce.summary.value = parseFloat(c).toFixed(2);
		}
	</script>
 
</head>
<body>
	<center>
		<div style="text-align:center;"><h2>บันทึก ข้อมูล ประกันภัย ภาคสมัครใจ</h2></div>
		
		<form id="insureforce" name="insureforce" method="post" action="process_unforce_save.php" onsubmit="return checkdata();">
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
						<select id="company" name="company" onChange="JavaScript:doCallAjax();">
							<option value="">เลือก</option>
							<?php 
							$qry_inf=pg_query("select \"InsCompany\",\"InsFullName\" from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
							while($res_inf=pg_fetch_array($qry_inf))
							{
								$InsCompany = $res_inf["InsCompany"];
								$InsFullName = $res_inf["InsFullName"];
							?>          
								<option value="<?php echo "$InsCompany"; ?>"><?php echo "$InsFullName"; ?></option>
							<?php 
							} 
							?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td><b>รหัสประเภทรถ</b></td>
					<td colspan="3"><input type="text" id="code" name="code" size="15" maxlength="50" autocomplete="off">      
					</td>
				</tr>
				<tr align="left">
					<td><b>ประเภทประกัน</b></td>
					<td>
						<span id="myShow">
							<select name="kind" id="kind">
							<?php
							$qry_inf1=pg_query("select \"CommCode\" from \"insure\".\"Commision\" WHERE \"InsCompany\" = '$tmp_com_id'  ORDER BY \"CommCode\" ASC");
							while($res_inf1=pg_fetch_array($qry_inf1)){
							$CommCode = $res_inf1["CommCode"];
							echo "<option value=\"$CommCode\">$CommCode</option>";
							}
							?>
							</select>
						</span>
					</td>
				</tr>
				<tr align="left">
					<td><b>วันที่เริ่ม</b></td>
					<td>
						<input onchange="JavaScript:fncChange();" name="date_start" id="date_start" type="text" readonly="true" size="15" value="<?php echo $nowDate; ?>"/ style="text-align:center;">
					</td>
				</tr>
				<tr align="left">
					<td><b>วันสิ้นสุด</b></td>
					<td>
						<input onchange="JavaScript:fncChangeStop();" name="date_end" id="date_end" type="text" readonly="true" size="15" value="<?php echo $next_year_date; ?>"/ style="text-align:center;">
					</td>
				</tr>
				<tr align="left">
					<td><b>ทุนประกัน</b></td>
					<td><input type="text" id="invest" name="invest" size="15" maxlength="10" style="text-align:right;" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ค่าเบี้ยประกัน</b></td>
					<td><input type="text" id="premium" name="premium" size="15" maxlength="10" style="text-align:right;" onkeyup="JavaScript:fncChangeMoney();" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ส่วนลด</b></td>
					<td><input type="text" id="discount" name="discount" size="15" maxlength="10" style="text-align:right;" value="0" onkeyup="JavaScript:fncChangeMoney();" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>เบี้ยที่เก็บลูกค้า</b></td>
					<td><input type="text" readonly="true" id="summary" name="collectcus" size="15" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>เลขรับแจ้ง</b></td>
					<td><input type="text" id="tempinsid" name="tempinsid" size="15" maxlength="25" autocomplete="off"></td>
				</tr>
				<tr align="left">
					<td><b>ผู้รับแจ้ง</b></td>
					<td><input type="text" id="insuser" name="insuser" size="30" maxlength="20" autocomplete="off"></td>
				</tr>
			</table>
			
			<table>
				<tr align="center">
					<td align="center">
						<br/>
						<input type="hidden" name="contractID" value="<?php echo $contractID; ?>" />
						<input type="hidden" name="assetDetailID" value="<?php echo $assetDetailID; ?>" />
						<input type="submit" name="submit" value="   บันทึก   " style="cursor:pointer;" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="ยกเลิก/ปิด" style="cursor:pointer;" onClick="window.close();" />
					</td>
				</tr>
			</table>
		</form>
	</center>
</body>
</html>