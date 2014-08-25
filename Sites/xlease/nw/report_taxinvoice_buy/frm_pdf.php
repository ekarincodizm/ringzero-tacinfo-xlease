<?php
include("../../config/config.php");

// todo
// ปัจจุบันไฟล์นี้ไม่อัพเดทตามเมนู ให้แก้ไขอีกครั้งก่อนนำไปใช้

$id_user = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime();

// หาชื่อพนักงานที่พิมพ์รายงาน
$query_user_fullname = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
$user_fullname = pg_fetch_result($query_user_fullname,0);

$searchPoint = pg_escape_string($_GET['date']);
list($yy,$mm) = explode("-",$searchPoint);

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

$show_month.$show_yy;

$save_data = "";
$save_data .= '
<table cellpadding="2" cellspacing="0" border="0" width="100%" style="font-size:36px;" >
	<tr style="font-weight:bold;text-align:center" >
		<td align="center"><b>(THCAP) รายงานภาษีขาย</b></td>
	</tr>	
	<tr>
		<td align="center"><b>ประจำเดือน '.$show_month.' ปี '.$show_yy.'</b></td>
	</tr>
	<tr>
		<td align="right">ผู้พิมพ์รายงาน :  '.$user_fullname.'</td>
	</tr>
</table>

<table cellpadding="2" cellspacing="0" border="1" width="100%" style="font-size:36px;">
<tr style="font-weight:bold;text-align:center" bgcolor="#F0F0F0">
	<td  align="center" width="10%">วันที่ภาษี</td>
	<td  align="center" width="10%">เลขที่ใบกำกับภาษี (หรือใบสำคัญ)</td>
	<td  align="center" width="12%">เลขที่สัญญา (ถ้ามี)</td>
	<td  align="center" width="20%">ชื่อผู้ออกใบกำกับภาษี</td>
	<td  align="center" width="24%">รายละเอียดรายการ</td>
	<td  align="center" width="8%">จำนวนเงิน</td>
	<td  align="center" width="8%">ภาษีมูลค่าเพิ่ม</td>
	<td  align="center" width="8%">จำนวนเงินรวม</td>
</tr>';
   
$dbquery2 = pg_query("	SELECT \"dcNoteDate\", a.\"dcNoteID\", \"contractID\", \"dcNoteDescription\", \"dcNoteAmtNET\", \"dcNoteAmtVAT\", \"dcNoteAmtALL\"
						FROM account.\"thcap_dncn\" a, account.\"thcap_dncn_details\" b
						WHERE a.\"dcNoteID\" = b.\"dcNoteID\" AND cast(\"dcNoteDate\" as varchar) like '$searchPoint%' AND \"dcNoteAmtVAT\" > '0.00'
						ORDER BY \"dcNoteDate\" ");
$rowquery2 = pg_num_rows($dbquery2);
while($rs2 = pg_fetch_assoc($dbquery2))
{
	$dcNoteDate = $rs2['dcNoteDate'];
	$dcNoteID = $rs2['dcNoteID'];
	$contractID = $rs2['contractID'];
	$dcNoteDescription = $rs2['dcNoteDescription'];
	$dcNoteAmtNET = $rs2['dcNoteAmtNET'];
	$dcNoteAmtVAT = $rs2['dcNoteAmtVAT'];
	$dcNoteAmtALL = $rs2['dcNoteAmtALL'];
	
	$sum1 += $dcNoteAmtNET;
	$sum2 += $dcNoteAmtVAT;
	$sum3 += $dcNoteAmtALL;
	
	$save_data .= '
	<tr style="font-size:36px">
		<td align="center">'.$dcNoteDate.'</td>
		<td align="center">'.$dcNoteID.'</td>
		<td align="center">'.$contractID.'</td>
		<td align="center">บริษัท ไทยเอซ แคปปิตอล จำกัด</td>
		<td align="left">'.$dcNoteDescription.'</td>
		<td align="rigth">'.number_format($dcNoteAmtNET,2).'</td>
		<td align="rigth">'.number_format($dcNoteAmtVAT,2).'</td>
		<td align="rigth">'.number_format($dcNoteAmtALL,2).'</td>
	</tr>';
}

if($rowquery2 == 0){
    $save_data .= "<tr><td colspan=\"8\" align=\"center\">---------- ไม่มีข้อมูล  ----------</td></tr>";
}else{
    $save_data .= '<tr style="font-size:36px">
		<td>รวม '.$rowquery2.' รายการ</td>
		<td colspan="4" align="right"><b>รวมทั้งสิ้น:  </b></td>
		<td align="right">'.number_format($sum1,2).'</td>
		<td align="right">'.number_format($sum2,2).'</td>
		<td align="right">'.number_format($sum3,2).'</td>
    </tr>';
}

$save_data .= '</table>';

// ------------------- PDF -------------------//
//START PDF
include_once('../../Classes/tcpdf/config/lang/eng.php');
include_once('../../Classes/tcpdf/tcpdfLegal.php');

//CUSTOM HEADER and FOOTER
class MYPDF extends TCPDF {
    public function Header(){

    }

    public function Footer(){
		$this->SetFont('AngsanaUPC', '', 12);// Set font
        //$this->Line(10, 200, 340, 200);
        $this->MultiCell(50, 0, 'วันที่พิมพ์ '.date('Y-m-d'), 0, 'L', 0, 0, '', '', true);
        $this->MultiCell(245, 5, 'หน้า '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 'R', 0, 0, '', '', true);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    // A4
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false); // กระดาษยาว

// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(true);

//set margins
$pdf->SetMargins(10, 5, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set font
$pdf->SetFont('AngsanaUPC', '', 14); //AngsanaUPC  CordiaUPC

$pdf->AddPage('L');

$pdf->writeHTML($save_data, true, false, true, false, '');

$pdf->Output();
?>