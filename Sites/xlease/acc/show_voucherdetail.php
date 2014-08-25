<?php
session_start();
include("../config/config.php");
$av_iduser=$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}


$vc_id=pg_escape_string($_GET["vc_id"]);

$query_name=pg_query("select a.\"vc_id\",b.\"fullname\" as markername,a.\"do_date\",c.\"fullname\" as approvename,a.\"appv_date\",a.\"receipt_id\",a.\"recp_date\",e.\"vtid\",e.\"voucher_type_name\" from account.tal_voucher a
left join \"Vfuser\" b on a.\"marker_id\"=b.\"id_user\" 
left join \"Vfuser\" c on a.\"approve_id\"=c.\"id_user\" 
left join account.\"nw_voucher_type\" e on a.\"vtid\"=e.\"vtid\"
where a.\"vc_id\"='$vc_id'");
if($res_name=pg_fetch_array($query_name)){
	$vc_id=$res_name["vc_id"];
	$markername=$res_name["markername"];
	$do_date=$res_name["do_date"]; 
	$approvename=$res_name["approvename"]; 
	$appv_date=$res_name["appv_date"]; 
	$receipt_id=$res_name["receipt_id"]; 
	
	if($receipt_id=="REC#"){
		$receiptname="ไม่ระบุผู้รับเงิน";
	}else{
		$qryreceipt=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$receipt_id'");
		if($resreceipt=pg_fetch_array($qryreceipt)){
			$receiptname=$resreceipt["fullname"];
		}
	}
	$recp_date=$res_name["recp_date"]; 
	$vc_name=$res_name["vc_id"]."->".$res_name["voucher_type_name"]; 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>แสดงรายละเอียด Voucher</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>

</head>

<body>
<div id="swarp" style="width:480px; height:auto; margin-left:auto; margin-right:auto;">

	<div id="warppage" style="width:450px; height:250px;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		
		<table width="450" border="0" style="background-color:#EEEDCC">
		<tr height="25">
			<td align="right"><b>รหัส Voucher</b></td>
			<td width="10">:</td>
			<td><?php echo $vc_id;?></td>
		</tr>
		<tr height="25">
			<td align="right"><b>ผู้ทำรายการ</b></td>
			<td width="10">:</td>
			<td><?php echo $markername;?> (วันที่ทำรายการ : <?php echo $do_date;?>)</td>
		</tr>
		<tr height="25">
			<td align="right"><b>ผู้อนุมัติรายการ</b></td>
			<td width="10">:</td>
			<td><?php echo $approvename;?> (วันที่อนุมัติรายการ : <?php echo $appv_date;?>)</td>
		</tr>
		<tr height="25">
			<td align="right"><b>ผู้รับเงิน</b></td>
			<td width="10">:</td>
			<td><?php echo $receiptname;?> (วันที่รับเงิน : <?php echo $recp_date;?>)</td>
		</tr>
		<tr height="25">
			<td align="right"><b>ประเภทค่าใช้จ่าย</b></td>
			<td width="10">:</td>
			<td><?php echo $vc_name;?></td>
		</tr>
		<tr align="center" height="50">
			<td colspan="3" style="background-color:#FFECB9;" height="30">
				<input type="button" value="CLOSE" onclick="window.close();">				
			</td>
		</tr>
		</table>
</div>
</body>
</html>
