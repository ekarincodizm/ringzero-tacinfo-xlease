<?php
session_start();
include("../../config/config.php");
$userkey=$_SESSION["av_iduser"];
$datepicker = $_GET['datepicker'];

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
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
<script language=javascript>
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
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#ff6600'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#ff6600'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a7').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#ff6600'); 
			$('#a8').css('background-color', '#79BCFF');
		}); 
		$('#a8').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#ff6600');
		}); 	
    });
});
</script>
<div align="right"><a href="cash_day_user_pdf.php?date=<?php echo $datepicker;?>&id=<?php echo $userkey;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
<?php
$query1=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$userkey'");
if($resvc1=pg_fetch_array($query1)){
 $fullname = $resvc1['fullname'];
}
?>
<div align="left"><b>ผู้รับเงิน : <?php echo "$fullname ($userkey)";?></b></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
<thead>
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <th id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">เลขที่ใบเสร็จ</th>
    <th id="a2" class="sort-text" style="cursor:pointer;">IDNO</th>
    <th id="a3" class="sort-text" style="cursor:pointer;">ชื่อลูกค้า</th>
    <th id="a4" class="sort-text" style="cursor:pointer;">ทะเบียน</th>
    <th id="a5" class="sort-text" style="cursor:pointer;">TypePay</th>
    <th id="a6" class="sort-text" style="cursor:pointer;">TName</th>
	<th id="a7" class="sort-text" style="cursor:pointer;">เวลารับชำระ <br>(ชั่วโมง:นาที)</th>
    <th id="a8" class="sort-number" style="cursor:pointer;">จำนวนเงิน</th>
</tr>
</thead>
<?php
$old_UserIDAccept = 0;
$query=pg_query("select * from \"VUserReceiptCash\" WHERE \"PostDate\"='$datepicker' and \"UserIDAccept\" ='$userkey' ORDER BY \"UserIDAccept\",\"refreceipt\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $UserIDAccept = "";
    $UserIDAccept = $resvc['UserIDAccept'];
    $refreceipt = $resvc['refreceipt'];
    $IDNO = $resvc['IDNO'];
    $A_NAME = trim($resvc['A_NAME']);
    $A_SIRNAME = trim($resvc['A_SIRNAME']);
    $TypePay = $resvc['TypePay'];
    $TName = $resvc['TName'];
    $AmtPay = $resvc['AmtPay'];
	$PostTime = $resvc['PostTime'];
	if($PostTime ==""){
		$PostTime="-";
	}else{
		$PostTime=substr($PostTime,0,5);
	}
    
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_VContact=pg_fetch_array($query_VContact)){
        $asset_type = $res_VContact['asset_type'];
        $C_REGIS = $res_VContact['C_REGIS'];
        $car_regis = $res_VContact['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }
    $sum_amt+=$AmtPay;
    $sum_amt_all+=$AmtPay;
    
    $typecode = "";
    $typecode = $refreceipt[2];
    if($typecode == "N"){
        $n_sum += $AmtPay;
    }elseif($typecode == "R"){
        $r_sum += $AmtPay;
    }elseif($typecode == "K"){
        $k_sum += $AmtPay;
    }

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo "$refreceipt"; ?></td>
        <td align="center"><?php echo $IDNO; ?></td>
        <td><?php echo $A_NAME." ".$A_SIRNAME; ?></td>
        <td align="left"><?php echo $regis; ?></td>
        <td align="center"><?php echo $TypePay; ?></td>
        <td><?php echo $TName; ?></td>
		<td align="center"><?php echo $PostTime; ?></td>
        <td align="right"><?php echo number_format($AmtPay,2); ?></td>
    </tr>
    
<?php
    $old_UserIDAccept = $UserIDAccept;
}
echo "</table>";
echo "<table width=\"100%\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" bgcolor=\"#F0F0F0\">";
echo "<tr><td class=\"sum\" align=\"center\"><a href=\"cash_day_user_pdf.php?date=$datepicker&id=$old_UserIDAccept\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
<td colspan=3 class=\"sum\"><b>รวม N: ".number_format($n_sum,2)." | รวม R: ".number_format($r_sum,2)." | รวม K: ".number_format($k_sum,2)."</b></td>
<td colspan=3 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td></tr>";

echo "<tr>
<td colspan=7 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td>
<td align=right class=\"sumall\"><b>".number_format($sum_amt_all,2)."</b></td></tr>";

if($num_row==0){
?>
<tr>
    <td colspan="7" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>

</table>

<?php
}
?>