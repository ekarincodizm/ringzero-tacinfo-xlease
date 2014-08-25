<!--=============แสดงในส่วนรายการที่ต้องตรวจสอบทั้งหมด===============-->
<fieldset><legend>รายการที่ต้องตรวจสอบทั้งหมด</legend>
<div> <input type=checkbox name="showall" onChange=showall()>แสดง/ซ่อนรายการทั้งหมด </div>
<br>
<div id="dataall"> </div>
<script type="text/javascript">
function showall()
{	
	var ele=$('input[name="showall"]');  
	if($(ele).is(':checked'))
	{ 	data = $.ajax({    
			url: "frm_showall.php", 
			async: false  
		}).responseText;	
		$("#dataall").html(data);	
	}
	else
	{	
		var data = "";	
		$("#dataall").html(data);	
	}
}		
</script>
</fieldset>



