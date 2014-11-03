<?php
include("../../config/config.php");
include("../function/emplevel.php");

$id_user = $_SESSION["av_iduser"];
$app_date = nowDateTime();

// ระดับสิทธิพนักงาน
$emplevel = emplevel($id_user);

$debtID = pg_escape_string($_GET["debtID"]);
$show = pg_escape_string($_GET["show"]); //จะแสดง ปุ่ม อนุมัติ/ไม่อนุมัติ เมื่อ=1
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>หมายเหตุ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
</head>
<script language="javascript">
function appv(no){
	var str_appv;
	if(no=='1'){
		str_appv='อนุมัติ';
	}
	else if(no=='0'){
		str_appv='ไม่อนุมัติ';
	}
	
	if(confirm('คุณต้องการ'+str_appv+'รายการนี้หรือไม่')){
		var stitle;
		$.post('process_approve.php',{
            debtID:<?php echo $debtID; ?>,
            stsapp:no,
			title:stitle
		},function(data){		
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
			}else if(data == "2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
			}else if(data == "3"){
				alert("ผิดผลาด มีการทำรายการไปก่อนหน้านี้แล้ว!");
			}else if(data == "4"){
				alert("ผิดผลาด ไม่สามารถดำเนินการได้ ผู้อนุมัติจะต้องเป็นบุคคล คนละคนกับ ผู้ตั้งหนี้!");
			}else{
				alert(data);
			}
			window.opener.location.reload();
			window.close();
		});
	}
}
</script>
<body>
<?php
// หาหมายเหตุในการตั้งหนี้
$qry = pg_query("select * from \"thcap_temp_otherpay_debt\" where \"debtID\" = '$debtID' ");
//$nub = pg_num_rows($qry);
while($res = pg_fetch_array($qry))
{
	$debtRemark = $res["debtRemark"]; // หมายเหตุในการตั้งหนี้
	$typePayID = $res["typePayID"]; //ประเภทการหนี้
	$typePayRefValue = $res["typePayRefValue"]; //ค่าอ้างอิงหนี้
	$contractID = $res["contractID"]; //เลขที่สัญญา
	$doerID = $res["doerID"]; // รหัสพนักงานที่ทำรายการ
}
if($debtRemark == "") // ถ้าไม่ได้ระบุหมายเหตุในการตั้งหนี้
{
	$debtRemark = "ไม่ได้ระบุหมายเหตุ";
}

