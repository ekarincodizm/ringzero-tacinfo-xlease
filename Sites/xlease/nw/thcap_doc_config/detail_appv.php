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
$autoID = $_GET['autoID'];
$menu = $_GET['menu'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตั้งค่าเอกสารสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function Check(){
	var Message = "check";
	var Noerror = Message;
	
	if(document.getElementById("newDoc").value==""){
		Message = "กรุณาระบุชื่อเอกสารสัญญา";
	} else if(document.getElementById("reason").value==""){
		Message = "กรุณาระบุเหตุผลที่เพิ่มเอกสาร";
	}
	
	if(Message==Noerror){
		return true;
		} else {
			alert(Message);
			return false;
			}
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
	<?php
		if($menu==1){
			$hidden = "hidden";
		}
		//หาข้อมูลที่แก้ไขใหม่
		$qry_appv = pg_query("select * from thcap_contract_doc_config_temp where \"doc_autoID\" = '$autoID'");
		while($res_appv = pg_fetch_array($qry_appv)){
			$configID = $res_appv['doc_ConfigID'];
			$conTypeName = $res_appv['doc_conTypeName'];
			$docName = $res_appv['doc_docName'];
			$doc_docName = $res_appv['doc_docName'];
			$doc_statusDoc = $res_appv['doc_statusDoc'];
			$doc_doerID = $res_appv['doc_doerID'];
			$doc_doerStamp = $res_appv['doc_doerStamp'];
			$doc_count_edit = $res_appv['doc_count_edit'];
			$doc_note = $res_appv['doc_note'];
			$doc_Ranking = $res_appv['doc_Ranking'];
		} // end while
		
		//หาข้อมูลที่แก้ไขก่อนหน้านี้
		if($doc_count_edit>0){
			
			$qry_old = pg_query("select * from thcap_contract_doc_config_temp where \"doc_ConfigID\" = '$configID' and doc_count_edit = (select max(doc_count_edit) from thcap_contract_doc_config_temp where \"doc_ConfigID\" = '$configID' and doc_status_appv = '1') ");
			while($res_old = pg_fetch_array($qry_old)){
				$Olddoc_conType = $res_old['doc_conTypeName'];
				$Olddoc_docName = $res_old['doc_docName'];
				$Olddoc_statusDoc = $res_old['doc_statusDoc'];
				$Oldnote_doc_note = $res_old['doc_note'];
				$Olddoc_Ranking = $res_old['doc_Ranking'];
			} // endwhile
			$Textshow = "ข้อมูลล่าสุดก่อนแก้ไข";
			$Textapp = "ข้อมูลที่ขอแก้ไข";
		} else {
			$Textshow = "";
			$Textapp = "เพิ่มข้อมูลใหม่";
		}
		
	?>
	<div align="center">
		<h2>อนุมติตั้งค่าเอกสารสัญญา <br> สัญญาประเภท: <?php echo $conTypeName;?> </h2>
	</div>
	<div>
			<div class="wrapper">
				<table table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" >
					<tr>
						<table table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center">
							<tr>
								<td>
								<fieldset>
								 <legend><?php echo $Textshow; ?></legend>
									<table align="center">
										<tr>
											<td align="center">
												<label><b>ชื่อเอกสาร: </b></label><input type="text" name="newDoc"  value="<?php echo $Olddoc_docName; ?>" style="background-color:#CCCCCC;" readonly >
											</td>
										</tr>
										<tr>
											<td align="center">
												<label><b>สถานะการใช้งาน: </b></label><select name="useable" disabled>
																						<option <?php if($Olddoc_statusDoc=="1"){ echo "selected";}?> value="1">ใช้งาน-ใช้งานเสมอ</option>
																						<option <?php if($Olddoc_statusDoc=="0"){ echo "selected";}?> value="0">ไม่ใช้งาน</option>
																						<option <?php if($Olddoc_statusDoc=="2"){ echo "selected";}?> value="2">ใช้งาน-ไม่จำเป็นต้องใช้เสมอ</option>
																					</select> 
											</td>
										</tr>
										<tr>
											<td>
												<label><b>จัดอันดับเอกสาร: </b></label><input type="text" id="doc_Ranking" name="doc_Ranking" value="<?php echo $Olddoc_Ranking; ?>" size="3" readonly />
											</td>
										</tr>
										<tr>
											<td align="center">
												<label><b>เหตุผล: </b></label><br>
												<textarea name="reason" cols="30" rows="8" readonly style="background-color:#CCCCCC;"><?php echo $Oldnote_doc_note; ?></textarea>
											</td>
										</tr>
									</table>
								</fieldset>
								</td>
								<td>
		<form action="process_appv.php" method="post">
								<fieldset>
								<legend><?php echo $Textapp; ?></legend>
									<table align="center">
										<tr>
											<td align="center">
												<label><b>ชื่อเอกสาร: </b></label><input type="text" name="newDoc" id="newDoc" value="<?php echo $docName; ?>" style="background-color:#CCCCCC;" readonly >
												<input type="hidden" name="configID" value="<?php echo $configID; ?>">
												<input type="hidden" name="conType" value="<?php echo $conTypeName; ?>">
												<input type="hidden" name="countEdit" value="<?php echo $doc_count_edit; ?>">
												<input type="hidden" name="autoID" value="<?php echo $autoID; ?>">
											</td>
										</tr>
										<tr>
											<td align="center">
												<label><b>สถานะการใช้งาน: </b></label><select name="useableshow" disabled>
																						<option <?php if($doc_statusDoc=="1"){ echo "selected";}?> value="1">ใช้งาน-ใช้งานเสมอ</option>
																						<option <?php if($doc_statusDoc=="0"){ echo "selected";}?> value="0">ไม่ใช้งาน</option>
																						<option <?php if($doc_statusDoc=="2"){ echo "selected";}?> value="2">ใช้งาน-ไม่จำเป็นต้องใช้เสมอ</option>
																					</select> 
												<input type="hidden" name="useable" value="<?php echo $doc_statusDoc; ?>"/>
											</td>
										</tr>
										<tr>
											<td>
												<label><b>จัดอันดับเอกสาร: </b></label><input type="text" id="doc_Ranking" name="doc_Ranking" value="<?php echo $doc_Ranking; ?>" size="3" readonly />
											</td>
										</tr>
										<tr>
											<td align="center">
												<label><b>เหตุผล: </b></label><br>
												<textarea name="reason" id="reason"  cols="30" rows="8" readonly style="background-color:#CCCCCC;"><?php echo $doc_note; ?></textarea>
											</td>
										</tr>
									</table>
								</fieldset>
								</td>
							</tr>
						</table>
					</tr>
				</table>
			</div>
			<div class="wrapper" <?php echo $hidden ?>>
				<table table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center">
					<tr bgcolor="#F2F5A9">
						<td align="center">
							<input type="submit" name="appv" id="appv" value="อนุมัติ" onclick="return Check();">
							<input type="submit" name="notappv" id="notappv" value="ไม่อนุมัติ" onclick="return Check();">
							<input type="button" name="cancle" id="cancle" value="ปิด" onclick="window.close();">
						</td>
					</tr>
				<table>
			</div>
		</form>
	</div>
</body>
</html>