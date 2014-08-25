<?php
session_start();
?>

<style type="text/css">
#Content {
    overflow: hidden;
    width: 100%;
    margin-top: 0px;
    color: #777;
    font-family: tahoma;
    font-size: 13px;
}

a:link, a:visited, a:hover {
    color: #585858;
    text-decoration: none;
}
a:hover {
    color: #ACACAC;
    text-decoration: underline;
}

#Content .title {
    background-color: #A8A800;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    margin: 0px;
    padding: 3px 3px 3px 2px;
    width: 300px;
    text-align: center;
}

#Content .listmenu {
    padding-top: 5px;
    border-bottom: black;
}

#Content .menu {
    background-color: #FAF2D3;
    font-size: 12px;
    color: #585858;
    margin: 0px;
    padding: 3px 3px 3px 3px;
    border: 1px solid #C0C0C0;
}
#Content .menu2 {
    background-color: #FAF2D3;
    font-size: 12px;
    color: #585858;
    margin: 0px;
    padding: 3px 3px 3px 3px;
    border: 1px solid #C0C0C0;
}
</style>


<?php
$idno = $_SESSION["ses_idno"];
$scusid = $_SESSION["ses_scusid"];

$code = md5(uniqid(rand().time(), true));
if($contractID!="")
{
	//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
	$qrychk=pg_query("select * from \"thcap_contract\" where \"contractID\"='$contractID'");
	if(pg_num_rows($qrychk)>0){
		$qr_ct = pg_query("select \"thcap_get_creditType\"('$contractID') as credit_type");
		if($qr_ct)
		{
			$rs_ct = pg_fetch_array($qr_ct);
			$credit_type = $rs_ct['credit_type'];
		}
	}
}
?>

<div id="Content">
<div class="title">(THCAP) ตารางแสดงการผ่อนชำระ</div>
<div class="listmenu">
<!-- <a class="menu" href="#" onclick="javascript:popU('frm_otherpay.php?idno=<?php echo "$contractID"; ?>','<?php echo "a0_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ข้อมูลที่ติดต่อ</a> -->
<a class="menu" href="#" onclick="javascript:popU('frm_address.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">ข้อมูลที่ติดต่อ</a>
<a class="menu" href="#" onclick="javascript:popU('follow_up_cus.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">บันทึกการติดตาม</a>
<a class="menu" href="#" onclick="javascript:popU('frm_otherpay.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=970,height=600')">รายการรับชำระทั้งหมด</a>
<?php
if($credit_type!="HIRE_PURCHASE"&&$credit_type!="LEASING")
{
?>
<a class="menu" href="#" onclick="javascript:popU('frm_interest_history.php?idno=<?php echo "$contractID"; ?>
','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=400')">ประวัติการเปลี่ยนแปลงอัตราดอกเบี้ย</a>
<?php
}
?>
<a class="menu" href="#" onclick="javascript:popU('frm_report_letter.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">ประวัติการส่งจดหมาย</a>
<a class="menu" href="#" onclick="javascript:popU('../Payments_Other/gen_debt_invoice_result.php?contractid=<?php echo "$contractID"; ?>&statusshow=1;','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=600')">ดูใบแจ้งหนี้</a>
<a class="menu" href="#" onclick="javascript:popU('frm_report_address.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=960,height=600')">ประวัติที่อยู่ตามสัญญา</a>
<a class="menu" href="#" onclick="javascript:popU('frm_list_account_contract.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=960,height=600')">รายการบันทึกบัญชีของสัญญา</a>
</div>
</div>