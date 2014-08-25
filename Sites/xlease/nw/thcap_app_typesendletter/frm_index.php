<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) อนุมัติประเภทเอกสารส่งจดหมาย</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){

    $("#auto_id").autocomplete({
        source: "s_type.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_list.php?auto_id="+ $("#auto_id").val());
    });

});

</script>
<center><h2>(THCAP) อนุมัติประเภทเอกสารส่งจดหมาย</h2></center>
<body >
<fieldset><legend><B>ประเภทเอกสารส่งจดหมายที่รออนุมัติ</B></legend>
<div class="ui-widget" align="center">
<div align="center" id="panel" style="padding-top: 10px;">
<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center" width="40%">
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
		<td width="50">ลำดับที่</td>
		<td>ชื่อประเภทของจดหมาย</td>	
		<td>ผู้ทำรายการ</td>
		<td>เวลาที่ทำรายการ</td>
		<td>ทำรายการ</td>		
		</tr>
		<?php 
		$qryspecial=pg_query("SELECT auto_id,\"sendName\",\"addUser\",\"addStamp\" FROM thcap_letter_head_temp where \"status\"='9' order by  auto_id ");
		$numspec=pg_num_rows($qryspecial);
		while($resspec=pg_fetch_array($qryspecial)){
				list($auto_id,$sendName,$addUser,$addStamp)=$resspec;
				//ชื่อผู้ที่ทำรายการ
				$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$addUser' ");
				$fullnameuser = pg_fetch_array($query_fullnameuser);
				$doerfullname=$fullnameuser["fullname"];
				$p+=1;
				if($p%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
				?>
				<td align="center"><?php echo $p;?></td>
				<td>&nbsp;&nbsp;<?php echo $sendName;?></td>
				<td>&nbsp;&nbsp;<?php echo $doerfullname;?></td>
				<td>&nbsp;&nbsp;<?php echo $addStamp;?></td>
				<td align="center"><a onclick="javascript:popU('frm_appvdetail.php?idno=<?php echo $auto_id;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')" style="cursor:pointer;"><font color="#0000FF"><u>ทำรายการ</u></font></a></td>
		</tr>
		<?php
		}//end while
		if($numspec==0){
			echo "<tr bgcolor=#FFFFFF height=30><td colspan=5 align=center><b>ไม่พบรายการ</b></td><tr>";
		}
	?>
</table>
</div>
</div>
</fieldset>
<fieldset><legend><B>ประเภทเอกสารส่งจดหมายที่ใช้ในระบบ</B></legend>
<?php include ("../thcap_typesendletter/frm_listdetail_insys.php")?>
</fieldset>
</body>