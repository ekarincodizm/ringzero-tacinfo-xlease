<?php
	if($limitshow == 't'){
		$header = "ประวัติการขอรหัสผ่านใหม่ 30 รายการล่าสุด (<a style=\"color:#FF3300;cursor:pointer;\" onclick=\"javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\"><u>ทั้งหมด</u></a>)";
		$limit = "limit 30";
		$widthtb = "100%";
	}else{
		include("../../config/config.php");
		$header = "ประวัติการขอรหัสผ่านใหม่  ";
		$widthtb = "80%";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> ประวัติการขอรหัสผ่านใหม่ </title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
<fieldset style="width:<?php echo $widthtb; ?>;margin:0px auto;">
		<legend><h3><?php echo $header; ?></h3></legend>
<?php
		$strSQL1 = "	SELECT a.*,b.\"fullname\" as \"doerfullname\",c.\"fullname\" as \"appvfullname\"
						FROM \"repass_admin\" a 
						LEFT JOIN \"Vfuser\" b ON a.\"id_user\" = b.\"id_user\"
						LEFT JOIN \"Vfuser\" c ON a.\"appvID\" = c.\"id_user\"
						WHERE a.\"repass_status\" != '0' 
						ORDER BY \"repassID\" DESC
						$limit
					";
		$objQuery1 = pg_query($strSQL1);
		$nrows1=pg_num_rows($objQuery1);
?>
				<table align="center" width="100%"  frame="box"  cellspacing="1" cellpadding="1" >
					<tr align="center" bgcolor="#CDC9C9">
						<th width="50">รายการที่</th>
						<th width="198">ชื่อ-นามสกุล ผู้ขอเปลี่ยน</th>
						<th width="190">Username</th>						
						<th width="150">วันที่ขอ</th>
						<th width="150">ผู้อนุมัติ</th>
						<th width="150">วันเวลาที่อนุมัติ</th>
						<th width="190"> สถานะ</th>

					</tr>
<?php 
					if($nrows1 != 0){
								$i = 0;	
								while($results1 = pg_fetch_array($objQuery1)){ 
									$i++;
									
									if($results1["repass_status"] == '1'){
										$status1 = 'ยืนยันแล้ว';
									}else if($results1["repass_status"] == '2'){
										$status1 = 'ปฎิเสธการยืนยัน';
									}
									
									
										if($i%2==0){
											echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=\"center\">";
										}else{
											echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=\"center\">";
										}
?>	
													<td><?php echo $i; ?></td>
													<td align="left"><?php echo $results1["doerfullname"]; ?></td>
													<td><?php echo $results1["repass_username"]; ?></td>
													<td><?php echo $results1["repass_date"]; ?></td>
													<td><?php echo $results1["appvfullname"]; ?></td>
													<td><?php echo $results1["appv_datetime"]; ?></td>
													<td><?php echo $status1; ?></td>
												</tr>
<?php 				} ?>
								<tr>
									<td bgcolor="#CDC5BF" colspan="10"><div align="center"></div> มีทั้งหมด <?php echo $nrows1; ?> รายการ</td>
								</tr>
					
<?php 			}else{ 
							echo "<tr><td colspan=\"6\" align=\"center\"><h1>ไม่พบข้อมูล</h1></td></tr>";
				}
?>
				</table>
	</fieldset>
</body>
</html>	
	