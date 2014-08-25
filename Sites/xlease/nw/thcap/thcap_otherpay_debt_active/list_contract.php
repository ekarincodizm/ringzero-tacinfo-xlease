<?php
include("../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานค้างชำระหนี้อื่นๆ</title>
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
<table width="950" border="1" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td bgcolor="#CDB79E" align="center" height="25px">
				<h1><b>แสดงแบบรายสัญญา</b><h1>
			</td>
		</tr>
</table>
<?php 

$sql = pg_query("SELECT distinct\"contractID\"  FROM vthcap_otherpay_debt_active order by \"contractID\"");

while($result = pg_fetch_array($sql)){ 
$conid = $result['contractID'];  
?>
	<div style="margin-top:0px" ></div>
	<table width="950" border="1" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td bgcolor="#CDB79E">
				 <b>
				 <span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
				 <font color="red"><u><?php echo $conid;?></u></font></span>
				 </b>
			</td>
		</tr>
	</table>	
	<table width="950" border="1" cellspacing="0" cellpadding="0"  align="center">	
		<tr>
			<td width="30%" valign="top"  bgcolor="#FFFFFF" >
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
			<td width="70%">
				<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr><td colspan="5" align="center" bgcolor="#8B795E" height="15px" ><font color="white"><b>--- รายการหนี้ค้างชำระ ---</b></font></td></tr>
					<tr bgcolor="#CDB38B">
						<th width="10%">รายการ</th>
						<th width="25%">ประเภท</th>
						<th>วันที่ตั้งหนี้</th>
						<th>จำนวนหนี้</th>
						<th>จำนวนหนี้คงเหลือ</th>
					</tr>	
					
					
			<?php 
					$sql2 = pg_query("SELECT * FROM vthcap_otherpay_debt_active where \"contractID\" = '$conid' order by \"typePayID\"");
					$i = 0;
					$sumamt = 0;
					$row2 = pg_num_rows($sql2);
					while($result2 = pg_fetch_array($sql2)){
					$i++;
					if($i%2==0){
						echo "<tr bgcolor=#EECFA1 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EECFA1';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFDEAD onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFDEAD';\" align=center>";
					}
					$typePayID=$result2['typePayID'];
					$sql3 = pg_query("SELECT \"tpDesc\",\"tpFullDesc\" FROM account.\"thcap_typePay\"  where \"tpID\" ='$typePayID' ");
					$result3 = pg_fetch_array($sql3);	
					echo "
								<td>".$i."</td>
								<td>".$result3['tpDesc']."</td>
								<td>".$result2['typePayRefDate']."</td>
								<td align=\"right\">".number_format($result2['typePayAmt'],2)."</td>
								<td align=\"right\">".number_format($result2['typePayLeft'],2)."</td>
						</tr>";
					$sumamt = $sumamt + $result2['typePayLeft'];	
					}
			?>			
					<tr bgcolor="#CDB38B">
						<td align="left" colspan="2" height="18px">
							รวม :  <font color="red"><b><?php echo $row2; ?></b></font> สัญญา
						</td>
						<td align="right" colspan="3" height="18px">
							รวมหนี้ที่ค้าง :  <font color="red"><u><b><?php echo number_format($sumamt,2); ?></b></u></font>
						</td>
					</tr>
				</table>	
			</td>
				
		</tr>			
	</table>
<?php 
$sumallamt = $sumallamt+$sumamt;
$rowsum = $row2 + $rowsum; 
} ?>
	<table width="950" border="1" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td align="center"  height="18px" width="15%" bgcolor="#FFFFFF">
							รวมทั้งหมด :  <font color="red"><b><?php echo $rowsum; ?></b></font> สัญญา
			</td>
			<td bgcolor="#CDB79E" align="right" height="25px">
				<h2> รวมหนี้ที่ค้างของทุกสัญญา :  <font color="red"><u><b><?php echo number_format($sumallamt,2); ?></b></u></font> บาท<h2>
			</td>
		</tr>
	</table>
</body>
</html>