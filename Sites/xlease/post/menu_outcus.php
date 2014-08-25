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
    width: 200px;
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
</style>


<?php
$idno = $_SESSION["ses_idno"];
$scusid = $_SESSION["ses_scusid"];

$code = md5(uniqid(rand().time(), true));

?>

<div id="Content">
<div class="title">ตารางการชำระเงินลูกค้า</div>
<div class="listmenu">
<!--<a class="menu" href="../outcus/select.php">ค้นหาข้อมูล</a>-->
<a class="menu" href="frm_close_cuspayment.php?menu=outcus">คิดยอดปิดบัญชี</a>
<a class="menu" href="#" onclick="javascript:popU('frm_otherpay.php?idno=<?php echo "$idno"; ?>','<?php echo "a0_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">รายการชำระค่าอื่นๆ</a>
<a class="menu" href="#" onclick="javascript:popU('frm_contact.php?idno=<?php echo "$idno"; ?>','<?php echo "a1_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=630,height=575')">ที่ติดต่อลูกค้า</a>
<a class="menu" href="#" onclick="javascript:popU('frm_detailcheque.php?idno=<?php echo "$idno"; ?>','<?php echo "a2_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">รายละเอียดเช็ค</a>
<a class="menu" href="#" onclick="javascript:popU('frm_force_show.php?idno=<?php echo "$idno"; ?>','<?php echo "a3_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ประกันภัย (พรบ)</a>
<a class="menu" href="#" onclick="javascript:popU('frm_unforce_show.php?idno=<?php echo "$idno"; ?>','<?php echo "a4_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ประกันภัยสมัครใจ</a>
<a class="menu" href="#" onclick="javascript:popU('frm_live_show.php?idno=<?php echo "$idno"; ?>','<?php echo "a7_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ประกันภัยคุ้ัมครองหนี้</a>
<a class="menu" href="#" onclick="javascript:popU('follow_up_cus.php?idno=<?php echo "$idno"; ?>&scusid=<?php echo "$scusid"; ?>','<?php echo "a5_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')">บันทึกการติดตาม</a>
<a class="menu" href="#" onclick="javascript:popU('cus_detail.php?idno=<?php echo "$idno"; ?>&scusid=<?php echo "$scusid"; ?>','<?php echo "a6_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')">ข้อมูลลูกค้า</a>
</div>
</div>