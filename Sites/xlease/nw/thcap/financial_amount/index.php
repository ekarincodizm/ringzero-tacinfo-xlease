<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../../../config/config.php");
list($contractID,$useless) = explode("#",$_POST['idno']);
if($contractID == ""){
	$contractID = $_GET["conid"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) เพิ่มวงเงินสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
$(document).ready(function(){

    $("#idno").autocomplete({
        source: "../../fapn_statement/s_idno.php",
        minLength:1
    });
	
	$("#chkvat").click(function(){
		if($("#chkvat").attr('checked')==false){
			$("#valuevat").val('');	
			document.getElementById("valuevat").readOnly = true;			
		}else{
			$("#valuevat").val('1.00');	
			document.getElementById("valuevat").readOnly = false;	
		}
	 });
	
	
});

function cal(mon){
		var money = $("#hdmoney").attr('value');
		var vat = $("#valuevat").attr('value');	
		
		if(mon == ""){
			mon = 0;
		}else if(mon == 'a'){
			var mon = $("#addmoney").attr('value');
				if(mon == ""){
					mon = 0;					
				}
		}else if(mon == 'b'){
			if($("#chkvat").attr('checked')==false){
				vat = 0;
				var mon = $("#addmoney").attr('value');
					if(mon == ""){
						mon = 0;					
					}
			}else{
				var mon = $("#addmoney").attr('value');
					if(mon == ""){
						mon = 0;					
					}
					vat = 1;
			}			
			
		}
		
		
		if(vat == ""){
			vat = 0;
		}
		
		var summon = parseFloat(money) + parseFloat(mon);		
		var more1 = parseFloat(mon) * parseFloat(vat) / 100;
		var more2 = (7 / 100) * parseFloat(more1);
		var summore = parseFloat(more1) + parseFloat(more2);
		
		
		$("#textmoney").text(addCommas(summon.toFixed(2)));
		$("#textmore1").text(addCommas(more1.toFixed(2)));
		$("#textmore2").text(addCommas(more2.toFixed(2)));
		$("#textsummore").text(addCommas(summore.toFixed(2)));
		
		
		document.getElementById("sendsummore").value = summore;
}

function chklist(){

	if(document.getElementById("addmoney").value==""){
		alert("กรุณาใส่ยอดวงเงินที่เพิ่ม");
		return false;
	}else if(document.getElementById("textnote").value==""){
		alert("กรุณาใส่เหตุผลที่ขอเพิ่ม");
		return false;
	}else{
	
		if($("#chkvat").attr('checked')==true){
			if(document.getElementById("valuevat").value==""){
				alert("กรุณาระบุค่าธรรมเนียมเพิ่มวงเงิน");
				return false;
			}
		
		}

		if(confirm('ยืนยันการขอเพิ่มวงเงิน')==true){
			return true;
			$("#btn_submit").attr('disabled', true);
		}else{
			return false;
			$("#btn_submit").attr('disabled', false);
		}
	}
	
}

function addCommas(nStr)
{
				nStr += '';
				x = nStr.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + ',' + '$2');
				}
				return x1 + x2;
}


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
</script>
</head>
<body >
 
<table width="500" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>     
			<div class="header"><h1></h1></div>
			<div class="wrapper">
			<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
			<fieldset><legend><B>(THCAP) เพิ่มวงเงินสัญญา</B></legend>

				<form name="search" method="post" action="index.php">
					<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
						<tr align="center">
							
							<td align="left">
								<input type="text" id="idno" name="idno" value="<?php echo $contractID; ?>" size="70" />
								<input type="submit" name="submit" value="   ค้นหา   ">
							</td>
					   </tr>
					   <tr>  
							
							<td align="left">
								<font color="gray" size="2px;">*เลขที่สัญญา,ผู้กู้,ผู้กู้ร่วม,ผู้ค้ำ,เลขบัตร</font>
							</td>
					   </tr>
					</table>
				</form>

			</fieldset> 
			</div>
        </td>
    </tr>
</table> 

<?php if($contractID != ""){ ?>
<form name="frm" action="process_add.php" method="POST">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>  
			<?php include("../../fapn_statement/Data_contract_detail.php"); ?>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset><legend>เพิ่มวงเงินสัญญา</legend>
				<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr>
						<td align="center" width="20%"></td>
						<td align="left" width="35%">
							เพิ่มเงิน : <input type="text" name="addmoney" id="addmoney" style="text-align:right;" onkeyup="cal(value);" onkeypress="check_num(event);" size="35">
						</td>
						<td align="left">
							วงเงินปัจจุบันเป็น : <span id="textmoney"><?php echo number_format($conCredit,2); ?></span> บาท
						</td>
							
					</tr>
					<tr>
						<td align="center" width="20%"></td>
						<td align="left" colspan="2">เหตุผล...<font color="red">*</font><br>
						<textarea cols="73" rows="5" name="textnote" id="textnote"></textarea>
							
						
						</td>						
					</tr>
					<tr>
						<td align="center" width="20%"></td>
						<td align="left" colspan="2">
							<input type="checkbox" id="chkvat" name="chkvat" onclick="javascript:cal('b')" checked> : เก็บค่าธรรมเนียมเพิ่มวงเงิน  <input type="text" size="5"  name="valuevat" id="valuevat" style="text-align:right;" value="1.00" onkeyup="cal('a');" onkeypress="check_num(event);" >%							
						</td>						
					</tr>
					<tr>
						<td align="center" width="20%"></td>
						<td align="left" colspan="2">
							 - ค่าประเมินและวิเคราะห์วงเงิน (เพิ่มเติม) :   <span id="textmore1">0.00</span> บาท 
							 ภาษีมูลค่าเพิ่ม   <span id="textmore2">0.00</span> บาท 
							 รวม  <span id="textsummore">0.00</span>  บาท
							 <input type="hidden" id="sendsummore" name="sendsummore"> <!--เงินรวม-->
							 <input type="hidden" name="hdmoney" id="hdmoney" value="<?php echo $conCredit; ?>"> <!--ยอดเดิม-->
							 <input type="hidden" name="conid" id="conid" value="<?php echo $contractID; ?>"> <!--เลขที่สัญญา-->
						
						</td>						
					</tr>
					<tr><td><p></td></tr>
					<tr>						
						<td align="center" colspan="3">
							 <input type="submit" id="btn_submit" value=" บันทึก " onclick="return chklist();" style="width:70px;height:50px;">
						</td>						
					</tr>
					<tr><td><br></td></tr>
				</table>					
			</fieldset>
		</td>
	</tr>	
</table> 
</form>
<?php } ?>

</body>
</html>
