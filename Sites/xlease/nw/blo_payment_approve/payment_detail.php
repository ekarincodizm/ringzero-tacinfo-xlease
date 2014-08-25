<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$receiptTempID = pg_escape_string($_GET["TempID"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(BLO) อนุมัติรับชำระเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.getElementById("noteAppv").value == ""){
		theMessage = theMessage + "\n -->  กรุณาระบุ หมายเหตุการอนุมัติ";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
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

<div class="header"><h1>(BLO) อนุมัติรับชำระเงิน</h1></div>

<?php
$qry_blo = pg_query("select * , \"receiptStamp\"::date as \"receiptDate\" , ta_array1d_count(\"costsID\"::character varying[]) as \"count_costsID\"
					from \"blo_receipt_temp\" where \"receiptTempID\" = '$receiptTempID' ");
while($res_blo = pg_fetch_array($qry_blo))
{
	$receiptTempID = $res_blo["receiptTempID"];
	$receiptDate = $res_blo["receiptDate"];
	$contractID = $res_blo["contractID"];
	$CusID = $res_blo["CusID"];
	$doerID = $res_blo["doerID"];
	$doerStamp = $res_blo["doerStamp"];
	$CusFullAddress = $res_blo["CusFullAddress"];
	$costsID = $res_blo["costsID"];
	$netAmt = $res_blo["netAmt"];
	$vatAmt = $res_blo["vatAmt"];
	$costsAmt = $res_blo["costsAmt"];
	$whtAmt = $res_blo["whtAmt"];
	$count_costsID = $res_blo["count_costsID"];
	
	// หาชื่อเต็มลูกค้า
	$qry_cus = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$CusID' ");
	$CusName = pg_result($qry_cus,0);
	
	// หาชื่อเต็มพนักงานที่ทำรายการ
	$qry_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
	$doerName = pg_result($qry_doer,0);
}
?>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right"><font color="#0000FF"><b>วันที่ชำระเงิน : </b></font></td>
						<td><?php echo $receiptDate; ?></td>
					</tr>
					<tr>
						<td align="right"><font color="#0000FF"><b>สัญญาเลขที่ : </b></font></td>
						<td><?php echo $contractID; ?></td>
					</tr>
					<tr>
						<td align="right"><font color="#0000FF"><b>ผู้ชำระเงิน : </b></font></td>
						<td><?php echo $CusName; ?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><font color="#0000FF"><b>ที่อยู่ : </b></font></td>
						<td align="left"><textarea name="address" id="address" cols="50" rows="3" readonly><?php echo $CusFullAddress; ?></textarea></td>
					</tr>
					<tr>
						<td align="right"><b><font color="#0000FF">ผู้ทำรายการ : </b></font></td>
						<td><?php echo $doerName; ?></td>
					</tr>
					<tr>
						<td align="right"><b><font color="#0000FF">วันเวลาที่ทำรายการ : </b></font></td>
						<td><?php echo $doerStamp; ?></td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
				
				<table id="tableDetail" align="center" width="90%" border="0" cellspacing="1" cellpadding="1" bgcolor="#000000">
					<tr align="center" bgcolor="#79BCFF">
						<th>NO.</th>
						<th>รายการ</th>
						<th>จำนวนเงิน</th>
						<th>ภาษีมูลค่าเพิ่ม</th>
						<th>รวมเงิน</th>
						<th>ภาษีหัก ณ ที่จ่าย</th>
					</tr>
				
				<?php
				$netAmt_sum = 0;
				$vatAmt_sum = 0;
				$costsAmt_sum = 0;
				$whtAmt_sum = 0;
				
				for($b=1; $b<=$count_costsID; $b++)
				{
					if($b%2==0){
						echo "<tr class=\"odd\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
					
					$bb = $b - 1;
					
					// หาชื่อค่าใช้จ่าย
					$qry_costsName = pg_query("SELECT \"costsName\" from \"blo_costs\" where \"costsID\" = (select ta_array1d_get('$costsID'::character varying[], '$bb', '1'))::integer");
					$costsName = pg_result($qry_costsName,0);
					
					// หาราคาก่อน vat
					$qry_netAmt_uni = pg_query("select ta_array1d_get('$netAmt'::character varying[], '$bb', '1')");
					$netAmt_uni = pg_result($qry_netAmt_uni,0);
					$netAmt_sum += $netAmt_uni;
					
					// หาราคา vat
					$qry_vatAmt_uni = pg_query("select ta_array1d_get('$vatAmt'::character varying[], '$bb', '1')");
					$vatAmt_uni = pg_result($qry_vatAmt_uni,0);
					$vatAmt_sum += $vatAmt_uni;
					
					// หาราคารวม vat
					$qry_costsAmt_uni = pg_query("select ta_array1d_get('$costsAmt'::character varying[], '$bb', '1')");
					$costsAmt_uni = pg_result($qry_costsAmt_uni,0);
					$costsAmt_sum += $costsAmt_uni;
					
					// หาราคา wht
					$qry_whtAmt_uni = pg_query("select ta_array1d_get('$whtAmt'::character varying[], '$bb', '1')");
					$whtAmt_uni = pg_result($qry_whtAmt_uni,0);
					$whtAmt_sum += $whtAmt_uni;
				?>
						<td><?php echo $b; ?></td>
						<td align="left"><?php echo $costsName; ?></td>
						<td align="right"><?php echo number_format($netAmt_uni,2); ?></td>
						<td align="right"><?php echo number_format($vatAmt_uni,2); ?></td>
						<td align="right"><?php echo number_format($costsAmt_uni,2); ?></td>
						<td align="right"><?php echo number_format($whtAmt_uni,2); ?></td>
					</tr>
				<?php
				}
				
				if($count_costsID > 0)
				{
				?>
					<tr bgcolor="#CCCCFF">
						<td align="right" colspan="2"><b>รวม</b></td>
						<td align="right"><b><?php echo number_format($netAmt_sum,2); ?></b></td>
						<td align="right"><b><?php echo number_format($vatAmt_sum,2); ?></b></td>
						<td align="right"><b><?php echo number_format($costsAmt_sum,2); ?></b></td>
						<td align="right"><b><?php echo number_format($whtAmt_sum,2); ?></b></td>
					</tr>
				<?php
				}
				else
				{
					echo "<tr><td colspan=\"6\">--- ไม่พบรายการ ---</td></tr>";
				}
				?>
				
				</table>
				
			</center>
			</fieldset>
			<br><br>
			
			<form method="post" action="process_approve.php">
				<input type="hidden" name="TempID" value="<?php echo $receiptTempID; ?>">
				
				<table>
					<tr>
						<td align="right"><b>หมายเหตุการอนุมัติ : </b></td>
						<td align="left"><textarea name="noteAppv" id="noteAppv" cols="40" rows="4"></textarea></td>
					</tr>
				</table>
				
				<br><br>
				
				<table>
					<tr>
						<td>
							<input type="submit" name="appv" id="appv" value="อนุมัติ" onclick="return validate();">
						</td>
						<td>
							<input type="submit" name="unappv" id="unappv" value="ไม่อนุมัติ" onclick="return validate();">
						</td>
						<td>
							<input type="button" value="ออก" onClick="RefreshMe();">
						</td>
					</tr>
				</table>
			</form>
			
		</td>
	</tr>
</table>

</body>
</html>