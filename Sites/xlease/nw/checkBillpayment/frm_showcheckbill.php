<?php
include("../../config/config.php");
$id_tranpay=$_GET["id_tranpay"];
$datepicker=$_GET["datepicker"];
$ref1=$_GET["ref1"];
$ref2=$_GET["ref2"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>ธนาคาร</td>
    <td>วันที่โอน</td>
    <td>เวลาที่โอน</td>
    <td>terminal_id</td>
    <td>ref1</td>
    <td>ref2</td>
    <td>ref_name</td>
    <td>post_to_idno</td>
    <td>จำนวนเงิน</td>
</tr>
<?php
$nub = 0;
$query=pg_query("select * from \"TranPay\" WHERE \"post_on_date\"='$datepicker' and \"ref1\"='$ref1' and \"ref2\"='$ref2' and \"id_tranpay\"='$id_tranpay'");
while($resvc=pg_fetch_array($query)){
    $n++;
    $bank_no = $resvc['bank_no'];
    $tr_date = $resvc['tr_date'];
    $tr_time = $resvc['tr_time'];
    $terminal_id = $resvc['terminal_id'];
    $ref1 = $resvc['ref1'];
    $ref2 = $resvc['ref2'];
    $ref_name = $resvc['ref_name'];
    $post_to_idno = $resvc['post_to_idno'];
    $amt = $resvc['amt'];
	$id_tranpay=$resvc['id_tranpay'];
    
    if(($old_bank != $bank_no) && $n!=1){
        echo "<tr><td colspan=\"9\" align=\"right\"><b>ธนาคาร $old_bank_name รวม $nub รายการ</b></td></tr>";
        $nub = 0;
    }
    
    $bankname = "";
    $query2=pg_query("select \"bankname\" from \"bankofcompany\" WHERE \"bankno\"='$bank_no' ");
    if($resvc2=pg_fetch_array($query2)){
        $bankname = $resvc2['bankname'];
    }

        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\" align=\"left\">";
        }else{
            echo "<tr class=\"even\" align=\"left\">";
        }
?>
        <td><?php echo $bankname; ?></td>
        <td align="center"><?php echo $tr_date; ?></td>
        <td align="center"><?php echo $tr_time; ?></td>
        <td><?php echo $terminal_id; ?></td>
        <td><?php echo $ref1; ?></td>
        <td><?php echo $ref2; ?></td>
        <td><?php echo $ref_name; ?></td>
        <td><?php echo $post_to_idno; ?></td>
        <td align="right"><?php echo number_format($amt,2); ?></td>
    </tr>
<?php
$nub++;
$old_bank = $bank_no;
$old_bank_name = $bankname;
}
?>
</table><br>
<?php
	$query_check=pg_query("select * from \"TranPay_audit\" where \"id_tranpay\"='$id_tranpay'");
	if($rescheck=pg_fetch_array($query_check)){
		$result=$rescheck["result"];
		$auditRemask=$rescheck["auditRemask"];
	}
?>
<table width="600" border="0" cellSpacing="1" cellPadding="1" bgcolor="#F4FED6" align="center">
<tr><td bgcolor="#049746" height="25" colspan="2"><font color="#FFFFFF">&nbsp;<b>ผลการตรวจ</b></font></td></tr>
<tr>
	<td align="right" width="150"><b>ผลการตรวจ :</b></td>
	<td height="50">
		<?php
			if($result==1){
				echo "ผ่าน";
			}else if($result==9){
				echo "ผิดปกติ";
			}
		?>
	</td>
</tr>
<tr height="130">
	<td align="right" valign="top"><b>Remark :</b></td>
	<td valign="top"><textarea name="remark" cols="50" rows="5" readonly="true"><?php echo $auditRemask;?></textarea></td>
</tr>
<tr>
	<td colspan="2" align="center" bgcolor="#FFFFFF" height="50">
		<input type="button" value="ปิด" onclick="window.close();">
	</td>
</tr>
</table>

</body>
</html>