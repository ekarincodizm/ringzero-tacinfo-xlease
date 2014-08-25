<?php
session_start();



require_once("../../sys_setup.php");
include("../../../../../config/config.php");
include("../../fpdf16/fpdf_writehtml.php");
$datepicker = $_GET['datepicker'];
$ty= $_GET['ty'];
$ty2 = $_GET['ty2'];

// สร้าง pdf ตาม ข้อมูล -------------------------------------------------------------------
$pdf=new PDF('L', 'mm', 'A4');

list($yy,$mm,$dd) = explode('-',$datepicker,3);

if($ty ==1){ $cc="ประจำวันที่ $datepicker ";}//จากวันที่อนุมัติ
			else if($ty ==2){ $cc="ประจำเดือน ".translate_month($mm)." $yy";}//จากวันที่ขอลด
			
			
			if($ty2 ==1){ $sql_ty2 = " and f.approve_dt::text like '$datepicker%' ";$bb="ค้นหาจาก วันที่อนุมัติ";}//จากวันที่อนุมัติ
			else if($ty2 ==2){ $sql_ty2 = " and f.\"O_DATE\"::text like '$datepicker%' ";$bb="ค้นหาจาก วันที่ขอส่วนลด";}//จากวันที่ขอลด
				
			$qry_fr=pg_query("SELECT m.car_license,f.\"O_RECEIPT\",f.\"O_MONEY\",cpro_name,m.idno,f.approve_status,f.create_by,f.create_datetime,f.\"O_memo\",f.approver,f.approve_dt 
			FROM \"FOtherpayDiscount\" f left join \"VJoinMain\" m on m.idno = f.\"IDNO\" WHERE m.deleted ='0' and m.car_license_seq = 0
			 and f.approve_status=1 $sql_ty2 order by f.create_datetime ");	
			 

				
			$nub=pg_num_rows($qry_fr);
				if($nub==0){
			$error ='ไม่พบข้อมูล';
			}
		$num_page_all = ceil($nub/25);
		
		$j=1;
		$k = 26;
		$y=1;
		$i =1;
		$sum=0;
		
		
		
			while($sql_row4=pg_fetch_array($qry_fr)){
				$cpro_name = $sql_row4['cpro_name'];
					$O_RECEIPT = $sql_row4['O_RECEIPT'];
					$car_license = $sql_row4['car_license'];
					$create_datetime =$sql_row4['create_datetime']; 
					$reason =$sql_row4['O_memo']; 
					//$approve_status = $sql_row4['approve_status'];
					$O_MONEY =$sql_row4['O_MONEY']; 
					$create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
					$idno = trim($sql_row4['idno']);
					$approver = $sql_row4['approver'];
					$approve_dt = $sql_row4['approve_dt'];

		$dt = $create_datetime;
			$by = $create_by;
		//if($approve_status==0)$st="รอการอนุมัติ";else $st="ไม่อนุมัติ";
		
		
					$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
   $res_userprofile=pg_fetch_array($res_profile);
   $by=  $res_userprofile["fullname"];
   
   $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$approver'");
   $res_userprofile=pg_fetch_array($res_profile);
   $approver=  $res_userprofile["fullname"];

					
				if(($y%$k==0 || $i==1)){
					$pdf->AddPage();
$pdf->AddFont('AngsanaNew','','angsa.php');
$pdf->AddFont('AngsanaNew','B','angsab.php');
$pdf->AddFont('AngsanaNew','I','angsai.php');
$pdf->SetFont('AngsanaNew','',14);
		if($y%$k==0){
		
			$y=1;
		}
						
$text =  '
<table border="0">
<tr >
		<td width="1035" ><FONT SIZE=+3>รายงานส่วนลดเข้าร่วม</FONT> </td>
		';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','',11);	
		$text= '<td width="100"  align="left">หน้าที่ '.$j.'/'.$num_page_all.'</td>
</tr>
<tr>
		<td width="400"  align="left"><font color=#FFFFFF>.</font></td>
		<td width="100"  align="left">'.$bb.' : '.$cc.' </td>
		<td width="450"  align="left" ><font color=#FFFFFF>.</font></td>
		<td width="100"  align="left" >วันที่พิมพ์ '.date('Y-m-d H:i:s').'</td>

</tr>
<tr>
		<td width="25"  align="left"><font color=#FFFFFF>.</font></td>
		<td width="325"  align="left">มีทั้งหมด '.$nub.' รายการ</td>

		<td width="100"  align="left"></td>
		<td width="300"  align="left">'.$aa.'</td>
		

</tr>
</table>';
		$pdf->WriteHTML($text);								
										
	$pdf->SetFont('AngsanaNew','',10);									
$text= '<table class="t2" border="1" cellSpacing="1" cellPadding="3" align="left" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="CENTER">
            <td width="50" align="CENTER">ลำดับ</td>
			<td width="100" align="CENTER">เลขที่สัญญา</td>
			<td width="60" align="CENTER">ทะเบียนรถ</td>
			<td width="150" align="CENTER">ชื่อลูกค้า</td>
			<td width="70" align="CENTER">จำนวนเงิน</td>
			<td width="150" align="CENTER">ผู้ขอลด</td>
			<td width="85" align="CENTER">วันเวลาที่ขอ</td>
			<td width="150" align="CENTER">ผู้อนุมัติ</td>
			<td width="85" align="CENTER">วันเวลาที่อนุมัติ</td>
			<td width="150" align="CENTER">เหตุผล</td></tr>';
			
				$pdf->WriteHTML($text);
$j++ ;	
}
		if($reason=="")$reason="-";
//echo $i;
//$amount_wo_dis =$amount_total-$amount;
$text='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<tr>
<td width="50" align="CENTER">'.$i.'</td>
<td  align="left" width="100" >'.$idno.'</td>
<td width="60" align="CENTER">'.$car_license.'</td>
<td width="150" align="left">'.$cpro_name.'</td>
<td width="70" align="RIGHT">'.number_format($O_MONEY,2).'</td>
<td width="150" align="left">'.$by.'</td>
<td width="85" align="CENTER">'.$dt.'</td>
<td width="150" align="left">'.$approver.'</td>
<td width="85" align="CENTER">'.$approve_dt.'</td>
<td width="150" align="left">'.$reason.'</td></tr>';
		$pdf->WriteHTML($text);
				/*	if(($i%25)==0){
$text.='
<tr>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<td width="735" align="left" colspan="6" ><font color=#FFFFFF>.</font></td>
		<td width="150" align="right">หน้าที่ '.$j.'</td>
</tr>';
						$j++ ;
					}*/
			 $y++;$i+=1;	
			//$sum_total = $sum_total+$amount_total;
			//$sum_wo_dis = $sum_wo_dis+$amount_discount;
			$sum += $O_MONEY;
			
				//$amount_wo_dis=0;
} 			if($error==''){
$text='


<tr>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<td width="360" align="RIGHT" ><H1 align="CENTER">จำนวนเงินรวม : </H1> </td>

		<td width="70" align="RIGHT" scope="col">'.number_format($sum,2).'</td>
</tr>
</table>
'; }else {
	$text .=$error ;
}
//echo $text ;
$pdf->WriteHTML($text);
$pdf->Output();
?>