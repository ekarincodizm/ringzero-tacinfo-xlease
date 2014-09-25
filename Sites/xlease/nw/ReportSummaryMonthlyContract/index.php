<?php
	session_start();
	include("../../config/config.php");
?>
<TITLE>(THCAP) รายงานสรุปสัญญาประจำเดือน</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<?php	
	$Command_Process = pg_escape_string($_POST["Comand"]);
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
	function Month_For_Select(){
		$Month_Arr = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$Cur_Month = get_current_Month();
		$Cur_Month = (int)$Cur_Month; 
		?>
			<select name="Month" id = "Month"> 
				<?php
					for($i=1;$i<=12;$i++)
					{   echo $i.'te';
						if($i==$Cur_Month){
							$Select_Status = "Selected";
						}else{
							$Select_Status = "";
						}
						?>	
						<option value = <?php if($i<10){echo "0".$i;}else{ echo $i; } echo ' '.$Select_Status; ?> ><?php echo $Month_Arr[$i-1]; ?></option>	
					<?php
						
					}		
				 ?>
			 </select>
		<?php
	}	
	function Year_For_Select(){
		$Start_Yr = 2010;
		$End_Yr = get_current_year()+1;
		?>
			<select name = "Year" id = "Year">
				<?php
					for($i=$End_Yr;$i>=$Start_Yr;$i--)
					{
						if($i == ($End_Yr-1)){
							$Select_Status = "Selected";
						}else{
							$Select_Status = "";
						}
					?>
						<option value = <?php echo $i.' '.$Select_Status; ?> >
							<?php	echo $i; ?>											
						</option>
					<?php		
					}		
				?>
			</select>
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
						<TD><?php echo $Data["u_birthday"]; ?></TD>
						<TD><?php echo $Data["dep_name"]; ?></TD>
						<TD><?php echo $Data["fdep_name"]; ?></TD>
						<TD><?php echo $Data["company"]; ?></TD>
						<TD><?php echo $Data["u_extens"]; ?></TD>
						<TD><?php echo $Data["u_direct"]; ?></TD>
						<TD><?php echo $Data["u_tel"]; ?></TD>
						<TD><?php echo $Data["u_email"]; ?></TD>
						<TD><?php echo $Data["startwork"]; ?></TD>
						
					</TR><!-- End Of Row -->
				<?php		
			}
		?>
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
					<TD><?php echo $Data["u_birthday"]; ?></TD>
					<TD><?php echo $Data["dep_name"]; ?></TD>
					<TD><?php echo $Data["fdep_name"]; ?></TD>
					<TD><?php echo $Data["company"]; ?></TD>
					<TD><?php echo $Data["u_extens"]; ?></TD>
					<TD><?php echo $Data["u_direct"]; ?></TD>
					<TD><?php echo $Data["u_tel"]; ?></TD>
					<TD><?php echo $Data["u_email"]; ?></TD>
					<TD><?php echo $Data["startwork"]; ?></TD>
					<TD><?php echo $Data["resign_date"]; ?></TD>
				</TR><!-- End Of Row -->	
			
			
			<?php
		}
		?>
		</table>
		<?php
	}
	function List_Contract_For_Check(){
		echo "ประเภทสัญญา : ";
		$Str_Get_Contract_Type = " SELECT \"conType\"  FROM thcap_contract_type ";
		$List_Contract_Type = pg_query($Str_Get_Contract_Type); 
		$i = 0;
		while($loop_typeContract = pg_fetch_array($List_Contract_Type)){
			// 
			?>
			<input type="checkbox" name="Contract_Check" id = "contract[<?php echo $i; ?>]" value="<?php echo $loop_typeContract["conType"];  ?>"> 
			<?php
			echo $loop_typeContract["conType"]." ";
			$i++;
		}	
	}
?>	

<script>
	function  chk_contract_select(){
		var objCheck = document.getElementsByName('Contract_Check');
		var Contract_Selected = "";
		for (i = 0; i < objCheck.length; i++) {
    		if (objCheck[i].checked){
    			Contract_Selected += objCheck[i].value+' ' ;
      		}
  		}
  		return(Contract_Selected);
	}
	function Chk_Input_Data(){
		var Err_Msg = '';
		$Ret_Chk = chk_contract_select();
		if($Ret_Chk ==''){
			Err_Msg = Err_Msg + 'กรุณาเลือกประเภทสัญญา \n';
		}else{
			document.getElementById("List_Contract_Chk").value = $Ret_Chk;
		}
		/*	Check Month Select  */	
		var Month_Sel = document.InPutCnd.Month.value;
		if(Month_Sel=='-'){
			Err_Msg = Err_Msg + 'กรุณาเลือกเดือนที่ต้องการ \n '; 
		}else{}
	
		var Year_Sel = document.InPutCnd.Year.value; 
		if(Year_Sel=='-'){
			Err_Msg = Err_Msg + 'กรุณาเลือกปีที่ต้องการ \n';
		}
	
		if(Err_Msg ==''){
			document.InPutCnd.Comand.value = "Show_Report";
			// return true;
			var month = $("#Month").val();
			var year = $("#Year").val();
			var contract_list = $("#List_Contract_Chk").val(); 
			$("#list_contract_show").load("Show_Contract.php",{
				s_month:month,
				s_year:year,
				s_contract_list:contract_list 
			});
		}else{
			alert(Err_Msg);
			return false;
		}
	}
	
</script>

<script>
	function test() {
	  alert('submit click');
	}
</script>		
<center>
<h1>(THCAP) รายงานสรุปสัญญาประจำเดือน</h1>
</center>

<fieldset><legend><b>เงื่่อนไข</b></legend>
	<FORM Name = "InPutCnd" id = "InPutCnd" Method = "post" action = "aaa.php" >
		<?php List_Contract_For_Check(); ?>
		เดือน :<?php Month_For_Select(); ?>		
		ปี : <?php Year_For_Select(); ?>
		<input type = "hidden" name = "Comand" id = "Comand" Value = "-">
		<input type = "hidden" name = "List_Contract_Chk" id = "List_Contract_Chk"  Size = 50 Value = "-">
		<input type= "button" value= "แสดงรายงาน"  name = "sbt_id" id = "sbt_id" onclick="Chk_Input_Data();"><!-- onclick="return Chk_Input_Data()" -->
	</FORM>
</fieldset>	
<div id="list_contract_show" style="margin-top:10px;">
	
</div>
