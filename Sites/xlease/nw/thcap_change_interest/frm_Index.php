<?php
include("../../config/config.php");

$contractID = $_GET["idno"];
if($contractID == ""){$contractID = $_POST["idno_text"];}

$nowday = nowDate(); // วันที่ปัจจุบัน

$id_user = $_SESSION["av_iduser"]; // id ของ user ที่กำลังใช้งานอยู่ในขณะนั้น
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

// หาสิทธิ์ของ user
$searchLevel = pg_query("select * from \"Vfuser\" where \"id_user\" = '$id_user' ");
while($leveluser = pg_fetch_array($searchLevel))
{
	$level_user = $leveluser["emplevel"];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ขอปรับอัตราดอกเบี้ย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#idno_text").autocomplete({
        //source: "s_idno.php",
		source: "s_idall.php",
        minLength:1
    });
	
	$("#actionDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function fncRemoveFile(number)
{ 
	var mySpan = document.getElementById('TextBoxGroup');
	var deleteDiv = document.getElementById("TextBoxDiv" + number);
	mySpan.removeChild(deleteDiv);
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm2.newRate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ อัตราดอกเบี้ยใหม่";
	}
	
	if (document.frm2.actionDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก วันที่เริ่มมีผล";
	}
	
	if (document.frm2.remark.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เหตุผลในการปรับอัตราดอกเบี้ย";
	}
	

	// If no errors, submit the form
	if (theMessage == noErrors){
		return true;
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
    
</head>
<body>

<center>
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both; text-align:center;"><h2>(THCAP) ขอปรับอัตราดอกเบี้ย</h2></div>

			<fieldset>
				<legend><B>ค้นหาเลขที่สัญญา</B></legend>
				<center>
				<div align="center" style="width:850px;" id="divmain">
					<div style="float:center; width:850px;">
					<form method="post" name="frm1" action="frm_Index.php">
						เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว : &nbsp
						<input type="text" name="idno_text" id="idno_text" value="<?php echo $contractID; ?>" size="70"> &nbsp
						<input type="submit" id="btnsearch" value="ค้นหา">
					</form>
					</div>
					<div style="clear:both;"></div>
					<div id="panel" align="left" style="margin-top:10px"></div>
				</div>
				</center>
			</fieldset>
			
			<?php
			if($contractID != "")
			{
			?>
				<br>
				<fieldset>
					<legend><B>ปรับอัตราดอกเบี้ย</B></legend>
					<center>
					<div align="center" style="width:850px;" id="divmain">
						<?php
							$qry_search_contract = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
							$numrows_contract = pg_num_rows($qry_search_contract);
							if($numrows_contract == 0)
							{
								echo "<h2><font color=\"#FF0000\">ไม่พบเลขที่สัญญา</font></h2>";
							}
							else
							{
								/*while($res = pg_fetch_array($qry_search_contract))
								{
									$oldRate = $res["conIntCurRate"];
								}*/
								
								// หาอัตราดอกเบี้ยปัจจุบัน
								$qry_oldRate = pg_query("select \"conIntCurRate\" from public.\"thcap_mg_contract_current\" where \"contractID\" = '$contractID'
														and \"rev\" = (select max(\"rev\") from \"thcap_mg_contract_current\" where \"contractID\" = '$contractID') ");
								$oldRate = pg_fetch_result($qry_oldRate,0);
						?>
								<form method="post" name="frm2" action="process_add_changeRateTemp.php" enctype="multipart/form-data">
								<div id='TextBoxGroup'>
								<table width="100%">
									<tr>
										<td align="right" width="50%">เลขที่สัญญา :</td>
										<td align="left">
											<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;">
												<font color="red"><u><?php echo $contractID; ?></u></font>
											</span>
										</td>
									</tr>
									<tr>
										<td align="right">อัตราดอกเบี้ยปัจจุบัน :</td>
										<td align="left"><?php echo $oldRate; ?></td>
										<input type="hidden" name="oldRate" value="<?php echo $oldRate; ?>">
									</tr>
									<tr>
										<td align="right">อัตราดอกเบี้ยใหม่ :</td>
										<td align="left"><input type="text" name="newRate" size="7"> <font color="#FF0000"><b>*</b></font></td>
									</tr>
									<tr>
										<td align="right">วันที่เริ่มมีผล :</td>
										<td align="left"><input type="text" name="actionDate" id="actionDate" style="text-align:center;" value="<?php echo $nowday; ?>" size="12"> <font color="#FF0000"><b>*</b></font></td>
									</tr>
									<tr>
										<td align="right">เวลาที่เริ่มมีผล :</td>
										<td align="left">
											ชั่วโมง:
											<select name="H_time">
												<?php
												for($h=0;$h<=23;$h++)
												{
													if(strlen($h) == 1)
													{
														$h = "0".$h;
													}
													echo "<option>$h</option>";
												}
												?>
											</select>
											นาที:
											<select name="M_time">
												<?php
												for($m=0;$m<=59;$m++)
												{
													if(strlen($m) == 1)
													{
														$m = "0".$m;
													}
													echo "<option>$m</option>";
												}
												?>
											</select>
											วินาที:
											<select name="S_time">
												<?php
												for($s=0;$s<=59;$s++)
												{
													if(strlen($s) == 1)
													{
														$s = "0".$s;
													}
													echo "<option>$s</option>";
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td align="right">เหตุผลในการปรับอัตราดอกเบี้ย :</td>
										<td align="left"><textarea name="remark"></textarea> <font color="#FF0000"><b>*</b></font></td>
									</tr>
									<tr>
										<td align="right">แนบไฟล์ scan ถ้ามี :</td>
										<td align="left"><input type="button" value="+ เพิ่ม" id="addButton"></td>
									</tr>
								</table>
								</div>
								<input type="hidden" name="contractID" value="<?php echo $contractID; ?>">
								<input type="hidden" name="rowsFile" id="rowsFile" value="0">
								<br><br><input type="submit" value="บันทึก" onclick="return validate();">
								</form>
						<?php
							}
						?>
					<br>
					</div>
					</center>
				</fieldset>
			<?php
			}
			?>
			
			<br>
			
			<fieldset>
				<legend><B>รายการที่อยู่ระหว่างการรออนุมัติ</B></legend>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th>รายการที่</th>
						<th>เลขที่สัญญา</th>
						<th>อัตราดอกเบี้ยปัจจุบัน</th>
						<th>อัตราดอกเบี้ยใหม่</th>
						<th>วันเวลาที่เริ่มมีผล</th>
						<th>ผู้ทำรายการขอปรับดอกเบี้ย</th>
						<th>วันเวลาที่ทำรายการขอปรับดอกเบี้ย</th>
						<th>รายละเอียด</th>
					</tr>
					<?php
					$query = pg_query("select * from public.\"thcap_changeRate_temp\" where \"Approved\" is null order by \"doerStamp\" ");
					$numrows = pg_num_rows($query);
					$i=0;
					while($result = pg_fetch_array($query))
					{
						$i++;
						$tempID = $result["tempID"];
						$contractID = $result["contractID"]; // เลขที่สัญญา
						$oldRate = $result["oldRate"]; // อัตราดอกเบี้ยปัจจุบัน
						$newRate = $result["newRate"]; // อัตราดอกเบี้ยใหม่
						$effectiveDate = $result["effectiveDate"]; // วันเวลาที่เริ่มมีผล
						$doerID = $result["doerID"]; // ผู้ทำรายการ
						$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
						
						$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
						while($result_name = pg_fetch_array($qry_name))
						{
							$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
						}
						
						if($i%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
						
						echo "<td align=\"center\">$i</td>";
						echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
										<u>$contractID</u></font></span></td>";
						echo "<td align=\"center\">$oldRate %</td>";
						echo "<td align=\"center\">$newRate %</td>";
						echo "<td align=\"center\">$effectiveDate</td>";
						echo "<td>$fullname</td>";
						echo "<td align=\"center\">$doerStamp</td>";
						echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_detail.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><img src=\"images/detail.gif\"></a></td>";
						echo "</tr>";
					}
					if($numrows==0){
						echo "<tr bgcolor=#FFFFFF height=20><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
					}else{
						//echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
					}
					?>
				</table>
			</fieldset>
			
			<br>
			
			<?php 
				$limit = "limit 30";
				include("frm_history.php");
			?>

		</td>
	</tr>
</table>
</center>

</body>

<script>
var counter;
counter = 0;
$('#addButton').click(function(){
	counter++;
	console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
	table = '<table width="100%">'
	+ '	<tr>'
	+ '		<td align="right" width="50%">ไฟล์แนบ :</td>'
	+ '		<td>'
	+ '			<input type="file" name="fileChangeRate[]" size="25" value="" /> <input type="button" value="ลบ" id="deleteFile'+ counter +'" onclick="fncRemoveFile('+ counter +')">'
	+ '		</td>'
	+ '	</tr>'
	+ '	</table>'
	
	newTextBoxDiv.html(table);
	newTextBoxDiv.appendTo("#TextBoxGroup");
	document.getElementById("rowsFile").value = counter;
});
</script>

</html>