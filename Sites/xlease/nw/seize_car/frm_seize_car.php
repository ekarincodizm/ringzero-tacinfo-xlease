<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
$nowdate = Date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
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
		/*$('th', table).hover( function(){   
			$(this).css('background-color', '#ff6600');   
		},   
		function(){   
			$(this).css('background-color', '#79BCFF');   
		}); 
		*/
		$('#a1').click( function(){   
			$('#a1').css('background-color', '#ff6600');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
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
			$('#a9').css('background-color', '#79BCFF');
		}); 
		$('#a9').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
			$('#a9').css('background-color', '#ff6600');
		}); 
		
    });
});
</script>

    </head>
<body>
<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
<div class="wrapper">

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0" class="sort-table">
	<thead>
	<tr bgcolor="#FFFFFF">
        <td  align="left" colspan="9" tyle="font-weight:bold;">สร้างรายการยึดรถประจำวันที่ <?php echo $nowdate; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
		<th align="center" id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">เลข NT</th>
        <th align="center" id="a2" class="sort-text" style="cursor:pointer;">เลขที่สัญญา</th>
        <th align="center" id="a3" class="sort-text" style="cursor:pointer;">ชื่อ</th>
        <th align="center" id="a4" class="sort-text" style="cursor:pointer;">ทะเบียน</th>
        <th align="center" id="a5" class="sort-text" style="cursor:pointer;">สีรถ</th>
        <th align="center" id="a6" class="sort-number" style="cursor:pointer;">จำนวนงวดที่ค้าง</th>
		<th align="center" id="a7" class="sort-number" style="cursor:pointer;">จำนวนวันที่ค้างถึงปัจจุบัน</th>
		<th align="center" id="a8" class="sort-number" style="cursor:pointer;">ยอดหนี้คงเหลือรวมดอกเบี้ย</th>
        <th align="center" id="a9" class="sort-text" style="cursor:pointer;">สถานะยึดรถ</th>
    </tr>
	</thead>
