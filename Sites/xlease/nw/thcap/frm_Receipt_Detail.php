<?php
include("../../config/config.php");
$id = $_GET['id'];
$condition=$_GET['condition'];
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
    $('table.sort-table').each(function(){
        var table = $(this); // เก็บตารางไว้ในตัวแปร table จะได้อ้างถึงได้ง่ายๆ
        $('th', table).each(function(column) { // เลือกหัว (th) ของแต่ละแถว
			var header = $(this); // เก็บ th ไว้ในตัวแปรจะได้อ้างง่ายๆ
            var sortKey = false; // ตัวแปรบอกว่า แถวนี้เรียงได้หรือไม่
            if(header.is('.sort-text')) { // เรียงคอลั่มแบบตัวอักษร
				sortKey = function(cell) {
					return cell.find('.sort-text').text().toUpperCase() + ' ' + cell.text().toUpperCase();// ทำการส่งข้อมูลในแต่ละช่องไปในตัวแปร sortKey
                };
            }else if(header.is('.sort-number')) { // เรียงคอลั่มแบบตัวเลข
                sortKey = function(cell) {
                    var temp = cell.text();
                    temp = parseFloat(temp);
                    return isNaN(temp)? 0 : temp;// ทำการเลือกแต่ละช่องโดยตรวจสอบก่อนว่าเป็นตัวเลขหรือไม่ ถ้าไม่เป็นให้เปลี่ยนค่าเป็น 0
                };
            }
                               
            if(sortKey) { // ถ้าตัวแปร sortKey มีค่าใส่เข้าไป
                header.click(function(){ // เมื่อคลิกที่ th ของแต่ละช่อง
					var sortDirection = 1; // 1 = น้อยไปมาก -1 = มากไปน้อย
					if(header.is('.sorted-asc')) { // class .sorted-asc เป็นตัวบอกว่าเรียงจากน้อยไปมากอยู่
                        sortDirection = -1;
                    }
                    var rows = table.find('tbody > tr').get(); // เอาค่าทุกแถวที่อยู่ใน tbody ออกมา
                    $.each(rows, function(index, row){ // ทำการเรียง
                        var cell = $(row).children('td').eq(column);
                        row.sortKey = sortKey(cell);
                    });
                    rows.sort(function(a, b) { // บอกทิศทางการเรียง
                        if(a.sortKey < b.sortKey) return -sortDirection;
                        if(a.sortKey > b.sortKey) return sortDirection;
                    });
                    $.each(rows, function(index, row){ // สลับแถวในตาราง
                        table.children('tbody').append(row);
                        row.sortKey = null;
                    });
                    //  ด้านล่างแค่บอกว่าเรียงไปทางไหนแล้วเฉยๆ
                    table.find('th').removeClass('sorted-asc').removeClass('sort-desc');
                    if(sortDirection == 1) {
                       header.addClass('sorted-asc');
                    }else {
                        header.addClass('sorted-desc');
                    }
                    table.find('th').removeClass('sorted').filter(':nth-child(' + (column+1) +')').addClass('sorted');
                });
            }
        });
	
		$('#a1').click( function(){   
			$('#a1').css('background-color', '#ff6600');   
			$('#a2').css('background-color', '#BCE6FC'); 
			$('#a3').css('background-color', '#BCE6FC'); 
			$('#a4').css('background-color', '#BCE6FC');
			$('#a5').css('background-color', '#BCE6FC'); 	
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#BCE6FC'); 
			$('#a4').css('background-color', '#BCE6FC');
			$('#a5').css('background-color', '#BCE6FC'); 	
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#BCE6FC'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#BCE6FC'); 
			$('#a5').css('background-color', '#BCE6FC'); 
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#BCE6FC'); 
			$('#a3').css('background-color', '#BCE6FC'); 
			$('#a4').css('background-color', '#ff6600');
			$('#a5').css('background-color', '#BCE6FC'); 	
		});
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#BCE6FC'); 
			$('#a3').css('background-color', '#BCE6FC'); 
			$('#a4').css('background-color', '#BCE6FC');
			$('#a5').css('background-color', '#ff6600'); 	
		}); 

    });
});
</script>

