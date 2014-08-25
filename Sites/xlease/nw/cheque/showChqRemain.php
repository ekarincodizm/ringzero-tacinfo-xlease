<?php
include("../../config/config.php");
$BAccount=pg_escape_string($_GET["BAccount"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แสดงรายละเอียดเช็คคงเหลือ</title>
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
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>* แสดงรายละเอียดเช็คคงเหลือ *</h2></div>       
			<div style="clear:both;"></div>
				<div align="center">
					<div class="ui-widget">
						<?php
							//หารายละเอียดของธนาคาร
							$qryreport=pg_query("select \"BName\", \"BBranch\", \"BCompany\" from \"BankInt\" where \"BAccount\"='$BAccount'");
							$result=pg_fetch_array($qryreport);
							list($BName,$BBranch,$BCompany)=$result;
						?>
						<div style="padding-top:20px;">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#CECECE">
						<tr style="font-weight:bold;" valign="top" bgcolor="#FFFFFF">
							<td colspan="2">
								ชื่อบริษัท : <?php echo $BCompany?> <br>
								เลขที่บัญชี : <?php echo $BAccount?><br>
								ชื่อธนาคาร : <?php echo $BName?>  สาขา : <?php echo $BBranch?></td>
						</tr>
						<tr>
							<td colspan="2" align="center" bgcolor="#FFFFFF"><br>
								<table width="600" border="0" cellSpacing="1" cellPadding="3" bgcolor="#CECECE">
								<tr><td colspan="2" bgcolor="#FFFFFF" align="right"><a href="pdf_chqRemain.php?BAccount=<?php echo $BAccount?>" target="_blank"><u><span>พิมพ์ทุกรายการ</span></u></a></td></tr>
								<tr style="font-weight:bold;color:#FFFFFF" valign="top" bgcolor="#026F38" align="center">
									<th width="50">เล่มที่</th>
									<th width="150">เลขที่เช็ค</th>
								</tr>
								<?php
								//หารายการคงเหลือทั้งหมดของเช็คเลขที่ที่เลือก
								$qrychq=pg_query("select chequebook,\"chequeNum\" from cheque_order a
								left join cheque_detail b on a.\"detailID\"=b.\"detailID\"
								where a.\"stscheque\"='FALSE' and \"BAccount\"='$BAccount' order by \"chequebook\",\"chequeNum\"");
								$nubchq=pg_num_rows($qrychq);
								
								$i=0;
								$nub=0;
								$sumchq = 0;
								$allsum=0;
								$chequeold="";
								while($reschq=pg_fetch_array($qrychq)){
									$nub+=1;
									list($chequebook,$chequeNum)=$reschq;
									
									if($chequeold!=$chequebook){
										$i+=1;
									}
									
									if($i%2==0){
										$color="#D6FEEA";
									}else{
										$color="#F2FFF9";
									}
									//ถ้าเล่มของเช็คไม่เหมือนกัน ให้แสดงรวมรายการในบรรทัดสุดท้าย
									if(($chequeold != $chequebook) && $nub != 1){
										echo "<tr bgcolor=#CCFFCC>
											<td align=right><b>เหลือในเล่ม</b></td><td align=center><b>$sumchq ใบ</b>
											<div style=\"float:right\"><a href=\"pdf_onlychqRemain.php?BAccount=$BAccount&chequebook=$chequeold\" target=\"_blank\"><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\" border=\"0\" title=\"พิมพ์รายการนี้\" style=\"cursor:pointer;\"></a></div>
											<div style=\"clear:both;\"></div>
											</td>
										</tr>";
										$sumchq = 0;
									}
									
									if($chequeold==$chequebook){
										$chequebook2="";
									}else{
										$chequebook2=$chequebook;
									}
									echo "
										<tr bgcolor=$color align=\"center\">
											<td><b>$chequebook2</b></td>
											<td>$chequeNum</td>
										</tr>
									";
									$chequeold=$chequebook;	
									$sumchq++;		
									$allsum++;										
								}
								echo "<tr bgcolor=#CCFFCC>
										<td align=right><b>เหลือในเล่ม</b></td><td align=center><b>$sumchq ใบ</b>
										<div style=\"float:right\"><a href=\"pdf_onlychqRemain.php?BAccount=$BAccount&chequebook=$chequeold\" target=\"_blank\"><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\" border=\"0\" title=\"พิมพ์รายการนี้\" style=\"cursor:pointer;\"></a></div>
										<div style=\"clear:both;\"></div>
										</td>
									</tr>";
								echo "<tr bgcolor=#A0FCEA>
									<td align=right><b>รวมเหลือทั้งหมด</b></td><td align=center><b>$allsum ใบ</b>
									</td>
								</tr>";
								if($nubchq==0){
									echo "<tr bgcolor=#FFFFFF height=50 align=center><td colspan=2>-ไม่พบรายการเช็คคงเหลือ-</td></tr>";
								}
								?>
								</table>
								<div style="text-align:center;padding:20px;"><input type="button" value="  ปิด  " onclick="window.close();"></div>
							</td>
						</tr>
						</table>
					</div>
				</div>
        </td>
    </tr>
</table>
</form>
</body>
</html>