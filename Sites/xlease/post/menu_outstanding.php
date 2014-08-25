<?php
session_start();
include("../config/config.php");
?>
<html>
<title>MENU</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<body>
<style type="text/css">
<!--
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
-->
</style>

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}

$(function(){
    $(window).bind("beforeunload",function(event){
        closeAll();
        return msg;
    });
});
</script>

<?php
function randomToken($len) { 
srand( date("s") ); 
$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
$chars.= "1234567890";
$ret_str = ""; 
$num = strlen($chars); 
for($i=0; $i < $len; $i++) { 
$ret_str.= $chars[rand()%$num];
} 
return $ret_str; 
}

$code = randomToken(15);

$idno = $_SESSION["ses_idno"];
$cusid = $_SESSION["ses_cudid_contact"];
$scusid = $_SESSION["ses_scusid"];

$search_top = $idno;
do{
    $qry_top=pg_query("select * from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_top=pg_fetch_array($qry_top);
    $CusID=$res_top["CusID"];
    $arr_idno[$res_top["IDNO"]]=$CusID;
    $search_top=$res_top["IDNO"];
}while(!empty($search_top));

$qry_top=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;

if(!empty($P_TransferIDNO)){
    do{
        $qry_fp2=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$P_TransferIDNO'");
        $res_fp2=pg_fetch_array($qry_fp2);
        $CusID=$res_fp2["CusID"];
        $arr_idno[$P_TransferIDNO]=$CusID;
        $P_TransferIDNO=$res_fp2["P_TransferIDNO"];
    }while(!empty($P_TransferIDNO));
}

$_SESSION["arr_idno"] = $arr_idno;
?>

<div id="Content">
<div class="title">ตารางการชำระเงินลูกค้า</div>
<div class="listmenu">
<a class="menu" href="frm_cal_cuspayment.php" target=frm_r>คิดยอดชำระ ณ วันที่</a>
<a class="menu" href="frm_close_cuspayment.php" target=frm_r>คิดยอดปิดบัญชี</a>
<a class="menu" href="#" onclick="javascript:popU('frm_otherpay.php?idno=<?php echo "$idno"; ?>','<?php echo "aa0_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">รายการชำระค่าอื่นๆ</a>
<a class="menu" href="#" onclick="javascript:popU('frm_contact.php?idno=<?php echo "$idno"; ?>&cusid=<?php echo "$cusid"; ?>','<?php echo "aa1_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=630,height=575')">ที่ติดต่อลูกค้า</a>
<a class="menu" href="#" onclick="javascript:popU('frm_detailcheque.php?idno=<?php echo "$idno"; ?>','<?php echo "aa2_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">รายละเอียดเช็ค</a>
<a class="menu" href="#" onclick="javascript:popU('frm_force_show.php?idno=<?php echo "$idno"; ?>','<?php echo "aa3_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ประกันภัย (พรบ)</a>
<a class="menu" href="#" onclick="javascript:popU('frm_unforce_show.php?idno=<?php echo "$idno"; ?>','<?php echo "aa4_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=730,height=600')">ประกันภัยสมัครใจ</a>
<a class="menu" href="#" onclick="javascript:popU('follow_up_cus.php?idno=<?php echo "$idno"; ?>&scusid=<?php echo "$scusid"; ?>','<?php echo "aa5_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')">บันทึกการติดตาม</a>
<a class="menu" href="#" onclick="javascript:popU('cus_detail.php?idno=<?php echo "$idno"; ?>&scusid=<?php echo "$scusid"; ?>','<?php echo "aa6_$code"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')">ข้อมูลลูกค้า</a>
</div>
</div>