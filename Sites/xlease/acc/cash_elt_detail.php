<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
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

<?php
$select = pg_query("SELECT * FROM \"TranPay\" WHERE \"PostID\"='$id'");
if($res=pg_fetch_array($select)){
    $tr_date = $res['tr_date'];
    $tr_time = $res['tr_time'];
    $pay_bank_branch = $res['pay_bank_branch'];
    $amt = $res['amt'];
    $bank_no = $res['bank_no'];
}
?>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function windowOpen(x) {
var
myWindow=window.open(x,'windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
</script>
<script type="text/javascript">

var counter = 0;
var myarray;
var mystring;
var k = 0;
$(document).ready(function(){

    $("#idno").autocomplete({
        source: "s_idno.php",
        minLength:1,
        close : function(event, ui){
            $.post('process_check_mv.php',{
                idno: $('#idno').val()
            },
            function(data){
                if(data.success){
                    $('#money').val(data.message);
                    $('#countpay').attr('disabled', false);

                    mystring = $('#idno').val();
                    myarray = mystring.split("#");
                    $("#showbtn").empty();
                    $('#showbtn').append("<input type=\"button\" name=\"btn1\" id=\"btn1\" value=\"ตาราง "+ myarray[0] +"\" onclick=\"window.open('../post/frm_viewcuspayment.php?idno_names='+ myarray[0] +'',''+ myarray[0] +'','width=1000,height=600');\">");
                }else{
                    alert(data.message);
                    $('#countpay').attr('disabled', true);
                }
            },'json');
            
            $.post('process_check_countdue.php',{
                idno: $('#idno').val()
            },
            function(dt){
                if(dt.success){
                    $("#a1").empty();
                    $('#a1').append(dt.message);
                }else{
                    alert(dt.message);
                }
            },'json');
        },open : function(event, ui){
            $('#countpay').attr('disabled', true);
            $('#countpay').val(0);
            $('#divmoney').val(0);
            $('#money').val(0);
            $('#btn1').attr('disabled', true);
            $('#btn1').attr("value", "ตารางการชำระเงิน");
        }
    });


 
    $('#addButton').click(function(){
        counter++;
        var newTextBoxDiv = $(document.createElement('div'))
            .attr("id", 'TextBoxDiv' + counter);
        
table = '<div style="border-style: dashed; border-width: 1px; border-color:#E0E0E0; margin-bottom:3px">'
+ ' <b>#'+ counter +'</b>&nbsp;<select name="typepayment'+ counter +'" id="typepayment'+ counter +'" onchange="JavaScript:chk133('+ counter +');">'
    + ' <?php
        $qry_type=pg_query("select * from \"TypePay\" WHERE (\"TypeID\" !=1) and \"TypeID\" != '133' ");
        while($res_type=pg_fetch_array($qry_type)){
            echo "<option value=$res_type[TypeID]>$res_type[TName]</option>";
        }
        ?>'
    + ' </select>&nbsp;<span id="type_detail' + counter + '"></span>&nbsp;<b>ยอดเงิน</b>&nbsp;<input type="text" name="amt'+ counter +'" id="amt'+ counter +'" style="text-align:right"> <span id=\"newidnoshow'+ counter +'\" style=\"display: none\">โอนไปเลขที่สัญญา <input type="text" name="newidno'+ counter +'" id="newidno'+ counter +'"></span>'
+ ' </div>';

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");
        $('#counter').val(counter);
    });
    
    $("#removeButton").click(function(){
        if(counter==0){
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        $('#counter').val(counter);
    });
    
});

function ChangeCount(id){
    if($('#money').val() == ""){
        $('#countpay').val(0)
        alert('ผิดผลาด ไม่พบยอดเงิน !');
    }else{
		$('#divmoney').val($('#countpay').val()*$('#money').val());
    }
}
function ch_idno(){
	for(i=1; i<=counter; i++){
		
		$("#TextBoxDiv" + i).remove();

	}
	counter=0;
}

function chk133(id){
	
	var idno1 = $('#idno').val().split( "#" )
	
	
	var aa = 0;
	var bb = 0;
	for(i=1; i<=counter; i++){
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
        windowOpen('../nw/join_cal/join_cal.php?idno='+ idno1[0]+ '&inputName=amt'+ id + '&pay_date=<?php echo $tr_date ?>&change_pay_type=1');
           
           $("#type_detail"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno='+ idno1[0]+ '&inputName=amt'+ id + '&pay_date=<?php echo $tr_date ?>&change_pay_type=1', function(){
            $("#type_detail"+ id).show();
            

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
        windowOpen('../nw/join_cal/join_cal.php?idno='+ idno1[0]+ '&inputName=amt'+ id + '&pay_date=<?php echo $tr_date ?>&change_pay_type=0');
               
				
				 $("#type_detail"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno='+ idno1[0]+ '&inputName=amt'+ id + '&pay_date=<?php echo $tr_date ?>&change_pay_type=0', function(){
            $("#type_detail"+ id).show();
            
			

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


    function chkValue(){
        
        $('#submitButton').attr("disabled", true);
        
        if($('#idno').val() == ""){
            alert('- ไม่พบ IDNO');
            $('#idno').focus();
            $('#submitButton').attr("disabled", false);
            return false;
        }

        var num = counter;
        var al;
        var n;
        var sbmt = 0;
        var sumamount = 0;
        var sumall = 0;
        for(i=1; i<=num; i++){
            
            sumamount = (sumamount*1) + ($('#amt'+ i).val()*1);
            
            n = 0;
            al = "";
            al = "ผิดผลาด ใน #"+ i +" ดังนี้\n";
            if($('#typepayment'+ i).val() == ""){
                al += "- ประเภท\n";
                n++;
            }
            if($('#amt'+ i).val() == ""){
                al += "- ยอดเงิน\n";
                n++;
            }
            if($('#typepayment'+ i).val() == "133"){
                if($('#newidno'+ i).val() == ""){
                    al += "- โอนไปเลขที่สัญญา\n";
                    n++;
                }
            }
            
            if(n > 0){
                sbmt++;
                alert(al);
            }

        }
        sumall = sumamount+($('#divmoney').val()*1);
        if($('#amount').val() != sumall){
            alert('ยอดเงินไม่ถูกต้อง กรุณาตรวจสอบยอดเงินอีกครั้ง');
            $('#submitButton').attr("disabled", false);
            return false;
        }else if(sbmt > 0){
            $('#submitButton').attr("disabled", false);
            return false;
        }else{
            document.f_list.submit();
        }
    }

</script>    

</head>
<body>

<?php
// ตรวจสอบก่อนว่า สามารถทำรายการได้หรือไม่
$qryDel = pg_query("select a.* from \"TranPay_Request_Cancel\" a, \"TranPay\" b where a.\"id_tranpay\" = b.\"id_tranpay\" and a.\"Approved\" = '9' and b.\"PostID\" = '$id'
					union
					select a.* from \"TranPay_Request_Cancel\" a, \"TranPay_deleted\" b where a.\"id_tranpay\" = b.\"id_tranpay_deleted\" and a.\"Approved\" = '1' and b.\"PostID\" = '$id' ");
$rowDel = pg_num_rows($qryDel);
if($rowDel > 0)
{ // ถ้ามีการขอยกเลิก หรือยกเลิกไปแล้ว
?>
	<center>
		<h2><font color="#FF0000">ไม่สามารถทำรายการ กรุณาตรวจสอบ!!</font></h2>
		<form name="frmNo" method="post" action="cash_elt.php">
			<input type="submit" value="ตกลง">
		</form>
	</center>
<?php
}
else
{
?>
	<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>


	<div style="float:left"><input type="button" value="ย้อนกลับ" class="ui-button" onclick="window.location='cash_elt.php'"></div>
	<div style="float:right">&nbsp;</div>
	<div style="clear:both"></div>        

	<fieldset><legend><B>ตัดรายการเงินที่ไม่ใช่ Bill Payment</B></legend>

	<div class="ui-widget">



	<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
	<tr bgcolor="#CEE7FF"><td colspan="4"><b>รายการ PostID : <?php echo $id; ?></b></td></tr>
	<tr>
		<td width="20%"><b>จำนวนเงิน </b></td>
		<td width="30%"><?php echo number_format($amt,2); ?> บาท.</td>
		<td width="20%"><b>วันเวลา/ที่โอน</b></td>
		<td width="30%"><?php echo "$tr_date $tr_time"; ?></td>
	</tr>
	<tr>
		<td><b>รหัสสาขาที่โอน </b></td>
		<td><?php echo $pay_bank_branch; ?></td>
		<td><b>ธนาคาร</b></td>
		<td>
	<?php
	$qry_bank=pg_query("select \"bankname\" from \"bankofcompany\" WHERE \"bankno\"='$bank_no' ");
	if($res_bank=pg_fetch_array($qry_bank)){
		$bankname = $res_bank["bankname"];
		echo "$bankname";
	}
	?>
		</td>
	</tr>
	</table>

	<form name="f_list" id="f_list" action="cash_elt_detail_insert.php" method="post">


	<div style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
	<table width="100%" cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td width="30%"><b>IDNO, ชื่อ/สกุล, Ref1, Ref2 :</b></td>
		<td width="70%"><input type="text" name="idno" id="idno" size="65" onchange="ch_idno()"><span id="showbtn"><input type="button" name="btn1" id="btn1" value="ตารางการชำระเงิน" disabled></span></td>
	</tr>
	<tr>
		<td><b>ชำระค่างวด :</b></td>
		<td>ยอดค่างวด : <input type="text" id="money" name="money" value="0" size="10" style="text-align:right; border:None" readonly> บาท.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เลือกจำนวนงวด : <span id="a1"><select name="countpay" id="countpay" onchange="JavaScript:ChangeCount();" disabled><option value=0>0</option></select></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รวมเงิน : <input type="text" id="divmoney" name="divmoney" size="10" value="0"  style="text-align:right; border:None" readonly> บาท.
		</td>
	</tr>
	</table>
	</div>

	<div id='TextBoxesGroup'></div>

	<div style="float:left"><input type="button" value="บันทึกข้อมูล" id="submitButton" onclick="JavaScript:chkValue();"></div>
	<div style="float:right"><input type="button" value="+ เพิ่มค่าใช้จ่ายอื่นๆ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></div>
	<div style="clear:both"></div>
	<input type="hidden" id="amount" name="amount" value="<?php echo $amt; ?>">
	<input type="hidden" id="counter" name="counter" value="0">
	<input type="hidden" id="cid" name="cid" value="<?php echo "$id"; ?>">
	</form>

	</div>

	 </fieldset>

			</td>
		</tr>
	</table>
<?php
}
?>
</body>
</html>