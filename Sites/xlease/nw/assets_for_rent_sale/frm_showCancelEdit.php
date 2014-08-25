<?php
include("../../config/config.php");
$i=0;
$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap_edit_newcon');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายการสินทรัพย์ที่ถูกไม่อนุมัติและไม่นำกลับมาใช้อีก</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T)
{
    newWindow = window.open(U, N, T);
}

</script>
</head>

<body>
<div style="margin-top:15px;"></div>
<center>
	<h2>(THCAP) รายการสินทรัพย์ที่ถูกไม่อนุมัติและไม่นำกลับมาใช้อีก</h2>
	<table width="98%">
		<tr>
			<td align="right"><input type="button" value="CLOSE" onClick="window.close();"></td>
		</tr>
	</table>
	<hr />
    <table align="center" width="98%" border="0" cellspacing="1" cellpadding="3" bgcolor="#BBBBEE">
        <tr align="center" bgcolor="#CDC9C9">
            <th>รายการที่</th>
            <th>ผู้ซื้อ</th>
            <th>ผู้ขาย</th>
            <th>เลขที่ใบสั่งซื้อ<br /><span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span></th>
            <th>เลขที่ใบเสร็จ<br /><span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span></th>
            <th>ผู้ยกเลิกการแก้ไข</th>
            <th>วันเวลาที่ยกเลิกการแก้ไข</th>
			<th>นำรายการกลับไปแก้ไข</th>
        </tr>
        <?php
        $query = pg_query("select * from public.\"thcap_asset_biz_temp\" where \"Approved\" = false and \"isCancel\" = '1' order by \"CancelStamp\" desc");
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
			$CancelID = $result["CancelID"]; // ผู้ยกเลิกการแก้ไข
            $CancelStamp = $result["CancelStamp"]; // วันเวลาที่ยกเลิกการแก้ไข
            
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
			
			$qry_cancel_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$CancelID' ");
            while($result_cancel_name = pg_fetch_array($qry_cancel_name))
            {
                $cancel_fullname = $result_cancel_name["fullname"]; // ชื่อของผู้ยกเลิกการแก้ไข
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
            echo "<td align=\"left\">$compThaiName</td>";
            echo "<td align=\"left\">$fullnameCorp</td>";
            echo "<td align=\"center\">$PurchaseOrderpopup</td>";
            echo "<td align=\"center\">$receiptNumberpopup</td>";
            echo "<td align=\"left\">$cancel_fullname</td>";
            echo "<td align=\"center\">$CancelStamp</td>";
			$nameform="frm".$i;	//ชื่อ form เพื่อไม่ให้ ชื่อ ซ้ำกัน
			echo "<form name=\"$nameform\" method=\"post\" action=\"process_cancelEdit.php\">";
			echo "<input type=\"hidden\" name=\"tempID\" id=\"tempID\" value=\"$tempID\">";
			echo "<input type=\"hidden\" name=\"isCancel\" id=\"isCancel\" value=\"0\">";
			echo "<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"if(confirm('ยืนยันการนำรายการกลับไปแก้ไข') == true){
			document.forms['$nameform'].submit();return false;
			}\" src=\"../thcap/images/return.gif\" width=\"20px;\" height=\"20px;\"></td>";	
			echo "</form>";
            echo "</tr>";
        }
        if($numrows==0){
            echo "<tr bgcolor=\"#FFFFFF\" height=\"50\"><td colspan=\"8\" align=\"center\"><b>ไม่พบรายการ</b></td><tr>";
        }else{
            echo "<tr bgcolor=\"#CDC5BF\" height=\"30\"><td colspan=\"8\" align=\"right\"><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
        }
        ?>
    </table>
</center>
</body>
</html>