<?php
session_start();
if($contractID2!=""){
	$contractID=$contractID2;
}else{
	include("../../config/config.php");
	$contractID=trim($_GET["contractID"]);
}

if($contractID==""){
	echo "<script>location.href='frm_Index.php';</script>";
	exit();
}
//ตรวจสอบว่ารายการนี้รออนุมัติอยู่หรือไม่
$qrychk=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='2'");
if(pg_num_rows($qrychk)>0){
	echo "<center><h2>รายการนี้กำลังรออนุมัติ กรุณาทำรายการหลังจากได้รับการอนุมัติแล้ว</h2></center>";
	exit();
}

?>
<link href="../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet">
<script src="../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
<script src="../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.js"></script>
<style>
#sortable { list-style-type: none; margin: 0 auto; padding: 0; width: 80%; }
#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 12px; height: 18px; }
#sortable li span { position: absolute; margin-left: -1.3em; }

#sortable1 { list-style-type: none; margin: 0 auto; padding: 0; width: 80%; }
#sortable1 li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 12px; height: 18px; }
#sortable1 li span { position: absolute; margin-left: -1.3em; }

#sortable2 { list-style-type: none; margin: 0 auto; padding: 0; width: 80%; }
#sortable2 li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 12px; height: 18px; }
#sortable2 li span { position: absolute; margin-left: -1.3em; }

</style>
<script language="javascript">
$(document).ready(function(){ 	
	$( "#sortable" ).sortable();
	$( "#sortable" ).disableSelection();

	$( "#sortable1" ).sortable();
	$( "#sortable1" ).disableSelection();

	$( "#sortable2" ).sortable();
	$( "#sortable2" ).disableSelection();
	
	function slideout(){
		setTimeout(function(){
			$("#response").slideUp("slow", function () {
			}); 
		}, 2000);
	}
	
	$("#response").hide();
	
	$(function(){
		$("#list ul").sortable({ opacity: 0.8, cursor: 'move',update: function() {	
				var order = $('#sortable').sortable("serialize")+'&'+ $('#sortable1').sortable("serialize")+'&'+$('#sortable2').sortable("serialize") + '&update=update&contractID='+'<?php echo $contractID;?>'; 
				$.post("process_change.php", order, function(theResponse){
					$("#response").html(theResponse);
					$("#response").slideDown('slow');
					slideout();
				}); 															 
			}								  
		});
	});
	
	$('#save').click(function(){
		if(confirm('ยืนยันการเปลี่ยนแปลงข้อมูล')==true){
			$.post('process_change.php',{
				update:'confirm',
				contractID: '<?php echo $contractID;?>',
			},
			function(data){
				if(data==1){
					alert("ยืนยันข้อมูลเรียบร้อยแล้ว");
					window.location='frm_Index.php?contractID='+'<?php echo $contractID;?>';
				}else if(data==2){
					alert("ไม่พบรายการที่แก้ไข");
				}else{
					alert("ผิดพลาดไม่สามารถยืนยันได้ "+data);
				}
			});
		}
	});
	
	$('#clear').click(function(){
		if(confirm('ยืนยันการยกเลิกการเปลี่ยนแปลงข้อมูล')==true){
			$.post('process_change.php',{
				update:'clear',
				contractID: '<?php echo $contractID;?>',
			},
			function(data){
				if(data==1){
					alert("ยกเลิกข้อมูลเรียบร้อยแล้ว");
					window.location='frm_Index.php?contractID='+'<?php echo $contractID;?>';
				}else if(data==2){
					alert("ไม่พบรายการที่แก้ไข");
				}else{
					alert("ผิดพลาดไม่สามารถยกเลิกได้ "+data);
				}
			});
		}
	});
	
	
});	
</script>
<div align="right" style="font-weight:bold;"><font color="red">*</font> <u>หมายเหตุ</u> ทำการเปลี่ยนแปลงลำัดับ โดยการลากที่ชื่อลูกค้าสลับตำแหน่งกัน</div>
<fieldset><legend><B>เลขที่สัญญา <span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></B></legend>
<?php
//ตรวจสอบว่ารายการนี้ว่าได้มีการแก้ไขแล้วยังไม่ยืนยันหรือไม่
$qrychk=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='3'");
if(pg_num_rows($qrychk)>0){
	echo "<div align=left><font color=red><b>* <span style=\"background-color:yellow\">เลขที่สัญญานี้มีการเปลี่ยนแปลงข้อมูล</span></b> แล้วยังไม่ได้ยืนยัน  ข้อมูลที่แสดงนั้นคือข้อมูลที่ยังไม่ถูกเปลี่ยนแปลง<br>
	หากทำการยืนยัน ระบบจะนำข้อมูลที่ถูกเปลี่ยนแปลงมาบันทึก หากไม่ต้องการให้ข้อมูลที่เปลี่ยนแปลงค้างในระบบ กรุณากดปุ่ม \"ล้างข้อมูล\"</font></div>";
}
?>

