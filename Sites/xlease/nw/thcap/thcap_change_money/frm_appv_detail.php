<?php
include("../../../config/config.php");

$tm_pk = $_GET["tm_pk"];
$show = $_GET["show"]; //หากเท่ากับ 'show' แสดงว่าให้ดูเท่านั้น ไม่อนุญาติให้มีการอนุมัติ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติย้ายเงินข้ามสัญญา</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	}
</script>

</head>

<body>
<?php
	$query = pg_query("	select a.*,b.\"BAccount\" as \"begintxt\",c.\"BAccount\" as \"endtxt\"
						from public.\"thcap_transfermoney_c2c_temp\" a 
						left join \"BankInt\" b on a.\"begin_trans_type\" = b.\"BID\"
						left join \"BankInt\" c on a.\"end_trans_type\" = c.\"BID\" 
						where a.\"tm_pk\" = '$tm_pk' ");
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{
		$begin_conid = $result["begin_conid"]; // เลขที่สัญญาต้นทาง
		$begin_trans_type = $result["begin_trans_type"]; // ประเภทเงินต้นทาง
		$end_conid = $result["end_conid"]; // รหัสสัญญาปลายทาง
		$end_trans_type = $result["end_trans_type"]; // รหัสประเภทการโอนปลายทาง
		$end_trans_money = $result["end_trans_money"]; // จำนวนเงินที่รับของสัญญาปลายทาง
		$all_trans_money = $result["all_trans_money"]; // จำนวนเงินทั้งหมดที่โอนจากต้นทาง
		$masterID = $result["masterID"]; // รายการที่ทำพร้อมกัน
		$doerID = $result["doerID"]; // รหัสผู้ขอย้ายเงิน
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		$changeMoneyDate = $result["changeMoneyDate"]; // วันที่ย้ายเงิน
		$reason = $result["reason"]; // เหตุผลในการย้ายเงิน
	
		$begin_trans_type_text = $result["begintxt"]; // ประเภทการโอนต้นทาง
		$end_trans_type_text = $result["endtxt"]; // ประเภทการโอนปลายทาง
	}	
	
	// if($begin_trans_type == "997")
	// {
		// $begin_trans_type_text = "เงินค้ำประกันการชำระหนี้";
	// }
	// elseif($begin_trans_type == "998")
	// {
		// $begin_trans_type_text = "เงินพักรอตัดรายการ";
	// }
	
	// if($end_trans_type == "997")
	// {
		// $end_trans_type_text = "เงินค้ำประกันการชำระหนี้";
	// }
	// elseif($end_trans_type == "998")
	// {
		// $end_trans_type_text = "เงินพักรอตัดรายการ";
	// }
	
	$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
	while($result_name = pg_fetch_array($qry_name))
	{
		$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
	}
?>

<?php  
	//สัญญาเดิม
	//ชื่อผู้กู้
	$pathroot=redirect($_SERVER['PHP_SELF'],'nw/search_cusco');	
	$qry_name1=pg_query("select * from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$begin_conid' and \"CusState\" = '0'");
	$numco=pg_num_rows($qry_name1);
	$i=1;
	$nameold0="";
	while($resco=pg_fetch_array($qry_name1))
	{
		$name2=trim($resco["thcap_fullname"]);//คำนำหน้า ชื่อ สกุล
		if($numco==1)
		{ 
			$nameold0=$name2;
		}
		else
		{
			if($i==$numco)
			{
				$nameold0=$nameold0." ".$name2;
			}
			else
			{
				$nameold0=$nameold0." ".$name2.", ";//เพิ่ม ,
			}
		}
		$i++;
	}

	
	//ชื่อผู้กู้ร่วม
	$qry_name1=pg_query("select * from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$begin_conid'and \"CusState\" = '1'");
	$numco1=pg_num_rows($qry_name1);
	$i=1;
	$nameold1="";
	while($resGua=pg_fetch_array($qry_name1)){		
		$name=trim($resGua["thcap_fullname"]);//คำนำหน้า ชื่อ สกุล
		if($numco1==1)
		{ 
			$nameold1=$name;
		}else{
			if($i==$numco1){
				$nameold1=$nameold1." ".$name;
			}else{
				$nameold1=$nameold1." ".$name.", ";
			}
		}
	$i++;
	}
	//ค้ำประกัน
	$qry_name1=pg_query("select * from \"vthcap_ContactCus_detail\"
		where \"contractID\" = '$begin_conid' and \"CusState\" = '2'");
		$numco=pg_num_rows($qry_name1);		
		$i=1;
		$nameold2="";
		
		while($resco=pg_fetch_array($qry_name1))
		{
			$name2=trim($resco["thcap_fullname"]);//คำนำหน้า ชื่อ สกุล
			if($numco==1){ 
				$nameold2=$name2;
				}else{
				if($i==$numco){
					$nameold2=$nameold2." ".$name2;
				}else{
					$nameold2=$nameold2." ".$name2.", ";//เพิ่ม ,
				}
			}
		$i++;
	}
	
	
	
		//สัญญาใหม่
	//ชื่อผู้กู้
	$qry_name1=pg_query("select * from \"vthcap_ContactCus_detail\"
		where \"contractID\" = '$end_conid' and \"CusState\" = '0'");
		$numco=pg_num_rows($qry_name1);
		$i=1;
		$namenewdetail="";
		while($resco=pg_fetch_array($qry_name1))
		{
			$namenew=trim($resco["thcap_fullname"]);//คำนำหน้า ชื่อ สกุล
			if($numco==1){ 
				$namenewdetail=$namenew;
				}else{
				if($i==$numco){
					$namenewdetail=$namenewdetail." ".$namenew;
				}else{
					$namenewdetail=$namenewdetail." ".$namenew.", ";//เพิ่ม ,
				}
			}
		$i++;
	}
	
		//ชื่อผู้กู้่ร่วม
	$qry_name1=pg_query("select * from \"vthcap_ContactCus_detail\"
		where \"contractID\" = '$end_conid' and \"CusState\" = '1'");
		$numco=pg_num_rows($qry_name1);
		$i=1;
		$namenew="";
		while($resco=pg_fetch_array($qry_name1))
		{
			$namenew=trim($resco["thcap_fullname"]);//คำนำหน้า ชื่อ สกุล
			if($numco==1){ 
				$namenewdetail1=$namenew;
				}else{
				if($i==$numco){
					$namenewdetail1=$namenewdetail1." ".$namenew;
				}else{
					$namenewdetail1=$namenewdetail1." ".$namenew.", ";//เพิ่ม ,
				}
			}
		$i++;
	}
	//ชื่อผู้กค้ำประกัน
	$qry_name1=pg_query("select * from \"vthcap_ContactCus_detail\"
		where \"contractID\" = '$end_conid' and \"CusState\" = '2'");
		$numco=pg_num_rows($qry_name1);		
		$i=1;
		$namenew="";
		while($resco=pg_fetch_array($qry_name1))
		{
			$namenew=trim($resco["thcap_fullname"]);//คำนำหน้า ชื่อ สกุล
			if($numco==1){ 
				$namenewdetail2=$namenew;
				}else{
				if($i==$numco){
					$namenewdetail2=$namenewdetail2." ".$namenew;
				}else{
					$namenewdetail2=$namenewdetail2." ".$namenew.", ";//เพิ่ม ,
				}
			}
		$i++;
	}
	
		//End Test
	?>
