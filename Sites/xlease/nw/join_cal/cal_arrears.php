<?php
session_start();
		session_register('amount');
		session_register('expire_date');
		session_register('pay_type');
		session_register('amount_month');
		session_register('pay_ar');
		session_register('change_pay_type');
		session_register('change_expire_date');
		unset($_SESSION['pay_ar']);
		unset($_SESSION['change_pay_type']);
		unset($_SESSION['change_expire_date']);


include("function_join.php");



//$show_con = $_REQUEST["show_con"];
if($_POST['add_new']=='new'){
	$expire_date =$_POST['start_ta_join_date'];
	$n=1;
}
else {
 $expire_date = $_POST['expire_date'];
 $n=0;
}

list($yy,$mm,$dd)=split("-",$expire_date);	
//$yy=$yy-543;


$car_month = $_POST['car_month'];
$update_m = $_POST['update_m'];
 $change_pay_type=$_POST['change_pay_type'];
  $pay_type =$_POST['pay_type'];
 
    $arrears =$_POST['arrears'];
	
	$arrears = str_replace(',','',$arrears);
	
	
	if($change_pay_type==1)
	$arrears = $arrears-5000 ;
	
	
$amount = $_POST['amount'];
$amount = str_replace(',','',$amount);
if($amount=='')
	echo "กรุณาระบุจำนวนเงินที่ชำระ";
