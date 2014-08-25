<?php
include("../config/config.php");

$cusid = pg_escape_string($_GET['cusid']);
$default_idno = pg_escape_string($_GET['idno']);

$qry_name=pg_query("select \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\" from \"Fa1\" WHERE \"CusID\" = '$cusid'");
if($res_name=pg_fetch_array($qry_name)){
    $A_FIRNAME=trim($res_name["A_FIRNAME"]);
    $A_NAME=trim($res_name["A_NAME"]);
    $A_SIRNAME=trim($res_name["A_SIRNAME"]);
    $fu_name = "$A_FIRNAME $A_NAME $A_SIRNAME";
}

// เก็บชื่อหลัก
$qry_mainFullname = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$cusid' ");
$mainFullname = pg_fetch_result($qry_mainFullname,0);
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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

</head>
<body>

<input type="hidden" name="mainFullname" id="mainFullname" value="<?php echo $mainFullname; ?>">

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value=" Back " class="ui-button" onclick="window.location='select.php';"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>จ่ายเงินสด</B></legend>
<form name="form">
<div class="ui-widget">

<div style="font-weight:bold; margin:10px 0px 10px 0px">ลูกค้า : <span style="color:#0000FF"><?php echo "$cusid $fu_name"; ?></span></div>

<div id="listbox">
<?php
$gg = 0;
$qry_fp=pg_query("select \"IDNO\", \"P_MONTH\", \"P_VAT\" from \"Fp\" WHERE \"CusID\" = '$cusid' ORDER BY \"IDNO\"");
while($res_fp=pg_fetch_array($qry_fp))
{
    $gg++;
    $IDNO=$res_fp["IDNO"];
    $P_MONTH=$res_fp["P_MONTH"];
    $P_VAT=$res_fp["P_VAT"];
    
    $arr_idno[] = $IDNO;
    
    $qry_un=pg_query("select \"C_CARNAME\", \"C_REGIS\" from \"UNContact\" WHERE \"IDNO\" = '$IDNO'");
    if($res_un=pg_fetch_array($qry_un)){
        $C_CARNAME=$res_un["C_CARNAME"];
        $C_REGIS=$res_un["C_REGIS"];
    }
    
    $qry_vcus=pg_query("select COUNT(\"IDNO\") AS cidno from \"VCusPayment\" WHERE \"IDNO\" = '$IDNO' AND \"R_Date\" is null");
    if($res_vcus=pg_fetch_array($qry_vcus)){
        $cidno=$res_vcus["cidno"];
        
    }
	
	$qry_VContact=pg_query("select \"dp_balance\" from \"VContact\" WHERE \"IDNO\"='$IDNO'");
	$res_VContact=pg_fetch_array($qry_VContact);
    $dp_balance = $res_VContact["dp_balance"]; // เงินรับฝาก
	
	// หาชื่อผู้เข้าร่วมคนล่าสุด
	$qry_lastCus = pg_query("select \"cusid\" from \"VJoinMain\" where \"idno\" = '$IDNO' ");
	$lastCusID = pg_fetch_result($qry_lastCus,0);
	
	// หาชื่อเต็ม
	$qry_lastFullname = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$lastCusID' ");
	$lastFullname = pg_fetch_result($qry_lastFullname,0);

?>
	<input type="hidden" name="<?php echo $IDNO; ?>" id="<?php echo $IDNO; ?>" value="<?php echo $lastFullname; ?>">
	
<div class="greenbox" id="<?php echo "DIV$IDNO"; ?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="15%">IDNO&nbsp;<a href="#" onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_sdasdsadsa"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางการชำระเงิน"><b><?php echo $IDNO; ?></b></a></td>
    <td width="20%"><?php echo $C_CARNAME; ?></td>
    <td width="10%"><?php echo $C_REGIS; ?></td>
    <td width="20%">ค่างวด (รวมภาษี)&nbsp;&nbsp;<?php echo number_format($P_MONTH+$P_VAT,2); ?></td>
    <td width="15%">เหลือ &nbsp;&nbsp;<?php echo $cidno; ?>&nbsp;งวด</td>
    <td width="20%">เงินรับฝาก&nbsp;&nbsp;<?php echo number_format($dp_balance,2); ?><input type="hidden" name="dbalance<?php echo $gg; ?>" id="dbalance<?php echo $gg; ?>" value="<?php echo $dp_balance; ?>"></td>
</tr>
</table>
</div>
<?php
}
?>
</div>

