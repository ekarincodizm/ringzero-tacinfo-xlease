<?php
include("../../config/config.php");
//path = nw/Payments_Mortgage_Temporary/frm_Index.php
$ReNew = $_GET["ConID"];
$conid1 = $_GET["conid1"];
//ทดสอบ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รับชำระเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function backAmt() //เรียกใช้ หน้า Loadbackamt.php
{  
   if((document.form1.ConID.value.length==15)||(document.form1.ConID.value.length==20)) //ตรวจสอบความยาวของ เลขที่สัญญา
	{   nameprimaryborrower();
		 var databackamt = $.ajax({    
         url: "Loadbackamt.php", 
         data:"idno="+$("#ConID").val()+"&datepicker="+$("#receiveDatePostMainPage").val(),
         async: false  
        }).responseText;
        $("#amt").html(databackamt); 
	}
	
	chkCreditType();
}
function nameprimaryborrower(){
	var name = $.ajax({    
         url: "frm_nameprimaryborrower.php", 
         data:"idno="+$("#ConID").val(),
         async: false  
        }).responseText;
        $("#primaryborrower").html(name); 		
}	
</script>
<script type="text/javascript">
$(document).ready(function(){
	if($("#ConID").val()!=""){backAmt();nameprimaryborrower();}
	$("#ConID").autocomplete({
      //  source: "s_contractID.php",
		 source: "s_idall.php",
        minLength:1
    });	
	$("#contractUseMoney").autocomplete({
		source: "s_idall.php",
        minLength:1
    });  
	
    $('#btn1').click(function(){
    // $("#panel").load("Payments_history.php?ConID="+ $("#ConID").val());
		window.location.href="Payments_history.php?ConID="+ $("#ConID").val();
    });
});

$(document).ready(function(){
	$("#receiveDatePostMainPage").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});

function checkdate()
{
	//ตรวจสอบว่าวันที่เลือกถ้ากรณีไม่ Lock เป็นวันที่มากกว่า 1 พ.ย. 55 หรือไม่ ถ้าใช่ไม่ให้ใช้วันนั้น		
	var bychannel=document.getElementById("byChannelMainPage").value;
	var istranpay=bychannel.split(",");
	var datecondition="2012-11-01";
		
	//ถ้าธนาคารที่เลือกมี isTranPay=1 ให้ตรวจสอบว่าวันที่เลือกเกิน 1 พ.ย. 55 หรือไม่
	if(istranpay[1]==1)
	{
		if(document.getElementById("receiveDatePostMainPage").value>=datecondition)
		{
			alert("วันที่เลือกจะต้องไม่เกิน 2012-10-31");
			document.getElementById("receiveDatePostMainPage").value="2012-10-31";
		}
	}
	
	// ถ้าเลือกช่องทางการจ่าย "เงินสด - STM" หรือ "เงินสด-ADV"
	if(istranpay[0]=="990" || istranpay[0]=="991")
	{
		document.getElementById('contractDiv').style.visibility = 'visible';
	}
	else
	{
		document.getElementById('contractDiv').style.visibility = 'hidden';
	}
}

function chkCreditType()
{
	if((document.form1.ConID.value.length==15)||(document.form1.ConID.value.length==20)) //ตรวจสอบความยาวของ เลขที่สัญญา
	{
		$.post("getCreditType.php",{
			ConID : $("#ConID").val()
		},
		function(data){
			if(data == "JOINT_VENTURE")
			{
				document.getElementById('payDiv').style.visibility = 'visible';
			}
			else
			{
				document.getElementById('payDiv').style.visibility = 'hidden';
				document.getElementById('cusPayMoney').value = '';
			}
		});
	}
	else
	{
		document.getElementById('payDiv').style.visibility = 'hidden';
		document.getElementById('cusPayMoney').value = '';
	}
}

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

