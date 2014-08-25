<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");

$sum=$_GET['summ'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-- (THCAP) ตัวคำนวณยอดผ่อน --</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/jQueryAlertDialogs/_assets/js/jquery.ui.draggable.js" type="text/javascript"></script>   
<script src="../../jqueryui/jQueryAlertDialogs/_assets/js/jquery.alerts.js" type="text/javascript"></script>
<link href="../../jqueryui/jQueryAlertDialogs/_assets/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />   
 
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
  }
}

$(document).ready(function(){ 
$("#panel1").load('cal_loan_minpay.php'); //(เงินกู้) คำนวณหายอดจ่ายขั้นต่ำ
	$("#pay").change(function(){ 
		var src = $('#pay option:selected').attr('value');
		document.getElementById('btn_cal').disabled = false;
		if(src == 'pay01'){	
			$("#panel1").load('cal_loan_minpay.php'); //(เงินกู้) คำนวณหายอดจ่ายขั้นต่ำ
		}else if(src == 'pay02'){	
			$("#panel1").load('cal_loan_monthlypayment.php'); //(เงินกู้) คำนวณหาจำนวนเดือนที่ผ่อน
		}else if(src == 'pay03'){
			$("#panel1").load('cal_factoring_chqguarantee.php'); //(เงินกู้) คำนวณยอดเช็คค้ำแฟคตอริ่ง		
		}else if(src == 'pay04'){
			$("#panel1").load('cal_lease_monthlypayment.php'); //(เช่าซื้อ/เช่า) คำนวณหายอดผ่อนต่อเดือน	
		}else if(src == 'pay05'){
			$("#panel1").load('cal_lease_flattoeff.php'); //(เช่าซื้อ/เช่า) หาอัตราดอกเบี้ย Effective rate จาก Flat rate
			document.getElementById('btn_cal').disabled = true;
		}else if(src == 'pay06'){
			$("#panel1").load('cal_usufruit_monthlypayment.php'); //(สิทธิเก็บกิน) คำนวณการผ่อนสัญญา			
		}else if(src == 'pay07'){
			$("#panel1").load('cal_factoring_pnamount.php'); //(แฟคตอริ่ง) คำนวณยอดตั๋ว
		}		
	});
});

function chkaction(frmapp){
	var src = $('#pay option:selected').attr('value');
		if(src == 'pay01'){	
			chkFact(frmapp);
			return false;	
		}else if(src == 'pay02'){	
			chkFact(frmapp);
			return false;
		}else if(src == 'pay03'){		
			chkFact(frmapp);
			return false;				
		}else if(src == 'pay04'){	
			chkFact(frmapp);
			return false;			
		}else if(src == 'pay05'){	
			actionfat(0,frmapp);			
		}else if(src == 'pay06'){	
			chkFact(frmapp);	
			return false;		
		}else if(src == 'pay07'){	
			chkFact(frmapp);	
			return false;		
		}else{
			return false;
		}
}

