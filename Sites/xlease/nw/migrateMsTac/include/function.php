<?php
$nowdate = nowDate();

function CheckAuth(){
    if(empty($_SESSION["ta_iduser"])){
        return false;
    }else{
        return true;
    }
}

function insertZero($inputValue,$digit){
    $str = "" . $inputValue;
    while (strlen($str) < $digit){
        $str = "0" . $str;
    }
    return $str;
}

function GetCusID(){
    $qry = pg_query("SELECT COUNT(\"cus_id\") AS countid FROM \"Customers\"");
    $res = pg_fetch_array($qry);
    $res_count=$res['countid'];
    if($res_count == 0){
        $res_sn = 1;
    }else{
        $res_sn = $res_count+1;
    }

    $cus_id = "CUS".insertZero($res_sn,5);
    return $cus_id;
}

function GetCarID(){
    $qry = pg_query("SELECT COUNT(\"car_id\") AS countid FROM \"Cars\"");
    $res = pg_fetch_array($qry);
    $res_count=$res['countid'];
    if($res_count == 0){
        $res_sn = 1;
    }else{
        $res_sn = $res_count+1;
    }

    $car_id = "CAR".insertZero($res_sn,5);
    return $car_id;
}
function GetRadioID(){
    $qry = pg_query("SELECT COUNT(\"radio_id\") AS countid FROM \"Radios\"");
    $res = pg_fetch_array($qry);
    $res_count=$res['countid'];
    if($res_count == 0){
        $res_sn = 1;
    }else{
        $res_sn = $res_count+1;
    }

    $rad_id = "RAD".insertZero($res_sn,5);
    return $rad_id;
}


function CusName($id){
    $qry = pg_query("SELECT pre_name,cus_name,surname FROM \"Customers\" WHERE cus_id='$id' ");
    if($res = pg_fetch_array($qry)){
        $pre_name=trim($res['pre_name']);
        $cus_name=trim($res['cus_name']);
        $surname=trim($res['surname']);
        return $pre_name." ".$cus_name." ".$surname;
    }else{
        return "";
    }
}
function GetYear($daystr)
	{
		if (trim($daystr) == "")
		{
			return "";
		}
		else
		{
			$d = explode("-" , $daystr);
			
		
			return $d[0];
		}
	}
	function TransTHDate($daystr)
	{
		if (trim($daystr) == "")
		{
			return "";
		}
		else
		{
			$d = explode("-" , $daystr);
			$d[0] = $d[0] + 543;
		
			return $d[2] . "/" . $d[1] . "/" . $d[0];
		}
	}
	function GetGraID(){
    $qry = pg_query("SELECT COUNT(\"gra_id\") AS countid FROM \"Garages\"");
    $res = pg_fetch_array($qry);
    $res_count=$res['countid'];
    if($res_count == 0){
        $res_sn = 1;
    }else{
        $res_sn = $res_count+1;
    }

    $gra_id = "GRA".insertZero($res_sn,3);
    return $gra_id;
}
function GetRgID(){
    $qry = pg_query("SELECT COUNT(\"regular_id\") AS countid FROM \"RegularCustomers\"");
    $res = pg_fetch_array($qry);
    $res_count=$res['countid'];
    if($res_count == 0){
        $res_sn = 1;
    }else{
        $res_sn = $res_count+1;
    }

    $rg_id = "RG".insertZero($res_sn,4);
    return $rg_id;
}

?>