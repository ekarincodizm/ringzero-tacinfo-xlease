<?php
include("../../config/config.php");
$datepicker = $_GET['datepicker'];
$type_date = $_GET['type_date'];

if(!empty($datepicker)){
?>

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

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

/* ใช้ sort ตามหัวข้อ
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
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF');
			$('#a4').css('background-color', '#79BCFF');   
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#79BCFF');
			$('#a4').css('background-color', '#79BCFF');   
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF');
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#ff6600');
			$('#a4').css('background-color', '#79BCFF');   
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF');
		});
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF');
			$('#a4').css('background-color', '#ff6600');   
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF');
			$('#a4').css('background-color', '#79BCFF');   
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#79BCFF');
		});
    });
});
*/
</script>

<div align="right"><a href="pdf_receipt_report.php?date=<?php echo "$datepicker"; ?>&type_date=<?php echo $type_date; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
<thead>
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <th id="a1"> <!-- class="sort-text" style="cursor:pointer;background-color:#ff6600;" -->วันเวลาที่ออกใบเสร็จ</th>
    <th id="a2"> <!-- class="sort-text" style="cursor:pointer;" -->เลขที่ใบเสร็จ</th>
    <th id="a3"> <!-- class="sort-text" style="cursor:pointer;" -->เลขที่สัญญา</th>
	<th id="a4"> <!-- class="sort-text" style="cursor:pointer;" -->ชื่อ-นามสกุลลูกค้า</th>
	<th id="a5"> <!-- class="sort-text" style="cursor:pointer;" -->จำนวนเงินใบเสร็จ</th>
    <th>ดูรายละเอียด</th>
	<th>พิมพ์</th>
</tr>
</thead>

<?php
if($type_date == 1) // ถ้าเลือกจากวันที่ทำรายการ
{
	$view_date = "where \"doerStamp\" = '$datepicker'";
}
if($type_date == 2) // ถ้าเลือกจากวันที่ออกใบเสร็จ
{
	$view_date = "where \"receiveDate\" = '$datepicker'";
}

$query=pg_query("select * from account.\"V_thcap_receipt_report\" $view_date order by \"doerID\" ");
						
$num_row = pg_num_rows($query);					

$sum_receiveAmt = 0;

$i=0;
$a=0;
while($resvc=pg_fetch_array($query))
{
	$doerID = $resvc['doerID'];
    $receiveDate = $resvc['receiveDate'];
    $receiptID = $resvc['receiptID'];
    $contractID = $resvc['contractID'];
    $cusFullName = $resvc['cusFullName'];
    $receiveAmt = $resvc['receiveAmt'];
	
	//------หาชื่อผู้ทำรายการ
	$query_name=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
	while($resvc_name=pg_fetch_array($query_name))
	{
		$fullname = $resvc_name['fullname'];
	}
	//----- จบการหาชื่อผู้ทำรายการ
	
	$sum_receiveAmt += $receiveAmt;
	
	//------- เช็ีคว่าใช่คนเดิมหรือไม่
		$checkIDone = $doerID;
		if($a==0)
		{
			$checkIDtwo = $checkIDone;
			echo "<tr BGCOLOR=\"#AFFFFF\"><td colspan=7><b>ผู้ทำรายการ : $fullname ($checkIDone)</b></td></tr>";
		}
		else
		{
			if($checkIDone != $checkIDtwo)
			{
				echo "<tr><td colspan=\"4\" align=\"right\" height=\"25\" ><b>รวมเงิน</b></td>";
				echo "<td colspan=\"1\" align=\"right\"><b>".number_format($sumone,2)."</b></td>";
				echo "<td colspan=\"2\"></td></tr>";
				echo "<tr BGCOLOR=\"#AFFFFF\"><td colspan=7><b>ผู้ทำรายการ : $fullname ($checkIDone)</b></td></tr>";
				$checkIDtwo = $checkIDone;
				$sumone = 0;
			}
		}
	//------- จบการเช็คคนเดิม

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo $receiveDate; ?></td>
        <td align="center"><?php echo $receiptID; ?></td>
        <td align="center"><?php echo $contractID; ?></td>
        <td align="left"><?php echo $cusFullName; ?></td>
        <td align="right"><?php echo number_format($receiveAmt,2); ?></td>
        <td align="center"><img src="images/detail.gif" width=19 height=19 style="cursor:pointer;"></td>
		<td align="center"><img src="images/iconPrint.png" width=19 height=19 style="cursor:pointer;"></td>
    </tr>
    
<?php
		$sumone += $receiveAmt;
		$a += 1;
		
		if($a == $num_row)
		{
			echo "<tr><td colspan=\"4\" align=\"right\" height=\"25\" ><b>รวมเงิน</b></td>";
			echo "<td colspan=\"1\" align=\"right\"><b>".number_format($sumone,2)."</b></td>";
			echo "<td colspan=\"2\"></td></tr>";
		}
}


if($num_row==0){
?>
<tr>
    <td colspan="7" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
echo "<tr BGCOLOR=\"#AFFFFF\"><td colspan=\"4\" align=\"right\" height=\"25\"><b>รวมเงินทั้งหมด</b></td>";
echo "<td colspan=\"1\" align=\"right\"><b>".number_format($sum_receiveAmt,2)."</b></td>";
echo "<td colspan=\"2\"></td></tr>";
?>

</table>

<?php
}
?>