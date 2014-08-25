<?php
include("../../config/config.php");
$language_user=$_SESSION['language'];

if($language_user=='TH'){	
	include("../../language/landTH.php");
}
else if($language_user=='LO'){	
	include("../../language/landLO.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	
    <title><?php echo $land_industrial_title; ?></title>	
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
<center><h2><?php echo $land_industrial_h2; ?></h2></center>

<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th width="60" align="center">&nbsp;<?php echo $land_global_tb_items; ?>&nbsp;</th>
		<th align="center">&nbsp;&nbsp;&nbsp;<?php echo $land_industrial_tb_name; ?>&nbsp;&nbsp;&nbsp;</th>
	</tr>
	<?php
	$query = pg_query("select * from public.\"th_corp_industype\" order by \"IndustypeID\" ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$IndustypeID = $result["IndustypeID"]; // รหัสประเภทอุตสาหกรรม
		$IndustypeName = $result["IndustypeName"]; // ชื่อประเภทอุตสาหกรรม
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"left\">$IndustypeName</td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=2 align=center><b>$land_global_search_found</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=2><b>$land_global_tb_itemsdata $i $land_global_tb_itemsno</b></td><tr>";		
	}
	?>
</table>

<br>

<center>
<input type="button" name="add" value="<?php echo $land_global_btn_add;?>" onclick="javascript:popU('frm_add.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')"> &nbsp;&nbsp;&nbsp; <input type="button" value="<?php echo $land_global_btn_close;?>" onclick="javascript:window.close();">
		
</center>
</body>
</html>