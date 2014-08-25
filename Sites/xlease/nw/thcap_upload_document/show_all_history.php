<?php
session_start();
include("../../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$id_user=$_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตรวจสอบเอกสารสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
	<div class="header" align="center" ><h1>ประวัติการตรวจสอบเอกสารสัญญา</h1></div>
	<div class="wrapper">
	<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
		<div >
			<table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<td width="4%">ลำดับที่</td>
					<td width="12%">เลขที่สัญญา</td>
					<td width="8%">ประเภทสัญญา</td>
					<td width="8%">ชื่อเอกสาร</td>
					<td width="8%">ผู้ทำรายการ</td>
					<td width="8%">วันที่ทำรายการ</td>
					<td width="8%">ผู้ตรวจสอบ</td>
					<td width="8%">วันที่ตรวจสอบ</td>
					<td width="5%">ผลการอนุมัติ</td>
					<td width="5%">หมายเหตุ</td>
				<tr>
				
			<?php
				$qry_his = pg_query("select a.*,b.\"conType\" from thcap_upload_document as a
									left join thcap_contract as b on a.\"contractID\" = b.\"contractID\"
									where a.\"Approved\" <> '2' 
									order by a.\"up_appvStamp\" DESC ");
				$nubhis=0;
				while($res_his=pg_fetch_array($qry_his)){
					$nubhis++;
						$up_autoID = $res_his['up_autoID'];
						$contractID = $res_his['contractID'];
						$conType = $res_his['conType'];
						$docTypename = $res_his['docTypename'];
						$up_doerID = $res_his['up_doerID'];
						$up_doerStamp = $res_his['up_doerStamp'];
						$up_appvID = $res_his['up_appvID'];
						$up_appvStamp = $res_his['up_appvStamp'];
						$Approved = $res_his['Approved'];
						
					
						//ผลการอนุมัติ
						if($Approved==0){
							$textAppv = "ไม่อนุมัติ";
							$colorSF="#FF000";
						}else{
							$textAppv = "อนุมัติ";
							$colorSF="#00FF00";
						}
						//ชื่อผู้ทำรายการ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$up_doerID' ");
						$up_DoerName=pg_fetch_result($qry_doername,0);
						//ชื่อผู้อนุมัติ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$up_appvID' ");
						$up_appvName=pg_fetch_result($qry_doername,0);
						
						if($nubhis%2==0){
						echo "<tr class=\"odd\" align=center>";
						} else {
						echo "<tr class=\"even\" align=center>";
						}
							echo "<td>$nubhis</td>";
							echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
							echo "<td>$conType</td>";
							echo "<td>$docTypename</td>";
							echo "<td>$up_DoerName</td>";
							echo "<td>$up_doerStamp</td>";
							echo "<td>$up_appvName</td>";
							echo "<td>$up_appvStamp</td>";
							echo "<td><font color=\"$colorSF\">$textAppv</font></td>";
							echo "<td><a onclick=\"javascript:popU('detail_appv.php?autoID=$up_autoID&menu=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=480,height=380')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></td>";
						echo "</tr>";
				} //end while
				if($nubhis == 0){
						echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
						}
			?>
							<tr bgcolor="#6699FF">
								<td colspan="10" align="left"><b>รายการทั้งหมด <?php echo $nubhis;?> รายการ<b></td>
							</tr>
			</table>
		</div>
	</table>
	</div>
</body>
</html>