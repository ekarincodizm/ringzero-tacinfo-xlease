<?php
include("../../config/config.php");
$credit_search=$_POST["credit_search"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จัดการฝ่าย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#79BCFF'); 
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#ff6600'); 
		}); 
    });
});
</script>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="padding-bottom: 10px;text-align:center;"><h2>จัดการฝ่าย</h2></div>
			<form method="post" name="form2" action="frm_Index.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>รหัสฝ่าย,ชื่อฝ่าย</b>&nbsp;
						<input id="credit_search" name="credit_search" size="60" />&nbsp;
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form>
			<div id="panel" style="padding-top: 20px;">
			<table width="100%" align="center" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#D0D0D0">
					<form method="post" name="form1" action="frm_IndexAdd.php">
					<tr height="50" bgcolor="#FFFFFF">
						<td colspan="5" align="right"><input type="submit" value="เพิ่มรายการ"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
					</form>
				</table>
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0" class="sort-table">
					<thead>
					<tr height="25" bgcolor="#79BCFF">
						<th width="100" id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">รหัสฝ่าย</th>
						<th width="200" id="a2" class="sort-text" style="cursor:pointer;">ชื่อฝ่าย</th>
						<th width="100" id="a3" class="sort-text" style="cursor:pointer;">สถานะการใช้งาน</th>
						<th width="50">แก้ไข</th>
					</tr>
					</thead>
					<?php
						if($credit_search==""){
							$query=pg_query("select * from \"f_department\" order by \"fdep_id\"");
						}else{
							$query=pg_query("select * from \"f_department\" where \"fdep_id\" like '%$credit_search%' or \"fdep_name\" like '%$credit_search%' order by \"fdep_id\"");
						}
						$numrow=pg_num_rows($query);
						$i=1;
						while($result=pg_fetch_array($query)){
							$fdep_id=$result["fdep_id"];
							$fdep_name=$result["fdep_name"];
							$fstatus=$result["fstatus"];
							
							if($fstatus=="f"){
								$txtstatus="ระงับการใช้งาน";
							}else{
								$txtstatus="เปิดใช้งาน";
							}
							echo "<tr align=center bgcolor=#FFFFFF>";
								echo "<td align=center>$fdep_id</td>";
								echo "<td align=left>$fdep_name</td>";
								echo "<td>$txtstatus</td>";
								echo "<td><a href=\"frm_IndexAdd.php?fdep_id=$fdep_id&method=edit\"><img src=\"images/edit.png\" width=16 height=16 style=\"cursor:pointer;\" border=0 title=\"แก้ไข\"></a></td>";
							echo "</tr>";
							$i++;
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=4 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
				</table>
				
			</div>
        </td>
    </tr>
</table>
</body>
</html>