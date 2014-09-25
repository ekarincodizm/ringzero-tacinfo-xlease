<?php
	// Purpose 			: - For Use In Menu "(THCAP) ตารางแสดงการผ่อนชำระ"
	//			 	      -	 แสดงข้อมูลเกี่ยวกับสัญญา ดังนี้ วันที่ฟ้อง,วันที่ขายโอนโอนสิทธิ์, วันที่ยืดคืนทรัพย์สิน,วันที่ปิดบัญชี
	// Create Date		: 2014-08-29
	// Create By 		: Kittichai Ruangsri
	// Input 		 	: $contractID เก็บข้อมูบ เลขที่สัญญา
	// Main Process		:  สืบค้นข้อมูล  วันที่ฟ้อง,วันที่ขายโอนโอนสิทธิ์, วันที่ยืดคืนทรัพย์สิน,วันที่ปิดบัญชี  ตามเลขที่สัญญา
	// Output			: To Screen
	
	//  สืบค้น วันที่ฟ้อง
	$qr1 = "select \"thcap_get_all_dateSue\"('$contractID') "; 
	$result = pg_query($qr1);
	$num_row = pg_num_rows($result);
	$Data = pg_fetch_result($result,0);
	if(EMPTY($Data)){
		$Msg_litigation = '';
	}else{
		$Msg_litigation = "<B>วันที่ฟ้อง : </B>". $Data;	
	} 

	//สืบค้น วันที่ยึดทรัพย์สิน
	$qr1="select \"thcap_get_all_date_seize\"('$contractID') ";
	$result = pg_query($qr1);
	$Data = pg_fetch_result($result,0);
	if(EMPTY($Data)){
		$Msg_detention = '';
	}else{ 
		$Msg_detention = "<B>วันที่ยึดรัพย์ : </B>". $Data;
	}
	
	// สืบค้น วันที่ขายโดนโอนสิทธิ์
	$qr1 = "select \"thcap_get_all_date_sold\"('$contractID') ";  
	$result = pg_query($qr1);
	$Data = pg_fetch_result($result,0);
	if(EMPTY($Data)){
		$Msg_Assignee = '';
	}else{
		$Msg_Assignee = "<B>วันที่ขายสัญญาโดยโอนหนี้: </B>".$Data;
	}
	
	
	
	// สืบค้น วันที่ ปิดบัญชี
	$qr1= "select \"thcap_checkcontractcloseddate\"('$contractID') ";
	$result = pg_query($qr1);
	$Data = pg_fetch_result($result,0);
	if(EMPTY($Data)){
		$Msg_Closing = '';
	}else{  
		$Msg_Closing = "<B>วันที่ปิดบัญชี :</B>".$Data; 
	}
	//echo " ".$Msg_litigation." ".$Msg_Assignee." ".$Msg_detention." ".$Msg_Closing;
 ?>
 <HTML>
	<table width = 100%>
		<TR>
			<?php
				if($Msg_litigation != ''){
					echo "<TD BGCOLOR = #FFFF99>";   
					echo $Msg_litigation; // แสดงววันที่ฟ้อง
					echo "</TD>";
				} 
				
				if($Msg_detention != ''){
					echo "<TD BGCOLOR = #FFFF99>";   
					echo $Msg_detention; // วันที่ยึดทรัพย์สิน
					echo "</TD>";
				} 
				
				if($Msg_Assignee != ''){
					echo "<TD BGCOLOR = #FFFF99>";   
					echo $Msg_Assignee; // วันที่ขายโดยโอนสิทธิ์
					echo "</TD>"; 
				}
				
							
				if($Msg_Closing != ''){
					echo "<TD BGCOLOR = #FFFF99>";   
					echo $Msg_Closing; // วันที่ปิดบัญชี
					echo "</TD>";
				}
			 ?>
		</TR>
	</table>	
 </HTML