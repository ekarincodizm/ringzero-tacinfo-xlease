<?php
include("../config/config.php");

$qr_contest = pg_query("select * from \"TAC_contest_types\"");
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
});
</script>
</head>

<body>
<div class="show_logo"></div>
<div class="show_content">
    <div align="center">
        <div class="container">
            <div class="top">
                <div class="logo"></div>
                <div class="head_title">
                    <!--<div class="step_logo"></div>-->
                </div>
            </div>
            <div class="middle">
                <!-- step 2 -->
                <div id="step2">
                    <div class="title">
                        <span class="inline_block"><h3>แบบฟอร์มการสมัครสมาชิก .:</h3></span>
                        <span class="inline_block"><h3>Register Form</h3></span>
                    </div>
                    <div class="note">
                        ข้อแนะนำ : โปรดระบุข้อมูลให้ครบถ้วน  ชัดเจน  พร้อมทั้งตรวจสอบข้อมูลให้เรียบร้อยก่อนดำเนินการในขั้นถัดไป หากมีข้อสงสัยในการสมัครติดต่อสอบถามรายละเอียดเพิ่มเติมได้ที่ 02-744-2288
                    </div>
                    <div class="form_title">
                        <span>ประเภทการแข่งขัน</span>
                    </div>
                    <form name="regis_form" id="regis_form" action="regis_process.php" method="post" enctype="multipart/form-data">
                        <div class="input_container">
                            <div class="input_label"><span class="req">*</span><span>เลือกประเภทการแข่งขัน : </span></div>
                            <div class="input">
                                <select name="contest_type" id="contest_type" class="source">
                                    <option value="">โปรดระบุประเภทการแข่งขัน</option>
                                    <?php
                                    while($rs = pg_fetch_array($qr_contest))
                                    {
                                        $contest_id = $rs["typeID"];
                                        $contest_name = $rs["type_name"];
                                        
                                        echo "<option value=\"$contest_id\">$contest_name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form_title">
                            <span>ชื่อทีม</span>
                        </div>
                        <div class="input_container">
                            <div class="input_label"><span class="req">*</span><span>ระบุชื่อทีม : </span></div>
                            <div class="input">
                                <input type="text" name="team_name" id="team_name" width="50"  class="source" />
                            </div>
                        </div>
                        <div class="form_title">
                            <span><span class="req">*</span>ข้อมูลสมาชิก</span>
                        </div>
                        <div class="note">
                            <div class="inline_block" style="width:84%;">คำชี้แจง : สมาชิกคนที่ 1 ให้ระบุชื่อหัวหน้าทีม และหนึ่งทีมจำเป็นต้องมีสมาชิกอย่างน้อย 1 คน  สูงสุดไม่เกิน 5 คน</div>
                            <div class="inline_block" style="width:15%; text-align:right;">
                            	<input type="button" name="btn_add_member" id="btn_add_member" class="btn btn-warning" value="เพิ่มสมาชิก" onclick="add_member();" />
                            </div>
                        </div>
                        <div class="input_container" id="member_data">
                            <!-- member 1 -->
                            <div class="group_data">
                                <div class="alert-block alert-info">
                                    <div class="inline_block" style="width:84%;">
                                        <i class="icon-user"></i>
                                        <span> ข้อมูลสมาชิกคนที่ </span>
                                        <span class="member_number">1</span>
                                    </div>
                                    <div class="inline_block" style="width:15%; text-align:right;">
                                        <input type="button" name="btn_delete_member" id="btn_delete_member1" class="btn" value="ลบ" onclick="delete_member(id);" />
                                    </div>
                                </div>
                                <div class="split">
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>ชื่อ - สกุล : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_name_tha[]" id="member_name_tha1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_name_eng[]" id="member_name_eng1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:100px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>ชื่อเล่น : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_nick_name[]" id="member_nick_name1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:100px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>วัน/เดือน/ปี เกิด : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_birth_day[]" id="member_birth_day1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>สถาบันการศึกษา : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_institute[]" id="member_institute1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:100px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>ระดับชั้น : </span></div>
                                        <div class="input">
                                            <select name="member_level[]" id="member_level1" style="width:96%;" class="source">
                                                <option value="">โปรดเลือกระดับการศึกษา</option>
                                                <option value="ปริญญาตรี">ปริญญาตรี</option>
                                                <option value="ปริญญาโท">ปริญญาโท</option>
                                                <option value="ปริญญาเอก">ปริญญาเอก</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>สาขาวิชา : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_branch[]" id="member_branch1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:150px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>เบอร์โทรศัพท์ : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_tel[]" id="member_tel1" style="width:96%;" class="source" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>E-Mail : </span></div>
                                        <div class="input">
                                            <input type="text" name="member_email[]" id="member_email1" style="width:96%;" class="source email" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>Upload บัตรประชาชน : </span></div>
                                        <div class="input">
                                            <input type="file" name="personal_card1" id="personal_card1" style="width:96%; height:inherit;" class="source file" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>Upload ทะเบียนบ้าน : </span></div>
                                        <div class="input">
                                            <input type="file" name="home_regis1" id="home_regis1" style="width:96%; height:inherit;" class="home_file source file" />
                                        </div>
                                    </div>
                                    <div class="inline_block" style="width:200px; margin-right:15px;">
                                        <div class="input_label"><span class="req">*</span><span>Upload รูปถ่าย : </span></div>
                                        <div class="input">
                                            <input type="file" name="photo1" id="photo1" style="width:96%; height:inherit;" class="pic_file source file" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form_title">
                            <span><span class="req"></span>รายละเอียดผลงานต่างๆที่ผ่านมาโดยสังเขป (ถ้ามี)</span>
                        </div>
                        <div class="note">
                            คำชี้แจง : ไฟล์ผลงานควรเป็นไฟล์ภาพนามสกุล *.jpg,*.jpeg,*.png หรือไฟล์ pdf เท่านั้น  สามารถอัพโหลดผลงานได้สูงสุด 3 ไฟล์เท่านั้น
                        </div>
                        <div class="input_container">
                            <div class="inline_block" style=" width:96%;">
                                <div class="input_label"><span>รายละเอียดผลงาน : </span></div>
                                <div class="input">
                                    <textarea name="project_details" id="project_details" style="width:98%; height:200px;" class="source1"></textarea>
                                </div>
                            </div>
                            <div class="inline_block" style=" width:30%;">
                                <div class="input_label"><span>Upload ภาพผลงาน : </span></div>
                                <div class="input">
                                    <input type="file" name="project_pic[]" id="project_pic1" style="height:inherit;" class="source1 file" />
                                </div>
                            </div>
                            <div class="inline_block" style=" width:30%;">
                                <div class="input_label"><span>Upload ภาพผลงาน : </span></div>
                                <div class="input">
                                    <input type="file" name="project_pic[]" id="project_pic2" style="height:inherit;" class="source1 file" />
                                </div>
                            </div>
                            <div class="inline_block" style=" width:30%;">
                                <div class="input_label"><span>Upload ภาพผลงาน : </span></div>
                                <div class="input">
                                    <input type="file" name="project_pic[]" id="project_pic3" style="height:inherit;" class="source1 file" />
                                </div>
                            </div>
                        </div>
                        <div class="submit align_right">
                            <div class="inline_block" style="width:48%; text-align:left;">
                                <input type="button" name="next_btn" id="next_btn" class="btn btn-success" value="ย้อนกลับ" onclick="back_steb(1);" />
                            </div>
                            <div class="inline_block" style="width:48%; text-align:right;">
                                <input type="button" name="next_btn" id="next_btn" class="btn btn-primary" value="ถัดไป" onclick="validate();" />
                            </div>
                        </div>
                    </form>
                </div>
                <!-- step 3 -->
                <div id="step3" style="display:none;">
                    <div class="title">
                        <span class="inline_block"><h3>ยืนยันความถูกต้อง .:</h3></span>
                        <span class="inline_block"><h3>Confirm</h3></span>
                    </div>
                    <div class="note">
                        ข้อแนะนำ : โปรดตรวจสอบความถูกต้องของข้อมูลให้เรียบร้อย  หากข้อมูลไม่ถูกต้องให้กดปุ่ม 'ย้อนกลับ' เพื่อดำเนินการแก้ไข 
                    </div>
                    <div class="note">
                        ข้อควรระวัง : เมื่อทำการยืนยันการสมัครแข่งขันแล้วจะไม่สามารถแก้ไขข้อมูลได้
                    </div>
                    <div class="form_title">
                        <span>ประเภทการแข่งขัน</span>
                    </div>
                    <div class="input_container">
                        <div class="input_label"><span>ประเภทการแข่งขัน : </span></div>
                        <div class="input">
                            <div id="show_contest_type" class="bold break_word">--</div>
                        </div>
                    </div>
                    <div class="form_title">
                        <span>ชื่อทีม</span>
                    </div>
                    <div class="input_container">
                        <div class="input_label"><span>ชื่อทีม : </span></div>
                        <div class="input">
                            <div id="show_team_name" class="bold break_word">--</div>
                        </div>
                    </div>
                    <div class="form_title">
                        <span>ข้อมูลสมาชิก</span>
                    </div>
                    <div class="input_container">
                        <!-- member 1 -->
                        <div class="alert-block alert-info">
                            <i class="icon-user"></i>
                            <span> ข้อมูลสมาชิกคนที่ 1</span>
                        </div>
                        <div class="split">
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล : </span></div>
                                <div class="input">
                                    <div id="show_member_name_tha1" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                <div class="input">
                                    <div id="show_member_name_eng1" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อเล่น : </span></div>
                                <div class="input">
                                    <div id="show_member_nick_name1" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>วัน/เดือน/ปี เกิด : </span></div>
                                <div class="input">
                                    <div id="show_member_birth_day1" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สถาบันการศึกษา : </span></div>
                                <div class="input">
                                    <div id="show_member_institute1" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ระดับชั้น : </span></div>
                                <div class="input">
                                    <div id="show_member_level1" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สาขาวิชา : </span></div>
                                <div class="input">
                                    <div id="show_member_branch1" class="bold break_word">--</div>
                                </div>
                            </div>
                        <div class="inline_block" style="width:150px; margin-right:15px;">
                            <div class="input_label"><span>เบอร์โทรศัพท์ : </span></div>
                            <div class="input">
                                <div id="show_member_tel1" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:200px; margin-right:15px;">
                            <div class="input_label"><span>E-Mail : </span></div>
                            <div class="input">
                                <div id="show_member_email1" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload บัตรประชาชน : </span></div>
                            <div class="input">
                                <div id="show_personal_card1" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload ทะเบียนบ้าน : </span></div>
                            <div class="input">
                                <div id="show_home_regis1" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload รูปถ่าย : </span></div>
                            <div class="input">
                                <div id="show_photo1" class="bold break_word">--</div>
                            </div>
                        </div>
                    </div>
                    <!-- member 2 -->
                    <div class="alert-block alert-info">
                            <i class="icon-user"></i>
                            <span> ข้อมูลสมาชิกคนที่ 2</span>
                        </div>
                        <div class="split">
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล : </span></div>
                                <div class="input">
                                    <div id="show_member_name_tha2" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                <div class="input">
                                    <div id="show_member_name_eng2" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อเล่น : </span></div>
                                <div class="input">
                                    <div id="show_member_nick_name2" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>วัน/เดือน/ปี เกิด : </span></div>
                                <div class="input">
                                    <div id="show_member_birth_day2" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สถาบันการศึกษา : </span></div>
                                <div class="input">
                                    <div id="show_member_institute2" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ระดับชั้น : </span></div>
                                <div class="input">
                                    <div id="show_member_level2" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สาขาวิชา : </span></div>
                                <div class="input">
                                    <div id="show_member_branch2" class="bold break_word">--</div>
                                </div>
                            </div>
                        <div class="inline_block" style="width:150px; margin-right:15px;">
                            <div class="input_label"><span>เบอร์โทรศัพท์ : </span></div>
                            <div class="input">
                                <div id="show_member_tel2" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:200px; margin-right:15px;">
                            <div class="input_label"><span>E-Mail : </span></div>
                            <div class="input">
                                <div id="show_member_email2" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload บัตรประชาชน : </span></div>
                            <div class="input">
                                <div id="show_personal_card2" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload ทะเบียนบ้าน : </span></div>
                            <div class="input">
                                <div id="show_home_regis2" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload รูปถ่าย : </span></div>
                            <div class="input">
                                <div id="show_photo2" class="bold break_word">--</div>
                            </div>
                        </div>
                    </div>
                    <!-- member 3 -->
                    <div class="alert-block alert-info">
                            <i class="icon-user"></i>
                            <span> ข้อมูลสมาชิกคนที่ 3</span>
                        </div>
                        <div class="split">
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล : </span></div>
                                <div class="input">
                                    <div id="show_member_name_tha3" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                <div class="input">
                                    <div id="show_member_name_eng3" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อเล่น : </span></div>
                                <div class="input">
                                    <div id="show_member_nick_name3" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>วัน/เดือน/ปี เกิด : </span></div>
                                <div class="input">
                                    <div id="show_member_birth_day3" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สถาบันการศึกษา : </span></div>
                                <div class="input">
                                    <div id="show_member_institute3" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ระดับชั้น : </span></div>
                                <div class="input">
                                    <div id="show_member_level3" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สาขาวิชา : </span></div>
                                <div class="input">
                                    <div id="show_member_branch3" class="bold break_word">--</div>
                                </div>
                            </div>
                        <div class="inline_block" style="width:150px; margin-right:15px;">
                            <div class="input_label"><span>เบอร์โทรศัพท์ : </span></div>
                            <div class="input">
                                <div id="show_member_tel3" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:200px; margin-right:15px;">
                            <div class="input_label"><span>E-Mail : </span></div>
                            <div class="input">
                                <div id="show_member_email3" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload บัตรประชาชน : </span></div>
                            <div class="input">
                                <div id="show_personal_card3" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload ทะเบียนบ้าน : </span></div>
                            <div class="input">
                                <div id="show_home_regis3" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload รูปถ่าย : </span></div>
                            <div class="input">
                                <div id="show_photo3" class="bold break_word">--</div>
                            </div>
                        </div>
                    </div>
                    <!-- member 4 -->
                    <div class="alert-block alert-info">
                            <i class="icon-user"></i>
                            <span> ข้อมูลสมาชิกคนที่ 4</span>
                        </div>
                        <div class="split">
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล : </span></div>
                                <div class="input">
                                    <div id="show_member_name_tha4" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                <div class="input">
                                    <div id="show_member_name_eng4" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อเล่น : </span></div>
                                <div class="input">
                                    <div id="show_member_nick_name4" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>วัน/เดือน/ปี เกิด : </span></div>
                                <div class="input">
                                    <div id="show_member_birth_day4" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สถาบันการศึกษา : </span></div>
                                <div class="input">
                                    <div id="show_member_institute4" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ระดับชั้น : </span></div>
                                <div class="input">
                                    <div id="show_member_level4" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สาขาวิชา : </span></div>
                                <div class="input">
                                    <div id="show_member_branch4" class="bold break_word">--</div>
                                </div>
                            </div>
                        <div class="inline_block" style="width:150px; margin-right:15px;">
                            <div class="input_label"><span>เบอร์โทรศัพท์ : </span></div>
                            <div class="input">
                                <div id="show_member_tel4" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:200px; margin-right:15px;">
                            <div class="input_label"><span>E-Mail : </span></div>
                            <div class="input">
                                <div id="show_member_email4" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload บัตรประชาชน : </span></div>
                            <div class="input">
                                <div id="show_personal_card4" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload ทะเบียนบ้าน : </span></div>
                            <div class="input">
                                <div id="show_home_regis4" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload รูปถ่าย : </span></div>
                            <div class="input">
                                <div id="show_photo4" class="bold break_word">--</div>
                            </div>
                        </div>
                    </div>
                    <!-- member 5 -->
                    <div class="alert-block alert-info">
                            <i class="icon-user"></i>
                            <span> ข้อมูลสมาชิกคนที่ 5</span>
                        </div>
                        <div class="split">
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล : </span></div>
                                <div class="input">
                                    <div id="show_member_name_tha5" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อ - สกุล(ภาษาอังกฤษ) : </span></div>
                                <div class="input">
                                    <div id="show_member_name_eng5" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ชื่อเล่น : </span></div>
                                <div class="input">
                                    <div id="show_member_nick_name5" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>วัน/เดือน/ปี เกิด : </span></div>
                                <div class="input">
                                    <div id="show_member_birth_day5" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สถาบันการศึกษา : </span></div>
                                <div class="input">
                                    <div id="show_member_institute5" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:100px; margin-right:15px;">
                                <div class="input_label"><span>ระดับชั้น : </span></div>
                                <div class="input">
                                    <div id="show_member_level5" class="bold break_word">--</div>
                                </div>
                            </div>
                            <div class="inline_block" style="width:200px; margin-right:15px;">
                                <div class="input_label"><span>สาขาวิชา : </span></div>
                                <div class="input">
                                    <div id="show_member_branch5" class="bold break_word">--</div>
                                </div>
                            </div>
                        <div class="inline_block" style="width:150px; margin-right:15px;">
                            <div class="input_label"><span>เบอร์โทรศัพท์ : </span></div>
                            <div class="input">
                                <div id="show_member_tel5" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:200px; margin-right:15px;">
                            <div class="input_label"><span>E-Mail : </span></div>
                            <div class="input">
                                <div id="show_member_email5" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload บัตรประชาชน : </span></div>
                            <div class="input">
                                <div id="show_personal_card5" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload ทะเบียนบ้าน : </span></div>
                            <div class="input">
                                <div id="show_home_regis5" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style="width:250px; margin-right:15px;">
                            <div class="input_label"><span>Upload รูปถ่าย : </span></div>
                            <div class="input">
                                <div id="show_photo5" class="bold break_word">--</div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="form_title">
                        <span><span class="req"></span>รายละเอียดผลงานต่างๆที่ผ่านมาโดยสังเขป (ถ้ามี)</span>
                    </div>
                    <div class="note">
                        คำชี้แจง : ไฟล์ผลงานควรเป็นไฟล์ภาพหรือไฟล์ pdf เท่านั้น  สามารถอัพโหลดผลงานได้สูงสุด 3 ไฟล์เท่านั้น
                    </div>
                    <div class="input_container">
                        <div class="inline_block" style=" width:96%;">
                            <div class="input_label"><span>รายละเอียดผลงาน : </span></div>
                            <div class="input">
                                <div id="show_project_details" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style=" width:30%;">
                            <div class="input_label"><span>Upload ภาพผลงาน : </span></div>
                            <div class="input">
                                <div id="show_project_pic1" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style=" width:30%;">
                            <div class="input_label"><span>Upload ภาพผลงาน : </span></div>
                            <div class="input">
                                <div id="show_project_pic2" class="bold break_word">--</div>
                            </div>
                        </div>
                        <div class="inline_block" style=" width:30%;">
                            <div class="input_label"><span>Upload ภาพผลงาน : </span></div>
                            <div class="input">
                                <div id="show_project_pic3" class="bold break_word">--</div>
                            </div>
                        </div>
                    </div>
                    <div class="submit align_right">
                        <div class="inline_block" style="width:48%; text-align:left;">
                            <input type="button" name="next_btn" id="next_btn" class="btn btn-success" value="ย้อนกลับ" onclick="back_steb(2);" />
                        </div>
                        <div class="inline_block" style="width:48%; text-align:right;">
                            <input type="button" name="next_btn" id="next_btn" class="btn btn-primary" value="ยืนยันการสมัคร" onclick="savedata();" />
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="bottom">
                <div class="bottom_content"></div>
                <div class="powered"></div>
            </div>-->
        </div>
    </div>
</div>
<script type="text/javascript" src="scripts/validate.js"></script>
</body>
</html>