<?php
set_time_limit(0);
include("../../config/config.php");
include("../function/nameMonth.php");

$m=$_POST["month"];
$y=$_POST["year"];
$show=$_POST["show"];
if($m=="" and $y==""){
	list($y,$m,$d)=explode("-",nowDate());
}
$val=$_POST["val"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) สรุปรายได้และวิเคราะห์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
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
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#FFB3B3'); 
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#FFB3B3');
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#FFB3B3');
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#ff6600'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#FFB3B3');
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#FFB3B3');
		}); 
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#ff6600'); 
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#FFB3B3');
		}); 
		$('#a7').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#ff6600'); 
			$('#a8').css('background-color', '#FFB3B3');
		}); 
		$('#a8').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF'); 
			$('#a5').css('background-color', '#79BCFF'); 
			$('#a6').css('background-color', '#79BCFF'); 
			$('#a7').css('background-color', '#FFB3B3'); 
			$('#a8').css('background-color', '#ff6600');
		}); 
		
    });
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
$(function(){
    $("#tabs").tabs();
});
function checkvalue(){
	if(document.form1.month.value==""){
		alert("กรุณาเลือกเดือนที่ต้องการให้แสดง");
		return false;
	}
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
</style>
    
</head>
<body id="mm">
<form method="post" name="form1" action="#">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) สรุปรายได้และวิเคราะห์</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
				<div align="center">
					<div class="ui-widget">
						<p align="center">						
							<label><b>เดือนที่รับชำระ</b></label>
							<select name="month">
								<option value="">---เลือก---</option>
								<option value="01" <?php if($show=="1" and $m=="01") echo "selected"; ?>>มกราคม</option>
								<option value="02" <?php if($show=="1" and $m=="02") echo "selected"; ?>>กุมภาพันธ์</option>
								<option value="03" <?php if($show=="1" and $m=="03") echo "selected"; ?>>มีนาคม</option>
								<option value="04" <?php if($show=="1" and $m=="04") echo "selected"; ?>>เมษายน</option>
								<option value="05" <?php if($show=="1" and $m=="05") echo "selected"; ?>>พฤษภาคม</option>
								<option value="06" <?php if($show=="1" and $m=="06") echo "selected"; ?>>มิถุนายน</option>
								<option value="07" <?php if($show=="1" and $m=="07") echo "selected"; ?>>กรกฎาคม</option>
								<option value="08" <?php if($show=="1" and $m=="08") echo "selected"; ?>>สิงหาคม</option>
								<option value="09" <?php if($show=="1" and $m=="09") echo "selected"; ?>>กันยายน</option>
								<option value="10" <?php if($show=="1" and $m=="10") echo "selected"; ?>>ตุลาคม</option>
								<option value="11" <?php if($show=="1" and $m=="11") echo "selected"; ?>>พฤศจิกายน</option>
								<option value="12" <?php if($show=="1" and $m=="12") echo "selected"; ?>>ธันวาคม</option>
							</select>
							<b>ค.ศ.</b><input type="text" name="year" value="<?php echo $y;?>" size="10" maxlength="">
							<input type="hidden" name="show" value="1"/>
							<input type="submit" id="btn00" value="ค้นหา" onclick="return checkvalue();"/>
						</p>
						<?php
						//หา list รายการที่จะนำมาแสดง
						$qry_top=pg_query("SELECT \"tpConType\" FROM account.\"thcap_typePay\" group by \"tpConType\"");
						while($res_top=pg_fetch_array($qry_top)){
							 $tpCon=$res_top["tpConType"];
							 $arr_tpCon[$tpCon]=$tpCon;
						 }
						
						?>
						<div id="tabs"> <!-- เริ่ม tabs -->
						<ul>
						<?php
						//กำหนด tab จาก list รายการที่ได้
						foreach($arr_tpCon as $i => $v){
							if(empty($i)){
								continue;
							}
							echo "<li><a href=\"#tabs-$i\">$i</a></li>";
						}
						echo "</ul>";
						
						//วนแสดงข้อมูลในแต่ละ tab
						foreach($arr_tpCon as $i => $v){
							if(empty($i)){
								continue;
							}							
							$tpConType = $i;							
						?>
						<?php
						//แสดงเฉพาะกรณีเลือกเดือนแ้ล้ว
						if($show=="1"){
						?>	
						<div id="tabs-<?php echo $tpConType; ?>">
						<div>
						<div style="float:left"><b>ประจำเดือน <?php echo nameMonthTH($m); ?> ค.ศ.<?php echo $y;?></b></div><div style="float:right"><a href="pdf_reportAnalize.php?m=<?php echo "$m"; ?>&y=<?php echo "$y"; ?>&tpConType=<?php echo $tpConType;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>					
						</div>
						<div style="clear:both;"></div>
						<table width="900" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
						<thead>
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<th id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;" width="60">ประเภท<br>รับชำระ</th>
							<th id="a2" class="sort-text" style="cursor:pointer;" width="120">คำอธิบาย<br>ประเภทรับชำระ</th>
							<th id="a3" class="sort-number" style="cursor:pointer;" width="100">ยอดรับชำระเงิน</th><!--ยอดรับชำระเงิน-->
							<th id="a4" class="sort-text" style="cursor:pointer;" width="100">ยอดรับชำระภาษีมูลค่าเพิ่ม</th><!--ยอดรับชำระภาษีมูลค่าเพิ่ม-->
							<th id="a5" class="sort-number" style="cursor:pointer;" width="100">ยอดรับชำระภาษีหัก ณ ที่จ่าย</th><!--ยอดรับชำระภาษีหัก ณ ที่จ่าย-->
							<th width="30">&nbsp;</th><!--ยอดรับชำระภาษีหัก ณ ที่จ่าย-->							
							<th id="a6" class="sort-text" style="cursor:pointer;" width="100">ยอดรับชำระรวมประจำเดือน</th><!--ยอดรับชำระรวมประจำเดือน-->
							<th id="a7" class="sort-text" style="cursor:pointer;background-color:#FFB3B3;" width="100">ยอดรับชำระรวมประจำเดือนก่อน</th><!--ยอดรับชำระรวมประจำเดือนก่อน-->
							<th id="a8" class="sort-text" style="cursor:pointer;background-color:#FFB3B3;">ยอดรับชำระรวมเฉลี่ย 3 เดือนก่อน</th><!--ยอดรับชำระรวมเฉลี่ย 3 เดือนก่อน-->
							<th></th>
							</tr>
						</thead>
						<?php
						$datenow=$y."-".$m."-"."01"; //วันที่ที่เลือก
						
						$qryreceipt=pg_query("select \"tpID\",\"tpDesc\",\"isSubsti\" from account.\"thcap_typePay\" where \"tpConType\"='$tpConType' order by \"tpID\" ");											
						$numreceipt=pg_num_rows($qryreceipt);
						
						//ตรวจสอบว่า contype เป็น HP หรือ LEASING หรือไม่ ถ้าใช่จะไม่มีแบ่งค่างวดและดอกเบี้ย
						$rescon=pg_query("select \"thcap_get_creditType\"('$tpConType')");
						list($contype)=pg_fetch_array($rescon);
						
						$i=0;
						$sumnet=0;
						$sumvat=0;
						$sumdebt=0;
						$sumwht=0;
						$sumnet2=0;
						$sumbeforetree=0;
						while($result=pg_fetch_array($qryreceipt)){
							/*//กำหนดค่าเริ่มต้นให้กับตัวแปร ทุกครั้งที่วนลูปจะคืนค่าเป็น 0 เพื่อรองรับค่าใหม่////*/
							$sumnetAmt=0; //netAmt
							$sumvatAmt=0; //vatAmt
							$sumwhtAmt=0; //whtAmt
							$sumdebtAmt=0; //debtAmt
							
							$sumdebtAmt_before=0; //ผลรวมก่อนหน้า 1 เดือน
							$sumdebtAmt3=0; //ผลรวมก่อนหน้า 3 เดือน
							
							$a[0]=0;
							$a[1]=0;
							$a[2]=0;
							$a[3]=0;
							
							$aa[3]=0;
							$aaa[3]=0;
							/*////////////*/
							
							$tpID=$result["tpID"];
							$tpDesc=$result["tpDesc"];
							$isSubsti=$result["isSubsti"]; //ถ้าเท่ากับ 1 ไม่ต้องนำมารวม
							
							//หาค่า netAmt, vatAmt, debtAmt ตามลำดับ  โดยแทนค่าใน function ดังนี้ thcap_cal_sumTypePay(typeID,ปี,เดือน,วัน)
							
						//ข้อมูลปัจจุบัน************************
							$qryvalue=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y','$m'))");
							$c=0;
							while($resvalue=pg_fetch_array($qryvalue)){
								$a[$c]=$resvalue["unnest"];
								$c++;
							}	
							$sumnetAmt=number_format($a[0],2,'.',''); 
							$sumvatAmt=number_format($a[1],2,'.',''); //vatAmt
							$sumwhtAmt=number_format($a[2],2,'.',''); //whtAmt
							$sumdebtAmt=number_format($a[3],2,'.',''); //debtAmt
							
						//จบข้อมูลปัจจุบัน**********************
							
						//ข้อมูล 1 เดือนก่อนหน้า**************************
							//หาเดือนก่อนหน้าที่เลือก 1 เดือน
							$qrymonth=pg_query("SELECT date(date('$datenow') - interval '1 month')");
							list($beformonth)=pg_fetch_array($qrymonth);
							list($y2,$m2,$d2)=explode("-",$beformonth);
							$datebefor=$datenow;
							
							$qryvalue1=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y2','$m2'))");
							$cc=0;
							while($resvalue1=pg_fetch_array($qryvalue1)){
								$aa[$cc]=$resvalue1["unnest"];
								$cc++;
							}	
							$sumdebtAmt_before=number_format($aa[3],2,'.',''); //debtAmt
						//จบข้อมูล 1 เดือนก่อนหน้า***************************
						
						//ข้อมูล 3 เดือนก่อนหน้า**************************
							$sumdebtThree=0;
							for($h=1;$h<=3;$h++){
								$qrymonth3=pg_query("SELECT date(date('$datebefor') - interval '1 month')");
								list($beformonth3)=pg_fetch_array($qrymonth3);
								list($y3,$m3,$d3)=explode("-",$beformonth3);
								
								
								$qryvalue3=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y3','$m3'))");
								$ccc=0;
								while($resvalue3=pg_fetch_array($qryvalue3)){
									$aaa[$ccc]=$resvalue3["unnest"];
									$ccc++;
								}	
								$sumdebtAmt3=number_format($aaa[3],2,'.',''); //debtAmt	
								$sumdebtThree=$sumdebtThree+$sumdebtAmt3;
								$datebefor=$y3."-".$m3."-".$d3;
							}		
							//หาเฉลี่ย 3 เดือน
							$sumdebtThree=$sumdebtThree/3;
							$sumdebtThree=number_format($sumdebtThree,2,'.','');
						//จบข้อมูล 3เดือนก่อนหน้า***************************
								
							//ตรวจสอบว่าเป็นชำระตามสัญญากู้หรือไม่
							$chktype=pg_getminpaytype($tpID);
							$chktypeprinc=pg_getprincipletype($tpID);
							$chktypeint=pg_getinteresttype($tpID);
							
							//ตรวจสอบว่าเป็นเงินค้ำประกันการชำระหนี้หรือไม่
							$chktypesecure=pg_getsecuremoneytype($tpID,2);
							
							//ตรวจสอบว่าเป็นเงินพักรอตัดรายการหรือไม่
							$chktypehold=pg_getholdmoneytype($tpID,2);
							
							if($tpID==$chktypeprinc || $tpID==$chktypeint){
								$sumnetAmt=$sumdebtAmt;
							}
							$i+=1;
							if($i%2==0){
								echo "<tr class=\"odd\" align=\"right\">";
								if($tpID==$chktype || $tpID==$chktypesecure || $tpID== $chktypehold || $isSubsti==1){
									$color="#D7D7FF";
									$color2="bgcolor=#D7D7FF";
									
									if($tpID==$chktype and ($contype=='HIRE_PURCHASE' OR $contype=='LEASING')){
										$color="#FFCCCC";
										$color2="";
									}
									
								}else{
									$color="#FFCCCC";
									$color2="";
								}
							}else{
								echo "<tr class=\"even\" align=\"right\">";
								if($tpID==$chktype || $tpID==$chktypesecure || $tpID== $chktypehold || $isSubsti==1 ){
									$color="#D7D7FF";
									$color2="bgcolor=#D7D7FF";
									
									if($tpID==$chktype and ($contype=='HIRE_PURCHASE' OR $contype=='LEASING')){
										$color="#FFE8E8";
										$color2="";
									}
								}else{
									$color="#FFE8E8";
									$color2="";
								}	
							}
														
							if(($sumdebtAmt > $sumdebtAmt_before) && ($sumdebtAmt_before > $sumdebtThree) && ($sumdebtAmt > $sumdebtThree)){
								$image="up.png";
							}else if(($sumdebtAmt < $sumdebtAmt_before) && ($sumdebtAmt_before < $sumdebtThree) && ($sumdebtAmt < $sumdebtThree)){
								$image="down.png";
							}else{
								$image="updown.png";
							}
							echo "
								<td align=center $color2><span onclick=\"javascript:popU('showdetail_Analize.php?m2=$m&y2=$y&tpID=$tpID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor:pointer;\"><u>$tpID</u></span></td>
								<td align=left $color2>$tpDesc</td>
								<td $color2>".number_format($sumnetAmt,2)."</td>
								<td $color2>".number_format($sumvatAmt,2)."</td>
								<td $color2>".number_format($sumwhtAmt,2)."</td>
								<td align=center $color2><img src=\"images/$image\" width=\"25\" height=\"25\"></td>
								<td $color2>".number_format($sumdebtAmt,2)."</td>
								<td bgcolor=$color>".number_format($sumdebtAmt_before,2)."</td>
								<td bgcolor=$color>".number_format($sumdebtThree,2)."</td>
								<td align=center $color2><img src=\"images/graph.gif\" width=\"16\" height=\"16\" title=\"แสดงกราฟ\" style=\"cursor:pointer;\" onclick=\"javascript:popU('graph_Analize.php?y=$y&tpID=$tpID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\"></td>
								</tr>
							";
							
							
							if($isSubsti!=1){
								if(($tpID!=$chktype and $tpID!=$chktypesecure and $tpID!=$chktypehold) OR $tpID==$chktype and ($contype=='HIRE_PURCHASE' OR $contype=='LEASING')){
									$sumnet+=$sumnetAmt;
									$sumvat+=$sumvatAmt;
									$sumdebt+=$sumdebtAmt;
									$sumwht+=$sumwhtAmt;
									$sumnet2+=$sumdebtAmt_before;
									$sumbeforetree+=$sumdebtThree;
								}
							}
							
						}
						echo "</table>";
						echo "<table width=\"900\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" bgcolor=\"#F0F0F0\">";
						if($numreceipt==0){
							echo "<tr><td colspan=10 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
						}else{	
							echo "<tr>
							<td class=\"sum\" align=right width=\"60\" >&nbsp;</td>
							<td class=\"sum\" align=right width=\"120\" ><b>รวม</b></td>
							<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumnet,2)."</b></td>
							<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumvat,2)."</b></td>
							<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumwht,2)."</b></td>
							<td class=\"sum\" width=\"30\"></td>
							<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumdebt,2)."</b></td>
							<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumnet2,2)."</b></td>
							<td align=right class=\"sum\" width=\"\"><b>".number_format($sumbeforetree,2)."</b></td>
							<td width=16 class=\"sum\"></td>
							</tr>";
						}
						?>
						<tr bgcolor="#D7D7FF"><td colspan="10"><b>* <u>หมายเหตุ</u> รายการที่ไม่นำมาคำนวณรวม มีดังนี้ <br>
						
						<ul>
							<li><font color="red">"ผ่อนชำระตามสัญญากู้/ชำระหนี้ตามตั๋วสัญญา" จะไม่ถูกนำมาคำนวณรวมกับยอดรวมเนื่องจากได้แยกเป็น ชำระคืนเงินต้น และดอกเบี้ยไว้แล้ว</font></li>
							<li><font color="red">"เงินค้ำประกันการชำระหนี้"</font></li>
							<li><font color="red">"เงินพักรอตัดรายการ" </font></li>
							<li><font color="red">"รายการรับแทนต่าง ๆ" </font></li>
						</ul>
						</b>
						</td>
						</tr>
						</table>
					</div>
					<?php
					} // ปิด if กรณีเลือกเดือน

					} //ปิดการวน loop tab
					if($show!="1"){
						echo "<div style=\"padding:20px\"><h2>----เลือกค้นหาเพื่อแสดงข้อมูล----</h2></div>";
					}
					?>
					</div> <!--ปิด tab-->
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>
</body>
</html>