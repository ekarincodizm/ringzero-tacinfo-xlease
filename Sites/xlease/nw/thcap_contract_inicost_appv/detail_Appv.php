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
$contractID = $_GET["contractID"];
$autoID = $_GET["ini_auto_id"];
$conDate = $_GET["conDate"];
$conEndDate = $_GET["conEndDate"];
$fullname = $_GET["fullname"];
$addStamp = $_GET["addStamp"];
$addUser = $_GET["addUser"];
$menu = $_GET["menu"];

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
	<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-size:12px; font-weight:bold;"valign="middle" bgcolor="#79BCFF" align="center">
				<th><font color="black">รายการที่</font></th>
				<th><font color="black">เลขที่สัญญา</font></th>
				<th><font color="black">ประเภทสัญญา</font></th>
				<th><font color="black">ผู้ทำรายการ</font></th>
				<th><font color="black">วันเวลาที่ทำรายการ</font></th>
				<?php
				if($menu==1){
					echo "<th><font color=\"black\">ผู้อนุมัติ</font></th>";
					echo "<th><font color=\"black\">วันเวลาที่อนุมัติ</font></th>";
				}
				?>
				<td><font color="black">ประเภทต้นทุนสัญญา</font></td>
				<td><font color="black">จำนวนต้นทุนสัญญา</font></td>
			</tr>
		
	<?php	
			if($menu==1){
			$query = pg_query("select *,thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' and a.\"contractID\"='$contractID' and a.ini_add_user='$addUser' and a.ini_add_stamp='$addStamp'  order by a.\"ini_add_stamp\" ASC  ");
			$hidden = "hidden";
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
			$autoID = $res['inicost_autoID'];
			//่ต่อ string สำหรับส่งค่าไป Update
			if($nub==1){
				$NewautoID = "$autoID";
			}else {
				$NewautoID = "$NewautoID,$autoID";
			}
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
				if($menu==1){
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
	<div <?php echo $hidden ?>>
		<table align="center">
			<tr>
				<td>
					<!--input <?php echo $hidden; ?>  type="button" name="Appv" value="อนุมัติ" onclick="window.location='Update_Appv.php?statusAppv=1&auto_id=<?php echo $NewautoID; ?>'"> 
					<input <?php echo $hidden; ?> type="button" name="setNotAppv" value="ไม่อนุมัติ" onclick="window.location='Update_Appv.php?statusAppv=0&auto_id=<?php echo $NewautoID; ?>'"/> 
					<input type='button' onclick="window.close();" value="ปิด"-->
					<form method="post" action="Update_Appv.php">
						<input type="hidden" name="auto_id" id="auto_id" value="<?php echo $NewautoID; ?>">
						<input name="appv" type="submit" value="อนุมัติ" />
						<input name="unappv" type="submit" value="ไม่อนุมัติ" />
						<input type='button' onclick="window.close();" value="ปิด">
					</form>
				</td>
			</tr>
		<table>
	</div>
	</table>
</body>
</html>