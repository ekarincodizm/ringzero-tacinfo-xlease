<?php
include("../../config/config.php");
include("../function/thaitxtdate.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user = $_SESSION["av_iduser"];
$usersql = pg_query("SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'  ");
$reuser = pg_fetch_array($usersql);
$leveluser = $reuser['emplevel'];

$nowDateTime = nowDateTime();
list($date,$time) = explode(" ",$nowDateTime);// แยกวันที่ และ เวลา
list($hour,$min,$sec) = explode(":",$time);//แยก ชั่วโมง นาที วินาที


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>แจ้งปรับปรุงเมนู</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){	
	$("#datelimitstart").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#datelimitend").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
});

function not(frm){
var con = $("#chkchoiseapp").val();
var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("appchk"+num)){
			if(document.getElementById("appchk"+num).checked){
				numchk+=1;				
			}	
		}	
	}
	if(numchk == 0){
		alert("กรุณาเลือกเมนูที่ต้องการอนุมัติ");
		return false;
	}else{
		frm.action="reason_notapp.php";
		frm.submit();
		document.myform.submit.disabled='true';
		return true;
	}	
}
function app(frm){
var con = $("#chkchoiseapp").val();
var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("appchk"+num)){
			if(document.getElementById("appchk"+num).checked){
				numchk+=1;				
			}	
		}	
	}
	if(numchk == 0){
		alert("กรุณาเลือกเมนูที่ต้องการอนุมัติ");
		return false;
	}else{
		frm.action="process_warning.php";
		frm.submit();
		document.myform.submit.disabled='true';
		return true;
	}	
}

function chklist(){

var con = $("#chkchoise").val();

var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("idmenu"+num)){
			if(document.getElementById("idmenu"+num).checked){
				numchk+=1;				
			}	
		}	
	}
	if(numchk == 0){
		alert("กรุณาเลือกเมนูที่ต้องการแจ้งปิด");
		return false;
	}else{ 
			var timenow = "<?php echo nowDateTime(); ?>";
			var timestart = $("#datelimitstart").val()+" "+$("#hourstart").val()+":"+$("#minutsstart").val()+":00";
			var timeend = $("#datelimitend").val()+" "+$("#hourend").val()+":"+$("#minutsend").val()+":00";
			if(timestart >= timeend){
				alert("กรุณาเลือกวันที่เปิดใช้มากกว่าวันที่ปิดใช้");
				return false;
			
			}else{
					if(confirm('ยืนยันการร้องขอปิดปรับปรุงเมนู')==true){			
						return true;
					}else{ 
						return false;
					}
			}		
	}	
}
</script>
</head>
<div style="margin-top:1px" ></div>
<body>
<?php if($leveluser <= 1){ ?>	
<form name="myform" method="post">
<table width="50%" frame="box" cellspacing="0" cellpadding="0"  align="center">		
		<tr>
			<td bgcolor="#7A8B8B" align="center" height="25px" colspan="6">
				<h1><b><font color="white">อนุมัติการแจ้งปิดปรับปรุงเมนู</font></b><h1>
			</td>
		</tr>
</table>		
<table width="50%" frame="box" cellspacing="0" cellpadding="0"  align="center">				
		<tr bgcolor="#B4CDCD">
			<th>ชื่อเมนู</th>
			<th>กำหนดวันที่ปิด</th>
			<th>กำหนดวันที่เปิด</th>
			<th>รวมระยะเวลา</th>
			<th>ผู้แจ้งปิด</th>
			<th>อนุมัติ</th>
		</tr>
<?php
	$appsql = pg_query("SELECT * FROM f_menu_warning where  appstatus = '0' order by datetime_submit DESC");
	$approw = pg_num_rows($appsql);
	if($approw > 0){
	$i=0;
	while($appre = pg_fetch_array($appsql)){ 
	$id_menuapp = $appre['id_menu'];
	$id_user = $appre['id_user'];
	$fmenuwarID = $appre['fmenuwarID'];
	$sqlmenu = pg_query("SELECT name_menu FROM f_menu where id_menu = '$id_menuapp'");
	$sqlmenure = pg_fetch_array($sqlmenu);
	$sqluser = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$id_user'");
	$squserre = pg_fetch_array($sqluser);
	
	
	$timecal = (strtotime($appre['e_time']) - strtotime($appre['s_time']));
	$timedetail = showUserSpend($timecal);
	
	$i++;
		if($i%2==0){
			echo "<tr bgcolor=#D1EEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#D1EEEE';\" align=center>";
		}else{
			echo "<tr bgcolor=#E0FFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E0FFFF';\" align=center>";
		}
	?>
			<td><?php echo $sqlmenure['name_menu'];  ?></td>
			<td><?php echo $appre['s_time'];  ?></td>
			<td><?php echo $appre['e_time'];  ?></td>
			<td><?php echo $timedetail; ?></td>
			<td><?php echo $squserre['fullname'];  ?></td>
			<td><input type="checkbox" name="appchk[]" id="appchk<?php echo $i; ?>" value="<?php echo $fmenuwarID  ?>"></td>
		
		
		</tr>
	
<?php	$min1 = "";
		$hr1 = "";

}
?>		
		<tr bgcolor="#B4CDCD">
			<input type="hidden" name="hdtype" value="approve">
			<td colspan="6" align="right" ><input type="button" onclick="not(this.form);" value="ไม่อนุมัติ"><input type="button" value="อนุมัติ" onclick="app(this.form);" ></td>
		</tr>
<?php }else{ ?>		

		<tr>
			<input type="hidden" name="hdtype" value="approve">
			<td colspan="6" align="center"><h2> ไม่มีรายการรออนุมัติ </h2></td>
		</tr>

<?php } ?>
</table>
<input type="hidden" value="<?php echo $i; ?>" id="chkchoiseapp">
</form>
<p>
<?php } ?>
<form action="process_warning.php" method="post">
<table width="60%" frame="box" cellspacing="0" cellpadding="0"  align="center">		
		<tr>
			<td bgcolor="#CDB79E" align="center" height="25px" colspan="4">
				<h1><b>แจ้งปรับปรุงเมนู</b><h1>
			</td>
		</tr>
