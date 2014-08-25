<?php
session_start();
$prebillIDMaster = pg_escape_string($_GET["prebillIDMaster"]); //กลุ่มรายการ
$edittime = pg_escape_string($_GET["edittime"]); //ครั้งที่แก้ไขข้อมูล  ถ้าเป็นการเพิ่มครั้งแรกจะเท่ากับ 0
$stsprocess = pg_escape_string($_GET["stsprocess"]); //สถานะการทำรายการ I-insert,U-update,D-delete
$app_user = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) FA อนุมัติบิลขอสินเชื่อ-ตรวจสอบและทำรายการอนุมัติ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	$("#show").hide();
	$("#chksell1").click(function(){
		$("#show").show();
	});
	$("#chksell2").click(function(){
		$("#show").hide();
	});
	$("#dateContact").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#submitbutton").click(function(){
	
		$("#submitbutton").attr('disabled', true);
		//ตรวจสอบว่ามีการ key ข้อมูลหรือไม่
		if (document.getElementById("chksell1").checked==false && document.getElementById("chksell2").checked==false){
			alert('กรุณาระบุว่าบิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?');
			$("#submitbutton").attr('disabled', false);
			return false;
        }
		
		if (document.getElementById("chksell1").checked==true){
			if(document.getElementById("rule1").checked==false &&
			document.getElementById("rule2").checked==false && 
			document.getElementById("rule3").checked==false &&
			document.getElementById("rule4").checked==false &&
			document.getElementById("rule5").checked==false){	
				alert('กรุณาระบุว่าในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใด');
				$("#submitbutton").attr('disabled', false);
				return false;
			}
         }
		 if($("#cusContact").val()==""){
			alert('กรุณาระบุผู้ที่ติดต่อในการสอบถามข้อมูล');
			$('#cusContact').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusPost").val()==""){
			alert('กรุณาระบุตำแหน่งข้อผู้ที่ติดต่อ');
			$('#cusPost').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusTel").val()==""){
			alert('กรุณาระบุเบอร์ของผู้ที่ติดต่อ');
			$('#cusTel').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#dateContact").val()==""){
			alert('กรุณาระบุวันที่ติดต่อ');
			$('#dateContact').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#note").val()==""){
			alert('กรุณาระบุเหตุผล');
			$('#note').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		
		var chksel;
		if(document.getElementById("chksell1").checked==true){
			chksel=$("#chksell1").val();
		}else{
			chksel=$("#chksell2").val();
		}
		
		if(document.getElementById("rule1").checked==true){
			rule=$("#rule1").val();
		}else if(document.getElementById("rule2").checked==true){
			rule=$("#rule2").val();
		}else if(document.getElementById("rule3").checked==true){
			rule=$("#rule3").val();
		}else if(document.getElementById("rule4").checked==true){
			rule=$("#rule4").val();
		}else if(document.getElementById("rule5").checked==true){
			rule=$("#rule5").val();
		}else{
			rule="null";
		}
		document.forms['my'].appv.click();
	});
	
	//กรณียกเลิกบิล
	$("#cancelbutton").click(function(){
		if(confirm('ยืนยันการยกเลิกบิลอีกครั้ง')){
			//ตรวจสอบกรณีเลือกอนุมัิติรายการที่เกี่ยวข้อง
			var chkapp = [];
			var counter=$("#numrows").val();
			document.forms['my'].unappv.click();
			
		}else{
			return false;
		}
	});
	
	//กรณีไม่อนุมัติ
	$("#dontsubmitbutton").click(function(){
		$("#dontsubmitbutton").attr('disabled', true);
		//ตรวจสอบว่ามีการ key ข้อมูลหรือไม่
		if (document.getElementById("chksell1").checked==false && document.getElementById("chksell2").checked==false){
			alert('กรุณาระบุว่าบิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?');
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
        }
		if (document.getElementById("chksell1").checked==true){
			if(document.getElementById("rule1").checked==false &&
			document.getElementById("rule2").checked==false && 
			document.getElementById("rule3").checked==false &&
			document.getElementById("rule4").checked==false &&
			document.getElementById("rule5").checked==false){	
				alert('กรุณาระบุว่าในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใด');
				$("#dontsubmitbutton").attr('disabled', false);
				return false;
			}
         }
		 if($("#cusContact").val()==""){
			alert('กรุณาระบุผู้ที่ติดต่อในการสอบถามข้อมูล');
			$('#cusContact').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusPost").val()==""){
			alert('กรุณาระบุตำแหน่งข้อผู้ที่ติดต่อ');
			$('#cusPost').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusTel").val()==""){
			alert('กรุณาระบุเบอร์ของผู้ที่ติดต่อ');
			$('#cusTel').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#dateContact").val()==""){
			alert('กรุณาระบุวันที่ติดต่อ');
			$('#dateContact').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#note").val()==""){
			alert('กรุณาระบุหมายเหตุ');
			$('#note').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		var chksel;
		if(document.getElementById("chksell1").checked==true){
			chksel=$("#chksell1").val();
		}else{
			chksel=$("#chksell2").val();
		}
		
		if(document.getElementById("rule1").checked==true){
			rule=$("#rule1").val();
		}else if(document.getElementById("rule2").checked==true){
			rule=$("#rule2").val();
		}else if(document.getElementById("rule3").checked==true){
			rule=$("#rule3").val();
		}else if(document.getElementById("rule4").checked==true){
			rule=$("#rule4").val();
		}else if(document.getElementById("rule5").checked==true){
			rule=$("#rule5").val();
		}else{
			rule="null";
		}
		
		var hour = $('#hour option:selected').attr('value');
		var min = $('#minute option:selected').attr('value');
		
		//ตรวจสอบกรณีเลือกอนุมัิติรายการที่เกี่ยวข้อง
		var chkapp = [];
		var counter=$("#numrows").val();
		
		for( i=0; i<counter; i++ ){
			if(document.getElementById("chkbill"+i).checked==true){
				chkapp[i] = {chk : $("#chkbill"+ i).val()};
			}
		}
		document.forms['my'].appv.click();
		$.post("process_fa.php",{
            method : "approve" , 
			prebillIDMaster : '<?php echo $prebillIDMaster;?>',
            statusapp : '0', 
			chksel :chksel,
			rule :rule,
			cusContact :$("#cusContact").val(),
			cusPost :$("#cusPost").val(),
            cusTel :$("#cusTel").val(), 
			dateContact :$("#dateContact").val()+' '+hour+':'+min,
			note :$("#note").val(),
			chkapp : JSON.stringify(chkapp)
        },
        function(data){
			if(data == "1"){
                alert("บันทึกรายการเรียบร้อย");
                opener.location.reload(true);
				self.close();
            }else if(data=="2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				alert(data);
                $("#dontsubmitbutton").attr('disabled', false);
            }else{
				alert("รายการนี้หรือบางรายการได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!");
                $("#dontsubmitbutton").attr('disabled', false);
			}
        });
	});
	
});
function selectAll(select){
    with (document.form2)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<?php
echo "<div align=\"center\"><h2>(THCAP) FA ตรวจสอบและทำรายการอนุมัติบิลขอสินเชื่อ</h2></div>";
echo "<div align=\"right\" style=\"width:850px;\"><input name=\"button\" type=\"button\" onclick=\"window.close();\" value=\" X ปิด \" /></div>";
echo "<div>";
include "frm_Detail.php";
echo "</div>";

//ดึงรูปบิลมาแสดง
$request=1;
echo "<div>";
include "frm_Picbill.php";
echo "</div>";

//แสดงรายการที่เกี่ยวข้องกับรายการนี้;
?>
<!--form name="form2"-->
<form name="my" method="post" action="process_fa.php">
<div style="width:850px;margin:0px auto;">
	<fieldset><legend><B>รายการอื่นๆ ของลูกหนี้รายนี้ (ที่สามารถอนุมัติได้)</B></legend>
		<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFE4B5" align="center">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#FFE4B5" align="center">
				<td>เลขที่ใบแจ้งหนี้</td>
				<td>ชื่อผู้ขายบิล</td>
				<td>ชื่อลูกหนี้ของผู้ขายบิล</td>
				<td>วันที่ใบแจ้งหนี้</td>
				<td>ยอดใบแจ้งหนี้</td>
				<td>วันที่วางบิล</td>
				<td>อื่นๆ</td>
				<td>เลือกอนุมัติ<br><a href="#" onclick="javascript:selectAll('chk');"><u>เลือกทั้งหมด</u></a></td>
			</tr>

			<?php
			//ดึงข้อมูลจากตาราง temp เฉพาะรายการที่ prebillIDMaster = auto_id และมีสถานะเป็นอนุมัติ และต้องไม่ใช่ใบเดียวกันกับใบที่สนใจ
				$qry=pg_query("SELECT \"numberInvoice\",\"prebillIDMaster\",
				\"userSalebillName\" as \"userSalebill\",\"userDebtorName\" as \"userDebtor\",				
				\"totalTaxInvoice\",\"dateInvoice\",\"dateBill\",\"addUser\"
				FROM \"vthcap_fa_prebill_temp\" a
				where \"prebillIDMaster\"=\"auto_id\"::text AND \"statusApp\"='2' and a.\"userDebtor\"='$CusID' and \"prebillIDMaster\"<>'$prebillIDMaster' and \"userSalebill\"='$CusID2' 
				order by \"dateBill\"");
				
				$numrows=pg_num_rows($qry);
				$i=0;
				$sum=0;
				$pt=0;
				while($result=pg_fetch_array($qry)){			
					$userSalebill2=$result["userSalebill"];
					$userDebtor2=$result["userDebtor"];			
					$dateInvoice2=$result["dateInvoice"];
					$prebillIDMaster2=$result["prebillIDMaster"];
					$numberInvoice2=$result["numberInvoice"];
					$totalTaxInvoice2=$result["totalTaxInvoice"];
					$dateBill2=$result["dateBill"];
					$addUser2=$result["addUser"]; //รหัสพนักงานที่ทำรายการ
					$sum+=$totalTaxInvoice2;
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#FFEBCD\" align=center>";
					}else{
						echo "<tr bgcolor=\"#FDF5E6\" align=center>";
					}
						
					echo "<td valign=top>$numberInvoice2</td>
						<td align=\"left\" valign=top>$userSalebill2</td>
						<td align=left valign=top>$userDebtor2</td>
						<td valign=top>$dateInvoice2</td>
						<td align=right valign=top>".number_format($totalTaxInvoice2,2)."</td>
						<td valign=top>$dateBill2</td>
						<td valign=top><span onclick=\"javascript:popU('frm_Detail.php?prebillIDMaster=$prebillIDMaster2&close=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>
					";
					
					//ตรวจสอบว่า user สามารถอนุมัติรายการได้หรือไม่
					$qrylevel=pg_query("SELECT ta_get_user_emplevel('$app_user')");
					list($emplevel)=pg_fetch_array($qrylevel);
					
					//กรณีผู้อนุมัติไม่ใช่คนเดียวกับผู้ทำรายการ หรือถ้าเป็นคนเดียวกัน ต้องมี  emplevel<=1 จึงสามารถอนุมัีติได้
					if(($addUser2!=$app_user) || ($addUser2==$app_user and $emplevel<=1)){
						echo"<td valign=top><input type=\"checkbox\" name=\"chk[]\" id=\"chk$pt\" value=\"$prebillIDMaster2\"></td>";
					}else{
						echo "<td valign=top>ไม่ให้อนุมัติตนเอง</td>";
					}
					echo "</tr>";
					$pt++;
				}
				
				if($numrows==0){
					echo "<tr><td colspan=8 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบรายการ-</b></td></tr>";
				}else{
					echo "<tr bgcolor=\"#FFC1C1\"><td colspan=4 align=center><b>รวม</b></td><td align=right>".number_format($sum,2)."</td><td colspan=3></td></tr>";				
				}
				?>
			</table>
	</fieldset>
