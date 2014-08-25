<?php
$av_iduser=$_SESSION["av_iduser"];
/*=============แสดงรายการที่ตรวจสอบ===============*/
if($condate=="1"){
	$conditiondate="EXTRACT(MONTH FROM \"receiveStamp\")='$month' and EXTRACT(YEAR FROM \"receiveStamp\")='$year'";
}else{
	$conditiondate="EXTRACT(MONTH FROM a.\"receiveDate\")='$month' and EXTRACT(YEAR FROM a.\"receiveDate\")='$year'";
}
?>
<form name="my" method="post" action="process_checkreceipt.php">

<fieldset><legend>ตรวจสอบรายการรับชำระเงิน</legend>
<div><span style="background-color:#CCCCCC;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><b> ใบเสร็จที่ยกเลิกแล้ว</b></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="color:#FFF;" valign="top" bgcolor="#838B83" align="center" >
	<td width="100">เลขที่ใบเสร็จ</td>
	<td width="70">วันที่รับชำระ</td>
	<td width="70">วันที่ทำรายการ</td>
	<td width="100">เลขที่สัญญา</td>
	<td width="200">ชื่อลูกค้า</td>
	<td width="270">รายละเอียดการรับชำระ</td>
	<td width="100">จำนวนเงิน</td>
	<td width="70"><!--a onclick="javascript:selectAll();" style="cursor:pointer;"><font color="#FFF">เลือกทั้งหมด</font></a--></td>
</tr>

