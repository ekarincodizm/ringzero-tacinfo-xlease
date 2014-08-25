<?php
session_start();
include("../config/config.php");
$idno = $_REQUEST['idno'];

$text_name = $_POST['text_name'];
$text_money = $_POST['text_money'];

$txtLawyer2 = $_POST['txtLawyer'];
echo $txtLawyer2;
$txtPenalty2 = $_POST['txtPenalty'];
echo $txtPenalty2;
$txtAct2 = $_POST['txtAct'];
$txtInsurance2 = $_POST['txtInsurance'];

$nowdate = Date('Y-m-d');
if(isset($_POST['tmoney'])) $tmoney = $_POST['tmoney']; else $tmoney = 1500;
if(isset($_POST['signDate'])) $signDate = $_POST['signDate']; else $signDate = nowDate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript"><!--
var gFiles = 0;
function addFile(){
    var li = document.createElement('div');
    li.setAttribute('id', 'file-' + gFiles);
    li.innerHTML = '<div align="left">ชื่อรายการ <input type="text" id="text_name" name="text_name[]" size="30"> ยอดเงิน <input type="text" id="text_money" name="text_money[]" size="10" style="text-align:right"> <span onclick="removeFile(\'file-' + gFiles + '\');" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span></div>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;
}
function removeFile(aId) {
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}

function delete_row_c(rowid) {
	var row = document.getElementById(rowid);
	row.parentNode.removeChild(row); 
}
function checkdate(){
	var signDate = document.getElementById("signDate").value;
	var idno = document.getElementById("idno").value;
	var tmoney = document.getElementById("tmoney").value;
	
	$.post("checkdata_nt.php", {signDate: signDate,idno : idno,tmoney : tmoney
  	},
  	 function(data){
		var data = data;
		document.getElementById("txtLawyer").value = data;

  	 });		
}

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

-->
</script>    

</head>
<body>

<fieldset><legend><B>ออก NT</B></legend>

<?php
//คำนวณหาค่าทนาย
$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
$res_FpFa1=pg_fetch_array($qry_FpFa1);
$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];

$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];

$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
while($resbf=pg_fetch_array($qry_before)){
    $sumamt2+=$resbf["CalAmtDelay"];
}

$qry_amt=@pg_query("select * ,'$signDate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$signDate') "); //รายการที่คำนวณ
while($res_amt=@pg_fetch_array($qry_amt)){
    $s_amt=pg_query("select \"CalAmtDelay\"('$signDate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);
	$sumamt2+=$res_s;
}

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}
$sum_amt=round($sumamt2-$otherpay_amt) ;
//จบคำนวณหาค่าทนายรวมค่าดอกเบี้ยล่าช้า

//ค่ามิเตอร์ + ปรับ
$qry_fp=pg_query("select sum(\"CusAmt\") as sumamt from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$idno' AND \"cuspaid\"='false';");
if($res_fp=pg_fetch_array($qry_fp)){
    $sumamt = $res_fp["sumamt"];
}

//ค่า พรบ.
$qry_if=pg_query("select \"CollectCus\" from insure.\"InsureForce\" WHERE \"IDNO\"='$idno' AND \"CusPayReady\"='false';");
if($res_if=pg_fetch_array($qry_if)){
    $CollectCus1 = $res_if["CollectCus"];
}

//ค่าประกันภัย
$qry_uif=pg_query("select \"CollectCus\" from insure.\"InsureUnforce\" WHERE \"IDNO\"='$idno' AND \"CusPayReady\"='false';");
if($res_uif=pg_fetch_array($qry_uif)){
    $CollectCus2 = $res_uif["CollectCus"];
}
//if($P_LAWERFEE == 'f'){
?>

<form name="frm_1" action="notice_add2.php" method="post">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<table width="100%" border="0">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="80%"><?php echo $idno; ?><input type="hidden" name="idno" id="idno" value="<?php echo $idno;?>"></td>
</tr>
<tr>
    <td><b>ค่าทนาย</b></td>
    <td><input type="text" size="13" style="text-align:right;" id="tmoney" name="tmoney" value="<?php echo $tmoney; ?>" onchange="checkdate()" /></td>
