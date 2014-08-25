<?php
include("../../config/config.php");

$contractID = $_GET["idno"];
if($contractID == ""){$contractID = $_POST["contractID_text"];}


$sqlchkcon = pg_query("select \"contractID\",\"conType\" from thcap_mg_contract where \"contractID\" = '$contractID'");
if(pg_num_rows($sqlchkcon) == 0){
	$sqlchkcon = pg_query("select \"contractID\",\"conType\" from \"thcap_lease_contract\" where \"contractID\"='$contractID'");
	if(pg_num_rows($sqlchkcon)==0)
	{
		$sqlchkcon = pg_query("select \"contractID\" from thcap_contract where \"contractID\" = '$contractID'");
		if(pg_num_rows($sqlchkcon) > 0 ){	
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=../fapn_statement/frm_Index.php?idno=$contractID\">";
			exit();
		}
	}
}

$remainqry = pg_fetch_array($sqlchkcon);

$nowday = nowDate(); // วันที่ปัจจุบัน

if(empty($_POST["signDate"])){
    $ssdate = nowDate();
}else{
    $ssdate=$_POST["signDate"];
}

$id_user = $_SESSION["av_iduser"]; // id ของ user ที่กำลังใช้งานอยู่ในขณะนั้น
$add_date=nowDateTime();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แสดงรับรู้รายได้เช่าซื้อ-เช่าทางการเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#contractID_text").autocomplete({
		source: "s_idall.php",
        minLength:1
    });
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
	
</script>   
</head>
<body>
<div align="center"><h2>(THCAP) แสดงรับรู้รายได้เช่าซื้อ-เช่าทางการเงิน</h2></div>
<table width="1250" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>

			<fieldset>
				<legend><B>ค้นหา</B></legend>
				<div align="center" style="width:850px;" id="divmain">
					<div style="float:center; width:850px;">
						<form method="post" name="form1" action="frm_Realize.php">
							เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว : &nbsp
							<input type="text" name="contractID_text" id="contractID_text" value="<?php echo $contractID; ?>" size="70">&nbsp;
							<input type="submit" id="btnsearch" value="ค้นหา">
						</form>
					</div>
					<div style="clear:both;"></div>
					<div id="panel" align="left" style="margin-top:10px"></div>
				</div>
			</fieldset>
			
				
			
			<?php
			if($contractID != "")
			{
			?>
			<div style="margin-top:0px;"><?php include('../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
			<div style="margin-top:10px;"><?php include('../thcap/Data_Realize.php'); ?></div>
			<?php
			}
			?>
		</td>
	</tr>
</table>
</html>