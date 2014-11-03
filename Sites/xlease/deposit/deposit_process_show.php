<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);
$nowdate = nowDate();//ดึง วันที่จาก server

$qry_vct=pg_query("select \"full_name\", \"CusID\", \"asset_id\", \"asset_type\", \"C_REGIS\", \"C_CARNUM\", \"gas_number\", \"P_STDATE\", \"C_YEAR\", \"C_TAX_ExpDate\",
						\"C_COLOR\", \"C_CARNAME\", \"dp_balance\", \"P_TOTAL\", \"RadioID\", \"P_MONTH\", \"P_VAT\", \"car_regis\"
					FROM \"VContact\" WHERE \"IDNO\" = '$idno'");
if($res_vct=pg_fetch_array($qry_vct)){
    $full_name=$res_vct["full_name"];
    $CusID=$res_vct["CusID"];
    $asset_id=$res_vct["asset_id"];
    $asset_type=$res_vct["asset_type"];
    if($asset_type == 1){
        $regis = $res_vct["C_REGIS"];
        $r_number="<b>เลขตัวถัง</b> ".$res_vct["C_CARNUM"];
    }else{
        $regis = $res_vct["car_regis"];
        $r_number="<b>เลขถังแก๊ส</b> ".$res_vct["gas_number"];
    }
    $P_STDATE=$res_vct["P_STDATE"];
    
    $C_YEAR=$res_vct["C_YEAR"];
    $C_TAX_ExpDate = $res_vct["C_TAX_ExpDate"]; 
    $C_COLOR = $res_vct["C_COLOR"];
    $C_CARNAME = $res_vct["C_CARNAME"];
    $dp_balance = $res_vct["dp_balance"];
    $P_TOTAL = $res_vct["P_TOTAL"];
    $RadioID = $res_vct["RadioID"];
    $P_MONTH = $res_vct["P_MONTH"];
    $P_VAT = $res_vct["P_VAT"];
    $P_SUM = $P_MONTH+$P_VAT;
    
}

$rs=pg_query("select \"discount_close\"('$nowdate','$idno')");
$discount_close=pg_fetch_result($rs,0);
$discount_close = round($discount_close,2);

$qry_stdate=pg_query("select conversiondatetothaitext('$P_STDATE')");
$stdate_th=pg_fetch_result($qry_stdate,0);

$qry_fr=pg_query("select COUNT(\"IDNO\") AS \"count_idno\" from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ");
if($res_fr=pg_fetch_array($qry_fr)){
    $count_idno = $res_fr["count_idno"];
}
?>

<script type="text/javascript">

