<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ยกเลิกใบเสร็จ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#id").autocomplete({
        source: "s_thcapmix.php",
        minLength:2
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_Receipt_DetailCancel.php?id="+ $("#id").val());
    });

});
function popU(U,N,T) {
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

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>
<fieldset><legend><B>(THCAP) ยกเลิกใบเสร็จ</B></legend>
<div class="ui-widget" align="center">
<div style="margin:0;padding-bottom:10px;">
<b>ค้นจาก เลขที่สัญญา, เลขที่ใบเสร็จ : </b><input type="text" id="id" name="id" size="40" />
<input type="button" id="btn1" value="ค้นหา"/>
</div>
 </fieldset>

        </td>
    </tr>
</table>

<div id="panel" style="padding-top: 10px;"></div>

<br><br><br>
<!-- หารายการที่ทำรายการอนุทัติไปแล้วในวันนี้ -->
<div>
	<table width="900" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr bgcolor="#FFFFFF">
			<td colspan="11" align="center" style="font-weight:bold;">รายการขอยกเลิกใบเสร็จที่รออนุมัติ</td>
		</tr>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<td>เลขที่สัญญา</td>
			<td>เลขที่ใบเสร็จ</td>
			<td>วันที่จ่าย</td>
			<td>จำนวนเงินจ่าย</td>
			<td>ผู้ขอยกเลิก</td>
			<td>วันเวลาที่ขอยกเลิก</td>
			<td>ผู้อนุมัติ</td>
			<td>วันเวลาที่อนุมัติ</td>
			<td>ผลการอนุมัติ</td>
		</tr>
		<?php
			$qry_app=pg_query("select a.\"contractID\",min(a.\"receiptID\") as \"receiptID\",a.\"requestUser\",a.\"requestDate\",a.\"result\" 
			from  thcap_temp_receipt_cancel a  
			where  a.\"approveStatus\"='2' 
			group by a.\"contractID\",a.\"requestUser\",a.\"requestDate\",a.\"result\"");
			$nub1=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$contract=$res_app["contractID"];
				$receipt=$res_app["receiptID"];
				$requestUser=$res_app["requestUser"];
				$requestDate=$res_app["requestDate"];
				$result=$res_app["result"];
				
				if($result==""){
					$result="";
				}else{
					$result="and a.\"result\"='$result'";
				}
				
				//หาข้อมูลที่เหลือออกมาแสดง 
				$qry_receipt=pg_query("select a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",sum(e.\"debtAmt\") as \"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\" 
				from thcap_temp_receipt_cancel a
				left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
				left join \"thcap_v_receipt_otherpay\" e on a.\"receiptID\"=e.\"receiptID\"
				where a.\"contractID\"='$contract' and a.\"receiptID\"='$receipt' and a.\"requestDate\"='$requestDate' and a.\"requestUser\"='$requestUser' $result and \"approveStatus\"='2' 
				group by a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\" 
				order by \"receiptID\"");
				
				$res_receipt=pg_fetch_array($qry_receipt);
				$cancelID=$res_receipt["cancelID"];
				$contractID=$res_receipt["contractID"];
				$receiptID=$res_receipt["receiptID"];
				$receiveDate=$res_receipt["receiveDate"];
				$receiveAmount=$res_receipt["receiveAmount"];
				$fullname=$res_receipt["fullname"];
				$byChannel=$res_receipt["nameChannel"];
				$debtID=$res_receipt["debtID"];
				$typePayID=$res_receipt["typePayID"];
					
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
				//หาุ typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
				
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<?php
				if($typeID==$typePayID){ //แสดงว่าเป็นค่างวด
					//ตรวจสอบอีกครั้งว่าเป็นค่างวดที่อยู่ในตาราง 201201 หรือไม่ 
					$qrychkreceiptID=pg_query("select * from thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
					$numchkreceiptID=pg_num_rows($qrychkreceiptID);
					if($numchkreceiptID==0){ //แสดงว่าเป็นค่างวดที่แสดงใบเสร็จแบบค่าอื่นๆ
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ								
					}else{
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=1\" target=\"_blank\"><u>$receiptID</u></a></td>"; // ย้ายไปเรียกใบเสร็จใน folder ค่าอื่นๆแทน | typepdf=1 หมายถึงค่างวด				
					}
				}else{
					echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ				
				}	
				?>
				<td align="left"><?php echo $receiveDate; ?></td>
				<td><?php echo $receiveAmount; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $requestDate; ?></td>
				<td>-</td>
				<td>-</td>
				<td><span onclick="javascript:popU('result_cancelreceipt.php?cancelID=<?php echo $cancelID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer;"><u><font color="red">รออนุมัติ</font></u></span></td>
			</tr>
			<?php
			} //end while
		
		// หาใบเสร็จที่ขอยกเลิก ที่ทำรายการอนุมัติไปแล้วในวันนี้
			$nowdate = nowDate();
			$qry_app2=pg_query("select * from thcap_temp_receipt_cancel a left join \"Vfuser\" b on a.\"approveUser\" = b.\"id_user\"  
			where a.\"approveStatus\"<>'2' and date(a.\"approveDate\")='$nowdate' 
			order by a.\"approveDate\" DESC");
			$nub2=pg_num_rows($qry_app2);
			while($res_app2=pg_fetch_array($qry_app2)){
				$contract=$res_app2["contractID"];
				$receipt=$res_app2["receiptID"];
				$requestUser=$res_app2["requestUser"];
				$requestDate=$res_app2["requestDate"];
				$result=$res_app2["result"];
				$fullnameapp=$res_app2["fullname"];
				
				if($result==""){
					$result="";
				}else{
					$result="and a.\"result\"='$result'";
				}
				
				$approveStatus=$res_app2["approveStatus"];
				$approveDate=substr($res_app2["approveDate"],0,19);
				
				//หาว่าใบเสร็จนี้ถูกลบหรือยังจากตาราง  thcap_v_receipt_otherpay
				$qrychk=pg_query("select * from thcap_v_receipt_otherpay where \"receiptID\"='$receipt'");
				$nubchk=pg_num_rows($qrychk);
				//หาข้อมูลที่เหลือออกมาแสดง 
				if($nubchk>0){ //แสดงว่ายังไม่ถูกลบแล้ว	
					$qry_receipt2=pg_query("select a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",sum(e.\"debtAmt\") as \"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",
					a.\"approveStatus\"
					from thcap_temp_receipt_cancel a
					left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
					left join \"thcap_v_receipt_otherpay\" e on a.\"receiptID\"=e.\"receiptID\"
					where a.\"contractID\"='$contract' and a.\"receiptID\"='$receipt' and a.\"requestDate\"='$requestDate' and a.\"requestUser\"='$requestUser' $result and \"approveStatus\"='$approveStatus' 
					group by a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",a.\"approveStatus\"
					order by \"receiptID\"");				
				}else{ //กรณียังไม่ถูกลบ
					$qry_receipt2=pg_query("select a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",sum(e.\"debtAmt\") as \"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",
					a.\"approveStatus\"
					from thcap_temp_receipt_cancel a
					left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
					left join \"thcap_v_receipt_otherpay_cancel\" e on a.\"receiptID\"=e.\"receiptID\"
					where a.\"contractID\"='$contract' and a.\"receiptID\"='$receipt' and a.\"requestDate\"='$requestDate' and a.\"requestUser\"='$requestUser' $result and \"approveStatus\"='$approveStatus' 
					group by a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",a.\"approveStatus\"
					order by \"receiptID\"");
				}
				$res_receipt2=pg_fetch_array($qry_receipt2);
				$cancelID=$res_receipt2["cancelID"];
				$contractID=$res_receipt2["contractID"];
				$receiptID=$res_receipt2["receiptID"];
				$receiveDate=$res_receipt2["receiveDate"];
				$receiveAmount=$res_receipt2["receiveAmount"];
				$fullname=$res_receipt2["fullname"];
				$byChannel=$res_receipt2["nameChannel"];
				$debtID=$res_receipt2["debtID"];
				$typePayID=$res_receipt2["typePayID"];
				$approveStatus=$res_receipt2["approveStatus"];
					
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
				//หาุ typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select2 = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select2);
					
				//นำ receiptID ที่ได้ ไปค้นหาในตารางผ่อนชำระค่าอื่นๆว่ามีหรือไม่ ถ้ามีแสดงว่าใบเสร็จนั้นเป็นของค่าใช้จ่ายอื่นๆ
				// $qrychkrec2=pg_query("select \"receiptID\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' and \"debtID\" is not null group by \"receiptID\"");
				// $numchkrec=pg_num_rows($qrychkrec2); //if > 0 แสดงว่าเป็นค่าใช้จ่ายอื่นๆ
				
				if($approveStatus=="1"){
					$txtstatus="อนุมัติ";
				}else if($approveStatus=="0"){
					$txtstatus="ไม่อนุมัติ";
				}else if($approveStatus=="3"){
					$txtstatus="ยกเลิกการทำรายการ";
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<?php
				if($typeID==$typePayID){
					//ตรวจสอบอีกครั้งว่าเป็นค่างวดที่อยู่ในตาราง 201201 หรือไม่ 
					$qrychkreceiptID=pg_query("select * from thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
					$numchkreceiptID=pg_num_rows($qrychkreceiptID);
					if($numchkreceiptID==0){ //แสดงว่าเป็นค่างวดที่แสดงใบเสร็จแบบค่าอื่นๆ
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ				
					}else{
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=1\" target=\"_blank\"><u>$receiptID</u></a></td>"; // ย้ายไปเรียกใบเสร็จใน folder ค่าอื่นๆแทน | typepdf=1 หมายถึงค่างวด				
					}
				}else{
					echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ				
				}	
				?>
				<td align="left"><?php echo $receiveDate; ?></td>
				<td><?php echo $receiveAmount; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $requestDate; ?></td>
				<td><?php echo $fullnameapp; ?></td>
				<td><?php echo $approveDate; ?></td>
				<td><span onclick="javascript:popU('result_cancelreceipt.php?cancelID=<?php echo $cancelID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer;"><u><?php echo $txtstatus;?></u></span></td>
			</tr>
			<?php
			} //end while
			$sumnub=$nub1+$nub2;
			if($sumnub == 0){
				 echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
	?>
	</table>
</div>
<?php
include("frm_appvcancel_history_limit.php");
?>
</body>
</html>