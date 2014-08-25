<?php
session_start();
include("../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$cmd = $_GET['cmd'];
$t = $_GET['t'];
$ChequeNo = $_GET['ChequeNo'];
$PostID = $_GET['PostID'];

if($cmd == "Cancel"){
    $qry = "UPDATE \"FCheque\" SET \"Accept\"='FALSE' WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo'";
    if( pg_query($qry) ){
        echo 1;
    }else{
        echo 0;
    }
}

elseif($cmd == "Edit1"){
    
    pg_query("BEGIN WORK");
    $status = 0;
    
    $txtChequeNo = $_GET['txtChequeNo'];
    $cbBankName = $_GET['cbBankName'];
    $txtBankBranch = $_GET['txtBankBranch'];
    $txtDateOnCheque = $_GET['txtDateOnCheque'];
    
    $qry = "UPDATE \"FCheque\" SET \"ChequeNo\"='$txtChequeNo',\"BankName\"='$cbBankName',\"BankBranch\"='$txtBankBranch',\"DateOnCheque\"='$txtDateOnCheque' WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo'";
    if(!pg_query($qry)){
        $status++;
    }
    
    if($ChequeNo != $txtChequeNo){
        $qry = "UPDATE \"DetailCheque\" SET \"ChequeNo\"='$txtChequeNo' WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo'";
        if(!pg_query($qry)){
            $status++;
        }
    }
    
    if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขเช็ค', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo 1;
    }else{
        pg_query("ROLLBACK");
        echo 0;
    }
    
}

elseif($cmd == "Edit2"){
    
    pg_query("BEGIN WORK");
    $status = 0;
    
    $payment = json_decode(stripcslashes($_POST["payment"]));
    $AmtOnCheque = $_POST['AmtOnCheque'];

    foreach($payment as $key => $value){
        $v_autoid = $value->autoid;
        $v_idno = $value->idno;
        $v_typepay = $value->typepay;
        $v_amount = $value->amount;
        
        if(!empty($v_autoid) AND empty($v_idno)){
            //UPDATE
            $qry = "UPDATE \"DetailCheque\" SET \"TypePay\"='$v_typepay',\"CusAmount\"='$v_amount' WHERE \"auto_id\"='$v_autoid'";
            if(!pg_query($qry)){
                $status++;
            }
        }elseif(empty($v_autoid) AND !empty($v_idno)){
            //INSERT
            $qry_select = pg_query("SELECT \"CusID\" FROM \"Fp\" WHERE \"IDNO\"='$v_idno'");
            if($res_select = pg_fetch_array($qry_select)){
                 $CusID = $res_select['CusID'];
            }
            
            $qry = "INSERT INTO \"DetailCheque\" (\"PostID\",\"ChequeNo\",\"CusID\",\"IDNO\",\"TypePay\",\"CusAmount\",\"ReceiptNo\",\"PrnDate\") 
            VALUES ('$PostID','$ChequeNo','$CusID','$v_idno','$v_typepay','$v_amount',DEFAULT,DEFAULT) ";
            if(!pg_query($qry)){
                $status++;
            }
        }
    }

    $qry = "UPDATE \"FCheque\" SET \"AmtOnCheque\"='$AmtOnCheque' WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo'";
    if(!pg_query($qry)){
        $status++;
    }
    
    if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขเช็ค', '$add_date')");
		//ACTIONLOG---
        pg_query("COMMIT");
        echo 1;
    }else{
        pg_query("ROLLBACK");
        echo 0;
    }
    
}

elseif($t == 1 AND $cmd == "Show"){
    
    $qry = pg_query("SELECT * FROM \"FCheque\" WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo' LIMIT 1");
    if($res=pg_fetch_array($qry)){
        $ChequeNo = $res['ChequeNo'];
        $BankName = $res['BankName'];
        $BankBranch = $res['BankBranch'];
        $DateOnCheque = $res['DateOnCheque'];
    }
?>

<script type="text/javascript">
$('#btnEdit1').click(function(){
    $.get('edit_cheque_api.php?cmd=Edit1&ChequeNo=<?php echo $ChequeNo; ?>&PostID=<?php echo $PostID; ?>&txtChequeNo='+$('#txtChequeNo').val()+'&cbBankName='+$('#cbBankName').val()+'&txtBankBranch='+$('#txtBankBranch').val()+'&txtDateOnCheque='+$('#txtDateOnCheque').val(), function(data){
        if(data == 1){
            $('#tb_no').val('');
            $('#div_show').empty();
            alert('บันทึกเรียบร้อยแล้ว');
        }else{
            alert('ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง');
        }
    });
});

$("#txtDateOnCheque").datepicker({
    showOn: 'button',
    buttonImage: 'calendar.gif',
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});
</script>

    <table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0">
    <tr>
        <td width="180"><b>เลขที่เช็ค :</b></td>
        <td><input type="text" name="txtChequeNo" id="txtChequeNo" value="<?php echo $ChequeNo; ?>"></td>
    </tr>
    <tr>
        <td><b>ธนาคาร :</b></td>
        <td>
            <select name="cbBankName" id="cbBankName">
            <?php
            $qry_bank = pg_query("SELECT * FROM \"BankCheque\" ORDER BY \"BankName\" ASC");
            while( $res_bank = pg_fetch_array($qry_bank) ){
                $b_BankCode = $res_bank['BankCode'];
                $b_BankName = $res_bank['BankName'];
                
                if($b_BankCode == $BankName){
                    echo "<option value=\"$b_BankCode\" selected>$b_BankName</option>";
                }else{
                    echo "<option value=\"$b_BankCode\">$b_BankName</option>";
                }
            }
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><b>สาขา :</b></td>
        <td><input type="text" name="txtBankBranch" id="txtBankBranch" value="<?php echo $BankBranch; ?>"></td>
    </tr>
    <tr>
        <td><b>วันที่บนเช็ค :</b></td>
        <td><input type="text" name="txtDateOnCheque" id="txtDateOnCheque" value="<?php echo $DateOnCheque; ?>"></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="button" name="btnEdit1" id="btnEdit1" value="บันทึก"></td>
    </tr>
    </table>
<?php
}