function windowOpen(x) {
var
myWindow=window.open(x,'windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}

var counter = 0;
var k = 0;
$(document).ready(function(){
    $("#dialog").hide();
    $('#addButton').click(function(){
        counter++;
        var newTextBoxDiv = $(document.createElement('div'))
            .attr("id", 'TextBoxDiv' + counter);
        
table = '<div style="border-style: dashed; border-width: 1px; border-color:#D0D0D0; margin-bottom:3px">'
+ ' <b>#'+ counter +'</b>&nbsp;<select name="typepayment'+ counter +'" id="typepayment'+ counter +'" onchange="JavaScript:chk134('+ counter +');chk133('+ counter +'); ">'
+ '<option value="">เลือก</option>'
    + ' <?php
        $qry_type=pg_query("select * from \"TypePay\" WHERE \"TypeID\" !=1 AND \"TypeID\" <> '299' ORDER BY \"TypeID\" ASC");
        while($res_type=pg_fetch_array($qry_type)){
            echo "<option value=$res_type[TypeID]>$res_type[TName]</option>";
        }
        ?>'
    + ' </select><span id=\"showtxtbox'+ counter +'\"></span>&nbsp;<b>ยอดเงิน</b>&nbsp;<input type="text" name="amt'+ counter +'" id="amt'+ counter +'" style="text-align:right" onkeyup="JavaScript:ChangeMoney();"> <span id=\"newidnoshow'+ counter +'\" style=\"display: none\">โอนไปเลขที่สัญญา <input type="text" name="newidno'+ counter +'" id="newidno'+ counter +'" onFocus="JavaScript:CheckCusIDNO('+ counter +');" onkeyup="JavaScript:CheckCusIDNO('+ counter +');"></span> <span id=\"alertshow'+ counter +'\" style=\"display: none\"></span> <input type=\"text\" name=\"submitchkconfirm'+ counter +'\" id=\"submitchkconfirm'+ counter +'\" value=\"0\" style=\"display: none\"><input type=\"button\" name=\"btncf'+ counter +'\" id=\"btncf'+ counter +'\" value=\"Admin ยืนยัน\" style=\"font-size:9px; display: none\" onclick="javascript:conpopup('+ counter +')">'
+ ' </div>';

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
        $('#counter').val(counter);
        ChangeMoney();
		
		var CusID_java = '<?php echo $CusID; ?>';
		var asset_id_java = '<?php echo $asset_id; ?>';
		var idno_java = '<?php echo $idno; ?>';
		$('#newidno'+counter).autocomplete({
			source: "s_idno_forTransfer.php?CusID="+CusID_java+"&asset_id="+asset_id_java+"&idno="+idno_java,
			minLength:1
		});
    });
    
    $("#removeButton").click(function(){
        if(counter==0){
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        $('#counter').val(counter);
        ChangeMoney();
    });
    
    $("#submitButton").click(function(){
        
        var num = counter;

        for(i=1; i<=num; i++){
            if( $('#typepayment'+ i).val() == "" ){
                alert('กรุณาเลือกประเภทรายการอื่นๆ !');
                return false;
            }
        }

        $("#submitButton").attr('disabled', true); // ห้ามคลิกปุ่มบันทึกซ้ำ
		
		$("#load_process").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">'); // รูปโหลด
		
        $.ajax({
			url : "deposit_process_submit.php",
			type : "post",
			data : {
						<?php for($i=1; $i<=10; $i++){ ?>
							typepayment<?php echo $i; ?>: $('#typepayment' + <?php echo $i; ?>).val(),
						<?php } ?>
						<?php for($i=1; $i<=10; $i++){ ?>
							amt<?php echo $i; ?>: $('#amt' + <?php echo $i; ?>).val(),
						<?php } ?>
						<?php for($i=1; $i<=10; $i++){ ?>
							newidno<?php echo $i; ?>: $('#newidno' + <?php echo $i; ?>).val(),
						<?php } ?>
						<?php for($i=1; $i<=10; $i++){ ?>
							submitchkconfirm<?php echo $i; ?>: $('#submitchkconfirm' + <?php echo $i; ?>).val(),
						<?php } ?>
						idno: '<?php echo $idno; ?>',
						datepicker: $('#datepicker').val(),
						countpay: $('#countpay').val(),
						divmoney: $('#divmoney').val(),
						discount: $('#discount').val(),
						old_cusid: '<?php echo $CusID; ?>',
						old_asid: '<?php echo $asset_id; ?>',
						money: $('#show_money_big').text(),
						counter: counter
					},
			dataType : "json",
			success : function(data){
				// handle http 200 responses
				if(data.success){
					//alert(data.message);
					document.location='deposit_print.php?idno=<?php echo $idno; ?>&data='+ data.message;
				}else{
					alert(data.message);
					$("#load_process").html('');
					$("#submitButton").attr('disabled', false);
				}
			},
			error : function(){
				// handle 500 or 404 responses
				alert("server call failed!! กรุณาลองใหม่อีกครั้ง");
				$("#load_process").html('');
				$("#submitButton").attr('disabled', false);
			},
			complete : function(){
				// if needed..  this will be called on both success and error http responses
				// ไม่ว่าจะ success หรือ error ส่วนนี้ก็จะทำงานเสมอ ถ้าต้องการให้ทำอะไรเสมอ ให้มาใส่ในส่วนนี้ครับ
			}
		});
    });
    
    $("#countpay").change(function(){
        var num = counter;
        var sumamount = 0;
        
        $('#divmoney').val( ($('#countpay').val()*$('#money').val()) - $('#discount').val() );
        
        for(i=1; i<=num; i++){
            sumamount += ( $('#amt'+ i).val()*1 );
        }
        sumamount += ( $('#divmoney').val()*1 );
        sumamount -= ( $('#discount').val()*1 );
        $('#show_money_big').text( sumamount );
        
        if($("#countpay").val() == <?php echo $count_idno; ?>){
            $('#discount').val('<?php echo $discount_close; ?>');
            $("#discountshow").show();
            ChangeMoney();
        }else{
            $('#discount').val(0);
            ChangeMoney();
            $("#discountshow").hide();
        }
        
    });
    
    $("#datepicker").change(function(){
        var aaaa = $("#datepicker").val();
        var brokenstring=aaaa.split("#");
        $('#show_select_money').text(brokenstring[1]);
    });
    
    $("#btn_popup").click(function(){
        $('#dialog').load('deposit_report_popup.php?idno=<?php echo $idno; ?>');
        $('#dialog').dialog({
            width: 600,
            height: 350
        });
    });
    
});

function ChangeMoney(){
    var num = counter;
    var sumamount = 0;
    
    $('#divmoney').val( ($('#countpay').val()*$('#money').val()) - $('#discount').val() );
    
    for(i=1; i<=num; i++){
        sumamount += ( $('#amt'+ i).val()*1 );
    }
    sumamount += ( $('#divmoney').val()*1 );
    $('#show_money_big').text( sumamount );
}

function chk133(id){
    var num = counter;
var datepicker1 = $('#datepicker').val().split( "#" )

    for(i=1; i<=num; i++){
        if($('#typepayment'+ id).val() == $('#typepayment'+ i).val() && i != id){
            alert('ห้ามเลือกประเภทรายการซ้ำ !');
            $('#typepayment'+ id).val('เลือก');
        }
    }
	var aa = 0;
	var bb = 0;
	for(i=1; i<=num; i++){
		var myString = '<?php $join_type1=pg_query("select join_get_join_type(1)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){ 
			if($('#typepayment'+ i).val() == mySplitResult[z]){
			aa ++;
			//alert('aa '+$('#typepayment'+ i).val());
		}
}
		var myString = '<?php $join_type1=pg_query("select join_get_join_type(2)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){ 

		if($('#typepayment'+ i).val() == mySplitResult[z]){
			bb ++;
			//alert('bb '+$('#typepayment'+ i).val());
		}
}
	}
	if( (aa>0 && bb>0) || (aa>1) || (bb>1) ){
		
                alert('ห้ามเลือกประเภทรายการ ค่าเข้าร่วมซ้ำ !');
				
//document.getElementById('typepayment'+ id).selectedIndex=0;
$('#typepayment'+ id).attr('selectedIndex', 0); 
		 return false;
 
    }
    if($('#typepayment'+ id).val() == "133"){
        $('#newidnoshow'+ id).attr("style", "display:");
    }else{
		var ck_else = 0;
		
		var myString = '<?php $join_type1=pg_query("select join_get_join_type(1)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){  
if( $("#typepayment"+ id).val() == mySplitResult[z] ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม แรกเข้า
		if(k!=1){
			ck_else =1;
        $("#amt"+ id).attr("readonly", "readonly");
		$("#amt" + id).val("");
        windowOpen('../nw/join_cal/join_cal.php?idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+datepicker1[0]+'&change_pay_type=1&page_name=deposit');
           
           $("#showtxtbox"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+datepicker1[0]+'&change_pay_type=1&page_name=deposit', function(){
            $("#showtxtbox"+ id).show();
            

            });
            
   // k=1 ;
		}else{
			 alert('ค่าเข้าร่วม สามารถเลือกได้รายการเดียวเท่านั้น!');
		
                return false;
		}
          
     
    }
}
var myString = '<?php $join_type1=pg_query("select join_get_join_type(2)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){  
if( $("#typepayment"+ id).val() == mySplitResult[z] ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม ธรรมดา
		if(k!=1){
			ck_else =1;
        $("#amt"+ id).attr("readonly", "readonly");
		$("#amt" + id).val("");
        windowOpen('../nw/join_cal/join_cal.php?idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+datepicker1[0]+'&change_pay_type=0&page_name=deposit');
               
				
				 $("#showtxtbox"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+datepicker1[0]+'&change_pay_type=0&page_name=deposit', function(){
            $("#showtxtbox"+ id).show();
            
			

            });
			//k=1 ;
				}else{
			 alert('ค่าเข้าร่วม สามารถเลือกได้รายการเดียวเท่านั้น!');
			
                return false;
			
		}
          
    
    }
}

if(ck_else ==0){
        $('#newidnoshow'+ id).attr("style", "display:none");
		$("#amt"+ id).attr("readonly", "");
}
    }
}

function CheckCusIDNO(id){
    $('#alertshow'+ id).hide();
    $('#btncf'+ id).hide();
    $('#submitchkconfirm'+ id).val('0');
            
    $.post('deposit_process_check.php',{
        cmd: 'check_cusid',
        old_cusid: '<?php echo $CusID; ?>',
        old_idno: '<?php echo $idno; ?>',
        asid: '<?php echo $asset_id; ?>',
        idno: $('#newidno'+ id).val()
    },
    function(data){
        if(data.success){
            $('#alertshow'+ id).attr("style", "display:; color:green");
            $('#alertshow'+ id).text(data.message);
            $('#alertshow'+ id).show();
        }else{
            $('#alertshow'+ id).attr("style", "display:; color:red");
            $('#alertshow'+ id).text(data.message);
            $('#alertshow'+ id).show();
			$('#btncf'+ id).show();
			if(data.status == '0')
			{
				document.getElementById('btncf'+id).disabled = true;
				document.getElementById('btncf'+id).title = 'ไม่สามารถยืนยันได้ เนื่องจากไม่มีเลขที่สัญญาอยู่ในระบบ';
			}
			else
			{
				document.getElementById('btncf'+id).disabled = false;
				document.getElementById('btncf'+id).title = '';
			}
        }
    },'json');
}

function conpopup(id){
    $('#dialog2').load('con_popup.php?id='+ id);
    $('#dialog2').dialog({
        width: 300,
        height: 150/*,
        close: function(event, ui){
            if(<?php echo $_SESSION['check_admin_confirm']; ?> == id){
                $('#alertshow'+ id).attr("style", "display:; color:green");
                $('#alertshow'+ id).text('Admin อนุมัติแล้ว');
                $('#alertshow'+ id).show();
                $('#btncf'+ id).hide();
            }else{
                alert(<?php echo $_SESSION['check_admin_confirm']; ?>);
                alert(id);
            }
        }*/
    });
}

function chk134(id){
    if( $('#typepayment'+ id).val() == 134 ){
        $('#amt'+id).attr("readonly", "readonly");
        $("#showtxtbox"+ id).load("api.php?cmd=load134&id="+ id);
        
        $.get('api.php?cmd=load134amt&idno=<?php echo $idno; ?>', function(data){
            $('#amt'+ id).val(data);
            ChangeMoney();
        });
    }else{
        $("#showtxtbox"+ id).empty();
        $('#amt'+id).attr("readonly", "");
        $('#amt'+ id).val('');
        ChangeMoney();
    }
}

function check100(id){
    var c1 = $('#txtkr'+id).val();
    if( isInt(c1) && c1>0 && c1<101 ){
        $.get('api.php?cmd=load134amt&idno=<?php echo $idno; ?>', function(data){
            var k1 = data*c1;
            $('#amt'+ id).val(k1);
            ChangeMoney();
        });
    }else{
        alert('อนุญาติให้กรอกเฉพาะตัวเลข 1 ถึง 100 เท่านั้น');
        $('#txtkr'+ id).val('');
    }
}

function isInt(x) {
    var y=parseInt(x);
    if (isNaN(y)) return false;
    return x==y && x.toString()==y.toString();
}


var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>

<style type="text/css">
.odd{
    background-color:#E0E0E0;
    font-size:13px
}
.even{
    background-color:#F0F0F0;
    font-size:13px
}
</style>
<form name="form" onsubmit="return false" >
<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">

<div style="float:left"><b>ชื่อ/สกุล</b> <?php echo "$full_name (<a href=\"#\" title=\"ดูตารางการชำระเงิน\" onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$idno','deposit_$idno','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768');\"><u>$idno</u></a>) | <b>ทะเบียน</b> $regis"; ?></div>
<div style="float:right"><input type="button" name="btn_popup" id="btn_popup" style="font-size:11px; color:blue; font-weight:bold" value="รายละเอียดใช้เงินรับฝาก"></div>
<div style="clear:both"></div>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td><b>วันทำสัญญา</b> <?php echo "$stdate_th"; ?></td>
    <td><b>RadioID</b> <?php echo "$RadioID"; ?></td>
    <td align="right"><b>ค่างวดไม่รวม VAT</b> <?php echo number_format($P_MONTH,2); ?></td>
    <td align="right"><b>Deposit Balance</b> <?php echo number_format($dp_balance,2); ?></td>
</tr>
<tr>
    <td><b>ปีรถ</b> <?php echo "$C_YEAR"; ?></td>
    <td><b>ประเภทรถ</b> <?php echo "$C_CARNAME"; ?></td>
    <td align="right"><b>VAT</b> <?php echo number_format($P_VAT,2); ?></td>
    <td align="right"><b>จำนวนงวดทั้งหมด</b> <?php echo "$P_TOTAL"; ?></td>
</tr>
<tr>
    <td><?php echo "$r_number"; ?></td>
    <td><b>สีรถ</b> <?php echo "$C_COLOR"; ?></td>
    <td align="right"><b>ค่างวดรวม VAT</b> <?php echo number_format($P_SUM,2); ?></td>
    <td align="right"><b>วันที่หมดอายุภาษี</b> <?php echo "$C_TAX_ExpDate"; ?></td>
</tr>
</table>
</div>


<?php
$sum_outstanding1 = 0;
$sum_outstanding2 = 0;

$qry_inf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $sum_outstanding1 = $res_inf["sum_outstanding"];
}

