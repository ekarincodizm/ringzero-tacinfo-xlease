<?php 
$corpID = pg_escape_string($_GET["corpid"]);
?>
<script type="text/javascript">
$( document ).ready(function() {
	$("#check1").click(function(){
		if(document.getElementById("check1").checked==true){	
			if(document.getElementById("check1_load").value=="1"){
				$("#main").load("follow_cus_thcap_main.php?corpid="+<?php echo $corpID;?>);
				$("#check1_load").val("2");
			} else {
				$("#main").show();
			}
		} else {
			$("#main").hide();
		}
	});
	$("#check2").click(function(){
		if(document.getElementById("check2").checked==true){	
			if(document.getElementById("check2_load").value=="1"){
				$("#second").load("follow_cus_thcap_second.php?corpid="+<?php echo $corpID;?>);
				$("#check2_load").val("2");
			} else {
				$("#second").show();
			}
		} else {
			$("#second").hide();
		}	
	});
	
	$("#check3").click(function(){
		if(document.getElementById("check3").checked==true){	
			if(document.getElementById("check3_load").value=="1"){
				$("#bond").load("follow_cus_thcap_bond.php?corpid="+<?php echo $corpID;?>);
				$("#check3_load").val("2");
			} else {
				$("#bond").show();
			}
		} else {
			$("#bond").hide();
		}	
	});
});
</script>
<input type="hidden" id="load_thcap" value="show">
<fieldset>
	<legend><input type="checkbox" id="check1" onchange="load_thcap();"><b>ผู้กู้หลัก</b></legend>
	<input type="hidden" id="check1_load" value="1">
	<div id="main"></div>
</fieldset>

<fieldset>
	<legend><input type="checkbox" id="check2" onchange="load_thcap();"><b>ผู้กู้ร่วม/ผู้เช่าซื้อร่วม</b></legend>
	<input type="hidden" id="check2_load" value="1">
	<div id="second"></div>
</fieldset>

<fieldset>
	<legend><input type="checkbox" id="check3" onchange="load_thcap();"><b>ผู้ค้ำประกัน</b></legend>
	<input type="hidden" id="check3_load" value="1">
	<div id="bond"></div>
</fieldset>