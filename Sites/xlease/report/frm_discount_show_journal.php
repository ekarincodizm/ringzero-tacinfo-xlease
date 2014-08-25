<?php
include("../config/config.php");

$yy=$_POST['year'];
$nowdate = nowDate();

pg_query("BEGIN WORK");
$status = 0;
$text_error = array();

$qry_array_chk=pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"cancel\"='FALSE' AND \"ref_id\" LIKE 'PSL%' AND EXTRACT(YEAR FROM \"acb_date\")='$yy'");
while($res_array_chk=pg_fetch_array($qry_array_chk)){
    $arr_chk[] = $res_array_chk["auto_id"];
}

$qry=@pg_query("SELECT * FROM \"Fp\" WHERE \"P_SL\" <> '0' AND EXTRACT(YEAR FROM \"P_CLDATE\")='$yy' ORDER BY \"IDNO\" ");
while($res=@pg_fetch_array($qry)){
    $IDNO = $res["IDNO"];
    $P_CLDATE = $res["P_CLDATE"];
    $P_CustByYear = ($res["P_CustByYear"]+543);
    $P_SL = $res["P_SL"];

    $gen_no=@pg_query("select account.\"gen_no\"('$P_CLDATE','AP')");
    $genid=@pg_fetch_result($gen_no,0);
    if(@empty($genid)){
        $text_error[] = "gen_no<br />";
        $status++;
        break;
    }

    $qry_chk=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"cancel\"='FALSE' AND \"acb_date\"='$P_CLDATE' AND \"ref_id\"='PSL#$IDNO' ");
    if($res_chk=@pg_fetch_array($qry_chk)){
        $auto_id = $res_chk["auto_id"];
        $diff = array($auto_id);
        $arr_chk = array_diff($arr_chk, $diff);

        //Update
        $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$P_CLDATE',\"acb_detail\"='ส่วนลดจ่ายลูกหนี้เลขที่สัญญา $IDNO',\"ref_id\"='PSL#$IDNO' WHERE \"auto_id\"='$auto_id'";
        if(!$res_up_sql=@pg_query($up_sql)){
            $text_error[] = "UPDATE AccountBookHead<br />$up_sql<br />";
            $status++;
            break;
        }
        
        $ud_detail=@pg_query("SELECT \"auto_id\",\"AmtDr\",\"AmtCr\" FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
        while($res_ud_detail=@pg_fetch_array($ud_detail)){
            $auto_id = $res_ud_detail["auto_id"];
            $AmtDr = $res_ud_detail["AmtDr"];
            $AmtCr = $res_ud_detail["AmtCr"];
            
            $acid = "";
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$P_CustByYear'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid = $res_ac["AcID"];
            }
            if(empty($acid)){
                $text_error[] = "SELECT AcID1<br />";
                $status++;
                break;
            }
            
            $acid_psl = "";
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='PSL'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_psl = $res_ac["AcID"];
            }
            if(empty($acid_psl)){
                $text_error[] = "SELECT PSL<br />";
                $status++;
                break;
            }
            
            if($AmtDr == 0 AND $AmtCr !=0){
                $up2_sql="UPDATE \"account\".\"AccountBookDetail\" SET \"AcID\"='$acid',\"AmtDr\"='0',\"AmtCr\"='$P_SL',\"RefID\"='$IDNO' WHERE \"auto_id\"='$auto_id'";
            }elseif($AmtDr != 0 AND $AmtCr ==0){
                $up2_sql="UPDATE \"account\".\"AccountBookDetail\" SET \"AcID\"='$acid_psl',\"AmtDr\"='$P_SL',\"AmtCr\"='0',\"RefID\"='$IDNO' WHERE \"auto_id\"='$auto_id'";
            }
            if(!$res_up2_sql=@pg_query($up2_sql)){
                $text_error[] = "UPDATE AccountBookDetail<br />";
                $status++;
                break;
            }
        }
        
    }else{
        //Insert
        $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") values  ('AP','$genid','$P_CLDATE','ส่วนลดจ่ายลูกหนี้เลขที่สัญญา $IDNO',DEFAULT,'PSL#$IDNO')";
        if(!$res_in_sql=@pg_query($in_sql)){
            $text_error[] = "INSERT AccountBookHead<br />$in_sql<br />";
            $status++;
            break;
        }
        
        $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
        $res_auto_id=@pg_fetch_result($atid,0);
        if(empty($res_auto_id)){
            $text_error[] = "SELECT AccountBookHead_auto_id_seq<br />";
            $status++;
            break;
        }
        
        $acid = "";
        $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$P_CustByYear'");
        if($res_ac=@pg_fetch_array($qry_ac)){
            $acid = $res_ac["AcID"];
        }
        if(empty($acid)){
            $text_error[] = "SELECT AcID2<br />";
            $status++;
            break;
        }
        
        $acid_psl = "";
        $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='PSL'");
        if($res_ac=@pg_fetch_array($qry_ac)){
            $acid_psl = $res_ac["AcID"];
        }
        if(empty($acid_psl)){
            $text_error[] = "SELECT PSL 2<br />";
            $status++;
            break;
        }
        
        $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$res_auto_id','$acid_psl','$P_SL','0','$IDNO')";
        if(!$res_indt_sql=@pg_query($indt_sql)){
            $text_error[] = "INSERT AccountBookDetail1<br />";
            $status++;
            break;
        }
        
        $indt_sql2="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\") values  ('$res_auto_id','$acid','0','$P_SL','$IDNO')";
        if(!$res_indt_sql2=@pg_query($indt_sql2)){
            $text_error[] = "INSERT AccountBookDetail2<br />";
            $status++;
            break;
        }
    }

}

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