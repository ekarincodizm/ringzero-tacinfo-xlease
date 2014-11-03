<?php
	session_start();
	include("../../config/config.php");
	$revtran = pg_escape_string($_GET['revtran']); 
	if(empty($revtran)){
		$revtran = pg_escape_string($_POST['revtran']);
	}
	
	$cur_yr = date("Y");
	$start_year = $cur_yr-10;
	$end_year = $cur_yr;
	if(pg_escape_string($_POST["Comand"]) =="ADD"){
		$Ref_ID = pg_escape_string($_POST["revtran"]); // รับเลขรหัสการโอน
		$Cus_ID = split('#',pg_escape_string($_POST["Cs_Data"])); // รับรหัสลูกค้า,ชื่อลูกค้่า,เลขประจำตัวประฃาฃน
		$N_Contact_Date = pg_escape_string($_POST["Contact_Date"]); // รับวันที่ที่ติดต่อ
		$Time_Contact = pg_escape_string($_POST["Hour"]).':'.pg_escape_string($_POST["Minute"]); // รับเวลาที่ติดต่อ
		$Detail_Save = pg_escape_string($_POST["detail_Save"]); // รับรายละเอียดการติดต่อ
		$doerID_Save = pg_escape_string($_POST["doerID"]); // รับรหัสผู้บันทึกข้อมูล
		// echo "รหัสผู้บันทึกข้อมูล".$doerID_Save;  
		$Time_Result = pg_query("select \"nowDateTime\"()"); 
		$Time_Input = pg_fetch_result($Time_Result,0,0); // เวลาที่ใช้ในการบันทึกข้อมูล
		
		// นำเข้าข้อมูลการติดต่อ
		$Str_Ins = "INSERT INTO finance.thcap_note_transfer";
		$Str_Ins = $Str_Ins."(\"revTranID\", \"CusID\", \"contactDate\", \"contactTime\",\"contactNote\", \"doerID\", \"doerStamp\")";
		$Str_Ins = $Str_Ins."VALUES( '".$Ref_ID."','".$Cus_ID[0]."','".$N_Contact_Date."','".$Time_Contact."','".$Detail_Save."','".$doerID_Save."','".$Time_Input."')";
		pg_query($Str_Ins);
	}
	if(pg_escape_string($_POST["Comand"])=="New_Chk"){
		$F_Customer_Data = pg_escape_string($_POST["Cs_Data"]);
		$F_Contact_Date = pg_escape_string($_POST["Contact_Date"]);
		$F_Detail = pg_escape_string($_POST["detail_Input"]);
		$F_Hrs = pg_escape_string($_POST["Hour"]); 
		$F_Min = pg_escape_string($_POST["Minute"]);
	}   
	
	// Get Full Name ตาม  $_SESSION["user_login"]
	$Str_Qry = " SELECT \"fullname\",\"id_user\" ";
	$Str_Qry = $Str_Qry." from \"Vfuser\" ";
	$Str_Qry = $Str_Qry." Where username = '".$_SESSION["user_login"]."'" ;  
		
	$Result = pg_query($Str_Qry);
	$LogIn_FullName = pg_fetch_result($Result, 0, 0);
	$LogIn_ID = pg_fetch_result($Result, 0, 1);
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['session_company_name']; ?></title>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link type="text/css" rel="stylesheet" href="act.css"></link>
<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	color: #3A3A3A;
}
H1 {
    font-size: 18px;
}
.title {
    text-align: center;
}
.TextTitle{
    color: #006600;
    font-size: 11px;
    font-weight: bold;
}
</style>
<script type="text/javascript">
$(function(){
	var start_yr = '<?php echo $start_year; ?>'; 
	var end_yr = '<?php echo $end_year; ?>'; 
	var dateBefore=null;
	$("#Contact_Date").datepicker({
		
		inline:true,
		showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'

		

	});
	
	// ยกเลิกการแปลงวันที่อัตโนมัติ
	/*$('#BDate').change(function(){
    		$('#BDate').datepicker('setDate', $(this).val());
		});
	*/
});