</table>
<table width="60%" frame="box" cellspacing="0" cellpadding="0"  align="center">
<tr>
	<td width="20%" align="right">จะปิดปรับปรุงวันที่ : </td>
	<td width="15%"><input type="text" name="datelimitstart" id="datelimitstart" size="10" value="<?php echo $date; ?>"></td>
	<td width="60%">เวลา : <select name="hourstart" id="hourstart">		
<?php		
		for($i=0;$i<=24;$i++){
			if($i < 10){
				$hh = "0".$i;
			}else{
				$hh = $i;
			}
?>			
			<option value="<?php echo $hh ?>" <?php if($hour == $hh){ echo "selected"; } ?> ><?php echo $hh ?></option>
<?php			
		}
?>		
		</select> นาฬิกา 
	
		<select name="minutsstart" id="minutsstart">
<?php		
		for($i=0;$i<60;$i++){
			if($i < 10){
				$mm = "0".$i;
			}else{
				$mm = $i;
			}
?>
<option value="<?php echo $mm ?>" <?php if($min == $mm){ echo "selected"; } ?>><?php echo $mm ?></option>
<?php
		}
?>		
		</select> นาที
	</td>
</tr>
<tr>	
	<td align="right">จะเปิดใช้วันที่ : </td>
	<td><input type="text" name="datelimitend" id="datelimitend" size="10" value="<?php echo $date; ?>"></td>
	<td >เวลา : <select name="hourend" id="hourend">		
<?php		
		for($i=0;$i<=24;$i++){
			if($i < 10){
				$hh = "0".$i;
			}else{
				$hh = $i;
			}
?>
<option value="<?php echo $hh ?>" <?php if($hour == $hh){ echo "selected"; } ?>><?php echo $hh ?></option>
<?php
		}
?>		
		</select> นาฬิกา 
	
		<select name="minutsend" id="minutsend">
<?php		
		for($i=0;$i<60;$i++){
			if($i < 10){
				$mm = "0".$i;
			}else{
				$mm = $i;
			}
?>
<option value="<?php echo $mm ?>" <?php if($min == $mm){ echo "selected"; } ?>><?php echo $mm ?></option>
<?php
		}
?>		
		</select> นาที
	</td>	
</tr>
<tr>
	<td colspan="2" align="right">คำเตือนที่จะแสดงให้ผู้ใช้เมนูเห็น :</td><td><input type="text" name="textdetail" size="65"></td>
</tr>
</table>

<table width="60%" frame="box" cellspacing="0" cellpadding="0"  align="center">		
		<tr>
			<td colspan="3" align="right" bgcolor="#8B795E"><input type="submit" value=" ดำเนินการ " onclick="return chklist();"></td>
		</tr>
		<tr bgcolor="#CDB38B">
			<th width="15%">รหัสเมนู</th>
			<th width="40%">ชื่อเมนู</th>
			<th width="45%">ปิดปรับปรุง</th>
		</tr>	

