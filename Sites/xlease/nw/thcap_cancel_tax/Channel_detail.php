<?php
session_start();
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ

if($showconfig!="no"){
	include("../../config/config.php");
	$taxinvoiceID=$_GET['taxinvoiceID'];
	$mapchq=$_GET['mapchq']; //=yes กรณีแสดงข้อมูลตอน mapchq ในเมนู "ยืนยันรายการเงินโอน (การเงิน)"
	$revChqID=$_GET['revChqID']; //รหัสเช็คที่ต้องการ map กรณีแสดงข้อมูลตอน mapchq ในเมนู "ยืนยันรายการเงินโอน (การเงิน)"
}

//ตรวจสอบว่ามีเลขที่ใบเสร็จนี้จริงหรือไม่
$qrychkrec=pg_query("select * from \"thcap_temp_taxinvoice_details\" where \"taxinvoiceID\"='$taxinvoiceID'");
if(pg_num_rows($qrychkrec)==0){ 
	echo "<div align=center><h2>---ไม่พบเลขที่ใบกำกับภาษี---</h2></div>";
	exit();
}
//################################เตรียมข้อมูลสำหรับตรวจสอบว่าสามารถขอยกเลิกใบเสร็จภายในหน้านี้ได้หรือไม่
$cancel=0;
//หาว่าเลขที่ใบเสร็จที่ยกเลิกจ่ายค่าอะไร
$qryother=pg_query("select \"typePayID\", \"contractID\" from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$taxinvoiceID' limit 1");
list($typePayID_chk, $contractID_chk)=pg_fetch_array($qryother);

//หาุ typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID_chk')");
list($typeID_chk) = pg_fetch_array($select);

//ตรวจสอบว่าพนักงานท่านนี้สามารถขอยกเลิกใบเสร็จได้หรือไม่
$qrychk=pg_query("select * from \"f_usermenu\" where id_user='$id_user' and id_menu='TM12' and status='TRUE'");
$numchk=pg_num_rows($qrychk); //ถ้ามีค่า > 0 แสดงว่ามีสิทธิ์ใช้ส่วนขอยกเลิกใบเสร็จได้
if($numchk>0){
	$cancel++;
}

//หาสถานะใบเสร็จว่าถูกยกเลิกหรือยัง
$qrystscancel=pg_query("select * from thcap_temp_taxinvoice_otherpay where \"taxinvoiceID\"='$taxinvoiceID'");
$numstscancel=pg_num_rows($qrystscancel); //ถ้ามีค่า > 0 แสดงว่ายังไม่ยกเลิก

