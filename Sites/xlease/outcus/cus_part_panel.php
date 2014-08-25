<?php
include("../config/config.php");

$regis = $_GET['regis'];
$nowdate = nowDate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<?php
$qry_name=pg_query("select * from \"Fc\" WHERE \"C_REGIS\" = '$regis'");
if($res_name=pg_fetch_array($qry_name)){
    $C_REGIS=$res_name["C_REGIS"];
    $C_CARNUM=$res_name["C_CARNUM"];
    $C_COLOR=$res_name["C_COLOR"];
}

$qry_name=pg_query("select * from \"UNContact\" WHERE \"C_REGIS\" = '$regis'");
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $arr_idno .= $IDNO.",";
    
    $qry_name1=pg_query("select \"ACStartDate\",\"SignDate\" from \"FpOutCus\" WHERE \"IDNO\" = '$IDNO'");
    if($res_name1=pg_fetch_array($qry_name1)){
        $ACStartDate=$res_name1["ACStartDate"];
        $SignDate=$res_name1["SignDate"];
    }
     if(empty($ACStartDate)){ $ACStartDate = "ไม่พบข้อมูล"; }
     if(empty($SignDate)){ $SignDate = "ไม่พบข้อมูล"; }
    
    $qry_vcorp=pg_query("select * from corporate.\"VCorpContact\" WHERE \"IDNO\" = '$IDNO'");
    if($res_vcorp=pg_fetch_array($qry_vcorp)){
        $TypeContact=$res_vcorp["TypeContact"];
        $AcClose=$res_vcorp["AcClose"];
        if(!$AcClose || $AcClose == 'FALSE' || $AcClose == 'f'){
            $last_idno = $IDNO;
        }
        
        if($TypeContact != "00"){
            $qry_tc=pg_query("select * from corporate.\"type_corp\" WHERE \"contact_code\" = '$TypeContact'");
            if($res_tc=pg_fetch_array($qry_tc)){
                $dtl_code=$res_tc["dtl_code"];
            }
        }
    }
    
?>
<div class="wbox">

<div style="float:left">
<b>IDNO</b> <?php echo "$IDNO"; ?> <b>ชื่อ/สกุล</b> <?php echo "$full_name"; ?> <b>รูปแบบ</b> <?php echo "$TypeContact $dtl_code"; ?>
</div>
<div style="float:right">
<font color="red"><b>วันทำสัญญา</b> <?php echo $SignDate; ?></font> <font color="blue"><b>วันที่งวดแรก</b> <?php echo $ACStartDate; ?></font>
</div>
<div style="clear:both">

<hr />

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#E0E0E0" style="font-weight:bold; text-align:center">
    <td width="25%">Invoice NO</td>
    <td width="25%">DueDate</td>
    <td width="25%">RefReceipt</td>
    <td width="25%">Amt</td>
</tr>
<?php
$qry_corp=pg_query("select * from corporate.\"corpinvoice\" WHERE \"IDNO\" = '$IDNO' AND \"Cancel\"='false' ORDER BY \"DueDate\" ASC");
$numrow_corp = pg_num_rows($qry_corp);
while($res_corp=pg_fetch_array($qry_corp)){
    $inv_no=$res_corp["inv_no"];
    $DueDate=$res_corp["DueDate"];
    $amt=$res_corp["amt"];
    $RefReceipt=$res_corp["RefReceipt"];
echo "
<tr>
    <td>$inv_no</td>
    <td>$DueDate</td>
    <td>$RefReceipt</td>
    <td align=right>".number_format($amt,2)."</td>
</tr>";
}

if($TypeContact == "00"){
    echo "<tr><td colspan=4 align=center>- ไม่มีประวัติการเข้าร่วม -</td></tr>";
}elseif($numrow_corp == 0){
    echo "<tr><td colspan=4 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>
</div>

<?php
}
?>

</body>
</html>