<?php
include("../../config/config.php");
$id = pg_escape_string($_GET['id']);
$condition=pg_escape_string($_GET['condition']);
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
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#BCE6FC'); 
			$('#a4').css('background-color', '#BCE6FC');	
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#BCE6FC'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#BCE6FC'); 
			
		});
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#BCE6FC');   
			$('#a2').css('background-color', '#BCE6FC'); 
			$('#a3').css('background-color', '#BCE6FC'); 
			$('#a4').css('background-color', '#ff6600'); 	
		}); 

    });
});
</script>

<?php
if($id != ""){
	echo "<table width=\"850\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" bgcolor=\"#D7F0FD\" align=\"center\" class=\"sort-table\">
	<thead>
	<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#BCE6FC\">
		<th>เลขที่สัญญา</th>
		<th id=\"a4\" class=\"sort-text\" style=\"cursor:pointer;\" >ผู้กู้หลัก</th>
		<th id=\"a1\" class=\"sort-text\" style=\"cursor:pointer;background-color:#ff6600;\">เลขที่ใบกำกับภาษี</th>
		<th id=\"a2\" class=\"sort-text\" style=\"cursor:pointer;\">วันที่จ่าย</th>
		<th id=\"a3\" class=\"sort-number\" style=\"cursor:pointer;\">จำนวนเงินที่จ่าย</th>
	
		<th>พิมพ์ใบกำกับภาษี</th>
	</tr>
	</thead>
	";
	if($condition=="1"){
		//ค้นหาเลขที่สัญญา
		
		$qry_con=pg_query("select \"contractID\" as contract,\"taxinvoiceID\",\"taxpointDate\" from thcap_v_taxinvoice_details 
		WHERE \"contractID\" = '$id' group by \"contractID\",\"taxinvoiceID\",\"taxpointDate\" order by \"taxpointDate\" DESC");
		
		
		$numcon=pg_num_rows($qry_con);
		
		if($numcon>0){ //แสดงว่ามีข้อมูล
			$status=1;
		}else{
			$status=0;
		}
	}else if($condition=="2"){
		//ค้นหาเลขที่ใบกำกับภาษี
		$qry_con=pg_query("select \"contractID\" as contract,\"taxinvoiceID\",\"taxpointDate\" from thcap_v_taxinvoice_details 
		WHERE \"taxinvoiceID\" = '$id' group by \"contractID\",\"taxinvoiceID\",\"taxpointDate\" order by \"taxinvoiceID\"");
		$numrec=pg_num_rows($qry_con);
		if($numrec>0){
			$status=1;
			
			
		}
	}
	
	if($status==1){
		while($result=pg_fetch_array($qry_con)){	
			$contractID=trim($result["contract"]);
			$taxinvoiceID=trim($result["taxinvoiceID"]);
			$taxpointDate=trim($result["taxpointDate"]);
	
			$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = 0");
			list($full_namecus) = pg_fetch_array($qry_cusname);
	
			if($condition=="1"){
				$id = $result['taxinvoiceID'];
			}

			
			$qry_con1=pg_query("select sum(\"debtAmt\") as \"debtAmt1\" from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$id' ");

			$result2=pg_fetch_array($qry_con1);	

			$sum_debtAmt = $result2["debtAmt1"]; // netAmt+vatAmt
			
			
			
	
			
			echo "<tr align=center bgcolor=\"#EAF9FF\">
			<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
			<u>$contractID</u></font></span></td>
			<td>$full_namecus</td>
			<td>$taxinvoiceID</td>
			<td>$taxpointDate</td>
			<td align=right>".number_format($sum_debtAmt,2)."</td>
			
			";
			if($numchkrec>0){
				echo "<td><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$taxinvoiceID&t=3&contractID=$contractID','reason_$taxinvoiceID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
			}else{
				echo "<td><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?rec_id=$taxinvoiceID&t=4','reason_$taxinvoiceID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
			}	
			echo "</tr>";
		}	
		
	}else{
		echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=6><h2>-ไม่พบข้อมูลบกำกับภาษี-</h2></td></tr>";
	}
	echo "</table>";
}else{ //กรณีไม่กรอกคำค้น
	echo "<center><h2>-กรุณากรอกคำค้นหาก่อนทำรายการ-</h2></center>";
}?>
