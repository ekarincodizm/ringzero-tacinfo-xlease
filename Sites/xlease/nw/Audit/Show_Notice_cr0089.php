<?php 
	include("../../config/config.php");
?>
<HTML>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HTML>
<?php
	
	$str_get_notice = "
						SELECT 
								\"Value\"
						FROM 
								\"thcap_audit_docs_detail\"
						WHERE 
								(\"Docs_ID\" = '".pg_escape_string($_GET["Doc_No"])."') AND
								(\"Element_ID\" = 'advice')
						
					  ";		
	$Result = pg_query($str_get_notice);
	$Data = pg_fetch_array($Result);
	$Notice_Data = $Data[0];
	$Full_Cust_Name = pg_escape_string($_GET["Cust_0"]).'#'.pg_escape_string($_GET["Cust_1"]);
?>
<TABLE>
	<TR>
		<TD align="right">
			<B>ชื่อร้าน / ชื่อผู้จัดจำหน่าย :</B>
		</TD>
		<TD>
			<?php 
				echo $Full_Cust_Name;
			?>
		</TD>
	</TR>
	<TR>
		<TD align="right">
			<B>ประเมินครั้งที่ :</B> 
		</TD>
		<TD>
			<?php
				echo pg_escape_string($_GET["AppvTime"]);
			?>	
		</TD>
	</TR>
	<TR align="top">
		<TD align="right" valign="top">
		   	<B>หมายเหตุ	:</B>	
		</TD>
		<TD>
			<textarea  cols="30" readonly ><?php echo $Notice_Data; ?></textarea>
			
		</TD>
	</TR>
	<TR>
		<TD colspan="2" align="center">
			<input type = "Button" value = "ปิด" onClick="window.close()" >
		</TD>
	</TR>
	
</TABLE>