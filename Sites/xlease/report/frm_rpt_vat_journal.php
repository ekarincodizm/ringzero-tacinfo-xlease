<?php
include("../config/config.php");

pg_query("BEGIN WORK");

$mm=$_POST['mm'];
$yy=$_POST['yy'];
$vat=$_POST['vat']; $vat = round($vat,2);
$mlastdate = date("Y-m-t",strtotime("$yy-$mm-01"));

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$s_yy=$yy+543;

$status = 0;
$text_error = array();

$gen_no=@pg_query("select account.\"gen_no\"('$mlastdate','AP')");
$genid=@pg_fetch_result($gen_no,0);
if(@empty($genid)){
    $text_error[] = "gen_no1<br />";
    $status++;
}

$gen_no2=@pg_query("select account.\"gen_no\"('$mlastdate','AP')");
$genid2=@pg_fetch_result($gen_no2,0);
if(@empty($genid2)){
    $text_error[] = "gen_no2<br />";
    $status++;
}

$qry_avat=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='AVAT'");
if($res_avat=@pg_fetch_array($qry_avat)){
    $acid_avat = $res_avat["AcID"];
}
if(@empty($acid_avat)){
    $text_error[] = "SELECT AVAT<br />";
    $status++;
}

$qry_vats=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='VATS'");
if($res_vats=@pg_fetch_array($qry_vats)){
    $acid_vats = $res_vats["AcID"];
}
if(@empty($acid_vats)){
    $text_error[] = "SELECT VATS<br />";
    $status++;
}

$qry_vat=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='VAT'");
if($res_vat=@pg_fetch_array($qry_vat)){
    $acid_vat = $res_vat["AcID"];
}
if(@empty($acid_vat)){
    $text_error[] = "SELECT VAT<br />";
    $status++;
}

$qry_vatb=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='VATB'");
if($res_vatb=@pg_fetch_array($qry_vatb)){
    $acid_vatb = $res_vatb["AcID"];
}
if(@empty($acid_vatb)){
    $text_error[] = "SELECT VATB<br />";
    $status++;
}


$cid = 0;
$qry_array_chk=pg_query("SELECT * FROM \"account\".\"AccountBookHead\"
WHERE \"ref_id\" LIKE 'VATS%' AND EXTRACT(MONTH FROM \"acb_date\")='$mm' AND EXTRACT(YEAR FROM \"acb_date\")='$yy' ORDER BY \"auto_id\" ASC");
while($res_array_chk=pg_fetch_array($qry_array_chk)){
    $auto_id[] = $res_array_chk["auto_id"];
    $cid++;
}

