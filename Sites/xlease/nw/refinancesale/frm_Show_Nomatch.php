<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];

$qry_nameuser = pg_query("select * from \"Vfuser\" where \"id_user\" = '$id_user'");
if($res_nameuser=pg_fetch_array($qry_nameuser)){
	$fullname=$res_nameuser["fullname"];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จับคู่เลขที่สัญญา</title>
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
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
		
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
		
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#ff6600'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
		
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#A8D3FF'); 
		
		}); 
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#ff6600'); 
					
		}); 
		
		
    });
	
	$('#btn1').click(function(){
		if(document.getElementById("search1").checked){
			$("#btn1").attr('disabled', true);
			$("#panel").text('กำลังค้นหาข้อมูล ....');
			$("#panel").load("s_match.php?dt="+ $("#search1").val() );
			$("#btn1").attr('disabled', false);
		}else if(document.getElementById("search2").checked){
			$("#btn1").attr('disabled', true);
			$("#panel").text('กำลังค้นหาข้อมูล ....');
			$("#panel").load("s_match.php?dt="+ $("#search2").val() );
			$("#btn1").attr('disabled', false);
		}
    });	
});
</script>

</head>
<body>
 <?php
	$query_userinvite=pg_query("select * from refinance.\"user_invite\" where \"id_user\" = '$id_user'");
	$numuser=pg_num_rows($query_userinvite);
	if($res_userinvite=pg_fetch_array($query_userinvite)){
		$status_use = $res_userinvite["status_use"];
	}
	if($status_use == 'f' || $numuser == "0"){
		echo "<div align=center><h1>คุณไม่มีสิทธิ์ในการทำรายการนี้</h1></div>";
	}else{
 ?>
<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper" style="width:950px;">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_IndexInvite.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>   
				
				<fieldset><legend><B>แสดงรายการ</B></legend>
					<div style="padding:20px;">
					<table width="900" border="0" align="center">
						<tr><td><b>พนักงานชื่อ : <?php echo $fullname;?></b><br><hr></td></tr>
					</table>
					<table width="600" border="0"  align="center">
						<tr>
						<td height="50"><input type="radio" name="status_match" id="search1" value="1" checked> รายการที่ยังไม่จับคู่</td>
						<td><input type="radio" name="status_match" id="search2" value="2"> รายการทั้งหมด</td>
						<td><input type="button" value="ค้นหา" name="btn1" id="btn1"></td></tr>
					</table>
					<hr>
					<div id="panel" style="padding-top: 10px;">
					<table width="900" cellpadding="1" cellspacing="1" border="0" bgcolor="#E8FFE8" align="center" class="sort-table">
						<thead>
						<tr align="center" height="25" bgcolor="#A8D3FF">
							<th class="sort-text" id="a1" style="cursor:pointer;background-color:#ff6600;"><b>เลขที่สัญญา</b></th>
							<th class="sort-text" id="a2" style="cursor:pointer;"><b>ชื่อ - นามสกุล</b></th>
							<th class="sort-text" id="a3" style="cursor:pointer;"><b>ทะเบียนรถยนต์</b></th>
							<th class="sort-text" id="a4" style="cursor:pointer;"><b>ชื่อรุ่นรถยนต์</b></th>
							<th class="sort-number" id="a5" style="cursor:pointer;"><b>ค่างวด<br>(Inc.Vat)</b></th>
							<th class="sort-text" id="a6" style="cursor:pointer;"><b>จำนวนงวด</b></th>
							<th><b>จับคู่เลขที่สัญญา</b></th>
							<th><b>บันทึกการติดตาม</b></th>
						</tr>
						</thead>
						<?php
							//แสดงรายการที่ยังไม่จับคู่
							$qry_nomatch=pg_query("SELECT \"IDNO\",\"CusID\",\"asset_id\",\"ActiveMatch\" FROM refinance.\"invite\" where \"id_user\" = '$id_user' and \"ActiveMatch\" = 'FALSE' group by \"IDNO\" ,\"CusID\",\"asset_id\",\"ActiveMatch\"");
							$nrows=pg_num_rows($qry_nomatch);
							
							$i = 1;
							while($res=pg_fetch_array($qry_nomatch)){  
								$IDNO=$res["IDNO"];
								$CusID=$res["CusID"];
								$asset_id=$res["asset_id"];
								$ActiveMatch = $res["ActiveMatch"];
								$qry_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' and \"CusID\" = '$CusID'");
								$res_VContact=pg_fetch_array($qry_VContact);
								$v_idno = $res_VContact["IDNO"];
								$v_cusid = $res_VContact["CusID"];
								$v_fullname = $res_VContact["full_name"];
								$v_ccarname = $res_VContact["C_CARNAME"];
								$v_paymentall = $res_VContact["P_MONTH"] + $res_VContact["P_VAT"];
								$v_ptotal = $res_VContact["P_TOTAL"];
													
								$qry_idno=pg_query("SELECT count(\"IDNO\") AS \"countidno\" FROM \"VCusPayment\" where \"R_Date\" is null and \"IDNO\" = '$v_idno' ");
								if($result_idno=pg_fetch_array($qry_idno)){
									$countidno = $result_idno["countidno"];
								}
													
								$v_ptotaled = $v_ptotal - $countidno;
													
								if($res_VContact["C_REGIS"]==""){
									$regis=$res_VContact["car_regis"];														
								}else{
									$regis=$res_VContact["C_REGIS"];					
								}
								
								if($ActiveMath == "f"){
									$color="#D5EFFD";  //ยังไม่จับคู่
								}else{
									$color="#EDF8FE"; //จับคู่แล้ว
								}
								echo "<tr bgcolor=$color>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$v_idno&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u><b>$v_idno</b></u></a></td>";
								echo "<td><a href=\"#\" onclick=\"javascript:popU('../../post/frm_contact.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=630,height=575')\"><u>$v_fullname</u></a></td>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('cus_detail.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><u>$regis</u></a></td>";
								echo "<td>$v_ccarname</td>";
								echo "<td align=right>";
								echo number_format($v_paymentall,2);
								echo "</td>";
								echo "<td align=center>$v_ptotaled/$v_ptotal</td>";
								if($ActiveMatch=="f"){
									echo "<td align=center><input type=\"button\" value=\"จับคู่\" onclick=\"javascript:popU('frm_Match_inviteSell.php?idno=$IDNO&cusid=$CusID&asset_id=$asset_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=400')\"></td>";
								}else{
									echo "จับคู่แล้ว";
								}
								if($ActiveMatch=="f"){
								echo "<td align=center><input type=\"button\" value=\"ชวนเพิ่ม\" onclick=\"javascript:popU('frm_Add_Invite.php?idno=$v_idno&cusid=$v_cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')\"></td>";
								}
								echo "</tr>";				
							
							$i++;
							}
							if($nrows == 0){
								echo "<tr height=50 bgcolor=#EDF8FE><td align=center colspan=9><b>ยังไม่มีรายการให้จับคู่</b></td></tr>";
							}
						?>
					</table>
					</div>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
<?php }?>
</body>
</html>