elseif($t == 2 AND $cmd == "Show"){
    
    $qry = pg_query("SELECT \"AmtOnCheque\" FROM \"FCheque\" WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo' LIMIT 1");
    if($res=pg_fetch_array($qry)){
        $AmtOnCheque = $res['AmtOnCheque'];
    }
?>

    <table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0">
    <tr>
        <td width="180"><b>ยอดเงินบนเช็ค :</b></td>
        <td><input type="text" name="txtAmtOnCheque" id="txtAmtOnCheque" value="<?php echo $AmtOnCheque; ?>" size="15" style="text-align:right"> บาท.</td>
    </tr>
    </table>

    <div style="float:left; margin-top:5px; margin-left:5px"><b>รายการที่ต้องการชำระจากเช็ค</b></div>
    
    <table cellpadding="3" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0">
    <tr bgcolor="#D0D0D0" style="font-weight:bold">
        <td width="10%">ลำดับ</td>
        <td width="30%">เลขที่สัญญา</td>
        <td width="30%">ประเภท</td>
        <td width="30%">ยอดเงิน</td>
    </tr>
<?php
$qry_detail = pg_query("SELECT * FROM \"DetailCheque\" WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo' ORDER BY \"IDNO\" ASC ");
while($res_detail=pg_fetch_array($qry_detail)){
    $auto_id = $res_detail['auto_id'];
    $IDNO = $res_detail['IDNO'];
    $TypePay = $res_detail['TypePay'];
    $CusAmount = $res_detail['CusAmount'];
    
    $sum += $CusAmount;
    
    $inub++;
?>
    <tr bgcolor="#FFFFFF">
        <td><?php echo $inub; ?><input id="txtAutoID<?php echo $inub; ?>" name="txtAutoID<?php echo $inub; ?>" type="hidden" value="<?php echo $auto_id; ?>"></td>
        <td><?php echo $IDNO; ?></td>
        <td>
            <select name="cbTypePay<?php echo $inub; ?>" id="cbTypePay<?php echo $inub; ?>">
            <?php
            $qry_tp = pg_query("SELECT * FROM \"TypePay\" ORDER BY \"TName\" ASC");
            while( $res_tp = pg_fetch_array($qry_tp) ){
                $tp_TypeID = $res_tp['TypeID'];
                $tp_TName = $res_tp['TName'];
                
                if($tp_TypeID == $TypePay){
                    echo "<option value=\"$tp_TypeID\" selected>$tp_TName</option>";
                }else{
                    echo "<option value=\"$tp_TypeID\">$tp_TName</option>";
                }
            }
            ?>
            </select>
        </td>
        <td><input type="text" name="txtCusAmount<?php echo $inub; ?>" id="txtCusAmount<?php echo $inub; ?>" value="<?php echo $CusAmount; ?>" size="15" style="text-align:right" onkeyup="javascript:updateSum()"></td>
    </tr>
<?php
}
?>
    </table>
    
    <div id="TextBoxesGroup"></div>
    
    <div style="padding:5px 0px 5px 0px; font-weight:bold; font-size:16px; text-align:right">ผลรวม <span id="Summary"><?php echo round($sum,2); ?></span> บาท</div>
    
    <div style="float:left"><input type="button" name="btnEdit2" id="btnEdit2" value="บันทึก"></div>
    <div style="float:right"><input type="button" name="btnAdd" id="btnAdd" value="เพิ่มรายการ"><input type="button" name="btnRemove" id="btnRemove" value="ลบรายการ"></div>
    <div style="clear:both"></div>

<script type="text/javascript">

var counter = <?php echo $inub; ?>;

$('#btnAdd').click(function(){
    
    counter++;
    
    var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);

    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#E0E0E0; margin-bottom:3px">'
    + ' <tr>'
    + ' <td width="10%">'+ counter +'</td>'
    + ' <td width="30%"><input id="txtIDNO' + counter + '" name="txtIDNO' + counter + '" type="text"></td>'
    + ' <td width="30%">'
    + '<select name="cbTypePay' + counter + '" id="cbTypePay' + counter + '">'
    + '<?php
    $qry_tp = pg_query("SELECT * FROM \"TypePay\" ORDER BY \"TName\" ASC");
    while( $res_tp = pg_fetch_array($qry_tp) ){
        $tp_TypeID = $res_tp['TypeID'];
        $tp_TName = $res_tp['TName'];

    echo "<option value=\"$tp_TypeID\">$tp_TName</option>";

    }
    ?>'
    + '</select></td>'
    + ' <td width="30%"><input id="txtCusAmount' + counter + '" name="txtCusAmount' + counter + '" type="text" size="15" style="text-align:right" value="0" onkeyup="javascript:updateSum()"></td>'
    + ' </tr>'
    + ' </table>';

    newTextBoxDiv.html(table);

    newTextBoxDiv.appendTo("#TextBoxesGroup");
});

