<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>   
	<title>(THCAP) แสดงรายละเอียดข้อมูลสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>


    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
<script type="text/JavaScript">
 function loadlist_detail(){	
	$("#list_detail").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$("#list_detail").load("frm_list_data.php?&year="+$("#year").val()) ;
}
</script>   
</head>
<body >
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>        
	<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
	<div style="clear:both;"></div>
	<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
<table align="center" border="0">	
	<tr>
		<td>ปีที่ทำสัญญา :</td>
		<td>
			<select name="year" id="year"> 	

				<?php $datenow1 = nowDate();
				list($year,$month,$day)=explode("-",$datenow1);
				$year0=$year-10;
				if($year0<2011){
					$year0=2011;				
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
	<tr><td colspan="8" align="center">
	
	</td></tr>
	<tr><td colspan="8" align="center">
	<input type="hidden" name="val" value="1"/>
	<input type="button" id="Search" name="Search"  value="ค้นหา" onclick="loadlist_detail();"/>
	</td></tr>
</table>
</fieldset><br>
<div name="list_detail" id="list_detail">
</div>
<?php 
	//	include("frm_list_data.php");
?>
     </td>
</tr>
</table>

</body>
</html>