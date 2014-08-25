<?php
include("../../config/config.php");

$app_date = Date('Y-m-d H:i:s');
$contractid = $_GET['conid'];

// หาประเภทสินเชื่อ
$qry_credit_type = pg_query("select \"thcap_get_creditType\"('$contractid') as credit_type");
$credit_type = pg_fetch_result($qry_credit_type,0);

//หาตำแหน่งที่ไฟล์นี้อยู่และเปลี่ยนเป็นการ กลับไปเริ่มที่หน้า xlease เสมอ
$realpath = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');
//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) แสดงวงเงิน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<div style="margin-top:1px" ></div>
<body>
<table width="850" border="1" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td bgcolor="#79CDCD" align="center" height="25px">
				<h1><b>แสดงวงเงินของสัญญา</b><h1>
			</td>
		</tr>
</table>
<?php 

if($credit_type == "LOAN" || $credit_type == "JOINT_VENTURE" || $credit_type == "PERSONAL_LOAN")
{
	$sql = pg_query("SELECT *  FROM thcap_mg_contract where \"contractID\" = '$contractid'");
}
else
{
	$sql = pg_query("SELECT *  FROM thcap_lease_contract where \"contractID\" = '$contractid'");
}

while($result = pg_fetch_array($sql)){ 
$conid = $result['contractID'];  
?>
	<div style="margin-top:0px" ></div>
	<table width="850" border="1" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td bgcolor="#79CDCD">
				 <b>		
				 <font color="red"><?php echo $conid;?></font>
				 </b>
			</td>
		</tr>
	</table>	
	<table width="850" border="1" cellspacing="0" cellpadding="0"  align="center">	
		<tr>
			<td width="35%" valign="top"  bgcolor="#FFFFFF" >
				<table width="99%"  cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<?php	$sql1 = pg_query("SELECT \"CusState\", \"CusID\" FROM \"thcap_ContactCus\" where \"contractID\" = '$conid' order by \"CusState\"");
					$i = 0;
					while($result1 = pg_fetch_array($sql1)){
						$cusid = $result1['CusID'];
						$sqlcus = pg_query("SELECT full_name FROM \"VSearchCus\" where  \"CusID\" = '$cusid'");
						 list($cusname) = pg_fetch_array($sqlcus);
						 
						if($cusname == ""){
							$sqlcus2 = pg_query("SELECT concat(COALESCE(\"corpType\",''::character varying), ' ', COALESCE(\"corpName_THA\",''::character varying)) AS fullname FROM \"th_corp\" where  \"corpID\" = '$cusid'");
							list($cusname) = pg_fetch_array($sqlcus2);	
						}
						
						if($result1['CusState'] == '0'){						
							echo "<tr><td> ผู้กู้หลัก : ".$cusname."</td></tr>";							
						}else if($result1['CusState'] > '0'){	
							$i++;
							echo "<tr><td> ผู้กู้ร่วม ".$i." : ".$cusname."</td></tr>";	
						}
					}
			?>							
				</table>	
			</td>
			<td width="65%">
				<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr><td colspan="5" align="center" bgcolor="#528B8B" height="15px" ><font color="white"><b>--- รายการใช้วงเงิน ---</b></font></td></tr>
					<tr bgcolor="#9FB6CD">
						<th width="15%">รายการ</th>
						<th width="40%">สัญญา</th>
						<th>จำนวนเงิน</th>
					</tr>	
					
					
			<?php 
					if($credit_type == "LOAN" || $credit_type == "JOINT_VENTURE" || $credit_type == "PERSONAL_LOAN")
					{
						$sql2 = pg_query("SELECT \"conCreditRef\" FROM thcap_mg_contract where \"contractID\" = '$contractid'");
					}
					else
					{
						$sql2 = pg_query("SELECT \"conCreditRef\" FROM thcap_lease_contract where \"contractID\" = '$contractid'");
					}
					
					$i = 0;
					
					$result2 = pg_fetch_array($sql2);
					 $conref = $result2['conCreditRef'];	
					
		
					$countarraysql = pg_query("SELECT ta_array_list('$conref')");
					while($countarray = pg_fetch_array($countarraysql)){ 
					$conidref = $countarray['ta_array_list'];
					
						$rearraysql = pg_query("SELECT ta_array_get('$conref','$conidref')");
						$rearray = pg_fetch_array($rearraysql);
						$sumarray += $rearray['ta_array_get'];
					$i++;
					
									
					if($i%2==0){
						echo "<tr bgcolor=#8DEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#8DEEEE';\" align=center>";
					}else{
						echo "<tr bgcolor=#97FFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#97FFFF';\" align=center>";
					}

					echo "
								<td>".$i."</td>
								<td><a onclick=\"javascript:popU('$realpath/frm_Index.php?show=1&idno=$conidref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"  ><u>".$conidref."</u></a></td>
								<td align=\"right\">".number_format($rearray['ta_array_get'],2)."</td>								
						</tr>";
				
					}					
}					
			?>			
					<tr bgcolor="#9FB6CD">
						<td align="left" colspan="2" height="18px">
							รวม :  <font color="red"><b><?php echo  $i; ?></b></font> รายการ
						</td>
						<td align="right" colspan="3" height="18px">
							รวมเงิน :  <font color="red"><u><b><?php echo number_format($sumarray,2); ?></b></u></font>
						</td>
					</tr>
				</table>	
			</td>
				
		</tr>			
	</table>
</body>
</html>