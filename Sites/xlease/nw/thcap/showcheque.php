<?php
session_start();
include("../../config/config.php");

$revChqID = $_REQUEST['revChqID'];
$bankRevAmt= $_REQUEST['bankRevAmt'];

$qrychq=pg_query("SELECT \"bankChqNo\",\"revChqToCCID\",\"bankChqAmt\" FROM finance.thcap_receive_cheque WHERE \"revChqID\"='$revChqID' and \"revChqStatus\"='6'");
$numrow=pg_num_rows($qrychq);
if($numrow>0){
if($reschq=pg_fetch_array($qrychq)){
	$bankChqNo=$reschq["bankChqNo"]; //เลขที่เช็ค
	$revChqToCCID=$reschq["revChqToCCID"]; //เลขที่สัญญา
	$bankChqAmt=$reschq["bankChqAmt"]; //จำนวนเงินในเช็ค
	$bankChqAmt2=number_format($bankChqAmt,2);
}
}
echo "<table width=\"600\" border=\"0\" cellSpacing=\"1\" cellPadding=\"1\" bgcolor=\"#FFDAB9\" align=\"center\" >
 <tr>
	<td align=\"right\" width=\"150\"><b>เลขที่เช็ค :</b></td>
	<td height=\"30\">$bankChqNo<input type=\"hidden\" name=\"bankChqNo\" id=\"bankChqNo\" value=\"$bankChqNo\"></td>
</tr>
<tr>
	<td align=\"right\"><b>เลขที่สัญญา :</b></td>
	<td height=\"30\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$revChqToCCID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$revChqToCCID</u></span><input type=\"hidden\" name=\"contractID2\" id=\"contractID2\" value=\"$revChqToCCID\"></td>
</tr>
<tr>
	<td align=\"right\"><b>จำนวนเงิน :</b></td>
	<td height=\"30\"><input type=\"hidden\" id=\"bankchqamt\" value=\"$bankChqAmt\">$bankChqAmt2</td>
</tr>
";
if($bankRevAmt!=$bankChqAmt and $numrow>0){
	if($bankChqAmt>$bankRevAmt){
		$result=$bankChqAmt-$bankRevAmt;
	}else{
		$result=0;
	}
echo "
<tr>
	<td align=\"right\"><input type=\"checkbox\" name=\"tariff\" id=\"tariff\" value=\"$result\" onclick=\"checkriff()\"></td>
	<td height=\"30\">
		<div id=\"tarshow\">มีค่าธรรมเนียม  ".number_format($result,2)." บาท</div>
		<div id=\"tarhide\">มีค่าธรรมเนียม  <input type=\"text\" name=\"tariffval\" id=\"tariffval\" value=".number_format($result,2)."></div>
	</td>
	
</tr>
<tr id=\"showfile\">
	<td align=\"right\"><b>สลิปเงินโอน : </b></td>
	<td><input type=\"file\" name=\"my_field[]\" id=\"addfile\"></td>
</tr>
";
}
echo"</table>";
?>
<script language=javascript>
$(document).ready(function(){
	$("#tarhide").hide();
	$("#showfile").hide();
});
function checkriff(){
	if(document.getElementById("tariff").checked==true){
		$("#tarshow").hide();
		$("#tarhide").show();
		$("#showfile").show();
	}else{
		$("#tarshow").show();
		$("#tarhide").hide();
		$("#showfile").hide();
	}
}
</script>
