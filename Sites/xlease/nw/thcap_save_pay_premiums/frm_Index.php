<?php
include("../../config/config.php");

set_time_limit(180);

$condate = pg_escape_string($_POST["condate"]);
$chksreach = pg_escape_string($_POST['chksh']); // เงื่อนไขหลัก
$chkbox1 = pg_escape_string($_POST["chkbox1"]);
$chkbox2 =  pg_escape_string($_POST["chkbox2"]);

// เงื่อนไขย่อย
$conditiondate = "a.\"typePayID\" in (select \"tpID\" from account.\"thcap_typePay\"
					where (\"tpDesc\" LIKE '%รับแทน%' AND \"tpDesc\" LIKE '%ประกัน%')
						OR (\"tpDesc\" LIKE '%รับแทน%' AND \"tpDesc\" LIKE '%พรบ%')) and ";

// เงื่อนไขหลัก
if($chksreach == "shday" || $chksreach == ""){ //ค้นหาแบบรายวัน

	$datepicker=pg_escape_string($_POST["datepicker"]);
	if($datepicker==""){
		$datepicker=nowDate();
	}
		if($condate=="1"){
			$conditiondate .= "date(a.\"doerStamp\")='$datepicker' order by b.\"doerStamp\""; // สำหรับตารางหลัก
			$conditiondate_for_history .= "date(\"doerStamp\")='$datepicker'"; // สำหรับตารางประวัติที่ชำระแล้ว
		}else{
			$conditiondate .= "date(b.\"typePayRefDate\")='$datepicker' order by b.\"typePayRefDate\""; // สำหรับตารางหลัก
			$conditiondate_for_history .= "date(\"typePayRefDate\")='$datepicker'"; // สำหรับตารางประวัติที่ชำระแล้ว
		}
		
	$monthsh = date('m');

}else if($chksreach == "shmonth"){ //ค้นหารายเดือน - ปี

	$monthsh = pg_escape_string($_POST['slbxSelectMonth']);
	$yearsh = pg_escape_string($_POST['slbxSelectYear']);
	
	if($monthsh == "not"){
		if($condate=="1"){
			$conditiondate .= "EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh' order by b.\"doerStamp\""; // สำหรับตารางหลัก
			$conditiondate_for_history .= "EXTRACT(YEAR FROM \"doerStamp\")='$yearsh'"; // สำหรับตารางประวัติที่ชำระแล้ว
		}else{
			$conditiondate .= "EXTRACT(YEAR FROM b.\"typePayRefDate\"::date)='$yearsh' order by b.\"typePayRefDate\""; // สำหรับตารางหลัก
			$conditiondate_for_history .= "EXTRACT(YEAR FROM \"typePayRefDate\"::date)='$yearsh'"; // สำหรับตารางประวัติที่ชำระแล้ว
		}		
	}else{	
		if($condate=="1"){
			$conditiondate .= "EXTRACT(MONTH FROM a.\"doerStamp\")='$monthsh' and EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh' order by b.\"doerStamp\""; // สำหรับตารางหลัก
			$conditiondate_for_history .= "EXTRACT(MONTH FROM \"doerStamp\")='$monthsh' and EXTRACT(YEAR FROM \"doerStamp\")='$yearsh'"; // สำหรับตารางประวัติที่ชำระแล้ว
		}else{
			$conditiondate .= "EXTRACT(MONTH FROM b.\"typePayRefDate\"::date)='$monthsh' and EXTRACT(YEAR FROM b.\"typePayRefDate\"::date)='$yearsh' order by b.\"typePayRefDate\""; // สำหรับตารางหลัก
			$conditiondate_for_history .= "EXTRACT(MONTH FROM \"typePayRefDate\"::date)='$monthsh' and EXTRACT(YEAR FROM \"typePayRefDate\"::date)='$yearsh'"; // สำหรับตารางประวัติที่ชำระแล้ว
		}
	}	
	
	$datepicker=nowDate();
}

$val=pg_escape_string($_POST["val"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) บันทึกจ่ายเบี้ยประกันภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: '../thcap/images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});

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
			$('#a9').css('background-color', '#79BCFF');
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
		});
		$('#a10').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
			$('#a9').css('background-color', '#79BCFF');
			$('#a10').css('background-color', '#ff6600');
			$('#a11').css('background-color', '#79BCFF');
		});
    });
});

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}

.changecolor1{
	background-color:grey;
}
.changecolor2{
	background-color:#6CC417;
}
.changecolor3{
	background-color:#FFF380;
}

</style>
    
