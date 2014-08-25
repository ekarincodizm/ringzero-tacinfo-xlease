<?php
include("../config/config.php");

$teamid = $_GET['teamid'];

$qr_contest = pg_query("select * from \"v_TAC_contest_team\" where \"teamID\"='$teamid'");
$rs_team = pg_fetch_array($qr_contest);

$team_name = $rs_team['team_name'];
$team_register_date = $rs_team['team_register_date'];
$team_workmanship_detail = str_replace("\r\n","<br />",$rs_team['team_workmanship_detail']);
$team_workmanship_file = split(",",$rs_team['team_workmanship_file']);
$type_name = $rs_team['type_name'];
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
<script type="text/javascript">
$(document).ready(function(){
	$('.show_content').fadeIn(1000);
	$('.preview').fancybox({
		minWidth: 450,
		maxWidth: 450	
	});
});
</script>
</head>

<body>
<div class="show_content">
    <div align="center">
        <div class="container">
            <div class="top">
                <div class="logo"></div>
                <div class="head_title">
                </div>
            </div>
            <div class="middle">
              <div id="step1">
                    <div class="title margin-bottom">
                        <span class="inline_block"><h3>ข้อมูลทีมที่เข้าร่วมแข่งขัน .:</h3></span>
                        <span class="inline_block"><h3>Team Info</h3></span>
                    </div>
                    <div class="input_container">
                    	<div class="inline_block bold">ชื่อทีม : </div>
                        <div class="inline_block bold"><?php echo $team_name; ?></div>
                    </div>
                    <div class="input_container">
                    	<div class="inline_block bold">วันที่สมัครเข้าร่วมโครงการ : </div>
                        <div class="inline_block bold"><?php echo $team_register_date; ?></div>
                    </div>
                    <div class="input_container">
                    	<div class="inline_block bold">ประเภทรายการที่ร่วมแข่งขัน : </div>
                        <div class="inline_block bold"><?php echo $type_name; ?></div>
                    </div>
                    <div class="input_container">
                    	<span class="bold">รายชื่อสมาชิก :</span>
                    </div>
                    <div class="input_container">
                  <?php
					$q_member = "select * from \"TAC_contest_members\" where \"teamID\"='$teamid'";
					$qr_member = pg_query($q_member);
					$i = 1;
					while($rs_member = pg_fetch_array($qr_member))
					{
						$member_name_tha = $rs_member['member_name_tha'];
						$member_name_eng = $rs_member['member_name_eng'];
						$member_nickname = $rs_member['member_nickname'];
						$member_mobile = $rs_member['member_mobile'];
						$member_email = $rs_member['member_email'];
						$member_pcard_path = $rs_member['member_pcard_path'];
						$member_home_regis_path = $rs_member['member_home_regis_path'];
						$member_pic_path = $rs_member['member_pic_path'];
						$member_birth_day = $rs_member['member_birth_day'];
						$member_institute = $rs_member['member_institute'];
						$member_level = $rs_member['member_level'];
						$member_branch = $rs_member['member_branch'];
						
						$mem_pcard_name = array_pop (split('/',$member_pcard_path));
						$mem_hregis_name = array_pop (split('/',$member_home_regis_path));
						$mem_pic_name = array_pop (split('/',$member_pic_path));
						
						echo "
						<div class=\"alert-block alert-info margin-top\">
                            <i class=\"icon-user\"></i>
                            <span> ข้อมูลสมาชิกคนที่ $i</span>
                        </div>
                        <div class=\"split\">
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>ชื่อ - สกุล : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_name_tha1\" class=\"bold break_word\">$member_name_tha</div>
                                </div>
                            </div>
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_name_eng1\" class=\"bold break_word\">$member_name_eng</div>
                                </div>
                            </div>
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>ชื่อเล่น : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_nick_name1\" class=\"bold break_word\">$member_nickname</div>
                                </div>
                            </div>
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>วัน/เดือน/ปี เกิด : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_birth_day1\" class=\"bold break_word\">$member_birth_day</div>
                                </div>
                            </div>
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>สถาบันการศึกษา : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_institute1\" class=\"bold break_word\">$member_institute</div>
                                </div>
                            </div>
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>ระดับชั้น : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_level1\" class=\"bold break_word\">$member_level</div>
                                </div>
                            </div>
                            <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                                <div class=\"input_label\"><span>สาขาวิชา : </span></div>
                                <div class=\"input\">
                                    <div id=\"show_member_branch1\" class=\"bold break_word\">$member_branch</div>
                                </div>
                            </div>
                        <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                            <div class=\"input_label\"><span>เบอร์โทรศัพท์ : </span></div>
                            <div class=\"input\">
                                <div id=\"show_member_tel1\" class=\"bold break_word\">$member_mobile</div>
                            </div>
                        </div>
                        <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                            <div class=\"input_label\"><span>E-Mail : </span></div>
                            <div class=\"input\">
                                <div id=\"show_member_email1\" class=\"bold break_word\">$member_email</div>
                            </div>
                        </div>
                        <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                            <div class=\"input_label\"><span>บัตรประชาชน : </span></div>
                            <div class=\"input\">
                                <div id=\"show_personal_card1\" class=\"bold break_word\">
									<a href=\"$member_pcard_path\" data-fancybox-group=\"gallery\" title=\"$mem_pcard_name\" class=\"btn btn-success preview\" >เรียกดู</a>
								</div>
                            </div>
                        </div>
                        <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                            <div class=\"input_label\"><span>ทะเบียนบ้าน : </span></div>
                            <div class=\"input\">
                                <div id=\"show_home_regis1\" class=\"bold break_word\">
									<a href=\"$member_home_regis_path\" data-fancybox-group=\"gallery\" title=\"$mem_hregis_name\" class=\"btn btn-success preview\" >เรียกดู</a>
								</div>
                            </div>
                        </div>
                        <div class=\"inline_block\" style=\"width:230px; margin-right:15px;\">
                            <div class=\"input_label\"><span>รูปถ่าย : </span></div>
                            <div class=\"input\">
                                <div id=\"show_photo1\" class=\"bold break_word\">
									<a href=\"$member_pic_path\" data-fancybox-group=\"gallery\" title=\"$mem_pic_name\" class=\"btn btn-success preview\" >เรียกดู</a>
								</div>
                            </div>
                        </div>
						</div>
						";
					}
					?>
                    </div>
                    <div class="input_container">
                        <div class="inline_block" style=" width:96%;">
                            <div class="input_label bold"><span>รายละเอียดผลงาน : </span></div>
                            <div class="input">
                                <div id="show_project_details" class="bold break_word">
                                	<?php echo $team_workmanship_detail; ?>
                                </div>
                            </div>
                        </div>
                        <?php
						$sum_team_file = sizeof($team_workmanship_file);
						$i = 0;
						while($i<$sum_team_file)
						{
							echo "<div class=\"inline_block\" style=\"width:30%;\">
								<div class=\"input_label\"><span>ภาพผลงาน : </span></div>
								<div class=\"input\">
									<div id=\"show_project_pic1\" class=\"bold break_word\">
										<a href=\"".$team_workmanship_file[$i]."\" data-fancybox-group=\"gallery\" title=\"ภาพผลงาน\" class=\"btn btn-success preview\" >เรียกดู</a>
									</div>
								</div>
							</div>";
							$i++;
						}
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>