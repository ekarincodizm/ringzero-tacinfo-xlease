<?php
include("../../config/config.php");
//-===============================================-
//					กำหนดค่าตัวแปร
//-===============================================-
$money = $_GET["money"]; //รับจำนวนเงินที่ต้องการหา
$avgmoney = 400; //จำนวนเงินที่จะนำไป บวกลบ กับยอดเงินหลักเพื่อหาเงินเฉลี่ย

$maxmoney = $money + $avgmoney; //เงินที่มากกว่าเงินโอน
$minmoney = $money - $avgmoney;	//เงินที่น้อยกว่าเงินโอน

//-===============================================-
//					Query
//-===============================================-

//query ที่จำนวนเงินเท่ากัน
$qry_money1 = pg_query("
						SELECT 	a.\"contractID\", a.\"conMinPay\", b.\"thcap_fullname\",1 as show
						FROM 	\"thcap_mg_contract\" a
						LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
						WHERE 	a.\"conMinPay\" = '$money' AND
								b.\"CusState\" = '0'
						 
						UNION

						SELECT 	a.\"contractID\", a.\"conMinPay\",b.\"thcap_fullname\",1
						FROM 	\"thcap_lease_contract\" a
						LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
						WHERE 	a.\"conMinPay\" = '$money' AND
								b.\"CusState\" = '0'
								
						UNION
						SELECT distinct a.\"contractID\",0, b.\"thcap_fullname\",2
						FROM account.\"thcap_mg_payTerm\" a
						LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
						WHERE 	\"ptMinPay\" = '$money' AND b.\"CusState\" = '0' and a.\"contractID\" not in (select \"contractID\" from \"thcap_contract\" where \"conMinPay\" > 0)

						ORDER BY  \"contractID\", \"conMinPay\" 
					");
$nummoney1=pg_num_rows($qry_money1);
						
//query ที่จำนวนเงินเฉลี่ยระหว่างยอดเงิน
$qry_money2 = pg_query("
							SELECT 	a.\"contractID\", a.\"conMinPay\", b.\"thcap_fullname\",1 as show
							FROM 	\"thcap_mg_contract\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							WHERE 	(a.\"conMinPay\" BETWEEN $minmoney AND  $maxmoney) AND
									b.\"CusState\" = '0'
							 
							UNION

							SELECT 	a.\"contractID\", a.\"conMinPay\",b.\"thcap_fullname\",1
							FROM 	\"thcap_lease_contract\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							WHERE 	(a.\"conMinPay\" BETWEEN $minmoney AND  $maxmoney) AND
									b.\"CusState\" = '0'
									
							UNION
							SELECT distinct a.\"contractID\",0, b.\"thcap_fullname\",2
							FROM account.\"thcap_mg_payTerm\" a
							LEFT JOIN \"vthcap_ContactCus_detail\" b ON a.\"contractID\" = b.\"contractID\"
							WHERE 	(\"ptMinPay\" BETWEEN $minmoney AND  $maxmoney) AND b.\"CusState\" = '0' and a.\"contractID\" not in (select \"contractID\" from \"thcap_contract\" where \"conMinPay\" > 0)

							ORDER BY  \"conMinPay\",\"contractID\" 
						");
$nummoney2=pg_num_rows($qry_money2);
						
//นับจำนวนข้อมูล											
$rows_transpay1 = pg_num_rows($qry_money1);
$rows_transpay2 = pg_num_rows($qry_money2);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>สัญญาที่มีความน่าจะเป็นในการโอนเงินมา</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="width:700px;margin:0px auto;" >
		<div style="padding-top:25px;"></div>
		<div><h1>สัญญาที่มีความน่าจะเป็นเจ้าของเงินโอน</h1></div>
		<!-- สัญญาที่มีค่างวดขั้นต่ำเท่ากับเงินโอน-->
		<fieldset><legend><b><font size="2px">สัญญาที่มีค่างวดเท่ากับ <?php echo number_format($money,2); ?> บาท </font></b></legend>
			<table frame="box" width="99%">
				<tr bgcolor="#CDC5BF" >
					<th width="30%">เลขที่สัญญา</th>
					<th width="50%">ชื่อผู้กู้หลัก</th>
					<th>ค่างวด</th>
				</tr>
				<?php
				if($rows_transpay1 > 0){
					$i = 0;			
						while($result_money1 = pg_fetch_array($qry_money1)){
							
							if($i%2==0){
								echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
							}else{
								echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
							}	
							$IDpopup = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".$result_money1["contractID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><font color=\"red\"><u>".$result_money1["contractID"]."</u></font></a>";

							IF($result_money1["thcap_fullname"]){
								if($result_money1["show"]=='2'){
									//หาจำนวนเงินทั้งหมดที่เกี่ยวข้องมาแสดง
									
									/* ใช้ในกรณี query ข้อมูลมาครั้งเดียว แต่จะมีปัญหาตรง จำนวนเงินไม่สามารถใส่ format ได้
									$qryallmoney=pg_query("select replace(replace(replace((SELECT ARRAY( select  distinct \"ptMinPay\" from account.\"thcap_mg_payTerm\"  where \"contractID\" = '$result_money1[contractID]'))::text, '{', ''),'}', ''), ',','/')");
									*/
									$qryallmoney=pg_query("select  distinct \"ptMinPay\" from account.\"thcap_mg_payTerm\"  where \"contractID\" = '$result_money1[contractID]' order by \"ptMinPay\"");
									$nummoney=pg_num_rows($qryallmoney);
									$i=0;
									$minpay="";
									while($resmoney=pg_fetch_array($qryallmoney)){
										$i++;
										list($money)=$resmoney;
										$money=number_format($money,2);
										if($i==$nummoney){
											$minpay=$minpay.$money;
										}else{
											$minpay=$minpay.$money."/";
										}
									}
									
								}else{
									$minpay = number_format($result_money1["conMinPay"],2);
								}
							}else{
								$minpay = "";
							}
							echo "<td>".$IDpopup."</td>";
							echo "<td align=\"left\">".$result_money1["thcap_fullname"]."</td>";
							echo "<td align=\"right\">".$minpay."</td>";
							echo "<tr>";
							
							$i++;
						}
				}else{
					echo "<tr bgcolor=\"#EEE5DE\" align=\"center\"><td colspan=\"5\">-- ไม่พบเลขที่สัญญาที่ใกล้เคียง --</td></tr>";
				}	
					echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $rows_transpay1 รายการ</td></tr>";		
				?>
			</table>
		</fieldset>	
		<div style="padding-top:25px;"></div>
		<!-- สัญญาที่มีค่างวดขั้นต่ำมากกว่าหรือน้อยกว่าเงินโอน 400 บาท-->
		<fieldset><legend><b><font size="2px">สัญญาที่มีค่างวดระหว่าง <?php echo number_format($minmoney,2); ?> - <?php echo number_format($maxmoney,2); ?> บาท</font></b></legend>
			<div align="left"><font color="gray">*บวกหรือลบจากเงินโอน 400 เผื่อกรณีรวมเงินค่าปรับมาด้วย</font></div>
			<table frame="box" width="99%">
				<tr bgcolor="#CDC5BF" >
					<th width="30%">เลขที่สัญญา</th>
					<th width="50%">ชื่อผู้กู้หลัก</th>
					<th>ค่างวด</th>
				</tr>
				<?php
				if($rows_transpay2 > 0){
					$i = 0;		
						while($result_money2 = pg_fetch_array($qry_money2)){
							
							if($i%2==0){
								echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
							}else{
								echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
							}	
							$IDpopup = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".$result_money2["contractID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><font color=\"red\"><u>".$result_money2["contractID"]."</u></font></a>";

							IF($result_money2["thcap_fullname"]){
								if($result_money2["show"]=='2'){
									//หาจำนวนเงินทั้งหมดที่เกี่ยวข้องมาแสดง
									/*ใช้ในกรณี query ข้อมูลมาครั้งเดียว แต่จะมีปัญหาตรง จำนวนเงินไม่สามารถใส่ format ได้
									$qryallmoney2=pg_query("select replace(replace(replace((SELECT ARRAY( select  distinct \"ptMinPay\" from account.\"thcap_mg_payTerm\"  where \"contractID\" = '$result_money2[contractID]'))::text, '{', ''),'}', ''), ',','/')");
									*/
									
									$qryallmoney=pg_query("select  distinct \"ptMinPay\" from account.\"thcap_mg_payTerm\"  where \"contractID\" = '$result_money2[contractID]' order by \"ptMinPay\"");
									$nummoney=pg_num_rows($qryallmoney);
									$i=0;
									$minpay="";
									while($resmoney=pg_fetch_array($qryallmoney)){
										$i++;
										list($money)=$resmoney;
										$money=number_format($money,2);
										if($i==$nummoney){
											$minpay=$minpay.$money;
										}else{
											$minpay=$minpay.$money."/";
										}
									}
								}else{
									$minpay = number_format($result_money2["conMinPay"],2);
								}
							}else{
								$minpay = "";
							}
							echo "<td>".$IDpopup."</td>";
							echo "<td align=\"left\">".$result_money2["thcap_fullname"]."</td>";
							echo "<td align=\"right\">".$minpay."</td>";
							echo "<tr>";
							
							$i++;
						}
				}else{
					echo "<tr bgcolor=\"#EEE5DE\" align=\"center\"><td colspan=\"5\">-- ไม่พบเลขที่สัญญาที่ใกล้เคียง --</td></tr>";
				}	
					echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $rows_transpay2 รายการ</td></tr>";
					
				?>
			</table>
		</fieldset>
	<div style="padding-top:25px;"></div>		
	<div style="text-align:center;"><input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;" ></div>
	<div style="padding-top:25px;"></div>	
</div>
</body>
</html>