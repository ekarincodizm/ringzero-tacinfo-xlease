<?php
include("../../config/config.php");

set_time_limit(180);

$condate= pg_escape_string($_POST["condate"]);
$chksreach = pg_escape_string($_POST['chksh']); // เงื่อนไขหลัก
$subsreach = pg_escape_string($_POST['subsh']); // เงื่อนไขย่อย
$chkbox1 = pg_escape_string($_POST["chkbox1"]);
$chkbox2 =  pg_escape_string($_POST["chkbox2"]);

// หาเงื่อนไขย่อยก่อน
if($subsreach == "type1"){ //ค้นหาตามประเภทสัญญา
	$drop1 = pg_escape_string($_POST['drop1']);
	if ($drop1 == "all")
	{
		$conditiondate = "";
	}
	else
	{
		$conditiondate = "a.\"contractID\" like '$drop1%' and ";
	}
}else if($subsreach == "idno1"){ //ค้นหาตามเลขที่สัญญา
	$txt1 = pg_escape_string($_POST['txt1']);
	if ($txt1 != ""){
		$conditiondate = "a.\"contractID\" = '$txt1' and ";
	}else{
		$conditiondate = "a.\"contractID\" = '$txt1' and ";
	}
}else if($subsreach == "type2"){ // ค้นหาตามประเภทหนี้
	$drop2 = pg_escape_string($_POST['drop2']);
	if($drop2 == "1") // รับแทนประกันภัยและพรบ.
	{
		$conditiondate = "a.\"typePayID\" in (select \"tpID\" from account.\"thcap_typePay\" where (\"tpDesc\" LIKE '%รับแทน%' AND \"tpDesc\" LIKE '%ประกัน%')
							OR (\"tpDesc\" LIKE '%รับแทน%' AND \"tpDesc\" LIKE '%พรบ%')) and ";
	}
	else
	{
		$conditiondate = "";
	}
}else if($subsreach == "idno2"){ //ค้นหาตามรหัสหนี้
	$txt2 = pg_escape_string($_POST['txt2']);
	if ($txt2 != ""){
		$conditiondate = "b.\"typePayID\" = '$txt2' and ";
	}else{
		$conditiondate = "b.\"typePayID\" = '$txt2' and ";
	}
}

// เงื่อนไขหลัก
if($chksreach == "shday" || $chksreach == ""){ //ค้นหาแบบรายวัน

	$datepicker=pg_escape_string($_POST["datepicker"]);
	if($datepicker==""){
		$datepicker=nowDate();
	}
		if($condate=="1"){
			$conditiondate .= "date(a.\"doerStamp\")='$datepicker' order by b.\"doerStamp\"";
		}else{
			$conditiondate .= "date(b.\"typePayRefDate\")='$datepicker' order by b.\"typePayRefDate\"";
		}
		
	$monthsh = date('m');

}else if($chksreach == "shmonth"){ //ค้นหารายเดือน - ปี

	$monthsh = pg_escape_string($_POST['slbxSelectMonth']);
	$yearsh = pg_escape_string($_POST['slbxSelectYear']);
	
	if($monthsh == "not"){
		if($condate=="1"){
			$conditiondate .= "EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh' order by b.\"doerStamp\"";
		}else{
			$conditiondate .= "EXTRACT(YEAR FROM b.\"typePayRefDate\"::date)='$yearsh' order by b.\"typePayRefDate\"";
		}		
	}else{	
		if($condate=="1"){
			$conditiondate .= "EXTRACT(MONTH FROM a.\"doerStamp\")='$monthsh' and EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh' order by b.\"doerStamp\"";
		}else{
			$conditiondate .= "EXTRACT(MONTH FROM b.\"typePayRefDate\"::date)='$monthsh' and EXTRACT(YEAR FROM b.\"typePayRefDate\"::date)='$yearsh' order by b.\"typePayRefDate\"";
		}
	}	
	
	$datepicker=nowDate();
}

$val=pg_escape_string($_POST["val"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการตั้งหนี้<?php echo $txt1;?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
$("#txt1").autocomplete({
        source: "listcus_main.php",
        minLength:1
		});
$("#txt2").autocomplete({
        source: "listcus_main1.php",
        minLength:1
		});
});
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#79BCFF');
			$('#a12').css('background-color', '#79BCFF');
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
			$('#a12').css('background-color', '#79BCFF');
		});
		$('#a11').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#79BCFF'); 
			$('#a8').css('background-color', '#79BCFF');
			$('#a9').css('background-color', '#79BCFF');
			$('#a10').css('background-color', '#79BCFF');
			$('#a11').css('background-color', '#ff6600');
			$('#a12').css('background-color', '#79BCFF');
		});
		$('#a12').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
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
			$('#a12').css('background-color', '#ff6600');
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
			<div style="text-align:center"><h2>(THCAP) รายงานการตั้งหนี้</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onClick="window.close();" style="cursor:pointer;"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานการตั้งหนี้</B></legend>
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
									<td align="right" style="background-color:#AAFF55;"><b>เงื่อนไขย่อย : </b></td>
									<td align="left" style="background-color:#EEFF99;">
										<input type="radio" id="subsh" name="subsh" value="type1" <?php if($subsreach == "type1" || $subsreach == ""){ echo "checked"; } ?>><b><label>ประเภทสัญญา</label></b>
										<select id="drop1" name="drop1">
											<option value="all">ทั้งหมด</option>
											<?php
											$objQuery = pg_query("select \"conType\",\"conTypeDetails\" from \"thcap_contract_type\"");
											while($objResuut = pg_fetch_array($objQuery))
											{
											$dropp =$objResuut["conType"];
											?>
											<option value="<?php echo $dropp; ?>" <?php if($dropp == $drop1){echo "selected";} ?>><?php echo $dropp;?></option>
											<?php
											}
											?>
										</select>
										<input type="radio" id="subsh" name="subsh" value="idno1" <?php if($subsreach == "idno1"){ echo "checked"; } ?>><b><label>เลขที่สัญญา</label></b>
										<input type="textbox" id="txt1" name="txt1" value="<?php echo $txt1; ?>">
										<input type="radio" id="subsh" name="subsh" value="type2" <?php if($subsreach == "type2"){ echo "checked"; } ?>><b><label>ประเภทหนี้</label></b>
										<select name="drop2">
											<option value="all" <?php if($drop2 == "all"){echo "selected";} ?> >ทั้งหมด</option>
											<option value="1" <?php if($drop2 == "1"){echo "selected";} ?> >รับแทนประกันภัยและพรบ.</option>
										</select>
										<input type="radio" id="subsh" name="subsh" value="idno2" <?php if($subsreach == "idno2"){ echo "checked"; } ?>><b><label>รหัสหนี้</label></b>
										<input type="textbox" id="txt2" name="txt2" value=<?php echo $txt2; ?>>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<input type="hidden" name="val" value="1"/>
										<input type="checkbox" name="chkbox1" value="10" <?php if($chkbox1 == 10){echo "checked";} ?> />แสดงรายการที่ยกเลิก  
										<input type="checkbox" name="chkbox2" value="20" <?php if($chkbox2 == 20){echo "checked";} ?> />แสดงรายการที่ยกเว้นหนี้
										<br/>
										<input type="submit" id="btn00" style="cursor:pointer;" value="เริ่มค้น"/>
									</td>
								</tr>
							</table>
						</p>
						<?php
						if($val == 1)
						{	
							$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9','0','3') and $conditiondate ");
							$numrow_main = pg_num_rows($sql_main);
							
							if($chkbox1 == 10)
							{
								$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9','3') and $conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
							if($chkbox2 == 20)
							{
								$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9','0') and $conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
							if($chkbox2 == 20 && $chkbox1 == 10)
							{
								$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9') and $conditiondate ");
								$numrow_main = pg_num_rows($sql_main);
							}
						?>
						<div>				
						

						<?php if($chksreach == "shmonth"){ ?>
							<div align="right"><a href="pdf_reportsetdept.php?yearsh=<?php echo "$yearsh"; ?>&monthsh=<?php echo "$monthsh"; ?>&condate=<?php echo "$condate"; ?>&chkbox1=<?php echo "$chkbox1"; ?>&chkbox2=<?php echo "$chkbox2"; ?>&subsh=<?php echo $subsreach; ?>&drop1=<?php echo "$drop1"; ?>&txt1=<?php echo "$txt1"; ?>&drop2=<?php echo "$drop2"; ?>&txt2=<?php echo "$txt2"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
						<?php }elseif($chksreach == "shday"){ ?>
							<div align="right"><a href="pdf_reportsetdept.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&chkbox1=<?php echo "$chkbox1"; ?>&chkbox2=<?php echo "$chkbox2"; ?>&subsh=<?php echo $subsreach; ?>&drop1=<?php echo "$drop1"; ?>&txt1=<?php echo "$txt1"; ?>&drop2=<?php echo "$drop2"; ?>&txt2=<?php echo "$txt2"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
						<?php } ?>
							
							<table width="1050" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
								<thead>
								<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
									<th id="a1" class="sort-text" style="cursor:pointer;" width="120">เลขที่สัญญา</th>
									<th id="a2" class="sort-text" style="cursor:pointer;" width="80">รหัสประเภท<br>ค่าใช้จ่าย</th>
									<th id="a9" class="sort-text" style="cursor:pointer;" width="80">รายละเอียด<br>ค่าใช้จ่าย</th>
									<th id="a3" class="sort-number" style="cursor:pointer;" width="80">ค่าอ้างอิง<br>ของค่าใช้จ่าย</th>
									<th id="a4" class="sort-text" style="cursor:pointer;background-color:#ff6600;" width="80">วันที่หนี้มีผล</th>
									<th id="a5" class="sort-number" style="cursor:pointer;" width="100">จำนวนหนี้ (บาท)</th>
									<th id="a6" class="sort-text" style="cursor:pointer;">ผู้ขอตั้งหนี้</th>
									<th id="a7" class="sort-text" style="cursor:pointer;">วันเวลาขอตั้งหนี้</th>
									<th id="a8" class="sort-text">รายละเอียด</th>
									<th id="a10" class="sort-text" style="cursor:pointer;">สถานะ</th>
									<th id="a11" class="sort-text" style="cursor:pointer;">วันที่จ่ายครบ</th>
									<th id="a12" class="sort-text" style="cursor:pointer;">เลขที่ใบเสร็จ</th>
								</tr>
								</thead>
								<?php
								$i=0;
								$sum_amt = 0;
								
								while($resultMain=pg_fetch_array($sql_main)){
									$debtIDMain = $resultMain["debtID"];
									
									$qryreceipt=pg_query("select *,to_char(\"doerStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"doerStamp1\" from \"thcap_v_otherpay_debt_realother\" a
									left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
									where a.\"debtID\" = '$debtIDMain' order by a.\"typePayRefDate\"");
									$numreceipt=pg_num_rows($qryreceipt);
								
								while($result=pg_fetch_array($qryreceipt)){
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
									$qry_tpDesc = pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
									$tpDescShow = pg_fetch_result($qry_tpDesc,0);
								
									// ถ้าจ่ายครบแล้ว ให้หาวันที่จ่ายครบ และเลขที่ใบเสร็จ
									if($debtStatus == '2')
									{
										$qry_receipt = pg_query("
																	SELECT
																		\"receiptID\",
																		\"receiveDate\"
																	FROM
																		\"thcap_temp_receipt_channel\"
																	WHERE
																		\"receiptID\" IN(select \"receiptID\" from \"thcap_temp_receipt_otherpay\" where \"debtID\" = '$debtIDMain') AND
																		\"receiptID\" NOT IN(select \"receiptID\" from \"thcap_temp_receipt_cancel\" where \"approveStatus\" = '1')
																	GROUP BY
																		\"receiptID\",
																		\"receiveDate\"
																	ORDER BY
																		\"receiveDate\" DESC,
																		\"receiptID\" DESC
																	LIMIT 1
																");
										$receiptID = pg_fetch_result($qry_receipt,0); // เลขที่ใบเสร็จ
										$receiveDate = pg_fetch_result($qry_receipt,1); // วันที่จ่าย
									}
									else
									{
										$receiptID = "";
										$receiveDate = "";
									}
									
									// สีเปลี่ยนไปตามสถานะ
									if($debtStatustxt == "ยกเลิก" or $debtStatustxt == "waive รายการ (ยกเว้นหนี้)"){
										echo "<tr class=changecolor1 align=\"center\">";
									}elseif($debtStatustxt == "จ่ายครบแล้ว"){
										echo "<tr class=changecolor2 align=\"center\">";
									}elseif($debtStatustxt == "ACTIVE / (ยังไม่ได้จ่าย หรือจ่ายไม่ครบ)"){
										echo "<tr class=changecolor3 align=\"center\">";
									}else{
										echo "<tr align=\"center\">";
									}
									
									echo "
										<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"blue\"><u>$contractID</u></font></span></td>
										<td>$typePayID</td>
										<td align=\"left\">$tpDescShow</td>
										<td>$typePayRefValue</td>
										<td>$typePayRefDate</td>
										<td align=right>".number_format($typePayAmt,2)."</td>
										<td align=left>$fullname</td>
										<td>$doerStamp</td>
										<td><a href=\"#\" onclick=\"javascript:popU('detail_create.php?debtID=$debtIDMain','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=410')\"><img src=\"images/detail.gif\"></a></td>
										<td>$debtStatustxt</td>
										<td>$receiveDate</td>
										<td><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')\" style=\"cursor:pointer;\"><font color=\"blue\"><u>$receiptID</u></font></span></td>
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
								echo "<tr><td bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการตั้งหนี้-</b></td></tr>";
							}else{	
								echo "<tr>
								<td class=\"sum\" align=right width=\"520\"><b>รวมเงิน</b></td><td align=right class=\"sum\" width=\"100\"><b>".number_format($sum_amt,2)."</b>
								<td class=\"sum\" align=right></td></tr>";
							}
						?>
							</table>
						</div>
						<?php
						}
						?>
					</div>
				</div>
			</fieldset>
			<br>
			<fieldset><legend><B>รออนุมัติการตั้งหนี้</B></legend>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภทค่าใช้จ่าย</td>
				<td>รายละเอียดค่าใช้จ่าย</td>
				<td>ค่าอ้างอิงของค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ตั้งหนี้</td>
				<td>วันเวลาขอตั้งหนี้</td>
				<td>เหตุผล</td>
			</tr>
			<?php
			$qry_fr=pg_query("	select a.*,to_char(a.\"doerStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"doerStamp1\",date(a.\"doerStamp\") as doerstamp2,b.\"fullname\"
								from \"thcap_temp_otherpay_debt\" a
								left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
								where a.\"debtStatus\" = '9' and a.\"ShowAppvStatus\"='1' order by a.\"doerStamp\" , a.\"debtID\" ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$debtID=$res_fr["debtID"];
				$doerUser=$res_fr["doerUser"];
				$doerStamp=$res_fr["doerStamp1"];
				$doerStamp2=$res_fr["doerstamp2"];
				$remark=$res_fr["remark"];
				$fullnameuser=$res_fr["fullname"];
				
				
				$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother\" where \"debtID\" = '$debtID' ");
				while($res_detail=pg_fetch_array($qry_detail))
				{
					$typePayID = $res_detail["typePayID"];
					$typePayRefValue = $res_detail["typePayRefValue"];
					$typePayRefDate = $res_detail["typePayRefDate"];
					$typePayAmt = $res_detail["typePayAmt"];
					$contractID = $res_detail["contractID"];
				}
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDescShow = $res_tpDesc["tpDesc"];
				}
				
				$i+=1;
				if($i%2==0){
					$color=0;
					if($chksreach == "shday" || $chksreach == ""){ //ค้นหาแบบรายวัน
						if($condate==1){
							if($datepicker==$doerStamp2){
								echo "<tr bgcolor=\"#FFCCCC\" align=center>";
								$color=1;
							}else{
								$color=0;		
							}
						}else if($condate==2){
							if($datepicker==$typePayRefDate){
								echo "<tr bgcolor=\"#FFCCCC\" align=center>";
								$color=1;
							}else{
								$color=0;
							}
						}else{
							$color=0;
						}
					}
					if($color==0){
						echo "<tr class=\"odd\" align=center>";
					}	
				}else{
					$color=0;
					if($chksreach == "shday" || $chksreach == ""){ //ค้นหาแบบรายวัน
						if($condate==1){
							if($datepicker==$doerStamp2){
								echo "<tr bgcolor=\"#FFCCCC\" align=center>";
								$color=1;
							}else{
								$color=0;		
							}
						}else if($condate==2){
							if($datepicker==$typePayRefDate){
								echo "<tr bgcolor=\"#FFCCCC\" align=center>";
								$color=1;
							}else{
								$color=0;
							}
						}else{
							$color=0;
						}
					}
					if($color==0){
						echo "<tr class=\"even\" align=center>";
					}
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td><?php echo $typePayID; ?></td>
				<td align="left"><?php echo $tpDescShow; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $fullnameuser; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<!-- <td align="left"><?php echo $remark; ?></td> -->
				<td><?php echo "<span style=\"cursor:pointer;\" onClick=\"javascript:popU('detail_debt.php?debtID=$debtID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><u>ดูเหตุผล</u></span>"; ?></td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
			</fieldset>	
        </td>
    </tr>
</table>
</form>
</body>
</html>