function actionfat(value,frmapp){
	if(value >= 1){
		return false;
	}else{
		document.getElementById('btn_cal').value = 'กำลังคำนวณ...';
		document.getElementById('btn_cal').disabled = true;
		var src = $('#pay option:selected').attr('value');
		if(src == 'pay01'){					
				$.post("cal_loan_minpay_form.php", { 
					pay: document.frm1.pay.value,
					month: document.frm1.month.value,
					datestart: document.frm1.datestart.value,
					datestartcon: document.frm1.datestartcon.value,
					interest: document.frm1.interest.value,
					tbmoney: document.frm1.tbmoney.value,
					payday: document.frm1.payday.value,
					paytype: document.frm1.pay.value,
					payother: document.frm1.payother.value,
					percentpayother: document.frm1.percentpayother.value
				},
				function(data){
					var n=data.search("Maximum execution time of 30 seconds");
						if(n > 0){
							alert('การคำนวณค่าดังกล่าวอาจใช้เวลานานเกินกว่าระยะเวลาที่กำหนดไว้ \nกรุณาระบุเดือนให้สอดคล้องกับเงินกู้');					
						}else{
							alert(data);
							var d=data.search("จำนวนเดือน ต้องเป็นตัวเลขจำนวนเต็มเท่านั้น");
							if(d < 0)
							{
								if(confirm('คุณต้องการดูรายการ ยอดการชำระล่วงหน้า ใช่หรือไม่ ?')==true){
									newWindow = window.open("cal_loan_minpay_pdf.php?capital="+document.frm1.tbmoney.value+"&datestart="+document.frm1.datestart.value+"&datestartcon="+document.frm1.datestartcon.value+"&interest="+document.frm1.interest.value+"&month="+document.frm1.month.value+"&payday="+document.frm1.payday.value+"&paytype="+document.frm1.pay.value+"&percentpayother="+document.frm1.percentpayother.value+"&payother="+document.frm1.payother.value);	
								}
							}
						}	
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';
				});	
		}else if(src == 'pay02'){	
				$.post("cal_loan_monthlypayment_form.php", { 
					pay: document.frm1.pay.value,
					tbmoney1: document.frm1.tbmoney1.value,
					interest1: document.frm1.interest1.value,
					moneypay: document.frm1.moneypay.value,
					datestart: document.frm1.datestart.value,
					datestartcon: document.frm1.datestartcon.value,
					payday: document.frm1.payday.value,
					paytype: document.frm1.pay.value	
				},
				function(data,status){
						var n=data.search("Maximum execution time of 30 seconds");
						if(n > 0){
							alert('การคำนวณค่าดังกล่าวอาจใช้เวลานานเกินกว่าระยะเวลาที่กำหนดไว้ \nกรุณาระบุยอดที่ผ่อนให้สอดคล้องกับเงินกู้');					
						}else{
							alert(data);
							if(confirm('คุณต้องการดูรายการ ยอดการชำระล่วงหน้า ใช่หรือไม่ ?')==true){
								newWindow = window.open("cal_loan_monthlypayment_pdf.php?capital="+document.frm1.tbmoney1.value+"&datestart="+document.frm1.datestart.value+"&datestartcon="+document.frm1.datestartcon.value+"&interest="+document.frm1.interest1.value+"&moneypay="+document.frm1.moneypay.value+"&payday="+document.frm1.payday.value+"&paytype="+document.frm1.pay.value);	
							}
						}					
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';
				});	
		}else if(src == 'pay03'){		
				$.post("cal_factoring_chqguarantee_form.php", { 
					pay: document.frm1.pay.value,
					ticketmoney: document.frm1.ticketmoney.value,
					realmoney: document.frm1.realmoney.value,
					interestrate: document.frm1.interestrate.value,
					datestart: document.frm1.datestart.value,
					dateend: document.frm1.dateend.value
				},
				function(data){
					alert(data);
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';
				});			
		}else if(src == 'pay04'){	
				if($("#notvat").attr("checked")==true){
						var vatchk = 'notvat';
				}else{
						var vatchk = 'sumvat';
				}
				$.post("cal_lease_monthlypayment_form.php", { 
					pay: document.frm1.pay.value,
					datestart: document.frm1.datestart.value,
					investment: document.frm1.investment.value,
					vatcal: document.frm1.vatcal.value,
					interest: document.frm1.interest.value,
					month: document.frm1.month.value,
					vat : vatchk
				},
				function(data){
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';	
					jConfirm(data, 'ผลการคำนวณ', function(r) {
						if(r){
							newWindow = window.open("cal_lease_monthlypayment_pdf.php?pay="+document.frm1.pay.value+"&datestart="+document.frm1.datestart.value+"&investment="+document.frm1.investment.value+"&vatcal="+document.frm1.vatcal.value+"&interest="+document.frm1.interest.value+"&month="+document.frm1.month.value+"&vat="+vatchk);						
						}
					});           					
				});			
		}else if(src == 'pay05'){	
				if($("#notvat").attr("checked")==true){
						var vatchk = 'notvat';
				}else{
						var vatchk = 'sumvat';
				}
				var genDate = new Array();
				var genMinPay = new Array();
				for(var i=1; i <= document.frm1.month.value; i++){
					genDate[i] = document.getElementById('genDate'+i).value;
					genMinPay[i] = document.getElementById('genMinPay'+i).value;
				}	
				$.post("cal_lease_flattoeff_form.php", { 
					genDatedata: genDate,
					genMinPaydata: genMinPay,
					datestart: document.frm1.datestart.value,
					interest: document.frm1.interest.value,
					investment: document.frm1.investment.value,
					vat : vatchk
				},
				function(data){	
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';
					jAlert('success', data, 'ผลการคำนวณ'); 			
				});	
		}else if(src == 'pay06'){	
				$.post("cal_usufruit_monthlypayment_form.php", {
					pay: document.frm1.pay.value,	
					month: document.frm1.month.value,
					datestart: document.frm1.datestart.value,
					datestartcon: document.frm1.datestartcon.value,
					interest: document.frm1.interest.value,
					tbmoney: document.frm1.tbmoney.value,
					payday: document.frm1.payday.value,
					payother: document.frm1.payother.value,
					nw_province: document.frm1.nw_province.value
				},
				function(data){
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';
					jConfirm(data, 'ผลการคำนวณ', function(r) {
						if(r){
								newWindow = window.open("cal_usufruit_monthlypayment_pdf.php?pay="+document.frm1.pay.value+"&datestart="+document.frm1.datestart.value+"&tbmoney="+document.frm1.tbmoney.value+"&interest="+document.frm1.interest.value+"&month="+document.frm1.month.value+"&datestartcon="+document.frm1.datestartcon.value+"&payday="+document.frm1.payday.value+"&payother="+document.frm1.payother.value+"&nw_province="+document.frm1.nw_province.value);						
							}
					});
				});
				
		}else if(src == 'pay07'){	
				if($("#payout").attr("checked")==true){
						var chk = 'payout';
				}else{
						var chk = 'payin';
				}
				$.post("cal_factoring_pnamount_form.php", { 
					pay: document.frm1.pay.value,
					datestart: document.frm1.datestart.value,
					pay_cus: document.frm1.pay_cus.value,
					factoring: document.frm1.factoring.value,
					dateend: document.frm1.dateend.value,
					interest: document.frm1.interest.value,
					check: chk
				},
				function(data){	
					document.getElementById('btn_cal').disabled = false;
					document.getElementById('btn_cal').value = 'คำนวณ';
					jAlert('success', data, '(แฟคตอริ่ง) คำนวณยอดตั๋ว'); 			
				});	
		}else{
			return false;
		}
	}
}
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>
<body>
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
		<div style="float:left">&nbsp;</div>
		<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;">
		<span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
		<div style="clear:both;"></div>
		<form name="frm1" method="POST">
		<div align="center">
		<div class="style5" style="width:auto; height:40px; padding-left:10px;">
		<table width="850" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			  <td><fieldset><legend><h3> (THCAP) ตัวคำนวณยอดผ่อน </h3></legend>
						<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td colspan="2">
								<fieldset><legend><h4> คำนวณหา </h4></legend>
									<center>
										<select id="pay" name="pay">
										<option value="pay01">(เงินกู้) คำนวณหายอดจ่ายขั้นต่ำ</option>	
										<option value="pay02">(เงินกู้) คำนวณหาจำนวนเดือนที่ผ่อน</option>	
										<option value="pay03">(เงินกู้) คำนวณยอดเช็คค้ำแฟคตอริ่ง</option>
										<option value="pay04">(เช่าซื้อ/เช่า) คำนวณหายอดผ่อนต่อเดือน</option>
										<option value="pay05">(เช่าซื้อ/เช่า) หาอัตราดอกเบี้ย Effective rate จาก Flat rate</option>	
										<option value="pay06">(สิทธิเก็บกิน) คำนวณการผ่อนสัญญา</option>
										<option value="pay07">(แฟคตอริ่ง) คำนวณยอดตั๋ว</option>
										</select>
										<br>
									</center>
								</fieldset>
								</td> 			
							</tr>		
						</table>
				<div id="panel1" style="padding-top: 10px;"></div>
				</fieldset>
			  </td>
			</tr>		
			 </div>
			<tr>
				<td colspan="4" align="center">
					<input style="width:100px; height:40px;" type="button" id="btn_cal" value="คำนวณ" onclick="return chkaction(this.form);" >
					<input style="width:100px; height:40px;" type="button" value="ปิด" onclick="window.close();">
				</td>
			</tr>
		</table>
		</div>
		</form>
        </td>
    </tr>
</table>
</body>
</html>