<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){
	
	
    $("#auditors").autocomplete({
        source: "list_emp.php",
        minLength:1
    });
	$("#customer").autocomplete({
        source: "list_customer.php",
        minLength:1
    });
	// radio feature
	document.frm.height2.disabled=true;
	document.frm.height3.disabled=true;
	document.frm.height4.disabled=true;
	document.frm.height5.disabled=true;
	document.frm.height6.disabled=true;
	document.frm.feature_other.disabled=true;
	// radio about deed
	document.frm.deed_owner_area_size1.disabled=true;
	document.frm.deed_owner_area_size2.disabled=true;
	// radio road	
	document.frm.road_state_detail.disabled=true;
	// radio generality
	document.frm.generality_detail.disabled=true;
	
	// ซ่อน checkbox utilities
	document.frm.utilities_electricity.disabled=true;
	document.frm.utilities_phone.disabled=true;
	document.frm.utilities_drain.disabled=true;
	document.frm.utilities_groundwater.disabled=true;
	document.frm.utilities_electricroad.disabled=true;
	document.frm.utilities_plumbing.disabled=true;

	
		$("input[type='radio']").change(function(){

			if(document.getElementById("feature1").checked){			
				document.frm.height1.disabled=false;
				document.frm.height2.disabled=true;
				document.frm.height3.disabled=true;
				document.frm.height4.disabled=true;
				document.frm.height5.disabled=true;
				document.frm.height6.disabled=true;
				document.frm.feature_other.disabled=true;		
			}else if(document.getElementById("feature2").checked){			
				document.frm.height1.disabled=true;
				document.frm.height2.disabled=false;
				document.frm.height3.disabled=true;
				document.frm.height4.disabled=true;
				document.frm.height5.disabled=true;
				document.frm.height6.disabled=true;
				document.frm.feature_other.disabled=true;		
			}else if(document.getElementById("feature3").checked){			
				document.frm.height1.disabled=true;
				document.frm.height2.disabled=true;
				document.frm.height3.disabled=false;
				document.frm.height4.disabled=true;
				document.frm.height5.disabled=true;
				document.frm.height6.disabled=true;
				document.frm.feature_other.disabled=true;		
			}else if(document.getElementById("feature4").checked){			
				document.frm.height1.disabled=true;
				document.frm.height2.disabled=true;
				document.frm.height3.disabled=true;
				document.frm.height4.disabled=false;
				document.frm.height5.disabled=true;
				document.frm.height6.disabled=true;
				document.frm.feature_other.disabled=true;		
			}else if(document.getElementById("feature5").checked){			
				document.frm.height1.disabled=true;
				document.frm.height2.disabled=true;
				document.frm.height3.disabled=true;
				document.frm.height4.disabled=true;
				document.frm.height5.disabled=false;
				document.frm.height6.disabled=true;
				document.frm.feature_other.disabled=true;		
			}else if(document.getElementById("feature6").checked){			
				document.frm.height1.disabled=true;
				document.frm.height2.disabled=true;
				document.frm.height3.disabled=true;
				document.frm.height4.disabled=true;
				document.frm.height5.disabled=true;
				document.frm.height6.disabled=false;
				document.frm.feature_other.disabled=false;		
			}			
			if(document.getElementById("deed_owner_area").checked){			
				document.frm.deed_owner_area_size1.disabled=false;
				document.frm.deed_owner_area_size2.disabled=false;
			}else{
				document.frm.deed_owner_area_size1.value="";
				document.frm.deed_owner_area_size2.value="";
				document.frm.deed_owner_area_size1.disabled=true;
				document.frm.deed_owner_area_size2.disabled=true;
			}
			if(document.getElementById("road_state").checked){			
				document.frm.road_state_detail.disabled=false;
			}else{
				document.frm.road_state_detail.value="";
				document.frm.road_state_detail.disabled=true;
			}
			if(document.getElementById("generality").checked){			
				document.frm.generality_detail.disabled=false;
			}else{
				document.frm.generality_detail.value="";
				document.frm.generality_detail.disabled=true;
			}
			
			if(document.getElementById("utilities").checked){			
				document.frm.utilities_electricity.disabled=false;
				document.frm.utilities_phone.disabled=false;
				document.frm.utilities_drain.disabled=false;
				document.frm.utilities_groundwater.disabled=false;
				document.frm.utilities_electricroad.disabled=false;
				document.frm.utilities_plumbing.disabled=false;
			}else{
				document.frm.utilities_electricity.disabled=true;
				document.frm.utilities_phone.disabled=true;
				document.frm.utilities_drain.disabled=true;
				document.frm.utilities_groundwater.disabled=true;
				document.frm.utilities_electricroad.disabled=true;
				document.frm.utilities_plumbing.disabled=true;
				document.frm.utilities_electricity.checked=false;
				document.frm.utilities_phone.checked=false;
				document.frm.utilities_drain.checked=false;
				document.frm.utilities_groundwater.checked=false;
				document.frm.utilities_electricroad.checked=false;
				document.frm.utilities_plumbing.checked=false;
			}
		
	});	
	// checkbox
	document.frm.wall_other_detail.disabled=true;
	document.frm.ground_top_other_detail.disabled=true;
	document.frm.ground_bot_other_detail.disabled=true;
	document.frm.roof_frame_other_detail.disabled=true;
	document.frm.roof_other_detail.disabled=true;
	document.frm.rest_other_detail.disabled=true;
	document.frm.door_other_detail.disabled=true;
	document.frm.window_other_detail.disabled=true;
	document.frm.ceiling_other_detail.disabled=true;
	document.frm.position_address_navigation_name.disabled=true;
	document.frm.land_state_cover_about1.disabled=true;
    document.frm.land_state_cover_about2.disabled=true;
	document.frm.land_state_hole_about1.disabled=true;
	document.frm.land_state_hole_about2.disabled=true;
	document.frm.land_level_height_about.disabled=true;
	document.frm.land_level_low_about.disabled=true;
	document.frm.useful_other_detail.disabled=true;
	document.frm.bind_rent_about.disabled=true;
	document.frm.bind_pawn_about.disabled=true;
	
	
	
	$("input[type='checkbox']").change(function(){
		
		if(document.getElementById("wall_other").checked){			
				document.frm.wall_other_detail.disabled=false;				
		}else{
				document.frm.wall_other_detail.disabled=true;
				document.frm.wall_other_detail.value="";
		}
		if(document.getElementById("ground_top_other").checked){			
				document.frm.ground_top_other_detail.disabled=false;				
		}else{
				document.frm.ground_top_other_detail.disabled=true;
				document.frm.ground_top_other_detail.value="";
		}
		if(document.getElementById("ground_bot_other").checked){			
				document.frm.ground_bot_other_detail.disabled=false;				
		}else{
				document.frm.ground_bot_other_detail.disabled=true;
				document.frm.ground_bot_other_detail.value="";
		}
		if(document.getElementById("roof_frame_other").checked){			
				document.frm.roof_frame_other_detail.disabled=false;				
		}else{
				document.frm.roof_frame_other_detail.disabled=true;
				document.frm.roof_frame_other_detail.value="";
		}
		if(document.getElementById("roof_other").checked){			
				document.frm.roof_other_detail.disabled=false;				
		}else{
				document.frm.roof_other_detail.disabled=true;
				document.frm.roof_other_detail.value="";
		}
		if(document.getElementById("rest_other").checked){			
				document.frm.rest_other_detail.disabled=false;				
		}else{
				document.frm.rest_other_detail.disabled=true;
				document.frm.rest_other_detail.value="";
		}
		if(document.getElementById("door_other").checked){			
				document.frm.door_other_detail.disabled=false;				
		}else{
				document.frm.door_other_detail.disabled=true;
				document.frm.door_other_detail.value="";
		}
		if(document.getElementById("window_other").checked){			
				document.frm.window_other_detail.disabled=false;				
		}else{
				document.frm.window_other_detail.disabled=true;
				document.frm.window_other_detail.value="";
		}
		if(document.getElementById("ceiling_other").checked){			
				document.frm.ceiling_other_detail.disabled=false;				
		}else{
				document.frm.ceiling_other_detail.disabled=true;
				document.frm.ceiling_other_detail.value="";
		}
		
		
		if(document.getElementById("position_address_navigation").checked){			
				document.frm.position_address_navigation_name.disabled=false;				
		}else{
				document.frm.position_address_navigation_name.disabled=true;
				document.frm.position_address_navigation_name.value="";
		}
		if(document.getElementById("land_state_cover").checked){			
				document.frm.land_state_cover_about1.disabled=false;
				document.frm.land_state_cover_about2.disabled=false;				
		}else{
				document.frm.land_state_cover_about1.disabled=true;
				document.frm.land_state_cover_about2.disabled=true;
				document.frm.land_state_cover_about1.value="";
				document.frm.land_state_cover_about2.value="";
		}
		
		
		if(document.getElementById("land_state_hole").checked){			
				document.frm.land_state_hole_about1.disabled=false;
				document.frm.land_state_hole_about2.disabled=false;				
		}else{
				document.frm.land_state_hole_about1.disabled=true;
				document.frm.land_state_hole_about2.disabled=true;
				document.frm.land_state_hole_about1.value="";
				document.frm.land_state_hole_about2.value="";
		}		
		if(document.getElementById("land_level_height").checked){			
				document.frm.land_level_height_about.disabled=false;
			
		}else{
				document.frm.land_level_height_about.disabled=true;
				document.frm.land_level_height_about.value="";
		}
		if(document.getElementById("land_level_low").checked){			
				document.frm.land_level_low_about.disabled=false;
			
		}else{
				document.frm.land_level_low_about.disabled=true;
				document.frm.land_level_low_about.value="";
		}
		if(document.getElementById("useful_other").checked){			
				document.frm.useful_other_detail.disabled=false;
			
		}else{
				document.frm.useful_other_detail.disabled=true;
				document.frm.useful_other_detail.value="";
		}
		if(document.getElementById("bind_rent").checked){			
				document.frm.bind_rent_about.disabled=false;
			
		}else{
				document.frm.bind_rent_about.disabled=true;
				document.frm.bind_rent_about.value="";
		}
		if(document.getElementById("bind_pawn").checked){			
				document.frm.bind_pawn_about.disabled=false;
			
		}else{
				document.frm.bind_pawn_about.disabled=true;
				document.frm.bind_pawn_about.value="";
		}
		
		
		
		
		
		
	});
});

