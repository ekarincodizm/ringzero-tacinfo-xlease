<?php
session_start();

include('../../config/config.php');
require('../../thaipdfclass.php');
require('../function/thaitxtdate.php');

$user_id = $_SESSION["av_iduser"];
$qry_name = pg_query("select \"fullname\" from \"Vfuser\" WHERE \"id_user\" = '$user_id'");
$user_name = pg_fetch_result($qry_name,0);

$printID = pg_escape_string($_GET['printID']); // รหัสหนังสือแจ้งเตือนรถหมดอายุและถอดป้าย

$qry_data = pg_query("
						SELECT
							get_date_thai_format(\"printDate\") AS \"printDate\",
							\"IDNO\",
							\"CusName\",
							\"CusAddress\",
							\"C_REGIS\",
							\"C_REGIS_BY\",
							get_date_thai_format(\"expireDate\") AS \"expireDate\"
						FROM
							\"car_expire_print\"
						WHERE
							\"printID\" = '$printID'
					");
$printDate = pg_fetch_result($qry_data,0);
$IDNO = pg_fetch_result($qry_data,1);
$CusName = pg_fetch_result($qry_data,2);
$CusAddress = pg_fetch_result($qry_data,3);
$C_REGIS = pg_fetch_result($qry_data,4);
$C_REGIS_BY = pg_fetch_result($qry_data,5);
$expireDate = pg_fetch_result($qry_data,6);

$pdf=new ThaiPDF();
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',20);
$pdf->SetXY(115,10);
$title=iconv('UTF-8','windows-874',"หนังสือแจ้งเตือนรถหมดอายุและถอดป้าย");
$pdf->MultiCell(90,4,$title,0,'R',0);
$pdf->Ln();
$pdf->SetXY(100,35);
$pdf->SetFont('AngsanaNew','B',20);	
$head=iconv('UTF-8','windows-874'," วันที่ ".$printDate);
$pdf->MultiCell(90,3,$head,0,'R',0);
$pdf->Ln();

$pdf->SetFont('AngsanaNew','B',16);	
$pdf->SetXY(15,45);
$head3=iconv('UTF-8','windows-874',"เรียน  ".$CusName);
$pdf->MultiCell(70,3,$head3,0,'L',0);


$pdf->SetXY(80,45);
$head1=iconv('UTF-8','windows-874',"เลขที่สัญญา  ".$IDNO);
$pdf->MultiCell(60,3,$head1,0,'L',0);

$pdf->SetXY(100,45);
$head2=iconv('UTF-8','windows-874',"เลขทะเบียน : ".$C_REGIS);
$pdf->MultiCell(90,3,$head2,0,'R',0);

// ส่วน เหนือความจดหมาย

$Y = 60;
$pdf->SetFont('AngsanaNew','',14);	
$pdf->SetXY(25,$Y);
$head2=iconv('UTF-8','windows-874',"ด้วยกฎกระทรวงว่าด้วยรถยนต์รับจ้างบรรทุกคนโดยสารไม่เกิน เจ็ดคน ที่จดทะเบียนในเขตกรุงเทพมหานคร พ.ศ. 2550  ");
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"ออกตามความในพระราชบัญญัติรถยนต์ พ.ศ. 2522  ซึ่งแก้ไขเพิ่มเติมโดยพระราชบัญญัติรถยนต์ (ฉบับที่ 13) พ.ศ.2547  ข้อ 14 ");
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"กำหนดว่่า  ''ให้เจ้าของรถมีหน้าที่ส่งคืนแผ่นป้ายทะเบียน  และนำใบคู่มือจดทะเบียนรถมาแสดงต่อนายทะเบียนเพื่อบันทึกหลักฐาน   " );
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"การระงับทะเบียนหรือเปลี่ยนประเภทรถภายใน 30 วัน   นับแต่วันที่ครบอายุการใช้งาน''" );
$pdf->MultiCell(175,3,$head2,0,'L',0);


$Y = 85;
$pdf->SetFont('AngsanaNew','',14);	
$pdf->SetXY(25,$Y);
$head2=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ ลิสซิ่ง จำกัด ขอเรียนว่ารถหมายเลขทะเบียน   " .$C_REGIS ."    ".$C_REGIS_BY."   ซึ่งท่านเป็นผู้ครอบครองและเป็น");
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"ผู้เข้าร่วมใช้ป้ายแท็กซี่  บัดนี้รถยนต์คันดังกล่าว จะครบอายุการใช้งานแล้ว  วันที่ ".$expireDate . "    ดังนั้น   จึงขอให้ท่าน");
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"ดำเนินการตามกฎหมายโดยเคร่งครัดต่อไปด้วย  หากฝ่าผืนหรือไม่ปฎิบัติตาม ต้องระวางโทษปรับไม่เกิน 1,000 บาท " );
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"ตามมาตรา 5  และมาตรา 58 แห่งพระราชบัญญัติรถยนต์ พ.ศ. 2522" );
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+10;
$pdf->SetXY(25,$Y);
$head2=iconv('UTF-8','windows-874',"จึงเรียนมาเพื่อทราบ" );
$pdf->MultiCell(175,3,$head2,0,'L',0);		

$Y = $Y+15;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874'," ขอแสดงความนับถือ ");
$pdf->MultiCell(175,3,$head2,0,'C',0);

$Y = $Y+19;
$pdf->SetXY(15,$Y);	
$head2=iconv('UTF-8','windows-874'," ( ".$user_name." ) ");
$pdf->MultiCell(175,3,$head2,0,'C',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"เจ้าหน้าที่ฝ่ายทะเบียน ");
$pdf->MultiCell(175,3,$head2,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);

$Y = $Y+20;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"หมายเหตุ : กรุณาติดต่อกลับเพื่อชำระค่าใช้จ่ายในการดำเนินการถอดป้าย");
$pdf->MultiCell(175,3,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"แผนกงานทะเบียนรถยนต์  โทร. 02-7442343 หรือ 02-7442366  ");
$pdf->MultiCell(175,3,$head2,0,'L',0);	


$pdf->SetFont('AngsanaNew','B',12);
$Y = $Y+6;
$pdf->SetXY(15,$Y);
$head2=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(175,3,$head2,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);	
$Y = $Y+60;
$pdf->SetXY(55,$Y);
$head2=iconv('UTF-8','windows-874',$CusName);
$pdf->MultiCell(100,6,$head2,0,'L',0);

$Y = $Y+6;
$pdf->SetXY(55,$Y);
$head2=iconv('UTF-8','windows-874',$CusAddress);
$pdf->MultiCell(100,6,$head2,0,'L',0);

$pdf->Output();
?>
</body>
</html>