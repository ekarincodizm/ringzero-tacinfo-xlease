<?php include("../../config/config.php");
$select_Search=pg_escape_string($_GET["date1"]);
$select_bid=pg_escape_string($_GET["bankint"]);//ช่องทางบัญชี เลขที่
$month1=pg_escape_string($_GET["month1"]);
$year1=pg_escape_string($_GET["year1"]);
$year2=pg_escape_string($_GET["year2"]);
$datepicker=pg_escape_string($_GET["datepicker"]);
$datefrom=pg_escape_string($_GET["datefrom"]);
$dateto=pg_escape_string($_GET["dateto"]);
$fromaccpaper=pg_escape_string($_GET["fromaccpaper"]);
$cancel=pg_escape_string($_GET["cancel"]);

if($fromaccpaper == '1'){
$select_bid=pg_escape_string($_GET["accserial"]);
echo 
"<head>
	<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />
    <META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">
    
    <link type=\"text/css\" rel=\"stylesheet\" href=\"act.css\"></link>
    
    <link type=\"text/css\" href=\"../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css\" rel=\"stylesheet\" />    
    <script type=\"text/javascript\" src=\"../../jqueryui/js/jquery-1.4.2.min.js\"></script>
    <script type=\"text/javascript\" src=\"../../jqueryui/js/jquery-ui-1.8.2.custom.min.js\"></script>
    <script type=\"text/javascript\" src=\"../../jqueryui/js/number.js\"></script>
</head>

<td align=\"center\">
	<h1>ข้อมูลบัญชีแยกประเภท เดือน $month1 ปี $year1</h1>
</td>
";
}

?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" >
	<tr>
	<td align="right">
		<img src="images/print.gif" height="20px"> 
		<a href="javascript:popU('frm_pdf.php?date1=<?php echo $select_Search;?>&bankint=<?php echo $select_bid;?>&year1=<?php echo $year1;?>&month1=<?php echo $month1;?>&year2=<?php echo $year2;?>&datepicker=<?php echo $datepicker;?>&datefrom=<?php echo $datefrom;?>&dateto=<?php echo $dateto;?>&cancel=<?php echo $cancel;?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
	</td>
</tr>
</table>
<!--หาเดือน ปี ที่แล้ว เพื่อเป็นข้อมูล ในการหาค่า ยกมา-->
<?php if($select_Search==2){
		$year = (int)$year2;
		if($year>2012){$year = $year-1;}
		else{$year='2555';}
}
else if($select_Search==1){
	$year = (int)$year1;
	if($year>2012){			
		$dateMY=$year1.'-'.$month1.'-'.'01';
		$dateMY= date ("Y-m-d", strtotime("-1 day", strtotime($dateMY)));		
		list($year,$month,$day)=explode("-",$dateMY);
		/*echo $year1.$month1;
		$year = (int)$year1;
		$month= (int)$month1;		
		if($year>2012){			
			if($month==12){
				$month=1;
				$year = $year-1;
			}else{
			$month= $month-1;
			}/*
		}
		else{$year='2555';}	*/
	}
	else{
		$year='2555';
	}
}
else if($select_Search==0){
	$year = 2012;
	$month= 12;
}
/*else if($select_Search==4){	
	$datepickernew = date ("Y-m-d", strtotime("-1 day", strtotime($datepicker)));
	
	list($year,$month,$day)=explode("-",$datepickernew);	
}*/
else if($select_Search==3){		
	$datefromnew = date ("Y-m-d", strtotime("-1 day", strtotime($datefrom)));	
	list($year,$month,$day)=explode("-",$datefromnew);	
}