<?php
$iduserold="";
$receiptID1="";
$sumamtuser=0; //เงินรวมทั้งหมดที่ user แต่ละคนรับชำระ
$sumamtall=0; //เงินรวมทั้งหมดทุก user
$i=0;
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$av_iduser' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];
if($emplevel<=1){ //ผู้ใช้งาน มี emplevel<=1 จะมองเ็ห็น เลขที่สัญญานั้น แม้ว่า จะตรวจสอบไปแล้ว
	if($bankint==""){ //ไม่มีการเลือกช่องทาง
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\"	where a.\"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where \"doerID\" is not null and \"doerID2\" is not null)
		and	  $conditiondate order by \"receiveUser\",a.\"receiptID\"");
	}
	else{   //มีการเลือกช่องทาง
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\"
		left join \"thcap_temp_receipt_channel\" c   on a.\"receiptID\"=c.\"receiptID\" where a.\"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where \"doerID\" is not null and \"doerID2\" is not null)
		and	 $conditiondate and  c.\"byChannel\"='$bankint' order by \"receiveUser\",a.\"receiptID\"");
	}
}
else{ //ผู้ใช้งาน มี emplevel >1 จะมองจะมองเไม่็ห็น เลขที่สัญญานั้นที่ตนตรวจไปแล้ว
	if($bankint==""){
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\",a.\"doerID\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\" where \"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where  \"doerID\" ='$av_iduser' or \"doerID2\" ='$av_iduser' or (\"doerID\" is not null and \"doerID2\" is not null)) and $conditiondate order by \"receiveUser\",a.\"receiptID\"");
	}
	else{
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\"
		left join \"thcap_temp_receipt_channel\" c   on a.\"receiptID\"=c.\"receiptID\" where a.\"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where  \"doerID\" ='$av_iduser' or \"doerID2\" ='$av_iduser' or (\"doerID\" is not null and \"doerID2\" is not null)) and $conditiondate and  c.\"byChannel\"='$bankint' order by \"receiveUser\",a.\"receiptID\"");
	}
}
while($resreceipt=pg_fetch_array($qryreceipt)){
	$receiptID=$resreceipt["receiptID"]; //เลขที่ใบเสร็จ
	$iduser=$resreceipt["id_user"]; //รหัสผู้ทำรายการรับชำระ
	$username=$resreceipt["fullname"]; //ชื่อผู้ทำรายการรับชำระ
	
	//กรณีคนละเลขที่ใบเสร็จให้แสดงช่องทางการชำระเงิน
	if($receiptID1!=""){
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID1' order by \"ChannelAmt\"  DESC");
	$sumchan=0;
			while($resstar=pg_fetch_array($qryredstar)){
				$chan=$resstar["byChannel"];
				$amt=$resstar["ChannelAmt"];
				$sumchan+=$amt;
				$byChannelRef=$resstar["byChannelRef"];
				
				$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
				list($chkhold) = pg_fetch_array($qry_hold);
										
				$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
				list($chksecur) = pg_fetch_array($qry_secur);
				
				if($chan=="999"){
					$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
				}else{
					//นำไปค้นหาในตาราง BankInt
					$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
					$ressearch=pg_fetch_array($qrysearch);
					list($BAccount,$BName)=$ressearch;
					$txtchannel3="ช่องทาง : $BAccount-$BName";
					
					if($chan==$chkhold || $chan==$chksecur || $chan=='990' || $chan=='991'){
						$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
					}
				}
			}
	}
		
	//แสดงผู้ทำรายการ ถ้าชื่อซ้ำกันจะไม่ให้แสดงซ้ำ 
	if($iduserold!=$iduser || $iduserold==""){
		//แสดงจำนวนเงินของ user ก่อนหน้า
		if($iduserold!=""){
		
			echo "<tr bgcolor=\"#E0EEE0\"><td colspan=6 align=right><b>จำนวนเงินรวมทุกใบเสร็จ : </b></td><td align=right><b>".number_format($sumamtuser,2)."</b></td><td bgcolor=\"#E0EEE0\"></td></tr>";
			$sumamtuser=0; //เริ่มนับจำนวนเงินรวมใหม่
		}
		$numberno++;
	
	$divname="datadetail".$numberno;  
	$divname1="user".$numberno;  //id ของ แสดง/ซ้อนข้อมูล
	$divname2="select".$numberno;  //id ของ เลือก
	//ตรวจสอบ levelของผู้ใช้
	$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$av_iduser' ");
	$leveluser = pg_fetch_array($query_leveluser);
	$emplevel=$leveluser["emplevel"];
	
		?>
		
		<tr bgcolor="#FFCCCC">
		
			<td colspan=6><b>ผู้รับเงิน : <?php echo $username; ?>(<?php echo $iduser; ?>)</b></td>
			<td><input type="checkbox" name="appentShowHide" id="<?php echo $divname1;?>" onChange=ShowData(<?php echo "'$divname1','$divname','$condate','$iduser','$month','$year','$divname2'";?>);>แสดง/ซ่อนข้อมูล</td>
			<?php if($emplevel<=3){  ?>
				<td><input type="checkbox" name="appent_1" id="<?php echo $divname2;?>" onChange=SelectData(<?php echo "'$divname','$divname1','$divname2'";?>);<?php ?> >เลือกทั้งหมด</td>
			<?php }else {?>
				<td></td>
			<?php }?>
			
		</tr>
		<tr>
		<td colspan=8>
			<div id ="<?php echo $divname;?>">
			
			</div>
		</td>
		</tr>		
		<?php 		
	}	
	//หารายการที่ชำระทั้งหมดของใบเสร็จที่ได้
	$receiptID_old="";
	$sumAmount=0; //จำนวนเงินรวมในใบเสร็จ
	$qrydata=pg_query("select \"receiptID\",\"receiveDate\",\"doerStamp\",\"contractID\",\"debtAmt\"-\"whtAmt\" as debtamt,\"whtAmt\", \"cusFullname\", 
	\"tpDesc\"||\"tpFullDesc\"||' '||\"typePayRefValue\" as detail,\"typePayID\",\"typePayRefValue\",\"tpDesc\",\"debtID\" as debtid,
	\"byChannelRef\",\"status\" from thcap_v_receipt_otherpay_all 
	where \"receiptID\"='$receiptID'  order by \"typePayID\"");
	while($result=pg_fetch_array($qrydata)){
		$receiptID1=$result["receiptID"]; //เลขที่ใบเสร็จของรายการนี้
		$receiptID2=$result["receiptID"]; //เลขที่ใบเสร็จ ที่เป็นตัวแปรตาม ถ้าใบเสร็จซ้ำกัน ตัวแปรนี้จะเป็นค่าว่าง
		$receiveDate=$result["receiveDate"]; //วันที่รับชำระ
		$doerStamp=$result["doerStamp"]; //วันที่ทำรายการรับชำระ
		$contractID=$result["contractID"]; //เลขที่สัญญา
		$cusname=$result["cusFullname"]; //ชื่อลูกค้า
		$receiveAmount=$result["debtamt"]; //จำนวนเงินที่ชำระ
		$sumAmount+=$receiveAmount; //จำนวนเงินรวมในใบเสร็จ
		$sumamtuser+=$receiveAmount; //เงินรวมทั้งหมดของ user แต่ละคนที่รับชำระ
		
		$sumamtall+=$receiveAmount; //เงินรวมทั้งหมดของทุก user ที่รับชำระ
		$whtAmt=$result["whtAmt"]; //ภาษีหัก ณ ที่จ่าย
		$typePayID=$result["typePayID"]; //รหัสรายการที่จ่าย
		$detail=$result["detail"]; //รายละเอียดรายการที่จ่าย
		$statusrec=$result["status"]; //สถานะใบเสร็จ 1=ไม่ยกเลิก 2=ยกเลิกแล้ว
		
		$i++;
		if($i%2==0){
			$color="#F0FFF0";
		}else{
			$color="#F5FFFA";		
		}
		$color2="#E0EEE0"; //แสดงช่องทาง
		if($statusrec=='2'){
			$color="#CCCCCC";
			$color2="#CCCCCC";
		}
		
		//ถ้าเลขที่ใบเสร็จเดียวกัน ให้แสดงแค่ครั้งเดียว
		if($receiptID_old==$receiptID1){
			$receiptID2=""; //ไม่ต้องแสดงเลขที่ใบเสร็จซ้ำ
			$receiveDate=""; //ไม่ต้องแสดงวันที่รับชำระซ้ำ
			$doerStamp=""; //วันต้องแสดงวันที่ทำรายการรับชำระซ้ำ
			$contractID=""; //ไม่ต้องแสดงเลขที่สัญญาซ้ำ
			$cusname=""; //ไม่ต้องแสดงชื่อลูกค้าซ้ำ
			$check=""; //ไม่ต้องแสดง checkbox ให้เลือกซ้ำ
			
			if($color=="#F0FFF0"){
				$color="#F5FFFA";
			}else{
				$color="#F0FFF0";
			}
			if($statusrec=='2'){
				$color="#CCCCCC";
				$color2="#CCCCCC";
			}
			$i--;
		}else{
			//$check="<input type=\"checkbox\" name=\"chk8[]\" value=\"$receiptID\">";
		}
	$receiptID_old=$receiptID1;
	} //end while แสดงรายการในใบเสร็จ
	//แสดงจำนวนเงินรวมในใบเสร็จ
	$receiptID1=$receiptID;
	$iduserold=$iduser;
}
//แสดงช่องทางการรับชำระของใบเสร็จใบสุดท้าย
$sumchan=0; //รวมทุกช่องทาง
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC");
	while($resstar=pg_fetch_array($qryredstar)){
		$chan=$resstar["byChannel"];
		$amt=$resstar["ChannelAmt"];
		$amt=$resstar["ChannelAmt"];
		$sumchan+=$amt;
		$byChannelRef=$resstar["byChannelRef"];
		
		$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
		list($chkhold) = pg_fetch_array($qry_hold);
								
		$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
		list($chksecur) = pg_fetch_array($qry_secur);
		
		if($chan=="999"){
			$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
		}else{
			//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$txtchannel3="ช่องทาง : $BAccount-$BName";
			
			if($chan==$chkhold || $chan==$chksecur || $chan=='990' || $chan=='991'){
				$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
			}
		}
	}
	echo "<tr bgcolor=\"$color2\"><td colspan=6 align=right><b>จำนวนเงินรวมทุกใบเสร็จ : </b></td><td align=right><b>".number_format($sumamtuser,2)."</b></td><td bgcolor=\"$color2\"></td></tr>";
	//สรุปเงินรวมทุกช่องทาง
	
