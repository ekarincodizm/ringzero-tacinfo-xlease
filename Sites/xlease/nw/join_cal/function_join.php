<?php

function date_ch_form_m($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		//$yy =$yy+543;

				$new_date = "$mm/$yy";
				return $new_date;
}
function date_ch_form_m2($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		$yy =$yy+543;

				$new_date = "$mm/$yy";
				return $new_date;
}
function date_ch_form_c($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		//$yy =$yy+543;

				$new_date = "$dd/$mm/$yy";
				return $new_date;
}
function date_ch_form($Str){
		list($dd,$mm,$yy)=split("/",$Str);
		//$yy =$yy-543;
				$new_date = "$yy-$mm-$dd";
				return $new_date;
}
function receipt_d($IDNO2,$trid){
	
		$join_type1=pg_query("select join_get_join_type(1)"); 
		$o_type_id1 = pg_fetch_result($join_type1,0); // รหัสค่าเข้าร่วม รวมแรกเข้า
		
		$join_type1=pg_query("select join_get_join_type(2)"); 
		$o_type_id2 = pg_fetch_result($join_type1,0); // รหัสค่าเข้าร่วมธรรมดา
		
		
		$Arr1 = explode("#", $o_type_id1);
		$Arr2 = explode("#", $o_type_id2);
		
		$qr ="select \"O_Type\" from \"FOtherpay\" WHERE \"O_RECEIPT\" ='$trid' AND \"IDNO\"='$IDNO2' and ( " ;
for($z = 0; $z < count($Arr1); $z++){
	$qr .="\"O_Type\"='$Arr1[$z]' or ";
	
}
for($z = 0; $z < count($Arr2); $z++){
	
	if($z<(count($Arr2)-1)){
		$qr .="\"O_Type\"='$Arr2[$z]' or ";
		
	}else{
		$qr .="\"O_Type\"='$Arr2[$z]' ) ";
		
	}
	
}


		$qry_con=pg_query($qr);
		$n_j = pg_num_rows($qry_con);//ถ้าเป็นค่าเข้าร่วม
		
		if($n_j>0){
		$query5 = "SELECT period_date,expire_date,pay_type,amount,cpro_name,id_main FROM ta_join_payment WHERE ta_join_payment_id='$trid' and deleted='0' ORDER BY id ";
		$sql_query5 = pg_query($query5);
		$num_row=pg_num_rows($sql_query5);
		$c_j =0;
		if($num_row!=0){
				while($sql_row5 = pg_fetch_array($sql_query5))
				{
					$period_date[$c_j] = date_ch_form_m2($sql_row5['period_date']); 	//เริ่มเดือน					

					$expire_date[$c_j] = date_ch_form_m2($sql_row5['expire_date']);//ถึงเดือน
					$ext_join[3][0] = $sql_row5['cpro_name']; // ชื่อลูกค้า
					$id_main= $sql_row5['id_main'];
					$pay_type[$c_j] = $sql_row5['pay_type'];
					$ext_join[$c_j][1] = $sql_row5['amount'];
					//$amount_month[$c_j] = $sql_row5['amount_month'];
					if($pay_type[$c_j]==1)$pay_m[$c_j]="100";else $pay_m[$c_j]="300";
					
					
					if($ext_join[$c_j][1]==5000){
					$ext_join[$c_j][0] = "- ค่าแรกเข้า ";	
					}else{
						if($period_date!=$expire_date){ //มากกว่า 1เดือน
					$ext_join[$c_j][0] = "- ค่าเข้าร่วม($pay_m[$c_j] บ/ด) ตั้งแต่เดือน $period_date[$c_j] ถึง $expire_date[$c_j]";
						}else{//เดือนเดียว
					$ext_join[$c_j][0] = "- ค่าเข้าร่วม($pay_m[$c_j] บ/ด) เดือน $period_date[$c_j]";		
						}
					}

					$c_j++;
				}
				
				
				
			$query4 = "SELECT address FROM \"VJoinMain\" WHERE id='$id_main' ";				
			$sql_query4 = pg_query($query4);
			$ext_join[3][1] =@pg_fetch_result($sql_query4,0);// ที่อยู่
			if($ext_join[3][1]=="")$ext_join[3][1]="-";

				
				

					}
				}
	return $ext_join ;
	
}


?>