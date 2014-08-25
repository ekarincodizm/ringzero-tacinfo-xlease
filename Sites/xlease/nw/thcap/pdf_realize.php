<?php
session_start();
include("../../config/config.php");
$company=$_SESSION["session_company_thainame_thcap"]; //ชื่อบริษัท
$id_user=$_SESSION["av_iduser"]; //ผู้ทำรายการ
$nowdate = nowDateTime();
$contractID = $_POST["idno_text"];
if($contractID == ""){$contractID = $_GET["contractID"];}

//หาชื่อผู้ทำรายการ
$qry_user=pg_query("select fullname from \"Vfuser\" where id_user='$id_user'");
list($username)=pg_fetch_array($qry_user);

//หาชื่อผู้เช่า
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '0'");
list($name)=pg_fetch_array($qry_name);

//หาจำนวนงวด
$qry_due=pg_query("select count(\"DueNo\") from account.thcap_acc_filease_realize_eff_present
WHERE \"contractID\" = '$contractID'");
list($alldue)=pg_fetch_array($qry_due);

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(285,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท $company");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,24);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา : $contractID  ชื่อผู้เช่า : $name");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"จำนวน $alldue งวด");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ : $nowdate ผู้พิมพ์ : $username");
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(3,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

$pdf->SetFont('AngsanaNew','B',11);
$pdf->SetXY(3,36);
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(13,36);
$buss_name=iconv('UTF-8','windows-874',"วันที่คิด\nยอดบัญชี");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(28,36);
$buss_name=iconv('UTF-8','windows-874',"วันที่ครบ\nกำหนดชำระ");
$pdf->MultiCell(17,4,$buss_name,0,'C',0);

$pdf->SetXY(44,36);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(17,4,$buss_name,0,'C',0);

$pdf->SetXY(62,36);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(82,36);
$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(97,36);
$buss_name=iconv('UTF-8','windows-874',"ภาษี    \nมูลค่าเพิ่ม");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(112,36);
$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(114,40);
	$buss_name=iconv('UTF-8','windows-874',"ก่อนชำระ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(132,36);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(132,40);
	$buss_name=iconv('UTF-8','windows-874',"ก่อนชำระ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
$pdf->SetXY(152,36);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(152,40);
	$buss_name=iconv('UTF-8','windows-874',"ทางบัญชี\nก่อนรายการ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(172,36);
$buss_name=iconv('UTF-8','windows-874',"เงินต้นที่  \nถูกตัดจ่าย");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(192,36);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยกิดขึ้น  \nในรอบรายการนี้");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(212,36);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย  \nที่ถูกตัดจ่าย");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(232,36);
$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(232,40);
	$buss_name=iconv('UTF-8','windows-874',"หลังชำระ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(252,36);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(252,40);
	$buss_name=iconv('UTF-8','windows-874',"หลังชำระ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(272,36);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(272,40);
	$buss_name=iconv('UTF-8','windows-874',"ทางบัญชี\nหลังรายการ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);	
	
$pdf->SetXY(3,45);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

//=========================//

$cline = 51;
$nub = 1;
$DueNo_old="";
$qry_realize = pg_query("
											SELECT *,date(\"receiveDate\") as \"receiduedate\" 
											FROM 
												account.thcap_acc_filease_realize_eff_acc_present
											WHERE
												\"contractID\" = '$contractID'
											ORDER BY 
												\"duedate\",
												\"DueNo\",
												\"receiveDate\" ");
$numrows = pg_num_rows($qry_realize);

while($res_realize = pg_fetch_array($qry_realize))
{
	$DueNo = $res_realize["DueNo"]; // งวดที่  
	$accdate  = $res_realize["accdate"]; // วันที่คิดยอดบัญชี
	$duedate = $res_realize["duedate"]; // วันที่ครบกำหนดชำระ
	$receiveDate = $res_realize["receiduedate"]; // วันที่รับชำระ 
	$receiptID = $res_realize["receiptID"]; // เลขที่ใบเสร็จ 
	$debtnet = number_format($res_realize["debtnet"],2); // ค่าเช่า 
	$debtvat = number_format($res_realize["debtvat"],2); // ภาษีมูลค่าเพิ่ม 
	$totalpriciple_before = number_format($res_realize["totalpriciple_before"],2); // เงินต้นคงเหลือก่อนชำระ
	$totalinterest_before = number_format($res_realize["totalinterest_before"],2); // ดอกเบี้ยคงเหลือก่อนชำระ
	$totalaccinterest_before = number_format($res_realize["totalaccinterest_before"],2); //ดอกเบี้ยคงเหลือทางบัญชีหลังรายการ
	$priciple_cut = number_format($res_realize["priciple_cut"],2); // เงินต้นที่ถูกตัดจ่าย
	$recinterest_cut = number_format($res_realize["recinterest_cut"],2); // จำนวนดอกเบี้ยที่เกิดขึ้นในรอบรายการนี้
	$interest_cut = number_format($res_realize["interest_cut"],2); // ดอกเบี้ยที่ถูกตัดจ่าย	
	$totalpriciple_left = number_format($res_realize["totalpriciple_left"],2); // เงินต้นคงเหลือหลังชำระ
	$totalinterest_left = number_format($res_realize["totalinterest_left"],2); //ดอกเบี้ยคงเหลือหลังชำระ
	$totalaccinterest_left = number_format($res_realize["totalaccinterest_left"],2); //ดอกเบี้ยคงเหลือทางบัญชีหลังรายการ
		
	if($nub == 26)
	{
		$nub = 1;
		$cline = 51;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท $company");
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,24);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา : $contractID  ชื่อผู้เช่า : $name");
		$pdf->MultiCell(100,4,$buss_name,0,'L',0);

		$pdf->SetXY(5,28);
		$buss_name=iconv('UTF-8','windows-874',"จำนวน $alldue งวด");
		$pdf->MultiCell(100,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,28);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ : $nowdate ผู้พิมพ์ : $username");
		$pdf->MultiCell(285,4,$buss_name,0,'R',0);

		$pdf->SetXY(3,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);

		$pdf->SetFont('AngsanaNew','B',11);
		$pdf->SetXY(3,36);
		$buss_name=iconv('UTF-8','windows-874',"งวดที่");
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);

		$pdf->SetXY(13,36);
		$buss_name=iconv('UTF-8','windows-874',"วันที่คิด\nยอดบัญชี");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(28,36);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ครบ\nกำหนดชำระ");
		$pdf->MultiCell(17,4,$buss_name,0,'C',0);

		$pdf->SetXY(44,36);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(17,4,$buss_name,0,'C',0);

		$pdf->SetXY(62,36);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(82,36);
		$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(97,36);
		$buss_name=iconv('UTF-8','windows-874',"ภาษี    \nมูลค่าเพิ่ม");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(112,36);
		$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(114,40);
			$buss_name=iconv('UTF-8','windows-874',"ก่อนชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(132,36);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(132,40);
			$buss_name=iconv('UTF-8','windows-874',"ก่อนชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(152,36);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(152,40);
			$buss_name=iconv('UTF-8','windows-874',"ทางบัญชี\nก่อนรายการ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(172,36);
		$buss_name=iconv('UTF-8','windows-874',"เงินต้นที่  \nถูกตัดจ่าย");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetXY(192,36);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยกิดขึ้น  \nในรอบรายการนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetXY(212,36);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย  \nที่ถูกตัดจ่าย");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetXY(232,36);
		$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(232,40);
			$buss_name=iconv('UTF-8','windows-874',"หลังชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(252,36);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(252,40);
			$buss_name=iconv('UTF-8','windows-874',"หลังชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(272,36);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(272,40);
			$buss_name=iconv('UTF-8','windows-874',"ทางบัญชี\nหลังรายการ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);	
	
		$pdf->SetXY(3,45);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);
	}
	
	//กรณีที่มีการจ่ายหลายครั้งใน 1 งวด ไม่ต้องแสดงเลขงวดซ้ำ
	if($DueNo_old==$DueNo){ 
		$DueNo="";
	}	
	if($DueNo==-1){
		// ถ้ารายการนี้เป็นยอดปิดสิ้นเดือน ไม่ต้องแสดงเลขงวด หรือข้อมูลอื่นๆบางอย่าง
		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(13,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$accdate");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(152,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalaccinterest_before");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(192,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$recinterest_cut");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(272,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalaccinterest_left");
		$pdf->MultiCell(19,4,$buss_name,0,'R',0);
		
	} else{
					
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(3,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$DueNo");
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(13,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$accdate");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(28,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$duedate");
		$pdf->MultiCell(17,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(43,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiveDate");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(62,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiptID");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);
	
		$pdf->SetXY(82,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$debtnet");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(97,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$debtvat");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(112,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalpriciple_before");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetXY(132,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalinterest_before");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(152,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalaccinterest_before");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(172,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$priciple_cut");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(192,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$recinterest_cut");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(212,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$interest_cut");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(232,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalpriciple_left");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(252,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalinterest_left");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(272,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$totalaccinterest_left");
		$pdf->MultiCell(19,4,$buss_name,0,'R',0);
	}
	$DueNo_old=$DueNo;
	$cline += 5;
	$nub+=1;
}
if($numrows==0){
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"--ไม่พบรายการ--");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(5,$cline+6);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(290,4,$buss_name,B,'C',0);
}
$pdf->SetXY(5,$cline-5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

$pdf->Output();
?>