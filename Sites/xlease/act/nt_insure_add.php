<?php
session_start();
include("../config/config.php");
$get_userid = $_SESSION["av_iduser"];
$officeid=$_SESSION["av_officeid"];
$now_date = nowDate();//ดึง วันที่จาก server
$to_date =date("Y-m-d", strtotime("+7 day",strtotime($now_date))); //บวกวันที่เพิ่ม 7 วัน
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$idno = pg_escape_string($_POST['idno']);
$chkbox = pg_escape_string($_POST['chkbox']);
$fee = pg_escape_string($_POST['fee']);
$datepicker = pg_escape_string($_POST['datepicker']);

$txt_detail = "";
$data_arr = "";
foreach($chkbox AS $v){
    $arr_chk = explode("#",$v);
    $sum += $arr_chk[1];
    $data_arr .= $arr_chk[0].":".$arr_chk[1]."|";
    
    $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$arr_chk[0]'");
    if($res_nn=pg_fetch_array($qry_nn)){
        $TName = $res_nn["TName"];
    }
    $txt_detail .= "- $TName ยอดเงิน ".number_format($arr_chk[1],2)." บาท\n";
}

if($fee != 0 && !empty($fee)){
    $txt_detail .= "- ค่าธรรมเนียม ยอดเงิน ".number_format($fee,2)." บาท";
}

$sum += $fee;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />

<script language="javascript">
var win = null;

function NewWindow(mypage,myname,w,h,scroll){
    LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
    settings =
    'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
    win = window.open(mypage,myname,settings)
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="  Back  " class="ui-button" onclick="javascript:window.location='nt_insure.php';"></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>NT ประกันภัย</B></legend>

<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

$genid=pg_query("select generate_id('$now_date','$officeid',3)");
$r_genid=pg_fetch_result($genid,0);
if(empty($r_genid)){
    $status++;
}

$qry_vcus=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE  \"R_Receipt\" is null and \"IDNO\"='$idno' LIMIT 1");
if($resvc=pg_fetch_array($qry_vcus)) {
    $DueDate = $resvc["DueDate"];
}

$in_sql="insert into \"NTHead\" (\"NTID\",\"IDNO\",\"do_date\",\"to_date\",\"remark\",\"makerid\",\"CusState\",\"remine_date\") 
values ('$r_genid','$idno','$now_date','$to_date','#INS','$get_userid','0','$DueDate')";
if( !pg_query($in_sql) ){
    $status++;
}

$in_sql2="insert into \"NTDetail\" (\"NTID\",\"Detail\",\"Amount\",\"MainDetail\") values ('$r_genid','$txt_detail','$sum','true')";
if( !pg_query($in_sql2) ){
    $status++;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) ออก NT ประกันภัย', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br />
<input type=\"button\" name=\"btnprint\" id=\"btnprint\" value=\"พิมพ์จดหมาย\" onClick=\"NewWindow('nt_insure_print.php?idno=$idno&dataarr=$data_arr&fee=$fee&datepicker=$datepicker','name','800','600','yes'); return false\">";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้<hr>$in_sql<hr>$in_sql2";
}
?>
</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>