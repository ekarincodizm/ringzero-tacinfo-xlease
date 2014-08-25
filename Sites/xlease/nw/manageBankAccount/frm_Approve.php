<?php
include("../../config/config.php");


//ข้อมูลบัญชีที่รออนุมัติ
$qry_sel1 = pg_query("		SELECT *,a.\"auto_id\" as autoid
							FROM \"BankInt_Waitapp\" a
							LEFT JOIN \"Vfuser\" b on a.\"add_user\" = b.\"id_user\"
							LEFT JOIN \"VSearchCusCorp\" c on a.\"BCompany\" = c.\"CusID\"
							WHERE a.\"statusApp\" = '2'
							ORDER BY a.\"add_date\"	
				    ");
					
//ประวัติการอนุมัติ
$qry_sel2 = pg_query("		SELECT a.*,b.\"fullname\" as doer_name,c.\"fullname\" as app_name,\"full_name\" as \"nameBCompany\"
							FROM \"BankInt_Waitapp\" a
							LEFT JOIN \"Vfuser\" b on a.\"add_user\" = b.\"id_user\"
							LEFT JOIN \"Vfuser\" c on a.\"app_user\" = c.\"id_user\"
							LEFT JOIN \"VSearchCusCorp\" d on a.\"BCompany\" = d.\"CusID\"
							WHERE a.\"statusApp\" <> '2'
							ORDER BY a.\"app_date\" DESC limit 30
				    ");					

	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติจัดการบัญชีธนาคารบริษัท</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
			<link type="text/css" rel="stylesheet" href="../thcap_edit_newcon/act.css"></link>
				<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
					<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
						<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
	<div style="padding-top:30px;"></div>
		<center>		
			<div><h2>อนุมัติจัดการบัญชีธนาคารบริษัท</h2></div>
			<fieldset style="width:85%;">
				<legend><B>รายการรออนุมัติ</B></legend>
					<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					   <tr>
							<td align="center">						
									<table align="center" frame="box" width="100%">				
											<tr bgcolor="#CDC9C9">
												<th>สถานะการทำรายการ</th>
												<th>เลขที่บัญชีธนาคาร</th>
												<th >ชื่อธนาคาร</th>
												<th >สาขาธนาคาร</th>
												<th >เจ้าของบัญชีธนาคาร</th>
												<th >ประเภทบัญชี</th>
												<th >Channel</th>	
												<th >ผู้ทำรายการ</th>	
												<th >วันเวลาที่ทำรายการ</th>	
												<th >ตรวจสอบ</th>													
											</tr>
									<?php
										$i = 1;
										$rows = pg_num_rows($qry_sel1);
										IF($rows > 0){ //หากมีข้อมูลที่จะต้องแก้ไข
											while($result = pg_fetch_array($qry_sel1)){
												$BAccount = $result["BAccount"];//เลขที่บัญชีธนาคาร
												$BName = $result["BName"];//ชื่อธนาคาร
												$BBranch = $result["BBranch"]; //สาขาธนาคาร
												$BCompany = $result["full_name"]; //เจ้าของบัญชีธนาคาร
												$BType = $result["BType"]; //เจ้าของบัญชีธนาคาร	
												$BChannel = $result["BChannel"]; //BChannel		
												$fullname = $result["fullname"]; //ผู้ทำรายการ
												$add_date = $result["add_date"]; //วันเวลาที่ทำรายการ
												$edittime=$result["edittime"]; //ครั้งที่ขอทำรายการ
												$auto_id=$result["autoid"]; //ครั้งที่ขอทำรายการ

												if($edittime=="0"){
													$txtedit="<font color=red>เพิ่มรายการ</font>";
												}else{
													$txtedit="แก้ไขรายการ";
												}

												if($BType == '1'){
													$BTypetxt = 'กระแสรายวัน';
												}else if($BType == '2'){
													$BTypetxt = 'ออมทรัพย์';
												}else{
													$BTypetxt = 'ไม่ระบุ';
												}
											

												if($i%2==0){
													echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
												}else{
													echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
												} 
												$i++;
											
											
											echo "
													<td>$txtedit</td>
													<td>$BAccount</td>
													<td align=\"left\">$BName</td>
													<td align=\"left\">$BBranch</td>
													<td align=\"left\">$BCompany</td>
													<td align=\"center\">$BTypetxt</td>
													<td align=\"center\">$BChannel</td>
													<td align=\"left\">$fullname</td>
													<td>$add_date</td>
													
													<td align=\"center\">";
													if($edittime=="0"){
														echo"<a onclick=\"popU('frm_Showappadd.php?auto_id=$auto_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\">
															<img src=\"../thcap_edit_newcon/images/detail.gif\" width=\"25px;\" height=\"25px;\" style=\"cursor:pointer;\">
														</a>";
													}else{
														echo"<a onclick=\"popU('frm_Showappedit.php?auto_id=$auto_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1205,height=800')\">
															<img src=\"../thcap_edit_newcon/images/detail.gif\" width=\"25px;\" height=\"25px;\" style=\"cursor:pointer;\">
														</a>";
													}
												echo "</td></tr>";
											$no++;
											}//ปิด While			
										}else{ //ปิด IF หากไม่มีข้อมูลที่จะต้องแก้ไข									
											echo "	
													<tr>
														<td colspan=\"10\"  align=\"center\">****  <h3> ไม่มีรายการรออนุมัติ  </h3>****</td>
													</tr>
												";	
										}
										
										echo	"
													<tr bgcolor=\"#DDDDDD\">
														<td colspan=\"10\">รวม $rows รายการ</td>
													</tr>													
												";			
									?>			
									</table>									
							</td>
						</tr>
						
					</table>
			</fieldset>

			
<div style="padding-top:70px;"></div>			
	<!-- รอตรวจสอบความถูกต้อง -->
	<fieldset style="width:85%;">
		<legend><font color="black"><B>ประวัติการอนุมัติ</B></font></legend>
					<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					   <tr>
							<td align="center">						
									<table align="center" frame="box" width="100%">				
											<tr bgcolor="#CDC9C9">
												<th >เลขที่บัญชีธนาคาร</th>
												<th >ชื่อธนาคาร</th>
												<th >สาขาธนาคาร</th>
												<th >เจ้าของบัญชีธนาคาร</th>
												<th >ประเภทบัญชี</th>
												<th >Channel</th>	
												<th >ผู้ทำรายการ</th>	
												<th >วันเวลาที่ทำรายการ</th>
												<th >ผู้อนุมัติ</th>	
												<th >วันเวลาที่อนุมัติ</th>
												<th >ผลการอนุมัติ</th>												
											</tr>
									<?php
										$i = 1;
										$rows = pg_num_rows($qry_sel2);
										IF($rows > 0){ //หากมีข้อมูลที่จะต้องแก้ไข
											while($result = pg_fetch_array($qry_sel2)){
												$BAccount = $result["BAccount"];//เลขที่บัญชีธนาคาร
												$BName = $result["BName"];//ชื่อธนาคาร
												$BBranch = $result["BBranch"]; //สาขาธนาคาร
												$BCompany = $result["nameBCompany"]; //เจ้าของบัญชีธนาคาร
												$BType = $result["BType"]; //เจ้าของบัญชีธนาคาร	
												$BChannel = $result["BChannel"]; //BChannel		
												$doer_name = $result["doer_name"]; //ผู้ทำรายการ
												$add_date = $result["add_date"]; //วันเวลาที่ทำรายการ
												$app_name = $result["app_name"]; //ผู้อนุมัติ
												$app_date = $result["app_date"]; //วันเวลาที่อนุมัติ
												$statusApp = $result["statusApp"]; //สถานะการอนุมัติ
												
												if($statusApp=="0"){
													$txtapp="ไม่อนุมัติ";
												}else{
													$txtapp="อนุมัติ";
												}

												IF($BType == '1'){
													$BTypetxt = 'กระแสรายวัน';
												}else if($BType == '2'){
													$BTypetxt = 'ออมทรัพย์';
												}else{
													$BTypetxt = 'ไม่ระบุ';
												}
											

												if($i%2==0){
													echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
												}else{
													echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
												} 
												$i++;
											
											
											echo "
													<td>$BAccount</td>
													<td align=\"left\">$BName</td>
													<td align=\"left\">$BBranch</td>
													<td align=\"left\">$BCompany</td>
													<td align=\"center\">$BTypetxt</td>
													<td align=\"center\">$BChannel</td>
													<td align=\"left\">$doer_name</td>
													<td align=\"center\">$add_date</td>
													<td align=\"left\">$app_name</td>
													<td align=\"center\">$app_date</td>	
													<td align=\"center\">$txtapp</td>									
													</form>
												";
											$no++;
											}//ปิด While			
										}else{ //ปิด IF หากไม่มีข้อมูลที่จะต้องแก้ไข									
											echo "	
													<tr>
														<td colspan=\"11\"  align=\"center\">****  <h3> ไม่มีประวัติการอนุมัติ  </h3>****</td>
													</tr>
												";	
										}
										
										echo	"
													<tr bgcolor=\"#DDDDDD\">
														<td colspan=\"11\">รวม $rows รายการ</td>
													</tr>													
												";			
									?>			
									</table>									
							</td>
						</tr>
						
					</table>
			</fieldset>
			
			
			
			
			
			
		</center>
		
</body>
</html>