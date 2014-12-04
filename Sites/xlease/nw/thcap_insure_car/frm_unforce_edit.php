<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]); // เลขที่สัญญา
$assetDetailID = pg_escape_string($_GET["assetDetailID"]); // รหัส PK ของสินทรัพย์

// หารายละเอียดรถ
$q = "
		SELECT
			a.\"UnforceID\",
			c.\"astypeName\",
			d.\"brand_name\",
			e.\"model_name\",
			CASE WHEN b.\"astypeID\" = '10' THEN f.\"motorcycle_no\" ELSE g.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN b.\"astypeID\" = '10' THEN b.\"productCode\" ELSE g.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN b.\"astypeID\" = '10' THEN f.\"regiser_no\" ELSE g.\"regiser_no\" END AS \"regiser_no\",
			CASE WHEN b.\"astypeID\" = '10' THEN h.\"car_color\" ELSE i.\"car_color\" END AS \"car_color\",
			a.\"Company\",
			a.\"Code\",
			a.\"Kind\",
			a.\"StartDate\",
			a.\"EndDate\",
			a.\"Invest\",
			a.\"Premium\",
			a.\"Discount\",
			a.\"TempInsID\",
			a.\"InsUser\",
			a.\"InsID\",
			a.\"InsDate\",
			a.\"NetPremium\"
		FROM
			insure.\"thcap_InsureUnforce\" a
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
		ORDER BY \"UnforceID\" DESC LIMIT 1
	";
