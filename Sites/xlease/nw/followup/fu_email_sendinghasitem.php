<?php
  include('Mail.php');
  include('Mail/mime.php');
    
  $date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
            // Constructing the email
            $sender = $temsendemail;                                             // Your email address
           

			$recipient = array();
				
					if(!empty($comid)){
							
							for($i = 0;$i < sizeof($comid);$i++ ){
								
								$sql1 = pg_query("select * from \"fu_company\" where \"comID\" = '$comid[$i]'");
								$com = pg_fetch_array($sql1);
								$id = $com['comID'];	
								$name = "บริษัท ".$com['com_name'];					
								$recipient[] = $com['com_email'];
								
						
							}
					}
					if(!empty($empconid)){

							for($o = 0;$o < sizeof($empconid);$o++ ){
						
								$sql2 = pg_query("select * from \"fu_empcontact\" where \"empconID\" = '$empconid[$o]'");
								$emp = pg_fetch_array($sql2);
								$idemp = $emp['empconID'];	
								$name = "คุณ ".$emp['empcon_name'];
								$recipient[] = $emp['empcon_email'];
					
						
						}
					}
			
		   
            $subject = "=?UTF-8?B?".base64_encode($temheader)."?=";                                                // Subject for the email
            $text = '.....';                                      // Text version of the email
            $html = "กราบเรียน $name\r\n".$temdetail;     															// HTML version of the email
            $crlf = "\n";
            $headers = array(
                            'From'          => $sender,
                            'Return-Path'   => $sender,
                            'Subject'       => $subject
                            );
     
            // Creating the Mime message
            $mime = new Mail_mime($crlf);
     
            // Setting the body of the email
			
            $mime->setTXTBody($text);
            $mime->setHTMLBody($html);
			
			
			
			//แนบไฟล์
			
					$qry_name9 = pg_query("select * from \"fu_template\" WHERE \"temID\" = '$temp1'");
					$result9=pg_fetch_array($qry_name9);						
					$ff = trim($result9["tem_file"]);
					$file9=explode("/",$ff);						
						$z = 1;
						$a=sizeof($file9);
					do{
							
					
							$file = "fileupload/$file9[$z]";                                                 // Name of the Attachment
							                            
							$mime->addAttachment ($file);  									// Add the attachment to the email
					$z++;
							
					}
					while($z<$a);
			
     
			// ภาษาไทย
			$mimeparams=array();
			$mimeparams['text_encoding']="7bit";
			$mimeparams['text_charset']="UTF-8";
			$mimeparams['html_charset']="UTF-8";
    
            // Set body and headers ready for base mail class
            $body = $mime->get($mimeparams);
            $headers = $mime->headers($headers);
     
            // SMTP authentication params
            $smtp_params["host"]     = "mail.thaiace.co.th";
            $smtp_params["port"]     = "25";
            $smtp_params["auth"]     = true;
            $smtp_params["username"] = "it@thaiace.co.th";
            $smtp_params["password"] = "Thaiace99910";
     
            // Sending the email using smtp
            $mail =& Mail::factory("smtp", $smtp_params);
            $result = $mail->send($recipient, $headers, $body);
            if($result == 1)
            {
				if(!empty($id) || $id!=""){
						$status = 0;
						pg_query("BEGIN");
						$sql2 = "Insert into \"fu_mail_history\"(\"comID\",\"temID\",\"maildate\") values('$id','$temp1','$date') ";
										$results2=pg_query($sql2);
					
												if($results2)
												{}
												else{
													$status++;
												}
														if($status == 0){
															pg_query("COMMIT");
														}else{
															pg_query("ROLLBACK");
														}
				}
				if(!empty($idemp) || $idemp != ""){
						$status = 0;
						pg_query("BEGIN");
						$sql2 = "Insert into \"fu_mail_history\"(\"empconID\",\"temID\",\"maildate\") values('$idemp','$temp1','$date') ";
										$results2=pg_query($sql2);
					
												if($results2)
												{}
												else{
													$status++;
												}
														if($status == 0){
															pg_query("COMMIT");
														}else{
															pg_query("ROLLBACK");
														}
				}
						echo "Email ถูกส่งให้ "." ".$email." "."เรียบร้อยแล้ว.";
						echo "<br>";
						echo "<meta http-equiv=\"refresh\" content=\"5; URL=index.php\">";
           
		    }else{
 
					echo "ไม่สามารถส่ง E-mail"." ".$email." "."ได้   อาจจะไม่มีอยู่จริง";
					echo "<br>";
					echo "หรืออาจะเกิดปัญหาจากผู้ให้บริการอินเทอร์เน็ต กรุณาลองใหม่ในภายหลัง";
					echo "<meta http-equiv=\"refresh\" content=\"5; URL=index.php\">";
			}
?>






