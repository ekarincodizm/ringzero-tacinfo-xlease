<?php
require_once("../../../config/config.php");

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";
				
				// นับจำนวนรายการทั้งหมด
				$qry_selcol = pg_query("
					SELECT \"colpre_serial\",\"contractID\" ,\"colpre_debtamt\",\"colpre_debtdetails\"
					FROM thcap_collect_pre					
					WHERE colpre_status = '0'");
				$row_Selcol=0;
				while($re_selcol = pg_fetch_array($qry_selcol)){
					$conid = $re_selcol['contractID'];
					$colpre_debtamt = $re_selcol['colpre_debtamt'];
					$Debt_Details = $re_selcol['colpre_debtdetails'];
					//จำนวนวันที่คำนวณในการติดตาม ให้ ดูตาม thcap_config แต่หากมีราย สัญญาที่ กำหนดวันที่ไม่ตรงกับ thcap_config ให้ ยึด ตาม รายสัญญา
				
					//--1.จำนวนวัน ที่กำหนดเป็นรายสัญญา ในตาราง  thcap_config
					$qry_config = pg_query("SELECT config_value FROM \"thcap_config\" where \"config_control\" = '$conid' 
					AND \"config_variable\" = 'collection_check_first'");
					list($s_config_control) = pg_fetch_array($qry_config); 
				
					//--2.หาวันที่ค้างชำระ
					$qry_over_date = pg_query("select (current_date-\"backDueDate\") AS \"over\" from \"thcap_lease_contract\" a
					left join \"thcap_backDueDatePerDay\" b on a.\"contractID\"=b.\"contractID\" 
					where a.\"contractID\"='$conid'");
					list($n_over_date) = pg_fetch_array($qry_over_date);
					$n_over_date=number_format($n_over_date,2);
					//--3.ตรวจสอบ
					$chk='0';
					if(($s_config_control > $n_over_date) and ($n_over_date !='') and ($s_config_control !='')){
						$chk='1';//ไม่แสดง
					}				
					
					//เบี้ยปรับน้อยกว่า 100 บาท
					$Details = explode("<p>",$Debt_Details);
					$txt_1="";
					for($num = 0;$num<sizeof($Details);$num++){
						$count=0;
						$detail=trim($Details[$num]);
						$txt =str_replace('- ค่าเบี้ยปรับ','',$detail,$count);
					
						if($count>0){
							$txt_1 =str_replace('บาท','',$txt);		
							continue;
						}					
					}
					$num_btwnt=0;
					// อยู่ระหว่างช่วง NT
					$qry_btwnt = pg_query("select \"NT_ID\" from \"thcap_history_nt\" 
					where \"contractID\" = '$conid' and \"NT_isprint\"='1' AND \"NT_enddate\" is null AND \"NT_Date\"::date <= current_date");
					$num_btwnt=pg_num_rows($qry_btwnt);
					// อยู่ระหว่างฟ้องศาล
					$dateSue=pg_query("select \"thcap_get_all_dateSue\"('$conid')<=current_date");
					list($s_dateSue) = pg_fetch_array($dateSue);
				
				
					
					//หาผลรวมหนี้ต่าง ๆ ที่ยังไม่ถึงกำหนด
					$qry_typePayLeft = pg_query("SELECT sum(a.\"typePayLeft\") AS \"sum_typePayLeft\"  FROM  thcap_v_otherpay_debt_realother_current a
					left join  account.\"thcap_typePay\"  b on a.\"typePayID\"=b.\"tpID\" 
					WHERE
						\"debtStatus\" = 1 AND
						\"typePayLeft\" > 0 AND 
						((\"debtDueDate\" IS NOT NULL) AND (\"debtDueDate\" > current_date)) AND
						\"contractID\" = '$conid'");
					$re_qry_typePayLeft = pg_fetch_array($qry_typePayLeft);
					
					if(($re_qry_typePayLeft["sum_typePayLeft"]==$colpre_debtamt)OR (($txt_1!="") AND ($txt_1 <=100))OR ($num_btwnt >0) OR(($s_dateSue !="") and ($s_dateSue=='t')) OR ($chk=='1')){}
					else{	$row_Selcol++;	}
				}				
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด <font color=\"red\"> ($row_Selcol)</font></a></div>";				
				$qry_year=pg_query("
					SELECT distinct(\"conType\")
					FROM thcap_contract
				");
				$row_Selcol=0;
				while($restype=pg_fetch_array($qry_year)){
					$row_Selcol=0;
					list($contracttype)=$restype;
					$qry_selcol = pg_query("
					SELECT \"colpre_serial\",\"contractID\" ,\"colpre_debtamt\",\"colpre_debtdetails\"
					FROM thcap_collect_pre					
					WHERE colpre_status = '0' AND \"thcap_get_contractType\"(\"contractID\") = '$contracttype'");
				$row_Selcol=0;
				while($re_selcol = pg_fetch_array($qry_selcol)){
					$conid = $re_selcol['contractID'];
					$colpre_debtamt = $re_selcol['colpre_debtamt'];
					$Debt_Details = $re_selcol['colpre_debtdetails'];
					//จำนวนวันที่คำนวณในการติดตาม ให้ ดูตาม thcap_config แต่หากมีราย สัญญาที่ กำหนดวันที่ไม่ตรงกับ thcap_config ให้ ยึด ตาม รายสัญญา
				
					//--1.จำนวนวัน ที่กำหนดเป็นรายสัญญา ในตาราง  thcap_config
					$qry_config = pg_query("SELECT config_value FROM \"thcap_config\" where \"config_control\" = '$conid' 
					AND \"config_variable\" = 'collection_check_first'");
					list($s_config_control) = pg_fetch_array($qry_config); 
				
					//--2.หาวันที่ค้างชำระ
					$qry_over_date = pg_query("select (current_date-\"backDueDate\") AS \"over\" from \"thcap_lease_contract\" a
					left join \"thcap_backDueDatePerDay\" b on a.\"contractID\"=b.\"contractID\" 
					where a.\"contractID\"='$conid'");
					list($n_over_date) = pg_fetch_array($qry_over_date);
					$n_over_date=number_format($n_over_date,2);
					//--3.ตรวจสอบ
					$chk='0';
					if(($s_config_control > $n_over_date) and ($n_over_date !='') and ($s_config_control !='')){
						$chk='1';//ไม่แสดง
					}				
					
					//เบี้ยปรับน้อยกว่า 100 บาท
					$Details = explode("<p>",$Debt_Details);
					$txt_1="";
					for($num = 0;$num<sizeof($Details);$num++){
						$count=0;
						$detail=trim($Details[$num]);
						$txt =str_replace('- ค่าเบี้ยปรับ','',$detail,$count);
					
						if($count>0){
							$txt_1 =str_replace('บาท','',$txt);		
							continue;
						}					
					}
					$num_btwnt=0;
					// อยู่ระหว่างช่วง NT
					$qry_btwnt = pg_query("select \"NT_ID\" from \"thcap_history_nt\" 
					where \"contractID\" = '$conid' and \"NT_isprint\"='1' AND \"NT_enddate\" is null AND \"NT_Date\"::date <= current_date");
					$num_btwnt=pg_num_rows($qry_btwnt);
					// อยู่ระหว่างฟ้องศาล
					$dateSue=pg_query("select \"thcap_get_all_dateSue\"('$conid')<=current_date");
					list($s_dateSue) = pg_fetch_array($dateSue);
				
				
					
					//หาผลรวมหนี้ต่าง ๆ ที่ยังไม่ถึงกำหนด
					$qry_typePayLeft = pg_query("SELECT sum(a.\"typePayLeft\") AS \"sum_typePayLeft\"  FROM  thcap_v_otherpay_debt_realother_current a
					left join  account.\"thcap_typePay\"  b on a.\"typePayID\"=b.\"tpID\" 
					WHERE
						\"debtStatus\" = 1 AND
						\"typePayLeft\" > 0 AND 
						((\"debtDueDate\" IS NOT NULL) AND (\"debtDueDate\" > current_date)) AND
						\"contractID\" = '$conid'");
					$re_qry_typePayLeft = pg_fetch_array($qry_typePayLeft);
					
					if(($re_qry_typePayLeft["sum_typePayLeft"]==$colpre_debtamt)OR (($txt_1!="") AND ($txt_1 <=100))OR ($num_btwnt >0) OR(($s_dateSue !="") and ($s_dateSue=='t')) OR ($chk=='1')){}
					else{	$row_Selcol++;	}
				}				
				//echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด <font color=\"red\"> ($row_Selcol)</font></a></div>";	
				echo "<div class=\"tab active\"><a id=\"$contracttype\" href=\"javascript:list_tab_menu('$contracttype');\">$contracttype <font color=\"red\"> ($row_Selcol) </font></a></div>";	
				}			
			echo "
				</div>
			</div>
		</div>
	</div>
	<div class=\"list_tab_menu\"></div>
";
?>