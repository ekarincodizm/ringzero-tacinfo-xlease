<?php
include("../../config/config.php");

$RadioNum = $_GET['RadioNum'];
$default_COID = $_GET['COID'];
$cusid = $_GET['CusID'];

$qry_name=pg_query("select * from \"Fa1\" WHERE \"CusID\" = '$cusid'");
if($res_name=pg_fetch_array($qry_name)){
    $A_FIRNAME=trim($res_name["A_FIRNAME"]);
    $A_NAME=trim($res_name["A_NAME"]);
    $A_SIRNAME=trim($res_name["A_SIRNAME"]);
    $fu_name = "$A_FIRNAME $A_NAME $A_SIRNAME";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$("#chkdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
</script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value=" Back " class="ui-button" onclick="window.location='frm_Index.php';"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>TAC-เช็ค</B></legend>

<div class="ui-widget">

<div style="font-weight:bold; margin:10px 0px 10px 0px">ลูกค้า : <span style="color:#0000FF"><?php echo "$cusid $fu_name"; ?></span></div>

<div id="listbox">
<?php
$gg = 0;
$qry_fp=pg_query("select a.\"COID\",a.\"RadioNum\",a.\"RadioCar\" from \"RadioContract\" a
left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" and \"CusState\"='0'
left join \"GroupCus\" c on b.\"GroupCusID\"=c.\"GroupCusID\"
where a.\"ContractStatus\"='1' and b.\"CusID\"='$cusid' order by a.\"COID\"");
while($res_fp=pg_fetch_array($qry_fp)){
    $gg++;
    $COID=$res_fp["COID"];
    $RadioNum=$res_fp["RadioNum"];
    $RadioCar=$res_fp["RadioCar"];
    
    $arr_coid[] = $COID;
    
?>
<div class="greenbox" id="<?php echo "DIV$IDNO"; ?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="15%">COID&nbsp;<b><?php echo $COID; ?></b></td>
    <td width="20%"><?php echo $RadioNum; ?></td>
    <td width="10%"><?php echo $RadioCar; ?></td>
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
<table width="100%" cellpadding="3" cellspacing="0" border="0" style="background-color:#FFD5D5; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
	<tr style="font-weight:bold">
		<td>เลขที่เช็ค</td>
		<td>ธนาคาร</td>
		<td>สาขาธนาคาร</td>
		<td>วันที่นำเช็คเข้าธนาคาร</td>
	</tr>
	<tr bgcolor="#FFECEC">
		<td width="20%"><input type="text" name="chknum" id="chknum"></td>
		<td width="30%"><input type="text" name="chkbank" id="chkbank" size="30"></td>
		<td width="30%"><input type="text" name="chkbrance" id="chkbrance" size="30"></td>
		<td><input type="text" id="chkdate" name="chkdate" id="chkdate" value="<?php echo $startDate;?>" size="15" style="text-align: center;"></td>
	</tr>
</table>
<br>
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
<select id="cb_coid1" name="cb_coid1" onchange="javascript:checkIDNO(1)">
<?php
foreach ($arr_coid as $a){
    if($a != $default_COID){
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
$qry_fp=pg_query("select * from \"TypePay\" WHERE \"TypeID\" <> '299' ORDER BY \"TypeID\" ASC");
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

 </fieldset>

        </td>
    </tr>
</table>

<script type="text/javascript">
var counter = 1;
$(document).ready(function(){

    $('#addButton').click(function(){
    counter++;
    console.log(counter);
    var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);

    table = '<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
    + ' <tr>'
    + ' <td width="10%">'+ counter +'</td>'
    + ' <td width="20%">'
    + ' <select id="cb_coid' + counter + '" name="cb_coid' + counter + '" onchange=\"javascript:checkIDNO('+counter+')\">'
    + ' <?php
    foreach($arr_coid as $a){
        if($a != $default_COID){
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
    $qry_fp=pg_query("select * from \"TypePay\" WHERE \"TypeID\" <> '299' ORDER BY \"TypeID\" ASC");
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
    + ' </table>';

        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup");


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
		if( $('#chknum').val() == "" ){
                alert('กรุณากรอกเลขที่เช็ค !');
				$('#chknum').focus();
                $("#submitButton").attr('disabled', false);
                return false;
        }else if($('#chkbank').val() == ""){
				alert('กรุณากรอกชื่อธนาคาร !');
				$('#chkbank').focus();
                $("#submitButton").attr('disabled', false);
                return false;
		}else if($('#chkdate').val() == ""){
				alert('กรุณาระบุวันที่นำเข้าเช็คธนาคาร !');
				$('#chkdate').focus();
                $("#submitButton").attr('disabled', false);
                return false;
		}
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
            payment[i] = {COID : $("#cb_coid"+ i).val() , typepay: $("#cb_typepay"+ i).val() , amount : $("#e_amount"+ i).val()};
        }
        
        $.post("api.php",{
            cmd : "save2" , 
			chknum : $("#chknum").val(),
			chkbank : $("#chkbank").val(),
			chkbrance : $("#chkbrance").val(),
			chkdate : $("#chkdate").val(),
            cusid : '<?php echo "$cusid"; ?>', 
            payment : JSON.stringify(payment) 
        },
        function(data){
            if(data == "1"){
                alert("บันทึกรายการเรียบร้อย");
                location.href = "frm_Index2.php";
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
            if($('#cb_coid'+ id).val() == $('#cb_coid'+ i).val()){
                alert('ห้ามเลือกประเภทรายการซ้ำ !');
                $('#cb_typepay'+ id).val('เลือก');
                return false;
            }
        }
    }
    
    if( $("#cb_typepay"+ id).val() == "1" ){ //ตรวจสอบถ้าเป็นค่างวด
        $.get('api.php?cmd=checkacclose&idno='+ $("#cb_coid"+ id).val(), function(data){
            if(data == 't'){
                alert('เลขที่สัญญานี้ ไม่สามารถทำรายการจ่ายเงิน ค่างวด ได้ !');
                $("#cb_typepay"+ id).val('เลือก');
                return false;
            }else{
                $("#e_amount"+ id).attr("readonly", "readonly");
                //เรียกลิสต์ค่างวดมาแสดง
                $("#type_detail"+ id).load("api.php?cmd=loaddue&id="+ id +"&idno="+ $("#cb_coid"+ id).val());
                $("#type_detail"+ id).show();
                $("#e_amount"+ id).val(0);
            }
        });
    }else if( $("#cb_typepay"+ id).val() == "134" ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม
        $("#e_amount"+ id).attr("readonly", "readonly");
        //เรียก txtBox ใส่ค่าเข้ารว่มมาแสดง
        $("#type_detail"+ id).load("api.php?cmd=load134&id="+ id, function(){
            $("#type_detail"+ id).show();
            
            $.get('api.php?cmd=load134amt&idno='+ $("#cb_coid"+ id).val(), function(data){
                var s1 = parseFloat( $('#txtkr'+id).val() );
                var sm = s1*data;
                $('#e_amount'+ id).val( sm );
                updateSummary();
            });
            
        });
    }else{
        $("#e_amount"+ id).val(0);
        $("#e_amount"+ id).attr("readonly", "");
    }
}

function amtDue(id,due,idno){
    if(due == $('#cbDue'+id).val()){
        $("#discount"+ id).show();
        $("#discount"+ id).load("api.php?cmd=loaddiscount&id="+ id +"&idno="+ idno, function(){
            $.get('api.php?cmd=loaddueamt&idno='+ $("#cb_coid"+ id).val(), function(data){
                var s1 = parseFloat( $('#cbDue'+id).val() );
                var s2 = parseFloat( $('#txtDiscount'+id).val() );
                var sm = (s1*data)-s2;
                $('#e_amount'+ id).val( sm );
                updateSummary();
            });
        });
    }else{
        $("#discount"+ id).hide();
        $.get('api.php?cmd=loaddueamt&idno='+ $("#cb_coid"+ id).val(), function(data){
            var s1 = parseFloat( $('#cbDue'+id).val() );
            var sm = s1*data;
            $('#e_amount'+ id).val( sm );
            updateSummary();
        });
    }
}

function updateAmount(id){
   $.get('api.php?cmd=loaddueamt&idno='+ $("#cb_coid"+ id).val(), function(data){
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
        $.get('api.php?cmd=load134amt&idno='+ $("#cb_coid"+ id).val(), function(data){
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
</script>


</body>
</html>