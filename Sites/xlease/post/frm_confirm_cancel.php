<?php
$NTID = $_GET["NTID"];
$IDNO = $_GET["idno"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>Approve Cancel NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
function chklist(){
	if(document.frm.note.value == ""){
		alert("กรุณาระบุเหตุผลที่ไม่อนุมัติ");
		return false;
	}else{	
		if(confirm('ยืนยันการบันทึก')==true){ return true;}else{ return false;}
	}	
}
</script>
<body bgcolor="">
<center>
	<div><h1>เหตุผลที่ไม่อนุมัติ</h1></div>
	<form name="frm" method="POST" action="process_canapp.php">	
		<table width="90%" align="center" frame="box" bgcolor="#DDDDDD">
			<!-- ค่าที่จะส่งไปอีกหน้าหนึ่ง -->
			<input type="hidden" value="<?php echo $IDNO; ?>" name="idno">
			<input type="hidden" value="<?php echo $NTID; ?>" name="NTID">
			<tr>
				<td width="30%" align="right">
					<b>เลข NT : </b>
				</td>
				<td>
					<?php echo $NTID; ?>
				</td>
			</tr>
			<tr>
				<td align="right">
					<b>เลขที่สัญญา : </b>
				</td>
				<td>
					<?php echo $IDNO; ?>
				</td>		
			</tr>
			<tr>
				<td align="right" valign="top">
					<b>เหตุผล :</b> 
				</td>
				<td >
					<textarea cols="35" rows="5" name="note"></textarea>
				</td>
				<td width="25%" valign="top" align="left">
					<font color="red">*</font>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<hr width="70%">
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table width="80%" align="center"  bgcolor="#DDDDDD">
						<tr>
							<td align="center">
								<input type="submit" value=" บันทึก " onclick="return chklist();" style="width:100px;height:50px;">
							</td>
							<td align="center">
								<input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;">
							</td>	
						</tr>
					</table>	
				</td>
			</tr>
		</table>
	</form>	
</center>		
</body>
</html>