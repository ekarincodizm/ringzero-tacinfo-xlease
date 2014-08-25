<?php
if($contractID ==""){
include("../../config/config.php");
$contractID = pg_escape_string($_GET["idno"]);
}
$pathreceipt = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments/');
$pathreceipt1 = redirect($_SERVER['PHP_SELF'],'nw/manageCustomer/');
$pathreceipt2 = redirect($_SERVER['PHP_SELF'],'nw/corporation/');
//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk=pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$contractID'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}

$qry_namemain=pg_query("select \"thcap_fullname\", \"A_MOBILE\", \"A_TELEPHONE\"
						from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
	$miscellaneous_code=trim($resnamemain["miscellaneous_code"]);
}

$customer_mobile=trim($resnamemain["A_MOBILE"]); // เบอร์มือถือ
	$customer_tel=trim($resnamemain["A_TELEPHONE"]); // เบอร์บ้าน
	if($customer_tel == "0"){$customer_tel = "ไม่ระบุ";}



$qry_add=pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\"
where  \"contractID\" = '$contractID'");
if($resadd=pg_fetch_array($qry_add)){
	$address=trim($resadd["thcap_address"]);
}

// หาประเภทสัญญา
$qry_creditType = pg_query("select \"thcap_get_creditType\"('$contractID')");
$creditType = pg_fetch_result($qry_creditType,0);

