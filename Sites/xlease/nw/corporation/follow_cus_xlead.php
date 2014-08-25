<?php 
$corpID = pg_escape_string($_GET["corpid"]);
?>
<script type="text/javascript">
$( document ).ready(function() {
	$("#checkX1").click(function(){
		if(document.getElementById("checkX1").checked==true){	
			if(document.getElementById("checkX1_load").value=="1"){
				$("#mainX").load("follow_cus_xlead_main.php?corpid="+<?php echo $corpID;?>);
				$("#checkX1_load").val("2");
			} else {
				$("#mainX").show();
			}
		} else {
			$("#mainX").hide();
		}
	});
	
	$("#checkX3").click(function(){
		if(document.getElementById("checkX3").checked==true){	
			if(document.getElementById("checkX3_load").value=="1"){
				$("#bondX").load("follow_cus_xlead_bond.php?corpid="+<?php echo $corpID;?>);
				$("#checkX3_load").val("2");
			} else {
				$("#bondX").show();
			}
		} else {
			$("#bondX").hide();
		}	
	});
});
</script>
<input type="hidden" id="load_thcap" value="show">
<fieldset>
	<legend><input type="checkbox" id="checkX1" ><b>ผู้เ่ช่าซื้อ</b></legend>
	<input type="hidden" id="checkX1_load" value="1">
	<div id="mainX"></div>
</fieldset>

<fieldset>
	<legend><input type="checkbox" id="checkX3" ><b>ผู้ค้ำประกัน</b></legend>
	<input type="hidden" id="checkX3_load" value="1">
	<div id="bondX"></div>
</fieldset>