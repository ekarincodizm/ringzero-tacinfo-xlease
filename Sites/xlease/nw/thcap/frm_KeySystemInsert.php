<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/number.js"></script>

<script type="text/javascript">
function chkDate(){
	/*$.post('process_check_date.php',{
		datepick: $('#datepick').val(),
		bank: $('#bank').val()
	},
	function(data){
		if(data==2){
			alert('ธนาคารนี้ สามารถทำรายการได้ไม่เกินวันที่ 2013-06-01');
			$('#datepick').val('2013-05-31');
		}
	});*/
}

function chkValue(){
	var num = counter;
	var al;
	var n;
	var sbmt = 0;
	var a;
	var payment = [];
	for( i=1; i<=num; i++ ){
		var c1 = $('#money'+ i).val();
		if ( isNaN(c1) || c1 == "" || c1 == 0){
			alert('ข้อมูลจำนวนเงินไม่ถูกต้อง');
			$('#money'+ i).select();
			$("#submitButton").attr('disabled', false);
			return false;
		}
	}
	
	/*$.post('process_check_date.php',{
		datepick: $('#datepick').val(),
		bank: $('#bank').val()
	},
	function(data){
		if(data==1){  
			for(i=1; i<=num; i++){
				n = 0;
				al = "";
				al = "ผิดผลาด ในรายการที่ #"+ i +" ดังนี้\n";
				if($('#bran'+ i).val() == ""){
					al += "- รหัสสาขาที่โอน\n";
					n++;
				}
				if($('#money'+ i).val() == ""){
					al += "- ยอดค่างวด\n";
					n++;
				}
				
				if(n > 0){
					sbmt++;
					alert(al);
				}
			}
			if(sbmt > 0){
				return false;
			}else{
				document.f_list.submit();
			}
		}else{
			alert('ไม่สามารถทำรายการได้ กรุณาตรวจสอบ');
		}
	});*/
	
	document.f_list.submit();
}