<?php
if($gg == 0){
    echo "<div class=\"yellowbox\" align=center>- ไม่พบข้อมูล -</div>";
}else{
?>

<br />

<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#D8D8D8; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr style="font-weight:bold">
    <td width="10%">รายการ</td>
    <td width="20%">สัญญาเลขที่</td>
    <td width="20%">รายการจ่ายเงิน</td>
    <td width="30%">&nbsp;</td>
    <td width="20%">จำนวนเงิน</td>
</tr>
</table>

<div id='TextBoxesGroup'>
    <div id="TextBoxDiv1">

		<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
			<tr>
				<td width="10%">1</td>
				<td width="20%">
					<select id="cb_idno1" name="cb_idno1" onchange="javascript:checkIDNO(1)">
						<?php
						$b = 0;
						foreach ($arr_idno as $a)
						{
							$b++;
							if($a != $default_idno){
								echo "<option value=\"$a\">$a</option>";
							}else{
								echo "<option value=\"$a\" selected>$a</option>";
							}
						}
						?>
					</select>
				</td>
				<td width="20%">
					<select id="cb_typepay1" name="cb_typepay1" onchange="javascript:checkType(1)">
						<option value="">เลือก</option>
						<?php
						$qry_fp=pg_query("select \"TypeID\", \"TName\" from \"TypePay\" WHERE \"TypeID\" <> '299' ORDER BY \"TName\" ASC");
						while($res_fp=pg_fetch_array($qry_fp)){
							$TypeID=$res_fp["TypeID"];
							$TName=$res_fp["TName"];
							echo "<option value=\"$TypeID\">$TName</option>";
						}
						?>
					</select>
				</td>
				<td width="30%">
					<span id="type_detail1"></span>
					<span id="discount1"></span>
				</td>
				<td width="20%"><input id="e_amount1" name="e_amount1" type="text" style="text-align:right" onkeyup="javascript:updateSummary();"></td>
			</tr>
			<tr>
				<td colspan="5" align="left"><span id="lastCus1"></span></td>
			</tr>
		</table>

    </div>
</div>

<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FFD5D5; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
<tr style="font-weight:bold">
    <td width="80%" colspan="4" align="right">รวมเงิน</td>
    <td width="20%" align="right"><span id="divsummery">0</span></td>
</tr>
</table>

<div style="margin-top:20px">
<div style="float:left"><input type="button" value="บันทึกข้อมูล" id="submitButton"></div>
<div style="float:right"><input type="button" value="+ เพิ่มรายการ" id="addButton"><input type="button" value="- ลบรายการ" id="removeButton"></div>
<div style="clear:both"></div>
</div>

<?php } ?>

</div>
</form>
 </fieldset>

        </td>
    </tr>
</table>

<script type="text/javascript">
var counter = 1;

