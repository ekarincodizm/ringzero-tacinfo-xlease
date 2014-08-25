<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$dt = $_GET['dt'];
if(empty($dt)){
    exit;
}

?>

<script language=javascript>
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
		}); 
		$('#a2').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#ff6600'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
		}); 
		$('#a3').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#ff6600'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
		}); 
		$('#a4').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#ff6600'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
		}); 
		$('#a5').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#ff6600'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#A8D3FF'); 
		}); 
		$('#a6').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#ff6600'); 
			$('#a7').css('background-color', '#A8D3FF');			
		}); 
		$('#a7').click( function(){   
			$('#a1').css('background-color', '#A8D3FF');   
			$('#a2').css('background-color', '#A8D3FF'); 
			$('#a3').css('background-color', '#A8D3FF'); 
			$('#a4').css('background-color', '#A8D3FF'); 
			$('#a5').css('background-color', '#A8D3FF'); 
			$('#a6').css('background-color', '#A8D3FF'); 
			$('#a7').css('background-color', '#ff6600'); 
		}); 
		
    });
	
});
</script>

<table width="900" cellpadding="1" cellspacing="1" border="0" bgcolor="#E8FFE8" align="center" class="sort-table">
<thead>
<tr align="center" height="25" bgcolor="#A8D3FF">
	<th class="sort-text" id="a1" style="cursor:pointer;background-color:#ff6600;"><b>เลขที่สัญญา</b></th>
	<th class="sort-text" id="a2" style="cursor:pointer;"><b>ชื่อ - นามสกุล</b></th>
	<th class="sort-text" id="a3" style="cursor:pointer;"><b>ทะเบียนรถยนต์</b></th>
	<th class="sort-text" id="a4" style="cursor:pointer;"><b>ชื่อรุ่นรถยนต์</b></th>
	<th class="sort-text" id="a5" style="cursor:pointer;"><b>ค่างวด<br>(Inc.Vat)</b></th>
	<th class="sort-number" id="a6" style="cursor:pointer;"><b>จำนวนงวด</b></th>
	<th><b>สัญญา<br>ทั้งหมด</b></th>
	<th class="sort-text" id="a7" style="cursor:pointer;"><b>สถานะ<br>การชวน</b></th>
	<th><b>เลือก</b></th>
