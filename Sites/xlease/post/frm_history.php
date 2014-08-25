<?php
	if($limitshow == 'true'){
		$header = "รายการที่รออนุมัติและประวัติการอนุมัติ การขอยกเลิกสัญญาเช่าซื้อ 30 รายการล่าสุด  (  <a onclick=\"javascript:popU('frm_history.php?frmlimit=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1050,height=650')\" style=\"cursor:pointer;\"><u>ประวัติการอนุมัติทั้งหมด</u></a>)";
	}else if($limitshowapp == 'true'){
		$header = "ประวัติการขอยกเลิกสัญญาเช่าซื้อ 30 รายการล่าสุด (  <a onclick=\"javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1050,height=650')\" style=\"cursor:pointer;\"><u>ทั้งหมด</u></a>)";
		$limit = "limit 30";
		$wherenowait = "WHERE \"appstatus\" != '0'";
		$strOrder = "\"appdate\" DESC";
	}else{
		include("../config/config.php");
		$header = "ประวัติการขอยกเลิกสัญญาเช่าซื้อ";
		$frmlimit = $_GET["frmlimit"];
		IF($frmlimit == 't'){
			$limitshow = 'true';
		}else{
			$wherenowait = "WHERE \"appstatus\" != '0'";
			$strOrder = "\"appdate\" DESC";
		}	
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title> ประวัติการยกเลิกสัญญาเช่าซื้อ</title>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
		<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr>
						<td colspan="7" align="center" bgcolor="#8B8B7A" height="15px" >
							<font color="white"><h3><?php echo $header; ?></h3></font>
						</td>
					</tr>
					<tr bgcolor="#CDCDB4" height="25px">
					
						<th>เลขที่สัญญา</th>	
						<th>ผู้ขอยกเลิก</th>
						<th>วันที่ขอยกเลิก</th>						
						<th>ผู้อนุมัติ</th>
						<th>วันที่อนุมัติ</th>
						<th>เหตุผลที่ขอยกเลิก</th>
						<th>สถานะ</th>
					
					</tr>	
			<?php 
				$i =0;
				
				FOR($loop=1;$loop<=2;$loop++){
				
						IF($limitshow == 'true'){
							IF($loop == 1){
								IF($frmlimit != 't'){
									$limit = "limit 30";
								}	
								$wherenowait = "WHERE \"appstatus\" = '0'";
								$strOrder = "\"cancel_date\" ASC";
							}else{								
								$wherenowait = "WHERE \"appstatus\" != '0'";
								$strOrder = "\"appdate\" DESC";			
							}
						}
						$sql = pg_query("SELECT * FROM \"Fp_cancel_approve\" $wherenowait order by $strOrder $limit");
						$row = pg_num_rows($sql);
						
						while($result = pg_fetch_array($sql)){
							$fp_appID = $result['fp_appID'];
							$iduser = $result['id_user'];
							$appstate = $result['appstatus'];
							$appuser = $result['appuser'];
							if($appstate == '1'){
								$status =  "อนุมัติ";
								$textcolor = "#00CD00";
							}else if($appstate == '2'){
								$status = 'ไม่อนุมัติ';
								$textcolor = "#CD0000";
							}else{
								$status = 'รออนุมัติ';
								$textcolor = "#8B8B00";
							}
							
							$sqluser = pg_query("SELECT  fullname FROM \"Vfuser\" where id_user = '$iduser' ");
							$userresult = pg_fetch_array($sqluser);
							
							$sqlapp = pg_query("SELECT  fullname FROM \"Vfuser\" where id_user = '$appuser' ");
							$appresult = pg_fetch_array($sqlapp);
							
							$textreason = $result['reason'];
							$subtextreason = mb_substr($textreason,0,55,'UTF-8');
					
							
								$i++;
								if($i%2==0){
									echo "<tr bgcolor=#EEEED1 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEED1';\" align=center>";
								}else{
									echo "<tr bgcolor=#FFFFE0 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFE0';\" align=center>";
								}
								echo "		<td>".$result['IDNO']."</td>
											<td align=\"left\">".$userresult['fullname']."</td>		
											<td>".$result['cancel_date']."</td>
											<td align=\"left\">".$appresult['fullname']."</td>
											<td >".$result['appdate']."</td>
											<td align=\"left\"><a onclick=\"javascript:popU('cc_detail_app.php?fp_appID=$fp_appID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=250')\" style=\"cursor: pointer\">".$subtextreason."...</a></td>
											<td><font color=\"$textcolor\">".$status."</font></td>
									</tr>";
						}
						
						IF($limitshow == 'true'){
							$sumrow += $row;
							IF($frmlimit != 't'){
								IF($row < 30){
									$limit = 30 - $row;
									$limit = "limit ".$limit;
								}else{
									$loop = 3;
								}
							}	
						}else{
							$sumrow = $row;
							$loop = 3;
						}						
				}		
			?>			
					<tr bgcolor="#CDCDB4">
						<td align="right" colspan="7"  height="18px">
							 <font color="red"><b><?php echo $sumrow; ?> รายการ </b></font> 
						</td>						
					</tr>
			</table>
</body>
</html>			