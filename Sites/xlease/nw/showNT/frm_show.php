<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

$condition=$_POST["condition"];
$s_idno=$_POST["s_idno"];
$startDate=$_POST["startDate"];
$endDate=$_POST["endDate"];

if($s_idno!=""){
	$txtshow="เลขที่สัญญา $s_idno";
}else{
	$txtshow="ตั้งแต่วันที่ $startDate ถึง $endDate";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายการ NT ทั้งหมด</title>
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
</head>
<body>
<?php
if($startDate==""){
	$startDate=$currentDate;
}
if($endDate==""){
	$endDate=$currentDate;
}
?>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>	
		<div class="header"><h1>รายการ NT ทั้งหมด</h1><h2><?php echo $txtshow;?></h2></div>
		<div align="right"><br><input type="button" value="กลับ" onclick="window.location='frm_Index.php'"></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0" class="sort-table">
				<thead>
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<th id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">เลขที่สัญญา</th>
					<th id="a2" class="sort-text" style="cursor:pointer;">IDNO</th>
					<th id="a3" class="sort-text" style="cursor:pointer;">ชื่อ</th>
					<th id="a4" class="sort-text" style="cursor:pointer;">ทะเบียน</th>
					<th id="a5" class="sort-text" style="cursor:pointer;">สีรถ</th>
					<th id="a6" class="sort-text" style="cursor:pointer;">สถานะใช้งาน</th>
				</tr>
				</thead>

				<?php
				if($s_idno!=""){
					$query = pg_query("select a.\"NTID\",a.\"IDNO\",e.\"full_name\",e.\"C_REGIS\",e.\"C_COLOR\",f.\"car_regis\",a.\"cancel\",g.\"CusState\" from \"NTHead\" a
					left join \"ContactCus\" b on a.\"IDNO\"=b.\"IDNO\" and a.\"CusState\"=b.\"CusState\"
					left join \"Fp\" d on a.\"IDNO\"=d.\"IDNO\"
					left join \"VCarregistemp\" e on d.\"IDNO\"=e.\"IDNO\"
					left join \"FGas\" f on d.asset_id=f.\"GasID\" 
					left join \"ContactCus\" g on d.\"IDNO\"=g.\"IDNO\" and b.\"CusID\"=g.\"CusID\"
					where (a.\"remark\" is null or a.\"remark\" not like '%#INS%') and a.\"IDNO\"='$s_idno' order by a.\"NTID\"");
				}else{
					$query = pg_query("select a.\"NTID\",a.\"IDNO\",e.\"full_name\",e.\"C_REGIS\",e.\"C_COLOR\",f.\"car_regis\",a.\"cancel\",g.\"CusState\" from \"NTHead\" a
					left join \"ContactCus\" b on a.\"IDNO\"=b.\"IDNO\" and a.\"CusState\"=b.\"CusState\"
					left join \"Fp\" d on a.\"IDNO\"=d.\"IDNO\"
					left join \"VCarregistemp\" e on d.\"IDNO\"=e.\"IDNO\"
					left join \"FGas\" f on d.asset_id=f.\"GasID\" 
					left join \"ContactCus\" g on d.\"IDNO\"=g.\"IDNO\" and b.\"CusID\"=g.\"CusID\"
					where (a.\"remark\" is null or a.\"remark\" not like '%#INS%') and (a.\"do_date\" between '$startDate' and '$endDate') order by a.\"NTID\"");
				}
				$numrows=pg_num_rows($query);
				$i=0;
				while($res_fr=pg_fetch_array($query)){
					$NTID = $res_fr["NTID"];
					$IDNO = $res_fr["IDNO"];
					$fullname = $res_fr["full_name"];
					if($res_fr["C_REGIS"]==""){
						$c_regis=$res_fr["car_regis"];
					}else{
						$c_regis=$res_fr["C_REGIS"];
					}	
					$C_COLOR = $res_fr["C_COLOR"]; if($C_COLOR=="") $C_COLOR="";
					$cancel = $res_fr["cancel"];
					$CusState = $res_fr["CusState"];
					if($CusState==0){
						$txtcus="<font color=#000099>(ผู้เช่าซื้อ)</font>";
					}else{
						$txtcus="<font color=#000099>(ผู้ค้ำ $CusState)</font>";
					}
					
					//ตรวจสอบว่าค่า NTID พบในตารางใหม่หรือไม่ ถ้าพบให้เลือกเงื่อนไขในตารางใหม่มาแสดง ถ้าไม่พบก็ให้แสดงข้อมูลเดิม
					$querycheck = pg_query("select * from \"nw_statusNT\" where \"NTID\"='$NTID'");
					$numrowscheck=pg_num_rows($querycheck);
					
					if($numrowscheck > 0){ //กรณีพบข้อมูลในตารางใหม่ ให้ยึดตารางใหม่เป็นหลัก
						if($res=pg_fetch_array($querycheck)){
							$statusNT=$res["statusNT"];
							//if($statusNT==6 || $statusNT==1 || $statusNT==3 || $statusNT==4 || $statusNT==5){
								if($statusNT==6){
									$txtstatus="<font color=red>ยกเลิก</font>";
								}else if($statusNT==1 || $statusNT==3 || $statusNT==4){
									$txtstatus="NT";
								}else if($statusNT==5){
									$txtstatus="รออนุมัติ";
								}else if($statusNT==2){
									$txtstatus="ไม่อนุมัติ NT";
								}
								if($i%2==0){
									echo "<tr class=\"odd\">";
								}else{
									echo "<tr class=\"even\">";
								}
								?>
									<td align="center">
										<span onclick="javascript:popU('notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><u><?php echo $NTID; ?></u></span>        
									</td>
									<td align="center">
										<span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><u><?php echo $IDNO; ?></u></span>        
									</td>
									<td align="left"><?php echo $fullname." ".$txtcus; ?></td>
									<td align="center"><?php echo $c_regis; ?></td>
									<td align="left"><?php echo $C_COLOR; ?></td>
									<td align="center"><?php echo $txtstatus; ?></td>
								</tr>
								<?php
								$i++;
							//}
						}
					}else{ //กรณีไม่พบในตาราง nw_statusNT
						if($cancel=='f'){
							//ตรวจสอบว่ารายการรออนุมัติยกเลิกอยู่หรือไม่
							$result2=pg_query("select * from \"NTHead\" where  \"cancelid\" is not null and  \"cancel_date\" is not null and \"IDNO\"='$IDNO' and cancel='FALSE'");
							$numresult=pg_num_rows($result2);
							if($numresult>0){ //แสดงว่ามีการรออนุมัติยกเลิก
								$txtstatus="รออนุมัติ";
							}else{
								$txtstatus="NT";
							}
							
						}else{
							$txtstatus="<font color=red>ยกเลิก</font>";
						}
						if($i%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
						?>
							<td align="center">
								<span onclick="javascript:popU('notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><u><?php echo $NTID; ?></u></span>        
							</td>
							<td align="center">
								<span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><u><?php echo $IDNO; ?></u></span>        
							</td>
							<td align="left"><?php echo $fullname." ".$txtcus;; ?></td>
							<td align="center"><?php echo $c_regis; ?></td>
							<td align="left"><?php echo $C_COLOR; ?></td>
							<td align="center"><?php echo $txtstatus; ?></td>
						</tr>
					<?php
						$i++;
					}
				} // end while
				?>
			</table>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<?php 
			if($i > 0){?>
			<tr>
				<td align="left" colspan="6">ทั้งหมด <?php echo $i; ?> รายการ</td>
			</tr>
			<?php
			}
			 
			if($i == 0){   
			?>
				<tr><td colspan="6" align="center">- ไม่พบข้อมูล -</td></tr>        
			<?php
			}
			?>
			</table>
		<div align="right"><br><input type="button" value="กลับ" onclick="window.location='frm_Index.php'"></div>
	</td>
</tr>
</table>
</body>
</html>