</tr>
</thead>
<?php
	if($dt == 3){
		$qry_nomatch=pg_query("SELECT \"IDNO\",\"CusID\",\"asset_id\",\"ActiveMatch\" FROM refinance.\"invite\" where \"id_user\" = '$id_user'  group by \"IDNO\" ,\"CusID\",\"asset_id\",\"ActiveMatch\" order by \"IDNO\"");
		$nrows=pg_num_rows($qry_nomatch);
		while($res=pg_fetch_array($qry_nomatch)){ 
			$IDNO=$res["IDNO"];
			$CusID=$res["CusID"];
			$asset_id=$res["asset_id"];
			$ActiveMatch = $res["ActiveMatch"];
			
			$qry_invite=pg_query("SELECT * FROM refinance.showrefinance where \"IDNO\"='$IDNO'");
			$numinvite=pg_num_rows($qry_invite);
			while($resinvite=pg_fetch_array($qry_invite)){
				list($v_idno,$CarID,$P_STDATE,$v_cusid,$v_fullname,$regis,$v_ccarname,$P_MONTH,$P_VAT,$v_ptotal,$v_ptotaled)=$resinvite;	
								
				$v_paymentall = $P_MONTH + $P_VAT;
													
				if($ActiveMatch == "f"){
					echo "<tr bgcolor=#EDF8FE>";
					echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$v_idno&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u><b>$v_idno</b></u></a></td>";
					echo "<td><a href=\"#\" onclick=\"javascript:popU('../../post/frm_contact.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=630,height=575')\"><u>$v_fullname</u></a></td>";
					echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('cus_detail.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><u>$regis</u></a></td>";
					echo "<td>$v_ccarname</td>";
					echo "<td align=right>";
					echo number_format($v_paymentall,2);
					echo "</td>";
					echo "<td align=center>$v_ptotaled/$v_ptotal</td>";
					echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('idno_detail.php?CusID=$v_cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\"></a></td>";
					echo "<td align=center>ชวนแล้ว</td>";
					if($ActiveMatch == "f"){
						echo "<td align=center><input type=\"button\" value=\"ชวนเพิ่มเติม\" onclick=\"javascript:popU('frm_Add_Invite.php?idno=$v_idno&cusid=$v_cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')\"></td>";
					}else{
						echo "<td align=center><font color=red>จับคู่แล้ว</font></td>";
					}				
					echo "</tr>";
				}
			}
		}
		
		if($nrows==0){
			echo "<tr><td colspan=\"9\" align=\"center\">-ไม่พบข้อมูล-</td></tr>";
		}
	}else if($dt == "1" || $dt == "2"){
		// 1=แสดงทั้งหมด, 2=แสดงเฉพาะลูกค้าที่ยังไม่ชวน
		
		$qry_invite=pg_query("SELECT * FROM refinance.showrefinance order by \"IDNO\"");
		$numinvite=pg_num_rows($qry_invite);
		while($resinvite=pg_fetch_array($qry_invite)){
			list($v_idno,$CarID,$P_STDATE,$v_cusid,$v_fullname,$regis,$v_ccarname,$P_MONTH,$P_VAT,$v_ptotal,$v_ptotaled)=$resinvite;	
								
			$v_paymentall = $P_MONTH + $P_VAT;
								
			//ตรวจสอบว่าเลขที่สัญญานี้เคยมีการชักชวนหรือยัง
			$query_invite=pg_query("select * from refinance.\"invite\" where \"IDNO\" = '$v_idno'");
			$num_invite=pg_num_rows($query_invite);
														
			if($num_invite == 0){
				$textstatus = "<font color=red>ยังไม่ชวน</font>";
				$color="#D5EFFD";
				$ActiveMatch2 = "";
			}else{
				$textstatus = "ชวนแล้ว";
				$color="#EDF8FE";
				if($res_numinvite=pg_fetch_array($query_invite)){
					$ActiveMatch2 = $res_numinvite["ActiveMatch"];
				}	
			}
			
			if($dt=="1"){
				$txtecho=($ActiveMatch2 =="" || $ActiveMatch2 =="f");
			}else{
				$txtecho=($ActiveMatch2 =="");
			}

			if($txtecho){
				echo "<tr bgcolor=$color>";
				echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$v_idno&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u><b>$v_idno</b></u></a></td>";
				echo "<td><a href=\"#\" onclick=\"javascript:popU('../../post/frm_contact.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=630,height=575')\"><u>$v_fullname</u></a></td>";
				echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('cus_detail.php?idno=$v_idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><u>$regis</u></a></td>";
				echo "<td>$v_ccarname</td>";
				echo "<td align=right>";
				echo number_format($v_paymentall,2);
				echo "</td>";
				echo "<td align=center>$v_ptotaled/$v_ptotal</td>";
				echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('idno_detail.php?CusID=$v_cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\"></a></td>";
				echo "<td align=center>$textstatus</td>";
															
				if($num_invite == 0){
					echo "<td align=center><input type=\"button\" value=\"บันทึกการชวน\" onclick=\"javascript:popU('frm_Add_Invite.php?idno=$v_idno&cusid=$v_cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')\"></td>";
				}else{
					echo "<td align=center><input type=\"button\" value=\"ชวนเพิ่มเติม\" onclick=\"javascript:popU('frm_Add_Invite.php?idno=$v_idno&cusid=$v_cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')\"></td>";
				}
				echo "</tr>";
			}
		}
		if($numinvite==0){
			echo "<tr><td colspan=\"9\" align=\"center\">-ไม่พบข้อมูล-</td></tr>";
		}				
	}
?>
</table>
					
