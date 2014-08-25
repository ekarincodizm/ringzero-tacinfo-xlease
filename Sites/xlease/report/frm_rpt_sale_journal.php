<?php
include("../config/config.php");

$mm = $_POST['mm'];
$yy = $_POST['yy'];
$nowdate = nowDate();

pg_query("BEGIN WORK");
$status = 0;
$text_error = array();

$arr_chk = array();
$qry_array_chk=pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\" LIKE 'BSAL%' AND EXTRACT(MONTH FROM \"acb_date\")='$mm' AND EXTRACT(YEAR FROM \"acb_date\")='$yy' ");
while($res_array_chk=pg_fetch_array($qry_array_chk)){
    $arr_chk[] = $res_array_chk["auto_id"];
}

$j = 0;
$qry_in=pg_query("SELECT * FROM \"VRptSale\" where EXTRACT(MONTH FROM \"P_STDATE\")='$mm' AND EXTRACT(YEAR FROM \"P_STDATE\")='$yy' ORDER BY \"P_STDATE\" ");
while($res_in=pg_fetch_array($qry_in)){
    //ต้องตรวจสอบระหว่างสัญญาเก่ากับใหม่ก่อน ถ้าเก่าสัญญาเป็นดังนี้ เช่น 114-22 ถ้าใหม่ จะเป็น 12-22 ดังนั้นต้อง substring คนละที่
	if(substr($res_in["IDNO"],3,1)=="-"){ //เลขที่สัญญาเก่า
		$condition=substr($res_in["IDNO"],4,2) != 22;
	}else if(substr($res_in["IDNO"],2,1)=="-"){ //เลขที่สัญญาใหม่
		$condition=substr($res_in["IDNO"],3,2) != 22;
	}
	if($condition){
    $j+=1;
    $P_STDATE = $res_in["P_STDATE"];
    $IDNO = $res_in["IDNO"]; 
    $fullname = $res_in["fullname"];
    $asset_name = $res_in["asset_name"];
    $asset_regis = $res_in["asset_regis"];
    $P_DOWN = $res_in["P_DOWN"]; $P_DOWN = round($P_DOWN,2);
    $P_BEGINX = $res_in["P_BEGINX"]; $P_BEGINX = round($P_BEGINX,2);
    $intall = $res_in["intall"]; $intall = round($intall,2);
    $hpnonvat = $res_in["hpnonvat"]; $hpnonvat = round($hpnonvat,2);
    $vatall = $res_in["vatall"]; $vatall = round($vatall,2);
    $hpall = $res_in["hpall"]; $hpall = round($hpall,2);
    $asset_type = $res_in["asset_type"];
    
    $strDate = date("d",strtotime($P_STDATE));
    $str_gen_date = "$yy-$mm-$strDate";
    
    $gen_no=@pg_query("select account.\"gen_no\"('$str_gen_date','AP')");
    $genid=@pg_fetch_result($gen_no,0);
    if(@empty($genid)){
        $text_error[] = "gen_no<br />";
        $status++;
        break;
    }

    $ac_year = ($yy+543);
    $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$ac_year'");
    if($res_ac=@pg_fetch_array($qry_ac)){
        $acid_year = $res_ac["AcID"];
    }
    if(empty($acid_year)){
        $text_error[] = "SELECT AcID YEAR<br />";
        $status++;
        break;
    }
    
    if($asset_type == 1){
        $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='BHP'");  //$asset_type == 1 ให้ select ที่เป็น BHP มาใช้
    }elseif($asset_type == 2){
        $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='SGAS'");  //$asset_type == 2 ให้ select ที่เป็น SGAS มาใช้
    }
    if($res_ac=@pg_fetch_array($qry_ac)){
        $acid_bhp = $res_ac["AcID"];
    }
    if(empty($acid_bhp)){
        $text_error[] = "SELECT BHP or SGAS<br />";
        $status++;
        break;
    }
    
    $ac_year_gp = "GP".substr($ac_year,2,2);
    $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$ac_year_gp'");
    if($res_ac=@pg_fetch_array($qry_ac)){
        $acid_gp = $res_ac["AcID"];
    }
    if(empty($acid_gp)){
        $text_error[] = "SELECT GP<br />";
        $status++;
        break;
    }


    $count_autoid = 0;
    $qry_chk=@pg_query("SELECT COUNT(\"auto_id\") as \"count_autoid\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$str_gen_date' AND \"ref_id\" = 'BSAL#$IDNO' ");
    if($res_chk=@pg_fetch_array($qry_chk)){
        $count_autoid = $res_chk["count_autoid"];
    }
    if($count_autoid != 0){
        $qry_chk_w=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$str_gen_date' AND \"ref_id\" LIKE 'BSAL#$IDNO'");
        if($res_chk_w=@pg_fetch_array($qry_chk_w)){
            $auto_id = $res_chk_w["auto_id"];
            $diff = array($auto_id);
            $arr_chk = array_diff($arr_chk, $diff);
        }
        
        $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$str_gen_date',\"acb_detail\"='รับรู้การขาย ของเลขที่สัญญา $IDNO',\"ref_id\"='BSAL#$IDNO' 
        WHERE \"auto_id\"='$auto_id'";
        if(!$res_up_sql=@pg_query($up_sql)){
            $text_error[] = "UPDATE AccountBookHead<br />$up_sql<br />";
            $status++;
            break;
        }
        
        $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
        if(!$del_detail){
            $text_error[] = "DELETE AccountBookDetail<br />$del_detail<br />";
            $status++;
            break;
        }else{
            if($hpnonvat != 0){
                $qry1="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$auto_id','$acid_year','$hpnonvat','0','$IDNO')";
                if(!$res1=@pg_query($qry1)){
                    $text_error[] = "INSERT AccountBookDetail 1<br />";
                    $status++;
                    break;
                }
            }
        
            $sum_begindown = $P_DOWN+$P_BEGINX;
            if($sum_begindown != 0){
                $qry2="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$auto_id','$acid_bhp','0','$sum_begindown','$IDNO')";
                if(!$res2=@pg_query($qry2)){
                    $text_error[] = "INSERT AccountBookDetail 2<br />";
                    $status++;
                    break;
                }
            }

            if($intall != 0){
                $qry3="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$auto_id','$acid_gp','0','$intall','$IDNO')";
                if(!$res3=@pg_query($qry3)){
                    $text_error[] = "INSERT AccountBookDetail 3<br />";
                    $status++;
                    break;
                }
            }
        }

        /*
        $ud_detail=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
        while($res_ud_detail=@pg_fetch_array($ud_detail)){
            $autodt_id = $res_ud_detail["auto_id"];
            
            $hhh++;
            if($hhh == 1){
                $up11_sql="UPDATE \"account\".\"AccountBookDetail\" SET \"AcID\"='$acid_year',\"AmtDr\"='$hpnonvat',\"AmtCr\"='0',\"RefID\"='$IDNO' WHERE \"auto_id\"='$autodt_id'";
            }elseif($hhh == 2){
                $sum_begindown = $P_DOWN+$P_BEGINX;
                $up11_sql="UPDATE \"account\".\"AccountBookDetail\" SET \"AcID\"='$acid_bhp',\"AmtDr\"='0',\"AmtCr\"='$sum_begindown',\"RefID\"='$IDNO' WHERE \"auto_id\"='$autodt_id'";
            }elseif($hhh == 3){
                $up11_sql="UPDATE \"account\".\"AccountBookDetail\" SET \"AcID\"='$acid_gp',\"AmtDr\"='0',\"AmtCr\"='$intall',\"RefID\"='$IDNO' WHERE \"auto_id\"='$autodt_id'";
                $hhh=0;
            }
            if(!$res_up11_sql=@pg_query($up11_sql)){
                $text_error[] = "UPDATE AccountBookDetail<br />";
                $status++;
                break;
            }
        }
        */
    }else{

        $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
        values  ('AP','$genid','$str_gen_date','รับรู้การขาย ของเลขที่สัญญา $IDNO',DEFAULT,'BSAL#$IDNO')";
        if(!$res_in_sql=@pg_query($in_sql)){
            $text_error[] = "INSERT AccountBookHead<br />";
            $status++;
            break;
        }

        $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
        $res_auto_id=@pg_fetch_result($atid,0);
        if(empty($res_auto_id)){
            $text_error[] = "SELECT AccountBookHead_auto_id_seq 1<br />";
            $status++;
            break;
        }

        if($hpnonvat != 0){
            $qry1="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$res_auto_id','$acid_year','$hpnonvat','0','$IDNO')";
            if(!$res1=@pg_query($qry1)){
                $text_error[] = "INSERT AccountBookDetail 1<br />";
                $status++;
                break;
            }
        }
        
        $sum_begindown = $P_DOWN+$P_BEGINX;
        if($sum_begindown != 0){
            $qry2="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$res_auto_id','$acid_bhp','0','$sum_begindown','$IDNO')";
            if(!$res2=@pg_query($qry2)){
                $text_error[] = "INSERT AccountBookDetail 2<br />";
                $status++;
                break;
            }
        }

        if($intall != 0){
            $qry3="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$res_auto_id','$acid_gp','0','$intall','$IDNO')";
            if(!$res3=@pg_query($qry3)){
                $text_error[] = "INSERT AccountBookDetail 3<br />";
                $status++;
                break;
            }
        }
    
    }

    } // IF
} // WHILE


if(!empty($arr_chk)){
    foreach($arr_chk as $s){
        $up_sql3="UPDATE \"account\".\"AccountBookHead\" SET \"cancel\"='TRUE' WHERE \"auto_id\"='$s'";
        if(!$res_up_sql3=@pg_query($up_sql3)){
            $text_error[] = "UPDATE AccountBookHead Cancel<br />";
            $status++;
            break;
        }
    }
}

if($status == 0){
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    $data['success'] = true;
    $data['message'] = "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้<br />$text_error[0]";
}

echo json_encode($data);
?>