<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รูปแบบจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#auto_id").autocomplete({
        source: "s_type.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_del.php?auto_id="+ $("#auto_id").val());
    });

});
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left">
<input type="button" value="เพิ่มรูปแบบจดหมาย" onclick="window.location='add_type_letter.php'">
<input type="button" value="แก้ไขรูปแบบจดหมาย" onclick="window.location='frm_edit_type_letter.php'">
<input type="button" value="ลบรูปแบบจดหมาย" onclick="window.location='frm_delete_type_letter.php'" disabled>
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>ลบรูปแบบจดหมาย</B></legend>
<div class="ui-widget" align="center">

<div style="margin:0; padding: 10px;">
<b>ค้นหา ชื่อประเภท :</b>&nbsp;
<input id="auto_id" name="auto_id" size="60" />&nbsp;
<input type="button" id="btn1" value="ค้นหา"/>
</div>

</div>
</fieldset>

<div align="center" id="panel" style="padding-top: 10px;">
<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center" width="80%">
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
		<td width="50">ลำดับที่</td>
		<td>ชื่อประเภทของจดหมาย</td>
		<td width="150">สถานะการใช้งาน</td>
		<td width="100">ลบ</td>
	</tr>
	<?php 
		$querytype = pg_query("select * from letter.\"type_letter\" order by \"auto_id\"");
		$num_row = pg_num_rows($querytype);
		$p=1;
		while($res_name=pg_fetch_array($querytype)){
			$auto_id = $res_name["auto_id"];
			$name = $res_name["type_name"];
			$status = $res_name["is_use"];
			if($status == 't'){
				$print_txt = "อนุญาตให้ไช้";
			}else{
				$print_txt = "ไม่อนุญาิตให้ใช้";
			}
		$i+=1;
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		?>
		<td align="center"><?php echo $p;?></td>
		<td>&nbsp;&nbsp;<?php echo $name;?></td>
		<td align="center"><?php echo $print_txt;?></td>
		<td align="center"><input type="button" value="เลือก" onclick="if(confirm('คุณยืนยันที่จะลบรายการนี้!!')){location.href='process_type.php?auto_id2=<?php echo $auto_id;?>&method2=delete'}"></td>
		</tr>
		<?php
		$p++;
		} //end while
	?>
</table>



        </td>
    </tr>
</table>

</body>
</html>