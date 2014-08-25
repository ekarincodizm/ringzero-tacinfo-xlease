<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$credit_search=$_POST["credit_search"];
$typeAnnId2=$_POST["typeAnnId2"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ดูประกาศที่ฉันได้รับ</title>
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
			$('#a4').css('background-color', '#79BCFF');
			$('#a5').css('background-color', '#79BCFF');
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF');
			$('#a5').css('background-color', '#79BCFF');
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#79BCFF');
			$('#a5').css('background-color', '#79BCFF');
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#ff6600');
			$('#a5').css('background-color', '#79BCFF');
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#79BCFF');   
			$('#a2').css('background-color', '#79BCFF'); 
			$('#a3').css('background-color', '#79BCFF'); 
			$('#a4').css('background-color', '#79BCFF');
			$('#a5').css('background-color', '#ff6600');
		}); 

    });
});
</script>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="padding-bottom: 10px;text-align:center;"><h2>ดูประกาศที่ฉันได้รับ</h2></div>
			<form method="post" name="form2" action="frm_userIndex.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>ชื่อเรื่อง</b>&nbsp;
						<input id="credit_search" name="credit_search" size="40" value="<?php echo $credit_search;?>"/>&nbsp;
						<b>ประเภท</b>&nbsp;
						<select name="typeAnnId2">
							<option value="">---เลือก----</option>
							<?php
							$querytype=pg_query("select * from \"nw_annoucetype\" where \"typeStatusUse\"='TRUE' order by \"typeAnnId\"");
							while($restype=pg_fetch_array($querytype)){
								$typeAnnId=$restype["typeAnnId"];
								$typeAnnName=$restype["typeAnnName"];
							?>
							<option value="<?php echo $typeAnnId?>" <?php if($typeAnnId2==$typeAnnId){ echo "selected"; }?>><?php echo $typeAnnName?></option>
							<?php
							}
							?>
						</select>
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form>
			<div id="panel" style="padding-top: 20px;">
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0" class="sort-table">
					<thead>
					<tr height="25" bgcolor="#79BCFF">
						<th id="a1" class="sort-text" style="cursor:pointer;background-color:#ff6600;">ลำดับ</th>
						<th id="a2" class="sort-text" style="cursor:pointer;">เรื่อง</th>
						<th id="a3" class="sort-text" style="cursor:pointer;">ประเภทประกาศ</th>
						<th id="a4" class="sort-text" style="cursor:pointer;">ผู้ตั้งเรื่อง</th>
						<th id="a5" class="sort-text" style="cursor:pointer;">วันที่ประกาศ</th>
						<th>แสดงประกาศ</th>
					</tr>
					</thead>
					<?php
						if($credit_search=="" and $typeAnnId2==""){
							$query=pg_query("select a.\"id_user\",c.\"annTitle\",a.\"annId\",a.\"statusAccept\",\"fullname\",\"typeAnnName\",c.\"approveDate\" from \"nw_annouceuser\" a
							left join \"nw_annoucement\" c on a.\"annId\"=c.\"annId\"
							left join \"Vfuser\" b on c.\"annAuthor\"=b.\"id_user\"
							left join \"nw_annoucetype\" d on c.\"typeAnnId\"=d.\"typeAnnId\"
							where a.\"id_user\"='$id_user' and \"statusAccept\" in('1','3') and \"statusApprove\"='TRUE' order by a.\"annId\" DESC");
						}else if($credit_search!="" and $typeAnnId2!=""){
							$query=pg_query("select a.\"id_user\",c.\"annTitle\",a.\"annId\",a.\"statusAccept\",\"fullname\",\"typeAnnName\",c.\"approveDate\" from \"nw_annouceuser\" a
							left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\"
							left join \"nw_annoucement\" c on a.\"annId\"=c.\"annId\"
							left join \"nw_annoucetype\" d on c.\"typeAnnId\"=d.\"typeAnnId\"
							where a.\"id_user\"='$id_user' and \"statusAccept\" in('1','3') and \"statusApprove\"='TRUE' and c.\"annTitle\" like '%$credit_search%' and c.\"typeAnnId\"='$typeAnnId2' order by a.\"annId\" DESC");
						}else if($credit_search=="" and $typeAnnId2!=""){
							$query=pg_query("select a.\"id_user\",c.\"annTitle\",a.\"annId\",a.\"statusAccept\",\"fullname\",\"typeAnnName\",c.\"approveDate\" from \"nw_annouceuser\" a
							left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\"
							left join \"nw_annoucement\" c on a.\"annId\"=c.\"annId\"
							left join \"nw_annoucetype\" d on c.\"typeAnnId\"=d.\"typeAnnId\"
							where a.\"id_user\"='$id_user' and \"statusAccept\" in('1','3') and \"statusApprove\"='TRUE' and c.\"typeAnnId\"='$typeAnnId2' order by a.\"annId\" DESC");
						}else if($credit_search!="" and $typeAnnId2==""){
							$query=pg_query("select a.\"id_user\",c.\"annTitle\",a.\"annId\",a.\"statusAccept\",\"fullname\",\"typeAnnName\",c.\"approveDate\" from \"nw_annouceuser\" a
							left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\"
							left join \"nw_annoucement\" c on a.\"annId\"=c.\"annId\"
							left join \"nw_annoucetype\" d on c.\"typeAnnId\"=d.\"typeAnnId\"
							where a.\"id_user\"='$id_user' and \"statusAccept\" in('1','3') and \"statusApprove\"='TRUE' and c.\"annTitle\" like '%$credit_search%' order by a.\"annId\" DESC");
						}
						$numrow=pg_num_rows($query);
						$i=1;
						while($result=pg_fetch_array($query)){
							$annId=$result["annId"];
							$annTitle=str_replaceout($result["annTitle"]);
							$authorname=trim($result["fullname"]);
							$approveDate=$result["approveDate"];
							$typeAnnName=$result["typeAnnName"];
							
							echo "<tr bgcolor=#FFFFFF>";
								echo "<td align=center>$i</td>";
								echo "<td align=left>$annTitle</td>";
								echo "<td>$typeAnnName</td>";
								echo "<td>$authorname</td>";
								echo "<td align=center>$approveDate</td>";
								echo "<td align=center><a onclick=\"javascript:popU('frm_show_approve.php?annId=$annId&status=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer;\"><img src=\"images/detail.gif\" width=19 height=19 border=0></a></td>";
							echo "</tr>";
							$i++;
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=6 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
				</table>
				<table width="100%" align="center" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#D0D0D0">
					<tr height="50" bgcolor="#FFFFFF">
						<td colspan="5" align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>