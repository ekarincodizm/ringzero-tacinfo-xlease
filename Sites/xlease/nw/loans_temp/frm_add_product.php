<?php
include('../../config/config.php');

$contract = $_GET["contract"]; // ประเภทสินเชื่อ
$qry_cusid = pg_query("select \"CusID\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contract' and \"CusState\" = '0' ");
list($cus_id) = pg_fetch_array($qry_cusid);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>เพิ่มสินค้า</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act_home_index2.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
$(document).ready(function(){
	$('#pick_receipt1').autocomplete({
		source: "listreceipt.php",
        minLength:1
	});
	$('#pick_receipt2').autocomplete({
		source: "listreceipt.php",
        minLength:1
	});
	$('#pick_receipt3').autocomplete({
		source: "listreceipt.php",
        minLength:1
	});
});	


function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function pick_Order(){
	var state = $('#pickOrder1').is(':checked');
	
	$('.hide').hide();
	
	if(state==true)
	{
		$('input[name="pickOrdertype"]').removeAttr('checked');
		$('#tr_add_rct').show();
		$('input[name="pick_receipt2"]').val('');
	}
	else
	{
		$('#pick_receipt1').val('');
		$('#tr_pick_type').show();
	}
}

function pick_type() {
	var state = $('#pickOrdertype1').is(':checked');
	
	$('.sub_hide').hide();
	
	if(state==true)
	{
		$('#tr_find_rct1').show();
		$('input[name="pick_receipt2"]').val('');
	}
	else
	{
		$('#tr_find_rct2').show();
		$('input[name="pick_receipt2"]').val('');
	}
}
function fct_add_receipt(){
	var rcid_full = $('#pick_receipt1').val().split('#');
	var rcID = rcid_full[0];
	var picked_itm = $('input[name="all_pick_itm[]"]');
	var all_picked_itm = $(picked_itm).length;
	var picked_val = '';
	var chk_itm = 0;
	var start_n = 0;
	if(rcID=='')
	{
		alert('กรุณาเลือกใบเสร็จก่อนครับ');
	}
	else
	{
		while(start_n<all_picked_itm)
		{
			picked_val = $(picked_itm[start_n]).val();
			picked_arr_val = picked_val.split(',');
			if(picked_arr_val[0]==rcID)
			{
				chk_itm++;
			}
			start_n++;
		}
		if(chk_itm==0)
		{
			$.post('genOrder.php',{rcid:rcID,type:'all'},function(data){
				if(data!='0')
				{
					$('#pick').show();
					$('#show_pick').append(data);
					$('.currentaddr').show();
				}
				else
				{
					alert('ไม่พบรายการสินค้าในใบเสร็จที่ระบุ');
				}
			});
		}
		else
		{
			alert('ไม่สามารถใช้ใบเสร็จนี้ได้  เนื่องจากคุณได้เลือกรายการสินค้าในใบเสร็จนี้แล้ว');
		}
	}
}
function remove_select_itm() {
	var sum_all = $('input[name="all_pick_itm[]"]:checked').length;
	if(sum_all<1)
	{
		alert('กรุณาเลือกรายการที่ต้องการลบก่อนครับ');
	}
	else
	{
		$('input[name="all_pick_itm[]"]:checked').parent().parent().remove();
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function list_select_item(grp){
	var rcID = "";
	var doc_h = $(document).height();
	var doc_w = $(document).width();
	var scrll_t = $(window).scrollTop();
	
	var popup_w = 800;
	
	var popup_pst_y = scrll_t+50;
	var popup_pst_x = (doc_w/2)-(popup_w/2);
	
	//for find dup rows
	var order;
	var all_order = 0;
	var run_order = 0;
	var order_val = '';
	
	$('.popup_pick_order').css('margin-top',popup_pst_y);
	$('.popup_pick_order').css('margin-left',popup_pst_x);
	
	$('.overlay2').css('height',doc_h);
	
	if(grp==1)
	{
		var rcid_full = $('#pick_receipt2').val().split('#');
		rcID = rcid_full[0];
		if(rcID=="")
		{
			alert('กรุณาระบุหมายเลขใบเสร็จก่อนครับ');
		}
		else
		{
			$.post('genOrder.php',{rcid:rcID,type:'each'},function(data){
				if(data!='0')
				{
					$('#show_list').html(data);
					
					//remove dup order
					order = $('#show_list').find('input[name="each_pick_itm[]"]');
					all_order = $(order).length;
					while(run_order<all_order)
					{
						order_val = $(order[run_order]).val();
						
						var picked_order = $('input[name="all_pick_itm[]"]');
						var all_picked_order = $(picked_order).length;
						var run_picked = 0;
						var picked_val = '';
						while(run_picked<all_picked_order)
						{
							picked_val = $(picked_order[run_picked]).val();
							if(order_val==picked_val)
							{
								$(order[run_order]).parent().parent().remove();
								break;
							}
							run_picked++;
						}
						run_order++;
					}
					if($('#show_list').find('input[name="each_pick_itm[]"]').length == 0)
					{
						$('#show_list').html('<span style="font-family:tahoma; font-size:13px; font-weight:bold; color:#ff0000;">คุณได้เลือกรายการสินค้าในใบเสร็จนี้หมดแล้วครับ</span>');
					}
					$('.overlay2').fadeIn(1000);
					doc_h = $(document).height();
					$('.overlay2').css('height',doc_h);
				}
				else
				{
					alert('ไม่พบรายการสินค้าในใบเสร็จที่ระบุ');
				}
			});
		}
	}
	else if(grp==2)
	{
		var rcid_full = $('#pick_receipt3').val().split('#');
		rcID = rcid_full[0];
		if(rcID=="")
		{
			alert('กรุณาระบุหมายเลขใบเสร็จก่อนครับ');
		}
		else
		{
			var all_itm = '';
			var picked_itm = $('input[name="all_pick_itm[]"]');
			var all_picked_itm = $(picked_itm).length;
			var running = 0;
			while(running<all_picked_itm)
			{
				var picked_itm_val = $(picked_itm[running]).val();
				var split_val = picked_itm_val.split(',');
				if(split_val[0]==rcID)
				{
					if(all_itm=='')
					{
						all_itm = split_val[1];
					}
					else
					{
						all_itm+=','+split_val[1];
					}
				}
				running++;
			}
			$.post('genOrder.php',{rcid:rcID,type:'group',allitm:all_itm},function(data){
				$('#show_list').html(data);
				$('.overlay2').fadeIn(1000);
			});
		}
	}
}
function add_select_itm(){
	var grp = $('input[name="pickOrdertype"]:checked').val();
	if(grp=='1')
	{
		var sum_all = $('input[name="each_pick_itm[]"]:checked').length;
		if(sum_all<1)
		{
			alert('กรุณาเลือกรายการที่ต้องการเพิ่มก่อนครับ');
		}
		else
		{
			var all_input = $('input[name="each_pick_itm[]"]:checked');
			all_input.attr('name','all_pick_itm[]');
			all_input.attr('disabled','disabled');
			all_input.parent().parent().append('<div class="delete_row inline"><img src="images/delete.png" width="24" height="24" style="cursor:pointer;" onclick="delete_this_row(this);" /></div>');
			$('.currentaddr').show();
			$('#show_pick').append(all_input.parent().parent());
			$('.overlay2').fadeOut(1000);
		}
	}
	else if(grp=='2')
	{
		var pick_grp = $('input[name="each_pick_grp[]"]:checked');
		var sum_pick_grp = pick_grp.length;
		//alert(sum_pick_grp);
		var pick_itm;
		var brand;
		var model;
		var rcNumber;
		for(var i=0;i<sum_pick_grp;i++)
		{
			pick_itm = $(pick_grp[i]).parent().parent().find('input[name="tbx_pick_itm"]').val();
			if(pick_itm!='')
			{
				if(isNaN(pick_itm)==false)
				{
					brand = $(pick_grp[i]).parent().parent().find('input[name="each_brand"]').val();
					model = $(pick_grp[i]).parent().parent().find('input[name="each_model"]').val();
					rcNumber = $(pick_grp[i]).val();
					
					//for filter dup order
					var all_itm = '';
					var picked_itm = $('input[name="all_pick_itm[]"]');
					var all_picked_itm = $(picked_itm).length;
					var running = 0;
					while(running<all_picked_itm)
					{
						var picked_itm_val = $(picked_itm[running]).val();
						var split_val = picked_itm_val.split(',');
						if(split_val[0]==rcNumber)
						{
							if(all_itm=='')
							{
								all_itm = split_val[1];
							}
							else
							{
								all_itm+=','+split_val[1];
							}
						}
						running++;
					}
					
					$.post('genOrder.php',{rcid:rcNumber,brand:brand,model:model,pick:pick_itm,type:'item_grop',allitm:all_itm},function(data){
						if(data!='0')
						{
							$('#show_pick').append(data);
							$('.currentaddr').show();
						}
						else
						{
							alert('ไม่พบรายการสินค้าในใบเสร็จที่ระบุ');
						}
					});
					$(pick_grp[i]).parent().parent().remove();
					$('.overlay2').fadeOut(1000);
				}
				else
				{
					alert('กรุณาระบุจำนวนเป็นตัวเลขเท่านั้นครับ');
				}
			}
			else
			{
				alert('กรุณาระบุจำนวนที่ต้องการให้ครบทุกรายการครับ');
				return false;
			}
		}
	}
}
function delete_this_row(elem){
	$(elem).parent().parent().remove();
}
function chk_max_itm(elem) {
	var slct = $('#'+elem).parent().parent().find('input[name="each_pick_itm[]"]').is(':checked');
	var max_itm = $('#'+elem).parent().parent().find('input[name="max_itm"]').val();
	var pick_itm = $('#'+elem).val();
	if(pick_itm!='')
	{
		if(slct==false)
		{
			$('#'+elem).parent().parent().find('input[name="each_pick_itm[]"]').attr('checked','checked');
		}
		if(parseInt(pick_itm) > parseInt(max_itm))
		{
			alert('ห้ามระบุเกินจำนวนสินค้าที่มีอยู่ครับ');
			$('#'+elem).val('');
			$('#'+elem).parent().parent().find('input[name="each_pick_itm[]"]').removeAttr('checked');
		}
	}
	else
	{
		if(slct==true)
		{
			$('#'+elem).parent().parent().find('input[name="each_pick_itm[]"]').removeAttr('checked');
		}
	}
}
function edit_addr() {
	if($('#edit_addr_chkbx').is(':checked')==true)
	{
		$('.tr_addr').hide();
		$('.tr_edit_addr').show();
	}
	else
	{
		$('.tr_edit_addr').hide();
		$('.tr_addr').show();
	}
}
</script>
<script type="text/javascript">
function add_new_cus(elem1,elem2){
	var doc_h = $(document).height();
	var doc_w = $(document).width();
	var scrll_t = $('body').scrollTop();
	
	var popup_h = $('.popup_new_cus').height();
	var popup_w = 450;
	
	var popup_pst_y = (doc_w/2)+(scrll_t/2)-150;
	var popup_pst_x = (doc_w/2)-(popup_w/2);
	
	$('.popup_new_cus').css('margin-top',popup_pst_y);
	$('.popup_new_cus').css('margin-left',popup_pst_x);
	
	$('.overlay').css('height',doc_h);
	
	$('#popup_input').val(elem1);
	$('#popup_cbx').val(elem2);
	
	$('#nc_prefix_name').val('');
	$('#nc_first_name').val('');
	$('#nc_last_name').val('');
	$('#nc_id_card').val('');
	
	$('.overlay').fadeIn(1000);
}
function close_popup(){
	var elem = $('#popup_cbx').val();
	$('#'+elem).removeAttr('checked');
	$('.overlay').fadeOut(1000);
}
function validate_nc(){
	var prefix = $('#nc_prefix_name').val();
	var fname = $('#nc_first_name').val();
	var lname = $('#nc_last_name').val();
	var id_card = $('#nc_id_card').val();
	
	var msg = "โปรดระบุ\r\n-------------------------------------------------------------------------------------------\r\n";
	var chk_msg = msg;
	
	if(prefix=='')
	{
		msg+='\r\n\t--> คำนำหน้า';
	}
	if(fname=='')
	{
		msg+='\r\n\t--> ชื่อลูกค้า';
	}
	if(lname=='')
	{
		msg+='\r\n\t--> นามสกุลลูกค้า';
	}
	if(id_card=='')
	{
		msg+='\r\n\t--> เลขบัตรประชาชนลูกค้า';
	}
	if(check_card()==false)
	{
		msg+='\r\n\t--> เลขบัตรประชาชนลูกค้าไม่ถูกต้อง';
	}
	if(msg!=chk_msg)
	{
		alert(msg);
	}
	else
	{
		$.post('add_new_cus.php',{prefix:prefix,fname:fname,lname:lname,id_card:id_card},function(data){
			if(data=='1')
			{
				get_new_cus();
			}
			else
			{
				alert('การทำรายการล้มเหลว');
			}
		});
	}
}
function get_new_cus(){
	var prefix = $('#nc_prefix_name').val();
	var fname = $('#nc_first_name').val();
	var lname = $('#nc_last_name').val();
	
	$.post('get_new_cus.php',{prefix:prefix,fname:fname,lname:lname},function(data){
		var input = $('#popup_input').val();
		$('#'+input).val(data);
		var elem = $('#popup_cbx').val();
		$('#'+elem).attr('disabled','disabled');
		$('.overlay').fadeOut(1000);
	});
}
function check_card(){
	var data = $('#nc_id_card').val();
	if(data.length==13)
	{
		var digit = data.split('');
		var i = 0;
		var m = 13;
		var sum = 0;
		while(i<12)
		{
			var s = digit[i]*m;
			sum = sum+s;
			i++;
			m--;
		}
		var chk_digit = 11-(sum%11);
		if(digit[12]==chk_digit)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function change_addr(elem){
	var cusid = '<?php echo $cus_id; ?>';
	var doc_h = $(document).height();
	var doc_w = $(document).width();
	var scrll_t = $(window).scrollTop();
	
	var popup_h = $('.popup_pick_addr').html();
	var popup_w = 450;
	
	var popup_pst_y = scrll_t+50;
	var popup_pst_x = (doc_w/2)-(popup_w/2)-150;
	
	$(elem).addClass('focus_list');
	
	$('.popup_pick_addr').css('margin-top',popup_pst_y);
	$('.popup_pick_addr').css('margin-left',popup_pst_x);
	
	$('.overlay1').css('height',doc_h);
	
	document.frm_add_new_addr.reset();
	

	$.post('gen_address.php',{cusid:cusid},function(data){
		$('.list_add').html(data);
		$('.overlay1').fadeIn(1000);
	});
}

function close_popup1(){
	$('.overlay1').fadeOut(1000);
}
function close_popup2(){
	$('.overlay2').fadeOut(1000);
}
function validate_na() {
	
	var cusid = '<?php echo $cus_id; ?>';
	
	var msg = "โปรดระบุ\r\n-------------------------------------------------------------------------------------------\r\n";
	var chk_msg = msg;
	
	if($('#na_homenumber').val()=='')
	{
		msg+='\r\n\t--> บ้านเลขที่';
	}
	if($('#na_tambon').val()=='')
	{
		msg+='\r\n\t--> ตำบล/แขวง';
	}
	if($('#na_district').val()=='')
	{
		msg+='\r\n\t--> อำเภอ/เขต';
	}
	if($('#na_province').val()=='')
	{
		msg+='\r\n\t--> จังหวัด';
	}
	if($('#na_zipcode').val()=='')
	{
		msg+='\r\n\t--> รหัสไปรษณีย์';
	}
	
	if(msg!=chk_msg)
	{
		alert(msg);
	}else{
		
		var na_room = $('#na_room').val();
		var na_floor = $('#na_floor').val();
		var na_homenumber = $('#na_homenumber').val();
		var na_building = $('#na_building').val();
		var na_moo = $('#na_moo').val();
		var na_village = $('#na_village').val();
		var na_soi = $('#na_soi').val();
		var na_road = $('#na_road').val();
		var na_tambon = $('#na_tambon').val();
		var na_district = $('#na_district').val();
		var na_province = $('#na_province').val();
		var na_zipcode = $('#na_zipcode').val();
		
		$.post('save_new_address.php',{na_room:na_room,na_floor:na_floor,na_homenumber:na_homenumber,na_building:na_building,na_moo:na_moo,na_village:na_village,na_soi:na_soi,na_road:na_road,na_tambon:na_tambon,na_district:na_district,na_province:na_province,na_zipcode:na_zipcode,cusid:cusid},function(data){
			if(data!='1')
			{
				if(data=='2')
				{
					alert('มีที่อยู่นี้แล้ว');
				}
				else
				{
					alert('บันทึกข้อมูลไม่สำเร็จ');
				}
			}
			else
			{
				$.post('gen_address.php',{cusid:cusid},function(data){
					$('.list_add').html(data);
				});
				document.frm_add_new_addr.reset();
			}
		});
	}
}
function pick_this_addr(assetid,full_addr){
	$('.focus_list').parent().find('.span_addr').html(full_addr);
	$('.focus_list').parent().parent().find('input[name="H_addr[]"]').val('0,'+assetid);
	$('.focus_list').removeClass('focus_list');
	$('.overlay1').fadeOut(1000);
}
function delete_this_addr(assetid,full_addr) {
	var cusid = '<?php echo $cus_id; ?>';
	$.post('delete_addr.php',{assetid:assetid},function(data){
		if(data!='1')
		{
			if(data=='2')
			{
				alert('ไม่สามารถลบที่อยู่นี้ได้เนื่องจากที่อยู่นี้ถูกนำไปใช้แล้ว');
			}
			else
			{
				alert('ไม่สามารถลบที่อยู่นี้ได้');
			}
		}
		else
		{
			var elem = $('.span_addr');
			var sum_elem = $(elem).length;
			
			var i = 0;
			
			while(i<sum_elem)
			{
				if($(elem[i]).html()==full_addr)
				{
					$(elem[i]).html('ใช้ที่อยู่เดียวกันกับสัญญา');
					$(elem[i]).parent().parent().find('input[name="H_addr[]"]').val('1,0');
				}
				i++;
			}
			$.post('gen_address.php',{cusid:cusid},function(data){
				$('.list_add').html(data);
			});
		}
	});
}
function change_addr_to_contract(){
	var elem = $('input[name="all_pick_itm[]"]');
	var all_elem = $(elem).length;
	
	var i = 0;
	
	while(i<all_elem)
	{
		if($('#use_same_contract').is(':checked')==true)
		{
			$(elem[i]).parent().parent().find('input[name="H_addr[]"]').val('1,0');
			$(elem[i]).parent().parent().find('.span_addr').html('ใช้ที่อยู่เดียวกันกับสัญญา');
		}
		else
		{
			$(elem[i]).parent().parent().find('input[name="H_addr[]"]').val('');
			$(elem[i]).parent().parent().find('.span_addr').html('ไม่ระบุ');
		}
		i++;
	}
}
function change_each_addr_to_contract() {
	$('.focus_list').parent().find('.span_addr').html('ใช้ที่อยู่เดียวกันกับสัญญา');
	$('.focus_list').parent().parent().find('input[name="H_addr[]"]').val('1,0');
	$('.focus_list').removeClass('focus_list');
	$('.overlay1').fadeOut(1000);
} 
function validate() 
{
	var picked_order = $('input[name="all_pick_itm[]"]:checked').length;
	if(picked_order==0)
	{
				theMessage = " กรุณาเลือกรายการสินค้าที่จะผูกกับสัญญา";
				alert(theMessage);
				return false;
	}else{
		
		if(confirm('ยืนยันการบันทึก')==true){ 
				var order = $('input[name="all_pick_itm[]"]:checked');
				var all_order = $(order).length;
				var start_r = 0;
				var order_val = '';
				var addr_val = '';
				while(start_r<all_order)
				{
					order_val = $(order[start_r]).val();
					addr_val = $(order[start_r]).parent().parent().find('input[name="H_addr[]"]').val();
					if(addr_val!='')
					{
						$(order[start_r]).val(order_val+','+addr_val);
					}
					$(order[start_r]).removeAttr('disabled');
					start_r++;
				}
				
				
				return true;
		}else{
			return false;
		}		
	}
};			

</script>
</head>
<body>
<center><div><h2>เพิ่มสินทรัพย์ให้กับสัญญา</h2></center>

<!--------------------------------------------------------------------------------------- -->
<div class="overlay1">
	<div class="popup_pick_addr">
    	<div class="note1">
        	หมายเหตุ : หากเคยเพิ่มที่อยู่แล้วและไม่มีการแก้ไขเปลี่ยนแปลงที่อยู่ให้เลือก ที่อยู่จากรายการด้านล่าง  แต่หากยังไม่เคยเพิ่มหรือมีการแก้ไขเปลี่ยนแปลงที่อยู่ให้ทำการเพิ่ม ที่อยู่ใหม่ครับ
        </div>
        <div class="data1">
        	<div class="x1" onclick="close_popup1();"></div>
        	<div class="data_head1">เลือกรายการที่ตั้งเครื่อง</div>
            <div class="same_contract"><input type="button" name="btn_same_contract" id="btn_same_contract" onclick="change_each_addr_to_contract();" value="ใช้ที่อยู่เดียวกันกับสัญญา" /></div>
        	<div class="list_add"></div>
            <div class="data_head1">หรือ เพิ่มใหม่</div>
            <div class="div_add_new_addr">
            	<div align="center">
                	<form name="frm_add_new_addr" id="frm_add_new_addr">
                        <table border="0" cellpadding="5" cellspacing="1" width="400">
                            <tr>
                                <td>ห้อง:</td>
                                <td><input type="text" name="na_room" id="na_room" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ชั้น:</td>
                                <td><input type="text" name="na_floor" id="na_floor" size="45" /></td>
                            </tr>
                            <tr>
                                <td>บ้านเลขที่<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_homenumber" id="na_homenumber" size="45" /></td>
                            </tr>
                            <tr>
                                <td>อาคาร:</td>
                                <td><input type="text" name="na_building" id="na_building" size="45" /></td>
                            </tr>
                            <tr>
                                <td>หมู่:</td>
                                <td><input type="text" name="na_moo" id="na_moo" size="45" /></td>
                            </tr>
                            <tr>
                                <td>หมู่บ้าน:</td>
                                <td><input type="text" name="na_village" id="na_village" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ซอย:</td>
                                <td><input type="text" name="na_soi" id="na_soi" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ถนน:</td>
                                <td><input type="text" name="na_road" id="na_road" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ตำบล/แขวง<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_tambon" id="na_tambon" size="45" /></td>
                            </tr>
                             <tr>
                                <td>อำเภอ/เขต<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_district" id="na_district" size="45" /></td>
                            </tr>
                            <tr>
                                <td>จังหวัด<span class="hilight">*</span>:</td>
                                <td>
                                    <select name="na_province" id="na_province">
                                        <option value="">-------------------- เลือกจังหวัด --------------------</option>
                                        <?php
                                            $qr_province = pg_query("select * from \"nw_province\" order by \"proName\" asc");
                                            while($rs_province = pg_fetch_array($qr_province))
                                            {
                                                $proID = $rs_province['proID'];
                                                $proName = $rs_province['proName'];
                                                echo "<option value=\"$proName\">$proName</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>รหัสไปรษณี<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_zipcode" id="na_zipcode" size="45" /></td>
                            </tr>
                        </table>
                    </form>
            	</div>
            </div>
            <div class="submit_na_frm">
            	<input type="button" name="submit_na" id="submit_na" class="ui-button-icon-primary" value="บันทึก" onclick="validate_na();" />
            </div>
        </div>
    </div>
    <div class="alert1"></div>
</div>

<!-------------------------------------- overlay เลือกรายการสินค้าที่จะผูกกับสัญญา ------------------------------------------------------------->

<div class="overlay2">
	<div class="popup_pick_order">
    	<div class="note2"> 
        </div>
        <div class="data2">
        	<div class="x2" onclick="close_popup2();"></div>
        	<div class="data_head2">เลือกรายการสินค้าที่จะผูกกับสัญญา</div>
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr id="list">
                	<td>
                    	<div class="row border white" id="show_list">
                            
                        </div>
                    </td>
                </tr>
            </table>
            <div class="submit_pick_order">
            	<input type="button" name="pick_itm" id="pick_itm" value="เพิ่มรายการ" onclick="add_select_itm();" />
            </div>
        </div>
    </div>
    <div class="alert2"></div>
</div>

<form name="frm" action="../thcap_edit_newcon/process_add_product.php" method="POST">



	<input type="hidden" value="<?php echo $contract; ?>" name="conid">	
	<input type="hidden" value="<?php echo $cus_id; ?>" name="mainID">	
	<table width="900"  align="center">
			<tr>
				<td><font color="red">*ในกรณีที่ยังไม่มีใบเสร็จให้สร้างใบเสร็จก่อน <input type="button" onclick="popU('../assets_for_rent_sale/frm_Index.php?autoappv=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=600')" value="เพิ่มใบเสร็จ"></td>
			</tr>
	</table>		
	<table width="900" frame="box" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#DDFFAA" id="tble">
			<tr>
				<td align="right"><font>เลือกรายการ </font><?php if($contype!="BH"){ ?><font color="#FF0000"><b> * </b></font><?php } ?><font> : </font></td>
				<td>
					<label><input type="radio" name="pickOrder" id="pickOrder1" onchange="pick_Order()" />เลือกทั้งใบเสร็จ</label> &nbsp;&nbsp;
					<label><input type="radio" name="pickOrder" id="pickOrder2" onchange="pick_Order()" />เลือกบางรายการในใบเสร็จ</label>
				</td>
			</tr>
			<tr class="hide" id="tr_add_rct">
				<td align="right"><font>ใบสั่งซื้อ / ใบเสร็จ </font><font color="#FF0000"><b> * </b></font><font> : </font></td>
				<td>
					<input type="text" name="pick_receipt1"  id="pick_receipt1" size="40px" />
					<input type="button" name="add_receipt" id="add_receipt" value="เลือกใบเสร็จ/ใบสั่งซื้อนี้" onclick="fct_add_receipt();" />
				</td>
			</tr>
			<tr class="hide" id="tr_pick_type">
				<td align="right"><font>รูปแบบการเลือก </font><font color="#FF0000"><b> * </b></font><font > : </font></td>
				<td>
					<label><input type="radio" name="pickOrdertype" id="pickOrdertype1" onchange="pick_type();" value="1" />เลือกแบบเจาะจงสินค้า</label> &nbsp;&nbsp;
					<label><input type="radio" name="pickOrdertype" id="pickOrdertype2" onchange="pick_type();" value="2" />เลือกแบบระบุจำนวน</label>
				</td>
			</tr>
			<!-- เลือกแบบเจาะจงสินค้า -->
			<tr class="hide sub_hide" id="tr_find_rct1">
				<td align="right"><font>ใบสั่งซื้อ / ใบเสร็จ </font><font color="#FF0000"><b> * </b></font><font> : </font></td>
				<td>
					<input type="text" name="pick_receipt2"  id="pick_receipt2" size="40px" />
					<input type="button" name="add_receipt" id="add_receipt" value="แสดงรายการ" onclick="list_select_item(1);" />
				</td>
			</tr>
			<!-- เลือกแบบระบุจำนวน -->
			<tr class="hide sub_hide" id="tr_find_rct2">
				<td align="right"><font>ใบสั่งซื้อ / ใบเสร็จ </font><font color="#FF0000"><b> * </b></font><font> : </font></td>
				<td>
					<input type="text" name="pick_receipt2"  id="pick_receipt3" size="40px" />
					<input type="button" name="add_receipt" id="add_receipt" value="แสดงรายการ" onclick="list_select_item(2);" />
				</td>
			</tr>
			<tr id="pick">
				<td align="right" valign="top"><font>รายการสินค้าที่ผูกกับสัญญ </font><font color="#FF0000"><b> * </b></font><font> : </font></td>
				<td>
					<label><input type="checkbox" name="use_same_contract" id="use_same_contract" onchange="change_addr_to_contract();" /> ใช้ที่อยู่เดียวกันกับสัญญาทั้งหมด</label>
					<input type="button" name="remove_itm" id="remove_itm" value="ลบรายการ" onclick="remove_select_itm();" />
					<div  id="show_pick" class="row border white">
					</div>
					<div class="row center"></div>
				</td>
			</tr>
			<tr align="center">
				<td colspan="5">
					<div style="padding-top:30px;"></div>
					<div class="row center">
						<input type="submit" name="add_itm" id="add_itm" value=" บันทึก " onclick="return validate();" style="width:100px;height:70px;" />
						<input type="button" name="closebtn" id="closebtn" value=" ปิด " onclick="window.close();" style="width:100px;height:70px;" />
					</div>
				</td>
			</tr>
	</table>
</form>	
	<!--------------------------------------------------------------------------------------- -->
	
