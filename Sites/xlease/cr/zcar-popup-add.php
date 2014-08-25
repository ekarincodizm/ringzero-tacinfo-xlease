<?php
include("../config/config.php");
$nowdate = nowDate();//ดึง วันที่จาก server
$idaddcar = pg_escape_string($_GET['idaddcar']);

$qry_cartax=pg_query("select \"TypeDep\" from carregis.\"CarTaxDue\" where \"IDCarTax\"='$idaddcar'");
if($res_cartax=pg_fetch_array($qry_cartax)){
    $TypeDep = $res_cartax["TypeDep"];
}

$ciddetail = 0;
$qry_detailcartax=pg_query("select \"TypePay\" from carregis.\"DetailCarTax\" where \"IDCarTax\"='$idaddcar'");
while($res_detailcartax=pg_fetch_array($qry_detailcartax)){
    $ciddetail++;
    $TypePay[] = $res_detailcartax["TypePay"];
}

if($TypeDep == 101){
    // "มิเตอร์+ภาษี";
    $chk_101 = @in_array('101', $TypePay);
    $chk_105 = @in_array('105', $TypePay);
    $chk_lob1 = @in_array('-1', $TypePay);
}elseif($TypeDep == 105){
    // "มิเตอร์";
    $chk_105 = @in_array('105', $TypePay);
    $chk_lob1 = @in_array('-1', $TypePay);
}
?>

<script type="text/javascript">
$("#datetax").datepicker({
    showOn: 'button',
    buttonImage: 'calendar.gif',
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});
$("#datetax_k").datepicker({
    showOn: 'button',
    buttonImage: 'calendar.gif',
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});
$("#datemeter").datepicker({
    showOn: 'button',
    buttonImage: 'calendar.gif',
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});
$("#datemeter_k").datepicker({
    showOn: 'button',
    buttonImage: 'calendar.gif',
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});

