<?php
include("../../config/config.php");
$ConID = $_REQUEST['ConID'];
$contractUseMoney = $_POST['contractUseMoney']; // เลขที่สัญญาที่ใช้เงิน
$cusPayMoney = $_POST["cusPayMoney"]; // จำนวนเงินที่ลูกค้าจะชำระ

//ข้อมูลมาจาก "ยืนยันรายการเงินโอน (การเงิน)"
$revTranID = $_POST["revTranID"];
$statusLock = $_POST["statusLock"]; //สถานะการ Lock 1=Lock || 2=Lock จากหน้าค้นหาหลัก
$statusPay = $_POST["statusPay"];

if($statusLock == 1)
{ // ถ้าเป็นเงินโอน
	//ตรวจสอบว่าเงินโอนมีสถานะเป็นอะไร
	$qrytran=pg_query("select \"bankRevAccID\",\"bankRevStamp\",\"BAccount\",\"balanceAmt\" from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranID'");
	list($bankRevAccID,$bankRevStamp,$BAccount,$bankRevAmt)=pg_fetch_array($qrytran);
	
	//แยกวันที่และเวลา
	$dateContact=trim(substr($bankRevStamp,0,10));
	$timeStamp=trim(substr($bankRevStamp,10));
}
elseif($statusLock == 2)
{ // ถ้ามาจากหน้าค้นหาหลัก
	$byChannelMainPage = $_POST["byChannelMainPage"]; // ช่องทางการจ่าย
	$dateContact = $_POST["receiveDatePostMainPage"]; // วันที่จ่าย
	
	if($byChannelMainPage == ""){$byChannelMainPage = $_GET["byChannelMainPage"];}
	if($dateContact == ""){$dateContact = $_GET["receiveDatePostMainPage"];}
	
	$selectByChannelMainPage = split(",",$byChannelMainPage);
	$bankRevAccID = $selectByChannelMainPage[0]; // ช่องทางการจ่ายที่เลือก
}

echo "<input type=\"hidden\" name=\"renew\" id=\"renew\" value=\"$ConID\">";
echo "<input type=\"hidden\" name=\"ConID2\" id=\"ConID2\" value=\"$ConID\">";

if(empty($ConID)){
   $ConID = $_POST['ConID'];
}

// ชื่อประเภทสินเชื่อแบบเต็ม
$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$ConID') ");
$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server 
$currentDate=nowDate();
$ycurent=substr($currentDate,0,4);
$qry_add=pg_query("select * from \"vthcap_ContactCus_detail\"
	where  \"contractID\" = '$ConID'");
if($resadd=pg_fetch_array($qry_add)){
	$address=trim($resadd["thcap_address"]);
}
$qry_name=pg_query("select * from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$ConID' and \"CusState\" > 0");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name)){
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
$i++;
}
$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$ConID' and \"CusState\" ='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}
$sql_head=pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$ConID' ");
$rowhead=pg_num_rows($sql_head);
if($rowhead == 0){$sql_head=pg_query("select * from public.\"thcap_lease_contract\" where \"contractID\" = '$ConID' ");}
$i = 1;
while($result=pg_fetch_array($sql_head))
{
	$conLoanIniRate = $result["conLoanIniRate"]; // อัตราดอกเบี้ยเริ่มแรก
	$conLoanMaxRate = $result["conLoanMaxRate"]; // อัตราดอกเบี้ยสูงสุด
	$conDate = $result["conDate"];
	$conStartDate = $result["conStartDate"];
	$conRepeatDueDay = $result["conRepeatDueDay"];
	$conLoanAmt = $result["conLoanAmt"];
	$conTerm = $result["conTerm"];
	$conMinPay = $result["conMinPay"];
	$conIntCurRate = $result["conIntCurRate"]; // อัตราดอกเบี้ยปัจจุบัน
	$conType = $result["conType"]; // ประเภทสินเชื่อ
	$conFinanceAmount = $result["conFinanceAmount"]; // ยอดจัดของสัญญา HP, FL, OL
}

// หารหัสเงินค้ำประกัน
$qry_getHoldMoneyType = pg_query("select account.\"thcap_getHoldMoneyType\"('$ConID','1')");
$res_getHoldMoneyType = pg_fetch_result($qry_getHoldMoneyType,0);

// หารหัสเงินพักรอตัดรายการ
$qry_getSecureMoneyType = pg_query("select account.\"thcap_getSecureMoneyType\"('$ConID','1')");
$res_getSecureMoneyType = pg_fetch_result($qry_getSecureMoneyType,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>(THCAP) รับชำระเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
$("#selectVice").hide();
$("#viceDetail").hide();
$("#divtb2").hide();
$("#divtb3").hide();
$("#whtDetail").hide();
$("#whtDetail_Payment").hide();
$("#moneyth").hide();
$("#fontwht").hide();
$("#fontwht_Payment").hide();
$("#txtwhtmain").hide();
$("#sum3").hide();

chkChannel();
	
var num = $("#hidenum").val();
for(a=0;a<num;a++){
	$("#moneytxt"+a).hide();
	$("#moneyth1"+a).hide();	
}

var cusPayMoney = '<?php echo $cusPayMoney; ?>';
if(cusPayMoney != '')
{
	$("#cusRemainMoney").text("จำนวนเงินคงเหลือจากลูกค้าที่สามารถใช้ได้ คือ "+addCommas(parseFloat(cusPayMoney).toFixed(2))+" บาท");
}

$("#selectVice_Payment").hide();
$("#viceDetail_Payment").hide();

document.getElementById("reasontextother").value='';
document.getElementById("reasontextother").readOnly=true;
document.getElementById("reasontextother").style.backgroundColor='#DDDDDD';
document.getElementById("reasontextappent").value='';
document.getElementById("reasontextappent").readOnly=true;
document.getElementById("reasontextappent").style.backgroundColor='#DDDDDD';

var chk_statusLock = '<?php echo $statusLock; ?>';

	if($chk_statusLock != 1)
	{
		$("#receiveDatePost").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	}

	$("#xlsDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#xlsjrDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	 $("#recxlease").autocomplete({
        source: "s_fotherpay.php",
        minLength:1
    });
	$("#spantotalamount").html($("#hidetotal").val())
	blfinance();
    $("#CONID").autocomplete({
      //  source: "s_contractID.php",
		 source: "s_idall.php",

        minLength:1
    });

    $('#btn1').click(function(){
    // $("#panel").load("Payments_history.php?ConID="+ $("#CONID").val());
		window.location.href="Payments_history.php?ConID="+ $("#CONID").val();
    });

});

var chk_dateContact = '<?php echo $dateContact; ?>'; // วันที่รับชำระ

function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
};

