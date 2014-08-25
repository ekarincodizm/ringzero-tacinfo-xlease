<?php
include("../../config/config.php");

$contractID = $_GET['contractID'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>รายละเอียดการจ่ายเช็คล่วงหน้า</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />

<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center">       
    <fieldset style="width:1000px;">
    	<legend><B>รายละเอียดการจ่ายเช็คล่วงหน้า</B></legend>
        <?php
		$qc = "SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0'";
		$qrc = pg_query($qc);
		$cusID = "";
		$cusName = "";
		if($qrc)
		{
			$rowc = pg_num_rows($qrc);
			if($rowc!=0)
			{
				while($rsc = pg_fetch_array($qrc))
				{
					$cusID = $rsc['CusID'];
					$cusName = $rsc['thcap_fullname'];
				}
			}
			else
			{
				$cusName = "ไม่มีข้อมูล";
			}
		}
		?>
        <div style="margin-top:15px; margin-bottom:15px; text-align:center; font-size:14px; font-weight:bold;">
        	เลขที่สัญญา : <a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ"><font color="red"><?php echo $contractID; ?></font></a> || ชื่อลูกค้า : <a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า"><font color="red"><?php echo $cusName; ?></font></a>
        </div>
        <table width="960" border="0" cellpadding="5" cellspacing="1">
        	<tr bgcolor="#79BCFF">
            	<th>เลขที่เช็ค</th>
                <th>วันที่บนเช็ค</th>
                <th>ธนาคารที่ออกเช็ค</th>
                <th>จ่ายบริษัท</th>
                <th>ยอดเช็ค</th>
                <th>ผู้นำเช็คเข้า</th>
                <th>ธนาคารที่นำเข้า</th>
				<th>เลขที่บัญชีที่นำเข้า</th>
            </tr>
            <?php
			$i = 0;
			$q = "	SELECT 		a.*,b.\"bankName\" 
					FROM 		finance.\"thcap_receive_cheque\" a 
					LEFT JOIN	\"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\" 
					WHERE 		a.\"revChqToCCID\"='$contractID' and a.\"isPostChq\"='1' and \"revChqStatus\" NOT IN ('4','9')
					ORDER BY 	a.\"bankChqDate\",a.\"bankChqNo\"
				";
			$qr = pg_query($q);
			if($qr)
			{
				$row = pg_num_rows($qr);
				if($row!=0)
				{
					while($rs = pg_fetch_array($qr))
					{
						$revChqID = $rs['revChqID'];
						$bankChqNo = $rs['bankChqNo'];
						$bankChqDate = substr($rs['bankChqDate'],0,10);
						$bankOutID = $rs['bankOutID'];
						$bankName = $rs['bankName'];
						$bankChqToCompID = $rs['bankChqToCompID'];
						$bankChqAmt = $rs['bankChqAmt'];
						
						$q1 = "select \"giveTakerID\",\"BID\" from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID'";
						$qr1 = pg_query($q1);
						$rs1 = pg_fetch_array($qr1);
						$giveTakerID = $rs1['giveTakerID'];
						//$giveTakerToBankAcc = $rs1['giveTakerToBankAcc']; นำออกเนื่องจากมีการใช้ BID แล้ว
						$BID = $rs1['BID'];
						
						if($BID!=""){
							$qry_ourbank = pg_query("SELECT b.\"bankName\",a.\"BAccount\" FROM \"BankInt\" a left join \"BankProfile\" b on a.\"BName\"=b.\"bankID\" where \"BID\" = '$BID'");
							list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
						}
						
						$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
						list($userfullname) = pg_fetch_array($qry_username);
						
						if($i%2==0)
						{
							echo "<tr class=\"odd\">";
						}
						else
						{
							echo "<tr class=\"even\">";
						}
						echo "
							<td align=\"center\"><u><a onclick=\"javascript:popU('Channel_detail_chq.php?revChqID=$revChqID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=550')\" style=\"cursor:pointer\">$bankChqNo</u></td>
							<td align=\"center\">$bankChqDate</td>
							<td align=\"center\">$bankName</td>
							<td align=\"center\">$bankChqToCompID</td>
							<td align=\"center\">".number_format($bankChqAmt,2,".",",")."</td>
							<td align=\"center\">$userfullname</td>
							<td align=\"center\">$ourbankname</td>
							<td align=\"center\">$BAccount</td>
							</tr>
						";
						
						unset($ourbankname);
						unset($userfullname);
						unset($BAccount);
					}
				}
				else
				{
					echo "
						<tr class=\"odd\">
							<td colspan=\"8\" style=\"text-align:center; font-size:12px; color:#333;\">************************************** ไม่มีข้อมูล **************************************</td>
						</tr>
					";
				}
			}
			?>
        </table>
    </fieldset>
</div>
</body>
</html>