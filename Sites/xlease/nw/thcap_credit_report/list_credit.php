<?php 
include("../../config/config.php");

	// ============================================================================
	// รับค่าเงื่อนไขที่ต้องการค้นหา
	// ============================================================================
	$txt_dcNoteID = pg_escape_string(trim($_REQUEST['txt_dcNoteID']));
	$s_date = pg_escape_string($_REQUEST['s_date']);
	$s_month = pg_escape_string($_REQUEST['s_month']);
	$s_year = pg_escape_string($_REQUEST['s_year']);
	$s_datefrom = pg_escape_string($_REQUEST['s_datefrom']);
	$s_dateto = pg_escape_string($_REQUEST['s_dateto']);
	$s_valuee = pg_escape_string($_REQUEST['s_value']);
	
	// ============================================================================
	// QUERY ในการค้นหาข้อมูล DEBIT NOTE ตามเงื่อนไขที่ผู้ใช้งานเลือก
	// ============================================================================
	$qry = "
		SELECT 
			\"dcNoteID\",
			\"contractID\",
			\"dcMainCusName\",
			\"subjectStatus\",
			\"dcNoteDate\",
			\"doerName\",
			\"doerStamp\",
			\"appvName\",
			\"appvStamp\"
		FROM
			account.v_thcap_dncn_active
		WHERE
			\"dcType\" = 2 "; // \"dcType\" = 2 (CREDIT NOTE)
	if($s_valuee=="0"){//รหัส Debit Note
		$qry .= "
			AND \"dcNoteID\"::date='$txt_dcNoteID' 
		";
	}else if($s_valuee=="1"){//ตามวันที่
		$qry .= "
			AND \"dcNoteDate\"::date='$s_date' 
		";
	}else if($s_valuee=="2"){//ตามเดือน ปี
		$qry .= "
			AND EXTRACT(MONTH FROM\"dcNoteDate\"::date)='$s_month' AND 
			EXTRACT(YEAR FROM\"dcNoteDate\"::date)='$s_year' 
		";
	}else if($s_valuee=="3"){//ตามช่วง
		$qry .= "
			AND \"dcNoteDate\"::date BETWEEN '$s_datefrom' AND '$s_dateto' 
		";
	}else if($s_valuee=="4"){//ค้นหาทั้งหมด
		$qry .= "";
	}
	$qry .= "
		order by \"dcNoteID\"
	";
	
	// ============================================================================
	// กำหนดค่าตัวแปรเริ่มต้น
	// ============================================================================
	$method = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานใบลดหนี้</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function detailapp_discount(idapp,statusapp){
		
		$('body').append('<div id="dialog"></div>');
		$('#dialog').load('../thcap_discount_debt/popup_app.php?idapp='+idapp+'&appstate='+statusapp);
		$('#dialog').dialog({
			title: 'รายละเอียดส่วนลด ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 670,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});	
};
</script >
<form name="frm"  method="post" target="_blank">
	<div style="margin-top:10px;"align="center">
		<table cellpadding="5" cellspacing="0" border="0" width="80%" bgcolor="#F0F0F0" align="center">
			<tr bgcolor="white">
				<td colspan="2" align="left"><font size="3" color="blue"><b>รายงานใบลดหนี้</b></font></td>
				<td colspan="9" align="right">					
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#BEBEBE" align="center">
				<td>ลำดับที่</td>
				<td>เลขที่ใบ</td>
				<td>เลขที่สัญญา</td> 	
				<td>ผู้กู้หลัก/ผู้เช่าซื้อ</td> 	
				<td>ประเภทรายการ</td> 	
				<td>วันที่มีผล</td> 	
				<td>ผู้ทำรายการ</td> 	
				<td>วันเวลาที่ทำรายการ</td> 	
				<td>ผู้อนุมัติ</td> 	
				<td>วันเวลาที่ทำรายการ </td>	
				<td>รายละเอียดเพิ่มเติม</td>
				
			</tr>
			<?php 
				$query_list = pg_query($qry);
				$num_row = pg_num_rows($query_list);
				if($num_row>0){
					$i = 0;
					while($res_v = pg_fetch_array($query_list)){
						$i++;
						$dcNoteID = $res_v['dcNoteID'];
						$contractID = $res_v['contractID'];
						$dcMainCusName = $res_v['dcMainCusName'];
						$subjectStatus = $res_v['subjectStatus'];						
						$dcNoteDate = $res_v['dcNoteDate'];
						$doerName = $res_v['doerName'];
						$doerStamp = $res_v['doerStamp'];
						$appvName = $res_v['appvName'];
						$appvStamp = $res_v['appvStamp'];
						
						//ประเภทรายการ
						if($subjectStatus=='1'){$subjectStatus="คืนเงินพักรอตัดรายการ หรอเงินค้ำประกันชำระหนี้";}	
						if($subjectStatus=='2'){$subjectStatus="ส่วนลดหนี้ใดๆ";}
						if($subjectStatus=='3'){$subjectStatus="คืนเงิน จากเงินที่ชำระหนี้ไว้เกิน หรือคืนเงินมัดจำใดๆ";}
						
						if($i%2==0){
							echo "<tr class=\"odd\" >";						
						}else{
							echo "<tr class=\"event\" >";						
						}
							echo "<td align=\"center\">$i</td>";
							
							echo "<td align=\"center\">$dcNoteID</td>";
							echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";						
							echo "<td align=\"left\">$dcMainCusName</td>";
							echo "<td align=\"center\">$subjectStatus</td>";
							
							echo "<td align=\"center\">$dcNoteDate</td>";
							echo "<td align=\"center\">$doerName</td>";	
							echo "<td align=\"center\">$doerStamp</td>";							
							echo "<td align=\"left\">$appvName</td>";
							echo "<td align=\"center\">$appvStamp</td>";							
							if($res_v['subjectStatus']=='2'){ ?>
								<td align="center"><img src="images/detail.gif" width="19" height="19" style="cursor:pointer;" 
								onclick="detailapp_discount('<?php echo $dcNoteID; ?>','0');"></td>								
							<?php } 
							echo "</tr>"; 			
					}
				}else{
					echo "<tr><td colspan=\"11\" align=\"center\">ไม่พบข้อมูลที่ค้นหา</td></tr>";
				}
			?>
		</table>
	</div>
</form>
</body>
</html>	