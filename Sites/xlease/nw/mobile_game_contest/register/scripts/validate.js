$(document).ready(function(){
	$('input[name="member_birth_day[]"]').datepicker({ 
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	 });
});

function enable_nextstep(){
	if($('#except').is(':checked')==true)
	{
		$('#next_btn').addClass('btn-primary');
		$('#next_btn').removeAttr('disabled');
	}
	else
	{
		$('#next_btn').removeClass('btn-primary');
		$('#next_btn').attr('disabled','disabled');
	}
}
function next_step(step){
	switch(step)
	{
		case 2:
			$('.show_content').fadeOut(1000,function(){
				window.location.href='register_form.php';
			});
			break;
		case 3:
			$('#step2').fadeOut(1000,function(){
				$('#step3').show();
			});
			break;
	}
}
function back_steb(step){
	switch(step)
	{
		case 1:
			$('.show_content').fadeOut(1000,function(){
				window.location.href='rules.php';
			});
			break;
		case 2:
			$('#step3').fadeOut(1000,function(){
				$('#step2').show();
			});
			break;
	}
}
function update_data(elem,isTextarea,isSelect){
	var data = $('#'+elem).val();
	
	if(data=='')
	{
		data = '--';
	}
	else
	{
		if(isSelect==true)
		{
			data = $('#'+elem+' option:selected').text();
		}
		
		if(isTextarea==true)
		{
			data = data.replace(/\r|\n/g,'<br />');
		}
	}
	
	$('#show_'+elem).html(data);
}

function savedata(){
	$('#regis_form').submit();
}

function validate(){
	var msg = 'ตรวจพบข้อผิดพลาด\r\n-----------------------------------------------------------';
	var chk_msg = msg;
	
	var source = $('.source');
	
	var a = 0;
	
	//alert(source.length);
	
	while(a<source.length)
	{
		var show_id = 'show_'+source.eq(a).attr('id');
		if(source.eq(a).val()=='' || source.eq(a).val()==undefined)
		{
			var label = source.eq(a).parent().parent().find('.input_label').text();
			
			label = label.replace(/\*/g,'');
			label = label.replace(/:/g,'');
			chk_msg+='\r\n\t--> โปรดระบุ '+label;
			
			source.eq(a).css('border','solid 1px #ff0000');
			
			$('#'+show_id).html('--');
			
			alert(chk_msg);
			
			return false;
		}
		else
		{
			if(source.eq(a).is('textarea')==true)
			{
				$('#'+show_id).html(source.eq(a).text().replace(/\\r\\n/g,'<br />'));
			}
			else if(source.eq(a).is('select')==true)
			{
				$('#'+show_id).html(source.eq(a).find('option:selected').text());
			}
			else
			{
				$('#'+show_id).html(source.eq(a).val());
			}
			source.eq(a).css('border','solid 1px #55be2c');
		}
		
		a++;
	}
	a = 0;
	var source1 = $('.source1');
	
	while(a<source1.length)
	{
		var show_id = 'show_'+source1.eq(a).attr('id');
		if(source1.eq(a).val()!=''&&source1.eq(a).val()!=undefined)
		{
			if(source1.eq(a).is('textarea')==true)
			{
				$('#'+show_id).html(source1.eq(a).val().replace(/(\r\n|\n|\r)/gm,"<br />"));
			}
			else
			{
				$('#'+show_id).html(source1.eq(a).val());
			}
		}
		else
		{
			$('#'+show_id).html('--');
		}
		
		a++;
	}
	
	var email = $('.email');
	a = 0;
	
	while(a<email.length)
	{
		var show_id = 'show_'+email.eq(a).attr('id');
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (reg.test(email.eq(a).val()) == false) 
        {
            chk_msg+='\r\n\t--> อีเมล์ไม่ถูกต้อง';
		
			email.eq(a).css('border','solid 1px #ff0000');
			
			$('#'+show_id).html('--');
			
			alert(chk_msg);
			
			return false;
        }
		else
		{
			email.eq(a).css('border','solid 1px #55be2c');
			$('#'+show_id).html(email.eq(a).val());
		}
		
		a++;
	}
	
	var file = $('.file');
	a = 0;
	
	while(a<file.length)
	{
		var show_id = 'show_'+file.eq(a).attr('id');
		if(file.eq(a).val()!='')
		{
			var pic_ext = file.eq(a).val().split('.');
			var pic_ext1 = pic_ext.pop().toLowerCase();
			if(pic_ext1!='jpg'&&pic_ext1!='jpeg'&&pic_ext1!='png'&&pic_ext1!='pdf')
			{
				chk_msg+='\r\n\t--> ประเภทไฟล์ไม่ถูกต้อง';
				
				file.eq(a).css('border','solid 1px #ff0000');
				$('#'+show_id).html('--');
				
				alert(chk_msg);
				
				return false;
			}
			else
			{
				file.eq(a).css('border','solid 1px #55be2c');
				$('#'+show_id).html(file.eq(a).val());
			}
		}
		
		a++;
	}
	
	next_step(3);
}

