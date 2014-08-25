<?php
include("../../config/config.php");



					
//เรียกข้อมูลเลขที่สัญญาที่่รอการตรวจสอบ
$qry_sel2 = pg_query("		SELECT a.\"coneditID\",a.\"user_do_datetime\",c.\"fullname\",b.*
							FROM \"thcap_contract_edit\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							LEFT JOIN \"Vfuser\" c ON a.\"user_do\" = c.\"id_user\"
							WHERE 	a.\"status_edit\" = '1' AND
									a.\"user_do\" IS NOT NULL AND
									a.\"status_app\" = '0' AND
									b.\"CusState\" = '0'
							ORDER BY a.\"user_do_datetime\"	DESC		
									
				    ");					


//ประวัติการอนุมัติสัญญาที่แก้ไข
$qry_sel3 = pg_query("		SELECT a.\"coneditID\",a.\"status_app\",a.\"user_app\",a.\"app_datetime\",a.\"noteapp\",a.\"user_do_datetime\",b.*,c.\"fullname\" as \"doername\",d.\"fullname\" as \"appname\"
							FROM \"thcap_contract_edit\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							LEFT JOIN \"Vfuser\" c ON a.\"user_do\" = c.\"id_user\"
							LEFT JOIN \"Vfuser\" d ON a.\"user_app\" = d.\"id_user\"
							WHERE 	a.\"status_edit\" != '0' AND
									a.\"status_app\" != '0' AND
									b.\"CusState\" = '0'
							ORDER BY a.\"app_datetime\"	DESC
							LIMIT 30		
				    ");					

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่รายละเอียดสัญญา BH</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
			<link type="text/css" rel="stylesheet" href="act.css"></link>
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
			<div><h2>(THCAP) อนุมัติใส่รายละเอียดสัญญา  BH</h2></div>
	<fieldset style="width:70%;">
		<legend><font color="black"><B>สัญญาที่แก้ไขแล้วรอการตรวจสอบ</B></font></legend>
					<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					   <tr>
							<td align="center">						
									<table align="center" frame="box" width="100%">				
											<tr bgcolor="#CDC9C9">
												<th width="70">รายการที่</th>
												<th width="156">เลขที่สัญญา</th>
												<th width="156">ประเภทสัญญา</th>
												<th width="156">ผู้กู้หลัก</th>
												<th width="156">เลขบัตร</th>
												<th width="156">ผู้แก้ไข</th>
												<th width="156">วันเวลาที่แก้ไข</th>
												<th width="156">รายละเอียด</th>										
											</tr>
									<?php
										$i = 1;
										$no = 1;
										$rows1 = pg_num_rows($qry_sel2);
										IF($rows1 > 0){ //หากมีข้อมูลที่จะต้องแก้ไข
											while($result1 = pg_fetch_array($qry_sel2)){
												$coneditID = $result1["coneditID"];//รหัสการแก้ไข
												$contractID = $result1["contractID"];//เลขที่สัญญา
												$thcap_fullname = $result1["thcap_fullname"]; //ชื่อผู้กู้หลัก
												$N_IDCARD = $result1["N_IDCARD"]; //รหัสบัตรผู้กู้หลัก
												$user_do_date = $result1["user_do_datetime"]; //วันเวลาที่ทำรายการ
												$fullname = $result1["fullname"]; //ชื่อผู้ทำรายการ
												
												//ถ้าไม่มีเลขบัตรให้เอาบัตรอื่นๆแทน
												IF($N_IDCARD ==""){
													$N_IDCARD = $result1["N_CARDREF"]; //รหัสบัตรอื่นๆของผู้กู้หลัก
												}
												//หาประเภทของสัญญา
													$qry_typecon = pg_query("SELECT \"thcap_get_creditType\"('$contractID')");
													list($credittype) = pg_fetch_array($qry_typecon);
													
												if($i%2==0){
													echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
												}else{
													echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
												} 
												$i++;
											
											echo "
													<td align=\"center\">$no</td>
													<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
																		<u>$contractID</u></font></span></td>
													<td align=\"center\">$credittype</td>
													<td align=\"left\">$thcap_fullname</td>
													<td align=\"center\">$N_IDCARD</td>
													<td align=\"center\">$fullname</td>
													<td align=\"center\">$user_do_date</td>	
													<td align=\"center\">
														<a onclick=\"popU('frm_show_data.php?hdcontractid=$contractID&coneditID=$coneditID&readonly=t&appv=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\">
															<img src=\"images/detail.gif\" width=\"25px;\" height=\"25px;\" style=\"cursor:pointer;\">
														</a>	
													</td>
													</form>
												";
											$no++;
											}//ปิด While			
										}else{ //ปิด IF หากไม่มีข้อมูลที่จะต้องแก้ไข									
											echo "	
													<tr>
														<td colspan=\"8\"  align=\"center\">****  <h2> ไม่มีสัญญารอการตรวจสอบ  </h2>****</td>
													</tr>
												";	
										}
										
										echo	"
													<tr bgcolor=\"#DDDDDD\">
														<td colspan=\"8\">รวม $rows1 รายการ</td>
													</tr>													
												";			
									?>			
									</table>
									
							</td>
						</tr>
						
					</table>
			</fieldset>
			
			
			
		<div style="padding-top:70px;"></div>	
		<fieldset style="width:90%;">
		<legend><font color="black"><B>ประวัติการอนุมัติสัญญาที่แก้ไข</B> ( <font color="blue"><a style="cursor:pointer;" onclick="popU('frm_history_app.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')"><u>ทั้งหมด</u></a></font> )</font></legend>
					<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					   <tr>
							<td align="center">						
									<table align="center" frame="box" width="100%">				
											<tr bgcolor="#CDC9C9">
												<th width="70">รายการที่</th>
												<th width="156">เลขที่สัญญา</th>
												<th width="156">ประเภทสัญญา</th>
												<th width="156">ผู้กู้หลัก</th>
												<th width="156">เลขบัตร</th>
												<th width="156">ผู้แก้ไข</th>
												<th width="156">วันเวลาที่แก้ไข</th>
												<th width="156">ผู้อนุมัติ</th>
												<th width="156">วันเวลาที่อนุมัติ</th>
												<th width="156">สถานะ</th>
												<th width="156">รายละเอียด</th>										
											</tr>
									<?php
										$i = 1;
										$no = 1;
										$rows3 = pg_num_rows($qry_sel3);
										IF($rows3 > 0){ //หากมีข้อมูล
											while($result1 = pg_fetch_array($qry_sel3)){
												$coneditID = $result1["coneditID"];//รหัสการแก้ไข
												$contractID = $result1["contractID"];//เลขที่สัญญา
												$thcap_fullname = $result1["thcap_fullname"]; //ชื่อผู้กู้หลัก
												$N_IDCARD = $result1["N_IDCARD"]; //รหัสบัตรผู้กู้หลัก
												$user_do_date = $result1["user_do_datetime"]; //วันเวลาที่ทำรายการ
												$fullnamedoer = $result1["doername"]; //ชื่อผู้ทำรายการ
												$app_datetime = $result1["app_datetime"]; //วันเวลาที่อนุมัติ
												$fullnameapp = $result1["appname"]; //ชื่อผู้อนุมัติ
												$status_app = $result1["status_app"]; //สถานะการอนุมัติ
												IF($status_app == '1'){
													$txtappstate = 'อนุมัติ';
												}else{
													$txtappstate = 'ไม่อนุมัติ';
												}
												
												//ถ้าไม่มีเลขบัตรให้เอาบัตรอื่นๆแทน
												IF($N_IDCARD ==""){
													$N_IDCARD = $result1["N_CARDREF"]; //รหัสบัตรอื่นๆของผู้กู้หลัก
												}
												//หาประเภทของสัญญา
													$qry_typecon = pg_query("SELECT \"thcap_get_creditType\"('$contractID')");
													list($credittype) = pg_fetch_array($qry_typecon);
													
												if($i%2==0){
													echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
												}else{
													echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
												} 
												$i++;
											
											echo "
													<td align=\"center\">$no</td>
													<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
																		<u>$contractID</u></font></span></td>
													<td align=\"center\">$credittype</td>
													<td align=\"left\">$thcap_fullname</td>
													<td align=\"center\">$N_IDCARD</td>
													<td align=\"center\">$fullnamedoer</td>
													<td align=\"center\">$user_do_date</td>	
													<td align=\"center\">$fullnameapp</td>	
													<td align=\"center\">$app_datetime</td>	
													<td align=\"center\">$txtappstate</td>	
													<td align=\"center\">
														<a onclick=\"popU('frm_show_data.php?hdcontractid=$contractID&coneditID=$coneditID&readonly=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\">
															<img src=\"images/detail.gif\" width=\"25px;\" height=\"25px;\" style=\"cursor:pointer;\">
														</a>	
													</td>
													</form>
												";
											$no++;
											}//ปิด While			
										}else{ //ปิด IF หากไม่มีข้อมูลที่จะต้องแก้ไข									
											echo "	
													<tr>
														<td colspan=\"11\"  align=\"center\">****  <h2> ไม่มีสัญญาที่เคยอนุมัติ  </h2>****</td>
													</tr>
												";	
										}
										
										echo	"
													<tr bgcolor=\"#DDDDDD\">
														<td colspan=\"11\">รวม $rows3 รายการ</td>
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