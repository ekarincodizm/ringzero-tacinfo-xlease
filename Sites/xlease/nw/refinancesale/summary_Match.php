<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$month = $_POST["month"];
$year = $_POST["year"];

$startDate = $year."-".$month."-01"." "."00:00:00"; 
if($month == "04" || $month == "06" || $month == "09" || $month == "11"){
	$endDate = $year."-".$month."-30"." "."23:59:59"; 
}else if($month == "02" and ($year%4 == 0)){
	$endDate = $year."-".$month."-29"." "."23:59:59"; 
}else if($month == "02" and ($year%4 != 0)){
	$endDate = $year."-".$month."-28"." "."23:59:59"; 
}else{
	$endDate = $year."-".$month."-31"." "."23:59:59"; 
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>สรุปรายละเอียดการจับคู่</title>
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
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#A8D3FF'); 
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#ff6600'); 
		}); 		
    });
});
</script>
</head>
<body>
 
<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper" style="width:950px;">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_IndexSummary.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>   
				
				<fieldset><legend><B>สรุปรายละเอียดการจับคู่</B></legend>
					<div style="padding:20px;">
					<form method="post" name="form1" action="#">
					<table width="600" border="0"  align="center">
						<tr>
							<td height="50" align="center"><b>เดือน :</b>
								<select name="month">
									<option value="" <?php if($month == ""){ echo "selected";}?>>---เลือก---</option>
									<option value="01" <?php if($month == "01"){ echo "selected";}?>>มกราคม</option>
									<option value="02" <?php if($month == "02"){ echo "selected";}?>>กุมภาพันธ์</option>
									<option value="03" <?php if($month == "03"){ echo "selected";}?>>มีนาคม</option>
									<option value="04" <?php if($month == "04"){ echo "selected";}?>>เมษายน</option>
									<option value="05" <?php if($month == "05"){ echo "selected";}?>>พฤษภาคม</option>
									<option value="06" <?php if($month == "06"){ echo "selected";}?>>มิถุนายน</option>
									<option value="07" <?php if($month == "07"){ echo "selected";}?>>กรกฎาคม</option>
									<option value="08" <?php if($month == "08"){ echo "selected";}?>>สิงหาคม</option>
									<option value="09" <?php if($month == "09"){ echo "selected";}?>>กันยายน</option>
									<option value="10" <?php if($month == "10"){ echo "selected";}?>>ตุลาคม</option>
									<option value="11" <?php if($month == "11"){ echo "selected";}?>>พฤศจิกายน</option>
									<option value="12" <?php if($month == "12"){ echo "selected";}?>>ธันวาคม</option>
								</select>
								<b>ปี ค.ศ. :</b>
								<select name="year">
									<option value="" <?php if($year == ""){ echo "selected";}?>>---เลือก---</option>
									<option value="2011" <?php if($year == "2011"){ echo "selected";}?>>2011</option>
									<option value="2012" <?php if($year == "2012"){ echo "selected";}?>>2012</option>
									<option value="2013" <?php if($year == "2013"){ echo "selected";}?>>2013</option>
									<option value="2014" <?php if($year == "2014"){ echo "selected";}?>>2014</option>
									<option value="2015" <?php if($year == "2015"){ echo "selected";}?>>2015</option>
									<option value="2016" <?php if($year == "2016"){ echo "selected";}?>>2016</option>
									<option value="2017" <?php if($year == "2017"){ echo "selected";}?>>2017</option>
									<option value="2018" <?php if($year == "2018"){ echo "selected";}?>>2018</option>
									<option value="2019" <?php if($year == "2019"){ echo "selected";}?>>2019</option>
									<option value="2020" <?php if($year == "2020"){ echo "selected";}?>>2020</option>
									<option value="2021" <?php if($year == "2021"){ echo "selected";}?>>2021</option>
									<option value="2022" <?php if($year == "2022"){ echo "selected";}?>>2022</option>
									<option value="2023" <?php if($year == "2023"){ echo "selected";}?>>2023</option>
									<option value="2024" <?php if($year == "2024"){ echo "selected";}?>>2024</option>
									<option value="2025" <?php if($year == "2025"){ echo "selected";}?>>2025</option>
								</select>
							<input type="submit" value="ค้นหา"></td>
						</tr>
					</table>
					</form>
					<hr>
					<div id="panel" style="padding-top: 10px;">
					<form name="form1" method="post" action="print_summaryCompare.php" target="_blank">
					<table width="600" cellpadding="1" cellspacing="1" border="0" bgcolor="#E8FFE8" align="center"  class="sort-table">
						<thead>
						<tr align="center" height="25" bgcolor="#A8D3FF">
							<th class="sort-number" id="a1" style="cursor:pointer;background-color:#ff6600;"><b>ลำดับที่</b></th>
							<th class="sort-text" id="a2" style="cursor:pointer;"><b>เลขที่สัญญาเก่า</b></th>
							<th class="sort-text" id="a3" style="cursor:pointer;"><b>เลขที่สัญาใหม่</b></th>
							<th class="sort-text" id="a4" style="cursor:pointer;"><b>ID, ชื่อ - สกุลพนักงานที่จับคู่</b></th>
						</tr>
						</thead>
						<?php
							//ดึงค่างวดสูงสุด ต่ำสุด และ limit ล่าสุดขึ้นมาเพื่อนำมาตรวจสอบตามเงื่อนไข
							if($year != ""){
								$qry_nomatch=pg_query("SELECT A.\"IDNO\" AS \"idno_new\",B.\"IDNO\" AS \"idno_old\",B.\"id_user\",C.\"fullname\" FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\" 
								left join \"Vfuser\" C on B.\"id_user\" = C.\"id_user\"
								where (A.\"matchDate\" between '$startDate' and '$endDate') order by B.\"id_user\"");
							}else{
								$qry_nomatch=pg_query("SELECT A.\"IDNO\" AS \"idno_new\",B.\"IDNO\" AS \"idno_old\",B.\"id_user\",C.\"fullname\" FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\" 
								left join \"Vfuser\" C on B.\"id_user\" = C.\"id_user\"
								order by B.\"id_user\"");
							}
							$nrows=pg_num_rows($qry_nomatch);
							$i = 1;
							while($res=pg_fetch_array($qry_nomatch)){  
								$idno_new=$res["idno_new"];
								$idno_old=$res["idno_old"];
								$id_user=$res["id_user"];
								$fullname = $res["fullname"];
								
								echo "<tr bgcolor=#EDF8FE height=25>";
								echo "<td align=center>$i</td>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$idno_old&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u>$idno_old</u></a></td>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$idno_new&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u>$idno_new</u></td>";
								echo "<td><font color=red>$id_user</font>,$fullname</td>";
								echo "</tr>";				
							
							$i++;
							}
							if($nrows == 0){
								echo "<tr height=50 bgcolor=#EDF8FE><td align=center colspan=4><b>ไม่พบรายการ</b></td></tr>";
							}else{
								echo "<tr height=50 bgcolor=#FFFFFF><td align=right colspan=4><input type=\"hidden\" name=\"month\" value=\"$month\"><input type=\"hidden\" name=\"year\" value=\"$year\"><input type=\"image\" src=\"images/print.gif\"></td></tr>";
							}
						?>
					</table>
					</form>
					</div>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>