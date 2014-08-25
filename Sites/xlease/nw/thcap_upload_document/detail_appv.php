<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$nowDate = nowDateTime();
$autoID =  pg_escape_string($_GET['autoID']);
$menu =  pg_escape_string($_GET['menu']);
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
	<div align="center">
		<h2>ตรวจสอบเอกสารสัญญา</h2>
	</div>
	<div>
		<form action="process_appv.php" method="post">
			<div class="wrapper">
				<table table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<td width="10%">เลขที่สัญญา</td>
						<td width="5%">ประเภทสัญญา</td>
						<td width="10%">ชื่อเอกสาร</td>
						<td width="5%">ไฟล์</td>
						<td width="20%">หมายเหตุ</td>
					</tr>
				<?php
				if($menu==1){
					$hidden = "hidden";
				}
				$qry_appv = pg_query("select \"up_autoID\",\"contractID\",\"docTypename\",\"conType\",\"up_doerID\",\"up_doerStamp\",\"noteFile\",\"pathFile\" from thcap_upload_document where \"up_autoID\" = '$autoID'");
				while($res_appv = pg_fetch_array($qry_appv)){
					$up_autoID = $res_appv['up_autoID'];
					$contractId = $res_appv['contractID'];
					$docTypename = $res_appv['docTypename'];
					$conType = $res_appv['conType'];
					$up_doerID = $res_appv['up_doerID'];
					$up_doerStamp = $res_appv['up_doerStamp'];
					$noteFile = $res_appv['noteFile'];
					$pathfile = $res_appv['pathFile'];
					
					if($noteFile==null){
						$textnoteFile = "ไม่ระบุ";
					} else {
						$textnoteFile = $noteFile;
					}
					
					echo "<tr align=\"center\">";
						echo "<td>$contractId</td>";
						echo "<td>$conType</td>";
						echo "<td>$docTypename</td>";
						echo "<td><a href=\"../upload/document_contract/$pathfile\" TARGET=\"_blank\"><img src=\"images/detail.gif\"></a></td>";
						echo "<td>$textnoteFile</td>";
					echo "</tr>";
				} // end while
				?>
					<tr>
						<input type="hidden" name="up_autoID" value="<?php echo $up_autoID; ?>">
					</tr>
					<tr <?php echo $hidden ?>>
						<td align="center" colspan="5">
							<input type="submit" name="appv" id="appv" value="อนุมัติ" onclick="return Check();">
							<input type="submit" name="notappv" id="notappv" value="ไม่อนุมัติ" onclick="return Check();">
							<input type="button" name="cancle" id="cancle" value="ปิด" onclick="window.close();">
						</td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</body>
</html>