<?php
session_start();
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ
if($showconfig!="no"){
	include("../../config/config.php");
	$receiptID=pg_escape_string($_GET['receiptID']);
	$mapchq=pg_escape_string($_GET['mapchq']); //=yes กรณีแสดงข้อมูลตอน mapchq ในเมนู "ยืนยันรายการเงินโอน (การเงิน)"
	$revChqID=pg_escape_string($_GET['revChqID']); //รหัสเช็คที่ต้องการ map กรณีแสดงข้อมูลตอน mapchq ในเมนู "ยืนยันรายการเงินโอน (การเงิน)"
}

//ตรวจสอบว่ามีเลขที่ใบเสร็จนี้จริงหรือไม่
$qrychkrec=pg_query("select \"receiptID\" from \"thcap_temp_receipt_details\" where \"receiptID\"='$receiptID'");
if(pg_num_rows($qrychkrec)==0){ 
	echo "<div align=center><h2>---ไม่พบเลขที่ใบเสร็จ---</h2></div>";
	exit();
}
//################################เตรียมข้อมูลสำหรับตรวจสอบว่าสามารถขอยกเลิกใบเสร็จภายในหน้านี้ได้หรือไม่
$cancel=0;
//หาว่าเลขที่ใบเสร็จที่ยกเลิกจ่ายค่าอะไร
$qryother=pg_query("select \"typePayID\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' group by \"typePayID\" limit 1");
list($typePayID_chk)=pg_fetch_array($qryother);

//หาเลขที่สัญญาของใบเสร็จนี้
$qrycon=pg_query("select \"thcap_receiptIDToContractID\"('$receiptID')");
list($contractID_chk)=pg_fetch_array($qrycon);

//หาุ typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID_chk')");
list($typeID_chk) = pg_fetch_array($select);

//ตรวจสอบว่าพนักงานท่านนี้สามารถขอยกเลิกใบเสร็จได้หรือไม่
$qrychk=pg_query("select \"id_user\" from \"f_usermenu\" where id_user='$id_user' and id_menu='TM12' and status='TRUE'");
$numchk=pg_num_rows($qrychk); //ถ้ามีค่า > 0 แสดงว่ามีสิทธิ์ใช้ส่วนขอยกเลิกใบเสร็จได้
if($numchk>0){
	$cancel++;
}

//หาสถานะใบเสร็จว่าถูกยกเลิกหรือยัง
$qrystscancel=pg_query("select \"receiptID\" from thcap_temp_receipt_otherpay where \"receiptID\"='$receiptID'");
$numstscancel=pg_num_rows($qrystscancel); //ถ้ามีค่า > 0 แสดงว่ายังไม่ยกเลิก

