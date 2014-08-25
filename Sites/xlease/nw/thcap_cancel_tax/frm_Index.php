<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ขอยกเลิกใบกำกับภาษี</title>
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
        $("#panel").load("frm_Tax_DetailCancel.php?id="+ $("#id").val());
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

<center><h1>(THCAP) ขอยกเลิกใบกำกับภาษี</h1></center>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>
<fieldset><legend><B>(THCAP) ขอยกเลิกใบกำกับภาษี</B></legend>
<div class="ui-widget" align="center">
<div style="margin:0;padding-bottom:10px;">
<b>ค้นจาก เลขที่สัญญา, เลขที่ใบกำกับภาษี : </b><input type="text" id="id" name="id" size="40" />
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
			<td colspan="11" align="center" style="font-weight:bold;">รายการขอยกเลิกใบกำกับภาษีที่รออนุมัติ</td>
		</tr>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<td>เลขที่สัญญา</td>
			<td>เลขที่ใบกำกับภาษี</td>
			<td>วันที่ใบกำกับภาษี</td>
			<td>จำนวนเงิน</td>
			<td>ผู้ขอยกเลิก</td>
			<td>วันเวลาที่ขอยกเลิก</td>
			<td>ผู้อนุมัติ</td>
			<td>วันเวลาที่อนุมัติ</td>
			<td>ผลการอนุมัติ</td>
		</tr>
		<?php
			$qry_app=pg_query("select \"contractID\", \"taxinvoiceID\", \"requestUser\", \"requestDate\", \"result\" 
								from \"thcap_temp_taxinvoice_cancel\"
								where \"approveStatus\" = '2' ");
			$nub1=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$contract=$res_app["contractID"];
				$receipt=$res_app["taxinvoiceID"];
				$requestUser=$res_app["requestUser"];
				$requestDate=$res_app["requestDate"];
				$result=$res_app["result"];
				
				if($result==""){
					$result="";
				}else{
					$result="and a.\"result\"='$result'";
				}
				
				//หาข้อมูลที่เหลือออกมาแสดง 
				$qry_receipt=pg_query("select a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",b.\"fullname\",a.\"requestDate\",e.\"taxpointDate\",sum(e.\"debtAmt\") as \"receiveAmount\",e.\"debtID\",e.\"typePayID\"
									from \"thcap_temp_taxinvoice_cancel\" a
									left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
									left join \"thcap_v_taxinvoice_otherpay\" e on a.\"taxinvoiceID\"=e.\"taxinvoiceID\"
									where a.\"contractID\"='$contract' and a.\"taxinvoiceID\"='$receipt' and a.\"requestDate\"='$requestDate' and a.\"requestUser\"='$requestUser' $result and \"approveStatus\"='2' 
									group by a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",e.\"debtID\",e.\"typePayID\"
									order by \"taxinvoiceID\" ");
				
				$res_receipt=pg_fetch_array($qry_receipt);
				$cancelTaxID=$res_receipt["cancelTaxID"];
				$contractID=$res_receipt["contractID"];
				$taxinvoiceID=$res_receipt["taxinvoiceID"];
				$taxpointDate=$res_receipt["taxpointDate"];
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
				<td style="color:#0000FF;"><span onclick="javascript:popU('../thcap/Channel_detail_v.php?receiptID=<?php echo $taxinvoiceID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $taxinvoiceID; ?></u></span></td>
				<td align="left"><?php echo $taxpointDate; ?></td>
				<td><?php echo $receiveAmount; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $requestDate; ?></td>
				<td>-</td>
				<td>-</td>
				<td><span onclick="javascript:popU('result_cancelreceipt.php?cancelTaxID=<?php echo $cancelTaxID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer;"><u><font color="red">รออนุมัติ</font></u></span></td>
			</tr>
			<?php
			} //end while
		
		// หาใบเสร็จที่ขอยกเลิก ที่ทำรายการอนุมัติไปแล้วในวันนี้
			$nowdate = nowDate();
			$qry_app2=pg_query("select * from thcap_temp_taxinvoice_cancel a left join \"Vfuser\" b on a.\"approveUser\" = b.\"id_user\"  
			where a.\"approveStatus\"<>'2' and date(a.\"approveDate\")='$nowdate' 
			order by a.\"approveDate\" DESC");
			$nub2=pg_num_rows($qry_app2);
			while($res_app2=pg_fetch_array($qry_app2)){
				$contract=$res_app2["contractID"];
				$receipt=$res_app2["taxinvoiceID"];
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
				
				//หาว่าใบเสร็จนี้ถูกลบหรือยังจากตาราง  thcap_v_taxinvoice_otherpay
				$qrychk=pg_query("select * from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$receipt'");
				$nubchk=pg_num_rows($qrychk);
				//หาข้อมูลที่เหลือออกมาแสดง 
				if($nubchk>0){ //แสดงว่ายังไม่ถูกลบแล้ว	
					$qry_receipt2=pg_query("select a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",sum(e.\"debtAmt\") as \"receiveAmount\",e.\"debtID\",e.\"typePayID\",
					a.\"approveStatus\"
					from thcap_temp_taxinvoice_cancel a
					left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
					left join \"thcap_v_taxinvoice_otherpay\" e on a.\"taxinvoiceID\"=e.\"taxinvoiceID\"
					where a.\"contractID\"='$contract' and a.\"taxinvoiceID\"='$receipt' and a.\"requestDate\"='$requestDate' and a.\"requestUser\"='$requestUser' $result and \"approveStatus\"='$approveStatus' 
					group by a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",e.\"debtID\",e.\"typePayID\",a.\"approveStatus\"
					order by \"taxinvoiceID\"");				
				}else{ //กรณียังไม่ถูกลบ
					$qry_receipt2=pg_query("select a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",sum(e.\"debtAmt\") as \"receiveAmount\",e.\"debtID\",e.\"typePayID\",
					a.\"approveStatus\"
					from thcap_temp_taxinvoice_cancel a
					left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
					left join \"thcap_v_taxinvoice_otherpay_cancel\" e on a.\"taxinvoiceID\"=e.\"taxinvoiceID\"
					where a.\"contractID\"='$contract' and a.\"taxinvoiceID\"='$receipt' and a.\"requestDate\"='$requestDate' and a.\"requestUser\"='$requestUser' $result and \"approveStatus\"='$approveStatus' 
					group by a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",e.\"debtID\",e.\"typePayID\",a.\"approveStatus\"
					order by \"taxinvoiceID\"");
				}
				$res_receipt2=pg_fetch_array($qry_receipt2);
				$cancelTaxID=$res_receipt2["cancelTaxID"];
				$contractID=$res_receipt2["contractID"];
				$taxinvoiceID=$res_receipt2["taxinvoiceID"];
				$taxpointDate=$res_receipt2["taxpointDate"];
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
				
				if($approveStatus=="1"){
					$txtstatus="อนุมัติ";
				}else if($approveStatus=="0"){
					$txtstatus="ไม่อนุมัติ";
				}else if($approveStatus=="3"){
					$txtstatus="ยกเลิกการทำรายการ";
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td style="color:#0000FF;"><span onclick="javascript:popU('../thcap/Channel_detail_v.php?receiptID=<?php echo $taxinvoiceID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $taxinvoiceID; ?></u></span></td>
				<td><?php echo $taxpointDate; ?></td>
				<td><?php echo $receiveAmount; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $requestDate; ?></td>
				<td><?php echo $fullnameapp; ?></td>
				<td><?php echo $approveDate; ?></td>
				<td><span onclick="javascript:popU('result_cancelreceipt.php?cancelTaxID=<?php echo $cancelTaxID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer;"><u><?php echo $txtstatus;?></u></span></td>
			</tr>
			<?php
			} //end while
			$sumnub=$nub1+$nub2;
			if($sumnub == 0){
				 echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
	?>
	</table>
</div>
<?php
include("frm_appvcancel_history_limit.php");
?>
</body>
</html>