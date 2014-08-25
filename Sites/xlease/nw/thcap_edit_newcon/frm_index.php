<?php
include("../../config/config.php");


//เรียกข้อมูลเลขที่สัญญาที่จะแก้ไข
$qry_sel1 = pg_query("		SELECT a.\"coneditID\",b.*
							FROM \"thcap_contract_edit\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							WHERE 	a.\"status_edit\" = '0' AND
									a.\"user_do\" IS NULL AND
									a.\"status_app\" IS NULL AND
									b.\"CusState\" = '0'
									
				    ");
					
//เรียกข้อมูลเลขที่สัญญาที่่รอการตรวจสอบ
$qry_sel2 = pg_query("		SELECT a.\"coneditID\",a.\"user_do_datetime\",c.\"fullname\",b.*
							FROM \"thcap_contract_edit\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							LEFT JOIN \"Vfuser\" c ON a.\"user_do\" = c.\"id_user\"
							WHERE 	a.\"status_edit\" = '1' AND
									a.\"user_do\" IS NOT NULL AND
									a.\"status_app\" = '0' AND
									b.\"CusState\" = '0'
									
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
			<div><h2>(THCAP) ใส่รายละเอียดสัญญา BH</h2></div>
			<fieldset style="width:70%;">
				<legend><B>สัญญาที่ต้องแก้ไขให้ถูกต้อง</B></legend>
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
												<th width="156">แก้ไข</th>										
											</tr>
									<?php
										$i = 1;
										$no = 1;
										$rows = pg_num_rows($qry_sel1);
										IF($rows > 0){ //หากมีข้อมูลที่จะต้องแก้ไข
											while($result = pg_fetch_array($qry_sel1)){
												$coneditID = $result["coneditID"];//รหัสการแก้ไข
												$contractID = $result["contractID"];//เลขที่สัญญา
												$thcap_fullname = $result["thcap_fullname"]; //ชื่อผู้กู้หลัก
												$N_IDCARD = $result["N_IDCARD"]; //รหัสบัตรผู้กู้หลัก
												//ถ้าไม่มีเลขบัตรให้เอาบัตรอื่นๆแทน
												IF($N_IDCARD ==""){
													$N_IDCARD = $result["N_CARDREF"]; //รหัสบัตรอื่นๆของผู้กู้หลัก
												}
												//หาประเภทของสัญญา
													$qry_typecon = pg_query("SELECT \"thcap_get_creditType\"('$contractID')");
													list($credittype) = pg_fetch_array($qry_typecon);
													
												//ตรวจดูว่าเลขที่สัญญานี้มีการแก้ไขมาก่อนแล้วหรือไม่
												$qry_sel1_2 = pg_query("		SELECT *
																			FROM \"thcap_contract_edit\" a							
																			WHERE 	a.\"contractID\" = '$contractID'				
																	");	
												$rows_nub = pg_num_rows($qry_sel1_2);					
													
													
												
											IF($rows_nub > 1){										
													echo "<tr bgcolor=#FFCCCC onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFCCCC';\" align=center>";										
											}else{		
												if($i%2==0){
													echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
												}else{
													echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
												} 
												$i++;
											}
											
											echo "
													<td align=\"center\">$no</td>
													<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
																		<u>$contractID</u></font></span></td>
													<td align=\"center\">$credittype</td>
													<td align=\"left\">$thcap_fullname</td>
													<td align=\"center\">$N_IDCARD</td>
													<td align=\"center\">
														<a onclick=\"popU('frm_show_data.php?hdcontractid=$contractID&coneditID=$coneditID&ShowfromReal=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\">
															<img src=\"images/edit_pa1.png\" width=\"25px;\" height=\"25px;\" style=\"cursor:pointer;\">
														</a>	
													</td>
													</form>
												";
											$no++;
											}//ปิด While			
										}else{ //ปิด IF หากไม่มีข้อมูลที่จะต้องแก้ไข									
											echo "	
													<tr>
														<td colspan=\"6\"  align=\"center\">****  <h2>ไม่มีสัญญาที่ต้องแก้ไข  </h2>****</td>
													</tr>
												";	
										}
										
										echo	"
													<tr bgcolor=\"#DDDDDD\">
														<td colspan=\"6\">รวม $rows รายการ</td>
													</tr>													
												";			
									?>			
									</table>
									<table align="center"  width="100%">	
										<tr>
											<td  align="right"><font color="red">** รายการสีแดงคือรายการที่เคยแก้ไขแล้วแต่ข้อมูลยังไม่ถูกต้อง</font></td>
										</tr>
									</table>
							</td>
						</tr>
						
					</table>
			</fieldset>

			
<div style="padding-top:70px;"></div>			
	<!-- รอตรวจสอบความถูกต้อง -->
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
			
			
			
			
			
			
		</center>
		
</body>
</html>