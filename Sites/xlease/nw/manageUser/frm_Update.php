<?php
include("../../config/config.php");
$credit_search=$_POST["credit_search"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขประวััติพนักงาน</title>
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
function check_salary(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.cutAccount.focus();
		return false;
	}
	return true;
}
function check_idnum(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.cutYear.focus();
		return false;
	}
	return true;
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

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="padding-bottom: 10px;text-align:center;"><h2>แก้ไขประวัติพนักงาน</h2></div>
			<form method="post" name="form2" action="frm_Update.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>ชื่อ - นามสกุลพนักงาน</b>&nbsp;
						<input id="credit_search" name="credit_search" size="60" />&nbsp;
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form>
			<div id="panel" style="padding-top: 20px;">
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0" class="sort-table">
					<thead>
					<tr height="25" bgcolor="#79BCFF">
						<th width="100" id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">รหัสพนักงาน</th>
						<th id="a2" class="sort-text" style="cursor:pointer;">ชื่อ-นามสกุล</th>
                        <th id="a2" class="sort-text" style="cursor:pointer;">ชื่อเล่น</th>
						<th id="a3" class="sort-text" style="cursor:pointer;">แผนก</th>
						<th id="a4" class="sort-text" style="cursor:pointer;">ฝ่าย</th>
						<th id="a5" class="sort-text" style="cursor:pointer;">ตำแหน่ง</th>
						<th id="a6" class="sort-text" style="cursor:pointer;">วันที่เริ่มทำงาน</th>
						<th id="a7" class="sort-text" style="cursor:pointer;">เบอร์ต่อภายใน</th>
						<th>แก้ไข</th>
					</tr>
					</thead>
                    <tbody>
					<?php
						if($credit_search==""){
							$query=pg_query("select a.\"id_user\",a.\"fullname\",c.\"dep_name\",d.\"fdep_name\",b.\"u_pos\",b.\"nickname\",b.\"startwork\",b.\"u_extens\" from \"Vfuser\" a 
							left join \"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"
							left join \"department\" c on a.\"user_group\"=c.\"dep_id\"
							left join \"f_department\" d on a.\"user_dep\"=d.\"fdep_id\" order by a.\"id_user\"");
						}else{
							$query=pg_query("select a.\"id_user\",a.\"fullname\",c.\"dep_name\",d.\"fdep_name\",b.\"u_pos\",b.\"startwork\",b.\"u_extens\" from \"Vfuser\" a 
							left join \"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"
							left join \"department\" c on a.\"user_group\"=c.\"dep_id\"
							left join \"f_department\" d on a.\"user_dep\"=d.\"fdep_id\" where a.\"fullname\" like '%$credit_search%' order by a.\"id_user\" ");
						}
						$numrow=pg_num_rows($query);
						$i=1;
						while($result=pg_fetch_array($query)){
							$id_user=$result["id_user"];
							$fullname=$result["fullname"];
							$dep_name=$result["dep_name"];
							$fdep_name=$result["fdep_name"];
							$nickname = $result["nickname"];
							$u_pos=$result["u_pos"];
							$startwork=$result["startwork"];
							if($startwork=="1900-01-01"){
								$startwork="ยังไม่ระบุ";
							}
							$u_extens=$result["u_extens"];
							
							echo "<tr bgcolor=#FFFFFF>";
								echo "<td align=center>$id_user</td>";
								echo "<td align=left>$fullname</td>";
								echo "<td align=left>$nickname</td>";
								echo "<td>$dep_name</td>";
								echo "<td>$fdep_name</td>";
								echo "<td>$u_pos</td>";
								echo "<td align=center>$startwork</td>";
								echo "<td>$u_extens</td>";
								echo "<td align=center><a href=\"frm_IndexAdd.php?id_user=$id_user&method=edit\"><img src=\"images/edit.png\" width=16 height=16 style=\"cursor:pointer;\"></a></td>";
							echo "</tr>";
							$i++;
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=\"9\" align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
                    </tbody>
				</table>
				<table width="100%" align="center" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#D0D0D0">
					<tr height="50" bgcolor="#FFFFFF">
						<td align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>