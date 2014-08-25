<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) อนุมัติ STOP VAT HP</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<center><h2>(THCAP) อนุมัติ STOP VAT HP</h2></center>
<body >
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก/ผู้เช่าซื้อหลัก</th>
		<th>ประเภทสัญญา</th>
		<th>วันที่เริ่มค้างชำระ</th>
		<th>ยอดมูลหนี้ </th>
		<th>ทำรายการ</th>
	</tr>
	<?php
	$qry_main = pg_query("select DISTINCT  a.\"contractID\" as \"contractID\",a.\"FullName\" as \"FullName\"
				from \"thcap_ContactCus\" a
				LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
				LEFT JOIN \"thcap_v_lease_table\" c on a.\"contractID\" =c.\"contractID\" 
				where \"CusState\" = '0' and \"conType\"='HP' and c.\"debtDueDate\" <= current_date 
				and \"receiptID\" is null and a.\"contractID\" not in (select \"contractID\"  from \"thcap_contract_vatcontrol\" 
where \"autoID\" in (select max(\"autoID\") from \"thcap_contract_vatcontrol\" group by \"contractID\") and \"status\"='1' )
				group by a.\"contractID\" ,a.\"FullName\"
				");
	$no=0;	
	
	while($res_main = pg_fetch_array($qry_main))
		{	
			$contractID = $res_main["contractID"];
			$qry_contractID = pg_query("select count( c.\"contractID\") as \"countcontractID\",min(c.\"debtDueDate\") as \"debtDueDate\" ,sum(c.\"typePayAmt\") as \"typePayAmt\"  from \"thcap_ContactCus\" a
				LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
				LEFT JOIN \"thcap_v_lease_table\" c on a.\"contractID\" =c.\"contractID\" 
				where \"CusState\" = '0' and \"conType\"='HP' and \"debtDueDate\" <= current_date and c.\"contractID\"='$contractID'
				and \"receiptID\" is null 
				");
			$res_contractID = pg_fetch_array($qry_contractID);
			$countcontractID = $res_contractID["countcontractID"]; 
			if($countcontractID>2){
				$FullName = $res_main["FullName"]; 
				$conType = "HP";
				$debtDueDate = $res_contractID ["debtDueDate"];
				$typePayAmt = $res_contractID ["typePayAmt"];
				$no+=1;
				if($no%2==0)
				{
					echo "<tr class=\"odd\" align=\"center\" height=25>";
				}
				else
				{
					echo "<tr class=\"even\" align=\"center\" height=25>";
				}
				echo "<td align=\"center\">$no</td>";
				echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";	
				echo "<td align=\"left\">$FullName</td>";
				echo "<td align=\"center\">$conType</td>";
				echo "<td align=\"center\">$debtDueDate</td>";
				echo "<td align=\"right\">".number_format($typePayAmt,2)."</td>";
				echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_checkappvdetail.php?idno=$contractID&payamt=$typePayAmt&fullname=$FullName&debtDueDate=$debtDueDate','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ทำรายการ</u></font></a></td>";	
				echo "</tr>";
			}
		}
	
	?>
	
</table>
<!--รายการ 30 ล่าลุด-->
</br>
<fieldset style="width:95%"><legend><font color="black"><b>
		ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historyappv.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>
	</legend>
	<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก/ผู้เช่าซื้อหลัก</th>
		<th>ประเภทสัญญา</th>
		<th>วันที่เริ่มค้างชำระ</th>	
		<th>ผู้ที่ทำรายการ</th>
		<th>วันที่ทำรายการ</th>					
		<th>ยอดมูลหนี้  ณ วันที่ stop vat</th>
		<th>ผู้ที่ทำการอนุมัติ</th>
		<th>วันที่ทำการอนุมัติ</th>	
		<th>หมายเหตุ</th>
		<th>ผลการอนุมัติ</th>
	</tr>

<?php

$qry_show30limit = pg_query("select * from \"thcap_contract_vatcontrol\"  where \"status\"='1'  order by \"doerstamp\" desc limit 30");
$i=0;	
$numrows = pg_num_rows($qry_show30limit);
while($res_show30limit = pg_fetch_array($qry_show30limit))
	{	$i+=1;
		$autoID = $res_show30limit["autoID"];
		$contractIDlimit = $res_show30limit["contractID"];
		$datebehindhandlimit = $res_show30limit["datebehindhand"];
		$payamtlimit = $res_show30limit["payamt"];
		$doeridlimit = $res_show30limit["doerid"];
		$doerstamplimit = $res_show30limit["doerstamp"];		
		$appvidlimit = $res_show30limit["appvid"];
		$appvstamplimit = $res_show30limit["appvstamp"]; 
		$notelimit = $res_show30limit["note"];
		$approvelimit= $res_show30limit["approve"];
		//ชื่อผู้ที่ทำรายการ
		$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doeridlimit' ");
		$fullnameuser = pg_fetch_array($query_fullnameuser);
		$doerfullname=$fullnameuser["fullname"];
		//ชื่อผู้ทำการอนุมัติ
		$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvidlimit' ");
		$fullnameuser = pg_fetch_array($query_fullnameuser);
		$empfullname=$fullnameuser["fullname"];
		
		//
		$qry_name = pg_query("select a.\"FullName\" as \"FullName\"
				from \"thcap_ContactCus\" a
				LEFT JOIN \"thcap_contract_vatcontrol\" b on a.\"contractID\" = b.\"contractID\"				
				where \"CusState\" = '0' and b.\"contractID\"='$contractIDlimit'
				");
		$res_name = pg_fetch_array($qry_name);
		$nameCus=$res_name ["FullName"];
		
		
		if($i%2==0)
		{
			echo "<tr  bgcolor=\"#EEE9E9\" align=\"center\" height=25>";
		}
		else
		{
			echo "<tr  bgcolor=\"#FFFAFA\" align=\"center\" height=25>";
		}
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractIDlimit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractIDlimit</u></font></a></td>";	
		echo "<td align=\"left\">$nameCus</td>";
		echo "<td align=\"center\">$conType</td>";//ประเภทสัญญา
		echo "<td align=\"center\">$datebehindhandlimit</td>";//ันที่เริ่มค้างชำระ
		echo "<td align=\"center\">$doerfullname</td>";//ผู้ที่ทำรายการ
		echo "<td align=\"center\">$doerstamplimit</td>";//วันที่ทำรายการ
		echo "<td align=\"right\">".number_format($payamtlimit,2)."</td>";//ยอดมูลหนี้  ณ วันที่ stop vat
		echo "<td align=\"center\">$empfullname</td>";//ผู้ที่การอนุมัติ
		echo "<td align=\"center\">$appvstamplimit</td>";//วันที่ทำการอนุมัติ
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_notedetail.php?idno=$autoID ','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";		
		
		//ผลการ stop vat
		if($approvelimit=='1'){
		echo "<td align=\"center\"><font color=\"green\">อนุมัติ</td>";}
		elseif($approvelimit=='0'){
		echo "<td align=\"center\"><font color=\"red\">ไม่อนุมัติ</td>";}
		echo "</tr>";
	}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=12 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=12><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	
?>
</table>
</fieldset>
</body>