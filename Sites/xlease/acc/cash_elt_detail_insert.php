<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<style type="text/css">
.result {
    font-size: 12px;
    line-height: 17px;
    height: 620px;
    overflow: auto;
    border: 1px solid #C0C0C0;
    background-color: #E0E0E0;
    padding-left: 3px;
    padding-right: 3px;
}
</style>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="  กลับ  " class="ui-button" onclick="window.location='cash_elt.php'"></div>
<div style="float:right">&nbsp;</div>
<div style="clear:both"></div>

<fieldset><legend><B>ตัดรายการเงินที่ไม่ใช่ Bill Payment</B></legend>

<div class="ui-widget" style="text-align:center">

<?php
$branch_id=$_SESSION["av_officeid"];
$id_user=$_SESSION["av_iduser"];
$datenow=date("Y-m-d");

$cid=pg_escape_string($_POST["cid"]);
$idno=pg_escape_string($_POST["idno"]);
$counter = pg_escape_string($_POST['counter']);
$divmoney = pg_escape_string($_POST['divmoney']);

$arr_idno = explode("#",$idno);

$select = pg_query("SELECT * FROM \"VContact\" WHERE \"IDNO\"='$arr_idno[0]'");
if($res=pg_fetch_array($select)){
    $full_name = $res['full_name'];
    $ref1 = $res['TranIDRef1'];
    $ref2 = $res['TranIDRef2'];
}

pg_query("BEGIN WORK");
$status = 0;

// ตรวจสอบว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_chk = pg_query("SELECT * FROM \"TranPay\" WHERE \"PostID\" = '$cid' AND \"post_on_asa_sys\" = 'FALSE' ");
$row_chk = pg_num_rows($qry_chk);
if($row_chk == 0)
{
	$status++;
	$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";
}

$up_sql="UPDATE \"TranPay\" SET \"ref1\"='$ref1',\"ref2\"='$ref2',\"ref_name\"='$full_name',\"post_on_asa_sys\"='TRUE',\"post_on_date\"='$datenow',\"post_to_idno\"='$arr_idno[0]' WHERE \"PostID\"='$cid' AND \"post_on_asa_sys\" = 'FALSE' RETURNING \"branch_id\" ";
$result2 = pg_query($up_sql);
if($result2)
{
	$check_update2 = pg_fetch_result($result2,0);
	if($check_update2 == ""){$status++;}
}
else
{
	$status++;
}

$up1_sql="UPDATE \"PostLog\" SET \"UserIDAccept\"='$id_user',\"AcceptPost\"='TRUE' WHERE \"PostID\"='$cid' RETURNING \"PostID\" ";
$result21 = pg_query($up1_sql);
if($result21)
{
	$check_update21 = pg_fetch_result($result21,0);
	if($check_update21 == ""){$status++;}
}
else
{
	$status++;
}

if($divmoney > 0){
    $in_detail="insert into \"DetailTranpay\" (\"PostID\",\"IDNO\",\"TypePay\",\"Amount\" ) values ('$cid','$arr_idno[0]','1','$divmoney')";
    if(!$result1=pg_query($in_detail)){
        $status+=1;
    }
}

$nub = 0;
if($counter>0){
    for($i=1; $i<=$counter; $i++){
         $typepayment = pg_escape_string($_POST['typepayment'.$i]);
         $amt = pg_escape_string($_POST['amt'.$i]);
         $newidno = pg_escape_string($_POST['newidno'.$i]);
         if($typepayment=="" || $amt==""){
            echo "ข้อมูลรายการที่ #$i ไม่ครบถ้วน $typepayment | $amt<br />";
         }else{
             $nub++;
             
             if($typepayment == "133"){
                 $in_detail="insert into \"DetailTranpay\" (\"PostID\",\"IDNO\",\"TypePay\",\"Amount\",\"RefID\") values ('$cid','$arr_idno[0]',$typepayment,'$amt','$newidno')";
             }else{
                 $in_detail="insert into \"DetailTranpay\" (\"PostID\",\"IDNO\",\"TypePay\",\"Amount\" ) values ('$cid','$arr_idno[0]',$typepayment,'$amt')";
             }
             if(!$result1=pg_query($in_detail)){
                $status+=1;
             }
         
         }
    }
}

$qry_passtr=pg_query("select pass_tranpay('$cid','$arr_idno[0]','$id_user')");
$res_pass=pg_fetch_result($qry_passtr,0);
if(!$res_pass){
    $status+=1;
}


if($status == 0){
    pg_query("COMMIT");
    echo "เพิ่มข้อมูลเรียบร้อยแล้ว<br><br><iframe border=\"0\" frameborder=\"no\" framespacing=\"0\" src=\"frm_recprint_acc_tr_$_SESSION[session_company_code].php?pid=$cid\" 
    scrolling=\"yes\" width=\"100%\" height=\"400\" allowtransparency=\"true\"></iframe>";
    //echo "เพิ่มข้อมูลเรียบร้อยแล้ว<br><br><input type=\"button\" value=\"Print\" name=\"pnt\" id=\"pnt\" onclick=\"javascript:popU('frm_recprint_acc_tr.php?pid=$cid','$cid','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600');\">";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถเพิ่มข้อมูลได้";
	if($error != ""){echo "<br>$error";}
	echo "<br><br>";
}
?>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>