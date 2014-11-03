
	<TABLE border="0"  bordercolor="#FF0"  width="98%" align = "center" ><!-- Start Table No. 1 -->
		<TR bordercolor="#FF0"><!-- Start Row:02 -->
			<TD  align="left" style="font-size:10px;font-weight:normal;">
				THCAP-FORM-CR-0046 Rev.002 20140816 
			</TD>	
		</TR><!-- End Row:02 -->
		<TR bordercolor="#FF0">
			<TD align="left" style="font-size:10px;font-weight:bold;">
				ประเภทสัญญา  
				<input NAME =  "Type_Contract_Select_Input" id = "Type_Contract_Select_Input" type = "text" value = "<?php echo $Contract_Type; ?>" readonly= true> 
				เลขที่่สัญญา <input type = "text" Name = "Contract_ID" id = "Contract_ID" value = "<?php echo $Contract_ID; ?>" readonly= true >
				<?php
					if(pg_escape_string($_POST["Purpose"]) == "Input")
					{
						//nothing	
					}else{
						echo "ตรวจสอบครั้งที่  <input type = \"text\" Name = \"Check_Time\" ID = \"Check_Time\"  readonly = true >";
					}
				?>
			</TD>
			
		</TR>
	</TABLE><!-- End Table No. 1 -->
	<TABLE border="1" width="98%" align = "center" style="font-size: 14px;"><!-- Start Table No. 2 -->
		<TR><!-- Start Row No.1 -->
			<TD width = "60%">
				A ใบส่งงาน-รับงานของเจ้าหน้าที่ตรวจสอบ(checker)/เจ้าหน้าที่สินเชื่อ
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%" >
							<input type="radio" Name = "Check_A" id="Check_A_1"  value="Have_Complete#1"  /> 
							มีสมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" Name = "Check_A" id="Check_A_2"  value="Have_NoComplete#-1" /> 
							มีแต่ไม่สมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" Name = "Check_A" id="Check_A_3"  value="No_Have#0" /> 
							ไม่มี
						
						</TD>	 
						
					</TR>
					
				</TABLE>
				
			</TD>
		</TR>
		<TR><!-- Start Row No.2 -->
			<TD width = "60%">
				B รายละเอียดพร้อมทั้งเอกสารรับกลับตามใบสั่งงาน
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%">
							<input type="radio" Name = "Check_B" id = "Check_B_1" value="Full_Complete#1" /> 
							ครบสมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" Name = "Check_B" id = "Check_B_2" value="Full_UnComplete#-1" /> 
							ครบแต่ไม่สมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" Name = "Check_B" id = "Check_B_3" value="Not_Full#0" /> 
							ไม่ครบ
						
						</TD>	 
						
					</TR>
					
				</TABLE>	
				
			</TD>
		</TR>
		<TR><!-- Start Row No.3 -->
			<TD>
				C การลงลายมือชื่อของเจ้าหน้าที่ Checker / เจ้าหน้าที่สินเชื่อ ที่รับ - ส่งงาน
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%">
							<input type="radio" name="Check_C"  id = "Check_C_1" value="Complete#1" /> 
							ครบถ้วน
							
						</TD>
						<TD width = "66%">
							<input type="radio" name="Check_C"  id = "Check_C_2" value="No_Complete#0" /> 
							ไม่ครบถ้วน
							
						</TD>
							 
						
					</TR>
					
				</TABLE>	
				
				
			</TD>
		</TR>
		<TR><!-- Start Row No.4 -->
			<TD>
				D หนังสือรับเงิน และ หนังสือสัญญาซื้อขาย / ใบเสร็จรับเงิน และ ใบกำกับภาษีค่าสินค้า
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%">
							<input type="radio" name = "Check_D" id = "Check_D_1" value="Have_Complete#1" /> 
							มีสมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name = "Check_D"  id = "Check_D_2" value="Have_NoComplete#-1" /> 
							มีแต่ไม่สมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name = "Check_D"  id = "Check_D_3" value="No_Have#0" /> 
							ไม่มี
						
						</TD>	 
						
					</TR>
					
				</TABLE>
				
			</TD>
		</TR>
		<TR><!-- Start Row No.5 -->
			<TD>
				E หนังสือรับรองเช่าซื้อ <B>(เฉพาะทรัพย์สินที่เป็นยานพาหนะ)</B>
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%">
							<input type="radio" name = "Check_E"  id = "Check_E_1" value="Have_Complete#1" /> 
							มีสมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name = "Check_E"  id = "Check_E_2"  value="Have_NoComplete#-1" /> 
							มีแต่ไม่สมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name = "Check_E"  id = "Check_E_3" value="No_Have#0" /> 
							ไม่มี
						
						</TD>	 
						
					</TR>
					
				</TABLE>
				
			</TD>
		</TR>
		<TR><!-- Start Row No.6 -->
			<TD>
				F หนังสือคำเตือนสำหรับผู้ค้ำประกัน<B>(เฉพาะทรัพย์สินที่เป็นรถจักรยานยนต์ กับรถยนต์)</B>
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%">
							<input type="radio"  name="Check_F" id = "Check_F_1"  value="Have_Complete#1" /> 
							มีสมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name="Check_F"  id = "Check_F_2" value="Have_NoComplete#-1" /> 
							มีแต่ไม่สมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name="Check_F"  id = "Check_F_3" value="No_Have#0" /> 
							ไม่มี
						
						</TD>	 
						
					</TR>
					
				</TABLE>
			</TD>
		</TR>
		<TR><!-- Start Row No.7 -->
			<TD>
				G หนังสือข้อตกลงการรับประกันการซื้อคืนทรัพย์สินจากผู้จำหน่ายสินค้า<B>(อ้างอิงใบอนุมัติ/ข้อตกลงกับผู้จัดจำหน่าย)</B>
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px;">
					<TR>
						<TD width = "33%">
							<input type="radio" name = "Check_G"  id = "Check_G_1" value="Have_Complete#1" /> 
							มีสมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name = "Check_G"  id = "Check_G_2" value="Have_NoComplete#-1" /> 
							มีแต่ไม่สมบูรณ์
							
						</TD>
						<TD width = "33%">
							<input type="radio" name = "Check_G"  id = "Check_G_3" value="No_Have#0" /> 
							ไม่มี
						
						</TD>	 
						
					</TR>
					
				</TABLE>
				
			</TD>
		</TR>
	</TABLE><!-- End Table No. 2 -->
	<TABLE width = "98%" align = "center" style="font-size: 14px;"><!-- Start Table No. 3 -->
		<TR><!-- Start Row No. 1 -->
			<TD>
				<B>ไม่สมบูรณ์ หมายถึง กรณีที่ส่วนซึ่งเป็นสาระสำคัญของข้อมูลต้องการทราบไม่มี หรือไม่เพียงพอเพื่อใช้ตรวจสอบข้อมูลเบื้องต้นของลูกค้า</B>
			</TD>	
			
		</TR><!-- End Row No. 1 -->
		<TR><!-- Start Row No. 2 -->
			<TD>
				หมายเหตุ <BR>
				<TEXTAREA cols=178 rows = 2  Name ="Note_P1-0" ID = "Note_P1-0" /></TEXTAREA>
			</TD>
			
		</TR><!-- End Row No. 2 -->
		<TR><!-- Start Row No. 3 -->
			<TD>
				<u>คำอธิบายสำหรับการตรวจสอบความเรียบร้อยของสัญญาหลัก และ สัญญาค้ำประกัน</u>
			</TD>
		</TR><!-- End Row No. 3 -->
		<TR><!-- Start Row No. 4 -->
			<TD>
				1 การลงลายมือชื่อ ลูกค้าต้องลงลายมือชื่อให้ครบทุกหน้าของสัญญา เพื่อแสดงว่าลูกค้าได้รับทราบเงื่อนไขในเอกสารครบถ้วน
			</TD>			
		</TR><!-- End Row No. 4 -->
		<TR><!-- Start Row No. 5 -->
			<TD>
				2 การลงลายมือชื่อ ลูกค้าต้องลงลายมือฃื่อให้ครบถ้วนทุกคนที่เกี่ยวข้องในสัญญาไม่ว่าจะเป็นสัญญาหลัก หรือ สัญญาค้ำประกัน <u>โดยเฉพาะลูกค้านิติบุคคล จะต้องมีการลงลายมือชื่อโดย</u>
			</TD>	
		</TR><!-- End Row No. 5 -->
		<TR><!-- Start Row No. 6 -->
			<TD>
				<u>กรรมการผู้มีอำนาจกระทำการและมีจำนวน หรือรายละเอียดตรงตามหนังสือรับรอง(การลงลายมือชื่อสำหรับนิติบุคคลหมายความรวมถึงว่าจะต้องประทับตรานิติบุคคลด้วย)</u>
			</TD>
		</TR><!-- End Row No. 6 -->
		<TR><!-- Start Row No. 7 -->
			<TD>
				3 การลงลายมือชื่อจะต้องมีลักษณะเหมือนกันในทุกเอกสาร โดยเฉพาะเอกสารที่เคยลงนามไว้ในเอกสารราชการ เช่น สัญญาเช่าซื้อ บัตรประชาชน หนังสือทะเบียนรถยนต์
			</TD>	
		</TR><!-- End Row No. 7 -->
		<TR><!-- Start Row No. 4 -->
			<TD>
				<span style="background-color:#00FFFF">
					กรุณาเลือก กรณีลูกค้าที่ต้องการ
					<input type="radio" name="Customer_Type"  id = "Cust_Personal" value="Personal#0" onclick="radio_chk_personal()" />
						บุคคลธรรมดา
				 	<input type="radio" name="Customer_Type"  id = "Cust_Person" value="Person#1" onclick="radio_chk_person()" />
			 			นิติบุคคล
			 	</span>	
			</TD> 
		</TR><!-- End Row No. 4 -->
	</TABLE><!-- End Table No. 3 -->
	
	<TABLE border="1" width = "98%" align="center" style="font-size: 14px" ><!-- Start Table No. 4 -->
		<TR><!-- Start Row No. 1 -->
			<TD  align="center" width="50%" style="font-size: 16px" >ตรวจสอบความเรียบร้อยของสัญญาหลัก</TD>
			<TD  align="center" width="50%" style="font-size: 16px" >ตรวจสอบความเรียบร้อยของสัญญาค้ำประกัน</TD>
			
		</TR><!-- End Row No. 1 -->
		<TR><!-- Start Row No. 2 -->
			<TD>
				<TABLE width="100%" style="font-size: 14px"><!-- Start Sub Table On Left Side -->
					<TR><!-- Start Row No.1 -->
						<TD colspan="2">
							<u>กรณีลูกค้าบุคคลธรรมดา</u>
						</TD>
					</TR><!-- End Row No.1 -->	
					<TR><!-- Start Row No.2 -->
						<TD colspan="2">
							H การลงลายมือชื่อ	
						</TD>
					</TR><!-- End Row No.2 -->	
					<TR><!-- Start Row No. 3 -->
						<TD width = "50%">
							&nbsp;&nbsp;
							<input type="radio" name="Check_H"  id = "Check_H_1" value="Complete#1" /> ครบถ้วนทุกหน้าของสัญญา
						</TD>
						<TD width = "50%">
							<input type="radio" name="Check_H"  id = "Check_H_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 3 -->
					<TR><!-- Start Row No. 4 -->
						<TD colspan="2">
							I จำนวนลายมือชื่อที่ลงในสัญญาครบถ้วนทุกคนตามสัญญา	
						</TD>
					</TR><!-- End Row No. 4 -->
					<TR><!-- Start Row No. 5 -->
						<TD>
							&nbsp;&nbsp;<input type="radio" name = "Check_I" id = "Check_I_1"  value="Complete#1" />ครบถ้วนทุกคน
						</TD>
						<TD>
							<input type="radio" name = "Check_I"  id = "Check_I_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข	
						</TD>
					</TR><!-- End Row No. 5 -->
					<TR><!-- Start Row No. 6 -->
						<TD colspan="2">
							J ลงลายมือชื่อในเอกสารครบทุกชุดตามที่บริษัทได้จัดทำขึ้น
							
						</TD>
					</TR><!-- End Row No. 6 -->
					<TR><!-- Start Row No. 7 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name = "Check_J" id = "Check_J_1"  value="Complete#1" />ครบถ้วนทุกชุด	
						</TD>
						<TD width="50%">
							<input type="radio" name = "Check_J" id = "Check_J_2"  value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
						
					</TR><!-- End Row No. 7 -->
					<TR><!-- Start Row No. 8 -->
						<TD colspan="2">
							K การลงลายมือชื่อ ใช้ลายมือตรงกันทุกหน้าของสัญญา และตรงกับบัตรประชาชน / ทะเบียนรถยนต์/ทะเบียนเครื่องจักร
						</TD>
					</TR><!-- End Row No. 8 -->
					<TR><!-- Start Row No. 9 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_K" id = "Check_K_1" value="Conform#1" />สอดคล้องทุกหน้าของสัญญา	
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_K" id = "Check_K_2" value="Not_Conform#0" />ยังไม่สอดคล้องต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 9 -->
					<TR><!-- Start Row No. 10 -->
						<TD colspan="2">
							<u>กรณีลูกค้านิติบุคคล</u>
						</TD>
					</TR><!-- End Row No. 10 -->
					<TR><!-- Start Row No. 11 -->
						<TD colspan="2">
							L การลงลายมือฃื่อประทับตรา
						</TD>
					</TR><!-- End Row No. 11 -->
					<TR><!-- Start Row No. 12 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_L"  id = "Check_L_1" value="Complete#1" />ครบถ้วนทุกหน้าของสัญญา	
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_L" id = "Check_L_2"  value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 12 -->
					<TR><!-- Start Row No. 13 -->
						<TD colspan="2">
							M จำนวนลายมือชื่อที่ลงในสัญญาครบถ้วนทุกคนตามสัญญา
						</TD>
					</TR><!-- End Row No. 13 -->
					<TR><!-- Start Row No. 14 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_M" id = "Check_M_1" value="Complete#1" />ครบถ้วนทุกคน
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_M" id = "Check_M_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 14 -->
					<TR><!-- Start Row No. 15 -->
						<TD colspan="2">
							N ลงลายมือชื่อในเอกสารครบทุกชุดตามที่บริษัทได้จัดทำขึ้น
						</TD>
					</TR><!-- End Row No. 15 -->
					<TR><!-- Start Row No. 16 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_N" id = "Check_N_1" value="Complete#1" />ครบถ้วนทุกชุด
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_N" id = "Check_N_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 16 -->
					<TR><!-- Start Row No. 16 -->
						<TD colspan="2">
							O การลงลายมือชื่อใช้ลายมือและตราประทับตรงกันทุกหน้าของสัญญาเทียบกับหนังสือรับรอง /ทะเบียนรถยนต์ /ทะเบียนเครื่องจักร
						</TD>
					</TR><!-- End Row No. 16 -->
					<TR><!-- Start Row No. 17 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_O" id = "Check_O_1" value="Conform#1" />สอดคล้องทุกหน้าของสัญญา
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_O" id = "Check_O_2" value="Not_Conform#0" />ยังไม่สอดคล้องต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 17 -->
				</TABLE><!-- End Sub Table On Left Side -->
				
			</TD>
			<TD>
				<TABLE width="100%" style="font-size: 14px"><!-- Start Sub Table On Right Side -->
					<TR><!-- Start Row No. 1 -->
						<TD colspan="2">
							<u>กรณีลูกค้าบุคคลธรรมดา</u>
						</TD>
					</TR><!-- End Row No.1 -->
					<TR><!-- Start Row No.2 -->
						<TD colspan="2">
							P การลงลายมือชื่อ
						</TD>
					</TR><!-- End Row No.2 -->	
					<TR><!-- Start Row No. 3 -->
						<TD width = "50%">
							&nbsp;&nbsp;<input type="radio" name="Check_P" id = "Check_P_1" value="Complete#1" /> ครบถ้วนทุกหน้าของสัญญา
						</TD>
						<TD width = "50%">
							<input type="radio" name="Check_P" id = "Check_P_2" value="Not_Complete#0" /> ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 3 -->
					<TR><!-- Start Row No. 4 -->
						<TD colspan="2">
							Q จำนวนลายมือชื่อที่ลงในสัญญาครบถ้วนทุกคนตามสัญญา		
						</TD>
					</TR><!-- End Row No. 4 -->
					<TR><!-- Start Row No. 5 -->
						<TD>
							&nbsp;&nbsp;<input type="radio" name="Check_Q" id = "Check_Q_1" value="Complete#1" />ครบถ้วนทุกคน
						</TD>
						<TD>
							<input type="radio" name="Check_Q" id = "Check_Q_2" value="Not_Complete#0" /> ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 5 -->
					<TR><!-- Start Row No. 6 -->
						<TD colspan="2">
							R ลงลายมือชื่อในเอกสารครบทุกชุดตามที่บริษัทได้จัดทำขึ้น
						</TD>
					</TR><!-- End Row No. 6 -->
					<TR><!-- Start Row No. 7 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_R"  id = "Check_R_1" value="Complete#1" />ครบถ้วนทุกชุด	
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_R"  id = "Check_R_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
						
					</TR><!-- End Row No. 7 -->
					<TR><!-- Start Row No. 8 -->
						<TD colspan="2">
							S การลงลายมือชื่อ ใช้ลายมือตรงกันทุกหน้าของสัญญา และตรงกับบัตรประชาชน / ทะเบียนรถยนต์/ทะเบียนเครื่องจักร
						</TD>
					</TR><!-- End Row No. 8 -->
					<TR><!-- Start Row No. 9 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_S" id = "Check_S_1" value="Conform#1" />สอดคล้องทุกหน้าของสัญญา	
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_S" id = "Check_S_2" value="Not_Conform#0" />ยังไม่สอดคล้องต้องแก้ไข
						</TD>
						
					</TR><!-- End Row No. 9 -->
					<TR><!-- Start Row No. 10 -->
						<TD colspan="2">
							<u>กรณีลูกค้านิติบุคคล</u>
						</TD>
					</TR><!-- End Row No. 10 -->
					<TR><!-- Start Row No. 11 -->
						<TD colspan="2">
						  T	 การลงลายมือฃื่อประทับตรา
						</TD>
					</TR><!-- End Row No. 11 -->
					<TR><!-- Start Row No. 12 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_T"  id = "Check_T_1" value="Complete#1" />ครบถ้วนทุกหน้าของสัญญา	
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_T"  id = "Check_T_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 12 -->
					<TR><!-- Start Row No. 13 -->
						<TD colspan="2">
							U จำนวนลายมือชื่อที่ลงในสัญญาครบถ้วนทุกคนตามสัญญา
						</TD>
					</TR><!-- End Row No. 13 -->
					<TR><!-- Start Row No. 14 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_U" id = "Check_U_1" value="Complete#1" />ครบถ้วนทุกคน
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_U" id = "Check_U_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 14 -->
					<TR><!-- Start Row No. 15 -->
						<TD colspan="2">
							V ลงสายมือชื่อในเอกสารครบทุกชุดตามที่บริษัทได้จัดทำขึ้น
						</TD>
					</TR><!-- End Row No. 15 -->
					<TR><!-- Start Row No. 16 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_V" id = "Check_V_1" value="Complete#1" />ครบถ้วนทุกชุด
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_V"  id = "Check_V_2" value="Not_Complete#0" />ยังไม่ครบถ้วนต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 16 -->
					<TR><!-- Start Row No. 16 -->
						<TD colspan="2">
							W การลงลายมือชื่อใช้ลายมือและตราประทับตรงกันทุกหน้าของสัญญาเทียบกับหนังสือรับรอง /ทะเบียนรถยนต์ /ทะเบียนเครื่องจักร
						</TD>
					</TR><!-- End Row No. 16 -->
					<TR><!-- Start Row No. 17 -->
						<TD width="50%">
							&nbsp;&nbsp;<input type="radio" name="Check_W"  id = "Check_W_1" value="Conform#1" />สอดคล้องทุกหน้าของสัญญา
						</TD>
						<TD width="50%">
							<input type="radio" name="Check_W" id = "Check_W_2" value="Not_Conform#0" />ยังไม่สอดคล้องต้องแก้ไข
						</TD>
					</TR><!-- End Row No. 17 -->
					
				</TABLE><!-- End Sub Table On Right Side -->
			</TD>	
		</TR><!-- End Row No. 2 -->
		
    </TABLE><!-- End Table No. 4-->
    <TABLE width="98%" align="center" style="font-size: 14px" ><!-- Start Table No. 5 -->
    	<TR><!-- Start Row No. 1 -->
    		<TD>
    			หมายเหตุุ<BR>
    			<TEXTAREA cols=178 rows = 2  Name ="Note_P1-1" ID = "Note_P1-1" /></TEXTAREA>
    		</TD>
    	</TR><!-- End Row No. 1 -->
    	<TR><!-- Start Row No. 2 -->
    		<TD>
    			<u>คำอธิบายสำหรับการตรวจสอบเรื่องภาพถ่ายทรัพย์สิน</u>
    		</TD>
    	</TR><!-- End Row No. 2 -->
    	<TR><!-- Start Row No. 3 -->
    		<TD>
    			การตรวจสอบภาพถ่ายกับข้อมูลเพื่อเป็นการยืนยันความถูกต้องว่าข้อมูลในสัญญารวมทั้งเอกสารอื่น ๆ นั้น ต้องตรงตามความเป็นจริงกับที่เจ้าหน้าที่ได้ไปตรวจสอบ ซึ่งประกอบด้วย 
    		</TD>
    	</TR><!-- End Row No. 3 -->
    	<TR><!-- Start Row No. 4 -->
    		<TD>
    			1.รายละเอียดของทรัพย์สิน ไม่ว่าจะะเป็นประเภท ยี่ห้อ รุ่น รหัส หมายเลข ซึ่งภาพถ่ายและเอกสารประกอบจะต้องตรงกัน 
    		</TD>
    	</TR><!-- End Row No. 4 -->
    	<TR><!-- Start Row No. 5 -->
    		<TD>
    			2.รายละเอียดของสถานที่ตั้งทรัพย์สินในสัญญา ใบส่งของ/ใบเสร็จรับเงิน/ใบกำกับภาษี  จะต้องตรงกับสถานที่จริง ซึ่งตรวจสอบได้จากภาพถ่ายที่่เจ้าหน้าที่ออกไปตรวจสอบยังสถานที่จริง 
    		</TD>
    	</TR><!-- End Row No. 5 -->
    	
    </TABLE><!-- End Table No. 5 -->
    <TABLE border="1" width="98%" align="center" style="font-size: 14px" ><!-- Start Table No. 6 -->
    	<TR><!-- Start Row No. 1 -->
    		<TH colspan="2" align="center" width="100%">
    			ภาพถ่ายทรัพย์สินทางปัญญา
    		</TH>
    		
    	</TR><!-- End Row No. 1 -->
    	<TR><!-- Start Row No. 2 -->
    		<TD width="50%">
    			x ภาพถ่ายเปรียบเทียบกับ ใบส่งของ/ใบเสร็จรับเงิน/ใบกำกับภาษี
    		</TD>
    		<TD width="50%">
    			<TABLE border="1" width="100%">
    				<TR>
    					<TD width="50%">
    						<input type="radio" name ="Check_X" id = "Check_X_1" value="Identical#1" />ข้อมูลตรงกัน	
    					</TD>
    					<TD width="50%">
    						<input type="radio" name ="Check_X" id = "Check_X_2"  value="Not_Identical#0" />ข้อมูลไม่ตรงกัน
    					</TD>
    				</TR>
    			</TABLE>
    		</TD>
    	</TR><!-- End Row No. 2-->
    	<TR><!-- Start Row No. 3 -->
    		<TD width="50%">
    			Y ภาพถ่ายเปรียบเทียบกับ รายละเอียดทรัพย์สินในสัญญา
    		</TD>
    		<TD width="50%">
    			<TABLE border="1" width="100%">
    				<TR>
    					<TD width="50%">
    						<input type="radio" name="Check_Y" id = "Check_Y_1" value="Identical#1" />ข้อมูลตรงกัน	
    					</TD>
    					<TD width="50%">
    						<input type="radio" name="Check_Y" id = "Check_Y_2" value="Not_Identical#0" />ข้อมูลไม่ตรงกัน
    					</TD>
    				</TR>
    			</TABLE>
    		</TD>
    	</TR><!-- End Row No. 3-->
    	
    </TABLE><!-- End Table No. 6 -->
	
