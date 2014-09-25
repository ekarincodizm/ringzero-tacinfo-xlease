<?php
session_start();

include("../../config/config.php");
require('../../thaipdfclass.php');

//----------------- รับข้อมูล ----------------------------------
$comp_name=$_SESSION["session_company_thainame_thcap"];
$doer = $_SESSION['av_iduser'];
$iduser= $_SESSION['av_iduser'];
$select_print_JV = $_POST["select_print_JV"];
$reprint = pg_escape_string($_POST["reprint"]);
$datenow = nowDateTime();//วันเวลาที่ทำรายการ
$status = 0;
pg_query("Begin");

if($reprint=='reprint'){
	//เนื่องมาจากเมนู (THCAP) ใบสำคัญรายวันจ่ายรอพิมพ์ส่ง  
}
else{
//ACTIONLOG
if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$doer', '(THCAP) พิมพ์ใบสำคัญรายวันทั่วไป', LOCALTIMESTAMP(0))")); else $status++;
//ACTIONLOG---
}
class PDF extends ThaiPDF
{

}
$pdf=new PDF('P','mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->SetAutoPageBreak(-3);
$page = $pdf->PageNo();	

for($i=0;$i<count($select_print_JV);$i++){
	
	$pdf->AddPage();
	
	$voucherID[$i]=$select_print_JV[$i];
	
	$qry = "select * from v_thcap_temp_voucher_details_journal where \"voucherID\"='$voucherID[$i]'";
	
	if($detail = pg_query($qry)){
		
		    $res_detail = pg_fetch_array($detail);
			
			$voucherDate = $res_detail['voucherDate'];
			$voucherTime = $res_detail['voucherTime'];
			$doerFull = $res_detail['doerFull'];
			$doerStamp = $res_detail['doerStamp'];
			$appvFull = $res_detail['appvFull'];
			$appvStamp = $res_detail['appvStamp'];
			$auditFull = $res_detail['auditFull'];
			$auditStamp = $res_detail['auditStamp'];
			$voucherRemark = $res_detail['voucherRemark'];
			$fromChannelDetails = $res_detail['fromChannelDetails'];
			$abh_id = $res_detail['abh_id'];
			$voucherCancelRef = $res_detail['voucherCancelRef'];
			$voucherStatus = $res_detail['voucherStatus'];
			$doerid_sign=$res_detail['doerID'];
			$appvID_sign=$res_detail['appvID'];
			$auditID_sign=$res_detail['auditID'];
			
	}else{
		echo "Query Error!";
	}
	
	$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID[$i]'");
	$res_bookhead = pg_fetch_array($qry_bookhead);
		$abh_autoid = $res_bookhead['abh_autoid'];
		
	if($voucherStatus == "0"){
		$pdf->Image("images/13.png",5,15,200);
	
	}
	//บันทึก การสั่งพิม
	$qrymax=pg_query("select max(\"timereprint\") from \"thcap_temp_voucher_details_reprint\"  where \"voucherID\"= '$voucherID[$i]'");
	list($printTime)=pg_fetch_array($qrymax);
	
	if($printTime==""){ //กรณียังไม่มีการเพิ่มข้อมูล
		$printTime=0;
	}else{
		$printTime=$printTime+1;
	}
	if($sql_reprint = pg_query("INSERT INTO \"thcap_temp_voucher_details_reprint\"(\"voucherID\", \"id_user\",\"printStamp\",\"timereprint\") 
						VALUES ('$voucherID[$i]', '$iduser', '$datenow','$printTime')"));		
	else $status++;
	/*if($sql_reprint = pg_query("INSERT INTO \"thcap_temp_voucher_details_reprint\"(\"voucherID\", \"id_user\",\"printStamp\") 
						VALUES ('$voucherID[$i]', '$iduser', '$datenow')"));		
	else $status++;*/
		
		
//---------------- กำหนดรายละเอียด --------------------------
$address = "555 ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพมหานคร  10240";
$detail = $voucherRemark;
$date = $voucherDate." ".$voucherTime;
$number = $voucherID[$i];
$line = "______________________________________________________________________________________________________________________________________";
$lineCut = "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
$doer = $doerFull;
$doer_stamp = $doerStamp;
$appv = $appvFull;
$appv_stamp = $appvStamp;
$audit = $auditFull;
$audit_stamp = $auditStamp;
$remak = $fromChannelDetails;
$abh_id = $abh_id;
$total_debit = 0;
$total_credit = 0;
//------------------------------------------------- รายละเอียด ---------------------------------------------------------------------
		
	for($a=0;$a<=0;$a++){
		
			switch($a){
				case 0:
					$cline = 54;//199
					break;
				/*case 1:
					$cline = 54;
					break;*/
			}
			
		if($abh_autoid!=""){
		
		$qry_detail = pg_query("select * from account.\"all_accBookDetail\" where abd_autoidabh='$abh_autoid' order by abd_autoid");
		
		while($res_detail = pg_fetch_array($qry_detail)){
			$abd_accBookID = $res_detail['abd_accBookID'];
			$accBookserial = $res_detail["accBookserial"];
			$abd_bookType = $res_detail["abd_bookType"];
			$abd_amount = $res_detail["abd_amount"];
			
			
			//รหัสบัญชี
			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(15,$cline);
			$title=iconv('UTF-8','windows-874',$abd_accBookID);
			$pdf->MultiCell(200,4,$title,0,'L',0);
			
			$qry_all = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$accBookserial' ");
		
				$accBookName = pg_fetch_result($qry_all,0);
				
				//รายการ
				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(35,$cline);
				$title=iconv('UTF-8','windows-874',$accBookName);
				$pdf->MultiCell(200,4,$title,0,'L',0);
				
			if($abd_bookType == 1) {
				//เดบิต
				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(115,$cline);
				$title=iconv('UTF-8','windows-874',number_format($abd_amount,2));
				$pdf->MultiCell(50,4,$title,0,'R',0);
				
				if($a==0){
					$total_debit += $abd_amount; 
				}
			}else{
				//เครดิต
				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(147,$cline);
				$title=iconv('UTF-8','windows-874',number_format($abd_amount,2));
				$pdf->MultiCell(50,4,$title,0,'R',0);
				
				if($a==0){
					$total_credit += $abd_amount;
				}
			}	
			
			$cline += 5;
		}
		}
		if($cline>($cline+(6*7))){
			$pdf->AddPage();
			$cline = $Y+6;
		
		for($j=0;$j<=0;$j++){
			switch($j){
				case 0:
					$n = 4;
					$frm_name = "ต้นฉบับ";
					break;
				/*case 1:
					$n = 149;
					$frm_name = "สำเนา";
					break;*/
			}
			//ชื่อบริษัท	
			$pdf->SetFont('AngsanaNew','B',14);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',"บริษัท  ".$comp_name);
			$pdf->MultiCell(120,4,$title,0,'L',0);

			//ต้นฉบับ
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(120,$n);
			$title=iconv('UTF-8','windows-874',$frm_name);
			$pdf->MultiCell(40,7,$title,0,'C',0);

			//รายวันจ่าย	
			$pdf->SetFont('AngsanaNew','B',14);
			$pdf->SetXY(157,$n);
			$title=iconv('UTF-8','windows-874',"รายวันทั่วไป
			JOURNAL VOUCHER");
			$pdf->MultiCell(40,7,$title,1,'C',0);

			$n += 8;	
			//ที่อยู่	
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',$address);
			$pdf->MultiCell(120,4,$title,0,'L',0);

			$n += 8;	
			//รายละเอียด	
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',"รายละเอียด:");
			$pdf->MultiCell(44,4,$title,0,'L',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(25,$n);
			$title=iconv('UTF-8','windows-874',$detail);
			$pdf->MultiCell(60,4,$title,0,'L',0);

			//วันที่	
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(162,$n);
			$title=iconv('UTF-8','windows-874',"วันที่:");
			$pdf->MultiCell(44,4,$title,0,'L',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(175,$n);
			$title=iconv('UTF-8','windows-874',$date);
			$pdf->MultiCell(44,4,$title,0,'L',0);

			$n += 6;
			//เลขที่
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(162,$n);
			$title=iconv('UTF-8','windows-874',"เลขที่:");
			$pdf->MultiCell(44,4,$title,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(175,$n);
			$title=iconv('UTF-8','windows-874',$number);
			$pdf->MultiCell(44,4,$title,0,'L',0);
			
			$n += 6;
			//เลขที่บันทึกบัญชี
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(125,$n);
			$title=iconv('UTF-8','windows-874',"เลขที่บันทึกบัญชี:");
			$pdf->MultiCell(44,4,$title,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(170,$n);
			$title=iconv('UTF-8','windows-874',$abh_id);
			$pdf->MultiCell(44,4,$title,0,'L',0);

			$n += 6;
			//เส้นคัน
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',$line);
			$pdf->MultiCell(200,4,$title,0,'L',0);

			$n += 4;
			//รหัสบัญชี
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(15,$n);
			$title=iconv('UTF-8','windows-874',"รหัสบัญชี
			Account");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			//รายการ
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(80,$n);
			$title=iconv('UTF-8','windows-874',"รายการ
			Paticulsrs");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			//เดบิต
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(150,$n);
			$title=iconv('UTF-8','windows-874',"เดบิต
			Dabit");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			//เครดิต
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(180,$n);
			$title=iconv('UTF-8','windows-874',"เครดิต
			Credit");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			$n += 6;
			//เส้นคัน
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',$line);
			$pdf->MultiCell(200,4,$title,0,'L',0);

			if($j==0){$n = 102;}else{$n = 247;}
			//หมายเหตุ
			$pdf->SetFont('AngsanaNew','U',10);
			$pdf->SetXY(35,$n);
			$title=iconv('UTF-8','windows-874',"หมายเหตุ :");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(47,$n);
			$title=iconv('UTF-8','windows-874',$remak);
			$pdf->MultiCell(200,4,$title,0,'L',0);

			if($j==0){$n = 104;}else{$n = 249;}
			//เส้นคัน
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',$line);
			$pdf->MultiCell(200,4,$title,0,'L',0);

			$n += 4;
			//Total
			$pdf->SetXY(120,$n);
			$title=iconv('UTF-8','windows-874',"TOTAL");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			//เดบิต
			$pdf->SetXY(115,$n);
			$title=iconv('UTF-8','windows-874',number_format($total_debit,2));
			$pdf->MultiCell(50,4,$title,0,'R',0);

			//เครดิต
			$pdf->SetXY(147,$n);
			$title=iconv('UTF-8','windows-874',number_format($total_credit,2));
			$pdf->MultiCell(50,4,$title,0,'R',0);

			$n += 2;
			//เส้นคัน
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',$line);
			$pdf->MultiCell(200,4,$title,0,'L',0);

			$n += 10;
			$pdf->SetXY(15,$n);
			$title=iconv('UTF-8','windows-874',"_______________________");
			$pdf->MultiCell(200,4,$title,0,'L',0);


			$pdf->SetXY(90,$n);
			$title=iconv('UTF-8','windows-874',"_______________________");
			$pdf->MultiCell(200,4,$title,0,'L',0);


			$pdf->SetXY(160,$n);
			$title=iconv('UTF-8','windows-874',"_______________________");
			$pdf->MultiCell(200,4,$title,0,'L',0);

			$n += 6;
			$pdf->SetXY(5,$n);
			$title=iconv('UTF-8','windows-874',$doer);
			$pdf->MultiCell(50,4,$title,0,'C',0);

			//เดบิต
			$pdf->SetXY(8,$n);
			$title=iconv('UTF-8','windows-874',$appv);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			//เครดิต
			$pdf->SetXY(78,$n);
			$title=iconv('UTF-8','windows-874',$audit);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$n += 6;
			$pdf->SetXY(5,$n);
			$title=iconv('UTF-8','windows-874',"ผู้ทำรายการ
			Financial office");
			$pdf->MultiCell(50,4,$title,0,'C',0);
	

			$pdf->SetXY(8,$n);
			$title=iconv('UTF-8','windows-874',"ผู้อนุมัติ
			Authorized By");
			$pdf->MultiCell(200,4,$title,0,'C',0);


			$pdf->SetXY(78,$n);
			$title=iconv('UTF-8','windows-874',"ผู้ตรวจสอบ
			Checked By");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$n += 8;
			$pdf->SetXY(5,$n);
			$title=iconv('UTF-8','windows-874',$doer_stamp);
			$pdf->MultiCell(50,4,$title,0,'C',0);


			$pdf->SetXY(8,$n);
			$title=iconv('UTF-8','windows-874',$appv_stamp);
			$pdf->MultiCell(200,4,$title,0,'C',0);


			$pdf->SetXY(78,$n);
			$title=iconv('UTF-8','windows-874',$audit_stamp);
			$pdf->MultiCell(200,4,$title,0,'C',0);
			
			if($j==0){
			$n += 4;
			//เส้นคัน
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(10,$n);
			$title=iconv('UTF-8','windows-874',$lineCut);
			$pdf->MultiCell(200,4,$title,0,'L',0);
			}
		}
		
		}
	} //end while detail
		
//----------------- pdf ----------------------------------

for($k=0;$k<=0;$k++){
	switch($k){
		case 0:
			$Y = 4;
			$frm_name = "ต้นฉบับ";
			break;
		/*case 1:
			$Y = 149;
			$frm_name = "สำเนา";
			break;*/
	}	
//ชื่อบริษัท	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',"บริษัท  ".$comp_name);
$pdf->MultiCell(120,4,$title,0,'L',0);

//ต้นฉบับ
$pdf->SetFont('AngsanaNew','U',18);
$pdf->SetXY(120,$Y);
$title=iconv('UTF-8','windows-874',$frm_name);
$pdf->MultiCell(40,7,$title,0,'C',0);

//รายวันจ่าย	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(157,$Y);
$title=iconv('UTF-8','windows-874',"รายวันทั่วไป
JOURNAL VOUCHER");
$pdf->MultiCell(40,7,$title,1,'C',0);

$Y += 8;	
//ที่อยู่	
$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(120,4,$title,0,'L',0);

$Y += 8;	
//รายละเอียด	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',"รายละเอียด:");
$pdf->MultiCell(44,4,$title,0,'L',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$detail);
$pdf->MultiCell(60,4,$title,0,'L',0);

//วันที่	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(125,$Y);
$title=iconv('UTF-8','windows-874',"วันที่:");
$pdf->MultiCell(44,4,$title,0,'R',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$date);
$pdf->MultiCell(44,4,$title,0,'L',0);

$Y += 6;
//เลขที่
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(125,$Y);
$title=iconv('UTF-8','windows-874',"เลขที่:");
$pdf->MultiCell(44,4,$title,0,'R',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$number);
$pdf->MultiCell(44,4,$title,0,'L',0);

$Y += 6;
//เลขที่บันทึกบัญชี
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(125,$Y);
$title=iconv('UTF-8','windows-874',"เลขที่บันทึกบัญชี:");
$pdf->MultiCell(44,4,$title,0,'R',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$abh_id);
$pdf->MultiCell(44,4,$title,0,'L',0);

$Y += 6;
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 4;
//รหัสบัญชี
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(15,$Y);
$title=iconv('UTF-8','windows-874',"รหัสบัญชี
Account");
$pdf->MultiCell(200,4,$title,0,'L',0);

//รายการ
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(80,$Y);
$title=iconv('UTF-8','windows-874',"รายการ
Paticulsrs");
$pdf->MultiCell(200,4,$title,0,'L',0);

//เดบิต
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(150,$Y);
$title=iconv('UTF-8','windows-874',"เดบิต
Dabit");
$pdf->MultiCell(200,4,$title,0,'L',0);

//เครดิต
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(180,$Y);
$title=iconv('UTF-8','windows-874',"เครดิต
Credit");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
if($k == 1){$start_1 = $Y;}else{$start_2 = $Y;}
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'L',0);

//---------------------------- รายการ -------------------------------------
			
//---------------------------- จบรายการ -------------------------------------

if($k==0){$Y = 102;}else{$Y = 247;}
//หมายเหตุ
$pdf->SetFont('AngsanaNew','U',10);
$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"หมายเหตุ :");
$pdf->MultiCell(200,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(47,$Y);
$title=iconv('UTF-8','windows-874',$remak);
$pdf->MultiCell(200,4,$title,0,'L',0);

if($k==0){$Y = 104;}else{$Y = 249;}
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 4;
//Total
$pdf->SetXY(120,$Y);
$title=iconv('UTF-8','windows-874',"TOTAL");
$pdf->MultiCell(200,4,$title,0,'L',0);

//เดบิต
$pdf->SetXY(115,$Y);
$title=iconv('UTF-8','windows-874',number_format($total_debit,2));
$pdf->MultiCell(50,4,$title,0,'R',0);

//เครดิต
$pdf->SetXY(147,$Y);
$title=iconv('UTF-8','windows-874',number_format($total_credit,2));
$pdf->MultiCell(50,4,$title,0,'R',0);

$Y += 2;
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'L',0);

//หาลายเซ็นของเจ้าหน้าที่ ผู้จ่าย 
$qry_fuser_detail = pg_query("select \"u_sign\" from  \"fuser_detail\" 
 where \"id_user\" = '$doerid_sign' ");
 
$pathu_sign = pg_fetch_result($qry_fuser_detail,0);
$Y_img=$Y;
if($pathu_sign !=""){
	$pathu_sign='../upload/sign/'.$pathu_sign;
	$pdf->Image($pathu_sign,15,4+$Y_img,30,15);
}

$Y += 10;
$pdf->SetXY(15,$Y);
$title=iconv('UTF-8','windows-874',"_______________________");
$pdf->MultiCell(200,4,$title,0,'L',0);

//หาลายเซ็นของเจ้าหน้าที่ ผู้อนุมัติ 
$qry_fuser_detailappv = pg_query("select \"u_sign\" from  \"fuser_detail\" 
 where \"id_user\" = '$appvID_sign' ");
 
$pathu_sign_appv = pg_fetch_result($qry_fuser_detailappv,0);
if($pathu_sign_appv !=""){
	$pathu_sign_appv='../upload/sign/'.$pathu_sign_appv;
	$pdf->Image($pathu_sign_appv,90,4+$Y_img,30,15);
}

$pdf->SetXY(90,$Y);
$title=iconv('UTF-8','windows-874',"_______________________");
$pdf->MultiCell(200,4,$title,0,'L',0);

//หาลายเซ็นของเจ้าหน้าที่ ผู้ตรวจ
$qry_fuser_detailaudit = pg_query("select \"u_sign\" from  \"fuser_detail\" 
 where \"id_user\" =  '$auditID_sign' ");
 
$pathu_sign_audit = pg_fetch_result($qry_fuser_detailaudit,0);
if($pathu_sign_audit !=""){
	$pathu_sign_audit='../upload/sign/'.$pathu_sign_audit;
	$pdf->Image($pathu_sign_audit,160,4+$Y_img,30,15);
}

$pdf->SetXY(160,$Y);
$title=iconv('UTF-8','windows-874',"_______________________");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(5,$Y);
$title=iconv('UTF-8','windows-874',$doer);
$pdf->MultiCell(50,4,$title,0,'C',0);

//เดบิต
$pdf->SetXY(8,$Y);
$title=iconv('UTF-8','windows-874',$appv);
$pdf->MultiCell(200,4,$title,0,'C',0);

//เครดิต
$pdf->SetXY(78,$Y);
$title=iconv('UTF-8','windows-874',$audit);
$pdf->MultiCell(200,4,$title,0,'C',0);

$Y += 6;
$pdf->SetXY(5,$Y);
$title=iconv('UTF-8','windows-874',"ผู้ทำรายการ
Financial office");
$pdf->MultiCell(50,4,$title,0,'C',0);


$pdf->SetXY(8,$Y);
$title=iconv('UTF-8','windows-874',"ผู้อนุมัติ
Authorized By");
$pdf->MultiCell(200,4,$title,0,'C',0);


$pdf->SetXY(78,$Y);
$title=iconv('UTF-8','windows-874',"ผู้ตรวจสอบ
Checked By");
$pdf->MultiCell(200,4,$title,0,'C',0);

$Y += 8;
$pdf->SetXY(5,$Y);
$title=iconv('UTF-8','windows-874',$doer_stamp);
$pdf->MultiCell(50,4,$title,0,'C',0);


$pdf->SetXY(8,$Y);
$title=iconv('UTF-8','windows-874',$appv_stamp);
$pdf->MultiCell(200,4,$title,0,'C',0);


$pdf->SetXY(78,$Y);
$title=iconv('UTF-8','windows-874',$audit_stamp);
$pdf->MultiCell(200,4,$title,0,'C',0);

	if($k==0){
	$Y += 4;
	//เส้นคัน
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(10,$Y);
	$title=iconv('UTF-8','windows-874',$lineCut);
	$pdf->MultiCell(200,4,$title,0,'L',0);
	}
}

} //end all
$pdf->Output();	
if($status == 0){
	pg_query("COMMIT");
	
}else{
	pg_query("ROLLBACK");		
}
?>