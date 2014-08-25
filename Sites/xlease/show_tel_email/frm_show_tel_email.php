<?php 
session_start();
include("../config/config.php");
$find = pg_escape_string($_POST["find"]);
$department_find = pg_escape_string($_POST["chk_department"]);
$txtname_emp_find = pg_escape_string($_POST["txtname_emp"]);
if($find==""){$find="0";}
if($txtname_emp_find !=""){  $emp_find =explode("#",$txtname_emp_find);$str_empId=$emp_find[0];}

if($find=="1"){
	$txtfind=$department_find;
}
else if(($find=="2")and ($txtname_emp_find !="")){
	$txtfind=$str_empId;	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<html>
<head>
	<title> เบอร์โทรศัพท์และ E-mail พนักงาน</title>

	
<script type="text/javascript">
$(document).ready(function(){
	$("#txtname_emp").autocomplete({
		source: "s_emp.php",
		minLength:1
	});
	<?php if($find=="0"){ ?>
		$("#chk_department").hide();     
		$("#txtname_emp").hide(); 
	<?php } else if($find=="1"){?>
		$("#chk_department").show();     
		$("#txtname_emp").hide(); 
	<?php } else if($find=="2"){?>
		$("#chk_department").hide();    
		$("#txtname_emp").show(); 	
	<?php }?>
});
function find_data(no){
	
	document.getElementById("txtname_emp").value= '';
	document.getElementById("chk_department").value= '';
	//ทั้งหมด
	if(no=='0'){
		$("#chk_department").hide();
		$("#txtname_emp").hide();		
		}
	else if(no=='1'){
		$("#chk_department").show();
		$("#txtname_emp").hide();
	}
	else if(no=='2'){
		$("#chk_department").hide();
		$("#txtname_emp").show();
	}
};
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
</head>
<body>
<div align="right">
	<img src="images/icon_pdf.gif" height="20px"><a href="javascript:popU('frm_print_PDF.php?data_find=<?php echo $txtfind; ?>&type=O&condition=<?php echo $find ;?>')"><b><u> พิมพ์(PDF)</u></b></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<img src="images/outlook.jpg" height="20px"><a href="javascript:popU('frm_print_excel.php?data_find=<?php echo $txtfind; ?>&type=O&condition=<?php echo $find ;?>')"><b><u>ไฟล์นำเข้าที่อยู่ Outlook</u></b></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<img src="images/excel.png" height="20px"><a href="javascript:popU('frm_print_excel.php?data_find=<?php echo $txtfind; ?>&type=E&condition=<?php echo $find ;?>')"><b><u>พิมพ์(Excel)</u></b></a>
<center>
<fieldset style="width:80%"><legend>ค้นหาข้อมูล</legend>
<form method="post" name="form2" action="frm_show_tel_email.php">
	<input type="radio" id="find1" name="find" onchange="find_data('0');" value="0" <?php if($find=="" || $find=="0"){ echo "checked"; }?>  />ทั้งหมด
	<input type="radio" id="find2" name="find" onchange="find_data('1');" value="1" <?php if($find=="1"){ echo "checked"; }?>/>แผนก 	
	<input type="radio" id="find3" name="find" onchange="find_data('2');" value="2" <?php if($find=="2"){ echo "checked"; }?>/>ชื่อพนักงาน	
		<select name="chk_department" id="chk_department">			
			<option value="">- เลือก -</option>			
			<?php
				$qry_name=pg_query("select \"dep_id\",\"dep_name\" from department where \"dep_id\" <>'AD' order by \"dep_id\"");
				while($res_name=pg_fetch_array($qry_name))
				{
					$dep_id = $res_name["dep_id"]; 
					$dep_name = $res_name["dep_name"];	
					
					if($dep_id == $department_find){
							echo "<option value=\"$dep_id\" selected >$dep_id : $dep_name";
					}
					else{
						echo "<option value=\"$dep_id\">$dep_id : $dep_name";
					}
					echo "</option>";
				}
			?>
		</select>
		<input  name="txtname_emp"  id="txtname_emp" value="<?php if($find=='2'){ echo $txtname_emp_find; }?>" size="54">	
	<input type="submit" value="ค้นหา" >
</form>
</fieldset>
</center>
<br>

<br>
<table width="90%" border cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0" class="sort-table">	
	<tr bgcolor="#FFFFFF">
		<td  align="left" colspan="5" style="font-weight:bold;">ตารางแสดง เบอร์โทรศัพท์และ E-mail ของพนักงาน</td>
	</tr>			
	<tr style="font-weight:bold;" valign="middle" bgcolor="#9AC0CD">
		<th align="center" id="t1" >ชื่อ - สกุล</a></th>
		<th align="center" id="t2" >เบอร์ภายใน</a></th>
		<th align="center" id="t3" >เบอร์ตรง</a></th>
		<th align="center" id="t4" >มือถือ</a></th>
		<th align="center" id="t5" >E-mail</a></th>
	</tr>	
	<?php
	if($find=='0'){
		$qry_gpuser=pg_query("select \"dep_id\",\"dep_name\" from department where \"dep_id\" <>'AD' order by \"dep_id\"");//แผนกในระบบทั้งหมด ยกเว้น AD	
	}
	else if($find=='1'){
		$qry_gpuser=pg_query("select \"dep_id\",\"dep_name\" from department where \"dep_id\" ='$department_find' and \"dep_id\" <>'AD' order by \"dep_id\"");//แผนกที่เลือก		
	}
	else if($find=='2'){
		$qry_gpuser=pg_query("select a.\"dep_id\",a.\"dep_name\" from department a 
		left join \"Vfuser\" b on a.dep_id=b.user_group where b.id_user='$str_empId' and a.\"dep_id\" <>'AD'");
		$condition="a.id_user='$str_empId'";	
	}
	$i=0;
	while($res_type=pg_fetch_array($qry_gpuser))
	{		
		$dep_id=$res_type["dep_id"];
		if($find!='2'){
			$condition="a.\"user_group\"='$dep_id'";
		}
		$dep_name=$res_type["dep_name"];
		echo "<tr bgcolor=\"#AFEEEE\">";
		echo "<td align=\"center\" colspan=5><b>$dep_name<b></td></tr>";
		
		$query=pg_query("select a.fullname,b.u_extens,b.u_direct,b.u_tel,b.u_email,b.nickname from \"Vfuser\" a 
			left join \"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"			
			where $condition and a.resign_date is null");			
		while($res_group=pg_fetch_array($query))
		{ 	
			$i++;
			$fullname=$res_group["fullname"];
			$u_extens=$res_group["u_extens"];
			if(($u_extens !="") and($u_extens !="-")){$u_extens ='#'.$u_extens;}
			$u_direct=$res_group["u_direct"];
			if(($u_direct !="")and($u_direct !="-")){$u_direct ='#'.$u_direct;}
			$u_tel=$res_group["u_tel"];
			$u_email=$res_group["u_email"];	
			$nickname=$res_group["nickname"];			


			if($nickname !=""){ $fullname .=' ('.$nickname.')';}
			
			if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
				$color="#FFCCCC";
			}else{
			echo "<tr class=\"even\"  align=center>";
				$color="#FFE8E8";
			}
			echo " 				
				<td align=\"left\" width=300 >$fullname</td>
				<td align=\"center\">$u_extens</td>
				<td align=\"center\">$u_direct</td>
				<td align=\"center\">$u_tel</td>
				<td align=\"center\"><a href=\"mailto:$u_email\"><U>$u_email<U></a></td></tr>";
		}
	}
?>
</table>