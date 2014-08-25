<?php
include("../../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
$colpre_serial = pg_escape_string($_GET['col_preID']);
list($hours,$minuts,$sec) = explode(":",Date('H:i:s'));

$qry_selcol = pg_query("SELECT \"contractID\",\"colpre_debtamt\",date(\"colpre_duedate\") as duedate,\"colpre_debtdetails\", \"colpre_debtupdatestamp\"  FROM thcap_collect_pre where \"colpre_serial\" = '$colpre_serial' ");
$re_selcol = pg_fetch_array($qry_selcol);
$contractID = $re_selcol['contractID'];
$Debt = number_format($re_selcol['colpre_debtamt'],2);
$duedate = $re_selcol['duedate'];
$Debt_Details = $re_selcol['colpre_debtdetails'];
$debt_update_time = $re_selcol['colpre_debtupdatestamp'];

$Details = explode("\n",$Debt_Details);

$day_next=date('Y-m-d',strtotime("+30 day"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ติดตามหนี้เบื้องต้น</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

$(document).ready(function(){

	$("#calldate").datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	$("#calldate_1").datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	document.getElementById("reasonother").readOnly = true;
				
				$("#relationtr").hide();
				$("#norelationtr").hide();
				$("#called").hide();
				$("#payment_date").hide();




	$("input[type='radio']").change(function(){

			if(document.getElementById("relation").checked){			
				$("#relationtr").show();								
				$("#norelationtr").hide();
								
			}
			
			if(document.getElementById("norelation").checked){
				$("#relationtr").hide();
				$("#payment_date").hide();	
				$("#norelationtr").show();		
			}
			
			if(document.getElementById("calling").checked){			
				$("#called").show();
				$("#payment_date").show();	
			}
			
			if(document.getElementById("misscall").checked){
				$("#called").hide();
				$("#relationtr").hide();
				$("#norelationtr").hide();
				$("#payment_date").hide();					
			}
			if(document.getElementById("receiptend").checked){
				$("#called").hide();
				$("#relationtr").hide();
				$("#norelationtr").hide();
				$("#payment_date").hide();	
						
			}
	});
	
	$("input[type='checkbox']").change(function(){
			if(document.getElementById("chknorela3").checked){
				document.getElementById("reasonother").readOnly = false;					
			}else{
				document.getElementById("reasonother").value="";
				document.getElementById("reasonother").readOnly = true;
			}
	});
});

function chklist(){

	var message_error = 'กรุณาระบุข้อมูลดังต่อไปนี้ด้วยครับ -------\n\n';
	var status = 0;
	
	if(document.getElementById("hourstart").value==""){
		message_error += '- เวลาที่ติดต่อ | ชั่วโมง \n';
		status++;
	}
	if(document.getElementById("minutsstart").value==""){
		message_error += '- เวลาที่ติดต่อ | นาที \n';
		status++;
	}


	//ถ้ามีการเลือก "มีผู้รับสาย"
	if($("#calling").is(':checked') || $("#misscall").is(':checked') || $("#receiptend").is(':checked')){
		if($("#calling").is(':checked')){ //กรณีมีผู้รับสาย	
			var calldate = document.getElementById("calldate").value; // วันที่ติดต่อ
			var day = document.getElementById("calldate_1").value; // วันที่นัดชำระ
			
			if(day > '<?php echo $day_next;?>'){
				message_error += '- วันที่นัดชำระกำหนดมากกว่า 30วัน \n';				
				status++;
			}
			
			if(!document.getElementById("relation").checked && !document.getElementById("norelation").checked){
				message_error += '- ความเกี่ยวข้อง \n';
				status++;
			}
			
			if(document.getElementById("norelation").checked){
				if(!document.getElementById("chknorela1").checked && !document.getElementById("chknorela2").checked && !document.getElementById("chknorela3").checked){
					message_error += '- เลือกคำตอบหรือระบุชื่อของบุคคลอื่นด้วยครับ \n';
					status++;
				}else{
					if($("#chknorela3").is(':checked')){
						if(document.getElementById("reasonother").value==""){
							message_error += '- ระบุชื่อผู้ที่ติดต่อด้วยครับ \n';
							status++;
						}	
					}
				}	
			}	
			
			// หาว่า วันที่ติดต่อ กับ วันที่นัดชำระ ห่างกันกี่วัน
			var theDate1 = Date.parse(calldate)/1000;
			var theDate2 = Date.parse(day)/1000;
			var diffDate = (theDate2-theDate1)/(60*60*24); // จำนวนวันที่ห่างกัน ของ วันที่ติดต่อ กับ วันที่นัดชำระ ห่างกันกี่วัน
			
			if(diffDate > 14){
				message_error += '- วันที่นัดชำระ ห้ามเกิน 14 วันนับจากวันที่ติดตาม \n';
				status++;
			}
		}
	}else{
		//ถ้าไม่มีการเลือกติดต่อ
		message_error += '- การติดต่อ \n';
		status++;
	}
	
	if(document.getElementById("calldata").value==""){
		message_error += '- รายละเอียดการติดต่อ \n';
		status++;
	}
	
	
	if(status != 0){	
		alert(message_error);
		return false;
	}else{
		return true;
	}

}				
</script>
</head>
<body bgcolor="#EEEEEE">
<form name="frm" method="post" action="process_add.php">
<table width="950" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
   <tr>
        <td align="center">
			<table align="center" width="100%" frame="box" bgcolor="#A2B5CD">
				<tr>
					<td align="center">
						<div style="padding-top:15px;"></div>
						<font color="red" size="5px;"><b>รายการติดตามหนี้เบื้องต้น</b></font>
						<div style="padding-bottom:15px;"></div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
        <td align="center">
			<table align="center" width="100%" bgcolor="#FFFFFF">
				<tr>
					<td align="center">
						<?php include('../Data_contract_detail.php');?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<?php
			$frm_add_call=true;
		?>
		<td>
			<div style="margin-top:0px;"><?php include('../../thcap_installments/contract_note.php'); //หมายเหตุ?></div>
		</td>
	</tr>
	<tr>
        <td align="center"  bgcolor="#FFFFFF">
			<fieldset style="background-color:#BCD2EE"><legend>ข้อมูลที่ติดต่อ</legend>
				<table align="center" width="100%" bgcolor="#FFFFFF">
					<tr>
						<td align="center">						
							<?php 
							$otherpage = "true";
							include('../../thcap_installments/frm_address.php');?>
						</td>
					</tr>
				</table>
			</fieldset>	
		</td>
	</tr>
	<tr>
        <td align="center"  bgcolor="#FFFFFF">
			<fieldset style="background-color:#BCD2EE"><legend>รายการรับชำระทั้งหมดประจำวัน</legend>
				<table align="center" width="100%" bgcolor="#FFFFFF">
					<tr>
						<td align="center">						
							<?php 
							$searchdate = "now"; //กำหนดเพื่อให้แสดงเฉพาะใบเสร็จในวันปัจจุบัน
							include('../Data_Receipt.php');?>
						</td>
					</tr>
				</table>
			</fieldset>	
		</td>
	</tr>
	<tr>
        <td align="center" bgcolor="#FFFFFF" >
			<fieldset style="background-color:#BCD2EE"><legend>รายละเอียดการติดตาม</legend>
				<table align="center" width="100%">
					<tr>
						<td align="right" width="30%" valign="top"><b><font color="red"> รายการค้างชำระ:</font><br>ข้อมูลปรับปรุงเมื่อ <br><?php echo $debt_update_time; ?></b></td>
						<td align="left" colspan="3"><b>
						<?php for($num = 0;$num<sizeof($Details);$num++){
								if($num == 0){}else{ echo "<br>"; }
								echo $Details[$num];
							  }	?>
						</b></td>	  
					</tr>	
					<tr>
						<td align="right" width="30%"><font color="red"> <b>รวมยอดค้างชำระ:</b></font></td>
						<td align="left"  width="20%"><b><?php echo $Debt; ?></b> บาท</td>

						<td align="right" width="20%"><font color="red"> <b>วันที่ครบกำหนดชำระ</b></font></td>
						<td align="left"><b><?php echo $duedate; ?></b></td>
					</tr>
					<tr>
						<td align="left" colspan="4"><br>
						<font color="red"><b>*หมายเหตุ ถ้ามีเบี้ยปรับ เบี้ยปรับที่คำนวณจะคำนวณล่วงหน้าไปอีก 7 วัน</b></font>
						</td>	  
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
		<tr>
        <td align="center" bgcolor="#FFFFFF">
			<fieldset><legend>รายละเอียดการติดต่อ</legend>
			
				<table align="center" width="100%" >
					<tr>
						<td align="right" width="30%">วันที่ติดต่อ:</td>
						<td align="left"  width="15%"><input type="text" id="calldate" name="calldate" size="10" value="<?php echo nowDate();?>"></td>
						<td align="left"  width="15%"></td>
						<td   width="15%"></td>
						<td></td>
					</tr>
					<tr>
						<td align="right"><font color="red">*</font>เวลา:</td>
						<td align="left">
							<select name="hourstart" id="hourstart">
										<option value="" selected>--</option>
							<?php		
										
									for($i=0;$i<=24;$i++){
										if($i < 10){
											$hh = "0".$i;
										}else{
											$hh = $i;
										}
							?>			
										<option value="<?php echo $hh ?>" ><?php echo $hh ?></option>
							<?php			
									}
							?>		
									</select> นาฬิกา 
									
									<select name="minutsstart" id="minutsstart">
										<option value="" selected>--</option>
							<?php		
									for($i=0;$i<60;$i++){
										if($i < 10){
											$mm = "0".$i;
										}else{
											$mm = $i;
										}
							?>
										<option value="<?php echo $mm ?>"><?php echo $mm ?></option>
							<?php
									}
							?>		
									</select> นาที 
						</td>
						<td align="left"></td>
						<td ></td>
						<td></td>
					</tr>
					<tr>
						<td align="right"><font color="red">*</font>การติดต่อ:</td>
						<td colspan="4">
						<input type="radio" name="Communication" id="calling"  value="calling" >มีผู้รับสาย
						<input type="radio" name="Communication" id="misscall"  value="misscall">ไม่มีผู้รับสาย
						<input type="radio" name="Communication" id="receiptend"  value="receiptend">ลูกค้าชำระแล้ว
						</td>
					</tr>
			<!--หากมีผู้รับสาย-->		
				
					<tr id="called">
						<td align="right" ><font color="red">*</font>ความเกี่ยวข้อง:</td>
						<td align="left"  ><input type="radio" name="Destination" id="relation"  value="relation" >ผู้กู้หลัก/ผู้กู้ร่วม/ผู้ค้ำ</td>
						<td align="left"  ><input type="radio" name="Destination" id="norelation"  value="norelation">บุคคลอื่น</td>
					</tr>
					
					<!--หากคนที่รับเป้นคนที่เกี่ยวข้องกับสัญญา-->
				
					<tr id="relationtr">
						<td align="right">ผู้รับ:</td>
						<td align="left" colspan="2" >
							<hr width="100%">
						<?php $qry_cuscon = pg_query("SELECT \"relation\",\"CusID\", thcap_fullname,\"CusState\" FROM \"vthcap_ContactCus_detail\" where  \"contractID\" = '$contractID' order by \"CusState\" "); 
								while($re_cuscon = pg_fetch_array($qry_cuscon)){
									$Cuscallid = $re_cuscon['CusID'];
									$thcap_fullname = $re_cuscon['thcap_fullname'];
									$relation = $re_cuscon['relation'];
									$CusState = $re_cuscon['CusState'];
									if($CusState == '0'){ $checked = "checked"; }else{ $checked = "";}
									echo "<input type=\"radio\" name=\"cuscall\" value=\"$Cuscallid\" $checked>$thcap_fullname ($relation)<br>";
								}
						?>
						</td>
					</tr>
			    	
					<!--************-->
					
					
					<!--หากคนที่รับเป้นคนที่ไม่เกี่ยวข้องกับสัญญา-->
					<tr id="norelationtr">
						<td align="right" ><font color="red">*</font></td>
						<td align="left" colspan="3" >
							<hr width="100%">
							<input type="checkbox" name="chknorela1" id="chknorela1" value="ไม่อยู่">ไม่อยู่
							<input type="checkbox" name="chknorela2" id="chknorela2" value="ไม่รู้จัก">ไม่รู้จัก
							<input type="checkbox" name="chknorela3" id="chknorela3" value="ชื่อผู้ติดต่อ">ชื่อผู้ติดต่อ
							<input type="text" name="reasonother" id="reasonother" size="28">
						</td>						
					</tr>
					
					<!--************-->
					<tr id="payment_date">
						<td align="right" width="30%"><font color="red"><b>*</b></font>วันที่นัดชำระ (ห้ามเกิน 14 วันนับจากวันที่โทรติดตาม):</td>
						<td align="left"  width="15%"><input type="text" id="calldate_1" name="calldate_1" size="10" value="<?php echo nowDate();?>"></td>
						<td align="left"  width="15%"></td>
						<td   width="15%"></td>
						<td></td>
					</tr>
			<!--************-->		
					<tr>
						<td align="right" valign="top"><font color="red"><b>*</b></font>บันทึก:</td>
						<td align="left" colspan="2" >
							<textarea name="calldata" id="calldata" rows="5" cols="50"></textarea>
							
						</td>						
					</tr>
					<tr>
						<td></td>
						<td align="center">
							<input type="hidden" name="col_preID" value="<?php echo $colpre_serial; ?>">
							<input type="image" src="../images/onebit_11.png" style="cursor:pointer;" onclick="return chklist();"  />
							
						</td>
						<td align="center">
							<input type="image"  src="../images/onebit_33.png" style="cursor:pointer;" onclick="window.close();" />
							
						</td>			
					</tr>
					
				</table>
			</fieldset>	
		</td>
	</tr>		
</table>
</form>