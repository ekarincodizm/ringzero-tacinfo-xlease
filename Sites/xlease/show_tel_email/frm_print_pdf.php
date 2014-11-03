<?php
include("../config/config.php");
require('../thaipdfclass.php');
$id_user=$_SESSION["av_iduser"];
$nowdate = nowDateTime();//เวลาที่ที่ พิมพ์ ตามเวลา server 

$data_find = pg_escape_string($_GET['data_find']);
$find = pg_escape_string($_GET['condition']);
$type_show = pg_escape_string($_GET['type']);

//ชื่อ ผู้พิมพ์
$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$name_user=pg_fetch_result($queryU,0);

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',10);
        $this->SetXY(5,6); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
    }
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
$title=iconv('UTF-8','windows-874',"ตารางแสดง เบอร์โทรศัพท์และ E-mail ของพนักงาน ");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',10); 
$pdf->SetXY(4,11); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$name_user);
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',10); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(200,4,$buss_name,0,'R',0);	
 

if($find !=""){
	if(($find=="1") and ($data_find !="")){ //แผนก
		$qry_name=pg_query("
								SELECT
									\"dep_id\",
									\"dep_name\"
								FROM
									\"department\"
								WHERE
									\"dep_id\" = '$data_find' AND
									\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
							");		
		$dep_name=pg_fetch_result($qry_name,0);		
	}
}

if($find=="0"){
	$qry_gpuser=pg_query("
							SELECT
								\"dep_id\",
								\"dep_name\",
								\"dep_tel\",
								\"dep_email\"
							FROM
								\"department\"
							WHERE
								\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
							ORDER BY
								\"dep_id\"
						");//แผนกในระบบทั้งหมด		
}
else if($find=="1"){
	$qry_gpuser=pg_query("
							SELECT
								\"dep_id\",
								\"dep_name\",
								\"dep_tel\",
								\"dep_email\"
							FROM
								\"department\"
							WHERE
								\"dep_id\" = '$dep_name' AND
								\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
							ORDER BY
								\"dep_id\"
						");//แผนกที่เลือก		
}
else if($find=="2"){
	$qry_gpuser=pg_query("
							SELECT
								a.\"dep_id\",
								a.\"dep_name\",
								a.\"dep_tel\",
								a.\"dep_email\"
							FROM
								\"department\" a
							LEFT JOIN
								\"Vfuser\" b on a.\"dep_id\" = b.\"user_group\"
							WHERE
								b.\"id_user\" = '$data_find' AND
								b.\"isadmin\" <> '1' AND
								a.\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
						");
	
	$condition="a.id_user='$data_find'";
}
	$pdf->SetFont('AngsanaNew','B',10); 
	$pdf->SetXY(5,20);
	$is_acid=iconv('UTF-8','windows-874',"ชื่อ - สกุล" );
	$pdf->MultiCell(77,8,$is_acid,1,'C',0);
	 
	$pdf->SetXY(82,20);
	$is_acid=iconv('UTF-8','windows-874',"ชื่อเล่น" );
	$pdf->MultiCell(20,8,$is_acid,1,'C',0); 
	  
	$pdf->SetXY(102,20);
	$is_acid=iconv('UTF-8','windows-874',"เบอร์ภายใน" );
	$pdf->MultiCell(15,8,$is_acid,1,'C',0); 

	$pdf->SetXY(117,20);
	$is_acid=iconv('UTF-8','windows-874',"เบอร์ตรง" );
	$pdf->MultiCell(15,8,$is_acid,1,'C',0); 

	$pdf->SetXY(132,20);
	$is_acid=iconv('UTF-8','windows-874',"มือถือ" );
	$pdf->MultiCell(20,8,$is_acid,1,'C',0); 

	$pdf->SetXY(152,20);
	$is_acid=iconv('UTF-8','windows-874',"E-mail" );
	$pdf->MultiCell(53,8,$is_acid,1,'C',0);
	$cline = 28;
	$nub = 0;
	while($res_type=pg_fetch_array($qry_gpuser))
	{	
		$dep_id=$res_type["dep_id"];
		$dep_name=$res_type["dep_name"];
		$dep_tel=$res_type["dep_tel"]; // เบอร์กลาง ของแผนก
		$dep_email=$res_type["dep_email"]; // อีเมล์กลาง ของแผนก
		
		if($dep_tel != ""){$dep_tel_text = "(เบอร์กลาง #$dep_tel";}else{$dep_tel_text = "(เบอร์กลาง ยังไม่ระบุ";}
		if($dep_email != ""){$dep_email_text = "E-mail $dep_email)";}else{$dep_email_text = "E-mail ยังไม่ระบุ)";}
		
		//ชื่อแผนก
		$pdf->SetFont('AngsanaNew','B',10);			
		$pdf->SetFillColor(182,182,182);		
		$pdf->SetXY(5,$cline);				
		$is_acid=iconv('UTF-8','windows-874',"$dep_name $dep_tel_text : $dep_email_text" );
		$pdf->Cell(200,5,$is_acid,0,1,'C',true); 
		
		$cline+=5;
		
		if($find !='2'){
			$condition="a.\"user_group\"='$dep_id'";
		}
		
		$query=pg_query("SELECT
							a.fullname,
							b.u_extens,
							b.u_direct,
							CASE WHEN char_length(replace(b.u_tel, '-', '')) = 10 THEN -- เบอร์มือถือ
								substring(replace(b.u_tel, '-', '') from 1 for 3)||'-'||substring(replace(b.u_tel, '-', '') from 4 for 3)||'-'||substring(replace(b.u_tel, '-', '') from 7 for 4)
							ELSE
								CASE WHEN char_length(replace(b.u_tel, '-', '')) = 9 THEN -- เบอร์บ้าน
									substring(replace(b.u_tel, '-', '') from 1 for 2)||'-'||substring(replace(b.u_tel, '-', '') from 3 for 3)||'-'||substring(replace(b.u_tel, '-', '') from 6 for 4)
								ELSE
									b.u_tel
								END
							END AS \"u_tel\",
							b.u_email,
							b.nickname
						FROM
							\"Vfuser_active\" a 
						LEFT JOIN
							\"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"			
						WHERE
							$condition AND
							a.\"isadmin\" <> '1'
						ORDER BY
							a.id_user");
		
		if($cline >= 270)
		{
			$nub = 0;
			$cline = 28;
			$pdf->AddPage();
			$pdf->SetFont('AngsanaNew','B',10); 
			
			$pdf->SetXY(5,20);
			$is_acid=iconv('UTF-8','windows-874',"ชื่อ - สกุล" );
			$pdf->MultiCell(77,8,$is_acid,1,'C',0);  
			
			$pdf->SetXY(82,20);
			$is_acid=iconv('UTF-8','windows-874',"ชื่อเล่น" );
			$pdf->MultiCell(20,8,$is_acid,1,'C',0);

			$pdf->SetXY(102,20);
			$is_acid=iconv('UTF-8','windows-874',"เบอร์ภายใน" );
			$pdf->MultiCell(15,8,$is_acid,1,'C',0); 

			$pdf->SetXY(117,20);
			$is_acid=iconv('UTF-8','windows-874',"เบอร์ตรง" );
			$pdf->MultiCell(15,8,$is_acid,1,'C',0); 

			$pdf->SetXY(132,20);
			$is_acid=iconv('UTF-8','windows-874',"มือถือ" );
			$pdf->MultiCell(20,8,$is_acid,1,'C',0); 

			$pdf->SetXY(152,20);
			$is_acid=iconv('UTF-8','windows-874',"E-mail" );
			$pdf->MultiCell(53,8,$is_acid,1,'C',0);
		}
		
		while($res_group=pg_fetch_array($query))
		{ 	
				
				$fullname=$res_group["fullname"];
				$u_extens=$res_group["u_extens"];
				if(($u_extens !="") and($u_extens !="-")){$u_extens ='#'.$u_extens;}
				$u_direct=$res_group["u_direct"];
				if(($u_direct !="")and($u_direct !="-")){$u_direct ='#'.$u_direct;}
				$u_tel=$res_group["u_tel"];
				$u_email=$res_group["u_email"];	
				$nickname=$res_group["nickname"];
    
				if($cline >= 270){
					$nub = 0;
					$cline = 28;
					$pdf->AddPage();
					$pdf->SetFont('AngsanaNew','B',10); 
					
					$pdf->SetXY(5,20);
					$is_acid=iconv('UTF-8','windows-874',"ชื่อ - สกุล" );
					$pdf->MultiCell(77,8,$is_acid,1,'C',0);  
					
					$pdf->SetXY(82,20);
					$is_acid=iconv('UTF-8','windows-874',"ชื่อเล่น" );
					$pdf->MultiCell(20,8,$is_acid,1,'C',0);
	  
					$pdf->SetXY(102,20);
					$is_acid=iconv('UTF-8','windows-874',"เบอร์ภายใน" );
					$pdf->MultiCell(15,8,$is_acid,1,'C',0); 

					$pdf->SetXY(117,20);
					$is_acid=iconv('UTF-8','windows-874',"เบอร์ตรง" );
					$pdf->MultiCell(15,8,$is_acid,1,'C',0); 

					$pdf->SetXY(132,20);
					$is_acid=iconv('UTF-8','windows-874',"มือถือ" );
					$pdf->MultiCell(20,8,$is_acid,1,'C',0); 

					$pdf->SetXY(152,20);
					$is_acid=iconv('UTF-8','windows-874',"E-mail" );
					$pdf->MultiCell(53,8,$is_acid,1,'C',0);
				}
			
		
			$pdf->SetFont('AngsanaNew','',10); 
			$pdf->SetXY(5,$cline);
			$is_acid=iconv('UTF-8','windows-874',$fullname);
			$pdf->MultiCell(77,5,$is_acid,1,'L',0);  
			
			$pdf->SetXY(82,$cline);
			$is_acid=iconv('UTF-8','windows-874',$nickname);
			$pdf->MultiCell(20,5,$is_acid,1,'C',0);
	  
			$pdf->SetXY(102,$cline);
			$is_acid=iconv('UTF-8','windows-874',$u_extens);
			$pdf->MultiCell(15,5,$is_acid,1,'L',0); 

			$pdf->SetXY(117,$cline);
			$is_acid=iconv('UTF-8','windows-874',$u_direct);
			$pdf->MultiCell(15,5,$is_acid,1,'L',0); 

			$pdf->SetXY(132,$cline);
			$is_acid=iconv('UTF-8','windows-874',$u_tel);
			$pdf->MultiCell(20,5,$is_acid,1,'L',0); 

			$pdf->SetXY(152,$cline);
			$is_acid=iconv('UTF-8','windows-874',$u_email);
			$pdf->MultiCell(53,5,$is_acid,1,'L',0); 
    
			$cline+=5;
			$nub++;
		}

	}
$pdf->Output();


?>