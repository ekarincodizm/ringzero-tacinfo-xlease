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
//เงือนไขในการเรียงดำลับข้อมูล
$Strsort3=$_GET['sort3'];
if($Strsort3==""){$Strsort3="ini_appv_stamp";}
$Strorder3=$_GET['order3'];
if($Strorder3==""){$Strorder3="DESC";}

if($Strorder3=="DESC"){
	$NewStrorder3="ASC";
} else {
	$NewStrorder3="DESC";
}
			// เงือนไขการเลือกใช้ Query สำหรับ Sort ข้อมูล
			if($Strsort3=="ini_add_user" || $Strsort3=="ini_appv_user"){
			$qry_con=pg_query("select a.\"contractID\",a.ini_add_user,a.ini_add_stamp,a.ini_appv_stamp,a.ini_appv_status,b.\"conType\",b.\"conDate\",thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\",a.\"ini_appv_stamp\",a.\"ini_appv_status\" order by (select fullname from \"Vfuser\" where id_user = '$Strsort3' ) $Strorder3 ");
			} else if($Strsort3=="ini_add_stamp" || $Strsort3=="ini_appv_stamp" ||$Strsort3=="ini_appv_status"){
			$qry_con=pg_query("select a.\"contractID\",a.ini_add_user,a.ini_add_stamp,a.ini_appv_stamp,a.ini_appv_status,b.\"conType\",b.\"conDate\",thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\",a.\"ini_appv_stamp\",a.\"ini_appv_status\" order by a.\"$Strsort3\" $Strorder3 ");
			} else if($Strsort3=="costtype"){
			$qry_con=pg_query("select a.\"contractID\",a.ini_add_user,a.ini_add_stamp,a.ini_appv_stamp,a.ini_appv_status,b.\"conType\",b.\"conDate\",thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\",a.\"ini_appv_stamp\",a.\"ini_appv_status\" order by (select costname from thcap_cost_type where costtype = '$Strsort3') $Strorder3 ");
			} else if($Strsort3=="contractID"){
			$qry_con=pg_query("select a.\"contractID\",a.ini_add_user,a.ini_add_stamp,a.ini_appv_stamp,a.ini_appv_status,b.\"conType\",b.\"conDate\",thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\",a.\"ini_appv_stamp\",a.\"ini_appv_status\" order by a.\"$Strsort3\" $Strorder3 ");
			} else {
			$qry_con=pg_query("select a.\"contractID\",a.ini_add_user,a.ini_add_stamp,a.ini_appv_stamp,a.ini_appv_status,b.\"conType\",b.\"conDate\",thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"<>'2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\",a.\"ini_appv_stamp\",a.\"ini_appv_status\" order by b.\"$Strsort3\" $Strorder3 ");
			}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่ต้นทุนสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>
<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div align="Center"><h1>ประวัติการอนุมัติต้นทุนสัญญา</h1></div>
		<div class="wrapper">		
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
				<td><font color="black">รายการที่</font></td>
				<td><a href="show_all_history.php?sort3=contractID&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>เลขที่สัญญา</u></font></td>
				<td><a href="show_all_history.php?sort3=conType&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>ประเภทสัญญา</u></font></td>
				<td><a href="show_all_history.php?sort3=conDate&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>วันที่ทำสัญญา</u></font></td>
				<td><a href="show_all_history.php?sort3=ini_add_user&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>ผู้ขอยกเว้น</u></font></td>
				<td><a href="show_all_history.php?sort3=ini_add_stamp&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>วันเวลาที่ขอยกเว้น</u></font></td>
				<td><a href="show_all_history.php?sort3=ini_appv_user&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>ผู้อนุมัติ</u></font></td>
				<td><a href="show_all_history.php?sort3=ini_appv_stamp&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>วันเวลาที่อนุมัติ</u></font></td>
				<td>รายละเอียด</td>
				<td><a href="show_all_history.php?sort3=ini_appv_status&order3=<?php echo $NewStrorder3;?>"><font color="black"><u>ผลการอนุมัติ</u></font></td>
			</tr>
			<?php
			
			$num_con=pg_num_rows($qry_con);
			$i=0;
			$num_all=0;
			while($res_con=pg_fetch_array($qry_con)){
			$num_all++;
				$contractID=$res_con["contractID"];
				$contractType = $res_con["conType"]; 
				$contractDate = $res_con["conDate"]; 
				$addUser = $res_con["ini_add_user"];
				$addStamp = $res_con["ini_add_stamp"];
				$appUser = $res_con["ini_appv_user"];
				$appStamp = $res_con["ini_appv_stamp"];
				$statusCon = $res_con["ini_appv_status"];
				$autoID = $res_con["ini_auto_id"];
				$investment = $res_con["investment"];
				
				$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
				while($res_chkStatus = pg_fetch_array($qry_chkStatus))
				{
				$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
				}
						
						if($conStatus == "11")
						{
							echo "<tr bgcolor=\"#CCCCCC\">";
						}
				$qry_fullname_adduser = pg_query("select fullname from \"Vfuser\" where id_user = '$addUser' ");
				$addFullname=pg_fetch_result($qry_fullname_adduser,0);
				
				$qry_fullname_appuser = pg_query("select fullname from \"Vfuser\" where id_user = '$appUser' ");
				$appFullname=pg_fetch_result($qry_fullname_adduser,0);
				
				if($statusCon==0){
					$statusConName="ไม่อนุมัติ";
					$fontcolor="#FF0000";
				} else {
					$statusConName="อนุมัติ";
					$fontcolor="#00ff00";
				}
				
				if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
			} else {
				echo "<tr class=\"even\" align=center>";
			}	
				echo "<td>$num_all</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
				echo "<td>$contractType</td>";
				echo "<td>$contractDate</td>";
				echo "<td>$addFullname</td>";
				echo "<td>$addStamp</td>";
				echo "<td>$appFullname</td>";
				echo "<td>$appStamp</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('detail_Appv.php?contractID=$contractID&ini_auto_id=$autoID&conDate=$contractDate&conEndDate=$conEndDate&conType=$contractType&fullname=$addFulname&addStamp=$addStamp&addUser=$addUser&menu=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></u></font></td>";
				echo "<td><font color=\"$fontcolor\">$statusConName</font></td>";
			echo "</tr>";
			$i++;
			} //endwhile
			?>
			<?php
			if($num_con == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			<tr bgcolor="#6699FF">
				<td colspan="12" align="left"><b>รายการทั้งหมด <?php echo $num_all;?> รายการ<b></td>
			</tr>
		</div>
	</td>
</tr>
</table>
</body>
</html>