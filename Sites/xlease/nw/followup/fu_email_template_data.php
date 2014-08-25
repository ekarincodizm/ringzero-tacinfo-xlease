<?php
include("../../config/config.php");
session_start();
$temID=pg_escape_string($_GET['temID']);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>
<center><legend><h2>... E-Mail Template Data...</h2></legend></center>
<?php 

				$qry_name=pg_query("select * from \"fu_template\" WHERE \"temID\" = '$temID'");
				$result=pg_fetch_array($qry_name);
				$temdetail1 = $result['tem_detail'];
				$temdetail3 = str_replaceout($temdetail1);
				

?>
	<hr width="850">
		<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>TemplateID:</b></td>
					<td bgcolor="#FFFFFF"><?php echo $result['temID'];?>
					</td>
			</tr>
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>ชื่อ Template:</b></td>
					<td bgcolor="#FFFFFF"><?php echo $result['tem_name'];?>
					</td>
			</tr>
			<tr bgcolor="#BCE6FC">
				<td width="200" height="25" align="right"><b>หัวเรื่อง:</b></td>
					<td bgcolor="#FFFFFF"><?php echo $result['tem_header'];?>

					</td>
			</tr>
			
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ข้อความ :</b></td>
						<td bgcolor="#FFFFFF"><textarea rows="10" cols="90" ><?php echo $temdetail3;?></textarea>
							
						</td>
			</tr>			
			<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>ไฟล์แนบ:</b></td>
						<td bgcolor="#FFFFFF">
						<?php
						$qry_name2 = pg_query("select * from \"fu_template\" WHERE \"temID\" = '$temID'");
						$result1=pg_fetch_array($qry_name2);						
						$ff = $result1["tem_file"];
						$file=explode("/",$ff);						
						
						for($i=1;$i<sizeof($file);$i++){
						?>							
						<a href="fileupload/<?php echo $file[$i];?>" target="_blank"><?php echo $file[$i];?>
						<br>
						<?php } ?>	
						
						
						</td>
										
			</tr>
			<!--<tr bgcolor="#BCE6FC">
					<td valign="top" align="right"><b>รหัสไฟล์ที่มีการ encode:</b></td>
						<td bgcolor="#FFFFFF">
							<textarea rows="5" cols="90" ><?php echo $result['tem_encode'];?></textarea>
						</td>
										
			</tr>-->
			
<tr bgcolor="#BCE6FC">
		
    <td valign="top" height="35" align="right"><b>ผู้ส่ง :</b></td>
		<td bgcolor="#FFFFFF">
			ชื่อ :<?php echo $result['tem_sendname'];?>
			<br>
			Email :<?php echo $result['tem_send_email'];?>
		
		</td>
</tr>
</table>
</body>
</form>