$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding2 = $res_inuf["sum_outstanding"];
}

$qry_amt=pg_query("select \"CusAmt\",\"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDNO\"='$idno' ");
$nub_amt = pg_num_rows($qry_amt);

if($nub_amt > 0 || $sum_outstanding1 > 0 || $sum_outstanding2 > 0){
    echo '<div style="background-color:#FFC0C0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">';
    echo "<b>ยอดค้าง</b><br />";
    if($sum_outstanding1 > 0){
        echo "ประกันภัยภาคบังคับ (พรบ.) : ".number_format($sum_outstanding1,2)."<br />";
    }
    if($sum_outstanding2 > 0){
        echo "ประกันภัยภาคสมัครใจ : ".number_format($sum_outstanding2,2)."<br />";
    }
    
    while($res_amt=pg_fetch_array($qry_amt)){
        $CusAmt = $res_amt["CusAmt"];
        $TypeDep = $res_amt["TypeDep"];
        
        if($CusAmt > 0){
            $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
            if($res_nn=pg_fetch_array($qry_nn)){
                $TName = $res_nn["TName"];
            }
            echo "$TName : ".number_format($CusAmt,2)."<br />";
        }
    }
    echo "</div>";
}

$qry_Fp=pg_query("select \"P_LAWERFEE\",\"P_ACCLOSE\",\"P_StopVat\" from \"Fp\" where \"IDNO\" ='$idno'");
if( $res_Fp=pg_fetch_array($qry_Fp) ){
    $s_LAWERFEE = $res_Fp["P_LAWERFEE"];
    $s_ACCLOSE = $res_Fp["P_ACCLOSE"];
    $s_StopVat = $res_Fp["P_StopVat"];
}

