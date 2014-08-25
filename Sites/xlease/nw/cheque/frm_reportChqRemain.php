<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานเช็คคงเหลือ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<form method="post" name="form1" action="#">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h1>รายงานเช็คคงเหลือ</h1></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานเช็คคงเหลือ</B></legend>
				<div align="center">
					<div class="ui-widget">
						<?php
							$qryreport=pg_query("select count(a.\"detailID\") as nub,a.\"BAccount\",\"BName\",\"BBranch\",\"BCompany\" from \"cheque_detail\" a
							left join \"BankInt\" b on a.\"BAccount\"=b.\"BAccount\"
							where a.\"detailID\" IN (select \"detailID\" from \"cheque_order\" where stscheque='FALSE' and \"isChq\"='1')
							group by a.\"BAccount\",\"BName\",\"BBranch\",\"BCompany\"");
							$numreport=pg_num_rows($qryreport);
						?>
						<div>
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#CECECE">
						<tr><td colspan="5" bgcolor="#FFFFFF" align="right"><a href="pdf_allchqRemain.php" target="_blank"><u><span>พิมพ์ทุกรายการ</span></u></a></td></tr>
						<tr style="font-weight:bold;color:#FFFFFF" valign="top" bgcolor="#026F38" align="center">
							<th width="180">ชื่อบริษัท</th>
							<th width="150">เลขที่บัญชี</th>
							<th width="150">ธนาคาร</th>
							<th width="150">สาขา</th>
							<th>เช็คคงเหลือ(เล่ม)</th>
						</tr>
						<?php
						$i=0;
						$allsum=0;
						while($result=pg_fetch_array($qryreport)){
							list($nub,$BAccount,$BName,$BBranch,$BCompany)=$result;
							
							//นับว่าแต่ละรายการมีเช็คคงเหลือทั้งหมดกี่ใบ
							$qrynubchq=pg_query("select count(\"chequeNum\") as nubchq from cheque_order a
							left join cheque_detail b on a.\"detailID\"=b.\"detailID\"
							where a.\"stscheque\"='FALSE' and \"BAccount\"='$BAccount'");
							$res=pg_fetch_array($qrynubchq);
							list($nubchq)=$res;
							
							$i+=1;
							if($i%2==0){
								echo "<tr bgcolor=#D6FEEA align=\"left\">";
							}else{
								echo "<tr bgcolor=#FFFFFF align=\"left\">";
							}
							
							echo "
								<td>$BCompany</td>
								<td align=center>$BAccount</td>
								<td>$BName</td>
								<td>$BBranch</td>
								<td align=center><span onclick=\"javascript:popU('showChqRemain.php?BAccount=$BAccount','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=700')\" style=\"cursor:pointer;color:blue;font-weight:bold;\" title=\"แสดงรายละเอียด\"><u>$nub</u></span> ($nubchq ใบ)</td>
								</tr>
							";	
							$allsum=$allsum+$nub;
						}
						
						if($numreport==0){
							echo "<tr bgcolor=#FFFFFF height=50 align=center><td colspan=5>-ไม่พบรายการเช็คคงเหลือ-</td></tr>";
						}else{
							echo "<tr bgcolor=#A0FCEA height=25 align=center><td colspan=4 align=right><b>รวมเล่มทั้งหมด</b></td><td><b>$allsum เล่ม</b></td></tr>";
						}
						?>
						</table>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>
</body>
</html>