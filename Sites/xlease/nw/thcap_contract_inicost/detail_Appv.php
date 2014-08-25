<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$app_date = Date('Y-m-d H:i:s');
$contractID = pg_escape_string($_GET["contractID"]);
$autoID = pg_escape_string($_GET["ini_auto_id"]);
$addStamp = pg_escape_string($_GET["addStamp"]);
$addUser = pg_escape_string($_GET["addUser"]);
$Menu	= pg_escape_string($_GET["menu"]);
?>
<script>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
 <title>(THCAP) ใส่ต้นทุนสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>
	<div><h2 align="center">รายละเอียดต้นทุนสัญญา</h2></div>
	<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
	<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold; font-size:12px;" valign="middle" bgcolor="#79BCFF" align="center">
				<th><font color="black">รายการที่</font></th>
				<th><font color="black">เลขที่สัญญา</font></th>
				<th><font color="black">ประเภทสัญญา</font></th>
				<th><font color="black">ผู้ทำรายการ</font></th>
				<th><font color="black">วันเวลาที่ทำรายการ</font></th>
				<?php // เพิ่ม field เมื่อเปิด popup จากหน้า ประวัติการอนุมัติ
				if($Menu==1){
					echo "<th><font color=\"black\">ผู้อนุมัติ</font></th>";
					echo "<th><font color=\"black\">วันเวลาที่อนุมัติ</font></th>";
				}
				?>
				<th><font color="black">ประเภทต้นทุนสัญญา</font></th>
				<th><font color="black">จำนวนต้นทุนสัญญา</font></th>
			</tr>
		
	<?php
		if($Menu==1){
			$query = pg_query("select *,thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' and a.\"contractID\"='$contractID' and a.ini_add_user='$addUser' and a.ini_add_stamp='$addStamp'  order by a.\"ini_appv_stamp\" DESC  ");
		} else {
			$query = pg_query("select *,thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"='2' and a.\"contractID\"='$contractID' and a.ini_add_user='$addUser' and a.ini_add_stamp='$addStamp'  order by a.\"ini_add_stamp\" ASC  ");
		}
			$nub=0;
			while($res=pg_fetch_array($query)){
			$nub++;
			$NcontractID = $res['contractID'];
			$conType = $res['conType'];
			$costType = $res['costtype'];
			$SumIniCost = $res['sumIniCost'];
			$NaddUserID = $res['ini_add_user'];
			$NaddStamp = $res['ini_add_stamp'];
			$appvUser = $res['ini_appv_user'];
			$appvStamp = $res['ini_appv_stamp'];
			
			$qry_fullname_adduser = pg_query("select fullname from \"Vfuser\" where id_user = '$NaddUserID' ");
				$addFulname=pg_fetch_result($qry_fullname_adduser,0);
			
			$qry_fullname_appvuser = pg_query("select fullname from \"Vfuser\" where id_user = '$appvUser' ");
				$appvFulname=pg_fetch_result($qry_fullname_appvuser,0);
				
			$qry_Costname = pg_query("select costname from thcap_cost_type where costtype = '$costType' ");
			$Costname = pg_fetch_result($qry_Costname,0);
			
			if($SumIniCost != ""){$txtSumIniCost = number_format($SumIniCost,2);}else{$txtSumIniCost = "";}
			
			if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
			} else {
				 echo "<tr class=\"even\" align=center>";
			} 
				echo "<td>$nub</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$NcontractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$NcontractID</u></font></td>";
				echo "<td>$conType</td>";
				echo "<td>$addFulname</td>";
				echo "<td>$NaddStamp</td>";
				if($Menu==1){
					echo "<td>$appvFulname</td>";
					echo "<td>$appvStamp</td>";
				}
				echo "<td>$Costname</td>";
				echo "<td align=\"right\">$txtSumIniCost</td>";
			echo "</tr>";
			}
	?>
		</table>
	</div>
	</table>
</body>
</html>