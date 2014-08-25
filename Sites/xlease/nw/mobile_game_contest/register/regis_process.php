<?php
include("../config/config.php");

$contest_type = chk_null(pg_escape_string($_POST['contest_type']));
$team_name = chk_null(pg_escape_string($_POST['team_name']));
$member_name_tha = $_POST['member_name_tha'];
$member_name_eng = $_POST['member_name_eng'];
$member_nick_name = $_POST['member_nick_name'];
$member_birth_day = $_POST['member_birth_day'];
$member_institute = $_POST['member_institute'];
$member_level = $_POST['member_level'];
$member_branch = $_POST['member_branch'];
$member_tel = $_POST['member_tel'];
$member_email = $_POST['member_email'];
$project_details = chk_null(pg_escape_string($_POST['project_details']));

$date = chk_null(date("Y-m-d H:i:s"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบลงทะเบียน :: TAC INFO e-Commerce Web Design Competition 2013</title>
<link href="../libralies/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../libralies/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="../css/flick/jquery-ui-1.9.0.custom.css" rel="stylesheet" type="text/css" />

<link href="css/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../scripts/jquery-1.9.0.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.10.0.custom.js"></script>
<script type="text/javascript" src="../libralies/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
<div align="center">
	<div class="container">
    	<div class="top">
        	<div class="logo"></div>
            <div class="head_title">
            	<!--<div class="step_logo"></div>-->
            </div>
        </div>
        <div class="middle">
        	<?php
			pg_query("BEGIN");
			$status = 0;
			$team_id = "";
			$q_team = "insert into \"TAC_contest_teams\"(\"team_name\",\"team_register_date\",\"team_workmanship_detail\",\"typeID\") values($team_name,$date,$project_details,$contest_type) returning \"teamID\"";
			$qr_team = pg_query($q_team);
			if(!$qr_team)
			{
				$status++;
			}
			else
			{
				$rs_team = pg_fetch_array($qr_team);
				$team_id = $rs_team['teamID'];
				
				if(!file_exists("uploads")&&!is_dir("uploads"))
				{
					mkdir("uploads", 0777);
				}
				
				if(!file_exists("uploads/project")&&!is_dir("uploads/project"))
				{
					mkdir("uploads/project", 0777);
				}
				
				mkdir("uploads/project/$team_id", 0777);
				
				$project_pic_path = "";
				
				for($i=0;$i<count($_FILES["project_pic"]["name"]);$i++)
				{
					if($_FILES["project_pic"]["name"][$i] != "")
					{
						if(move_uploaded_file($_FILES["project_pic"]["tmp_name"][$i],"uploads/project/$team_id/".iconv("UTF-8","TIS-620",$team_id."_".$i."_".$_FILES["project_pic"]["name"][$i])))
						{
							if($project_pic_path=="")
							{
								$project_pic_path = "register/uploads/project/$team_id/".iconv("UTF-8","TIS-620",$team_id."_".$i."_".$_FILES["project_pic"]["name"][$i]);
							}
							else
							{
								$project_pic_path.=",register/uploads/project/$team_id/".iconv("UTF-8","TIS-620",$team_id."_".$i."_".$_FILES["project_pic"]["name"][$i]);
							}
						}
					}
				}
				if($project_pic_path!="")
				{
					$q_update_path = "update \"TAC_contest_teams\" set \"team_workmanship_file\"='$project_pic_path' where \"teamID\"='$team_id'";
					$qr_update_path = pg_query($q_update_path);
					if(!$qr_update_path)
					{
						$status++;
					}
				}
				
				$sum_member = count($member_name_tha);
				
				$n = 0;
				$pcard_path = "";
				
				while($n<$sum_member)
				{
					$mem_name_th = pg_escape_string($member_name_tha[$n]);
					if($mem_name_th!="")
					{
						$mem_name_th = chk_null($mem_name_th);
						$mem_name_en = chk_null(pg_escape_string($member_name_eng[$n]));
						$mem_nickname = chk_null(pg_escape_string($member_nick_name[$n]));
						$mem_birthday = chk_null(pg_escape_string($member_birth_day[$n]));
						$mem_institute = chk_null(pg_escape_string($member_institute[$n]));
						$mem_level = chk_null(pg_escape_string($member_level[$n]));
						$mem_branch = chk_null(pg_escape_string($member_branch[$n]));
						$mem_tel = chk_null($member_tel[$n]);
						$mem_email = chk_null(pg_escape_string($member_email[$n]));
						
						$q_ins_member = "insert into \"TAC_contest_members\"(\"member_name_tha\",\"member_name_eng\",\"member_nickname\",\"member_mobile\",\"member_email\",\"member_birth_day\",\"member_institute\",\"member_level\",\"member_branch\",\"teamID\") values($mem_name_th,$mem_name_en,$mem_nickname,$mem_tel,$mem_email,$mem_birthday,$mem_institute,$mem_level,$mem_branch,'$team_id') returning \"memberID\"";
						//echo $q_ins_member;
						$qr_ins_member = pg_query($q_ins_member);
						if(!$qr_ins_member)
						{
							$status++;
						}
						else
						{
							$rs_member = pg_fetch_array($qr_ins_member);
							$member_id = $rs_member['memberID'];
							
							if(!file_exists("uploads")&&!is_dir("uploads"))
							{
								mkdir("uploads", 0777);
							}
							
							if(!file_exists("uploads/members")&&!is_dir("uploads/members"))
							{
								mkdir("uploads/members", 0777);
							}
							
							if(!file_exists("uploads/members/$team_id")&&!is_dir("uploads/members/$team_id"))
							{
								mkdir("uploads/members/$team_id", 0777);
							}
							
							if(!file_exists("uploads/members/$team_id/$member_id")&&!is_dir("uploads/members/$team_id/$member_id"))
							{
								mkdir("uploads/members/$team_id/$member_id", 0777);
							}
							
							if($_FILES["personal_card".($n+1)]["name"] != "")
							{
								if(move_uploaded_file($_FILES["personal_card".($n+1)]["tmp_name"],"uploads/members/$team_id/$member_id/".iconv("UTF-8","TIS-620",$team_id."_".$member_id."_".'1_'.$_FILES["personal_card".($n+1)]["name"])))
								{
									$pcard_path = "uploads/members/$team_id/$member_id/".iconv("UTF-8","TIS-620",$team_id."_".$member_id."_".$_FILES["personal_card".($n+1)]["name"]);
									$q_update_pcard = "update \"TAC_contest_members\" set \"member_pcard_path\"='$pcard_path' where \"memberID\"='$member_id'";
									$qr_update_pcard = pg_query($q_update_pcard);
									if(!$qr_update_pcard)
									{
										$status++;
									}
								}
							}
							if($_FILES["home_regis".($n+1)]["name"] != "")
							{
								if(move_uploaded_file($_FILES["home_regis".($n+1)]["tmp_name"],"uploads/members/$team_id/$member_id/".iconv("UTF-8","TIS-620",$team_id."_".$member_id."_".'2_'.$_FILES["home_regis".($n+1)]["name"])))
								{
									$home_regis_path = "uploads/members/$team_id/$member_id/".iconv("UTF-8","TIS-620",$team_id."_".$member_id."_".$_FILES["home_regis".($n+1)]["name"]);
									$q_update_home = "update \"TAC_contest_members\" set \"member_home_regis_path\"='$home_regis_path' where \"memberID\"='$member_id'";
									$qr_update_home = pg_query($q_update_home);
									if(!$qr_update_home)
									{
										$status++;
									}
								}
							}
							if($_FILES["photo".($n+1)]["name"] != "")
							{
								if(move_uploaded_file($_FILES["photo".($n+1)]["tmp_name"],"uploads/members/$team_id/$member_id/".iconv("UTF-8","TIS-620",$team_id."_".$member_id."_".'3_'.$_FILES["photo".($n+1)]["name"])))
								{
									$photo_path = "uploads/members/$team_id/$member_id/".iconv("UTF-8","TIS-620",$team_id."_".$member_id."_".$_FILES["photo".($n+1)]["name"]);
									$q_update_photo = "update \"TAC_contest_members\" set \"member_pic_path\"='$photo_path' where \"memberID\"='$member_id'";
									$qr_update_photo = pg_query($q_update_photo);
									if(!$qr_update_photo)
									{
										$status++;
									}
								}
							}
						}
					}
					
					$n++;
				}
			}
			if($status==0)
			{
				echo "<div class=\"alert-block alert-success margin-bottom\" style=\"width:96%;\"><h4>ผลลัพธ์ : ทำรายการสมัครเข้าร่วมโครงการเรียบร้อยแล้ว จะมีเจ้าหน้าที่จากบริษัทโทรไปยืนยันอีกครั้งใน 1 -2 วัน หากไม่ได้รับการยืนยัน สามารถติดต่อได้ที่ 02-744-2288</h4></div>";
				pg_query("COMMIT");
			}
			else
			{
				echo "<div class=\"alert-block alert-error margin-bottom\" style=\"width:96%;\"><h4>ผิดพลาด : ทำรายการไม่สำเร็จ กรุณาตรวจสอบประเภทไฟล์ที่อัพโหลด  หรือลองใหม่ภายหลังครับ หรือติดต่อ 02-744-2288 หากพบปัญหาในการสมัคร</h4></div>";
				pg_query("ROLLBACK");
			}
			echo "<div class=\"alert-block margin-bottom\" style=\"width:96%;\"><input type=\"button\" name=\"btn_home\" id=\"btn_home\" value=\"กลับหน้าหลัก\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php'\" /></div>";
			?>
        </div>
    </div>
</div>
</body>
</html>