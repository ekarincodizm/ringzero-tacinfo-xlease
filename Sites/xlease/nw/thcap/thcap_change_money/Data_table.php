<?php
include("../../../config/config.php");
$conidsh = $_GET['conidserh'];
$chkNowDate = nowDate();
if(!empty($conidsh) && $conidsh != ""){
	$sql_nub1 = pg_query("SELECT * FROM thcap_contract where \"contractID\" = '$conidsh'");
	$rowchk1 = pg_num_rows($sql_nub1);
	IF($rowchk1 == 0){
			echo "<script type='text/javascript'>alert('ขออภัย เลขที่สัญญา \" $conidsh \" ไม่มีอยู่ในระบบ !!')</script>";
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_data.php\">";
			exit();
	}else{
		$sql_nub = pg_query("SELECT * FROM vthcap_contract_money where \"contractID\" = '$conidsh' and \"contractBalance\" > 0 order by \"contractID\"");
		$rowchk = pg_num_rows($sql_nub);
		if($rowchk == 0){
			 
			echo "<script type='text/javascript'>alert('ขออภัย เลขที่สัญญา \" $conidsh \" ไม่มี เงินพัก/เงินค้ำ !!')</script>";
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_data.php\">";
			exit();
			 
		}else{
			$addchange = true;
		}
	}	
}else{
	// $sql = pg_query("SELECT distinct(\"contractID\") FROM vthcap_contract_money where \"contractBalance\" > 0 order by \"contractID\"");
	//$addchange	= false;
	echo "<script type='text/javascript'>alert('กรุณาระบุเลขที่สัญญา ')</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_data.php\">";
	exit();
}

