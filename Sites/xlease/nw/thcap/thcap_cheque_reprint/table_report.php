<?php
include("../../../config/config.php");
$strings = $_GET["condition"];
$datecon = $_GET["datecon"];
if($datecon == ""){
	$datecon = nowDate();
}
$condition = " date(a.\"$strings\") = '$datecon' ";

$strSort = $_GET["sort"];
if($strSort == "")
{
	$strSort = "$strings";
}

$strOrder = $_GET["order"];
if($strOrder == "")
{
	$strOrder = "ASC";
}

$qry_selcol = pg_query("SELECT a.\"revChqToCCID\",a.\"bankChqNo\",date(a.\"revChqDate\") AS \"revChqDate1\",date(a.\"bankChqDate\") AS \"bankChkDate1\",
								b.\"bankName\",a.\"bankChqToCompID\",a.\"bankChqAmt\",a.\"revChqID\"
						FROM \"finance\".\"thcap_receive_cheque\" a 
						left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
						where $condition order by \"$strSort\" $strOrder");
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ..........</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body >
<form name="frm" method="post">

	<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center" >	
	   <tr>
			<td align="center">
					<table align="center" frame="box" width="100%">
							<div style="padding-top:1px;"></div>	
							<tr bgcolor="#CDC0B0">
								<th><a onclick="javascript:sort('revChqToCCID','<?php echo $strNewOrder ?>');" style="cursor:pointer;"><u>เลขที่สัญญา</u></a></th>
								<th>ชื่อ-นามสกุล ลูกค้า</th>
								<th><a onclick="javascript:sort('bankChqNo','<?php echo $strNewOrder ?>');" style="cursor:pointer;"><u>เลขที่เช็ค</u></a></th>
								<th><a onclick="javascript:sort('bankChqDate','<?php echo $strNewOrder ?>');" style="cursor:pointer;"><u>วันที่บนเช็ค</u></a></th>
								<th><a onclick="javascript:sort('BID','<?php echo $strNewOrder ?>');" style="cursor:pointer;"><u>ธนาคารที่ออกเช็ค</u></a></th>
								<th>จ่ายบริษัท</th>
								<th><a onclick="javascript:sort('bankChqAmt','<?php echo $strNewOrder ?>');" style="cursor:pointer;"><u>ยอดเช็ค(บาท)</u></a></th>
								<th>พิมพ์</th>		
							</tr>
			
								
					<?php		
						
											
						$row_Selcol = pg_num_rows($qry_selcol);
							if($row_Selcol > 0){
							
								while($re_selcol = pg_fetch_array($qry_selcol)){
									$revChqToCCID = $re_selcol["revChqToCCID"];
									$revChqID = $re_selcol["revChqID"];
									$bankChqNo=$re_selcol["bankChqNo"];
									$bankChqDate = $re_selcol["bankChkDate1"]; 
									$bankName = $re_selcol["bankName"]; 
								
									$bankChqToCompID = $re_selcol["bankChqToCompID"]; 
									$bankChqAmt = $re_selcol["bankChqAmt"]; 
								
									
	
									
									//หาชื่อลูกค้า
									$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
									list($cusid,$fullname) = pg_fetch_array($qry_cusname);									

																									
									$i++;
									if($i%2==0){
										echo "<tr bgcolor=#EEDFCC onmouseover=\"javascript:this.bgColor = '#8DEEEE';\" onmouseout=\"javascript:this.bgColor = '#EEDFCC';\" align=center>";
									}else{
										echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#8DEEEE';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
									} 
					?>
									
											<td>
												<span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $revChqToCCID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;">
												<font color="red"><u><?php echo $revChqToCCID ?></u></font></span>
											</td>
											<td align="left">
												<a style="cursor:pointer;" onclick="javascipt:popU('../../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
												(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
												<?php echo $fullname; ?>
											</td>
											<td><?php echo $bankChqNo; ?></td>
											<td><?php echo $bankChqDate; ?></td>
											<td align="left"><?php echo $bankName; ?></td>
											<td><?php echo $bankChqToCompID; ?></td>
											<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
											<td>
												<a style="cursor:pointer;" onclick="javascipt:popU('pdf_cheque.php?maincon=<?php echo $revChqToCCID; ?>&typecon=<?php echo $strings; ?>&datecon=<?php echo $datecon; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">
												<img src="../images/icoPrint.png"></a>
											</td>
										</tr>
					<?php } ?>
						<tr bgcolor="#DDDAB2">
							<td colspan="12">
								รวม: <?php echo $row_Selcol ;?> รายการ	
															
							</td>
						</tr>
					<?php }else{  echo "<tr bgcolor=\"#BFEFFF\"><td align=\"center\" colspan=\"12\"><h2> ไม่พบรายการรับเช็ค </h2></td></tr>"; }?>	
					</table>
				</td>
			</tr>
	</table>
</form>
</body>