var k=0 ; // เช็คค่าเข้าร่วม ซ้ำกัน
$(document).ready(function(){

    $('#addButton').click(function(){
    counter++;
    console.log(counter);
    var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);

    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
    + ' <tr>'
    + ' <td width="10%">'+ counter +'</td>'
    + ' <td width="20%">'
    + ' <select id="cb_idno' + counter + '" name="cb_idno' + counter + '" onchange=\"javascript:checkIDNO('+counter+')\">'
    + ' <?php
    foreach($arr_idno as $a){
        if($a != $default_idno){
            echo "<option value=\"$a\">$a</option>";
        }else{
            echo "<option value=\"$a\" selected>$a</option>";
        }
    }
    ?>'
    + ' </select>'
    + ' </td>'
    + ' <td width="20%">'
    + ' <select id="cb_typepay' + counter + '" name="cb_typepay' + counter + '" onchange=\"javascript:checkType('+counter+')\">'
    + '<option value="">เลือก</option>'
    + ' <?php
    $qry_fp=pg_query("select \"TypeID\", \"TName\" from \"TypePay\" WHERE \"TypeID\" <> '299' ORDER BY \"TName\" ASC");
    while($res_fp=pg_fetch_array($qry_fp)){
        $TypeID=$res_fp["TypeID"];
        $TName=$res_fp["TName"];
        echo "<option value=$TypeID>$TName</option>";
    }
    ?>'
    + ' </select>'
    + ' </td>'
    + ' <td width="30%"><span id="type_detail' + counter + '"></span><span id="discount' + counter + '"></span></td>'
    + ' <td width="20%"><input id="e_amount' + counter + '" name="e_amount' + counter + '" type="text" style="text-align:right" onkeyup="javascript:updateSummary()"></td>'
    + ' </tr>'
	+ ' <tr>'
	+ ' <td colspan="5" align="left"><span id="lastCus' + counter + '"></span></td>'
	+ ' </tr>'
    + ' </table>';

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");

		if(document.getElementById($('#cb_idno'+ counter).val()).value != '')
		{
			$("#lastCus"+counter).text("ชื่อผู้เข้าร่วมคนล่าสุด(ที่จะแสดงในใบเสร็จ) : "+document.getElementById($('#cb_idno'+ counter).val()).value);
		}
		else
		{
			$("#lastCus"+counter).text("ชื่อผู้เข้าร่วมคนล่าสุด(ที่จะแสดงในใบเสร็จ) : "+document.getElementById("mainFullname").value);
		}
    });
    
    $("#removeButton").click(function(){
        if(counter==1){
            alert("ห้ามลบ !!!");
			
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
        updateSummary();
    });
    
    $("#submitButton").click(function(){

        for(i=1; i<=counter; i++){
            if( $('#cb_typepay'+ i).val() == "" ){
                alert('กรุณาเลือกประเภทรายการจ่ายเงิน !');
                $("#submitButton").attr('disabled', false);
                return false;
            }
        }
        
        $("#submitButton").attr('disabled', true);
        var payment = [];
        for( i=1; i<=counter; i++ ){
            var c1 = $('#e_amount'+ i).val();
            if ( isNaN(c1) || c1 == "" || c1 == 0){
                alert('ข้อมูลจำนวนเงินไม่ถูกต้อง'+i);
                $('#e_amount'+ i).focus();
                $("#submitButton").attr('disabled', false);
                return false;
            }
            payment[i] = {idno : $("#cb_idno"+ i).val() , typepay: $("#cb_typepay"+ i).val() , amount : $("#e_amount"+ i).val()};
        }
        
        $.post("api.php",{
            cmd : "save" , 
            cusid : '<?php echo "$cusid"; ?>', 
            payment : JSON.stringify(payment) 
        },
        function(data){
            if(data == "1"){
                alert("บันทึกรายการเรียบร้อย");
                location.href = "select.php";
                $("#submitButton").attr('disabled', false);
            }else{
                alert("ผิดผลาด ไม่สามารถบันทึกได้!");
                $("#submitButton").attr('disabled', false);
            }
        });

    });
    
});

