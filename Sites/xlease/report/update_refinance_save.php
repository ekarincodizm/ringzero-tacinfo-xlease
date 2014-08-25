<?php
include("../config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$text_error = array();
$idno_old = $_POST['str1'];
$idno_new = $_POST['str2'];

if( empty($idno_old) || empty($idno_new) ){
    $text_error[] = "ไม่พบข้อมูลเลขที่สัญญา !<br />";
    $status++;
}else{

$count_ref = 0;
$qry_catid=@pg_query("SELECT COUNT(\"auto_id\") as countautoid FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REF#$idno_old' AND \"cancel\"='FALSE'");
if($res_catid=@pg_fetch_array($qry_catid)){
    $count_ref = $res_catid["countautoid"];
}

$qry=pg_query("SELECT * FROM \"Fp\" WHERE \"IDNO\" = '$idno_new'");
if($res_fp=pg_fetch_array($qry)){
    $new_P_STDATE = $res_fp["P_STDATE"];
    $new_P_CustByYear = $res_fp["P_CustByYear"]+543;
    $new_sub_P_CustByYear = substr($new_P_CustByYear,2,2);
}

$qry2=pg_query("SELECT * FROM \"Fp\" WHERE \"IDNO\" = '$idno_old'");
if($res_fp2=pg_fetch_array($qry2)){
    $old_P_ACCLOSE = $res_fp2["P_ACCLOSE"];
    $old_P_CustByYear = $res_fp2["P_CustByYear"]+543;
    $old_sub_P_CustByYear = substr($old_P_CustByYear,2,2);
    
    $s_P_BEGINX = $res_fp2["P_BEGINX"];
    $s_payment_nonvat = $res_fp2["P_MONTH"];
    $s_payment_all = $res_fp2["P_MONTH"]+$res_fp2["P_VAT"];
    $s_fp_ptotal = $res_fp2["P_TOTAL"];
    $money_all_no_vat = $s_payment_nonvat*$s_fp_ptotal;
}

//if($old_P_ACCLOSE == "FALSE" OR $old_P_ACCLOSE == "f"){
    $up_sql="UPDATE \"Fp\" SET \"P_ACCLOSE\"='TRUE', \"P_CLDATE\"='$new_P_STDATE' WHERE \"IDNO\"='$idno_old'";
    if(!$res_up_sql=@pg_query($up_sql)){
        $text_error[] = "UPDATE Fp ACCLOSE False !<br />$up_sql<br />";
        $status++;
    }
//}

$qry=pg_query("SELECT * FROM \"UNContact\" WHERE \"IDNO\" = '$idno_old'");
if($res_un_old=pg_fetch_array($qry)){
    $old_full_name = $res_un_old["full_name"];
    $old_C_REGIS = $res_un_old["C_REGIS"];
    $old_C_CARNAME = $res_un_old["C_CARNAME"];
}

$qry=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\" = '$idno_old' AND \"R_Receipt\" IS NOT NULL ORDER BY \"DueNo\" DESC");
if($res=pg_fetch_array($qry)){
    $DueNo = $res["DueNo"]; //
    $waitincome = round($res["waitincome"],2);
    $Remine = round($res["Remine"],2);
}else{
    $waitincome = round($money_all_no_vat-$s_P_BEGINX,2);
    $Remine = round($money_all_no_vat,2);
}

$qry7=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\" = '$idno_old' AND \"R_Receipt\" IS NULL ORDER BY \"DueNo\" ASC");
while($res7=pg_fetch_array($qry7)){
    $DueDate = $res7["DueDate"];
    $gg++;
    if($gg == 4){ break; }
    if($new_P_STDATE >= $DueDate){
        $waitincome = round($res7["waitincome"],2);
        //$Remine = round($res7["Remine"],2);
    }else{
        break;
    }
}

/* ======= เริ่ม เตรียมข้อมูล ======= */
if($count_ref > 0){

}else{
    $gen_no=@pg_query("select account.\"gen_no\"('$new_P_STDATE','AP')");
    $genid=@pg_fetch_result($gen_no,0);
    if(empty($genid)){
        $text_error[] = "Empty GenID !<br />";
        $status++;
    }
}

