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
<title>(THCAP) อนุมัติปิดสัญญา</title>
<script language=javascript>
	function popU(U,N,T){
		newWindow = window.open(U, N, T);
	}
</script>
</head>
<body>
	<div  align="center"><h2>(THCAP) อนุมัติปิดสัญญา</h2></div>
	<div id="panel" style="padding-top: 10px;">
		<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
			<tr align="center" bgcolor="#79BCFF">
				<th>รายการที่</th>
				<th>เลขที่สัญญา</th>
				<th>วันที่ปิดสัญญา</th>
				<th>ทำรายการ</th>
			</tr>
			<?php 
			$query = pg_query("
								SELECT
									DISTINCT \"contractID\",
									\"thcap_checkcontractcloseddate\"(\"contractID\") AS \"contractcloseddate\"
								FROM
									\"thcap_mg_contract_current\"
								WHERE
									\"thcap_checkcontractcloseddate\"(\"contractID\") IS NOT NULL AND -- สัญญาที่ปิดแล้ว ทางบัญชี
									\"thcap_get_all_isAbsClose\"(\"contractID\", current_date) <> '1' AND -- สัญญาที่ยังไม่ปิด ทาง abs
									\"contractID\" NOT IN(select \"contractID\" from \"thcap_contract_absclose_request\" where \"conabsclose_status\" = '9') -- ไม่เอารายการที่ขอผ่านระบบปกติ
								ORDER BY
									2, 1
							");

			$numrows = pg_num_rows($query);
			$i=0;
			while($result = pg_fetch_array($query))
			{
				$i++;
				$contractID=$result["contractID"];
				$contractcloseddate=$result["contractcloseddate"];
				
				if($i%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
				
				echo "<td align=center>".number_format($i,0)."</td>";
				echo "<td align=center><font color=\"#0000FF\" style=\"cursor:pointer;\" onClick=\"popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=650')\"><u>$contractID</u></font></td>";
				echo "<td align=center>$contractcloseddate</td>";
				echo "<td align=center><input type=\"button\" value=\"ปิดสัญญา\" style=\"cursor:pointer;\" onClick=\"popU('popup_approve.php?contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')\" /></td>";
			} //end while

			if($numrows==0){
				echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>ไม่พบรายการ</b></td><tr>";
			}else{
				echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=7><b>ทั้งหมด ".number_format($i,0)." รายการ</b></td><tr>";
			}
			?>
		</table>
	</div>
</body>
</html>