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
$conid = pg_escape_string($_POST['conid']);
if(isset($_GET['refresh'])){
$conid = pg_escape_string($_GET['conid']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) UPLOAD เอกสารสัญญา </title>
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
<style>
#list
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
#uploadtab
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
#button
{
margin-top:20px;
}
</style>
</head>
<body>
	<div align="center">
		<h1>(THCAP) UPLOAD เอกสารสัญญา</h1>
	</div>
	
	<div id="list">
	<fieldset>
	<legend>รายการเอกสารที่ upload เรียบร้อยแล้ว</legend>
		<form action="process_appv.php" method="post">
			<div class="wrapper">
				<table table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<td width="10%">เลขที่สัญญา</td>
						<td width="5%">ประเภทสัญญา</td>
						<td width="10%">ชื่อเอกสาร</td>
						<td width="5%">ไฟล์</td>
						<td width="18%">หมายเหตุ</td>
						<td width="7%">ทำรายการ</td>
					</tr>
				<?php
				$qry_appv = pg_query("select  * from thcap_upload_document t1 where t1.\"contractID\" = '$conid' and add_or_edit = (select max(add_or_edit) from thcap_upload_document t2 where t2.\"contractID\" = '$conid' and t1.\"docTypename\"=t2.\"docTypename\" and t2.\"Approved\" <> '0') 
										and t1.\"Approved\" <> '0' 
										
									");
				$numrow = pg_num_rows($qry_appv);
				if($numrow>0){
					while($res_appv = pg_fetch_array($qry_appv)){
						$up_autoID = $res_appv['up_autoID'];
						$contractId = $res_appv['contractID'];
						$docTypename = $res_appv['docTypename'];
						$conType = $res_appv['conType'];
						$up_doerID = $res_appv['up_doerID'];
						$up_doerStamp = $res_appv['up_doerStamp'];
						$noteFile = $res_appv['noteFile'];
						$pathfile = $res_appv['pathFile'];
						$Approved = $res_appv['Approved'];
						$add_or_edit = $res_appv['add_or_edit'];
						
						if($Approved!=1){
							if($add_or_edit>0){
								$status_text = "รออนุมัติ upload แก้ไข";
								$hidden = "hidden";
							}else{
								$status_text = "รออนุมัติ upload ใหม่";
								$hidden = "hidden";
							}
						}else {
							$hidden = "";
						}
						if($noteFile==null){
							$textnoteFile = "ไม่ระบุ";
						} else {
							$textnoteFile = $noteFile;
						}
					
						echo "<tr align=\"center\">";
							echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractId','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractId</u></font></td>";
							echo "<td>$conType</td>";
							echo "<td>$docTypename</td>";
							echo "<td><a href=\"../upload/document_contract/$pathfile\" TARGET=\"_blank\"><img src=\"images/detail.gif\"></a></td>";
							echo "<td>$textnoteFile</td>";
							echo "<td>";
								if($hidden==""){
									echo "<input type=\"button\" value=\"แก้ไข\" onclick=\"javascript:popU('frm_edit.php?up_aoutoid=$up_autoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=480');\">";
								} else {
									echo "$status_text";
								}
							echo "</td>";
						echo "</tr>";
					} // end while
				} else {
					echo "<tr align=\"center\"><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
				}
				?>
				</table>
			</div>
		</form>
		</fieldset>
	</div>
	<div id="uploadtab">
		
		<?php 
		include("frm_not_require.php") 
		?>
	</div>
	<div align="center" id="button"><input type="button" value="กลับหน้าหลัก" onclick="location.href='frm_index.php'"/></div>
</body>
</html>