function calculate()
{
	var chk_con_type = '<?php echo $chk_con_type; ?>'; // ชื่อเต็มประเภทสัญญา
	var cusPayMoney = '<?php echo $cusPayMoney; ?>';
	
	obj = myfrm.elements['chk[]'];
	obj_wht = myfrm.elements['moneytxt[]']; // หักภาษี ณ ที่จ่าย*
	var k=0;
	var t1k=0;
	var total;
	
	var temp_k = 0;
	var temp_t1k = 0;
	var temp_sumwht = 0;
	
	var sumwht = 0;

	if(obj.length > 0)
	{
		for(i=0; i<obj.length; i++)
		{    
			if(obj[i].checked)
			{		
				total = obj[i].value;
				var obj2=total.split(" ");
				
				//--- โยนค่าไปคำนวนใน PHP
					temp_k = temp_k+"#"+obj2[0];
					temp_t1k = temp_t1k+"#"+obj2[0];
					temp_sumwht = temp_sumwht+"#"+document.getElementById("moneytxt"+obj2[3]).value; // รวมภาษีหัก ณ ที่จ่าย
				//--- จบการโยนค่าไปคำนวนใน PHP
			}
		}
		
		$.post("calculate.php",{
			temp_k : temp_k,
			operator : "k"
		},
		function(data_k){
			document.getElementById("receiveAmountPost").value = data_k;
		});
		k = document.getElementById("receiveAmountPost").value;
		
		$.post("calculate.php",{
			temp_t1k : temp_t1k,
			operator : "t1k"
		},
		function(data_t1k){
			document.getElementById("sum1").value = data_t1k; // ค่าใช่จ่ายอื่นๆ
		});
		t1k = document.getElementById("sum1").value;
		
		$.post("calculate.php",{
			temp_sumwht : temp_sumwht,
			operator : "sumwht"
		},
		function(data_sumwht){
			if(document.getElementById("interestRatePost").checked == true)
			{
				document.getElementById("sum2").value = data_sumwht; // รวมภาษีหัก ณ ที่จ่าย
			}
			else
			{
				document.getElementById("sum2").value = 0;
			}
		});
		sumwht = document.getElementById("sum2").value;
		
	}
	else
	{
		if(document.getElementById("chk0").checked)
		{
			var str = document.getElementById("chk0").value;
			var obj2 = str.split(" ");
			/*k = obj2[0];
			t1k = obj2[0];
			sumwht = document.getElementById("moneytxt"+obj2[3]).value;*/ // รวมภาษีหัก ณ ที่จ่าย
			
			//--- โยนค่าไปคำนวนใน PHP
				temp_k = temp_k+"#"+obj2[0];
				temp_t1k = temp_t1k+"#"+obj2[0];
				temp_sumwht = temp_sumwht+"#"+document.getElementById("moneytxt"+obj2[3]).value; // รวมภาษีหัก ณ ที่จ่าย
			//--- จบการโยนค่าไปคำนวนใน PHP
			
			$.post("calculate.php",{
				temp_k : temp_k,
				operator : "k"
			},
			function(data_k){
				document.getElementById("receiveAmountPost").value = data_k;
			});
			k = document.getElementById("receiveAmountPost").value;
			
			$.post("calculate.php",{
				temp_t1k : temp_t1k,
				operator : "t1k"
			},
			function(data_t1k){
				document.getElementById("sum1").value = data_t1k; // ค่าใช่จ่ายอื่นๆ
			});
			t1k = document.getElementById("sum1").value;
			
			$.post("calculate.php",{
				temp_sumwht : temp_sumwht,
				operator : "sumwht"
			},
			function(data_sumwht){
				if(document.getElementById("interestRatePost").checked == true)
				{
					document.getElementById("sum2").value = data_sumwht; // รวมภาษีหัก ณ ที่จ่าย
				}
				else
				{
					document.getElementById("sum2").value = 0;
				}
			});
			sumwht = document.getElementById("sum2").value;
		}
		else
		{
			document.getElementById("receiveAmountPost").value = '0.00';
			document.getElementById("sum1").value = '0.00';
		}
	}
	
	//-----------------------------------
	var sss = 0;
        var c1 = $('#t2').val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        //sss += parseFloat(c1);
		sss = c1;
		
	var qqq = 0;
        var c1 = $('#money_Deposit').val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        //qqq += parseFloat(c1);	
		qqq = c1;
		
	var eee = 0;
        var c1 = $('#money_Guarantee').val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        //eee += parseFloat(c1);
		eee = c1;
		
	var ppp = 0; // เบี้ยปรับ
	if(document.getElementById("payPenalty").checked == true)
	{
		var c1 = $('#amtPenalty').val();
		if ( isNaN(c1) || c1 == ""){
			c1 = 0;
		}
		ppp = c1;
	}
	
	var aaa = 0;
	if(chk_con_type == 'JOINT_VENTURE' && chk_dateContact < '2014-02-01') // ถ้าเป็นสัญญา JOINT_VENTURE และ ชำระเงินก่อนวันที่ 2014-02-01
	{
		if(document.getElementById("payAdviser").checked == true)
		{
			var c1 = $('#sumPayAdviser').val();
			if ( isNaN(c1) || c1 == ""){
				c1 = 0;
			}
			aaa = c1;
		}
	}

	//ksum = k + qqq + sss + eee;
	//k = document.getElementById("receiveAmountPost").value;
	
	var temp_ksum;
	temp_ksum = sss+"#"+qqq+"#"+eee+"#"+ppp+"#"+aaa+"#"+temp_k;
	
	$.post("calculate.php",{
		temp_ksum : temp_ksum,
		operator : "ksum"
	},
	function(data_ksum){
		var sumdel=document.getElementById("sum2").value; //ภาษี ณ ที่จ่ายค่าอื่นๆ
		var sumdel2=document.getElementById("sum3").value; //ภาษี ณ ที่จ่ายค่างวด
		
		document.getElementById("receiveAmountPost2").value = data_ksum;
		document.getElementById("receiveAmountPost3").value = ((parseFloat(data_ksum)-parseFloat(sumdel))-parseFloat(sumdel2)).toFixed(2);
		blfinance();
		
		cusRemainMoney(data_ksum); // จำนวนเงินคงเหลือจากลูกค้าที่สามารถใช้ได้
	});
	//ksum = document.getElementById("receiveAmountPost2").value;
	var kdel = document.getElementById("receiveAmountPost3").value;
	
	document.getElementById("receiveAmountPost").value = parseFloat(k).toFixed(2); // ค่าใช่จ่ายอื่นๆ
	//document.getElementById("receiveAmountPost2").value = ksum.toFixed(2); // รวมทั้งหมด
	document.getElementById("receiveAmountPost2").value = ksum;
	//document.getElementById("receiveAmountPost3").value = kdel.toFixed(2); // รวมได้รับสุทธิ
	document.getElementById("receiveAmountPost3").value = kdel;
	document.getElementById("sum1").value = parseFloat(t1k).toFixed(2); // ค่าใช่จ่ายอื่นๆ แต่ละรายการ
	if(document.getElementById("interestRatePost").checked == true)
	{
		document.getElementById("sum2").value = parseFloat(sumwht).toFixed(2); // รวมภาษีหัก ณ ที่จ่าย
	}
	else
	{
		document.getElementById("sum2").value = 0; // รวมภาษีหัก ณ ที่จ่าย
	}
	
	chkOverWht(); // เช็คว่าจำนวนเงินที่ปรับ เกินค่าที่กำหนดให้ปรับหรือไม่
}