function checkSelectCB(id){
    
    $("#type_detail"+ id).hide();
    $("#discount"+ id).hide();

    for(i=1; i<=counter; i++){
        if($('#cb_typepay'+ id).val() == $('#cb_typepay'+ i).val() && i != id){
            if($('#cb_idno'+ id).val() == $('#cb_idno'+ i).val()){
                alert('ห้ามเลือกประเภทรายการซ้ำ !');
                $('#cb_typepay'+ id).val('เลือก');
                return false;
            }
        }
    }


    
		var aa = 0;
	var bb = 0;
	for(i=1; i<=counter; i++){
		var myString = '<?php $join_type1=pg_query("select join_get_join_type(1)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){     

if($('#cb_typepay'+ i).val() == mySplitResult[z]){
			if($('#cb_idno'+ id).val() == $('#cb_idno'+ i).val()){
			aa ++;
			}
			//alert('aa '+$('#typepayment'+ i).val());
		}
}
		var myString = '<?php $join_type1=pg_query("select join_get_join_type(2)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
	for(z = 0; z < mySplitResult.length; z++){  
		if($('#cb_typepay'+ i).val() == mySplitResult[z]){
			if($('#cb_idno'+ id).val() == $('#cb_idno'+ i).val()){
			bb ++;
			}
			//alert('bb '+$('#typepayment'+ i).val());
		}
		

}



			
	}
	if( (aa>0 && bb>0) || (aa>1) || (bb>1) ){
		
                alert('ห้ามเลือกประเภทรายการ ค่าเข้าร่วมซ้ำ !');
				
//document.getElementById('typepayment'+ id).selectedIndex=0;
$('#cb_typepay'+ id).attr('selectedIndex', 0); 
		 return false;
 
    }
	
	

	
	
    if( $("#cb_typepay"+ id).val() == "1" ){ //ตรวจสอบถ้าเป็นค่างวด
        $.get('api.php?cmd=checkacclose&idno='+ $("#cb_idno"+ id).val(), function(data){
            if(data == 't'){
                alert('เลขที่สัญญานี้ ไม่สามารถทำรายการจ่ายเงิน ค่างวด ได้ !');
                $("#cb_typepay"+ id).val('เลือก');
                return false;
            }else{
                $("#e_amount"+ id).attr("readonly", "readonly");
                //เรียกลิสต์ค่างวดมาแสดง
                $("#type_detail"+ id).load("api.php?cmd=loaddue&id="+ id +"&idno="+ $("#cb_idno"+ id).val());
                $("#type_detail"+ id).show();
                $("#e_amount"+ id).val(0);
            }
        });
    }else if( $("#cb_typepay"+ id).val() == "134" ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม
        $("#e_amount"+ id).attr("readonly", "readonly");
        //เรียก txtBox ใส่ค่าเข้ารว่มมาแสดง
        $("#type_detail"+ id).load("api.php?cmd=load134&id="+ id, function(){
            $("#type_detail"+ id).show();
            
            $.get('api.php?cmd=load134amt&idno='+ $("#cb_idno"+ id).val(), function(data){
                var s1 = parseFloat( $('#txtkr'+id).val() );
                var sm = s1*data;
                $('#e_amount'+ id).val( sm );
                updateSummary();
            });
            
        });
    }else{
		var ck_else = 0;
		
			var myString = '<?php $join_type1=pg_query("select join_get_join_type(1)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  

	for(z = 0; z < mySplitResult.length; z++){  
	
	if( $("#cb_typepay"+ id).val() == mySplitResult[z] ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม แรกเข้า
		if(k!=1){
			  ck_else =1;
        $("#e_amount"+ id).attr("readonly", "readonly");
$("#e_amount"+ id).val("");
        windowOpen('../nw/join_cal/join_cal.php?idno='+ $("#cb_idno"+ id).val()+ '&inputName=e_amount'+ id + '&change_pay_type=1&page_name=detail');
                updateSummary();
           $("#type_detail"+ id).load("api.php?cmd=load_join1&id="+ id+'&idno='+ $("#cb_idno"+ id).val()+ '&inputName=e_amount'+ id + '&change_pay_type=1&page_name=detail', function(){
            $("#type_detail"+ id).show();
          

            });
            
   // k=1 ;
		}else{
			 alert('ค่าเข้าร่วม สามารถเลือกได้รายการเดียวเท่านั้น!');
			$('#cb_typepay'+ id).val('เลือก');
                return false;
		}
		
	}
	}
	
				var myString = '<?php $join_type1=pg_query("select join_get_join_type(2)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  

	for(z = 0; z < mySplitResult.length; z++){  
     
	 
    if( $("#cb_typepay"+ id).val() == mySplitResult[z] ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม ธรรมดา
		if(k!=1){
			ck_else =1;
        $("#e_amount"+ id).attr("readonly", "readonly");
$("#e_amount"+ id).val("");
        windowOpen('../nw/join_cal/join_cal.php?idno='+ $("#cb_idno"+ id).val()+ '&inputName=e_amount'+ id + '&change_pay_type=0&page_name=detail');
                updateSummary();
				
				 $("#type_detail"+ id).load("api.php?cmd=load_join1&id="+ id+'&idno='+ $("#cb_idno"+ id).val()+ '&inputName=e_amount'+ id + '&change_pay_type=0&page_name=detail', function(){
            $("#type_detail"+ id).show();
            
			

            });
			//k=1 ;
				}else{
			 alert('ค่าเข้าร่วม สามารถเลือกได้รายการเดียวเท่านั้น!');
			 $('#cb_typepay'+ id).val('เลือก');
                return false;
			
		}
          
    
    }
	}
		if(ck_else ==0){
        $("#e_amount"+ id).val(0);
        $("#e_amount"+ id).attr("readonly", "");
		}
		
    }
}

