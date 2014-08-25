<?php
include("../../config/config.php");

$yy=pg_escape_string($_POST["yy"]);
$mm=pg_escape_string($_POST["mm"]);
$chk_all=pg_escape_string($_POST["chk_all"]);
$from_p=pg_escape_string($_POST["from_p"]);
if($mm==""){
$mm = date("m");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) บัญชีกระดาษทำการ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/number.js"></script>
</head>
<script type="text/JavaScript">
$(document).ready(function(){	
	<?php if($from_p=='1'){ ?>
	$("#yy").val('<?php echo $yy;?>');
	$("#mm").val('<?php echo $mm;?>');
	if('<?php echo $chk_all=='yes'?>'){		
		document.getElementById('chk_all').checked = true;
	}
	window.document.frm1.btn1.click();
	<?php }?>
	
});

function click_btn1()
{
	var chk_all="";
	if($("#chk_all").is(':checked')){
		chk_all = "yes";
	}else{
		chk_all = "no";
	}
	$('#panel').empty();
	$('#panel').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$("#panel").load("frm_list_show.php?yy="+ $("#yy").val() +"&ty="+ $("#ty").val() +"&mm="+ $("#mm").val()+"&chk_all="+chk_all);
}

function click_btn2()
{
	var chk_all="";
	if($("#chk_all_from_save").is(':checked')){
		chk_all = "yes";
	}else{
		chk_all = "no";
	}
	$('#panel').empty();
	$('#panel').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$("#panel").load("frm_save_show.php?save_id="+ $("#save_id").val()+"&chk_all="+chk_all);
}

function refreshListBox() // refresh ประเภทสินทรัพย์ ทั้งหมด
{  
	var dataSaveList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataSaveList  
		  url: "dataForSaveList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1
		  async: false  
	}).responseText;
	
	$("select#save_id").html(dataSaveList); // นำค่า dataSaveList มาแสดงใน listbox ที่ชื่อ save_id..
}
</script>

<body>
<div  align="center">
			<h2>(THCAP) บัญชีกระดาษทำการ</h2>
</div>
<div style="text-align:right;"><input type="button" value=" Close " onclick="window.close();"></div>
<form name="frm1" id="frm1"  action="" method="post"> 
<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
	<div style="margin:5px" align="center">
		<table width="100%">
			<tr>
				<td align="center" width="50%">
					<h3>ค้นหาจากข้อมูลปัจจุบัน</h3>
					<b>ปี</b>
					<select name="yy" id="yy"> 	

									<?php $datenow1 = nowDate();
									list($year,$month,$day)=explode("-",$datenow1);
									$year0= $year +10;
									for($t=2013;$t<=2023;$t++){
									if($t == $year){ ?> 
									<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
									<?php		}else{ ?>
									<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
									<?php  
													}
									} 
									?>	
					</select>

					<b>เดือน</b>
					<select name="mm" id="mm">
						<option value="01" <?php if($mm=="01" or $mm=="1") echo "selected";?>>มกราคม</option>
						<option value="02" <?php if($mm=="02" or $mm=="2") echo "selected";?>>กุมภาพันธ์</option>
						<option value="03" <?php if($mm=="03" or $mm=="3") echo "selected";?>>มีนาคม</option>
						<option value="04" <?php if($mm=="04" or $mm=="4") echo "selected";?>>เมษายน</option>
						<option value="05" <?php if($mm=="05" or $mm=="5") echo "selected";?>>พฤษภาคม</option>
						<option value="06" <?php if($mm=="06" or $mm=="6") echo "selected";?>>มิถุนายน</option>
						<option value="07" <?php if($mm=="07" or $mm=="7") echo "selected";?>>กรกฎาคม</option>
						<option value="08" <?php if($mm=="08" or $mm=="8") echo "selected";?>>สิงหาคม</option>
						<option value="09" <?php if($mm=="09" or $mm=="9") echo "selected";?>>กันยายน</option>
						<option value="10" <?php if($mm=="10") echo "selected";?>>ตุลาคม</option>
						<option value="11" <?php if($mm=="11") echo "selected";?>>พฤศจิกายน</option>
						<option value="12" <?php if($mm=="12") echo "selected";?>>ธันวาคม</option>  
					</select>

					<br>
					<input type="checkbox" name="chk_all" id="chk_all" />แสดงสมุดทั้งหมด รวมถึงสมุดที่ไม่ได้ใช้งาน
					<br>
					<input type="button" name="btn1" id="btn1" value="ค้นหา" onclick="click_btn1();">
				</td>
				<td align="center" width="50%">
					<h3>ค้นหาจากข้อมูลที่บันทึกไว้</h3>
					<select name="save_id" id="save_id">
					<?php
						$qry_from_save = pg_query("select * from account.thcap_ledger_save_head order by ledger_year DESC, ledger_month DESC, \"doerStamp\" DESC ");
						$row_from_save = pg_num_rows($qry_from_save);
						while($res_from_save = pg_fetch_array($qry_from_save))
						{
							$save_id = $res_from_save["save_id"];
							$save_name = $res_from_save["save_name"];
							$ledger_month = $res_from_save["ledger_month"];
							$ledger_year = $res_from_save["ledger_year"];
							
							echo "<option value=\"$save_id\">$save_name(เดือน $ledger_month ปี $ledger_year)</option>";
						}
					?>
					</select>
					<br>
					<input type="checkbox" name="chk_all_from_save" id="chk_all_from_save" />แสดงสมุดทั้งหมด รวมถึงสมุดที่ไม่ได้ใช้งาน
					<br>
					<input type="button" name="btn1" id="btn2" value="ค้นหา" onclick="click_btn2();" <?php if($row_from_save == 0){echo "disabled";} ?> />
					<input type="button" name="updatelistbox" id="updatelistbox" value="click" onClick="refreshListBox();" hidden>
				</td>
			</tr>
		</table>
	</div>
</fieldset><br>


</form>
<div id="panel" name="panel" ></div>
</body>
</html>