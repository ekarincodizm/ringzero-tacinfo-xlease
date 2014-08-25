<?php
include("../../config/config.php");

$contractID = $_GET['contractID'];

$cur_path = redirect($_SERVER['PHP_SELF'],'nw/thcap');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>รายละเอียดเช็คที่ครบกำหนด</title>
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
    	<legend><B>รายละเอียดเช็คที่ครบกำหนด</B></legend>
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
                <th>วันที่ครบกำหนด</th>
                <th>ธนาคารที่ออกเช็ค</th>
                <th>จ่ายบริษัท</th>
                <th>ยอดเช็ค</th>
            </tr>
            <?php
			$cur_date = nowDateTime();
			$i = 0;
			$q = "	SELECT 		a.*,b.\"bankName\" 
					FROM 		finance.\"thcap_receive_cheque\" a 
					LEFT JOIN 	\"BankProfile\" b on a.\"bankOutID\" = b.\"bankID\" 
					WHERE 		a.\"revChqToCCID\"='$contractID' and a.\"bankChqDate\"<='$cur_date'
								and a.\"revChqID\" not in(select distinct b.\"revChqID\" from finance.thcap_receive_transfer b where b.\"revChqID\" is not null and b.\"revTranStatus\" = '3')
								and a.\"revChqID\" not in(select distinct c.\"revChqID\" from finance.thcap_receive_cheque_return c where c.\"revChqID\" is not null and c.\"statusChq\" = '1')
								and a.\"revChqStatus\" not in('0','3','4')
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
						
						
						if($i%2==0)
						{
							echo "<tr class=\"odd\">";
						}
						else
						{
							echo "<tr class=\"even\">";
						}
						echo "
							<td align=\"center\"><a style=\"cursor:pointer;\" onclick=\"javascipt:popU('$cur_path/frm_showcheque.php?revChqID=$revChqID&&bankChqNo=$bankChqNo','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>$bankChqNo</u></font></a></td>
							<td align=\"center\">$bankChqDate</td>
							<td align=\"center\">$bankName</td>
							<td align=\"center\">$bankChqToCompID</td>
							<td align=\"center\">".number_format($bankChqAmt,2,".",",")."</td>
							</tr>
						";
					}
				}
				else
				{
					echo "
						<tr class=\"odd\">
							<td colspan=\"5\" style=\"text-align:center; font-size:12px; color:#333;\">************************************** ไม่มีข้อมูล **************************************</td>
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