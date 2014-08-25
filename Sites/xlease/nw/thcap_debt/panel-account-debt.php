<?php
include("../../config/config.php");
$datepicker = $_GET['datepicker'];
$type_date = $_GET['type_date'];
$type_view = $_GET['type_view'];

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
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF');
			$('#a4').css('background-color', '#79BCFF');   
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#ff6600');
		}); 
    });
});
</script>

<div align="right"><a href="thcap_account_debt_pdf.php?date=<?php echo "$datepicker"; ?>&pdf=<?php echo $type_date; ?>&type_view=<?php echo $type_view; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
<thead>
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <th id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">เลขที่ใบแจ้งหนี้</th>
    <th id="a2" class="sort-text" style="cursor:pointer;">เลขที่สัญญา</th>
    <th id="a3" class="sort-text" style="cursor:pointer;">วันที่ออกใบแจ้งหนี้</th>
	<th id="a4" class="sort-text" style="cursor:pointer;">รายการค่าใช้จ่าย</th>
	<th id="a5" class="sort-text" style="cursor:pointer;">Ref</th>
    <th id="a6" class="sort-text" style="cursor:pointer;">จำนวนเงิน</th>
    <th>รายละเอียด</th>
</tr>
</thead>

<?php
if($type_view == 1) // แสดงเฉพาะอนุมัติ
{
	$view = "and \"thcap_invoice_action\".\"appvXStatus\" = '1' and \"thcap_invoice_action\".\"appvYStatus\" = '1' ";
}
elseif($type_view == 2) // แสดงเฉพาะไม่อนุมัติ
{
	$view = "and (\"thcap_invoice_action\".\"appvXStatus\" = '0' or \"thcap_invoice_action\".\"appvYStatus\" = '0')";
}
elseif($type_view == 3) // แสดงเฉพาะรออนุมัติ
{
	$view = "and  ((\"appvXStatus\" is NULL and \"appvYStatus\" ='1') or (\"appvYStatus\" is NULL and \"appvXStatus\" ='1') or (\"appvXStatus\" is NULL and \"appvYStatus\" is null )) ";
}
if($type_view == 4) // แสดงทั้งหมด
{
	$view = "";
}


if($type_date == 1) // ถ้าเลือกจากวันที่ทำรายการ
{
$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice\".\"invoiceTypePayRef\" , \"thcap_typePay\".\"tpDesc\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
						and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
						and \"thcap_invoice_action\".\"doerStamp\"='$datepicker'
						and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
						order by \"thcap_invoice\".\"invoiceID\" ");
$num_row = pg_num_rows($query);

//---------รวมเงิน
$query_sum=pg_query("select sum(\"thcap_invoice\".\"invoiceAmt\") as \"test\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
						and \"thcap_invoice_action\".\"doerStamp\"='$datepicker'
						and \"thcap_invoice_action\".\"invActionType\" = 'I' $view ");
while($test_deaw=pg_fetch_array($query_sum))
{
	$test = $test_deaw['test'];
}
//---------จบการรวมเงิน
}

if($type_date == 2) // ถ้าเลือกจากวันที่อนุมัติรายการ
{
$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice\".\"invoiceTypePayRef\" , \"thcap_typePay\".\"tpDesc\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
					and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
					and \"thcap_invoice_action\".\"appvYStamp\" <= \"thcap_invoice_action\".\"appvXStamp\"
					and \"thcap_invoice_action\".\"appvXStamp\"='$datepicker'
					and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
					order by \"thcap_invoice\".\"invoiceID\" ");
$num_row = pg_num_rows($query);

if($num_row == 0) // ถ้าไม่เจอลองเช็คอีกแบบ
{
$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice\".\"invoiceTypePayRef\" , \"thcap_typePay\".\"tpDesc\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
					and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
					and \"thcap_invoice_action\".\"appvYStamp\" >= \"thcap_invoice_action\".\"appvXStamp\"
					and \"thcap_invoice_action\".\"appvYStamp\"='$datepicker'
					and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
					order by \"thcap_invoice\".\"invoiceID\" ");
$num_row = pg_num_rows($query);

//---------รวมเงิน
$query_sum=pg_query("select sum(\"thcap_invoice\".\"invoiceAmt\") as \"test\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
						and \"thcap_invoice_action\".\"appvYStamp\"='$datepicker'
						and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
						and \"thcap_invoice_action\".\"appvYStamp\" >= \"thcap_invoice_action\".\"appvXStamp\" ");
while($test_deaw=pg_fetch_array($query_sum))
{
	$test = $test_deaw['test'];
}
//---------จบการรวมเงิน
}

if($num_row > 0) // ถ้าเจอใช้อันนี้เลย
{
//---------รวมเงิน
$query_sum=pg_query("select sum(\"thcap_invoice\".\"invoiceAmt\") as \"test\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
						and \"thcap_invoice_action\".\"appvXStamp\"='$datepicker'
						and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
						and \"thcap_invoice_action\".\"appvYStamp\" <= \"thcap_invoice_action\".\"appvXStamp\" ");
while($test_deaw=pg_fetch_array($query_sum))
{
	$test = $test_deaw['test'];
}
//---------จบการรวมเงิน
}

}						
						
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $invoiceID = $resvc['invoiceID'];
    $contractID = $resvc['contractID'];
    $invoiceDate = $resvc['invoiceDate'];
    $invoiceAmt = trim($resvc['invoiceAmt']);
    $invoiceVATRate = $resvc['invoiceVATRate'];
    $invoiceAmtVAT = $resvc['invoiceAmtVAT'];
    $invoiceWHTRate = $resvc['invoiceWHTRate'];
	$tpDesc = $resvc['tpDesc'];
	$invoiceTypePayRef = $resvc['invoiceTypePayRef'];
	
	if($invoiceDate ==""){
		$invoiceDate="-";
	}else{
		$invoiceDate=substr($invoiceDate,0,10);
	}

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo $invoiceID; ?></td>
        <td align="center"><?php echo $contractID; ?></td>
        <td align="center"><?php echo $invoiceDate; ?></td>
        <td align="center"><?php echo $tpDesc; ?></td>
        <td align="center"><?php echo $invoiceTypePayRef; ?></td>
        <td align="right"><?php echo $invoiceAmt; ?></td>
		<td align="center"><?php echo "<a href=\"#\" onclick=\"javascript:popU('frm_detail.php?account_debt=$invoiceID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=520')\"><img src=\"images/detail.gif\" width=19 height=19 style=\"cursor:pointer;\"></a>"; ?></td>
        <!-- <td align="right"><?php echo number_format($AmtPay,2); ?></td> -->
    </tr>
    
<?php
}


if($num_row==0){
?>
<tr>
    <td colspan="7" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>
</table>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<?php
echo "<td align=right class=\"sum\"><b>รวมเงิน &nbsp; &nbsp; $test</b></td></tr>";
?>

</table>

<?php
}
?>