if($s_LAWERFEE == 't' || $s_ACCLOSE == 't' || $s_StopVat == 't'){
    echo '<div style="background-color:#FFC0C0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:center">';
    if($s_LAWERFEE == 't'){
        echo '<img src="picflash1.gif" border="0" width="120" height="50">';
    }
    if($s_ACCLOSE == 't'){
        echo '<img src="picflash2.gif" border="0" width="120" height="50">';
    }
    if($s_StopVat == 't'){
        echo '<img src="picflash3.gif" border="0" width="120" height="50">';
    }
    echo "</div>";
}
?>


<table width="100%" cellpadding="3" cellspacing="1" border="0" bgcolor="#000000">
<tr style="font-weight:bold" align="center" bgcolor="#ACACAC">
    <td>วันที่</td>
    <td>ยอดเงิน</td>
    <td>ใช้ไป</td>
    <td>คงเหลือ</td>
    <td>เลขที่ใบรับ</td>
</tr>
<?php
$qry_remain=pg_query("select \"IDNO\", \"O_DATE\", \"O_RECEIPT\", \"O_MONEY\", \"remain\"
					FROM \"VDepositRemain\" WHERE \"IDNO\" = '$idno' ORDER BY \"O_DATE\" ASC");
$row_remain = pg_num_rows($qry_remain);
while($res_remain=pg_fetch_array($qry_remain)){
    $IDNO=$res_remain["IDNO"];
    $O_DATE=$res_remain["O_DATE"];
    $O_RECEIPT=$res_remain["O_RECEIPT"];
    $O_MONEY=$res_remain["O_MONEY"];
    $remain=$res_remain["remain"];
    
    if($remain == "" || empty($remain)){
        $used = 0;
        $balance = $O_MONEY;
    }else{
        $used = $O_MONEY-$remain;
        $balance = $remain;
    }
    
    $sum_balance+=$balance;
    
    $arr_date[] = $O_DATE;
    $arr_money[] = $sum_balance;
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
    
?>
    <td align="center"><?php echo "$O_DATE"; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="right"><?php echo number_format($used,2); ?></td>
    <td align="right"><?php echo number_format($balance,2); ?></td>
    <td align="center"><?php echo "$O_RECEIPT"; ?></td>
</tr>
<?php
}
?>
<tr bgcolor="#ACACAC">
    <td colspan="4" align="right"><b><?php echo number_format($sum_balance,2); ?></b><input type="hidden" name="maxmoney" id="maxmoney" value="<?php echo $sum_balance; ?>"></td>
    <td></td>