// ตรวจสอบสิทธิ
if($id_user != $doerID || $emplevel <= 1)
{
	// ถ้า คนละคนกัน หรือ level น้อยกว่าหรือเท่ากับ 0 สามารถทำงานได้ตามปกติ
}
else
{
	$appvCan = "disabled title=\"ไม่สามารถดำเนินการได้ ผู้อนุมัติจะต้องเป็นบุคคล คนละคนกับ ผู้ตั้งหนี้\" ";
}
?>
<br>
<center>
<h2>หมายเหตุ</h2>
<fieldset style="width:50%"><legend>รายละเอียด</legend>
<table border="0">
<?php //แสดงข้อมูล ราบละเอียด 
	$qrt_sql=pg_query("select \"contractID\",\"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"debtDueDate\"
	from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID'");
	$row_num=pg_num_rows($qrt_sql);
	if ($row_num>0) {//มีข้อมูลจริง  
		$res_sql=pg_fetch_array($qrt_sql);
		// หารายละเอียดค่าใช้จ่ายนั้นๆ
		$typePayID=$res_sql["typePayID"];
		$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
		$res_sql_tpDesc=pg_fetch_array($qry_tpDesc);
	?>
		<tr><td align="right"><b>เลขที่สัญญา :</b></td><td align="left"><?php echo $res_sql["contractID"];?></td></tr>
		<tr><td align="right"><b>รหัสประเภทค่าใช้จ่าย :</b></td><td align="left"><?php echo $typePayID;?></td></tr>
		<tr><td align="right"><b>รายละเอียดค่าใช้จ่าย :</b></td><td align="left"><?php echo $res_sql_tpDesc["tpDesc"];?></td></tr>
		<tr><td align="right"><b>ค่าอ้างอิงของค่าใช้จ่าย :</b></td><td align="left"><?php echo $res_sql_tpDesc["tpFullDesc"].' - '.$res_sql["typePayRefValue"];?></td></tr>
		<tr><td align="right"><b>วันที่ตั้งหนี้ :</b></td><td align="left"><?php echo $res_sql["typePayRefDate"];?></td></tr>
		<tr><td align="right"><b>วันที่ครบกำหนดชำระ :</b></td><td align="left"><?php if($res_sql["debtDueDate"] != ""){echo $res_sql["debtDueDate"];}else{echo "ไม่มีวันที่ครบกำหนดชำระ";} ?></td></tr>
		<tr><td align="right"><b>จำนวนหนี้ (รวมาภาษีมูลค่าเพิ่ม ถ้ามี):</b></td><td align="left"><?php echo number_format($res_sql["typePayAmt"],2);?></td></tr>	
		
<?php	}else{ //ไม่มีข้อมูล ?>
		<tr><td align="center" colspan="2"><b>--ไม่พบข้อมูล--</b></tr>
<?php	} ?>
</table>

</fieldset>

<fieldset style="width:50%"><legend>หมายเหตุ</legend>
	<textarea cols="40" rows="7" readonly><?php echo $debtRemark; ?></textarea>
	
</fieldset>
<br>
<?php
$qry_cancel_note_chk = pg_query("SELECT * FROM \"thcap_temp_otherpay_debt\" WHERE \"typePayID\" = '$typePayID' AND \"typePayRefValue\" = '$typePayRefValue' AND \"contractID\" = '$contractID' AND \"debtID\" != '$debtID'");
$row_cancel_note_chk = pg_num_rows($qry_cancel_note_chk);
IF($row_cancel_note_chk > 0){
	$num = 1;
	$qry_debt_desc = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" WHERE \"tpID\" = '$typePayID'");
	list($descdebt) = pg_fetch_array($qry_debt_desc);
	
	echo "<h2>เลขที่สัญญา: <font color=\"red\">$contractID</font> <br>ประเภทหนี้:  <font color=\"red\">$descdebt</font> <br>เลขอ้างอิง:<font color=\"red\"> $typePayRefValue</font> <br>เคยมีการขอยกเลิก/ยกเว้นหนี้ไปแล้ว <font color=\"red\">$row_cancel_note_chk</font> ครั้ง</h2>";
	echo "<b>...เหตุผลการยกเว้น...</b><br><textarea cols=\"50\" rows=\"4\" readonly>";	
		while($re_cancel_note_chk = pg_fetch_array($qry_cancel_note_chk)){
			$cancel_debt = $re_cancel_note_chk["debtID"];
			$qry_cancel_note = pg_query("SELECT \"remark\" FROM \"thcap_temp_except_debt\" WHERE \"debtID\" = '$cancel_debt' AND \"Approve\" = 't' ORDER BY \"appvStamp\"");
			while($re_cancel_note_chk = pg_fetch_array($qry_cancel_note)){
				$note_except = $re_cancel_note_chk["remark"];
				
				echo "ครั้งที่  $num :: $note_except \n";
				$num++;
			}
		}	
	echo "</textarea>";

}
?>
<br>
<br>
<?php if($show=='1' ){?>
<input type="button" value="อนุมัติ" <?php echo $appvCan; ?> onclick="appv('1');"/>
<input type="button" value="ไม่อนุมัติ" <?php echo $appvCan; ?> onclick="appv('0');"/>
<br>
<br>
<?php }?>
<input type="button" value="Close" onclick="window.close();"/>
</center>
</body>
</html>