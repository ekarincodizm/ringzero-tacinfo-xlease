<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");
$nowdate = Date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
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
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#ff6600'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
		}); 
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#ff6600'); 
			$('#a7').css('background-color', '#79BCFF'); 
		}); 
		$('#a7').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#ff6600'); 
		}); 
		
    });
});
</script>

    </head>
<body>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
<div class="wrapper">

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0" class="sort-table">
	<thead>
	<tr bgcolor="#FFFFFF">
        <td  align="left" colspan="7" tyle="font-weight:bold;">ออก NT ประจำวันที่ <?php echo $nowdate; ?></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <th align="center" id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">เลขที่สัญญา</th>
        <th align="center" id="a2" class="sort-text" style="cursor:pointer;">ชื่อ</th>
        <th align="center" id="a3" class="sort-text" style="cursor:pointer;">ทะเบียน</th>
        <th align="center" id="a4" class="sort-text" style="cursor:pointer;">สีรถ</th>
        <th align="center" id="a5" class="sort-number" style="cursor:pointer;">จำนวนงวดที่ค้าง</th>
		<th align="center" id="a6" class="sort-number" style="cursor:pointer;">จำนวนวันที่ค้างถึงปัจจุบัน</th>
        <th align="center" id="a7" class="sort-text" style="cursor:pointer;">สถานะ NT</th>
    </tr>
	</thead>