</tr>
</table>

<?php
if($row_remain > 0){
?>

<div style="background-color:#F0F0F0; margin-top: 3px; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
    <td width="15%"><b>เลือกวันที่ : </b></td>
    <td width="85%">
<select name="datepicker" id="datepicker">
<?php
$a = 0;
foreach($arr_date AS $d){
    if($a==0){
        $tmp_select_money = "$arr_money[$a]";
    }
    echo "<option value=\"$d#$arr_money[$a]\">$d</option>";
    $a++;
}

$a-=1;
?>
<option value="<?php echo "$nowdate#$arr_money[$a]"; ?>"><?php echo $nowdate; ?></option>
</select>
    </td>
</tr>
<tr>
    <td><b>ชำระค่างวด :</b></td>
    <td>ยอดค่างวด : <input type="text" id="money" name="money" value="<?php echo $P_SUM; ?>" size="10" style="background-color:#F0F0F0; text-align:right; border:None" readonly> บาท.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
เลือกจำนวนงวด :&nbsp;
<?php
$adata = "<select name=\"countpay\" id=\"countpay\">";
for($i=0; $i<=$count_idno; $i++){
    $adata .= "<option value=$i>$i</option>";
}
$adata .= "</select>";
echo $adata;
?>
<span id="discountshow" style="display: none"> ส่วนลด <input type="text" name="discount" id="discount" size="10" value="0" style="text-align:right" onkeyup="JavaScript:ChangeMoney();"> บาท.</span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รวมเงิน : <input type="text" id="divmoney" name="divmoney" size="10" value="0"  style="background-color:#F0F0F0; text-align:right; border:None " readonly> บาท.
    </td>
</tr>
</table>

<div id="TextBoxesGroup"></div>

<div align="right" style="font-size: 16px; color:red; font-weight:bold">สามารถใช้ยอดเงินได้ <span id="show_select_money"><?php echo $tmp_select_money; ?></span> บาท</div>

<div align="right" style="font-size: 16px; font-weight:bold">รวมยอดเงินที่ใช้ <span id="show_money_big">0</span> บาท</div>

</div>

<input type="hidden" id="counter" name="counter" value="0">

<div name="load_process" id="load_process"></div>
<div style="float:left"><input type="button" value="บันทึกข้อมูล" id="submitButton"></div>
<div style="float:right">
<input type="button" value="+ เพิ่มค่าใช้จ่ายอื่นๆ" id="addButton">
<input type="button" value="- ลบรายการ" id="removeButton">
</div>
<div style="clear:both"></div>

<?php
}
?>

<div id="dialog" title="รายละเอียดใช้เงินรับฝาก <?php echo $idno; ?>">
</div>

<div id="dialog2" title="Admin ยืนยัน">
</div></form>