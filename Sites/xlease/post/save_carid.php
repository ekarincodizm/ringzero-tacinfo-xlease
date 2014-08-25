<?php
session_start();
//global $reslast,$rescc,$rescount;

 $c_regis=$_POST[car_regis];
 $c_number=$_POST[car_number];
 $c_engine=$_POST[car_engine];

 $pf_cusname=$_POST[fcus_name];
 $pf_cusid=$_POST[fcus_id];

 include("../config/config.php");

 $qrylast=pg_query("select *   from \"ContactID\" ");
 $reslast=pg_fetch_array($qrylast);
 $ta=$reslast["carid"];

 function insertZero($inputValue , $digit )
		{
			$str = "" . $inputValue;
			while (strlen($str) < $digit)
			{
				$str = "0" . $str;
			}
			return $str;
        }

		$a = $ta+1;
 echo	$car_sn="TAX".insertZero($a , 5);

 $in_sql="insert into \"Fc\" (\"CarID\",\"C_REGIS\",\"C_CARNUM\",\"C_MARNUM\") 
          values  
          ('$car_sn','$c_regis','$c_number','$c_engine')";
 if($result=pg_query($in_sql))
 {
  $status ="OK".$in_sql;
 }
 else
 {
  $status ="error insert Re".$in_sql;
 }

//echo $status;
echo "<meta http-equiv=\"refresh\" content=\"0;URL=av_step3.php?car_id=$car_sn&cus_id=$pf_cusid&cus_name=$pf_cusname\">"."<br>";   


?>