</head>
<body id="mm">
<form method="post" name="form1" action="frm_Index.php">
<table width="1050" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) บันทึกจ่ายเบี้ยประกันภัย</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>บันทึกจ่ายเบี้ยประกันภัย</B></legend>
				<div align="center">
					<div class="ui-widget">
						<p align="center">
							<table>
								<tr>
									<td align="right" style="background-color:#55AAFF;"><b>เงื่อนไขหลัก : </b></td>
									<td align="left" style="background-color:#99EEFF;">
										<b>รายงานตาม</b>
										<select name="condate">
											<option value="1" <?php if($condate=="1") echo "selected";?>>วันที่ทำรายการ</option>
											<option value="2" <?php if($condate=="2") echo "selected";?>>วันที่หนี้มีผล</option>
										</select>
										<input type="radio" id="chksh" name="chksh" value="shday" <?php if($chksreach =="shday" || $chksreach==""){ echo "checked"; } ?>>
										<label><b>วันที่</b></label>
										<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
										<input type="radio" id="chksh" name="chksh" value="shmonth" <?php if($chksreach =="shmonth"){ echo "checked"; } ?>>
										<label><b>เดือน</b></label>
										<select id="slbxSelectMonth" name="slbxSelectMonth" >
												<option value="not"<?php if($monthsh=="not"){echo "selected";} ?> style="background-Color:#FFFCCC" >แสดงทั้งหมด</option>
												<option value="01"<?php if($monthsh=='01'){echo "selected";} ?>>มกราคม</option>
												<option value="02"<?php if($monthsh=='02'){echo "selected";} ?>>กุมภาพันธ์</option>
												<option value="03"<?php if($monthsh=='03'){echo "selected";} ?>>มีนาคม</option>
												<option value="04"<?php if($monthsh=='04'){echo "selected";} ?>>เมษายน</option>
												<option value="05"<?php if($monthsh=='05'){echo "selected";} ?>>พฤษภาคม</option>
												<option value="06"<?php if($monthsh=='06'){echo "selected";} ?>>มิถุนายน</option>
												<option value="07"<?php if($monthsh=='07'){echo "selected";} ?>>กรกฎาคม</option>
												<option value="08"<?php if($monthsh=='08'){echo "selected";} ?>>สิงหาคม</option>
												<option value="09"<?php if($monthsh=='09'){echo "selected";} ?>>กันยายน</option>
												<option value="10"<?php if($monthsh=='10'){echo "selected";} ?>>ตุลาคม</option>
												<option value="11"<?php if($monthsh=='11'){echo "selected";} ?>>พฤศจิกายน</option>
												<option value="12"<?php if($monthsh=='12'){echo "selected";} ?>>ธันวาคม</option>
										</select>
										<label><b>ปี</b></label>
										<select id="slbxSelectYear" name="slbxSelectYear">
											<?php 
											$datenow = date('Y');
											if($yearsh == ""){
												$datenow1 = date('Y');
											}else{
												$datenow1 = $yearsh;
											}
												$yearback = $datenow -30;														
											 for($t=$yearback;$t<=$datenow;$t++){													  
													if($t == $datenow1){ ?> 
														<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
											<?php	}else{ ?>
														<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
											<?php  
													}
												} 
											?>	
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<input type="hidden" name="val" value="1"/>
										<input type="checkbox" name="chkbox1" value="10" <?php if($chkbox1 == 10){echo "checked";} ?> />แสดงรายการที่ยกเลิก  
										<input type="checkbox" name="chkbox2" value="20" <?php if($chkbox2 == 20){echo "checked";} ?> />แสดงรายการที่ยกเว้นหนี้
										<br/>
										<input type="submit" id="btn00" name="btn00" value="เริ่มค้น"/>
									</td>
								</tr>
							</table>
						</p>
						<?php
						if($val == 1)
						{
							if($chkbox2 == 20 && $chkbox1 == 10)
							{
								$sql_main = pg_query("select
														a.\"debtID\" as \"debtID\"
													from
														\"thcap_temp_otherpay_debt\" a,
														\"thcap_v_otherpay_debt_realother\" b
													where
														a.\"debtID\" = b.\"debtID\" and
														a.\"debtStatus\" not in('9') and
														a.\"debtID\" not in(select \"debtID\" from \"thcap_pay_insurer\") and
														$conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
							elseif($chkbox1 == 10)
							{
								$sql_main = pg_query("select
														a.\"debtID\" as \"debtID\"
													from
														\"thcap_temp_otherpay_debt\" a,
														\"thcap_v_otherpay_debt_realother\" b
													where
														a.\"debtID\" = b.\"debtID\" and
														a.\"debtStatus\" not in('9','3') and
														a.\"debtID\" not in(select \"debtID\" from \"thcap_pay_insurer\") and
														$conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
							elseif($chkbox2 == 20)
							{
								$sql_main = pg_query("select
														a.\"debtID\" as \"debtID\"
													from
														\"thcap_temp_otherpay_debt\" a,
														\"thcap_v_otherpay_debt_realother\" b
													where
														a.\"debtID\" = b.\"debtID\" and
														a.\"debtStatus\" not in('9','0') and
														a.\"debtID\" not in(select \"debtID\" from \"thcap_pay_insurer\") and
														$conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
							else
							{
								$sql_main = pg_query("select
														a.\"debtID\" as \"debtID\"
													from
														\"thcap_temp_otherpay_debt\" a,
														\"thcap_v_otherpay_debt_realother\" b
													where
														a.\"debtID\" = b.\"debtID\" and
														a.\"debtStatus\" not in('9','0','3') and
														a.\"debtID\" not in(select \"debtID\" from \"thcap_pay_insurer\") and
														$conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
						?>
						<div>
							<table width="1050">
								<tr>
									<td align="left"><b>ค้างชำระเบี้ยประกันภัย</b></td>
								</tr>
							</table>
							<table width="1050" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
								<thead>
									<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
										<th id="a1" class="sort-text" style="cursor:pointer;" width="120">เลขที่สัญญา</th>
										<th id="a2" class="sort-text" style="cursor:pointer;" width="80">รหัสประเภท<br>ค่าใช้จ่าย</th>
										<th id="a3" class="sort-text" style="cursor:pointer;" width="80">รายละเอียด<br>ค่าใช้จ่าย</th>
										<th id="a4" class="sort-text" style="cursor:pointer;" width="140">ค่าอ้างอิง<br>ของค่าใช้จ่าย</th>
										<th id="a5" class="sort-text" <?php if($condate=="2"){echo "style=\"cursor:pointer;  background-color:#ff6600;\"";}else{echo "style=\"cursor:pointer;\"";} ?> width="80">วันที่หนี้มีผล</th>
										<th id="a6" class="sort-number" style="cursor:pointer;" width="100">จำนวนหนี้ (บาท)</th>
										<th id="a7" class="sort-text" style="cursor:pointer;">ผู้ขอตั้งหนี้</th>
										<th id="a8" class="sort-text" <?php if($condate=="1"){echo "style=\"cursor:pointer;  background-color:#ff6600;\"";}else{echo "style=\"cursor:pointer;\"";} ?>>วันเวลาขอตั้งหนี้</th>
										<th id="a9" class="sort-text">รายละเอียด</th>
										<th id="a10" class="sort-text" style="cursor:pointer;">สถานะ</th>
										<th id="a11" class="sort-text">ทำรายการ</th>
									</tr>
								</thead>
								<?php
								$i=0;
								$sum_amt = 0;
								
								while($resultMain=pg_fetch_array($sql_main))
								{
									$debtIDMain = $resultMain["debtID"];
									
									$qryreceipt=pg_query("select *,to_char(\"doerStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"doerStamp1\" from \"thcap_v_otherpay_debt_realother\" a
									left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
									where a.\"debtID\" = '$debtIDMain' order by a.\"typePayRefDate\"");
									$numreceipt=pg_num_rows($qryreceipt);
								
									while($result=pg_fetch_array($qryreceipt))
									{
										$contractID=$result["contractID"];
										$typePayID=$result["typePayID"];
										$typePayRefValue=$result["typePayRefValue"];
										$typePayRefDate=$result["typePayRefDate"];
										$typePayAmt=$result["typePayAmt"];
										$fullname=$result["fullname"];
										$doerStamp=$result["doerStamp1"];
										$debtStatus=$result["debtStatus"];
										$doerID=$result["doerID"];
										if($doerID=="000"){
											$fullname="อัตโนมัติโดยระบบ";
										}
										
										if($debtStatus == '1'){
											$debtStatustxt = 'ACTIVE / (ยังไม่ได้จ่าย หรือจ่ายไม่ครบ)';
										}else if($debtStatus == '2'){
											$debtStatustxt = 'จ่ายครบแล้ว';
										}else if($debtStatus == '3'){
											$debtStatustxt = 'waive รายการ (ยกเว้นหนี้)';
										}else if($debtStatus == '4'){
											$debtStatustxt = 'ยกเลิกใบเสร็จ';
										}else if($debtStatus == '5'){
											$debtStatustxt = 'ลดหนี้เป็น 0.00';
										}else if($debtStatus == '9'){
											$debtStatustxt = 'รออนุมัติ';
										}else if($debtStatus == '0'){
											$debtStatustxt = 'ยกเลิก';
										}
									}
								
									// หารายละเอียดค่าใช้จ่ายนั้นๆ
									$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
									while($res_tpDesc = pg_fetch_array($qry_tpDesc))
									{
										$tpDescShow = $res_tpDesc["tpDesc"];
									}
									
									$btn_transaction = ""; // ปุ่มทำรายการ
									
									// code ใหม่ สีเปลี่ยนไปตามสถานะ
									if($debtStatustxt == "ยกเลิก" or $debtStatustxt == "waive รายการ (ยกเว้นหนี้)")
									{
										echo "<tr class=changecolor1 align=\"center\">";
									}
									elseif($debtStatustxt == "จ่ายครบแล้ว")
									{
										echo "<tr class=changecolor2 align=\"center\">";
										$btn_transaction = "<img src=\"../thcap/images/onebit_20.png\" height=\"25\" width=\"25\" style=\"cursor:pointer;\" onclick=\"javascript:popU('popup_transection.php?debtID=$debtIDMain','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=410')\">";
									}
									elseif($debtStatustxt == "ACTIVE / (ยังไม่ได้จ่าย หรือจ่ายไม่ครบ)")
									{
										echo "<tr class=changecolor3 align=\"center\">";
									}
									else
									{
										// code เดิม สีสลับบรรทัดเลขคู่และคี่
										$i+=1;
										if($i%2==0)
										{
											echo "<tr class=\"odd\" align=\"center\">";
										}
										else
										{
											echo "<tr class=\"even\" align=\"center\">";
										}
									}
									
									echo "
											<td>$contractID</td>
											<td>$typePayID</td>
											<td align=\"left\">$tpDescShow</td>
											<td>$typePayRefValue</td>
											<td>$typePayRefDate</td>
											<td align=right>".number_format($typePayAmt,2)."</td>
											<td align=left>$fullname</td>
											<td>$doerStamp</td>
											<td><img src=\"../thcap/images/detail.gif\" style=\"cursor:pointer;\" onclick=\"javascript:popU('detail_create.php?debtID=$debtIDMain','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=410')\"></td>
											<td>$debtStatustxt</td>
											<td>$btn_transaction</td>
										</tr>
									";
									$sum_amt+=$typePayAmt;
									unset($debtStatustxt);
								}
								?>
							</table>
							<table width="1050" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
							<?php
							if($numrow_main == 0){
								echo "<tr><td colspan=11 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการตั้งหนี้-</b></td></tr>";
							}else{	
								echo "<tr class=\"sum\">
										<td  align=right width=\"527\"><b>รวมเงิน</b></td>
										<td align=right width=\"100\"><b>".number_format($sum_amt,2)."</b></td>
										<td colspan=5 align=right></td>
									</tr>";
							}
						?>
							</table>
							
							<br/><br/><br/>
							
							<table width="1050">
								<tr>
									<td align="left"><b>ชำระเบี้ยประกันภัยแล้ว</b></td>
								</tr>
							</table>
							<table width="1050" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
								<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
									<th>เลขที่สัญญา</th>
									<th>วันที่ตั้งหนี้</th>
									<th>จำนวนหนี้ (บาท)</th>
									<th>กรมธรรม์เลขที่</th>
									<th>บริษัทประกันภัย</th>
									<th>จำนวนเงิน<br/>ที่ชำระนายหน้า</th>
									<th>ปรับปรุงส่วนเกิน<br/>เข้ารายได้อื่นๆ</th>
									<th>ผู้บันทึก</th>
									<th>วันเวลาที่บันทึก</th>
								</tr>
								<?php
								$i=0;
								$sum_pay = 0;
								
								$sql_pay = pg_query("SELECT
														b.\"contractID\",
														b.\"typePayRefDate\",
														b.\"typePayAmt\",
														a.\"policyNo\",
														a.\"insurer_id\",
														a.\"payAmt\",
														a.\"doerID\",
														a.\"doerStamp\"
													FROM
														\"thcap_pay_insurer\" a,
														\"thcap_temp_otherpay_debt\" b
													WHERE
														a.\"debtID\" = b.\"debtID\" AND
														a.\"debtID\" IN(select \"debtID\" from \"thcap_temp_otherpay_debt\" where $conditiondate_for_history)
													ORDER BY
														a.\"doerStamp\"
													");
								$row_pay = pg_num_rows($sql_pay);
								while($resultPay = pg_fetch_array($sql_pay))
								{
									$contractID = $resultPay["contractID"];
									$typePayRefDate = $resultPay["typePayRefDate"];
									$typePayAmt = $resultPay["typePayAmt"];
									$policyNo = $resultPay["policyNo"];
									$insurer_id = $resultPay["insurer_id"];
									$payAmt = $resultPay["payAmt"];
									$doerSaveID = $resultPay["doerID"];
									$saveStamp = $resultPay["doerStamp"];
									
									// หาชื่อบริษัทประกันภัย
									$qry_insurer_name = pg_query("select \"insurer_name\" from \"insurer\" where \"insurer_id\" = '$insurer_id' ");
									$insurer_name = pg_fetch_result($qry_insurer_name,0);
									
									// หาชื่อผู้บันทึก
									$qry_doerSaveName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerSaveID' ");
									$doerSaveName = pg_fetch_result($qry_doerSaveName,0);
									
									// code เดิม สีสลับบรรทัดเลขคู่และคี่
									$i+=1;
									if($i%2==0)
									{
										echo "<tr class=\"odd\" align=\"center\">";
									}
									else
									{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "
											<td align=\"center\">$contractID</td>
											<td align=\"center\">$typePayRefDate</td>
											<td align=\"right\">".number_format($typePayAmt,2)."</td>
											<td align=\"center\">$policyNo</td>
											<td align=\"left\">$insurer_name</td>
											<td align=\"right\">".number_format($payAmt,2)."</td>
											<td align=\"right\">".number_format($typePayAmt - $payAmt,2)."</td>
											<td align=\"left\">$doerSaveName</td>
											<td align=\"center\">$saveStamp</td>
										</tr>
									";
									$sum_amt += $typePayAmt;
									$sum_pay += $payAmt;
								}
								
								if($row_pay == 0)
								{
									echo "<tr bgcolor=\"#E9F8FE\"><td colspan=9 align=center><b>--ไม่พบรายการบันทึก--</b></td></tr>";
								}
								?>
							</table>
							
							<br/><br/><br/>
							
							<table width="1050">
								<tr>
									<td align="left"><b>สรุป การจ่ายเบี้ยประกัน แยกตามบริษัทประกันภัย</b></td>
								</tr>
							</table>
							<table width="1050" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
								<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
									<th>บริษัทประกันภัย</th>
									<th>เบี้ยประกันภัย</th>
									<th>ชำระแล้ว</th>
									<th>คงค้าง</th>
								</tr>
								<?php
								$i=0;
								$sum_insurer_amt = 0;
								
								$sql_pay = pg_query("SELECT
														\"insurer_id\",
														\"insurer_name\"
													FROM
														\"insurer\"
													ORDER BY
														\"insurer_name\"
													");
								$row_pay = pg_num_rows($sql_pay);
								while($resultPay = pg_fetch_array($sql_pay))
								{
									$insurer_id = $resultPay["insurer_id"];
									$insurer_name = $resultPay["insurer_name"];
									
									// หาจำนวนเงิน
									$qry_insurer_amt = pg_query("
																	SELECT
																		sum(\"payAmt\")
																	FROM
																		\"thcap_pay_insurer\"
																	WHERE
																		\"debtID\" IN(select \"debtID\" from \"thcap_temp_otherpay_debt\" where $conditiondate_for_history) AND
																		\"insurer_id\" = '$insurer_id'
																");
									$insurer_amt = pg_fetch_result($qry_insurer_amt,0);
									$sum_insurer_amt += $insurer_amt;
									
									// code เดิม สีสลับบรรทัดเลขคู่และคี่
									$i+=1;
									if($i%2==0)
									{
										echo "<tr class=\"odd\" align=\"center\">";
									}
									else
									{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "
											<td align=\"center\">$insurer_name</td>
											<td align=\"center\"></td>
											<td align=\"right\">".number_format($insurer_amt,2)."</td>
											<td align=\"center\"></td>
										</tr>
									";
								}
								
								echo "
									<tr class=\"sum\">
										<td align=\"center\">รวม</td>
										<td align=\"right\">".number_format($sum_amt,2)."</td>
										<td align=\"right\">".number_format($sum_insurer_amt,2)."</td>
										<td align=\"right\">".number_format($sum_amt - $sum_insurer_amt,2)."</td>
									</tr>
								";
								?>
							</table>
						</div>
						<?php
						}
						?>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>
</body>
</html>