if($cid == 0){  // INSERT

    $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('AP','$genid','$mlastdate','บันทึกภาษีขาย เดือน $month[$mm] ปี $s_yy','VATS')";
    if(!$res_in_sql=@pg_query($in_sql)){
        $text_error[] = "INSERT AccountBookHead 1<br />$in_sql<br />";
        $status++;
    }

    $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
    $res_auto_id=@pg_fetch_result($atid,0);
    if(empty($res_auto_id)){
        $text_error[] = "SELECT AccountBookHead_auto_id_seq<br />";
        $status++;
    }

    $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('AP','$genid2','$mlastdate','บัญชีภาษีซื้อ/ภาษีขาย เข้าภาษีมูลค่าเพิ่ม เดือน $month[$mm] ปี $s_yy','VATS')";
    if(!$res_in_sql=@pg_query($in_sql)){
        $text_error[] = "INSERT AccountBookHead 2<br />$in_sql<br />";
        $status++;
    }

    $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
    $res_auto_id2=@pg_fetch_result($atid,0);
    if(empty($res_auto_id2)){
        $text_error[] = "SELECT AccountBookHead_auto_id_seq 2<br />";
        $status++;
    }
    
}else{  // UPDATE
    if($cid == 1){
        $qry_head2=pg_query("SELECT \"acb_detail\" FROM \"account\".\"AccountBookHead\" WHERE \"auto_id\"='$auto_id[0]'");
        if($res_head2=pg_fetch_array($qry_head2)){
            $acb_detail = $res_head2["acb_detail"];
            if( strstr($acb_detail,"บันทึกภาษีขาย") ){
                
                $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$mlastdate',\"acb_detail\"='บันทึกภาษีขาย เดือน $month[$mm] ปี $s_yy',\"ref_id\"='VATS' WHERE \"auto_id\"='$auto_id[0]'";
                if(!$res_up_sql=@pg_query($up_sql)){
                    $text_error[] = "UPDATE AccountBookHead 5.1<br />$up_sql<br />";
                    $status++;
                }
                
                $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id[0]'");
                if(!$del_detail){
                    $text_error[] = "DELETE AccountBookDetail 6.1<br />$del_detail<br />";
                    $status++;
                }

                $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('AP','$genid2','$mlastdate','บัญชีภาษีซื้อ/ภาษีขาย เข้าภาษีมูลค่าเพิ่ม เดือน $month[$mm] ปี $s_yy','VATS')";
                if(!$res_in_sql=@pg_query($in_sql)){
                    $text_error[] = "INSERT AccountBookHead 4.1<br />$in_sql<br />";
                    $status++;
                }
                
                $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                $res_auto_id2=@pg_fetch_result($atid,0);
                if(empty($res_auto_id2)){
                    $text_error[] = "SELECT AccountBookHead_auto_id_seq 2<br />";
                    $status++;
                }
                $res_auto_id = $auto_id[0];
            }else{
                
                $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid2',\"acb_date\"='$mlastdate',\"acb_detail\"='บัญชีภาษีซื้อ/ภาษีขาย เข้าภาษีมูลค่าเพิ่ม เดือน $month[$mm] ปี $s_yy',\"ref_id\"='VATS' WHERE \"auto_id\"='$auto_id[0]'";
                if(!$res_up_sql=@pg_query($up_sql)){
                    $text_error[] = "UPDATE AccountBookHead 5.2<br />$up_sql<br />";
                    $status++;
                }

                $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id[0]'");
                if(!$del_detail){
                    $text_error[] = "DELETE AccountBookDetail 6.2<br />$del_detail<br />";
                    $status++;
                }
                
                $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") values ('AP','$genid','$mlastdate','บันทึกภาษีขาย เดือน $month[$mm] ปี $s_yy','VATS')";
                if(!$res_in_sql=@pg_query($in_sql)){
                    $text_error[] = "INSERT AccountBookHead 4.2<br />$in_sql<br />";
                    $status++;
                }

                $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
                $res_auto_id=@pg_fetch_result($atid,0);
                if(empty($res_auto_id)){
                    $text_error[] = "SELECT AccountBookHead_auto_id_seq<br />";
                    $status++;
                }
                $res_auto_id2 = $auto_id[0];
            }
        }
    }else{
        foreach($auto_id AS $value){
            $ckl++;
            if($ckl==1){
                $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid',\"acb_date\"='$mlastdate',\"acb_detail\"='บันทึกภาษีขาย เดือน $month[$mm] ปี $s_yy',\"ref_id\"='VATS' WHERE \"auto_id\"='$value'";
            }
            if($ckl==2){
                $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"type_acb\"='AP',\"acb_id\"='$genid2',\"acb_date\"='$mlastdate',\"acb_detail\"='บัญชีภาษีซื้อ/ภาษีขาย เข้าภาษีมูลค่าเพิ่ม เดือน $month[$mm] ปี $s_yy',\"ref_id\"='VATS' WHERE \"auto_id\"='$value'";
            }
            if(!$res_up_sql=@pg_query($up_sql)){
                $text_error[] = "UPDATE AccountBookHead $ckl<br />$up_sql<br />";
                $status++;
            }

            $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$value'");
            if(!$del_detail){
                $text_error[] = "DELETE AccountBookDetail<br />$del_detail<br />";
                $status++;
            }
        }
        $res_auto_id = $auto_id[0];
        $res_auto_id2 = $auto_id[1];
    }
}

$indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_avat','$vat','0')";
if(!$res_indt_sql=@pg_query($indt_sql)){
    $text_error[] = "INSERT AccountBookDetail1 $indt_sql<br />";
    $status++;
}

$indt_sql2="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_vats','0','$vat')";
if(!$res_indt_sql2=@pg_query($indt_sql2)){
    $text_error[] = "INSERT AccountBookDetail2 $indt_sql2<br />";
    $status++;
}