$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='INV'");
if($res_ac=@pg_fetch_array($qry_ac)){
    $acid_inv = $res_ac["AcID"];
}
if(empty($acid_inv)){
    $text_error[] = "Empty INV ID !<br />";
    $status++;
}

$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$old_P_CustByYear'");
if($res_ac=@pg_fetch_array($qry_ac)){
    $acid_543 = $res_ac["AcID"];
}
if(empty($acid_543)){
    $text_error[] = "Empty ACID 543 !<br />";
    $status++;
}

$old_sub_P_CustByYear_gp = "GP".$old_sub_P_CustByYear;
$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$old_sub_P_CustByYear_gp'");
if($res_ac=@pg_fetch_array($qry_ac)){
    $acid_gp = $res_ac["AcID"];
}
if(empty($acid_gp)){
    $text_error[] = "Empty ACID GP !<br />";
    $status++;
}
/* ======= จบ เตรียมข้อมูล ======= */

/* ======= เริ่ม insert ข้อมูล======= */
if($count_ref > 0){
    $qry_catid=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REF#$idno_old' AND \"cancel\"='FALSE'");
    if($res_catid=@pg_fetch_array($qry_catid)){
        $res_auto_id = $res_catid["auto_id"];
    }
    
    $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$res_auto_id'");
    if(!$del_detail){
        $text_error[] = "DELETE AccountBookDetail 1<br />$del_detail<br />";
        $status++;
    }
    
    $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"acb_date\"='$new_P_STDATE',\"acb_detail\"='บอกเลิกสัญญาเช่าซื้อ $old_full_name โอนบัญชี ไปสินค้ารถยึดคืน ทะเบียน $old_C_REGIS ยี่ห้อ $old_C_CARNAME',\"ref_id\"='REF#$idno_old' WHERE \"auto_id\"='$res_auto_id'";
    if(!$res_up_sql=@pg_query($up_sql)){
        $text_error[] = "UPDATE AccountBookHead 5.1<br />$up_sql<br />";
        $status++;
    }
    
}else{
    $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") 
    values  ('AP','$genid','$new_P_STDATE','บอกเลิกสัญญาเช่าซื้อ $old_full_name โอนบัญชี ไปสินค้ารถยึดคืน ทะเบียน $old_C_REGIS ยี่ห้อ $old_C_CARNAME','REF#$idno_old')";
    if(!$res_in_sql=@pg_query($in_sql)){
        $text_error[] = "Insert AccountBookHead Error ! $in_sql<br />";
        $status++;
    }

    $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
    $res_auto_id=@pg_fetch_result($atid,0);
    if(empty($res_auto_id)){
        $text_error[] = "SELECT AccountBookHead_auto_id_seq 1 Error !<br />";
        $status++;
    }
}

$sum_detail = $Remine-$waitincome;
$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_inv','$sum_detail','0')";
if(!$res_in_sql=@pg_query($in_sql)){
    $text_error[] = "INSERT AccountBookDetail 1 Error ! $in_sql<br />";
    $status++;
}

$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_gp','$waitincome','0')";
if(!$res_in_sql=@pg_query($in_sql)){
    $text_error[] = "INSERT AccountBookDetail 2 Error ! $in_sql<br />";
    $status++;
}

$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_543','0','$Remine')";
if(!$res_in_sql=@pg_query($in_sql)){
    $text_error[] = "INSERT AccountBookDetail 3 Error ! $in_sql<br />";
    $status++;
}
/* ======= จบ  insert ข้อมูล======= */

$count_rev = 0;
$qry_catid=@pg_query("SELECT COUNT(\"auto_id\") as countautoid FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REV#$idno_old' AND \"cancel\"='FALSE'");
if($res_catid=@pg_fetch_array($qry_catid)){
    $count_rev = $res_catid["countautoid"];
}

