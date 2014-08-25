<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$yy = pg_escape_string($_GET['yy']);
$ty = pg_escape_string($_GET['ty']);
$mm = pg_escape_string($_GET['mm']);

$datedate = date ("Y-m", strtotime("-1 month", strtotime($yy.'-'.$mm)));
list($year_b,$month_b,$day_b)=explode("-",$datedate);

$datedate = date ("Y-m", strtotime("+1 month", strtotime($yy.'-'.$mm)));
list($year_n,$month_n,$day_n)=explode("-",$datedate);

$chk_all = pg_escape_string($_GET['chk_all']);//กด แสดงทุกสมุดทั้งหมด รวมถึงสมุดที่ไม่ได้ใช้งาน หรือไม่ โดย yes = กด ,no =ไม่กด

$doerID = $_SESSION["av_iduser"];
$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$month_shot = array('1'=>'มกราคม', '2'=>'กุมภาพันธ์', '3'=>'มีนาคม', '4'=>'เมษายน', '5'=>'พฤษภาคม', '6'=>'มิถุนายน', '7'=>'กรกฏาคม', '8'=>'สิงหาคม' ,'9'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');

$show_month = $month[$mm];
$show_yy = $yy+543;

?>
<html>
<head>  
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
$(function() {
	$( document ).tooltip();
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function del(pauto_id)
{
	$.post("process_set_statement.php",
	{
		auto_id : pauto_id,
		ud : 'd'
	},
	function(data){		
		if(data == 'YES'){
			alert('บันทึกสำเร็จ');
			$('#btn1').click();
		}else{
			alert('เกิดข้อผิดผลาด '+data);
			$('#btn1').click();
		}
	});
}

function setstatement(pauto_id)
{
	if(document.getElementById('income'+pauto_id).checked == false && document.getElementById('balance'+pauto_id).checked == false)
	{
		alert('กรุณาเลือกว่าเป็น งบกำไรขาดทุน หรือ งบดุล');
	}
	else if(document.getElementById('income'+pauto_id).checked == true)
	{
		$.post("process_set_statement.php",
		{
			auto_id : pauto_id,
			statement : 'income',
			ud : 'u'
		},
		function(data){		
			if(data == 'YES'){
				alert('บันทึกสำเร็จ');
				$('#btn1').click();
			}else{
				alert('เกิดข้อผิดผลาด '+data);
				$('#btn1').click();
			}
		});
	}
	else if(document.getElementById('balance'+pauto_id).checked == true)
	{
		$.post("process_set_statement.php",
		{
			auto_id : pauto_id,
			statement : 'balance',
			ud : 'u'
		},
		function(data){		
			if(data == 'YES'){
				alert('บันทึกสำเร็จ');
				$('#btn1').click();
			}else{
				alert('เกิดข้อผิดผลาด '+data);
				$('#btn1').click();
			}
		});
	}
}
function gen(accid,accidno){//บัญชีที่เลือกค้นหา	
	var s_month='<?php echo $mm ;?>';//เดือนที่เลือกค้นหา
	var s_year='<?php echo $yy ;?>';//ปีที่เลือกค้นหา
	
	if(accid !="" && s_month !="" && s_year !=""){
		if(confirm('คุณต้องการจะ GEN เลขที่บัญชี   '+ accidno +' ใช้หรือไม่')==true){
	
		$.post("process_gen.php", {accid: accid,s_month : s_month,s_year : s_year
		},
		function(data){					
			if(data==1){
				alert(' การ GEN เสร็จสิ้น');
				window.document.my2.find_yourself.click();
			}
			else{
			alert(data);
			alert('มีข้อผิดพลาด ! กรุณาลองใหม่');}
		});
		}
	}
	else{	alert('กรุณาเลือกรายการค้นหาใหม่');}
}
</script>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" >
	<tr>
	<td align="right" width="50%">	
	<form name="my" method="post" action="frm_index.php">
		<input type="hidden" name="yy" id="yy" value="<?php echo $year_b; ?>">
		<input type="hidden" name="mm" id="mm" value="<?php echo $month_b; ?>">
		<input type="hidden" name="chk_all" id="chk_all" value="<?php echo $chk_all; ?>">
		<input type="hidden" name="from_p" id="from_p" value="1">
		<input type="submit" id="back"  value="ก่อนหน้า" />
	</form>
	</td>
	<td align="left"width="50%">
	<form name="my1" method="post" action="frm_index.php">
		<input type="hidden" name="yy" id="yy" value="<?php echo $year_n; ?>">
		<input type="hidden" name="mm" id="mm" value="<?php echo $month_n; ?>">
		<input type="hidden" name="chk_all" id="chk_all" value="<?php echo $chk_all; ?>">
		<input type="hidden" name="from_p" id="from_p" value="1">
		<input type="submit" id="next"  value="ถัดไป" />
	</form>
	<form name="my2" method="post" action="frm_index.php" hidden>
		<input type="hidden" name="yy" id="yy" value="<?php echo $yy; ?>">
		<input type="hidden" name="mm" id="mm" value="<?php echo $mm; ?>">
		<input type="hidden" name="chk_all" id="chk_all" value="<?php echo $chk_all; ?>">
		<input type="hidden" name="from_p" id="from_p" value="1">
		<input type="submit" id="find_yourself"  value="ค้นหาตัวเอง"  />
	</form>
	
	</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%">
				<tr>
					<td align="left" >
						<font size="3"><?php echo "ประจำเดือน $show_month ปี $show_yy"; ?></font>
						<input type="button" value="SAVE" style="cursor:pointer;" onClick="javascript:popU('popup_save.php?yy=<?php echo $yy; ?>&mm=<?php echo $mm; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300');">
					</td>
					<td align="right" >
						<img src="../thcap/images/excel.png" height="20px"> 
						<a href="javascript:popU('frm_excel.php?yy=<?php echo $yy; ?>&mm=<?php echo $mm; ?>&chk_all=<?php echo $chk_all; ?>')"><b><u>ออกรายงาน (EXCEL)</u></b></a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="images/print.gif" height="20px">
						<a href="javascript:popU('frm_pdf.php?yy=<?php echo $yy; ?>&mm=<?php echo $mm; ?>&chk_all=<?php echo $chk_all; ?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" cellpadding="5" cellspacing="1" border="0" bgcolor="#D0D0D0">
<tr bgcolor="#6AB5FF" style="font-weight:bold">
	<td align="center" rowspan="2">ชื่อบัญชี</td>
    <td align="center" rowspan="2">เลขที่บัญชี</td>
    <td align="center" colspan="2">ยอดยกมา</td>
    <td align="center" colspan="2">งบทดลอง (<?php echo "$month[$mm] $yy";?>)</td>
	<td align="center" colspan="2">งบกำไรขาดทุน</td>
	<td align="center" colspan="2">งบดุล</td>
	<td align="center" rowspan="2">ทำรายการ กำหนดงบ</td>
</tr>
<tr bgcolor="#6AB5FF" style="font-weight:bold">
	<td align="center">เดบิต</td>
    <td align="center">เครดิต</td>
    <td align="center">เดบิต</td>
    <td align="center">เครดิต</td>
	<td align="center">เดบิต</td>
    <td align="center">เครดิต</td>
	<td align="center">เดบิต</td>
    <td align="center">เครดิต</td>
</tr>
<?php
$sum = 0;
$sum_up = 0;
/*if($chk_all=="yes"){
	
}
else if($chk_all=="no"){
	
}*/
$qry = pg_query("SELECT \"accBookserial\",\"accBookID\",\"accBookName\",\"accBookUnit\",\"accBookType\" FROM account.\"all_accBook\" ORDER BY \"accBookID\", \"accBookserial\" ASC");
//หาวัน สิ้นเดือน
$set_mm = (int)$mm;	
$sql_day =  pg_query("SELECT \"gen_numdaysinmonth\"($set_mm,$yy)");
$sql_day =pg_fetch_array($sql_day); 
$yy0=$yy-1;
list($cday)=$sql_day;

$dr0 = 0; // รวม เดบิต ของ ยอดยกมา
$cr0 = 0; // รวม เครดิต ของ ยอดยกมา
$dr1 = 0; // รวม เดบิต ของ งบทดลอง
$cr1 = 0; // รวม เครดิต ของ งบทดลอง
$dr2 = 0; // รวม เดบิต ของ งบกำไรขาดทุน
$cr2 = 0; // รวม เครดิต ของ งบกำไรขาดทุน
$dr3 = 0; // รวม เดบิต ของ งบดุล
$cr3 = 0; // รวม เครดิต ของ งบดุล

while($res=pg_fetch_array($qry))
{
    $Acserial= $res['accBookserial'];
	$AcID = $res['accBookID'];
    $AcName = $res['accBookName'];
    $accBookUnit = $res['accBookUnit'];
	$accBookType = $res['accBookType']; // ประเภทสมุดบัญชี
	
	$abh_sum=  pg_query("SELECT \"auto_id\", \"ledger_balance\", \"income_statement\", \"balance_sheet\" from account.\"thcap_ledger_detail\" where  \"accBookserial\" ='$Acserial' and \"is_ledgerstatus\" = '1' 
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$set_mm' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday'
	");
	$abh_sum0 =pg_fetch_array($abh_sum); 
	list($auto_id, $abh_sum1, $income_statement, $balance_sheet)=$abh_sum0;
	
	//มีรายการในนอกเหนือหารสรุปแต่ละเดือน
	$abh_list_detail=  pg_query("SELECT count(\"auto_id\") from account.\"thcap_ledger_detail\" where  \"accBookserial\" ='$Acserial'
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and \"is_ledgerstatus\"='0'");
	$res_list_detail =pg_fetch_array($abh_list_detail); 
	list($rows_list_detail)=$res_list_detail;	
	
	//ยอดยกมา	
	$abh_balance=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$Acserial'
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12'
	and \"is_ledgerstatus\"='1'");
	$abh_balance0 =pg_fetch_array($abh_balance); 
	list($abh_balance1)=$abh_balance0;
	/*****ถ้า ไม่ได้ติกให้แสดงทุกสมุด และ สรุปของบัญชีในเดือนเป็น 0 และยกยกมาเป็น 0 จะไม่แสดง******/
	
	if(($chk_all=="no") and (($abh_sum1==0) or ($abh_sum1=="")) and (($abh_balance1==0) or ($abh_balance1=="")) 
	and (($rows_list_detail==0))){}
	else {
	if($accBookUnit=='0'){?>
	<tr style="font-size:11px" bgcolor="#FF9900">
	<?php } else {?>
	
	<tr style="font-size:11px" bgcolor="#ffffff">
	<?php } ?>
		<td align="left"><a href="javascript:popU('../thcap_accbank_type/frm_Index.php?fromaccpaper=<?php echo '1'; ?>&accserial=<?php echo $Acserial; ?>&date1=<?php echo '1'; ?>&month1=<?php echo $mm; ?>&year1=<?php echo $yy; ?>','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=600')"><u><?php echo "$AcName";?></u></a></td>
		<td align="center">
		<table width="100%">
			<tr>
				<td align="left" title="<?php echo "$Acserial";?>"><?php echo "$AcID";?></td>
				<td align="right"><input type="button" name="btn_gen" id="btn_gen" value="G" onclick="gen('<?php echo "$Acserial";?>','<?php echo "$AcID";?>');"></td>
			</tr>
		</table>
		</td>
		<?php
		// ยอดยกมา
		if($abh_balance1 == ""){$abh_balance1 = 0;}
		if($accBookUnit=='0')
		{
			$sql_abh_balance_unit=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$Acserial'
			and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12'
			and \"is_ledgerstatus\"='2'");
			$sql_abh_balance_unit =pg_fetch_array($sql_abh_balance_unit); 
			list($abh_balance_unit)=$sql_abh_balance_unit;
			if($abh_balance_unit ==''){$abh_balance_unit =0.00;}
			 $sum += $abh_balance_unit;
			
			if($abh_balance_unit != "" && $abh_balance_unit < 0)
			{
				$abh_balance_unit *= -1;
				$abh_balance_unit = '('.number_format($abh_balance_unit,2).')';
			}
			else
			{
				$abh_balance_unit = '('.number_format($abh_balance_unit,2).')';
			}
			$color="\"#FF9900\"";//สีส้ม
		}
		else
		{	$color="\"#FFEFD5\"";
			$abh_balance_unit = "";
		}
		
		if($abh_balance1 >= 0)
		{
			$abh_balance2 = $abh_balance1;
			echo "<td align=\"right\" bgcolor=$color>".number_format($abh_balance2,2)."$abh_balance_unit"."</td>";
			echo "<td  bgcolor=$color></td>";
			
			$dr0 += $abh_balance2;
		}
		else
		{
			$abh_balance2 = $abh_balance1 * -1;
			echo "<td  bgcolor=$color></td>";
			echo "<td align=\"right\" bgcolor=$color>".number_format($abh_balance2,2)."$abh_balance_unit"."</td>";
			
			$cr0 += $abh_balance2;
		}
		
		//----------------------
		
		// งบทดลอง
		if($abh_sum1 == ""){$abh_sum1 = 0;}
		if($accBookUnit=='0')
		{
			$sql_abh_balance_unit_sum=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where  \"accBookserial\" ='$Acserial' and \"is_ledgerstatus\" ='2' 
			and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$set_mm' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday'
			");
			$sql_abh_balance_unit_sum =pg_fetch_array($sql_abh_balance_unit_sum); 
			list($abh_balance_unit_sum)=$sql_abh_balance_unit_sum;
			if($abh_balance_unit_sum ==''){$abh_balance_unit_sum =0;}
			$sum_up += $abh_balance_unit_sum;
			
			if($abh_balance_unit_sum != "" && $abh_balance_unit_sum < 0)
			{
				$abh_balance_unit_sum *= -1;
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
			else
			{
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
			$color="\"#FF9900\"";
		}
		else
		{
			$abh_balance_unit_sum = "";
			$color="\"#FFFACD\"";
		}
		
		if($abh_sum1 >= 0)
		{
			$abh_sum2 = $abh_sum1;
			echo "<td align=\"right\" bgcolor=$color>".number_format($abh_sum2,2)."$abh_balance_unit_sum"."</td>";
			echo "<td bgcolor=$color></td>";
			
			$dr1 += $abh_sum2;
		}
		else
		{
			$abh_sum2 = $abh_sum1 * -1;
			echo "<td bgcolor=$color></td>";
			echo "<td align=\"right\" bgcolor=$color>".number_format($abh_sum2,2)."$abh_balance_unit_sum"."</td>";
			
			$cr1 += $abh_sum2;
		}
		?>
		
		<?php
		if($accBookUnit=='0')
		{	$color="\"#FF9900\"";
			$color_1="\"#FF9900\"";	
		}
		else{
			$color="\"#C1FFC1\"";
			$color_1="\"#CAE1FF\"";			
		}
		if($auto_id != "")
		{
			if($income_statement == "" && $balance_sheet == "")
			{
				echo "<td align=\"center\" colspan=\"2\" bgcolor=$color><input type=\"radio\" name=\"selectstatement$auto_id\" id=\"income$auto_id\" value=\"income\">งบกำไรขาดทุน</td>";
				echo "<td align=\"center\" colspan=\"2\" bgcolor=$color_1><input type=\"radio\" name=\"selectstatement$auto_id\" id=\"balance$auto_id\" value=\"balance\">งลดุล</td>";
				echo "<td align=\"center\" ><input type=\"button\" value=\"SAVE\" onClick=\"setstatement('$auto_id');\"></td>";
			}
			elseif($income_statement != "")
			{
				// งบกำไรขาดทุน
				
				if($abh_sum1 >= 0)
				{
					$income_statement2 = $income_statement;
					echo "<td align=\"right\" bgcolor=$color>".number_format($income_statement2,2)."</td>";
					echo "<td bgcolor=$color></td>";
					
					$dr2 += $income_statement2;
				}
				else
				{
					$income_statement2 = $income_statement * -1;
					echo "<td bgcolor=$color></td>";
					echo "<td align=\"right\" bgcolor=$color>".number_format($income_statement2,2)."</td>";
					
					$cr2 += $income_statement2;
				}
				
				echo "<td bgcolor=$color_1></td><td bgcolor=$color_1></td>";
				echo "<td align=\"center\"><input type=\"button\" value=\"ตั้งค่าใหม่\" onClick=\"del('$auto_id');\"></td>";
			}
			elseif($balance_sheet != "")
			{
				echo "<td bgcolor=$color></td><td bgcolor=$color></td>";
				
				// งบดุล
				if($abh_sum1 >= 0)
				{
					$balance_sheet2 = $balance_sheet;
					echo "<td align=\"right\" bgcolor=$color_1>".number_format($balance_sheet2,2)."</td>";
					echo "<td bgcolor=$color_1></td>";
					
					$dr3 += $balance_sheet2;
				}
				else
				{
					$balance_sheet2 = $balance_sheet * -1;
					echo "<td bgcolor=$color_1></td>";
					echo "<td align=\"right\" bgcolor=$color_1>".number_format($balance_sheet2,2)."</td>";
					
					$cr3 += $balance_sheet2;
				}
				
				echo "<td align=\"center\"><input type=\"button\" value=\"ตั้งค่าใหม่\" onClick=\"del('$auto_id');\"></td>";
			}
		}
		else
		{
			echo "<td align=\"center\" colspan=\"2\" bgcolor=$color>-</td>";
			echo "<td align=\"center\" colspan=\"2\" bgcolor=$color_1>-</td>";
			echo "<td align=\"center\">ยังไม่ได้ gen ข้อมูล</td>";
		}
		?>
	</tr>	
<?php
	}
}
?>
<tr bgcolor="#FFCECE">
    <td colspan="2"></td>
    <td align="right"><?php echo number_format($dr0,2); ?></td>
    <td align="right"><?php echo number_format($cr0,2); ?></td>
	<td align="right"><?php echo number_format($dr1,2); ?></td>
    <td align="right"><?php echo number_format($cr1,2); ?></td>
	<td align="right"><?php echo number_format($dr2,2); ?></td>
    <td align="right"><?php echo number_format($cr2,2); ?></td>
	<td align="right"><?php echo number_format($dr3,2); ?></td>
    <td align="right"><?php echo number_format($cr3,2); ?></td>
	<td></td>
</tr>
<tr bgcolor="#FFCECE">
    <td align="left" colspan="2">กำไรสุทธิ</td>
    <td></td>
    <td></td>
	<td></td>
    <td></td>
	<?php
	if($dr2 > $cr2)
	{
		echo "<td></td><td align=\"right\">".number_format($dr2-$cr2,2)."</td>";
	}
	elseif($cr2 > $dr2)
	{
		echo "<td align=\"right\">".number_format($cr2-$dr2,2)."</td><td></td>";
	}
	else
	{
		echo "<td align=\"right\">".number_format(0,2)."</td><td align=\"right\">".number_format(0,2)."</td>";
	}
	
	if($dr3 > $cr3)
	{
		echo "<td></td><td align=\"right\">".number_format($dr3-$cr3,2)."</td>";
	}
	elseif($cr3 > $dr3)
	{
		echo "<td align=\"right\">".number_format($cr3-$dr3,2)."</td><td></td>";
	}
	else
	{
		echo "<td align=\"right\">".number_format(0,2)."</td><td align=\"right\">".number_format(0,2)."</td>";
	}
	?>
	<td></td>
</tr>
<tr bgcolor="#FFCECE">
    <td colspan="2"></td>
    <td></td>
    <td></td>
	<td></td>
	<td></td>
	<?php
	if($dr2 > $cr2)
	{
		echo "<td align=\"right\">".number_format($dr2,2)."</td><td align=\"right\">".number_format($cr2+($dr2-$cr2),2)."</td>";
	}
	elseif($cr2 > $dr2)
	{
		echo "<td align=\"right\">".number_format($dr2+($cr2-$dr2),2)."</td><td align=\"right\">".number_format($cr2,2)."</td>";
	}
	else
	{
	?>
		<td align="right"><?php echo number_format($dr2,2); ?></td>
		<td align="right"><?php echo number_format($cr2,2); ?></td>
	<?php
	}
	
	if($dr3 > $cr3)
	{
		echo "<td align=\"right\">".number_format($dr3,2)."</td><td align=\"right\">".number_format($cr3+($dr3-$cr3),2)."</td>";
	}
	elseif($cr3 > $dr3)
	{
		echo "<td align=\"right\">".number_format($dr3+($cr3-$dr3),2)."</td><td align=\"right\">".number_format($cr3,2)."</td>";
	}
	else
	{
	?>
		<td align="right"><?php echo number_format($dr3,2); ?></td>
		<td align="right"><?php echo number_format($cr3,2); ?></td>
	<?php
	}
	?>
    <td></td>
</tr>
</table>