</div>
<!--/form-->
<div style="padding:5px;"></div>
<div style="width:850px;margin:0px auto;">
	<fieldset><legend><B>ทำรายการอนุมัติ</B></legend>
		<div align="center" style="padding:10px;"><font size="2" color="red"><b>ข้าพเจ้าได้ทำการตรวจสอบรายละเอียดตามข้อมูลด้านบนอย่างละเอียดโดยตัวข้าพเจ้าเองแล้ว และยินดีรับผิดชอบในความเสียหายทุกประการ</b></font></div>
		<table width="80%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#FFE4B5">
			<tr><td bgcolor="#FFE4B5">&nbsp;</td></tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td>
					<div style="padding-left:10px"><b>บิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?</b><font color="red"><b>*</b></font></div>
					<div style="padding-left:20px"><input type="radio" name="chksell" id="chksell1" value="1">ตรวจสอบแล้วมีการซื้อขายจริง</div>
					<div style="padding-left:20px"><input type="radio" name="chksell" id="chksell2" value="2">ตรวจสอบแล้วไม่มีการซื้อขายจริง</div>
					<div><hr></div>
				</td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5" id="show">
				<td>
					<div style="padding-left:10px"><b>ในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใดหรือไม่?</b><font color="red"><b>*</b></font></div>
					<div style="padding-left:20px"><input type="radio" name="rule" id="rule1" value="1">ไม่ผิดข้อกำหนด (ซื้อขายจริง ได้รับสินค้าครบถ้วนแล้ว)</div>
					<div style="padding-left:20px"><input type="radio" name="rule" id="rule2" value="2">ซื้อขายจริง แต่ยังไม่ได้รับสินค้า</div>
					<div style="padding-left:20px"><input type="radio" name="rule" id="rule3" value="3">มีการคืนสินค้าบางส่วนหรือทั้งหมด</div>
					<div style="padding-left:20px"><input type="radio" name="rule" id="rule4" value="4">ยกเลิกการซื้อแล้วเนื่องจากมีปัญหาสินค้า</div>
					<div style="padding-left:20px"><input type="radio" name="rule" id="rule5" value="5">ซื้อขายจริง แต่ยังได้รับสินค้าไม่ครบ</div>
					<div><hr></div>
				</td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td><div style="padding:5px;"><b>ผู้ที่ติดต่อในการสอบถามข้อมูล</b> : <input type="text" name="cusContact" id="cusContact" size="40"><font color="red"><b>*</b></font><div></td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td><div style="padding:5px;"><b>ตำแหน่งของผู้ที่ติดต่อ</b> : <input type="text" name="cusPost" id="cusPost" size="30"><font color="red"><b>*</b></font></div></td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td><div style="padding:5px;"><b>เบอร์ของผู้ที่ติดต่อ</b> : <input type="text" name="cusTel" id="cusTel" size="30"><font color="red"><b>*</b></font></div></td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td><div style="padding:5px;"><b>วันและเวลาที่ติดต่อโดยประมาณ</b> : <input type="text" name="dateContact" id="dateContact" size="10">
				เวลา
				<select id="hour" name="hour">
				<?php 
				for($p=0;$p<=23;$p++){
					if($p<10){
						$p="0".$p;
					}
					echo "<option value=$p>$p</option>";
				}
				?>
				</select>:
				<select id="minute" name="minute" >
				<?php 
				for($pp=0;$pp<=59;$pp++){
					if($pp<10){
						$pp="0".$pp;
					}
					echo "<option value=$pp>$pp</option>";
				}
				?>
				</select>
				(ชั่วโมง:นาที)
				<font color="red"><b>*</b></font></div></td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td valign="top"><div style="padding-left:5px;"><b>:::หมายเหตุ:::</b><font color="red"><b>*</b></font></div></td>
			</tr>
			<tr align="left" bgcolor="#FFEFD5">
				<td valign="top"><div style="padding-left:5px;"><textarea name="note" id="note" cols="40" rows="3"></textarea></div></td>
			</tr>
			
			<tr align="center">
				<td colspan=3>
					<input type="hidden" name="method" value="approve">
					<input type="hidden" id="numrows" name="numrows" value="<?php echo $numrows;?>">					
					<input type="hidden" name="prebillIDMaster" id="prebillIDMaster" value="<?php echo $prebillIDMaster;?>">
					<input name="appv" type="submit" value="อนุมัติ" hidden />
					<input name="unappv" type="submit" value="ไม่อนุมัติ" hidden />
					<input type="button" id="submitbutton" name="approve" value="ตรวจสอบแล้ว">
					<input type="hidden" id="dontsubmitbutton" value="ไม่อนุมัติ"/><!--ปุ่มนี้ยังไม่ต้องทำอะไร ให้ซ่อนไว้ก่อน เผื่อในอนาคตได้ใช้ -->
					<input type="button" id="cancelbutton" value="ยกเลิกบิล"/>
				</td>
			</tr>
		</table>
	</fieldset> 
</div>
</form>
</body>
</html>