function add_member(){
	
	var member_number = $('.member_number');
	var sum_member = $(member_number).length;
	
	if(sum_member>=5)
	{
		var msg = 'ตรวจพบข้อผิดพลาด\r\n-----------------------------------------------------------';
		msg+='\r\n\r\n\t--> ภายในหนึ่งทึมสามารถมีสมาชิกได้ไม่เกิน 5 คน';
		alert(msg);
	}
	else
	{
		var new_row = '<div class="group_data">'
					+'<div class="alert-block alert-info">'
                    +'<div class="inline_block" style="width:84%;">'
                    +'<i class="icon-user"></i>'
                    +'<span> ข้อมูลสมาชิกคนที่ </span>'
                    +'<span class="member_number">'+(sum_member+1)+'</span>'
                    +'</div>'
                    +'<div class="inline_block" style="width:15%; text-align:right;">'
                    +'<input type="button" name="btn_delete_member" id="btn_delete_member'+(sum_member+1)+'" class="btn" value="ลบ" onclick="delete_member(id);" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="split">'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>ชื่อ - สกุล : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_name_tha[]" id="member_name_tha'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_name_eng[]" id="member_name_eng'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:100px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>ชื่อเล่น : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_nick_name[]" id="member_nick_name'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:100px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>วัน/เดือน/ปี เกิด : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_birth_day[]" id="member_birth_day'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>สถาบันการศึกษา : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_institute[]" id="member_institute'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:100px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>ระดับชั้น : </span></div>'
                    +'<div class="input">'
                    +'<select name="member_level[]" id="member_level'+(sum_member+1)+'" style="width:96%;" class="source">'
                    +'<option value="">โปรดเลือกระดับการศึกษา</option>'
                    +'<option value="ปริญญาตรี">ปริญญาตรี</option>'
                    +'<option value="ปริญญาโท">ปริญญาโท</option>'
                    +'<option value="ปริญญาเอก">ปริญญาเอก</option>'
                    +'</select>'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>สาขาวิชา : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_branch[]" id="member_branch'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:150px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>เบอร์โทรศัพท์ : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_tel[]" id="member_tel'+(sum_member+1)+'" style="width:96%;" class="source" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>E-Mail : </span></div>'
                    +'<div class="input">'
                    +'<input type="text" name="member_email[]" id="member_email'+(sum_member+1)+'" style="width:96%;" class="source email" />'
                    +'</div>'
                    +'</div>'
                    +'<br />'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>Upload บัตรประชาชน : </span></div>'
                    +'<div class="input">'
                    +'<input type="file" name="personal_card'+(sum_member+1)+'" id="personal_card'+(sum_member+1)+'" style="width:96%; height:inherit;" class="per_file source file" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>Upload ทะเบียนบ้าน : </span></div>'
                    +'<div class="input">'
                    +'<input type="file" name="home_regis'+(sum_member+1)+'" id="home_regis'+(sum_member+1)+'" style="width:96%; height:inherit;" class="home_file source file" />'
                    +'</div>'
                    +'</div>'
                    +'<div class="inline_block" style="width:200px; margin-right:15px;">'
                    +'<div class="input_label"><span class="req">*</span><span>Upload รูปถ่าย : </span></div>'
                    +'<div class="input">'
                    +'<input type="file" name="photo'+(sum_member+1)+'" id="photo'+(sum_member+1)+'" style="width:96%; height:inherit;" class="pic_file source file" />'
                    +'</div>'
                    +'</div>'
					+'</div>'
                    +'</div>';
		
		$('#member_data').append(new_row);
		
		$('#member_birth_day'+(sum_member+1)).datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		 });
	}
}

function delete_member(elem){
	
	var member_number = $('.member_number');
	var sum_member = $(member_number).length;
	
	if(sum_member<=1)
	{
		var msg = 'ตรวจพบข้อผิดพลาด\r\n-----------------------------------------------------------';
		msg+='\r\n\r\n\t--> ภายในหนึ่งทึมต้องมีสมาชิกอย่างน้อย 1 คน';
		alert(msg);
	}
	else
	{
		$('#'+elem).parent().parent().parent().remove();
		
		var delete_btn = $('input[name="btn_delete_member"]');
		var name_th = $('input[name="member_name_tha[]"]');
		var name_en = $('input[name="member_name_eng[]"]');
		var nick_name = $('input[name="member_nick_name[]"]');
		var birth_day = $('input[name="member_birth_day[]"]');
		var institute = $('input[name="member_institute[]"]');
		var level = $('select[name="member_level[]"]');
		var branch = $('input[name="member_branch[]"]');
		var tel = $('input[name="member_tel[]"]');
		var email = $('input[name="member_email[]"]');
		var pid = $('.per_file');
		var himeid = $('.home_file');
		var picid = $('.pic_file');
		
		var i = 0;
		
		while(i<(sum_member-1))
		{
			$('.member_number').eq(i).html(i+1);
			$(delete_btn[i]).attr('id','btn_delete_member'+(i+1));
			$(name_th[i]).attr('id','member_name_tha'+(i+1));
			$(name_en[i]).attr('id','member_name_eng'+(i+1));
			$(nick_name[i]).attr('id','member_nick_name'+(i+1));
			$(birth_day[i]).attr('id','member_birth_day'+(i+1));
			$(institute[i]).attr('id','member_institute'+(i+1));
			$(level[i]).attr('id','member_level'+(i+1));
			$(branch[i]).attr('id','member_branch'+(i+1));
			$(tel[i]).attr('id','member_tel'+(i+1));
			$(email[i]).attr('id','member_email'+(i+1));
			$(pid[i]).attr('name','personal_card'+(i+1));
			$(pid[i]).attr('id','personal_card'+(i+1));
			$(himeid[i]).attr('name','home_regis'+(i+1));
			$(himeid[i]).attr('id','home_regis'+(i+1));
			$(picid[i]).attr('name','photo'+(i+1));
			$(picid[i]).attr('id','photo'+(i+1));
			
			i++;
		}
	}
}