$qry=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\" = '$idno_old' AND \"V_Receipt\" IS NOT NULL AND \"R_Receipt\" IS NULL ORDER BY \"DueNo\" ASC");
$numrows = pg_num_rows($qry);
if($numrows > 0){
    while($res=pg_fetch_array($qry)){
        $VatValue = round($res["VatValue"],2);
        $sum_VatValue += $VatValue;
    }
    
    //Insert เฉพาะรายการที่มี vat มากกว่า 0 รายการ
    if($count_rev > 0){
        
    }else{
        $gen_no=@pg_query("select account.\"gen_no\"('$new_P_STDATE','AP')");
        $genid2=@pg_fetch_result($gen_no,0);
        if(empty($genid2)){
            $text_error[] = "Empty GenID 2 !<br />";
            $status++;
        }
    }

    $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='NVAT'");
    if($res_ac=@pg_fetch_array($qry_ac)){
        $acid_nvat = $res_ac["AcID"];
    }
    if(empty($acid_nvat)){
        $text_error[] = "Empty NVAT ID !<br />";
        $status++;
    }
    
    $qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='AVAT'");
    if($res_ac=@pg_fetch_array($qry_ac)){
        $acid_avat = $res_ac["AcID"];
    }
    if(empty($acid_avat)){
        $text_error[] = "Empty AVAT ID !<br />";
        $status++;
    }


if($count_rev > 0){
    $qry_catid=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REV#$idno_old' AND \"cancel\"='FALSE'");
    if($res_catid=@pg_fetch_array($qry_catid)){
        $res_auto_id2 = $res_catid["auto_id"];
    }
    
    $del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$res_auto_id2'");
    if(!$del_detail){
        $text_error[] = "DELETE AccountBookDetail 2<br />$del_detail<br />";
        $status++;
    }
    
    $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"acb_date\"='$new_P_STDATE',\"acb_detail\"='ภาษีมูลค่าเพิ่มที่ส่งแล้ว แต่ลูกค้า $old_full_name ไม่ชำระ $sum_VatValue งวดๆละ $VatValue',\"ref_id\"='REV#$idno_old' WHERE \"auto_id\"='$res_auto_id2'";
    if(!$res_up_sql=@pg_query($up_sql)){
        $text_error[] = "UPDATE AccountBookHead 5.2<br />$up_sql<br />";
        $status++;
    }
}else{
    $in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") 
    values  ('AP','$genid2','$new_P_STDATE','ภาษีมูลค่าเพิ่มที่ส่งแล้ว แต่ลูกค้า $old_full_name ไม่ชำระ $sum_VatValue งวดๆละ $VatValue','REV#$idno_old')";
    if(!$res_in_sql=@pg_query($in_sql)){
        $text_error[] = "Insert AccountBookHead 2 Error ! $in_sql<br />";
        $status++;
    }

    $atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
    $res_auto_id2=@pg_fetch_result($atid,0);
    if(empty($res_auto_id2)){
        $text_error[] = "SELECT AccountBookHead_auto_id_seq 2 Error !<br />";
        $status++;
    }
}
    $in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_nvat','$sum_VatValue','0')";
    if(!$res_in_sql=@pg_query($in_sql)){
        $text_error[] = "INSERT AccountBookDetail 2.1 Error ! $in_sql<br />";
        $status++;
    }

    $in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_avat','0','$sum_VatValue')";
    if(!$res_in_sql=@pg_query($in_sql)){
        $text_error[] = "INSERT AccountBookDetail 2.2 Error ! $in_sql<br />";
        $status++;
    }
    
}

if($count_rev > 0 AND $numrows == 0){
    $qry_catid=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REV#$idno_old' AND \"cancel\"='FALSE'");
    if($res_catid=@pg_fetch_array($qry_catid)){
        $res_auto_id2 = $res_catid["auto_id"];
    }
    $up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"cancel\"='TRUE' WHERE \"auto_id\"='$res_auto_id2'";
    if(!$res_up_sql=@pg_query($up_sql)){
        $text_error[] = "UPDATE Out Vat AccountBookHead<br />$up_sql<br />";
        $status++;
    }
}


}// จบตรวจสอบว่า ได้ส่ง IDNO ทั้ง 2 ตัวมาหรือไม่

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