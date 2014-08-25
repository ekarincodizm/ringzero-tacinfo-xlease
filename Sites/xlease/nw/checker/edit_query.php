<?php



session_start();

if(empty($_SESSION["av_iduser"])){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");

$appsecurID = $_POST['appsecurID'];
$securID = $_POST['securID'];
$id_user = $_SESSION["av_iduser"];
$auditorID = $_POST['auditors'];
$CusID = $_POST['customer'];

$feature = $_POST['feature'];
if($feature == 1){
$height = $_POST['height1'];
}else if($feature == 2){
$height = $_POST['height2'];
}else if($feature == 3){
$height = $_POST['height3'];
}else if($feature == 4){
$height = $_POST['height4'];
}else if($feature == 5){
$height = $_POST['height5'];
}else if($feature == 6){
$height = $_POST['height6'];
$feature_other = $_POST['feature_other'];
}

$size_build = $_POST['size_build'];
$size_area = $_POST['size_area'];
$struncture_build = $_POST['struncture_build'];
$wall_brick = $_POST['wall_brick'];
$wall_wood_brick = $_POST['wall_wood_brick'];
$wall_wood = $_POST['wall_wood'];
$wall_other = $_POST['wall_other'];
$wall_other_detail = $_POST['wall_other_detail'];

$ground_top_con = $_POST['ground_top_con'];
$ground_top_wood = $_POST['ground_top_wood'];
$ground_top_parquet = $_POST['ground_top_parquet'];
$ground_top_ceramic = $_POST['ground_top_ceramic'];
$ground_top_other = $_POST['ground_top_other'];
$ground_top_other_detail = $_POST['ground_top_other_detail'];

$ground_bot_con = $_POST['ground_bot_con'];
$ground_bot_wood = $_POST['ground_bot_wood'];
$ground_bot_parquet = $_POST['ground_bot_parquet'];
$ground_bot_ceramic = $_POST['ground_bot_ceramic'];
$ground_bot_other = $_POST['ground_bot_other'];
$ground_bot_other_detail = $_POST['ground_bot_other_detail'];

$roof_frame_iron = $_POST['roof_frame_iron'];
$roof_frame_con = $_POST['roof_frame_con'];
$roof_frame_wood = $_POST['roof_frame_wood'];
$roof_frame_unknow = $_POST['roof_frame_unknow'];
$roof_frame_other = $_POST['roof_frame_other'];
$roof_frame_other_detail = $_POST['roof_frame_other_detail'];

$roof_zine = $_POST['roof_zine'];
$roof_deck = $_POST['roof_deck'];
$roof_tile_duo = $_POST['roof_tile_duo'];
$roof_tile_monern = $_POST['roof_tile_monern'];
$roof_other = $_POST['roof_other'];
$roof_other_detail = $_POST['roof_other_detail'];

$ceiling_gypsum = $_POST['ceiling_gypsum'];
$ceiling_tile = $_POST['ceiling_tile'];
$ceiling_structure = $_POST['ceiling_structure'];
$ceiling_nothing = $_POST['ceiling_nothing'];
$ceiling_other = $_POST['ceiling_other'];
$ceiling_other_detail = $_POST['ceiling_other_detail'];

$door_wood = $_POST['door_wood'];
$door_glass = $_POST['door_glass'];
$door_plywood = $_POST['door_plywood'];
$door_iron = $_POST['door_iron'];
$door_other = $_POST['door_other'];
$door_other_detail = $_POST['door_other_detail'];

$window_open_glass = $_POST['window_open_glass'];
$window_silde_glass = $_POST['window_slide_glass'];
$window_scale_glass = $_POST['window_scale_glass'];
$window_wood = $_POST['window_wood'];
$window_other = $_POST['window_other'];
$window_other_detail = $_POST['window_other_detail'];

$rest_wc = $_POST['rest_wc'];
$rest_basin = $_POST['rest_basin'];
$rest_tub = $_POST['rest_tub'];
$rest_other = $_POST['rest_other'];
$rest_other_detail = $_POST['rest_other_detail'];

$quan_cave = $_POST['quan_cave'];
$quan_units = $_POST['quan_units'];
$quan_room = $_POST['quan_room'];
$floor_number = $_POST['floor_number'];

$fire = $_POST['fire'];
$room_height = $_POST['room_height'];
$build_inside_area = $_POST['build_inside_area'];
$roof_interval = $_POST['roof_interval'];
$deed_quantity = $_POST['deed_quantity'];
$cost_near = $_POST['cost_near'];
$cost_checker = $_POST['cost_checker'];

$typedocument = $_POST['typedocument'];
$typebuild = $_POST['typebuild'];
$typebuild_floor = $_POST['typebuild_floor'];

$size_build_width = $_POST['size_build_width'];
$size_build_long = $_POST['size_build_long'];

$deed_owner = $_POST['deed_owner'];
$deed_owner_area = $_POST['deed_owner_area'];
$deed_owner_area_size1 = $_POST['deed_owner_area_size1'];
$deed_owner_area_size2 = $_POST['deed_owner_area_size2'];

$address = $_POST['address'];

$position_address_right_map = $_POST['position_address_right_map'];
$position_address_fail_map = $_POST['position_address_fail_map'];
$position_address_success = $_POST['position_address_success'];
$position_address_fail = $_POST['position_address_fail'];
$position_address_navigation = $_POST['position_address_navigation'];
$position_address_navigation_name = $_POST['position_address_navigation_name'];

$land_shape_rectangle = $_POST['land_shape_rectangle'];
$land_shape_square = $_POST['land_shape_square'];
$land_shape_trapezuid = $_POST['land_shape_trapezuid'];
$land_shape_triangle = $_POST['land_shape_triangle'];
$land_shape_polygon = $_POST['land_shape_polygon'];

$land_state_coverall = $_POST['land_state_coverall'];
$land_state_cover = $_POST['land_state_cover'];
$land_state_cover_about1 = $_POST['land_state_cover_about1'];
$land_state_cover_about2 = $_POST['land_state_cover_about2'];
$land_state_cover_about = $land_state_cover_about1."/".$land_state_cover_about2;
$land_state_hole = $_POST['land_state_hole'];
$land_state_hole_about1 = $_POST['land_state_hole_about1'];
$land_state_hole_about2 = $_POST['land_state_hole_about2'];

$land_level_match = $_POST['land_level_match'];
$land_level_height = $_POST['land_level_height'];
$land_level_height_about = $_POST['land_level_height_about'];
$land_level_low = $_POST['land_level_low'];
$land_level_low_about = $_POST['land_level_low_about'];

$communication = $_POST['communication'];
$road_type = $_POST['road_type'];
$road_state = $_POST['road_state'];
$road_state_detail = $_POST['road_state_detail'];
$roadtobuild = $_POST['roadtobuild'];
$road_status = $_POST['road_status'];
$road_vehicles = $_POST['road_vehicles'];

$useful_home = $_POST['useful_home'];
$useful_commerce = $_POST['useful_commerce'];
$useful_rent = $_POST['useful_rent'];
$useful_stored = $_POST['useful_stored'];
$useful_industry = $_POST['useful_industry'];
$useful_agriculture = $_POST['useful_agriculture'];
$useful_other = $_POST['useful_other'];
$useful_other_detail = $_POST['useful_other_detail'];

$utilities = $_POST['utilities'];
$utilities_electricity = $_POST['utilities_electricity'];
$utilities_plumbing = $_POST['utilities_plumbing'];
$utilities_phone = $_POST['utilities_phone'];
$utilities_drain = $_POST['utilities_drain'];
$utilities_groundwater = $_POST['utilities_groundwater'];
$utilities_electricroad = $_POST['utilities_electricroad'];
$environment = $_POST['environment'];
$environment_trade = $_POST['environment_trade'];
$environment_home = $_POST['environment_home'];
$environment_factory = $_POST['environment_factory'];
$environment_slum = $_POST['environment_slum'];
$environment_military = $_POST['environment_military'];
$environment_tomb = $_POST['environment_tomb'];
$environment_shrine = $_POST['environment_shrine'];
$environment_temple = $_POST['environment_temple'];
$environment_highvoltage = $_POST['environment_highvoltage'];
$environment_dirt = $_POST['environment_dirt'];
$environment_closeplace = $_POST['environment_closeplace'];

$bind_rent = $_POST['bind_rent'];
$bind_rent_about = $_POST['bind_rent_about'];
$bind_pawn = $_POST['bind_pawn'];
$bind_pawn_about = $_POST['bind_pawn_about'];
$bind_all = $_POST['bind_all'];
$bind_rentbuy = $_POST['bind_rentbuy'];
$bind_nothing = $_POST['bind_nothing'];

$expropriate = $_POST['expropriate'];

$advancementnow = $_POST['advancementnow'];
$advancementcontinue = $_POST['advancementcontinue'];
$advancement = $_POST['advancement'];

$generality = $_POST['generality'];
$generality_detail = $_POST['generality_detail'];

$nearhome_status = $_POST['nearhome_status'];
$nearhomesize = $_POST['nearhomesize'];
$nearhometel = $_POST['nearhometel'];
$nearhomeprice = $_POST['nearhomeprice'];

$landoffice = $_POST['landoffice'];
$landoffice_branch = $_POST['landoffice_branch'];
$feel_checker = $_POST['feel_checker'];

$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$samedate = $_POST['date'];
list($day,$month,$year)=explode("-",$samedate);
$year1 = $year - 543;
$date = $year1."-".$month."-".$day;

$marketvalue = $_POST['marketvalue'];
$salevalue = $_POST['salevalue'];


$status = 0;
$status1 = 0;
$status2 = 0;


if(!empty($_FILES["fileup"]["name"])){

	@mkdir("fileupload/".$securID."/",0777);
	$path="fileupload/".$securID."/";
		
		for($i=0;$i<count($_FILES["fileup"]["name"]);$i++)
		{		
			if($_FILES["fileup"]["name"][$i] != "")
				{
					
					if(move_uploaded_file($_FILES["fileup"]["tmp_name"][$i],$path.$_FILES["fileup"]["name"][$i]))
						{
							 $file = $file."!#".$_FILES["fileup"]["name"][$i];
						
						}
				}
		}
}else{
		 $file = $_POST['filesame'];
}			

			if($auditorID==""){
				$auditorID="null";
			}
			if($feature==""){
				$feature="0";
			}else{
				$feature = "'".$feature."'";
			}
			if($feature_other=="")
			{
				$feature_other="null";
			}else{
				$feature_other= "'".$feature_other."'";
			}
			if($height==""){
				$height="null";
			}else{
				$height="'".$height."'";
			}if($size_build==""){
				$size_build="null";
			}else{
				$size_build="'".$size_build."'";
			}if($size_area==""){
				$size_area="null";
			}else{
				$size_area="'".$size_area."'";
			}if($struncture_build==""){
				$struncture_build="null";
			}else{
				$struncture_build="'".$struncture_build."'";
			}if($wall_brick==""){
				$wall_brick="0";
			}else{
				$wall_brick="'".$wall_brick."'";
			}if($wall_wood_brick==""){
				$wall_wood_brick="0";
			}else{
				$wall_wood_brick="'".$wall_wood_brick."'";
			}if($wall_wood==""){
				$wall_wood="0";
			}else{
				$wall_wood="'".$wall_wood."'";
			}if($wall_other==""){
				$wall_other="0";
			}else{
				$wall_other="'".$wall_other."'";
			}if($wall_other_detail==""){
				$wall_other_detail="null";
			}else{
				$wall_other_detail="'".$wall_other_detail."'";
			}if($ground_top_con==""){
				$ground_top_con="0";
			}else{
				$ground_top_con="'".$ground_top_con."'";
			}if($ground_top_wood==""){
				$ground_top_wood="0";
			}else{
				$ground_top_wood="'".$ground_top_wood."'";
			}if($ground_top_parquet==""){
				$ground_top_parquet="0";
			}else{
				$ground_top_parquet="'".$ground_top_parquet."'";
			}if($ground_top_ceramic==""){
				$ground_top_ceramic="0";
			}else{
				$ground_top_ceramic="'".$ground_top_ceramic."'";
			}if($ground_top_other==""){
				$ground_top_other="0";
			}else{
				$ground_top_other="'".$ground_top_other."'";
			}if($ground_top_other_detail==""){
				$ground_top_other_detail="null";
			}else{
				$ground_top_other_detail="'".$ground_top_other_detail."'";
			}if($ground_bot_con==""){
				$ground_bot_con="0";
			}else{
				$ground_bot_con="'".$ground_bot_con."'";
			}if($ground_bot_wood==""){
				$ground_bot_wood="0";
			}else{
				$ground_bot_wood="'".$ground_bot_wood."'";
			}if($ground_bot_parquet==""){
				$ground_bot_parquet="0";
			}else{
				$ground_bot_parquet="'".$ground_bot_parquet."'";
			}if($ground_bot_ceramic==""){
				$ground_bot_ceramic="0";
			}else{
				$ground_bot_ceramic="'".$ground_bot_ceramic."'";
			}if($ground_bot_other==""){
				$ground_bot_other="0";
			}else{
				$ground_bot_other="'".$ground_bot_other."'";
			}if($ground_bot_other_detail==""){
				$ground_bot_other_detail="null";
			}else{
				$ground_bot_other_detail="'".$ground_bot_other_detail."'";
			}if($roof_frame_iron==""){
				$roof_frame_iron="0";
			}else{
				$roof_frame_iron="'".$roof_frame_iron."'";
			}if($roof_frame_con==""){
				$roof_frame_con="0";
			}else{
				$roof_frame_con="'".$roof_frame_con."'";
			}if($roof_frame_wood==""){
				$roof_frame_wood="0";
			}else{
				$roof_frame_wood="'".$roof_frame_wood."'";
			}if($roof_frame_unknow==""){
				$roof_frame_unknow="0";
			}else{
				$roof_frame_unknow="'".$roof_frame_unknow."'";
			}if($roof_frame_other==""){
				$roof_frame_other="0";
			}else{
				$roof_frame_other="'".$roof_frame_other."'";
			}if($roof_frame_other_detail==""){
				$roof_frame_other_detail="null";
			}else{
				$roof_frame_other_detail="'".$roof_frame_other_detail."'";
			}if($roof_zine==""){
				$roof_zine="0";
			}else{
				$roof_zine="'".$roof_zine."'";
			}if($roof_deck==""){
				$roof_deck="0";
			}else{
				$roof_deck="'".$roof_deck."'";
			}if($roof_tile_duo==""){
				$roof_tile_duo="0";
			}else{
				$roof_tile_duo="'".$roof_tile_duo."'";
			}if($roof_tile_monern==""){
				$roof_tile_monern="0";
			}else{
				$roof_tile_monern="'".$roof_tile_monern."'";
			}if($roof_other==""){
				$roof_other="0";
			}else{
				$roof_other="'".$roof_other."'";
			}if($roof_other_detail==""){
				$roof_other_detail="null";
			}else{
				$roof_other_detail="'".$roof_other_detail."'";
			}if($ceiling_gypsum==""){
				$ceiling_gypsum="0";
			}else{
				$ceiling_gypsum="'".$ceiling_gypsum."'";
			}if($ceiling_tile==""){
				$ceiling_tile="0";
			}else{
				$ceiling_tile="'".$ceiling_tile."'";
			}if($ceiling_structure==""){
				$ceiling_structure="0";
			}else{
				$ceiling_structure="'".$ceiling_structure."'";
			}if($ceiling_nothing==""){
				$ceiling_nothing="0";
			}else{
				$ceiling_nothing="'".$ceiling_nothing."'";
			}if($ceiling_other==""){
				$ceiling_other="0";
			}else{
				$ceiling_other="'".$ceiling_other."'";
			}if($ceiling_other_detail==""){
				$ceiling_other_detail="null";
			}else{
				$ceiling_other_detail="'".$ceiling_other_detail."'";
			}if($door_wood==""){
				$door_wood="0";
			}else{
				$door_wood="'".$door_wood."'";
			}if($door_glass==""){
				$door_glass="0";
			}else{
				$door_glass="'".$door_glass."'";
			}if($door_plywood==""){
				$door_plywood="0";
			}else{
				$door_plywood="'".$door_plywood."'";
			}if($door_iron==""){
				$door_iron="0";
			}else{
				$door_iron="'".$door_iron."'";
			}if($door_other==""){
				$door_other="0";
			}else{
				$door_other="'".$door_other."'";
			}if($door_other_detail==""){
				$door_other_detail="null";
			}else{
				$door_other_detail="'".$door_other_detail."'";
			}if($window_open_glass==""){
				$window_open_glass="0";
			}else{
				$window_open_glass="'".$window_open_glass."'";
			}if($window_silde_glass==""){
				$window_silde_glass="0";
			}else{
				$window_silde_glass="'".$window_silde_glass."'";
			}if($window_scale_glass==""){
				$window_scale_glass="0";
			}else{
				$window_scale_glass="'".$window_scale_glass."'";
			}if($window_wood==""){
				$window_wood="0";
			}else{
				$window_wood="'".$window_wood."'";
			}if($window_other==""){
				$window_other="0";
			}else{
				$window_other="'".$window_other."'";
			}if($window_other_detail==""){
				$window_other_detail="null";
			}else{
				$window_other_detail="'".$window_other_detail."'";
			}if($rest_wc==""){
				$rest_wc="0";
			}else{
				$rest_wc="'".$rest_wc."'";
			}if($rest_basin==""){
				$rest_basin="0";
			}else{
				$rest_basin="'".$rest_basin."'";
			}if($rest_tub==""){
				$rest_tub="0";
			}else{
				$rest_tub="'".$rest_tub."'";
			}if($rest_other==""){
				$rest_other="0";
			}else{
				$rest_other="'".$rest_other."'";
			}if($rest_other_detail==""){
				$rest_other_detail="null";
			}else{
				$rest_other_detail="'".$rest_other_detail."'";
			}if($quan_cave==""){
				$quan_cave="null";
			}else{
				$quan_cave="'".$quan_cave."'";
			}if($quan_units==""){
				$quan_units="null";
			}else{
				$quan_units="'".$quan_units."'";
			}if($quan_room==""){
				$quan_room="null";
			}else{
				$quan_room="'".$quan_room."'";
			}if($floor_number==""){
				$floor_number="null";
			}else{
				$floor_number="'".$floor_number."'";
			}if($fire==""){
				$fire="null";
			}else{
				$fire="'".$fire."'";
			}if($room_height==""){
				$room_height="null";
			}else{
				$room_height="'".$room_height."'";
			}if($build_inside_area==""){
				$build_inside_area="null";
			}else{
				$build_inside_area="'".$build_inside_area."'";
			}if($roof_interval==""){
				$roof_interval="null";
			}else{
				$roof_interval="'".$roof_interval."'";
			}if($deed_quantity==""){
				$deed_quantity="null";
			}else{
				$deed_quantity="'".$deed_quantity."'";
			}if($cost_near==""){
				$cost_near="null";
			}else{
				$cost_near="'".$cost_near."'";
			}if($cost_checker==""){
				$cost_checker="null";
			}else{
				$cost_checker="'".$cost_checker."'";
			}if($typedocument==""){	
				$typedocument="null";
			}else{
				$typedocument="'".$typedocument."'";
			}if($typebuild==""){
				$typebuild="null";
			}else{
				$typebuild="'".$typebuild."'";
			}if($typebuild_floor==""){
				$typebuild_floor="null";
			}else{
				$typebuild_floor="'".$typebuild_floor."'";
			}if($size_build_width==""){
				$size_build_width="null";
			}else{
				$size_build_width="'".$size_build_width."'";
			}if($size_build_long==""){
				$size_build_long="null";
			}else{
				$size_build_long="'".$size_build_long."'";
			}if($deed_owner==""){
				$deed_owner="null";
			}else{
				$deed_owner="'".$deed_owner."'";
			}if($deed_owner_area==""){
				$deed_owner_area="null";
			}else{
				$deed_owner_area="'".$deed_owner_area."'";
			}if($deed_owner_area_size1==""){
				$deed_owner_area_size1="null";
			}else{
				$deed_owner_area_size1="'".$deed_owner_area_size1."'";
			}if($deed_owner_area_size2==""){
				$deed_owner_area_size2="null";
			}else{
				$deed_owner_area_size2="'".$deed_owner_area_size2."'";
			}if($address==""){
				$address="null";
			}else{
				$address="'".$address."'";
			}if($position_address_right_map==""){
				$position_address_right_map="0";
			}else{
				$position_address_right_map="'".$position_address_right_map."'";
			}if($position_address_fail_map==""){
				$position_address_fail_map="0";
			}else{
				$position_address_fail_map="'".$position_address_fail_map."'";
			}if($position_address_success==""){
				$position_address_success="0";
			}else{
				$position_address_success="'".$position_address_success."'";
			}if($position_address_fail==""){
				$position_address_fail="0";
			}else{
				$position_address_fail="'".$position_address_fail."'";
			}if($position_address_navigation==""){
				$position_address_navigation="0";
			}else{
				$position_address_navigation="'".$position_address_navigation."'";
			}if($position_address_navigation_name==""){
				$position_address_navigation_name="null";
			}else{
				$position_address_navigation_name="'".$position_address_navigation_name."'";
			}if($land_shape_rectangle==""){
				$land_shape_rectangle="0";
			}else{
				$land_shape_rectangle="'".$land_shape_rectangle."'";
			}if($land_shape_square==""){
				$land_shape_square="0";
			}else{
				$land_shape_square="'".$land_shape_square."'";
			}if($land_shape_trapezuid==""){
				$land_shape_trapezuid="0";
			}else{
				$land_shape_trapezuid="'".$land_shape_trapezuid."'";
			}if($land_shape_triangle==""){
				$land_shape_triangle="0";
			}else{
				$land_shape_triangle="'".$land_shape_triangle."'";
			}if($land_shape_polygon==""){
				$land_shape_polygon="0";
			}else{
				$land_shape_polygon="'".$land_shape_polygon."'";
			}if($land_state_coverall==""){
				$land_state_coverall="0";
			}else{
				$land_state_coverall="'".$land_state_coverall."'";
			}if($land_state_cover==""){
				$land_state_cover="0";
			}else{
				$land_state_cover="'".$land_state_cover."'";
			}if($land_state_cover_about=="" || $land_state_cover_about=='/'){
				$land_state_cover_about="null";
			}else{
				$land_state_cover_about="'".$land_state_cover_about."'";
			}if($land_state_hole==""){
				$land_state_hole="0";
			}else{
				$land_state_hole="'".$land_state_hole."'";
			}if($land_state_hole_about1==""){
				$land_state_hole_about1="null";
			}else{
				$land_state_hole_about1="'".$land_state_hole_about1."'";
			}if($land_state_hole_about2==""){
				$land_state_hole_about2="null";
			}else{
				$land_state_hole_about2="'".$land_state_hole_about2."'";
			}if($land_level_match==""){
				$land_level_match="0";
			}else{
				$land_level_match="'".$land_level_match."'";
			}if($land_level_height==""){
				$land_level_height="0";
			}else{
				$land_level_height="'".$land_level_height."'";
			}if($land_level_height_about==""){
				$land_level_height_about="null";
			}else{
				$land_level_height_about="'".$land_level_height_about."'";
			}if($land_level_low==""){
				$land_level_low="0";
			}else{
				$land_level_low="'".$land_level_low."'";
			}if($land_level_low_about==""){
				$land_level_low_about="null";
			}else{
				$land_level_low_about="'".$land_level_low_about."'";
			}if($communication==""){
				$communication="null";
			}else{
				$communication="'".$communication."'";
			}if($road_type==""){
				$road_type="null";
			}else{
				$road_type="'".$road_type."'";
			}if($road_state==""){
				$road_state="null";
			}else{
				$road_state="'".$road_state."'";
			}if($road_state_detail==""){
				$road_state_detail="null";
			}else{
				$road_state_detail="'".$road_state_detail."'";
			}if($roadtobuild==""){
				$roadtobuild="null";
			}else{
				$roadtobuild="'".$roadtobuild."'";
			}if($road_status==""){
				$road_status="null";
			}else{
				$road_status="'".$road_status."'";
			}if($road_vehicles==""){
				$road_vehicles="null";
			}else{
				$road_vehicles="'".$road_vehicles."'";
			}if($useful_home==""){
				$useful_home="0";
			}else{
				$useful_home="'".$useful_home."'";
			}if($useful_commerce==""){
				$useful_commerce="0";
			}else{
				$useful_commerce="'".$useful_commerce."'";
			}if($useful_rent==""){
				$useful_rent="0";
			}else{
				$useful_rent="'".$useful_rent."'";
			}if($useful_stored==""){
				$useful_stored="0";
			}else{
				$useful_stored="'".$useful_stored."'";
			}if($useful_industry==""){
				$useful_industry="0";
			}else{
				$useful_industry="'".$useful_industry."'";
			}if($useful_agriculture==""){
				$useful_agriculture="0";
			}else{
				$useful_agriculture="'".$useful_agriculture."'";
			}if($useful_other==""){
				$useful_other="0";
			}else{
				$useful_other="'".$useful_other."'";
			}if($useful_other_detail==""){
				$useful_other_detail="null";
			}else{
				$useful_other_detail="'".$useful_other_detail."'";
			}if($utilities==""){
				$utilities="null";
			}else{
				$utilities="'".$utilities."'";
			}if($utilities_electricity==""){
				$utilities_electricity="0";
			}else{
				$utilities_electricity="'".$utilities_electricity."'";
			}if($utilities_plumbing==""){
				$utilities_plumbing="0";
			}else{
				$utilities_plumbing="'".$utilities_plumbing."'";
			}if($utilities_phone==""){
				$utilities_phone="0";
			}else{
				$utilities_phone="'".$utilities_phone."'";
			}if($utilities_drain==""){
				$utilities_drain="0";
			}else{
				$utilities_drain="'".$utilities_drain."'";
			}if($utilities_groundwater==""){
				$utilities_groundwater="0";
			}else{
				$utilities_groundwater="'".$utilities_groundwater."'";
			}if($utilities_electricroad==""){
				$utilities_electricroad="0";
			}else{
				$utilities_electricroad="'".$utilities_electricroad."'";
			}if($environment==""){
				$environment="null";
			}else{
				$environment="'".$environment."'";
			}if($environment_trade==""){
				$environment_trade="0";
			}else{
				$environment_trade="'".$environment_trade."'";
			}if($environment_home==""){
				$environment_home="0";
			}else{
				$environment_home="'".$environment_home."'";
			}if($environment_factory==""){
				$environment_factory="0";
			}else{
				$environment_factory="'".$environment_factory."'";
			}if($environment_slum==""){
				$environment_slum="0";
			}else{
				$environment_slum="'".$environment_slum."'";
			}if($environment_military==""){
				$environment_military="0";
			}else{
				$environment_military="'".$environment_military."'";
			}if($environment_tomb==""){
				$environment_tomb="0";
			}else{
				$environment_tomb="'".$environment_tomb."'";
			}if($environment_shrine==""){
				$environment_shrine="0";
			}else{
				$environment_shrine="'".$environment_shrine."'";
			}if($environment_temple==""){
				$environment_temple="0";
			}else{
				$environment_temple="'".$environment_temple."'";
			}if($environment_highvoltage==""){
				$environment_highvoltage="0";
			}else{
				$environment_highvoltage="'".$environment_highvoltage."'";
			}if($environment_dirt==""){
				$environment_dirt="0";
			}else{
				$environment_dirt="'".$environment_dirt."'";
			}if($environment_closeplace==""){
				$environment_closeplace="null";
			}else{
				$environment_closeplace="'".$environment_closeplace."'";
			}if($bind_rent==""){
				$bind_rent="0";
			}else{
				$bind_rent="'".$bind_rent."'";
			}if($bind_rent_about==""){
				$bind_rent_about="null";
			}else{
				$bind_rent_about="'".$bind_rent_about."'";
			}if($bind_pawn==""){
				$bind_pawn="0";
			}else{
				$bind_pawn="'".$bind_pawn."'";
			}if($bind_pawn_about==""){
				$bind_pawn_about="null";
			}else{
				$bind_pawn_about="'".$bind_pawn_about."'";
			}if($bind_all==""){
				$bind_all="0";
			}else{
				$bind_all="'".$bind_all."'";
			}if($bind_rentbuy==""){
				$bind_rentbuy="0";
			}else{
				$bind_rentbuy="'".$bind_rentbuy."'";
			}if($bind_nothing==""){
				$bind_nothing="0";
			}else{
				$bind_nothing="'".$bind_nothing."'";
			}if($expropriate==""){
				$expropriate="null";
			}else{
				$expropriate="'".$expropriate."'";
			}if($advancementnow==""){
				$advancementnow="null";
			}else{
				$advancementnow="'".$advancementnow."'";
			}if($advancementcontinue==""){
				$advancementcontinue="null";
			}else{
				$advancementcontinue="'".$advancementcontinue."'";
			}if($advancement==""){
				$advancement="null";
			}else{
				$advancement="'".$advancement."'";
			}if($generality==""){
				$generality="null";
			}else{
				$generality="'".$generality."'";
			}if($generality_detail==""){
				$generality_detail="null";
			}else{
				$generality_detail="'".$generality_detail."'";
			}if($nearhome_status==""){
				$nearhome_status="null";
			}else{
				$nearhome_status="'".$nearhome_status."'";
			}if($nearhomesize==""){
				$nearhomesize="null";
			}else{
				$nearhomesize="'".$nearhomesize."'";
			}if($nearhometel==""){
				$nearhometel="null";
			}else{
				$nearhometel="'".$nearhometel."'";
			}if($nearhomeprice==""){
				$nearhomeprice="null";
			}else{
				$nearhomeprice="'".$nearhomeprice."'";
			}if($landoffice==""){
				$landoffice="null";
			}else{
				$landoffice="'".$landoffice."'";
			}if($landoffice_branch==""){
				$landoffice_branch="null";
			}else{
				$landoffice_branch="'".$landoffice_branch."'";			
			}if($date==""){
				$date="null";
			}else{
				$date="'".$date."'";
			}if($datenow==""){
				$datenow="null";
			}else{
				$datenow="'".$datenow."'";
			}if($marketvalue==""){
				$marketvalue="null";
			}else{
				$marketvalue="'".$marketvalue."'";
			}if($salevalue==""){
				$salevalue="null";
			}else{
				$salevalue="'".$salevalue."'";
			}if($feel_checker==""){
				$feel_checker="null";
			}else{
				$feel_checker="'".$feel_checker."'";
			}if($securID==""){
				$securID="null";
			}if($file==""){
				$file="null";
			}else{
				$file="'".$file."'";
			}


if($wall_brick=="0" && $wall_wood_brick=="0" && $wall_wood=="0" && $wall_other=="0"){

		$wall_brick="null";
		$wall_wood_brick="null";
		$wall_wood="null";
		$wall_other="null";
}
if($ground_top_con=="0" && $ground_top_wood=="0" && $ground_top_parquet=="0" && $ground_top_ceramic=="0" && $ground_top_other=="0" ){
		
		$ground_top_con="null"; 
		$ground_top_wood="null";
		$ground_top_parquet="null";
		$ground_top_ceramic="null";
		$ground_top_other="null";
}
if($ground_bot_con=="0" && $ground_bot_wood=="0" && $ground_bot_parquet=="0" && $ground_bot_ceramic=="0" && $ground_bot_other=="0"){
	
		$ground_bot_con="null";
		$ground_bot_wood="null";
		$ground_bot_parquet="null";
		$ground_bot_ceramic="null";
		$ground_bot_other="null";
}
if($roof_frame_iron=="0" && $roof_frame_con=="0" && $roof_frame_wood=="0" && $roof_frame_unknow=="0" && $roof_frame_other=="0"){

		$roof_frame_iron="null";
		$roof_frame_con="null";
		$roof_frame_wood="null";
		$roof_frame_unknow="null";
		$roof_frame_other="null";
}
if($roof_zine=="0" && $roof_deck=="0" && $roof_tile_duo=="0" && $roof_tile_monern=="0" && $roof_other=="0"){

		$roof_zine="null";
		$roof_deck="null";
		$roof_tile_duo="null";
		$roof_tile_monern="null";
		$roof_other="null";
}
if($ceiling_gypsum=="0" && $ceiling_tile=="0" && $ceiling_structure=="0" && $ceiling_nothing=="0" && $ceiling_other=="0"){

		$ceiling_gypsum="null";
		$ceiling_tile="null";
		$ceiling_structure="null";
		$ceiling_nothing="null";
		$ceiling_other="null";
}
if($door_wood=="0" && $door_glass=="0" && $door_plywood=="0" && $door_iron=="0" && $door_other=="0"){

		$door_wood="null";
		$door_glass="null";
		$door_plywood="null";
		$door_iron="null";
		$door_other="null";
}
if($window_open_glass=="0" && $window_silde_glass=="0" && $window_scale_glass=="0" && $window_wood=="0" && $window_other=="0"){

		$window_open_glass="null";
		$window_silde_glass="null";
		$window_scale_glass="null";
		$window_wood="null";
		$window_other="null";
}
if($rest_wc=="0" && $rest_basin=="0" && $rest_tub=="0" && $rest_other=="0"){

		$rest_wc="null";
		$rest_basin="null";
		$rest_tub="null";
		$rest_other="null";
}
if($position_address_right_map=="0" && $position_address_fail_map=="0" && $position_address_success=="0" && $position_address_navigation=="0" && $position_address_fail=="0"){

		$position_address_right_map="null";
		$position_address_fail_map="null";
		$position_address_success="null";
		$position_address_navigation="null";
		$position_address_fail="null";
}
if($land_shape_rectangle=="0" && $land_shape_polygon=="0" && $land_shape_triangle=="0" && $land_shape_trapezuid=="0" && $land_shape_square=="0"){

		$land_shape_rectangle="null";
		$land_shape_polygon="null";
		$land_shape_triangle="null";
		$land_shape_trapezuid="null";
		$land_shape_square="null";
}
if($land_state_coverall=="0" && $land_state_cover=="0" && $land_state_hole=="0"){

		$land_state_coverall="null";
		$land_state_cover="null";
		$land_state_hole="null";
}
if($land_level_match=="0" && $land_level_height=="0" && $land_level_low=="0"){

		$land_level_match="null";
		$land_level_height="null";
		$land_level_low="null";
}
if($useful_home=="0" && $useful_commerce=="0" && $useful_rent=="0" && $useful_stored=="0" && $useful_industry=="0" && $useful_other=="0" && $useful_agriculture=="0"){
		
		$useful_home="null";
		$useful_commerce="null";
		$useful_rent="null";
		$useful_stored="null";
		$useful_industry="null";
		$useful_other="null";
		$useful_agriculture="null";
}
if($utilities_electricity=="0" && $utilities_electricroad=="0" && $utilities_plumbing=="0" && $utilities_groundwater=="0" && $utilities_drain=="0" && $utilities_phone=="0"){

		$utilities_electricity="null";
		$utilities_electricroad="null";
		$utilities_plumbing="null";
		$utilities_groundwater="null";
		$utilities_drain="null";
		$utilities_phone="null";
}
if($environment_trade=="0" && $environment_dirt=="0" && $environment_highvoltage=="0" && $environment_temple=="0" && $environment_shrine=="0" && $environment_tomb=="0" && $environment_military=="0" && $environment_home=="0" && $environment_slum=="0" && $environment_factory=="0"){
		
		$environment_trade="null";
		$environment_dirt="null";
		$environment_highvoltage="null";
		$environment_temple="null";
		$environment_shrine="null";
		$environment_tomb="null";
		$environment_military="null";
		$environment_home="null";
		$environment_slum="null";
		$environment_factory="null";
		
}
if($bind_rent=="0" && $bind_pawn=="0" && $bind_all=="0" && $bind_rentbuy=="0" && $bind_nothing=="0"){
	
		$bind_rent="null";
		$bind_pawn="null";
		$bind_all="null";
		$bind_rentbuy="null";
		$bind_nothing="null";
}


pg_query("BEGIN");

$sql = " INSERT INTO temp_securities_detail(  
			feature,
			feature_other,
			height,
			size_build,
			size_area, 
            structure_build, 
			wall_brick, 
			wall_wood_brick, 
			wall_wood, 
			wall_other, 
            wall_other_detail, 
			ground_top_con, 
			ground_top_wood, 
			ground_top_parquet, 
            ground_top_ceramic, 
			ground_top_other, 
			ground_top_other_detail, 
            ground_bot_con, 
			ground_bot_wood, 
			ground_bot_parquet, 
			ground_bot_ceramic, 
            ground_bot_other, 
			ground_bot_other_detail, 
			roof_frame_iron, 
			roof_frame_con, 
            roof_frame_wood, 
			roof_frame_unknow, 
			roof_frame_other, 
			roof_frame_other_detail, 
            roof_zine, 
			roof_deck, 
			roof_tile_duo, 
			roof_tile_monern, 
			roof_other, 
            roof_other_detail, 
			ceiling_gypsum, 
			ceiling_tile, 
			ceiling_structure, 
            ceiling_nothing, 
			ceiling_other, 
			ceiling_other_detail, 
			door_wood, 
            door_glass, 
			door_plywood, 
			door_iron, 
			door_other, 
			door_other_detail, 
            window_open_glass, 
			window_slide_glass, 
			window_scale_glass, 
			window_wood, 
            window_other, 
			window_other_detail, 
			rest_wc, 
			rest_basin, 
			rest_tub, 
            rest_other, 
			rest_other_detail, 
			quan_cave, 
			quan_unit, 
			quan_room,  
			floor_number, 
			fire, 
			room_height, 
			build_inside_area, 
            roof_interval, 
			\"Deed_quantity\", 
			cost_near, 
			cost_checker, 
			typedocument, 
            typebuild, 
			typebuild_floor, 
			size_build_width, 
			size_build_long, 
            deed_owner, 
			deed_owner_area, 
			deed_owner_area_size1, 
			deed_owner_area_size2, 
            address, 
			position_add_right_map, 
			position_add_fail_map, 
			position_add_success, 
            position_add_fail, 
			position_add_navigation, 
			position_add_navigation_name, 
            land_shape_rectangle, 
			land_shape_square, 
			land_shape_trapqzuid, 
            land_shape_triangle, 
			land_shape_polygon, 
			land_state_coverall, 
            land_state_cover, 
			land_state_cover_about, 
			land_state_hole, 
			land_state_hole_about1, 
            land_state_hole_about2, 
			land_level_match, 
			land_level_height, 
            land_level_height_about, 
			land_level_low, 
			land_level_low_about, 
            communication, 
			road_type, 
			road_state, 
			road_state_detail, 
			road_to_build, 
            road_status, 
			road_vehicles, 
			useful_home, 
			useful_commerce, 
			useful_rent, 
            useful_stored, 
			useful_industry, 
			useful_agriculture,
			useful_other, 
            useful_other_detail, 
			utilities, 
			utilities_electricity, 
			utilities_plumbing, 
            utilities_phone, 
			utilities_drain, 
			utilities_groundwater, 
			utilities_electri_road, 
            environment, 
			environment_trade, 
			environment_home, 
			environment_factory, 
            environment_slum, 
			environment_military, 
			environment_tomb, 
			environment_shrine, 
            environment_temple, 
			environment_highvoltage, 
			environment_dirt, 
            environment_closeplace, 
			bind_rent, 
			bind_rent_about, 
			bind_pawn, 
            bind_pawn_about, 
			bind_all, 
			bind_rentbuy, 
			bind_nothing, 
			expropriate, 
            advancementnow, 
			advancementcontinue, 
			advancement, 
			generality, 
            generality_detail, 
			nearhome_status, 
			nearhomesize, 
			nearhometel, 
            nearhomeprice, 
			landoffice, 
			landoffice_branch, 
			file, 
			date, 
			marketvalue, 
            salevalue,
			\"feel_checker\",
			\"securID\",
			\"CusID\",
			\"id_user\",
			\"id_auditor\"
			
			)VALUES(
				$feature,
			$feature_other,
			$height,
			$size_build,
			$size_area,
			$struncture_build ,
			$wall_brick,
			$wall_wood_brick,
			$wall_wood,
			$wall_other,
			$wall_other_detail,
			$ground_top_con ,
			$ground_top_wood,
			$ground_top_parquet,
			$ground_top_ceramic,
			$ground_top_other,
			$ground_top_other_detail,
			$ground_bot_con,
			$ground_bot_wood,
			$ground_bot_parquet,
			$ground_bot_ceramic,
			$ground_bot_other,
			$ground_bot_other_detail,
			$roof_frame_iron,
			$roof_frame_con,
			$roof_frame_wood,
			$roof_frame_unknow,
			$roof_frame_other,
			$roof_frame_other_detail,
			$roof_zine,
			$roof_deck,
			$roof_tile_duo,
			$roof_tile_monern,
			$roof_other,
			$roof_other_detail,
			$ceiling_gypsum,
			$ceiling_tile,
			$ceiling_structure,
			$ceiling_nothing,
			$ceiling_other,
			$ceiling_other_detail,
			$door_wood,
			$door_glass,
			$door_plywood,
			$door_iron,
			$door_other,
			$door_other_detail,
			$window_open_glass,
			$window_silde_glass,
			$window_scale_glass,
			$window_wood,
			$window_other,
			$window_other_detail,
			$rest_wc,
			$rest_basin,
			$rest_tub,
			$rest_other,
			$rest_other_detail,
			$quan_cave,
			$quan_units,
			$quan_room,
			$floor_number,
			$fire,
			$room_height,
			$build_inside_area,
			$roof_interval,
			$deed_quantity,
			$cost_near,
			$cost_checker,
			$typedocument,
			$typebuild,
			$typebuild_floor,
			$size_build_width,
			$size_build_long,
			$deed_owner,
			$deed_owner_area,
			$deed_owner_area_size1,
			$deed_owner_area_size2,
			$address,
			$position_address_right_map,
			$position_address_fail_map,
			$position_address_success,
			$position_address_fail,
			$position_address_navigation,
			$position_address_navigation_name,
			$land_shape_rectangle,
			$land_shape_square,
			$land_shape_trapezuid,
			$land_shape_triangle,
			$land_shape_polygon,
			$land_state_coverall,
			$land_state_cover,
			$land_state_cover_about,
			$land_state_hole,
			$land_state_hole_about1,
			$land_state_hole_about2,
			$land_level_match,
			$land_level_height,
			$land_level_height_about,
			$land_level_low,
			$land_level_low_about,
			$communication,
			$road_type,
			$road_state,
			$road_state_detail,
			$roadtobuild,
			$road_status,
			$road_vehicles,
			$useful_home,
			$useful_commerce,
			$useful_rent,
			$useful_stored,
			$useful_industry,
			$useful_agriculture,
			$useful_other,
			$useful_other_detail,
			$utilities,
			$utilities_electricity,
			$utilities_plumbing,
			$utilities_phone,
			$utilities_drain,
			$utilities_groundwater,
			$utilities_electricroad,
			$environment,
			$environment_trade,
			$environment_home,
			$environment_factory,
			$environment_slum,
			$environment_military,
			$environment_tomb,
			$environment_shrine,
			$environment_temple,
			$environment_highvoltage,
			$environment_dirt,
			$environment_closeplace,
			$bind_rent,
			$bind_rent_about,
			$bind_pawn,
			$bind_pawn_about,
			$bind_all,
			$bind_rentbuy,
			$bind_nothing,
			$expropriate,
			$advancementnow,
			$advancementcontinue,
			$advancement,
			$generality,
			$generality_detail,
			$nearhome_status,
			$nearhomesize,
			$nearhometel,
			$nearhomeprice,
			$landoffice,
			$landoffice_branch,
			$file,
			$date,
			$marketvalue,
			$salevalue,
			$feel_checker,
			$securID,
			'$CusID',
			'$id_user',
			'$auditorID')
			" ;