$(document).ready(function(){

    $("#Cs_Data").autocomplete({
        source: "s_cuscorp.php",
        minLength:1
    });
});
</script>
<script type="text/javascript">
function check_Input_Date(data_In){
	
	var str = data_In;  
	var Date_split = str.split("-");
	var chk = 0; 
	var Err_Msg;
	
	if(str == '')
	{
		chk++;
	}
	else if(str.length != 10)
	{
		chk++;
	}
	else if(str.substring(4, 5) != "-" || str.substring(7, 8) != "-")
	{
		chk++;
	}
	else if(Date_split.length != 3)
	{
		chk++;
	}
	else
	{
		var dtYear = parseInt(Date_split[0]);   
		var dtMonth = parseInt(Date_split[1]); 
		var dtDay = parseInt(Date_split[2]); 
		
		if(isNaN(dtYear) == true){
			chk++;
		}
		if(isNaN(dtMonth) == true){
			chk++;
		}
		if(isNaN(dtDay) == true){
			chk++;
		}
			
		if (dtMonth < 1 || dtMonth > 12){
			chk++;
		}else if (dtDay < 1 || dtDay> 31) {
			chk++;
		}else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) {
			chk++;
		} else if (dtMonth == 2) {
			var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			if (dtDay> 29 || (dtDay ==29 && !isleap)) 
            chk++;
		}
	}
    
	if(chk>0){ 
		Err_Msg = '-> กรุณาระบุ \"วันที่ติดต่อ\" ให้ถูกต้อง \n';
	}else{
		Err_Msg ='';
	}
	
	return(Err_Msg);
}// End Of function check_Input_Date(data_In)

function Chk_Input_Data(){
	var Err_Msg = '';
	var Err_Fnd = false;
	var Can_Save = false;
/*	Check Customer Data Input  */	
	var customer = document.frm_Input.Cs_Data.value;
	if(customer==''){
		Err_Msg = "-> กรุณาระบุ  \"ข้อมูลลูกค้า\"   \n";
		Err_Fnd = true;
	}else{
	}
	
	var Err_Date = '';
	var Date_Chk = document.frm_Input.Contact_Date.value;
	Err_Date = check_Input_Date(Date_Chk);  
	if(Err_Date!=''){
		Err_Msg = Err_Msg + Err_Date;
		Err_Fnd = true;
	}else{
		Err_Fnd = false;
	}
/* Check เวลาที่ติดต่อ เป็น ชั่วโมง*/	
	var Hr_Chk = document.frm_Input.Hour.value;
	if(Hr_Chk=='-'){
		Err_Msg = Err_Msg + "-> กรุณาเลิอก \"ชั่วโมง\" ที่ติดต่อ \n";
		Err_Fnd = true;	
	}
    
/* Check นาทีที่ติดต่อ */	
	var Min_Chk = document.frm_Input.Minute.value;
	if(Min_Chk=='-'){
		Err_Msg = Err_Msg + "-> กรุณาเลิอก \"นาที\" ที่ติดต่อ \n";
		Err_Fnd = true;	
	}
	

/* Check การกรอกรายละเอียดการติดต่อ */	
	var Detail_Chk = document.frm_Input.detail_Input.value;
	var n = Detail_Chk.length;
	if(Detail_Chk==''){
		Err_Msg = Err_Msg + "-> กรุณากรอก รายละเอียดการติดต่อ \n";
		Err_Fnd = true;
	}else{
	}
	
	
	
	if(Err_Fnd==true){
		alert(Err_Msg);
		Can_Save = false;
	}else{	
		if(confirm("ต้องการบันทึกข้อมูล  ")){
			Can_Save = true;
			document.frm_Input.Comand.value="ADD";
			document.frm_Input.detail_Save.value =  document.frm_Input.detail_Input.value;
		}else{
			Can_Save = false;
		}
	}
	return(Can_Save);
	
}
</script>
<?php 
	function get_current_hour()
	{
		$Str_Now_Time = "SELECT \"nowDateTime\"() ";
		$Result_Now_Time = pg_query($Str_Now_Time);
		$Data = pg_fetch_result($Result_Now_Time, 0, 0);
		$Tmp_Var = split(" ",$Data);
		$Tmp_Var = split(":",$Tmp_Var[1]);
		return($Tmp_Var[0]);
	}
 	function List_Hour_For_Select($F_Hrs)
  	{
  		    
  		if(($F_Hrs=="-")){
  			//$time_now = getdate($time); 
			//$Hour = get_current_hour();
		}else{
			$Hour = $F_Hrs;
		}	
		
	?>
  		<select Name = Hour>
  				<?php
  					echo "<option value=\"-\" Selected = true>--เลือก--</option>";
  					for($i=0;$i<24;$i++){
  						$data_show = "$i";
						if($i<10){
							$data_show="0".$data_show;
						}
						
						if($data_show == $F_Hrs){$chk_select_H = "selected";}else{$chk_select_H = "";}
						
						echo "<option value=\"$i\" $chk_select_H >$data_show</option>";
							
					}// End Of for Loop	
  				?>	
		</select>
  <?php
  }
  function List_Minute_For_Select($F_Min)
  { 
  		if($F_Min==""){
  			$Min = -1;
   		}else{
			$Min = $F_Min;
		}	
  	?>
  		<select Name = Minute>
  			
  				<?php
  					echo "<option value=\"-\" Selected = true>--เลือก--</option>";
  					for($i=0;$i<60;$i++){
						$data_show = "$i";
						if($i<10){
							$data_show="0".$data_show;
						}
						
						if($data_show == $F_Min){$chk_select_M = "selected";}else{$chk_select_M = "";}
						
						echo "<option value=\"$i\" $chk_select_M >$data_show</option>";	
					}	
  				?>
  				
		</select> 	
  	
  	
  	
  	<?php
  }
