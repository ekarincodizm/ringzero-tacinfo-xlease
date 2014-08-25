<?php
include("../../config/config.php");
$i=0;
$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap_edit_newcon');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ประวัติการตรวจสอบสินทรัพย์สำหรับเช่า-ขาย</title>
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
<div style="margin-top:15px;"></div>
<center>
	<h2>ประวัติการตรวจสอบสินทรัพย์สำหรับเช่า-ขาย</h2>
    <hr />
    <table align="center" width="98%" border="0" cellspacing="1" cellpadding="3" bgcolor="#BBBBEE">
        <tr align="center" bgcolor="#CDC9C9">
            <th>รายการที่</th>
            <th>ผู้ซื้อ</th>
            <th>ผู้ขาย</th>
            <th>เลขที่ใบสั่งซื้อ<br /><span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span></th>
            <th>เลขที่ใบเสร็จ<br /><span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span></th>
            <th>ผู้ทำรายการ</th>
            <th>วันเวลาที่ทำรายการ</th>
            <th>ผู้อนุมัติ</th>
            <th>วันเวลาที่อนุมัติ</th>
            <th>ผลการอนุมัติ</th>
        </tr>
        <?php
        $query = pg_query("select * from public.\"thcap_asset_biz_temp\" where \"Approved\" is not null order by \"appvStamp\" desc");
        $numrows = pg_num_rows($query);
        while($result = pg_fetch_array($query))
        {
            $i++;
            $tempID = $result["tempID"]; // รหัส temp
            $compID = $result["compID"]; // ID บริษัท (ผู้ซื้อ)
            $corpID = $result["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
            $PurchaseOrder = $result["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
            $receiptNumber = $result["receiptNumber"]; // เลขที่ใบเสร็จ
            $doerID = $result["doerID"]; // ผู้ทำรายการ
            $doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
            $appvID = $result["appvID"];  //ผู้อนุมัติ
            $appvStamp = $result["appvStamp"];  //เวลาอนุมัติ
            $Approved = $result["Approved"];  //ผลการอนุมัติ
            
            if($Approved=="t")
            {
                $appv_res = "อนุมัติ";
            }
            else
            {
                $appv_res = "ไม่อนุมัติ";
            }
            
            $qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
            while($result_name = pg_fetch_array($qry_name))
            {
                $fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
            }
            
            $qry_appv_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvID' ");
            while($result_appv_name = pg_fetch_array($qry_appv_name))
            {
                $appv_fullname = $result_appv_name["fullname"]; // ชื่อของผู้ที่ทำรายการอนุมัติ
            }
            
            // หาชื่อบริษัท (ผู้ซื้อ)
            $qry_nameCom = pg_query("select * from public.\"thcap_company\" where \"compID\" = '$compID' ");
            while($result_name = pg_fetch_array($qry_nameCom))
            {
                $compThaiName = $result_name["compThaiName"]; // ชื่อของ บริษัท (ผู้ซื้อ)
            }
            
            // หาชื่อบริษัท (ผู้ซื้อ)
            $qry_nameCorp = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
            while($result_name = pg_fetch_array($qry_nameCorp))
            {
                $fullnameCorp = $result_name["full_name"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
            }
			
			$receiptNumberpopup = "<a style=\"cursor:pointer;\" onclick=\"javascript:popU('$relpaths/view_product.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=550')\"><u>$receiptNumber</u></a>";						
			$PurchaseOrderpopup = "<a style=\"cursor:pointer;\" onclick=\"javascript:popU('$relpaths/view_product.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=550')\"><u>$PurchaseOrder</u></a>";
            
            if($i%2==0){
                echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
            }else{
                echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
            }
            
            echo "<td align=\"center\">$i</td>";
            echo "<td>$compThaiName</td>";
            echo "<td>$fullnameCorp</td>";
            echo "<td align=\"center\">$PurchaseOrderpopup</td>";
            echo "<td align=\"center\">$receiptNumberpopup</td>";
            echo "<td>$fullname</td>";
            echo "<td align=\"center\">$doerStamp</td>";
            echo "<td align=\"center\">$appv_fullname</td>";
            echo "<td align=\"center\">$appvStamp</td>";
            echo "<td align=\"center\">$appv_res</td>";
            echo "</tr>";
        }
        if($numrows==0){
            echo "<tr bgcolor=\"#FFFFFF\" height=\"50\"><td colspan=\"10\" align=\"right\"><b>ไม่พบรายการ</b></td><tr>";
        }else{
            echo "<tr bgcolor=\"#CDC5BF\" height=\"30\"><td colspan=\"10\" align=\"right\"><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
        }
        ?>
    </table>
</center>
</body>
</html>