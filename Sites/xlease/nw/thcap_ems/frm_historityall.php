<?php
include("../../config/config.php");
$sort = pg_escape_string($_GET["descOrascby"]);
$orderby = pg_escape_string($_GET["orderby"]);

if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "\"dorestamp_ems\" ";
}
$query = pg_query("select auto_id as \"id\",\"contractID\",\"cusName\" as \"cusname\",\"emsnumber\",\"sendDate\",
	\"dorestamp_ems\" as \"dorestamp_ems\",\"addressCon\",(select b.fullname from \"Vfuser\" b where b.\"id_user\" = a.\"id_user\") as \"id_user\",(select d.fullname from \"Vfuser\" d where d.\"id_user\" = a.\"doreid_ems\") as \"dorename_ems\"
	from \"thcap_letter_send\" a 
	where auto_id in (select auto_id from \"thcap_letter_send\" where type_send='E' and \"emsnumber\"  is not null  and \"sendDate\" >= '2013-10-01') and type_send='E' and \"emsnumber\"  is not null  and \"sendDate\" >= '2013-10-01'

union

	select \"sendID\" as \"id\",\"contractID\",\"receiveName\" as \"cusname\",a.\"emsnumber\" as \"emsnumber\",\"sendDate\",
	a.\"dorestamp_ems\" as \"dorestamp_ems\",a.\"addrCus\" as \"addressCon\",
	(select c.fullname from \"Vfuser\" c where c.\"id_user\" = b.\"id_user\") as \"id_user\" ,(select d.fullname from \"Vfuser\" d where d.\"id_user\" = a.\"doreid_ems\") as \"dorename_ems\"
	from \"thcap_letter_detail\" a left join \"thcap_letter_send\" b on b.auto_id=a.\"sendID\"
	where \"sendID\" in (select \"sendID\" from \"thcap_letter_detail\"  where type_send='E' and \"emsnumber\"  is not null  and \"sendDate\" >= '2013-10-01' ) and a.type_send='E' and a.\"emsnumber\"  is not null  and \"sendDate\" >= '2013-10-01' order by  $orderby $sort");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการใส่เลขที่ EMS</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  

<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
</head>
<script type="text/javascript">

$(document).ready(function(){  
	window.opener.location.reload();});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>
<center><h1>ประวัติการใส่เลขที่ EMS</h1></center>
<table  align="center" width="99%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"contractID\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลขที่สัญญา</u></font></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"cusname\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ชื่อผู้รับ</u></font></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"emsnumber\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลข EMS</u></font></th>
		<th>ที่อยู่ที่ติดต่อส่งเอกสาร</th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"id_user\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>
		ผู้ทำรายการ</u></font></th>
        <th><a href='frm_historityall.php?orderby=<?php echo "\"sendDate\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ทำรายการ</u></font></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"dorename_ems\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ผู้คีย์เลขที่ EMS
		</u></font></th>
        <th><a href='frm_historityall.php?orderby=<?php echo "\"dorestamp_ems\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันเวลาที่คีย์เลขที่ EMS</u></font></th>
	</tr>
	<?php	
	$i=0;
	$numrows1 = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{	    $contractID=$result ["contractID"];
			$cusName=$result["cusname"];
			$emsnumber=$result["emsnumber"];
			$addressCon=$result["addressCon"];
			$id_user=$result["id_user"];
			$sendDate=$result["sendDate"];
			$doreid_ems=$result["dorename_ems"];
			$dorestamp_ems=$result["dorestamp_ems"];
			
			//ชื่อคนทำรายการ
			/*$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$id_user' ");
			$nameuser = pg_fetch_array($query_fullname);
			$fullnamedoerid=$nameuser["fullname"];
			//ชื่อคีย์ข้อมูล
			$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doreid_ems'");
			$nameuser_ems = pg_fetch_array($query_fullname);
			$fullnameems =$nameuser_ems ["fullname"];*/
			$i++;
			if($i%2==0){
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}else{
				echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
				
			echo "<td align=\"center\">$i</td>";
			echo "<td align=\"center\">$contractID</td>";				
			echo "<td align=\"left\">$cusName</td>";
			echo "<td align=\"left\">$emsnumber</td>";
			echo "<td align=\"left\">$addressCon</td>";
			echo "<td align=\"left\">$id_user</td>";
			echo "<td align=\"center\">$sendDate</td>";			
			echo "<td align=\"left\">$doreid_ems</td>";
			echo "<td align=\"center\">$dorestamp_ems</td>";
		
		
	}
	if($numrows1==0){
			echo "<tr bgcolor=#CDC9C9 height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=9><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
	
</table>
