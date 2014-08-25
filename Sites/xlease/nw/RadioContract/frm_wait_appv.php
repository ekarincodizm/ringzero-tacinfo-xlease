<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
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
	<table align="center" width="900"  cellspacing="1" cellpadding="1" bgcolor="#CDC9C9">
		<tr><td colspan="8" bgcolor="#FFFFFF"><b>รายการที่รออนุมัติอยู่ในขณะนี้</b></td></tr>
		<tr align="center" bgcolor="#8B8989" style="color:#FFFFFF" height="25">
			<th>เลขที่สัญญาวิทยุ</th>
			<th>รหัสวิทยุ</th>
			<th>ทะเบียนรถยนต์</th>
			<th>ชื่อ  - นามสกุล</th>
		</tr>
		<?php	
		$query=pg_query("select * from public.\"RadioContract\" where \"ContractStatus\" = '0' order by \"COID\" ");
		$numrows = pg_num_rows($query);
		$i=0;
		while($result = pg_fetch_array($query))
		{
			$i++;		
			$COID = $result["COID"];
			$RadioNum = $result["RadioNum"];
			$RadioCar = $result["RadioCar"];
			$RadioRelationID = $result["RadioRelationID"];
				$query_rid=pg_query("select * from public.\"GroupCus_Active\" where \"GroupCusID\" = '$RadioRelationID' ");
				$numrow=pg_num_rows($query_rid);
				if($numrow==1)
				{
					while($result2=pg_fetch_array($query_rid)){
					$CusID = $result2["CusID"];
					}
						$query_name=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$CusID' ");
							while($result3=pg_fetch_array($query_name)){
							$A_FIRNAME = $result3["A_FIRNAME"];
							$A_NAME = $result3["A_NAME"];
							$A_SIRNAME = $result3["A_SIRNAME"];
							}
				}
				else
				{
					$A_FIRNAME = "";
					$A_NAME = "";
					$A_SIRNAME = "";
				}
			
			if($i%2==0){
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFE4C4';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}else{
				echo "<tr  bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFE4C4';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
			
			echo "<td align=\"center\">$COID</td>";
			echo "<td align=\"center\">$RadioNum</td>";
			echo "<td align=\"center\">$RadioCar</td>";
			echo "<td align=\"center\">$A_FIRNAME $A_NAME $A_SIRNAME</td>";		
			echo "</tr>";
			
		}
		if($numrows==0){
			echo "<tr bgcolor=#EEE9E9 height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#FFFF66\" ><td colspan=10><b>ทั้งหมด $numrows รายการ</b></td><tr>";
		}
		?>
	</table>
</body>
</html>