<div id="list">
<div style="padding-top:10px;">
<fieldset><legend><B>ผู้กู้หลัก</B></legend>
<ul id="sortable">
<?php
	$qrycontact=pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0' order by \"ranking\",\"CusState\"");
	$nub=pg_num_rows($qrycontact);
	$i=0;
	while($row=pg_fetch_array($qrycontact)){
		$i++;
		$contractID = $row['contractID'];
		$CusState = $row['CusState'];
		$CusID = $row['CusID'];
		$ranking = $row['ranking'];
		$thcap_fullname = $row['thcap_fullname'];
		
		?>
		<li id="arr_<?php echo $CusID; ?>" style="background-color:#f9e1e3;border:1px solid #FFC1C1;">
		<span style="cursor:pointer;padding-left:10px;"><?php echo "$i. $thcap_fullname";?></span>
		</li>
	<?php
	}
?>
</ul>
<?php
if($nub==0){
	echo "<div align=\"center\"><h2>--ไม่พบข้อมูล--</h2></div>";
}
?>
</fieldset> 
</div>


<div style="padding-top:10px;">
<fieldset><legend><B>ผู้กู้ร่วม</B></legend>
<ul id="sortable1">
<?php
	$qrycontact1=pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='1' order by \"ranking\",\"CusState\"");
	$nub1=pg_num_rows($qrycontact1);
	$i=0;
	while($row1=pg_fetch_array($qrycontact1)){
		$i++;
		$contractID = $row1['contractID'];
		$CusState = $row1['CusState'];
		$CusID = $row1['CusID'];
		$ranking = $row1['ranking'];
		$thcap_fullname = $row1['thcap_fullname'];
		
		?>
		<li id="arr1_<?php echo $CusID; ?>" style="background-color:#FFEFD5;border:1px solid #F4A460;">
		<span style="cursor:pointer;padding-left:10px;"><?php echo "$i. $thcap_fullname ($CusID)";?></span>
		</li>
	<?php
	}
?>
</ul>
<?php
if($nub1==0){
	echo "<div align=\"center\"><h2>--ไม่พบข้อมูล--</h2></div>";
}
?>
</fieldset> 
</div>

<div style="padding-top:10px;">
<fieldset><legend><B>ผู้ค้ำ</B></legend>
<ul id="sortable2">
<?php
	$qrycontact2=pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='2' order by \"ranking\",\"CusState\"");
	$nub2=pg_num_rows($qrycontact2);
	$i=0;
	while($row2=pg_fetch_array($qrycontact2)){
		$i++;
		$contractID = $row2['contractID'];
		$CusID = $row2['CusID'];
		$ranking = $row2['ranking'];
		$thcap_fullname = $row2['thcap_fullname'];
		
		?>
		<li id="arr2_<?php echo $CusID; ?>" style="background-color:#FFEFD5;border:1px solid #F4A460;">
		<span style="cursor:pointer;padding-left:10px;"><?php echo "<b>$i.</b> $thcap_fullname ($CusID)";?></span>
		</li>
	<?php
	}
?>
</ul>
<?php
if($nub2==0){
	echo "<div align=\"center\"><h2>--ไม่พบข้อมูล--</h2></div>";
}
?>
</fieldset> 
</div>
<div id="response"></div>
<div style="text-align:center;padding:20px;">
<input type="button" id="save" value="ยืนยันการเปลี่ยนแปลงข้อมูล" style="width:200px;height:40px;">
<input type="button" id="clear" value="ยกเลิกรายการที่เปลี่ยนแปลง" style="width:200px;height:40px;">
</div>
</div>
</fieldset>