$qr = pg_query($q);
$UnforceID = pg_fetch_result($qr,0); // รหัส PK
$astypeName = pg_fetch_result($qr,1); // ประเภทสินค้า
$brand_name = pg_fetch_result($qr,2); // ยี่ห้อ
$model_name = pg_fetch_result($qr,3); // รุ่น
$chassis = pg_fetch_result($qr,4); // เลขตัวถัง
$engine = pg_fetch_result($qr,5); // เลขตัวเครื่อง
$regiser_no = pg_fetch_result($qr,6); // ทะเบียนรถ
$car_color = pg_fetch_result($qr,7); // สีรถ
$Company = pg_fetch_result($qr,8); // บริษัทประกัน
$Code = pg_fetch_result($qr,9); // รหัสประเภทรถ
$Kind = pg_fetch_result($qr,10); // ประเภทประกัน
$StartDate = pg_fetch_result($qr,11); // วันที่เริ่ม
$EndDate = pg_fetch_result($qr,12); // วันสิ้นสุด
$Invest = pg_fetch_result($qr,13); // ทุนประกัน
$Premium = pg_fetch_result($qr,14); // ค่าเบี้ยประกัน
$Discount = pg_fetch_result($qr,15); // ส่วนลด
$TempInsID = pg_fetch_result($qr,16); // เลขรับแจ้ง
$InsUser = pg_fetch_result($qr,17); // ผู้รับแจ้ง
$InsID = pg_fetch_result($qr,18); // เลขที่ของกรมธรรม์
$InsDate = pg_fetch_result($qr,19); // วันที่รับกรมธรรม์
$NetPremium = pg_fetch_result($qr,20); // เบี้ยสุทธิ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไข ข้อมูล ประกันภัย ภาคสมัครใจ</title>
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
			
			if(document.getElementById("receive_insurance").value == '1')
			{
				$("#InsDate").datepicker({
					showOn: 'button',
					buttonImage: '../thcap/images/calendar.gif',
					buttonImageOnly: true,
					changeMonth: true,
					changeYear: true,
					dateFormat: 'yy-mm-dd'
				});
			}
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
			
			if(f.receive_insurance.value == "1")
			{
				if (f.InsID.value == "")
				{
					errMsg += "- เลขกรมธรรม์\n";
					if (!objFocus)
						objFocus = f.InsID;
				}
				
				if (f.InsDate.value == "")
				{
					errMsg += "- วันที่รับกรมธรรม์\n";
				}
				
				if (f.NetPremium.value == "")
				{
					errMsg += "- เบี้ยสุทธิ\n";
					if (!objFocus)
						objFocus = f.NetPremium;
				}
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
		<div style="text-align:center;"><h2>แก้ไข ข้อมูล ประกันภัย ภาคสมัครใจ</h2></div>
		
		<form id="insureforce" name="insureforce" method="post" action="process_unforce_edit.php" onsubmit="return checkdata();">
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
								<option value="<?php echo "$InsCompany"; ?>" <?php if($InsCompany == $Company){echo "selected";} ?>><?php echo "$InsFullName"; ?></option>
							<?php 
							} 
							?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td><b>รหัสประเภทรถ</b></td>
					<td colspan="3"><input type="text" id="code" name="code" size="15" maxlength="50" value="<?php echo $Code; ?>" autocomplete="off"></td>
				</tr>
				<tr align="left">
					<td><b>ประเภทประกัน</b></td>
					<td>
						<span id="myShow">
							<select name="kind" id="kind">
							<?php
							$qry_inf1=pg_query("select \"CommCode\" from \"insure\".\"Commision\" WHERE \"InsCompany\" = '$tmp_com_id'  ORDER BY \"CommCode\" ASC");
							while($res_inf1=pg_fetch_array($qry_inf1))
							{
								$CommCode = $res_inf1["CommCode"];
								
								if($CommCode == $Kind){$kindSelect = "selected";}
								else{$kindSelect = "";}
								
								echo "<option value=\"$CommCode\" $kindSelect>$CommCode</option>";
							}
							?>
							</select>
						</span>
					</td>
				</tr>
				<tr align="left">
					<td><b>วันที่เริ่ม</b></td>
					<td>
						<input onchange="JavaScript:fncChange();" name="date_start" id="date_start" type="text" readonly="true" size="15" value="<?php echo $StartDate; ?>"/ style="text-align:center;">
					</td>
				</tr>
				<tr align="left">
					<td><b>วันสิ้นสุด</b></td>
					<td>
						<input onchange="JavaScript:fncChangeStop();" name="date_end" id="date_end" type="text" readonly="true" size="15" value="<?php echo $EndDate; ?>"/ style="text-align:center;">
					</td>
				</tr>
				<tr align="left">
					<td><b>ทุนประกัน</b></td>
					<td><input type="text" id="invest" name="invest" size="15" maxlength="10" style="text-align:right;" value="<?php echo $Invest; ?>" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ค่าเบี้ยประกัน</b></td>
					<td><input type="text" id="premium" name="premium" size="15" maxlength="10" style="text-align:right;" value="<?php echo $Premium; ?>" onkeyup="JavaScript:fncChangeMoney();" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ส่วนลด</b></td>
					<td><input type="text" id="discount" name="discount" size="15" maxlength="10" style="text-align:right;" value="<?php echo $Discount; ?>" onkeyup="JavaScript:fncChangeMoney();" autocomplete="off"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>เบี้ยที่เก็บลูกค้า</b></td>
					<td><input type="text" readonly="true" id="summary" name="collectcus" size="15" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>เลขรับแจ้ง</b></td>
					<td><input type="text" id="tempinsid" name="tempinsid" size="15" maxlength="25" value="<?php echo $TempInsID; ?>" autocomplete="off"></td>
				</tr>
				<tr align="left">
					<td><b>ผู้รับแจ้ง</b></td>
					<td><input type="text" id="insuser" name="insuser" size="30" maxlength="20" value="<?php echo $InsUser; ?>" autocomplete="off"></td>
				</tr>
				<?php
				if($InsID != "" || $InsDate != "" || $NetPremium != "")
				{
					echo "<input type=\"hidden\" id=\"receive_insurance\" name=\"receive_insurance\" value=\"1\" />";
				?>
					<tr align="left">
						<td><b>เลขกรมธรรม์</b></td>
						<td><input type="text" id="InsID" name="InsID" size="30" value="<?php echo $InsID;?>" autocomplete="off" /></td>
					</tr>
					<tr align="left">
						<td><b>วันที่รับกรมธรรม์</b></td>
						<td><input type="text" id="InsDate" name="InsDate" value="<?php echo $InsDate; ?>" readonly="true" size="15" style="text-align:center;" /></td>
					</tr>
					<tr align="left">
						<td><b>เบี้ยสุทธิ</b></td>
						<td><input type="text" id="NetPremium" name="NetPremium" size="15" maxlength="10" style="text-align:right;" value="<?php echo $NetPremium; ?>" onkeyup="JavaScript:fncChangeMoney();" autocomplete="off" /> <span class="text_gray">บาท.</span></td>
					</tr>
				<?php
				}
				else
				{
					echo "<input type=\"hidden\" id=\"receive_insurance\" name=\"receive_insurance\" value=\"0\" />";
				}
				?>
			</table>
			
			<table>
				<tr align="center">
					<td align="center">
						<br/>
						<input type="hidden" name="UnforceID" value="<?php echo $UnforceID; ?>" />
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
	setTimeout("document.getElementById('kind').value = '<?php echo $Kind; ?>';",1000);
</script>

</html>