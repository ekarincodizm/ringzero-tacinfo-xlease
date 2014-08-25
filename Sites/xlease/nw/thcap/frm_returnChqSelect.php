<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$contractID=$_GET["contractID"];
?>

<form name="frm" method="POST">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="8" align="left" height="25"><u><b>หมายเหตุ</b></u><br>
				<font color="red"><span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font>
				<div style="padding-top:5px;"><font color="red"> <span style="background-color:#FFEBCD;">&nbsp;&nbsp;&nbsp;</span> รายการสีส้ม คือ เช็คคืนรอจัดการ</font></div>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td colspan="8" align="left" style="font-weight:bold;">คืนเช็คให้เลขที่สัญญา : <span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
				<font color="red"><u><?php echo $contractID; ?></u></font></span></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
				<td>เลขที่เช็ค</td>
				<td>วันที่บนเช็ค</td>
				<td>ธนาคารที่ออกเช็ค</td>
				<td>สาขา</td>
				<td>จ่ายบริษัท</td>
				<td>ยอดเช็ค(บาท)</td>
				<td>ประเภทเช็ค</td>
				<td>เช็คที่ต้องการคืน(<a onclick="javascript:selectAll('revchqid');" style="cursor:pointer;"><u>ทั้งหมด</u></a>)</td>
			</tr>
			<?php
			
			$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" a
			left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
			WHERE \"revChqStatus\" in('2','8') and \"revChqToCCID\"='$contractID'
			AND \"chqKeeperID\" not in(select \"chqKeeperID\" from finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqStatus\" = '2' and \"BID\" is not null)
			order by a.\"revChqID\"");
			$nub=pg_num_rows($qry_fr);
			$i=0;
			while($res_fr=pg_fetch_array($qry_fr)){
				$revChqID = $res_fr["revChqID"];
				$bankChqNo=$res_fr["bankChqNo"];
				$bankChqDate = $res_fr["bankChqDate"]; 
				$bankName = $res_fr["bankName"]; 
				$bankOutBranch = $res_fr["bankOutBranch"]; 
				$bankChqToCompID = $res_fr["bankChqToCompID"]; 
				$bankChqAmt = $res_fr["bankChqAmt"]; 
				$revChqStatus=$res_fr["revChqStatus"];
				$revChqToCCID = $res_fr["revChqToCCID"];
				$isInsurChq = $res_fr["isInsurChq"];
				
				//ตรวจสอบว่าเป็นเช็คประเภทใด
				if($isInsurChq=="0"){
					if($res_fr["isPostChq"]=="1"){
						$txtchq="เช็คชำระล่วงหน้า";
					}else{
						$txtchq="เช็คปกติ";
					}
				}else{
					$txtchq="เช็คค้ำประกัน";
				}
				
				if($revChqStatus==2){
					$txtchq="เช็คคืน";
				}
				
				$i+=1;
				if($i%2==0){
					if($isInsurChq==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr class=\"odd\" align=center>";
					}
					
					if($revChqStatus==2){ //กรณีเป็นเช็คเด้ง
						echo "<tr bgcolor=\"#FFEBCD\" align=center>";
					}
				}else{
					if($isInsurChq==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
					
					if($revChqStatus==2){ //กรณีเป็นเช็คเด้ง
						echo "<tr bgcolor=\"#FFEBCD\" align=center>";
					}
				}
			?>
				
				<td><?php echo $bankChqNo; ?></td>
				<td><?php echo $bankChqDate; ?></td>
				<td align="left"><?php echo $bankName; ?></td>
				<td><?php echo $bankOutBranch; ?></td>
				<td><?php echo $bankChqToCompID; ?></td>
				<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
				<td><?php echo $txtchq; ?></td>
				<td>
					<?php
					//ตรวจสอบว่ารออนุมัติอยู่หรือไม่
					$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revChqID'");
					$numchkapp=pg_num_rows($qrychkapp);

					if($numchkapp>0){
						echo "<input type=\"hidden\" name=\"revchqid[]\" id=\"revchqid$i\" value=\"$revChqID\" disabled> รออนุมัติ";
					}else{
						echo "<input type=\"checkbox\" name=\"revchqid[]\" id=\"revchqid$i\" value=\"$revChqID\">";
					}
					?>
					
				</td>	
			</tr>
			
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50 bgcolor=\"#FFFFFF\"><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			
			
			?>
			<tr>
				<td align="right" colspan="8">
					<input type="hidden" id="counter" value="<?php echo $nub;?>">
					<input type="button" id="btnsub" value="บันทึก">
				</td>
			</tr>
			</table>
			</div>
	</td>
</tr>
</table>
</form>
<script language=javascript>
$(document).ready(function(){
	$('#btnsub').click(function(){
		var con = $("#counter").val();
		var numchk;
		numchk = 0;
		
		for(var num = 1;num<=con;num++){	
			if(document.getElementById("revchqid"+num).checked){
				numchk+=1;			
			}		
		}
		if(numchk == 0){
			alert("กรุณาเลือกรายการก่อน");
		}else{
			if(confirm("ยืนยันรายการอีกครั้ง")==true){
				var revchq = [];
				
				//วนลูปเพื่อเก็บว่าเลือกอะไรบ้าง
				var i;
				i=0;
			
				for(var num = 1;num<=con;num++){
					if(document.getElementById("revchqid"+num).checked){
						revchq[i]={revchqid:$("#revchqid"+num).val()};	
						i++;
					}	
				}
				
				//บันทึกข้อมูล
				$.post("api.php",{
					cmd : 'addreturnchq' , 
					revchq : JSON.stringify(revchq) 
				},
				function(data){
					if(data == 1){	
						alert("บันทึกข้อมูลเรียบร้อยแล้ว");
						document.location="frm_returnChq.php";
					}else if(data==2){
						alert("รายการนี้ถูกเรียกใช้ด้วยเมนูอื่น กรุณาตรวจสอบ!");
						document.location="frm_returnChq.php";
					}else if(data==3){
						alert("มีบางรายการกำลังรออนุมัติ กรุณาตรวจสอบ!");
						document.location="frm_returnChq.php";
					}else{
						alert("บันทึกข้อมูลผิดพลาดกรุณาตรวจสอบ!");
						alert(data);
					}
				});
			}
			
		}
	});
});
</script>
