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
    <title>(THCAP) อนุมัติตั้งค่าเอกสารสัญญา</title>
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
	<div class="header" align="center" ><h1>ประวัติการอนุมัติตั้งค่าเอกสารสัญญา</h1></div>
	<div class="wrapper">
	<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
		<div >
			<table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F2F5A9">
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<td>ลำดับที่</td>
					<td>ประเภทสัญญา</td>
					<td>ชื่อเอกสาร</td>
					<td>สถานะการใช้งาน</td>
					<td>อันดับเอกสาร</td>
					<td>ผู้ทำรายการ</td>
					<td>วันที่ทำรายการ</td>
					<td>ผู้อนุมัติ</td>
					<td>วันที่อนุมัติ</td>
					<td>สถานะการตั้งค่า</td>
					<td>ผลการอนุมัติ</td>
					<td>หมายเหตุ</td>
				<tr>
				
			<?php
				$qry_his = pg_query("select * from thcap_contract_doc_config_temp where doc_status_appv <> '2' 
									order by \"doc_appvStamp\" DESC ");
				$nubhis=0;
				while($res_his=pg_fetch_array($qry_his)){
					$nubhis++;
						$doc_autoID = $res_his['doc_autoID'];
						$doc_ConfigID = $res_his['doc_ConfigID'];
						$doc_conTypeName = $res_his['doc_conTypeName'];
						$doc_docName = $res_his['doc_docName'];
						$doc_statusDoc = $res_his['doc_statusDoc'];
						$doc_doerID = $res_his['doc_doerID'];
						$doc_doerStamp = $res_his['doc_doerStamp'];
						$doc_count_edit = $res_his['doc_count_edit'];
						$doc_note = $res_his['doc_note'];
						$doc_appvID = $res_his['doc_appvID'];
						$doc_appvStamp = $res_his['doc_appvStamp'];
						$doc_status_appv = $res_his['doc_status_appv'];
						$doc_Ranking = $res_his['doc_Ranking'];
						//สถานะการตั้งค่า
						if($doc_count_edit>0){
							$textEdit = "แก้ไข";
						} else {
							$textEdit = "เพิ่มใหม่";
						}
						//สถานะการใช้งาน
						if($doc_statusDoc==0){
							$textStatusDoc = "ไม่ใช้งาน";
							$colorF="#FF000";
						} else if($doc_statusDoc==1){
							$textStatusDoc = "ใช้งาน-ใช้งานเสมอ";
							$colorF="#00FF00";
						}else{
							$textStatusDoc = "ใช้งาน-ไม่จำเป็นต้องใช้เสมอ";
							$colorF="#FFA500";
						}
						//ผลการอนุมัติ
						if($doc_status_appv==0){
							$textAppv = "ไม่อนุมัติ";
							$colorSF="#FF000";
						}else{
							$textAppv = "อนุมัติ";
							$colorSF="#00FF00";
						}
						//ชื่อผู้ทำรายการ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$doc_doerID' ");
						$doc_DoerName=pg_fetch_result($qry_doername,0);
						//ชื่อผู้อนุมัติ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$doc_appvID' ");
						$doc_appvName=pg_fetch_result($qry_doername,0);
						
						if($nubhis%2==0){
						echo "<tr class=\"odd\" align=center>";
						} else {
						echo "<tr class=\"even\" align=center>";
						}
							echo "<td>$nubhis</td>";
							echo "<td>$doc_conTypeName</td>";
							echo "<td>$doc_docName</td>";
							echo "<td><font color=\"$colorF\">$textStatusDoc</font></td>";
							echo "<td>$doc_Ranking</td>";
							echo "<td>$doc_DoerName</td>";
							echo "<td>$doc_doerStamp</td>";
							echo "<td>$doc_appvName</td>";
							echo "<td>$doc_appvStamp</td>";
							echo "<td>$textEdit</td>";
							echo "<td><font color=\"$colorSF\">$textAppv</font></td>";
							echo "<td><a onclick=\"javascript:popU('detail_appv.php?autoID=$doc_autoID&menu=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=480')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></td>";
						echo "</tr>";
				} //end while
				if($nubhis == 0){
						echo "<tr><td colspan=12 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
						}
			?>
							<tr bgcolor="#6699FF">
								<td colspan="12" align="left"><b>รายการทั้งหมด <?php echo $nubhis;?> รายการ<b></td>
							</tr>
			</table>
		</div>
	</table>
	</div>
</body>
</html>