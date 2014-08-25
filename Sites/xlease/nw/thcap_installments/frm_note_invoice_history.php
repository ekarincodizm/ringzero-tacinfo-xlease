<?php
include("../../config/config.php");

$contractID = $_GET['contractID']; //เลขที่สัญญา

$qry_note_invoice = pg_query("select * from \"thcap_contract_note\" where \"contractID\"= '$contractID' and \"noteType\" = '1' order by \"doerStamp\" desc ");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แสดงประวัติการแก้ไขหมายเหตุการวางบิล/ใบแจ้งหนี้</title>
<link href="act.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center">
	<fieldset style="width:1000px;">
    	<legend><b>ประวัติการแก้ไขหมายเหตุการวางบิล/ใบแจ้งหนี้ :: เลขที่สัญญา <?php echo $contractID; ?></b></legend>
        <div style="width:960px; margin-top:15px;">
        	<table border="0" cellpadding="5" cellspacing="1" width="100%">
            	<tr bgcolor="#79BCFF">
                	<th>ครั้งที่</th>
                    <th>หมายเหตุ</th>
                    <th>ผู้แก้ไข</th>
                    <th>วันเวลาที่แก้ไข</th>
					<th>ผู้อนุมัติ</th>
                    <th>วันเวลาที่อนุมัติ</th>
					<th>ผลการอนุมัติ</th>
					<th>ยกเลิก</th>
                </tr>
                <?php
				$i = 0;
				while($res = pg_fetch_array($qry_note_invoice))
				{
					$i++;
					$noteID = $res["noteID"]; // รหัส note
					$noteDetail = $res["noteDetail"]; // หมายเหตุ
					$doerID = $res["doerID"]; // ผู้แก้ไข
					$doerStamp = $res["doerStamp"]; // วันเวลาที่แก้ไข
					$appvID = $res["appvID"]; // ผู้อนุมัติ
					$appvStamp = $res["appvStamp"]; // วันเวลาที่อนุมัติ
					$Approved = $res["Approved"]; // ผลการอนุมัติ
					$cancel = $res["cancel"]; // ยกเลิกหรือไม่
					
					if($Approved == "t"){$ApprovedText = "อนุมัติ";}
					elseif($Approved == "f"){$ApprovedText = "ไม่อนุมัติ";}
					elseif($Approved == ""){$ApprovedText = "รออนุมัติ";}
					else{$ApprovedText = "";}
					
					if($cancel == "t")
					{
						echo "<tr style=\"background-color:#FF0000; font-size:11px;\">";
					}
					else
					{
						if($i%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
					}
					
					// หาชื่อผู้ทำรายการ
					$qry_doerFullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"= '$doerID' ");
					$doerFullname = pg_fetch_result($qry_doerFullname,0);
					
					// หาชื่อผู้อนุมัติ
					$qry_appvFullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"= '$appvID' ");
					$appvFullname = pg_fetch_result($qry_appvFullname,0);
					
					echo "<td align=\"center\">$i</td>";
					echo "<td align=\"left\">$noteDetail</td>";
					//echo "$doerID";
					//echo "<tr>$appvID</tr>";
					echo "<td align=\"left\">$doerFullname</td>";
					echo "<td align=\"center\">$doerStamp</td>";
					echo "<td align=\"left\">$appvFullname</td>";
					echo "<td align=\"center\">$appvStamp</td>";
					echo "<td align=\"center\">$ApprovedText</td>";
					if($cancel != "t" || $Approved != "f"){echo "<td align=\"center\"><a href=\"frm_del_note_invoice.php?noteID=$noteID\"><img src=\"../thcap/images/del.png\" width=\"19\" height=\"19\" style=\"cursor:pointer;\"></a></td>";}
					else{echo "<td align=\"center\"></td>";}
					echo "</tr>";
				}
				?>
            </table>
        </div>
    </fieldset>
</div>
</body>
</html>