<?php
session_start();
include("../config/config.php");  

$dt = $_POST['idno']; $arr_dt = explode("#",$dt);
$cid = $_POST["idno_names"];
//$cid = $_GET["cid"];
//$pid = $_GET["id"];
$nowdate = nowDate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td> 

<div class="wrapper">

<div style="float:left"><input name="button" type="button" onclick="window.location='frm_cheque_edit_select.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
<div style="clear:both"></div>

<fieldset><legend><B>แก้ไขรายการเช็ค</B></legend>

<?php
$qry1=pg_query("select * from \"FCheque\" WHERE \"PostID\"='$arr_dt[1]' AND \"ChequeNo\"='$cid' AND \"IsPass\"='FALSE'");
if($res1=pg_fetch_array($qry1)){
    $ChequeNo = $res1["ChequeNo"];
    $BankName = $res1["BankName"];
    $BankBranch = $res1["BankBranch"];
    $DateOnCheque = $res1["DateOnCheque"]; if(empty($DateOnCheque)) $DateOnCheque = $nowdate;
    $DateEnterBank = $res1["DateEnterBank"]; if(empty($DateEnterBank)) $DateEnterBank = $nowdate;
?>

<form name="frm_1" id="frm_1" action="frm_cheque_edit_detail_ok.php" method="post">
<input type="hidden" name="in_PostID" value="<?php echo $arr_dt[1]; ?>">
<input type="hidden" name="in_ChequeNo_old" value="<?php echo $ChequeNo; ?>">
<table width="100%" cellspacing="1" cellpadding="5" border="0">
<tr>
    <td width="15%"><b>เลขที่เช็ค</b></td>
    <td width="35%"><input type="text" name="in_ChequeNo" value="<?php echo $ChequeNo; ?>" size="20"></td>
    <td width="20%"></td>
    <td width="30%"></td>
</tr>
<tr>
    <td><b>ธนาคาร</b></td>
    <td>
<select name="in_BankName">
<?php
$qry2=pg_query("select * from \"BankCheque\" ORDER BY \"BankName\" ASC");
while($res2=pg_fetch_array($qry2)){
    $BankCode2 = $res2["BankCode"];
    $BankName2 = $res2["BankName"];
    
    if($BankName == $BankCode2)
        echo "<option value=\"$BankCode2\" selected>$BankName2</option>";
    else
        echo "<option value=\"$BankCode2\">$BankName2</option>";
}

?>    
</select>
    </td>
    <td><b>สาขา</b></td>
    <td><input type="text" name="in_BankBranch" value="<?php echo $BankBranch; ?>" size="30"></td>
</tr>
<tr>
    <td><b>วันที่บนเช็ค</b></td>
    <td>
<input type="text" size="13" style="text-align:center;" id="DateOnCheque" name="DateOnCheque" value="<?php echo $DateOnCheque; ?>" readonly><input name="button1" type="button" onclick="displayCalendar(document.frm_1.DateOnCheque,'yyyy-mm-dd',this)" value="ปฏิทิน">
    </td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td colspan="4" align="center"><br /><input type="submit" name="submit" value="บันทึก"></td>
</tr>
</table>
</form>

<?php
}else{
    echo "<center>เช็ครายการนี้ผ่านแล้ว ไม่สามารถแก้ไขได้</center>";
}
?>

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>