var counter = 1;
$(document).ready(function(){
chkDate(); //เช็คธนาคารเริ่มต้น และวันที่ปัจจุบันว่าสามารถทำรายการได้หรือไม่
    $('#addButton').click(function(){
        counter++;
        var newTextBoxDiv = $(document.createElement('div'))
            .attr("id", 'TextBoxDiv' + counter);
        
table = "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\" style=\"border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px\">"
+ " <tr bgcolor=\"#EECBAD\"><td colspan=\"8\"><b>รายการที่ #" + counter + "</b></td></tr>"
+ " <tr>"
+ " <td width=\"10%\" align=\"right\"><b>เวลาโอน</b></td>"
+ " <td width=\"20%\"> "
+ " <select name=\"hh" + counter + "\" id=\"hh" + counter + "\">"
+ " <?php
for($i=0; $i<24; $i++){
echo "<option value=$i>$i</option>";
}
?>"
+ " </select> "
+ " <select name=\"mm" + counter + "\" id=\"mm" + counter + "\">"
+ " <?php
for($i=0; $i<60; $i++){
echo "<option value=$i>$i</option>";
}
?>"
+ " </select> น."
+ " </td>"
+ " <td width=\"15%\" align=\"right\"><b>รหัสสาขาที่โอน </b></td>"
+ " <td width=\"\"><input type=\"text\" id=\"bran" + counter + "\" name=\"bran" + counter + "\" size=\"15\"></td>"
+ " <td width=\"10%\" align=\"right\"><b>จำนวนเงิน </b></td>"
+ " <td width=\"\"><input type=\"text\" id=\"money" + counter + "\" name=\"money" + counter + "\" size=\"15\" style=\"text-align:right\" onKeyPress=\"checknumber(event)\"></td>"
+ " <td width=\"10%\" align=\"right\"><b>แนบไฟล์</b></td>"
+ "	<td><input type=\"file\" name=\"my_field" + counter + "[]\" id=\"addfile" + counter + "\"></td>"
+ " </tr>"
+ " </table>";

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
        $('#counter').val(counter);
       
    });
    
    $("#removeButton").click(function(){
        if(counter!=1){           
			
			$("#TextBoxDiv" + counter).remove();
			counter--;
			$('#counter').val(counter);
		}else{
			alert("ห้ามลบ !!!");
            return false;
		}
    });
    
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
    
</head>
<body>

<form name="f_list" id="f_list" action="cash_no_bill_insert.php" method="post" enctype="multipart/form-data">
<table width="880" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<div style="float:left"></div>
		<div style="float:right">&nbsp;</div>
		<div style="clear:both"></div>

		<fieldset><legend><B>ใส่รายการโอนเงิน</B></legend>
		<div class="ui-widget">
				<div style="padding: 10px 0 10px 0">
				<b>ธนาคาร</b> : 
				<select name="bank" id="bank" onchange="JavaScript:chkDate();">
				<?php
				$qry_bank=pg_query("select * from \"BankInt\" WHERE \"isTranPay\" = '1' ORDER BY \"BankInt\".\"BAccount\" ASC");
				while($res_bank=pg_fetch_array($qry_bank)){
					$BID = $res_bank["BID"];
					$bankname = $res_bank["BName"];
					$bankno = $res_bank["BAccount"];
					echo "<option value=\"$BID\">$bankno, $bankname</option>\n";
				}
				?>
				</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
					$senddate=date('Y-m-d', strtotime('-1 days'));
				?>
				<b>วันที่โอน</b> : <input name="datepick" id="datepick" type="text" readonly="true" size="15" style="text-align:center;" value="<?php echo $senddate; ?>" onchange="JavaScript:chkDate();"><input name="btndate" id="btndate" type="button" onclick="displayCalendar(document.f_list.datepick,'yyyy-mm-dd',this);" value="ปฏิทิน"></div>

				<div id='TextBoxesGroup'>
				<div id="TextBoxDiv1">
					<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px">
					<tr bgcolor="#EECBAD"><td colspan="8"><b>รายการที่ #1</b></td></tr>
					<tr>
						<td width="10%" align="right"><b>เวลาโอน</b></td>
						<td width="20%"> 
							<select name="hh1" id="hh1">
								<?php
								for($i=0; $i<24; $i++){
									echo "<option value=\"$i\">$i</option>";
								}
								?>
							</select>
							<select name="mm1" id="mm1">
							<?php
							for($i=0; $i<60; $i++){
								echo "<option value=\"$i\">$i</option>";
							}
							?>
							</select> น.
						</td>
						<td width="15%" align="right"><b>รหัสสาขาที่โอน </b></td>
						<td width=""><input type="text" id="bran1" name="bran1" value="" size="15"></td>
						<td width="10%" align="right"><b>จำนวนเงิน </b></td>
						<td width=""><input type="text" id="money1" name="money1" size="15" style="text-align:right" onKeyPress="checknumber(event)"></td>
						<td width="10%" align="right"><b>แนบไฟล์</b></td>
						<td><input type="file" name="my_field1[]" id="addfile1"></td>
					</tr>
					</table>
				</div>
				</div>

				<div style="float:left"><input type="hidden" name="val" id="val" value="1"><input type="button" value="บันทึกข้อมูล" id="submitButton" onclick="JavaScript:chkValue();"></div>
				<div style="float:right"><input type="button" value="+ เพิ่มรายการ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></div>
				<div style="clear:both"></div>
				<input type="hidden" id="counter" name="counter" value="1">			
		</div>
		</fieldset>
	</td>
</tr>
</table>
</form>


<div>
<div style="padding-top:50px"><span style="background-color:#8B8B7A;"><b>รายการที่รออนุมัติ</b></span></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="1" bgcolor="#EEE9BF">
<tr style="font-weight:bold;color:#FFFFFF;" valign="top" bgcolor="#8B8B7A" align="center">
	<td>วันที่โอน</td>
	<td>เวลาที่โอน</td>
	<td>รหัสรายการเงินโอน</td>
	<td>ประเภทการนำเข้า</td>
	<td>เลขที่บัญชี</td>
	<td>สาขา</td>
	<td>วันที่ทำรายการ</td>
	<td>จำนวนเงิน</td>
	<td>ไฟล์แนบ</td>
</tr>
<?php 
	$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1");
	while($re_acc = pg_fetch_array($qry_acc)){
		$BID2 = $re_acc['BID'];
		$BAccount2 = $re_acc['BAccount'];
		$BName2 = $re_acc['BName'];
						
		//แสดงชื่อธนาคาร
		echo "<tr bgcolor=\"#CDCDB4\"><td colspan=\"11\"><span style=\"background-color:#EEEED1;\"><b>$BAccount2-$BName2</b></span></td></tr>";
						
		//หาวันที่ที่ต้องแสดง
		$querydate=pg_query("select date(\"bankRevStamp\") as \"bankRevStamp\" from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\"='5' 
		and \"bankRevAccID\"='$BID2' group by date(\"bankRevStamp\") ORDER BY date(\"bankRevStamp\") ASC");
							
		$nub=0;
		while($resdate=pg_fetch_array($querydate)){
			$nub++;
			$datemain=$resdate["bankRevStamp"];
								
			//แสดงวันที่
			echo "<tr bgcolor=\"#CDB5CD\"><td colspan=\"11\"><b>วันที่โอน : <span onclick=\"javascript:popU('frm_Showbankeach.php?BID=$BID2&datemain=$datemain','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><u>$datemain</u></span></b></td></tr>";
							
			//ค้นหารายการตามธนาคารและวันที่
			$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\"='5'
			and \"bankRevAccID\" = '$BID2' and date(\"bankRevStamp\")='$datemain' ORDER BY \"bankRevStamp\",\"doerStamp\" ASC");							
								
			while($resvc=pg_fetch_array($query)){
				$revTranID = $resvc['revTranID'];
				$cnID = $resvc['cnID'];
				$BID = $resvc['bankRevAccID'];
				$bankRevBranch = trim($resvc['bankRevBranch']);
				$bankRevStamp = trim($resvc['bankRevStamp']);
				$bankRevAmt = trim($resvc['bankRevAmt']);
				$doerID = $resvc['doerID'];
				$doerStamp = $resvc['doerStamp'];
				$tranActionID = $resvc['tranActionID'];
				$BAccount = $resvc['BAccount'];
				$appvXID = $resvc['appvXID']; //ฝ่ายบัญชีที่อนุมัติจะใช้สำหรับตรวจสอบในส่วนการเงินว่าไม่ให้เป็นคนเดียวกันกับคนอนุมัติครั้งแรก
				$revTranStatus = $resvc['revTranStatus']; //สถานะสำหรับตรวจสอบกรณีให้แสดงปุ่ม "เลือกใช้รายการนี้"
				$contractID = $resvc['contractID']; //เลขที่สัญญาที่โอน
									
				$dateRevStamp=trim(substr($bankRevStamp,0,10)); //วันที่โอน
				$timeRevStamp=trim(substr($bankRevStamp,10)); //เวลาที่โอน
								
				$pictran=trim($resvc['pictran']);
				$realpath = redirect($_SERVER['PHP_SELF'],'nw/thcap/upload/addcheque/'.$pictran);
									
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#FFE1FF align=\"center\">";
				}else{
					echo "<tr bgcolor=#EED2EE align=\"center\">";
				}
										
				?>
				<td height="30"><?php echo $dateRevStamp; ?></td>
				<td><?php echo $timeRevStamp; ?></td>
				<td><?php echo $revTranID; ?></td>
				<td><?php echo $cnID; ?></td>
				<td><?php echo $BAccount; ?></td>
				<td><?php echo $bankRevBranch; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<td align="right"><?php echo number_format($bankRevAmt,2); ?></td>
				<?php
					if($pictran==""){
						echo "<td>-</td>";
					}else{
						echo "<td><a href=\"$realpath\" target=\"_blank\"><img src=\"images/open.png\" width=18 heigh=18></a></td>";
					}
				?>
				</tr>
				<?php
			}
		}
	
		if($nub==0){
			echo "<tr bgcolor=#FFFFE0><td colspan=\"9\" height=\"30\" align=center><b>-- ไม่พบข้อมูล --</b></td></tr>";
		}
	}
?>
</table>
</div>
</body>
</html>