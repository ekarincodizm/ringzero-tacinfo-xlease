<?php
session_start();
include("../config/config.php");

pg_query("BEGIN WORK");
$status = 0;

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];
$id = pg_escape_string($_POST['id']);
$fieldedit = pg_escape_string($_POST['fieldedit']);

$qry = pg_query("SELECT * FROM insure.batch WHERE \"id\"='$id' AND \"type\"='N' AND \"approve_id\" IS NULL ");
if($res = pg_fetch_array($qry)){
    $InsID = $res['InsID'];
    $Company = $res['Company'];
    $StartDate = $res['StartDate'];
    $EndDate = $res['EndDate'];
    $Code = $res['Code'];
    $InsMark = $res['InsMark'];
    $Capacity = $res['Capacity'];
    
    $Kind = $res['Kind'];
    $Invest = $res['Invest'];
    $Premium = $res['Premium'];
    $Discount = $res['Discount'];
    $CollectCus = $res['CollectCus'];
    $InsUser = $res['InsUser'];
    
    $NetPremium = $res['NetPremium'];
    $TaxStamp = $res['TaxStamp'];
	$TaxStamp = Round($TaxStamp,0);
    $Vat = $res['Vat'];
}

$count_fieldedit = 0;
$arr_fieldedit = @explode(",",$fieldedit);
$count_fieldedit = @count($arr_fieldedit);

