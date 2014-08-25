<?php
$realpath=pg_escape_string($_GET['realpath']);
if($status=="1"){
	$dt=$idno; //กรณีส่งค่ามาจากหน้า "(THCAP) ตารางแสดงการผ่อนชำระ"
}else{
	include("../../config/config.php");
	$dt = pg_escape_string($_GET['dt']);
}

$senddetail = pg_escape_string($_GET['sendID']);

if(empty($dt)){
    exit;
}

if($senddetail==""){
	$condition1="";
	$condition2="";
}else{
	$condition1="and detail='$senddetail'";
	$condition2="and auto_id in (select auto_id from \"thcap_letter_detailRef\" where detail='$senddetail')";
}
?>

<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
    <?php
	if($status!="1"){ //กรณีมาจาก "(THCAP) ตารางแสดงการผ่อนชำระ" จะไม่แสดงข้อมูลนี้
		echo "<td>เลขที่สัญญา</td>";
	}
	?>
    <td>วันที่ส่ง</td>
    <td>ชื่อ/สกุล</td>
    <td>ที่อยู่</td>
	<td>รูปแบบจดหมาย</td>
    <td>ประเภทการส่ง</td>
	<?php
	if($status!="1"){ //กรณีมาจาก "(THCAP) ตารางแสดงการผ่อนชำระ" จะไม่แสดงข้อมูลนี้
		echo "<td>พิมพ์จดหมาย</td>";
		echo "<td>พิมพ์ใบเหลือง</td>";
	}
	?>
</tr>