$(function(){
	var dateBefore=null;
	$("#date").datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: 'button',
		buttonImage: 'images/calendar.gif',
		buttonImageOnly: true,
		dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'], 
		monthNamesShort: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
		changeMonth: true,
		changeYear: true ,
		beforeShow:function(){
			if($(this).val()!=""){
				var arrayDate=$(this).val().split("-");		
				arrayDate[2]=parseInt(arrayDate[2])-543;
				$(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);
			}
			setTimeout(function(){
				$.each($(".ui-datepicker-year option"),function(j,k){
					var textYear=parseInt($(".ui-datepicker-year option").eq(j).val())+543;
					$(".ui-datepicker-year option").eq(j).text(textYear);
				});				
			},50);

		},
		onChangeMonthYear: function(){
			setTimeout(function(){
				$.each($(".ui-datepicker-year option"),function(j,k){
					var textYear=parseInt($(".ui-datepicker-year option").eq(j).val())+543;
					$(".ui-datepicker-year option").eq(j).text(textYear);
				});				
			},50);		
		},
		onClose:function(){
			if($(this).val()!="" && $(this).val()==dateBefore){			
				var arrayDate=dateBefore.split("-");
				arrayDate[2]=parseInt(arrayDate[2])+543;
				$(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);	
			}		
		},
		onSelect: function(dateText, inst){ 
			dateBefore=$(this).val();
			var arrayDate=dateText.split("-");
			arrayDate[2]=parseInt(arrayDate[2])+543;
			$(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);
		}

	});
	
});

			
function checkList(){


	if(document.getElementById("feature1").checked){
		if(document.frm.height1.value==""){
			alert('กรุณากรอกความสูงของ ตึกแถว ด้วยครับ');
			return false;
			}	
	}
	if(document.getElementById("feature2").checked){
		if(document.frm.height2.value==""){
			alert('กรุณากรอกความสูงของ ทาวน์เฮ้าส์ ด้วยครับ');
			return false;
			}	
	}
	if(document.getElementById("feature3").checked){
		if(document.frm.height3.value==""){
			alert('กรุณากรอกความสูงของ บ้านเดี่ยว ด้วยครับ');
			return false;
			}	
	}
	if(document.getElementById("feature4").checked){
		if(document.frm.height4.value==""){
			alert('กรุณากรอกความสูงของ บ้านแฝด ด้วยครับ');
			return false;
			}	
	}
	if(document.getElementById("feature5").checked){
		if(document.frm.height5.value==""){
			alert('กรุณากรอกความสูงของ อาคารพาณิชย์ ด้วยครับ');
			return false;
			}	
	}
	if(document.getElementById("feature6").checked){
		if(document.frm.feature_other.value==""){
			alert('กรุณากรอกลักษณะของ หลักทรัพย์ ด้วยครับ');
			return false;
			}
		if(document.frm.height6.value==""){
			
			alert('กรุณากรอกความสูงของ หลักทรัพย์ด้วยครับ');
			return false;
			}	
	}
	if(!document.getElementById("wall_brick").checked && !document.getElementById("wall_wood_brick").checked && !document.getElementById("wall_wood").checked && !document.getElementById("wall_other").checked){
			alert('กรุณาเลือกลักษณะของ ฝาผนัง ด้วยครับ');
			return false;

	}
	if(document.getElementById("wall_other").checked){
		if(document.frm.wall_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ  ฝาผนังที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(!document.getElementById("ground_top_con").checked && !document.getElementById("ground_top_wood").checked && !document.getElementById("ground_top_parquet").checked && !document.getElementById("ground_top_ceramic").checked && !document.getElementById("ground_top_other").checked){
			alert('กรุณาเลือกลักษณะของ พื้นชั้นบน ด้วยครับ');
			return false;

	}
	if(document.getElementById("ground_top_other").checked){
		if(document.frm.ground_top_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ  พื้นชั้นบน ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(!document.getElementById("ground_bot_con").checked && !document.getElementById("ground_bot_wood").checked && !document.getElementById("ground_bot_parquet").checked && !document.getElementById("ground_bot_ceramic").checked && !document.getElementById("ground_bot_other").checked){
			alert('กรุณาเลือกลักษณะของ พื้นชั้นล่าง ด้วยครับ');
			return false;

	}
	if(document.getElementById("ground_bot_other").checked){
		if(document.frm.ground_bot_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ  พื้นชั้นล่าง ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(!document.getElementById("roof_frame_iron").checked && !document.getElementById("roof_frame_con").checked && !document.getElementById("roof_frame_wood").checked && !document.getElementById("roof_frame_unknow").checked && !document.getElementById("roof_frame_other").checked){
			alert('กรุณาเลือกลักษณะของ โครงหลังคา ด้วยครับ');
			return false;

	}
	if(document.getElementById("roof_frame_other").checked){
		if(document.frm.roof_frame_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ โครงหลังคา ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(!document.getElementById("roof_zine").checked && !document.getElementById("roof_deck").checked && !document.getElementById("roof_tile_duo").checked && !document.getElementById("roof_tile_monern").checked && !document.getElementById("roof_other").checked){
			alert('กรุณาเลือกลักษณะของ วัสดุมุงหลังคา ด้วยครับ');
			return false;

	}
	if(document.getElementById("roof_other").checked){
		if(document.frm.roof_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ วัสดุมุงหลังคา ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("roof_frame_other").checked){
		if(document.frm.roof_frame_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ โครงหลังคา ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("rest_other").checked){
		if(document.frm.rest_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียดลักษณะของห้องน้ำ  ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("door_other").checked){
		if(document.frm.door_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ ประตู ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("window_other").checked){
		if(document.frm.window_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ หน้าต่าง ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("ceiling_other").checked){
		if(document.frm.ceiling_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด ลักษณะของ ฝ้าเพดาน ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.frm.quan_cave.value=="" && document.frm.quan_units.value=="" && document.frm.quan_room.value==""){
			alert('กรุณาระบุ จำนวนคูหา หรือ จำนวนหลัง หรือ จำนวนห้อง  ด้วยครับ');
			return false;
	
	}
	if(document.frm.build_inside_area.value==""){
			alert('กรุณาระบุ พื้นที่ภายในอาคาร  ด้วยครับ');
			return false;
	
	}
	if(!document.getElementById("useful_home").checked && !document.getElementById("useful_commerce").checked && !document.getElementById("useful_rent").checked && !document.getElementById("useful_stored").checked && !document.getElementById("useful_industry").checked && !document.getElementById("useful_agriculture").checked && !document.getElementById("useful_other").checked){
			alert('กรุณาระบุ การใช้ประโยชน์ของหลักทรัพย์ ในปัจจุบัน ด้วยครับ');
			return false;
	
	}
	if(document.getElementById("useful_other").checked){
		if(document.frm.useful_other_detail.value==""){
			alert('กรุณาระบุ รายละเอียด  การใช้ประโยชน์ของหลักทรัพย์ ในปัจจุบัน ที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("position_address_navigation").checked){
		if(document.frm.position_address_navigation_name.value==""){
			alert('กรุณาระบุ ชื่อผู้นำชี้ ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("land_state_hole").checked){
		if(document.frm.land_state_hole_about1.value==""){
			alert('กรุณาระบุ ความลึกของบ่อด้วยครับ ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("land_level_height").checked){
		if(document.frm.land_level_height_about.value==""){
			alert('กรุณาระบุ ระดับของหลักทรัพย์ที่สูงกว่าถนน ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("land_level_low").checked){
		if(document.frm.land_level_low_about.value==""){
			alert('กรุณาระบุ ระดับของหลักทรัพย์ที่ต่ำกว่าถนน ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("road_state").checked){
		if(document.frm.road_state_detail.value==""){
			alert('กรุณาระบุ ลักษณะของถนนที่เพิ่มมา ด้วยครับ');
			return false;
			}
	}
	if(document.getElementById("bind_pawn").checked){
		if(document.frm.bind_pawn_about.value==""){
			alert('กรุณาระบุ ภาระจำนองให้ครบ ด้วยครับ');
			return false;
			}
	}
	else{
	return true;
	}
 }

var counter = 1;
function fncCreateElement(){
		
		counter++;
	   var mySpan = document.getElementById('mySpan');
	
	   var myElement1 = document.createElement('input');
	    myElement1.setAttribute('type',"file");
	    myElement1.setAttribute('name',"fileup[]");
		myElement1.setAttribute('id',"fileup" + counter);		
		mySpan.appendChild(myElement1);		
}

function fncRemoveElement(){

	   var mySpan = document.getElementById('mySpan'); 
		if(counter > 1 )
		{
			var deleteFile = document.getElementById("fileup" + counter);
			mySpan.removeChild(deleteFile);
			counter--;
		}
}


</script>


<body>
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
		
<?php 
	
			$appsecurID = $_GET['appsecurID'];
			$id = $_GET['securID'];
			if($id == ""){
			
				echo "<hr width=850>";
				echo "<center><h1> ไม่มีข้อมูล ...</h1></center>";
				exit();
			}
			
				$sql6 = @pg_query("select * from \"nw_securities\" where \"securID\" = '$id'");
				$row6 = @pg_num_rows($sql6);
				if($row6 == 0){
						echo "<hr width=850>";
						echo "<center><h1> ไม่พบข้อมูล... </h1></center>";
						exit();
				}
					$sql = pg_query("select * from \"nw_securities_detail\" where \"securID\" = '$id'");
					$row = pg_num_rows($sql);
					
					
					
					if($row == 0){
					
					
						$sql1 = pg_query("select * from \"approve_securities_detail\" where \"securID\" = '$id' and (\"status\" = '0' OR \"status\" = '2')");
						$row1 = pg_num_rows($sql1);
							if($row1 == 0){
				
								echo "<hr width=850>";
								echo "<center><h1> ยังไม่มีการเพิ่มการประเมิน </h1></center>";
								?> 
								<center><input type="button" value="เพิ่มการประเมินหลักทรัพย์" onclick="parent.location.href='estimate.php?deed=<?php echo $deed ?>&securID=<?php echo $id ?>'"></center>						
								<?php
								exit();
							}else{
							
								echo "<hr width=850>";
								echo "<center><h1> มีการประเมินหลักทรัพย์นี้ไปแล้ว กำลังอยู่ในระหว่างการรออนุมัติ </h1></center>";
								exit();
							}
					}else{
					
							$re = pg_fetch_array($sql);
							$id_user = $re['id_user'];
							$CusID = $re['CusID'];
							$auditorID = $re['id_auditor'];
							

					}
		

		//พนักงานคีย์ข้อมูล
			$strSQL3 = "SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'";
			$objQuery3 = pg_query($strSQL3);
			$results3 = pg_fetch_array($objQuery3);
				
		//ลูกค้า
			$strSQL4 = "SELECT * FROM \"Fa1\" where \"CusID\" = '$CusID'";
			$objQuery4 = pg_query($strSQL4);
			$results4 = pg_fetch_array($objQuery4);
			
		//พนักงานคีย์ข้อมูล
			$strSQL5 = "SELECT * FROM \"fuser\" where \"id_user\" = '$auditorID'";
			$objQuery5 = pg_query($strSQL5);
			$results5 = pg_fetch_array($objQuery5);
			
		//เลขที่โฉนด
			
			$ssql = pg_query("select * from \"nw_securities\" where \"securID\" = '$id'");
			$showdeed1 = pg_fetch_array($ssql);
			$deed = "โฉนดเลขที่ ".$showdeed1['numDeed'];
?>


<form name="frm" method="POST" action="edit_query.php" enctype="multipart/form-data">
<center><legend><h2>... Checker Department ...</h2></legend></center>
<center><legend><h3> แก้ไขการตรวจสอบและการประเมินราคาหลักทรัพย์ </h3></legend></center>
<hr width="850">
	<table width="850" cellSpacing="1" cellPadding="2" border="1"  align="center">
	
	<!--hidden-->
		<input type="hidden" name="appsecurID" value="<?php echo $appsecurID; ?>">
		<input type="hidden" name="securID" value="<?php echo $id; ?>">
		<input type="hidden" name="auditors" value="<?php echo $auditorID; ?>">
		<input type="hidden" name="customer" value="<?php echo $CusID; ?>">
			<tr>
				<td align="center" colspan="2" ><h2><b><?php echo $deed; ?></b></h2></td>
			</tr>
			<tr>
				<td align="left" >ชื่อลูกค้า :  <?php echo trim($results4["A_NAME"])." ".trim($results4["A_SIRNAME"]);?></td>
				<td align="left" >ผู้ตรวจสอบ : <?php echo trim($results5["fname"])." ".trim($results5["lname"]);?></td>
			</tr>
			<tr>
				<td align="left" >พนักงานเพิ่มข้อมูล: <?php echo $results3["fname"]." ".$results3["lname"];?></td>
				
				<?php   $datesame=$re['date'];
						list($year,$month,$day)=explode("-",$datesame);
						$year1 = $year + 543;
						$date = $day."-".$month."-".$year1;
				
				?>
				
				<td align="left" >วันที่สำรวจ : <input type="text" name="date" readonly="true" id="date" value="<?php  echo $date;?>" onchange="chkdate();"></td>
			</tr>
	</table>
	<table width="850" cellSpacing="0" cellPadding="0" border="0" align="center">
			<tr>
				<td><legend><h4> ลักษณะของสิ่งปลูกสร้าง/อาคาร/โครงสร้างตัวอาคาร </h4></legend></td>
			</tr>
	</table>
	<table width="850" cellSpacing="1" cellPadding="2" frame="BORDER" align="center">
			<tr>			
				<td width="80" align="left">ลักษณะ <font color="red">* </font>: </td>		
				<td width="200"><input type="radio" name="feature" id="feature1" value="1" <?php if($re['feature'] == 1){ echo "checked"; } ?> >ตึกแถวสูง <input type="text" size="7" name="height1" <?php if($re['feature'] == 1){ ?> value=<?php echo $re['height']; } ?>> ชั้น</td>
				<td width="200"><input type="radio" name="feature" id="feature2" value="2" <?php if($re['feature'] == 2){ echo "checked"; } ?> >ทาวน์เฮ้าส์ สูง <input type="text" size="7" name="height2" <?php if($re['feature'] == 2){ ?> value=<?php echo $re['height']; } ?>> ชั้น</td>
				<td width="200"><input type="radio" name="feature" id="feature3" value="3" <?php if($re['feature'] == 3){ echo "checked"; } ?> >บ้านเดี่ยวตึก สูง  <input type="text" size="7" name="height3" <?php if($re['feature'] == 3){ ?> value=<?php echo $re['height']; } ?>> ชั้น</td>			
			</tr>
			<tr>
			
				<td></td>
				<td><input type="radio" name="feature" id="feature4" value="4" <?php if($re['feature'] == 4){ echo "checked"; } ?> >บ้านแฝด สูง<input type="text" size="7" name="height4" <?php if($re['feature'] == 4){ ?> value=<?php echo $re['height']; } ?>> ชั้น</td>
				<td><input type="radio" name="feature" id="feature5" value="5" <?php if($re['feature'] == 5){ echo "checked"; } ?> >อาคารพาณิชย์ สูง<input type="text" size="7" name="height5" <?php if($re['feature'] == 5){ ?> value=<?php echo $re['height']; } ?>> ชั้น</td>
				<td colspan="2"><input type="radio" name="feature" id="feature6" value="6" <?php if($re['feature'] == 6){ echo "checked"; } ?> >อื่นๆ <input type="text" size="7" name="feature_other" <?php if($re['feature'] == 6){ ?> value=<?php echo $re['feature_other']; } ?>> สูง <input type="text" size="7" name="height6" value=<?php if($re['feature'] == 6){ echo $re['height']; } ?>> ชั้น</td>			
			</tr>
			<tr>
								
				<td colspan="2">ขนาดอาคาร <input type="text" size="12" name="size_build" value=<?php echo $re['size_build']; ?> > เมตร</td>
				<td colspan="2">พื้นที่ใช้สอยรวม <input type="text" size="12" name="size_area" value=<?php echo $re['size_area']; ?> > ตารางเมตร</td>		
			</tr>
			<tr>
							
				<td colspan="4"> โครงสร้างหลักของอาคาร <input type="text" size="20" name="struncture_build" value=<?php echo $re['structure_build']; ?>></td>
					
			</tr>
	</table>
	
	<table width="850" cellSpacing="1" cellPadding="2" frame="BORDER" align="center">
			
			<tr bgcolor="#DFE6EF">
				<td width="150" align="center">ฝาผนัง <font color="red">* </font></td>
				<td width="150" align="center">พื้นชั้นบน <font color="red">* </font></td>
				<td width="150" align="center">พื้นชั้นล่าง <font color="red">* </font></td>
				<td width="150" align="center">โครงหลังคา <font color="red">* </font></td>
				<td width="150" align="center">วัสดุมุงหลังคา <font color="red">* </font></td>
				
			</tr>
			<tr>
			</tr>
			<tr>
				<td><input type="checkbox" name="wall_brick" value="1" id="wall_brick" <?php if($re['wall_brick'] == 1){ echo "checked"; } ?>>ก่ออิฐ</td>
				<td><input type="checkbox" name="ground_top_con" value="1" id="ground_top_con" <?php if($re['ground_top_con'] == 1){ echo "checked"; } ?>>คอนกรีตปูด้วย</td>
				<td><input type="checkbox" name="ground_bot_con" value="1" id="ground_bot_con" <?php if($re['ground_bot_con'] == 1){ echo "checked"; } ?>>คอนกรีตปูด้วย</td>
				<td><input type="checkbox" name="roof_frame_iron" value="1" id="roof_frame_iron" <?php if($re['roof_frame_iron'] == 1){ echo "checked"; } ?>>เหล็ก</td>
				<td><input type="checkbox" name="roof_zine" value="1" id="roof_zine" <?php if($re['roof_zine'] == 1){ echo "checked"; } ?>>สังกะสี</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" name="wall_wood_brick" id="wall_wood_brick" value="1" <?php if($re['wall_wood_brick'] == 1){ echo "checked"; } ?>>ก่ออิฐ/ไม้</td>
				<td><input type="checkbox" name="ground_top_wood" id="ground_top_wood" value="1"  <?php if($re['ground_top_wood'] == 1){ echo "checked"; } ?>>ไม้</td>
				<td><input type="checkbox" name="ground_bot_wood" id="ground_bot_wood" value="1" <?php if($re['ground_bot_wood'] == 1){ echo "checked"; } ?>>ไม้</td>
				<td><input type="checkbox" name="roof_frame_con"  id="roof_frame_con" value="1" <?php if($re['roof_frame_con'] == 1){ echo "checked"; } ?>>คอนกรีต</td>
				<td><input type="checkbox" name="roof_deck" id="roof_deck" value="1" <?php if($re['roof_deck'] == 1){ echo "checked"; } ?>>ดาดฟ้า</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" name="wall_wood" id="wall_wood" value="1" <?php if($re['wall_wood'] == 1){ echo "checked"; } ?>>ไม้</td>				
				<td><input type="checkbox" name="ground_top_parquet" id="ground_top_parquet" value="1" <?php if($re['ground_top_parquet'] == 1){ echo "checked"; } ?>>ปาร์เก้</td>
				<td><input type="checkbox" name="ground_bot_parquet" id="ground_bot_parquet" value="1" <?php if($re['ground_bot_parquet'] == 1){ echo "checked"; } ?>>ปาร์เก้</td>
				<td><input type="checkbox" name="roof_frame_wood" id="roof_frame_wood" value="1" <?php if($re['roof_frame_wood'] == 1){ echo "checked"; } ?>>ไม้</td>
				<td><input type="checkbox" name="roof_tile_duo" id="roof_tile_duo" value="1" <?php if($re['roof_tile_duo'] == 1){ echo "checked"; } ?>>กระเบื้องลอนคู่</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" name="wall_other" id="wall_other" value="1" <?php if($re['wall_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" size="10" name="wall_other_detail" <?php if($re['wall_other'] == 1){ ?> value=<?php echo $re['wall_other_detail']; } ?>></td>
				<td><input type="checkbox" name="ground_top_ceramic"  id="ground_top_ceramic" value="1"  <?php if($re['ground_top_ceramic'] == 1){ echo "checked"; } ?>>เซรามิค</td>
				<td><input type="checkbox" name="ground_bot_ceramic" id="ground_bot_ceramic" value="1" <?php if($re['ground_bot_ceramic'] == 1){ echo "checked"; } ?>>เซรามิค</td>
				<td><input type="checkbox" name="roof_frame_unknow" id="roof_frame_unknow" value="1" <?php if($re['roof_frame_unknow'] == 1){ echo "checked"; } ?>>ตรวจสอบไม่ได้</td>
				<td><input type="checkbox" name="roof_tile_monern" id="roof_tile_monern" value="1" <?php if($re['roof_tile_monern'] == 1){ echo "checked"; } ?>>กระเบื้องโมเนียร์</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="ground_top_other" id="ground_top_other" value="1" <?php if($re['ground_top_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" size="10" name="ground_top_other_detail" <?php if($re['ground_top_other'] == 1){ ?> value=<?php echo $re['ground_top_other_detail']; } ?>></td>
				<td><input type="checkbox" name="ground_bot_other" id="ground_bot_other" value="1" <?php if($re['ground_bot_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" size="10" name="ground_bot_other_detail" <?php if($re['ground_bot_other'] == 1){ ?> value=<?php echo $re['ground_bot_other_detail']; } ?>></td>
				<td><input type="checkbox" name="roof_frame_other" id="roof_frame_other" value="1" <?php if($re['roof_frame_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" size="10" name="roof_frame_other_detail" <?php if($re['roof_frame_other'] == 1){ ?> value=<?php echo $re['roof_frame_other_detail']; } ?>></td>
				<td><input type="checkbox" name="roof_other" id="roof_other" value="1" <?php if($re['roof_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" size="10" name="roof_other_detail" <?php if($re['roof_other'] == 1){ ?> value=<?php echo $re['roof_other_detail']; } ?>></td>
			</tr>
			<tr bgcolor="#DFE6EF"> 
				<td align="center">ฝ้าเพดาน</td>
				<td align="center">ประตู</td>
				<td align="center">หน้าต่าง</td>
				<td align="center">ห้องน้ำ และสุขภัณฑ์</td>
				<td align="center">จำนวนคูหา/หลัง <font color="red">* </font></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ceiling_gypsum" value="1" <?php if($re['ceiling_gypsum'] == 1){ echo "checked"; } ?>>ยิปซั่มบอร์ดฉาบเรียบ</td>
				<td><input type="checkbox" name="door_wood" value="1" <?php if($re['door_wood'] == 1){ echo "checked"; } ?>>บานเปิดไม้</td>
				<td><input type="checkbox" name="window_open_glass" value="1" <?php if($re['window_open_glass'] == 1){ echo "checked"; } ?>>บานเปิดกระจก</td>
				<td><input type="checkbox" name="rest_wc" value="1" <?php if($re['rest_wc'] == 1){ echo "checked"; } ?>>โถชักโครก</td>
				<td align="left"><input type="text" size="15" name="quan_cave" value=<?php echo $re['quan_cave']; ?>> คูหา</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ceiling_tile" value="1" <?php if($re['ceiling_tile'] == 1){ echo "checked"; } ?>>กระเบื้องแผ่นเรียบ</td>
				<td><input type="checkbox" name="door_glass" value="1" <?php if($re['door_glass'] == 1){ echo "checked"; } ?>>บานเปิดกระจก</td>
				<td><input type="checkbox" name="window_slide_glass" value="1" <?php if($re['window_slide_glass'] == 1){ echo "checked"; } ?>>บานเลื่อนกระจก</td>
				<td><input type="checkbox" name="rest_basin" value="1" <?php if($re['rest_basin'] == 1){ echo "checked"; } ?>>อ่างล้างหน้า</td>
				<td align="left">(**2)<input type="text" size="10" name="quan_units" value=<?php echo $re['quan_unit']; ?>> หลัง</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ceiling_structure" value="1" <?php if($re['ceiling_structure'] == 1){ echo "checked"; } ?>>คสล.</td>
				<td><input type="checkbox" name="door_plywood" value="1" <?php if($re['door_plywood'] == 1){ echo "checked"; } ?>>ไม้อัด</td>
				<td><input type="checkbox" name="window_scale_glass" value="1" <?php if($re['window_scale_glass'] == 1){ echo "checked"; } ?>>บานเกล็ดกระจก</td>
				<td><input type="checkbox" name="rest_tub" value="1" <?php if($re['rest_tub'] == 1){ echo "checked"; } ?>>อ่างอาบน้ำ</td>
				<td align="left"><input type="text" size="15" name="quan_room" value=<?php echo $re['quan_room']; ?>> ห้อง</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ceiling_nothing" value="1" <?php if($re['ceiling_nothing'] == 1){ echo "checked"; } ?>>ไม่มี</td>
				<td><input type="checkbox" name="door_iron" value="1" <?php if($re['door_iron'] == 1){ echo "checked"; } ?>>เหล็ก</td>
				<td><input type="checkbox" name="window_wood" value="1" <?php if($re['window_wood'] == 1){ echo "checked"; } ?>>กระจกกรอบไม้</td>
				<td><input type="checkbox" name="rest_other" id="rest_other" value="1" <?php if($re['rest_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" size="10" name="rest_other_detail" <?php if($re['rest_other'] == 1){ ?> value=<?php echo $re['rest_other_detail']; } ?>></td>
				<td align="left"></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ceiling_other" id="ceiling_other" value="1"  <?php if($re['ceiling_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" size="10" name="ceiling_other_detail" <?php if($re['ceiling_other'] == 1){ ?> value=<?php echo $re['ceiling_other_detail']; } ?>></td>
				<td><input type="checkbox" name="door_other" id="door_other" value="1" <?php if($re['door_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" size="10" name="door_other_detail" <?php if($re['door_other'] == 1){ ?> value=<?php echo $re['door_other_detail']; } ?>></td>
				<td><input type="checkbox" name="window_other" id="window_other" value="1" <?php if($re['window_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" size="10" name="window_other_detail" <?php if($re['window_other'] == 1){ ?> value=<?php echo $re['window_other_detail']; } ?>></td>
				<td></td>
				<td align="left">อยู่ชั้นที่ <input type="text" size="10" name="floor_number" value=<?php echo $re['floor_number']; ?>></td>
			</tr>
			<tr >
				<td colspan="5">
					<table width="850" cellSpacing="1" cellPadding="2"  align="center">
						<tr bgcolor="#DFE6EF">
							<td width="150" align="left">อุปกรณ์ดับเพลิง</td>
							<td width="150" align="left">ความสูงภายในห้อง</td>
							<td width="150" align="left">พื้นที่ภายในอาคาร <font color="red">* </font></td>
							<td width="150" align="center">ระยะห่างของหลังคา</td>
						</tr>
						<tr>
							<td  align="left">
								<input type="radio" name="fire" value="1" <?php if($re['fire'] == 1){ echo "checked"; } ?>> มี 
								<input type="radio" name="fire" value="2" <?php if($re['fire'] == 2){ echo "checked"; } ?>> ไม่มี
							</td>
							<td  align="left"><input type="text" size="10" name="room_height" value=<?php echo $re['room_height']; ?>> เมตร</td>
							<td  align="left"><input type="text" size="10" name="build_inside_area" value=<?php echo $re['build_inside_area']; ?>> ตรม.</td>
							<td  align="center">(**1)<input type="text" size="10" name="roof_interval" value=<?php echo $re['roof_interval']; ?>> เมตร</td>
						</tr>
						<tr>
							<td align="left" colspan="3">
								<div style="float:right;">จำนวนโฉนดที่ </div>
							</td>						
							<td align="center">(**3)<input type="text" size="10" name="deed_quantity" value=<?php echo $re['Deed_quantity']; ?> > ฉบับ</td>							
						</tr>
						<tr>
							<td align="left" colspan="2"><u>ราคาประเมินข้างเคียง</u>
								<input type="text" size="20" name="cost_near" value=<?php echo $re['cost_near']; ?>> บาท
							<td align="left" colspan="2"><u>ราคาประเมินของเช็คเกอร์</u>
								<input type="text" size="20" name="cost_checker" value=<?php echo $re['cost_checker']; ?>> บาท
							</td>							
						</tr>
					</table>
					<table width="850" cellSpacing="1" cellPadding="2"  align="center">
						<tr>			
							<td width="70" align="left"><u>หมายเหตุ </u></td>
							<td align="left" colspan="3">
								**1. กรณีเป็นบ้านเดี่ยวต้องแจ้งว่า หลังคาบ้านมีระยะห่างกับบ้านที่อยู่ใกล้เคียง 4 ทิศ เป็นระยะห่างกี่เมตร
							</td>							
						</tr>
						<tr>
							<td></td>
							<td align="left" colspan="3">
								**2. กรณีบ้านหลายหลังตั้งอยู่ในโฉนดเดียวกัน ต้องแจ้งรายละเอียดของบ้านแต่ละหลังที่อยู่ในบริเวณนั้นๆด้วย
							</td>							
						</tr>
						<tr>
							<td></td>
							<td align="left" colspan="3">
								**3. กรณีโฉนดหลายฉบับ และมีบ้านตั้งอยู่บนโฉนดเหล่านั้น ต้องแจ้งรายละเอียดเพิ่มเติมด้วย
							</td>	
						</tr>
					</table>
				</td>
			</tr>
	</table>
	<table width="850" cellSpacing="1" cellPadding="2"  align="center">
		<tr>
			<td width="20"></td>
			<td width="200"></td>
			<td width="200"></td>
			<td width="200"></td>
			<td width="200"></td>
		</tr>
		<tr>
			<td>1.</td>
			<td colspan="5">ประเภทเอกสารสิทธิ์</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" name="typedocument" value="1" <?php if($re['typedocument'] == 1){ echo "checked"; } ?>> ที่ดินว่างเปล่า</td>
			<td><input type="radio" name="typedocument" value="2" <?php if($re['typedocument'] == 2){ echo "checked"; } ?>> ที่ดิน+บ้าน</td>
			<td><input type="radio" name="typedocument" value="3" <?php if($re['typedocument'] == 3){ echo "checked"; } ?>> คอนโด</td>
			<td></td>			
		</tr>
		<tr>
			<td></td>
			<td>ประเภทอาคารบ้าน <input type="text" size="3" name="typebuild_floor" value=<?php echo $re['typebuild_floor']; ?>> ชั้น</td>
			<td><input type="radio" name="typebuild" value="3" <?php if($re['typebuild'] == 3){ echo "checked"; } ?>> ปูน+ไม้</td>
			<td><input type="radio" name="typebuild" value="2" <?php if($re['typebuild'] == 2){ echo "checked"; } ?>> ไม้</td>
			<td><input type="radio" name="typebuild" value="1" <?php if($re['typebuild'] == 1){ echo "checked"; } ?>> ปูน</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="4">
				ขนาดสิ่งปลูกสร้าง กว้าง <input type="text" size="5" name="size_build_width" value=<?php echo $re['size_build_width']; ?>> เมตร	
				ยาว <input type="text" size="5" name="size_build_long" value=<?php echo $re['size_build_long']; ?>> เมตร
			</td>			
		</tr>
		<tr>
			<td>2.</td>
			<td colspan="5">ชื่อผู้ถือกรรมสิทธิ์  <input type="text" size="20" name="deed_owner" value=<?php echo $re['deed_owner']; ?>></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" name="deed_owner_area" value="1" <?php if($re['deed_owner_area'] == 1){ echo "checked"; } ?>> ทั้งแปลง</td>
			<td colspan="3">
				<input type="radio" name="deed_owner_area" id="deed_owner_area" value="2" <?php if($re['deed_owner_area'] == 2){ echo "checked"; } ?>> เฉพาะส่วน
				ในอัตราส่วน <input type="text" size="10" name="deed_owner_area_size1" <?php if($re['deed_owner_area'] == 2){ ?> value=<?php echo $re['deed_owner_area_size1']; }?>> ใน <input type="text" size="10"name="deed_owner_area_size2" <?php if($re['deed_owner_area'] == 2){ ?> value=<?php echo $re['deed_owner_area_size2']; } ?>> ส่วน
			</td>			
		</tr>
		<tr>
			<td>3.</td>
			<td colspan="5">สถานที่ตั้ง </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5"><textarea rows="5" cols="80" name="address" ><?php echo $re['address']; ?></textarea></td>
		</tr>
		<tr>
			<td>4.</td>
			<td colspan="5">ตำแหน่งที่ตั้งที่ดิน </td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="checkbox" name="position_address_right_map" value="1" <?php if($re['position_add_right_map'] == 1){ echo "checked"; } ?>> ถูกต้องตรงกับรูปแผนที่ในหนังสือแสดงสิทธิ์</td>
			<td colspan="2"><input type="checkbox" name="position_address_fail_map" value="1" <?php if($re['position_add_fail_map'] == 1){ echo "checked"; } ?>> ไม่ตรงกับรูปแผนที่ในหนังสือแสดงสิทธิ์</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="checkbox" name="position_address_success" value="1" <?php if($re['position_add_success'] == 1){ echo "checked"; } ?>> พบหลักเขตที่ดิน</td>
			<td colspan="2"><input type="checkbox" name="position_address_fail" value="1" <?php if($re['position_add_fail'] == 1){ echo "checked"; } ?>> ไม่พบหลักเขตที่ดิน</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="4"><input type="checkbox" name="position_address_navigation" id="position_address_navigation" value="1" <?php if($re['position_add_navigation'] == 1){ echo "checked"; } ?>> ผู้นำชี้ คือ <input type="text" size="20" name="position_address_navigation_name" <?php if($re['position_add_navigation'] == 1){ ?> value=<?php echo $re['position_add_navigation_name']; } ?>></td>		
		</tr>
		<tr>
			<td>5.</td>
			<td colspan="5">รูปร่างของที่ดิน และสภาพของที่ดิน </td>			
		</tr>
		<tr>
			<td></td>
			<td>รูปร่างของที่ดิน</td>
			<td><input type="checkbox" name="land_shape_rectangle" value="1" <?php if($re['land_shape_rectangle'] == 1){ echo "checked"; } ?>> สี่เหลี่ยมผืนผ้า</td>
			<td><input type="checkbox" name="land_shape_square" value="1" <?php if($re['land_shape_square'] == 1){ echo "checked"; } ?>> สี่เหลี่ยมจัตุรัส</td>
			<td><input type="checkbox" name="land_shape_trapezuid" value="1" <?php if($re['land_shape_trapezuid'] == 1){ echo "checked"; } ?>> สี่เหลี่ยมคางหมู</td>	
					
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><input type="checkbox" name="land_shape_triangle" value="1" <?php if($re['land_shape_triangle'] == 1){ echo "checked"; } ?>> สามเหลี่ยม</td>	
			<td colspan="2"><input type="checkbox" name="land_shape_polygon" value="1" <?php if($re['land_shape_polygon'] == 1){ echo "checked"; } ?>> หลายเหลี่ยม</td>						
		</tr>
		<tr>
			<td></td>
			<td>สภาพของที่ดิน</td>
			<td><input type="checkbox" name="land_state_coverall" value="1" <?php if($re['land_state_coverall'] == 1){ echo "checked"; } ?>> ถมแล้วทั้งแปลง</td>
			<td colspan="2"><input type="checkbox" name="land_state_cover" id="land_state_cover" value="1" <?php if($re['land_state_cover'] == 1){ echo "checked"; } ?>> ถมบางส่วน ประมาณ 
			<?php list($a,$b)=explode("/",$re['land_state_cover_about']); ?>
			<input type="text" size="5" name="land_state_cover_about1" <?php if($re['land_state_cover'] == 1){ ?> value=<?php echo $a; } ?>> ใน 
			<input type="text" size="5" name="land_state_cover_about2" value=<?php echo $b; ?>> ส่วนของพื้นที่</td>				
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan="3">
				<input type="checkbox" name="land_state_hole"  id="land_state_hole" value="1" <?php if($re['land_state_hole'] == 1){ echo "checked"; } ?>> เป็นบ่อลึกประมาณ <input type="text" size="3" name="land_state_hole_about1" <?php if($re['land_state_hole'] == 1){ ?> value=<?php echo $re['land_state_hole_about1']; } ?>> เมตร  คิดเป็นเนื้อที่ประมาณ <input type="text" size="5" name="land_state_hole_about2" <?php if($re['land_state_hole'] == 1){ ?> value=<?php echo $re['land_state_hole_about2']; } ?>> ไร่
			</td>				
		</tr>
		<tr>
			<td></td>
			<td>ระดับของดิน</td>
			<td><input type="checkbox" name="land_level_match" value="1" <?php if($re['land_level_match'] == 1){ echo "checked"; } ?>> สูงเสมอถนน</td>
			<td colspan="2"><input type="checkbox" name="land_level_height" id="land_level_height" value="1" <?php if($re['land_level_height'] == 1){ echo "checked"; } ?>> สูงกว่าถนน ประมาณ <input type="text" size="10" name="land_level_height_about" <?php if($re['land_level_height'] == 1){ ?> value=<?php echo $re['land_level_height_about']; } ?>> เมตร</td>				
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan="3">
				<input type="checkbox" name="land_level_low" id="land_level_low"  value="1" <?php if($re['land_level_low'] == 1){ echo "checked"; } ?>> ต่ำกว่าถนนประมาณ  <input type="text" size="3" name="land_level_low_about" <?php if($re['land_level_low'] == 1){ ?> value=<?php echo $re['land_level_low_about']; } ?>> เมตร
			</td>				
		</tr>
		<tr>
			<td>6.</td>
			<td colspan="5">การคมนาคมผ่านด้านหน้าทรัพย์สิน </td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" name="communication" value="1" <?php if($re['communication'] == 1){ echo "checked"; } ?>>อยู่ริมถนนหลัก</td>
			<td><input type="radio" name="communication" value="2" <?php if($re['communication'] == 2){ echo "checked"; } ?>>อยู่ริมถนนย่อย</td>
			<td><input type="radio" name="communication" value="3" <?php if($re['communication'] == 3){ echo "checked"; } ?>>ที่ตาบอด</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>ประเภทถนน</td>
			<td><input type="radio" name="road_type" value="1" <?php if($re['road_type'] == 1){ echo "checked"; } ?>> สาธารณะ</td>
			<td><input type="radio" name="road_type" value="2" <?php if($re['road_type'] == 2){ echo "checked"; } ?>> ของโครงการ </td>	
			<td><input type="radio" name="road_type" value="3" <?php if($re['road_type'] == 3){ echo "checked"; } ?>> ส่วนบุคคล </td>
			<td></td>	
		</tr>
		<tr>
			<td></td>
			<td>ลักษณะถนน</td>
			<td><input type="radio" name="road_state" value="1" <?php if($re['road_state'] == 1){ echo "checked"; } ?>> คอนกรีตเสริมเหล็ก</td>
			<td><input type="radio" name="road_state" value="2" <?php if($re['road_state'] == 2){ echo "checked"; } ?>> ลาดยาง</td>	
			<td><input type="radio" name="road_state" value="3" <?php if($re['road_state'] == 3){ echo "checked"; } ?>> ลูกรัง</td>	
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><input type="radio" name="road_state" value="4" <?php if($re['road_state'] == 4){ echo "checked"; } ?>> หินคลุก</td>
			<td><input type="radio" name="road_state" value="5" <?php if($re['road_state'] == 5){ echo "checked"; } ?>> ดิน</td>
			<td><input type="radio" name="road_state" id="road_state" value="6" <?php if($re['road_state'] == 6){ echo "checked"; } ?>> อื่นๆ <input type="text" size="15" name="road_state_detail" <?php if($re['road_state'] == 6){ ?> value=<?php echo $re['road_state_detail']; } ?>></td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="4">ระยะทางจากหน้าถนนถึงหน้าทรัพย์สินประมาณ <input type="text" size="10" name="roadtobuild" value=<?php echo $re['road_to_build']; ?> > เมตร</td>					
		</tr>
		<tr>
			<td></td>
			<td>สภาพของถนน</td>	
			<td><input type="radio" name="road_status" value="1" <?php if($re['road_status'] == 1){ echo "checked"; } ?>> ดี</td>
			<td><input type="radio" name="road_status" value="2" <?php if($re['road_status'] == 2){ echo "checked"; } ?>> ปานกลาง</td>	
			<td><input type="radio" name="road_status" value="3" <?php if($re['road_status'] == 3){ echo "checked"; } ?>> ชำรุด</td>				
		</tr>
		<tr>
			<td></td>
			<td>รถยนต์สามารถเข้าถึงทรัพย์สิน</td>
			<td colspan="3">
				<input type="radio" name="road_vehicles" value="1" <?php if($re['road_vehicles'] == 1){ echo "checked"; } ?>> ได้
				<input type="radio" name="road_vehicles" value="2" <?php if($re['road_vehicles'] == 2){ echo "checked"; } ?>>ไม่ได้  เนื่องจากเป็นทางแคบรถยนต์ไม่สามารถเข้า-ออกได้
			</td>							
		</tr>
		<tr>
			<td>7.</td>
			<td colspan="5">การใช้ประโยชน์ปัจจุบัน <font color="red">* </font></td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="useful_home" value="1" <?php if($re['useful_home'] == 1){ echo "checked"; } ?>> ที่อยู่อาศัย</td>	
			<td><input type="checkbox" name="useful_commerce" value="1" <?php if($re['useful_commerce'] == 1){ echo "checked"; } ?>> พาณิชยกรรม</td>
			<td><input type="checkbox" name="useful_rent" value="1" <?php if($re['useful_rent'] == 1){ echo "checked"; } ?>> ให้เช่า</td>	
			<td><input type="checkbox" name="useful_stored" value="1" <?php if($re['useful_stored'] == 1){ echo "checked"; } ?>> เก็บไว้เฉยๆ</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="useful_industry" value="1" <?php if($re['useful_industry'] == 1){ echo "checked"; } ?>> อุตสาหกรรม</td>	
			<td><input type="checkbox" name="useful_agriculture" value="1" <?php if($re['useful_agriculture'] == 1){ echo "checked"; } ?>> เกษตรกรรม</td>
			<td colspan="2"><input type="checkbox" name="useful_other" id="useful_other" value="1" <?php if($re['useful_other'] == 1){ echo "checked"; } ?>> อื่นๆ <input type="text" size="10" name="useful_other_detail" <?php if($re['useful_other'] == 1){ ?> value=<?php echo $re['useful_other_detail']; } ?>></td>							
		</tr>
		<tr>
			<td>8.</td>
			<td>สาธารณูปโภค</td>	
			<td><input type="radio"  name="utilities" id="utilities" value="1" <?php if($re['utilities'] == 1){ echo "checked"; } ?>> มี</td>	
			<td><input type="radio"  name="utilities"  value="2" <?php if($re['utilities'] == 2){ echo "checked"; } ?>> ไม่มี</td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="utilities_electricity" value="1" <?php if($re['utilities_electricity'] == 1){ echo "checked"; } ?>> ไฟฟ้า</td>	
			<td><input type="checkbox" name="utilities_plumbing" value="1" <?php if($re['utilities_plumbing'] == 1){ echo "checked"; } ?>> ประปา</td>
			<td><input type="checkbox" name="utilities_phone" value="1" <?php if($re['utilities_phone'] == 1){ echo "checked"; } ?>> โทรศัพท์</td>	
			<td><input type="checkbox" name="utilities_drain" value="1" <?php if($re['utilities_drain'] == 1){ echo "checked"; } ?>> ท่อระบายน้ำ</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="utilities_groundwater" value="1" <?php if($re['utilities_groundwater'] == 1){ echo "checked"; } ?>> น้ำบาดาล</td>	
			<td><input type="checkbox" name="utilities_electricroad" value="1" <?php if($re['utilities_electri_road'] == 1){ echo "checked"; } ?>> ไฟฟ้าถนน</td>						
		</tr>
		<tr>
			<td>9.</td>
			<td>สภาพแวดล้อม </td>
			<td><input type="radio" name="environment" value="1" <?php if($re['environment'] == 3){ echo "checked"; } ?>> แย่</td>	
			<td><input type="radio" name="environment" value="1" <?php if($re['environment'] == 2){ echo "checked"; } ?>> ปานกลาง</td>
			<td><input type="radio" name="environment" value="1" <?php if($re['environment'] == 1){ echo "checked"; } ?>> ดี</td>					
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="environment_trade" value="1" <?php if($re['environment_trade'] == 1){ echo "checked"; } ?>> ย่านการค้า</td>	
			<td><input type="checkbox" name="environment_home" value="1" <?php if($re['environment_home'] == 1){ echo "checked"; } ?>> ย่านที่อยู่อาศัย</td>
			<td><input type="checkbox" name="environment_factory" value="1" <?php if($re['environment_factory'] == 1){ echo "checked"; } ?>> ย่านโรงงาน</td>	
			<td><input type="checkbox" name="environment_slum" value="1" <?php if($re['environment_slum'] == 1){ echo "checked"; } ?>> ย่านสลัม</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="environment_military" value="1" <?php if($re['environment_military'] == 1){ echo "checked"; } ?>> ใกล้ เขตทหาร</td>
			<td><input type="checkbox" name="environment_tomb" value="1" <?php if($re['environment_tomb'] == 1){ echo "checked"; } ?>> ใกล้ สุสาน</td>	
			<td><input type="checkbox" name="environment_shrine" value="1" <?php if($re['environment_shrine'] == 1){ echo "checked"; } ?>> ใกล้ ศาลเจ้า</td>	
			<td><input type="checkbox" name="environment_temple" value="1" <?php if($re['environment_temple'] == 1){ echo "checked"; } ?>> ใกล้ วัด/โบสถ์/มัสยิด</td>	
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="environment_highvoltage" value="1" <?php if($re['environment_highvoltage'] == 1){ echo "checked"; } ?>> ใกล้ รัสมีสายไฟฟ้าแรงสูง</td>	
			<td><input type="checkbox" name="environment_dirt" value="1" <?php if($re['environment_dirt'] == 1){ echo "checked"; } ?>> ใกล้สิงปฎิกูล/เขตอันตราย</td>	
		</tr>
		<tr>
			<td></td>
			<td colspan="3">สถานที่สำคัญบริเวณใกล้เคียงได้แก่  <input type="text" size="25" name="environment_closeplace" value=<?php echo $re['environment_closeplace']; ?>></td>	
		</tr>
		<tr>
			<td>10.</td>
			<td>ภาระผูกพัน </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="checkbox" name="bind_rent" id="bind_rent" value="1" <?php if($re['bind_rent'] == 1){ echo "checked"; } ?>> ภาระการเช่า คงเหลือ <input type="text" size="20" name="bind_rent_about" <?php if($re['bind_rent'] == 1){ ?> value=<?php echo $re['bind_rent_about']; } ?>></td>	
			<td colspan="2"><input type="checkbox" name="bind_pawn" id="bind_pawn" value="1" <?php if($re['bind_pawn'] == 1){ echo "checked"; } ?>> ภาระจำนองกับ <input type="text" size="20"name="bind_pawn_about" <?php if($re['bind_pawn'] == 1){ ?> value=<?php echo $re['bind_pawn_about']; } ?>></td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="3">
				<input type="checkbox" name="bind_all" value="1" <?php if($re['bind_all'] == 1){ echo "checked"; } ?>> ภาระจำยอมทั้งแปลง	
				<input type="checkbox" name="bind_rentbuy" value="1" <?php if($re['bind_rentbuy'] == 1){ echo "checked"; } ?>> อยู่ในระหว่างสัญญาเช่า-ซื้อ
				<input type="checkbox" name="bind_nothing" value="1" <?php if($re['bind_nothing'] == 1){ echo "checked"; } ?>> ไม่มีภาระผูกพันใดๆ
			</td>					
		</tr>
		<tr>
			<td>11.</td>
			<td>การเวนคืน </td>
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" name="expropriate" value="1" <?php if($re['expropriate'] == 1){ echo "checked"; } ?>> อาจถูกเวนคืน</td>	
			<td><input type="radio" name="expropriate" value="2" <?php if($re['expropriate'] == 2){ echo "checked"; } ?>> ไม่มีการเวนคืน</td>				
		</tr>
		<tr>
			<td>12.</td>
			<td colspan="4">แนวโน้มความเจริญหรือการพัฒนา </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3">โครงการพัฒนาของรัฐ</td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> ที่มีอยู่แล้วคือ <input type="text" size="40" name="advancementnow" value=<?php echo $re['advancementnow']; ?>></td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> อยู่ระหว่างการดำเนินการ ทำอะไร <input type="text" size="40" name="advancementcontinue" value=<?php echo $re['advancementcontinue']; ?>></td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="3">แนวโน้มความเจริญหรือการพัฒนา</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" name="advancement" value="1" <?php if($re['advancement'] == 1){ echo "checked"; } ?>> น้อย</td>	
			<td><input type="radio" name="advancement" value="2" <?php if($re['advancement'] == 2){ echo "checked"; } ?>> ปานกลาง</td>
			<td><input type="radio" name="advancement" value="3" <?php if($re['advancement'] == 3){ echo "checked"; } ?>> มาก</td>				
		</tr>
		<tr>
			<td>13.</td>
			<td colspan="4">สภาพทั่วไปของทรัพย์สิน (ระบรายละเอียดส่วนที่ชำรุด) </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3"><input type="radio" name="generality" id="generality" value="1" <?php if($re['generality'] == 1){ echo "checked"; } ?>> มี ถ้าชำรุดมีส่วนต่างๆ <input type="text" size="40" name="generality_detail" <?php if($re['generality'] == 1){ ?> value=<?php echo $re['generality_detail']; } ?>></td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" name="generality" value="2" <?php if($re['generality'] == 2){ echo "checked"; } ?>> ไม่มี</td>	
		</tr>
		<tr>
			<td>14.</td>
			<td colspan="4">ประกาศขายบ้าน(ข้างเคียง) </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> ลักษณะบ้านที่ขาย <input type="text" size="25" name="nearhomestatus" value=<?php echo $re['nearhome_status']; ?>> กี่ตารางวา <input type="text" size="25" name="nearhomesize" value=<?php echo $re['nearhomesize']; ?>></td>	
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> เบอร์โทร <input type="text" size="25" name="nearhometel" value=<?php echo $re['nearhometel']; ?>> ราคาขาย <input type="text" size="25" name="nearhomeprice" value=<?php echo $re['nearhomeprice']; ?>></td>	
		</tr>
		<tr>
			<td>15.</td>
			<td colspan="4">สำนักงานกรมที่ดิน  <input type="text" size="25" name="landoffice" value=<?php echo $re['landoffice']; ?>> สาขาที่จด  <input type="text" size="25" name="landoffice_branch" value=<?php echo $re['landoffice_branch']; ?>></td>
		</tr>
		<tr>
			<td>16.</td>
			<td colspan="4">ความรู้สึกของฝ่ายตรวจสอบ </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5"><textarea rows="5" cols="80" name="feel_checker"> <?php echo $re['feel_checker']; ?></textarea></td>
		</tr>
	
		<tr>
			<td colspan="5"><B> ไฟล์แนบ</b></td>
			
		</tr>
		<tr>
			<td></td>
			<td colspan="4" bgcolor="#FFFFFF">
			
						<?php
						
							$qry_name9 = pg_query("select \"file\",\"securID\" from \"nw_securities_detail\" where \"securID\" = '$id'");					
							$result9=pg_fetch_array($qry_name9);						
							$ff = $result9["file"];
							$file=explode("!#",$ff);	
						for($i=1;$i<sizeof($file);$i++){
						?>							
						<a href="fileupload/<?php echo $result9["securID"];?>/<?php echo $file[$i];?>" target="_blank"><?php echo $file[$i];?>
						<br>
						<?php } ?>	
						
				<input type="hidden" value="<?php echo $ff ?>" name="filesame">		
			</td>
		</tr>
		<tr>
			<td colspan="5"><B> แนบไฟล์ใหม่</b></td>
			
		</tr>
		<tr>
			<td></td>
			<td colspan="4">
				<input type="file" id="fileup" name="fileup[]">
				<input name="addButton" id="addButton" type="button" value="+" onClick="JavaScript:fncCreateElement();">
				<input name="removeButton" id="removeButton" type="button" value="-" onClick="JavaScript:fncRemoveElement();">
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<span id="mySpan"></span>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
				<td></td>
				<td align="center" colspan="2"><input type="submit" value=" ยืนยัน " style="width:100px; height:35px;" onclick="return checkList()"></td>
				<td align="left" colspan="2"><input type="button" value="ยกเลิก" style="width:100px; height:35px;" onclick="window.close()"></td>
		</tr>
		
	
	</table>
</form>

		</td>
	</tr>			
</table>
</body>