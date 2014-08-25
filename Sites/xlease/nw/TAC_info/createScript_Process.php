<?php
	header('Content-Type: text/html; charset=UTF-8');
	include("config.php");
	$sender=$_POST['tbxSender'];
	$message=$_POST['tarMessage'];
	$sendTime=$_POST['tbxSendTime'];
	$sendType=$_POST['radio'];
	if($sendType==1)
	{
		if($message!="")
		{
			$sql="select * from \"TrMember\" where \"disabled\"='n' and \"isAdmin\"='0' and \"telephonenumber\"!='' and \"telephonenumber\" not in (select \"telephoneNumber\" from \"TrSMSHistory\")";
			
			$dbquery=pg_query($sql);
			$rows=pg_num_rows($dbquery);
			$date=date("Y-m-d H-m-s");
			$date1=date("Y-m-d H:m:s");
			
			if($rows!=0)
			{
				while($result=pg_fetch_assoc($dbquery))
				{
					$telNumber=$result['telephonenumber'];
					if($sender==""&&$sendTime=="")
					{
						$text=iconv("UTF-8","",$telNumber.",".$message."\r\n");
						$strFileName = "sms_TextFile/not_send/tel_text_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"createTime\") values('$telNumber','$message','$date1')";
						pg_query($sql);
					}
					else if($sender=="")
					{
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber.",".$message.",,".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/not_send/tel_text_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"sendTime\",\"createTime\") values('$telNumber','$message','$sendTime','$date1')";
						pg_query($sql);
					}else
					{
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber.",".$message.",".$sender.",".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/not_send/tel_text_sender_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"senderName\",\"sendTime\",\"createTime\") values('$telNumber','$message','$sender','$sendTime','$date1')";
						pg_query($sql);
					}
				}
				if($objFopen)
				{
					header("Location:sendSMS.php");
				}
			}
			else
			{
				echo "ไม่มีเบอร์โทรศัพท์ที่ยังไม่ได้ส่งข้อความ";	
			}
		}
		else
		{
			echo "ยังไม่ได้ระบุข้อความที่ต้องการส่ง";
		}
	}
	else if($sendType==2)
	{
		if($message!="")
		{
			$sql="select * from \"TrMember\" where \"disabled\"='n' and \"isAdmin\"='0' and \"telephonenumber\"!='' and \"telephonenumber\" in (select \"telephoneNumber\" from \"TrSMSHistory\")";
			
			$dbquery=pg_query($sql);
			$rows=pg_num_rows($dbquery);
			$date=date("Y-m-d H-m-s");
			$date1=date("Y-m-d H:m:s");
			
			if($rows!=0)
			{
				while($result=pg_fetch_assoc($dbquery))
				{
					$telNumber=$result['telephonenumber'];
					if($sender==""&&$sendTime=="")
					{
						$text=iconv("UTF-8","",$telNumber.",".$message."\r\n");
						$strFileName = "sms_TextFile/sended/tel_text_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"createTime\") values('$telNumber','$message','$date1')";
						pg_query($sql);
					}
					else if($sender=="")
					{
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber.",".$message.",,".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/sended/tel_text_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"sendTime\",\"createTime\") values('$telNumber','$message','$sendTime','$date1')";
						pg_query($sql);
					}else
					{
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber.",".$message.",".$sender.",".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/sended/tel_text_sender_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"senderName\",\"sendTime\",\"createTime\") values('$telNumber','$message','$sender','$sendTime','$date1')";
						pg_query($sql);
					}
				}
				if($objFopen)
				{
					header("Location:sendSMS.php");
				}
			}
			else
			{
				echo "ไม่มีเบอร์โทรศัพท์ที่เคยส่งข้อความแล้ว";	
			}
		}
		else
		{
			echo "ยังไม่ได้ระบุข้อความที่ต้องการส่ง";
		}
	}
	else if($sendType==3)
	{
		if($message!="")
		{
			$sql="select * from \"TrMember\" where \"disabled\"='n' and \"isAdmin\"='0' and \"telephonenumber\"!=''";
			
			$dbquery=pg_query($sql);
			$rows=pg_num_rows($dbquery);
			$date=date("Y-m-d H-m-s");
			$date1=date("Y-m-d H:m:s");
			
			if($rows!=0)
			{
				while($result=pg_fetch_assoc($dbquery))
				{
					$telNumber=$result['telephonenumber'];
					if($sender==""&&$sendTime=="")
					{
						$text=iconv("UTF-8","",$telNumber.",".$message."\r\n");
						$strFileName = "sms_TextFile/send_all/tel_text_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"createTime\") values('$telNumber','$message','$date1')";
						pg_query($sql);
					}
					else if($sender=="")
					{
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber.",".$message.",,".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/send_all/tel_text_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"sendTime\",\"createTime\") values('$telNumber','$message','$sendTime','$date1')";
						pg_query($sql);
					}else
					{
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber.",".$message.",".$sender.",".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/send_all/tel_text_sender_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"senderName\",\"sendTime\",\"createTime\") values('$telNumber','$message','$sender','$sendTime','$date1')";
						pg_query($sql);
					}
				}
				if($objFopen)
				{
					header("Location:sendSMS.php");
				}
			}
			else
			{
				echo "ไม่พบเบอร์โทรศัพท์";	
			}
		}
		else
		{
			echo "ยังไม่ได้ระบุข้อความที่ต้องการส่ง";
		}
	}
	else if($sendType==4)
	{
		if($message!="")
		{
			$rows2=$_POST['rows'];
			$date=date("Y-m-d H-m-s");
			$date1=date("Y-m-d H:m:s");
			for($z=1;$z<=$rows2;$z++)
			{
				if($sender==""&&$sendTime=="")
				{
					$telNumber[$z]=$_POST["tbxTele$z"];
					if($telNumber[$z]!="")
					{
						$text=iconv("UTF-8","",$telNumber[$z].",".$message."\r\n");
						$strFileName = "sms_TextFile/send_custom/tel_text_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"createTime\") values('$telNumber[$z]','$message','$date1')";
						pg_query($sql);
					}
				}
				else if($sender=="")
				{
					if($telNumber[$z]!="")
					{
						$telNumber[$z]=$_POST["tbxTele$z"];
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber[$z].",".$message.",,".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/send_custom/tel_text_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"sendTime\",\"createTime\") values('$telNumber[$z]','$message','$sendTime','$date1')";
						pg_query($sql);
					}
				}else
				{
					if($telNumber[$z]!="")
					{
						$telNumber[$z]=$_POST["tbxTele$z"];
						$sendTime1=substr($sendTime,2,2).substr($sendTime,5,2).substr($sendTime,8,2).substr($sendTime,11,2).substr($sendTime,14,2);
						$text=iconv("UTF-8","",$telNumber[$z].",".$message.",".$sender.",".$sendTime1."\r\n");
						$strFileName = "sms_TextFile/send_custom/tel_text_sender_time_".$date.".txt";
						$objFopen = fopen($strFileName, 'a');
						fwrite($objFopen, $text);
						$sql="insert into \"TrSMSHistory\"(\"telephoneNumber\",\"message\",\"senderName\",\"sendTime\",\"createTime\") values('$telNumber[$z]','$message','$sender','$sendTime','$date1')";
						pg_query($sql);
					}
				}
			}
			if($objFopen)
			{
				header("Location:sendSMS.php");
			}
			else
			{
				echo "บันทึกไฟล์ไม่สำเร็จ";
			}
		}
		else
		{
			echo "คุณยังไม่ได้ระบุข้อความที่จะส่ง";
		}
	}
?>