<?php
if($id != ""){
	echo "<table width=\"1050\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" bgcolor=\"#D7F0FD\" align=\"center\" class=\"sort-table\">
	<thead>
	<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#BCE6FC\">
		<th>เลขที่สัญญา</th>
		<th id=\"a5\" class=\"sort-text\" >ผู้กู้หลัก</th>
		<th id=\"a1\" class=\"sort-text\" style=\"cursor:pointer;background-color:#ff6600;\">เลขที่ใบเสร็จ</th>
		<th id=\"a2\" class=\"sort-text\" style=\"cursor:pointer;\">วันที่จ่าย</th>
		<th id=\"a3\" class=\"sort-number\" style=\"cursor:pointer;\">จำนวนเงินที่จ่าย</th>
		<th id=\"a4\" class=\"sort-text\" style=\"cursor:pointer;\">ช่องทางการจ่าย</th>
		<th>พิมพ์ใบเสร็จเป็นชุด</th>
		<th>พิมพ์เฉพาะต้นฉบับ</th>
		<th>พิมพ์เฉพาะสำเนา</th>
	</tr>
	</thead>
	";
	if($condition=="1"){
		//ค้นหาจากเลขที่สัญญา
		
		$qry_con=pg_query("select \"contractID\" as contract,\"receiptID\",\"receiveDate\",\"byChannel\"
		from thcap_v_receipt_otherpay 
		WHERE \"contractID\" = '$id' 
		group by \"contractID\",\"receiptID\",\"receiveDate\",\"byChannel\" order by \"receiptID\"");
		

		
		$numcon=pg_num_rows($qry_con);
		
		if($numcon>0){ //แสดงว่ามีข้อมูล
			$status=1;
			
			
		}else{
			$status=0;
		}
	}else if($condition=="2"){
		//ค้นหาจากเลขที่ใบเสร็จ
		$qry_con=pg_query("select \"debtID\",\"contractID\" as contract,\"receiptID\",\"receiveDate\",\"debtAmt\" as \"receiveAmount\",\"typePayID\",\"byChannel\" from thcap_v_receipt_otherpay 
		WHERE \"receiptID\" = '$id' order by \"receiptID\" limit 1");
		$numrec = pg_num_rows($qry_con);
		
		
		if($numrec>0){
			$status=1;
			
			$qry_con1=pg_query("select sum(\"debtAmt\") as \"receiveAmount\" from thcap_v_receipt_otherpay 
			WHERE \"receiptID\" = '$id'");
			$result1=pg_fetch_array($qry_con1);
		}else{
				$status=0;		
		}
	}
	
	if($status==1){
		while($result=pg_fetch_array($qry_con)){;	
			$contractID=trim($result["contract"]);
			$receiptID=trim($result["receiptID"]);
			$receiveDate=trim($result["receiveDate"]);
			
			$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = 0");
			list($full_namecus) = pg_fetch_array($qry_cusname);
			
			if($condition=="1"){
				$qry_debtAmt=pg_query("select sum(\"debtAmt\") as \"receiveAmount\" from thcap_v_receipt_otherpay 
				WHERE \"receiptID\" = '$receiptID'");
				$result_debtAmt=pg_fetch_array($qry_debtAmt);
				
				$receiveAmount=trim($result_debtAmt["receiveAmount"]);
				
			}else if($condition=="2"){
				$receiveAmount=trim($result1["receiveAmount"]);
			}
			$byChannel=trim($result["byChannel"]);
			$debtID=trim($result["debtID"]);
			$typePayID=trim($result["typePayID"]);
			IF($typePayID == ""){
				//หาประเภทหนี้ที่ชำระ
				$qry_typepayid = pg_query("select \"typePayID\" from \"thcap_v_receipt_otherpay\" WHERE \"receiptID\" = '$receiptID'");
				list($typePayID) = pg_fetch_array($qry_typepayid);
			}
			
			//หารหัสที่เป็นเงินต้น
			$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
			list($typeID) = pg_fetch_array($select);

			//ตรวจสอบว่าเป็นสัญญาประเภทใด
			$contype=pg_creditType($contractID); 
			
			if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$txtby="ไม่ระบุ";}
			else{
				//นำไปค้นหาในตาราง BankInt
				$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
				$ressearch=pg_fetch_array($qrysearch);
				list($BAccount,$BName)=$ressearch;
				$txtby="$BAccount-$BName";
			}
			$receiveAmount = number_format($receiveAmount,2);
			echo "<tr align=center bgcolor=\"#EAF9FF\">
			<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
			<u>$contractID</u></font></span></td>
			<td>$full_namecus</td>
			<td><span onclick=\"javascript : popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=920,height=680');\" style=\"cursor:pointer;\"><u>$receiptID</u></span></td>
			<td>$receiveDate</td>
			<td align=right>$receiveAmount</td>
			<td>$txtby</td>
			";
			// if($debtID>0){ //ถ้ามีค่าแสดงว่าเป็นค่าใช้จ่ายอื่นๆ
				// echo "<td><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=1&contractID=$contractID','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
				// echo "<td bgcolor=\"#FFC1C1\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=1&contractID=$contractID&type=real','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
				// echo "<td bgcolor=\"#BCEE68\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=1&contractID=$contractID&type=copy','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";

			
			// }else{
				// $qry_conid=pg_query("select a.\"contractID\" from thcap_temp_int_201201  a WHERE a.\"receiptID\" = '$receiptID'");
				// $rows1=pg_num_rows($qry_conid);
				if($typePayID==$typeID && ($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN')){ //กรณีเป็นค่างวด
					echo "<td><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=2','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
					echo "<td bgcolor=\"#FFC1C1\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=2&type=real','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
					echo "<td bgcolor=\"#BCEE68\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=2&type=copy','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";							
				}else{
					echo "<td><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=1&contractID=$contractID','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
					echo "<td bgcolor=\"#FFC1C1\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=1&contractID=$contractID&type=real','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
					echo "<td bgcolor=\"#BCEE68\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$receiptID&t=1&contractID=$contractID&type=copy','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";			
				}
			// }	
			echo "</tr>";
}			
		
	}else{
		echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=\"8\"><h2>-ไม่พบข้อมูลใบเสร็จ-</h2></td></tr>";
	}
	echo "</table>";
}else{ //กรณีไม่กรอกคำค้น
	echo "<center><h2>-กรุณากรอกคำค้นหาก่อนทำรายการ-</h2></center>";
}?>