if( substr($id,0,1)=="F" ){
	if($Discount==""){
		$Discount=0;
	}else{
		$Discount=$Discount;
	}
    if($count_fieldedit == 1){
        $ud = ${$fieldedit};
        $sql = "UPDATE \"insure\".\"InsureForce\" SET \"$fieldedit\"='$ud',\"Discount\"='$Discount' WHERE \"InsFIDNO\"='$id' ";
        if(!$result=pg_query($sql)){
            $status++;
        }
		if($fieldedit=="Capacity"){ //กรณีเป็นการแก้ไข cc รถ
			//หา IDNO 
			$qryidno=pg_query("select \"IDNO\" from \"insure\".\"InsureForce\" where \"InsFIDNO\"='$id'");
			$residno=pg_fetch_array($qryidno);
			list($idno)=$residno;
			
			$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
				\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
				\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
				\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
			select 
				\"IDNO\",\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
				\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\",
				\"C_TAX_MON\", \"C_StartDate\", \"CarID\", '$user_id', '$add_date', '$ud', 
				\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$idno' order by auto_id DESC limit 1";
			if($result_carregis=pg_query($in_carregis)){
			}else{
				$status++;
			}
		}
    }else{
        foreach($arr_fieldedit as $v){
            $ud = ${$v};
            $sql = "UPDATE \"insure\".\"InsureForce\" SET \"$v\"='$ud' WHERE \"InsFIDNO\"='$id' ";
            if(!$result=pg_query($sql)){
                $status++;
            }
			
			if($v=="Capacity"){ //กรณีเป็นการแก้ไข cc รถ
				//หา IDNO 
				$qryidno=pg_query("select \"IDNO\" from \"insure\".\"InsureForce\" where \"InsFIDNO\"='$id'");
				$residno=pg_fetch_array($qryidno);
				list($idno)=$residno;
				
				//หาข้อมูลรถ
				$qrycar=pg_query("select \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
					   \"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
					   \"C_TAX_MON\", \"C_StartDate\" ,\"RadioID\", \"CarType\",\"C_CAR_CC\",\"CarID\" from \"VCarregistemp\" where \"IDNO\"='$idno'"); 
				$rescar=pg_fetch_array($qrycar);
				list($C_REGIS, $C_CARNAME, $C_YEAR, $C_REGIS_BY, 
					 $C_COLOR, $C_CARNUM, $C_MARNUM, $C_Milage, $C_TAX_ExpDate, 
					 $C_TAX_MON, $C_StartDate ,$RadioID, $CarType,$C_CAR_CC,$CarID)=$rescar;
					 
				if($C_REGIS==""){ $C_REGIS="null";}else{ $C_REGIS="'".$C_REGIS."'"; }
				if($C_CARNAME==""){ $C_CARNAME="null";}else{ $C_CARNAME="'".$C_CARNAME."'"; }
				if($C_YEAR==""){ $C_YEAR="null";}else{ $C_YEAR="'".$C_YEAR."'"; }
				if($C_REGIS_BY==""){ $C_REGIS_BY="null";}else{ $C_REGIS_BY="'".$C_REGIS_BY."'"; }
				if($C_COLOR==""){ $C_COLOR="null";}else{ $C_COLOR="'".$C_COLOR."'"; }
				if($C_CARNUM==""){ $C_CARNUM="null";}else{ $C_CARNUM="'".$C_CARNUM."'"; }
				if($C_MARNUM==""){ $C_MARNUM="null";}else{ $C_MARNUM="'".$C_MARNUM."'"; }
				if($C_Milage==""){ $C_Milage="null";}else{ $C_Milage="'".$C_Milage."'"; }
				if($C_TAX_ExpDate==""){ $C_TAX_ExpDate="null";}else{ $C_TAX_ExpDate="'".$C_TAX_ExpDate."'"; }
				if($C_TAX_MON==""){ $C_TAX_MON="null";}else{ $C_TAX_MON="'".$C_TAX_MON."'"; }
				if($C_StartDate==""){ $C_StartDate="null";}else{ $C_StartDate="'".$C_StartDate."'"; }
				if($RadioID==""){ $RadioID="null";}else{ $RadioID="'".$RadioID."'"; }
				if($CarType==""){ $CarType="null";}else{ $CarType="'".$CarType."'"; }
				if($CarID==""){ $CarID="null";}else{ $CarID="'".$CarID."'"; }
				
				$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
					\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
					\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
					\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
				select 
					\"IDNO\",\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
					\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\",
					\"C_TAX_MON\", \"C_StartDate\", \"CarID\", '$user_id', '$add_date', '$ud', 
					\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$idno' order by auto_id DESC limit 1";
				
				if($result_carregis=pg_query($in_carregis)){
				}else{
					$status++;
				}
			}
        }
    }
	
	
}elseif( substr($id,0,1)=="U" ){
	if($Discount==""){
		$Discount=0;
	}else{
		$Discount=$Discount;
	}
    if($count_fieldedit == 1){
        $ud = ${$fieldedit};
        if($fieldedit == "InsID"){
            $sql = "UPDATE \"insure\".\"InsureUnforce\" SET \"TempInsID\"='$ud',\"Discount\"='$Discount' WHERE \"InsUFIDNO\"='$id' ";
        }else{
            $sql = "UPDATE \"insure\".\"InsureUnforce\" SET \"$fieldedit\"='$ud',\"Discount\"='$Discount' WHERE \"InsUFIDNO\"='$id' ";
        }
        if(!$result=pg_query($sql)){
            $status++;
        }
    }else{
        foreach($arr_fieldedit as $v){
            $ud = ${$v};
            if($v == "InsID"){
                $sql = "UPDATE \"insure\".\"InsureUnforce\" SET \"TempInsID\"='$ud' WHERE \"InsUFIDNO\"='$id' ";
            }else{
			   $sql = "UPDATE \"insure\".\"InsureUnforce\" SET \"$v\"='$ud' WHERE \"InsUFIDNO\"='$id' ";
            }
            if(!$result=pg_query($sql)){
                $status++;
            }
        }
    }

}elseif( substr($id,0,1)=="L" ){
	if($Discount==""){
		$Discount=0;
	}else{
		$Discount=$Discount;
	}
    if($count_fieldedit == 1){
        $ud = ${$fieldedit};
        if($fieldedit == "InsID"){
            $sql = "UPDATE \"insure\".\"InsureLive\" SET \"TempInsID\"='$ud',\"Discount\"='$Discount' WHERE \"InsLIDNO\"='$id' ";
        }else{
            $sql = "UPDATE \"insure\".\"InsureLive\" SET \"$fieldedit\"='$ud',\"Discount\"='$Discount' WHERE \"InsLIDNO\"='$id' ";
        }
        if(!$result=pg_query($sql)){
            $status++;
        }
    }else{
        foreach($arr_fieldedit as $v){
            $ud = ${$v};
            if($v == "InsID"){
                $sql = "UPDATE \"insure\".\"InsureLive\" SET \"TempInsID\"='$ud' WHERE \"InsLIDNO\"='$id' ";
            }else{
			   $sql = "UPDATE \"insure\".\"InsureLive\" SET \"$v\"='$ud' WHERE \"InsLIDNO\"='$id' ";
            }
            if(!$result=pg_query($sql)){
                $status++;
            }
        }
    }

}


    $sql = "UPDATE \"insure\".\"batch\" SET \"approve_date\"='$nowdate',\"approve_id\"='$user_id' WHERE \"id\"='$id' ";
    if(!$result=pg_query($sql)){
        $status++;
    }

if($status == 0){
    //pg_query("ROLLBACK");
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติแก้ไขประกัน', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    $data['success'] = true;
    $data['message'] = "บันทึกเรียบร้อย";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้";
}

echo json_encode($data)
?>