</tr>
<tr>
    <td><b>คิดถึงวันที่</b></td>
    <td><input type="text" size="13" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo $signDate; ?>" onchange="checkdate()"/><input name="button2" type="button" onclick="displayCalendar(document.frm_1.signDate,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
</tr>
<tr bgcolor="#FFFFFF" id="1">
    <td align="left"></td>
    <td height="30"><font color="red"><b>*กรุุณาระบุรายการไม่เกิน 6 รายการ*</b></font></td>
</tr>
<tr bgcolor="#FFFFFF" id="1">
    <td align="left"></td>
    <td>
		ชื่อรายการ  <input type="text" name="text1" size="30" value="ค่าทนายรวมค่าดอกเบี้ยล่าช้า" readonly>  ยอดเงิน
		<input type="text" style="text-align:right" name="txtLawyer" id="txtLawyer" value="<?php if($txtLawyer2 == ""){ echo round($tmoney+$sum_amt);}else{ echo $txtLawyer2;}?>" onkeyup="javascript:updateSum()" size="10" readonly>
		<span style="cursor:pointer;" onclick="JavaScript:if(confirm('ยืนยันการลบข้อมูล')==true){delete_row_c(1);}"><i>- ลบรายการนี้ -</i></span>
		<?php //$sumall += ($tmoney+$sum_amt); ?>
    </td>
</tr>
<?php if($asset_type == 1 AND $sumamt > 0){  ?>
<tr bgcolor="#FFFFFF" id="2">
    <td align="left"></td>
    <td>
		ชื่อรายการ <input type="text" name="text2" size="30" value="ค่ามิเตอร์+ปรับ" readonly>  ยอดเงิน
		<input type="text" style="text-align:right" name="txtPenalty" id="txtPenalty" value="<?php echo round(($sumamt+1000),2); ?>" size="10">
		<span style="cursor:pointer;" onclick="JavaScript:if(confirm('ยืนยันการลบข้อมูล')==true){delete_row_c(2);}"><i>- ลบรายการนี้ -</i></span>

    </td>
</tr>
<?php } ?>
<?php if($CollectCus1 > 0){ ?>
<tr bgcolor="#FFFFFF" id="3">
    <td align="left"></td>
    <td>
		ชื่อรายการ <input type="text" name="text3" size="30" value="ค่า พรบ." readonly>  ยอดเงิน
		<input type="text" style="text-align:right" name="txtAct" id="txtAct" value="<?php echo round($CollectCus1,2); ?>"  size="10">
		<span style="cursor:pointer;" onclick="JavaScript:if(confirm('ยืนยันการลบข้อมูล')==true){delete_row_c(3);}"><i>- ลบรายการนี้ -</i></span>

    </td>
</tr>
<?php } ?>

<?php if($CollectCus2 > 0){  ?>
<tr bgcolor="#FFFFFF" id="4">
    <td align="left"></td>
    <td>
		ชื่อรายการ <input type="text" name="text4" size="30" value="ค่าประกันภัย" readonly>  ยอดเงิน
		<input type="text" style="text-align:right" name="txtInsurance" id="txtInsurance" value="<?php echo round($CollectCus2,2); ?>" size="10">
		<span style="cursor:pointer;" onclick="JavaScript:if(confirm('ยืนยันการลบข้อมูล')==true){delete_row_c(4);}"><i>- ลบรายการนี้ -</i></span>
    </td>
</tr>
<?php } ?>
<tr>
    <td></td>
    <td>
		<span onclick="addFile();" style="cursor:pointer;"><br><b><i>+ เพิ่มรายการอื่นๆ +</i></b><br><br></span>
		<div id="files-root">
		<?php
			if(isset($text_name)){
				for($i=0;$i<count($text_name);$i++){
					echo "<div align=\"left\">ชื่อรายการ <input type=\"text\" id=\"text_name\" name=\"text_name[]\" size=\"30\" value=\"$text_name[$i]\"> ยอดเงิน <input type=\"text\" id=\"text_money\" name=\"text_money[]\" size=\"10\" value=\"$text_money[$i]\" style=\"text-align:right\"></div>";
				}
			}
		?>
		</div>
    </td>
</tr>
<tr>
    <td></td>
    <td><input name="btnButton" id="btnButton" type="submit" value="ต่อไป..." /></td>
</tr>
</table>
</fieldset> 
<?php
if($P_LAWERFEE == 't'){
?>
<table width="100%" border="0">
	<tr><td colspan="7" height="30" bgcolor="#FFFFFF"><b>รายการ NT ที่เคยออก</b></td></tr>
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">NTID</td>
        <td align="center">วันที่ออก NT</td>
        <td align="center">คิดถึงวันที่</td>
        <td align="center">สถานะ</td>
		<td align="center">สถานะอนุมัติ</td>
        <td align="center">ยกเลิกโดย</td>
        <td align="center">วันที่ยกเลิก</td>
    </tr>
	<?php
	$qry_name=pg_query("SELECT * FROM \"NTHead\" where \"IDNO\"='$idno' and \"CusState\"='0' ORDER BY \"do_date\" ");
	while($res_name=pg_fetch_array($qry_name)){
		$NTID = $res_name["NTID"];
		$do_date = $res_name["do_date"];
		$to_date = $res_name["to_date"];
		$cancel = $res_name["cancel"]; if($cancel=='f') $cancel = "<font color=blue>ใช้งานปกติ</font>"; else $cancel = "ยกเลิก";
		$makerid = $res_name["makerid"];
		$cancelid = $res_name["cancelid"];
		$cancel_date = $res_name["cancel_date"];
		
		$qry_notice=pg_query("SELECT \"statusNT\" FROM \"nw_statusNT\" where \"NTID\"='$NTID'");
		if($res_notice=pg_fetch_array($qry_notice)){
			$statusNT = $res_notice["statusNT"];
		}
		
		$qry_fullname_c=pg_query("SELECT fullname FROM \"Vfuser\" where \"id_user\"='$cancelid'");
		if($res_fullname_c=pg_fetch_array($qry_fullname_c)){
			$fullname_c = $res_fullname_c["fullname"];
		}

		$in+=1;
		if($in%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
?>   
			<td align="center"><span onclick="javascript:popU('../nw/approve_nt/detail_notice.php?ntid=<?php echo "$NTID"?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=450')" style="cursor: pointer;" title="Re Print"><u><?php echo $NTID;?></u></span></td>
			<td align="center"><?php echo $do_date; ?></td>
			<td align="center"><?php echo $to_date; ?></td>
			<td align="center"><?php echo $cancel; ?></td>
			<td align="center">
			<?php
			if($statusNT == 2){
				echo "<span onclick=\"javascript:popU('../nw/approve_nt/result_noapp.php?ntid=$NTID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')\" style=\"cursor: pointer;\" title=\"เหตุผลที่ไม่อนุมัติ\"><u>ไม่อนุมัติ</u></span>";
			}else{
				echo "อนุมัติ";
			}
			?>
			</td>
			<td align="left"><?php echo $fullname_c; ?></td>
			<td align="center"><?php echo $cancel_date; ?></td>
		</tr>
<?php
	} //end while
?>
</table>
<?php
}
?>
</form>

<?php
//}else{  
//    echo "<div align=center>รายการนี้ได้ออก NT ไปแล้ว</div>";
//}
?>




</body>
</html>