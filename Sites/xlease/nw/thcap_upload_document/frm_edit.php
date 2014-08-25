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
$contractID = $_GET['conid'];
$autoID = $_GET['up_aoutoid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) UPLOAD เอกสารสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function validateFileExtension(fld) {
    if(!/(\.pdf)$/i.test(fld.value)) {
        alert("upload ได้เฉพาะไฟล์ pdf เท่านั้น");      
        fld.form.reset();
        fld.focus();        
        return false;   
    }   
    return true; 
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<script>
function checkfile(){
		if(document.getElementById("file").value==""||document.getElementById("file").value==null){
			alert('กรุณาเลือกไฟล์ upload ด้วย');
			return false;
		} else if(document.getElementById("note").value==""||document.getElementById("note").value==null){
			alert('กรุณาระบุเหตุผล ด้วย');
			return false;
		}
}
</script>
<style>
#detail
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
#upform
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
</style>
</head>
<body>
	<div><h1 align="center">(THCAP) UPLOAD เอกสารสัญญา</h1></div>
	<div id="detail">
		<h3 align="center"><b>รายละเอียดเอกสารสัญญาที่ต้องการแก้ไข</b></h3>
			<table table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<td width="10%">เลขที่สัญญา</td>
						<td width="5%">ประเภทสัญญา</td>
						<td width="10%">ชื่อเอกสาร</td>
						<td width="5%">ไฟล์</td>
						<td width="20%">หมายเหตุ</td>
					</tr>
<?php 	
	if($autoID!=""){ 
		$qry_appv = pg_query("select * from thcap_upload_document where \"up_autoID\" = '$autoID'");
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
	} 
?>
			</table>
	</fieldset>
	</div>
	<h3 align="center"><b>แก้ไข Upload เอกสารสัญญา</b></h3>
	<div id="upform">
		<form name="frmUpload" action="process_edit.php" method="post" enctype="multipart/form-data" onsubmit="return checkfile();">
		<table border="0" cellSpacing="1" cellPadding="3" align="center">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center" >
				<td align="center"><b>เหตุผล</b></td>
				<td align="center"><b>ค้นหาไฟล์</b></td>
			</tr>
			<tr>
				<td><input type="text" name="note" id="note" size="50"></td>
				<td><input type="file" name="file" id="file" size="20" onchange="return validateFileExtension(this)"></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="submit" name="upload" value="upload"/> 
					<input type="button" name="cancle" value="cancle" onclick="window.close();"/>
				</td>
			</tr>
		</table>
			<input type="hidden" name="up_autoid" value="<?php echo $autoID; ?>"/>
		</form>
	</div>
</body>
</html>