<?php
include("../config/config.php");

pg_query("BEGIN WORK");

$mm = $_POST['mm'];
$yy = $_POST['yy'];
$status = 0;
$text_error = array();
$arr_chk = array();
$arr_CustYear = array();

//หารายการเดิมของ Head เพื่อเอาไว้ตรวจสอบและลบออก
$qry_array_chk=pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\" LIKE 'BREC%' AND EXTRACT(MONTH FROM \"acb_date\")='$mm' AND EXTRACT(YEAR FROM \"acb_date\")='$yy' ");
while($res_array_chk=pg_fetch_array($qry_array_chk)){
    $arr_chk[] = $res_array_chk["auto_id"];
}

$qry_in=pg_query("SELECT * FROM \"Fr\" where EXTRACT(MONTH FROM \"R_Date\")='$mm' AND EXTRACT(YEAR FROM \"R_Date\")='$yy' AND \"Cancel\"='false' 
ORDER BY \"R_Date\",\"R_Receipt\",\"R_DueNo\" ASC ");
$num_row = pg_num_rows($qry_in);
while($res_in=pg_fetch_array($qry_in)){
    $p_sl = 0;
    $j++;
    $IDNO = $res_in["IDNO"]; 
    $R_DueNo = $res_in["R_DueNo"];
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"]; if($j==1) $old_date = $R_Date;
    $R_Money = $res_in["R_Money"]; $R_Money = round($R_Money,2);
    $R_Bank = $res_in["R_Bank"];
    $cur_year = $res_in["CustYear"];
    
    if(@empty($cur_year) AND $R_DueNo < 99){
        $text_error[] = "empty CustYear $R_Date<br />";
        $status++;
        break;
    }

    //หา VAT
    $qry_in3=pg_query("select \"VatValue\" from \"FVat\" WHERE (\"IDNO\"='$IDNO' AND \"V_DueNo\"='$R_DueNo' AND \"Cancel\"='FALSE')");
    if($res_in3=pg_fetch_array($qry_in3)){
        $vat = $res_in3["VatValue"]; $vat = round($vat,2);
    }

    //หาส่วนลด
    $qry_in4=pg_query("select \"P_SL\" from \"Fp\" WHERE (\"IDNO\"='$IDNO' AND \"P_TOTAL\"='$R_DueNo')");
    if($res_in4=pg_fetch_array($qry_in4)){
        $p_sl = $res_in4["P_SL"]; $p_sl = round($p_sl,2);
    }

    if($R_Date != $old_date){ //ตรวจสอบเพื่อสรุปยอดรวมแต่ละวัน
        $show_unique_CustYear = array_unique($arr_CustYear);
        sort($show_unique_CustYear);
        foreach($show_unique_CustYear as $v){
            if( ${'cu_'.$v} == 0 && ${'cu_vat'.$v} == 0 && ${'ca_'.$v} == 0 && ${'ca_vat'.$v} == 0 ){
                
            }else{
            $cu = round(${'cu_'.$v},2);
            $cuvat = round(${'cu_vat'.$v},2);
            $ca = round(${'ca_'.$v},2);
            $cavat = round(${'ca_vat'.$v},2);
            
            $gen_no=@pg_query("select account.\"gen_no\"('$old_date','AP')");
            $genid=@pg_fetch_result($gen_no,0);
            if(@empty($genid)){
                $text_error[] = "gen_no<br />";
                $status++;
                break;
            }
            
            $gen_no2=@pg_query("select account.\"gen_no\"('$old_date','AP')");
            $genid2=@pg_fetch_result($gen_no2,0);
            if(@empty($genid2)){
                $text_error[] = "gen_no2<br />";
                $status++;
                break;
            }
            
            $ac_year = ($v+543);
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$ac_year'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_year = $res_ac["AcID"];
            }
            if(empty($acid_year)){
                $text_error[] = "SELECT AcID YEAR [$ac_year] [R_Date = $R_Date / R_Receipt = $R_Receipt]<br />$qry_ac<br />";
                $status++;
                break;
            }
            
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='SAV1'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_sav1 = $res_ac["AcID"];
            }
            if(empty($acid_sav1)){
                $text_error[] = "SELECT SAV1<br />";
                $status++;
                break;
            }
            
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='CASH'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_cash = $res_ac["AcID"];
            }
            if(empty($acid_cash)){
                $text_error[] = "SELECT CASH<br />";
                $status++;
                break;
            }

            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='AVAT'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_avat = $res_ac["AcID"];
            }
            if(empty($acid_avat)){
                $text_error[] = "SELECT AVAT<br />";
                $status++;
                break;
            }

            $count_autoid = 0;
            $qry_chk=@pg_query("SELECT COUNT(\"auto_id\") as \"count_autoid\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$old_date' AND \"acb_detail\" LIKE '%$v' ");
            if($res_chk=@pg_fetch_array($qry_chk)){
                $count_autoid = $res_chk["count_autoid"];
            }
            
            if($count_autoid != 0){
                if($count_autoid == 1){
                    $qry_chk_w=@pg_query("SELECT \"auto_id\",\"acb_detail\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$old_date' AND \"acb_detail\" LIKE '%$v' ORDER BY \"auto_id\" ASC");
                    while($res_chk_w=@pg_fetch_array($qry_chk_w)){
                        $auto_id = $res_chk_w["auto_id"];
                        $acb_detail = $res_chk_w["acb_detail"];
                        $diff = array($auto_id);
                        $arr_chk = array_diff($arr_chk, $diff);
                        
                        if( strstr($acb_detail,"ภาษี") ){
                            $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid2',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',
                            \"ref_id\"='BREC' WHERE \"auto_id\"='$auto_id'";
                            if(!$res_up_sql=@pg_query($up_sql)){
                                $text_error[] = "UPDATE AccountBookHead 4.1<br />$up_sql<br />";
                                $status++;
                                break;
                            }
                            
                            $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                            values  ('AP','$genid','$old_date','บันทึกรับเงินลูกหนี้ปี $v',DEFAULT,'BREC')";
                            if(!$res_in_sql=@pg_query($in_sql)){
                                $text_error[] = "INSERT AccountBookHead<br />";
                                $status++;
                                break;
                            }

                            $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                            $res_auto_id1=@pg_fetch_result($atid,0);
                            if(empty($res_auto_id1)){
                                $text_error[] = "SELECT AccountBookHead_auto_id_seq 1<br />";
                                $status++;
                                break;
                            }
                            $res_auto_id2 = $auto_id;
                        }else{
                            $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ปี $v',\"ref_id\"='BREC' 
                            WHERE \"auto_id\"='$auto_id'";
                            if(!$res_up_sql=@pg_query($up_sql)){
                                $text_error[] = "UPDATE AccountBookHead 4.2<br />$up_sql<br />";
                                $status++;
                                break;
                            }
                            
                            $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                            values  ('AP','$genid2','$old_date','บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',DEFAULT,'BREC')";
                            if(!$res_in_sql=@pg_query($in_sql)){
                                $text_error[] = "INSERT AccountBookHead<br />";
                                $status++;
                                break;
                            }

                            $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                            $res_auto_id2=@pg_fetch_result($atid,0);
                            if(empty($res_auto_id2)){
                                $text_error[] = "SELECT AccountBookHead_auto_id_seq 2<br />";
                                $status++;
                                break;
                            }
                            $res_auto_id1 = $auto_id;
                        }
                        
                        $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
                        if(!$del_detail){
                            $text_error[] = "DELETE AccountBookDetail 8<br />$del_detail<br />";
                            $status++;
                            break;
                        }
                        
                    }
                    
                if($cu != 0 OR $ca != 0){
                    if($cu != 0){
                        $qry_cu="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_sav1','$cu','0')";
                        if(!$res_cu=@pg_query($qry_cu)){
                            $text_error[] = "INSERT AccountBookDetail 1.1<br />";
                            $status++;
                            break;
                        }
                    }
                
                    if($ca != 0){
                        $qry_ca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_cash','$ca','0')";
                        if(!$res_ca=@pg_query($qry_ca)){
                            $text_error[] = "INSERT AccountBookDetail 1.2<br />";
                            $status++;
                            break;
                        }
                    }
                
                    $cuca = ($cu+$ca);
                    if($cuca != 0){
                        $qry_cuca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_year','0','$cuca')";
                        if(!$res_cuca=@pg_query($qry_cuca)){
                            $text_error[] = "INSERT AccountBookDetail 1.3<br />";
                            $status++;
                            break;
                        }
                    }
                }

                if($cuvat != 0 OR $cavat != 0){
                    if($cuvat != 0){
                        $qry_cuvat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_sav1','$cuvat','0')";
                        if(!$res_cuvat=@pg_query($qry_cuvat)){
                            $text_error[] = "INSERT AccountBookDetail2.1<br />";
                            $status++;
                            break;
                        }
                    }

                    if($cavat != 0){
                        $qry_cavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_cash','$cavat','0')";
                        if(!$res_cavat=@pg_query($qry_cavat)){
                            $text_error[] = "INSERT AccountBookDetail 2.2<br />";
                            $status++;
                            break;
                        }
                    }

                    $cuvatcavat = ($cuvat+$cavat);
                    if($cuvatcavat != 0){
                        $qry_cuvatcavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_avat','0','$cuvatcavat')";
                        if(!$res_cuvatcavat=@pg_query($qry_cuvatcavat)){
                            $text_error[] = "INSERT AccountBookDetail 2.3<br />";
                            $status++;
                            break;
                        }
                    }
                }
                    
                }else{
                    $qry_chk_w=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$old_date' AND \"acb_detail\" LIKE '%$v' ORDER BY \"auto_id\" ASC");
                    while($res_chk_w=@pg_fetch_array($qry_chk_w)){
                        $auto_id = $res_chk_w["auto_id"];
                        $diff = array($auto_id);
                        $arr_chk = array_diff($arr_chk, $diff);

                        $cccc++;
                        if($cccc == 1){
                            $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ปี $v',\"ref_id\"='BREC' 
                            WHERE \"auto_id\"='$auto_id'";
                            if(!$res_up_sql=@pg_query($up_sql)){
                                $text_error[] = "UPDATE AccountBookHead<br />$up_sql<br />";
                                $status++;
                                break;
                            }
                        }else{
                            $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid2',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',
                            \"ref_id\"='BREC' WHERE \"auto_id\"='$auto_id'";
                            if(!$res_up_sql=@pg_query($up_sql)){
                                $text_error[] = "UPDATE AccountBookHead<br />$up_sql<br />";
                                $status++;
                                break;
                            }
                            $cccc = 0;
                        }
                    
                        $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
                        if(!$del_detail){
                            $text_error[] = "DELETE AccountBookDetail<br />$del_detail<br />";
                            $status++;
                            break;
                        }else{
                            
                            if( ($cu != 0 OR $ca != 0) AND $cccc == 1 ){
                                if($cu != 0){
                                    $qry_cu="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_sav1','$cu','0')";
                                    if(!$res_cu=@pg_query($qry_cu)){
                                        $text_error[] = "INSERT AccountBookDetail 1.1<br />";
                                        $status++;
                                        break;
                                    }
                                }
                            
                                if($ca != 0){
                                    $qry_ca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_cash','$ca','0')";
                                    if(!$res_ca=@pg_query($qry_ca)){
                                        $text_error[] = "INSERT AccountBookDetail 1.2<br />";
                                        $status++;
                                        break;
                                    }
                                }
                            
                                $cuca = ($cu+$ca);
                                if($cuca != 0){
                                    $qry_cuca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_year','0','$cuca')";
                                    if(!$res_cuca=@pg_query($qry_cuca)){
                                        $text_error[] = "INSERT AccountBookDetail 1.3<br />";
                                        $status++;
                                        break;
                                    }
                                }
                            }

                            if( ($cuvat != 0 OR $cavat != 0) AND $cccc != 1 ){
                                if($cuvat != 0){
                                    $qry_cuvat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_sav1','$cuvat','0')";
                                    if(!$res_cuvat=@pg_query($qry_cuvat)){
                                        $text_error[] = "INSERT AccountBookDetail2.1<br />";
                                        $status++;
                                        break;
                                    }
                                }

                                if($cavat != 0){
                                    $qry_cavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_cash','$cavat','0')";
                                    if(!$res_cavat=@pg_query($qry_cavat)){
                                        $text_error[] = "INSERT AccountBookDetail 2.2<br />";
                                        $status++;
                                        break;
                                    }
                                }

                                $cuvatcavat = ($cuvat+$cavat);
                                if($cuvatcavat != 0){
                                    $qry_cuvatcavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_avat','0','$cuvatcavat')";
                                    if(!$res_cuvatcavat=@pg_query($qry_cuvatcavat)){
                                        $text_error[] = "INSERT AccountBookDetail 2.3<br />";
                                        $status++;
                                        break;
                                    }
                                }
                            }
                            
                        }
                    
                    }
                }

            }else{  //Insert
                $innn++;
                $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                values  ('AP','$genid','$old_date','บันทึกรับเงินลูกหนี้ปี $v',DEFAULT,'BREC')";
                if(!$res_in_sql=@pg_query($in_sql)){
                    $text_error[] = "INSERT AccountBookHead<br />";
                    $status++;
                    break;
                }

                $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                $res_auto_id1=@pg_fetch_result($atid,0);
                if(empty($res_auto_id1)){
                    $text_error[] = "SELECT AccountBookHead_auto_id_seq 1<br />";
                    $status++;
                    break;
                }
                
                $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                values  ('AP','$genid2','$old_date','บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',DEFAULT,'BREC')";
                if(!$res_in_sql=@pg_query($in_sql)){
                    $text_error[] = "INSERT AccountBookHead<br />";
                    $status++;
                    break;
                }

                $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                $res_auto_id2=@pg_fetch_result($atid,0);
                if(empty($res_auto_id2)){
                    $text_error[] = "SELECT AccountBookHead_auto_id_seq 2<br />";
                    $status++;
                    break;
                }

                if($cu != 0 OR $ca != 0){
                    if($cu != 0){
                        $qry_cu="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_sav1','$cu','0')";
                        if(!$res_cu=@pg_query($qry_cu)){
                            $text_error[] = "INSERT AccountBookDetail 1.1<br />";
                            $status++;
                            break;
                        }
                    }
                
                    if($ca != 0){
                        $qry_ca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_cash','$ca','0')";
                        if(!$res_ca=@pg_query($qry_ca)){
                            $text_error[] = "INSERT AccountBookDetail 1.2<br />";
                            $status++;
                            break;
                        }
                    }
                
                    $cuca = ($cu+$ca);
                    if($cuca != 0){
                        $qry_cuca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_year','0','$cuca')";
                        if(!$res_cuca=@pg_query($qry_cuca)){
                            $text_error[] = "INSERT AccountBookDetail 1.3<br />";
                            $status++;
                            break;
                        }
                    }
                }

                if($cuvat != 0 OR $cavat != 0){
                    if($cuvat != 0){
                        $qry_cuvat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_sav1','$cuvat','0')";
                        if(!$res_cuvat=@pg_query($qry_cuvat)){
                            $text_error[] = "INSERT AccountBookDetail2.1<br />";
                            $status++;
                            break;
                        }
                    }

                    if($cavat != 0){
                        $qry_cavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_cash','$cavat','0')";
                        if(!$res_cavat=@pg_query($qry_cavat)){
                            $text_error[] = "INSERT AccountBookDetail 2.2<br />";
                            $status++;
                            break;
                        }
                    }

                    $cuvatcavat = ($cuvat+$cavat);
                    if($cuvatcavat != 0){
                        $qry_cuvatcavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_avat','0','$cuvatcavat')";
                        if(!$res_cuvatcavat=@pg_query($qry_cuvatcavat)){
                            $text_error[] = "INSERT AccountBookDetail 2.3<br />";
                            $status++;
                            break;
                        }
                    }
                }
                
            }
                
            
            }
            
            ${'cu_'.$v} = 0;
            ${'cu_vat'.$v} = 0;
            ${'ca_'.$v} = 0;
            ${'ca_vat'.$v} = 0;
        }
        $arr_CustYear = array();

     
    }
    if($R_DueNo < 99){
        $arr_CustYear[] = $res_in["CustYear"];
    }
    $old_date = $R_Date;

    
    if($R_Bank == "CU"){
        ${'cu_'.$cur_year} += $R_Money-$p_sl;
        ${'cu_vat'.$cur_year} += $vat;
    }elseif( $R_Bank == "CA" OR $R_Bank == "CCA" ){
        ${'ca_'.$cur_year} += $R_Money-$p_sl;
        ${'ca_vat'.$cur_year} += $vat;
    }

    if($num_row == $j){

        $show_unique_CustYear = array_unique($arr_CustYear);
        sort($show_unique_CustYear);
        foreach($show_unique_CustYear as $v){
            if( ${'cu_'.$v} == 0 && ${'cu_vat'.$v} == 0 && ${'ca_'.$v} == 0 && ${'ca_vat'.$v} == 0 ){
                
            }else{
            $cu = round(${'cu_'.$v},2);
            $cuvat = round(${'cu_vat'.$v},2);
            $ca = round(${'ca_'.$v},2);
            $cavat = round(${'ca_vat'.$v},2);
            
            $gen_no=@pg_query("select account.\"gen_no\"('$old_date','AP')");
            $genid=@pg_fetch_result($gen_no,0);
            if(@empty($genid)){
                $text_error[] = "gen_no<br />";
                $status++;
                break;
            }
            
            $gen_no2=@pg_query("select account.\"gen_no\"('$old_date','AP')");
            $genid2=@pg_fetch_result($gen_no2,0);
            if(@empty($genid2)){
                $text_error[] = "gen_no2<br />";
                $status++;
                break;
            }
            
            $ac_year = ($v+543);
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$ac_year'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_year = $res_ac["AcID"];
            }
            if(empty($acid_year)){
                $text_error[] = "SELECT AcID YEAR<br />";
                $status++;
                break;
            }
            
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='SAV1'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_sav1 = $res_ac["AcID"];
            }
            if(empty($acid_sav1)){
                $text_error[] = "SELECT SAV1<br />";
                $status++;
                break;
            }
            
            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='CASH'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_cash = $res_ac["AcID"];
            }
            if(empty($acid_cash)){
                $text_error[] = "SELECT CASH<br />";
                $status++;
                break;
            }

            $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='AVAT'");
            if($res_ac=@pg_fetch_array($qry_ac)){
                $acid_avat = $res_ac["AcID"];
            }
            if(empty($acid_avat)){
                $text_error[] = "SELECT AVAT<br />";
                $status++;
                break;
            }

            $count_autoid = 0;
            $qry_chk=@pg_query("SELECT COUNT(\"auto_id\") as \"count_autoid\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$old_date' AND \"acb_detail\" LIKE '%$v' ");
            if($res_chk=@pg_fetch_array($qry_chk)){
                $count_autoid = $res_chk["count_autoid"];
            }

            if($count_autoid != 0){ //update
            
                if($count_autoid == 1){
                    $qry_chk_w=@pg_query("SELECT \"auto_id\",\"acb_detail\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$old_date' AND \"acb_detail\" LIKE '%$v' ORDER BY \"auto_id\" ASC");
                    while($res_chk_w=@pg_fetch_array($qry_chk_w)){
                        $auto_id = $res_chk_w["auto_id"];
                        $acb_detail = $res_chk_w["acb_detail"];
                        $diff = array($auto_id);
                        $arr_chk = array_diff($arr_chk, $diff);
                        
                        if( strstr($acb_detail,"ภาษี") ){
                            $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid2',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',
                            \"ref_id\"='BREC' WHERE \"auto_id\"='$auto_id'";
                            if(!$res_up_sql=@pg_query($up_sql)){
                                $text_error[] = "UPDATE AccountBookHead 4.1<br />$up_sql<br />";
                                $status++;
                                break;
                            }
                            
                            $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
                            if(!$del_detail){
                                $text_error[] = "DELETE AccountBookDetail 5.1<br />$del_detail<br />";
                                $status++;
                                break;
                            }
                            
                            $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                            values  ('AP','$genid','$old_date','บันทึกรับเงินลูกหนี้ปี $v',DEFAULT,'BREC')";
                            if(!$res_in_sql=@pg_query($in_sql)){
                                $text_error[] = "INSERT AccountBookHead<br />";
                                $status++;
                                break;
                            }

                            $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                            $res_auto_id1=@pg_fetch_result($atid,0);
                            if(empty($res_auto_id1)){
                                $text_error[] = "SELECT AccountBookHead_auto_id_seq 1<br />";
                                $status++;
                                break;
                            }
                            $res_auto_id2 = $auto_id;
                        }else{
                            $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ปี $v',\"ref_id\"='BREC' 
                            WHERE \"auto_id\"='$auto_id'";
                            if(!$res_up_sql=@pg_query($up_sql)){
                                $text_error[] = "UPDATE AccountBookHead 4.2<br />$up_sql<br />";
                                $status++;
                                break;
                            }
                            
                            $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
                            if(!$del_detail){
                                $text_error[] = "DELETE AccountBookDetail 5.2<br />$del_detail<br />";
                                $status++;
                                break;
                            }
                            
                            $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                            values  ('AP','$genid2','$old_date','บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',DEFAULT,'BREC')";
                            if(!$res_in_sql=@pg_query($in_sql)){
                                $text_error[] = "INSERT AccountBookHead<br />";
                                $status++;
                                break;
                            }

                            $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                            $res_auto_id2=@pg_fetch_result($atid,0);
                            if(empty($res_auto_id2)){
                                $text_error[] = "SELECT AccountBookHead_auto_id_seq 2<br />";
                                $status++;
                                break;
                            }
                            $res_auto_id1 = $auto_id;
                        }
                        
                    }
                    
                    if($cu != 0 OR $ca != 0){
                        if($cu != 0){
                            $qry_cu="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_sav1','$cu','0')";
                            if(!$res_cu=@pg_query($qry_cu)){
                                $text_error[] = "INSERT AccountBookDetail 1.1<br />";
                                $status++;
                                break;
                            }
                        }
                    
                        if($ca != 0){
                            $qry_ca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_cash','$ca','0')";
                            if(!$res_ca=@pg_query($qry_ca)){
                                $text_error[] = "INSERT AccountBookDetail 1.2<br />";
                                $status++;
                                break;
                            }
                        }
                    
                        $cuca = ($cu+$ca);
                        if($cuca != 0){
                            $qry_cuca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_year','0','$cuca')";
                            if(!$res_cuca=@pg_query($qry_cuca)){
                                $text_error[] = "INSERT AccountBookDetail 1.3<br />";
                                $status++;
                                break;
                            }
                        }
                    }

                    if($cuvat != 0 OR $cavat != 0){
                        if($cuvat != 0){
                            $qry_cuvat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_sav1','$cuvat','0')";
                            if(!$res_cuvat=@pg_query($qry_cuvat)){
                                $text_error[] = "INSERT AccountBookDetail2.1<br />";
                                $status++;
                                break;
                            }
                        }

                        if($cavat != 0){
                            $qry_cavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_cash','$cavat','0')";
                            if(!$res_cavat=@pg_query($qry_cavat)){
                                $text_error[] = "INSERT AccountBookDetail 2.2<br />";
                                $status++;
                                break;
                            }
                        }

                        $cuvatcavat = ($cuvat+$cavat);
                        if($cuvatcavat != 0){
                            $qry_cuvatcavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_avat','0','$cuvatcavat')";
                            if(!$res_cuvatcavat=@pg_query($qry_cuvatcavat)){
                                $text_error[] = "INSERT AccountBookDetail 2.3<br />";
                                $status++;
                                break;
                            }
                        }
                    }      
                }else{
            
                $qry_chk_w=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"acb_date\"='$old_date' AND \"acb_detail\" LIKE '%$v' ORDER BY \"auto_id\" ASC");
                while($res_chk_w=@pg_fetch_array($qry_chk_w)){
                    $auto_id = $res_chk_w["auto_id"];
                    $diff = array($auto_id);
                    $arr_chk = array_diff($arr_chk, $diff);

                    $cccc++;
                    if($cccc == 1){
                        $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ปี $v',\"ref_id\"='BREC' 
                        WHERE \"auto_id\"='$auto_id'";
                        if(!$res_up_sql=@pg_query($up_sql)){
                            $text_error[] = "UPDATE AccountBookHead<br />$up_sql<br />";
                            $status++;
                            break;
                        }
                    }else{
                        $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid2',\"acb_date\"='$old_date',\"acb_detail\"='บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',
                        \"ref_id\"='BREC' WHERE \"auto_id\"='$auto_id'";
                        if(!$res_up_sql=@pg_query($up_sql)){
                            $text_error[] = "UPDATE AccountBookHead<br />$up_sql<br />";
                            $status++;
                            break;
                        }
                        $cccc = 0;
                    }
                
                    $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id'");
                    if(!$del_detail){
                        $text_error[] = "DELETE AccountBookDetail<br />$del_detail<br />";
                        $status++;
                        break;
                    }else{
                        
                        if( ($cu != 0 OR $ca != 0) AND $cccc == 1 ){
                            if($cu != 0){
                                $qry_cu="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_sav1','$cu','0')";
                                if(!$res_cu=@pg_query($qry_cu)){
                                    $text_error[] = "INSERT AccountBookDetail 1.1<br />";
                                    $status++;
                                    break;
                                }
                            }
                        
                            if($ca != 0){
                                $qry_ca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_cash','$ca','0')";
                                if(!$res_ca=@pg_query($qry_ca)){
                                    $text_error[] = "INSERT AccountBookDetail 1.2<br />";
                                    $status++;
                                    break;
                                }
                            }
                        
                            $cuca = ($cu+$ca);
                            if($cuca != 0){
                                $qry_cuca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_year','0','$cuca')";
                                if(!$res_cuca=@pg_query($qry_cuca)){
                                    $text_error[] = "INSERT AccountBookDetail 1.3<br />";
                                    $status++;
                                    break;
                                }
                            }
                        }

                        if( ($cuvat != 0 OR $cavat != 0) AND $cccc != 1 ){
                            if($cuvat != 0){
                                $qry_cuvat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_sav1','$cuvat','0')";
                                if(!$res_cuvat=@pg_query($qry_cuvat)){
                                    $text_error[] = "INSERT AccountBookDetail2.1<br />";
                                    $status++;
                                    break;
                                }
                            }

                            if($cavat != 0){
                                $qry_cavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_cash','$cavat','0')";
                                if(!$res_cavat=@pg_query($qry_cavat)){
                                    $text_error[] = "INSERT AccountBookDetail 2.2<br />";
                                    $status++;
                                    break;
                                }
                            }

                            $cuvatcavat = ($cuvat+$cavat);
                            if($cuvatcavat != 0){
                                $qry_cuvatcavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$auto_id','$acid_avat','0','$cuvatcavat')";
                                if(!$res_cuvatcavat=@pg_query($qry_cuvatcavat)){
                                    $text_error[] = "INSERT AccountBookDetail 2.3<br />";
                                    $status++;
                                    break;
                                }
                            }
                        }
                        
                    }
                
                }
                }
            }else{  //Insert

                $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                values  ('AP','$genid','$old_date','บันทึกรับเงินลูกหนี้ปี $v',DEFAULT,'BREC')";
                if(!$res_in_sql=@pg_query($in_sql)){
                    $text_error[] = "INSERT AccountBookHead<br />";
                    $status++;
                    break;
                }

                $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                $res_auto_id1=@pg_fetch_result($atid,0);
                if(empty($res_auto_id1)){
                    $text_error[] = "SELECT AccountBookHead_auto_id_seq 1<br />";
                    $status++;
                    break;
                }
                
                $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"sub_type\",\"ref_id\") 
                values  ('AP','$genid2','$old_date','บันทึกรับเงินลูกหนี้ภาษีมูลค่าเพิ่มค้างรับของลูกหนี้ปี $v',DEFAULT,'BREC')";
                if(!$res_in_sql=@pg_query($in_sql)){
                    $text_error[] = "INSERT AccountBookHead<br />";
                    $status++;
                    break;
                }

                $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                $res_auto_id2=@pg_fetch_result($atid,0);
                if(empty($res_auto_id2)){
                    $text_error[] = "SELECT AccountBookHead_auto_id_seq 2<br />";
                    $status++;
                    break;
                }

                if($cu != 0 OR $ca != 0){
                    if($cu != 0){
                        $qry_cu="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_sav1','$cu','0')";
                        if(!$res_cu=@pg_query($qry_cu)){
                            $text_error[] = "INSERT AccountBookDetail 1.1<br />";
                            $status++;
                            break;
                        }
                    }
                
                    if($ca != 0){
                        $qry_ca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_cash','$ca','0')";
                        if(!$res_ca=@pg_query($qry_ca)){
                            $text_error[] = "INSERT AccountBookDetail 1.2<br />";
                            $status++;
                            break;
                        }
                    }
                
                    $cuca = ($cu+$ca);
                    if($cuca != 0){
                        $qry_cuca="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id1','$acid_year','0','$cuca')";
                        if(!$res_cuca=@pg_query($qry_cuca)){
                            $text_error[] = "INSERT AccountBookDetail 1.3<br />";
                            $status++;
                            break;
                        }
                    }
                }

                if($cuvat != 0 OR $cavat != 0){
                    if($cuvat != 0){
                        $qry_cuvat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_sav1','$cuvat','0')";
                        if(!$res_cuvat=@pg_query($qry_cuvat)){
                            $text_error[] = "INSERT AccountBookDetail2.1<br />";
                            $status++;
                            break;
                        }
                    }

                    if($cavat != 0){
                        $qry_cavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_cash','$cavat','0')";
                        if(!$res_cavat=@pg_query($qry_cavat)){
                            $text_error[] = "INSERT AccountBookDetail 2.2<br />";
                            $status++;
                            break;
                        }
                    }

                    $cuvatcavat = ($cuvat+$cavat);
                    if($cuvatcavat != 0){
                        $qry_cuvatcavat="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_avat','0','$cuvatcavat')";
                        if(!$res_cuvatcavat=@pg_query($qry_cuvatcavat)){
                            $text_error[] = "INSERT AccountBookDetail 2.3<br />";
                            $status++;
                            break;
                        }
                    }
                }
                
            }
                
            
            }
            
            ${'cu_'.$v} = 0;
            ${'cu_vat'.$v} = 0;
            ${'ca_'.$v} = 0;
            ${'ca_vat'.$v} = 0;
        }
        $arr_CustYear = array();

    }
    
}// WHILE


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