<center><h2>(THCAP) อนุมัติย้ายเงินข้ามสัญญา</h2></center>
<br>
<table align="center" border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td align="right"><font color="#0000FF">ย้ายจากสัญญา : </font></td>
		<?php echo "<td align=\"left\"><a style=\"cursor:pointer;\" onClick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$begin_conid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$begin_conid</u></FONT></a></td>"; ?>
	
	</tr>
	<?   // Test Display ?>
    <tr>
		<td align="right"><font color="#0000FF">ชื่อผู้กู้หลักจากสัญญาเดิม: </font></td>
		<td align="left"><?php echo $nameold0; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">ชื่อผู้กู้ร่วมจากสัญญาเดิม: </font></td>
		<td align="left"><?php echo $nameold1; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">ชื่อผู้ค้ำประกันจากสัญญาเดิม: </font></td>
		<td align="left"><?php echo $nameold2; ?></td>
	</tr>
	
	<?   // End Test Display ?>

	<tr>
		<td align="right"><font color="#0000FF">จากประเภทเงิน : </font></td>
		<td align="left"><?php echo $begin_trans_type_text; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">ให้กับสัญญา : </font></td>
		<?php echo "<td align=\"left\"><a style=\"cursor:pointer;\" onClick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$end_conid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$end_conid</u></FONT></a></td>"; ?>
	</tr>
	
		<?   // Test Display ?>
		<tr>
		<td align="right"><font color="#0000FF">ชื่อผู้กู้หลักจากสัญญาใหม่: </font></td>
		<td align="left"><?php echo $namenewdetail; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">ชื่อผู้กู้ร่วมจากสัญญาใหม่: </font></td>
		<td align="left"><?php echo$namenewdetail1; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">ชื่อผู้ค้ำประกันจากสัญญาใหม่: </font></td>
		<td align="left"><?php echo$namenewdetail2; ?></td>
	</tr>
		
	
		<?   // End Test Display ?>
	
	

	<tr>
		<td align="right"><font color="#0000FF">ประเภทเงินที่รับ : </font></td>
		<td align="left"><?php echo $end_trans_type_text; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">วันที่ย้ายเงิน : </font></td>
		<td align="left"><?php echo $changeMoneyDate; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">จำนวนเงิน : </font></td>
		<td align="left"><?php echo number_format($end_trans_money,2); ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">ผู้ทำรายการ : </font></td>
		<td align="left"><?php echo $fullname; ?></td>
	</tr>
	<tr>
		<td align="right"><font color="#0000FF">วันเวลาที่ทำรายการ : </font></td>
		<td align="left"><?php echo $doerStamp; ?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><font color="#0000FF">เหตุผลในการโอนย้าย : </font></td>
		<td align="left"><textarea readOnly><?php echo $reason; ?></textarea></td>
	</tr>
<?php if($show != 'show'){ ?>	
	<tr>
		<td colspan="2" align="center">
		<form name="my" method="post" action="process_appv.php">
		<!--input type="button" value="อนุมัติ" onclick="window.location='process_appv.php?appv=1&tm_pk=<?php echo $tm_pk; ?>&moveID=<?php echo $doerID; ?>'"> &nbsp;&nbsp;&nbsp; 
		<input type="button" value="ไม่อนุมัติ" onclick="window.location='process_appv.php?appv=0&tm_pk=<?php echo $tm_pk; ?>&moveID=<?php echo $doerID; ?>'"--> 
		<input type="hidden" name="tm_pk" id="tm_pk" value="<?php echo $tm_pk; ?>">
		<input type="hidden" name="moveID" id="moveID" value="<?php echo $doerID; ?>">
		<input name="appv" type="submit" value="อนุมัติ" />
		<input name="unappv" type="submit" value="ไม่อนุมัติ" />

		&nbsp;&nbsp;&nbsp; 
		<input type="button" value="ออก" onclick="javascript:window.close();">
		</form>
		</td>
	</tr>
<?php } ?>	
</table>
</body>
</html>