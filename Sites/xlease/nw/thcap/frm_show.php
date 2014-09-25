<?php
session_start();
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ

//ตรวจสอบว่าพนักงานมีระดับใด
$qrylevel=pg_query("select \"ta_get_user_emplevel\"('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

?>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function checkdata(){
	var acctype = "";
	var accsumnum = $("#accsumnum").val();
	var checknum = 0;
	for(i=1;i<=accsumnum;i++){	
		if($("#acctype"+i).attr("checked") == true){
			acctype = acctype+"@"+$("#acctype"+i).val();
			checknum++;
		}	
	}
	if(checknum == 0){
		alert("- กรุณาเลือกประเภทบัญชีที่ต้องการทำรายการ! -");
		return false;
	}
}
function clearapp(revtran)
{
	if(confirm("ยืนยันการยกเลิกการอนุมัตินี้")==true){
		$.post("api.php",{
            cmd : "clearapp" , 
            revTranID : revtran 
        },
        function(data){
            if(data == 1){
                alert("มีการยกเลิกการอนุมัติ 1 รายการ เรียบร้อยแล้ว");
				location.reload();
            }else if(data==2){
				alert("ผิดผลาด ไม่ยกเลิกได้!");
            }else if(data==3){
				alert("มีการยกเลิกการอนุมัติ 0 รายการ");
				location.reload();
			}else if(data==4){
				alert("ไม่สามารถทำรายการเช็คคืนได้ เนื่องจากมีการทำรายการก่อนหน้านี้แล้ว กรุณาตรวจสอบ!!");
				location.reload();
			}else{
				alert(data);
			}
        });
	}	
	
}

//function เช็คคืน
function badchq(revtran){
	$('body').append('<div id="dialog3"></div>');
		$('#dialog3').load('selectchq_popup.php?revtran='+revtran);
		$('#dialog3').dialog({
		title: 'เลือกรายการเช็คคืน',
		resizable: false,
		modal: true,  
		width: 1100,
		height: 600,
		close: function(ev, ui){
			$('#dialog3').remove();
		}
	});
}

// ไม่ใช่เงินของลูกค้า
function anonymous(revtran){
	$('body').append('<div id="dialog"></div>');
		$('#dialog').load('anonymous_popup.php?revtran='+revtran);
		$('#dialog').dialog({
		title: 'ไม่ทราบว่าเงินของใคร',
		resizable: false,
		modal: true,  
		width: 500,
		height: 200,
		close: function(ev, ui){
			$('#dialog').remove();
		}
	});	
}

function delapp(revtran){
		$('body').append('<div id="dialog"></div>');
			$('#dialog').load('delresult_popup.php?revtran='+revtran);
			$('#dialog').dialog({
			title: 'ยืนยันการลบข้อมูล ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 300,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});	
}
function returnchq(revChq){
		if(confirm('ต้องการกลับไปยืนยันนำเช็คเข้าธนาคารอีกครั้ง!!')==true){
			$.post("api.php",{
            cmd : "returnchq" , 
            revChqID : revChq 
			},
			function(data){
				if(data == 1){	
					alert("กลับไปยืนยันนำเช็คเข้าธนาคารเรียบร้อยแล้ว");
					location.reload();
				}else{
					alert("ผิดพลาดไม่สามารถทำรายการได้");
					alert(data);
				}
			});
		}else{
			return false;
		}
}

function returnchq_bounced(revChq){
		
		if(confirm('คุณต้องการกลับไปยืนยันนำเช็คเข้าธนาคารอีกครั้ง!!')==true){
			$.post("api.php",{
            cmd : "returnchq_bounced" , 
            revChqID : revChq 
			},
			function(data){
				if(data == 1){	
					alert("กลับไปยืนยันนำเช็คเข้าธนาคารเรียบร้อยแล้ว");
					location.reload();
				}else{
					alert("ผิดพลาดไม่สามารถทำรายการได้");
					alert(data);
				}
			});
		}else{
			return false;
		}
}
//function สำหรับบันทึกข้อความการติดต่อจากลูกค้า 
function contact_note(revtran){
	/*$('body').append('<div id="dialog2"></div>'); 
		$('#dialog2').load('Money_transfers_Note?revtran='+revtran);
		$('#dialog2').dialog({
		title: 'บันทึกข้อความ',
		resizable: false,
		modal: true,  
		width: 1100,
		height: 600,
		close: function(ev, ui){
			$('#dialog2').remove();
		}
	});
	*/
	popU('Money_transfers_Note.php?revtran='+revtran,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=600');
}
</script>
<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
</style>

<div align="left"><font color="#777777">* หมายเหตุ กรณีที่มีข้อความว่า "คุณไม่มีสิทธิ์อนุมัติ" หมายถึง คุณไม่มีสิทธื์ ตรวจสอบเพื่ออนุมัติรายการที่ตนเองเป็นผู้เพิ่มหรือแก้ไขรายการ ในธนาคารของวันนั้นๆได้</font></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่โอน</td>
	<td>เวลาที่โอน</td>
	<td>รหัสรายการเงินโอน</td>
    <td>ประเภทการนำเข้า</td>
    <td>เลขที่บัญชี</td>
	<td>สาขา</td>
    <td>ผู้ทำรายการ</td>
    <td>วันที่ทำรายการ</td>
    <td>จำนวนเงิน</td>
    <td></td>
    <td>ทำรายการอื่น ๆ</td>
</tr>

<?php
//แสดงเฉพาะรายการที่รออนุมัติ
if($app==1){ //กรณีบัญชีอนุมัติ
	//ค้นหาชื่อธนาคารทั้งหมดตามเงื่อนไข
	$qry_acc = pg_query("select \"BID\", \"BAccount\", \"BName\" from \"BankInt\" where \"isTranPay\" = 1 order by \"BID\"");
	$nub = 0;	
	while($re_acc = pg_fetch_array($qry_acc)){
		$BID = $re_acc['BID'];
		$BAccount = $re_acc['BAccount'];
		$BName = $re_acc['BName'];
		
		//แสดงชื่อธนาคาร
		echo "<tr bgcolor=\"#FFC1C1\"><td colspan=\"11\"><b>$BAccount-$BName</b></td></tr>";
		
		//ค้นหารายการที่โอนเข้าธนาคารที่แสดง โดยเรียงจากวันที่โอน
		$query=pg_query("select \"revTranID\", \"cnID\", \"bankRevBranch\", \"bankRevStamp\", \"bankRevAmt\", \"doerID\", \"doerStamp\", \"tranActionID\", \"appvXID\"
		from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\"='9' 
		and \"appvXID\" is null and \"bankRevAccID\"='$BID' ORDER BY \"bankRevStamp\",\"revTranID\" ASC");
		$dateRevStamp_old="";
		$nub=0;
		while($resvc=pg_fetch_array($query)){
			$revTranID = $resvc['revTranID'];
			$cnID = $resvc['cnID'];
			$bankRevBranch = trim($resvc['bankRevBranch']);
			$bankRevStamp = trim($resvc['bankRevStamp']);
			
			$dateRevStamp=trim(substr($bankRevStamp,0,10)); //วันที่โอน
			$timeRevStamp=trim(substr($bankRevStamp,10)); //เวลาที่โอน
			
			$bankRevAmt = trim($resvc['bankRevAmt']);
			$doerID = $resvc['doerID'];
			$doerStamp = $resvc['doerStamp'];
			$tranActionID = $resvc['tranActionID'];
			$appvXID = $resvc['appvXID']; //ฝ่ายบัญชีที่อนุมัติจะใช้สำหรับตรวจสอบในส่วนการเงินว่าไม่ให้เป็นคนเดียวกันกับคนอนุมัติครั้งแรก
			
			$qr_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$doerID'");
			$rs_doer = pg_fetch_array($qr_doer);
			$doerName = $rs_doer['fullname'];
			
			// กำหนดให้อนุมัติได้เฉพาะคนที่ไม่ได้ทำรายการและคนละคนกับคนอนุมัติคนแรก หรืออนุมัติมัติได้เฉพาะผู้ที่มีึสิทธิ์เท่านั้น (กรณีฝ่ายบัญชีอนุมัติ appvXID จะเป็นค่า null)
			if($doerID==$user_id && $emplevel>1)
			{
				$canApprove = "canNot"; // ไม่มีสิทธิ์ตรวจสอบอนุมัติรายการ
			}
			
			if($dateRevStamp==$dateRevStamp_old){ //กรณีที่วันที่เท่ากันไม่ต้องแสดงอีกครั้ง
				$dateRevStamp2="";
			}else{ //ถ้าไม่เท่ากันให้แสดงสรุปรวม
				if($dateRevStamp_old!="")
				{
					if($canApprove == "canNot")
					{ // ถ้าไม่มีสิทธิ์ตรวจสอบอนุมัติรายการ
						echo "<tr><td colspan=\"8\" align=\"right\"><b>รวม </b></td><td align=\"right\"><b>$sumbank</b></td><td align=center><font color=\"#888888\">คุณไม่มีสิทธิ์อนุมัติ</font></td></tr>";
					}
					else
					{
						echo "<tr><td colspan=\"8\" align=\"right\"><b>รวม </b></td><td align=\"right\"><b>$sumbank</b></td><td align=center><span onclick=\"javascript:popU('frm_checkbill.php?BID=$BID&app=$app&dateRevStamp=$dateRevStamp_old','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')\" style=\"cursor: pointer;\" title=\"ยังไม่ตรวจ\"><font color=\"red\"><u>ตรวจสอบ</u></font></span></td></tr>";
					}
					
					unset($sumbankRevAmt);
					unset($canApprove);
				}
				$dateRevStamp2=$dateRevStamp;
			}

			$i+=1;
			if($i%2==0){
				echo "<tr class=\"odd\" align=\"center\">";
			}else{
				echo "<tr class=\"even\" align=\"center\">";
			}
			
			$bankRevAccID="$BAccount-$BName";
			?>
				<td><?php echo $dateRevStamp2; ?></td>
				<td><?php echo $timeRevStamp; ?></td>
				<td height="30"><?php echo $revTranID; ?></td>
				<td><?php echo $cnID; ?></td>
				<td><?php echo $bankRevAccID; ?></td>
				<td><?php echo $bankRevBranch; ?></td>
				<td><?php echo $doerName; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<td align="right"><?php echo number_format($bankRevAmt,2); ?></td>
				<?php
					echo "<td></td>";
				?>
			</tr>
			<?php
			$nub++;
			$dateRevStamp_old=$dateRevStamp;
			$sumbankRevAmt += $bankRevAmt;

			$sumbank = number_format($sumbankRevAmt,2);
		}
		if($nub==0){
			echo "<tr><td height=50 align=center colspan=11><b>---ไม่พบข้อมูล---</b></td></tr>";
		}else{
			//แสดงผลรวม record สุดท้ายของแต่ละธนาคาร
			if($canApprove == "canNot")
			{ // ถ้าไม่มีสิทธิ์ตรวจสอบอนุมัติรายการ
				echo "<tr><td colspan=\"8\" align=\"right\"><b>รวม </b></td><td align=\"right\"><b>$sumbank</b></td><td align=center><font color=\"#888888\">คุณไม่มีสิทธิ์อนุมัติ</font></td></tr>";
			}
			else
			{
				echo "<tr><td colspan=\"8\" align=\"right\"><b>รวม </b></td><td align=\"right\"><b>$sumbank</b></td><td align=center><span onclick=\"javascript:popU('frm_checkbill.php?BID=$BID&app=$app&dateRevStamp=$dateRevStamp','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')\" style=\"cursor: pointer;\" title=\"ยังไม่ตรวจ\"><font color=\"red\"><u>ตรวจสอบ</u></font></span></td></tr>";
			}
			
			unset($sumbankRevAmt);
			unset($canApprove);
		}
	}

}else{ //กรณีการเงินอนุมัติให้ดึงข้อมูลที่รออนุมัติหรือที่อนุมัติแล้วเพื่อรอเลือกใช้รายการ
	$acctype = $_POST['acctype'];
	?>	
	<form method="post" name="frm">
		<div style="padding-top:20px 0 10px;">
		<?php
			$qry_acc = pg_query("select \"BAccount\", \"BName\", \"BID\" from \"BankInt\" where \"isTranPay\" = 1");
			$accnum = 0;
			while($re_acc = pg_fetch_array($qry_acc)){
				$accnum++;
				$BAccount = $re_acc['BAccount'];
				$BName = $re_acc['BName'];
				$BID = $re_acc['BID'];
				
				//ค้นหาว่าแต่ละธนาคารเหลือรายการทั้งหมดกี่รายการ
				$qrynumchk=pg_query("select \"bankRevAccID\" from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE (\"revTranStatus\" IN ('9','1','6')) 
				and \"appvXID\" is not null and \"bankRevAccID\" = '$BID'");
				$numchk=pg_num_rows($qrynumchk);
				
				if( $acctype!=""){
					if(in_array($BID,$acctype) ){
						$check="checked";
					}else{
						$check="";
					}
				}
				echo "<input type=\"checkbox\" name=\"acctype[]\" id=\"acctype$accnum\" value=\"$BID\" $check><span style=\"background-color:#FFF0F5;\"><b> $BAccount-$BName </b></span>(เหลือ <span style=\"color:red\">$numchk</span> รายการ)";					  
				if($accnum%3==0){
					echo "<br>";
				}
			}		
		?>
		<input type="hidden" id="accsumnum" value="<?php echo  $accnum ?>">	
		<input type="submit" id="btn1" value="ค้นหา" onclick="return checkdata();">
		</div>
	</form>
	<?php
	if($emplevel<=1){
	?>
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/clean.png" width="23" height="23"> หมายถึง ทำรายการล้างข้อมูล กลับไปสถานะตรวจสอบรายการ</font></div>
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/del.png" width="23" height="23"> หมายถึง  ลบรายการเงินโอน</font></div>	
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/mix.png" width="24" height="23"> หมายถึง  map เช็คกับใบเสร็จที่ออกไปแล้ว</font></div>
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/refresh.png" width="23" height="23"> หมายถึง  ยกเลิกรายการเช็คนี้ กลับไปเมนู "ยืนยันนำเช็คเข้าธนาคาร" อีกครั้ง</font></div>
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/return.gif" width="20" height="20"> หมายถึง  ทำรายการเช็คคืน</font></div>
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/note_icon.png" width="20" height="20"> หมายถึง  บันทึกข้อความ หมายเหตุ การตรวจสอบการโอนเงิน</font></div>
	<div style="text-align:left;padding-top:10px;"><font color="red"><img src="images/anonymous_icon.jpg" width="23" height="23"> หมายถึง  ทำรายการว่ารายการนี้ไม่ทราบว่าเป็นเงินของใคร หรือ อาจไม่ใช่เงินของลูกค้า หรือเงินที่ธนาคารโอนมาผิด เมื่อครบกำหนดต้องปิดบัญชีแล้ว โดยการทำรายการดังกล่าวจะมีผลให้เกิด Receive Voucher ณ วันที่รับเงิน โดยเข้าบัญชี เจ้าหนี้ - (211002) เงินพักไม่สามารถระบุชื่อผู้ชำระ หรือเพื่อรอทำคืนกรณีที่ไม่ใช่เงินลูกค้า หรือธนาคารโอนผิด</font></div>
	<?php
	}
	?>
	<div style="text-align:left;padding-top:10px;"><font color="red">* รายการที่น่าจะเป็น Chqeue เป็นเพียงการเปรียบเทียบให้อัตโนมัติ ซึ่งอาจถูกหรือไม่ถูกต้อง</font></div>
	<div style="text-align:left;padding-top:10px;"><font color="red">* หากท่านไม่สามารถทำรายการใดได้ ให้แจ้งผู้ใช้งานท่านอื่นทำรายการแทน เนื่องจากระบบกำหนดไว้ว่า ผู้สร้างรายการจะไม่สามารถตรวจสอบรายการตนเองได้ <br>และหากยังไม่สามารถทำรายการได้อีก ให้ติดต่อฝ่าย IT</font></div>
	<br>
		<span onclick="javascript:popU('frm_checkbill.php?revTranID=bid_1&app=2&tranActionID=-1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')" style="cursor: pointer;" title="รับเงินสด RV"><font color="blue"><u>รับเงินสด RV</u></font></span>
	</br>
	<?php
	if($acctype=="" and $app==2){
		echo "<tr><td colspan=12 align=center height=50><h2>กรุณาเลือกเลขที่บัญชีที่ต้องการทำรายการ</h2></td></tr>";
	}
	for($loop = 0;$loop<sizeof($acctype);$loop++){
		if($acctype[$loop] != "" ){
			$qry_acc = pg_query("select \"BAccount\", \"BName\" from \"BankInt\" where \"isTranPay\" = 1 and \"BID\" = '$acctype[$loop]'");
			while($re_acc = pg_fetch_array($qry_acc)){
				$BAccount = $re_acc['BAccount'];
				$BName = $re_acc['BName'];
				
				//แสดงชื่อธนาคาร
				echo "<tr bgcolor=\"#FFC1C1\"><td colspan=\"12\"><span style=\"background-color:#FFE4E1;\"><b>$BAccount-$BName</b></span></td></tr>";
				
				//หาวันที่ที่ต้องแสดง โดยวันที่  ที่แสดงจะมี วันที่ที่มีการโอนเงิน และมีเช็คเด้งในวันนั้นๆ
				$querydate=pg_query("select date(\"bankRevStamp\") as \"datestamp\" from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\" IN ('9','1','6') 
							and \"appvXID\" is not null and \"bankRevAccID\" = '$acctype[$loop]' group by date(\"bankRevStamp\") 
							union 
							SELECT date(\"giveTakerDate\") as \"datestamp\" FROM finance.\"V_thcap_receive_cheque_chqManage\" 
							WHERE \"bankRevResult\" in ('1','2') AND \"revChqStatus\"='6' AND \"BID\"='$acctype[$loop]'
							AND \"chqKeeperID\"  NOT IN (SELECT \"chqKeeperID\" FROM finance.thcap_receive_transfer WHERE \"chqKeeperID\" IS NOT NULL) 
							union 
							select date(\"giveTakerDate\") as \"datestamp\" from finance.\"thcap_receive_cheque_keeper\" 
							where \"bankRevResult\"='3' AND \"BID\"='$acctype[$loop]'
							and \"chqKeeperID\" not in(select \"chqKeeperID\" from finance.\"thcap_receive_transfer\" where  \"chqKeeperID\" is not null)
							GROUP BY \"datestamp\" ORDER BY \"datestamp\"");
				
				while($resdate=pg_fetch_array($querydate)){
					$datemain=$resdate["datestamp"];
					
					//แสดงวันที่
					echo "<tr bgcolor=\"\"><td colspan=\"12\"><b>วันที่โอน : $datemain</b></td></tr>";
					
					//ค้นหารายการตามธนาคารและวันที่
					$query=pg_query("select \"revTranID\", \"cnID\", \"bankRevAccID\", \"bankRevBranch\", \"bankRevStamp\", \"bankRevAmt\", \"doerID\", \"doerStamp\", \"tranActionID\",
					\"appvXID\", \"revTranStatus\", \"contractID\", \"balanceAmt\", \"cusnamebill\", \"bankRevRef1\", \"bankRevRef2\", \"invoiceID\"
					from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\" IN ('9','1','6')
					and \"appvXID\" is not null and \"bankRevAccID\" = '$acctype[$loop]' and date(\"bankRevStamp\")='$datemain' ORDER BY \"bankRevStamp\",\"doerStamp\",\"revTranID\" ASC");
					$arrchq="";
					while($resvc=pg_fetch_array($query)){
						$revTranID = $resvc['revTranID'];
						$cnID = $resvc['cnID'];
						$BID = $resvc['bankRevAccID'];
						$bankRevBranch = trim($resvc['bankRevBranch']);
						$bankRevStamp = trim($resvc['bankRevStamp']);
						$bankRevAmt = trim($resvc['bankRevAmt']);
						$doerID = $resvc['doerID'];
						$doerStamp = $resvc['doerStamp'];
						$tranActionID = $resvc['tranActionID'];
						$appvXID = $resvc['appvXID']; //ฝ่ายบัญชีที่อนุมัติจะใช้สำหรับตรวจสอบในส่วนการเงินว่าไม่ให้เป็นคนเดียวกันกับคนอนุมัติครั้งแรก
						$revTranStatus = $resvc['revTranStatus']; //สถานะสำหรับตรวจสอบกรณีให้แสดงปุ่ม "เลือกใช้รายการนี้"
						$contractID = $resvc['contractID']; //เลขที่สัญญาที่โอน
						
						$dateRevStamp=trim(substr($bankRevStamp,0,10)); //วันที่โอน
						$timeRevStamp=trim(substr($bankRevStamp,10)); //เวลาที่โอน
						$balanceAmt=trim($resvc['balanceAmt']);
						$cusnamebill=trim($resvc['cusnamebill']); //ชื่อลูกค้าใน bill
						
						$qr_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$doerID'");
						$rs_doer = pg_fetch_array($qr_doer);
						$doerName = $rs_doer['fullname'];	

						// หาข้อมูลว่าไม่ใช่เงินของลูกค้า หรือไม่ ถ้าเป็น 1 คือไม่ใช่เงินของลูกค้า
						$qry_isAnonymous = pg_query("select \"isAnonymous\" from finance.thcap_receive_transfer where \"revTranID\" = '$revTranID' ");
						$isAnonymous = pg_result($qry_isAnonymous,0);

						if($resvc['bankRevRef1']!="" and $resvc['bankRevRef2'] !=""){
							$txtinvoice="<br><span onclick=\"javascript:popU('Channel_detail_i.php?debtInvID=$resvc[invoiceID]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor:pointer;color:#228B22;\">(ใบแจ้งหนี้ <u>$resvc[invoiceID]</u>)</span>";
						}else{
							$txtinvoice="";
						}
						$textcustomer="<br><font color=blue>$cusnamebill</font>";
					
									
						
							$i+=1;
							echo "<form method=\"post\" action=\"../Payments_Other/Payments_history.php\">";
							if($i%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							$qry_acc = pg_query("select \"BAccount\", \"BName\" from \"BankInt\" where \"BID\" = '$BID'");
							if($re_acc = pg_fetch_array($qry_acc)){
								$BAccount = $re_acc['BAccount'];
								$BName = $re_acc['BName'];
								$bankRevAccID="$BAccount-$BName";
							}
							?>
								<td height="30"><?php echo $dateRevStamp; ?></td>
								<td><?php echo $timeRevStamp; ?></td>
								<td width="180"><?php echo $revTranID."$txtinvoice $textcustomer <span style=\"color:red;cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><u>$contractID</u></span>"; ?></td>
								<td><?php echo $cnID; ?></td>
								<td><?php echo $bankRevAccID; ?></td>
								<td><?php echo $bankRevBranch; ?></td>
                                <td><?php echo $doerName; ?></td>
								<td><?php echo $doerStamp; ?></td>
								<td align="right"><?php if($isAnonymous == "1"){echo "<img src=\"images/anonymous_icon.jpg\" width=\"20\" height=\"20\" title=\"ไม่ทราบว่าเงินของใคร\">";} ?>
								<a onclick="javascript:popU('popup-trans-like-contract.php?money=<?php echo $bankRevAmt; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=650')" style="cursor:pointer;"  title="คลิกเพื่อดูรายละเอียด"><u><?php echo number_format($bankRevAmt,2); ?></u></a></td>
								<?php
								if($revTranStatus=="1" || $revTranStatus=="6"){ //กรณีอนุมัติแล้วให้แสดงปุ่ม "เลือกใช้รายการนี้"
									//กำหนดให้อนุมัติได้เฉพาะคนที่ไม่ได้ทำรายการและคนละคนกับคนอนุมัติคนแรก หรืออนุมัติได้เฉพาะผู้ที่มีสิทธิ์เท่านั้น (กรณีฝ่ายบัญชีอนุมัติ appvXID จะเป็นค่า null)
									if(($doerID!=$user_id and $appvXID!=$user_id) || $emplevel<=1){
										if($revTranStatus=="6"){
											$txtamt="<br><font color=red>(เงินคงเหลือใช้ได้ ".number_format($balanceAmt,2).")</font>";
										}else{
											$txtamt="";
										}
										echo "<td>
										<input type=\"hidden\" name=\"revTranID\" value=\"$revTranID\">
										<input type=\"hidden\" name=\"statusLock\" value=\"1\">
										<input type=\"hidden\" name=\"statusPay\" value=\"revTranID\">
										<input type=\"hidden\" name=\"ConID\" value=\"$contractID\">
										<input type=\"submit\" value=\"เลือกใช้รายการนี้\"> $txtamt";
										echo "</td>";
										echo "<td><img src=\"images/note_icon.png\" width=\"20\" height=\"20\" title=\"บันทึกข้อความ\" onclick=\"contact_note('$revTranID')\" style=\"cursor:pointer;\">";
										//ตรวจสอบ emplevel ข้อพนักงาน
										if($emplevel<=1){
											//ตรวจสอบก่อนว่าเงินใช้ไปแล้วหรือยัง ถ้าใช้แล้วจะไม่สามารถล้างรายการได้
											if($bankRevAmt==$balanceAmt){
												echo "<img src=\"images/clean.png\" width=\"23\" height=\"23\" title=\"ทำรายการล้างข้อมูล กลับไปสถานะตรวจสอบรายการ\" onclick=\"clearapp('$revTranID')\" style=\"cursor:pointer;\">";
											}
										}
										echo "</td>";
									}else{
										echo "<td>ไม่สามารถทำรายการได้</td>";
									}
										
								}else{
									//กำหนดให้อนุมัติได้เฉพาะคนที่ไม่ได้ทำรายการและคนละคนกับคนอนุมัติคนแรก หรืออนุมัติมัติได้เฉพาะผู้ที่มีึสิทธิ์เท่านั้น (กรณีฝ่ายบัญชีอนุมัติ appvXID จะเป็นค่า null)
									if(($doerID!=$user_id and $appvXID!=$user_id) || $emplevel<=1){
										echo "<td><span onclick=\"javascript:popU('frm_checkbill.php?revTranID=$revTranID&app=$app&tranActionID=$tranActionID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')\" style=\"cursor: pointer;\" title=\"ยังไม่ตรวจ\"><font color=\"red\"><u>ตรวจสอบ</u></font></span>";										
										
										$qrychq=pg_query("SELECT \"revChqID\", \"bankChqNo\"
										FROM finance.\"V_thcap_receive_cheque_chqManage\" WHERE \"bankRevResult\" in(1,2) AND \"revChqStatus\"=6 AND \"BID\"='$BID'
										AND \"bankChqAmt\"='$bankRevAmt' AND date(\"giveTakerDate\")='$datemain'");
										$numchq=pg_num_rows($qrychq);
										//ตรวจสอบรายการว่าน่าจะเป็นเช็คหรือไม่
										$sql_chq=pg_query("select a.\"revChqID\" as \"revChqID\",a.\"bankChqNo\" as \"bankChqNo\" from finance.\"thcap_receive_cheque\" a
										left join finance.\"thcap_receive_cheque_keeper\" b on a.\"revChqID\" =b.\"revChqID\" 
										where b.\"bankRevResult\" ='3' and   b.\"BID\"='$BID' AND \"bankChqAmt\"='$bankRevAmt' AND date(\"bankRevDate\")='$datemain'
										and \"chqKeeperID\" not in(select \"chqKeeperID\" from finance.\"thcap_receive_transfer\" where  \"chqKeeperID\" is not null)
										");
										$numsql_chq=pg_num_rows($sql_chq);
										
										if(($numchq>0) and ($numsql_chq==0)){
											echo "<br><font color=#698B69><b>(รายการนี้น่าจะเป็น Cheque เลขที่ ";
											$nc=0;
											while($reschq=pg_fetch_array($qrychq)){
												$nc++;
												$revChqID=$reschq['revChqID'];
												$bankChqNo=$reschq['bankChqNo'];
												if($nc==1){
												echo "<span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$revChqID&bankChqNo=$bankChqNo	','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\">
													<u>$bankChqNo</u></span>";
												}
												else{
													echo "<span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$revChqID&bankChqNo=$bankChqNo','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\">
													 หรือ <u>$bankChqNo</u></span>";
												}
											}
											echo ")</b></font>";
										}elseif(($numchq==0) and ($numsql_chq>0)){
											echo "<br><font color=red><b>(รายการนี้น่าจะเป็น Cheque เลขที่ ";
											$nc=0;
											while($res_chq=pg_fetch_array($sql_chq)){
												$nc++;
												$revChqID=$res_chq['revChqID'];
												$bankChqNo=$res_chq['bankChqNo'];
												if($nc==1){
												echo "<span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$revChqID&bankChqNo=$bankChqNo	','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\">
													<u>$bankChqNo</u></span>";
												}
												else{
													echo "<span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$revChqID&bankChqNo=$bankChqNo','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\">
													 หรือ <u>$bankChqNo</u></span>";
												}
											}
											echo ")</b></font>";
										}elseif(($numchq>0) and ($numsql_chq>0)){
											echo "<br><b>(รายการนี้น่าจะเป็น Cheque เลขที่ ";
											$nc=0;
											while($res_chq=pg_fetch_array($sql_chq)){
												$nc	++;
												$revChqID=$res_chq['revChqID'];
												$bankChqNo=$res_chq['bankChqNo'];
												if($nc==1){
												echo "<span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$revChqID&bankChqNo=$bankChqNo	','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\">
													<u>$bankChqNo</u></span>";	
												}
												else{
													echo "<span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$revChqID&bankChqNo=$bankChqNo','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\">
													 หรือ <u>$bankChqNo</u></span>";
												}
											}
											echo ")</b>";
										
										}
										echo "</td>";
										echo "<td width=\"60\">";
										//กรณีที่ยังไ่ม่ได้ตรวจสอบและพนักงานมี emplevel<=1
										if($revTranStatus=="9" and $emplevel<=1)
										{
											if($isAnonymous == 0){echo "&nbsp;<img src=\"images/anonymous_icon.jpg\" width=\"23\" height=\"23\" title=\"ไม่ทราบว่าเงินของใคร\" onclick=\"anonymous('$revTranID')\" style=\"cursor:pointer;\">";}
											echo "&nbsp;<img src=\"images/del.png\" width=\"23\" height=\"23\" title=\"ลบข้อมูล\" onclick=\"delapp('$revTranID')\" style=\"cursor:pointer;\">";
											echo "&nbsp;<img src=\"images/return.gif\" width=\"20\" height=\"20\" title=\"เช็คคืน\" onclick=\"badchq('$revTranID')\" style=\"cursor:pointer;\">";
										}
										echo "<img src=\"images/note_icon.png\" width=\"20\" height=\"20\" title=\"บันทึกข้อความ\" onclick=\"contact_note('$revTranID')\" style=\"cursor:pointer;\">";
										echo "</td>";
										
									}else{
										echo "<td colspan=\"2\">ไม่สามารถทำรายการได้</td>";
									}
								}
								?>
							</form>
							</tr>
							<?php
							
					} //end while รายการที่แสดง
				
					//ค้นหา check ของวันนั้นๆ
					
					$qrychq2=pg_query("SELECT \"revChqID\", \"bankChqNo\", \"revChqToCCID\", \"namebank\", \"giveTakerDate\", \"bankChqAmt\"
					FROM finance.\"V_thcap_receive_cheque_chqManage\" 
					WHERE \"bankRevResult\" IN ('1','2') AND \"revChqStatus\"='6' AND \"BID\"='$acctype[$loop]'
					AND date(\"giveTakerDate\")='$datemain' AND \"chqKeeperID\" NOT IN (SELECT \"chqKeeperID\" FROM finance.thcap_receive_transfer WHERE \"chqKeeperID\" IS NOT NULL) 
					ORDER BY \"revChqID\"");
					$numrows2=pg_num_rows($qrychq2);
					if($numrows2>0){ //กรณีพบข้อมูล
						if($emplevel<=1){
							$colspan="9";
						}else{
							$colspan="7";
						}
						echo "<tr>
						<td colspan=12>
						<table width=\"100%\" border=\"0\" style=\"border-style: dashed; border-width: 1px; border-color:#698B69; margin-bottom:5px\">
						<tr bgcolor=#CDCDC1><td colspan=$colspan><b>วันที่ $datemain  เช็คทั้งหมดที่ยังไม่ได้นำไปใช้ </b></td></tr>
						<tr bgcolor=#8B8B83  style=\"color:#FFFFFF\">
							<th width=150>รหัสรายการเช็ค</th>
							<th>เลขที่เช็ค</th>
							<th>เลขที่สัญญา</th>
							<th>ธนาคารที่ออกเช็ค</th>
							<th>วันที่นำเข้า</th>
							<th>จำนวนเงิน</th>
						";
						if($emplevel<=1){
							echo "<th>map เช็คกับใบเสร็จ</th>";
							echo "<th>รายการพิเศษ</th>";
						}
						echo "</tr>";
							$p=0;
							while($reschq2=pg_fetch_array($qrychq2)){
								$p++;
								if($p%2==0){
									$color="#FFFFF0";
								}else{
									$color="#EEEEE0";
								}
								echo "<tr align=center bgcolor=$color>
								<td><span onclick=\"javascript:popU('frm_showcheque.php?revChqID=$reschq2[revChqID]&bankChqNo=$reschq2[bankChqNo]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=600')\" style=\"cursor: pointer;\"><u>$reschq2[revChqID]</u></span></td>
								<td><span onclick=\"javascript:popU('Channel_detail_chq.php?revChqID=$reschq2[revChqID]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=550')\" style=\"cursor:pointer\"><u>$reschq2[bankChqNo]</u></span></td>
								<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$reschq2[revChqToCCID]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$reschq2[revChqToCCID]</u></span></td>
								<td>$reschq2[namebank]</td>
								<td>$reschq2[giveTakerDate]</td>
								<td align=right><a onclick=\"javascript:popU('popup-trans-like-contract.php?money=".$reschq2[bankChqAmt]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=650')\" style=\"cursor:pointer;\"  title=\"คลิกเพื่อดูรายละเอียด\"><u>".number_format($reschq2[bankChqAmt],2)."</u></font></td>
								
								";
								if($emplevel<=1){
									echo "<td align=center><img src=\"images/mix.png\" width=25 height=24 onclick=\"javascript:popU('popup-mapchq.php?revChqID=$reschq2[revChqID]&bankChqNo=$reschq2[bankChqNo]&contractID=$reschq2[revChqToCCID]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=650')\" style=\"cursor:pointer;\"  title=\"map เช็คกับใบเสร็จที่ออกไปแล้ว\"></td>";
									echo "<td align=center><img src=\"images/refresh.png\" width=24 height=24 onclick=\"returnchq('$reschq2[revChqID]')\" style=\"cursor:pointer;\"  title=\"ย้อนกลับไป ยืนยันนำเช็คเข้าธนาคาร\"></td>";
								}
								echo "</tr>";
							}
							
						echo "</table>
						</td></tr>";
					}					
				 //end while วันที่แสดง	
				//การแสดง กรอบเช็คเด้งที่ยังไม่ได้ผูก 	
				include("frm_bounced_check.php");			
				}		
				
			} // end while ธนาคารที่แสดง	
		}		
	}
}
?>
</table>