<?php
$qry_name2=pg_query("select auto_id,\"contractID\",\"sendDate\",\"cusName\",\"addressCon\",\"type_send\",\"regisnumber\",\"CusID\" from \"vthcap_letter\" 
where \"contractID\" ='$dt' $condition1 group by auto_id,\"contractID\",\"sendDate\",\"cusName\",\"addressCon\",\"type_send\",\"regisnumber\",\"CusID\" order by \"contractID\",\"sendDate\"");
$num_row = pg_num_rows($qry_name2);
while($res_name2=pg_fetch_array($qry_name2)){
	$auto_id = $res_name2["auto_id"];
	$contractID = $res_name2["contractID"];
	$sendDate = $res_name2["sendDate"];
	$name = $res_name2["cusName"];
    $address=$res_name2["addressCon"];
    $detail=$res_name2["detail"];
	$type_send=$res_name2["type_send"];
	$reg_num=$res_name2["regisnumber"];
    $CusID=$res_name2["CusID"];
    
	if($CusID!=""){//ตรวจสอบว่าที่อยู่ตรงกับที่ให้ไว้หรือไม่ (ถ้ามีค่าแสดงว่าเป็นที่อยู่ตามสัญญาซึ่งแก้ไขไม่ได้อยู่แล้วจึงไม่ได้เช็ค)
		$address=ereg_replace('[[:space:]]+', '', trim($address)); //ตัดช่องว่างออก
		
		//หาที่อยู่เดิม
		$qry_addold=pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\" 
		WHERE \"contractID\" ='$contractID' and \"CusID\"='$CusID'");
		list($addressold)=pg_fetch_array($qry_addold);
		
		$addressold=ereg_replace('[[:space:]]+', '', trim($addressold)); //ตัดช่องว่างออก
		
		if($address!=$addressold){
			$txtalert="<font color=red>จดหมายอาจถูกส่งไปที่อื่น ไม่ใช้ที่อยู่ที่ลูกค้าลูกค้าแจ้งไว้</font>";
		}else{
			$txtalert="";
		}
	}
    $nub = 0;
    $show_type = "";
    //ค้นหาว่าส่งรายการอะไรบ้าง
	$qrydetail=pg_query("select detail,\"detailRef\" from \"thcap_letter_detailRef\" where \"sendID\"='$auto_id' $condition2");
	while($resdetail=pg_fetch_array($qrydetail)){
		if($resdetail['detailRef']==""){
			$ref="";
		}else{
			//ตรวจสอบว่า ref เป็นใบเสร็จหรือใบแจ้งหนี้
			$qryreceipt=pg_query("select * from thcap_v_receipt_details where \"receiptID\"='$resdetail[detailRef]'");
			if(pg_num_rows($qryreceipt)>0){ //แสดงว่าเป็นใบเสร็จ
				$ref="<span onclick=\"javascript:popU('$realpath/Channel_detail.php?receiptID=$resdetail[detailRef]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><font color=red>(<u>$resdetail[detailRef]</u>)</font></span>";
			}else{
				//ตรวจสอบว่าเป็นใบกำกับหรือไม่
				$qrytax=pg_query("select * from thcap_v_taxinvoice_details where \"taxinvoiceID\"='$resdetail[detailRef]'");
				if(pg_num_rows($qrytax)>0){ //แสดงว่าเป็นใบกำกับ
					$ref="<span onclick=\"javascript:popU('$realpath/Channel_detail_v.php?receiptID=$resdetail[detailRef]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><font color=red>(<u>$resdetail[detailRef]</u>)</font></span>";
				}else{
					//ตรวจสอบว่าเป็นใบแจ้งหนี้หรือไม่
					$qrydebt=pg_query("select * from \"Vthcap_debt_invoice\" where \"debtInvID\"='$resdetail[detailRef]'");
					if(pg_num_rows($qrydebt)>0){ //แสดงว่าเป็นใบแจ้งหนี้
						$ref="<span onclick=\"javascript:popU('$realpath/Channel_detail_i.php?debtInvID=$resdetail[detailRef]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><font color=red>(<u>$resdetail[detailRef]</u>)</font></span>";
					}else{
						$ref="<font color=red>($resdetail[detailRef])</font>";
					}
				}
			}
			
		}
		//หาชื่อของรายการที่ส่ง
		$qry_name3=pg_query("select \"sendName\" from \"thcap_letter_head\" WHERE \"auto_id\"='$resdetail[detail]'");
        if($res_name3=pg_fetch_array($qry_name3)){
			$type_name=$res_name3["sendName"];
			$nub += 1;
			
			if($nub == 1){
				$show_type .= "$type_name $ref";
			}else{
				$show_type .= ", $type_name $ref";
			}
        }
	}

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
	
	if($status!="1"){ //กรณีมาจาก "(THCAP) ตารางแสดงการผ่อนชำระ" จะไม่แสดงข้อมูลนี้
		echo "<td align=\"center\" valign=\"top\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;color:red\"><u>$contractID</u></span></td>";
	}
	?>
    <td valign="top"><?php echo "$sendDate"; ?></td>
    <td valign="top"><?php echo "$name"; ?></td>
    <td valign="top" width="250"><?php echo "$address\n$txtalert"; ?></td>
	<td valign="top"><?php echo "$show_type"; ?></td>
	<td valign="top" width="150" align="center">
		<?php 
		if($type_send == "N"){
			echo "ส่งธรรมดา";
		}else if($type_send == "R"){
			echo "ลงทะเบียน";
		}else if($type_send == "A"){
			if($reg_num != ""){
				$reg_num = $reg_num;
			}else{
				$reg_num = "ไม่ระบุ";
			}
			echo "ลงทะเบียนตอบรับ<br><font color=\"red\">($reg_num)</font>";
		}else if($type_send == "E"){
			echo "EMS";
		}
		?>
	</td>
	<?php
	if($status!="1"){ //กรณีมาจาก "(THCAP) ตารางแสดงการผ่อนชำระ" จะไม่แสดงข้อมูลนี้
		echo "<td valign=\"top\" align=\"center\"><input type=\"button\" value=\"พิมพ์จดหมาย\" onclick=\"window.open('print_letter.php?cus_lid=$auto_id')\"></td>";
		echo "<td valign=\"top\" align=\"center\">";
		
		$nowdate=nowDate();
		$post="คลองจั่น";
		if($reg_num == ""){
			echo "-";
		}else{
			echo "<input type=\"button\" value=\"พิมพ์ใบเหลือง\" onclick=\"window.open('print_yellow.php?cus_lid=$auto_id&nowdate=$nowdate&post=$post')\">";
		}
		echo "</td>";
	}
	?>
</tr>

<?php
	$reg_num="";
} //end while

if($num_row == 0){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr><td colspan=10 align=left>พบข้อมูลทั้งหมด $num_row รายการ</td></tr>";
}
?>
</table>