<?php
session_start();
include("../../config/config.php");
set_time_limit(0);
$id_user = $_SESSION["av_iduser"];

$check = $_POST['chks'];
if($check == 'some'){
$checked2 = "checked=\"checked\" ";
$checked1 = null;
$datewhere = $_POST['sdate'];
$empid = $_POST['empname'];
						
						
			if($datewhere != null and $empid == null){
					
				$qry_nomatch=pg_query("SELECT * FROM refinance.\"invite\" where Date(\"inviteDate\") = '$datewhere'  order by \"inviteDate\" ");
			}else if($datewhere == null and $empid != null){
				$qry_nomatch=pg_query("SELECT * FROM refinance.\"invite\" where \"id_user\" = '$empid'  order by \"id_user\" ");		
			}else if($datewhere != null and $empid != null){
				$qry_nomatch=pg_query("SELECT * FROM refinance.\"invite\" where \"id_user\" = '$empid' and Date(\"inviteDate\") = '$datewhere'  order by \"id_user\"");
			}else{
				$qry_nomatch=pg_query("SELECT * FROM refinance.\"invite\"  order by \"id_user\" ");
				$checked1 = "checked=\"checked\" ";
				$checked2 = null;
			}


}else{
			$qry_nomatch=pg_query("SELECT * FROM refinance.\"invite\"  order by \"id_user\" ");
			$checked1 = "checked=\"checked\" ";
			$checked2 = null;
}			
			
			
			$nrows=pg_num_rows($qry_nomatch);
			
			
function utf8_wordwrap($str, $width) {
	$str = preg_split('#[\s\n\r]+#', $str);
	$len = 0;
	foreach ($str as $val) {
		 $val .= ' ';
		$tmp = mb_strlen($val, 'utf-8');
		$len += $tmp;
		if($len <= $width){
			$return .= $val;	
			
		}else{
			break;
		}	
	}
	return $return;
}			
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>สรุปรายละเอียดการชักชวน</title>
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
   
	$("#sdate").datepicker({
				showOn: 'button',
				buttonImage: 'images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'				
			}); 
			
			
	$("input[type='radio']").change(function(){
	
			if($(this).val()=="all"){
				document.sfrm.sdate.value=null;
				document.sfrm.empname.value=null;
			}
	});

	$("#empname").autocomplete({
        source: "s_userinvite.php",
        minLength:2
    }); 	
			
});

function autochoise(){	
	document.sfrm.chks2.checked="checked";
	
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
			$('#a7').css('background-color', '#A8D3FF'); 
			$('#a8').css('background-color', '#A8D3FF');
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
			$('#a8').css('background-color', '#A8D3FF');
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
			$('#a8').css('background-color', '#A8D3FF');
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#ff6600'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
			$('#a8').css('background-color', '#A8D3FF');
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
			$('#a8').css('background-color', '#A8D3FF');
		}); 
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#ff6600'); 
			$('#a7').css('background-color', '#A8D3FF');
			$('#a8').css('background-color', '#A8D3FF');			
		}); 
		$('#a7').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#ff6600'); 
			$('#a8').css('background-color', '#A8D3FF');
		}); 
		$('#a8').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF');
			$('#a8').css('background-color', '#ff6600'); 
		});
		$('#a9').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF');
			$('#a8').css('background-color', '#A8D3FF');
			$('#a9').css('background-color', '#ff6600');
			$('#a10').css('background-color', '#A8D3FF');			
		});
		$('#a10').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF');
			$('#a8').css('background-color', '#A8D3FF');
			$('#a9').css('background-color', '#A8D3FF');
			$('#a10').css('background-color', '#ff6600');	
		});
		
		
    });
});

</script>

</head>
<body>
 <form name="sfrm" method="post" action="frm_invite_datetime_Detail.php">