?>	
</table>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="color:#FFF;" valign="top" bgcolor="#FFFFFF" align="center" >	
	<td width="100"></td>
	<td width="70"></td>
	<td width="70"></td>
	<td width="100"></td>
	<td width="200"></td>
	<td width="270"></td>
	<td width="100"></td>
	<td width="70"></font></td>
</tr>
<tr bgcolor="#FFCCCC">
	<td colspan=6 align=right><b>จำนวนเงินรวมทั้งหมด : </b></td>
	<td align=right><b><?php echo number_format($sumamtall,2);?></b></td>
	<td></td></tr>
</table>
<script type="text/javascript">

function ShowData(divname1,divname,condate,iduser,month,year,divname2){	//ติก  แสดง /ซ่อน ข้อมูล
	
	if(document.getElementById(divname1).checked == true)
	{  
		var data = $.ajax({    
			url: "ChangeShowData.php?&condate="+condate+"&iduser="+iduser+"&month="+month+"&year="+year+"&divname="+divname+"&divname2="+divname2+"&byChannel=<?php echo $bankint ;?>", 
			async: false  
		}).responseText;	
		$("#"+divname).html(data);		
	}
	else
	{
		var data="";
		$("#"+divname).html(data);
		document.getElementById(divname2).checked = false;
	
	}
	if(document.getElementById(divname2).checked == true)
	{  
		document.getElementById(divname2).checked = false;
	}
}
function SelectData(divname,divname1,idname){	//Change box เลือก
 
    if(document.getElementById(divname1).checked == false)
	{	document.getElementById(idname).checked = false;
		alert('กรุณา เลือก " แสดง/ซ่อนข้อมูล  " ก่อน!');		
	}
	else
	{
		var ele=$('input[name='+divname+']');  
		if(document.getElementById(idname).checked == true)
		{  		
			var num;
			num=0;
			for (i=0; i< ele.length; i++)
			{
				if($(ele[i]).is(':checked')){
					num+=1;
				}
			}
			for (i=0; i< ele.length; i++)
			{
				$(ele[i]).attr ( "checked" ,"checked" );
			}
		}
		else
		{ 	
			for (i=0; i< ele.length; i++)
			{
				$(ele[i]).removeAttr('checked');
			}
		}
	}
}
function Clicksave(){
	var  no =1;
	var num=0;
	var recid="";
	while(no<=<?php echo $numberno?>)  //ตรวจสอบดูว่า มี การติก ถูกหรือไม่
	{
		var chkcheck="datadetail"+no;
		var ele=$('input[name='+chkcheck+']');  
		
			for (i=0; i< ele.length; i++)
			{
				if($(ele[i]).is(':checked')){					
					recid+=$(ele[i]).val()+"#";
					num+=1;
				}				
			}
			no+=1;	
	}
	
	if(num==0){ 
		alert("กรุณาเลือกรายการ");
		return false;	
	}
	else
	{   document.getElementById('receipt').value=recid;
		return true;
		/*$.post("process_checkreceipt.php",{
				receipt : JSON.stringify(receipt) 
			},			
			function(data){
				if(data == 1){
					alert("มีบางรายการตรวจสอบก่อนหน้านี้แล้ว กรุณาตรวจสอบ");
				}else if(data==2){
					alert("บันทึกรายการเรียบร้อย");
					location.href = "frm_Index.php?val=1&condate="+'<?php echo $condate;?>'+"&month="+'<?php echo $month;?>'+"&year="+'<?php echo $year;?>'+"&bankint="+'<?php echo $bankint;?>';//refresh เป็นหน้าแรก
				}else if(data==4){
					alert("ผิดผลาด เนื่องจากผู้ที่ทำรายการรับเงิน ไม่สามารถทำการตรวจสอบรายการชำระเงินได้  หรือ ผู้ที่ทำการตรวจสอบรายการชำระเงินครั้งที่ 1 แล้วไม่สามารถทำการตรวจสอบรายการชำระเงินครั้งที่  2 ได้ ");
				}else if(data==5){
					alert("ผิดผลาด เนื่องจากการตรวจสอบรายการชำระเงินทำได้สูงสุด 2 ครั้งเท่านั้น");
				}
				else{
					alert("ผิดผลาด ไม่สามารถบันทึกได้ กรุณาตรวจสอบ");
				}
			});	*/
	}
}

</script>
<div style="padding-top:20px;text-align:right;">
<input type="submit" id="btnsave" onclick="return Clicksave();" value="บันทึก">
<input type="hidden" name="receipt" id="receipt">
<input type="hidden" name="condate" id="condate" value="<?php echo $condate;?>">
<input type="hidden" name="month" id="month" value="<?php echo $month;?>">
<input type="hidden" name="year" id="year" value="<?php echo $year;?>">
<input type="hidden" name="bankint" id="bankint" value="<?php echo $bankint;?>">
</div>
</form>
</fieldset>