<?php
$qry_fr=pg_query("select a.\"NTID\",a.\"IDNO\",a.\"do_date\",a.\"CusState\",a.\"cancel\" from \"NTHead\" a 
left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
where a.\"cancel\" = 'FALSE' and a.\"CusState\" = '0' and b.\"repo\" = 'FALSE' and b.\"P_ACCLOSE\" = 'FALSE' and a.\"remark\" is null"); 
$numrows=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){
    $NTID = $res_fr["NTID"];
    $IDNO = $res_fr["IDNO"];
	$do_date = $res_fr["do_date"];
	$CusState = $res_fr["CusState"];
	$cancel = $res_fr["cancel"]; //วันที่ของงวดที่ค้างชำระ
   
	$do_date_nub = date("Y-m-d", strtotime("+30 day", strtotime($do_date))); //นำวันที่ออก NT บวกเพิ่มอีก 30 วัน เพื่อจะได้วันสุดท้ายที่ครบกำหนดยึดรถ

	//ให้แสดงทุกรายการ ต่อให้ยังไม่ถึงกำหนด
	$qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
	if($res_vc=pg_fetch_array($qry_vc)){
		$full_name = $res_vc["full_name"];
		$C_COLOR = $res_vc["C_COLOR"];
		$asset_type = $res_vc["asset_type"];
		$C_REGIS = $res_vc["C_REGIS"];
		$car_regis = $res_vc["car_regis"];
		if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
	}
	$qry_daydelay = pg_query("select COUNT(\"DueNo\") as \"SumDueNo\",MAX(\"daydelay\") as \"daydelay\" from \"VRemainPayment\" where \"IDNO\" = '$IDNO' group by \"IDNO\"");
	if($res_delay=pg_fetch_array($qry_daydelay)){
		$SumDueNo = $res_delay["SumDueNo"];
		$daydelay = $res_delay["daydelay"];
	}
	
	$i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\">";
	}else{
		echo "<tr class=\"even\">";
	}
	?>
	<td align="center">
		<span onclick="javascript:popU('../../post/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;"><u><?php echo $NTID; ?></u></span>        
	</td>
	<td align="center">
		<span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><u><?php echo $IDNO; ?></u></span>        
	</td>
	<td align="left"><?php echo $full_name; ?></td>
	<td align="left"><?php echo $show_regis; ?></td>
	<td align="left"><?php echo $C_COLOR; ?></td>
	<td align="center"><?php echo $SumDueNo; ?></td>
	<td align="center"><?php echo $daydelay; ?></td>
	<?php
		$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
		$payall1=0;
		while($resbf=pg_fetch_array($qry_before)){
			$CalAmtDelay=$resbf["CalAmtDelay"];
			$payall1=$payall1+$CalAmtDelay;	
		}//จบ หารายการที่ชำระแล้ว
	
		//หายอดค้างกรณียังไม่ชำระค่างวด
		$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$IDNO'");
		$res_FpFa1=pg_fetch_array($qry_FpFa1);
		$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
	
		$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
		$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
		$stdate=$res_VCusPayment["DueDate"];
		
		$qry_amt=@pg_query("select * ,'$nowdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO')  AND (\"DueDate\" BETWEEN '$stdate' AND '$nowdate') "); //รายการที่คำนวณ
		$payall2=0;
		while($res_amt=@pg_fetch_array($qry_amt)){
			$s_amt=pg_query("select \"CalAmtDelay\"('$nowdate','$res_amt[DueDate]',$s_payment_all)"); 
			$res_s=pg_fetch_result($s_amt,0);
			
			$payall2=$payall2+($s_payment_all+$res_s);
		}
		$payall = $payall1+$payall2;
		//จบการหายอดค้างกรณียังไม่ชำระค่างวด
		
		//หาดอกเบี้ยที่ชำระแล้ว
		$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$IDNO' AND \"Cancel\"='FALSE' ");
		if($re_mny=pg_fetch_array($qry_moneys)){
			$otherpay_amt = $re_mny["sum_money_otherpay"];
		}
		
		$payall = $payall - $otherpay_amt;
	?>
	<td align="center"><?php echo number_format($payall,2); ?></td>
	<td align="center" width="100">
		<?php
		//ให้แสดงเฉพาะรายการที่ครบกำหนดส่ง NT 30 วัน
		if($nowdate > $do_date_nub){ //ให้แสดงเฉพาะรายการที่ครบกำหนดส่ง NT 30 วัน
			$query_seize=pg_query("select * from \"nw_seize_car\" where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID' order by \"seizeID\" DESC limit 1");
			$num_seize = pg_num_rows($query_seize);
			if($num_seize == 0){
				echo "<span onclick=\"javascript:popU('frm_result_seize.php?idno=$IDNO&ntid=$NTID&method=add','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')\" style=\"cursor: pointer;\" ><u>รอส่งเรื่อง</u></span>";
			}else{
				
				if($result_seize = pg_fetch_array($query_seize)){
					$status_approve = $result_seize["status_approve"];
				}
				if($status_approve == '1'){ //รออนุมัติหลังจากส่งเรื่องแล้ว
					echo "<span onclick=\"javascript:popU('frm_result_seize.php?idno=$IDNO&ntid=$NTID&method=edit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')\" style=\"cursor: pointer;\" ><font color=blue><u>รออนุมัติ</u></font></span>";
				}elseif($status_approve == '2'){ //รอแจ้งงานหลังจากอนุมัติแล้ว
					echo "<span onclick=\"javascript:popU('frm_send_seize.php?idno=$IDNO&ntid=$NTID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=600')\" style=\"cursor: pointer;\" ><font color=red><u>รอแจ้งงาน</u></font></span>";
				}elseif($status_approve == '3'){ //แจ้งงานเรียบร้อยแล้วอยู่ระหว่างยึด
					echo "<a href=\"pdf_send_seize.php?IDNO=$IDNO&NTID=$NTID\" target=\"_blank\"><b>อยู่ระหว่างยึด</b></a>";
					echo "<input name=\"btn\" type=\"button\" value=\"ขอยกเลิก\" onclick=\"javascript:popU('frm_cancel.php?idno=$IDNO&ntid=$NTID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\"/>";					
				}elseif($status_approve == '5'){ //ไม่อนุมัติสามารถส่งเรื่องใหม่อีกครั้ง
					echo "<span onclick=\"javascript:popU('frm_result_seize.php?idno=$IDNO&ntid=$NTID&method=add','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')\" style=\"cursor: pointer;\" ><u>รอส่งเรื่อง</u></span>";
				}elseif($status_approve == '6'){ //อยู่ระหว่างอนุมัติยกเลิกงานระหว่างยึด
					echo "<span>รออนุมัติยกเลิกงานระหว่างยึด</span>";
				}
			}
		}else{
			echo "<font color=red>NT ไม่ครบ 30 วัน</font>";		
		}
		?>
	</td>
	</tr>
	<?php

} //end while	

?>
</table>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<?php 
if($numrows > 0){
?>
    <tr>
        <td align="left" colspan="3">ทั้งหมด <?php echo $nub; ?> รายการ</td>
        <td colspan="4" align="right"></td>
    </tr>
<?php
}
?>
<?php 
if($numrows == 0){   
?>
    <tr><td colspan="7" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<div align="center"><br><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

        </td>
    </tr>
</table>

</body>
</html>