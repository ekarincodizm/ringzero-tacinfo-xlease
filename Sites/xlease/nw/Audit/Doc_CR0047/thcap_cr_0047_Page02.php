<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<TITLE>ใบสรุปการโทรตรวจสอบเครดิตลูกค้า และเงื่อนไขของสินเชื่อ</TITLE>
	</HEAD>
	<body>
		<table width="85%" align="center" ><!-- Start Table No. 1 -->
			<TR><!-- Start Row No. 1 -->
				<TH align="left" width = "50%" style="font-size:20px;font-weight:100;">
					<!-- บริษัท ไทยเอซ แคปปิ ตอล จำกัด -->
				</TH>
				<TH align="right" width = "50%" style="font-size:20px;font-weight:100;">
					ฝ่ายสินเชื่อ
				</TH>
				
			</TR><!-- End Row No. 1 -->
			<TR>
				<TD colspan="2" align="right" style="font-size:11px;font-weight:100;">
					THCAP-FORM-CR-0047
				</TD>
			</TR>
			<TR>
			<TD colspan="2" align="center" style="font-size:14px;font-weight:normal;">
			 	_______________________________________________________________________________________________________________________________________________________________________
			</TD>
			</TR>	
			
		</table><!-- End Table No. 1 -->
		
		<div width="85%"style="font-size: 14px;" align="left" >
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			<u><b>ข้อมูลผู้ที่ถูกตรวจสอบ จากกิจการที่ทำงานด้วย (กรณีเป็น พนักงานประจำ)</b></u>
				(โทรตรวจสอบกับฝ่ายบุคคล หรือ ฝ่ำยบัญชี ถ้าไม่มี)
			<BR><BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
			ชื่อผู้ให้ข้อมูล<input type="text" Name = "Checked_Give_Name" ID = "Checked_Give_Name" />
				ตำแหน่ง<input type="text" Name = "Checked_Give_Range" ID = "Checked_Give_Range" />
				เบอร์ที่โทรเข้า<input type="text" Name = "Checked_Give_Tel" />
			<BR>	
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" Name = "Checked_Choice" ID = "Chk_Choice_1" 
			onclick="Clear_CR0047_Employee_End_Date_Value()" />
			&nbsp;ติดต่อมากกว่า 2 รอบ โดยทิ้งช่วงมากกว่า 4 ชั่วโมงจากการติดต่อครั้งแรก ไม่สามารถติดต่อได้
			<BR>	
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" Name = "Checked_Choice" ID = "Chk_Choice_2" 
			onclick="Clear_CR0047_Employee_End_Date_Value()" />
			&nbsp;ติดต่อแล้ว แต่พนักงานที่เรียนสายด้วยไม่ทราบ และไม่สามารถโอนสายไปให้ผู้อื่นที่ทราบและสามารถให้ข้อมูลได้
			<BR>
				
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" Name = "Checked_Choice" ID = "Chk_Choice_3" />
			&nbsp;ติดต่อแล้ว พบว่าผู้ที่ถูกตรวจสอบ พ้นสภาพจากการเป็นพนักงานไปแล้ว ตั้งแต่วันที่
			<input type="text" Name = "Emp_End_Date" ID = "Emp_End_Date" />
			<br>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" Name = "Checked_Choice" ID = "Chk_Choice_4" 
			 onclick="Clear_CR0047_Employee_End_Date_Value()" />
			 &nbsp;ติดต่อแล้ว พบว่๋าผู้ที่ถูกตรวจสอบ ไม่มีชื่อเป็นพนักงาน ในบริษัทดังกล่าว
			<BR><BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<U>ข้อมูลของผู้ที่ถูกตรวจสอบ ที่สอบถามได้กับผู้ให้ข้อมูล</U>(กรณีที่ไม่ได้รับข้อมูลจะต้องเข้าเงื่อนไขใดเงื่อนไขหนึ่งข้างต้น)
			<BR>
						
		</div>
		
		<fieldset style="font-size: 14px;">
			<legend>ข้อมูลของผู้ที่ถูกตรวจสอบ</legend>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			<u>ข้อมูลการทำงาน</u>
				ตำแหน่งงาน <input type="text" Name = 'Txt_Check_Range' ID = 'Txt_Check_Range' />
				งานที่รับผิดชอบ<input type="text" Name = 'Txt_Check_Function' ID = 'Txt_Check_Function' />
			<BR>
				
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			<U>อายุงาน</U>&nbsp;
				<input type="radio" name="Job_1" ID = "Long_Less_Hlf_Yrs" 
				onclick="Disable_Element_And_Clear_Value('Txt_Long_Define')"/>ไม่เกิน 6 เดือน &nbsp;
				<input type="radio" name="Job_1" ID = "Long_Less_One_Yrs"
				onclick="Disable_Element_And_Clear_Value('Txt_Long_Define')"/>6 เดือน ถึง ไม่เกิน 1 ปี &nbsp;
				<input type="radio"	name="Job_1" ID = "Long_Less_Two_Yrs"
				onclick="Disable_Element_And_Clear_Value('Txt_Long_Define')"/> 1 ปี ถึง ไม่เกิน 2 ปี &nbsp;
				<input type="radio" name="Job_1" ID = "Long_Define" 
				onclick="Enable_Element('Txt_Long_Define')" />
				<input type="text"	name="Txt_Long_Define" ID = "Txt_Long_Define" 
				 onkeypress="check_num(event);" />ปี
			<BR>	
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 
			<U>รายได้เงินเดือนไม่รวมค่าคอมมิชชั่น ค่าล่วงเวลาและโบนัส</U>
			<input type="text" name = "Txt_Checked_Salary" ID = "Txt_Checked_Salary" 
			 onkeypress="check_num(event);"/>บาท/เดือน 
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			<u>ข้อมูลกิจการ</u>
			เปิดมาแล้ว
			<input type="text" size="10" name = "Txt_Checked_Business_Long" ID = "Txt_Checked_Business_Long" 
			 onkeypress="check_num(event);"	/>ปึ &nbsp;
			มีจำนวนพนักงานประมาณ 
			<input type="text" size="10" name = "Txt_Checked_Num_Employee" ID = "Txt_Checked_Num_Employee"
			 onkeypress="check_num(event);"	/>คน
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;	
			<U>ผู้ถูกตรวจสอบมีผลการปฎิบัติงานอย่างไร</U>
				<input type="text" size="50" name = "Txt_Job_Result" ID = "Txt_Job_Result" />
		</fieldset>
		
		<BR>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
		<b></b><u>ผลสรุปการโทรศัพท์ตรวจสอบ</u></b><br>
		
		<fieldset style="font-size: 14px;">
			<legend>ผลสรุปการโทรศัพท์ตรวจสอบ</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ลงชื่อ ................................................ ผู้โทรศัพท์ตรวจสอบ  
				วันที่ตรวจสอบ <input type="text" />เวลา<input type="text" />
			<br>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ข้าพเจ้าได้ทำการโทรตรวจสอบตามนโยบายที่บริษัทกำหนด ครบถ้วนทั้งหมด โดยข้าพเจ้าตรวจสอบแล้ว
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;	
			กรณีที่ผู้ถูกตรวจสอบเป็นพนักงำนประจำ ตรวจสอบกับข้อมูลจากกิจการที่ผู้ถูกตรวจสอบทำอยู่ 
				<input name="Check_1" type="radio" value="" /> สอดคล้อง &nbsp;
				<input name="Check_1" type="radio" value="" /> ไม่สอดคล้อง
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ข้ำพเจ้า
				<input name="Check_2" type="radio" value=""/>ไม่มีความเห็นเพิ่มเติม
				<input name="Check_2" type="radio" value=""/>ข้าพเจ้ามีความเห็นเพิ่มเติมที่ไม่สามารถระบุได้ตามแบบฟอร์มดังนี้
			<BR>
				
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;	
			<textarea rows="4" cols="70"></textarea>
			<BR><BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ลงชื่อ.........................................ผู้วิเคราะห์สินเชื่อ 
				วันที่ตรวจสอบ <input type="text" />เวลา <input type="text" />
			<BR>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;		
			ข้าพเจ้าได้ทำการตรวจทานรายงานฉบัยนี้ด้วยความระมัดระวัง และเป็นไปตามนโยบายที่บริษัทกำหนด ครบถ้วนทั้งหมด
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			<u>ข้อมูลที่ผู้โทรศัพท์ตรวจสอบ ตรวจสอบได้ กับข้อมูลที่ผู้ที่ถูกตรวจสอบ ยื่นกับบริษัท และจากการตรวจสอบกับเอกสารรายได้</u>
			<br>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			<U>รายการเดินบัญชีธนาคาร ข้ำพเจ้าพบว่าข้อมูลทั้งหมด</U>
				<input name="Check_3" type="radio" value=""/>สอดคล้อง  &nbsp;
				<input name="Check_3" type="radio" value=""/>ไม่สอดคล้อง
			<br>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ข้าพเจ้า 
				<input name="Check_4" type="radio" value="" />ไม่มีความเห็นเพิ่มเติม 
				<input name="Check_4" type="radio" value="" />ข้ำพเจ้ามีความเห็นเพิ่มเติมที่ไม่สามารถระบุได้ตามแบบฟอร์มดังนี้
			<BR>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			<textarea rows="4" cols="70"></textarea>
			
		</fieldset>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ลงชื่อ ...................................... ผู้ตรวจสอบภายใน 
				วันที่ตรวจสอบ <input type="text"/>
				เวลา<input type="text"/>
			<br>	
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			ข้าพเจ้าตรวจสอบ บันทึกเสียงการสนทนาของผู้โทรศัพท์ตรวจสอบ พบว่าข้อมูลในเอกสำรฉบับนี้ 
				<input name="Check_5" type="radio" value="" />สอดคล้อง 
				<input name="Check_5" type="radio" value="" />ไม่สอดคล้อง
			<BR>		
	</body>
</HTML>