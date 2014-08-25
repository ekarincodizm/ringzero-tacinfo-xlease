<?php
require_once("../../config/config.php");
$s=pg_escape_string($_GET['s']); //ตัวแปรสำหรับบอกว่าแสดงข้อมูลอะไร
if($s==1){ //ใบแจ้งหนี้ที่ถึงกำหนดส่ง
	$qrnum=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\",\"sendduedate\"
	from \"Vthcap_send_invoice\" 
	where \"print_user\" is null and \"sendduedate\" <= current_date and \"status_sent\"='TRUE'
	order by \"sendduedate\"");
	$row2 = pg_num_rows($qrnum);
	echo "
		<div class=\"tab_menu_contrainer\">
			<div class=\"menu_box\">
				<div class=\"tab_box\">
					<div class=\"slide_tab\">
						<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0',1);\">ทั้งหมด <font color=red>($row2)</font></a></div>
		";					
						$qr = pg_query("select distinct(\"conType\") as \"conType\" from thcap_contract order by \"conType\"");
						if($qr)
						{
							$row = pg_num_rows($qr);
							if($row!=0)
							{
								while($rs=pg_fetch_array($qr))
								{
									$tabID = $rs['conType'];
									$tab_name = $rs['conType'];
									
									
									$qrnum=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\",\"sendduedate\"
										from \"Vthcap_send_invoice\" 
										where \"print_user\" is null and \"sendduedate\" <= current_date
										and \"conType\"='$tabID' and \"status_sent\"='TRUE'
										order by \"sendduedate\"");	
									$row2 = pg_num_rows($qrnum);
	
									echo "
										<div class=\"tab\"><a id=\"$tabID\" href=\"javascript:list_tab_menu('$tabID',1);\">$tab_name <font color=red>($row2)</font></a></div>
									";
								}
							}
						}
				echo "
					</div>
				</div>
			</div>
		</div>
		<div class=\"list_tab_menu\"></div>
	";
}else if($s==2){ //ใบแจ้งหนี้ที่พิมพ์แล้วรอส่ง
	$qrnum=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\" ,\"sendduedate\",\"invoiceDate\",
			\"printname\" as fullname,\"print_date\"
			from \"Vthcap_send_invoice\" 
			where \"print_user\" is not null and \"send_user\" is null
			order by \"sendduedate\"");
	$row2 = pg_num_rows($qrnum);
	echo "
		<div class=\"tab_menu_contrainer\">
			<div class=\"menu_box\">
				<div class=\"tab_box\">
					<div class=\"slide_tab\">
						<div class=\"tab2 active\"><a id=\"01\" href=\"javascript:list_tab_menu('01',2);\">ทั้งหมด <font color=red>($row2)</font></a></div>
		";				
						//หา contype ทั้งหมดที่ต้องนำมาแสดง
						$qr = pg_query("select distinct(\"conType\") as \"conType\" from thcap_contract order by \"conType\"");
						if($qr)
						{
							$row = pg_num_rows($qr);
							if($row!=0)
							{
								$i=1;
								while($rs=pg_fetch_array($qr))
								{
									$tabID = $rs['conType']."-".$i; //เติม i เพื่อให้ id ไม่ซ้ำกับใบแจ้งหนี้ที่ถึงกำหนดส่ง
									$tab_name = $rs['conType'];
									
									$qrnum=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\" ,\"sendduedate\",\"invoiceDate\",
									\"printname\" as fullname,\"print_date\"
									from \"Vthcap_send_invoice\"
									where \"print_user\" is not null and \"send_user\" is null
									and \"conType\"='$rs[conType]'
									order by \"debtInvID\"");
									$row2 = pg_num_rows($qrnum);
			
									echo "
										<div class=\"tab2\"><a id=\"$tabID\" href=\"javascript:list_tab_menu('$tabID',2);\">$tab_name <font color=red>($row2)</font></a></div>
									";
									$i++;
								}
							}
						}
				echo "
					</div>
				</div>
			</div>
		</div>
		<div class=\"list_tab_menu2\"></div>
	";
}
?>