function cusRemainMoney(crm)
{ // จำนวนเงินคงเหลือจากลูกค้าที่สามารถใช้ได้
	var cusPayMoney = '<?php echo $cusPayMoney; ?>'; 
	if(cusPayMoney != '')
	{	
		var result=cusPayMoney-crm;
		if(result<0){
			$("#cusRemainMoney").text("");
			$("#cusRemainMoneyMinus").text("จำนวนเงินคงเหลือจากลูกค้าที่สามารถใช้ได้ คือ "+addCommas(parseFloat(parseFloat(cusPayMoney) - parseFloat(crm)).toFixed(2))+" บาท");
		}
		else{
			$("#cusRemainMoneyMinus").text("");
			$("#cusRemainMoney").text("จำนวนเงินคงเหลือจากลูกค้าที่สามารถใช้ได้ คือ "+addCommas(parseFloat(parseFloat(cusPayMoney) - parseFloat(crm)).toFixed(2))+" บาท");
		}
	}
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function receivevicechk(){ // ใบเสร็จออกแทนของค่าอื่นๆ
	if(document.getElementById("receiveVice").checked == true)
	{
		$("#selectVice").show();
		$("#viceDetail").show();
		//$("#viceDetail").focus();
	}
	else
	{
		$("#selectVice").hide();
		$("#viceDetail").hide();
		
		$("#viceDetail").val()='';
	}
}
function receivevicechk_Payment(){ // ใบเสร็จออกแทนของค่างวด
	if(document.getElementById("receiveVice_Payment").checked == true)
	{
		$("#selectVice_Payment").show();
		$("#viceDetail_Payment").show();
		//$("#viceDetail_Payment").focus();
	}
	else
	{
		$("#selectVice_Payment").hide();
		$("#viceDetail_Payment").hide();
		
		$("#viceDetail_Payment").val()='';
	}
}
function receivewhtchk(){ // ภาษีหัก ณ ที่จ่าย ของค่าอื่นๆ
var num = $("#hidenum").val();
	if(document.getElementById("interestRatePost").checked == true)
	{
		$("#whtDetail").show();
		$("#moneyth").show();
		$("#fontwht").show();
		
		for(d=0;d<num;d++)
		{
			$("#moneytxt"+d).show();
			$("#moneyth1"+d).show();
		}
		calculate();
		truemoneyother();
	}
	else
	{
		$("#whtDetail").hide();
		$("#moneyth").hide();
		$("#fontwht").hide();
				
		for(d=0;d<num;d++)
		{
			$("#moneytxt"+d).hide();
			//$("#moneytxt"+d).val("");
			$("#moneyth1"+d).hide();
		}
		document.getElementById("whtDetail").value = '';
		document.getElementById("sum2").value = '0'; // รวมภาษีหัก ณ ที่จ่าย
	}
	
	calculate();
	truemoneyother();
}
function receivewhtchk_Payment(){ // ภาษีหัก ณ ที่จ่าย ของค่างวด
	if(document.getElementById("interestRatePost_Payment").checked == true)
	{
		$("#whtDetail_Payment").show();
		$("#fontwht_Payment").show();
		$("#txtwhtmain").show();
		$("#sum3").show();
		truemoney();
	}
	else
	{
		$("#whtDetail_Payment").hide();
		$("#fontwht_Payment").hide();
		$("#txtwhtmain").hide();
		$("#sum3").hide();
		document.getElementById("whtDetail_Payment").value = '';
	}
	CalWhtMain();
	chkOverWht();
}
function chkAdd()
{	
	if(document.getElementById("appent").checked == true)
	{	
		$("#divtb2").slideDown("slow").show("slow");
		$(".maindiv2").css("margin-top","40px");
	}
	else
	{
		$("#divtb2").slideUp("slow").hide("slow");
		$(".maindiv2").css("margin-top","100px");
		
		document.getElementById('t2').value=0;
		
		document.getElementById("interestRatePost_Payment").checked = false;
		receivewhtchk_Payment();
		//$("#t2").val() = '0';
		//$("#t3").val() = '0';
		
		test();
	}
}

function chkAdviser() // ชำระค่าที่ปรึกษา
{	
	if(document.getElementById("payAdviser").checked == true)
	{	
		$("#divtb3").slideDown("slow").show("slow");
		$(".maindiv3").css("margin-top","40px");
	}
	else
	{
		$("#divtb3").slideUp("slow").hide("slow");
		$(".maindiv3").css("margin-top","100px");
		
		document.getElementById('sumPayAdviser').value=0;
		test();
	}
}

function test()
{
	calculate();
}

function chkTypePayRefDate(numChk) // เช็คว่าว่าที่โอนเงินน้อยกว่าวันที่ตั้งหนี้ค่าอื่นๆหรือไม่
{
	var statusLock;
	statusLock = '<?php echo $statusLock; ?>';
	if(statusLock == '1')
	{ // ถ้าเป็นเงินโฮน ให้ตรวจสอบวันที่ตั้งหนี้ กับวันที่รับชำระก่อน
		var dateContact;
		dateContact = '<?php echo $dateContact; ?>';
		if(dateContact < document.getElementById('typePayRefDate'+numChk).value)
		{
			alert('วันที่ตั้งหนี้ค่าอื่นๆที่เลือก ต้องน้อยกว่าหรือเท่ากับวันที่โอนเงิน');
			document.getElementById('chk'+numChk).checked = false;
			//return false;
		}
	}
}

function showdetails()
{
	var chk_con_type = '<?php echo $chk_con_type; ?>'; // ชื่อเต็มประเภทสัญญา
	
	// ตรวจสอบว่ามีการทำรายการหรือไม่
	var chkClickOther = myfrm.elements['chk[]'];
	var checkClick = false;
	if(chkClickOther.length > 0)
	{
		for(var i=0; i<chkClickOther.length; i++ )
		{
			if(document.getElementById('chk'+i).checked == true)
			{
				checkClick = true;
			}
		}
	}
	else
	{
		if(document.getElementById('chk0').checked == true)
		{
			checkClick = true;
		}
	}
	
	if(checkClick == false)
	{
		if(chk_con_type == 'JOINT_VENTURE')
		{
			if(chk_dateContact < '2014-02-01') // ถ้าชำระเงินก่อนวันที่ 2014-02-01
			{
				if(document.getElementById("appent").checked == false && document.getElementById("payAdviser").checked == false && parseFloat(document.getElementById('money_Deposit').value) <= 0.00 && parseFloat(document.getElementById('money_Guarantee').value) <= 0.00 && parseFloat(document.getElementById('amtPenalty').value) <= 0.00)
				{
					alert('ท่านยังไม่ได้ทำรายการรับชำระ กรุณาเลือกรายการที่จะรับชำระ หรือระบุจำนวนเงินที่จะรับชำระก่อน!!');
					return false;
				}
			}
			else // ถ้าชำระเงินตั้งแต่วันที่ 2014-02-01 เป็นต้นไป :: ไม่ต้องดูเรื่องค่าที่ปรึกษา
			{
				if(document.getElementById("appent").checked == false && parseFloat(document.getElementById('money_Deposit').value) <= 0.00 && parseFloat(document.getElementById('money_Guarantee').value) <= 0.00 && parseFloat(document.getElementById('amtPenalty').value) <= 0.00)
				{
					alert('ท่านยังไม่ได้ทำรายการรับชำระ กรุณาเลือกรายการที่จะรับชำระ หรือระบุจำนวนเงินที่จะรับชำระก่อน!!');
					return false;
				}
			}
		}
		else
		{
			if(document.getElementById("appent").checked == false && parseFloat(document.getElementById('money_Deposit').value) <= 0.00 && parseFloat(document.getElementById('money_Guarantee').value) <= 0.00 && parseFloat(document.getElementById('amtPenalty').value) <= 0.00)
			{
				alert('ท่านยังไม่ได้ทำรายการรับชำระ กรุณาเลือกรายการที่จะรับชำระ หรือระบุจำนวนเงินที่จะรับชำระก่อน!!');
				return false;
			}
		}
	}
	// จบการตรวจสอบว่ามีการทำรายการหรือไม่

	//กรณีมาจากเงินโอน จะตรวจสอบก่อนว่าจำนวนเงินที่ชำระเท่ากับเงินที่โอนมาหรือไม่
	if(document.getElementById('statusPay').value=='revTranID'){
		/*if(parseFloat(document.getElementById('receiveAmountPost3').value)!=parseFloat(document.getElementById('bankRevAmt').value)){
			alert('จำนวนเงินรวมที่ชำระ ไม่เท่ากับจำนวนเงินที่โอนมา กรุณาตรวจสอบ!!');
			return false;
		}*/
		
		if(parseFloat(document.getElementById('receiveAmountPost3').value) > parseFloat(document.getElementById('bankRevAmt').value)){
			alert('จำนวนเงินรวมที่ชำระ ห้ามมากกว่าเงินโอน!!');
			return false;
		}
	}
	
	var currentDate = '<?php echo $currentDate; ?>';
	if(document.getElementById('receiveDatePost').value > currentDate){
		alert('ห้ามเลือกวันที่รับชำระมากกว่าวันที่ปัจจุบัน');
		return false;
	}
	
	// ห้ามชำระค่างวดเงินกู้จำนอง <= 0.00 บาท
	if((chk_con_type != 'HIRE_PURCHASE' && chk_con_type != 'LEASING' && chk_con_type != 'GUARANTEED_INVESTMENT') && document.getElementById("appent").checked == true && parseFloat(document.getElementById('t2').value) <= 0.00)
	{
		alert('ห้ามรับชำระค่างวดเงินกู้จำนอง น้อยกว่าหรือเท่ากับ 0.00 บาท');
		return false;
	}
	
	//-- ตรวจสอบจำนวนเงินในขณะนั้นว่าพอชำระหรือไม่หรือไม่
		var chkgetHoldMoneyType = '<?php echo $res_getHoldMoneyType; ?>'; // เงินพักรอตัดรายการ
		var chkgetSecureMoneyType = '<?php echo $res_getSecureMoneyType; ?>'; // เงินค้ำประกัน
	
		if(document.getElementById("byChannelPost").value == chkgetSecureMoneyType+',0' || document.getElementById("byChannelPost").value == chkgetSecureMoneyType+',1')
		{
			$.post("../thcap/thcap_change_money/check_money.php",{
				id : '<?php echo $ConID; ?>',
				moneyType : chkgetSecureMoneyType
			},
			function(dataMoneychk){
				if(parseFloat(dataMoneychk) < parseFloat(document.getElementById('receiveAmountPost3').value))
				{
					alert('จำนวนเงินค้ำประกันในขณะนี้ไม่พอรับชำระ กรุณาทำรายการใหม่!!');
					//window.location='Payments_history.php?ConID=' + '<?php echo $ConID; ?>';
					return false;
				}
				else
				{
					subShowdetails();
				}
			});
		}
		else if(document.getElementById("byChannelPost").value == chkgetHoldMoneyType+',0' || document.getElementById("byChannelPost").value == chkgetHoldMoneyType+',1')
		{
			$.post("../thcap/thcap_change_money/check_money.php",{
				id : '<?php echo $ConID; ?>',
				moneyType : chkgetHoldMoneyType
			},
			function(dataMoneychk){
				if(parseFloat(dataMoneychk) < parseFloat(document.getElementById('receiveAmountPost3').value))
				{
					alert('จำนวนเงินพักรอตัดรายการในขณะนี้ไม่พอรับชำระ กรุณาทำรายการใหม่!!');
					//window.location='Payments_history.php?ConID=' + '<?php echo $ConID; ?>';
					return false;
				}
				else
				{
					subShowdetails();
				}
			});
		}
		else
		{
			subShowdetails();
		}
	//-- จบการตรวจสอบจำนวนเงินในขณะนั้นว่าพอย้ายหรือไม่
}

function subShowdetails()
{
	var chk_con_type = '<?php echo $chk_con_type; ?>'; // ชื่อเต็มประเภทสัญญา
	var cusPayMoney = '<?php echo $cusPayMoney; ?>'; // จำนวนเงินที่ลูกค้าจะชำระ
	
	if(document.getElementById('statusOverWht').value > 0 || document.getElementById('statusOverWhtHP').value > 0)
	{
		alert('ห้ามแก้ไข ภาษีหัก ณ หักที่จ่าย เกินจำนวนที่บริษัทกำหนดไว้ (5 บาท ต่อรายการ)');
		return false;
	}
	else if(document.getElementById("payPenalty").checked == true && parseFloat(document.getElementById('amtPenalty').value) > parseFloat(document.getElementById('fullPenalty').value))
	{
		alert('ห้ามชำระเบี้ยปรับ เกินจำนวนเบี้ยปรับที่ค้างชำระ || เบี้ยปรับที่ค้างชำระ : '+parseFloat(document.getElementById('fullPenalty').value)+' เบี้ยปรับที่จะชำระ : '+parseFloat(document.getElementById('amtPenalty').value));
		return false;
	}
	/* todo comment ในส่วนนี้ทิ้งเอาไว้ก่อน ถ้าอนาคตต้องการจะบังคับให้จ่ายเบี้ยปรับอย่างน้อยครึ่งนึง ในกรณีที่มียอดเบี้ยปรับตั้งแต่ 100 บาทขึ้นไป สามารถเอา comment ในส่วนนี้ออก เพื่อใช้งานได้
	else if(document.getElementById("payPenalty").checked == true && document.getElementById("appent").checked == true && parseFloat(document.getElementById('amtPenalty').value) < parseFloat(document.getElementById('halfPenalty').value) && parseFloat(document.getElementById('fullPenalty').value) >= 100.00)
	{ // เบี้ยปรับล่าช้า
		alert('ต้องชำระเบี้ยปรับอย่างน้อยครึ่งหนึ่งของราคาเบี้ยปรับ คือ '+document.getElementById('halfPenalty').value+' บาท จากจำนวนเต็ม คือ '+document.getElementById('fullPenalty').value+' บาท');
		return false;
	}
	*/
	else if(cusPayMoney != '' && parseFloat(document.getElementById('receiveAmountPost2').value) != parseFloat(cusPayMoney))
	{
		alert('ไม่สามารถรับชำระได้ เนื่องจากจำนวนเงินรับชำระ ไม่ตรงตามที่ลูกค้าต้องการจะชำระ\r\nจำนวนเงินที่ลูกค้ากำหนดจะชำระคือ '+addCommas(parseFloat(cusPayMoney).toFixed(2))+' บาท\r\nจำนวนเงินที่ชำระในครั้งนี้คือ '+addCommas(parseFloat(document.getElementById('receiveAmountPost2').value).toFixed(2))+' บาท');
		return false;
	}
	else if(document.getElementById('receiveVice').checked == true && document.getElementById('viceDetail').value == '')
	{
		alert('กรุณาระบุ ใบเสร็จออกแทน ของ ค่าใช้จ่ายอื่น ๆ');
		return false;
	}
	else if(document.getElementById('receiveVice_Payment').checked == true && document.getElementById('viceDetail_Payment').value == '')
	{
		alert('กรุณาระบุ ใบเสร็จออกแทน ของ ค่างวด');
		return false;
	}
	else if(document.getElementById('receiveVice').checked == true && document.getElementById('receiveVice_Payment').checked == true
			&& document.getElementById('viceDetail').value == document.getElementById('viceDetail_Payment').value)
	{
		alert('ไม่สามารถรับชำระได้ เนื่องจาก ใบเสร็จออกแทน ของค่าอื่นๆและค่างวด ซ้ำกัน');
		return false;
	}
	else
	{		
		var totalamount = document.getElementById('receiveAmountPost2').value; // จำนวนเงินรวมทั้งหมด
			totalamount = parseFloat(totalamount);
			totalamount = addCommas(totalamount.toFixed(2));
		var debt1_origin = document.getElementById('sum1').value; // หนี้อื่นๆ ใช้คำนวน
		var debt1 = document.getElementById('sum1').value; // หนี้อื่นๆ
			debt1 = parseFloat(debt1);
			debt1 = addCommas(debt1.toFixed(2));
		var tax_debt1_origin = document.getElementById('sum2').value; // ภาษีหัก ณ ที่จ่าย ค่าอื่นๆ ใช้คำนวน
		var tax_debt1 = document.getElementById('sum2').value; // ภาษีหัก ณ ที่จ่าย ค่าอื่นๆ
			tax_debt1 = parseFloat(tax_debt1);
			tax_debt1 = addCommas(tax_debt1.toFixed(2));
		var total_debt1 = parseFloat(debt1_origin) - parseFloat(tax_debt1_origin); // รับสุทธิค่าอื่นๆ
		var total_debt1_origin = parseFloat(debt1_origin) - parseFloat(tax_debt1_origin); // รับสุทธิค่าอื่นๆ ใช้คำนวน
			total_debt1 = parseFloat(total_debt1);
			total_debt1 = addCommas(total_debt1.toFixed(2));
		var debt2_origin = document.getElementById('t2').value; // จำนวนเงินค่างวด ใช้คำนวน
		var debt2 = document.getElementById('t2').value; // จำนวนเงินค่างวด
			debt2 = parseFloat(debt2);
			debt2 = addCommas(debt2.toFixed(2));
		var tax_debt2_origin = document.getElementById('sum3').value; // ภาษีหัก ณ ที่จ่าย ค่างวด ใช้คำนวน
		var tax_debt2 = document.getElementById('sum3').value; // ภาษีหัก ณ ที่จ่าย ค่างวด
			tax_debt2 = parseFloat(tax_debt2);
			tax_debt2 = addCommas(tax_debt2.toFixed(2));
		var total_debt2 = parseFloat(debt2_origin) - parseFloat(tax_debt2_origin); // รับสุทธิจำนวนเงินค่างวด
		var total_debt2_origin = parseFloat(debt2_origin) - parseFloat(tax_debt2_origin); // รับสุทธิจำนวนเงินค่างวด ใช้คำนวน
			total_debt2 = parseFloat(total_debt2);
			total_debt2 = addCommas(total_debt2.toFixed(2));
		//var totalamountCal = parseFloat(total_debt1_origin) + parseFloat(total_debt2_origin); // รวมรับชำระสุทธิทั้งหมด
		var totalamountCal = document.getElementById('receiveAmountPost3').value; // รวมรับชำระสุทธิทั้งหมด
			totalamountCal = parseFloat(totalamountCal);
			totalamountCal = addCommas(totalamountCal.toFixed(2));
		var payPenalty = document.getElementById('amtPenalty').value; // เบี้ยปรับ
			payPenalty = addCommas(parseFloat(payPenalty).toFixed(2));
		var money_Deposit1 = document.getElementById('money_Deposit').value; // เงินพักรอตัดรายการ ( เงินรับฝาก )
			money_Deposit1 = addCommas(parseFloat(money_Deposit1).toFixed(2));
		var money_Guarantee1 = document.getElementById('money_Guarantee').value; // เงินค้ำประกันการชำระหนี้
			money_Guarantee1 = addCommas(parseFloat(money_Guarantee1).toFixed(2));
		
		if(chk_con_type == 'JOINT_VENTURE')
		{
			if(chk_dateContact < '2014-02-01') // ถ้าชำระเงินก่อนวันที่ 2014-02-01
			{
				money_Adviser = document.getElementById('sumPayAdviser').value;
				money_Adviser = addCommas(parseFloat(money_Adviser).toFixed(2));
			}
			else // ถ้าชำระเงินตั้งแต่วันที่ 2014-02-01 เป็นต้นไป
			{
				money_Adviser = 0.00;
				money_Adviser = addCommas(parseFloat(money_Adviser).toFixed(2));
			}
		}
		else
		{
			money_Adviser = 0.00;
			money_Adviser = addCommas(parseFloat(money_Adviser).toFixed(2));
		}
		
		var rstext='หนี้อื่น\r\n\n\tจำนวนเงิน :\t\t\t'+debt1+'\t\tบาท\r\n\tภาษีหัก ณ ที่จ่าย :\t\t'+tax_debt1+'\t\tบาท\r\n\tรวมรับสุทธิ :\t\t\t'+total_debt1+'\t\tบาท\r\n\nหนี้เงินกู้\r\n\n\tจำนวนเงิน :\t\t\t'+debt2+'\t\tบาท\r\n\tภาษีหัก ณ ที่จ่าย :\t\t'+tax_debt2+'\t\tบาท\r\n\tรวมรับชำระสุทธิ :\t\t'+total_debt2+'\t\tบาท\r\n\nเบี้ยปรับ : \t\t\t\t'+payPenalty+'\t\tบาท\nเงินค้ำประกันการชำระหนี้ : \t'+money_Guarantee1+'\t\tบาท\nเงินพักรอตัดรายการ(เงินรับฝาก) : '+money_Deposit1+'\t\tบาท\nค่าที่ปรึกษา : \t\t\t'+money_Adviser+'\t\tบาท\r\n\nยอดหนี้ที่ชำระทั้งหมด :\t'+totalamount+'\t\tบาท\r\nเงินสดรับสุทธิทั้งหมด :\t'+totalamountCal+'\t\tบาท';
		//var rstext = '<table><tr><td>test</td></tr></table>';
		if(confirm(rstext)==true)
		{
			document.myfrm.submit();
		}
	}
}

function addCommas(nStr)
{ // function สำหรับเพิ่มลูกน้ำ
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
	{
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
return x1 + x2;
}

function chkOverWht()
{ // function สำหรับตวรจสอบว่า มีการเปลี่ยนจำนวนเงิน ภาษีหัก ณ ที่จ่าย เกิน 5 บาท หรือไม่
	var num = $("#hidenum").val();
	var deffWht = 0;
	var statusOverWht = 0;
	var deffsum3 = 0;
	var sumdel3;
	
	for(d=0;d<num;d++)
	{
		
		deffWht = parseFloat($("#moneytxt"+d).val()) - parseFloat($("#CHKmoneytxt"+d).val());
		
		if(deffWht >= -5.00 && deffWht <= 5.00)
		{ // ถ้าไม่เกินค่าที่กำหนด
			document.getElementById("moneytxt"+d).style.backgroundColor = "#FFFFFF";
		}
		else
		{ // ถ้าเกินค่าที่กำหนด
			statusOverWht++;
			document.getElementById("moneytxt"+d).style.backgroundColor = "#FF5555";
		}
	}
	
	deffsum3 = parseFloat($("#sum3").val()) - parseFloat($("#CHKsum3").val());
	if(deffsum3 >= -5.00 && deffsum3 <= 5.00)
	{ // ถ้าไม่เกินค่าที่กำหนด
		document.getElementById("sum3").style.backgroundColor = "#FFFFFF";
	}
	else
	{ // ถ้าเกินค่าที่กำหนด
		statusOverWht++;
		document.getElementById("sum3").style.backgroundColor = "#FF5555";
	}
	
	document.getElementById("receiveAmountPost3").value = (parseFloat(document.getElementById("receiveAmountPost2").value)-parseFloat(document.getElementById("sum2").value)-parseFloat(sumdel3)).toFixed(2);
	
	document.getElementById("statusOverWht").value = statusOverWht;
	
	truewht();
	
}
function clearNum(id){
	var value;
	value = document.getElementById(id).value;
	if(value=='0')
	{
		document.getElementById(id).value='';
	}
}
function checkNull(id){
	var value;
	value = document.getElementById(id).value;
	if(value=='')
	{
		document.getElementById(id).value='0';
	}
}

// จำนวนเงินที่แท้จริง
function truemoneyother()
{
	document.getElementById("receiveAmountPost3").value = ((parseFloat(document.getElementById("receiveAmountPost2").value) - parseFloat(document.getElementById("sum2").value)) - parseFloat(document.getElementById("sum3").value)).toFixed(2);
	truewht();
	
	obj = myfrm.elements['chk[]'];
	obj_wht = myfrm.elements['moneytxt[]']; // หักภาษี ณ ที่จ่าย*
	var k=0;
	var t1k=0;
	var total;
	
	var temp_k = 0;
	var temp_t1k = 0;
	var temp_sumwht = 0;
	
	var sumwht = 0;

	if(obj.length > 0)
	{
		for(i=0; i<obj.length; i++)
		{    
			if(obj[i].checked)
			{		
				total = obj[i].value;
				var obj2=total.split(" ");
				
				//--- โยนค่าไปคำนวนใน PHP
					temp_sumwht = temp_sumwht+"#"+document.getElementById("moneytxt"+obj2[3]).value; // รวมภาษีหัก ณ ที่จ่าย
				//--- จบการโยนค่าไปคำนวนใน PHP
			}
		}
		
		temp_sumwht = temp_sumwht+"#"+document.getElementById("sum3").value;
		
		$.post("calculate.php",{
			temp_sumwht : temp_sumwht,
			operator : "sumwht"
		},
		function(data_sumwht){
			if(document.getElementById("interestRatePost").checked == true)
			{
				document.getElementById("receiveAmountPost4").value = data_sumwht; // รวมภาษีหัก ณ ที่จ่าย
			}
			else
			{
				document.getElementById("receiveAmountPost4").value = 0;
			}
		});
		sumwht = document.getElementById("receiveAmountPost4").value;
		
	}
	else
	{
		if(document.getElementById("chk0").checked)
		{
			var str = document.getElementById("chk0").value;
			var obj2 = str.split(" ");
			
			//--- โยนค่าไปคำนวนใน PHP
				temp_sumwht = temp_sumwht+"#"+document.getElementById("moneytxt"+obj2[3]).value; // รวมภาษีหัก ณ ที่จ่าย
			//--- จบการโยนค่าไปคำนวนใน PHP
			
			temp_sumwht = temp_sumwht+"#"+document.getElementById("sum3").value;
			
			$.post("calculate.php",{
				temp_sumwht : temp_sumwht,
				operator : "sumwht"
			},
			function(data_sumwht){
				if(document.getElementById("interestRatePost").checked == true)
				{
					document.getElementById("receiveAmountPost4").value = data_sumwht; // รวมภาษีหัก ณ ที่จ่าย
				}
				else
				{
					document.getElementById("receiveAmountPost4").value = 0;
				}
			});
			sumwht = document.getElementById("receiveAmountPost4").value;
		}
		else
		{
			document.getElementById("receiveAmountPost").value = '0.00';
			document.getElementById("sum1").value = '0.00';
		}
	}
	
	//-----------------------------------
	var sss = 0;
        var c1 = $('#t2').val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        //sss += parseFloat(c1);
		sss = c1;
		
	var qqq = 0;
        var c1 = $('#money_Deposit').val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        //qqq += parseFloat(c1);	
		qqq = c1;
		
	var eee = 0;
        var c1 = $('#money_Guarantee').val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        //eee += parseFloat(c1);
		eee = c1;
	
	var ppp = 0;
	if(document.getElementById("payPenalty").checked == true)
	{
		var c1 = $('#amtPenalty').val();
		if ( isNaN(c1) || c1 == ""){
			c1 = 0;
		}
		ppp = c1;
	}
	
	var temp_ksum;
	temp_ksum = sss+"#"+qqq+"#"+eee+"#"+ppp+"#"+temp_k;
	
	$.post("calculate.php",{
		temp_ksum : temp_ksum,
		operator : "ksum"
	},
	function(data_ksum){
		var sumdel=document.getElementById("receiveAmountPost4").value; //ภาษี ณ ที่จ่ายค่าอื่นๆ
	});
	sumwht = document.getElementById("receiveAmountPost4").value;
	
	/*if(document.getElementById("interestRatePost").checked == true)
	{
		document.getElementById("receiveAmountPost4").value = parseFloat(sumwht).toFixed(2); // รวมภาษีหัก ณ ที่จ่าย
	}
	else
	{
		document.getElementById("receiveAmountPost4").value = 0; // รวมภาษีหัก ณ ที่จ่าย
	}*/
	
	chkOverWht(); // เช็คว่าจำนวนเงินที่ปรับ เกินค่าที่กำหนดให้ปรับหรือไม่
	truewht();
}

function truewht()
{
	var s2d = $('#sum2').val();
	var s3d = $('#sum3').val();
	
	sssddd = parseFloat(s2d)+parseFloat(s3d);
	$("#receiveAmountPost4").val(sssddd.toFixed(2));
	blfinance();
}

function truemoney()
{
	document.getElementById("receiveAmountPost3").value = ((parseFloat(document.getElementById("receiveAmountPost2").value) - parseFloat(document.getElementById("sum2").value)) - parseFloat(document.getElementById("sum3").value)).toFixed(2);
	truewht();	
}

// หมายเหตุให้เปิดการพิมพ์เมื่อมีการติ๊กถูก

function typereason(val){ //ค่าอื่นๆ

	if(document.getElementById(val).checked){
		
		document.getElementById("reasontextother").readOnly=false;
		document.getElementById("reasontextother").style.backgroundColor='#FFFFFF';
		document.getElementById("reasontextother").focus();
	}else{
	
		document.getElementById("reasontextother").value='';
		document.getElementById("reasontextother").readOnly=true;
		document.getElementById("reasontextother").style.backgroundColor='#DDDDDD';
	}	
}

function typereasonloan(val){ //เงินกู้

	if(document.getElementById(val).checked){
		
		document.getElementById("reasontextappent").readOnly=false;
		document.getElementById("reasontextappent").style.backgroundColor='#FFFFFF';
		document.getElementById("reasontextappent").focus();
	}else{
	
		document.getElementById("reasontextappent").value='';
		document.getElementById("reasontextappent").readOnly=true;
		document.getElementById("reasontextappent").style.backgroundColor='#DDDDDD';
	}	
}
//จบหมายเหตุ

function blfinance(){
	<?php if($bankRevAmt){ ?>
		m1 = <?php echo $bankRevAmt ?>;
		m2 = document.getElementById("receiveAmountPost3").value;
		if(m2 >= 0){
			mfinal = parseFloat(m1) - parseFloat(m2);
		}else{
			mfinal = parseFloat(m1);
		}
		mtxtfinal = addCommas(mfinal.toFixed(2));
		$("#balancefinance").text("  (จำนวนเงินโอนคงเหลือ "+mtxtfinal+" บาท )");
	<?php } ?>
};

function chkSelectOtherCon() // ตรวจสอบสัญญาอื่นๆที่จะใช้เงิน กรณีที่มีผู้กู้เหมือนกัน
{
	if(document.getElementById("ConID").value == '')
	{
		alert('กรุณาเลือกเลขที่สัญญา');
		return false;
	}
	else
	{
		return true;
	}
}
</script>

<style type="text/css">
#Content .menu {
    background-color: #FAF2D3;
    font-size: 12px;
    color: #585858;
    margin: 0px;
    padding: 3px 3px 3px 3px;
    border: 1px solid #C0C0C0;
}

div.banner {
  margin: 0;
  font-size: 14px;
  font-weight: bold;
  line-height: 1.1;
  text-align: center;
  position: fixed;
  top: 2em;
  left: auto;
  //width: 20.5em;
  right: 2em;
  z-index: 999;
  background-color: #ADFF2F;
  color: #000000;
}
div.bannerMinus {
  margin: 0;
  font-size: 14px;
  font-weight: bold;
  line-height: 1.1;
  text-align: center;
  position: fixed;
  top: 2em;
  left: auto;
  //width: 20.5em;
  right: 2em;
  z-index: 999;
  background-color: #ADFF2F;
  color: #EE2C2C;
}
</style>

<link href="styles/style_v3.css" rel="stylesheet" type="text/css">
</head>
<body>
<!-- new code start at this point-->

<center><h2>(THCAP) รับชำระเงิน</h2></center>
<center>
<!--<b>เลขที่สัญญา</b>&nbsp;-->
			<input id="CONID" name="CONID" size="60" value="<?php echo $ConID;?>" <?php if($statusLock==1){ echo "disabled=true"; }?> hidden>&nbsp;
			<input type="button" id="btn1" value="ค้นหา" <?php if($statusLock==1){ echo "disabled=true"; }?> hidden />
			<input type="button" value="กลับไปหน้าค้นหา" onClick="window.location='frm_Index.php'">
			<?php if($statusPay=="revTranID"){?> <input type="button" value="กลับไปหน้าเงินโอน" onclick="window.location='../thcap/frm_Index_finance.php'"> <?php } ?>
			<?php
			if($statusPay=="revTranID")
			{ // ถ้าเป็นเงินโอน ให้เลือกสัญญาอื่นๆ ที่มีผู้กู้เหมือนกันได้ โดยตัวเลิอกจะเหมือนกับเมนู (THCAP) ย้ายเงินข้ามสัญญา
				echo "<br>";
				echo "<form method=\"post\" name=\"frmSelectCon\" action=\"Payments_history.php\">";
				echo "เลขที่สัญญา ที่มีผู้กู้เดียวกัน : ";
				echo "<select name=\"ConID\" id=\"ConID\">";
				echo "<option value=\"\">--เลือกสัญญา--</option>";
				
				$q = pg_query("select count(\"contractID\") as cc FROM \"thcap_ContactCus\" where \"contractID\" = '$ConID' group by \"contractID\" ");
				list($count) = pg_fetch_array($q);
				$qq = pg_query("select \"contractID\" FROM \"thcap_ContactCus\"  group by \"contractID\" having count(\"contractID\") = '$count' order by \"contractID\"");
				while($w = pg_fetch_array($qq))
				{ 
					$conid1 = $w["contractID"];	
					$p = 0;
					$qqq = pg_query("select * FROM \"thcap_ContactCus\" where \"contractID\" = '$ConID' ");
					while($reqqq = pg_fetch_array($qqq))
					{
						$CusID1 = $reqqq['CusID'];
						$CusState1 = $reqqq['CusState'];
						$qqq1 = pg_query("select * FROM \"thcap_ContactCus\" where \"CusID\" = '$CusID1' and \"CusState\"='$CusState1' and \"contractID\" = '$conid1' ");
						$rowqqq1 = pg_num_rows($qqq1);
						if($rowqqq1 == 0){
							$p += 1;
						}
					}
					
					if($p == 0)
					{
						if($conid1 != $ConID)
						{
							echo "<option value=\"$conid1\">$conid1</option>";
						}
					}
				}
				echo "</select>";
				echo "<input type=\"hidden\" name=\"revTranID\" value=\"$revTranID\" >";
				echo "<input type=\"hidden\" name=\"statusLock\" value=\"$statusLock\" >";
				echo "<input type=\"hidden\" name=\"statusPay\" value=\"$statusPay\" >";
				echo "<input type=\"submit\" value=\"ใช้เงินกับสัญญานี้\" onClick=\"return chkSelectOtherCon();\" />";
				echo "</form>";
			}
			?>
</center><hr>	
	<div align="right" class="banner" id="cusRemainMoney" name="cusRemainMoney" ></div>	
	<div align="right" class="bannerMinus" id="cusRemainMoneyMinus" name="cusRemainMoneyMinus" ></div>	
<form method="post" name="myfrm" action="Process_Payment.php"><!--Process_Payment.php-->
<?php
$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลักจาก mysql
$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$ConID' and \"CusState\"='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
	$typecus=trim($resnamemain["type"]); //1 คือบุคคลธรรมดา 2 คือนิติบุคคล
}


