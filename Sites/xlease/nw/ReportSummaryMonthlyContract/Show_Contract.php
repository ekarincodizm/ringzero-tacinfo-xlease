<?php
	include("../../config/config.php"); 
	include("table_function.php");
 	// echo '<<<'.$_REQUEST['s_month'].'>>>'.$_REQUEST['s_year'].'&&&'.$_REQUEST['s_contract_list'];
 
	// รับค่าตัวแปร เพื่อการสืบค้นข้อมูล สำหรับ ทำรายงานสรุปสัญญา
	$Contract_List = pg_escape_string($_REQUEST['s_contract_list']);
	$Month = pg_escape_string($_REQUEST['s_month']);
	$Year = pg_escape_string($_REQUEST['s_year']);
	$Contract_Arr = split(" ", $Contract_List);
	$Array_Long = count($Contract_Arr);
 
?>
<HTML>
	<center>
	<table border=0 bgcolor="#CCCCCC" width="200%">
    	<?php 
    		Head_Table($Month,$Year+543); 
			for($i=0;$i<$Array_Long-1;$i++){
				Start_Contract($Contract_Arr[$i]); // แสดงข้อมูลเกี่ยวกับ  ประเภทสัญญา ใน 1 row
				Show_Contract_Detail($Month,$Year,$Contract_Arr[$i]);
			} 
    	?>
    	
	</table>
	</center>
	
</HTML>