//กรณียังไม่ยกเลิกและมีสิทธิ์ในเมนูขอยกเลิก ตรวจสอบว่ากำลังรออนุมัติยกเลิกอยู่หรือไม่
if($numstscancel>0 and $numchk>0){ 
	//กรณีเป็นใบเสร็จยกเลิกค่างวดจะไม่สามารถยกเลิกได้ถ้ามีการรออนุมัติยกเลิกค่างวดอยู่ เนื่องจากมีผลกระทบกับการยกเลิกค่างวดงวดอื่นๆด้วย
	if($typePayID_chk==$typeID_chk){ //กรณีประเภทการจ่ายเป็นจ่ายค่างวด
		//ตรวจสอบว่ามีเลขที่สัญญาและรหัสค่างวดนั้นรออนุมัติยกเลิำกอยู่หรือไม่
		$qrycheck=pg_query("select * from thcap_temp_taxinvoice_cancel a
		left join \"thcap_temp_taxinvoice_otherpay\" b on a.\"taxinvoiceID\"=b.\"taxinvoiceID\"
		where a.\"contractID\" = '$contractID_chk' and \"typePayID\"='$typePayID_chk' and \"approveStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
		if($numcheck>0){ 
			$cancel=0; //ไม่อนุญาตให้ยกเลิกเนื่องจากมีรายการค่างวดที่รออนุมัติอยู่
		}else{
			$cancel++; //อนุญาตให้ยกเลิก
		}
	}else{//กรณีเป็นการจ่ายค่าอื่นๆ จะสามารถ
		$qrycheck=pg_query("select \"typePayID\" from thcap_temp_taxinvoice_cancel a
		left join \"thcap_temp_taxinvoice_otherpay\" b on a.\"taxinvoiceID\"=b.\"taxinvoiceID\"
		where a.\"taxinvoiceID\" = '$taxinvoiceID' and \"approveStatus\"='2'");
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

//##################################จบการเตรียมข้อมูล########################################
				
//หาใบกำกับภาษี
/*$qrytax=pg_query("SELECT \"thcap_taxinvoiceIDTotaxinvoiceID\"('$taxinvoiceID')");
$restax=pg_fetch_array($qrytax);
$taxnum=$restax["thcap_taxinvoiceIDTotaxinvoiceID"];
if($taxnum==""){
	$taxnum="-";
}else{
	$qry_taxcancel = pg_query("SELECT * FROM thcap_temp_taxinvoice_otherpay_cancel where \"taxinvoiceID\" = '$taxnum'");
	$rows_taxcancel = pg_num_rows($qry_taxcancel);
	IF($rows_taxcancel > 0){
		$taxtxtcancel = "  (<font color=\"red\"><b> ถูกยกเลิก </b></font>)";
	}

}*/

$recdate = pg_query("select \"taxpointDate\",\"contractID\" from thcap_v_taxinvoice_otherpay 
where \"taxinvoiceID\" = '$taxinvoiceID' group by \"taxpointDate\",\"contractID\" ");
$rows_receipdate_normal = pg_num_rows($recdate);
if($rows_receipdate_normal == 0){ //หากไม่มีข้อมูลให้ไปหาในวิวใบเสร็จที่ถูกยกเลิก
	
	$recdate = pg_query("select \"taxpointDate\",\"contractID\" from thcap_v_taxinvoice_otherpay_cancel
	where \"taxinvoiceID\" = '$taxinvoiceID'  group by \"taxpointDate\",\"contractID\" ");
	$txtcancel = "  (<font color=\"red\"><b> ถูกยกเลิก </b></font>)";
}
list($taxpointDate,$contractIDshow)=pg_fetch_array($recdate);

/* ใบเสร็จออกแทน
$qry_typeReceive = pg_query("select \"typeReceive\",\"typeDetail\" from thcap_temp_taxinvoice_details 
where \"taxinvoiceID\" = '$taxinvoiceID' ");
list($typeReceive,$typeDetail)=pg_fetch_array($qry_typeReceive);*/
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
				taxinvoiceID: '<?php echo $taxinvoiceID;?>'
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
	<input type="button"  value="ขอยกเลิกใบกำกับภาษีนี้" onclick="javascript:popU('ReceiptCancelConfirm.php?contractID=<?php echo $contractID_chk;?>&taxinvoiceID=<?php echo $taxinvoiceID;?>&statusshow=1&typePayID=<?php echo $typePayID_chk;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"/>
	<?php
	}else{
	?>
	<input type="button"  value="ขอยกเลิกใบกำกับภาษีนี้" onclick="javascript:popU('ReceiptOtherCancelConfirm.php?contractID=<?php echo $contractID_chk;?>&taxinvoiceID=<?php echo $taxinvoiceID;?>&statusshow=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" />
	<?php
	}
	?>
	</div>
	<?php 
	}
} 
?>
<div><b>เลขที่สัญญา :  <span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractIDshow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="blue">
		  <u><?php echo $contractIDshow; ?></u></b></font></span></div>