$('#btn_save').click(function(){
    $("#btnshow").attr('disabled', true);
    var datastring = $('#frm1').serialize();
    $.post('zcar-submit.php',{
        cid: '<?php echo $idaddcar; ?>',
        datastring:datastring
    },
    function(data){
        if(data.success){
            $('#panel').empty();
            mystring = $('#tbsearch').val();
            myarray = mystring.split("|");
            var cregis = encodeURIComponent ( myarray[0] );
            $("#panel").load("zcar-panel.php?regis="+ cregis);
            $('#dialog').dialog({
                width: 300,
                height: 150
            });
            $('#dialog').text('บันทึกข้อมูลเรียบร้อยแล้ว');
        }else{
            //console.log(data);
            alert(data.message);
        }
    },'json');

    $("#btnshow").attr('disabled', false);
});

    var counter = 0;
    $('#btn_add').click(function(){
		/* กำหนดให้เพิ่มได้ทีละ 1 รายการเนื่องจากต้องตรวจสอบในหน้าหลักก่อนว่าเกิดที่กำหนดไว้หรือไม่ แต่ตอนนี้กำหนดให้สามารถเพิ่มได้เรื่อยๆ ส่วนนี้จึงไม่ต้องใช้
        if(counter == 1){
            alert('เพิ่มได้สูงสุด 1 รายการ');
            return false;
        }
		*/
        counter++;
        
        $.post("zcar-showadd.php",
            { c:counter },
            function(data){
                $( data ).appendTo("#TextBoxesGroup");
            },
            'html' //สามารถระบุว่าจะเป็น html,json,text ก็ได้ขึ้นอยู่กับสิ่งที่ return
        );
        $('#txtcounter').val(counter);
        chkbtn(counter);
    });

    $("#btn_lob").click(function(){
        $("#TextBoxDiv" + counter).remove();
        counter--;
        $('#txtcounter').val(counter);
        chkbtn(counter);
    });
    
    function chkbtn(a){
        if(a > 0){
            $("#btn_lob").attr('disabled', false);
        }else{
            $("#btn_lob").attr('disabled', true);
        }
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

<form name="frm1" id="frm1" action="" method="post">
<?php
if($TypeDep == 101){ //ถ้าเป็นประเภทค่าภาษีรถยนต์ต้องตรวจสอบ 2 ค่าคือ ค่าภาษีกับค่าตรวจมิเตอร์
    if(!$chk_101){ //ถ้าไม่พบค่าภาษีรถยนต์
?>
		<div style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="50%">เลขที่ใบเสร็จ <input type="text" name="txt_bill_tax" id="txt_bill_tax" size="15"> (ค่าภาษีรถยนต์)</td>
					<td width="25%">จำนวนเงิน <input type="text" name="txt_money_tax" id="txt_money_tax" size="15" style="text-align:right"></td>
					<td width="25%">วันที่ <input type="text" id="datetax" name="datetax" size="13" value="<?php echo $nowdate; ?>"></td>
				</tr>
			</table>
		</div>

		<div style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="50%"><span style="color:#808080">เลขที่ใบเสร็จ</span> <input type="text" name="txt_bill_tax_k" id="txt_bill_tax_k" size="15"> (ลงขัน ค่าภาษีรถยนต์)</td>
					<td width="25%">จำนวนเงิน <input type="text" name="txt_money_tax_k" id="txt_money_tax_k" size="15" style="text-align:right"></td>
					<td width="25%">วันที่ <input type="text" id="datetax_k" name="datetax_k" size="13" value="<?php echo $nowdate; ?>"></td>
				</tr>
			</table>
		</div>

<?php
    }else { echo '<div style="background-color:#FFFFDF; border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">รายการนี้ได้เพิ่ม 101:ค่าภาษีรถยนต์ ไปแล้ว</div>'; }
	
	if(!$chk_105){ //ถ้าไม่พบค่าตรวจมิเตอร์
?>
		<div style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="50%">เลขที่ใบเสร็จ <input type="text" name="txt_bill_meter" id="txt_bill_meter" size="15"> (ตรวจมิเตอร์)</td>
					<td width="25%">จำนวนเงิน <input type="text" name="txt_money_meter" id="txt_money_meter" size="15" style="text-align:right"></td>
					<td width="25%">วันที่ <input type="text" id="datemeter" name="datemeter" size="13" value="<?php echo $nowdate; ?>"></td>
				</tr>
			</table>
		</div>

		<div style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="50%"><span style="color:#808080">เลขที่ใบเสร็จ</span> <input type="text" name="txt_bill_meter_k" id="txt_bill_meter_k" size="15"> (ลงขัน ตรวจมิเตอร์)</td>
					<td width="25%">จำนวนเงิน <input type="text" name="txt_money_meter_k" id="txt_money_meter_k" size="15" style="text-align:right"></td>
					<td width="25%">วันที่ <input type="text" id="datemeter_k" name="datemeter_k" size="13" value="<?php echo $nowdate; ?>"></td>
				</tr>
			</table>
		</div>

<?php
		}else{ echo '<div style="background-color:#FFFFDF; border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">รายการนี้ได้เพิ่ม 105:ตรวจมิเตอร์ ไปแล้ว</div>'; }
		
} elseif($TypeDep == 105){ //ถ้าเป็นประเภทตรวจมิเตอร์ ให้ตรวจสอบแค่ค่าตรวจมิเตอร์อย่างเดียว
    if(!$chk_105){
?>
		<div style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="50%">เลขที่ใบเสร็จ <input type="text" name="txt_bill_meter" id="txt_bill_meter" size="15"> (ตรวจมิเตอร์)</td>
					<td width="25%">จำนวนเงิน <input type="text" name="txt_money_meter" id="txt_money_meter" size="15" style="text-align:right"></td>
					<td width="25%">วันที่ <input type="text" id="datemeter" name="datemeter" size="13" value="<?php echo $nowdate; ?>"></td>
				</tr>
			</table>
		</div>

		<div style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="50%">เลขที่ใบเสร็จ <input type="text" name="txt_bill_meter_k" id="txt_bill_meter_k" size="15"> (ลงขัน ตรวจมิเตอร์)</td>
					<td width="25%">จำนวนเงิน <input type="text" name="txt_money_meter_k" id="txt_money_meter_k" size="15" style="text-align:right"></td>
					<td width="25%">วันที่ <input type="text" id="datemeter_k" name="datemeter_k" size="13" value="<?php echo $nowdate; ?>"></td>
				</tr>
			</table>
		</div>

<?php
    }else{ echo '<div style="background-color:#FFFFDF; border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">รายการนี้ได้เพิ่ม 105:ตรวจมิเตอร์ ไปแล้ว</div>'; }
}
?>

<div id="TextBoxesGroup"></div>
<input type="hidden" name="txtcounter" id="txtcounter" value="0">

<div style="margin-top:10px; float:left"><input type="button" name="btn_save" id="btn_save" value="บันทึกข้อมูล"></div>
<div style="margin-top:10px; float:right"><input type="button" name="btn_add" id="btn_add" value="+ เพิ่มรายการอื่นๆ"><input type="button" name="btn_lob" id="btn_lob" value="- ลบรายการ" disabled="true"></div>
<div style="clear:both"></div>

</form>