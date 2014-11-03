<?php 
	function get_Login_Full_Name_By_Login_ID($Id)
	{
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
	
	function Input_Contract_ID_From_User($Name)
	{
		?>	
			<input type="text" name="<?php echo $Name; ?>" id = "<?php echo $Name; ?>"  />
		<?php
		
	}
		
	function Load_Law_Legend_for_select($Html_Name){
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
			<select name = <?php echo $Html_Name; ?> >
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
	
	function Load_Contract_Type_For_Select($Name){
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
	
	function show_doc_msg($Str_Show,$Font_Size){
	// แสดงข้อความตรงกลางในเอกสาร	
		?>
		<DIV align="center" style="font-size: <?php echo $Font_Size;?>">
			<?php
				echo $Str_Show;
			?>			
		</DIV>
		<?php
		
	}
	
	
	
	
	function show_Line_Link_To_Check_Document($Txt_Doc,$File_Name)
	{   
		?>  
			<a onclick="javascript:popU('<?php echo $File_Name; ?>'
										,''
						 	 			,'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" 
						style="cursor:pointer;"> 
							<font color="blue">
								<U>	
									<?php 
										echo $Txt_Doc; 
									?>
								</U>
							<font>
			</a>
		<?php	
	}
	
?>
<script>
	function Chk_Input_Data(Contract_Type,ID_Input)
	{	
		alert('Chk_Input_Data');
		return false;
		var Contract_Chk = document.getElementById(Contract_Type);
		var Input_Chk = document.getElementById(ID_Input);
		$Err_Msg ="";
		alert(Contract_Chk.value);
		
		if(Contract_Chk.value =="-"){
			$Err_Msg = 'กรุณาเลือกประเภทสัญญา'.'\n';
		}
		
		return false;
		
		
		
	}
	function popU(U,N,T) {
    	newWindow = window.open(U, N, T);
	}
</script>