else{
			if($update_m=='1'){
				
			
				
				if($pay_type==0){
					$_SESSION['pay_type']='0';
					$return = $amount%300;
	//$amount = $amount+5000 ;  //****************************
			$amount_month = floor($amount/300) ;
			$payt = "300/เดือน";
				}else {
					$_SESSION['pay_type']='1';
					$return = $amount%100;
				$amount_month = floor($amount/100) ;
				$payt = "100/เดือน";
				}	
				$amount_net = $amount-$return ;
											$_SESSION['change_expire_date']=$expire_date;
										    $expire_date =  MKTIME(0,0,0,$mm+$amount_month-$n,01,$yy) ;
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount;
									        $_SESSION['change_pay_type']=0;
											$_SESSION['expire_date']=$expire_date;
											
										
											$_SESSION['amount_month']=$amount_month;
							
												
				if($change_pay_type==0){
					$amount = number_format($amount);		
				$amount_net = number_format($amount_net);	
				
		
											
										
														
												$result = "<br>
                                           
												<table border=1 cellpadding=0 cellspacing=0>
                           <tr>
                            <td><div align=left>รูปแบบการชำระเงิน</div></td>
                            <td><div align=right>เงินปรับปรุง</div></td>
                            <td><div align=left>$payt</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
							<tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>$amount_net</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						   
							 <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                        </table>
							"; }else{
								
								$_SESSION['change_pay_type']=1;
		$return = $amount%5000;
		$return = number_format($return);	
					$amount = number_format($amount);	
								$result = "<br>
                                           
												<table border=1 cellpadding=0 cellspacing=0>
												<tr>
                            <td><div align=left>เปลี่ยนประเภทการชำระเงินเป็นแบบ</div></td>
                            <td><div align=right>100 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                           <tr>
                            <td><div align=left>รูปแบบการชำระเงิน</div></td>
                            <td><div align=right>เงินปรับปรุง</div></td>
                            <td><div align=left>&nbsp;</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
							
						  
							 <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                        </table>
							";	
								
							}
								 }else{
if($change_pay_type==1 && $pay_type==0){
	//$amount = $amount+5000 ;  //****************************
			if($amount>=5000){
				if($arrears==0){ //ไม่มีค่าค้างชำระ กดเปลี่ยน 5000
				$change=$amount-5000;
						
						$amount_month = floor($change/100) ;
						$return = $change%100;
											$_SESSION['change_expire_date']=$expire_date;
										    $expire_date =  MKTIME(0,0,0,$mm+$amount_month-$n,01,$yy) ;
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount_net;
											$_SESSION['expire_date']=$expire_date;
											$_SESSION['pay_type']='1';
											$_SESSION['amount_month']=$amount_month;
											$_SESSION['change_pay_type']=1;
												
											$amount = number_format($amount);	
											$change = number_format($change);
											$return = number_format($return);
											
												$result = "
                                                
												<table border=1 cellpadding=0 cellspacing=0>
                          <tr>
                            <td><div align=left>เปลี่ยนประเภทการชำระเงินเป็นแบบ</div></td>
                            <td><div align=right>100 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						
                          <tr>
                            <td><div align=left>หักค่าเปลี่ยนประเภทการชำระ</div></td>
                            <td><div align=right><font color=red>5,000 </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>คงเหลือ</div></td>
                            <td><div align=right><font color=red>$change </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>$change</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                            <td colspan=2><div align=center><font color=red>$expire_date</font></div></td>
                            
                          </tr>
                        </table>
							"; 
						
				
				
				}else {//มีค่าค้างชำระ //กดเปลี่ยน 5000บาท
					$remain = $amount-$arrears;//ลบค่าเปลี่ยน 5000 เดือนนั้น จากเดิม 300 เป็น 100
					
					if($remain>=5000){ //เกิน 5000
				$change=$remain-5000;
				
									        $arrears2 = $arrears-100;
											$change2 = $change+100;
										
											$amount_month = floor($change2/100) ;
											$amount_month1 = floor($arrears/300) ;
											$amount_month2 =$amount_month+$amount_month1;
										
											$return = $change%100;
											$_SESSION['change_expire_date']=$expire_date;
											$expire_date =  MKTIME(0,0,0,$mm+$amount_month2-$n,01,$yy) ;
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount_net;
											$_SESSION['expire_date']=$expire_date;
											$_SESSION['pay_type']='1';
											$_SESSION['amount_month']=$amount_month+1;
											$_SESSION['change_pay_type']=1;
											$_SESSION['ar_ch']=1;
											$_SESSION['change']=$change2;
											$_SESSION['arrears']=$arrears2;
											
											$amount = number_format($amount);											
											$arrears = number_format($arrears);
											$change = number_format($change);
											$return = number_format($return);
											//$amount_month = $amount_month+1;
												$result = "
                                           
												<table border=1 cellpadding=0 cellspacing=0>
                          <tr>
                            <td><div align=left>เปลี่ยนประเภทการชำระเงินเป็นแบบ</div></td>
                            <td><div align=right>100 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						  <tr>
                            <td><div align=left>หักค่าค้างชำระ</div></td>
                            <td><div align=right><font color=red>$arrears2</font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>หักค่าเปลี่ยนประเภทการชำระ</div></td>
                            <td><div align=right><font color=red>5,000 </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>คงเหลือ</div></td>
                            <td><div align=right><font color=red>".number_format($change2)." </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                           <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>".number_format($change2)."</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>";
						  if($return!=0){$result .= "
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>";
						  }$result .= "
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                            <td colspan=2><div align=center><font color=red>$expire_date</font></div></td>
                            
                          </tr>
                        </table>
							"; 
											
										
						}
						else {//มีค่าค้างชำระ และกดเปลี่ยน  แต่มีไม่ถึง 5000
										if($update_m=='1'){
											$_SESSION['amount']=$amount;
										}else{
											$_SESSION['amount']='0';
										}
											$_SESSION['expire_date']=$expire_date;
											if($amount==5000){
											$_SESSION['pay_type']='1';
											}else{
												$_SESSION['pay_type']='0';
											}
											$_SESSION['amount_month']='0';
							
											$amount = number_format($amount);											
											$arrears = number_format($arrears);
											$change = number_format($change);
											$return = number_format($return);
											$remain = number_format($remain);
														if($update_m=='1'){
												$result = "<br>
                                           
												<table border=1 cellpadding=0 cellspacing=0>
                           <tr>
                            <td><div align=left>รูปแบบการชำระเงิน</div></td>
                            <td><div align=right>เงินปรับปรุง</div></td>
                            <td><div align=left>&nbsp;</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
					
						   

                        </table>
							";  }else{
												$result = "
                              <font size=3 color=red>จำนวนเงินสุทธิไม่ถึง 5,000 บาท  <br>
							ไม่เพียงพอที่จะเปลี่ยนประเภทการชำระเงิน</font><br><br>          
												<table border=1 cellpadding=0 cellspacing=0>
                      
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
							 <tr>
                            <td><div align=left>หักค่าค้างชำระ</div></td>
                            <td><div align=right><font color=red>$arrears</font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                   			 <tr>
                            <td><div align=left>คงเหลือสุทธิ</div></td>
                            <td><div align=right><font color=red>$remain </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						  
                         
                        </table><br> 
						  <font size=3 color=red>ไม่สามารถที่จะเปลี่ยนประเภทการชำระเงินได้  <br>
							"; 
							}
						}
				}
				
			}else{//กดเปลี่ยน แต่เงินไม่ถึง 5000
				//$remain = $amount-$arrears;
				//if($remain<0)
				if($update_m=='1'){
											$_SESSION['amount']=$amount;
										}else{
											$_SESSION['amount']='0';
										}
											
											$_SESSION['expire_date']=$expire_date;
											if($amount==5000){
											$_SESSION['pay_type']='1';
											}else{
												$_SESSION['pay_type']='0';
											}
											$_SESSION['amount_month']='0';
											$amount = number_format($amount);											
											$arrears = number_format($arrears);											
											
											$remain = $amount;
											if($update_m=='1'){
												$result = "<br>
                                           
												<table border=1 cellpadding=0 cellspacing=0>
                         <tr>
                            <td><div align=left>รูปแบบการชำระเงิน</div></td>
                            <td><div align=right>เงินปรับปรุง</div></td>
                            <td><div align=left>&nbsp;</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
					
						   

                        </table>
							";  }else{
												$result = "<br>
                               <font size=3 color=red>จำนวนเงินสุทธิไม่ถึง 5,000 บาท  <br>
							ไม่เพียงพอที่จะเปลี่ยนประเภทการชำระเงิน</font><br><br>                 
												<table border=1 cellpadding=0 cellspacing=0>
                      
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
					
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$remain </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>

                        </table>
							"; 
							}
			}
			
	
}else {//ไม่กดเปลี่ยน 5000
	if($arrears==0){//ไม่มีค่าค้างชำระ
								if($pay_type==0){//แบบ 300
								$amount_month = floor($amount/300) ;
								$return = $amount%300;
							
											$expire_date =  MKTIME(0,0,0,$mm+$amount_month-$n,01,$yy) ;
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount_net;
											$_SESSION['expire_date']=$expire_date;
											$_SESSION['pay_type']='0';
											$_SESSION['amount_month']=$amount_month;
											
											
											$amount = number_format($amount);											
											$arrears = number_format($arrears);
											$remain = number_format($remain);
											$return = number_format($return);
											
												$result = "
                                                
												<table border=1 cellpadding=0 cellspacing=0>
                          <tr>
                            <td><div align=left>ประเภทการชำระเงินเป็นแบบ</div></td>
                            <td><div align=right>300 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						 
                          
                          <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด</div></td>
                            <td><div align=right><font color=red>$amount_month </font></div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                            <td colspan=2><div align=center><font color=red>$expire_date</font></div></td>
                            
                          </tr>
                        </table>
							"; 
								}else {//แบบ 100
									$amount_month = floor($amount/100) ;
									$return = $amount%100;
							if($_POST[start_pay_date]==$_POST[expire_date]){
								$amount_month = $amount_month-1;
							}
											$expire_date =  MKTIME(0,0,0,$mm+$amount_month-$n,01,$yy) ;
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount_net;
											$_SESSION['expire_date']=$expire_date;
											$_SESSION['pay_type']='1';
											$_SESSION['amount_month']=$amount_month;
											$amount = number_format($amount);											
											$arrears = number_format($arrears);
											$remain = number_format($remain);
											$return = number_format($return);
											
												$result = "
                                                
												<table border=1 cellpadding=0 cellspacing=0>
                          <tr>
                            <td><div align=left>ประเภทการชำระเงินแบบ</div></td>
                            <td><div align=right>100 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						  <tr>
                            <td><div align=left>หักค่าค้างชำระ</div></td>
                            <td><div align=right><font color=red>$arrears</font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                      
                          <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด</div></td>
                            <td><div align=right><font color=red>$amount_month </font></div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                            <td colspan=2><div align=center><font color=red>$expire_date</font></div></td>
                            
                          </tr>
                        </table>
							"; 
									
								}
	}else {//แบบมีค่าค้างชำระ 
		$remain = $amount-$arrears;
		if(($amount-$arrears)<0){
		echo "<font size=3 color=red> จำนวนเงินที่ชำระน้อยกว่าค่าค้างชำระ </font><br>";	
		}
		if($pay_type==0){		
		
								if($remain<=0){
								//$amount_month = 0;
								//$return = 0;
								$return = $amount%300;
								
							//	$return= $return*(-1);
								$_SESSION['pay_ar']=$remain-$return;;
								//$remain=$remain*(-1);
								$amount_month = floor($amount/300);
								if($remain==0){
									$amount_month1 = floor($amount/300) ; //แบบ300
								}else{
									$amount_month1 = floor($remain/300) ; //แบบ300
								}
								
							//	echo $amount_month;
							
							
									}	else{
										$amount_month = floor($amount/300);
								$amount_month1 = floor($remain/300) ; //แบบ300
								//echo $amount_month;
								$return = $remain%300;
								
									}
									if($_POST[start_pay_date]==$_POST[expire_date]){
								$amount_month = $amount_month-1;
							}
							
											$expire_date =  MKTIME(0,0,0,$mm+$amount_month-$n,01,$yy) ;
											//echo $expire_date."1";
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount_net;
											$_SESSION['expire_date']=$expire_date;
											$_SESSION['pay_type']='0';
											$_SESSION['amount_month']=$amount_month+1;
											$amount = number_format($amount);											
											$arrears = number_format($arrears);
											//$remain = number_format($remain);
											$return = number_format($return);
										
												$result = "
                                                
												<table border=1 cellpadding=0 cellspacing=0>
                          <tr>
                            <td><div align=left>ประเภทการชำระเงินเป็นแบบ</div></td>
                            <td><div align=right>300 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						  <tr>
                            <td><div align=left>หักค่าค้างชำระ</div></td>
                            <td><div align=right><font color=red>$arrears</font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>";
                        if($remain<=0){
							$remain = $remain*(-1);
                            $remain1=$remain+$return;
						    $result .= "   <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>".number_format($amount_net)."</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                                <td><div align=right><font color=red>$expire_date</font></div></td>
                              <td><div align=left>&nbsp;</div></td>
                          </tr><tr>
                            <td><div align=left><font color=red>ค่าค้างชำระ คงเหลือ </font></div></td>
                            <td><div align=right><font color=red>".number_format($remain1)." </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                        </table>
							"; 
						}else{
							$remain1=$remain-$return;
							 $result .= "<tr>
                            <td><div align=left>คงเหลือ</div></td>
                            <td><div align=right><font color=red>".number_format($remain)." </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>";
							  $result .= "   <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month1 </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>".number_format($remain1)."</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                            <td><div align=right><font color=red>$expire_date</font></div></td>
                             <td><div align=left>&nbsp;</div></td>
                          </tr>
                        </table>
							"; 
						}
					
                         
								}else {
									if($remain<=0){
								
								$amount_month = floor($amount/100);
									if($remain==0){
									$amount_month1 = floor($amount/100) ; //แบบ100
								}else{
									$amount_month1 = floor(($amount)/100) ; //แบบ100
								}
								
							$return = $amount%100;
								$_SESSION['pay_ar']=$amount;
									}	else{
										$amount_month = floor($amount/100);
									$amount_month1 = floor($remain/100) ;//แบบ100
									$return = $remain%100;
								
									}
									
											$expire_date =  MKTIME(0,0,0,$mm+$amount_month-$n,01,$yy) ;
											$expire_date = date_ch_form_m(date("Y-m-d", $expire_date)); 
											$amount_net = $amount-$return ;
											$_SESSION['amount']=$amount_net;
											$_SESSION['expire_date']=$expire_date;
											$_SESSION['pay_type']='1';
											$_SESSION['amount_month']=$amount_month;
											$amount = number_format($amount);											
											$arrears = number_format($arrears);
											$remain = number_format($remain);
											$return = number_format($return);
											
												$result = "
                                                
												<table border=1 cellpadding=0 cellspacing=0>
                          <tr>
                            <td><div align=left>ประเภทการชำระเงินเป็นแบบ</div></td>
                            <td><div align=right>100 บ./</div></td>
                            <td><div align=left>เดือน</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>จำนวนเงินที่ชำระ</div></td>
                            <td><div align=right><font color=red>$amount </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						  <tr>
                            <td><div align=left>หักค่าค้างชำระ</div></td>
                            <td><div align=right><font color=red>$arrears</font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr> ";
                        
                              if($remain<=0){
							$remain = $remain*(-1);
                            $remain1=$remain+$return;
						    $result .= "   <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month1 </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>".number_format($amount_net)."</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                                <td><div align=right><font color=red>$expire_date</font></div></td>
                              <td><div align=left>&nbsp;</div></td>
                          </tr><tr>
                            <td><div align=left><font color=red>ค่าค้างชำระ คงเหลือ</font></div></td>
                            <td><div align=right><font color=red>".number_format($remain1)." </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                        </table>
							"; 
						}else{
							$remain1=$remain-$return;
							 $result .= "<tr>
                            <td><div align=left>คงเหลือ</div></td>
                            <td><div align=right><font color=red>".number_format($remain)." </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>";
							  $result .= "   <tr>
                            <td><div align=left>ใช้ได้ทั้งหมด <font color=red>$amount_month1 </font>เดือน</div></td>
                            <td><div align=right>จากเงิน <font color=red>".number_format($remain1)."</font> </div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
						   <tr>
                            <td><div align=left>ได้รับเงินทอน</div></td>
                            <td><div align=right><font color=red>$return </font></div></td>
                            <td><div align=left>บาท</div></td>
                          </tr>
                          <tr>
                            <td><div align=left>ใช้ได้ถึง</div></td>
                            <td><div align=right><font color=red>$expire_date</font></div></td>
                             <td><div align=left>&nbsp;</div></td>
                          </tr>
                        </table>
							"; 
						
						}
									
								}
		
	}
}
}
		//				$amount = $amount-$return;
		//	$result .= "<input type=\"hidden\" name=\"amount\" value=\"$amount\" />
		//	<input type=\"hidden\" name=\"expire_date\" value=\"$expire_date\" />
		//	<input type=\"hidden\" name=\"amount_month\" value=\"$amount_month\" />";
		 if($return>0){
				echo "<font size=3 color=red>ห้ามมีเงินทอน</font><br><br>"; 
				$_SESSION['amount']='0';
			 }else echo "<br>";
			// echo $_SESSION['amount'];
			 echo $result;
			if($_SESSION['amount']!='0'){
			 echo "<br><input name=\"Close\" type=\"button\" id=\"Close\" 
onClick=\"Javascript:updateOpener()\" value=\" ยืนยัน \"></input>";
			}

	
		
	}
	 	    unset($_SESSION['change_pay_type']);
		unset($_SESSION['change_expire_date']);
		unset($_SESSION['cpro_id']);
		unset($_SESSION['amount']);
		unset($_SESSION['expire_date']);
		unset($_SESSION['pay_type']);
		unset($_SESSION['amount_month']);
		unset($_SESSION['pay_ar']);  
?>