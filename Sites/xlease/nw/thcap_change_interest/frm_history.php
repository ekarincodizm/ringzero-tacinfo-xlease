<?php
if($limit == ""){
	include("../../config/config.php");
	$header = "<B>ประวัติการอนุมัติขอปรับอัตราดอกเบี้ย</B>";
}else{
	$header = "<B>ประวัติผลการอนุมัติ 30 รายการล่าสุด</B>( <font color=\"blue\"><a style=\"cursor:pointer;\" onclick=\"popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')\"><u>ทั้งหมด</u></a></font> )</font>";

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติการขอปรับอัตราดอกเบี้ย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>	
	<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<fieldset>
						<legend><?php echo $header; ?></legend>
						<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
							<tr align="center" bgcolor="#79BCFF">
								<th>รายการที่</th>
								<th>เลขที่สัญญา</th>
								<th>อัตราดอกเบี้ยปัจจุบัน</th>
								<th>อัตราดอกเบี้ยใหม่</th>
								<th>วันเวลาที่เริ่มมีผล</th>
								<th>ผู้ทำรายการอนุมัติ</th>
								<th>วันเวลาที่ทำรายการอนุมัติ</th>
								<th>ผลการอนุมัติ</th>
								<th>รายละเอียด</th>
							</tr>
							<?php
							$queryAppv = pg_query("select * from public.\"thcap_changeRate_temp\" where \"Approved\" is not null order by \"appvStamp\" DESC $limit ");
							$numrowsAppv = pg_num_rows($queryAppv);
							$i=0;
							while($resultAppv = pg_fetch_array($queryAppv))
							{
								$i++;
								$tempID = $resultAppv["tempID"];
								$contractID = $resultAppv["contractID"]; // เลขที่สัญญา
								$oldRate = $resultAppv["oldRate"]; // อัตราดอกเบี้ยปัจจุบัน
								$newRate = $resultAppv["newRate"]; // อัตราดอกเบี้ยใหม่
								$effectiveDate = $resultAppv["effectiveDate"]; // วันเวลาที่เริ่มมีผล
								$doerID = $resultAppv["doerID"]; // ผู้ทำรายการ
								$doerStamp = $resultAppv["doerStamp"]; // วันเวลาที่ทำรายการ
								$appvID = $resultAppv["appvID"]; // ผู้ทำรายการอนุมัติ
								$appvStamp = $resultAppv["appvStamp"]; // วันเวลาที่ทำรายการอนุมัติ
								$Approved = $resultAppv["Approved"];
								
								if($Approved == "t"){$textAppv = "<font color=\"#0000FF\">อนุมัติ</font>";}else{$textAppv = "<font color=\"#FF0000\">ไม่อนุมัติ</font>";}
								
								$qry_name_appv = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvID' ");
								while($result_name_appv = pg_fetch_array($qry_name_appv))
								{
									$fullname = $result_name_appv["fullname"]; // ชื่อของผู้ที่ทำรายการ
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
								echo "<td align=\"center\">$appvStamp</td>";
								echo "<td align=\"center\">$textAppv</td>";
								echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_detail.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><img src=\"images/detail.gif\"></a></td>";
								echo "</tr>";
							}
							if($numrowsAppv==0){
								echo "<tr bgcolor=#FFFFFF height=20><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
							}else{
								//echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
							}
							?>
						</table>
					</fieldset>
			</td>
		</tr>
	</table>
</body>
</html>	

