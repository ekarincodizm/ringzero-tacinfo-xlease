<?php
include("../config/config.php");
$datepicker = pg_escape_string($_POST['datepicker']);
if($datepicker==""){
	$datepicker = pg_escape_string($_GET['datepicker']);
	if($datepicker==""){
		$datepicker=nowDate();//ดึง วันที่จาก server
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	$('#a1').click( function(){   
		$('#a1').css('background-color', '#ff6600');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a2').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#ff6600'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a3').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#ff6600'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a4').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#ff6600'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a5').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#ff6600'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a6').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#ff6600'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a7').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#ff6600'); 
		$('#a8').css('background-color', '#79BCFF');
	}); 
	$('#a8').click( function(){   
		$('#a1').css('background-color', '#79BCFF');   
		$('#a2').css('background-color', '#79BCFF'); 
		$('#a3').css('background-color', '#79BCFF'); 
		$('#a4').css('background-color', '#79BCFF'); 
		$('#a5').css('background-color', '#79BCFF'); 
		$('#a6').css('background-color', '#79BCFF'); 
		$('#a7').css('background-color', '#79BCFF'); 
		$('#a8').css('background-color', '#ff6600');
	}); 
});
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style> 
</head>
<body id="mm">
<form method="post" name="form1" action="cash_day.php">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>      
		<div style="float:left"><div style="float:left"><input type="button" value="รายงานเงินสดประจำวัน" class="ui-button" onclick="window.location='cash_day.php'" disabled><input type="button" value="รายงานเฉพาะค่าวิทยุ" class="ui-button" onclick="window.location='cash_day_radio.php'"></div></div>
		<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
		<div style="clear:both;"></div>
		<fieldset><legend><B>เงินสดประจำวัน</B></legend>
			<div align="center">
				<div class="ui-widget">
					<p align="center">
						<label for="birds"><b>วันที่</b></label>
						<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15">
						<input type="submit" id="btn00" value="เริ่มค้น"/>
					</p>
					<div id="panel">
						<?php
						//######################แสดงรายละเอียดทั้งหมด
						$orderby2=pg_escape_string($_GET['order']);
						$condition=pg_escape_string($_GET['condition']);
						$id=pg_escape_string($_GET['id']);
						
						if($orderby2==""){
							$orderby="a.\"refreceipt\"";
						}else if($orderby2=="a1"){
							$orderby="a.\"refreceipt\"";
						}else if($orderby2=="a2"){
							$orderby="a.\"IDNO\"";
						}else if($orderby2=="a3"){ //ชื่อลูกค้า
							$orderby="name";
						}else if($orderby2=="a4"){ //ทะเบียนรถ
							$orderby="carregis";
						}else if($orderby2=="a5"){ //TypePay
							$orderby="a.\"TypePay\"";
						}else if($orderby2=="a6"){ //TName
							$orderby="a.\"TName\"";
						}else if($orderby2=="a7"){ //เวลารับชำระ
							$orderby="post";
						}else if($orderby2=="a8"){ //จำนวนเงิน
							$orderby="a.\"AmtPay\"";
						}
						
						if($condition==""){
							$condition="ASC";
						}else{
							if($condition =="ASC"){
								$condition="DESC";
							}else{
								$condition="ASC";
							}
						}
						?>
						<div align="right"><a href="cash_day_pdf.php?date=<?php echo "$datepicker"; ?>&order=<?php echo $orderby2;?>&condition=<?php echo $condition;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<td id="a1" style="cursor:pointer;<?php if($id=="" || $id=="a1"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a1&condition=<?php echo $condition;?>&order=a1&datepicker=<?php echo $datepicker;?>">เลขที่ใบเสร็จ</a></td>
							<td id="a2" style="cursor:pointer;<?php if($id=="a2"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a2&condition=<?php echo $condition;?>&order=a2&datepicker=<?php echo $datepicker;?>">IDNO</a></td>
							<td id="a3" style="cursor:pointer;<?php if($id=="a3"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a3&condition=<?php echo $condition;?>&order=a3&datepicker=<?php echo $datepicker;?>">ชื่อลูกค้า</a></td>
							<td id="a4" style="cursor:pointer;<?php if($id=="a4"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a4&condition=<?php echo $condition;?>&order=a4&datepicker=<?php echo $datepicker;?>">ทะเบียน</a></td>
							<td id="a5" style="cursor:pointer;<?php if($id=="a5"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a5&condition=<?php echo $condition;?>&order=a5&datepicker=<?php echo $datepicker;?>">TypePay</a></td>
							<td id="a6" style="cursor:pointer;<?php if($id=="a6"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a6&condition=<?php echo $condition;?>&order=a6&datepicker=<?php echo $datepicker;?>">TName</a></td>
							<td id="a7" style="cursor:pointer;<?php if($id=="a7"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a7&condition=<?php echo $condition;?>&order=a7&datepicker=<?php echo $datepicker;?>">เวลารับชำระ <br>(ชั่วโมง:นาที)</a></td>
							<td id="a8" style="cursor:pointer;<?php if($id=="a8"){ echo "background-color:#ff6600";}?>;"><a href="cash_day.php?id=a8&condition=<?php echo $condition;?>&order=a8&datepicker=<?php echo $datepicker;?>">จำนวนเงิน</a></td>
						</tr>

						<?php
						$old_UserIDAccept = 0;

						$query=pg_query("select a.\"UserIDAccept\",a.\"refreceipt\",a.\"IDNO\",((btrim(a.\"A_NAME\"::text)) || ' '::text) || btrim(a.\"A_SIRNAME\"::text) as name,
						a.\"TypePay\",a.\"TName\",a.\"AmtPay\",SUBSTRING(CAST(a.\"PostTime\" AS character varying),1,5) as post,
						case when (b.\"car_regis\" is null ) then case when (b.\"C_REGIS\" is null) then c.\"RadioCar\" else b.\"C_REGIS\" End  End carregis
						from \"VUserReceiptCash\" a
						left join \"VContact\" b on a.\"IDNO\"=b.\"IDNO\" 
						left join \"RadioContract\" c on a.\"IDNO\"=c.\"COID\"
						WHERE \"PostDate\"='$datepicker' ORDER BY a.\"UserIDAccept\",$orderby $condition");

						$num_row = pg_num_rows($query);
						while($resvc=pg_fetch_array($query)){
							$nub+=1;
							$UserIDAccept = "";
							$UserIDAccept = $resvc['UserIDAccept'];
							$refreceipt = $resvc['refreceipt'];
							$IDNO = $resvc['IDNO'];
							$namecus = trim($resvc['name']);
							$TypePay = $resvc['TypePay'];
							$TName = $resvc['TName'];
							$AmtPay = $resvc['AmtPay'];
							$PostTime = $resvc['post'];
							$regis = $resvc['carregis'];
							
							if(($UserIDAccept != $old_UserIDAccept) && $nub != 1){
								echo "<tr><td class=\"sum\" align=\"center\"><a href=\"cash_day_user_pdf.php?date=$datepicker&id=$old_UserIDAccept&order=$orderby2&condition=$condition\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
								<td colspan=3 class=\"sum\"><b>รวม N: ".number_format($n_sum,2)." | รวม R: ".number_format($r_sum,2)." | รวม K: ".number_format($k_sum,2)."</b></td>
								<td colspan=3 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td></tr>";
								$sum_amt = 0;
								$n_sum = 0;
								$r_sum = 0;
								$k_sum = 0;
							}
							
							if($UserIDAccept != $old_UserIDAccept){
								$query1=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$UserIDAccept'");
								if($resvc1=pg_fetch_array($query1)){
									$fullname = $resvc1['fullname'];
								}
								echo "<tr><td colspan=7><b>ผู้รับเงิน : $fullname ($UserIDAccept)</b></td></tr>";
							}
							
							$sum_amt+=$AmtPay;
							$sum_amt_all+=$AmtPay;
							
							$typecode = "";
							$typecode = $refreceipt[2];
							if($typecode == "N"){
								$n_sum += $AmtPay;
							}elseif($typecode == "R"){
								$r_sum += $AmtPay;
							}elseif($typecode == "K"){
								$k_sum += $AmtPay;
							}

							$i+=1;
							if($i%2==0){
								echo "<tr class=\"odd\" align=\"left\">";
							}else{
								echo "<tr class=\"even\" align=\"left\">";
							}
						?>
								<td align="center"><?php echo "$refreceipt"; ?></td>
								<td align="center"><?php echo $IDNO; ?></td>
								<td><?php echo $namecus; ?></td>
								<td align="left"><?php echo $regis; ?></td>
								<td align="center"><?php echo $TypePay; ?></td>
								<td><?php echo $TName; ?></td>
								<td align="center"><?php echo $PostTime; ?></td>
								<td align="right"><?php echo number_format($AmtPay,2); ?></td>
							</tr>
							
						<?php
							$old_UserIDAccept = $UserIDAccept;
						}

						echo "<tr><td class=\"sum\" align=\"center\"><a href=\"cash_day_user_pdf.php?date=$datepicker&id=$old_UserIDAccept&order=$orderby2&condition=$condition\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
						<td colspan=3 class=\"sum\"><b>รวม N: ".number_format($n_sum,2)." | รวม R: ".number_format($r_sum,2)." | รวม K: ".number_format($k_sum,2)."</b></td>
						<td colspan=3 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td></tr>";

						echo "<tr>
						<td colspan=7 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td>
						<td align=right class=\"sumall\"><b>".number_format($sum_amt_all,2)."</b></td></tr>";

						if($num_row==0){
						?>
						<tr>
							<td colspan="7" align="center">- ไม่พบข้อมูล -</td>
						</tr>
						<?php
						}
						?>

						</table>
					</div>
				</div>
			</div>
		</fieldset>
	</td>
</tr>
</table>
</form>
</body>
</html>