<table width="1170" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper" style="width:1150px;">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_IndexSummary.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>   
				
				<fieldset><legend><B>สรุปรายละเอียดการชักชวนลูกค้า</B></legend>
					<div style="padding:20px;">
					<table width="600" frame="border"  align="center">
						<tr>
							<td>
								<input type="radio" name="chks" id="chks1" value="all" <?php echo $checked1; ?>> ทั้งหมด
							</td>
							<td>
								<input type="radio" name="chks" id="chks2" value="some"  <?php echo $checked2; ?>> กรอง
							</td>
							<td>
								วันที่ : <input type="text" name="sdate" id="sdate" value="<?php echo $datewhere ?>" onchange="javascript : autochoise()">
							</td>
							<td>
								ชื่อพนักงาน : <input type="text" name="empname" id="empname" onchange="javascript : autochoise()">
							</td>
							<td>
								<input type="submit" value="ค้นหา">
							</td>
						</tr>
					</table>
					
					<hr>
					<div align="right" style="padding-top:5px; padding-bottom:5px;">
						<img src="icoPrint.png" border="0" width="17" height="14">&nbsp;<a href="#" onclick="javascript:popU('frm_invite_datetime_Detail_print.php?empid=<?php echo $empid; ?>&date=<?php echo $datewhere; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=750')" ><u>พิมพ์ข้อมูลทั้งหมด</u></a>
					</div>
					<div id="panel" style="padding-top: 10px;">
					<table width="1100" cellpadding="1" cellspacing="1" border="0" bgcolor="#E8FFE8" align="center" class="sort-table">
						<thead>
						<tr align="center" height="25" bgcolor="#A8D3FF">
							<th class="sort-number" id="a1" style="cursor:pointer;background-color:#ff6600;"><b>ลำดับที่</b></th>
							<th class="sort-text" id="a2" style="cursor:pointer;"><b>เลขที่สัญญา</b></th>
							<th class="sort-text" id="a3" style="cursor:pointer;"><b>ชื่อ - นามสกุล</b></th>
							<th class="sort-text" id="a4" style="cursor:pointer;"><b>ทะเบียนรถยนต์</b></th>
							<th class="sort-text" id="a5" style="cursor:pointer;"><b>ชื่อรุ่น</b></th>
							<th class="sort-text" id="a6" style="cursor:pointer;"><b>ค่างวด<br>(Inc.Vat)</b></th>
							<th class="sort-text" id="a7" style="cursor:pointer;"><b>จำนวนงวด</b></th>
							<th class="sort-text" id="a8" style="cursor:pointer;"><b>ID, ชื่อ - สกุลพนักงาน</b></th>
							<th class="sort-text" id="a9" style="cursor:pointer;"><b>วันที่ สนทนา</b></th>
							<th class="sort-text" id="a10" style="cursor:pointer;"><b>รายละเอียดการสนทนา</b></th>
						</tr>
						</thead>
						<?php
						
						
							
							$i = 1;
							while($res=pg_fetch_array($qry_nomatch)){  
								$IDNO=$res["IDNO"];
								$inviteID=$res["inviteID"];
								
								
								$CusID=$res["CusID"];
								$asset_id=$res["asset_id"];
								$id_user = $res["id_user"];
								$invite_detail = $res["invite_detail"];
								list($invite_date,$invite_time) = explode(" ",$res["inviteDate"]);
								
								
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
								
				
								echo "<tr bgcolor=#EDF8FE height=25>";
								echo "<td align=center>$i</td>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$v_idno&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u><b>$v_idno</b></u></a></td>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_contact.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=630,height=575')\"><u>$v_fullname</u></a></td>";
								echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('cus_detail.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><u>$regis</u></a></td>";
								echo "<td align=center>$v_ccarname</td>";
								echo "<td align=right>";
								echo number_format($v_paymentall,2);
								echo "</td>";
								echo "<td align=center>$v_ptotaled/$v_ptotal</td>";
								
								$qry_user=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$id_user'");
								$res_user=pg_fetch_array($qry_user);
								$q_iduser=$res_user["id_user"];
								$q_fullname = $res_user["fullname"];
								
								echo "<td ><font color=red>$q_iduser</font>, $q_fullname</td>";
								echo "<td align=center>$invite_date</td>";
								
									$invite_detail = utf8_wordwrap($invite_detail,30);
									
								if($invite_detail == null){
								
								}else{								
								echo "<td><a href=\"#\" onclick=\"javascript:popU('frm_invite_datetime_Detail_pop.php?IDNO=$IDNO&inviteID=$inviteID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=480,height=550')\">$invite_detail<font color=\"blue\">...</font></a></td>";
								
								}
								echo "</tr>";				
							
							$i++;
							}
							 ?>
								
						<?php
						?>
					</table>
					</div>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
</form>
</body>
</html>