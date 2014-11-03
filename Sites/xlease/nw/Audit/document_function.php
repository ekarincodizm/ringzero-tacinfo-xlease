<?php ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
	function Create_Parameter_For_Save_To_cr0046()
	{  // สร้าง ค่า Parameter สำหรับการบันทึกลงฐานข้อมูล
		$Approve_In = explode('#',pg_escape_string($_POST['Check_Status']));// ผลการตรวจสอบเอกสาร
		$Arrove_ID = explode('#',pg_escape_string($_POST['Checker']));
		$Doc_No = Get_Document_No('CR0046'); // Gen เลขที่เอกสาร
		$Time_Stamp = Get_Current_Date_And_Time(); // ดึงวันเวลาปัจจุบัน จาก Server
		$Time_Check = Get_Approve_Time(pg_escape_string($_POST['Contract_ID']));
		$Legend_In = explode('#',pg_escape_string($_POST['Legend']));
		$Check_A = explode('#',pg_escape_string($_POST['Check_A']));
		$Check_B = explode('#',pg_escape_string($_POST['Check_B']));
		$Check_C = explode('#',pg_escape_string($_POST['Check_C']));
		$Check_D = explode('#',pg_escape_string($_POST['Check_D']));
		$Check_E = explode('#',pg_escape_string($_POST['Check_E']));
		$Check_F = explode('#',pg_escape_string($_POST['Check_F']));
		$Check_D = explode('#',pg_escape_string($_POST['Check_D']));
		$Check_G = explode('#',pg_escape_string($_POST['Check_G']));
		$Check_H = explode('#',pg_escape_string($_POST['Check_H']));
		$Check_I = explode('#',pg_escape_string($_POST['Check_I']));
		$Check_J = explode('#',pg_escape_string($_POST['Check_J']));
		$Check_K = explode('#',pg_escape_string($_POST['Check_K']));
		$Check_L = explode('#',pg_escape_string($_POST['Check_L']));
		$Check_M = explode('#',pg_escape_string($_POST['Check_M']));
		$Check_N = explode('#',pg_escape_string($_POST['Check_N']));
		$Check_O = explode('#',pg_escape_string($_POST['Check_O']));
		$Check_P = explode('#',pg_escape_string($_POST['Check_P']));
		$Check_Q = explode('#',pg_escape_string($_POST['Check_Q']));
		$Check_R = explode('#',pg_escape_string($_POST['Check_R']));
		$Check_S = explode('#',pg_escape_string($_POST['Check_S']));
		$Check_T = explode('#',pg_escape_string($_POST['Check_T']));
		$Check_U = explode('#',pg_escape_string($_POST['Check_U']));
		$Check_V = explode('#',pg_escape_string($_POST['Check_V']));
		$Check_W = explode('#',pg_escape_string($_POST['Check_W']));
		$Check_X = explode('#',pg_escape_string($_POST['Check_X']));
		$Check_Y = explode('#',pg_escape_string($_POST['Check_Y']));
		$Check_Z = explode('#',pg_escape_string($_POST['Check_Z']));
		$Check_AA = explode('#',pg_escape_string($_POST['Check_AA'])); 
		$Check_AB = explode('#',pg_escape_string($_POST['Check_AB']));
		$Check_AC = explode('#',pg_escape_string($_POST['Check_AC']));
		$Check_AD = explode('#',pg_escape_string($_POST['Check_AD']));
		$Check_AE = explode('#',pg_escape_string($_POST['Check_AE']));
		$Check_AF = explode('#',pg_escape_string($_POST['Check_AF']));
		$Check_AI = explode('#',pg_escape_string($_POST['Check_AI']));
		$Check_AJ = explode('#',pg_escape_string($_POST['Check_AJ']));
		$Check_AK = explode('#',pg_escape_string($_POST['Check_AK']));
		$Check_AL = explode('#',pg_escape_string($_POST['Check_AL']));
		$Check_AM = explode('#',pg_escape_string($_POST['Check_AM']));
		$Check_AN = explode('#',pg_escape_string($_POST['Check_AN']));
		$Parameter = array(
							"ID"			=> $Doc_No,
							"conType" 		=> pg_escape_string($_POST['Type_Contract_Select_Input']), // ประเภทสัญญา
							"contractID" 	=> pg_escape_string($_POST['Contract_ID']),// เลขที่สัญญา
							"appvTimes"		=> $Time_Check,
							"appvNote" 		=> pg_escape_string($_POST['Checker_Note']), // บันทึกเพิ่มเติมผู้ตรวจสอบ
							"appvID"		=> $Arrove_ID[0], // รหัสผู้ตรวจสอบ ดึงจากผู้ที่ Login เข้าระบบเพื่อใช้งาน
							"appvStamp"		=> $Time_Stamp, // วันเวลาที่ใช้ในการตรวจสอบข้อมูล	
							"Approved" 		=> $Approve_In[1], // ผลการตรวจสอบ 1 = ผ่าน  0 = ไม่ผ่าน
							"LegendID"		=> $Legend_In[0], // รหัสพนักงาน ของ ทนายความผู้ให้คำรับรอง 	
							"Check_A" 		=> $Check_A[1], // ผลการตรวจสอบจากข้อ A							
							"Check_B"		=> $Check_B[1], // ผลการตรวจสอบจากข้อ B
							"Check_C"		=> $Check_C[1], // ผลการตรวจสอบจากข้อ C
							"Check_D"		=> $Check_D[1], // ผลการตรวจสอบจากข้อ D
							"Check_E"		=> $Check_E[1], // ผลการตรวจสอบจากข้อ E
							"Check_F"		=> $Check_F[1], // ผลการตรวจสอบจากข้อ F
							"Check_G"		=> $Check_G[1], // ผลการตรวจสอบจากข้อ G
							"CheckNote1"	=> pg_escape_string($_POST['Note_P1-0']), // หมายเหตุ หลังข้อ G	ในหน้า 1
							"Check_H"		=> $Check_H[1], // ผลการตรวจสอบจากข้อ H
							"Check_I"		=> $Check_I[1], // ผลการตรวจสอบจากข้อ I
							"Check_J"		=> $Check_J[1], // ผลการตรวจสอบจากข้อ J
							"Check_K"		=> $Check_K[1], // ผลการตรวจสอบจากข้อ K
							"Check_L"		=> $Check_L[1], // ผลการตรวจสอบจากข้อ L
							"Check_M"		=> $Check_M[1], // ผลการตรวจสอบจากข้อ M
							"Check_N"		=> $Check_N[1], // ผลการตรวจสอบจากข้อ N
							"Check_O"		=> $Check_O[1], // ผลการตรวจสอบจากข้อ O
							"Check_P"		=> $Check_P[1], // ผลการตรวจสอบจากข้อ P
							"Check_Q"		=> $Check_Q[1], // ผลการตรวจสอบจากข้อ Q
							"Check_R"		=> $Check_R[1], // ผลการตรวจสอบจากข้อ R
							"Check_S"		=> $Check_S[1], // ผลการตรวจสอบจากข้อ S
							"Check_T"		=> $Check_T[1], // ผลการตรวจสอบจากข้อ T
							"Check_U"		=> $Check_U[1], // ผลการตรวจสอบจากข้อ U
							"Check_V"		=> $Check_V[1], // ผลการตรวจสอบจากข้อ V
							"Check_W"		=> $Check_W[1], // ผลการตรวจสอบจากข้อ W
							"Check_X"		=> $Check_X[1], // ผลการตรวจสอบจากข้อ X
							"Check_Y"		=> $Check_Y[1], // ผลการตรวจสอบจากข้อ Y
							"Check_Z"		=> $Check_Z[1], // ผลการตรวจสอบจากข้อ Z
							"Check_AA"		=> $Check_AA[1], // ผลการตรวจสอบจากข้อ @
							"Check_AB"		=> $Check_AB[1], // ผลการตรวจสอบจากข้อ #
							"Check_AC"		=> $Check_AC[1], // ผลการตรวจสอบจากข้อ $
							"Check_AD"		=> $Check_AD[1], // ผลการตรวจสอบจากข้อ %
							"Check_AE"		=> $Check_AE[1], // ผลการตรวจสอบจากข้อ ก
							"Check_AF"		=> $Check_AF[1], // ผลการตรวจสอบจากข้อ ข
							"Check_AI"		=> $Check_AI[1], // ผลการตรวจสอบจากข้อ ค
							"Check_AJ"		=> $Check_AJ[1], // ผลการตรวจสอบจากข้อ ง
							"Check_AK"		=> $Check_AK[1], // ผลการตรวจสอบจากข้อ จ
							"Check_AL"		=> $Check_AL[1], // ผลการตรวจสอบจากข้อ ฉ
							"Check_AM"		=> $Check_AM[1], // ผลการตรวจสอบจากข้อ ช
							"CheckNote2"	=> pg_escape_string($_POST['Note_P1-1']), // หมายเหตุ หลังข้อ W	ในหน้า 1
							"Check_AN"		=> $Check_AN[1], // ผลการตรวจสอบก่อนข้อ ค
							"CheckNote3"	=> pg_escape_string($_POST['Note_P2-0']) // หมายเหตุ หลังข้อ ข ในหน้า 2
		);// End Of array 
		return($Parameter);	
	}// End function Create_Parameter_For_Save_To_cr0076
	function Create_SQL_Comand_For_Insert_To_cr0046($Parameter)
	{  // สร้าง SQL Comand สำหรับบันทึกลงฐานช้อมูล 
		$Sql_Cmd =	"
						INSERT INTO 
									\"thcap_audit_check_cr0046\"(
            														\"ID\",  /* เลขที่เอกสาร  */ 
            														\"conType\", /* ประเภทสัญญา */
            														\"contractID\", /* เลจที่สัญญา */
            														\"appvTimes\", /* ครั้งที่ ตรวจสอบสัญญา */ 
            														\"appvNote\", /* บันทึกเพิ่มเติมจากผู้ตรวจสอบ หรือ หมายเหตุการตรวจสอบ */
            														\"appvID\", /* รหัสผู้ตรวจสอบ ดึงจากการ LogIn เข้าระบบ */
            														\"appvStamp\", /* วันเวลาที่ใช้ในการตรวจสอบ */
            														\"Approved\", /* ผลการตรวจสอบเอกสาร 1 =ผ่าน 0 = ไม่ผ่าน  */
            														\"LawerID\", /* รหัสประจำตัวทนายผู้ให้คำรับรอง */
            														\"CheckA\", /* ผลการตรวจสอบเอกสารในข้อ A 1 = มีสมบูรณ์ -1 = มีแต่ไม่สมบูรณ์  0 = ไม่มี */ 
            														\"CheckB\", /* ผลการตรวจสอบเอกสารในข้อ B 1 = ครบสมบูรณ์ -1= ครบแต่ไม่สมบูรณ์ 0 = ไม่ครบ*/
            														\"CheckC\", /* ผลการตรวจสอบเอกสารในข้อ C 1 = ครบถ้วน  0 = ไม่ครบถ้วน */ 
            														\"CheckD\", /* ผลการตรวจสอบเอกสารในข้อ D 1=มีสมบูรณ์  -1=มีแต่ไม่สมบูรณ์   0=ไม่มี*/
            														\"CheckE\", /* ผลการตรวจสอบเอกสารในข้อ E 1=มีสมบูรณ์  -1=มีแต่ไม่สมบูรณ์   0=ไม่มี */
            														\"CheckF\", /* ผลการตรวจสอบเอกสารในข้อ F 1=มีสมบูรณ์  -1=มีแต่ไม่สมบูรณ์   0=ไม่มี*/
            														\"CheckG\", /* ผลการตรวจสอบเอกสารในข้อ G 1=มีสมบูรณ์  -1=มีแต่ไม่สมบูรณ์   0=ไม่มี*/
            														\"CheckNote1\", /* หมายเหตุ หลังข้อ G	ในหน้า 1 */
            														\"CheckH\", /* ผลการตรวจสอบในข้อ H 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckI\", /* ผลการตรวจสอบในข้อ I 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckJ\", /* ผลการตรวจสอบในข้อ J 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckK\", /* ผลการตรวจสอบในข้อ K 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckL\", /* ผลการตรวจสอบในข้อ L 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckM\", /* ผลการตรวจสอบในข้อ M 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckN\", /* ผลการตรวจสอบในข้อ N 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckO\", /* ผลการตรวจสอบในข้อ O 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckP\", /* ผลการตรวจสอบในข้อ P 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckQ\", /* ผลการตรวจสอบในข้อ Q 1=ครบถ้วนทุกคน   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckR\", /* ผลการตรวจสอบในข้อ R 1=ครบถ้วนทุกชุด   0=ยังไม่ครบถ้วนต้องแก้ไข */
            														\"CheckS\", /* ผลการตรวจสอบในข้อ S 1=สอดคล้องทุกหน้าของสัญญา 0 = ยังไม่สอดคล้องต้องแก้ไข */
            														\"CheckT\", /* ผลการตรวจสอบในข้อ T 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข*/
            														\"CheckU\", /* ผลการตรวจสอบในข้อ U 1=ครบถ้วนทุกชุด   0=ยังไม่ครบถ้วนต้องแก้ไข*/
            														\"CheckV\", /* ผลการตรวจสอบในข้อ V 1=ครบถ้วนทุกชุด   0=ยังไม่ครบถ้วนต้องแก้ไข*/
            														\"CheckW\", /* ผลการตรวจสอบในข้อ W 1=สอดคล้องทุกหน้าของสัญญา 0 = ยังไม่สอดคล้องต้องแก้ไข */
            														\"CheckX\",	/* ผลการตรวจสอบในข้อ X 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข*/
            														\"CheckY\", /* ผลการตรวจสอบในข้อ Y 1=ครบถ้วนทุกหน้าของสัญญา   0=ยังไม่ครบถ้วนต้องแก้ไข*/
            														\"CheckZ\", /* ผลการตรวจสอบในข้อ Z 1=มี  0=ไม่มี*/
            														\"CheckAA\",/* ผลการตรวจสอบในข้อ @ 1=ตรงเงื่อนไข  0=ไม่ตรงเงื่อนไข*/
            														\"CheckAB\",/* ผลการตรวจสอบในข้อ # 1= ครบ  0=ไม่ครบ*/ 
            														\"CheckAC\",/* ผลการตรวจสอบในข้อ $ 1=มี  0=ไม่มี*/
            														\"CheckAD\",/* ผลการตรวจสอบในข้อ % 1=มี  0=ไม่มี*/
            														\"CheckAE\",/* ผลการตรวจสอบในข้อ ก   1=มี  0=ไม่มี */
            														\"CheckAF\",/* ผลการตรวจสอบในข้อ ข 	1=ตรงตามวัตถุประสงค์ 0 = ไม่ตรงตามวัตถุประสงค์   */
            														\"CheckAI\",/* ผลการตรวจสอบในข้อ ค  1 = มี 0 = ไม่มี */
            														\"CheckAJ\",/* ผลการตรวจสอบในข้อ ง  1 = ครอบคลุม 0 = ไม่ครอบคลุม*/
            														\"CheckAK\",/* ผลการตรวจสอบในข้อ จ  1 = มี 0 = ไม่มี*/ 
            														\"CheckAL\",/* ผลการตรวจสอบในข้อ ฉ  1 = มี 0 = ไม่มี*/
            														\"CheckAM\",/* ผลการตรวจสอบในข้อ ช  1=ใช้ได้  0=ใช้ไม่ได้*/
            														\"CheckNote2\", /* หมายเหตุ 2 จากหน้า 1 */
            														\"CheckAN\", /* ผลการตรวจสอบการเป็นผู้รับมอบอำนาจของผู้ลงนาม 1 = ใข่ 0 = ไม่ใช่*/
            														\"CheckNote3\" /* หมายเหตุ 3 หน้า 2 */
            													)
    								VALUES (
    											'".$Parameter['ID']."',
    											'".$Parameter['conType']."',
    											'".$Parameter['contractID']."',
    											'".$Parameter['appvTimes']."',
    											'".$Parameter['appvNote']."', 
            									'".$Parameter['appvID']."', 
            									'".$Parameter['appvStamp']."',
            									'".$Parameter['Approved']."',
            									'".$Parameter['LegendID']."',
            									'".$Parameter['Check_A']."',
            									'".$Parameter['Check_B']."',
            									'".$Parameter['Check_C']."',
            									'".$Parameter['Check_D']."',
            									'".$Parameter['Check_E']."',
            									'".$Parameter['Check_F']."',
            									'".$Parameter['Check_G']."',
            									'".$Parameter['CheckNote1']."',
            									'".$Parameter['Check_H']."',
            									'".$Parameter['Check_I']."',
            									'".$Parameter['Check_J']."',
            									'".$Parameter['Check_K']."',
            									'".$Parameter['Check_L']."',
            									'".$Parameter['Check_M']."',
            									'".$Parameter['Check_N']."',
            									'".$Parameter['Check_O']."',
            									'".$Parameter['Check_P']."',
            									'".$Parameter['Check_Q']."',
            									'".$Parameter['Check_R']."',
            									'".$Parameter['Check_S']."',
            									'".$Parameter['Check_T']."',
            									'".$Parameter['Check_U']."',
            									'".$Parameter['Check_V']."',
            									'".$Parameter['Check_W']."',
            									'".$Parameter['Check_X']."',
            									'".$Parameter['Check_Y']."',
            									'".$Parameter['Check_Z']."',
            									'".$Parameter['Check_AA']."',
            									'".$Parameter['Check_AB']."',
            									'".$Parameter['Check_AC']."',
            									'".$Parameter['Check_AD']."',
            									'".$Parameter['Check_AE']."',
            									'".$Parameter['Check_AF']."',
            									'".$Parameter['Check_AI']."',
            									'".$Parameter['Check_AJ']."',
            									'".$Parameter['Check_AK']."',
            									'".$Parameter['Check_AL']."',
            									'".$Parameter['Check_AM']."',
            									'".$Parameter['CheckNote2']."',
            									'".$Parameter['Check_AN']."',
            									'".$Parameter['CheckNote3']."'
           )";
           return($Sql_Cmd);
	}// End function Create_SQL_Comand_For_Insert_To_cr0046
	
	function Create_Sql_Cmd_For_Show_Stye_Short_From_cr0046($Rcd_Need)
	{  // สร้าง Sql Comand สำหรับบันทึกลงตาราง
		$Sql_Cmd = "
						SELECT  
								\"thcap_audit_check_cr0046\".\"ID\" As \"ID\",
								\"thcap_audit_check_cr0046\".\"autoID\" As \"AutoID\",
								\"thcap_audit_check_cr0046\".\"conType\" As \"conType\", 
								\"thcap_audit_check_cr0046\".\"contractID\" AS \"contractID\",
								\"thcap_audit_check_cr0046\".\"appvTimes\" As \"appvTimes\", 
								\"Vfuser\".\"fullname\" As \"fullname\",
								\"thcap_audit_check_cr0046\".\"appvStamp\" As \"appvStamp\",
								\"thcap_audit_check_cr0046\".\"appvNote\" As \"appvNote\",
								\"thcap_audit_check_cr0046\".\"Approved\" As \"Approved\"
						FROM 
								\"thcap_audit_check_cr0046\" 
								INNER JOIN \"Vfuser\" 
													ON 
														(\"thcap_audit_check_cr0046\".\"appvID\" = \"Vfuser\".\"id_user\")
						ORDER By \"AutoID\" DESC
						LIMIT ".$Rcd_Need."	
						
					";
		return($Sql_Cmd);	
	}// End function Create_Sql_Cmd_For_Show_Stye_Short_From_cr0046 
	function Create_Sql_Cmd_For_Show_Style_Full_From_cr0046($Doc_ID_Need)
	{ // สร้าง Sql Comand สำหรับ ดึงข้อมูลจาก ตาราง thcap_audit_check_cr0046
		$Sql_Cmd = "
					SELECT 
							\"ID\", /* เลขที่เอกสาร */
							\"conType\", /*ประเภทสัญญา */
							\"contractID\", /*เลขที่สัญญา  */
							\"appvTimes\", /* ลำดับครั้งการตรวจเอกสาร */
							\"appvNote\", /* บันทึกเพิ่มเติมจากผู้ตรวจสอบ */
       						\"appvID\", /* เลขประจำตัวผู้ตรวจสอบเอกสาร */ 
       						\"appvStamp\", /* เวลาที่ตรวจสอบเอกสาร */ 
       						\"Approved\", /* ผลการตรวจเอกสาร 1=ผ่าน, 0 = ไม่ผ่าน  */ 
       						\"LawerID\", /* เลขประจำตัวทนายผู้ให้คำรับรอง */ 
       						\"CheckA\", /* ผลการตรวจเอกสารในข้อ A */ 
       						\"CheckB\", /* ผลการตรวจเอกสารในข้อ B */   
       						\"CheckC\", /* ผลการตรวจเอกสารในข้อ C */ 
       						\"CheckD\", /* ผลการตรวจเอกสารในข้อ D */
       						\"CheckE\", /* ผลการตรวจเอกสารในข้อ E */ 
       						\"CheckF\", /* ผลการตรวจเอกสารในข้อ F */ 
       						\"CheckG\", /* ผลการตรวจเอกสารในข้อ G */ 
       						\"CheckNote1\",/* หมายเหตุจากหน้า 1 หลังข้อ G */ 
       						\"CheckH\", /* ผลการตรวจเอกสารในข้อ H */
       						\"CheckI\", /* ผลการตรวจเอกสารในข้อ I */ 
       						\"CheckJ\", /* ผลการตรวจเอกสารในข้อ J */ 
       						\"CheckK\", /* ผลการตรวจเอกสารในข้อ K */ 
       						\"CheckL\", /* ผลการตรวจเอกสารในข้อ L */ 
       						\"CheckM\", /* ผลการตรวจเอกสารในข้อ M */
       						\"CheckN\", /* ผลการตรวจเอกสารในข้อ N */
       						\"CheckO\", /* ผลการตรวจเอกสารในข้อ O */
       						\"CheckP\", /* ผลการตรวจเอกสารในข้อ P */
       						\"CheckQ\", /* ผลการตรวจเอกสารในข้อ Q */ 
       						\"CheckR\", /* ผลการตรวจเอกสารในข้อ R */ 
       						\"CheckS\", /* ผลการตรวจเอกสารในข้อ S */ 
       						\"CheckT\", /* ผลการตรวจเอกสารในข้อ T */ 
       						\"CheckU\", /* ผลการตรวจเอกสารในข้อ U */
       						\"CheckV\", /* ผลการตรวจเอกสารในข้อ V */ 
       						\"CheckW\", /* ผลการตรวจเอกสารในข้อ W */ 
       						\"CheckX\", /* ผลการตรวจเอกสารในข้อ X */
       						\"CheckY\", /* ผลการตรวจเอกสารในข้อ Y */ 
       						\"CheckZ\", /* ผลการตรวจเอกสารในข้อ  Z */
       						\"CheckAA\", /* ผลการตรวจเอกสารในข้อ  @ */
       						\"CheckAB\", /* ผลการตรวจเอกสารในข้อ  # */ 
       						\"CheckAC\", /* ผลการตรวจเอกสารในข้อ  $ */
       						\"CheckAD\", /* ผลการตรวจเอกสารในข้อ  % */ 
       						\"CheckAE\", /* ผลการตรวจเอกสารในข้อ   ก */ 
       						\"CheckAF\", /* ผลการตรวจเอกสารในข้อ   ข */ 
       						\"CheckAI\", /* ผลการตรวจเอกสารในข้อ   ค */
       						\"CheckAJ\", /* ผลการตรวจเอกสารในข้อ   ง */ 
       						\"CheckAK\", /* ผลการตรวจเอกสารในข้อ   จ */ 
       						\"CheckAL\", /* ผลการตรวจเอกสารในข้อ   ฉ */
	   						\"CheckAM\", /* ผลการตรวจเอกสารในข้อ   ช */
	   						\"CheckNote2\", 
	   						\"CheckAN\", 
       						\"CheckNote3\"
  					FROM 
  							thcap_audit_check_cr0046
  					WHERE
  							\"ID\" = '$Doc_ID_Need'		
					";
		return($Sql_Cmd);
	}
	function Create_SQL_Comand_For_Show_Notice($ContractID,$Time_Chk)
	{  // สร้าง Sql Comand สำหรับ แสดง หมายเหตุ
		$Sql_Show_Notice = "
							SELECT 
									\"Vfuser\".\"fullname\" As \"fullname\" ,
									\"thcap_audit_check_cr0046\".\"appvStamp\" As \"appvStamp\",
									\"thcap_audit_check_cr0046\".\"appvNote\" As \"appvNote\"
							FROM 
									\"thcap_audit_check_cr0046\" 
										INNER JOIN \"Vfuser\" 
																ON 
																	(\"thcap_audit_check_cr0046\".\"appvID\" = \"Vfuser\".\"id_user\")
							WHERE 
									(\"thcap_audit_check_cr0046\".\"contractID\" = '$ContractID') AND
									(\"thcap_audit_check_cr0046\".\"appvTimes\" = ".$Time_Chk.")";
		
		return($Sql_Show_Notice);
	}// End function Create_SQL_Comand_For_Show_Notice
	function Disable_Element_From_H_To_W()
	{   // ทำให้ Element ในข้อ H to W ไม่สามารถ Click ได้
		?>
			<script>
				document.getElementById("Check_H_1").disabled=true;
				document.getElementById("Check_H_2").disabled=true;
				document.getElementById("Check_I_1").disabled=true;
				document.getElementById("Check_I_2").disabled=true;
				document.getElementById("Check_J_1").disabled=true;
				document.getElementById("Check_J_2").disabled=true;
				document.getElementById("Check_K_1").disabled=true;
				document.getElementById("Check_K_2").disabled=true;
				document.getElementById("Check_L_1").disabled=true;
				document.getElementById("Check_L_2").disabled=true;
				document.getElementById("Check_M_1").disabled=true;
				document.getElementById("Check_M_2").disabled=true;
				document.getElementById("Check_N_1").disabled=true;
				document.getElementById("Check_N_2").disabled=true;
				document.getElementById("Check_O_1").disabled=true;
				document.getElementById("Check_O_2").disabled=true;
				document.getElementById("Check_P_1").disabled=true;
				document.getElementById("Check_P_2").disabled=true;
				document.getElementById("Check_Q_1").disabled=true;
				document.getElementById("Check_Q_2").disabled=true;
				document.getElementById("Check_R_1").disabled=true;
				document.getElementById("Check_R_2").disabled=true;
				document.getElementById("Check_S_1").disabled=true;
				document.getElementById("Check_S_2").disabled=true;
				document.getElementById("Check_T_1").disabled=true;
				document.getElementById("Check_T_2").disabled=true;
				document.getElementById("Check_U_1").disabled=true;
				document.getElementById("Check_U_2").disabled=true;
				document.getElementById("Check_V_1").disabled=true;
				document.getElementById("Check_V_2").disabled=true;
				document.getElementById("Check_W_1").disabled=true;
				document.getElementById("Check_W_2").disabled=true;
			</script>
		<?php
	}
	function Get_Document_No($DocVar)
	{  // สร้างค่า เลขที่ เอกสาร
		$Sql_Get_Doc_No = " 
							SELECT 
									\"thcap_gen_AuditdocumentID\"('".CR0046."')
						  ";
		$Result = pg_query($Sql_Get_Doc_No);
		$Data = pg_fetch_result($Result,0);
		return($Data);
	}
	function Get_Approve_Time($Contract_ID)
	{  // สร้างจำนวนครรั้งที่ ตรวจสอบ เอกสาร ตามเชที่สัญญา ในตัวแปร $Contract_ID ใช้กับตาราง  thcap_audit_check_cr0046 
		$SQL_Max_Chk_Time_Contract = "
										SELECT 
												MAX(\"appvTimes\")
  										FROM 
  											thcap_audit_check_cr0046
  										WHERE 
  											\"contractID\" = '$Contract_ID'	
									 ";
		$Result = pg_query($SQL_Max_Chk_Time_Contract);
		$Data = pg_fetch_array($Result);
		if(empty($Data[0]))
		{
			return 1;
		}else{
			return $Data[0]+1;
		}					
	}
	function Get_Approve_Time_1($Contract_ID)
	{   // สร้างจำนวนครรั้งที่ ตรวจสอบ เอกสาร ตามเชที่สัญญา ในตัวแปร $Contract_ID ใช้กับตาราง  thcap_autdit_docs_main ออกเบบใหม่
		$SQL_Max_Chk_Time_Contract = "
										SELECT 
												MAX(\"AppvTimes\")
  										FROM 
  											thcap_autdit_docs_main
  										WHERE 
  											\"contractID\" = '$Contract_ID'	
									 ";
		$Result = pg_query($SQL_Max_Chk_Time_Contract);
		$Data = pg_fetch_array($Result);
		if(empty($Data[0]))
		{
			return 1;
		}else{
			return $Data[0]+1;
		}					
	}
	function Get_Checker_Name($Run_No)
	{   // ดึงค่าผู้ตรวจสอบเอกสาร 
		$Str_Get = "
	 					SELECT 
	 							\"Value\"
						FROM 
								\"thcap_audit_docs_detail\" 
						WHERE 	
								(\"main_autoID\" = ".$Run_No.") And 
								(\"Element_Name\" = 'Checker')
					";			
		$Result = pg_query($Str_Get);
		$Data = pg_fetch_array($Result);
		$N_Result = explode('#',$Data[0]);
		return($N_Result[1]);
	} 
	
	function Get_Current_Date_And_Time()
	{
		$Str_Get_Current_Date_And_Time = "	
											SELECT 
													\"nowDateTime\"()
										 ";
		$Result = pg_query($Str_Get_Current_Date_And_Time);
		$Data = pg_fetch_result($Result, 0, 0);
		return($Data);								 
	}
	function get_Login_Full_Name_By_Login_ID($Id)
	{  // รับค่า ชื่อเต็ม ของ ผู้ Login เข้าใช้ระบบงาน 
		$Str_Get_Full_Name ="
								SELECT 
										fullname  
								FROM 
										\"Vfuser\" 
								WHERE 
										\"id_user\" = '$Id'  
									
							";
		$Result = pg_query($Str_Get_Full_Name);
		$Data = pg_fetch_result($Result,0);
		return $Data;
		
	}
	function Get_notice_cr0046_to_show($Contract_ID,$Time_Chk)
	{   // ดึงข้อมูล เตรียมแสดงข้อความ หมายเหตุ
		$Sql = Create_SQL_Comand_For_Show_Notice($Contract_ID,$Time_Chk);
		$Result = pg_query($Sql);	
		$Data = pg_fetch_array($Result);
		return($Data);
	}// End Function Get_notice_cr0046_to_show
	function Head_Table_Of_Check_Doc()
	{  // หัวตาราง 
		?>
			<TR bgcolor="#79BCFF">
				<TH align="center">
					รายการที่
				</TH>
				<TH align="center">
					ประเภทสินเชื่อ
				</TH>
				<TH align="center">
					เลขที่สัญญา
				</TH>
				<TH align="center">
					ตรวจสอบครั้งที่
				</TH>
				<TH align="center">
					ผู้ทำรายการตรวจสอบ
				</TH>
				<TH align="center">
					วันเวลาที่ตรวจสอบ
				</TH>
				<TH align="center">
					หมายเหตุ
				</TH>
				<TH align="center">
					ผลการตรวจสอบ
				</TH>
				<TH align ="center">
					เลขที่เอกสาร
				</TH>	
			</TR>
			
		<?php
	}
	
	function Head_Table_Of_Check_Doc_cr_0089()
	{   //หัวตาราง
		?>
			<TR bgcolor="#79BCFF">
				<TH>รายการที่</TH>
				<TH>เลขที่เอกสาร</TH>
				<TH>ชื่อร้าน/ชื่อผู้จัดจำหน่าย</TH>
				<TH>ตรวจสอบครั้งที่</TH>
				<TH>ชื่อผู้ประเมิน</TH>
				<TH>วันเวลาที่ประเมิน</TH>
				<TH>หมายเหตุ</TH>
				<TH>คะแนนที่ได้</TH>
				<TH>Col-9</TH>
			</TR>
		
		<?php
	}
	
	function Input_Contract_ID_From_User($Name)
	{  // Input Type = Text For Use In Form
			?>	
			<input type="text" name="<?php echo $Name; ?>" id = "<?php echo $Name; ?>"  />
		<?php
		
	}
	function Insert_Data_To_thcap_audits_docs_main($Data_In)
	{  // นำเข้าข้อมูลในตาราง
		$Str_Ins = "
						INSERT INTO 
									thcap_audit_docs_main(
															\"Contract_ID\",
															\"Con_Type\", 
															\"AppvTime\"
														  )
									values(
											'".$Data_In["Contract_ID"]."',
											'".$Data_In["Type_Contract_Select_Input"]."',
											".$Data_In["Appv_Time"]."
											)						
					";
		//var_dump($Str_Ins);			
		pg_query($Str_Ins);			
		
	}
	
	function Load_Contract_Type_For_Select($Name)
	{   // แสดงปประเภทสัญญาให้เลือก
		$Str_Get_Contract_Type = "	
									SELECT 
											\"conType\"  
									FROM 
											thcap_contract_type 
									ORDER By
       										\"conType\" ASC	
       							";
		$Result = pg_query($Str_Get_Contract_Type);
		?>
			<select name="<?php echo $Name; ?>" id = "<?php echo $Name; ?>" >
				<option value="-">เลือกประเภทสินเชื่อ</option>
				<?php
					while($Data = pg_fetch_array($Result))
					{
						?>
						<option value="<?php echo $Data['conType']; ?>">
							<?php echo $Data["conType"]; ?>
						</option>
						<?php
					}
		
				?>
			</select>
			<?php								
	}
	
	function Load_Law_Legend_for_select($Html_Name)
	{   // ดึงจ้อมูล ทนายความ มาให้เลือก
		$Str_Get_Law_Legend = "	SELECT 
										\"Vfuser_active\".id_user As id,
										\"Vfuser_active\".fullname as full_name
								FROM 
										\"Vfuser_active\",
										\"f_department\"
								WHERE 	
										(\"Vfuser_active\".user_dep = \"f_department\".\"fdep_id\") 
										and (\"f_department\".\"organizeID\" = 4)
								ORDER By 
										full_name ASC
							 ";
		$Result =  pg_query("$Str_Get_Law_Legend");					 
		
		?>
			<select name = <?php echo $Html_Name; ?> id = <?php echo $Html_Name; ?> >
				<option value="<?php echo "-"; ?>">
					<?php echo " "; ?>
				</option>
				<?php	
					while($Data = pg_fetch_array($Result))
					{
						?>
							 <option value="<?php echo $Data['id'].'#'.$Data['full_name']; ?>">
							 	<?php echo $Data['full_name']; ?>
							 </option>
						<?php
					}
				
				?>
				
			</select>
		
		<?php
	}
	function Insert_To_Table($Sql_Ins) // นำเข้าข้อมูล ในตาราง
	{	echo "<BR><BR>";
		if(pg_query($Sql_Ins))
		{
			echo "ระบบสามารถบันทึกข้อมูลได้";
			?>
			<script>
				alert("ระบบสามารถบันทึกข้อมูลได้");
				window.location = 'thcap_cr_0046_contract_type.php';
			</script>
			<?php
			
		}else{
			echo "ระบบไม่สามารถบันทึกข้อมูลได้";
			?>
			<script>
				alert("ระบบไม่สามารถบันทึกข้อมูลได้ กรุณา กด F5 อีกครั้งเพื่อพยามบันทึกข้อมูล");
				//window.location = 'Docs_Chk_Select.php';
			</script>
			
			<?php
		}
	} 
	function Load_Data_For_Show_Of_cr_0046()
	{
			
	}
	function Row_Table_Of_cr_0046_Old($Rcd_Need)
	{  // แสดงข้อมูลของแถว ของเอกสาร cr_0046
		$Sql_Cmd_Row_Show = Create_Sql_Cmd_For_Show_Stye_Short_From_cr0046($Rcd_Need);	
		$Result = pg_query($Sql_Cmd_Row_Show); $Count = 0; echo '<BR>';
		while($Data = pg_fetch_array($Result))
		{
			$Count++;
		 	if($Data['Approved'] == 0){
				$Result_Chk = "ไม่ผ่าน";
			}elseif($Data['Approved'] == 1){
				$Result_Chk = "ผ่าน";
			}
			
			if($Count%2==0) // กำหนด รูปแบบการแสดงข้อมูลของ แถว ในตาราง
			{
				$Class_Type = "odd";	
			}else
			{
				$Class_Type = "even";
			}
			// แสดงข้อมูลในแต่ละแถว แถวละ 5 Column(รายการที่,ประเภทสัญญา,เลขที่สัญญา,ตรวจสอบครั้งที่,ผู้ทำรายการ,วันเวลาที่ตรวจสอบ,หมายเหตุ,ผลการตรวจสอบ)
			?>
				<TR class="<?php echo $Class_Type; ?>"><!-- Start Row -->
					<TD align="center">
						<?php 
							echo $Count; 
						?>
					</TD><!-- รายการที่ -->
					<TD align="center">
						<?php 
							echo $Data['conType']; 
						?>
					</TD><!-- ประเภทสัญญา -->
					<TD align="left">
						<?php  
							Show_Line_Link_Display_Contract_installments_By_ContractID($Data['contractID']);
						?>
					</TD><!-- เลขที่สัญญา -->
					<TD align="center">
						<?php 
							echo $Data['appvTimes']; 
						?>
					</TD><!-- จำนวนครั้งที่ตรวจสอบ -->
					<TD align="left">
						<?php 
							echo $Data['fullname']; 
						?>
					</TD><!-- ผู้ทำรายการ -->
					<TD align="center">
						<?php 
							echo $Data['appvStamp']; 
						?>
					</TD><!--วันเวลาที่ทดสอบระบบ -->
					<TD align="left">
						<?php 
							// echo $Data['appvNote'];
							Show_Line_Link_Display_Approve_Note($Data); 
						?>
					</TD><!-- หมายเหตุการตรวจสอบ  -->
					<TD align="center">
						<?php 
							echo $Result_Chk; // แสดงผลการตรวจสอบ ผ่าน หรือ ไม่ผ่าน
						?>
					</TD><!-- ผลการตรวจสอบเอกสาร -->
					<TD> 
						<?php  
							show_Image_Link_Display_Docs_Detail_cr0046($Data['ID']); 
						?>
					</TD>
				</TR><!-- End Row -->
				
			<?php

		}			
		
	}// End function Row_Table_Of_cr_0046 
	function Create_String_For_Load_Document($Doc_Type,$Rcd_Need)
	{   // สร้าง Sql Comand สำหรับดึงข้อมูลมาแสดง
		$Str_Fst = "
						SELECT 
								thcap_audit_docs_main.\"Con_Type\",
								thcap_audit_docs_main.\"Contract_ID\",
								thcap_audit_docs_main.\"AppvTime\",
								thcap_audit_docs_main.\"AppvStamp\",
								thcap_audit_docs_main.\"auto_ID\",
								thcap_audit_docs_detail.\"Docs_ID\",
								thcap_audit_docs_detail.\"Element_Name\",
								thcap_audit_docs_detail.\"Value\"
						FROM 
								\"thcap_audit_docs_main\",\"thcap_audit_docs_detail\"
						WHERE 
								(thcap_audit_docs_main.\"auto_ID\" = thcap_audit_docs_detail.\"main_autoID\") and  
      							(thcap_audit_docs_detail.\"Element_Name\" = 'Check_Status') and 
      							(\"Docs_ID\"  Like '".CR0046."%')
      					ORDER By
      							thcap_audit_docs_main.\"auto_ID\"  DESC		
						LIMIT 	".$Rcd_Need."
					";
					
					
		return($Str_Fst);	
	}
	 
	
	function Row_Table_Of_cr_0046($Rcd_Need)
	{   // สร้างข้อมูลแถวของเอกสาร cr0046
		$Sql_Cmd_Row_Show = Create_String_For_Load_Document('CR0046','30');	
		$Result = pg_query($Sql_Cmd_Row_Show); $Count = 0; 
		
		while($Data = pg_fetch_array($Result))
		{
			$Checker = Get_Checker_Name($Data[4]);
			$Count++; 
		 	
			if($Count%2==0) // กำหนด รูปแบบการแสดงข้อมูลของ แถว ในตาราง
			{
				$Class_Type = "odd";	
			}else
			{
				$Class_Type = "even";
			}
			// แสดงข้อมูลในแต่ละแถว แถวละ 5 Column(รายการที่,ประเภทสัญญา,เลขที่สัญญา,ตรวจสอบครั้งที่,ผู้ทำรายการ,วันเวลาที่ตรวจสอบ,หมายเหตุ,ผลการตรวจสอบ)
			?> 
				<TR class="<?php echo $Class_Type; ?>"><!-- Start Row -->
					<TD align="center">
						<?php 
							echo $Count; 
						?>
					</TD><!-- รายการที่ -->
					<TD align="center">
						<?php 
							echo $Data[0]; 
						?>
					</TD><!-- ประเภทสัญญา -->
					<TD align="center">
						<?php  
							Show_Line_Link_Display_Contract_installments_By_ContractID($Data[1]);
						?>
					</TD><!-- เลขที่สัญญา -->
					<TD align="center">
						<?php 
							echo $Data[2]; 
						?>
					</TD><!-- จำนวนครั้งที่ตรวจสอบ -->
					<TD align="left">
						<?php 
							echo $Checker; 
						?>
					</TD><!-- ผู้ทำรายการ -->
					<TD align="center">
						<?php 
							echo $Data[3]; 
						?>
					</TD><!--วันเวลาที่ทดสอบระบบ -->
					<TD align = "center">
						<?php 

							Show_Line_Link_Display_Approve_Note($Data); 
						?>
					</TD><!-- หมายเหตุการตรวจสอบ  -->
					<TD align="center">
						<?php 
							// แสดงผลการตรวจสอบ ผ่าน หรือ ไม่ผ่าน
							$Show = explode('#',$Data[7]);
							if($Show[1] == 1){
								echo "ผ่าน";
							}else{
								echo "ไม่ผ่าน";
							}
						?>
					</TD><!-- ผลการตรวจสอบเอกสาร -->
					<TD align = "center"> 
						<?php  
							show_Image_Link_Display_Docs_Detail_cr0046($Data[5]); 
						?>
					</TD>
				</TR><!-- End Row -->
				
			<?php

		}			
		
	}// End function Row_Table_Of_cr_0046
	function show_doc_msg($Str_Show,$Font_Size){
	// แสดงข้อความตรงกลางในเอกสาร	
		?>
		<DIV align="center">
			<H2>
				<?php
					echo $Str_Show;
				?>
			</H2>			
		</DIV>
		<?php
		
	}
	
	function show_Image_Link_Display_Docs_Detail_cr0046($Doc_ID)
	{   // ทำ text hyperlink ไปข้อมูลการบันทึกการตรวจสอบเอกสาร
		?>
		<a onclick = "Display_Docs_cr_0046('<?php echo $Doc_ID; ?>')"
		   style="cursor:pointer;"
		   title="รายละเอียดการตรวจสอบเอกสาร" >
			<font color = "blue">
				<u>
					<?php
						echo $Doc_ID;
					?>
				</u>
			</font>   
		</a>
		<?php
		
	}
	
	function show_Line_Link_To_Check_Document($Txt_Doc,$File_Name)
	{   // ทำ Text Link
		?>  
			<a onclick="javascript:popU('<?php echo $File_Name; ?>',
										'',
										'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720')" 
						style="cursor:pointer;">
						<font color = "blue">
							<u> 
									<?php 
										echo $Txt_Doc; 
									?>
							</u>		
						</font>	
							
							
			</a>
		<?php	
	}// End Of show_Line_Link_To_Check_Document
	
	function show_Line_Link_To_Check_Document_2($Txt_Doc,$File_Name)
	{   // สร้าง Link สำหรับการ เปิด Window
		?>  
			<a onclick="javascript:popU('<?php echo $File_Name; ?>',
										'',
										'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" 
						style="cursor:pointer;">
						<font color = "blue">
							<u> 
									<?php 
										echo $Txt_Doc; 
									?>
							</u>		
						</font>	
							
							
			</a>
		<?php	
	}// End Of show_Line_Link_To_Check_Document_2
	
	function Show_Line_Link_Display_Contract_installments_By_ContractID($ContractID)// แสดงตารรางการผ่อนชำระ ของแต่ละสัญญา
	{
		$Url_Open = "../thcap_installments/frm_Index.php?show=1&idno=".$ContractID;
		show_Line_Link_To_Check_Document($ContractID,$Url_Open);
		
	}
	
	function Show_Line_Link_Display_Approve_Note($Data_show)
	{   // ทำข้อมูลไว้สำหรับสร้าง การ Link
		// Define ค่า Parameter (เลขที่เอกสาร,เลขที่สัญญา) 
		$Parameter_For_Search = "Docs_ID=".$Data_show['Docs_ID']."&"."Contract_ID=".$Data_show[1];
		// Define File Name กับ Parameter
		$File_Name = "Show_Notice.php?".$Parameter_For_Search;
		show_Line_Link_To_Check_Document_2('หมายเหตุ',$File_Name);		
	}	
?>
<script>
	function Chk_Input_Data(Contract_Type,Contract_ID)
	{  // ตรวจสอบการนำเข้าข้อมูล
		var Chk_Status = true;
		var Err_Msg = "";
		var Contract_Type_Check = document.getElementById(Contract_Type).value;
		var Contract_ID_Check = document.getElementById(Contract_ID).value;
		if(Contract_Type_Check == '-'){
			Err_Msg = Err_Msg +  'กรุณาเลือก ประเภทสินเชื่อ\n';
			Chk_Status = false;
		}
		if(Contract_ID_Check==null || Contract_ID_Check==""){
        	Err_Msg = Err_Msg + 'กรุณาระบุ เลขที่สัญญา \n';
        	Chk_Status = false;
    	}
    	if(Chk_Status == false){
    		alert(Err_Msg);
    	}
		return Chk_Status;
	}
	function Chk_Input_Data_cr_0046(){ // ตรวจสอบข้อมูลนำเข้า ของเอกสาร CR0046
		var Chk_Status = true;
		var Err_Msg = "";
		var Err_Msg2 = "";
		
		// ตรวจสอบการเลือกประภทลูกค้า ว่าเป็น บุคคลธรรมดา หรือ เป็น นิติบุคคล จากหน้าที่ 1
		var Chk_Personal = document.getElementById('Cust_Personal').checked;
		var Chk_Person = document.getElementById('Cust_Person').checked;		
		if(!(Chk_Personal || Chk_Person))
		{
			alert('ในหน้าที่ 1 กรุณาเลือก ลูกค้า ว่าเป็น บุคคลธรรมดา หรือ เป็น นิติบุคคล');
			return false;
		}	
			
		// รับค่าตัวแปร จาก ข้อ A หน้าที่ 1
		var Chk_A_1 = document.getElementById('Check_A_1').checked;
		var Chk_A_2 = document.getElementById('Check_A_2').checked;
		var Chk_A_3 = document.getElementById('Check_A_3').checked;
		// Check กรณีที่ไม่ได้เลือก
		if(!(Chk_A_1 || Chk_A_2 || Chk_A_3))
		{   
			if(Err_Msg.length == 0)
			{
				Err_Msg += "A";	
			}else{
				Err_Msg += ",A";
			}
			
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ B หน้าที่ 1
		var Chk_B_1 = document.getElementById('Check_B_1').checked;
		var Chk_B_2 = document.getElementById('Check_B_2').checked;
		var Chk_B_3 = document.getElementById('Check_B_3').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_B_1 || Chk_B_2 || Chk_B_3))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "B";
			}else{
				Err_Msg += ",B";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ C หน้าที่ 1
		var Chk_C_1 = document.getElementById('Check_C_1').checked;
		var Chk_C_2 = document.getElementById('Check_C_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_C_1 || Chk_C_2))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "C";
			}else{
				Err_Msg += ",C";
			}
			Chk_Status = false;  
		} 
		
		// รับค่าตัวแปร จาก ข้อ D หน้าที่ 1
		var Chk_D_1 = document.getElementById('Check_D_1').checked;
		var Chk_D_2 = document.getElementById('Check_D_2').checked;
		var Chk_D_3 = document.getElementById('Check_D_3').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_D_1 || Chk_D_2 || Chk_D_3))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "D";
			}else{
				Err_Msg += ",D";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ E หน้าที่ 1
		var Chk_E_1 = document.getElementById('Check_E_1').checked;
		var Chk_E_2 = document.getElementById('Check_E_2').checked;
		var Chk_E_3 = document.getElementById('Check_E_3').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_E_1 || Chk_E_2 || Chk_E_3))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "E";
			}else{
				Err_Msg += ",E";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ F หน้าที่ 1
		var Chk_F_1 = document.getElementById('Check_F_1').checked;
		var Chk_F_2 = document.getElementById('Check_F_2').checked;
		var Chk_F_3 = document.getElementById('Check_F_3').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_F_1 || Chk_F_2 || Chk_F_3))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "F";
			}else{
				Err_Msg += ",F";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ G หน้าที่ 1
		var Chk_G_1 = document.getElementById('Check_G_1').checked;
		var Chk_G_2 = document.getElementById('Check_G_2').checked;
		var Chk_G_3 = document.getElementById('Check_G_3').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_G_1 || Chk_G_2 || Chk_G_3))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "G";
			}else{
				Err_Msg += ",G";
			}
			Chk_Status = false;  
		}
		
		if(Chk_Personal)
		{
			
			// รับค่าตัวแปร จาก ข้อ H หน้าที่ 1
			var Chk_H_1 = document.getElementById('Check_H_1').checked;
			var Chk_H_2 = document.getElementById('Check_H_2').checked;
			
			// Check กรณีที่ไม่ได้เลือก ข้อ H หน้า 1
			if(!(Chk_H_1 || Chk_H_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "H";
				}else{
					Err_Msg += ",H";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ I หน้าที่ 1
			var Chk_I_1 = document.getElementById('Check_I_1').checked;
			var Chk_I_2 = document.getElementById('Check_I_2').checked;

			// Check กรณีที่ไม่ได้เลือก ข้อ I หน้า 1
			if(!(Chk_I_1 || Chk_I_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "I";
				}else{
				Err_Msg += ",I";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ J หน้าที่ 1
			var Chk_J_1 = document.getElementById('Check_J_1').checked;
			var Chk_J_2 = document.getElementById('Check_J_2').checked;
			
			// Check กรณีที่ไม่ได้เลือก ข้อ J หน้า 1
			if(!(Chk_J_1 || Chk_J_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "J";
				}else{
					Err_Msg += ",J";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ K หน้าที่ 1
			var Chk_K_1 = document.getElementById('Check_K_1').checked;
			var Chk_K_2 = document.getElementById('Check_K_2').checked;
			
			// Check กรณีที่ไม่ได้เลือก ข้อ K หน้า 1
			if(!(Chk_K_1 || Chk_K_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "K";
				}else{
					Err_Msg += ",K";
				}
				Chk_Status = false;  
			}
		
		}// End Of if(Chk_Personal) กรณีที่เป็น บุคคลธรรมดา
		
		
		if(Chk_Person)
		{
						
			// รับค่าตัวแปร จาก ข้อ L หน้าที่ 1
			var Chk_L_1 = document.getElementById('Check_L_1').checked;
			var Chk_L_2 = document.getElementById('Check_L_2').checked;
		
			// Check กรณีที่ไม่ได้เลือก ข้อ L
			if(!(Chk_L_1 || Chk_L_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "L";
				}else{
					Err_Msg += ",L";
				}
				Chk_Status = false;  
			}
		
		
				// รับค่าตัวแปร จาก ข้อ M หน้าที่ 1
				var Chk_M_1 = document.getElementById('Check_M_1').checked;
				var Chk_M_2 = document.getElementById('Check_M_2').checked;
	
				// Check กรณีที่ไม่ได้เลือก ข้อ M หน้า 1
				if(!(Chk_M_1 || Chk_M_2 ))
				{ 
					if(Err_Msg.length == 0)
					{
						Err_Msg += "M";
					}else{
						Err_Msg += ",M";
					}
					Chk_Status = false;  
				}
		
				// รับค่าตัวแปร จาก ข้อ N หน้าที่ 1
				var Chk_N_1 = document.getElementById('Check_N_1').checked;
				var Chk_N_2 = document.getElementById('Check_N_2').checked;
		
				// Check กรณีที่ไม่ได้เลือกข้อ N หน้าที่ 1
				if(!(Chk_N_1 || Chk_N_2 ))
				{ 
					if(Err_Msg.length == 0)
					{
						Err_Msg += "N";
					}else{
						Err_Msg += ",N";
					}
					Chk_Status = false;  
				}
		
				// รับค่าตัวแปร จาก ข้อ O หน้าที่ 1
				var Chk_O_1 = document.getElementById('Check_O_1').checked;
				var Chk_O_2 = document.getElementById('Check_O_2').checked;

				// Check กรณีที่ไม่ได้เลือก ข้อ O หน้าที่ 1
				if(!(Chk_O_1 || Chk_O_2 ))
				{ 
					if(Err_Msg.length == 0)
					{
						Err_Msg += "O";
					}else{
						Err_Msg += ",O";
					}
					Chk_Status = false;  
				}
			
			}// End Of 	if(Chk_Person) กรณีที่เป็น นิติบุคคล
		
		if(Chk_Personal)
		{
					
			// รับค่าตัวแปร จาก ข้อ P หน้าที่ 1
			var Chk_P_1 = document.getElementById('Check_P_1').checked;
			var Chk_P_2 = document.getElementById('Check_P_2').checked;
	
			// Check กรณีที่ไม่ได้เลือก ข้อ P หน้าที่ 1
			if(!(Chk_P_1 || Chk_P_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "P";
				}else{
					Err_Msg += ",P";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ Q หน้าที่ 1
			var Chk_Q_1 = document.getElementById('Check_Q_1').checked;
			var Chk_Q_2 = document.getElementById('Check_Q_2').checked;
			
			// Check กรณีที่ไม่ได้เลือก ข้อ Q หน้าที่ 1
			if(!(Chk_Q_1 || Chk_Q_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "Q";
				}else{
					Err_Msg += ",Q";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ R หน้าที่ 1
			var Chk_R_1 = document.getElementById('Check_R_1').checked;
			var Chk_R_2 = document.getElementById('Check_R_2').checked;
		
			// Check กรณีที่ไม่ได้เลือก ข้อ R หน้าที่ 1
			if(!(Chk_R_1 || Chk_R_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "R";
				}else{
					Err_Msg += ",R";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ S หน้าที่ 1
			var Chk_S_1 = document.getElementById('Check_S_1').checked;
			var Chk_S_2 = document.getElementById('Check_S_2').checked;

			// Check กรณีที่ไม่ได้เลือก ข้อ S หน้าที่ 1
			if(!(Chk_S_1 || Chk_S_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "S";
				}else{
					Err_Msg += ",S";
				}
				Chk_Status = false;  
			}
		
		} // End Of if(Chk_Personal) กรณีที่เป็น บุคคลธรรมดา		
		
		if(Chk_Person)
		{
						
			// รับค่าตัวแปร จาก ข้อ T หน้าที่ 1
			var Chk_T_1 = document.getElementById('Check_T_1').checked;
			var Chk_T_2 = document.getElementById('Check_T_2').checked;
		
			// Check กรณีที่ไม่ได้เลือก ข้อ T หน้า 1
			if(!(Chk_T_1 || Chk_T_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "T";
				}else{
					Err_Msg += ",T";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ U หน้าที่ 1
			var Chk_U_1 = document.getElementById('Check_U_1').checked;
			var Chk_U_2 = document.getElementById('Check_U_2').checked;
	
			// Check กรณีที่ไม่ได้เลือก ข้อ U หน้า 1
			if(!(Chk_U_1 || Chk_U_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "U";
				}else{
					Err_Msg += ",U";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ V หน้าที่ 1
			var Chk_V_1 = document.getElementById('Check_V_1').checked;
			var Chk_V_2 = document.getElementById('Check_V_2').checked;
	
			// Check กรณีที่ไม่ได้เลือก
			if(!(Chk_V_1 || Chk_V_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "V";
				}else{
					Err_Msg += ",V";
				}
				Chk_Status = false;  
			}
		
			// รับค่าตัวแปร จาก ข้อ W หน้าที่ 1
			var Chk_W_1 = document.getElementById('Check_W_1').checked;
			var Chk_W_2 = document.getElementById('Check_W_2').checked;	
	
			// Check กรณีที่ไม่ได้เลือก ข้อ W หน้าที่ 1 
			if(!(Chk_W_1 || Chk_W_2 ))
			{ 
				if(Err_Msg.length == 0)
				{
					Err_Msg += "W";
				}else{
					Err_Msg += ",W";
				}
				Chk_Status = false;  
			}
		
		} // End Of if(Chk_Person) กรณีที่เป็น นิติบุคคล
		
		
		
		
		// รับค่าตัวแปร จาก ข้อ X หน้าที่ 1
		var Chk_X_1 = document.getElementById('Check_X_1').checked;
		var Chk_X_2 = document.getElementById('Check_X_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_X_1 || Chk_X_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "X";
			}else{
				Err_Msg += ",X";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ Y หน้าที่ 1
		var Chk_Y_1 = document.getElementById('Check_Y_1').checked;
		var Chk_Y_2 = document.getElementById('Check_Y_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Y_1 || Chk_Y_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "Y";
			}else{
				Err_Msg += ",Y";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ Z หน้าที่ 2
		var Chk_Z_1 = document.getElementById('Check_Z_1').checked;
		var Chk_Z_2 = document.getElementById('Check_Z_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Z_1 || Chk_Z_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "Z";
			}else{
				Err_Msg += ",Z";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ @ หน้าที่ 2
		var Chk_AA_1 = document.getElementById('Check_AA_1').checked;
		var Chk_AA_2 = document.getElementById('Check_AA_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AA_1 || Chk_AA_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "@";
			}else{
				Err_Msg += ",@";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ # หน้าที่ 2
		var Chk_AB_1 = document.getElementById('Check_AB_1').checked;
		var Chk_AB_2 = document.getElementById('Check_AB_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AB_1 || Chk_AB_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "#";
			}else{
				Err_Msg += ",#";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ $ หน้าที่ 2
		var Chk_AC_1 = document.getElementById('Check_AC_1').checked;
		var Chk_AC_2 = document.getElementById('Check_AC_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AC_1 || Chk_AC_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "$";
			}else{
				Err_Msg += ",$";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ % หน้าที่ 2
		var Chk_AD_1 = document.getElementById('Check_AD_1').checked;
		var Chk_AD_2 = document.getElementById('Check_AD_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AD_1 || Chk_AD_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "%";
			}else{
				Err_Msg += ",%";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ ก หน้าที่ 2
		var Chk_AE_1 = document.getElementById('Check_AE_1').checked;
		var Chk_AE_2 = document.getElementById('Check_AE_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AE_1 || Chk_AE_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "ก";
			}else{
				Err_Msg += ",ก";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ ข หน้าที่ 2
		var Chk_AF_1 = document.getElementById('Check_AF_1').checked;
		var Chk_AF_2 = document.getElementById('Check_AF_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AF_1 || Chk_AF_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "ข";
			}else{
				Err_Msg += ",ข";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก หัวตาราง หน้าที่ 2
		var Chk_AN_1= document.getElementById('Check_AN_1').checked;
		var Chk_AN_2 = document.getElementById('Check_AN_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AN_1 || Chk_AN_2 ))
		{ 
			if(Err_Msg2.length == 0)
			{
				Err_Msg2 += "ที่ กรณีการมอบอำนาจให้กระทำการแทน ผู้ลงนามเป็นผู้รับมอบอำนาจ โปรดเลือก ใช่ หรือ ไม่ใช่\n";
			}else{
				Err_Msg2 += ",ที่ กรณีการมอบอำนาจให้กระทำการแทน ผู้ลงนามเป็นผู้รับมอบอำนาจ โปรดเลือก ใช่ หรือ ไม่ใช่\n";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ ค หน้าที่ 2
		var Chk_AI_1= document.getElementById('Check_AI_1').checked;
		var Chk_AI_2 = document.getElementById('Check_AI_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AI_1 || Chk_AI_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "ค";
			}else{
				Err_Msg += ",ค";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ ง หน้าที่ 2
		var Chk_AJ_1= document.getElementById('Check_AJ_1').checked;
		var Chk_AJ_2 = document.getElementById('Check_AJ_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AI_1 || Chk_AI_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "ง";
			}else{
				Err_Msg += ",ง";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ จ หน้าที่ 2
		var Chk_AK_1= document.getElementById('Check_AK_1').checked;
		var Chk_AK_2 = document.getElementById('Check_AK_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AK_1 || Chk_AK_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "จ";
			}else{
				Err_Msg += ",จ";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ ฉ หน้าที่ 2
		var Chk_AL_1= document.getElementById('Check_AL_1').checked;
		var Chk_AL_2 = document.getElementById('Check_AL_2').checked;
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_AK_1 || Chk_AK_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "ฉ";
			}else{
				Err_Msg += ",ฉ";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ข้อ ช หน้าที่ 2
		var Chk_AM_1= document.getElementById('Check_AM_1').checked;
		var Chk_AM_2 = document.getElementById('Check_AM_2').checked;
		// Check กรณีที่ไม่ได้เลือก
		if(!(Chk_AM_1 || Chk_AM_2 ))
		{ 
			if(Err_Msg.length == 0)
			{
				Err_Msg += "ช";
			}else{
				Err_Msg += ",ช";
			}
			Chk_Status = false;  
		}
		
		// รับค่าตัวแปร จาก ผลการตรวจสอบ หน้าที่ 2
		var Checker_1 = document.getElementById('Checker_1').checked;
		var Checker_2 = document.getElementById('Checker_2').checked;
		// Check กรณีที่ไม่ได้เลือก
		if(!(Checker_1 || Checker_2 ))
		{ 
			if(Err_Msg2.length == 0)
			{
				Err_Msg2 += "ที่ กรณีผลการตรวจสอบ โปรดเลือก ผ่าน หรือ ไม่ผ่าน\n";
			}else{
				Err_Msg2 += ",ที่ กรณีผลการตรวจสอบ โปรดเลือก ผ่าน หรือ ไม่ผ่าน\n";
			}
			
			
			Chk_Status = false;  
		}
		
		
		
		var Legend_Name = document.getElementById('Legend').value;
		if(Legend_Name == '-')
		{
			if(Err_Msg2.length == 0)
			{
				Err_Msg2 += "กรุณาเลือกทนายผู้ให้คำรับรอง\n";
			}else{
				Err_Msg2 += ",กรุณาเลือกทนายผู้ให้คำรับรอง\n";
			}
			Chk_Status = false;
		}
		
		if(Err_Msg.length>0){
			Err_Msg = "ในข้อ " + Err_Msg;
		}
		
		if(Chk_Status == false)
		{
			alert('กรุณานำเข้าข้อมูล'+ Err_Msg+'\n'+Err_Msg2);
		}
		 
		if(Chk_Status == true){
			if(confirm("ต้องการบันทึกข้อมูลหรือไม่")){
				Create_Array_For_Save_Doc_cr0046(); // เตรียมบันทึกข้อมูล แล้วบันทึกข้อมูล ของ เอกสาร CR0046
			}else{
				Chk_Status = false;
			}
		}
		return Chk_Status;
	}// End Of function Chk_Input_Data_cr_0046  
	
	function Chk_Input_Data_cr_0089_type_1()
	{
		
		var val_chk = document.getElementById('Dealer_Name').value;
		
		if(val_chk==null || val_chk==""){
			alert('กรุณานำเข้าผู้จัดจำหน่าย')
			return false;	
		}else{
			return true;
		}
	}
	function Chk_Input_Data_cr_0089_type_2()
	{
		
		
		var Chk_Status = true;
		var Err_Msg = "";
		
		var Valuator_Date = document.getElementById("Txt_Date").value;
		if(Valuator_Date =="")
		{
			Err_Msg = "กรุณานำเข้า วันที่ประเมิน  \n";
			Chk_Status = false;
		} 
				
		var Chk_Fst_Name = document.getElementById("Valuator_Name").value;
		if(Chk_Fst_Name == "")
		{
			Err_Msg = Err_Msg + "กรุณานำเข้า ชื่อ-สกุล ผู้ประเมิน  \n";
			Chk_Status = false;
		} 
					
		var Rank = document.getElementById("Rank").value
		if(Rank == "")
		{
			Err_Msg = Err_Msg + "กรุณานำเข้า ตำแหน่ง ของ  ผู้ประเมิน \n";
			Chk_Status = false;
		}
		
		var Rank = document.getElementById("Telephone").value
		if(Rank == "")
		{
			Err_Msg = Err_Msg + "กรุณานำเข้า เบอร์โทรศัพท์ ผู้ประเมิน \n";
			Chk_Status = false;
		}
		
		
		var Chk_Choice_Excellent = document.getElementById('C1_Excellent').checked; //สถานะ การ Check ใน ช่อง  ดีเยี่ยม  
		var Chk_Choice_Good = document.getElementById('C1_Good').checked; // สพานะการ Check ในช่อง ดี
		var Chk_Choice_Middle = document.getElementById('C1_Middle').checked; // สถานะการ Check ในช่อง ปานกลาง
		var Chk_Choice_ShouldImprove = document.getElementById('C1_ShouldImprove').checked; // สถานะการ Check ในช่อง ควรปรับปรุง
		var Chk_Choice_MustImprove = document.getElementById('C1_MustImprove').checked; // สถานะการ Check ในช่อง ต้องปรับปรุง  
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Choice_Excellent || Chk_Choice_Good || Chk_Choice_Middle || Chk_Choice_ShouldImprove || Chk_Choice_MustImprove ))
		{
			Err_Msg = Err_Msg + "กรุณาเลือกในหัวข้อ 1 สามารถติดต่อประสานงานได้ง่าย \n";
			Chk_Status = false;
		}
		
		var Chk_Choice_Excellent = document.getElementById('C2_Excellent').checked; //สถานะ การ Check ใน ช่อง  ดีเยี่ยม  
		var Chk_Choice_Good = document.getElementById('C2_Good').checked; // สพานะการ Check ในช่อง ดี
		var Chk_Choice_Middle = document.getElementById('C2_Middle').checked; // สถานะการ Check ในช่อง ปานกลาง
		var Chk_Choice_ShouldImprove = document.getElementById('C2_ShouldImprove').checked; // สถานะการ Check ในช่อง ควรปรับปรุง
		var Chk_Choice_MustImprove = document.getElementById('C2_MustImprove').checked; // สถานะการ Check ในช่อง ต้องปรับปรุง  
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Choice_Excellent || Chk_Choice_Good || Chk_Choice_Middle || Chk_Choice_ShouldImprove || Chk_Choice_MustImprove ))
		{
			Err_Msg = Err_Msg + "กรุณาเลือกในหัวข้อ 2  ระยะเวลาการให้บริการ รวดเร็ว เป็นไปตามเวลาที่ตกลง \n";
			Chk_Status = false;
		}
		
		var Chk_Choice_Excellent = document.getElementById('C3_Excellent').checked; //สถานะ การ Check ใน ช่อง  ดีเยี่ยม  
		var Chk_Choice_Good = document.getElementById('C3_Good').checked; // สพานะการ Check ในช่อง ดี
		var Chk_Choice_Middle = document.getElementById('C3_Middle').checked; // สถานะการ Check ในช่อง ปานกลาง
		var Chk_Choice_ShouldImprove = document.getElementById('C3_ShouldImprove').checked; // สถานะการ Check ในช่อง ควรปรับปรุง
		var Chk_Choice_MustImprove = document.getElementById('C3_MustImprove').checked; // สถานะการ Check ในช่อง ต้องปรับปรุง  
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Choice_Excellent || Chk_Choice_Good || Chk_Choice_Middle || Chk_Choice_ShouldImprove || Chk_Choice_MustImprove ))
		{
			Err_Msg = Err_Msg + "กรุณาเลือกในหัวข้อ 3  ความสุภาพ ไมตรีจิต จิตบริการ  \n";
			Chk_Status = false;
		}
		
		var Chk_Choice_Excellent = document.getElementById('C4_Excellent').checked; //สถานะ การ Check ใน ช่อง  ดีเยี่ยม  
		var Chk_Choice_Good = document.getElementById('C4_Good').checked; // สพานะการ Check ในช่อง ดี
		var Chk_Choice_Middle = document.getElementById('C4_Middle').checked; // สถานะการ Check ในช่อง ปานกลาง
		var Chk_Choice_ShouldImprove = document.getElementById('C4_ShouldImprove').checked; // สถานะการ Check ในช่อง ควรปรับปรุง
		var Chk_Choice_MustImprove = document.getElementById('C4_MustImprove').checked; // สถานะการ Check ในช่อง ต้องปรับปรุง  
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Choice_Excellent || Chk_Choice_Good || Chk_Choice_Middle || Chk_Choice_ShouldImprove || Chk_Choice_MustImprove ))
		{
			Err_Msg = Err_Msg + "กรุณาเลือกในหัวข้อ 4  ความกระตือรือร้น ช่วยติดตามงาน   \n";
			Chk_Status = false;
		}
		
		var Chk_Choice_Excellent = document.getElementById('C5_Excellent').checked; //สถานะ การ Check ใน ช่อง  ดีเยี่ยม  
		var Chk_Choice_Good = document.getElementById('C5_Good').checked; // สพานะการ Check ในช่อง ดี
		var Chk_Choice_Middle = document.getElementById('C5_Middle').checked; // สถานะการ Check ในช่อง ปานกลาง
		var Chk_Choice_ShouldImprove = document.getElementById('C5_ShouldImprove').checked; // สถานะการ Check ในช่อง ควรปรับปรุง
		var Chk_Choice_MustImprove = document.getElementById('C5_MustImprove').checked; // สถานะการ Check ในช่อง ต้องปรับปรุง  
		// Chekc กรณีที่ไม่ได้เลือก
		if(!(Chk_Choice_Excellent || Chk_Choice_Good || Chk_Choice_Middle || Chk_Choice_ShouldImprove || Chk_Choice_MustImprove ))
		{
			Err_Msg = Err_Msg + "กรุณาเลือกในหัวข้อ 5  ความรับผิดชอบ  \n";
			Chk_Status = false;
		}
		
		
		if(Err_Msg.length > 0)
		{
			alert(Err_Msg);
		}
		if(Chk_Status)
		{
			if(confirm("ต้องการบันทึกข้อมูลหรือไม่"))
			{
				Create_Array_For_Save_Doc_cr0089();
			}
		}
	}
		
	function Create_Array_For_Save_Doc_cr0046(){

		var elem = document.getElementById('frmMain').elements;
		var Send_Array = [];

		for(var i =0;i<elem.length;i++)
		{
			str = elem[i].type+"|"+elem[i].name+"|"+elem[i].id+"|"+elem[i].value+"|"+elem[i].checked;
		
			Send_Array.push(str);
		}
		
		$.post( "save_thcap_cr0046.php",
				{dat: Send_Array, name :'hello'},
				function(data){
					alert(data);
					window.close();
				}	
		)
		
		
		
		
	}
	
	function  Create_Array_For_Save_Doc_cr0089()
	{   alert("Create Array 0089 Called");
		// เก็บเอกสาร cr0089 ลงฐานข้อมูล เก็นข้อมูล จาก Form ที่ ID = "frm1"
		var elem = document.getElementById('frm1').elements;
		var Send_Array = [];
		// alert("No"+elem.length);
		for(var i =0;i<elem.length;i++)
		{
			str = elem[i].type+"|"+elem[i].name+"|"+elem[i].id+"|"+elem[i].value+"|"+elem[i].checked;
			//alert(str);
			Send_Array.push(str);
		}
		
		$.post( "save_thcap_Audit_Docs_All.php",{data: Send_Array,doctype:'cr0089'})
		.done(function( data ) {
			 alert( "Data Loaded: " + data );
		});
	
		
		
	}
	
	
	
	
	function Display_Docs_cr_0046(Doc_ID)
	{ // แสดงข้อมูลเอกสาร cr_0046
		
		var mi_u = 'Show_thcap_cr0046.php?Doc_ID='+Doc_ID;
		var mi_t = 'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720';
		popU(mi_u,'',mi_t);
		
	}
	function popU(U,N,T) {
    	newWindow = window.open(U, N, T);
	}
	
	function radio_chk_person(){ // ทำกรณีที่  คลิก  นิติบุคคล ในเอกสาร cr0046
		
		document.getElementById("Check_H_1").disabled=true;
		document.getElementById("Check_H_1").checked=false;
		document.getElementById("Check_H_2").disabled=true;
		document.getElementById("Check_H_2").checked=false;
		document.getElementById("Check_I_1").disabled=true;
		document.getElementById("Check_I_1").checked=false;
		document.getElementById("Check_I_2").disabled=true;
		document.getElementById("Check_I_2").checked=false;
		document.getElementById("Check_J_1").disabled=true;
		document.getElementById("Check_J_1").checked=false;
		document.getElementById("Check_J_2").disabled=true;
		document.getElementById("Check_J_2").checked=false;
		document.getElementById("Check_K_1").disabled=true;
		document.getElementById("Check_K_1").checked=false;
		document.getElementById("Check_K_2").disabled=true;
		document.getElementById("Check_K_2").checked=false;
		document.getElementById("Check_L_1").disabled=false;
		document.getElementById("Check_L_2").disabled=false;
		document.getElementById("Check_M_1").disabled=false;
		document.getElementById("Check_M_2").disabled=false;
		document.getElementById("Check_N_1").disabled=false;
		document.getElementById("Check_N_2").disabled=false;
		document.getElementById("Check_O_1").disabled=false;
		document.getElementById("Check_O_2").disabled=false;
		document.getElementById("Check_P_1").disabled=true;
		document.getElementById("Check_P_1").checked=false;
		document.getElementById("Check_P_2").disabled=true;
		document.getElementById("Check_P_2").checked=false;
		document.getElementById("Check_Q_1").disabled=true;
		document.getElementById("Check_Q_1").checked=false;
		document.getElementById("Check_Q_2").disabled=true;
		document.getElementById("Check_Q_2").checked=false;
		document.getElementById("Check_R_1").disabled=true;
		document.getElementById("Check_R_1").checked=false;
		document.getElementById("Check_R_2").disabled=true;
		document.getElementById("Check_R_2").checked=false;
		document.getElementById("Check_S_1").disabled=true;
		document.getElementById("Check_S_1").checked=false;
		document.getElementById("Check_S_2").disabled=true;
		document.getElementById("Check_S_2").checked=false;
		document.getElementById("Check_T_1").disabled=false;
		document.getElementById("Check_T_2").disabled=false;
		document.getElementById("Check_U_1").disabled=false;
		document.getElementById("Check_U_2").disabled=false;
		document.getElementById("Check_V_1").disabled=false;
		document.getElementById("Check_V_2").disabled=false;
		document.getElementById("Check_W_1").disabled=false;
		document.getElementById("Check_W_2").disabled=false;
	
	}
	function radio_chk_personal(){ // ทำจาก Click กรณีที่เป็น บุคคลธรรมดา  ในเอกสาร cr0046
	
		document.getElementById("Check_H_1").disabled=false;
		document.getElementById("Check_H_2").disabled=false;
		document.getElementById("Check_I_1").disabled=false;
		document.getElementById("Check_I_2").disabled=false;
		document.getElementById("Check_J_1").disabled=false;
		document.getElementById("Check_J_2").disabled=false;
		document.getElementById("Check_K_1").disabled=false;
		document.getElementById("Check_K_2").disabled=false;
		document.getElementById("Check_L_1").disabled=true; 
		document.getElementById("Check_L_1").checked=false;
		document.getElementById("Check_L_2").disabled=true; 
		document.getElementById("Check_L_2").checked=false;
		document.getElementById("Check_M_1").disabled=true; 
		document.getElementById("Check_M_1").checked=false;
		document.getElementById("Check_M_2").disabled=true; 
		document.getElementById("Check_M_2").checked=false;
		document.getElementById("Check_N_1").disabled=true; 
		document.getElementById("Check_N_1").checked=false;
		document.getElementById("Check_N_2").disabled=true; 
		document.getElementById("Check_N_2").checked=false;
		document.getElementById("Check_O_1").disabled=true; 
		document.getElementById("Check_O_1").checked=false;
		document.getElementById("Check_O_2").disabled=true; 
		document.getElementById("Check_O_2").checked=false;
		document.getElementById("Check_P_1").disabled=false;
		document.getElementById("Check_P_2").disabled=false;
		document.getElementById("Check_Q_1").disabled=false;
		document.getElementById("Check_Q_2").disabled=false;
		document.getElementById("Check_R_1").disabled=false;
		document.getElementById("Check_R_2").disabled=false;
		document.getElementById("Check_S_1").disabled=false;
		document.getElementById("Check_S_2").disabled=false;
		document.getElementById("Check_T_1").disabled=true;
		document.getElementById("Check_T_1").checked=false;
		document.getElementById("Check_T_2").disabled=true;
		document.getElementById("Check_T_2").checked=false;
		document.getElementById("Check_U_1").disabled=true;
		document.getElementById("Check_U_1").checked=false;
		document.getElementById("Check_U_2").disabled=true;
		document.getElementById("Check_U_2").checked=false;
		document.getElementById("Check_V_1").disabled=true;
		document.getElementById("Check_V_1").checked=false;
		document.getElementById("Check_V_2").disabled=true;
		document.getElementById("Check_V_2").checked=false;
		document.getElementById("Check_W_1").disabled=true;
		document.getElementById("Check_W_1").checked=false;
		document.getElementById("Check_W_2").disabled=true;
		document.getElementById("Check_W_2").checked=false;
	}
	
	
	
</script>