$("#btnRemove").click(function(){
    if(counter == <?php echo $inub; ?>){
        return false;
    }
    $("#TextBoxDiv" + counter).remove();
    counter--;
    updateSum();
});

$('#btnEdit2').click(function(){
    if( $('#Summary').text() != $('#txtAmtOnCheque').val() ){
        alert('ผลรวมทุกรายการต้องตรงกับยอดเงินบนเช็ค !');
        return false;
    }

    $("#btnEdit2").attr('disabled', true);
    var payment = [];
    for(var i = 1; i<=counter; i++){
        var c1 = $('#txtIDNO'+ i).val();
        var c2 = $('#cbTypePay'+ i).val();
        var c3 = $('#txtCusAmount'+ i).val();
        var c4 = $('#txtAutoID'+ i).val();

        if ( c1 == "" && i > <?php echo $inub; ?> ){
            alert('กรุณากรอกเลขที่สัญญาให้ครบถ้วน !');
            $("#btnEdit2").attr('disabled', false);
            return false;
        }
        if ( c3 == "" || c3 == 0 ){
            alert('กรุณากรอกยอดเงินให้ครบถ้วน !');
            $("#btnEdit2").attr('disabled', false);
            return false;
        }
        
        payment[i] = {autoid : c4 , idno : c1 , typepay: c2 , amount : c3};
    }
    
    $.post("edit_cheque_api.php?cmd=Edit2&ChequeNo=<?php echo $ChequeNo; ?>&PostID=<?php echo $PostID; ?>",{
        AmtOnCheque : $('#txtAmtOnCheque').val(), 
        payment : JSON.stringify(payment) 
    },
    function(data){
        if(data == "1"){
            $('#tb_no').val('');
            $('#div_show').empty();
            alert("บันทึกรายการเรียบร้อย");
            $("#btnEdit2").attr('disabled', false);
        }else{
            alert("ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง!");
            $("#btnEdit2").attr('disabled', false);
        }
    });

});

function updateSum(){
    var sum = 0;
    for(var i = 1; i<=counter; i++){
        sum += parseFloat($('#txtCusAmount'+i).val());
    }
    $('#Summary').text(sum);
}
</script>
    
<?php
}

elseif($t == 3 AND $cmd == "Show"){
    $qry = pg_query("SELECT \"Accept\" FROM \"FCheque\" WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo' LIMIT 1");
    if($res=pg_fetch_array($qry)){
        if($res['Accept'] == "f"){
            echo "<div style=\"padding: 5px 5px 5px 5px; color:#ff0000\">เช็ครายการนี้ได้ถูกยกเลิกไปแล้ว</div>";
            exit;
        }
    }
?>
    <script type="text/javascript">
    $('#btnCancel').click(function(){
        $.get('edit_cheque_api.php?cmd=Cancel&ChequeNo=<?php echo $ChequeNo; ?>&PostID=<?php echo $PostID; ?>', function(data){
            if(data == 1){
                $('#tb_no').val('');
                $('#div_show').empty();
                alert('ยกเลิกเรียบร้อยแล้ว');
            }else{
                alert('ไม่สามารถยกเลิกได้ กรุณาลองใหม่อีกครั้ง');
            }
        });
    });
    </script>

    <table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0">
    <tr>
        <td width="180"><b>ยืนยันการยกเลิก :</b></td>
        <td><input type="button" name="btnCancel" id="btnCancel" value="ยกเลิก"></td>
    </tr>
    </table>
<?php
}
?>