if($addchange == true)
{
	$sql = pg_query("SELECT distinct(\"contractID\") FROM vthcap_contract_money where \"contractID\" = '$conidsh' order by \"contractID\"");	
	$idtext=0;
	$result = pg_fetch_array($sql);
	$conid = $result['contractID'];  
?>
<form name="frm" id="frm" action="process_change.php" method="post">
<fieldset>
		<legend><b>
				 <span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
				 <font color="red"><u><?php echo $conid;?></u></font></span>
		</b></legend>	

	<table width="1000"  cellspacing="0" cellpadding="0"  align="center">	
		<tr>
			<td width="25%" valign="top"  bgcolor="#FFFFFF" >
				<table width="99%"  cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<?php	$sql1 = pg_query("SELECT \"CusState\", \"CusID\" FROM \"thcap_ContactCus\" where \"contractID\" = '$conid' order by \"CusState\"");
					$i = 0;
					while($result1 = pg_fetch_array($sql1)){
						$cusid = $result1['CusID'];
						$sqlcus = pg_query("SELECT full_name FROM \"VSearchCus\" where  \"CusID\" = '$cusid'");
						 list($cusname) = pg_fetch_array($sqlcus);
						 
						if($cusname == ""){
							$sqlcus2 = pg_query("SELECT concat(COALESCE(\"corpType\",''::character varying), ' ', COALESCE(\"corpName_THA\",''::character varying)) AS fullname FROM \"th_corp\" where  \"corpID\" = '$cusid'");
							list($cusname) = pg_fetch_array($sqlcus2);	
						}
						
						if($result1['CusState'] == '0'){						
							echo "<tr><td> ผู้กู้หลัก : ".$cusname."</td></tr>";							
						}else if($result1['CusState'] > '0'){	
							$i++;
							echo "<tr><td> ผู้กู้ร่วม ".$i." : ".$cusname."</td></tr>";	
						}
					}
			?>							
				</table>	
			</td>
			
			<td width="75%">
				
				<table width="100%" frame="box"  cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr><td colspan="5" align="center" bgcolor="#838B83" height="15px" ><font color="white"><b>กำหนดจำนวนยอดเงินที่จะใช้ในการย้ายครั้งนี้</b></font></td></tr>
					<tr bgcolor="#C1CDC1">
						<th width="10%">เลือกเงิน <font color="#FF0000">*</font></th>
						<th width="30%">ประเภท</th>
						<th width="20%">ยอดเงิน</th>
						<th width="20%">จำนวนเงินที่ย้าย <font color="#FF0000">*</font></th>
						<th width="20%">ย้ายแล้วคงเหลือ</th>
					</tr>		
			<?php 
					$sql3 = pg_query("SELECT * FROM vthcap_contract_money where \"contractID\" = '$conid' and \"moneyType\" in('997', '998') order by \"moneyType\"");
					$i = 0;
					$sumamt = 0;
					$row3 = pg_num_rows($sql3);
					while($result3 = pg_fetch_array($sql3))
					{
						$i++;
						if($i%2==0){
							echo "<tr bgcolor=#E0EEE0 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E0EEE0';\" align=center>";
						}else{
							echo "<tr bgcolor=#F0FFF0 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F0FFF0';\" align=center>";
						}
						$typePayID=$result3['typePayID'];
						$moneyType=$result3['moneyType'];
						$monvalue = $result3['contractBalance'];
						echo "<td><input type=\"radio\" name=\"selectmoney\" id=\"money$i\" value=\"$moneyType\" onchange=\"chkMyMoney('$i', '$moneyType')\"></td>";
						echo "
								<td>".$result3['BAccount']."<input type=\"hidden\" name=\"moneytypelabel[]\" id=\"moneytypelabel$moneyType\" value=\"".$result3['BAccount']."\" /></td>
								<td align=\"right\">".number_format($monvalue,2)."</td>
								<td align=\"center\">";
								if($moneyType == '998'){
								echo "<input type=\"text\" name=\"changechos998\" id=\"changechos998\" autocomplete=\"off\" onkeyup=\"calsum('998','$i')\" onkeypress=\"check_num(event);\" Onblur=\"javascript :checkNull(id),calsum('998','$i')\" onfocus=\"clearNum(id)\" value=\"0\" size=\"10\" style=\"text-align:right;\" readOnly></td>";
								}elseif($moneyType == '997'){	
								echo "<input type=\"text\" name=\"changechos997\" id=\"changechos997\" autocomplete=\"off\" onkeyup=\"calsum('997','$i')\" onkeypress=\"check_num(event);\" Onblur=\"javascript :checkNull(id),calsum('997','$i')\" onfocus=\"clearNum(id)\" value=\"0\" size=\"10\" style=\"text-align:right;\" readOnly></td>";
								}
								echo "<td><span id=\"textsum$i\">".number_format($monvalue,2)."</span></td>";
								echo "	</tr>
								<input type=\"hidden\" name=\"chkmon\" id=\"chkmon$i\" value=\"$monvalue\">
							";
					}
			?>		
					<input type="hidden" name="rowMoney" id="rowMoney" value="<?php echo $row3; ?>">
					</tr>
					<tr bgcolor="#C1CDC1">
						<td align="right" height="18px" colspan="5" width="13%">
							รวมเงินที่ย้าย :  <font color="red"><b><span id="textsumall">0.00</span></font> บาท
						</td>
					</tr>
				</table>	
			</td>					
		</tr>			
	</table>
</fieldset>
	<table width="99%" frame="border" border="0" cellspacing="0" cellpadding="0" style="margin-top:0px" align="center" id="tbl">	
		<tr bgcolor="#EECBAD">	
			<td align="center" colspan="10">
				<input type="button" id="btnadd" name="btnadd" value="+" onclick="test();"> เพิ่มการย้ายเงิน
				<input type="button" id="btnadd" name="btndel" value="-" onclick="fncDelete();"> ลบการย้ายเงิน
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				วันที่ย้ายเงิน <font color="#FF0000">*</font>  : <input type="text" name="datechange" id="datechange" style="text-align:center;">
			</td>
		</tr>
	</table>
	
<div style="padding-top:10px;">	</div>	
	
	<table width="99%"   cellspacing="0" cellpadding="0" style="margin-top:0px" align="center">			
					<tr valign="top">
						<td align="left" colspan="3">
						<input type="button" id="back" onclick="javascript :loadpage('1')" value="กลับ" style="width:150px;height:50px;">
						</td>
						<td align="center" colspan="3">
							<table><tr><td>เหตุผลที่ขอย้ายเงิน : </td><td><textarea name="reason" id="reason"></textarea></td><td><font color="#FF0000">*</font></td></tr></table>
						</td>
						<td align="right" colspan="3">
							<input type="submit" value="ย้ายเงิน" onclick="return chklist()" style="width:150px;height:50px;"> 		
						</td>			
					</tr>
				<input type="hidden" id="hiddeni" name="hiddeni" value="<?php echo $i; ?>">
				<input type="hidden" id="hiddensumall" name="hiddensumall" value="0">		
				<input type="hidden" name="conidori" id="conidori" value="<?php echo $conid; ?>">	
	</table>
</form>	

<?php
}
?>

