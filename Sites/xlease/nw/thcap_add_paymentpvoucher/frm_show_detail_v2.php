<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td align="left" width="15%"><b>วันที่ทำรายการ :</b></td>
        <td width="85%">
		<input type="text" id="datepicker" name="datepicker"  value="<?php echo nowDate(); ?>" size="15"></td>
    </tr>	
    <tr>
        <td align="left" valign="top"><b>คำอธิบายรายการ :</b></td>
        <td>
		<textarea id="text_add" name="text_add" rows="5" cols="50"></textarea>
        </td>
    </tr>
	<tr>
	<td><b>จ่ายให้ :<b></td>
	<td>
	<input type="radio" id="to1" name="to" onchange="topayfull('0');" value="0" <?php if($to=="" || $to=="0"){ echo "checked"; }?>  />บุคคลภายนอก
	<input type="radio" id="to2" name="to" onchange="topayfull('3');" value="3" <?php if($to=="3"){ echo "checked"; }?>/>พนักงานบริษัท
	<input type="radio" id="to3" name="to" onchange="topayfull('1');" value="1" <?php if($to=="1"){ echo "checked"; }?>/>ลูกค้าบุคคล
	<input type="radio" id="to4" name="to" onchange="topayfull('2');" value="2" <?php if($to=="2"){ echo "checked"; }?>/>ลูกค้านิติบุคคล</td>
	 </tr>
	 <tr>
		<td></td>
		<td>
		<span id="payfullin">
		<input  name="topayfullin"  id="topayfullin" size="54" onkeyup="KeyData();" onblur="KeyData();" >
		</span>
		<span id="payfullout">
		<input  name="topayfullout"  id="topayfullout" size="54" >
		</span></td>
	</tr>	
	<tr>
	<tr>
        <td align="left" valign="15%">
			<b>รายการนี้สำหรับตั้งลูกหนี้ตามสัญญาเลขที่ :</b>
		</td>
        <td>
			<input type="text" id="contractid" name="contractid" value="" size="45">
        </td>
	</tr>
	<tr>
        <td align="left" valign="15%">
			<b>วันที่รายการภาษีมูลค่าเพิ่ม :</b>
		</td>
        <td>
			<input type="text" id="datevat" name="datevat" value="" size="45">
        </td>
	</tr>
	<td><b>จุดประสงค์:</b></td>
	<td>
	<select name="voucherPurpose" id="voucherPurpose" OnChange="changepurpose();">
	
		<?php
			if($voucherPurposetype=="PV"){
				$qry_GenType = pg_query("select * from account.\"thcap_purpose\" where \"thcap_purpose_vouchertype\"='1' order by \"thcap_purpose_id\" ");
			}
			else if($voucherPurposetype=="JV"){
				$qry_GenType = pg_query("select * from account.\"thcap_purpose\" where \"thcap_purpose_vouchertype\"='3' order by \"thcap_purpose_id\" ");
			}
			echo "<option value=\"\">-- กรุณาเลือกจุดประสงค์ --</option>";			
			while($res_gentype=pg_fetch_array($qry_GenType)){
				$GenType = $res_gentype["thcap_purpose_id"];
				$GenName = $res_gentype["thcap_purpose_name"];
				
				echo "<option value=\"$GenType\">$GenType : $GenName</option>";
				
			}
			?>
	</select>
	<?php
	if($voucherPurposetype=="PV"){ ?>
		<input type =checkbox name="chk_insert_channel" id="chk_insert_channel" onchange="this.checked=true" checked="checked">
	<?php } else if($voucherPurposetype=="JV"){ ?>
		<input type =checkbox name="chk_insert_channel" id="chk_insert_channel" onchange="this.checked=false" >
	<?php } ?>
	</tr>
</table>