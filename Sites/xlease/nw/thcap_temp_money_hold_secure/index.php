<?php 
include("../../config/config.php");
include("../../nw/function/load_date_table_ thcap_temp_money_hold_secure.php");
include("../../nw/function/emplevel.php");

$user_id = $_SESSION["av_iduser"]; // รหัสพนักงาน
$emplevel = emplevel($user_id); // ระดับพนักงาน
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<div style="margin-top:10px;" align="center"><h1>(THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ</h1></div>
	<div style="margin-top:10px; width:60%;margin-left:auto;margin-right:auto;">
	<fieldset><legend>เงือนไขการค้นหา</legend>
		<table align="center" cellspacing="10px">
			
			<tr>
				<td ALIGN  = "RIGHT"><input type="radio" id="money_hold" name="search_type"  value="998" /></td>
				<td><b>เงินพัก</b></td>
			</tr>
			<tr>
				<td ALIGN  = "RIGHT" >
					<input type="radio" id="money_secure" name="search_type"  value="997"  checked />
				</td>
				<td><b>เงินค้ำประกัน</b></td>
			</tr>
			<tr>
				<td ALIGN  = "RIGHT" >
					<input type="radio" id="money_deposits" name="search_type" value="deposits" />
				</td>
				<td><b>เงินมัดจำ</b></td>
			</tr>
			<tr>
				<td ALIGN  = "RIGHT">
					<b>วันที่ข้อมูล</b>
				</td>
				<?php  
					     $query_x = load_dataDate_from_thcap_temp_money_hold_secure();
						 $num_row = pg_num_rows($query_x); // echo 'New No. Of Row Is'.$num_row;
                        
				?>
				
				<td>
					<select name="date_sel" id="date_sel"> 
					<?php
					   for($i=0; $i<$num_row; $i++)
					   {
					     $data =  pg_fetch_array($query_x); 
						 ?>
						 <option value="<?php echo $data['dataDate'];?>" ><?php echo $data['dataDate']; ?></option>	
						  
					   <?php
					   }
					
					?>
				
					</select>
				</td>
			</tr>
			
			<!--ตามปี-->
			
			
			<!--จบตามปี-->
			
			<tr>
				<td colspan="2" align="center">
					<input type="button" id="Search"  value="ค้นหา" style="cursor:pointer;" />
					<input type="button" id="btnGen" value="GEN" onClick="popupGen();" style="cursor:pointer;" />
				</td>
		    </tr>
		</table>
	</fieldset>
	</div>
	
	<div id="list_tmp_money_hold_secure" style="margin-top:10px;"></div>
	
	<div id="list_wait_cancel" style="width:80%;margin-top:30px;margin-left:auto;margin-right:auto;">
	   <?php // include("Block_Table_Of_tmp_money_hold_secure.php"); ?>
	</div>
    
</bodY>
</html>

<script>
	var emplevel = '<?php echo $emplevel; ?>'; // ระดับพนักงาน
	var typeGen; // ข้อมูลที่จะ Gen
	
	function checkChoice() // ตรวจสอบตัวเลือก
	{
		if(document.getElementById("money_hold").checked == true && emplevel <= 1) // ถ้าเลือก เงินพัก และระดับพนักงาน น้อยกว่าหรือเท่ากับ 1
		{
			document.getElementById("btnGen").style.visibility = 'visible'; // แสดงปุ่ม GEN ข้อมูล
			typeGen = document.getElementById("money_hold").value;
		}
		else if(document.getElementById("money_secure").checked == true && emplevel <= 1) // ถ้าเลือก เงินค้ำประกัน และระดับพนักงาน น้อยกว่าหรือเท่ากับ 1
		{
			document.getElementById("btnGen").style.visibility = 'visible'; // แสดงปุ่ม GEN ข้อมูล
			typeGen = document.getElementById("money_secure").value;
		}
		else
		{
			document.getElementById("btnGen").style.visibility = 'hidden'; // ซ้อนปุ่ม GEN ข้อมูล
		}
	}
	
	checkChoice(); // เช็คตัวเลือกเมื่อเข้าโปรแกรมครั้งแรก
	
	function popupGen() // เปิด popup เพื่อ Gen ข้อมูล
	{
		popU('popup_gen_money_hold_secure.php?typeGen='+typeGen+'','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=350');
	}
	
	// เมื่อคลิกปุ่มค้นหา
	$("#Search").click(function(){
		var money_type = $("input[name=search_type]:checked").val();
		var date_sel;	
		var date_sel = $("#date_sel").val();
		var searchValue = $("input[name=search_type]:checked").val();
		
		$("#list_tmp_money_hold_secure").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังค้นหา โปรดรอสักครู่...">');
		
		$("#list_tmp_money_hold_secure").load("list_tmp_money_hold_secure.php",{
			p_money_type:money_type,
			p_date_sel:date_sel,
			p_searchValue:searchValue
		});
	});

	// เมื่อเปลี่ยนตัวเลือกที่จะค้นหา
	$("input[name=search_type]").change(function(){
		checkChoice();
	});

	function popU(U,N,T)
	{
		newWindow = window.open(U, N, T);
	}
</script>