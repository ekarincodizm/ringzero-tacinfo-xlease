<?php 
include("../../config/config.php");
include("../../nw/function/load_date_table_ thcap_temp_money_hold_secure.php");

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
				<td colspan="3" align="right">
				<!-- <input type="hidden" name="val" value="1"/> -->
				<input type="button" id="Search"  value="ค้นหา" />
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

$("#s_voucher").autocomplete({
        source: "voucher_autocomplete.php",
        minLength:1
});

$("#Search").click(function(){
	var money_type = $("input[name=search_type]:checked").val();
    var date_sel;	
	var date_sel = $("#date_sel").val();
	var searchValue = $("input[name=search_type]:checked").val();
	
	
	$("#list_tmp_money_hold_secure").load("list_tmp_money_hold_secure.php",{
			p_money_type:money_type,
			p_date_sel:date_sel,
			p_searchValue:searchValue,
			
			});
	
	// ตรวจสอบว่า ในส่วนเงื่อนไขรอง มีการเลือกที่จะ  ค้นหาตามรายละเอียดหรือไม่
	if($("#chk_s_detail").is(':checked')){
		chk_sel_detail = "on";
	}else{
		chk_sel_detail = "off";
	}
	
	
	if($("#chk_voucher_purpose").is(':checked')){
		chk_sel_purpose = "on";
	}else{
		chk_sel_purpose = "off";
	}
	
	
	
	// ตรวจสอบว่า เลือกค้นหาตามรายละเอียดในเงื่อนไขรองหรือไม่ 
	
	if(chk == 0){
		$("#list_voucher").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$("#list_voucher").load("list_voucher.php",{
			txt_voucher:tv,
			s_date:date1,
			s_month:month,
			s_year:year,
			s_datefrom:datefrom,
			s_dateto:dateto,
			s_sel_year:sel_year,
			s_value:searchValue,
			s_cancel:cancel,
			s_detail:detail,
			s_purpose_idx:purpose_idx,
			s_chk_detail:chk_sel_detail,
			s_chk_purpose:chk_sel_purpose
			});
	}else{
		alert(errorMessage);
		return false;
	}
});
$("input[name=search_type]").change(function(){
	$("#s_voucher").val('');
	$("#datepicker").val('');
	$("#month").val('');
	$("#datefrom").val('');
	$("#dateto").val('');
	$("#s_detail").val('');
	$("#sel_year").val('<?php echo $v_year ?>');
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>