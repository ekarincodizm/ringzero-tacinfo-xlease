<?php  
	function Add_2_Array($Arr_A,$Arr_B){
		$Result = array(8);
		// Clear All Value In Array To 0.00
		// $Result[0] = 0.00; $Result[1] = 0.00; $Result[2] = 0.00; $Result[4] = 0.00; $Result[5] = 0.00; $Result[6] = 0.00; $Result[7] = 0.00; 
		// Sum Data In Array
		$Result[0] = $Arr_A[0] + $Arr_B[0];
		$Result[1] = $Arr_A[1] + $Arr_B[1];
		$Result[2] = $Arr_A[2] + $Arr_B[2];
		$Result[3] = $Arr_A[3] + $Arr_B[3]; 
		$Result[4] = $Arr_A[4] + $Arr_B[4];
		$Result[5] = $Arr_A[5] + $Arr_B[5];
		$Result[6] = $Arr_A[6] + $Arr_B[6];
		$Result[7] = $Arr_A[7] + $Arr_B[7];
		return($Result);
	}
	function Create_SQL_Cmd($Contract_Type,$Month,$Year){
		$Str_Query = " SELECT ";
		$Str_Query = $Str_Query."\"conDate\",";  // วันที่ทำสัญญา
		$Str_Query = $Str_Query."\"conType\","; // ประเภทสัญญา
		$Str_Query = $Str_Query."\"contractID\","; // เลขที่สัญญา 		
		$Str_Query = $Str_Query." (select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = (select ta_array_get(\"thcap_contract_temp\".\"CusIDarray\", '0'))) AS \"CusName\", "; // ชื่อลูกค้า
		$Str_Query = $Str_Query." (select \"fullname\" from \"Vfuser\" where \"id_user\" = \"thcap_contract_temp\".\"case_owners_id\") AS \"case_owners_name\", "; // เจ้าของเคส
		$Str_Query = $Str_Query." (select \"fullname\" from \"Vfuser\" where \"id_user\" = \"thcap_contract_temp\".\"doerUser\") AS \"doerName\", ";//  คนทำสัญญา";
		$Str_Query = $Str_Query." \"doerStamp\", ";// วันเวลาทำรายการ
		$Str_Query = $Str_Query." (select \"fullname\" from \"Vfuser\" where \"id_user\" = (select \"appvID\" from \"thcap_contract_check_temp\" where \"autoID\" = (select min(\"autoID\") from \"thcap_contract_check_temp\" where \"ID\" = \"thcap_contract_temp\".\"autoID\"))) AS \"examiner1\", ";  // -- คนตรวจ 1  ;
		$Str_Query = $Str_Query." (select \"fullname\" from \"Vfuser\" where \"id_user\" = (select \"appvID\" from \"thcap_contract_check_temp\" where \"autoID\" = (select min(\"autoID\") from \"thcap_contract_check_temp\" where \"ID\" = \"thcap_contract_temp\".\"autoID\" and \"autoID\" > (select min(\"autoID\") from \"thcap_contract_check_temp\" where \"ID\" = \"thcap_contract_temp\".\"autoID\")))) AS \"examiner2\", "; // คนตรวจ 2
		$Str_Query = $Str_Query." CASE WHEN \"conDownToFinanceVat\" IS NOT NULL THEN ";//
		$Str_Query = $Str_Query."\"conDownToFinance\" + \"conDownToFinanceVat\" "; 		
		$Str_Query = $Str_Query." ELSE ";
		$Str_Query = $Str_Query." CASE WHEN \"conDownToFinance\" IS NOT NULL THEN ";
		$Str_Query = $Str_Query." \"conDownToFinance\" ";
		$Str_Query = $Str_Query." ELSE"; 	
		$Str_Query = $Str_Query." CASE WHEN \"conDownToDealer\" IS NOT NULL THEN "; 
		$Str_Query = $Str_Query." \"conDownToDealer\" ";  
		$Str_Query = $Str_Query." ELSE ";
		$Str_Query = $Str_Query." NULL::numeric ";
		$Str_Query = $Str_Query." END ";
		$Str_Query = $Str_Query." END ";
		$Str_Query = $Str_Query." END AS \"conDown\","; // เงินดาวน์
		$Str_Query = $Str_Query." cal_rate_or_money('VAT', \"conDate\", \"conMinPay\", 2) AS \"conMinPayOutVat\", ";// -- ยอดผ่อนต่องวด ก่อน VAT";
		$Str_Query = $Str_Query."\"conMinPay\","; // ยอดผ่อนต่องวดรวม VAT
		$Str_Query = $Str_Query."\"conTerm\","; //จำนวนงวด
		$Str_Query = $Str_Query."\"conLoanIniRate\", "; // ดอกเบี้ยต่อปี(%)
		$Str_Query = $Str_Query." CASE WHEN (select sum(\"pricePerUnit\") from \"thcap_asset_biz_detail\" where \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_contract_asset_temp\" where \"contractID\" = \"thcap_contract_temp\".\"contractID\" and \"doerStamp\" = \"thcap_contract_temp\".\"doerStamp\")) IS NOT NULL THEN "; 
		$Str_Query = $Str_Query." cal_rate_or_money('VAT', \"conDate\", (select sum(\"pricePerUnit\") from \"thcap_asset_biz_detail\" where \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_contract_asset_temp\" where \"contractID\" = \"thcap_contract_temp\".\"contractID\" and \"doerStamp\" = \"thcap_contract_temp\".\"doerStamp\")), 2) ";
		$Str_Query = $Str_Query." END AS \"sumAssetAmtOutVat\", "; // ราคาสินทรัพย์ ก่อน VAT";
		$Str_Query = $Str_Query." (select sum(\"pricePerUnit\") from \"thcap_asset_biz_detail\" where \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_contract_asset_temp\" where \"contractID\" = \"thcap_contract_temp\".\"contractID\"  and \"doerStamp\" = \"thcap_contract_temp\".\"doerStamp\")) AS \"sumAssetAmt\", "; // ราคาสินทรัพย์ รวม VAT" 
		$Str_Query = $Str_Query." CASE WHEN \"conFinanceAmount\" IS NOT NULL THEN ";
		$Str_Query = $Str_Query." CASE WHEN \"conFinAmtExtVat\" IS NOT NULL THEN ";
		$Str_Query = $Str_Query." \"conFinAmtExtVat\"  ";
		$Str_Query = $Str_Query." ELSE ";
		$Str_Query = $Str_Query." cal_rate_or_money('VAT', \"conDate\", \"conFinanceAmount\", 2) ";
		$Str_Query = $Str_Query." END ";
		$Str_Query = $Str_Query." ELSE ";
		$Str_Query = $Str_Query." cal_rate_or_money('VAT', \"conDate\", \"conLoanAmt\", 2) ";
		$Str_Query = $Str_Query." END AS \"conAmtOutVat\", "; // ยอดจัด ก่อน VAT";
		$Str_Query = $Str_Query." CASE WHEN \"conFinanceAmount\" IS NOT NULL THEN ";
		$Str_Query = $Str_Query." \"conFinanceAmount\" ";
		$Str_Query = $Str_Query." ELSE ";
		$Str_Query = $Str_Query." \"conLoanAmt\" ";
		$Str_Query = $Str_Query." END AS \"conAmt\", "; // ยอดจัด รวม VAT
		$Str_Query = $Str_Query." \"Approved\","; // สถานะการอนุมัติ
		$Str_Query = $Str_Query." CASE WHEN \"Approved\" = TRUE THEN ";
		$Str_Query = $Str_Query."  'อนุมัติ'";
		$Str_Query = $Str_Query." ELSE ";
		$Str_Query = $Str_Query." CASE WHEN \"Approved\" = FALSE THEN ";
		$Str_Query = $Str_Query." 'ไม่อนุมัติ' ";
		$Str_Query = $Str_Query."  ELSE ";
		$Str_Query = $Str_Query." 'รออนุมัติ'";
		$Str_Query = $Str_Query." END ";
		$Str_Query = $Str_Query." END AS \"ApprovedText\" "; // ข้อความการอนุมัติ"
		$Str_Query = $Str_Query." FROM ";
		$Str_Query = $Str_Query." \"thcap_contract_temp\" ";
		$Str_Query = $Str_Query." WHERE ";
		$Str_Query = $Str_Query." (\"conCredit\" IS NULL) "; 
		$Str_Query = $Str_Query." AND (\"conType\" = '$Contract_Type') ";
		$Str_Query = $Str_Query." AND ( substring((to_char(\"conDate\", 'YYYY-MM-DD')) from 1 for 4) = '$Year' ";  // ปีที่เลือก
		$Str_Query = $Str_Query." AND  substring((to_char(\"conDate\", 'YYYY-MM-DD')) from 6 for 2) = '$Month' )"; 
		$Str_Query = $Str_Query." AND (\"Approved\" IS NULL OR \"Approved\" = TRUE) ";
		$Str_Query = $Str_Query." ORDER BY ";
		$Str_Query = $Str_Query." \"conDate\", \"contractID\" ";
		
		return($Str_Query);
	} 
	function Cerate_SQL_Comand_Asset_Buy($Contract_ID,$doer_stamp){
		$Str_Query = " SELECT ";
		$Str_Query = $Str_Query. "\"corpID\", ";
		$Str_Query = $Str_Query. " (select \"full_name\" ";
		$Str_Query = $Str_Query. " from \"VSearchCusCorp\" ";
		$Str_Query = $Str_Query. " where \"CusID\" = \"thcap_asset_biz\".\"corpID\") ";
		$Str_Query = $Str_Query. " AS \"buyFrom\" ";
		$Str_Query = $Str_Query. " FROM ";
		$Str_Query = $Str_Query. " \"thcap_asset_biz\"";
		$Str_Query = $Str_Query. " WHERE ";
		$Str_Query = $Str_Query. " \"assetID\" in";
		$Str_Query = $Str_Query. " (select \"assetID\" ";
		$Str_Query = $Str_Query. " from \"thcap_asset_biz_detail\" ";
		$Str_Query = $Str_Query. " where \"assetDetailID\" in ";
		$Str_Query = $Str_Query. " (select \"assetDetailID\" ";
		$Str_Query = $Str_Query. " from \"thcap_contract_asset_temp\" "; 
		$Str_Query = $Str_Query. " where \"contractID\" = '$Contract_ID' ";
		$Str_Query = $Str_Query. " and \"doerStamp\" = '$doer_stamp'))";
		$Str_Query = $Str_Query. " GROUP BY ";
		$Str_Query = $Str_Query. " \"corpID\" ";
		return($Str_Query);
		 
	}
	function Create_SQL_Comand_Tax_Date($Contract_ID,$doer_Stamp){
		$Str_Query = " SELECT";
		$Str_Query = $Str_Query." DISTINCT \"payDate\" ";
		$Str_Query = $Str_Query." FROM ";
		$Str_Query = $Str_Query." \"thcap_asset_biz\" ";
		$Str_Query = $Str_Query." WHERE ";
		$Str_Query = $Str_Query." \"assetID\" in ";
		$Str_Query = $Str_Query." (select \"assetID\" ";
		$Str_Query = $Str_Query." from \"thcap_asset_biz_detail\"  ";
		$Str_Query = $Str_Query." where \"assetDetailID\" in ";
		$Str_Query = $Str_Query." (select \"assetDetailID\" ";
		$Str_Query = $Str_Query." from \"thcap_contract_asset_temp\"  ";
		$Str_Query = $Str_Query." where \"contractID\" = '$Contract_ID'  ";
		$Str_Query = $Str_Query." and \"doerStamp\" = '$doer_Stamp')) ";
		return($Str_Query);
	}
	function Create_SQL_Comand_Money_Pledge($Contract_ID){
	// สร้าง Sql Comamd สำหรับดึงข้อมูลในส่วน ที่เป็นเงินมัดจำ ตามเลขที่สัญญา ที่ส่งมาไว้ในตัวแปร ที่ชื่อ $Contract_ID 
		$Str_Query = "
					 	SELECT
								a.\"contractID\",
								a.\"typePayID\",
								b.\"tpDesc\",
								SUM(a.\"typePayAmt\") AS \"typePayAmt\"
						FROM
								\"thcap_temp_otherpay_debt\" a,
								account.\"thcap_typePay\" b
						WHERE
								a.\"typePayID\" = b.\"tpID\" AND
								a.\"typePayID\" like '%997' AND
								a.\"debtStatus\" IN('1', '2', '9') AND  /* 1 = อนุมัติ , 2 = จ่ายแล้ว,3 = รออนุมัติ */ 
								a.\"contractID\" = '".$Contract_ID."'/* ระบุเลขที่สัญญาที่ต้องการ */
						GROUP BY
								a.\"contractID\",
								a.\"typePayID\",
								b.\"tpDesc\"
					  ";
		return($Str_Query);
	}
	function Display_number_float($num_in){
		if(is_null($num_in)){
			echo ' ';
		}else{
			echo number_format($num_in,2);	
		}
		
	}
	function Display_number_int($num_in){
		if(is_null($num_in)){
			echo '';
		}else{
			echo number_format($num_in,0);	
		}
		
	}
	function get_Asset_Buy_From($Contract_ID,$doer_Stamp){
		$SQL_Use = Cerate_SQL_Comand_Asset_Buy($Contract_ID,$doer_Stamp);
		$Result = pg_query($SQL_Use);
		$Buy_From_List = "";	
		while($data = pg_fetch_array($Result)){
			if($Buy_From_List != ""){
				$Buy_From_List = $Buy_From_List.' , '.$data["buyFrom"];
			}else{
				$Buy_From_List = $data["buyFrom"];
			}	
		}	
		return($Buy_From_List);
	}
	function get_Month_Name($Month_Idx){
		$Month_Arr = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		return($Month_Arr[$Month_Idx-1]);
	}
	function get_Tax_Date($Contract_ID,$doer_Stamp){
		$Sql_Cmd = Create_SQL_Comand_Tax_Date($Contract_ID,$doer_Stamp); 
		$Result = pg_query($Sql_Cmd);
		$Tax_Date = "";	
		while($Data = pg_fetch_array($Result)){
			if($Tax_Date != ""){
				$Tax_Date = $Tax_Date.' , '.$Data[0];
			}else{
				$Tax_Date = $Data[0];
			}	
		}	
		
		return($Tax_Date);   		
	}
	function get_text_condition_pay($Contract_Id,$Down_Payment){
		// สร้าง ข้อความ สำหรับ คอลัมน์ เงื่อนไข เพิ่มเติมค่าใช้จ่าย 
		// $Contract_Id เลขที่สัญญา
		// $Down_Payment รับค่า เงินดาวน์
		 
		$Txt_Ret =""; // เงินดาวน์
		if(!(is_null($Down_Payment))){
			$Txt_Ret = "ดาวน์  ".number_format($Down_Payment,2,".",",");//' ดาวน์   '.Display_number_float($Down_Payment);
		} 
		$Str_Money_Pledge = get_text_money_pledge($Contract_Id); // เงินมัดจำ
		if(($Txt_Ret!="")and($Str_Money_Pledge!="")){
			$Mid_Str = " , ";
		}else{
			$Mid_Str = " ";
		}
		$Txt_Ret = $Txt_Ret.$Mid_Str.$Str_Money_Pledge; 
		
		return($Txt_Ret);				
	}
	function get_text_money_pledge($ContractID){
		// สร้างข้อความ สำหรับเงินมัดจำ 
		$Str_Qry = Create_SQL_Comand_Money_Pledge($ContractID);
		$Result = pg_query($Str_Qry);
		$Num_Row = pg_num_rows($Result);
		$Str = "";
		for($i=0;$i<$Num_Row;$i++){
			$Data = pg_fetch_array($Result);
			$Str = $Data['tpDesc'].' '.number_format($Data['typePayAmt'],'2',".",","); 
		}
		return($Str);
	}
	function Head_Table($Month,$Year){
		?>
		<tr style="font-size:11px;font-weight: bold;">
        	<td colspan="19" align="center" bgcolor="#9CF"><?php echo "รายงานสรุปสัญญาประจำเดือน ".get_Month_Name($Month)." ".$Year; ?></td>
    	</tr>
    	<tr style="font-size:11px;font-weight: bold;"> 
    		<td rowspan="2" align= "center" bgcolor="#FC0" width="50">วันที่ทำสัญญา</td>
    		<td rowspan="2" align= "center" bgcolor="#FC0" width="100">เลขที่สัญญา</td>
    		<td rowspan="2" align= "center" bgcolor="#FF9" width="150">ชื่อลูกค้า</td>
        	<td rowspan="2" align="center"  bgcolor="#FF9" width="150">เจ้าของเคส</td>
        	<td rowspan="2" align="center"  bgcolor="#FF9" width="150">คนทำสัญญา</td>
        	<td rowspan="2" align="center"  bgcolor="#FF9" width="150">คนตรวจ 1</td>
	        <td rowspan="2" align="center"  bgcolor="#FF9" width="150">คนตรวจ  2</td>
    	    <td rowspan="2" align="center" bgcolor="#CCF" width="250">ซื้อทรัพย์สินมาจาก</td>
        	<td rowspan="2" align="center" bgcolor="#FF9" width="110">เงื่อนไขเพิ่มเติม(ค่าใช้จ่าย)<BR>รวม VAT</td>
	        <td colspan="2" align="center" bgcolor= "#0F0">ยอดผ่อนต่องวด</td>
    	    <td rowspan="2" bgcolor="#FF9" align="center"  width = "50">จำนวน<BR>งวด </td>
        	<td rowspan="2" bgcolor="#FF9" align="center" width = "50">ดอกเบี้ย<BR>ต่อปี(%)</td>
	        <td rowspan="2" bgcolor="#C9F" align="center" width = "95">ราคาทรัพย์สิน<BR>ก่อน VAT</td>
    	    <td rowspan="2" bgcolor="#FF8080" align="center" width = "95">ราคาทรัพย์สิน<BR>รวม VAT</td>
        	<td rowspan="2" bgcolor="#C9F" align="center" width = "95">ยอดจัด <BR>ก่อน VAT</td>
	        <td rowspan="2" bgcolor="#FF8080" align="center" width = "95">ยอดจัด<BR>รวม VAT </td>
    	    <td rowspan="2" bgcolor="#0F0" align="center" width = "70">วันที่ใน<BR>ใบกำกับภาษี</td>
        	<td bgcolor="#FF9" rowspan="2" width = "50" align="center">สถานะ</td>
       	</tr>
    	<tr style="font-size:11px;font-weight: bold;">
        	<td bgcolor="#C9F" width="70" align="center">ก่อน VAT</td>
        	<td bgcolor="#FF8080" width="70" align="center">รวม VAT</td>
        </tr>
		
		<?php
	}
	function Show_Blank_Record(){
		?>	
		<TR bgcolor="#FFFFFF">
			<TD bgcolor="#F2F5A9" colspan="19" align="center">
				<-- ไม่พบข้อมูล -->		
			</TD>
			
		</TR>
		
		<?php
	}
	function Show_Contract_Detail($Month,$Year,$Contract_Type){
		$Sql_Cmd = Create_SQL_Cmd($Contract_Type,$Month,$Year);
		//echo "##########<BR>".$Sql_Cmd."<BR>$$$$$$$$$$";
		$Result_Show = pg_query($Sql_Cmd);
		$Num_Row = pg_num_rows($Result_Show);
		// echo "No. Of Row Is ".$Num_Row."<BR>"; 
		if($Num_Row!=0){
			$i=1; $Color1 = '#00FFFF'; $Color2 = '#00FF99';  
			$Sum_Arr = array(0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00);
			for($i=0;$i<$Num_Row;$i++){
				$Data = pg_fetch_array($Result_Show);
				if(($i%2)==0){
					$Row_Color = $Color1;
				}else{
					$Row_Color = $Color2;
				}
				if($Data['ApprovedText'] == 'รออนุมัติ'){
					$Row_Color = '#F4FA58';
				}
				$Add = Show_Each_Record($Data,$Row_Color); 
				$Sum_Arr = Add_2_Array($Sum_Arr,$Add);
			}
			Show_Summary_Record($Sum_Arr);
		}else{
			Show_Blank_Record();
		}
	}
	function Show_Each_Record($Data_Set,$Row_Color){ 
		$Buy_List = get_Asset_Buy_From($Data_Set['contractID'],$Data_Set['doerStamp']);
		// echo ">>>".$Buy_List."<<<";
		$Tax_Date = get_Tax_Date($Data_Set['contractID'],$Data_Set['doerStamp']);
		?>
			<TR bgcolor="<?php echo $Row_Color; ?>" style="font-size:11px;" >
				<TD><?php echo $Data_Set['conDate']; ?></TD><!-- วันที่ทำสัญญา -->
				<TD>
					<u>
						<a onclick="javascript:popU('../thcap_installments/frm_Index.php?idno=<?php echo $Data_Set['contractID']; ?>'
													,''
						 	 						,'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" 
									 				style="cursor:pointer;"> 
							<font color="blue">	
								<?php 
									echo	$Data_Set['contractID']; 
								?>
							<font>
						</a>
					</u>		 
					
				</TD><!-- เลขที่สัญญา -->
				<TD><?php echo $Data_Set['CusName']; ?></TD><!--ชื่อลูกค้า -->
				<TD><?php echo $Data_Set['case_owners_name']; ?></TD><!-- เจ้าของเคส -->
				<TD><?php echo $Data_Set['doerName']; ?></TD><!-- คนทำสัญญา -->
				<TD><?php echo $Data_Set['examiner1']; ?></TD><!-- คนตรวจ 1 -->
				<TD><?php echo $Data_Set['examiner2']; ?></TD><!-- คนตรวจ 2 -->
				<TD><?php echo $Buy_List;?></TD><!-- ซื้อสินทรัพย์มาจาก -->
				<TD align="right">
					<?php   
						$Txt_Cnd_Pay = get_text_condition_pay($Data_Set['contractID'],$Data_Set['conDown']);
						echo $Txt_Cnd_Pay;
					?></TD><!-- เงินดาวน์รวม VAT เงินมัดจำ -->
				<TD align="right"><?php Display_number_float($Data_Set['conMinPayOutVat']); ?></TD><!-- ยอดผ่อนต่องวด ก่อน VAT-->
				<TD align="right"><?php Display_number_float($Data_Set['conMinPay']); ?></TD><!-- ยอดผ่อนต่องวดรวม VAT -->
				<TD align="center"><?php Display_number_int($Data_Set['conTerm']); ?></TD><!--จำนวนงวด -->
				<TD align="right"><?php Display_number_float($Data_Set['conLoanIniRate']); ?></TD><!-- ดอกเบี้ยต่อปี(%) -->
				<TD align="right"><?php Display_number_float($Data_Set['sumAssetAmtOutVat']); ?></TD><!-- ราคาสินทรัพย์ ก่อนรวม VAT -->
				<TD align="right"><?php Display_number_float($Data_Set['sumAssetAmt']); ?></TD><!-- ราคาสินทรัพย์ รวม VAT -->
				<TD align="right"><?php Display_number_float($Data_Set['conAmtOutVat']); ?></TD><!-- ยอดจัด ก่อน VAT -->
				<TD align="right"><?php Display_number_float($Data_Set['conAmt']); ?></TD><!-- ยอดจัด รวม  VAT -->
				<TD align="center" ><?php echo $Tax_Date; ?></TD><!-- วันที่ในใบกำกับภาษี -->
				<TD align="center"><?php echo $Data_Set['ApprovedText']; ?></TD><!-- ข้อความการอนุมัติ -->
			</TR>
		
		<?php
		// Prepare Value For Compute Summation From Each Row
		$Add = array(8);
		$Add[0] =  $Data_Set['conMinPayOutVat']; // รับค่า  ยอดผ่อนต่องวด ก่อน VAT
		$Add[1] =  $Data_Set['conMinPay']; // ยอดผ่อนต่องวดรวม VAT
		$Add[2] =  $Data_Set['conTerm']; // จำนวนงวด 
		$Add[3] =  $Data_Set['conLoanIniRate']; // ดอกเบี้ยต่อปี
		$Add[4] =  $Data_Set['sumAssetAmtOutVat']; // ราคาสินทรัพย์  ก่อนรวม VAT
		$Add[5] =  $Data_Set['sumAssetAmt']; // ราคาสินทรัพย์ รวม VAT
		$Add[6] =  $Data_Set['conAmtOutVat']; // ยอกจัดก่อน VAT
		$Add[7] =  $Data_Set['conAmt']; // ยอดจัดรวม VAT
		return($Add);
		
		
	} 
	function Show_Summary_Record($Data_In){
		?>
		<TR bgcolor="#FC0" style="font-size:11px;font-weight: bold;" >
			<TD colspan="9" align="right">
				รวม	
			</TD>
			<TD align="right"><?php Display_number_float($Data_In[0]);  ?></TD>
			<TD align="right"><?php Display_number_float($Data_In[1]);  ?></TD>
			<TD align="center"><?php Display_number_int($Data_In[2]); ;  ?></TD>
			<TD align="right"><?php Display_number_float($Data_In[3]);  ?></TD>
			<TD align="right"><?php Display_number_float($Data_In[4]);  ?></TD>
			<TD align="right"><?php Display_number_float($Data_In[5]);  ?></TD>
			<TD align="right"><?php Display_number_float($Data_In[6]);  ?></TD>
			<TD align="right"><?php Display_number_float($Data_In[7]);  ?></TD>
			<TD colspan="3">
				
			</TD>
			
		</TR>
		
		<?php
	}
	function Start_Contract($Contract_Type){
		?>
		<tr bgcolor="#FFFFFF">
        	<td colspan="19" align="LEFT"><?php echo "ประเภทสัญญา ".$Contract_Type." :"; ?></td>
    	</tr>
		
		<?php
	}
?>
<script>
	function popU(U,N,T) {
    	newWindow = window.open(U, N, T);
	}
</script>