//กรณียังไม่ยกเลิกและมีสิทธิ์ในเมนูขอยกเลิก ตรวจสอบว่ากำลังรออนุมัติยกเลิกอยู่หรือไม่
if($numstscancel>0 and $numchk>0){ 
	//กรณีเป็นใบเสร็จยกเลิกค่างวดจะไม่สามารถยกเลิกได้ถ้ามีการรออนุมัติยกเลิกค่างวดอยู่ เนื่องจากมีผลกระทบกับการยกเลิกค่างวดงวดอื่นๆด้วย
	if($typePayID_chk==$typeID_chk){ //กรณีประเภทการจ่ายเป็นจ่ายค่างวด
		//ตรวจสอบว่ามีเลขที่สัญญาและรหัสค่างวดนั้นรออนุมัติยกเลิำกอยู่หรือไม่
		$qrycheck=pg_query("select a.\"receiptID\" from thcap_temp_receipt_cancel a
		left join \"thcap_temp_receipt_otherpay\" b on a.\"receiptID\"=b.\"receiptID\"
		where a.\"contractID\" = '$contractID_chk' and \"typePayID\"='$typePayID_chk' and \"approveStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
		if($numcheck>0){ 
			$cancel=0; //ไม่อนุญาตให้ยกเลิกเนื่องจากมีรายการค่างวดที่รออนุมัติอยู่
		}else{
			$cancel++; //อนุญาตให้ยกเลิก
		}
	}else{//กรณีเป็นการจ่ายค่าอื่นๆ จะสามารถ
		$qrycheck=pg_query("select \"typePayID\" from thcap_temp_receipt_cancel a
		left join \"thcap_temp_receipt_otherpay\" b on a.\"receiptID\"=b.\"receiptID\"
		where a.\"receiptID\" = '$receiptID' and \"approveStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
		if($numcheck>0){ 
			$cancel=0; //ไม่อนุญาตให้ยกเลิกเนื่องจากมีเลขที่ใบเสร็จนี้รออนุมัติยกเลิกอยู่
		}else{
			$cancel++; //อนุญาตให้ยกเลิก
		}
	}
}else{
	$cancel=0; //กรณีมีการยกเลิกใบเสร็จนี้แล้วจะไม่สามารถขอยกเลิกได้
}
//ตรวจสอบว่ามีเลขที่สัญญานั้นรออนุมัติอยู่หรือไม่
		$qrycheck=pg_query("select \"typePayID\",\"requestUser\" from thcap_temp_receipt_cancel a
		left join \"thcap_temp_receipt_otherpay\" b on a.\"receiptID\"=b.\"receiptID\"
		where a.\"contractID\" = '$contractID_chk' and \"approveStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
		
//##################################จบการเตรียมข้อมูล########################################
				
//หาใบกำกับภาษี
$qrytax=pg_query("SELECT \"thcap_receiptIDTotaxinvoiceID\"('$receiptID')");
$restax=pg_fetch_array($qrytax);
$taxnum=$restax["thcap_receiptIDTotaxinvoiceID"];
if($taxnum==""){
	$taxnum="-";
}else{
	$qry_taxcancel = pg_query("SELECT \"taxinvoiceID\" FROM thcap_temp_taxinvoice_otherpay_cancel where \"taxinvoiceID\" = '$taxnum'");
	$rows_taxcancel = pg_num_rows($qry_taxcancel);
	IF($rows_taxcancel > 0){
		$taxtxtcancel = "( <font color=\"red\"><b> ถูกยกเลิก </b></font>) ";
	}
}

$recdate = pg_query("select \"receiveDate\",\"contractID\" from thcap_v_receipt_otherpay 
where \"receiptID\" = '$receiptID' group by \"receiveDate\",\"contractID\" ");
$rows_receipdate_normal = pg_num_rows($recdate);
if($rows_receipdate_normal == 0){ //หากไม่มีข้อมูลให้ไปหาในวิวใบเสร็จที่ถูกยกเลิก
	
	$recdate = pg_query("select \"receiveDate\",\"contractID\" from thcap_v_receipt_otherpay_cancel
	where \"receiptID\" = '$receiptID'  group by \"receiveDate\",\"contractID\" ");
	$res = pg_num_rows($recdate);
	if($res>0){
		$qryAppv_user = pg_query("select \"approveUser\",\"requestUser\" from \"thcap_temp_receipt_cancel\"  where (\"approveStatus\" = '1')   and \"receiptID\" = '$receiptID'");
		$app_id = pg_fetch_result($qryAppv_user,0);
		$req_id = pg_fetch_result($qryAppv_user,1);
		
		// ถ้าไม่พบรหัสผู้ขอยกเลิก และหรัสผู้อนุมัติยกเลิก ให้หาจากใบเสร็จหลักที่ยกเลิก
		if($app_id == "" && $req_id == "")
		{
			$qryAppv_user_from_main = pg_query("select \"approveUser\",\"requestUser\" from \"thcap_temp_receipt_cancel\"
									where \"cancelID\" = (select \"cancelID\" from thcap_temp_receipt_otherpay_cancel where \"receiptID\" = '$receiptID') ");
			$app_id = pg_fetch_result($qryAppv_user_from_main,0);
			$req_id = pg_fetch_result($qryAppv_user_from_main,1);
		}
		
		$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$app_id' ");
		$select_name = pg_fetch_result($qryname,0);
		
		$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$req_id' ");
		$req_name = pg_fetch_result($qryname,0);
		$txtcancel = "<font color=\"red\"><b> เอกสารนี้ถูกยกเลิก </b></font>";
	}
	
}
list($receiveDate,$contractIDshow)=pg_fetch_array($recdate);

$qry_typeReceive = pg_query("select \"typeReceive\",\"typeDetail\",\"abh_id\" from thcap_temp_receipt_details 
where \"receiptID\" = '$receiptID' ");
list($typeReceive,$typeDetail,$abh_id)=pg_fetch_array($qry_typeReceive);

// หารหัส abh_autoid
if($abh_id != "")
{
	$qry_abh_autoid = pg_query("select \"abh_autoid\" from account.\"all_accBookHead\" where \"abh_id\" = '$abh_id' ");
	$abh_autoid = pg_result($qry_abh_autoid,0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){
	$('#btn2').click(function(){
		if(confirm('ยืนยันการ map เช็ครายการนี้')){
			$.post('process_mapchq.php',{
				revChqID: '<?php echo $revChqID;?>',
				receiptID: '<?php echo $receiptID;?>'
			},
			function(data){
				if(data==1){
					alert("ไม่พบเช็คที่จะ map อาจทำรายการไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ");
					RefreshMe();
				}else if(data==2){
					alert("ทำรายการเรียบร้อยแล้ว");
					RefreshMe();
				}else{
					alert("ไม่สามารถทำรายการได้ กรุณาตรวจสอบ");
					alert(data);
				}
			});
		}	
	});
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
<title>รายละเอียดช่องทางการจ่าย</title>
</head>
<body>
<?php
if($showconfig!="no"){
	if($mapchq!='yes'){ //กรณี map เช็คจะไม่แสดงส่วนนี้
		?>
		<div style="text-align:center"><h2>รายละเอียดช่องทางการจ่ายของใบเสร็จ</h2></div>
		<?php
	}
	if($typeReceive != ""){ 
	?>
	<div  style="text-align:center;color:red;font-weight:bold;">ใบเสร็จออกแทน <?php echo "$typeReceive : $typeDetail"; ?></div>
	<?php 
	} 
if($cancel>0){
	if($mapchq!='yes'){ //กรณี map เช็คจะไม่แสดงส่วนนี้
	?>
	<div style="text-align:right">
	<?php
	//ถ้าประเภทการจ่ายเหมือนกันแสดงว่าเป็นเงินต้น ให้ popup หน้าของเงินต้น
	if($typePayID_chk==$typeID_chk){
	?>	
	<input type="button"  value="ขอยกเลิกใบเสร็จนี้" onclick="javascript:popU('ReceiptCancelConfirm.php?contractID=<?php echo $contractID_chk;?>&receiptID=<?php echo $receiptID;?>&statusshow=1&typePayID=<?php echo $typePayID_chk;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"/>
	<?php
	}else{
	?>
	<input type="button"  value="ขอยกเลิกใบเสร็จนี้" onclick="javascript:popU('ReceiptOtherCancelConfirm.php?contractID=<?php echo $contractID_chk;?>&receiptID=<?php echo $receiptID;?>&statusshow=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" />
	<?php
	}
	?>
	</div>
	<?php 
	}
}
//แสดงข้อความใบเสร็จนี้ยังใช้งานอยู่
$qryresult=pg_query("select * from thcap_v_receipt_otherpay_cancel
	where \"receiptID\" = '$receiptID'");
$numsnote=pg_num_rows($qryresult);
if($numsnote>0){} 
else{
	echo "<table align=right><tr><td><font color=\"red\"><b>ใบเสร็จนี้ยังใช้งานอยู่ </b></font></td></tr></table>";
}
?>
<?php 
$default_date="2013-03-01";//กำหนดวันเริ่มต้นที่ต้องการให้แสดง
$receiveDate_bill=$receiveDate;
	list($receiveDate_bill) = explode(" ", $receiveDate_bill); //แยกวันที่และเวลาออกจากกัน (นำวันที่ไปใช้งานอย่างเดียว)
//ตรวจสอบว่ามีการส่งจดหมายหรือยัง
$qrysend=pg_query("select \"sendDate\" from vthcap_letter where \"detailRef\" ='$receiptID' and \"contractID\" = '$contractID_chk'");
$numsend=pg_num_rows($qrysend);
if($numsend>0){
	$txtsend="จัดส่งแล้ว";
	list($sendDate)=pg_fetch_array($qrysend);
}else{
	$txtsend="ยังไม่จัดส่ง";
	}
?>
<table width="100%">
	<tr>
		<td>
			
		</td>
		<td>
			<?php
						if($txtcancel!=""){
							echo "<table cellspacing=1 cellpadding=1 align=right>
							<tr>
								<td vlign=middle align=right><img src=\"images/del.png\"/><font color=#FF0000><b>$txtcancel</b></font><br>
								<b>ผู้ขอยกเลิก: </b>$req_name<br> <b>โดย:</b>$select_name</td>
							</tr>
							</table>";
						}
						if($numcheck>0){
							$ReqUser=pg_fetch_result($qrycheck,1);
							$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$ReqUser' ");
							$select_name = pg_fetch_result($qryname,0);
							
							$textstatus = "เอกสารนี้อยู่ระหว่างรออนุมัติยกเลิก";
						echo "<table cellspacing=1 cellpadding=1 align=right>
							<tr>
								<td vlign=middle align=right><img src=\"images/sandclock.png\"/><font color=#FF0000><b>$textstatus</b></font><br>
								<b>โดย:</b>$select_name</td>
							</tr>
							</table>";
						}
					?>
		</td>
	</tr>
	<?php
	if($receiveDate >= '2013-01-01 00:00:00')
	{
		if($abh_id == "")
		{
			echo "<tr><td><b>การบันทึกบัญชีของใบเสร็จนี้ : <font color=\"red\">รายการนี้ยังไม่บันทึกบัญชี</font></b></td></tr>";
		}
		else
		{
			echo "<tr><td><b>การบันทึกบัญชีของใบเสร็จนี้ : </b> <img src=\"images/detail.gif\" onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" style=\"cursor:pointer;\"></td></tr>";
		}
	}
	?>
	<tr>
		<td width="50%"><b>เลขที่สัญญา :  <span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractIDshow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="blue">
		  <u><?php echo $contractIDshow; ?></u></b></font></span>
		</td>
		<td rowspan="4">
		  <?php if($receiveDate_bill>=$default_date){ ?>
				<table cellspacing=1 cellpadding=1 align=right>
					<tr>
						<td bgcolor=red align=right>
							<table bgcolor=#FFFFFF cellspacing=0 cellpadding=5>
								<tr>
									<td>
										<b>สถานะการส่ง&nbsp;:</b>&nbsp;<?php echo $txtsend;?><?php if($txtsend=="จัดส่งแล้ว"){echo "<br><b>วันที่จัดส่ง&nbsp;:</b>&nbsp;".$sendDate;}?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
		  <?php }else{ ?>
		  <?php } ?>
		</td>
	</tr>
	<tr><td><b>รหัสใบเสร็จ : <font color="red"><?php echo $receiptID; ?></font></b>	
	</td></tr>
	<tr><td><b>หมายเหตุยกเลิกใบเสร็จ : </b>
	<?php if($res>0){ 
		$qryresult=pg_query("SELECT \"cancelID\",\"result\" FROM thcap_temp_receipt_cancel where \"receiptID\"='$receiptID' and	\"approveStatus\"='1'");
		$numsnote=pg_num_rows($qryresult);
		if($numsnote>0){
			$resresult=pg_fetch_array($qryresult);
			list($cancelID,$resultnote)=$resresult;
			if($resultnote==""){//ไม่มีหมายเหตุ
				echo "<img src=\"images/onebit_20.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap/result_cancelreceipt.php?cancelID=$cancelID&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\">";
			}
			else{//มีหมายเหตุแล้ว
				echo "<img src=\"images/open.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap/result_cancelreceipt.php?cancelID=$cancelID&show=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\">";
			}
		}		
	}
	?>
	
	</td></tr>
	
	<?php } ?>
	<tr><td><b>ใบกำกับภาษี : <span onclick="javascript:popU('Channel_detail_v.php?receiptID=<?php echo $taxnum; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $taxnum; ?></u></span></b><?php echo $taxtxtcancel; ?></td></tr>
	<?php
		$qry_receiptDate = pg_query("select \"receiptDate\"from thcap_temp_receipt_details 
		where \"receiptID\" = '$receiptID' ");
		list($receiptDate)=pg_fetch_array($qry_receiptDate);
	?>
	<tr><td><b>วันที่ใบเสร็จ : <font color='blue'><?php echo $receiptDate; ?></font></b></td></tr>
	<tr><td><b>วันที่รับเงิน : <font color='blue'><?php echo substr($receiveDate,0,19); ?></font> (หากคนละวันกับวันที่ใบเสร็จ อาจเกิดจากที่รับเงินจากเงินโอนที่ไม่ทราบว่าเป็นของใครมาก่อน)</b></td></tr>
	<br>
</table>
<div><b>การจ่ายที่เกี่ยวข้อง :</b></div>  
<table width="100%" cellSpacing="1" cellPadding="2" bgcolor="#EEEED1" align="center">
<tr bgcolor="#CDCDB4"><th>รหัส</th><th>รายละเอียด</th><th>จำนวนเงิน</th><th>VAT</th><th>จำนวนเงินรวม VAT</th><th>ภาษีหัก ณ ที่จ่าย</th></tr>

<?php 
$sumnet=0;
$sumvat=0;
$sumdebt=0;
$sumwht=0;

$typesql = pg_query("select a.\"typePayID\",a.\"tpDesc\"||a.\"tpFullDesc\"||' '||a.\"typePayRefValue\" as namedetail,
case when b.\"contractID\" is null then c.\"contractID\" else b.\"contractID\" end as \"contractID\",\"netAmt\",\"vatAmt\",
\"debtAmt\",\"whtAmt\",\"userFullname\",d.\"doerStamp\",a.\"receiveDate\",\"typeReceive\",\"typeDetail\",\"receiptRemark\"
from thcap_v_receipt_otherpay a 
left join thcap_temp_otherpay_debt b on a.\"debtID\" = b.\"debtID\" 
left join \"thcap_temp_int_201201\" c on a.\"receiptID\" = c.\"receiptID\"
left join (		select \"receiptID\",\"doerStamp\",\"userFullname\" 
					from \"thcap_v_receipt_details\" 
					where \"receiptID\" = '$receiptID' 
					group by \"receiptID\",\"doerStamp\",\"userFullname\" ) d on a.\"receiptID\" = d.\"receiptID\"
where a.\"receiptID\" = '$receiptID' ");

$rows_receip_normal = pg_num_rows($typesql);

if($rows_receip_normal == 0){ //หากไม่มีข้อมูลในใบเสร็จธรรมดาแสดงว่าใบเสร็จอาจจะถูกยกเลิกไปแล้ว ให้ไปดูในตารางใบเสร็จที่ถูกยกเลิกแทน
	$typesql = pg_query("select a.\"typePayID\",a.\"tpDesc\"||a.\"tpFullDesc\"||' '||a.\"typePayRefValue\" as namedetail,
	case when b.\"contractID\" is null then c.\"contractID\" else b.\"contractID\" end as \"contractID\",\"netAmt\",\"vatAmt\",
	\"debtAmt\",\"whtAmt\",\"userFullname\",d.\"doerStamp\",a.\"receiveDate\",\"typeReceive\",\"typeDetail\",\"receiptRemark\"
	from thcap_v_receipt_otherpay_cancel a 
	left join thcap_temp_otherpay_debt b on a.\"debtID\" = b.\"debtID\" 
	left join \"thcap_temp_int_201201\" c on a.\"receiptID\" = c.\"receiptID\"
	left join (		select \"receiptID\",\"doerStamp\",\"userFullname\" 
					from \"thcap_v_receipt_details_cancel\" 
					where \"receiptID\" = '$receiptID' 
					group by \"receiptID\",\"doerStamp\",\"userFullname\" ) d on a.\"receiptID\" = d.\"receiptID\"
	where a.\"receiptID\" = '$receiptID' ");	
}

while($typequery = pg_fetch_array($typesql)){
	$detail = $typequery['namedetail'];
	$typePayID=$typequery["typePayID"];
	$contractID=$typequery["contractID"];
	$netAmt=$typequery["netAmt"];
	$vatAmt=$typequery["vatAmt"];
	$debtAmt=$typequery["debtAmt"];
	$whtAmt=$typequery["whtAmt"];
	$userFullname=$typequery["userFullname"];
	$doerStamp=$typequery["doerStamp"];
	$receiptRemark=$typequery["receiptRemark"];
	?>
	<tr bgcolor="#FFFFE0">
		<td align="center"><?php echo $typePayID;?></td>
		<td><?php echo $detail;?></td>
		<td align="right"><?php echo number_format($netAmt,2);?></td>
		<td align="right"><?php echo number_format($vatAmt,2);?></td>
		<td align="right"><?php echo number_format($debtAmt,2);?></td>
		<td align="right"><?php echo number_format($whtAmt,2);?></td>
	</tr>
<?php 
	$sumnet=$sumnet+$netAmt;
	$sumvat=$sumvat+$vatAmt;
	$sumdebt=$sumdebt+$debtAmt;
	$sumwht=$sumwht+$whtAmt;
} 
?>
<tr align="right" style="font-weight:bold;">
	<td colspan="2" align="center">รวม</td>
	<td><?php echo number_format($sumnet,2);?></td>
	<td><?php echo number_format($sumvat,2);?></td>
	<td><?php echo number_format($sumdebt,2);?></td>
	<td><?php echo number_format($sumwht,2);?></td>
</tr>
<table>
<br>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" frame="box" bgcolor="#E8E8E8" align="center">
<?php 

	$sqlchannel = pg_query("SELECT \"byChannel\",\"ChannelAmt\",\"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID'");

			
	$rowchannel = pg_num_rows($sqlchannel);
if($rowchannel > 0){
?>
<tr>
    <td height="25" colspan="4" align="center" ><b> มีช่องทางการจ่ายดังนี้ </b>
	<hr width="90%"></td>
</tr>

<?php 	$num = 0;
		while($rechannel = pg_fetch_array($sqlchannel)){
			$byChannel  = $rechannel["byChannel"];
			$ChannelAmt  = $rechannel["ChannelAmt"];
			$byChannelRef  = $rechannel["byChannelRef"];
			
			//ค้นหาเลขที่เช็คจากรหัสเงินโอน
			$qrychq=pg_query("select a.\"revTranID\",a.\"revChqID\",b.\"bankChqNo\" from finance.thcap_receive_transfer a
			left join finance.thcap_receive_cheque b on a.\"revChqID\"=b.\"revChqID\"
			where \"revTranID\"='$byChannelRef'");
			list($revtranid,$chqid,$chqno)=pg_fetch_array($qrychq);
			
			//แสดงรหัสเงินโอน
			if($revtranid!=""){
				$revtranid="<font color=red> รหัสเงินโอน <span onclick=\"javascript:popU('frm_transpay_detail.php?revTranID=$byChannelRef','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=550')\" style=\"cursor:pointer\"><u>$byChannelRef</u></span></font><br>";
			}
			
			//แสดงเงินเลขที่เช็ค
			if($chqno!=""){
				$chqno="<font color=red>(เลขที่เช็ค <span onclick=\"javascript:popU('Channel_detail_chq.php?revChqID=$chqid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=550')\" style=\"cursor:pointer\"><u>$chqno</u></span>)</font>";
			}
			
			$num++;
			if($byChannel=="" || $byChannel=="0"){$txtchannel="ไม่ระบุ";}
			else{
				if($byChannel=="999"){
					$txtchannel="ภาษีหัก ณ ที่จ่าย";
				}else{
					//นำไปค้นหาในตาราง BankInt
					$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
					$ressearch=pg_fetch_array($qrysearch);
					list($BAccount,$BName)=$ressearch;
					$txtchannel="$BAccount-$BName";	
					
					$qry_hold2 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('','1')");
					list($chkhold2) = pg_fetch_array($qry_hold2);
							
					$qry_secur2 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('','1')");
					list($chksecur2) = pg_fetch_array($qry_secur2);
					
					if($byChannel==$chksecur2 || $byChannel==$chkhold2 || $byChannel=='990' || $byChannel=='991'){
						$txtchannel=$txtchannel." เลขที่ <span style=\"color:red;cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$byChannelRef','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><u>$byChannelRef</u></span>";
					}
	
				}
			}
			
			echo  "<tr><td height=\"25\" width=\"25%\" align=\"right\" valign=\"top\"><b>ช่องทางที่ $num : </td><td width=\"40%\">$txtchannel</b> $revtranid $chqno</td>";	
			echo  "<td height=\"25\" align=\"right\" width=\"15%\" valign=\"top\"><b>จำนวนเงิน  : </b></td><td align=\"left\" valign=\"top\">".number_format($ChannelAmt,2)." <b>บาท</b></td></tr>";		
			$sumamt=$sumamt+$ChannelAmt;
		}
		echo "<tr><td colspan=4><hr width=\"90%\"></td></tr>";
		echo  "<tr><td height=\"25\" align=\"right\"></td><td ></td>";	
		echo  "<td height=\"25\" align=\"right\" width=\"15%\"><b>รวมรับชำระ  : </b></td><td align=\"left\">".number_format($sumamt,2)." <b>บาท</b></td></tr>";		
}			
?>	
</table><br>
<div><b>ผู้ออกใบเสร็จ :</b> <?php echo $userFullname;?></div>
<div><b>วันเวลาที่ออกใบเสร็จ :</b> <?php echo $doerStamp;?></div>
<div style="padding-top:10px;width:400px;">
	<fieldset><legend><b>หมายเหตุใบเสร็จ</b></legend>
		<textarea cols="60" rows="4" readonly><?php echo $receiptRemark;?></textarea>
	</fieldset>
	
	<?php
		// หาเลขที่ cancelID
		$qry_cancelID_ref = pg_query("select \"cancelID\" from \"thcap_temp_receipt_otherpay_cancel\" where \"receiptID\" = '$receiptID' ");
		$cancelID_ref = pg_fetch_result($qry_cancelID_ref,0);
		
		// หาว่ามีรายการที่ถูกยกเลิกอัตโนมัติเนื่องจากใบเสร็จหลักหรือไม่
		if($cancelID_ref == "")
		{
			$haveReceiptID_ref = "no";
		}
		else
		{
			$qry_ReceiptID_ref = pg_query("select distinct a.\"receiptID\",
									(select sum(e.\"debtAmt\") from thcap_v_receipt_otherpay_cancel e where a.\"receiptID\" = e.\"receiptID\") as \"sumDebtAmt\"
								from thcap_temp_receipt_otherpay_cancel a
								where a.\"cancelID\" = '$cancelID_ref' and a.\"receiptID\" <> '$receiptID' ");
			$row_ReceiptID_ref = pg_num_rows($qry_ReceiptID_ref);
			if($row_ReceiptID_ref > 0)
			{
				$haveReceiptID_ref = "yes";
			}
			else
			{
				$haveReceiptID_ref = "no";
			}
		}
		
		if($haveReceiptID_ref == "yes")
		{
	?>
			<br>
			<fieldset><legend><b>ใบเสร็จอื่นๆ ที่ถูกยกเลิกไปพร้อมกับใบเสร็จนี้</b></legend>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
					<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
						<th>เลขที่ใบเสร็จ</th>
						<th>จำนวนเงินในใบเสร็จ</th>
					</tr>
					<?php
					$i=0;
					while($resRef = pg_fetch_array($qry_ReceiptID_ref))
					{
						$receiptID_ref = $resRef["receiptID"];
						$sumDebtAmt = $resRef["sumDebtAmt"];
						
						$i+=1;
						if($i%2==0){
							echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
						}else{
							echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
						}
					?>
						<td align="center"><span onclick="javascript:popU('Channel_detail.php?receiptID=<?php echo $receiptID_ref; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><font color="#0000FF"><u><?php echo $receiptID_ref; ?></u></font></span></td>
						<td align="right"><?php echo number_format($sumDebtAmt,2); ?></td>
					</tr>
					<?php
					}
					?>
				</table>
			</fieldset>
	<?php
		}
	?>
</div>
<?php
if($showconfig!="no"){
	if($mapchq=='yes'){ //กรณี map เช็คจะแสดงส่วนนี้
		?>
		<div style="text-align:center;padding:20px"><hr><input type="button" id="btn2" value="ยืนยันการ map เช็คกับใบเสร็จ <?php echo $receiptID; ?>"></div>
		<?php 
	}else{
		?>
		<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
		<?php 
	}
} 
?>
</body>
</html>