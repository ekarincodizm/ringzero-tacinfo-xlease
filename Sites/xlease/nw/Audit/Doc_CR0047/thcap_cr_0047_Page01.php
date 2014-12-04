<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<TITLE>ใบสรุปการโทรตรวจสอบเครดิตลูกค้า และเงื่อนไขของสินเชื่อ</TITLE>
	</HEAD>
	<body>
		<table width="100%"><!-- Start Table No. 1 -->
			<TR><!-- Start Row No. 1 -->
				<TH align="left" width = "50%" style="font-size:20px;font-weight:100;">
					<!-- บริษัท ไทยเอซ แคปปิ ตอล จำกัด -->
				</TH>
				<TH align="right" width = "50%" style="font-size:20px;font-weight:100;">
					ฝ่ายสินเชื่อ
				</TH>
				
			</TR><!-- End Row No. 1 -->
			<TR><!-- Start Row No. 2 -->
				<TD colspan="2" align="right" style="font-size:11px;font-weight:100;">
					THCAP-FORM-CR-0047
				</TD>
			</TR><!-- End Row No. 2 -->
			<TR><!-- Start Row No. 3 -->
				<TD colspan="2" align="center" style="font-size:22px;font-weight:bold;">
					
				</TD>
			</TR><!-- End Row No. 3 -->
			<TR><!-- Start Row No. 4 -->
			<TD colspan="2" align="center" style="font-size:14px;font-weight:normal;">
			 	_______________________________________________________________________________________________________________________________________________________________________
			</TD>
			</TR><!-- End Row No. 4 -->	
			
		</table><!-- End Table No. 1 -->
		<div width="95%"style="font-size: 14px;" align="left" >
			หมายเหตุ : เรียนลูกค้า / ผู้ให้ข้อมูลว่า บริษัทจำเป็นที่จะต้องโทรตรวจสอบเพื่อประโยชน์ในการพิจารณาให้สินเชื่อ กับ ผู้ขอสินเชื่อ / ผู้กู้ร่วม-ผู้ค้ำประกัน<BR><BR>
				
			<B><U>ให้กากบาทลงในช่องที่ถูกต้อง และตรงกับข้อมูลที่ได้รับทุกช่อง</U></B><BR>
			เพื่ออนุมัติสินเชื่อ ลูกค้าชื่อ
				<input type="text" size = 50 ReadOnly 
					value = "<?php
								if(pg_escape_string($_POST["Purpose"]) == "Input")
								{
									$Cus_Name_Show = pg_escape_string($_POST["Dealer_Name"]);
									echo $Cus_Name_Show; 	
								}
							 ?>"/>
				เกี่ยวข้องเป็น<BR>
				<input type = "radio" Name = "Concern" ID = "Borrower"  onclick="Disable_CR0047_Other_Concern()">ผู้ขอสินเชื่อ
				<input type = "radio" Name = "Concern" ID = "Co-Borrower" onclick="Disable_CR0047_Other_Concern()">ผู้กู้ร่วม – ผู้ค้ำประกัน
				<input type	= "radio" Name = "Concern" ID = "Other_Concern" onclick="Enable_CR0047_Other_Concern()">อื่นๆ คือ
				<input type="TEXT" Name = "Other_Conern" ID =  "Other_Conern" size="100" readonly="true" />
			<BR><BR>
			
			<b><u>ข้อมูลผู้ที่ถูกตรวจสอบ (โทรตรวจสอบจากที่พัก หรือโทรศัพท์มือถือ)</u></b>
				เบอร์ที่โทรเข้าที่พัก/โทรศัพท์มือถือ<input type="TEXT" size="30" />
			<BR>	
			
			&nbsp;&nbsp;&nbsp;
			<u>ผู้ให้ข้อมูล </u>
				<input type="radio" Name = "Give_Data" Id = "Give_Self"onclick="Disable_CR0047_Text_Give_By()" />ตัวผู้ที่ถูกตรวจสอบเอง
				<input type="radio" Name = "Give_Data" Id = "Give_RealName" onclick="Enalbe_CR0047_Text_Give_By()"/>ชื่อจริง
				<input type="text" name="Give_RealName" id = "Give_RealName"  />สัมพันธุ์โดย
				<input type="text" name="Give_Relation" id = "Give_Relation" />
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			<input type="radio" Name = "Give_Data" ID ="Give_2Time_Contact" onclick="Disable_CR0047_Text_Give_By()" /> ได้ทำการติดต่อมากกว่า 2 รอบ โดยทิ้งช่วงมำกกว่า 4 ชั่วโมงจากการติดต่อครั้งแรก ไม่สามารถติดต่อได้
			<BR>
			
			&nbsp;&nbsp;&nbsp;
			<u>ปัจจุบันพักอาศัยที่</u>
			<input type="text" Name ="Txt_Address" ID = "Txt_Address" size="80" />
			<BR>
			
			&nbsp;&nbsp;&nbsp;
			<u>ที่พักปัจจุบันในเอกสาร</u>
				<input name = "Ad_0" id = "Ad_Same" type="radio" >ตรงกัน
				<input name = "Ad_0" id = "Ad_Diff" type="radio"/>ไม่ตรงกัน 
				|<u>สถานะกรรมสิทธิ์</u>
				<input name = "Ad_Owner" id = "Is_Owner" type="radio" onclick="Disable_CR0047_Text_House_Rent()" />เจ้าของ
				<input name = "Ad_Owner" id = "Rent" 	type="radio" onclick="Enalbe_CR0047_Text_House_Rent()" />เช่าเดือนละ
				<input type="text" Name = "House_Rent" id = "House_Rent" onkeypress="check_num(event);" />บาท 
			<BR>
			
			&nbsp;&nbsp;&nbsp;
			<U>ที่พักอาศัยอื่น</U>
				<input name = "Ad_1" type="radio" Name = "Address" ID = "Have_Address"  Onclick = "Enable_CR0047_txt_Address()" />
				มี อยู่ที่ <input type="text" Name = "Txt_Address" ID = "Txt_Address" size="60" readonly="true" />
				<input name = "Ad_1" type="radio" Name = "Address" ID = "No_Address" onclick="Disable_CR0047_txt_Address()" /> ไม่มี 	
				<input name = "Ad_1" type="radio" Name = "Address" ID = "UnDisclosed_Address" onclick="Disable_CR0047_txt_Address()" /> ไม่เปิดเผย
			<BR>
				
			&nbsp;&nbsp;&nbsp;
			<U>พักอาศัยที่ที่พักปัจจุบันมาแล้ว</U>
				<input name ="Ad_Long" type="radio" ID = "A_Half_Yrs" onclick="Disable_Element_And_Clear_Value('Long_Live')" />ภายใน 6 เดือน
				<input name ="Ad_Long" type="radio" ID = "A_One_Yrs" onclick="Disable_Element_And_Clear_Value('Long_Live')"/>6 เดือน ถึง ไม่เกิน 1 ปี
				<input name ="Ad_Long" type="radio" ID = "A_Define" onclick="Enable_Element('Long_Live')" />
				<input type="text" size="4" Name = "Long_Live" ID = "Long_Live" onkeypress="check_num(event);" ReadOnly = "true" /> ปี 
			<BR>
			
			&nbsp;&nbsp;&nbsp;	
			<fieldset>
				<legend>ข้อมูลสินเชื่อ</legend>
				<U>สาเหตุที่ขอสินเชื่อ / เช่าทรัพย์สิน</U>
					<input type="text" Name = "Txt_Borrow_Cnd" ID = "Txt_Borrow_Cnd" size="40" />
				<BR>
					
				<U>ท่านได้ขอสินเชื่อกับสถาบันการเงินอื่นจากสาเหตุข้างต้น</U>
					<input name = "Other_Req" ID = "Other_Req_Have"  type = "radio"
						onclick="Enable_Element('Txt_Num_Req');Enable_Element('Txt_Other_Req')"/>
					มี  <input type="text" Name = "Txt_Num_Req" ID = "Txt_Num_Req" readonly="true" onkeypress="check_num(event);" /> ที่
					ได้แก่ <input type="text" Name = "Txt_Other_Req" ID = "Txt_Other_Req" readonly="true" />  
					<input name = "Other_Req" ID = "Other_Req_No" type = "radio"
						onclick="Disable_Element_And_Clear_Value('Txt_Num_Req');Disable_Element_And_Clear_Value('Txt_Other_Req')"/> ไม่มี
					<BR>
				
				<U>ภายใน 1 ปี ที่ผ่านมา มีใช้สินเชื่อนอกระบบหรือหรือไม่</U>
					<input name = "loan_1" id = "loan_1_have" type="radio" 
						onclick="Enable_Element('Num_Outlaw_Loan');Enable_Element('Rate_Outlaw_Loan')" />
					มี จำนวน <input type="text" size="20" name = 'Num_Outlaw_Loan' id = 'Num_Outlaw_Loan' 
							readonly="true" onkeypress="check_num(event);" />
					อัตรา ด.บ<input type = "text" name ='Rate_Outlaw_Loan' id = 'Rate_Outlaw_Loan' 
							readonly="true" onkeypress="check_num(event);" />/
					<input name = "loan_1" id = "loan_1_nohave" type="radio"
						onclick="Disable_Element_And_Clear_Value('Num_Outlaw_Loan');Disable_Element_And_Clear_Value('Rate_Outlaw_Loan');"/>ไม่มี 
				<BR>	
			</fieldset>
			<BR>
				
			&nbsp;&nbsp;&nbsp;
			<U>ข้อมูลด้านอาชีพ</U>
				(เป็นพนักงำนประจำ หรือ ประกอบกิจการส่วนตัว)<BR>
			
			&nbsp;&nbsp;&nbsp;
			<input type="radio" Name = "Job_Type" id = "Job_Employee"
			 onclick="Process_CR0047_Staff_Clck();"/>
			พนักงานประจำ โดยทำงานอยู่ที่
			<input type="text" Name = "Txt_Comp_Name" ID = "Txt_Comp_Name" size="35"/>
			ตำแหน่ง 
			<input type="text" Name = "Txt_Comp_Range" ID = "Txt_Comp_Range"/>
			
			<fieldset id = "Job_Employee">
				<legend>พนักงานประจำ</legend>
				<u>อายุงาน</u>
					<input name ="Job_1" id = "Lower_Half_Yrs" type="radio"/> ไม่เกิน 6 เดือน &nbsp;
					<input name ="Job_1" id = "Lower_One_Yrs"  type="radio"/> 6 เดือน ถึง ไม่เกิน 1 ปี &nbsp;
					<input name ="Job_1" id = "Lower_Two_Yrs"  type="radio"/>1 ปี ถึง ไม่เกิน 2 ปี&nbsp;
					<input name ="Job_1" type="radio" id = "Define_Yrs" />
					<input  type="text" Name = "Txt_Yrs_Define" ID = "Txt_Yr_Define"/> ปี 
				<BR>
				
				<U>ความพึงพอใจในงานปัจจุบัน</U>&nbsp;
					<input name ="Job_2" id = "Job_Not_OK" type="radio"/>ไม่พึงพอใจ/ต้องการเปลี่ยนงานเร็วๆนี้	 &nbsp;
					<input name ="Job_2" id = "Job_OK"type="radio"/> พอใจกับงานที่ทำอยู่ 
				<BR>
				
				<U>รายได้เงินเดือนไม่รวมค่่าคอมมิชชั่น ค่่าล่วงเวลาและโบนัส</U>	
					<input type="text" Name = "Txt_Salary" ID = "Txt_Salary"  /> บาท/เดือน
				<BR>
				
				<U>ข้อมูลกิจการที่ท่านทำงาน</U>&nbsp;
					เปิดมาแล้ว<input type="text" size="5" Name = "Txt_Yr_Long_1"  ID = "Txt_Yr_Long_1"  />ปี 
					มีจำนวนพนักงานประมาณ <input type="text"  Name = "Txt_Num_Employee_1" ID = "Txt_Num_Employee_1" size = "5" />คน
				<BR> 
			</fieldset>
			
			&nbsp;&nbsp;&nbsp;
			<input type="radio" Name = "Job_Type" id = "Job_Business" 
			 onclick="Process_CR0047_Business_Owner()"	/>กิจการส่วนตัว อาชีพคือ
			<input type="text" Name = "Business_Owner_Job" ID = "Business_Owner_Job" size="55"/>
			
			<fieldset id = "Job_Business_Owner">
				<legend>กิจการส่วนตัว</legend>
				<u>ข้อมูลกิจการ</u>
					มีพนักงานในกิจการ 
					<input type="text" Name = "Txt_Num_Employee_2" ID = "Txt_Num_Employee_2"
					 onkeypress="check_num(event);"/>คน 
					ค่าใช้จ่ายพนักงานต่อเดือน 
					<input type="text" Name = "Txt_All_Pay_Per_Month" ID = "Txt_All_Pay_Per_Month"
					onkeypress="check_num(event);"/>
					บาท
				<BR> 
				
				<u>อายุกิจการ</u>
					<input name = "Job_3" id = "Lower_Half_Yrs_2" type = "radio"/>ไม่เกิน 6 เดือน&nbsp;
					<input name = "Job_3" id = "Lower_One_Yrs_2" type="radio" />6 เดือน ถึง ไม่เกิน 1 ปี&nbsp;
					<input name = "Job_3" id = "Lower_Two_Yrs_2" type="radio"  />	1 ปี ถึง ไม่เกิน 2 ปี 
					<input name = "Job_3" id = "Txt_Yrs_Define" type="radio" />
					<input type="text" name = "Txt_Yrs_Input" id = "Txt_Yrs_Input" 
					 onkeypress="check_num(event);"/> ปี
				<BR>
				
				<u>รายได้ต่อเดือนก่อนหักค่าใช้จ่าย</u>
					<input type = "text" Name = "Txt_Month_Income" ID = "Txt_Month_Income">บาท &nbsp;
				<u>หลังหักค่าใช้จ่ำย</u>
					<input type="text" Name = "Txt_Month_NetIncome" ID = "Txt_Month_NetIncome"/> บาท
				<BR>
					
				<u>สำนักงานกับที่พัก</u>
					<input name = "Job_5" id = "Same" type="radio"/>ที่เดียวกัน &nbsp;
					<input name = "Job_5" id = "Different" type="radio"/> คนละที่ อยู่ที่  
					<input type="text" Name = "Txt_Address_2" ID = "Txt_Address_2" size="50" />
				<BR>
				
				<u>สำนักงาน</u>	
					<input name="Job_4" id = "Hire_Office" type="radio"/>
					เช่า เดือนละ<input  type="text" Name = "Month_Rental" ID = "Month_Rental" />&nbsp;
					<input name="Job_4" id = "Office_Owner_NoPay"type="radio"/>เป็นเจ้าของเองปลอดภาระ<BR>
					<input name="Job_4" id = "Office_Owner_Pay"type="radio"/>เป็นเจ้าของเองติดภาระ 
						มียอดคงเหลือประมาณ <input type="text" Name = "Balance_Value" ID = "Balance_Value" /> บาท 
						ผ่อนเดือน <input type="text" Name = "Install_Ment" ID = "Install_Ment"/>บาท
				<BR>
				
				<U>สัดส่วนระหว่างลูกค้าประจำ กับลูกค้าจร</U>&nbsp;(100%) 
					ลูกค้าประจำ<input type="text" Name = "Cust_Regular" ID = "Cust_Regular" />% 
					ลูกค้าจร <input type="text" Name = "Cust_Jorn" ID = "Cust_Jorn"/>%
				<BR>
				
				หากเสียลูกค้าประจำไปกิจการจะมีผลกระทบ หรือไม่ อย่ำงไร
					<input type="text" Name = "Txt_Lost_Effect" ID = "Txt_Lost_Effect" />
				<br>
				
				(กรณีที่ลูกค้าทำขนส่ง หรือโรงงาน)<u>จำนวนรถบรรทุกหรือเครื่องจักรที่มีมูลค่าประมาณเดียวกันมี</u>
					<input type="text" Name = "Txt_Num_Car" ID = "Txt_Num_Car" />คัน	
					
						
			</fieldset>		
		</div>
	</body>
</HTML>