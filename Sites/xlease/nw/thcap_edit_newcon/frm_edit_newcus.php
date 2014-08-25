<?php
include("../../config/config.php");
$contract = $_GET["contract"];
$readonlyna = $_GET["readonly"];

/*---- หาเงื่อนไขในการหาลูกค้าที่ยังไม่มีข้อมูลสำคัญอื่นๆ  ที่เป็นลูกค้าใหม่โดยไม่ได้ผ่านเมนู เพิ่มลูกค้า อาทิเช่นเมนู  "(THCAP) ผูกสัญญาเงินกู้ชั่วคราว" โดยจะหาจากลูกค้าที่มีข้อมูล ใน Field ที่บังคับห้ามว่างเท่านั้น --\\
{ */

	//ดึงชื่อ column จากตาราง Fa1 ใน query หลักใช้ชื่อว่า a
		$qry1 = pg_query("	SELECT column_name 
							FROM information_schema.COLUMNS 
							WHERE 	table_name = 'Fa1' 
									AND column_name != 'CusID' 
									AND column_name != 'A_FIRNAME' 
									AND column_name != 'A_NAME'
									AND column_name != 'A_SIRNAME'
									AND column_name != 'Approved'						
						");
		//ชื่อชื่อ column กับคำสั่ง where ที่ต้องการโดยอ้างชื่อตาราง a				
		while($result1 = pg_fetch_array($qry1)){
				$condition = $condition." AND a.\"".$result1["column_name"]."\" IS NULL";		
		}

	//ดึงชื่อ column จากตาราง Fn ใน query หลักใช้ชื่อว่า b
		$qry2 = pg_query("	SELECT column_name 
							FROM information_schema.COLUMNS 
							WHERE 	table_name = 'Fn' 
									AND column_name != 'CusID' 
									AND column_name != 'N_STATE' 
									AND column_name != 'N_SAN'
									AND column_name != 'N_AGE'
									AND column_name != 'N_CARD'	
									AND column_name != 'N_IDCARD'	
									AND column_name != 'N_OT_DATE'							
						");
		//ชื่อชื่อ column กับคำสั่ง where ที่ต้องการโดยอ้างชื่อตาราง b				
		while($result2 = pg_fetch_array($qry2)){
				$condition = $condition." AND b.\"".$result2["column_name"]."\" IS NULL";		
		}
		
		
	//ค้นหาข้อมูลลูกค้าที่เป็นลูกค้าใหม่โดยไม่ได้ผ่านเมนู เพิ่มลูกค้า อาทิเช่นเมนู  "(THCAP) ผูกสัญญาเงินกู้ชั่วคราว" โดยจะหาจากลูกค้าที่มีข้อมูล ใน Field ที่บังคับห้ามว่างเท่านั้น
		$qry3 = pg_query("	SELECT a.*,b.*,z.\"CusState\"
							FROM \"vthcap_ContactCus_detail\" z
							LEFT JOIN \"Fa1\" a ON z.\"CusID\" = a.\"CusID\"
							LEFT JOIN \"Fn\" b ON z.\"CusID\" = b.\"CusID\"
							WHERE 
									z.\"contractID\" = '$contract' AND
									a.\"CusID\" IS NOT NULL AND
									a.\"A_FIRNAME\" IS NOT NULL AND
									a.\"A_NAME\" IS NOT NULL AND
									a.\"A_SIRNAME\" IS NOT NULL AND
									b.\"N_IDCARD\" IS NOT NULL
									$condition
							ORDER BY z.\"CusState\"			
						");	
	//ค้นหาข้อมูลลูกค้าที่เป็นลูกค้าปกติหรือลูกค้าเก่า
		$qry4 = pg_query("	SELECT y.\"CusID\",y.\"CusState\",y.\"type\",y.\"thcap_fullname\",y.\"relation\",q.\"A_FIRNAME\",q.\"A_NAME\",q.\"A_SIRNAME\",y.\"N_IDCARD\",y.\"N_CARDREF\"
							FROM \"vthcap_ContactCus_detail\" y
							LEFT JOIN \"Fa1\" q ON y.\"CusID\" = q.\"CusID\"
							LEFT JOIN \"Fn\" w ON y.\"CusID\" = w.\"CusID\"
							WHERE y.\"contractID\" = '$contract' AND
								  y.\"CusID\" NOT IN(		
										SELECT a.\"CusID\"
										FROM \"vthcap_ContactCus_detail\" z
										LEFT JOIN \"Fa1\" a ON z.\"CusID\" = a.\"CusID\"
										LEFT JOIN \"Fn\" b ON z.\"CusID\" = b.\"CusID\"
										WHERE 
												z.\"contractID\" = '$contract' AND
												a.\"CusID\" IS NOT NULL AND
												a.\"A_FIRNAME\" IS NOT NULL AND
												a.\"A_NAME\" IS NOT NULL AND
												a.\"A_SIRNAME\" IS NOT NULL AND
												b.\"N_IDCARD\" IS NOT NULL
												$condition
										ORDER BY a.\"CusID\"
									)
						Order by y.\"CusState\"			
									
						");						

/* } */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>แก้ไขข้อมูลลูกค้าเพิ่มเติม</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body bgcolor="">
	<fieldset style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:100%;">
		<legend ><h2>ข้อมูลลูกค้า</h2></legend>
		<div><font size="2px;"><u>หมายเหตุ</u></font><font color="red" size="2px;"> - ลูกค้าที่มีแถบสีแดงคือลูกค้าที่ยังไม่มีข้อมูลสำคัญ กรุณาระบุให้ครบด้วย !!</font></div>								
		<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
		   <tr>
				<td align="center">						
						<table align="center" frame="box" width="100%">
								
								<tr bgcolor="#CDC9C9">
									<th width="160">สถานะ</th>
									<th width="120">รหัสลูกค้า</th>
									<th width="140">รหัสบัตรประชาชน</th>
									<th width="120">ชื่อนำหน้า</th>
									<th width="160">ชื่อจริง</th>
									<th width="160">นามสกุล</th>
									<th width="80">แก้ไข</th>										
								</tr>
				
									
						<?php	
							$i = 0;
							$rows = pg_num_rows($qry3);
							$rows1 = pg_num_rows($qry4);
							
							if($rows > 0 OR $rows1 > 0){
									//ลูกค้าที่ต้องแก้ไขข้อมูล (ลูกค้าใหม่)
									while($result3 = pg_fetch_array($qry3)){
											$CusID = $result3["CusID"];
											$A_FIRNAME = $result3["A_FIRNAME"];
											$A_NAME = $result3["A_NAME"];
											$A_SIRNAME = $result3["A_SIRNAME"];
											$N_IDCARD = $result3["N_IDCARD"];
											$CusState = $result3["CusState"];
											IF($CusState == '0'){
												$relation = 'ผู้เช่าซื้อ';
											}else if($CusState == '1'){
												$relation = 'ผู้เช่าซื้อร่วม';
											}else{
												$relation = 'ผู้ค้ำประกัน';
											}
											
											IF($N_IDCARD == ""){
												$N_IDCARD = $result3["N_CARDREF"];
											}	
											
											$i++;
											echo "<tr bgcolor=#FFCCCC onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFCCCC';\" align=center>";									
						?>
											<td align="center"><?php echo $relation; ?></td>
											<td align="center"><?php echo $CusID ?></td>
											<td align="center"><?php echo $N_IDCARD; ?></td>
											<td align="left"><?php echo $A_FIRNAME; ?></td>
											<td align="left"><?php echo $A_NAME; ?></td>
											<td align="left"><?php echo $A_SIRNAME; ?></td>
											
											<?php if($readonlyna == 't'){ ?>
												<td align="center">
													<a onclick="javascript:popU('../manageCustomer/showdetail2.php?CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')">
														<img src="images/detail.gif" width="25px;" height="25px;" style="cursor:pointer;">
													</a>		
												</td>
											<?php }else{ ?>
												<td align="center">
												
													<?php 	//ตรวจสอบว่าหากมีการรออนุมัติแก้ไขข้อมูลลูกค้าอยู่นั้น ให้ไม่สามารถแก้ไขได้
															$qry_temp=pg_query("select * from \"Customer_Temp\" where \"CusID\"='$CusID' and \"statusapp\"='2'");
															$num_temp=pg_num_rows($qry_temp);
															if($num_temp>0){
																echo " รออนุมัติ ";
															}else{
													?>		
													<a onclick="javascript:popU('../manageCustomer/frm_Edit.php?CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')">
														<img src="images/edit_pa1.png" width="25px;" height="25px;" style="cursor:pointer;">
													</a>
													<?php } ?>
												</td>


											<?php } ?>	
										</tr>
								<?php } ?>
						<?php		
								//ลูกค้าเก่าที่ถูกเพิ่มอย่างถูกต้อง
								while($result4 = pg_fetch_array($qry4)){
									$type = $result4["type"];
									$CusID = $result4["CusID"];
									$N_IDCARD = $result4["N_IDCARD"];								
									$CusState = $result4["CusState"];
									IF($CusState == '0'){
										$relation = 'ผู้เช่าซื้อ';
									}else if($CusState == '1'){
										$relation = 'ผู้เช่าซื้อร่วม';
									}else{
										$relation = 'ผู้ค้ำประกัน';
									}
									
									IF($N_IDCARD == ""){
										$N_IDCARD = $result4["N_CARDREF"];
									}
									
									if($type == '1'){ 										
											$A_FIRNAME = $result4["A_FIRNAME"];
											$A_NAME = $result4["A_NAME"];
											$A_SIRNAME = $result4["A_SIRNAME"];										
									}else if($type == '2'){	
											$A_NAME = $result4["thcap_fullname"];
									}
										$i++;
										if($i%2==0){
											echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
										}else{
											echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
										} 
						?>
										
											<td align="center"><?php echo $relation; ?></td>
											<td align="center"><?php echo $CusID ?></td>
											<td align="center"><?php echo $N_IDCARD; ?></td>
											<td align="left"><?php echo $A_FIRNAME; ?></td>
											<td align="left"><?php echo $A_NAME; ?></td>
											<td align="left"><?php echo $A_SIRNAME; ?></td>
											
									<?php if($readonlyna == 't'){ ?>
									
												<td align="center">	
													<?php if($type == '1'){ ?>
														<a onclick="javascript:popU('../manageCustomer/showdetail2.php?CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')">
															<img src="images/detail.gif" width="25px;" height="25px;" style="cursor:pointer;">
														</a>	
													<?php }else if($type == '2'){
																$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$CusID' ");
																$corp_regis = pg_fetch_result($qry_corp_regis,0);
													?>		
														<a onclick="javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=<?php echo $corp_regis; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')">
															<img src="images/detail.gif" width="25px;" height="25px;" style="cursor:pointer;">
														</a>
													<?php } ?>
												</td>
												
									<?php }else{ ?>
											
											
											
											<td align="center">	
											<?php if($type == '1'){ ?>
												<a onclick="javascript:popU('../manageCustomer/frm_Edit.php?CusID=<?php echo $CusID; ?>&autoapp=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')">
													<img src="images/edit_pa1.png" width="25px;" height="25px;" style="cursor:pointer;">
												</a>	
											<?php }else if($type == '2'){
														$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$CusID' ");
														$corp_regis = pg_fetch_result($qry_corp_regis,0);
											?>		
												<a onclick="javascript:popU('../corporation/frm_EditCorpAll.php?corpID=<?php echo $CusID; ?>&editcorp=2&autoapp=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')">
													<img src="images/edit_pa1.png" width="25px;" height="25px;" style="cursor:pointer;">
												</a>
											<?php } ?>
											</td>
											
									<?php } ?>		
											
											
											
											
											
											
										</tr>
								<?php } ?>
								
								
								
								<tr bgcolor="#DDDDDD">
										<td colspan="8">รวม: <?php echo $rows+$rows1 ;?> รายการ</td>
								</tr>			
					<?php }else{  echo "<tr bgcolor=\"#BFEFFF\"><td align=\"center\" colspan=\"7\"><h2> ไม่พบข้อมูลลูกค้าใหม่  </h2></td></tr>"; }?>							
						</table>
					</td>
				</tr>
		</table>		
	</form>
</body>