//$qry_name=pg_query("select * from public.\"thcap_temp_otherpay_debt\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" is null order by \"typePayRefDate\" ");
$qry_name=pg_query("select * from public.\"thcap_v_otherpay_debt_realother_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
$numrows = pg_num_rows($qry_name);
/*if($numrows == 0)
{
	echo "<center><h3><b>ผู้กู้หลัก : $name3</b></h3></center>";
	echo "<center><h2>ไม่พบข้อมูลหนี้ที่ค้างชำระค่าอื่นๆชั่วคราวของสัญญา: $ConID</h2>";
	echo "<center><h2>กรุณารอสักคู่ ระบบกำลังจะนำท่านไปยังหน้า \"(THCAP)รับชำระเงินกู้จำนองชั่วคราว\"</h2>";
	echo "<meta http-equiv='refresh' content='4; URL=../Payments_Mortgage_Temporary/Payments_history.php?ConID=$ConID'>";
	echo "</center>";
	//echo "<input type=\"button\" value=\"กลับไปหน้าค้นหา\" onclick=\"window.location='frm_Index.php'\"></center>";
}
else
{*/
//------------------หาข้อมูลจาก (THCAP) ตารางแสดงการผ่อนชำระ

	// หาค่าจาก function ใน postgres
	$backAmt = pg_query("select \"thcap_backAmt\"('$ConID','$currentDate')");
	$backAmt = pg_fetch_result($backAmt,0); // ยอดค้างชำระปัจจุบัน
	
	$backDueDate = pg_query("select \"thcap_backDueDate\"('$ConID','$currentDate')");
	$backDueDate = pg_fetch_result($backDueDate,0); // วันที่เริ่มค้างชำระ
	
	$nextDueAmt = pg_query("select \"thcap_nextDueAmt\"('$ConID','$currentDate')");
	$nextDueAmt = pg_fetch_result($nextDueAmt,0); // จำนวน
	
	$nextDueDate = pg_query("select \"thcap_nextDueDate\"('$ConID','$currentDate')");
	$nextDueDate = pg_fetch_result($nextDueDate,0); // ยอดที่จะครบกำหนดในวันที่
	// จบการหาค่าจาก function ใน postgres
?>
<center>
<?php
// หารหัสการจ่ายที่เป็นค่างวด
$qry_getMinPayType = pg_query("select account.\"thcap_mg_getMinPayType\"('$ConID')");
$rec_getMinPayType = pg_fetch_result($qry_getMinPayType,0);

// หาว่ามีการขอยกเลิกใบเสร็จค่างวดของเลขที่สัญญานี้อยู่หรือไม่ ถ้ามีต้องรับชำระค่างวดไม่ได้
$haveCancelPayment = 0;
$qry_cancelPayment = pg_query("select \"receiptID\" from \"thcap_temp_receipt_cancel\" where \"contractID\" = '$ConID' and \"approveStatus\" = '2' ");
while($rec_cancelPayment = pg_fetch_array($qry_cancelPayment))
{
	$cancelReceiptID = $rec_cancelPayment["receiptID"]; // เลขที่ใบเสร็จที่ขอยกเลิก
	$qry_chkDebtID = pg_query("select \"debtID\" from \"thcap_temp_receipt_otherpay\" where \"receiptID\" = '$cancelReceiptID' ");
	$res_chkDebtID = pg_fetch_result($qry_chkDebtID,0); // รหัสหนี้ที่ขอยกเลิก
	if($res_chkDebtID == "")
	{
		$haveCancelPayment++;
	}
	else
	{
		$qry_chkTypePay = pg_query("select \"typePayID\" from \"thcap_temp_otherpay_debt\" where \"debtID\" = '$res_chkDebtID' ");
		$rec_chkTypePay = pg_fetch_result($qry_chkTypePay,0);
		
		if($rec_chkTypePay == $rec_getMinPayType){$haveCancelPayment++;}
	}
}
if($haveCancelPayment > 0){echo "<div align=\"left\"><font color=\"#FF0000\"><b>* ไม่สามารถรับชำระค่างวดได้ เนื่องจากเลขที่สัญญานี้มีการรออนุมัติยกเลิกใบเสร็จค่างวดอยู่ ถ้าต้องการรับชำระค่างวดกรุณาแจ้งผู้ดูแล</font></b></div>";}

// ตรวจสอบก่อนว่ามีการรออนุมัติย้ายเงินพักรอตัดรายการของสัญญานี้หรือไม่ (เฉพาะกรณี ย้ายออกไป)
$qry_chk998 = pg_query("select \"begin_trans_type\" from \"thcap_transfermoney_c2c_temp\" where \"begin_conid\" = '$ConID' and \"appstatus\" = '2' and \"begin_trans_type\" = '998' ");
$row_chk998 = pg_num_rows($qry_chk998);
if($row_chk998 > 0)
{ // ถ้ามีการรออนุมัติย้ายเงินพักรอตัดรายการของสัญญานี้หรือไม่ (เฉพาะกรณี ย้ายออกไป)
	$haveWait998 = "have";
	echo "<div align=\"left\"><font color=\"#FF0000\"><b>* ไม่สามารถใช้ เงินพักรอตัดรายการ ในการรับชำระเงินได้ เนื่องจากสัญญานี้มีการรออนุมัติย้ายเงินข้ามสัญญาอยู่ ถ้าต้องการใช้ เงินพักรอตัดรายการ กรุณาแจ้งผู้ดูแล</b></font></div>";
}

// ตรวจสอบก่อนว่ามีการรออนุมัติย้ายเงินคำประกันของสัญญานี้หรือไม่ (เฉพาะกรณี ย้ายออกไป)
$qry_chk997 = pg_query("select \"begin_trans_type\" from \"thcap_transfermoney_c2c_temp\" where \"begin_conid\" = '$ConID' and \"appstatus\" = '2' and \"begin_trans_type\" = '997' ");
$row_chk997 = pg_num_rows($qry_chk997);
if($row_chk997 > 0)
{ // ถ้ามีการรออนุมัติย้ายเงินคำประกันของสัญญานี้หรือไม่ (เฉพาะกรณี ย้ายออกไป)
	$haveWait997 = "have";
	echo "<div align=\"left\"><font color=\"#FF0000\"><b>* ไม่สามารถใช้ เงินค้ำประกัน ในการรับชำระเงินได้ เนื่องจากสัญญานี้มีการรออนุมัติย้ายเงินข้ามสัญญาอยู่ ถ้าต้องการใช้ เงินค้ำประกัน กรุณาแจ้งผู้ดูแล</b></font></div>";
}

require("page/block1.php");
require("page/block2.php");

// ถ้าเป็นสัญญาประเภท "JOINT_VENTURE" และวันที่รับชำระ น้อยกว่าวันที่ 1 กุมภัาพันธ์ 2557
if($chk_con_type == "JOINT_VENTURE" && $dateContact < '2014-02-01'){require("page/blockAdviser.php");}

if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING" || $chk_con_type == "GUARANTEED_INVESTMENT" || $chk_con_type == "FACTORING" || $chk_con_type == "SALE_ON_CONSIGNMENT"){require("page/blockHP.php");} // ถ้าเป็นสินเชื่อประเภท HP(Hire Purchase - เช่าซื้อ)
else{require("page/block3.php");} // ถ้าเป็นสินเชื่อประเภทอื่น

require("page/block4.php");
?>
</center>
<input type="hidden" name="statusOverWhtHP" id="statusOverWhtHP" value="0"> <!-- ใช้เช็คว่ามีการเปลี่ยนภาษีหัก ณ ที่จ่ายของสัญญา HP, FL, OL เกินหรือไม่ -->
<?php
//}
?>
</form>
</body>

<script>

//โค้ดแสดงเวลาปัจจุบัน
var limit="<?php echo DATE('H:i:s'); ?>"
var parselimit=limit.split(":");
parselimit=parselimit[0]*60*60+parselimit[1]*60+parselimit[2]*1;

function showFilled(Value) {
	return (Value > 9) ? "" + Value : "0" + Value;
}

function StartClock24(a)
{
	if(a==0){
		if(document.getElementById("statusLock").value!=1){ //กรณีที่ไม่ได้ Lock (คือไม่ได้มาจากหน้าเงินโอน)	
			timeoutID=setInterval(function(){
				parselimit+=1
				curhour=Math.floor(parselimit/3600)%24;
				curmin=Math.floor(parselimit/60)%60;
				cursec=parselimit%60;
				document.myfrm.timeStamp.value = showFilled(curhour) + ":"  + showFilled(curmin) + ":" + showFilled(cursec);
			},1000);
		}
	}else{
		//clearInterval(timeoutID);
		if(document.getElementById("statusLock").value!=1){ //กรณีที่ไม่ได้ Lock (คือไม่ได้มาจากหน้าเงินโอน)	
			document.myfrm.timeStamp.value="23:59:59";
		}
	}
}
function checkdate(){
	//ตรวจสอบว่าวันที่เลือกถ้ากรณีไม่ Lock เป็นวันที่มากกว่า 1 พ.ย. 55 หรือไม่ ถ้าใช่ไม่ให้ใช้วันนั้น
	if(document.getElementById("statusLock").value!=1){ //กรณีเป็นไม่ใช่ Lock (คือมาจากหน้าโอนเงิน)		
		var bychannel=document.getElementById("byChannelPost").value;
		var istranpay=bychannel.split(",");
		var datecondition="2012-11-01";
			
		//ถ้าธนาคารที่เลือกมี isTranPay=1 ให้ตรวจสอบว่าวันที่เลือกเกิน 1 พ.ย. 55 หรือไม่
		if(istranpay[1]==1){
			if(document.getElementById("receiveDatePost").value>=datecondition){
				alert("วันที่เลือกจะต้องไม่เกิน 2012-10-31");
				document.getElementById("receiveDatePost").value="2012-10-31";
			}
		}	
	}
}
//จบโค้ดแสดงเวลาปัจจุบัน

function CalWhtMain()
{ // หาจำนวน ภาษีหัก ณ ที่จ่าย ของค่างวด
	if(document.getElementById("interestRatePost_Payment").checked == true)
	{
		var contractID; // เลขที่สัญญา
		contractID = '<?php echo $ConID; ?>';
		
		var datalist = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist
			  url: "CalWhtMain.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
			  data:"datewht="+document.getElementById("receiveDatePost").value+"&contractID="+contractID, // ส่งตัวแปร GET ชื่อ datewht ให้มีค่าเท่ากับ ค่าของ date
			  async: false
		}).responseText;
		//$("#sum3").html(datalist); // นำค่า datalist มาแสดงใน twxtbox ที่ชื่อ sum3
		$("#sum3").val(datalist);
		$("#CHKsum3").val(datalist);
		
		 var sumpost3;
		 sumpost3 = parseFloat(document.getElementById("receiveAmountPost3").value)-parseFloat(document.getElementById("sum3").value);
		 document.getElementById("receiveAmountPost3").value=sumpost3.toFixed(2);
		 blfinance();
	}
	else
	{
		$("#sum3").val(0.00);
		$("#CHKsum3").val(0.00);
	}
		
	checkdate();
	//ตรวจสอบว่าวันที่จ่ายตรงกับปัจจุบันหรือไม่ ถ้าไม่ตรงให้เวลาแสดงค่าคงที่
	if(document.getElementById("receiveDatePost").value==document.getElementById("datenow").value)
	{
		//ให้แสดงเวลาไปเรื่อยๆ 
		StartClock24(0);
	}
	else
	{
		//ให้แสดงเวลาเป็น 23:59:59
		StartClock24(1);
	}
}
CalWhtMain();

</script>
</html>