//INSERT 2

/*
$AmtDr = 0;
$AmtCr = 0;
$qry_in=@pg_query("SELECT A.*,B.* FROM account.\"AccountBookHead\" A inner join account.\"AccountBookDetail\" B on A.\"auto_id\"=B.\"autoid_abh\" 
WHERE A.\"type_acb\"='GJ' AND EXTRACT(MONTH FROM A.\"acb_date\")='$mm' AND EXTRACT(YEAR FROM A.\"acb_date\")='$yy' AND B.\"AcID\"='$acid_vatb' ");
while($res_in=@pg_fetch_array($qry_in)){
    $AmtDr += $res_in["AmtDr"];
    $AmtCr += $res_in["AmtCr"];
}
$bl = ($AmtDr+$AmtCr); $bl = round($bl,2);
*/

$query=pg_query("SELECT \"auto_id\",\"acb_date\",\"acb_detail\" FROM \"account\".\"AccountBookHead\" 
WHERE (EXTRACT(MONTH FROM \"acb_date\")='$mm') AND (EXTRACT(YEAR FROM \"acb_date\")='$yy') AND \"type_acb\"='GJ' AND \"ref_id\"='VATB' AND \"cancel\"='FALSE' ORDER BY \"acb_id\" ASC ");
while($resvc=pg_fetch_array($query)){
    $auto_id = $resvc['auto_id'];

    $sum_amtdr = 0;
    $sum_amtcr = 0;
    $amt_vat = 0;
    $query_detail=pg_query("SELECT \"AcID\",\"AmtDr\",\"AmtCr\" FROM \"account\".\"AccountBookDetail\" WHERE \"autoid_abh\"='$auto_id' ");
    while($resvc_detail=pg_fetch_array($query_detail)){
        $AcID = "";
        $AcID = $resvc_detail['AcID'];
        $AmtDr = round($resvc_detail['AmtDr'],2);
        $AmtCr = round($resvc_detail['AmtCr'],2);

        $sum_amtdr += $AmtDr;
        $sum_amtcr += $AmtCr;

        if($AcID == '1999'){
            if($AmtDr == 0 AND $AmtCr != 0){
                $type = 1;
                $amt_vat += $AmtCr;
            }else{
                $type = 2;
                $amt_vat += $AmtDr;
            }
        }
    }
    
    if($type == 1){
        $txt_show1 = ($sum_amtcr-$amt_vat)*-1;
        $txt_show2 = $amt_vat*-1;
        $txt_show3 = $sum_amtdr*-1;
    }elseif($type == 2){
        $txt_show1 = ($sum_amtdr-$amt_vat);
        $txt_show2 = $amt_vat;
        $txt_show3 = $sum_amtcr;
    }
    $bl+=$txt_show2;
}


if($vat > $bl){ //VATS > VATB
    $summmm = $vat-$bl;
    $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_vats','$vat','0')";
    if(!$res_indt_sql=@pg_query($indt_sql)){
        $text_error[] = "INSERT AccountBookDetail2.1 $indt_sql<br />";
        $status++;
    }
    $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_vatb','0','$bl')";
    if(!$res_indt_sql=@pg_query($indt_sql)){
        $text_error[] = "INSERT AccountBookDetail2.2 $indt_sql<br />";
        $status++;
    }
    $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_vat','0','$summmm')";
    if(!$res_indt_sql=@pg_query($indt_sql)){
        $text_error[] = "INSERT AccountBookDetail2.3 $indt_sql<br />";
        $status++;
    }
}else{ //VATS < VATB
    $summmm = $bl-$vat;
    $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_vats','$vat','0')";
    if(!$res_indt_sql=@pg_query($indt_sql)){
        $text_error[] = "INSERT AccountBookDetail3.1 $indt_sql<br />";
        $status++;
    }
    $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_vat','$summmm','0')";
    if(!$res_indt_sql=@pg_query($indt_sql)){
        $text_error[] = "INSERT AccountBookDetail3.2 $indt_sql<br />";
        $status++;
    }
    $indt_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_vatb','0','$bl')";
    if(!$res_indt_sql=@pg_query($indt_sql)){
        $text_error[] = "INSERT AccountBookDetail3.3 $indt_sql<br />";
        $status++;
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