<?php 

$sql = pg_query("SELECT id_menu, name_menu, status_menu, path_menu FROM f_menu order by name_menu");
$i=0;
while($result = pg_fetch_array($sql)){  
$id_menu = $result['id_menu'];
$name_menu = $result['name_menu'];

$i++;
		if($i%2==0){
			echo "<tr bgcolor=#EECFA1 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EECFA1';\" align=center>";
		}else{
			echo "<tr bgcolor=#FFDEAD onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFDEAD';\" align=center>";
		}
echo "

		<td align=\"left\">$id_menu</td>
		<td align=\"left\">$name_menu</td>";
	
		$sqlchk = pg_query("SELECT * FROM f_menu_warning where \"fmenuwarID\" = (SELECT MAX(\"fmenuwarID\") FROM f_menu_warning where id_menu = '$id_menu' and e_time > '$nowDateTime') ");
		$rowchk = pg_num_rows($sqlchk);
		$rechk = pg_fetch_array($sqlchk);
		$fmenuwarID1 = $rechk['fmenuwarID'];
		$stimeshow = $rechk['s_time'];
		if($rowchk > 0){
			if($rechk['appstatus'] == '0'){
				echo "<td align=\"center\"><a onclick=\"javascript:popU('changetime.php?fmenuid=$fmenuwarID1&state=chgwait','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=720,height=250')\" style=\"cursor:pointer\">ขอเลื่อน! (รายการนี้อยู่ในระหว่างรอการอนุมัติ)</a></td></tr>";
			}else if($rechk['appstatus'] == '1' &&  $stimeshow > $nowDateTime){
				echo "<td align=\"center\"><a onclick=\"javascript:popU('changetime.php?fmenuid=$fmenuwarID1&state=chgwait','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=720,height=250')\" style=\"cursor:pointer\">ขอเลื่อน! (อนุมัติแล้ว จะปิดใช้งานวันที่ $stimeshow)</a></td></tr>";
			}else if($rechk['appstatus'] == '1' &&  $stimeshow < $nowDateTime){
				$endtimeori = date($rechk['e_time']);						
				$nowdatecheck = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
				
				// นำวันเวลามาลบกัน โดยใช้ database คำนวณ
				$qry_deftime1 = pg_query("select ('$endtimeori'::timestamp without time zone - '$nowdatecheck'::timestamp without time zone)::time");
				$deftime1 = pg_fetch_result($qry_deftime1,0);

				// แยก ชั่วโมง กับ นาที ออกมา
				list($deftime1_h, $deftime1_m) = explode(":", $deftime1);
				
				// หาว่าทั้งหมดเป็นกี่นาที
				if($deftime1_h > 0)
				{
					$min11 = ($deftime1_h * 60) + $deftime1_m;
				}
				else
				{
					$min11 = $deftime1_m * 1; // คูณ 1 เพื่อกรณีที่เป็นเลข 2 หลัก แล้วหลักหน้าเป็น 0 เช่น 03 จะได้ตัดออก เหลือเป็น 3 เท่านั้น
				}
				
				$mm1 = "";
				if($min11 <= 15 and $min11 > 0){

					if($min11 != ""){ $mm1 = "<font color=\"red\">เหลือประมาณ ".$min11." นาที</font>";}
				}
				echo "<td align=\"center\"><a onclick=\"javascript:popU('changetime.php?fmenuid=$fmenuwarID1&state=chgapp','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=720,height=250')\" style=\"cursor:pointer\">ขอเลื่อน! (อนุมัติแล้ว อยู่ระหว่างการปิดเมนูเพื่อปรับปรุงแล้ว) <br>$mm1</a></td></tr>";
			}else{
				echo "<td align=\"center\"><input type=\"checkbox\" value=\"$id_menu\" name=\"idmenu[]\" id=\"idmenu$i\"></td></tr>";
			}
		}else{
			echo "<td align=\"center\"><input type=\"checkbox\" value=\"$id_menu\" name=\"idmenu[]\" id=\"idmenu$i\"></td></tr>";
		}
}		
?>
<tr>
	<input type="hidden" name="hdtype" value="insert">
	<td colspan="3" align="right" bgcolor="#8B795E"><input type="submit" value=" ดำเนินการ " onclick="return chklist();"></td>
	<input type="hidden" value="<?php echo $i; ?>" id="chkchoise">
</tr>
</table>
</form>	
</body>
</html>