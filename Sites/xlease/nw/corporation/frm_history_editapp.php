<?php
include("../../config/config.php");
$where = "\"conRepeatDueDay\" is not null ";
$cur_path_ins = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติอนุมัติการแก้ไขข้อมูลลูกค้านิติบุคคล</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center><h1>ประวัติอนุมัติการแก้ไขข้อมูลลูกค้านิติบุคคล</h1></center>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
    <tr align="center" bgcolor="#CDC9C9">
        <th>รายการที่</th>
        <th>เลขที่นิติบุคคล</th>
        <th>ชื่อนิติบุคคล<br>ภาษาไทย</th>
        <th>ชื่อนิติบุคคล<br>ภาษาอังกฤษ</th>
        <th>ชื่อย่อ/เครื่องหมาย<br>ทางการค้า</th>
        <th>ผู้ทำรายการ</th>
        <th>วันเวลาที่ทำรายการ</th>
        <th>ผู้ทำรายการอนุมัติ</th>
        <th>วันเวลาที่อนุมัติ</th>
        <th>ผลการอนุมัติ</th>
        <th>รายละเอียด</th>
    </tr>
    <?php
    $query = pg_query("select * from public.\"th_corp_temp\" a where a.\"Approved\" is not null and a.\"corpID\"<>'0' and a.\"corpEdit\"<>'0' and a.\"corpEdit\"<>(select min(b.\"corpEdit\") from public.\"th_corp_temp\" b where b.\"corpID\"=a.\"corpID\") order by \"appvStamp\" desc");
    $numrows = pg_num_rows($query);
    $i=0;
    while($result = pg_fetch_array($query))
    {
        $i++;
        $corp_regis = $result["corp_regis"]; // เลขทะเบียนนิติบุคคล
        $corpName_THA = $result["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
        $corpName_ENG = $result["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
        $trade_name = $result["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
        $doerUser = $result["doerUser"]; // ผู้ทำรายการ
        $doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
        $Approved = $result["Approved"];
        $appvUser = $result["appvUser"];
        $appvStamp = $result["appvStamp"];
		$corpEdit = $result["corpEdit"];
		
		if($Approved=="t")
		{
			$view_lnk = "javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')";
		}
		else
		{
			if($corpEdit=="")
			{
				$view_lnk = "javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis&view=3&editable=f','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')";
			}
			else
			{
				$view_lnk = "javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis&view=3&editable=f&corpedit=$corpEdit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')";
			}
		}
		
        if($Approved=="t")
        {
            $Approved = "อนุมัติ";
        }
        else
        {
            $Approved = "ไม่อนุมัติ";
        }
        
        $qry_appv_name = pg_query("select * from public.\"Vfuser\" where \"username\" = '$appvUser' ");
        while($result_appv_name = pg_fetch_array($qry_appv_name))
        {
            $appv_fullname = $result_appv_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
        }
        
        $qry_name = pg_query("select * from public.\"Vfuser\" where \"username\" = '$doerUser' ");
        while($result_name = pg_fetch_array($qry_name))
        {
            $fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
        }
        
        if($i%2==0){
            echo "<tr bgcolor=\"#FFFAFA\">";
        }else{
            echo "<tr bgcolor=\"#EEE9E9\">";
        }
        
        echo "<td align=\"center\">$i</td>";
        echo "<td align=\"center\">$corp_regis</td>";
        echo "<td>$corpName_THA</td>";
        echo "<td>$corpName_ENG</td>";
        echo "<td>$trade_name</td>";
        echo "<td>$fullname</td>";
        echo "<td align=\"center\">$doerStamp</td>";
        echo "<td align=\"center\">$appv_fullname</td>";
        echo "<td align=\"center\">$appvStamp</td>";
        echo "<td align=\"center\">$Approved</td>";
        echo "<td align=\"center\"><a onclick=\"$view_lnk\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
        echo "</tr>";
    }
    if($numrows==0){
        echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
    }else{
        echo "<tr bgcolor=\"#CDC9C9\" height=30><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
    }
    ?>
</table>