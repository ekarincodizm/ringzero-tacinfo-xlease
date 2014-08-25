<?php 
session_start();
include("config/config.php"); 
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;

$qry=pg_query("SELECT \"CarID\",\"C_StartDate\" FROM \"VCarregistemp\" ORDER BY \"CarID\" ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $CarID = "";
    $C_StartDate = "";
    $new_date = "";
    $CarID = $res['CarID'];
    $C_StartDate = $res['C_StartDate'];

    if(empty($C_StartDate) || $C_StartDate == ''){
        $date_empty+=1; 
    }else{
        list($n_year,$n_month,$n_day) = split('-',$C_StartDate);
        if($n_month == 11 || $n_month == 12){
            $new_date = "2010-$n_month-$n_day";
        }else{
            $new_date = "2011-$n_month-$n_day";
        }
        
		
		$up_sql="UPDATE \"Fc\" SET \"C_TAX_ExpDate\"='$new_date' WHERE \"CarID\"='$CarID'";
        if($result=pg_query($up_sql)){
            $status_true+=1;
        }else{
            $status+=1;
        }
		
		//นำเลขที่สัญญาล่าสุดที่ใช้ทะเบียนนี้มา insert
		$qrycarnow=pg_query("select \"IDNO\" from \"Fp\" a
		left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$CarID' order by \"P_STDATE\" DESC limit 1");
		$rescarnow=pg_fetch_array($qrycarnow);
		list($idnonow)=$rescarnow;

		$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
			\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
			\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
			\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
		select 
			\"IDNO\",\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
			\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", '$new_date',
			\"C_TAX_MON\", \"C_StartDate\", \"CarID\", '$add_user', '$add_date', \"C_CAR_CC\", 
			\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$idnonow' order by auto_id DESC limit 1";

			if($result_carregis=pg_query($in_carregis)){
		}else{
			$status++;
		}
        
    }
}


if($status == 0){
    pg_query("COMMIT");
    echo "Total : $rows rows<br />Update success : $status_true rows<br />Empty date : $date_empty rows";
}else{
    pg_query("ROLLBACK");
    echo "error !";
}

?>