// เนื่องจากข้อมูล มีตั้งแต่ 2012(2555) $year='2555'
if($year=='2555'){}
else{

if($select_Search=='1'){	//เดือนปี 	
	$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
	and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month' and \"is_ledgerstatus\"='1'");
}
else if($select_Search=='2'){	//ปี 
	$sql_day =  pg_query("SELECT \"gen_numdaysinmonth\"('12',$year)");
	$sql_day =pg_fetch_array($sql_day); 
	list($cday)=$sql_day;
	$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
	and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(MONTH FROM \"ledger_stamp\")= '12' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday' and \"is_ledgerstatus\"='1'");
}
else if($select_Search=='0'){//ทุกช่วงเวลา
	$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
	and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month' and \"is_ledgerstatus\"='1'");
}
else if($select_Search=='4'){//วันที่
	// แยกวันเดือนปีที่สนใจ
	list($focus_year,$focus_month,$focus_day)=explode("-",$datepicker);
	
	$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
	AND auto_id in (SELECT MAX(auto_id)  from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
    AND \"ledger_stamp\"::date <'$datepicker')");
}
else if($select_Search=='3'){//ตามช่วง
	$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
	and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(DAY FROM \"ledger_stamp\") =$day 
	and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month' and \"is_ledgerstatus\"='1'");
}
$abh_sumb =pg_fetch_array($abh_sum); 
list($abh_balance)=$abh_sumb;
$abh_balance1=$abh_balance;
if ($abh_balance==""){$abh_balance=0;}

if($select_Search=='4')
{// ถ้าเลือกแบบวันที่
	// ตรวจสอบก่อนว่า ปีและเดือนที่สนใจ มีการ gen ข้อมูลอยู่แล้วหรือยัง
	$qry_chk_date_gen = pg_query("select * from account.\"thcap_ledger_detail\" where EXTRACT(YEAR FROM \"ledger_stamp\") = '$focus_year' AND EXTRACT(MONTH FROM \"ledger_stamp\") = '$focus_month' ");
	$row_chk_date_gen = pg_num_rows($qry_chk_date_gen);
	if($row_chk_date_gen == 0){$abh_balance1 = "";}
}

} ?>

<font style="background-color:#EECCCC;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> &nbsp;&nbsp;&nbsp; <font color="555555">รายการสีชมพูอ่อน หมายถึง รายการที่ถูกยกเลิกจากการปรับปรุง</font>
<br>
<font style="background-color:#FFFFCC;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> &nbsp;&nbsp;&nbsp; <font color="555555">รายการสีเหลืองอ่อน หมายถึง รายการที่เป็นรายการปรับปรุง</font>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">

<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
   <td>วันที่</td>
    <td>เอกสาร</td>
	<td>เลขที่อ้างอิง</td>
	<td>รายการ</td>
	<td>จำนวนเงินเดบิต</td>
    <td>จำนวนเงินเครดิต</td>
    <td>ยอดคงเหลือ</td>
</tr>
<?php	
if($year!='2555'){

			$n=0;			
			if($select_bid!="")
			{	
				$n++;
				$sumnub++;
				$query_BookID=pg_query("select \"accBookType\" from account.\"all_accBook\"
								where  \"accBookserial\"='$select_bid'");
				$res_BookID =pg_fetch_array($query_BookID); 
				list($BookType)=$res_BookID;
				$condition="";
				if($cancel=="off"){
					$condition=" and (\"abh_correcting_entries_abh_autoid\" IS NULL AND  \"abh_is_correcting_entries\" <> '1' ) " ;
				}		
				
				if($select_Search=='1'){  //ตามเดือน ปี
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",
					b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance, b.\"abh_refid\",
					b.\"abh_reftype\", b.\"abh_autoid\", b.\"abh_correcting_entries_abh_autoid\", b.\"abh_is_correcting_entries\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' and  EXTRACT(YEAR FROM a.\"ledger_stamp\") = '$year1' 
					and  EXTRACT(MONTH FROM a.\"ledger_stamp\") = '$month1' $condition ";
					//วันที่ ยอดมา
					$tiemto=$year1."-".$month1."-01";
				}
				else if($select_Search=='2'){ //ตามปี	
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",
					b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance,
					a.\"abd_accbookid\" as \"accBookID\", b.\"abh_refid\", b.\"abh_reftype\", b.\"abh_autoid\", b.\"abh_correcting_entries_abh_autoid\", b.\"abh_is_correcting_entries\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' and  EXTRACT(YEAR FROM a.\"ledger_stamp\") = '$year2' 
					$condition";
					
					 //ปี ยอดมา					
					$tiemto=$year2."-"."01-01";
				}
				else if($select_Search=='0'){
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",
					b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance, b.\"abh_refid\",
					b.\"abh_reftype\", b.\"abh_autoid\", b.\"abh_correcting_entries_abh_autoid\", b.\"abh_is_correcting_entries\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' $condition";
					//วันที่ ยอดมา
					$tiemto="2013"."-"."01"."-01";
				
				}
				else if($select_Search=='4'){
					list($year4,$month4,$day4)=explode("-",$datepicker);
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",
					b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance, b.\"abh_refid\",
					b.\"abh_reftype\", b.\"abh_autoid\", b.\"abh_correcting_entries_abh_autoid\", b.\"abh_is_correcting_entries\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' 
					and  EXTRACT(YEAR FROM a.\"ledger_stamp\") = '$year4' and  EXTRACT(DAY FROM a.\"ledger_stamp\") = '$day4'
					and  EXTRACT(MONTH FROM a.\"ledger_stamp\") = '$month4'	$condition ";
					//วันที่ ยอดมา
					//$tiemto=$datepickernew;	
					$tiemto=$datepicker;		
				}
				else if($select_Search=='3'){				
					list($year3,$month3,$day3)=explode("-",$datefrom);
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",
					b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance, b.\"abh_refid\",
					b.\"abh_reftype\", b.\"abh_autoid\", b.\"abh_correcting_entries_abh_autoid\", b.\"abh_is_correcting_entries\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' 
					and  a.\"ledger_stamp\"::date between '$datefrom' and '$dateto' $condition ";
					//วันที่ ยอดมา
					$tiemto=$datefrom;				
				}
				$query.=" order by a.\"auto_id\" asc ";
				$query=pg_query($query);
				$numrows = pg_num_rows($query);	
				$n=0;
				$i=0;
				if($numrows < 1 && $abh_balance1 == ""){echo "<tr><td colspan=6 height=50 align=center><b>--ไม่มีข้อมูล--</b></td></tr>";}
				else{
				echo "<tr class=\"odd\" align=\"center\">"; ?>
				<td><?php echo $tiemto; ?></td>  
					<td></td>
					<td></td>
					<td align="left">ยอดยกมา</td>
				<?php
				if($abh_balance<0){
						$abh_balance_replace = str_replace("-", "",$abh_balance);
				}
				else{
					$abh_balance_replace=$abh_balance;
				}
				if($abh_balance < 1){  //ถ้า ติดลบ และ 0
					$netcredit=$abh_balance_replace;	?>								
					<td align="right"></td>
					<td align="right"><?php echo number_format($abh_balance_replace,2); ?></td>	
				<?php } else {         //ถ้า มากกว่า 0
					$netdebit=$abh_balance_replace;?>
					<td align="right"><?php echo number_format($abh_balance_replace,2); ?></td>
					<td align="right"></td>					
				<?php }	
				?>
					<td align="right"><?php echo number_format($abh_balance,2);?></td></tr>
				<?php
				while($resvc=pg_fetch_array($query))
				{
					$abh_stamp = $resvc['abh_stamp']; 
					$abh_id = $resvc['abh_id']; 
					$accBookName = $resvc['accBookName']; 
					$abh_detail = $resvc['abh_detail'];
					$abd_bookType = $resvc['abd_bookType']; 
					$abd_amount = $resvc['abd_amount']; 
					$ledger_balance = $resvc['ledger_balance'];
					$abh_refid = $resvc["abh_refid"];
					$abh_reftype = $resvc["abh_reftype"];
					$abh_autoid = $resvc["abh_autoid"];
					$abh_correcting_entries_abh_autoid = $resvc["abh_correcting_entries_abh_autoid"];
					$abh_is_correcting_entries = $resvc["abh_is_correcting_entries"];
	
					$i+=1;
					
					if($abh_correcting_entries_abh_autoid != "")
					{ // ถ้าเป็นรายการที่ถูกยกเลิกจากการปรับปรุง
						echo "<tr style=\"font-size:11px; background-color:#EECCCC;\" align=\"center\">";
					}
					elseif($abh_is_correcting_entries == "1")
					{ // ถ้าเป็นรายการปรับปรุง
						echo "<tr style=\"font-size:11px; background-color:#FFFFCC;\" align=\"center\">";
					}
					else
					{
						if($i%2==0){
							echo "<tr class=\"odd\" align=\"center\">";
						}
						else{
							echo "<tr class=\"even\" align=\"center\">";
						}
					}
					
							
						?> 
						<td><?php echo $abh_stamp ?></td>
						<?php
						echo "<td><span onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"blue\">
							<u>$abh_id</u></font></span></td>";
						
						if($abh_reftype=='0'){									
							echo "<td><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else if($abh_reftype=='1'){								
							echo "<td><span onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else if($abh_reftype=='2'){
						
						echo "<td><span onclick=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else if($abh_reftype=='3'){							
							echo "<td><span onclick=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else if($abh_reftype=='101'){
							echo "<td><span onclick=\"javascript:popU('../thcap_dncn/popup_dncn.php?idapp=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else if($abh_reftype=='102'){
							echo "<td><span onclick=\"javascript:popU('../thcap_dncn/popup_dncn.php?idapp=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else if($abh_reftype=='998'){								
							echo "<td><span onclick=\"javascript:popU('../thcap/Channel_detail_v.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
							<u>$abh_refid</u></font></span></td>";
						}
						else{								
							echo "<td>$abh_refid</td>";
						}
						
						//--- ในคำอธิบายรายการ ถ้ามีเลขที่สัญญา ให้คลิกลิ้งได้ด้วย
							//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
							$abh_detail_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/';
							
							// สิ่งที่จะ replace
							$abh_detail_replace = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";
							
							// ที่ replace ได้
							$abh_detail = preg_replace($abh_detail_format,$abh_detail_replace, $abh_detail);
						//--- 
						?>
						
						<td align="left"><?php echo $abh_detail; ?></td>
						<?php if($abd_bookType=='1'){
						$netdebit=$netdebit+$abd_amount;
						$sun_debit=$sun_debit+$abd_amount;
						?>					
							<td align="right"><?php echo number_format($abd_amount,2); ?></td>				
							<td align="right"></td>
						<?php }else {
							$netcredit=$netcredit+$abd_amount ;
							$sun_credit=$sun_credit+$abd_amount;
						?>					
							<td align="right"></td>
							<td align="right"><?php echo number_format($abd_amount,2); ?></td>	
						<?php }?>
						<td align="right"><?php echo number_format($ledger_balance,2); ?></td>	
						</tr>
						<?php				
					
				}//end while
				if($i == 0 && $abh_balance1 == "")
				{
					echo "<tr><td colspan=6 height=50 align=center><b>--ไม่มีข้อมูล--</b></td></tr>";
				}
				else
				{
					if($i == 0 && $abh_balance1 != "")
					{ // ถ้าไม่มีรายการย่อย แต่มียอดยกมา
						$ledger_balance = $abh_balance1; // ให้ยอดยกไป เท่ากับ ยอกยกมา
						
						// หาวันที่สำหรับยอดยกไป
						if($select_Search=='1'){	//เดือนปี 	
							$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
							and EXTRACT(YEAR FROM \"ledger_stamp\") =$year1 and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month1' and \"is_ledgerstatus\"='1'");
						}
						else if($select_Search=='2'){	//ปี 
							$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
							and EXTRACT(YEAR FROM \"ledger_stamp\") =$year2 and \"is_ledgerstatus\"='1'");
						}
						else if($select_Search=='0'){//ทุกช่วงเวลา
							$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
							and \"is_ledgerstatus\"='1'");
						}
						else if($select_Search=='4'){//วันที่
							$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
							AND auto_id in (SELECT MAX(auto_id)  from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
							AND \"ledger_stamp\"::date <'$datepicker')");
						}
						else if($select_Search=='3'){//ตามช่วง
							$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
							and \"ledger_stamp\" >= '$datefrom' and \"ledger_stamp\" <= '$dateto' and \"is_ledgerstatus\"='1'");
						}
						
						$fetch_stamp = pg_fetch_array($qry_stamp);
						list($abh_stamp) = $fetch_stamp;
						if($abh_stamp==""){
							if($select_Search=='2'){
								$abh_stamp=$tiemto=$year2."-"."12-31";
							}else if($select_Search=='1'){
								$abh_stamp=$tiemto=$year1."-".$month1."-31";
							}
						}
					}
				
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
					}
					else{
						echo "<tr class=\"even\" align=\"center\">";
					}
					if(($select_Search=='1') or(($select_Search=='2')))
					{
						
						$timestamp = strtotime($abh_stamp); 
						$m= date('m', $timestamp);
						$timestamp = strtotime($abh_stamp); 
						$y = date('Y', $timestamp);
						$sql_countday =  pg_query("SELECT \"gen_numdaysinmonth\"($m,$y)");
						$sql_countday =pg_fetch_array($sql_countday); 
						list($countday)=$sql_countday;
						$tiemsend=$y."-".$m."-".$countday;
					}
					else if($select_Search=='0'){
						$tiemsend=$abh_stamp;
					}
					else if($select_Search=='4'){
						$tiemsend=$datepicker;}
					else if($select_Search=='3'){
						$tiemsend=$dateto;}
					?>					
						<td align="center"><?php echo $tiemsend ?></td>  
						<td></td>
						<td></td>
						<td align="left">ยอดยกไป</td>	
				
					<?php 
					//ยอดจากการ select ผลสรุปของแต่ละเดือน 
					list($nyear,$nmonth,$nday)=explode("-",$tiemsend);					
					$abh_sum=  pg_query("SELECT  \"ledger_balance\" from account.\"thcap_ledger_detail\" where  \"accBookserial\" ='$select_bid' and \"is_ledgerstatus\" = '1' 
					and EXTRACT(YEAR FROM \"ledger_stamp\") = '$nyear' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$nmonth' and EXTRACT(DAY FROM \"ledger_stamp\")= '$nday'
					");
					$abh_sum0 =pg_fetch_array($abh_sum); 
					list($abh_sum)=$abh_sum0;
					
					if($abh_sum==""){}
					else{$ledger_balance=$abh_sum;}
					
					if($ledger_balance<0){
						$ledger_balance_replace = str_replace("-", "",$ledger_balance);
					}else{
						$ledger_balance_replace = $ledger_balance;
					}					
					if($ledger_balance > 0) { 
							$netcredit= $netcredit+$ledger_balance_replace;?>							
							<td align="right"></td>
							<td align="right"><?php echo number_format($ledger_balance_replace,2); ?></td>
						<?php } else {  
							$netdebit= $netdebit+$ledger_balance_replace ;?>
							<td align="right"><?php echo number_format($ledger_balance_replace,2); ?></td>
							<td align="right"></td>							
						<?php  }
					?>
					<td align="right"></td>
					</tr>
					<tr bgcolor="#ffbaba">
						<td colspan="3"></td>
						<td align="left"><b>ยอดประจำงวด<b></td>	
						<td align="right"><b><?php echo number_format($sun_debit,2); ?><b></td>
						<td align="right"><b><?php echo number_format($sun_credit,2); ?><b></td>
				
						<?php 						
						$abh_sumBalance=$sun_debit - $sun_credit;
						?>
						<td align="right"><b><?php echo number_format($abh_sumBalance,2); ?><b></td>
					</tr>
					<tr bgcolor="#ffbaba">
						<td colspan="3"></td>
						<td align="left"><b>ยอดรวม<b></td>
						<td align="right"><b><?php echo number_format($netdebit,2); ?><b></td>
						<td align="right"><b><?php echo number_format($netcredit,2); ?><b></td>
						<td></td>
					</tr>
			
			<?php
				
				
					 }
				}
				}
			else{}
}
else{
	echo "<tr><td colspan=11 height=50 align=center><b>--ไม่มีข้อมูล ปี 2555--</b></td></tr>";
}
			?>
</table>



