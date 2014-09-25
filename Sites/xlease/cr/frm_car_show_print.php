<?php // แก้ไข ตาม Req ที่ 7094 (2014-09-02) ปรับขนาด Font แล้วต้องจัดตำแหน่งข้อมูลใหม่
set_time_limit(0);
include("../config/config.php");

if(!empty($_GET['tb_search'])){$tb_search = pg_escape_string($_GET['tb_search']);} // ทะเบียนรถ
if(!empty($_GET['mm'])) { $mm = pg_escape_string($_GET['mm']);}
if(!empty($_GET['yy'])) { $yy = pg_escape_string($_GET['yy']);}
if(!empty($_GET['car_type'])){ $Car_Type = pg_escape_string($_GET['car_type']);}
$nowdate = date("Y/m/d");

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

if($tb_search != "")
{
	$show_tb_search = "(เฉพาะทะเบียนรถที่มีตัวอักษร \"$tb_search\")";
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(190,4,$buss_name,0,'R',0);
 
    }
 
}
// PrePare String Of ประเภทรถ
if($Car_Type=="Taxi"){
	$Str_Car_Type = "(รถแท๊กซี่)";
}elseif($Car_Type=="Car"){
	$Str_Car_Type = "(รถบ้าน)";
}elseif($Car_Type=="All"){
	$Str_Car_Type = "(รถแท๊กซี่ และ รถบ้าน)";
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี".$Str_Car_Type);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ ลิสซิ่ง จำกัด");//(2014-09-02) แก้ไข ตาม Job No. 7093
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy $show_tb_search");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(98,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"วันนัด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;                           

    $qry_name=pg_query("select * from carregis.\"CarTaxDue\"
        where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' 
        AND (\"TypeDep\"='101' OR \"TypeDep\"='105' ) AND \"BookIn\"='false' ORDER BY \"TaxDueDate\",\"IDNO\" ASC ");
     
        $rows = pg_num_rows($qry_name); $Count_Show=0;
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $TaxValue = $res_name["TaxValue"];  
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
                if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            
		$qry_name2=pg_query("select a.\"CarID\" as asset_id,a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" from \"Carregis_temp\" a
		left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
		left join \"Fa1_FAST\" c on b.\"CusID\"=c.\"CusID\"
		WHERE a.\"IDNO\"='$IDNO' order by \"auto_id\" DESC limit 1 ");
		$num_cartemp=pg_num_rows($qry_name2);
		if($num_cartemp==0){
			//กรณีเป็น Gas 
			$qry_name2=pg_query("SELECT a.\"asset_id\",b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
			LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
			LEFT JOIN \"Fa1_FAST\" c ON a.\"CusID\" = c.\"CusID\"
			WHERE \"IDNO\"='$IDNO' ");
		}	       
	   //$qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"]; 
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];   
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
			$C_StartDate = $res_name2["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate)); 			
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }   
        }
		
		// ตรวจสอบทะเบียนรถ ว่าต้องการแสดงหรือไม่
		$qry_chk_car = pg_query("SELECT '$show_regis' like '%$tb_search%'");
		$chk_car = pg_fetch_result($qry_chk_car,0);
		if($chk_car == 'f')
		{
			continue; // ถ้าไม่ใช่ทะเบียนที่ต้องการแสดง ให้ข้ามไป
		}
		
		$Str_Get_Char = " SELECT '$show_regis' like 'ท%' or '$show_regis' like 'ม%' ";
		$Result = pg_query($Str_Get_Char);
		$Data = pg_fetch_result($Result,0); // return boolean ถ้าได้ true จะเป็นรถ taxi
		
		if($Car_Type == "Taxi" && $Data != 't') // ถ้าเลือกให้แสดงรถ Taxi แต่ ทะเบียนรถที่ได้ไม่ใช้รถ Taxi
		{
			continue; // ข้ามรายการนี้ไป ให้วนรอบต่อไปเลย
		}
		elseif($Car_Type == "Car" && $Data == 't') // ถ้าเลือกให้แสดงรถบ้าน แต่ ทะเบียนรถที่ได้เป็นรถ Taxi
		{
			continue; // ข้ามรายการนี้ไป ให้วนรอบต่อไปเลย
		}	
		elseif($Car_Type == "All") // ถ้าให้แสดงทั้งหมด
		{
			// ปล่อยให้ทำงานต่อไป
		}
		
		$Count_Show++;
		
if($i > 34){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ ลิสซิ่ง จำกัด");//(2014-09-02) แก้ไข ตาม Job No. 7093
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy $show_tb_search");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(98,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"วันนัด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',15);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(35,4,$buss_name,0,'L',0); // แสดงเลขที่สัญญา

$pdf->SetXY(35,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(50,4,$buss_name,0,'L',0); // แสดงชื่อ-สกุล

$pdf->SetXY(85,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(20,4,$buss_name,0,'L',0); // แสดงทะเบียน

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',$C_StartDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0); // แสดงวันที่เริ่ม

$pdf->SetXY(123,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TaxDueDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0); // แสดงวันครบกำหนด

$pdf->SetXY(154,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_meter);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(175,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ApointmentDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$cline+=7; 
$i+=1; 
      
}  

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น  $Count_Show รายการ");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(4,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>