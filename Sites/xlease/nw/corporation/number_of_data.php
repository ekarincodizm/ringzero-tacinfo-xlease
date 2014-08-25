<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php"); 
?>
<?php
	$query_old = pg_query("select a.\"corp_regis\" , a.\"corpType\" , a.\"corpName_THA\" , a.\"Approved\" , a.\"corpEdit\" from public.\"th_corp_temp\" a 
							where a.\"corpEdit\" = (select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" b where b.\"corp_regis\" = a.\"corp_regis\") 
							and (a.\"Approved\" is null or a.\"Approved\" = 'false') 
							and a.\"hidden\" = 'false' and a.\"corpID\" = '0' ");
	$numrows_old = pg_num_rows($query_old);
	
	echo "มีข้อมูลนิติบุคคลที่ <u>รออนุมัติ</u> และ <u>ไม่อนุมัติ</u> จำนวน <b>$numrows_old</b> รายการ";
?>