<?php
session_start();
include("../../config/config.php");
$typesearch=$_POST["typesearch"];
if($typesearch=="0"){ //ประกันใหม่
	list($securID,$val)=explode("#",$_POST["numDeed"]);
	$val=substr($val,30);
	$txtval="ประกันใหม่ ของเลขที่โฉนด $val";
}else{
	$val=$_POST["contractID"];
	$txtval="ต่ออายุของเลขที่สัญญา $val";
	
	$qry_name=pg_query("select a.auto_id,c.\"costBuilding\", c.\"costFurniture\", 
       c.\"costEngine\", c.\"costStock\", c.\"textOther\", c.\"costOther\", c.\"insureSpecial\", 
       c.\"totalChip\", c.\"numberQ\" from \"thcap_insure_main\" a
	left join thcap_insure_temp b on a.\"auto_tempID\"=b.\"auto_id\"
	left join thcap_insure_checkchip c on b.\"checkchipID\"=c.\"auto_id\"
	where b.\"ContractID\" ='$val' ");
	
	list($auto_id,$costBuilding, $costFurniture,$costEngine, $costStock, $textOther, $costOther, $insureSpecial, $totalChip, $numberQ)=pg_fetch_array($qry_name);
	$sumcost=$costBuilding+$costFurniture+$costEngine+$costStock+$costOther;

	$sumcost2= sprintf("%4.2f", $sumcost);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ประกันอัคคีภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){  
    $("#s1").hide();
	$("#s2").hide();
	$("#s3").hide();
	$("#s4").hide();
	$("#s5").hide();
	$("#textOther").hide();
	$("#insureSpec").hide();
	$("#submitadd").hide();
	//เพิ่มภัยพิเศษ
	$("#addSpec").click(function(){ 
		$("#insureSpec").show();
		$("#insureSpec").val('');
		$("#insureSpec").focus();
		$("#submitadd").show();
		$("#addSpec").hide();
	});
	
	$("#submitadd").click(function(){ 
		if($("#insureSpec").val()==""){
			alert("กรุณากรอกภัยเพิ่มพิเศษ");
			$("#insureSpec").focus();
		}else{
			$.post("process_insure.php",{
				cmd : "addspec",
				insureSpec : $("#insureSpec").val(), 
			},
			function(data){
				if(data == "1"){
					alert("บันทึกข้อมูลเรียบร้อยแล้ว");
					$("#loadspec").load("process_insure.php?cmd=showspec");
					$("#insureSpec").hide();
					$("#submitadd").hide();
					$("#addSpec").show();
				}else if(data=="2"){
					alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				}
			});
		}
	});
	
	$("#cost1").click(function(){ 
		if($('#cost1') .attr( 'checked')==true){
			$("#s1").show();
			$("#ss1").show();
			$("#txt1").focus();
		}else{
			$("#s1").hide();
			$("#ss1").hide();
			$("#txt1").val('');
		}
	});
	$("#cost2").click(function(){ 
		if($('#cost2').attr( 'checked')==true){
			$("#s2").show();
			$("#ss2").show();
			$("#txt2").focus();
		}else{
			$("#s2").hide();
			$("#ss2").hide();
			$("#txt2").val('');
		}
	});
	$("#cost3").click(function(){ 
		if($('#cost3').attr( 'checked')==true){
			$("#s3").show();
			$("#ss3").show();
			$("#txt3").focus();
		}else{
			$("#s3").hide();
			$("#ss3").hide();
			$("#txt3").val('');
		}
	});
	$("#cost4").click(function(){ 
		if($('#cost4').attr( 'checked')==true){
			$("#s4").show();
			$("#ss4").show();
			$("#txt4").focus();
		}else{
			$("#s4").hide();
			$("#ss4").hide();
			$("#txt4").val('');
		}
	});
	$("#cost5").click(function(){ 
		if($('#cost5').attr( 'checked')==true){
			$("#s5").show();
			$("#ss5").show();
			$("#textOther").show();
			$("#textOther1").show();
			
			$("#textOther").focus();
			$("#textOther1").focus();
		}else{
			$("#s5").hide();
			$("#ss5").hide();
			$("#txt5").val('');
			$("#textOther").hide();
			$("#textOther1").val('');
			$("#textOther1").hide();
		}
	});
});
function checkdata() {
	$("#submitbutton").attr('disabled', true);
		//ถ้าเลือกสิ่งปลูกสร้าง ให้ระบุจำนวนเงินด้วย
		if($('#cost1').attr( 'checked')==true){
			if($("#txt1").val()==""){
				alert('กรุณาระบุจำนวนเงินงวดนี้');
				$("#txt1").focus();
				$("#submitbutton").attr('disabled', false);
				return false;
			}
		}
		
		//ถ้าเลือกเฟอร์นิเจอร์ ให้ระบุจำนวนเงินด้วย
		if($('#cost2').attr( 'checked')==true){
			if($("#txt2").val()==""){
				alert('กรุณาระบุจำนวนเงินงวดนี้');
				$("#txt2").focus();
				$("#submitbutton").attr('disabled', false);
				return false;
			}
		}
		
		//ถ้าเลือกเครื่องจักร ให้ระบุจำนวนเงินด้วย
		if($('#cost3').attr( 'checked')==true){
			if($("#txt3").val()==""){
				alert('กรุณาระบุจำนวนเงินงวดนี้');
				$("#txt3").focus();
				$("#submitbutton").attr('disabled', false);
				return false;
			}
		}
		
		//ถ้าเลือกสต๊อกสินค้าให้ระบุจำนวนเงินด้วย
		if($('#cost4').attr( 'checked')==true){
			if($("#txt4").val()==""){
				alert('กรุณาระบุจำนวนเงินงวดนี้');
				$("#txt4").focus();
				$("#submitbutton").attr('disabled', false);
				return false;
			}
		}
		
		//ถ้าเลือกอื่นๆ 
		if($('#cost5').attr( 'checked')==true){
			if($("#textOther").val()==""){
				alert('กรุณาระบุรายการอื่นๆ');
				$("#textOther").focus();
				$("#submitbutton").attr('disabled', false);
				return false;
			}
			
			if($("#txt5").val()==""){
				alert('กรุณาระบุจำนวนเงินงวดนี้');
				$("#txt5").focus();
				$("#submitbutton").attr('disabled', false);
				return false;
			}	
		}
		
		//ให้ระบุเบี้ยรวมด้วย
		if($("#totalchip").val()==""){
			alert('กรุณาระบุเบี้ยรวม');
			$("#totalchip").focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	updateSummary();
	//return true;
	
}
function updateSummary(){
	var sss = 0;
    
    var c1 = $('#txt1').val();
	var c2 = $('#txt2').val();
	var c3 = $('#txt3').val();
	var c4 = $('#txt4').val();
	var c5 = $('#txt5').val();
	
	if($('#cost1') .attr( 'checked')==false){
		c1 = 0;
	}
	if($('#cost2') .attr( 'checked')==false){
		c2 = 0;
	}
	if($('#cost3') .attr( 'checked')==false){
		c3 = 0;
	}
	if($('#cost4') .attr( 'checked')==false){
		c4 = 0;
	}
	if($('#cost5') .attr( 'checked')==false){
		c5 = 0;
	}
    
	if ( isNaN(c1) || c1 == ""){
          c1 = 0;
    }
	if ( isNaN(c2) || c2 == ""){
          c2 = 0;
    }
	if ( isNaN(c3) || c3 == ""){
          c3 = 0;
    }
	if ( isNaN(c4) || c4 == ""){
          c4 = 0;
    }
	if ( isNaN(c5) || c5 == ""){
          c5 = 0;
    }

    sss = parseFloat(c1)+parseFloat(c2)+parseFloat(c3)+parseFloat(c4)+parseFloat(c5);

    $("#sumtotal").val(sss.toFixed(2));
}

</script>

</head>
<body>

<form name="form1" method="post" action="process_insure.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:800px;">
				<div align="center"><h2>เพิ่มรายละเอียดเงินเอาประกันภัย</h2></div>
				<fieldset><legend><B><?php echo $txtval;?></B></legend>
					<div style="padding-top:20px;">
						<table width="100%" border="0"  align="center">
						<tr>
							<td width="100" colspan="3"><b>:: จำนวนเงินเอาประกันภัยทั้งสิ้น ::</b></td>	
						</tr>
						<tr>
							<td width="50"></td>
							<td>
								<input type="checkbox" name="cost1" id="cost1" value="1" onclick="javascript:updateSummary()" <?php if($costBuilding!=""){ echo "checked"; }?>> - สิ่งปลูกสร้าง (รากฐานฯไม่รวม)
							</td>
							<td>
								<?php 
								if($costBuilding!=""){
								?>
									<div id="ss1"><input type="text" name="txt1" id="txt1" size="20" style="text-align:right;" onkeyup="javascript:updateSummary();" value="<?php echo $costBuilding; ?>"></div>
								<?php
								}else{
								?>
									<div id="s1"><input type="text" name="txt1" id="txt1" size="20" style="text-align:right;" onkeyup="javascript:updateSummary();"></div>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost2" id="cost2" value="1" onclick="javascript:updateSummary()" <?php if($costFurniture!=""){ echo "checked"; }?>> - เฟอร์นิเจอร์ เครื่องตกแต่งติดตั้งตรึงตรา และของใช้ต่างๆ
							</td>
							<td>
								<?php 
								if($costFurniture!=""){
								?>
									<div id="ss2"><input type="text" name="txt2" id="txt2" size="20" style="text-align:right;" onkeyup="javascript:updateSummary();" value="<?php echo $costFurniture; ?>"></div>
								<?php
								}else{
								?>
									<div id="s2"><input type="text" name="txt2" id="txt2" size="20" style="text-align:right;" onkeyup="javascript:updateSummary()"></div>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost3" id="cost3" value="1" onclick="javascript:updateSummary()" <?php if($costEngine!=""){ echo "checked"; }?>> - เครื่องจักร
							</td>
							<td>
								<?php 
								if($costEngine!=""){
								?>
									<div id="ss3"><input type="text" name="txt3" id="txt3" size="20" style="text-align:right;" onkeyup="javascript:updateSummary();" value="<?php echo $costEngine; ?>"></div>
								<?php
								}else{
								?>
									<div id="s3"><input type="text" name="txt3" id="txt3" size="20" style="text-align:right;" onkeyup="javascript:updateSummary()"></div>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost4" id="cost4" value="1" onclick="javascript:updateSummary()" <?php if($costStock!=""){ echo "checked"; }?>> - สต๊อกสินค้า
							</td>
							<td>
								<?php 
								if($costStock!=""){
								?>
									<div id="ss4"><input type="text" name="txt4" id="txt4" size="20" style="text-align:right;" onkeyup="javascript:updateSummary();" value="<?php echo $costStock; ?>"></div>
								<?php
								}else{
								?>
									<div id="s4"><input type="text" name="txt4" id="txt4" size="20" style="text-align:right;" onkeyup="javascript:updateSummary()"></div>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost5" id="cost5" value="1" onclick="javascript:updateSummary()" <?php if($costOther!=""){ echo "checked"; }?>> - อื่นๆ ระบุ... 
								<?php 
								if($costOther!=""){
								?>
									<input type="text" name="textOther" id="textOther1" size="30" value="<?php echo $textOther;?>">
								<?php }else{ ?>
									<input type="text" name="textOther" id="textOther" size="30">
								<?php } ?>
							</td>
							<td>
								<?php 
								if($costOther!=""){
								?>
									<div id="ss5"><input type="text" name="txt5" id="txt5" size="20" style="text-align:right;" onkeyup="javascript:updateSummary()" value="<?php echo $costOther;?>"></div>
								<?php }else{ ?>
									<div id="s5"><input type="text" name="txt5" id="txt5" size="20" style="text-align:right;" onkeyup="javascript:updateSummary()"></div>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="right"><b>รวมทุกประกันทั้งสิ้น</b></td>
							<td><div><input type="text" name="sumtotal"  id="sumtotal" readonly="true" size="20" style="text-align:right;" value="<?php echo $sumcost2;?>"></div></td>
						</tr>
						<tr>
							<td colspan="3"><hr color="#FFB3B3"></td>
						</tr>
						<tr>
							<td width="100" colspan="3"><b>:: ภัยเพิ่มพิเศษ ::</b><input type="button" id="addSpec" value="เพิ่มภัยพิเศษ"> <input type="text" name="insureSpec" id="insureSpec" size="80"><img src="images/save.png" width="18" height="18" id="submitadd" align="top" style="cursor:pointer;" title="บันทึกภัยเพิ่มพิเศษ"></td>	
						</tr>
						<tr>
							<td colspan="3">
							<div id="loadspec">
								<table width="100%" border="0"  align="center">
								<?php
								//ดึงภัยเพิ่มพิเศษขึ้นมาจากฐานข้อมูล
								$qryspecial=pg_query("SELECT auto_id, \"specialName\" FROM thcap_insure_insurespecial");
								$numspec=pg_num_rows($qryspecial);
								while($resspec=pg_fetch_array($qryspecial)){
									list($specid,$specname)=$resspec;
									
									$a=explode("-",$insureSpecial);
									for($i=0;$i<sizeof($a);$i++){
										if(trim($specname)==trim($a[$i])){
											$chk=1;
											break;
										}else{
											$chk=0;
										}
									}
								?>
									<tr>
										<td width="50"></td>
										<td colspan="2">
											<input type="checkbox" name="spec[]" value="<?php echo $specid;?>" <?php if($chk=="1"){ echo "checked"; }?>> <?php echo $specname;?>
										</td>
									</tr>
								<?php
								}
								if($numspec==0){
									echo "<tr><td colspan=3>==== ไม่พบข้อมูล ====</td></tr>";
								}
								?>
								</table>
							</div>
							</td>
						</tr>
						<tr>
							<td colspan="3"><hr color="#FFB3B3"></td>
						</tr>
						<tr>
							<td colspan="3"><b>:: เบี้ยรวม :</b> <input type="text" name="totalchip" id="totalchip" onkeypress="return check_number(event);" value="<?php echo $totalChip;?>"></td>	
						</tr>
						<tr>
							<td colspan="3"><b>:: เลขคิว :</b> <input type="text" name="numQ" id="numQ" value="<?php echo $numberQ;?>"></td>	
						</tr>
						<tr>
							<td colspan="3"><hr color="#FFB3B3"></td>
						</tr>
						<tr>
							<td align="center" colspan="3"><br>
								<input type="hidden" name="typesearch" value="<?php echo $typesearch;?>">
								<input type="hidden" name="securID" value="<?php echo $securID;?>">
								<input type="hidden" name="val" value="<?php echo $val;?>">
								<input type="hidden" name="cmd" value="addfirst">
								<input type="submit" value="  บันทึก  " id="submitbutton" onclick="return checkdata();">&nbsp;
								<input type="button" value="  กลับ  " onclick="location='frm_IndexChip.php';">
							</td>
						</tr>
						</table>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
</form>
</body>
</html>