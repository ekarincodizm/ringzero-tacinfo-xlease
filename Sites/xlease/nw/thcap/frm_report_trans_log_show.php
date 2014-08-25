<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$condition=$_GET["condition"];
$detail=trim($_GET["detail"]);


if($condition==1){ //กรณีเลือกวันที่
	if($detail==""){ //กรณีไม่เลือกวันที่ให้แสดงทั้งหมด
		$showtxt="แสดงประวัติการแก้ไขทั้งหมด";
		$con="";
	}else{
		$showtxt="แสดงประวัติการแก้ไขทั้งหมด วันที่จัดการกับข้อมูล : ";
		$con="where date(a.\"dateStamp\")='$detail'";
	}
}else if($condition==2){ //กรณีเลือกรหัสโอน
	$showtxt="รหัสรายการเงินโอน  : ";
	$con ="where a.\"revTranID\"='$detail'";
}else if($condition==3){ //กรณีเลือกผู้ทำรายการ
	list($iduser,$before_username)=explode('-',$detail);
	$showtxt="พนักงานที่ทำรายการ : ";
	$con ="where a.\"id_user\"='$iduser'";
	$nameuser = str_replace('5SPACE5',' ',$before_username);
	$detail = $iduser."-".$nameuser;
}

?>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<div style="width:1000px;margin:0 auto;"><h2><?php echo "$showtxt $detail"?></h2></div>
<table width="1000" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" align="center">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>รหัสรายการเงินโอน</td>
    <td>ผู้ทำรายการ</td>
    <td>ลักษณะการทำรายการ</td>
	<td>ธนาคารที่รับโอน</td>
	<td>ช่องทาง</td>
    <td>จำนวนเงิน</td>
	<td>วันที่และเวลาเงินเข้า</td>
    <td>วันที่ทำรายการ</td>
	<td>เหตุผล </td>
</tr>
<?php
$query=pg_query("select a.\"auto_id\",a.\"revTranID\",a.\"id_user\",b.\"fullname\",a.\"detail\",a.\"BAccount\",a.\"bankRevBranch\",a.\"bankRevAmt\",
a.\"bankRevStamp\",a.\"dateStamp\",\"remark\" 
from \"finance\".\"thcap_receive_transfer_log\" a 
left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\"
$con order by auto_id");

$numrows=pg_num_rows($query);

$p=0;
while($resvc=pg_fetch_array($query)){
	$revTranID = $resvc['revTranID'];
	$id_user = $resvc['id_user'];
	$fullname = $resvc['fullname'];
	$detail = trim($resvc['detail']);
	$BAccount = trim($resvc['BAccount']);
	$bankRevBranch = trim($resvc['bankRevBranch']);
	$bankRevAmt = trim($resvc['bankRevAmt']);
	$bankRevStamp = $resvc['bankRevStamp'];
	$dateStamp = $resvc['dateStamp'];
	$remark = $resvc['remark'];
	$auto_id = $resvc['auto_id'];

	$i+=1;
	if($i%2==0){
		if($numnoapp>0){
			$color="#FFAEB9";
		}else{
			$color="#EDF8FE";
		}
		echo "<tr bgcolor=$color align=\"center\">";
	}else{
		if($numnoapp>0){
			$color="#FFAEB9";
		}else{
			$color="#D5EFFD";
		}
		echo "<tr bgcolor=$color align=\"center\">";
	}
	
	
?>
        <td height="30"><?php echo $revTranID; ?></td>
        <td><?php echo "$id_user-$fullname"; ?></td>
        <td align="left"><?php echo $detail; ?></td>
        <td><?php echo $BAccount; ?></td>
        <td><?php echo $bankRevBranch; ?></td>
        <td><?php echo number_format($bankRevAmt,2);; ?></td>        
		<td><?php echo $bankRevStamp; ?></td>
		<td><?php echo $dateStamp;?></td>
		<td>
		<?php
		if($remark==""){
			echo "-ไม่มี-";
		}else{
		?>
		<img src="images/detail.gif" width="19" height="19" style="cursor:pointer" title="ดูเหตุผล" onclick="popU('thcap_edit_acc_receipt/frm_pop_txt.php?auto_id=<?php echo $auto_id;?>&stsshow=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=200')">
		<?php
		}
		?>
		</td>
		</tr>
<?php
}
if($numrows==0){ //ให้แสดงกรณีบัญชีอนุมัติ เพราะมีหลายรายการจึงต้องแสดงผลรวม แต่ถ้าการเงินอนุมัติจะแสดงแค่รายการเดียว
	echo "<tr><td colspan=\"9\" align=\"center\" height=50><b>-ไม่พบข้อมูล-</b></td></tr>";					
}
?>
</table>


