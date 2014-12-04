<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>(THCAP) รายงานสัญญาที่อยู่ระหว่างยึด</title>
<script language=javascript>
	function popU(U,N,T){
		newWindow = window.open(U, N, T);
	}
</script>
</head>
<body>
	<div  align="center"><h1>(THCAP) รายงานสัญญาที่อยู่ระหว่างยึด</h1><h3>แสดงสัญญาที่ยังอยู่ระหว่างการยึด (ยังยึดได้ไม่ครบถ้วน)</h3></div>
	<div id="panel" style="padding-top: 10px;">
		<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
			<tr align="center" bgcolor="#79BCFF">
				<th>รายการที่</th>
				<th>เลขที่สัญญา</th>
				<th>วันที่เริ่มทำการยึด</th>
			</tr>
			<?php
			$qry_conType = pg_query("
										SELECT
											\"conType\"
										FROM
											\"thcap_contract_type\"
										WHERE
											\"conType\" IN
											(
												SELECT
													DISTINCT \"thcap_get_contractType\"(\"contractID\")
												FROM
													\"thcap_mg_contract_current\"
												WHERE
													\"thcap_get_all_date_seize\"(\"contractID\") IS NOT NULL AND
													\"thcap_get_all_date_totalseize\"(\"contractID\") IS NULL
											)
										ORDER BY
											\"conType\"
									");
			$i=0;
			while($res_conType = pg_fetch_array($qry_conType))
			{
				$conType = $res_conType["conType"];
				
				echo "<tr bgcolor=\"#CCCCFF\"><td colspan=3 align=\"center\"><b>ประเภทสัญญา $conType</b></td><tr>";
				
				$query = pg_query("
									SELECT
										DISTINCT \"contractID\",
										\"thcap_get_all_date_seize\"(\"contractID\")
									FROM
										\"thcap_mg_contract_current\"
									WHERE
										\"thcap_get_all_date_seize\"(\"contractID\") IS NOT NULL AND
										\"thcap_get_all_date_totalseize\"(\"contractID\") IS NULL AND
										\"thcap_get_contractType\"(\"contractID\") = '$conType'
									ORDER BY
										2, 1
								");
				$numrows = pg_num_rows($query);
				$l=0;
				while($result = pg_fetch_array($query))
				{
					$i++;
					$l++;
					$contractID = $result["contractID"];
					$thcap_get_all_date_seize = $result["thcap_get_all_date_seize"];
					
					if($l%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
					
					echo "<td align=center>".number_format($l,0)."</td>";
					echo "<td align=center><font color=\"#0000FF\" style=\"cursor:pointer;\" onClick=\"popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=650')\"><u>$contractID</u></font></td>";
					echo "<td align=center>$thcap_get_all_date_seize</td>";
				} //end while
				
				echo "<tr class=\"sum\"><td colspan=3 align=\"left\">รวม $conType : ".number_format($l,0)." รายการ</td><tr>";
			}

			if($i==0){
				echo "<tr bgcolor=#FFFFFF height=50><td colspan=3 align=center><b>ไม่พบรายการ สัญญาที่อยู่ระหว่างยึด</b></td><tr>";
			}else{
				echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=3><b>รวมทั้งหมด ".number_format($i,0)." รายการ</b></td><tr>";
			}
			?>
		</table>
	</div>
</body>
</html>