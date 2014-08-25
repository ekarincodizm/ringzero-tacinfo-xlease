<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) จัดการยี่ห้อรุ่นสินทรัพย์ขาย-เช่า</title>
<link href="css/act.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function operation_start(){
	var operate_type = $('#cbx_pick_type').val();
	if(operate_type=='')
	{
		alert('โปรดระบุ\n-----------------------------------\n\n---> ประเภทการดำเนินการ');
	}
	else if(operate_type=='brand')
	{
		$.post('gen_brand_frm.php',function(data){
			$('#add_frm').html(data);
			$('#fs_frm').show();
		});
		$.post('gen_brand_data.php',function(data){
			$('#show_data').html(data);
			$('#fs_data').show();
		});
	}
	else if(operate_type=='model')
	{
		$.post('gen_model_frm.php',function(data){
			$('#add_frm').html(data);
			$('#fs_frm').show();
		});
		$.post('gen_model_data.php',function(data){
			$('#show_data').html(data);
			$('#fs_data').show();
		});
	}
}
function add_brand(){
	var brand = $('#tbx_brand_name').val();
	var astype = $('#astype').val();
	if(brand=='')
	{
		alert('โปรดระบุ\n-----------------------------------\n\n---> ชื่อยี่ห้อ');
	}
	else
	{
		$.post('add_brand.php',{brand:brand,astype:astype},function(data){
			if(data==1)
			{
				alert('บันทึกข้อมูลเรียบร้อยแล้ว');
				operation_start();
			}
			else if(data==2)
			{
				alert('มียี่ห้อและประเภทที่ระบุอยู่ในระบบแล้ว กรุณาระบุใหม่ครับ');
			}
			else
			{
				alert('ไม่สามารถบันทึกข้อมูลได้');
			}
		});
	}
}
function add_model(){
	var brand_id = $('#tbx_brand_name').val();
	var model = $('#tbx_model_name').val();
	if(brand_id=='')
	{
		alert('โปรดระบุ\n-----------------------------------\n\n---> ชื่อยี่ห้อ');
	}
	else if(model=='')
	{
		alert('โปรดระบุ\n-----------------------------------\n\n---> ชื่อรุ่น');
	}
	else
	{
		$.post('add_model.php',{brandid:brand_id,model:model},function(data){
			if(data==1)
			{
				alert('บันทึกข้อมูลเรียบร้อยแล้ว');
				operation_start();
			}
			else if(data==2)
			{
				alert('มีรุ่นที่ระบุอยู่ในระบบแล้ว กรุณาระบุใหม่ครับ');
			}
			else
			{
				alert('ไม่สามารถบันทึกข้อมูลได้');
			}
		});
	}
}
</script>
</head>

<body>
<div align="center">
	<div style="width:900px; display:block;">
        <h2>(THCAP) จัดการยี่ห้อรุ่นสินทรัพย์ขาย-เช่า</h2>
        <hr />
        <fieldset style="margin-bottom:15px;">
            <legend><b>เลือกประเภทการดำเนินการ</b></legend>
            <div style="margin:15px 0px; text-align:center;">
                <span style="display:inline-block">เลือกประเภท : </span>
                <select name="cbx_pick_type" id="cbx_pick_type" style="display:inline-block;">
                    <option value="">--------- เลือกประเภท ---------</option>
                    <option value="brand">เพิ่มยี่ห้อ</option>
                    <option value="model">เพิ่มรุ่น</option>
                </select>
                <input type="button" name="btn_operation" id="btn_operation" value="เพิ่มข้อมูล" onclick="operation_start();" />
            </div>
        </fieldset>
        <fieldset id="fs_frm"  style="margin-bottom:15px; display:none;">
        	<legend><b>เพิ่มข้อมูล</b></legend>
        	<div id="add_frm" style="margin:15px 0px;"></div>
        </fieldset>
        <fieldset id="fs_data"  style="margin-bottom:15px; display:none;">
        	<legend><b>ข้อมูลทั้งหมด</b></legend>
        	<div id="show_data" style="margin:15px 0px;"></div>
        </fieldset>
    </div>
</div>
</body>
</html>