<?php
include("../../config/config.php");
$currentdate=nowDate();
$iduser = $_SESSION["av_iduser"];

$fromaccpaper=pg_escape_string($_GET["fromaccpaper"]);
$select_bid=pg_escape_string($_GET["accserial"]);//ช่องทางบัญชี เลขที่

$save_id = pg_escape_string($_GET["save_id"]);
$qry_from_save = pg_query("select save_name, ledger_month, ledger_year from account.thcap_ledger_save_head where save_id = '$save_id' ");
$save_name = pg_result($qry_from_save,0);
$month1 = pg_result($qry_from_save,1);
$year1 = pg_result($qry_from_save,2);

$date1=pg_escape_string($_GET["date1"]);
/*$month1=pg_escape_string($_GET["month1"]);
$year1=pg_escape_string($_GET["year1"]);*/
///////////////////////////////////////////
if($datepicker==""){
	$datepicker=$currentdate;
	$datefrom=$currentdate;
	$dateto=$currentdate;
}
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];
if($emplevel<=1){
	$empl=1;
}
else{
	$empl=2;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) สมุดบัญชีแยกประเภท</title>
	
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
	$("#datepicker").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#datefrom").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#dateto").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	<?php if($fromaccpaper=='1'){ ?>
	$("#date1").val(<?php echo $date1;?>);
	$("#bankint").val(<?php echo $select_bid;?>);
	$("#year1").val(<?php echo $year1;?>);
	$("#month1").val('<?php echo $month1;?>');	
	window.document.frm1.Search.click();
	<?php }?>
});
function check(){
	var i=0;
	var checkerror=true;
	var theMessage = "กรุณาเลือกเงื่อนไข ที่จะค้นหาให้ครบ: \n-----------------------------------\n";
	var selectdate=false;
	var r=document.getElementsByName("date1");
	var cancel = "";
	while(i<r.length){
		if(r[i].checked==true){
			selectdate=true;
			if(r[i].value=='1'){
				if($("#month1").val()==''){
					checkerror=false;
					theMessage = theMessage + "\n --> กรุณาเลือกเดือนที่จะค้นหา";				
				}				
			}
			break;}
		else{i++;}
	}
	if($("#s_cancel").is(':checked')){
		cancel = "on";
	}else{
		cancel = "off";
	}
	if(($("#bankint").val()!='')&&(selectdate==true)&&(checkerror==true) ){
		$("#detail").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$("#detail").load("save_acc_frm_listdetail.php?date1="+r[i].value+"&bankint="+$("#bankint").val()+"&year1="+$("#year1").val()+"&month1="+$("#month1").val()+"&year2="+$("#year2").val()
		+"&datepicker="+$("#datepicker").val()+"&datefrom="+$("#datefrom").val()+"&dateto="+$("#dateto").val()+"&cancel="+cancel+"&save_id="+$("#save_id").val());
		}
	else{
		if($("#bankint").val()==''){
			theMessage = theMessage + "\n --> กรุณาเลือกบัญชี";	
		}
		
	alert(theMessage);
	}
}
</script>
<body>
<?php
	if($fromaccpaper == '1')
	{
		echo "<h1>ข้อมูลบัญชีแยกประเภท เดือน $month1 ปี $year1</h1>";
		
		// หาชื่อสมุดบัญชี
		$qry_select_acc = pg_query("SELECT \"accBookName\" FROM account.\"V_all_accBook\" WHERE \"accBookserial\" = '$select_bid' ");
		$select_acc = pg_result($qry_select_acc,0);
		
		echo "<h2>$select_acc</h2>";
		echo "<h3>จากข้อมูลที่บันทึกไว้ \"$save_name (เดือน $month1 ปี $year1)\"</h3>";
	}
	else { ?>
	<div  align="center">
			<h2>(THCAP) สมุดบัญชีแยกประเภท</h2>
	</div>
<?php } ?>	
<div style="text-align:right;"><input type="button" value=" Close " onclick="window.close();"></div>
<form name="frm1" id="frm1" action="" method="post"> 
<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
<table align="center">
	<tr>
		<td><b>บัญชี  :</b></td>
		<td><select name="bankint" id="bankint" disabled>
				<?php 	
						$sql_bank = pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
							echo "<option value=\"\">- เลือกช่องทาง-</option>";
						while($res_name = pg_fetch_array($sql_bank)){						
							$Acserial = $res_name["accBookserial"];
							$AcID = $res_name["accBookID"];
							$AcName = $res_name["accBookName"];	?>
							<option value="<?php echo $Acserial ?>"<?php if($select_bid==$res_name["accBookserial"]){ echo "selected";} ?>>
							<?php echo $AcID .":". $AcName; ?>
							<?php echo "</option>";
							
							
						} 
				?></select>
		</td>		
		<td><b>ค้นหาจาก:</b></td>		
		<td hidden><input type="radio" id="alldate" name="date1"  value="0" <?php if($date1=="" || $date1=="0"){ echo "checked"; }?> /></td>
		<td hidden>ทุกช่วงเวลา</td></tr>
		<tr hidden>
			<td colspan="3"></td>				
			<td><input type="radio" id="date4" name="date1"  value="4" <?php if($date1=="4"){ echo "checked"; }?> /></td>
			<td>ตามวันที่ :</td>
			<td>
			<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">&nbsp;	
			</td>
		</tr>

		<tr>
		<td colspan="3"></td>
		<td>
		<input type="radio" id="date1" name="date1"  value="1" <?php if($date1=="1"){ echo "checked"; }?> /></td>
		<td>ตามเดือน:</td>
		<td><select name="month1" id="month1" disabled> 
				<option value="">--เลือกเดือน--</option>
				<option value="01" <?php if($month1=="01" or $month1=="1") echo "selected";?>>มกราคม</option>
				<option value="02" <?php if($month1=="02" or $month1=="2") echo "selected";?>>กุมภาพันธ์</option>
				<option value="03" <?php if($month1=="03" or $month1=="3") echo "selected";?>>มีนาคม</option>
				<option value="04" <?php if($month1=="04" or $month1=="4") echo "selected";?>>เมษายน</option>
				<option value="05" <?php if($month1=="05" or $month1=="5") echo "selected";?>>พฤษภาคม</option>
				<option value="06" <?php if($month1=="06" or $month1=="6") echo "selected";?>>มิถุนายน</option>
				<option value="07" <?php if($month1=="07" or $month1=="7") echo "selected";?>>กรกฎาคม</option>
				<option value="08" <?php if($month1=="08" or $month1=="8") echo "selected";?>>สิงหาคม</option>
				<option value="09" <?php if($month1=="09" or $month1=="9") echo "selected";?>>กันยายน</option>
				<option value="10" <?php if($month1=="10") echo "selected";?>>ตุลาคม</option>
				<option value="11" <?php if($month1=="11") echo "selected";?>>พฤศจิกายน</option>
				<option value="12" <?php if($month1=="12") echo "selected";?>>ธันวาคม</option>
		</select>
		<td>ปี  </td>		
		<td><select name="year1" id="year1" disabled> 	

				<?php $datenow1 = nowDate();
				list($year,$month,$day)=explode("-",$datenow1);	
				$year0=$year-10;
				if($year0<2013){
					$year0=2013;				
				}
				$year1=$year+3;
				for($t=$year0;$t<=$year1;$t++){
					if($t == $year){ ?> 
						<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
					<?php } else{ ?>
						<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
					<?php  
					}
				} 
				?>		
			</select></td>
		</td>
	</tr>
	<tr hidden><td></td>
		<td></td>
		<td></td>
		<td><input type="radio" id="date2" name="date1"  value="2"  <?php if($date2=="2"){ echo "checked"; }?> /></td>
		<td>ปี :</td>
		<td>
			<select name="year2" id="year2"> 	

				<?php $datenow1 = nowDate();
				list($year,$month,$day)=explode("-",$datenow1);
				$year0=$year-10;
				if($year0<2013){
					$year0=2013;				
				}
				$year1=$year+3;
				for($t=$year0;$t<=$year1;$t++){
					if($t == $year){ ?> 
						<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
					<?php } else{ ?>
						<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
					<?php  
					}
				} 
				?>	
			</select></td>
	
		</td>
	</tr>
	<tr hidden>
			<td colspan="3"></td>	
			<td><input type="radio" id="date3" name="date1"  value="3" <?php if($date1=="3"){ echo "checked"; }?> /></td>			
			<td>ตามช่วง: จาก </td>
			<td>
				<input type="text" id="datefrom" name="datefrom" value="<?php echo $datefrom; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
			</td>
			<td>ถึง</td>
			<td>
				<input type="text" id="dateto" name="dateto" value="<?php echo $dateto; ?>" size="15" readonly="true" style="text-align:center">&nbsp;		
			</td>
		</tr>
	<tr><td colspan="8" align="center">
	<input type="checkbox" name="s_cancel" id="s_cancel" />แสดงรายการที่ยกเลิก
	</td></tr>
	<tr>
		<td colspan="8" align="center">
			<input type="hidden" name="val" value="1"/>
			<input type="hidden" id="save_id" name="save_id" value="<?php echo $save_id; ?>"/>
			<input type="button" id="Search" name="Search"  value="ค้นหา" onclick="check();"/>
		</td>
	</tr>
</table>
</fieldset><br>
<font color="red"><b>
* การไม่แสดงรายการยกเลิก อาจทำให้ยอดคงเหลือไม่ต่อเนื่อง
</b></font>
</form>
<div name="detail" id="detail">
</div>
</body>
</html>