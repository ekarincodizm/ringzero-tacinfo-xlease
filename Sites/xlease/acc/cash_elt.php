<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>
    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

			<div style="float:left"><input type="button" value="เงินโอนไม่ผ่าน Bill Payment" class="ui-button" onclick="window.location='cash_no_bill.php'"><input type="button" value="ตัดรายการเงิน" class="ui-button" onclick="window.location='cash_elt.php'" disabled></div>
			<div style="float:right">&nbsp;</div>
			<div style="clear:both"></div>
			
			<fieldset><legend><B>รายการอยู่ระหว่างรออนุมัติยกเลิก</B></legend>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th align="center">รายการที่</th>
						<th align="center">ธนาคาร</th>
						<th align="center">รหัสสาขา</th>
						<th align="center">วันเวลาที่โอน</th>
						<th align="center">จำนวนเงิน</th>
						<th align="center">ผู้ทำรายการ</th>
						<th align="center">วันเวลาที่ทำรายการ</th>
					</tr>
					<?php
					$query = pg_query("select b.*, a.\"doerID\", a.\"doerStamp\", a.\"autoID\" from \"TranPay_Request_Cancel\" a left join \"TranPay\" b on a.id_tranpay = b.id_tranpay where a.\"Approved\" = '9' order by a.\"doerStamp\" ");
					$numrows = pg_num_rows($query);
					$i=0;
					while($result = pg_fetch_array($query))
					{
						$i++;
						$amt = $result['amt'];
						$pay_bank_branch = $result['pay_bank_branch'];
						$tr_date = $result['tr_date'];
						$tr_time = $result['tr_time'];
						$bank_no = $result['bank_no'];
						$PostID = $result['PostID'];
						$id_tranpay = $result['id_tranpay'];
						$doerID = $result['doerID'];
						$doerStamp = $result['doerStamp'];
						$autoID = $result['autoID'];
						
						$BankName = 0;
						$qry_bank=pg_query("select \"BankName\" from \"BankCheque\" WHERE \"BankNo\"='$bank_no' ");
						if($res_bank=pg_fetch_array($qry_bank))
						{
							$BankName = $res_bank["BankName"];
						}
						
						$qry_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
						while($result_name = pg_fetch_array($qry_name))
						{
							$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
						}
						
						if($i%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
				?>
						<td align="center"><?php echo $i; ?></td>
						<td align="center"><?php echo $BankName; ?></td>
						<td align="center"><?php echo $pay_bank_branch; ?></td>
						<td align="center"><?php echo "$tr_date $tr_time"; ?></td>
						<td align="right"><?php echo number_format($amt,2); ?></td>
						<td align="left"><?php echo $fullname; ?></td>
						<td align="center"><?php echo $doerStamp; ?></td>
						</tr>
				<?php
					}
					if($numrows==0){
						echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>ไม่พบรายการ</b></td></tr>";
					}else{
						echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=7><b>ข้อมูลทั้งหมด $i รายการ</b></td></tr>";
					}
					?>
				</table>
			</fieldset>

			<fieldset><legend><B>ตัดรายการเงินที่ไม่ใช่ Bill Payment</B></legend>

				<div class="ui-widget">

					<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
						<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
							<td align="center">ธนาคาร</td>
							<td align="center">รหัสสาขา</td>
							<td align="center">วันเวลาที่โอน</td>
							<td align="center">จำนวนเงิน</td>
							<td align="center">ทำรายการ</td>
							<td align="center">ยกเลิก</td>
						</tr>
		
						<?php
						$select = pg_query("SELECT \"amt\", \"pay_bank_branch\", \"tr_date\", \"tr_time\", \"bank_no\", \"PostID\", \"id_tranpay\"
											FROM \"TranPay\" WHERE \"post_on_asa_sys\"='false' AND \"terminal_id\"='TR-ACC'
											and \"id_tranpay\" not in(select \"id_tranpay\" from \"TranPay_Request_Cancel\" where \"Approved\" = '9')
											ORDER BY \"tr_date\",\"tr_time\" ASC;");
						while($res=pg_fetch_array($select)){
							$amt = $res['amt'];
							$pay_bank_branch = $res['pay_bank_branch'];
							$tr_date = $res['tr_date'];
							$tr_time = $res['tr_time'];
							$bank_no = $res['bank_no'];
							$PostID = $res['PostID'];
							$id_tranpay = $res['id_tranpay'];
							
							$BankName = 0;
							$qry_bank=pg_query("select \"BankName\" from \"BankCheque\" WHERE \"BankNo\"='$bank_no' ");
							if($res_bank=pg_fetch_array($qry_bank)){
								$BankName = $res_bank["BankName"];
							}
							
							$in+=1;
							if($in%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}
						?>
								<td><?php echo $BankName; ?></td>
								<td><?php echo $pay_bank_branch; ?></td>
								<td><?php echo "$tr_date $tr_time"; ?></td>
								<td align="right"><?php echo number_format($amt,2); ?></td>
								
								<td align="center"><input type="button" name="acc" id="acc" value="ทำรายการนี้" onclick="window.location='cash_elt_detail.php?id=<?php echo $PostID; ?>'"></td>
								<!--<td align="center"><input type="button" name="acc" id="acc" value="ทำรายการนี้" onclick="javascript:popU('<?php echo "cash_elt_detail.php?id=$PostID"; ?>','<?php echo $PostID; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600');"></td>-->
								<td align="center"><span onclick="javascript:popU('frm_acc_del.php?id_tranpay=<?php echo $id_tranpay; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=450')" style="cursor: pointer;"><img src="image/del.png"></span></td>
						<?php
							echo "</tr>";
						}
						?>
					</table>

				</div>

			</fieldset>

        </td>
    </tr>
	<tr>
		<td>
			<?php
				include("frm_historyAccDel_limit.php");
			?>
		</td>
	</tr>
</table>

</body>
</html>