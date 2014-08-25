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
?>

<div id="Content">
<div class="title">(THCAP) แสดงวงเงินและหนี้</div>
<div class="listmenu">
<!-- <a class="menu" href="#" onclick="javascript:popU('frm_otherpay.php?idno=<?php echo "$contractID"; ?>','<?php echo "a0_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ข้อมูลที่ติดต่อ</a> -->
<a class="menu" href="#" onclick="javascript:popU('../thcap_installments/frm_address.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">ข้อมูลที่ติดต่อ</a>
<a class="menu" href="#" onclick="javascript:popU('../thcap_installments/follow_up_cus.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">บันทึกการติดตาม</a>
<a class="menu" href="#" onclick="javascript:popU('../thcap_installments/frm_otherpay.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">รายการชำระค่าอื่นๆ</a>
</div>
</div>