?>
</head>

<body>

	<div class="title_top">บันทึกการโอน
    	<TABLE bgcolor="#FFFFCC">
    		<TR>
    			<TD ALITGN = Left>
    				<?php echo "รหัสการโอนเงิน : ".$revtran;  ?>
    			</TD>
    		</TR>
    	</TABLE>
    </div>
    
    <div id = part_input>
    	<Form method="POST" name = "frm_Input" id = "frm_Input" action = "Money_transfers_Note_Add.php" >
    		<TABLE>
    			<TR>
    				<TD align="RIGHT">		
    					ข้อมูลลูกค้า : 
    				</TD>
    				<TD>
    					<input type="text" name="Cs_Data"  id = "Cs_Data"  size="40" value = "<?php echo $F_Customer_Data; ?>"/>
    				</TD>
    			</TR>
    			<TR>
    				<TD align="RIGHT">
    					วันที่ติดค่อ  :
    				</TD>
    				<TD>
    					<input type="text" size="12" style="text-align:center;" id="Contact_Date" name="Contact_Date" value="<?php echo $F_Contact_Date; ?>" />
    					Ex.2014-12-31(ค.ศ.)
    				</TD>
    			</TR>
                <TR>
                	<TD align="RIGHT">
                		เวลาที่ติดต่อ: 
                	</TD>
                	<TD>
                		<?php List_Hour_For_Select($F_Hrs); echo "(ช.ม.)"; List_Minute_For_Select($F_Min); echo "(นาที)"; ?>
                	</TD>
                	
                </TR>    			
    		    <TR>
    		    	<TD align="RIGHT">
    		    		รายละเอียดการติดต่อ :  
    		    	</TD>
    		    	<TD>
    		    		<textarea rows="4" cols="50" id = detail_Input><?php echo $F_Detail; ?></textarea> 
    		    		<input type="Hidden" SIZE="100" id = detail_Save name = detail_Save value ="Before Input Detail"> 
    		    	</TD>
    		    </TR> 
    		    <TR>
    		    	<TD>
    		    		
    		    	</TD>
    		    	<TD>
    		    		<input type="Hidden" name="Emp_Memo" id = "Emp_Memo" size ="40" value ="<?php echo $LogIn_ID.'#'.$_SESSION["user_login"].'#'.$LogIn_FullName; ?>" ReadOnly/>
    		    		<input type="Hidden" name="doerID" id = "doerID" size = "10" value = "<?php echo $LogIn_ID; ?>" ReadOnly> 
    		    		<input type="Hidden" name="Comand" id = "Comand" size = "25" >  
    		    	</TD>
    		    	
    		    </TR>
               <TR>  		
            	<TD  colspan='2' ALIGN = 'CENTER'>             		
    					
			
			  	
			<!-- <input type = "hidden" id = "frm_cmd" name = "frm_cmd" id = "frm_cmd" value="Save"> -->
			<input type = "hidden" id = "revtran" name = "revtran" id = "frm_cmd" value="<?php echo $revtran; ?>">				
    		<input type = "submit" value = "บันทึก" onclick="return Chk_Input_Data()">
    		<input type = "reset"  value = "ยกเลิก">
    			</TD>
    		</TR>
    		</TABLE>
    	</form>	
    	
    </div>
    <!-- Part Of Show List Contact Note -->
    <div style="background-color: #F8F8FF">
    	<fieldset><legend><b>ข้อมูลที่ติดต่อ</b></legend>
    		<?php
    			// echo $revtran; 
				$Str_Query = " SELECT \"doerID\",\"contactDate\",\"CusID\",\"contactTime\",\"contactNote\" FROM  finance.\"thcap_note_transfer\"  "; 
				$Str_Query = $Str_Query. " WHERE \"revTranID\" = '".$revtran."'";
				$Str_Query = $Str_Query. " Order By \"contactDate\" DESC,\"contactTime\" DESC ";
				// echo $Str_Query;
				$Result_Show = pg_query($Str_Query);
				$Num_Row = pg_num_rows($Result_Show);
				// echo 'No. Of Row :'.$Num_Row; 
				if($Num_Row==0){
					echo "ไม่มีประวัติการติดต่อ";	
				}else{
					echo "<TABLE BORDER = 0 WIDTH = 100%>";
					for($i=1;$i<=$Num_Row;$i++){
						$Color_A = '#E3F6CE';  $Color_B ='#CEF6F5'; 
						if(($i%2)==0){
							$Row_Bkg = $Color_A; 
						}else{
							$Row_Bkg = $Color_B;
						}
						$Data_Show = pg_fetch_array($Result_Show);
						// echo $i;
						//print_r($Data_Show);
						// ดึงชื่อผู้บันทึกข้อมูล
						$Str_Qry = " SELECT \"fullname\" ";
						$Str_Qry = $Str_Qry." from \"Vfuser\" ";
						$Str_Qry = $Str_Qry." Where id_user = '".$Data_Show["doerID"]."'" ;
						// echo "<BR>".$Str_Qry."<BR>";
						$Result_Full_Name = pg_query($Str_Qry);
						$Data_Full_Name = pg_fetch_result($Result_Full_Name, 0, 0);
						// echo '***'.$Data_Full_Name.'####';
						// ดึงฃื่อลูกค้า 
						$Str_Qry = "SELECT \"full_name\" ";
						$Str_Qry = $Str_Qry . " FROM \"VSearchCusCorp\" ";
						$Str_Qry = $Str_Qry . " WHERE \"CusID\" = '".$Data_Show["CusID"]."'";
						$Result_Cust_Name = pg_query($Str_Qry);
						$Data_Cust_Name = pg_fetch_result($Result_Cust_Name, 0, 0);
						echo "<TR BGCOLOR = #F7F2E0 >";// Start Row No. 1
						echo "<TD ALIGN = 'RIGHT' WIDTH = 21%><B> ชื่อผู้ทำรายการ : </B>";
						echo "</TD>";
						echo "<TD ALIGN = 'LEFT' WIDTH = 30%>".$Data_Full_Name;
						echo "</TD>";
						echo "<TD ALIGN = 'RIGHT' WIDTH = 29%><B> วันที่ เจรจา : </B>".$Data_Show["contactDate"];
						echo "</TD>";
						echo "</TR>";// End Row No. 1
						
						echo "<TR BGCOLOR = ".$Row_Bkg." >";// Start Row No. 2
						echo "<TD ALIGN = 'RIGHT' ><B> ชื่อลูกค้าที่ติดต่อ : </B> ";
						echo "</TD>";
						echo "<TD colspan = \"2\" >".$Data_Cust_Name." (".$Data_Show["CusID"].")";
						echo "</TD>";
						echo "</TR>";// End Row No. 2 
						
						echo "<TR BGCOLOR = ".$Row_Bkg.">";// Strart Row No. 3
						echo "<TD ALIGN = 'RIGHT'>";
						echo "<B> เวลาที่ติดต่อ :</B>";
						echo "</TD>";
						echo "<TD colspan = \"2\">".$Data_Show["contactTime"];
						echo "</TD>";
						echo "</TR>";// End Row No. 3
						
						echo "<TR BGCOLOR = ".$Row_Bkg.">"; //Start Row No. 4
						echo "<TD ALIGN = 'RIGHT' >";
						//echo "รายละเอียดการติดต่อ :".$Data_Show["contactNote"];
						$Detail_Show = str_replace('\\r\\n','<BR>',$Data_Show["contactNote"]);
						echo "<B>รายละเอียดการติดต่อ :</B> ";  
						echo "</TD>";
						echo "<TD COLSPAN = \"2\">".$Detail_Show;
						echo "</TD>";
						echo "</TR>"; // End Row No. 4
						echo "<TR><TD colspan = \"3\"><BR><BR></TD></TR>";
						
					}
					echo "</TABLE>";
				}
    		?>
    	</fieldset>
    </div>
	
