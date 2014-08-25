<?php 
include("../config/config.php"); 
$select_com = pg_escape_string($_GET['com']);
$select_date = pg_escape_string($_GET['date']);

if(empty($select_com) || empty($select_date)){
    echo "กรุณาเลือกข้อมูล !";
    exit;
}

$qry_cq=pg_query("select * from insure.\"VListUnForcePayBy\" WHERE \"Company\"='$select_com' AND \"CQDate\"='$select_date' AND \"Cancel\"='false' ORDER BY \"IDNO\" DESC LIMIT 1 ");
if($res_cq=pg_fetch_array($qry_cq)){
    $CQID = $res_cq["CQID"];
    $CQDate = $res_cq["CQDate"];
    $CQAmt = $res_cq["CQAmt"];
    $Remark = $res_cq["Remark"];
}
?>

<div style="margin: 5px 0px 5px 0px; text-align:right; font-weight:bold">
เลขที่เช็ค <?php echo "$CQID"; ?> | วันที่บนเช็ค <?php echo "$CQDate"; ?> | <span style="color:green">ยอดเงิน <?php echo number_format($CQAmt,2); ?> บาท.</span>
</div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
    <td align="center">InsID</td>
    <td align="center">IDNO</td>
    <td align="center">ชื่อ-สกุล</td>
    <td align="center">StartDate</td>
    <td align="center">Premium</td>
    <td align="center">CoPayInsAmt</td>
</tr>
<?php
$qry_if=pg_query("select * from insure.\"VListUnForcePayBy\" WHERE \"Company\"='$select_com' AND \"CQDate\"='$select_date' AND \"Cancel\"='false' ORDER BY \"IDNO\" ");
$rows = pg_num_rows($qry_if);
while($res_if=pg_fetch_array($qry_if)){
        $InsUFIDNO = $res_if["InsUFIDNO"];
        $IDNO = $res_if["IDNO"];
        $Premium = $res_if["Premium"];
        $StartDate = $res_if["StartDate"];
        $InsID = $res_if["InsID"];
        $CoPayInsAmt = $res_if["CoPayInsAmt"];
            $summary += $CoPayInsAmt;
        $CoPayInsID = $res_if["CoPayInsID"];

    $qry_name=pg_query("select full_name from insure.\"VInsUnforceDetail\" WHERE \"InsUFIDNO\"='$InsUFIDNO'");
    if($res_name=pg_fetch_array($qry_name)){
        $full_name = $res_name["full_name"];
    }
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="left"><?php echo "$InsID"; ?></td>
    <td align="center"><?php echo "$IDNO"; ?></td>
    <td align="left"><?php echo "$full_name"; ?></td>
    <td align="center"><?php echo "$StartDate"; ?></td>
    <td align="right"><?php echo number_format($Premium,2); ?></td>
    <td align="right"><?php echo number_format($CoPayInsAmt,2); ?></td>
</tr>
<?php

    $sum_pm += $Premium;
    $sum_cp += $CoPayInsAmt;
}

if($rows == 0){
?>
<tr bgcolor="#FFFFFF" style="font-size:12px;">
    <td align="center" colspan=10>ไม่พบข้อมูล</td>
</tr>
<?php
}else{
?>
<tr bgcolor="#FFFFFF" style="font-size:12px; font-weight:bold">
    <td align="right" colspan=4>ผลรวม</td>
    <td align="right"><?php echo number_format($sum_pm,2); ?></td>
    <td align="right"><?php echo number_format($sum_cp,2); ?></td>
</tr>
<?php
}
?>
</table>


<div>
<b style="color:green">Remark</b>

<?php
if(!empty($Remark)){
?>

<br /><textarea name="txtarea" id="txtarea" rows="5" cols="55" readonly><?php echo $Remark; ?></textarea>

<?php
}else{ echo "ไม่พบ Remark."; }
?>

</div>