<?php
$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\",MAX(daydelay) as daydelay,MIN(\"DueDate\") as datedue from \"VRemainPayment\" GROUP BY \"IDNO\" ");
while($res_fr=pg_fetch_array($qry_fr)){
    $IDNO = $res_fr["IDNO"];
    $SumDueNo = $res_fr["SumDueNo"];
	$daydelay = $res_fr["daydelay"];
	$DueDate = $res_fr["datedue"]; //วันที่ของงวดที่ค้างชำระ
   
	if($SumDueNo == 1 || $SumDueNo == 2){
		//ดึงจำนวนงวดสุดท้าย และวันที่ครบกำหนดค่างวดสุดท้ายขึ้นมา เพื่อนำมาเปรียบเทียบกับงวดที่ค้างว่าใช่งวดสุดท้าย หรือ รองสุดท้ายหรือไม่
		$qry_fr1 = pg_query("select MAX(\"DueNo\") as \"DueNo1\",MAX(\"DueDate\") as \"DueDate1\" from \"VCusPayment\" where \"IDNO\" = '$IDNO' GROUP BY \"IDNO\"");
		if($res_vc1 = pg_fetch_array($qry_fr1)){
			$DueNo1 = $res_vc1["DueNo1"]; 
			$DueDate1 = $res_vc1["DueDate1"]; //วันที่ที่ต้องชำระงวดสุดท้าย
			$DueNo2 = $DueNo1 - 1;
		}
		
		if($SumDueNo == 1){  //กรณีค้างแค่ 1 งวด (ดูว่าใช่งวดสุดท้ายหรือไม่)
			if($DueDate == $DueDate1){ //เปรียบเทียบงวดที่ค้างล่าสุด ว่าใช่งวดสุดท้ายหรือไม่ ถ้าใช่ให้ + เพิ่ม 3 เดือน(เพื่อดูว่าครบกำหนดออก NT หรือยัง)	
				$DueDateEnd = date("Y-m-d", strtotime("+3 month", strtotime($DueDate1)));
				 //กรณีวันที่ปัจจุบันมากกว่าวันที่ต้องชำระ ให้ออก NT
				if($nowdate > $DueDateEnd) $numDue = 1;else $numDue = 0;
			}else{$numDue = 0;}
						
		}elseif($SumDueNo == 2){
			$qry_fr2 = pg_query("select \"DueDate\" as \"DueDateBeforLast\" from \"VCusPayment\" where \"IDNO\" = '$IDNO' and \"DueNo\" = '$DueNo2'");
			// วันที่ของงวดที่ต้องชำระงวดรองสุดท้าย
			if($res_vc2 = pg_fetch_array($qry_fr2)){$DueDateBeforLast = $res_vc2["DueDateBeforLast"]; }
			if($DueDate == $DueDateBeforLast){ //เปรียบเทียบงวดที่ค้างล่าสุด ว่าใช่งวดรองสุดท้ายหรือไม่ ถ้าใช่ให้ + เพิ่ม 3 เดือน(เพื่อดูว่าครบกำหนดออก NT หรือยัง)
				$DueDateBeforLast1 = date("Y-m-d", strtotime("+3 month", strtotime($DueDateBeforLast)));
				//กรณีวันที่ปัจจุบันมากกว่าวันที่ต้องชำระ ให้ออก NT
				if($nowdate > $DueDateBeforLast1) $numDue = 2;else $numDue = 0;
			}else{$numDue = 0;}
			
		}else{ $numDue = 0;}
		
	}else{ $numDue = 0;}
	
	if($SumDueNo > 2 || $numDue != 0){ 
        $nub+=1;
	   //$qry_vc=pg_query("select * from \"VContacttest\" WHERE \"IDNO\"='$IDNO' ");
		
		$qry_vc=pg_query("SELECT a.\"C_COLOR\",a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" FROM \"Carregis_temp\" a
		LEFT JOIN \"Fp\" b on a.\"IDNO\"=b.\"IDNO\" 
		LEFT JOIN \"VSearchCus\" c on b.\"CusID\"=c.\"CusID\" 
		where a.\"IDNO\"='$IDNO' order by auto_id DESC limit 1");
		$num_cartemp=pg_num_rows($qry_vc);
		if($num_cartemp==0){
			//กรณีเป็น Gas จะไม่มี C_COLOR
			$qry_vc=pg_query("SELECT b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
			LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
			LEFT JOIN \"VSearchCus\" c ON a.\"CusID\" = c.\"CusID\"
			WHERE \"IDNO\"='$IDNO' ");
		}
		
		if($res_vc=pg_fetch_array($qry_vc)){
			$full_name = $res_vc["full_name"];
			$C_COLOR = $res_vc["C_COLOR"];
			$asset_type = $res_vc["asset_type"];
			$C_REGIS = $res_vc["C_REGIS"];
			$car_regis = $res_vc["car_regis"];
			if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
		}

		$P_LAWERFEE = "";
		$qry_vc3=pg_query("select \"P_LAWERFEE\" from \"Fp\" WHERE \"IDNO\"='$IDNO' ORDER BY \"P_LAWERFEE\"");
		if($res_vc3=pg_fetch_array($qry_vc3)){
			$P_LAWERFEE = $res_vc3["P_LAWERFEE"];
		}

        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center">
			<span onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><u><?php echo $IDNO; ?></u></span>        
        </td>
        <td align="left"><?php echo $full_name; ?></td>
        <td align="left"><?php echo $show_regis; ?></td>
        <td align="left"><?php echo $C_COLOR; ?></td>
        <td align="center"><?php echo $SumDueNo; ?></td>
		<td align="center"><?php echo $daydelay; ?></td>
        <td align="center">
		<?php
		if($P_LAWERFEE == 't'){
			//ตรวจสอบสถานะเพิ่มเติมในตาราง nw_statusNT
			//ตรวจสอบว่าส่งจดหมายครบหรือยัง
			$query_sts4 = pg_query("select b.\"statusNT\" from \"NTHead\" a
				left join \"nw_statusNT\" b on a.\"NTID\" = b.\"NTID\"
				where a.cancel='FALSE' and (a.\"remark\" is null or a.\"remark\" not like '%#INS%') and \"statusNT\"='4' and a.\"IDNO\" = '$IDNO'");
			$num_sts4=pg_num_rows($query_sts4);
			if($num_sts4 > 0){ //กรณีส่งจดหมายยังไม่ครบ
			?>
				<span onclick="javascript:popU('nw_noticeSend.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_reprint_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')" style="cursor: pointer;" title="Re Print"><u>ส่งจดหมายยังไม่ครบ</u></span>
			<?php
			}else{	//กรณีส่้งจดหมายครบแล้ว ให้ดึงข้อมูลขึ้นมาทั้งหมด		
				//$query_sts = pg_query("select b.\"statusNT\" from \"NTHead\" a
				//					left join \"nw_statusNT\" b on a.\"NTID\" = b.\"NTID\"
				//					where a.cancel='FALSE' and (a.\"remark\" is null or a.\"remark\" not like '%#INS%') and \"CusState\"='0' and a.\"IDNO\" = '$IDNO'"); 
				
				$query_sts = pg_query("select a.\"statusNT\" from \"nw_statusNT\" a
									left join \"NTHead\" b on a.\"NTID\" = b.\"NTID\"
									where b.cancel='FALSE' and (b.\"remark\" is null or b.\"remark\" not like '%#INS%') and \"CusState\"='0' and b.\"IDNO\" = '$IDNO'"); 
				$numrows_sts = pg_num_rows($query_sts);
		
				if($numrows_sts == 0){
					//$query_pay=pg_query("select * from \"PostLog\" a left join \"FCash\" b on a.\"PostID\"=b.\"PostID\" where a.\"AcceptPost\"='FALSE' and b.\"TypePay\"='1' and b.\"cancel\"='FALSE' and b.\"IDNO\" = '$IDNO'");
					//$num_pay=pg_num_rows($query_pay);
					//if($num_pay > 0){
					//	echo "<font color=blue>รออนุัมัติจ่ายเงิน</font>";
					//}else{
					?>
						<span onclick="javascript:popU('notice_reprint1.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_reprint_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')" style="cursor: pointer;" title="Re Print"><u>ออกแล้ว</u></span>
					<?php
					//}
				}else{ //กรณีมีข้อมูลใน nw_statusNT ให้ดึง statusNT ออกมา
					if($res_sts=pg_fetch_array($query_sts)){
						$statusNT = $res_sts["statusNT"];
					}
				
					//เช็คสถานะตามเงื่อนไขที่ระบุ
					if($statusNT == 0){
						echo "<font color=#0000CC>รออนุมัติ</font>";
					}elseif($statusNT == 1){
					?>
						<span onclick="javascript:popU('notice_reprint1.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_reprint_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')" style="cursor: pointer;" title="Re Print"><font color="red"><u>รอส่งจดหมาย</u></font></span>
					<?php
					}elseif($statusNT == 2){
					?>
					<span onclick="javascript:popU('notice_add.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')" style="cursor: pointer;" title="แก้ไขข้อมูล"><font color="red"><u>ไม่อนุมัติ</u></font></span>
					<?php
					}elseif($statusNT == 3){
						//$query_pay=pg_query("select * from \"PostLog\" a left join \"FCash\" b on a.\"PostID\"=b.\"PostID\" where a.\"AcceptPost\"='FALSE' and b.\"TypePay\"='1' and b.\"cancel\"='FALSE' and b.\"IDNO\" = '$IDNO'");
						//$num_pay=pg_num_rows($query_pay);
						//if($num_pay > 0){
						//	echo "<font color=blue>รออนุัมัติจ่ายเงิน</font>";
						//}else{
					?>	
						<span onclick="javascript:popU('notice_reprint1.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_reprint_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')" style="cursor: pointer;" title="Re Print"><u>ส่งจดหมายครบแล้ว</u></span>
					<?php
						//}
					}elseif($statusNT == 5){
						echo "<font color=blue>รออนุมัติยกเลิก</font>";
					}
				}
			}
		?>
		<?php
		}else{
			?>
			<span onclick="javascript:popU('notice_add.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')" style="cursor: pointer;" title="เพิ่มข้อมูล"><u>รออยู่</u></span>
			<?php
		}
		?>      
		</td>
    </tr>
<?php
	} //end if
} // end while
?>
</table>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<?php 
if($nub > 0){
?>
    <tr>
        <td align="left" colspan="3">ทั้งหมด <?php echo $nub; ?> รายการ</td>
        <td colspan="4" align="right"><a href="notice_pdf.php" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
    </tr>
<?php
}
?>
<?php 
if($nub == 0){   
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