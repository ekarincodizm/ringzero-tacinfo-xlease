<?php
	session_start();
	include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<TITLE>รายงานพนักงานเข้า-ออกประจำเดือน</TITLE>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<?php	
	$Command_Process = pg_escape_string($_POST["Comand"]); 
	$Month_Receive = pg_escape_string($_POST['Month']);
	$Year_Receive = pg_escape_string($_POST['Year']); 
	function Create_SQL_Command_For_Member_In($Company,$Month,$Year){
		$Str_In_Employee = " SELECT ";
    	$Str_In_Employee = $Str_In_Employee." a.\"fname\"||' '||a.\"lname\" AS \"fullNane\", "; // ชื่อ-นามสกุล
    	$Str_In_Employee = $Str_In_Employee." a.\"empid\"  AS emp_id,"; // รหัสพนักงาน
		$Str_In_Employee = $Str_In_Employee." b.\"u_pos\",";// ตำแหน่ง  
    	$Str_In_Employee = $Str_In_Employee." b.\"u_idnum\", "; // เลขบัตรประชาขน
		$Str_In_Employee = $Str_In_Employee." b.\"u_birthday\", "; // วันเกิด
		$Str_In_Employee = $Str_In_Employee." c.\"dep_name\", "; // แผนก
		$Str_In_Employee = $Str_In_Employee." d.\"fdep_name\", "; // ฝ่าย
		$Str_In_Employee = $Str_In_Employee." ( SELECT nw_organize.organize_name ";
		$Str_In_Employee = $Str_In_Employee." FROM nw_organize ";
		$Str_In_Employee = $Str_In_Employee." WHERE nw_organize.\"organizeID\" = d.\"organizeID\" ";
		$Str_In_Employee = $Str_In_Employee." ) AS \"company\" ,";
 		$Str_In_Employee = $Str_In_Employee." b.\"u_extens\","; // เบอร์ภายใน
 		$Str_In_Employee = $Str_In_Employee." b.\"u_direct\", "; // เบอร์ตรง
 		$Str_In_Employee = $Str_In_Employee." b.\"u_tel\", "; // เบอร์มือถือ
 		$Str_In_Employee = $Str_In_Employee." b.\"u_email\","; // e-mail
 		$Str_In_Employee = $Str_In_Employee." b.\"startwork\" "; // วันที่เรื่มงาน
 		$Str_In_Employee = $Str_In_Employee." FROM ";
		$Str_In_Employee = $Str_In_Employee." \"fuser\" a ";
		$Str_In_Employee = $Str_In_Employee." LEFT JOIN ";
		$Str_In_Employee = $Str_In_Employee." \"fuser_detail\" b ON a.\"id_user\" = b.\"id_user\" ";
		$Str_In_Employee = $Str_In_Employee." LEFT JOIN ";
		$Str_In_Employee = $Str_In_Employee." \"department\" c ON a.\"user_group\" = c.\"dep_id\" ";
		$Str_In_Employee = $Str_In_Employee." LEFT JOIN ";
		$Str_In_Employee = $Str_In_Employee." \"f_department\" d ON a.\"user_dep\" = d.\"fdep_id\" ";
		$Str_In_Employee = $Str_In_Employee." WHERE ";
		$Str_In_Employee = $Str_In_Employee." EXTRACT(MONTH FROM b.\"startwork\") ='".$Month."' AND";
		$Str_In_Employee = $Str_In_Employee." EXTRACT(YEAR FROM b.\"startwork\") = '".$Year."'"; 
		$Str_In_Employee = $Str_In_Employee." AND d.\"organizeID\" = ".$Company;
		$Str_In_Employee = $Str_In_Employee." ORDER BY ";
		$Str_In_Employee = $Str_In_Employee." b.\"startwork\", a.\"fname\", a.\"lname\"";  
	    return($Str_In_Employee);
	}
	function Create_SQL_Command_For_Member_Out($Company,$Month,$Year){
		$Str_Out_Employee = " SELECT ";
    	$Str_Out_Employee = $Str_Out_Employee." a.\"fname\"||' '||a.\"lname\" AS \"fullNane\", "; // ชื่อ-นามสกุล
    	$Str_Out_Employee = $Str_Out_Employee." a.\"empid\"  AS emp_id,"; // รหัสพนักงาน
		$Str_Out_Employee = $Str_Out_Employee." b.\"u_pos\",";// ตำแหน่ง  
    	$Str_Out_Employee = $Str_Out_Employee." b.\"u_idnum\", "; // เลขบัตรประชาขน
		$Str_Out_Employee = $Str_Out_Employee." b.\"u_birthday\", "; // วันเกิด
		$Str_Out_Employee = $Str_Out_Employee." c.\"dep_name\", "; // แผนก
		$Str_Out_Employee = $Str_Out_Employee." d.\"fdep_name\", "; // ฝ่าย
 		$Str_Out_Employee = $Str_Out_Employee." ( SELECT nw_organize.organize_name ";
		$Str_Out_Employee = $Str_Out_Employee."  FROM nw_organize "; 
 		$Str_Out_Employee = $Str_Out_Employee." WHERE nw_organize.\"organizeID\" = d.\"organizeID\" ";
		$Str_Out_Employee = $Str_Out_Employee." ) AS \"company\",";
 		$Str_Out_Employee = $Str_Out_Employee." b.\"u_extens\","; // เบอร์ภายใน
 		$Str_Out_Employee = $Str_Out_Employee." b.\"u_direct\", "; // เบอร์ตรง
 		$Str_Out_Employee = $Str_Out_Employee." b.\"u_tel\", "; // เบอร์มือถือ
 		$Str_Out_Employee = $Str_Out_Employee." b.\"u_email\","; // e-mail
 		$Str_Out_Employee = $Str_Out_Employee." b.\"startwork\","; // วันที่เรื่มงาน
 		$Str_Out_Employee = $Str_Out_Employee." b.\"resign_date\" "; // วันที่ลาออก 
 		$Str_Out_Employee = $Str_Out_Employee." FROM ";
		$Str_Out_Employee = $Str_Out_Employee." \"fuser\" a ";
		$Str_Out_Employee = $Str_Out_Employee." LEFT JOIN ";
		$Str_Out_Employee = $Str_Out_Employee." \"fuser_detail\" b ON a.\"id_user\" = b.\"id_user\" ";
		$Str_Out_Employee = $Str_Out_Employee." LEFT JOIN ";
		$Str_Out_Employee = $Str_Out_Employee." \"department\" c ON a.\"user_group\" = c.\"dep_id\" ";
		$Str_Out_Employee = $Str_Out_Employee." LEFT JOIN ";
		$Str_Out_Employee = $Str_Out_Employee." \"f_department\" d ON a.\"user_dep\" = d.\"fdep_id\" ";
		$Str_Out_Employee = $Str_Out_Employee." WHERE ";
		$Str_Out_Employee = $Str_Out_Employee." b.\"resign_date\" IS NOT NULL AND "; // เฉพาะคนที่ลาออกไปแล้ว
		$Str_Out_Employee = $Str_Out_Employee." EXTRACT(MONTH FROM b.\"resign_date\") ='".$Month."' AND"; 
		$Str_Out_Employee = $Str_Out_Employee." EXTRACT(YEAR FROM b.\"resign_date\") = '".$Year."'  AND ";
		$Str_Out_Employee = $Str_Out_Employee." d.\"organizeID\" = ".$Company;
		$Str_Out_Employee = $Str_Out_Employee." ORDER BY ";
		$Str_Out_Employee = $Str_Out_Employee." b.\"resign_date\", a.\"fname\", a.\"lname\" ";
		return($Str_Out_Employee);
	} 
	function Display_Top_Table($Company_Name){
		?> <BR><BR> 
			<TABLE border="0" width = 100% bgcolor="#FF9933">
				<TR style="font-size:13px;font-weight:bold">
					<TD>
						<?php echo $Company_Name; ?>	
					</TD>
				</TR>
			</TABLE>	
			
		<?php
	}
	function Display_Tail_Table(){
		?>
			
		<?php
	}
	function Display_Member_In($ComIdx,$Month,$Year){
		echo "Display Member In Called ".$ComIdx; 
		if($ComIdx == "All"){
			// load รายการบริษัททั้งหมด 
			$Str_Query = " SELECT \"organizeID\", \"organize_name\" ";
			$Str_Query = $Str_Query . " FROM \"nw_organize\" ";
			$Result = pg_query($Str_Query);
			$num_row = pg_num_rows($Result);
			for($i=0;$i<$num_row;$i++){
				$Data = pg_fetch_array($Result);
				echo $Data[0].' ';
				Display_Member_In_One_Company($Data[0], $Year, $Month);	
			}
			
		}else{
			Display_Member_In_One_Company($ComIdx,$Month,$Year);
		}
		
	}
	function Display_Member_In_One_Company($Com_Idx,$Month,$Year){
		show_head_row_member_in($Month,$Year);
		$Sql_Cmd = Create_SQL_Command_For_Member_In($Com_Idx,$Month,$Year);
		$Result = pg_query($Sql_Cmd);
		$Num_Row = pg_num_rows($Result);
		if($Num_Row == 0){
			?>
			<TR style="font-size:13px;">
				<TD COLSPAN = "12" align="CENTER">
					<-- ไม่พบข้อมูล -->
				</TD>
			</TR>
			<?php
		}else{
			
			for($i=1;$i<=$Num_Row;$i++){
				$Data = pg_fetch_array($Result);
				if($i%2==0){
					$Class_Define = " class=\"odd\" ";
				}else{
					$Class_Define = " class=\"even\" ";
				}
				?>
					<TR <?php echo $Class_Define; ?> ><!-- Start Of Row -->
						<TD ALIGN = "CENTER" ><?php echo $i; ?></TD>
						<TD><?php echo $Data["emp_id"]; ?></TD>
						<TD><?php echo $Data["fullNane"]; ?></TD>
						<TD><?php echo $Data["u_pos"]; ?></TD>
						<TD><?php echo $Data["u_idnum"]; ?></TD>
						<TD align="center"><?php echo $Data["u_birthday"]; ?></TD>
						<TD><?php echo $Data["dep_name"]; ?></TD>
						<TD><?php echo $Data["fdep_name"]; ?></TD>
						<TD align="center"><?php echo $Data["u_extens"]; ?></TD>
						<TD align="center"><?php echo $Data["u_direct"]; ?></TD>
						<TD><?php echo $Data["u_tel"]; ?></TD>
						<TD><?php echo $Data["u_email"]; ?></TD>
						<TD align="center"><?php echo $Data["startwork"]; ?></TD>
						
					</TR><!-- End Of Row -->
				<?php		
			}
			
		}
		?>
		</TABLE><BR>
		<?php
	}
	function Display_Member_Out_One_Company($Com_Idx,$Month,$Year){
		show_head_row_member_Out($Month,$Year);
		$Sql_Cmd = Create_SQL_Command_For_Member_Out($Com_Idx,$Month,$Year);
		$Result = pg_query($Sql_Cmd);
		$Num_Row = pg_num_rows($Result);
		if($Num_Row == 0){
			?>
			<TR style="font-size:13px;">
				<TD COLSPAN = "13" align="CENTER">
					<-- ไม่พบข้อมูล -->
				</TD>
			</TR>
			<?php
		}else{
			for($i=1;$i<=$Num_Row;$i++){
				$Data = pg_fetch_array($Result);
				if($i%2==0){
					$Class_Define = " class=\"odd\" ";
				}else{
					$Class_Define = " class=\"even\" ";
				}
				?>
				<TR <?php echo $Class_Define; ?> ><!-- Start Of Row -->
					<TD align="CENTER"><?php echo $i;  ?></TD>
					<TD><?php echo $Data["emp_id"]; ?></TD>
					<TD><?php echo $Data["fullNane"]; ?></TD>
					<TD><?php echo $Data["u_pos"]; ?></TD>
					<TD><?php echo $Data["u_idnum"]; ?></TD>
					<TD align="center"><?php echo $Data["u_birthday"]; ?></TD>
					<TD><?php echo $Data["dep_name"]; ?></TD>
					<TD><?php echo $Data["fdep_name"]; ?></TD>
					<TD align="center"><?php echo $Data["u_extens"]; ?></TD>
					<TD align="center"><?php echo $Data["u_direct"]; ?></TD>
					<TD><?php echo $Data["u_tel"]; ?></TD>
					<TD><?php echo $Data["u_email"]; ?></TD>
					<TD align="center"><?php echo $Data["startwork"]; ?></TD>
					<TD align="center"><?php echo $Data["resign_date"]; ?></TD>
				</TR><!-- End Of Row -->	
				<?php
			}				
	   }
	   ?>
	 </TABLE><BR>
	   <?php	
	}
	function Display_Member_Out($ComIdx,$Month,$Year){
		echo "Display Member Out Called".$ComIdx."<BR><BR>";
		if($ComIdx == "All"){
			// load รายการบริษัททั้งหมด 
			$Str_Query = " SELECT \"organizeID\", \"organize_name\" ";
			$Str_Query = $Str_Query . " FROM \"nw_organize\" ";
			$Result = pg_query($Str_Query);
			$num_row = pg_num_rows($Result); echo "test ".$num_row;
			for($i=0;$i<$num_row;$i++){
				$Data = pg_fetch_array($Result);
				echo $Data[0].'<BR>';
				Display_Member_Out_One_Company($Data[0], $Month, $Year);	
			}
			
		}else{
			Display_Member_Out_One_Company($ComIdx,$Month,$Year);
		}
		
	} 
	function Display_Member_In_And_Out($Com_Idx,$Month_Range, $Year_Range){
		if($Com_Idx =='All'){
			$Company = get_all_company();
			while($Data=pg_fetch_array($Company)){
				Display_Top_Table($Data[1]);// แสดงชื่อ บริษัท  
				Display_Member_In_One_Company($Data[0], $Month_Range,$Year_Range);
				Display_Member_Out_One_Company($Data[0],$Month_Range,$Year_Range);
			}
		}else{
			$Company_Name =  get_company_name($Com_Idx); 
			Display_Top_Table($Company_Name);
			Display_Member_In_One_Company($Com_Idx, $Month_Range,$Year_Range);
			Display_Member_Out_One_Company($Com_Idx,$Month_Range,$Year_Range);
		}
		
	} 
	function get_company_name($Com_Idx){
		$Str_Query = " SELECT \"organizeID\", \"organize_name\" ";
		$Str_Query = $Str_Query . " FROM \"nw_organize\" ";
		$Str_Query = $Str_Query . " WHERE \"organizeID\" = ".$Com_Idx." ";
		$Result = pg_query($Str_Query);
		$Data = pg_fetch_array($Result);
		return($Data[1]);		
	}
	function get_all_company(){
		$Str_Query = " SELECT \"organizeID\", \"organize_name\" ";
		$Str_Query = $Str_Query . " FROM \"nw_organize\" ";
		$Result = pg_query($Str_Query);
		return($Result);
		
	}
	function get_current_year()
	{
		$Str_Now_Time = "SELECT \"nowDateTime\"() ";
		$Result_Now_Time = pg_query($Str_Now_Time);
		$Data = pg_fetch_result($Result_Now_Time, 0, 0);
		$Tmp_Var = split(" ",$Data);
		$Tmp_Var = split("-",$Tmp_Var[0]);
		return($Tmp_Var[0]);
	}
	function get_current_Month()
	{
		$Str_Now_Time = "SELECT \"nowDateTime\"() ";
		$Result_Now_Time = pg_query($Str_Now_Time);
		$Data = pg_fetch_result($Result_Now_Time, 0, 0);
		$Tmp_Var = split(" ",$Data);
		$Tmp_Var = split("-",$Tmp_Var[0]);
		return($Tmp_Var[1]);
	}
	function show_company_name_for_select($Cmd_Input){
		// echo "Show Company Name "; 
		$Str_Query = " SELECT \"organizeID\", \"organize_name\" ";
		$Str_Query = $Str_Query . " FROM \"nw_organize\" ";
		// echo $Str_Query;
		$Result = pg_query($Str_Query);
		$num_row = pg_num_rows($Result);
		/*echo $num_row; */ $i=0;  
		if($Cmd_Input == 'Show_Report'){
			$Cmp_Chk = pg_escape_string($_POST['Com_Sel']);
		}
		?>
		<select name="Com_Sel">
			<option value="All">ทั้งหมด</option>
		<?php 
		while($Data = pg_fetch_array($Result)){
			if($Data['organizeID']==$Cmp_Chk ){
				$Chk_Str = "selected";
			}else{
				$Chk_Str = "";
			}
		   	?>
			
				<option value="<?php echo $Data['organizeID'];?>"<?php echo $Chk_Str; ?>><?php echo $Data['organize_name']; ?></option>
			<?php
			$i++;
		}
		?>
				 
			
		</select>
		<?php
	}
	function Month_For_Select($Month_Chk){
		if(Empty($Month_Chk)){
			$Cur_Month = get_current_Month();
		}else{
			$Cur_Month = $Month_Chk;
		}	
		$Month_Arr = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		?>
			<select name="Month" id = "Month">
				
				<?php
					for($i=1;$i<=12;$i++)
					{
						if($i<10){
							$Month_Value = '0'.strval($i);
						}else{
							$Month_Value = strval($i);
						}
						
						if($Cur_Month == $Month_Value){
							$Select_Status = "Selected";
						}else{
							$Select_Status = "";
						}
					?>	
						<option value = <?php echo $i.' '.$Select_Status; ?> ><?php echo $Month_Arr[$i-1]; ?></option>	
					<?php
					}		
				?>
			</select>
		<?php
	}	
	function Year_For_Select($Year_Chk){
		$Start_Yr = 1975;
		$Cur_Yr = get_current_year();
		$End_Yr = $Cur_Yr+1;
		
		if(Empty($Year_Chk)){
		}else{
			$Cur_Yr = $Year_Chk;
		}	
		
		?>
			<select name = "Year" id = "Year">
				  
				<?php
					$Select_Status = "";
					for($i=$End_Yr;$i>=$Start_Yr;$i--)
					{
						if($i == $Cur_Yr){
							$Select_Status = "Selected";
						}else{
							$Select_Status = "";
						}
					 ?>
						<option value = <?php echo $i.' '.$Select_Status; ?> ><?php echo $i; ?></option>
					<?php		
					}		
				?>
			</select>
		<?php
	} 
	function show_head_row_member_in($Month,$Year){
		$Month_Arr = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		?>
		<table name = 'tb1' id = 'tb1' width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0" >
				<TR>
					<TH colspan="12" align="LEFT" style="font-size:12px;">
						<?php echo 'พนักงานเข้า ประจำเดือน  '.$Month_Arr[$Month-1].' ปี '.$Year; ?> 
						
					</TH>
				</TR>
				<TR style="font-size:12;font-weight:lighter;" valign="middle" bgcolor="#79BCFF" >
					<TH width="35" >ลำดับที่</TH><!-- Col 1 -->
					<TH width="80">รหัสพนักงาน</TH><!-- Col 2 -->
					<TH>ชื่อ-นามสกุล</TH><!-- Col 3 -->
					<TH>ตำแหน่ง</TH><!-- Col 4 -->
					<TH width="100">เลขบัตรประฃาชน</TH><!-- Col 5 -->
					<TH>วันเกิด</TH><!-- Col 6 -->
					<TH>แผนก</TH><!-- Col 7 -->
					<TH>ฝ่าย</TH><!-- Col 8 -->
					<TH>เบอร์ภายใน</TH><!-- Col 9 -->
					<TH>เบอร์ตรง</TH><!-- Col 10 -->
					<TH>เบอร์มือถือ</TH><!-- Col 11 -->
					<TH>E-mail</TH><!-- Col 12 -->
					<TH>วันที่เริ่มงาน</TH><!-- Col 13 -->
				</TR>
				
	     
		<?php
	}
	function show_head_row_member_Out($Month,$Year){
		
		$Month_Arr = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		?>
		<table name = 'tb1' id = 'tb1' width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0" >
				<TR>
					<TH colspan="13" align = 'LEFT' style="font-size:12;">
						<?php echo 'พนักงานลาออก ประจำเดือน  '.$Month_Arr[$Month-1].' ปี '.$Year; ?> 
						
					</TH>
				</TR>
				<TR style="font-size:12;font-weight:normal;" valign="middle" bgcolor="#FFFF66" >
					<TH width="35">ลำดับที่</TH><!-- Col 1 -->
					<TH width="80">รหัสพนักงาน</TH><!-- Col 2 -->
					<TH>ชื่อ-นามสกุล</TH><!-- Col 3 -->
					<TH>ตำแหน่ง</TH><!-- Col 4 -->
					<TH width="100">เลขบัตรประฃาชน</TH><!-- Col 5 -->
					<TH>วันเกิด</TH><!-- Col 6 -->
					<TH>แผนก</TH><!-- Col 7 -->
					<TH>ฝ่าย</TH><!-- Col 8 -->
					<TH>เบอร์ภายใน</TH><!-- Col 9 -->
					<TH>เบอร์ตรง</TH><!-- Col 10 -->
					<TH>เบอร์มือถือ</TH><!-- Col 11 -->
					<TH>E-mail</TH><!-- Col 12 -->
					<TH>วันที่เริ่มงาน</TH><!-- Col 13 -->
					<TH>วันที่ลาออก</TH><!-- Col 13 -->
				</TR>
				
	     
		<?php
		
		
	}
	function Show_Member_In_Table_Blank(){
		?>
		<table name = 'tb1' id = 'tb1' width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0" >
				<TR style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" >
					<TH>ลำดับที่</TH><!-- Col 1 -->
					<TH>ชื่อ-นามสกุล</TH><!-- Col 2 -->
					<TH>ตำแหน่ง</TH><!-- Col 3 -->
					<TH>เลขบัตรประฃาชน</TH><!-- Col 4 -->
					<TH>วันเกิด</TH><!-- Col 5 -->
					<TH>แผนก</TH><!-- Col 6 -->
					<TH>ฝ่าย</TH><!-- Col 7 -->
					<TH>บริษัท</TH><!-- Col 8 -->
					<TH>เบอร์ภายใน</TH><!-- Col 9 -->
					<TH>เบอร์ตรง</TH><!-- Col 10 -->
					<TH>เบอร์มือถือ</TH><!-- Col 11 -->
					<TH>E-mail</TH><!-- Col 12 -->
					<TH>วันที่เริ่มงาน</TH><!-- Col 13 -->
				</TR>
				<TR>
					<TD align="center" colspan="13">-- ไม่พบข้อมูล --</TD>
				</TR>
	     </table>  	
		<?php
	}
	function Show_Member_In_Table($Rcd_Set_Input){
		
		?>
			<table name = 'tb1' id = 'tb1' width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0" >
				<TR style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" >
					<TH>ลำดับที่</TH><!-- Col 1 -->
					<TH>ชื่อ-นามสกุล</TH><!-- Col 2 -->
					<TH>ตำแหน่ง</TH><!-- Col 3 -->
					<TH>เลขบัตรประฃาชน</TH><!-- Col 4 -->
					<TH>วันเกิด</TH><!-- Col 5 -->
					<TH>แผนก</TH><!-- Col 6 -->
					<TH>ฝ่าย</TH><!-- Col 7 -->
					<TH>บริษัท</TH><!-- Col 8 -->
					<TH>เบอร์ภายใน</TH><!-- Col 9 -->
					<TH>เบอร์ตรง</TH><!-- Col 10 -->
					<TH>เบอร์มือถือ</TH><!-- Col 11 -->
					<TH>E-mail</TH><!-- Col 12 -->
					<TH>วันที่เริ่มงาน</TH><!-- Col 13 -->
				</TR>
				
		<?php
			$Num_Row = pg_num_rows($Rcd_Set_Input);
			for($i=1;$i<=$Num_Row;$i++){
				$Data = pg_fetch_array($Rcd_Set_Input);
				if($i%2==0){
					$Class_Define = " class=\"odd\" ";
				}else{
					$Class_Define = " class=\"even\" ";
				}
				?>
					<TR <?php echo $Class_Define; ?> ><!-- Start Of Row -->
						<TD ALIGN = "CENTER" ><?php echo $i; ?></TD>
						<TD><?php echo $Data["fullNane"]; ?></TD>
						<TD><?php echo $Data["u_pos"]; ?></TD>
						<TD><?php echo $Data["u_idnum"]; ?></TD>
						<TD align="center"><?php echo $Data["u_birthday"]; ?></TD>
						<TD><?php echo $Data["dep_name"]; ?></TD>
						<TD><?php echo $Data["fdep_name"]; ?></TD>
						<TD><?php echo $Data["company"]; ?></TD>
						<TD align="center"><?php echo $Data["u_extens"]; ?></TD>
						<TD align="center"><?php echo $Data["u_direct"]; ?></TD>
						<TD><?php echo $Data["u_tel"]; ?></TD>
						<TD><?php echo $Data["u_email"]; ?></TD>
						<TD align="center"><?php echo $Data["startwork"]; ?></TD>
						
					</TR><!-- End Of Row -->
				<?php		
			}
		?>
			</table>
		<?php
	}	
	function Show_Member_Out_Table_Blank(){
		?>
		<table  name = 'tb1' id = 'tb1' width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0" >
				<TR style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" >
					<TH>ลำดับที่</TH><!-- Col 1 -->
					<TH>ชื่อ-นามสกุล</TH><!-- Col 2 -->
					<TH>ตำแหน่ง</TH><!-- Col 3 -->
					<TH>เลขบัตรประฃาชน</TH><!-- Col 4 -->
					<TH>วันเกิด</TH><!-- Col 5 -->
					<TH>แผนก</TH><!-- Col 6 -->
					<TH>ฝ่าย</TH><!-- Col 7 -->
					<TH>บริษัท</TH><!-- Col 8 -->
					<TH>เบอร์ภายใน</TH><!-- Col 9 -->
					<TH>เบอร์ตรง</TH><!-- Col 10 -->
					<TH>เบอร์มือถือ</TH><!-- Col 11 -->
					<TH>E-mail</TH><!-- Col 12 -->
					<TH>วันที่เริ่มงาน</TH><!-- Col 13 -->
					<TH>วันที่ลาออก</TH><!-- Col 13 -->
				</TR>
				<TR>
					<TD  align= "center" colspan ="14">-- ไม่พบข้อมูล --</TD>
				</TR>
		</table>
		<?php
	}
	function Show_Member_Out_Table($Rcd_Set_Input){
		
		?>
		<table  name = 'tb1' id = 'tb1' width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0" >
				<TR style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" >
					<TH>ลำดับที่</TH><!-- Col 1 -->
					<TH>ชื่อ-นามสกุล</TH><!-- Col 2 -->
					<TH>ตำแหน่ง</TH><!-- Col 3 -->
					<TH>เลขบัตรประฃาชน</TH><!-- Col 4 -->
					<TH>วันเกิด</TH><!-- Col 5 -->
					<TH>แผนก</TH><!-- Col 6 -->
					<TH>ฝ่าย</TH><!-- Col 7 -->
					<TH>บริษัท</TH><!-- Col 8 -->
					<TH>เบอร์ภายใน</TH><!-- Col 9 -->
					<TH>เบอร์ตรง</TH><!-- Col 10 -->
					<TH>เบอร์มือถือ</TH><!-- Col 11 -->
					<TH>E-mail</TH><!-- Col 12 -->
					<TH>วันที่เริ่มงาน</TH><!-- Col 13 -->
					<TH>วันที่ลาออก</TH><!-- Col 13 -->
				</TR>
		<?php
		$Num_Row = pg_num_rows($Rcd_Set_Input); 
		for($i=1;$i<=$Num_Row;$i++){
			$Data = pg_fetch_array($Rcd_Set_Input);
			if($i%2==0){
				$Class_Define = " class=\"odd\" ";
			}else{
				$Class_Define = " class=\"even\" ";
			}
			?>
				<TR <?php echo $Class_Define; ?> ><!-- Start Of Row -->
					<TD align="CENTER"><?php echo $i;  ?></TD>
					<TD><?php echo $Data["fullNane"]; ?></TD>
					<TD><?php echo $Data["u_pos"]; ?></TD>
					<TD><?php echo $Data["u_idnum"]; ?></TD>
					<TD align="center"><?php echo $Data["u_birthday"]; ?></TD>
					<TD><?php echo $Data["dep_name"]; ?></TD>
					<TD><?php echo $Data["fdep_name"]; ?></TD>
					<TD><?php echo $Data["company"]; ?></TD>
					<TD align="center"><?php echo $Data["u_extens"]; ?></TD>
					<TD align="center"><?php echo $Data["u_direct"]; ?></TD>
					<TD><?php echo $Data["u_tel"]; ?></TD>
					<TD><?php echo $Data["u_email"]; ?></TD>
					<TD align="center"><?php echo $Data["startwork"]; ?></TD>
					<TD align="center"><?php echo $Data["resign_date"]; ?></TD>
				</TR><!-- End Of Row -->	
			
			
			<?php
		}
		?>
		</table>
		<?php
	}
	