// ตรวจสอบเลขที่สัญญาหลัก
function chkContract()
{
	var bychannelRef=document.getElementById("byChannelMainPage").value;
	var istranpayRef=bychannelRef.split(",");
	
	var chkcontinue = 1; // ตรวจสอบว่าสามารถทำงานต่อไปได้หรือไม่ 1 ได้ 0 ไม่ได้
	
	if(document.getElementById('ConID').value == "")
	{
		alert('กรุณาระบุเลขที่สัญญา');
		return false;
	}
	else
	{
		if((document.form1.ConID.value.length==15)||(document.form1.ConID.value.length==20)) //ตรวจสอบความยาวของ เลขที่สัญญา
		{
			$.post("getCreditType.php",{
				ConID : $("#ConID").val()
			},
			function(data){
				if(data == "JOINT_VENTURE")
				{
					if(document.getElementById('cusPayMoney').value == '')
					{
						chkcontinue = 0;
						alert('กรุณาระบุ จำนวนเงินที่ต้องการจะชำระ');
						return false;
					}
				}
			});
		}
		
		$.post("chk_contract.php",{
			ConID : $("#ConID").val()
		},
		function(data){
			if(data == 0)
			{ // ถ้าไม่มีเลขที่สัญญาในระบบ
				alert('ขออภัย!! ไม่มีเลขที่สัญญา '+$("#ConID").val()+' อยู่ในระบบ กรุณาเลือกเลขที่สัญญาใหม่');
				return false;
			}
			else
			{		
				// ถ้าเลือกช่องทางการจ่าย "เงินสด - STM" หรือ "เงินสด-ADV"
				if(istranpayRef[0]=="990" || istranpayRef[0]=="991")
				{
					if(document.getElementById('contractUseMoney').value == "")
					{
						alert('กรุณาระบุเลขที่สัญญาที่ใช้เงิน');
						return false;
					}
					else
					{
						$.post("chk_contract.php",{
							ConID : $("#contractUseMoney").val()
						},
						function(data){
							if(data == 0)
							{ // ถ้าไม่มีเลขที่สัญญาในระบบ
								alert('ขออภัย!! ไม่มีเลขที่สัญญาที่ใช้เงิน '+$("#contractUseMoney").val()+' อยู่ในระบบ กรุณาเลือกเลขที่สัญญาที่ใช้เงินใหม่');
								return false;
							}
							else
							{
								if((document.form1.ConID.value.length==15)||(document.form1.ConID.value.length==20)) //ตรวจสอบความยาวของ เลขที่สัญญา
								{
									$.post("getCreditType.php",{
										ConID : $("#ConID").val()
									},
									function(data){
										if(data == "JOINT_VENTURE")
										{
											if(document.getElementById('cusPayMoney').value == '')
											{
												chkcontinue = 0;
												alert('กรุณาระบุ จำนวนเงินที่ต้องการจะชำระ');
												return false;
											}
										}
									});
								}
		
								if(chkcontinue == 1)
								{ // ถ้าไม่มีข้อผิดพลาด
									document.form1.submit();
								}
							}
						});
					}
				}
				else
				{
					if((document.form1.ConID.value.length==15)||(document.form1.ConID.value.length==20)) //ตรวจสอบความยาวของ เลขที่สัญญา
					{
						$.post("getCreditType.php",{
							ConID : $("#ConID").val()
						},
						function(data){
							if(data == "JOINT_VENTURE")
							{
								if(document.getElementById('cusPayMoney').value == '')
								{
									chkcontinue = 0;
									alert('กรุณาระบุ จำนวนเงินที่ต้องการจะชำระ');
									return false;
								}
							}
						});
					}
		
					if(chkcontinue == 1)
					{ // ถ้าไม่มีข้อผิดพลาด
						document.form1.submit();
					}
				}
			}
		});
	}
	return false;
}
</script>

<script language="JavaScript">
<!--
function windowOpen() {
var
myWindow=window.open('search2.php','windowRef','width=1600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
//--></script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<form name="form1" id="form1" method="post" action="Payments_history.php">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center;padding-bottom: 10px;"><h2>(THCAP) รับชำระเงิน</h2></div>

			<fieldset><legend><B>ค้นหาข้อมูล</B></legend>

			<div class="ui-widget" align="center">

			<div style="margin:0">
			<b>เลขที่สัญญา</b>&nbsp;
			<?php 			
			if($conid1!=""){
			   echo "<input id=\"ConID\" name=\"ConID\" size=\"60\" value=\"$conid1\"onfocus=\"backAmt();\" onchange=\"backAmt();\" 		 onkeyup=\"backAmt();\"/>&nbsp;";
			}
			else{
			   echo "<input id=\"ConID\" name=\"ConID\" size=\"60\" value=\"$ReNew\" onfocus=\"backAmt();\" onchange=\"backAmt();\" onkeyup=\"backAmt();\"/>&nbsp;";			
			}
			?>			
			<!--<input type="button" id="btn1" value="ค้นหา"/>-->
			
			<!--<input name="openPopup" type="button" id="openPopup" onClick="Javascript:windowOpen();" value="ค้นหาจากชื่อผู้กู้หลัก/ร่วม" />-->
			
			<br><br>
			ช่องทางการจ่าย :
			<select name="byChannelMainPage" id="byChannelMainPage" class="textbox_tb3" onchange="checkdate();">
				<?php
					//ดึงข้อมูลจากฐานข้อมูล
					$qrychannel=pg_query("select \"BID\",\"BAccount\",\"BName\",\"isTranPay\" from \"BankInt\" where \"BCompany\"='THCAP' and \"isChannel\"='1' and \"isSelectable\" = '1' order by \"BID\"");
					while($reschn=pg_fetch_array($qrychannel)){
						list($BID,$BAccount,$BName,$isTranPay)=$reschn;
						?>
						<option value=<?php echo $BID.",$isTranPay";?>><?php echo "$BAccount-$BName"; ?></option>
						<?php
					}
				?>
			</select>
			&nbsp;
			วันที่จ่าย : <input type="text" id="receiveDatePostMainPage" name="receiveDatePostMainPage" value="<?php echo nowDate(); ?>" size="15" style="text-align: center;" onchange="checkdate(); backAmt();">
			
			<input type="hidden" name="statusLock" value="2">
			
			<br><br>
			<div id="contractDiv" name="contractDiv"><span>เลขที่สัญญาที่ใช้เงิน : </span><input type="text" id="contractUseMoney" name="contractUseMoney" size="50"></div>
			
			<br>
			<div id="payDiv" name="payDiv"><span>จำนวนเงินที่จะรับชำระ : </span><input type="text" id="cusPayMoney" name="cusPayMoney" size="20" style="text-align: right;" onkeypress="check_num(event);"></div>
			<br>
			<div id="primaryborrower">			
			</div>
			<div id="amt">
			</div>
			<br><br>
			<input type="button"  value="  NEXT  " onclick="return chkContract();"/>
			
			</div>
			<div id="panel" style="padding-top: 20px;"></div>

			</div>

			 </fieldset>

        </td>
    </tr>
</table>
</form>

<?php
if($conid1!=""){}
else{
	if($ReNew != "")
	{
?>
	<script type="text/javascript">
        $("#panel").load("Payments_history.php?ConID=" + $("#ConID").val());
	</script>
<?php
	}
}
?>
<script>
checkdate();
chkCreditType();
</script>

</body>
</html>