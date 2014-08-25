<?php
include("../../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการพิมพ์ใบเสร็จ</title>

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

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>
<center><h1>ประวัติการพิมพ์ใบเสร็จ</h1></center>


    <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center">
    	<tr style="background-color:#CCCCCC;">
			<th align="center">รายการที่</th>
			<th align="center">เลขที่ใบเสร็จ</th>        	
            <th align="center">ลูกค้า</th>
            <th align="center">เลขที่สัญญา</th>			
            <th align="center">ผู้พิมพ์</th>
			<th align="center">วันที่/เวลาที่พิมพ์</th>
			<th align="center">เหตุผล</th>
        </tr>
        <?php
				$i = 0;
					//ค้นหาเลขที่สัญญา
					$qry_con=pg_query("SELECT a.id,a.\"receiptid\",a.\"doerID\",a.\"doerStamp\",b.\"CusFullName\",b.\"contractID\"
							FROM \"blo_receipt_reprint\"  a
							left join \"blo_receipt\"  b  on a.\"receiptid\"= b.\"receiptID\" order by a.\"doerStamp\" DESC");
					$numcon=pg_num_rows($qry_con);
					
					if($numcon>0){ //แสดงว่ามีข้อมูล
						$status=1;
					}else{
						$status=0;
					}
				if($status==1){
					while($result=pg_fetch_array($qry_con)){
						$id=trim($result["id"]);			
						$contractID=trim($result["contractID"]); //เลขที่สัญญา
						$receiptID=trim($result["receiptid"]); //เลขที่ใบเสร็จ
						
						$doerID=trim($result["doerID"]); //ชื่อผู้ขอพิมพ์
						$doerStamp=trim($result["doerStamp"]); //วันที่เวลาที่พิมพ์
						$CusFullName=trim($result["CusFullName"]); 
						$i++;
						
						//หาชื่อ
							$qry_fullname = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$doerID'" );
							list($full_fullname) = pg_fetch_array($qry_fullname);
							
						if($i%2==0){
							echo "<tr bgcolor=\"#EEEEEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=\"center\">";
						}else{
							echo "<tr bgcolor=\"#DDDDDD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\" align=\"center\">";
						}
				
						
						echo "
						<td>$i</td>
						<td>$receiptID</td>
						<td align=\"left\">$CusFullName</td>
						<td>$contractID</td>						
						<td align=\"left\">$full_fullname</td>
						<td>$doerStamp</td>
						<td><img src=\"images/detail.gif\" 
						onclick=\"popU('frm_note_reprint.php?id=$id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350')\" style=\"cursor:pointer;\"></td>
						";		
						echo "</tr>";
					}	
					
				}else{
					echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=10><h2>-ไม่พบข้อมูลการพิมพ์ใบเสร็จ-</h2></td></tr>";
				}
		?>
    </table>
</body>
