<?php 
include("../../config/config.php");
$CusID = $_GET['cusid'];

$qry_name=pg_query("select \"full_name\" from \"VSearchCus\" WHERE \"CusID\" = '$CusID'");
$result=pg_fetch_array($qry_name);
$name=trim($result["full_name"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
	<table  align="center">
		<tr>
			<td align="center">
				<br>
			</td>
		</tr>
		<tr>
			<td align="center">
				<font size="5px"><b>หลักทรัพย์ค้ำประกันกับบริษัท</b></font><p><font size="3px" color="#CD853F"><b>-- <?php echo $name; ?> --</b></font>
			</td>
		</tr>
	</table>
	<table frame="box" align="center" bgcolor="#EEEEE0">
		<?php
			$sql = pg_query("SELECT distinct(a.\"numid\") as numid FROM nw_linknumsecur a left join nw_securities_customer b on a.\"securID\" = b.\"securID\" 
							 where b.\"CusID\" = '$CusID' ");
			$sqlrows = pg_num_rows($sql);	
			if($sqlrows == 0){ echo "<div style=\"background-color:#FFFF99\"> ไม่พบข้อมูล </div>"; }else{				 
				while($result = pg_fetch_array($sql)){
					$numid = $result["numid"];
					$cusco = 'true';	
				
				echo "<tr><td align=\"center\">"; include("../securities/frm_ShowdetailLink.php"); echo "</td></tr>";
			
			
					$numid = "";
					$cusco = "";
				}
			}
		?>
		
	</table>
</body>
</html>