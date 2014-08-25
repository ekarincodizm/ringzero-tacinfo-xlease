<?php
$last_msg_id=pg_escape_string($_GET['last_msg_id']);
$action=pg_escape_string($_GET['action']);

if($action <> "get")
{
?>
<script type="text/javascript">
$(document).ready(function(){		
	function last_msg_funtion() 
	{ 
	   var ID=$(".message_box:last").attr("id");
		$('div#last_msg_loader').html('<img src="images/progress.gif">');
		$.post("frm_Picbill.php?action=get&last_msg_id="+ID+"&prebillIDMaster="+'<?php echo $prebillIDMaster; ?>'+"&statusApp="+'<?php echo $statusApp; ?>'+"&edittime="+'<?php echo $edittime; ?>',
		
		function(data){
			if (data != "") {
			$(".message_box:last").after(data);			
			}
			$('div#last_msg_loader').empty();
		});
	};  
	
	$(window).scroll(function(){
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		   last_msg_funtion();
		}
	}); 	
});
</script>
<style>
.message_box
{
	height:600px;
	width:750px;
	padding:5px ;
	margin:0 auto;
}
#last_msg_loader
{
	text-align: right;
	width: 920px;
	
	margin: 10px auto 0 auto;
}

</style>
<?php
if($request!=1){
	include("../../config/config.php");
	$prebillID=pg_escape_string($_GET["prebillID"]);
	$prebillIDMaster=pg_escape_string($_GET["prebillIDMaster"]);
	$edittime=pg_escape_string($_GET["edittime"]);
	$stsprocess=pg_escape_string($_GET["stsprocess"]);
	$statusApp=pg_escape_string($_GET["statusApp"]);
}

//ค้นหาไฟล์ scan จากตาราง thcap_fa_prebill_file 
if($statusApp=='2'){ //ถ้าสถานะอนุมัิติ จะยังไม่มีเลข prebillID
	$val="\"auto_temp\"";
}else{
	$val="\"prebillID\"";
}
				
$close=pg_escape_string($_GET["close"]);

?>
<div style="text-align:center;"><hr><h2>-- ไฟล์สแกนบิล --</h2></div>
<?php
include('load_first_pic.php'); //Include load_first.php
?>
<div id="last_msg_loader"></div>
<?php
}
else
{
	$prebillIDMaster=pg_escape_string($_GET['prebillIDMaster']);
	$prebillID=pg_escape_string($_GET['prebillID']);
	$edittime=pg_escape_string($_GET["edittime"]);
	$stsprocess=pg_escape_string($_GET["stsprocess"]);
	$statusApp=pg_escape_string($_GET["statusApp"]);
	include('load_second_pic.php'); //include load_second.php
}
?>