$result = pg_query($sql);

	if($result){
	}else{
		$status++;
	}

		if($status == 0){
		
			$sql1=pg_query("select \"securdeID\" from \"temp_securities_detail\" order by \"securdeID\" DESC");
			$result1 = pg_fetch_array($sql1);
			$securdetailID = $result1['securdeID'];
		
			$sql2=pg_query("insert into \"approve_securities_detail\"(\"securdeID\",\"status\",\"date\",\"securID\",\"id_user\") values($securdetailID,4,$datenow,'$securID','$id_user')");
				
					if($sql2){
					}else{
						$status1++;
					}
					
					if($status1 == 0){
					
							if(!empty($appsecurID)){
							
							
								$sql3=pg_query("update \"approve_securities_detail\" SET \"status\" = 3 where \"appsecurID\" = $appsecurID");
					
								if($sql3){
								}else{
									$status2++;
								}
								
									if($status2 == 0){
									
										pg_query("COMMIT");
										
										echo "<script type='text/javascript'>alert(' เพิ่มข้อมูลเรียบร้อย รอการอนุมัติ ')</script>";
										?>
										<center><input type="button" value=" ปิด " onclick="window.close();">
										<?php
										exit();
									}else{
										pg_query("ROLLBACK");
										echo $sql;			
										echo "<script type='text/javascript'>alert(' ไม่สามารถเพิ่มข้อมูลได้ ')</script>";
										?>
										<center><input type="button" value=" ปิด " onclick="window.close();">
										<?php
										exit();
									}

							}else{
									pg_query("COMMIT");
										
										echo "<script type='text/javascript'>alert(' เพิ่มข้อมูลเรียบร้อย รอการอนุมัติ ')</script>";
										?>
										<center><input type="button" value=" ปิด " onclick="window.close();">
										<?php
										exit();
							}

									
					}else{
						pg_query("ROLLBACK");
						echo $sql;			
						echo "<script type='text/javascript'>alert(' ไม่สามารถเพิ่มข้อมูลได้ ')</script>";
						?>
						<center><input type="button" value=" ปิด " onclick="window.close();">
						<?php
						exit();
					}
		}else{
			pg_query("ROLLBACK");
			echo $sql;			
			echo "<script type='text/javascript'>alert(' ไม่สามารถเพิ่มข้อมูลได้ ')</script>";
			?>
			<center><input type="button" value=" ปิด " onclick="window.close();">
			<?php
			exit();
		}

?>