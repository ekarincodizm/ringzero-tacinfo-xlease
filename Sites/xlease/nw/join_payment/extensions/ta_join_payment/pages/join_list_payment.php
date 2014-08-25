<?php

  $query = "SELECT * FROM ta_join_payment WHERE id_main='$id2' and deleted='0' and car_license_seq='$car_license_seq'  ORDER BY period_date,expire_date,pay_date,id asc ";
		
				$sql_query = pg_query($query);
				$nn = pg_num_rows($sql_query);
	if($nn!=0){
	?>
<p><strong>ประวัติการชำระเงินค่าเข้าร่วม</strong></p>
<?php
		$query15 = "SELECT ta_join_payment_id,period_date,expire_date,change_pay_type FROM ta_join_payment WHERE id_main='$id2' and deleted ='0' and car_license_seq='$car_license_seq'  order by period_date,expire_date,pay_date,id asc ";
	
		$sql_query15 = pg_query($query15);
		$num_rows15 = pg_num_rows($sql_query15);
		$er01=0;
		$n_ck = 0;
		$n_ch = 0;
		if($num_rows15>0){
			while($sql_row15 = pg_fetch_array($sql_query15))
				{
			
			$change_pay_type =	$sql_row15[change_pay_type];
			$expire_date =	$sql_row15[expire_date];
					if($expire_date==''  && $n_ck==0){
				//เมื่อ ไม่มี วันหมดอายุ ให้ไปดึง เดือนที่เริ่มชำระ - 1
				$exp_new3=pg_query("select join_date_diff_month('$start_pay_date2',1)");
            $expire_date=@pg_fetch_result($exp_new3,0);
			}
			$period_date =	$sql_row15[period_date];
			if($exp_old==""){
				
			$exp_old = $expire_date ;
			}
			
			//if($change_pay_type!=1){
				
			
			$exp_new2=pg_query("select join_date_add_month('$exp_old',1)");
            $exp_new=@pg_fetch_result($exp_new2,0);
				
		/*	}else{
				$n_ch=1;
				$exp_new = $exp_old;
				
			}
*/
if($change_pay_type==1){
	$n_ch=1;
	
}
	
			if($period_date!=$exp_new && $n_ck!=0){//ตั้งแต่ ปัจุบัน != ถึงของอันก่อน + 1 และไม่ทำครั้งแรก
			//echo $n_ck." ".$period_date." ".$exp_new."<br>";
				$er01 ++;
				}
				
			$exp_old = $expire_date ;
			$n_ck++;
			
			
			
				}
				$er01 -= $n_ch; //ถ้า จ่ายแรกเข้า 5000 วันที่จะเท่ากัน ให้ - err ไป 1
				if($er01>0){
		?>

<h2><font color=red>ระวัง! การจ่ายมีการกระโดดข้ามเดือน โปรดแจ้งฝ่ายไอที</font></h2>
<?php } } ?>
<table border="0" align="center" cellpadding="1" cellspacing="1" >

  <tr bgcolor="#CCCCFF">
    <td bgcolor="#66CCFF" height="30px" ><div align="center">&nbsp;ลำดับ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;เลขที่ใบเสร็จ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ทะเบียนรถ&nbsp;</div></td>
   <td bgcolor="#66CCFF"><div align="center">&nbsp;สัญญาเลขที่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ชื่อลูกค้า&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;วันที่ชำระเงิน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ประเภทชำระ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ตั้งแต่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ใช้ได้ถึง&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวนเดือน&nbsp;</div></td>
  

    <td bgcolor="#66CCFF" ><div align="center">&nbsp;หมายเหตุ &nbsp;</div></td>
  </tr>
  <?php

				
	$i=1;
	$type = 300 ;
	$next_m = 1;
	$ck_amount=0;
	$ck_amount2=0;
	
	
				while($sql_row = pg_fetch_array($sql_query))
				{		
				
				$change_pay_type = $sql_row[change_pay_type];
				
				if($config==1){
				$period_date55 =	$sql_row[period_date];
				$expire_date55  =	$sql_row[expire_date];
				$amount_month55 =	$sql_row[amount_month];
				$pay_type55=	$sql_row[pay_type];
				}
				$ta_join_payment_id = $sql_row[ta_join_payment_id];
				if($change_pay_type==1 && $ck_amount!=1){
						$type = 100 ;	
						
						}	
				
					$pay_type = $sql_row[pay_type];	
				if($pay_type=='0'){
					$pay_type = "300/เดือน";
					$pay_type = $type."/เดือน";
					
						if($change_pay_type==1 && $ck_amount!=1){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}
				}
				else if($pay_type=='1'){
				if($change_pay_type==1 && $ck_amount!=1){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}else{
				$pay_type = "100/เดือน";
				$pay_type = $type."/เดือน";
						}
				}
				else if($pay_type=='2'){
				
					
						if($change_pay_type==1 && $ck_amount!=1){
							$pay_type  = "<font color=red>SP(ค่าเข้าร่วม)</font>" ;
					
						}else {
							$pay_type = "SP(".$type.")";
						}
				}$id =	$sql_row[id];
						$pay_date =	$sql_row[pay_date];
					$pay_date = date_ch_form_c($pay_date);
					
					//$expire_date =	$sql_row[expire_date];
					//$expire_date = date_ch_form_m($expire_date);
					if($change_pay_type==1 && $ck_amount!=1){
						$next_m = 0 ;}
						else {
							$next_m = 1;
						}
	
			 $discount = $sql_row[amount_discount];
			 if($discount!=0 && $change_pay_type ==1){
				 
			 $amount_show =  number_format($sql_row[amount])."<font color=red>*</font>";
			 }else {
				 $amount_show =  number_format($sql_row[amount]); 
			 }
		if($config==1){
			$period_date = date_ch_form_m($period_date55);
			 $expire_date_pay = date_ch_form_m($expire_date55);
				$month_pay =	$amount_month55;

		

						
				
if($pay_type55=='1'){
	$pay_type = "100/เดือน";
}else if($pay_type55=='0'){
	$pay_type = "300/เดือน";
}else{
		$pay_type = "SP(".$type.")";
}

								if($change_pay_type==1 && $ck_amount55!=1){ //5000 ครั้งแรก
						if($pay_type55=='2'){
							$pay_type  = "<font color=red>SP(ค่าเข้าร่วม)</font>" ;
							
						}else{
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
						}
					
						}
						if($change_pay_type==1){
							$ck_amount55 = 1;
						}
						
						
}
$qry_vcus=pg_query("select \"PayType\",\"O_BANK\" from \"FOtherpay\" WHERE  \"O_RECEIPT\"='".$sql_row[ta_join_payment_id]."' ");

$rows = pg_num_rows($qry_vcus);
if($rows > 0){
if($resvc=pg_fetch_array($qry_vcus)) {
	$note2 = " / ".$resvc['PayType'];
	$O_BANK = $resvc['O_BANK'];
}
}else{
	
	$qry_vcus=pg_query("select \"PayType\",\"O_memo\" from \"FOtherpayDiscount\" WHERE  \"O_RECEIPT\"='".$sql_row[ta_join_payment_id]."' ");

$rows = pg_num_rows($qry_vcus);
if($rows > 0){
if($resvc=pg_fetch_array($qry_vcus)) {
	$note2 = "ส่วนลด ".$resvc['O_memo'];
	
}
}
	
	
}

				?>
  <tr>
    <td bgcolor="#EEFBFA" height="30px" ><div align="center"><u><a title="หมายเหตุ" href="javascript:MM_openbrWindow('showDetailCall.php?id=<?php print $sql_row[id] ?>','','scrollbars=no,width=500,height=260, left = 0, top = 0')"><?Php print $i ?></a></u></div></td>
    <td bgcolor="#EEFBFA"><div align=left><u><a href="javascript:popU('ta_join_payment_detail.php?id=<?php print $id ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[ta_join_payment_id] ?></a></u></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><?Php print $sql_row[car_license]; if($sql_row[car_license_seq]!=0) print "/".$sql_row[car_license_seq]; ?></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><u><a href="javascript:popU('../../../../../post/frm_viewcuspayment.php?idno=<?php print $sql_row[idno]  ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[idno] ?></a></u></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><?Php print $sql_row[cpro_name] ?></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><?Php print $pay_date ?></div></td>
    <td bgcolor="#EEFBFA"><div align=left><?Php print $pay_type ?></div></td>
    <td bgcolor="#EEFBFA"><div align=right><?Php print  $amount_show ?></div></td>
    <td bgcolor="#EEFBFA"><div align=left><?Php print $period_date ?></div></td>
    <td bgcolor="#EEFBFA"><div align=left><?Php print $expire_date_pay ?></div></td>
    <td bgcolor="#EEFBFA"><div align=center><?Php print $month_pay ?></div></td>
   
   
    <td bgcolor="#EEFBFA"><div align=left><textarea name="note" style="width:100px;height:20px;" id="note" readonly="readonly" ><?php 
	if($sql_row[pay]!=""){
	print $sql_row[pay].$note2." ".$sql_row[note] ;
}else{
	print $O_BANK.$note2." ".$sql_row[note] ;
}
	/*
	$query7 = "SELECT * FROM ta_join_payment WHERE ta_join_payment_id ='".$sql_row[ta_join_payment_id]."' and  ta_join_payment_id  like 'TAJ-%' ";
	//echo $query7;
		$sql_query7 = pg_query($query7);
		$Num_Rows7 = pg_num_rows($sql_query7);*/
	
		 ?></textarea></div></td>
  </tr>
  <?php 
  
  	if($change_pay_type==1 && $ck_amount==1){
					  
					   $ck_amount2=1;
					}
  

  
  
  
  $i++;
  } ?>
    <tr>
    <td colspan="14"  bgcolor="#EEFBFA"><div align="right"><?php if($rights_ta_join_payment_add && $cancel=='0'){ ?>
      
        <input value="เพิ่มประวัติการชำระ" type="button" name="เพิ่ม" 
				   onclick="javascript:window.open('ta_join_payment_add.php?id=<?php print $id2 ?>&expire_date=<?php 
				   if($expire_date_pay==''){
					   	list($dd5,$mm5,$yy5)=split("/",$start_ta_join_date);
						//$yy5=$yy5-543;	

						$expire_date_pay =  MKTIME(0,0,0,$mm5-1,'01',$yy5) ;
$expire_date_pay = date("m/Y", $expire_date_pay);
  	list($mm6,$yy6)=split("/",$expire_date_pay);
		//$expire_date_pay  = $mm6."/".($yy6+543) ;
		$expire_date_pay  = $mm6."/".($yy6) ;
				   }
				 
				   
				   print $expire_date_pay ?>&pay_type=<?php print $type ?>','_blank')" id="เพิ่ม" /><?php } ?>
    </div></td>
  </tr>
</table>
<p><?php if($error1!=0){
	echo "<h3><font color=red>มีการคำนวนผิดพลาด โปรดระวังเป็นพิเศษ</font></h3>";
}
?>
เริ่มคิดค่าบริการเข้าร่วม วันที่ <?Php print $start_ta_join_date ;if($expire_date_pay!=""){?> ใช้ได้ถึง <?Php print $expire_date_pay ?> <br /><br />
<font color="#9900FF">ยอดค้างชำระ ณ วันที่ <?Php 

list($mm3,$yy3)=split("/",$expire_date_pay);
//$yy3=$yy3-543;
$period =  MKTIME(0,0,0,$mm3, '01', $yy3) ;			
$now =  MKTIME(0,0,0,date("m"), '01', date("Y")) ;
//$now =  MKTIME(0,0,0,'07', '01', '2011') ;
$month = $now-$period;


$month = round($month/60/60/24/30);	
$arrears = ($month*$type);
if($arrears<0){
	$arrears =0;
}

//$yy = date("Y")+543;
$yy = date("Y");
print date("d")."/".date("m")."/".$yy ?> ทั้งหมด <font color="red"> <?Php print number_format($arrears) ?> </font>บาท</font></p>

   <?php }
  $query = "SELECT * FROM ta_join_payment WHERE id_main='$id2' and deleted='1' ORDER BY update_datetime asc ";
				
				$sql_query = pg_query($query);
				$num_row_del = pg_num_rows($sql_query);
				if($num_row_del!=0){
	?>

<strong><font color=red>รายการที่เคยถูกยกเลิก</font></strong><br /><br>
<table border="0" align="center" cellpadding="1" cellspacing="1" >

  <tr bgcolor="#CCCCFF">
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ลำดับ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;เลขที่ใบเสร็จ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ทะเบียนรถ&nbsp;</div></td>
   <td bgcolor="#66CCFF"><div align="center">&nbsp;สัญญาเลขที่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ชื่อลูกค้า&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;วันที่ชำระเงิน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ประเภทการชำระ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ตั้งแต่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ใช้ได้ถึง&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวนเดือน&nbsp;</div></td>
  
    <td colspan="2" bgcolor="#66CCFF"><div align="center">ผู้ยกเลิก</div></td>
  </tr>
   <?php

	$ui = 1;
				while($sql_row = pg_fetch_array($sql_query))
				{	
				$change_pay_type = $sql_row[change_pay_type];
				
				
				$users_del = $sql_row[update_by]; //ใบเสร็จแบบเก่า ใช้ update_by
				if($sql_row[create_by]!=""){ //ใบเสร็จแบบใหม่  แบบเก่า create_by จะไม่มี
					
				$res_profile=pg_query("select postuser from \"CancelReceipt\" where admin_approve='true' and ref_receipt = '".$sql_row[ta_join_payment_id]."' ");
   $res_userprofile=pg_fetch_array($res_profile);
   $users_del=  $res_userprofile["postuser"];

				}
   				$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$users_del'");
   $res_userprofile=pg_fetch_array($res_profile);
   $users_del=  $users_del."-".$res_userprofile["fullname"];
				//list($users_del,$bb,$cc) = split(" ",$users_del);
				
				$pay_date =	$sql_row[pay_date];
					$pay_date = date_ch_form_c($pay_date);
				
					$pay_type = $sql_row[pay_type];	
					
				if($pay_type=='0'){
					$pay_type = "300/เดือน";
					//$pay_type = $type."/เดือน";
					
						if($change_pay_type=='1' ){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}
				}
				else if($pay_type=='1'){
				
				if($change_pay_type=='1' ){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}else{
				$pay_type = "100/เดือน";
				//$pay_type = $type."/เดือน";
				
				
						}
				}
				else if($pay_type=='2'){
				
					
						if($change_pay_type=='1'){
							$pay_type  = "<font color=red>SP(ค่าเข้าร่วม)</font>" ;
					
						}else {
							$pay_type = "SP(".$type.")";
						}
				}
				
				$period_date =	$sql_row[period_date];
				
				if($period_date!=""){
					$period_date = date_ch_form_c($period_date);
					list($dd5,$mm5,$yy5)=split("/",$period_date);	
							 $period_date = $mm5."/".$yy5;
				}
					else $period_date= '-';
					
					$expire_date  =	$sql_row[expire_date];
				if($expire_date !=""){
					
					$expire_date  = date_ch_form_c($expire_date);
					list($dd6,$mm6,$yy6)=split("/",$expire_date);	
							$expire_date = $mm6."/".$yy6;
				}
					else $expire_date= '-';
				
				?>
 
   <tr>
    <td bgcolor="#F4F4F4"><div align="center"><u><a title="หมายเหตุ" href="javascript:MM_openbrWindow('showDetailCall.php?id=<?php print $sql_row[id] ?>','','scrollbars=no,width=500,height=260, left = 0, top = 0')"><?Php print $ui ?></a></u></div></td>
     <td bgcolor="#F4F4F4"><div align=left><u><a href="javascript:popU('ta_join_payment_detail.php?id=<?php print $sql_row[id] ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[ta_join_payment_id] ?></a></u></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $sql_row[car_license]; if($sql_row[car_license_seq]!=0) print "/".$sql_row[car_license_seq]; ?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><u><a href="javascript:popU('../../../../../post/frm_viewcuspayment.php?idno=<?php print $sql_row[idno] ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[idno] ?></a></u></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $sql_row[cpro_name] ?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $pay_date ?></div></td>
    <td bgcolor="#F4F4F4"><div align=left><?Php print $pay_type ?></div></td>
    <td bgcolor="#F4F4F4"><div align=right><?Php print number_format($sql_row[amount])?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $period_date ?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $expire_date ?></div></td>
    <td bgcolor="#F4F4F4"><div align=center><?Php print $sql_row[amount_month] ?></div></td>
   
    <td bgcolor="#F4F4F4"><div align="left"><?Php print $users_del ?></div></td>
  </tr>
  <?php $ui++;} ?>
  
  </table><br />
<?php }}else {echo "ไม่มีประวัติการชำระเงินค่าเข้าร่วม";}   ?>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function MM_openbrWindow(theURL,winName,features) { 
	window.open(theURL,winName,features);
}
</script>