<script language=javascript>

$("#datechange").datepicker({
	showOn: 'button',
	buttonImage: '../images/calendar.gif',
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'yy-mm-dd'
});
	
var rowMoney = '<?php echo $row3; ?>'; // จำนวนประเภทเงินที่จะใช้ย้าย

var chkNowDate = '<?php echo $chkNowDate; ?>'; // วันที่ปัจจุบัน

$(document).ready(function(){

	$("#conidchange0").autocomplete({
				source: "s_idall.php",
				minLength:1
	});

});


var count = 0;
function test(){
	count++;
	var tb = document.getElementById('tbl');
	var tbody = document.createElement('tbody');
	if(count%2==0){
		tbody.setAttribute('bgcolor','#E0EEE0');
	}else{
		tbody.setAttribute('bgcolor','#F5FFFA');
	}
	tb.insertBefore(tbody, null);
	tr = document.createElement("tr");
	 tbody.insertBefore(tr, null);
	 
	  td =  document.createElement("td");
     var id =  document.createTextNode("รูปแบบ :");
	 td.setAttribute("align","Right");
     td.insertBefore(id, null);
     tr.insertBefore(td, null);
	 
	  td =  document.createElement("td");
	 var se = document.createElement("select");
	 se.setAttribute('name','typechange[]');
	 se.setAttribute("id","typechange"+count);
	 se.setAttribute("Onchange","changecontext("+count+")");	 
	se.options[0] = new Option("ผู้กู้/ผู้ค้ำ เดียวกัน","same");
	se.options[1] = new Option("ผู้กู้/ผู้ค้ำ ต่างกัน","def");
	se.options[0].selected =1;
     td.insertBefore(se, null);
	 tr.insertBefore(td, null);
	 
	 td =  document.createElement("td");
     var id =  document.createTextNode("เลขที่สัญญา :");
	 var mark =  document.createTextNode(" *");	
	 var font = document.createElement("font");
	 font.style.color = "red";
	 font.appendChild(mark);
	 td.setAttribute("align","Right");
     td.insertBefore(id, null);
     tr.insertBefore(td, null);
	 
		td =  document.createElement("td");
	 var se = document.createElement("input");
	 se.setAttribute('name','conidchange[]');
	 se.setAttribute("id","conidchange"+count);
	 se.setAttribute('size','25');
     td.insertBefore(se, null);
	 tr.insertBefore(td, null);
	 
	  
	 var se = document.createElement("select");
	 se.setAttribute('name','sameconid[]');
	 se.setAttribute("id","sameconid"+count);
	 se.setAttribute("Style","width:160px");
	 se.options[0] = new Option("เลือกเลขที่สัญญา","");
	 var num=1;
	 <?php
	$p = 0;				 
					$q = pg_query("select count(\"contractID\") as cc FROM \"thcap_ContactCus\" where \"contractID\" = '$conid' group by \"contractID\" ");
					list($count) = pg_fetch_array($q);
					$qq = pg_query("select \"contractID\" FROM \"thcap_ContactCus\"  group by \"contractID\" having count(\"contractID\") = '$count' order by \"contractID\"");
					while($w = pg_fetch_array($qq)){ 
						$conid1 = $w['contractID'];	

							$qqq = pg_query("select * FROM \"thcap_ContactCus\" where \"contractID\" = '$conid' ");
							while($reqqq = pg_fetch_array($qqq)){
								$CusID1 = $reqqq['CusID'];
								$CusState1 = $reqqq['CusState'];
								//$qqq1 = pg_query("select * FROM \"thcap_ContactCus\" where \"CusID\" = '$CusID1' and \"CusState\"='$CusState1' and \"contractID\" = '$conid1' and \"contractID\" != '$conid' "); // ใช้ในกรณีที่ไม่ต้องการให้ตัวเลือกเลขที่สัญญาที่จะย้ายไป มีของตัวเองอยู่ด้วย
								$qqq1 = pg_query("select * FROM \"thcap_ContactCus\" where \"CusID\" = '$CusID1' and \"CusState\"='$CusState1' and \"contractID\" = '$conid1' ");
								$rowqqq1 = pg_num_rows($qqq1);
								if($rowqqq1 == 0){
									$p += 1;
								}
							}
						if($p == 0){ ?>
							se.options[num] = new Option("<?php echo $conid1 ; ?>","<?php echo $conid1 ; ?>");
							num++;
						<?php	$sh = 1;
						}						
							
					 $p = 0;
					}				
	if($sh != 1){ ?>
		se.options[num] = new Option(" ไม่มีสัญญาอื่น ","");
		num++;
	<?php	} ?>
	se.options[0].selected =1;
     td.insertBefore(se, null);
	 td.insertBefore(font, null);
	 tr.insertBefore(td, null);
	  
	 td =  document.createElement("td");
     var id =  document.createTextNode("จำนวนเงิน  :");
	 var mark =  document.createTextNode(" *");	
	 var font = document.createElement("font");
	 font.style.color = "red";
	 font.appendChild(mark);
	 td.setAttribute("align","Right"); 
     td.insertBefore(id, null);
     tr.insertBefore(td, null);
	 
	 td =  document.createElement("td");
	 var se = document.createElement("input");
	 se.setAttribute('name','moneychange[]');
	 se.setAttribute("id","moneychange"+count);
	 se.setAttribute("onBlur","checkNull(id)");
	 se.setAttribute("onFocus","clearNum(id)");
	 se.setAttribute("autocomplete","off");		
	 se.setAttribute("value","0");
	 se.setAttribute("style","text-align:right");
     td.insertBefore(se, null);
	 td.insertBefore(font, null);
	 tr.insertBefore(td, null);
	 
	 
	 td =  document.createElement("td");
     var id =  document.createTextNode("ย้ายไป  :");
	 var mark =  document.createTextNode(" *");	
	 var font = document.createElement("font");
	 font.style.color = "red";
	 font.appendChild(mark);
	 td.setAttribute("align","Right");
     td.insertBefore(id, null);
     tr.insertBefore(td, null);
	 
	 td =  document.createElement("td");
	 var se = document.createElement("select");
	 se.setAttribute('name','typechangeto[]');
	 se.setAttribute("id","typechangeto"+count);
	se.options[0] = new Option("เลือกรายการ","");
	se.options[1] = new Option("เงินพักรอตัดรายการ","998");
	se.options[2] = new Option("เงินค้ำประกันฯ","997");
	se.options[0].selected =1;
     td.insertBefore(se, null);
	 td.insertBefore(font, null);
	 tr.insertBefore(td, null);
	 
	 td =  document.createElement("td");
     var id =  document.createTextNode("");
     td.insertBefore(id, null);
     tr.insertBefore(td, null);
	 
	 
	 tb.appendChild(tbody);
	 
	 $("#sameconid"+count).show();
	 $("#conidchange"+count).hide();
	 
	 $("#conidchange"+count).autocomplete({
			source: "s_idall.php",
			minLength:1
	});
};