<div><b>รหัสใบกำกับภาษี : <font color="red"><?php echo $taxinvoiceID; ?></font></b><?php echo $txtcancel; ?></div>
<?php } ?>
<div style="clear:both;"></div>
<div><b>วันที่ใบกำกับภาษี : <?php echo substr($taxpointDate,0,19); ?></b></div>
<div><b>การจ่ายที่เกี่ยวข้อง :</b></div>  
<table width="100%" cellSpacing="1" cellPadding="2" bgcolor="#EEEED1" align="center">
<tr bgcolor="#CDCDB4"><th>รหัส</th><th>รายละเอียด</th><th>จำนวนเงิน</th><th>VAT</th><th>จำนวนเงินรวม VAT</th><th>ภาษีหัก ณ ที่จ่าย</th></tr>
<?php 
$sumnet=0;
$sumvat=0;
$sumdebt=0;
$sumwht=0;

$typesql = pg_query("select a.\"typePayID\",a.\"tpDesc\"||a.\"tpFullDesc\"||' '||a.\"typePayRefValue\" as namedetail,
b.\"contractID\",\"netAmt\",\"vatAmt\",
\"debtAmt\",\"whtAmt\",d.\"userFullname\",d.\"doerStamp\",a.\"taxpointDate\"
from thcap_v_taxinvoice_otherpay a 
left join thcap_temp_otherpay_debt b on a.\"debtID\" = b.\"debtID\" 
left join (		select \"taxinvoiceID\",\"doerStamp\",\"userFullname\" 
					from \"thcap_v_taxinvoice_details\" 
					where \"taxinvoiceID\" = '$taxinvoiceID' 
					group by \"taxinvoiceID\",\"doerStamp\",\"userFullname\" ) d on a.\"taxinvoiceID\" = d.\"taxinvoiceID\"
where a.\"taxinvoiceID\" = '$taxinvoiceID' ");

$rows_receip_normal = pg_num_rows($typesql);
if($rows_receip_normal == 0){ //หากไม่มีข้อมูลในใบเสร็จธรรมดาแสดงว่าใบเสร็จอาจจะถูกยกเลิกไปแล้ว ให้ไปดูในตารางใบเสร็จที่ถูกยกเลิกแทน



	
	$typesql = pg_query("select a.\"typePayID\",a.\"tpDesc\"||a.\"tpFullDesc\"||' '||a.\"typePayRefValue\" as namedetail,
	b.\"contractID\",\"netAmt\",\"vatAmt\",
	\"debtAmt\",\"whtAmt\",\"userFullname\",d.\"doerStamp\",a.\"taxpointDate\",\"typeReceive\",\"typeDetail\",\"receiptRemark\"
	from thcap_v_taxinvoice_otherpay_cancel a 
	left join thcap_temp_otherpay_debt b on a.\"debtID\" = b.\"debtID\"
	left join (		select \"taxinvoiceID\",\"doerStamp\",\"userFullname\" 
					from \"thcap_v_taxinvoice_details_cancel\" 
					where \"taxinvoiceID\" = '$taxinvoiceID' 
					group by \"taxinvoiceID\",\"doerStamp\",\"userFullname\" ) d on a.\"taxinvoiceID\" = d.\"taxinvoiceID\"
	where a.\"taxinvoiceID\" = '$taxinvoiceID' ");
	
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
<table><br>
<div><b>ผู้ออกใบกำกับภาษี :</b> <?php echo $userFullname;?></div>
<div><b>วันเวลาที่ออกใบกำกับภาษี :</b> <?php echo $doerStamp;?></div>
<div style="padding-top:10px;width:400px;">
<fieldset><legend><b>หมายเหตุใบกำกับภาษี</b></legend>
<textarea cols="60" rows="4" readonly><?php echo $receiptRemark;?></textarea>
</fieldset>
</div>
<?php
if($showconfig!="no"){
	if($mapchq=='yes'){ //กรณี map เช็คจะแสดงส่วนนี้
		?>
		<div style="text-align:center;padding:20px"><hr><input type="button" id="btn2" value="ยืนยันการ map เช็คกับใบเสร็จ <?php echo $taxinvoiceID; ?>"></div>
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