// หาที่อยู่ส่งเอกสารตามสัญญา
if($creditType == "LOAN" || $creditType == "JOINT_VENTURE" || $creditType == "PERSONAL_LOAN")
{
	$qry_sentaddress = pg_query("select sentaddress from \"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
	$sentaddress = pg_fetch_result($qry_sentaddress,0);
}
else
{
	$qry_sentaddress = pg_query("select sentaddress from \"thcap_lease_contract\" where \"contractID\" = '$contractID' ");
	$sentaddress = pg_fetch_result($qry_sentaddress,0);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding-bottom: 3px;
    text-align: right;
}
</style>

</head>

<body>
<?php if($otherpage != 'true'){ ?>
<div class="title_top">ข้อมูลที่ติดต่อ</div>

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="<?php echo $bgcolor; ?>"  align="center">
<tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="left" valign="middle">
    <td colspan=2>เลขที่สัญญา : <?php echo $contractID; ?></td>
</tr>
</table>

<fieldset><legend><B><font color="red">ที่อยู่ส่งเอกสารตามสัญญา</font></B></legend>
	<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="<?php echo $bgcolor; ?>"  align="center">
		<tr>
			<td align="right" valign="top" width="150"><b>ที่อยู่ส่งเอกสารตามสัญญา : </b></td>
			<td align="left" valign="top"><?php echo "$sentaddress"; ?></td>
		</tr>
	</table>
</fieldset>
<?php
}

$qry_name=pg_query("select \"CusID\", \"thcap_fullname\", \"N_IDCARD\", \"thcap_address\", \"relation\", \"email\", \"CusState\"
					from \"vthcap_ContactCus_detail\"
					where  \"contractID\" = '$contractID' order by \"CusState\", \"ranking\" ");
$numco=pg_num_rows($qry_name);
if($numco > 0)
{
	$i=1;
	$z=1;
	while($resco=pg_fetch_array($qry_name))
	{
			$CusID=trim($resco["CusID"]);
			$name2=trim($resco["thcap_fullname"]);
			$IDCARD=trim($resco["N_IDCARD"]); //เลขบัตรประชาชน
			//$customer_mobile=trim($resco["A_MOBILE"]); // เบอร์มือถือ
			//$customer_tel=trim($resco["A_TELEPHONE"]); // เบอร์บ้าน
			//if($customer_tel == "0"){$customer_tel = "ไม่ระบุ";}
			$address=trim($resco["thcap_address"]);
			if($address == ""){$address = "ไม่ระบุ";}
			$typetext = trim($resco["relation"]);
			$email = trim($resco["email"]); // e-mail

			//หาเบอร์บ้าน
			$qrytel=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$CusID' and phonetype='1' order by \"doerStamp\" DESC limit 1");
			list($customer_tel)=pg_fetch_array($qrytel);
			if($customer_tel == "0"){$customer_tel = "ไม่ระบุ";}
			
			//หาเบอร์มือถือ
			$qrymobile=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$CusID' and phonetype='2' order by \"doerStamp\" DESC limit 1");
			list($customer_mobile)=pg_fetch_array($qrymobile);
			if($customer_mobile == "0"){$customer_mobile = "ไม่ระบุ";}
			
			//หาเบอร์โทรสาร
			$qryfax=pg_query("select phonenum from ta_phonenumber where \"CusID\"='$CusID' and phonetype='3' order by \"doerStamp\" DESC limit 1");
			list($customer_fax)=pg_fetch_array($qryfax);
			if($customer_fax == "0"){$customer_fax = "ไม่ระบุ";}
			
			//หา regis
			$qry_chkCorp = pg_query("select \"corpID\",\"corp_regis\" from \"th_corp\" left join \"VSearchCusCorp\" on \"corpID\"::text = \"CusID\" where \"CusID\" = '$CusID' ");
			while($resco1=pg_fetch_array($qry_chkCorp))
			{
			$corp_regis=trim($resco1["corp_regis"]);
			}
			
			// ตรวจสอบว่าเป็นลูกค้านิติบุคคลหรือไม่
			$qry_chkCorp = pg_query("select \"corpID\" from \"th_corp\" where \"corpID\"::text = '$CusID' ");
			$row_chkCorp = pg_num_rows($qry_chkCorp);
			if($row_chkCorp > 0){$iCorp = "yes";}else{$iCorp = "no";}
?>
		<fieldset><legend><B><font color="blue">ข้อมูล<?php echo $typetext; ?> <?php if($resco["CusState"] == '1'){ ?>  คนที่ <?php echo $i; }else if($resco["CusState"] == '2'){ ?>  คนที่ <?php echo $z; }?></font></B></legend>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="<?php echo $bgcolor; ?>"  align="center">
				<tr>
					<td align="right" valign="top"><b>ที่อยู่ที่ติดต่อ : </b></td>
					<td align="left" valign="top"><?php echo "$address"; ?></td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>ชื่อ <?php echo $typetext; ?>   : </b></td>
					<td align="left" valign="top"><?php echo "$name2"." (".$IDCARD.") "; ?> 
						<img src="<?php echo $pathreceipt;?>images/detail.gif" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('<?php if($iCorp == "yes") { echo $pathreceipt2;?>frm_viewcorp_detail.php?corp_regis=<?php echo $corp_regis; }else{ echo $pathreceipt1;?>showdetail2.php?CusID=<?php echo $CusID;?>&type=2<?php } ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" width="115"><b>เบอร์โทรศัพท์มือถือ : </b></td>
					<td align="left" valign="top"><u><a href="#" onclick="javascript:popU('<?php echo $pathreceipt;?>frm_ShowPhone.php?CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$customer_mobile"; ?></a></u>
						<img src="<?php echo $pathreceipt;?>images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('<?php echo $pathreceipt;?>frm_AddPhone.php?CusID=<?php echo $CusID; ?>&type=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>เบอร์บ้าน : </b></td>
					<td align="left" valign="top"><u><a href="#" onclick="javascript:popU('<?php echo $pathreceipt;?>frm_ShowPhone.php?CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$customer_tel"; ?></a></u>
						<img src="<?php echo $pathreceipt;?>images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('<?php echo $pathreceipt;?>frm_AddPhone.php?CusID=<?php echo $CusID; ?>&type=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
					</td>
				</tr>
				<?php
				if($iCorp == "yes")
				{ // ถ้าเป็นลูกค้านิติบุคคล
				?>
					<tr>
						<td align="right" valign="top"><b>เบอร์โทรสาร : </b></td>
						<td align="left" valign="top"><u><a href="#" onclick="javascript:popU('<?php echo $pathreceipt;?>frm_ShowPhone.php?CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><?php echo "$customer_fax"; ?></a></u>
							<img src="<?php echo $pathreceipt;?>images/edit.png" width="16" height="16" style="cursor:pointer" onclick="javascript:popU('<?php echo $pathreceipt;?>frm_AddPhone.php?CusID=<?php echo $CusID; ?>&type=3','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
						</td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td align="right" valign="top"><b>e-mail : </b></td>
					<td align="left" valign="top"><?php echo $email; ?></td>
				</tr>
			</table>
		</fieldset>
<?php
		if($resco["CusState"] == '1'){ $i++; }
		if($resco["CusState"] == '2'){ $z++; }
	}
?>
	</fieldset>	
	
	
<?php
}
?>

</body>
</html>