function fncDelete(){
		var  tb =document.getElementById('tbl');
		var del = tb.rows.length;
		if(del>1){
			tb.deleteRow(del-1);
			count--;
		}
		
};

$("#sameconid"+count).show();
$("#conidchange"+count).hide();

function changecontext(num){
	
	var aaaa = $("#typechange"+num+" option:selected").attr('value');
		if(aaaa == 'def'){			
				$("#conidchange"+num).show();
				$("#sameconid"+num).hide();				
		}else{			
				$("#sameconid"+num).show();
				$("#conidchange"+num).hide();
		}
};



function checkNull(id){
	var value;
	value = document.getElementById(id).value;
	if(value=='')
	{
		document.getElementById(id).value='0';
	}
};

function clearNum(id){
	var value;
	value = document.getElementById(id).value;
	if(value=='0')
	{
		document.getElementById(id).value='';
	}
};

function chklist()
{	
	var sum = 0;
	var sumlimit = 0;
	var moneychange;
	
	// เช็คว่าเลือกประเภทเงินที่จะใช้ย้ายหรือยัง
		var chkMoney = 0;
		for(con=1;con<=rowMoney;con++)
		{
			if(document.getElementById("money"+con).checked == true)
			{
				chkMoney = 1;
			}
		}
		if(chkMoney == 0)
		{
			alert('กรุณาเลือกประเภทเงินที่จะใช้ย้าย');
			return false;
		}
	// จบการเช็คว่าเลือกประเภทเงินที่จะใช้ย้ายหรือยัง

	for(con=1;con<=count;con++)
	{	
		if(parseFloat($("#moneychange"+con).val()) <= 0)
		{
			alert('กรุณาระบุจำนวนเงินให้ครบ');
			return false;
		}
		else
		{
			moneychange = $("#moneychange"+con).val();	
			sum = parseFloat(sum) + parseFloat(moneychange);		
		}	
		
	}
	sum = sum.toFixed(2);
	
	for(con=1;con<=count;con++)
	{
		if($("#typechange"+con+" option:selected").attr('value')=='same')
		{		
			if($("#sameconid"+con).attr('value') == "")
			{
				alert('กรุณาเลือกเลขที่สัญญาที่จะย้ายไปให้ครบ');
				return false;
			}	
		}
	
		if($("#typechange"+con+" option:selected").attr('value')=='def')
		{		
			if($("#conidchange"+con).attr('value') == "")
			{
				alert('กรุณาใส่เลขที่สัญญาที่จะย้ายไปให้ครบ');
				return false;
			}	
		}
		
		if($("#typechangeto"+con).attr('value') == "")
		{
			alert('กรุณาเลือกย้ายไปให้ครบ');
			return false;
		}
	}
	
	// ตรวจสอบว่ามีการเลือกรายการซ้ำหรือไม่
	for(con=1;con<=count;con++)
	{
		if(con>1)
		{
			for(con2=1;con2<con;con2++)
			{
				if($("#typechange"+con+" option:selected").attr('value')!='def')
				{
					if($("#typechange"+con2+" option:selected").attr('value')!='def')
					{
						if($("#sameconid"+con).attr('value') == $("#sameconid"+con2).attr('value') && $("#typechangeto"+con).attr('value') == $("#typechangeto"+con2).attr('value'))
						{
							alert('มีการย้ายเงินซ้ำ กรุณาตรวจสอบ');
							return false;
						}
					}
					else
					{
						if($("#sameconid"+con).attr('value') == $("#conidchange"+con2).attr('value') && $("#typechangeto"+con).attr('value') == $("#typechangeto"+con2).attr('value'))
						{
							alert('มีการย้ายเงินซ้ำ กรุณาตรวจสอบ');
							return false;
						}
					}
				}
				else
				{
					if($("#typechange"+con2+" option:selected").attr('value')=='def')
					{
						if($("#conidchange"+con).attr('value') == $("#conidchange"+con2).attr('value') && $("#typechangeto"+con).attr('value') == $("#typechangeto"+con2).attr('value'))
						{
							alert('มีการย้ายเงินซ้ำ กรุณาตรวจสอบ');
							return false;
						}
					}
					else
					{
						if($("#conidchange"+con).attr('value') == $("#sameconid"+con2).attr('value') && $("#typechangeto"+con).attr('value') == $("#typechangeto"+con2).attr('value'))
						{
							alert('มีการย้ายเงินซ้ำ กรุณาตรวจสอบ');
							return false;
						}
					}
				}
			}
		}
	}
	// จบการตรวจสอบว่ามีการเลือกรายการซ้ำหรือไม่
	
	if($("#datechange").attr('value') == "")
	{
		alert('กรุณาใส่วันที่ย้ายเงิน');
		return false;
	}
	
	if($("#datechange").attr('value') > chkNowDate)
	{
		alert('วันที่ย้ายเงินห้ามมากกว่าวันที่ปัจจุบัน');
		return false;
	}
	
	if($("#reason").attr('value') == "")
	{
		alert('กรุณาระบุเหตุผลที่ขอย้ายเงินข้ามสัญญา');
		return false;
	}
	
	sumlimit = parseFloat($("#hiddensumall").val());
	
	if(sum != sumlimit)
	{
		alert('จำนวนเงินที่กำหนดไว้รวม : '+sumlimit+'\r\nจำนวนเงินที่ย้ายรวม : '+sum+'\r\n จำนวนเงินไม่เท่ากันอาจทำให้เงินสูญหายได้ กรุณาแก้ไขให้ถูกต้อง!!');
		return false;
	}
	else if(sum == 0)
	{
		alert('ไม่พบจำนวนเงินที่จะย้าย');
		return false;
	}
	else
	{
		//checkcontractindb('1');
	}
	
	//-- ตรวจสอบจำนวนเงินในขณะนั้นว่าพอย้ายหรือไม่
	var checkMoneyType = $('input[name="selectmoney"]:checked').val();
	var moneychk = $('#changechos'+checkMoneyType).val(); // จำนวนเงินที่จะย้าย
	$.post("check_money.php",{
		id : '<?php echo $conidsh; ?>',
		moneyType : checkMoneyType
	},
	function(dataMoney){
		if(parseFloat(moneychk) > parseFloat(dataMoney))
		{ // ถ้าจำนวนเงินคงเหลือไม่พอย้าย
			alert('จำนวนเงินในขณะนี้ไม่พอที่จะย้าย อาจมีผู้อื่นใช้เงินในระหว่างนี้ กรุณาทำรายการใหม่ทั้งหมด \r\n\ จำนวนเงินที่เหลืออยู่ '+number_format(dataMoney)+' จำนวนเงินที่จะย้าย '+number_format(moneychk));
			window.location.reload();
			//return false;
		}
		else
		{
			checkcontractindb('1');
		}
	});
	//-- จบการตรวจสอบจำนวนเงินในขณะนั้นว่าพอย้ายหรือไม่
	
	return false;
};