function amtDue(id,due,idno){
    if(due == $('#cbDue'+id).val()){
        $("#discount"+ id).show();
        $("#discount"+ id).load("api.php?cmd=loaddiscount&id="+ id +"&idno="+ idno, function(){
            $.get('api.php?cmd=loaddueamt&idno='+ $("#cb_idno"+ id).val(), function(data){
                var s1 = parseFloat( $('#cbDue'+id).val() );
                var s2 = parseFloat( $('#txtDiscount'+id).val() );
                var sm = (s1*data)-s2;
                $('#e_amount'+ id).val( sm );
                updateSummary();
            });
        });
    }else{
        $("#discount"+ id).hide();
        $.get('api.php?cmd=loaddueamt&idno='+ $("#cb_idno"+ id).val(), function(data){
            var s1 = parseFloat( $('#cbDue'+id).val() );
            var sm = s1*data;
            $('#e_amount'+ id).val( sm );
            updateSummary();
        });
    }
}

function updateAmount(id){
   $.get('api.php?cmd=loaddueamt&idno='+ $("#cb_idno"+ id).val(), function(data){
        var s1 = parseFloat( $('#cbDue'+id).val() );
        var s2 = parseFloat( $('#txtDiscount'+id).val() );
        var sm = (s1*data)-s2;
        $('#e_amount'+ id).val( sm );
        updateSummary();
    });
}

function updateAmount134(id){
    var c1 = $('#txtkr'+id).val();
    if( isInt(c1) && c1>0 && c1<101 ){
        $.get('api.php?cmd=load134amt&idno='+ $("#cb_idno"+ id).val(), function(data){
            var s1 = parseFloat( c1 );
            var sm = s1*data;
            $('#e_amount'+ id).val( sm );
            updateSummary();
        });   
    }else{
        alert('อนุญาติให้กรอกเฉพาะตัวเลข 1 ถึง 100 เท่านั้น');
        $('#txtkr'+ id).val('');
    }
}

function updateSummary(){
    var sss = 0;
    for( i=1; i<=counter; i++ ){
        var c1 = $('#e_amount'+ i).val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        sss += parseFloat(c1);
    }
    $("#divsummery").text(sss.toFixed(2));
}

function checkIDNO(id){
    checkSelectCB(id);
    updateSummary();
	
	if(document.getElementById($('#cb_idno'+ id).val()).value != '')
	{
		$("#lastCus"+id).text("ชื่อผู้เข้าร่วมคนล่าสุด(ที่จะแสดงในใบเสร็จ) : "+document.getElementById($('#cb_idno'+ id).val()).value);
	}
	else
	{
		$("#lastCus"+id).text("ชื่อผู้เข้าร่วมคนล่าสุด(ที่จะแสดงในใบเสร็จ) : "+document.getElementById("mainFullname").value);
	}
}

function checkType(id){
    checkSelectCB(id);
    updateSummary();
}

function isInt(x) {
    var y=parseInt(x);
    if (isNaN(y)) return false;
    return x==y && x.toString()==y.toString();
}

checkIDNO(1);
</script>


</body>
</html>