<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$iduser = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime();

pg_query("BEGIN");
$status = 0;

$mm = pg_escape_string($_POST["mm"]);
$yy = pg_escape_string($_POST["yy"]);
$save_name = pg_escape_string($_POST["save_name"]);
$save_notes = pg_escape_string($_POST["save_notes"]);

$save_name = checknull($save_name);
$save_notes = checknull($save_notes);

$yy0 = $yy-1; // ปีก่อนหน้า


$qry_save = "insert into account.thcap_ledger_save_head(save_name, ledger_month, ledger_year, \"doerID\", \"doerStamp\", notes)
			values($save_name, '$mm', '$yy', '$iduser', '$nowDateTime', $save_notes) returning save_id ";
$run_save = pg_query($qry_save);
if($run_save)
{
	$save_id = pg_result($run_save,0);
	
	// บันทึก ยอดยกมา
	$qry_save_beginning = "insert into account.thcap_ledger_save_detail(auto_id_ref, ledger_stamp, abh_autoid, \"accBookserial\", abd_accbookid, abd_booktype, abd_amount, is_ledgerstatus,
							ledger_balance, doerid, doerstamp, income_statement, balance_sheet, save_id, ledger_id)
						select auto_id_ref, ledger_stamp, abh_autoid, \"accBookserial\", abd_accbookid, abd_booktype, abd_amount, is_ledgerstatus,
							ledger_balance, doerid, doerstamp, income_statement, balance_sheet, '$save_id', auto_id
						from account.thcap_ledger_detail where EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12'
						and \"is_ledgerstatus\" in('1','2')";
	$run_save_beginning = pg_query($qry_save_beginning);
	if($run_save_beginning){}else{$status++;}
	
	// บันทึก ข้อมูลปัจจุบัน
	$qry_save_detail = "insert into account.thcap_ledger_save_detail(auto_id_ref, ledger_stamp, abh_autoid, \"accBookserial\", abd_accbookid, abd_booktype, abd_amount, is_ledgerstatus,
							ledger_balance, doerid, doerstamp, income_statement, balance_sheet, save_id, ledger_id)
						select auto_id_ref, ledger_stamp, abh_autoid, \"accBookserial\", abd_accbookid, abd_booktype, abd_amount, is_ledgerstatus,
							ledger_balance, doerid, doerstamp, income_statement, balance_sheet, '$save_id', auto_id
						from account.thcap_ledger_detail where EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\") <= '$mm' ";
	$run_save_detail = pg_query($qry_save_detail);
	if($run_save_detail){}else{$status++;}
}
else
{
	$status++;
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><br><font color=\"#0000FF\">บันทึกสำเร็จ</font></center>";
	echo "<script>window.opener.document.forms[0].updatelistbox.click();</script>";
	echo "<center><br><input type=\"button\" value=\" ตกลง \" onclick=\"window.close();\" style=\"cursor:pointer;\"/></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><br><font color=\"#FF0000\">บันทึกผิดพลาด !</font></center>";
	echo "<script>window.opener.document.forms[0].updatelistbox.click();</script>";
	echo "<center><br><input type=\"button\" value=\" CLOSE \" onclick=\"window.close();\" style=\"cursor:pointer;\"/></center>";
}
?>