// ตรวจสอบหากเป็นผู้กู้/ผู้ค้ำต่างกัน  ดูว่าเลขที่สัญญามีในระบบหรือไม่
var messer = "เลขที่สัญญานี้ไม่มีในระบบ -- ";

function checkcontractindb(numcheck){
	if($("#typechange"+numcheck+" option:selected").attr('value')=='def')
	{			
			$.post("checkid.php",{
				id : $("#conidchange"+numcheck).attr('value')					
			},
			function(data){	
				var brokenstring=data.split("#");
				if(brokenstring[0] == 'YES'){
					alert(messer + brokenstring[1]);
					return false;
						
				}
				
				if(numcheck < count){
						numchecknew = parseFloat(numcheck) + 1;
						checkcontractindb(numchecknew);
				}else{
						checklst();
				}
			});
	}else{
		if(numcheck < count){
				numchecknew = parseFloat(numcheck) + 1;
				checkcontractindb(numchecknew);
		}else{
				checklst();		
		}
	}
};



function checklst(){
		var conidori = $('#conidori').val();
		var moneytype = $('input[name="selectmoney"]:checked').val();
		var moneytypelabel = $('#moneytypelabel'+moneytype).val();
		var moneyfirst = $('#changechos'+moneytype).val();
		//for loop
		var conto1 = $('select[name="sameconid[]"]');
		var conto2 = $('input[name="conidchange[]"]');
		var typechange = $('select[name="typechange[]"]');
		var typechangeto = $('select[name="typechangeto[]"]');
		var moneylast = $('input[name="moneychange[]"]');
		
		var all_row = $(moneylast).length;
		
		var msg = 'ยืนยันการย้ายเงินจาก\r\n\r\n\tสัญญา: '+conidori+'  ประเภท:  '+moneytypelabel+' จำนวนเงิน: '+number_format(moneyfirst)+' บาท\r\n\r\n';
		msg+='ไปยัง\r\n';
		
		var i=0;
		while(i<all_row)
		{
			var conto_label = '';
			var type_c = $(typechange[i]).val();
			if(type_c=='same')
			{
				conto_label = $(typechange[i]).parent().parent().find('select[name="sameconid[]"]').val();
			}
			else if(type_c=='def')
			{
				conto_label = $(typechange[i]).parent().parent().find('input[name="conidchange[]"]').val();
			}
			var typechangeto_label = $(typechangeto[i]).find('option:selected').text();
			var moneylast_label = $(moneylast[i]).val();
			msg+='\r\n\tสัญญา :  '+conto_label+'  ประเภท:  '+typechangeto_label+'  จำนวน:  '+number_format(moneylast_label)+' บาท';
			i++;
		}
		if(confirm(msg)==true)
		{	
			$("#frm").submit();
			// return true;
		}
		else
		{
			return false;	
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
function number_format(number)
{
	var num = parseFloat(number);
	var money =  addCommas(num.toFixed(2));
	
	return money;
}

function calsum(id,i){	
	var moneykey = $("#changechos"+id).attr('value'); //ค่าเงินที่กรอก
	var moneysame = parseFloat($("#chkmon"+i).attr('value')); //ค่าเงินที่มีในระบบ
	
	if(moneykey == ''){ moneykey = '0';}
	balanceamt = moneysame - parseFloat(moneykey); //จำนวนที่เหลือ
	if(balanceamt < 0){
		$("#changechos"+id).val($("#chkmon"+i).attr('value'));
		$("#textsum"+i).text("0.00");
	}else{
		$("#textsum"+i).text(addCommas(balanceamt.toFixed(2)));
	}
	
	var counti = $("#hiddeni").attr('value'); //จำนวนช่องใส่เงิน
	var moneysumall = 0;
	
	
	txt998 = $("#changechos998").attr('value');
	txt997 = $("#changechos997").attr('value');
	
	if(!txt998){
		txt998 = '0';
	}
	
	if(txt997 == ''){
		txt997 = 0;
	}
	moneysumall =  parseFloat(txt998) + parseFloat(txt997); //ค่าเงินที่มีในระบบ	
	
	$("#textsumall").text(addCommas(moneysumall.toFixed(2)));
	$("#hiddensumall").val(moneysumall);
	
};

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
};

function chkMyMoney(MyMoney, moneyType)
{
	if(rowMoney > 1)
	{
		if(moneyType == '997')
		{
			document.getElementById("changechos998").value = 0;
			document.getElementById("changechos998").readOnly = true;
			document.getElementById("changechos997").readOnly = false;
			if(MyMoney == '1'){calsum('998', '2');}
			else{calsum('998', '1');}
		}
		else if(moneyType == '998')
		{
			document.getElementById("changechos997").value = 0;
			document.getElementById("changechos997").readOnly = true;
			document.getElementById("changechos998").readOnly = false;
			if(MyMoney == '1'){calsum('997', '2');}
			else{calsum('997', '1');}
		}
		else
		{
			alert('เกิดข้อผิดพลาด ไม่พบรหัสประเภทเงิน');
			return false;
		}
	}
	
	if(document.getElementById("chkmon"+MyMoney).value == 0.00)
	{
		alert('ไม่สามารถเลือกรายการนี้ได้ เนื่องจากไม่มีเงินเหลืออยู่');
		document.getElementById("money"+MyMoney).checked = false;
		return false;
	}
}

</script>