?>	

<script> 
	function chk_chkbox_all_select(Var_In){
		var obj_all = document.getElementById('company[999]');
		if(obj_all.checked){
			for(i=0;i<Var_In;i++){
				var Chk_Idx = 'company['+i+']';
				document.getElementById(Chk_Idx).checked = true;
			}
		}else{
		}
	}
	function chk_chkbox_company_select(id){
		var obj_chk = document.getElementById(id);
		if(obj_chk.checked==true){
		}else{
			document.getElementById('company[999]').checked = false;
		}
	}
	function chk_company_select(){
		alert("function chk_company_select");
		var objCheck = document.getElementsByName('company_select');
		var company_selected = "";
		alert('long is '+objCheck.length);
		alert('xxx');
		for(i=0;i<objCheck.length;i++){
			if(objCheck[i].checked){
				company_selected += objCheck[i].value+' ';
			}
		}
		alert('บริษัทที่เลือก คือ '+company_selected);
	}
	function Chk_Input_Data(){
		var Err_Msg = '';
		/*	Check Month Select  */	
		var Month_Sel = document.InPutCnd.Month.value;
		if(Month_Sel=='-'){
			Err_Msg = Err_Msg + 'กรุณาเลือกเดือนที่ต้องการ \n '; 
		}else{}
	
		var Year_Sel = document.InPutCnd.Year.value; 
		if(Year_Sel=='-'){
			Err_Msg = Err_Msg + 'กรุณาเลือกปีที่ต้องการ \n';
		}
			Err_Msg = Err_Msg + chk_company_select();
		if(Err_Msg ==''){
			document.InPutCnd.Comand.value = "Show_Report";
			return true;
		}else{
			alert(Err_Msg);
			return false;
		}
	}
	
	function Set_Command_For_Show_Report(){
		document.InPutCnd.Comand.value = "Show_Report";
	}
	
</script>


<fieldset><legend><b>รายงานพนักงานเข้า-ออกประจำเดือน</b></legend>
	<FORM Name = "InPutCnd" id = "InPutCnd" Method = "post" >
	<?php 
		$Cmd_Input = pg_escape_string($_POST['Comand']); 
			echo "บริษัท "; show_company_name_for_select($Cmd_Input); ?>
		เดือน :<?php Month_For_Select($Month_Receive); ?>		
		ปี : <?php Year_For_Select($Year_Receive); ?>
		<input type = "hidden" name = "Comand" id = "Comand" Value = "-">
		<input type=submit value= "ค้นหา" onclick="Set_Command_For_Show_Report()">
		<!-- <input type=text value="" id="com_select_list" name = "com_select_list" > --> 
	</FORM>
</fieldset>	
<?php 
// แสดงข้อมูลรายงาน	
	if($Command_Process == "Show_Report"){
		$Month_Range = pg_escape_string($_POST["Month"]);
		$Year_Range = pg_escape_string($_POST["Year"]);		
		$Com_Idx = pg_escape_string($_POST["Com_Sel"]);
		$Month_Arr = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"); 
		Display_Member_In_And_Out($Com_Idx,$Month_Range, $Year_Range);
		 
 	}
?>